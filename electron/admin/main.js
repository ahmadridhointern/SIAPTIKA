/**
 * electron/admin/main.js
 * ─────────────────────────────────────────────────────────────────────────────
 * Main process untuk aplikasi SIAPTIKA Administrator.
 *
 * Bertanggung jawab untuk:
 *  - Memulai server Laravel lokal secara otomatis.
 *  - Membuka jendela Administrator setelah server siap.
 *  - Mematikan server Laravel saat aplikasi ditutup.
 *
 * Entry URL: http://127.0.0.1:8000/login  (dialihkan ke /admin/dashboard setelah login)
 */

'use strict';

const { app, BrowserWindow, dialog } = require('electron');
const path = require('path');
const { startLaravel, stopLaravel, LARAVEL_URL } = require('../shared/laravel-bridge');

// ─── Pengaturan Keamanan Dasar Electron ──────────────────────────────────────
app.setName('SIAPTIKA Administrator');

// Paksa penggunaan resolusi warna standar
app.commandLine.appendSwitch('force-color-profile', 'srgb');

// ─── State ────────────────────────────────────────────────────────────────────
let mainWindow = null;

/**
 * Membuat jendela utama Administrator.
 */
function createWindow() {
    mainWindow = new BrowserWindow({
        width:              1400,
        height:             900,
        minWidth:           1024,
        minHeight:          700,
        title:              'SIAPTIKA Administrator',
        autoHideMenuBar:    true,
        show:               false,          // Sembunyikan hingga konten siap
        backgroundColor:    '#0f172a',      // Warna latar (gelap) agar tidak flash putih
        webPreferences: {
            nodeIntegration:    false,      // Keamanan: nonaktifkan akses Node di renderer
            contextIsolation:   true,       // Keamanan: isolasi konteks
            sandbox:            true,       // Keamanan: sandbox renderer process
        },
    });

    // Tampilkan jendela hanya setelah konten awal selesai dimuat
    mainWindow.once('ready-to-show', () => {
        mainWindow.show();
    });

    // Muat halaman login Administrator
    mainWindow.loadURL(`${LARAVEL_URL}/login`);

    mainWindow.on('closed', () => {
        mainWindow = null;
    });
}

/**
 * Menampilkan splash screen saat server sedang dimulai.
 */
function createSplashWindow() {
    const splash = new BrowserWindow({
        width:           460,
        height:          280,
        frame:           false,
        alwaysOnTop:     true,
        transparent:     false,
        resizable:       false,
        backgroundColor: '#0f172a',
    });

    // Muat konten splash sebagai HTML inline
    splash.loadURL(`data:text/html;charset=utf-8,${encodeURIComponent(`
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <style>
                * { margin: 0; padding: 0; box-sizing: border-box; }
                body {
                    background: #0f172a;
                    color: #e2e8f0;
                    font-family: 'Segoe UI', system-ui, sans-serif;
                    display: flex;
                    flex-direction: column;
                    align-items: center;
                    justify-content: center;
                    height: 100vh;
                    gap: 20px;
                    user-select: none;
                }
                .logo {
                    font-size: 32px;
                    font-weight: 800;
                    letter-spacing: 2px;
                    color: #38bdf8;
                }
                .sub {
                    font-size: 13px;
                    color: #94a3b8;
                    letter-spacing: 0.5px;
                }
                .loading {
                    display: flex;
                    gap: 8px;
                    margin-top: 10px;
                }
                .dot {
                    width: 8px; height: 8px;
                    background: #38bdf8;
                    border-radius: 50%;
                    animation: bounce 1.4s infinite ease-in-out;
                }
                .dot:nth-child(2) { animation-delay: 0.2s; }
                .dot:nth-child(3) { animation-delay: 0.4s; }
                @keyframes bounce {
                    0%, 80%, 100% { transform: scale(0); opacity: 0.3; }
                    40% { transform: scale(1); opacity: 1; }
                }
                .status { font-size: 12px; color: #64748b; margin-top: 8px; }
            </style>
        </head>
        <body>
            <div class="logo">SIAPTIKA</div>
            <div class="sub">Memulai server aplikasi...</div>
            <div class="loading">
                <div class="dot"></div>
                <div class="dot"></div>
                <div class="dot"></div>
            </div>
            <div class="status">Mohon tunggu beberapa saat</div>
        </body>
        </html>
    `)}`);

    return splash;
}

// ─── Bootstrap Aplikasi ───────────────────────────────────────────────────────
app.whenReady().then(async () => {
    const splash = createSplashWindow();

    const ready = await startLaravel();

    splash.close();

    if (!ready) {
        dialog.showErrorBox(
            'SIAPTIKA — Gagal Memulai',
            'Server aplikasi tidak dapat dimulai dalam batas waktu yang ditentukan.\n\n' +
            'Pastikan PHP tersedia dan file aplikasi tidak rusak.\n' +
            'Silakan hubungi administrator sistem.'
        );
        app.quit();
        return;
    }

    createWindow();
});

// ─── Event Lifecycle ──────────────────────────────────────────────────────────
app.on('window-all-closed', () => {
    stopLaravel();
    app.quit();
});

app.on('before-quit', () => {
    stopLaravel();
});
