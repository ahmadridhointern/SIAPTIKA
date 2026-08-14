/**
 * laravel-bridge.js
 * ─────────────────────────────────────────────────────────────────────────────
 * Shared module untuk mengelola siklus hidup server Laravel lokal.
 *
 * Tanggung jawab:
 *  - Memverifikasi keberadaan runtime PHP & berkas aplikasi Laravel.
 *  - Mengelola startup child process PHP dengan konfigurasi mandiri.
 *  - Melakukan polling deteksi kesiapan HTTP server (/up).
 *  - Mencegah proses ganda (*multiple server processes*).
 *  - Menghentikan proses server secara bersih saat Electron ditutup.
 *  - Menyediakan pesan diagnostik informatif saat terjadi kendala.
 */

'use strict';

const { spawn, execSync } = require('child_process');
const http       = require('http');
const path       = require('path');
const fs         = require('fs');

// ─── Konstanta ───────────────────────────────────────────────────────────────
const LARAVEL_HOST    = '127.0.0.1';
const LARAVEL_PORT    = 8000;
const LARAVEL_URL     = `http://${LARAVEL_HOST}:${LARAVEL_PORT}`;
const HEALTH_ENDPOINT = '/up';
const POLL_INTERVAL   = 350;   // ms
const POLL_TIMEOUT    = 35000; // ms — batas maksimal menunggu startup (35 detik)

let laravelProcess = null;
let isStarting = false;

/**
 * Menentukan informasi binary PHP dan php.ini yang akan digunakan.
 *
 * @returns {{ bin: string|null, ini: string|null, dir: string|null, exists: boolean }}
 */
function resolvePhpRuntime() {
    const exeDir = path.dirname(process.execPath);

    // 1. Cek di samping .exe (Standard extraFiles packaging)
    const bundledPhpExe = path.join(exeDir, 'runtime', 'php', 'php.exe');
    const bundledPhpIni = path.join(exeDir, 'runtime', 'php', 'php.ini');
    if (fs.existsSync(bundledPhpExe)) {
        return {
            bin: bundledPhpExe,
            ini: fs.existsSync(bundledPhpIni) ? bundledPhpIni : null,
            dir: path.dirname(bundledPhpExe),
            exists: true,
        };
    }

    // 2. Cek di resourcesPath (extraResources fallback)
    if (process.resourcesPath) {
        const resPhpExe = path.join(process.resourcesPath, 'runtime', 'php', 'php.exe');
        const resPhpIni = path.join(process.resourcesPath, 'runtime', 'php', 'php.ini');
        if (fs.existsSync(resPhpExe)) {
            return {
                bin: resPhpExe,
                ini: fs.existsSync(resPhpIni) ? resPhpIni : null,
                dir: path.dirname(resPhpExe),
                exists: true,
            };
        }
    }

    // 3. Cek di folder development electron/runtime/php/
    const devPhpExe = path.join(__dirname, '..', 'runtime', 'php', 'php.exe');
    const devPhpIni = path.join(__dirname, '..', 'runtime', 'php', 'php.ini');
    if (fs.existsSync(devPhpExe)) {
        return {
            bin: devPhpExe,
            ini: fs.existsSync(devPhpIni) ? devPhpIni : null,
            dir: path.dirname(devPhpExe),
            exists: true,
        };
    }

    // 4. Development fallback — periksa apakah php ada di PATH
    return {
        bin: 'php',
        ini: null,
        dir: null,
        exists: false, // Menandakan bahwa bundled php tidak ditemukan
    };
}

/**
 * Menentukan path root direktori aplikasi Laravel.
 *
 * @returns {{ root: string, exists: boolean }}
 */
function resolveLaravelRoot() {
    const exeDir = path.dirname(process.execPath);

    // 1. Production: resources/laravel/ di samping .exe
    const prodRoot = path.join(exeDir, 'resources', 'laravel');
    if (fs.existsSync(path.join(prodRoot, 'artisan'))) {
        return { root: prodRoot, exists: true };
    }

    // 2. Production: di dalam process.resourcesPath/laravel
    if (process.resourcesPath) {
        const resRoot = path.join(process.resourcesPath, 'laravel');
        if (fs.existsSync(path.join(resRoot, 'artisan'))) {
            return { root: resRoot, exists: true };
        }
    }

    // 3. Development: root project (naik 2 level dari electron/)
    const devRoot = path.resolve(__dirname, '..', '..');
    if (fs.existsSync(path.join(devRoot, 'artisan'))) {
        return { root: devRoot, exists: true };
    }

    return { root: prodRoot, exists: false };
}

