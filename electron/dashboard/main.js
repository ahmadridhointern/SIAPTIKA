/**
 * electron/dashboard/main.js
 * ─────────────────────────────────────────────────────────────────────────────
 * Main process untuk aplikasi SIAPTIKA Dashboard Pegawai.
 *
 * Tanggung jawab:
 *  - Mengelola Single Instance Lock (mencegah duplikasi instance).
 *  - Memulai server Laravel lokal secara otomatis dengan runtime PHP mandiri.
 *  - Membuka jendela Dashboard Pegawai (Read-Only) setelah server merespons HTTP 200.
 *  - Menghentikan server Laravel saat seluruh jendela ditutup.
 *
 * Entry URL: http://127.0.0.1:8000/pegawai/dashboard
 */

'use strict';

const { app, BrowserWindow, dialog, shell } = require('electron');
const path = require('path');
const { startLaravel, stopLaravel, getLaravelUrl } = require('../shared/laravel-bridge');

// ─── Single Instance Lock ─────────────────────────────────────────────────────
const gotTheLock = app.requestSingleInstanceLock();

if (!gotTheLock) {
    console.log('[SIAPTIKA Dashboard] Instance lain sudah berjalan. Menutup instance baru.');
    app.quit();
} else {
    app.on('second-instance', () => {
        // Fokuskan jendela yang sudah ada jika pengguna membuka instance kedua
        if (mainWindow) {
            if (mainWindow.isMinimized()) mainWindow.restore();
            mainWindow.focus();
        }
    });

    // ─── Pengaturan Keamanan Dasar Electron ───────────────────────────────────
    app.setName('SIAPTIKA Dashboard');
    app.commandLine.appendSwitch('force-color-profile', 'srgb');

    // ─── State ────────────────────────────────────────────────────────────────
    let mainWindow = null;

    /**
     * Membuat jendela utama Dashboard Pegawai.
     */
    function createWindow() {
        mainWindow = new BrowserWindow({
            width:              1400,
            height:             900,
            minWidth:           1024,
            minHeight:          700,
            title:              'SIAPTIKA Dashboard',
            autoHideMenuBar:    true,
            show:               false,          // Sembunyikan hingga konten siap
            backgroundColor:    '#0f172a',      // Warna latar gelap agar tidak flash putih
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

        // Muat halaman dashboard publik Pegawai
        mainWindow.loadURL(`${getLaravelUrl()}/pegawai/dashboard`);

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
                <div class="sub">Memulai SIAPTIKA Dashboard...</div>
                <div class="loading">
                    <div class="dot"></div>
                    <div class="dot"></div>
                    <div class="dot"></div>
                </div>
                <div class="status">Menyiapkan portal informasi & arsip</div>
            </body>
            </html>
        `)}`);

        return splash;
    }

    // ─── Bootstrap Aplikasi ───────────────────────────────────────────────────
    app.whenReady().then(async () => {
        const splash = createSplashWindow();

        const result = await startLaravel();

        try {
            if (splash && !splash.isDestroyed()) {
                splash.close();
            }
        } catch (_) {}

        if (!result.success) {
            // Susun pesan error yang informatif, sertakan cuplikan log jika tersedia
            let detail = result.message || 'Server aplikasi tidak dapat dimulai.';

            if (result.logSnippet && result.logSnippet.length > 0) {
                detail += '\n\n─── Cuplikan Log (50 baris terakhir) ───\n' + result.logSnippet;
            }

            detail += '\n\nSilakan hubungi administrator sistem.';

            const buttons = result.logPath
                ? ['Tutup Aplikasi', 'Buka Folder Log']
                : ['Tutup Aplikasi'];

            const { response } = await dialog.showMessageBox({
                type:    'error',
                title:   'SIAPTIKA Dashboard — Gagal Memulai',
                message: 'Server aplikasi lokal tidak dapat dimulai.',
                detail,
                buttons,
                defaultId: 0,
                cancelId:  0,
            });

            if (result.logPath && response === 1) {
                shell.openPath(result.logPath);
            }

            app.quit();
            return;
        }

        createWindow();
    });

    // ─── Event Lifecycle ──────────────────────────────────────────────────────
    app.on('window-all-closed', () => {
        stopLaravel();
        app.quit();
    });

    app.on('before-quit', () => {
        stopLaravel();
    });
}
