@echo off
setlocal

cd /d "%~dp0"

set PHP_HOME=C:\php
set BIND_HOST=0.0.0.0
set PORT=8765

echo ========================================
echo VideoFetcher Panel - PHP Sunucusu
echo ========================================
echo.

if exist "%PHP_HOME%\php.exe" (
    set PHP_EXE=%PHP_HOME%\php.exe
    set PHP_INI=%PHP_HOME%\php.ini
) else (
    where php >nul 2>&1
    if errorlevel 1 (
        echo HATA: PHP bulunamadi.
        echo C:\php kurulu degilse PATH'e php ekleyin veya PHP_HOME degiskenini duzenleyin.
        pause
        exit /b 1
    )
    for /f "delims=" %%P in ('where php') do set PHP_EXE=%%P
    set PHP_INI=
)

"%PHP_EXE%" -r "echo 'PHP: ' . PHP_VERSION . PHP_EOL;"

if not exist "videos" mkdir "videos"

echo.
echo Panel adresleri:
echo   Yerel:  http://localhost:%PORT%/
echo   APK:    http://[BU_PC_IP]:%PORT%/
echo.
echo API:
echo   http://localhost:%PORT%/get_latest_video_info.php
echo   http://localhost:%PORT%/videos/current.mp4
echo   http://localhost:%PORT%/ping.php
echo.
echo PC IP icin: ipconfig
echo Cihaz goremiyorsa: firewall_allow_%PORT%.bat ^(yonetici olarak^) calistir.
echo Ilk giris: README ve SECURITY.md dosyalarina bakin.
echo Canli ortamda config.local.php ile sifreyi degistirin.
echo.
echo Sunucu baslatiliyor... Durdurmak icin Ctrl+C
echo.

set DOCROOT=%~dp0
if "%DOCROOT:~-1%"=="\" set DOCROOT=%DOCROOT:~0,-1%

if defined PHP_INI (
    "%PHP_EXE%" -c "%PHP_INI%" -S %BIND_HOST%:%PORT% -t "%DOCROOT%"
) else (
    "%PHP_EXE%" -S %BIND_HOST%:%PORT% -t "%DOCROOT%"
)

pause