/**
 * Memastikan seluruh subdirektori storage dan bootstrap/cache tersedia.
 *
 * @param {string} laravelRoot
 */
function ensureStorageDirectories(laravelRoot) {
    const dirs = [
        path.join(laravelRoot, 'storage', 'app'),
        path.join(laravelRoot, 'storage', 'framework', 'cache', 'data'),
        path.join(laravelRoot, 'storage', 'framework', 'sessions'),
        path.join(laravelRoot, 'storage', 'framework', 'views'),
        path.join(laravelRoot, 'storage', 'logs'),
        path.join(laravelRoot, 'bootstrap', 'cache'),
    ];

    for (const dir of dirs) {
        try {
            if (!fs.existsSync(dir)) {
                fs.mkdirSync(dir, { recursive: true });
            }
        } catch (e) {
            console.error(`[Laravel Bridge] Gagal membuat direktori ${dir}:`, e.message);
        }
    }
}

/**
 * Melakukan permintaan HTTP GET ke endpoint health check Laravel.
 *
 * @returns {Promise<boolean>}
 */
function checkLaravelReady() {
    return new Promise((resolve) => {
        const req = http.get(`${LARAVEL_URL}${HEALTH_ENDPOINT}`, { timeout: 2000 }, (res) => {
            resolve(res.statusCode === 200);
        });
        req.on('error', () => resolve(false));
        req.on('timeout', () => {
            req.destroy();
            resolve(false);
        });
    });
}

/**
 * Menunggu hingga server Laravel merespons dengan HTTP 200.
 *
 * @returns {Promise<boolean>}
 */
async function waitForLaravel() {
    const startTime = Date.now();

    while (Date.now() - startTime < POLL_TIMEOUT) {
        const ready = await checkLaravelReady();
        if (ready) {
            return true;
        }
        await new Promise((resolve) => setTimeout(resolve, POLL_INTERVAL));
    }

    return false;
}

/**
 * Memulai server Laravel lokal menggunakan PHP runtime yang sesuai.
 *
 * @returns {Promise<{ success: boolean, error?: string, message?: string }>}
 */
