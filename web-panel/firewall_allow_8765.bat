@echo off
echo Windows Firewall: port 8765 aciliyor (Video Panel)...
netsh advfirewall firewall add rule name="VideoFetcher Panel 8765" dir=in action=allow protocol=TCP localport=8765
if errorlevel 1 (
    echo HATA: Kural eklenemedi. Bu dosyayi sag tik ^> Yonetici olarak calistir.
) else (
    echo Tamam: TCP 8765 giris trafiine izin verildi.
)
pause
