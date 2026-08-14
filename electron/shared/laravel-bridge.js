/**
 * laravel-bridge.js
 * ─────────────────────────────────────────────────────────────────────────────
 * Shared module untuk mengelola siklus hidup server Laravel lokal.
 *
 * Tanggung jawab:
 *  - Mencari binary PHP yang tersedia (bundled runtime atau PATH).
 *  - Menjalankan `php artisan serve` sebagai child process tersembunyi.
 *  - Melakukan polling ke endpoint health check `/up` hingga server siap.
 *  - Menghentikan proses Laravel secara bersih saat aplikasi Electron ditutup.
 *
 * Keamanan:
 *  - Server hanya di-bind ke 127.0.0.1 (loopback), tidak ke 0.0.0.0.
 *  - Tidak ada kredensial Supabase atau APP_KEY yang diteruskan ke renderer.
 */

'use strict';

const { spawn } = require('child_process');
const http       = require('http');
const path       = require('path');
const fs         = require('fs');

// ─── Konstanta ───────────────────────────────────────────────────────────────
const LARAVEL_HOST    = '127.0.0.1';
const LARAVEL_PORT    = 8000;
const LARAVEL_URL     = `http://${LARAVEL_HOST}:${LARAVEL_PORT}`;
const HEALTH_ENDPOINT = '/up';
const POLL_INTERVAL   = 500;   // ms
const POLL_TIMEOUT    = 30000; // ms — maksimal menunggu 30 detik
const STARTUP_RETRY   = 60;    // jumlah polling sebelum timeout

let laravelProcess = null;

/**
 * Menentukan path binary PHP yang akan digunakan.
 *
 * Urutan pencarian:
 *  1. Bundled PHP (production): <app-exe-dir>/runtime/php/php.exe
 *     (electron-builder menempatkan extraFiles di samping .exe)
 *  2. Development fallback: binary `php` dari PATH sistem
 */
function findPhpBinary() {
    // Dalam production, process.execPath adalah path ke .exe aplikasi
    // extraFiles ditempatkan di folder yang sama dengan .exe
    const exeDir = path.dirname(process.execPath);
    const bundledPhp = path.join(exeDir, 'runtime', 'php', 'php.exe');

    if (fs.existsSync(bundledPhp)) {
        console.log('[Laravel Bridge] Menggunakan bundled PHP:', bundledPhp);
        return bundledPhp;
    }

    // Development fallback — gunakan php dari PATH
    console.log('[Laravel Bridge] Menggunakan PHP dari PATH sistem');
    return 'php';
}

/**
 * Menentukan path root aplikasi Laravel.
 *
 * Dalam development: project root (tiga level di atas electron/shared/)
 * Dalam production: resources/laravel/ di samping .exe
 */
function findLaravelRoot() {
    const exeDir = path.dirname(process.execPath);
    const prodRoot = path.join(exeDir, 'resources', 'laravel');
    const artisanProd = path.join(prodRoot, 'artisan');

    if (fs.existsSync(artisanProd)) {
        return prodRoot;
    }

    // Development: <project>/electron/shared/laravel-bridge.js → naik 3 level
    const devRoot = path.join(__dirname, '..', '..', '..');
    const artisanDev = path.join(devRoot, 'artisan');

    if (fs.existsSync(artisanDev)) {
        return devRoot;
    }

    // Fallback terakhir
    return prodRoot;
}

/**
 * Melakukan satu permintaan HTTP GET ke endpoint health check Laravel.
 * Mengembalikan Promise<boolean>.
 */
function checkLaravelReady() {
    return new Promise((resolve) => {
        const req = http.get(`${LARAVEL_URL}${HEALTH_ENDPOINT}`, { timeout: 2000 }, (res) => {
            resolve(res.statusCode === 200);
        });
        req.on('error', () => resolve(false));
        req.on('timeout', () => { req.abort(); resolve(false); });
    });
}

/**
 * Menunggu hingga server Laravel merespons dengan HTTP 200.
 * Mengembalikan Promise<boolean> — true jika berhasil, false jika timeout.
 */
async function waitForLaravel() {
    const start = Date.now();

    while (Date.now() - start < POLL_TIMEOUT) {
        const ready = await checkLaravelReady();
        if (ready) return true;
        await new Promise((r) => setTimeout(r, POLL_INTERVAL));
    }

    return false;
}

/**
 * Memulai server Laravel lokal menggunakan php artisan serve.
 * Jika sudah berjalan, langsung mengembalikan true.
 *
 * @returns {Promise<boolean>} true jika server berhasil siap.
 */
async function startLaravel() {
    // Cek apakah sudah berjalan (misalnya, dijalankan manual saat development)
    const alreadyRunning = await checkLaravelReady();
    if (alreadyRunning) {
        console.log('[Laravel Bridge] Server sudah berjalan di', LARAVEL_URL);
        return true;
    }

    const phpBin     = findPhpBinary();
    const laravelDir = findLaravelRoot();

    console.log('[Laravel Bridge] Menjalankan server:', phpBin);
    console.log('[Laravel Bridge] Laravel root:', laravelDir);

    laravelProcess = spawn(
        phpBin,
        ['artisan', 'serve', `--host=${LARAVEL_HOST}`, `--port=${LARAVEL_PORT}`],
        {
            cwd:         laravelDir,
            windowsHide: true,
            detached:    false,
            stdio:       'ignore', // Sembunyikan output dari user
        }
    );

    laravelProcess.on('error', (err) => {
        console.error('[Laravel Bridge] Gagal menjalankan PHP:', err.message);
    });

    laravelProcess.on('exit', (code) => {
        console.log('[Laravel Bridge] Proses Laravel berhenti, kode:', code);
        laravelProcess = null;
    });

    console.log('[Laravel Bridge] Menunggu server siap...');
    return await waitForLaravel();
}

/**
 * Menghentikan proses server Laravel secara bersih.
 */
function stopLaravel() {
    if (laravelProcess) {
        console.log('[Laravel Bridge] Menghentikan server Laravel...');
        laravelProcess.kill('SIGTERM');
        laravelProcess = null;
    }
}

module.exports = { startLaravel, stopLaravel, LARAVEL_URL };