async function startLaravel() {
    // 1. Cek apakah server sudah aktif (mencegah proses ganda)
    const alreadyActive = await checkLaravelReady();
    if (alreadyActive) {
        console.log('[Laravel Bridge] Server Laravel sudah aktif di', LARAVEL_URL);
        return { success: true };
    }

    if (isStarting) {
        console.log('[Laravel Bridge] Server sedang dalam proses inisialisasi...');
        const ready = await waitForLaravel();
        return { success: ready };
    }

    isStarting = true;

    try {
        const phpRuntime = resolvePhpRuntime();
        const laravelInfo = resolveLaravelRoot();

        // 2. Validasi keberadaan biner PHP
        if (!phpRuntime.exists && process.execPath.includes('SIAPTIKA')) {
            console.error('[Laravel Bridge] Biner PHP runtime tidak ditemukan.');
            return {
                success: false,
                error: 'PHP_MISSING',
                message: 'Biner PHP runtime tidak ditemukan pada paket instalasi aplikasi.\nPastikan instalasi lengkap dan berkas tidak terhapus oleh antivirus.',
            };
        }

        // 3. Validasi keberadaan direktori Laravel
        if (!laravelInfo.exists) {
            console.error('[Laravel Bridge] Direktori aplikasi Laravel tidak ditemukan.');
            return {
                success: false,
                error: 'LARAVEL_MISSING',
                message: 'Berkas inti aplikasi (artisan) tidak ditemukan pada direktori instalasi.',
            };
        }

        console.log('[Laravel Bridge] Binary PHP :', phpRuntime.bin);
        console.log('[Laravel Bridge] Config INI :', phpRuntime.ini || '(default)');
        console.log('[Laravel Bridge] Laravel Dir:', laravelInfo.root);

        // 4. Pastikan struktur direktori storage tersedia
        ensureStorageDirectories(laravelInfo.root);

        // 5. Siapkan argumen eksekusi
        const artisanPath = path.join(laravelInfo.root, 'artisan');
        const spawnArgs = [];

        if (phpRuntime.ini) {
            spawnArgs.push('-c', phpRuntime.ini);
        }

        // Sertakan CA cert mandiri untuk HTTPS / Supabase Storage S3
        if (phpRuntime.dir) {
            const caCertPath = path.join(phpRuntime.dir, 'cacert.pem');
            if (fs.existsSync(caCertPath)) {
                spawnArgs.push('-d', `curl.cainfo=${caCertPath}`);
                spawnArgs.push('-d', `openssl.cafile=${caCertPath}`);
            }
        }

        spawnArgs.push(artisanPath, 'serve', `--host=${LARAVEL_HOST}`, `--port=${LARAVEL_PORT}`);

        // 6. Siapkan variabel lingkungan (inject direktori PHP ke PATH & PHPRC)
        const childEnv = { ...process.env };
        if (phpRuntime.dir) {
            const caCertPath = path.join(phpRuntime.dir, 'cacert.pem');
            childEnv.PHPRC = phpRuntime.dir;
            childEnv.PATH  = `${phpRuntime.dir};${childEnv.PATH || ''}`;
            if (fs.existsSync(caCertPath)) {
                childEnv.SSL_CERT_FILE  = caCertPath;
                childEnv.CURL_CA_BUNDLE = caCertPath;
            }
        }

        // 7. Siapkan berkas log server
        const logFile = path.join(laravelInfo.root, 'storage', 'logs', 'laravel-server.log');
        let outStream = 'ignore';
        try {
            outStream = fs.openSync(logFile, 'a');
        } catch (e) {
            outStream = 'ignore';
        }

        laravelProcess = spawn(
            phpRuntime.bin,
            spawnArgs,
            {
                cwd:         laravelInfo.root,
                windowsHide: true,
                detached:    false,
                env:         childEnv,
                stdio:       ['ignore', outStream, outStream],
            }
        );

        laravelProcess.on('error', (err) => {
            console.error('[Laravel Bridge] Gagal memulai proses PHP:', err.message);
        });

        laravelProcess.on('exit', (code, signal) => {
            console.log(`[Laravel Bridge] Proses Laravel berhenti (code: ${code}, signal: ${signal})`);
            laravelProcess = null;
        });

        console.log('[Laravel Bridge] Mendeteksi kesiapan HTTP server Laravel...');
        const isReady = await waitForLaravel();

        if (!isReady) {
            console.error('[Laravel Bridge] Timeout: Server Laravel tidak merespons dalam batas waktu.');
            return {
                success: false,
                error: 'SERVER_TIMEOUT',
                message: 'Server aplikasi lokal tidak merespons dalam batas waktu yang ditentukan.\nSilakan periksa log di storage/logs/laravel-server.log.',
            };
        }

        return { success: true };

    } finally {
        isStarting = false;
    }
}

/**
 * Menghentikan proses server Laravel secara bersih saat aplikasi ditutup.
 */
function stopLaravel() {
    if (laravelProcess) {
        const pid = laravelProcess.pid;
        console.log(`[Laravel Bridge] Menghentikan server Laravel (PID: ${pid})...`);

        try {
            if (process.platform === 'win32' && pid) {
                // Hentikan proses dan seluruh child process di Windows
                execSync(`taskkill /pid ${pid} /T /F`, { stdio: 'ignore' });
            } else {
                laravelProcess.kill('SIGTERM');
            }
        } catch (e) {
            try {
                laravelProcess.kill('SIGKILL');
            } catch (_) {}
        }

        laravelProcess = null;
    }
}

// Hook penanganan terminasi proses Node
process.on('exit', () => stopLaravel());
process.on('SIGINT', () => { stopLaravel(); process.exit(0); });
process.on('SIGTERM', () => { stopLaravel(); process.exit(0); });

module.exports = { startLaravel, stopLaravel, LARAVEL_URL };
