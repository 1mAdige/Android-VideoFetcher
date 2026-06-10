# VideoFetcher Web Panel

VideoFetcher Android uygulaması için video yükleme ve dağıtım paneli.

Panel **isteğe bağlıdır** — uygulama doğrudan MP4 linki ile de kullanılabilir. Bkz. [ana README](../README.md).

---

## Ne işe yarar?

1. Panelden MP4 yüklenir → `videos/current.mp4` olarak saklanır
2. Uygulama `get_latest_video_info.php` ile güncellemeyi kontrol eder
3. Video değiştiyse indirilir ve oynatılır

Veritabanı **gerekmez**. Tüm veriler dosya sisteminde tutulur.

---

## Yerel deneme (Windows)

| Dosya | Açıklama |
|-------|----------|
| `start_server.bat` | PHP yerleşik sunucusunu port 8765’te başlatır |
| `firewall_allow_8765.bat` | Windows güvenlik duvarında port açar (yönetici) |

Bu betikler yalnızca yerel deneme içindir. Canlı ortamda hosting kullanın.

```bat
start_server.bat
```

Tarayıcı: `http://localhost:8765/`

İlk giriş bilgileri için [ana README](../README.md#güvenlik) bölümüne bakın.

---

## Hostinge yükleme

1. `web-panel/` dosyalarını sunucuya yükleyin
2. PHP 7.0+ ve `videos/`, `data/` yazma iznini doğrulayın
3. `config.local.example.php` → `config.local.php` kopyalayıp şifreyi değiştirin
4. Uygulama ayarlarına panel URL’nizi girin

---

## API

| Uç | Açıklama |
|----|----------|
| `get_latest_video_info.php` | Video zaman damgası |
| `videos/current.mp4` | Güncel video dosyası |
| `get_latest_video.php` | Alternatif stream |
| `ping.php` | Durum kontrolü |

---

## Güvenlik

- `config.local.php` ile varsayılan şifreyi değiştirin
- HTTPS kullanın
- [SECURITY.md](../SECURITY.md)
