@echo off
setlocal
cd /d "%~dp0"

if not exist "php\php.exe" (
    echo Bundled PHP was not found.
    echo Extract the complete ZIP again, then run this file.
    pause
    exit /b 1
)

echo Starting Marathi and Mathematics Learning Portal...
"php\php.exe" artisan optimize:clear
if errorlevel 1 (
    echo.
    echo Startup failed. Install the Microsoft Visual C++ 2015-2022 x64 runtime:
    echo https://aka.ms/vs/17/release/vc_redist.x64.exe
    pause
    exit /b 1
)

start "" powershell -NoProfile -WindowStyle Hidden -Command "Start-Sleep -Seconds 2; Start-Process 'http://127.0.0.1:8000/login'"
echo.
echo Portal: http://127.0.0.1:8000/login
echo Keep this window open. Press Ctrl+C to stop the portal.
echo.
"php\php.exe" artisan serve --host=127.0.0.1 --port=8000

pause
