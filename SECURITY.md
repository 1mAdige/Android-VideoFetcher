# Güvenlik

## Panel giriş bilgileri

`config.example.php` içindeki varsayılan kullanıcı adı ve şifre **yalnızca yerel deneme** içindir.

Canlı (production) ortama yüklemeden önce:

1. `config.local.example.php` dosyasını `config.local.php` olarak kopyalayın
2. Güçlü ve benzersiz bir şifre belirleyin
3. `config.local.php` dosyasının web üzerinden erişilemediğinden emin olun

`config.local.php` git deposuna eklenmez.

## HTTPS

Panel ve video dosyalarına mümkün olduğunca HTTPS üzerinden erişin.

## Dosya izinleri

- `videos/` — yalnızca panel üzerinden yükleme için yazılabilir olmalı
- `data/devices.json` — uygulama kaydı için yazılabilir; dizin listelemesi kapalı olmalı

## Sorun bildirimi

Güvenlik açığı bildirimi: [fth.elb@gmail.com](mailto:fth.elb@gmail.com)
