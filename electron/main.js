const { app, BrowserWindow } = require("electron");

function createWindow() {

    const win = new BrowserWindow({
        width: 1400,
        height: 900,
        autoHideMenuBar: true
    });

    win.loadURL("http://127.0.0.1:8000");
}

app.whenReady().then(createWindow);