# 🏦 Aplikasi Antrian Bank

Sistem antrian berbasis web menggunakan **PHP**, **WebSocket (Ratchet)**, dan **QR Code**. Dibangun untuk lingkungan perbankan yang membutuhkan manajemen antrian teller secara real-time.

---

## 🗂️ Struktur Proyek

```
aplikasiantrian/
├── assets/              # CSS, JS, font
├── data/                # Data JSON & counter (persisten)
│   ├── antrian.json
│   ├── nasabah.json
│   ├── teller.json
│   └── data.txt
├── images/              # Gambar & foto tim
├── phpqrcode/           # Library QR Code
├── qrcode/              # Script generate QR Code
├── teller/
│   └── teller.php       # Halaman teller (loket)
├── websockets/
│   ├── bin/
│   │   └── queue-server.php   # Entry point WebSocket server
│   ├── src/MyApp/
│   │   └── Queue.php          # Logic antrian WebSocket
│   └── vendor/                # Dependency Ratchet
├── api/
│   ├── index.php        # REST API router (semua endpoint)
│   └── .htaccess        # URL rewrite rules
├── index.html           # Halaman utama (ambil nomor antrian)
├── splash.html          # Halaman display antrian
├── teller1.html         # Shortcut loket 1
├── teller2.html         # Shortcut loket 2
├── teller3.html         # Shortcut loket 3
├── Dockerfile           # Image PHP + Apache (web)
├── Dockerfile.websocket # Image PHP CLI (WebSocket)
└── docker-compose.yml   # Orkestrasi semua service
```

---

## 🚀 Menjalankan dengan Docker

### Prasyarat
- [Docker Engine](https://docs.docker.com/engine/install/) sudah terinstall di server
- Port **80** dan **8080** tersedia

### Langkah-langkah

**1. Clone / upload project ke server**
```bash
git clone <repo-url> aplikasiantrian
cd aplikasiantrian
```

**2. Build dan jalankan semua service**
```bash
docker compose up -d --build
```

**3. Cek status container**
```bash
docker compose ps
```

**4. Lihat log real-time**
```bash
# Semua service
docker compose logs -f

# Hanya web
docker compose logs -f web

# Hanya websocket
docker compose logs -f websocket
```

**5. Hentikan semua service**
```bash
docker compose down
```

---

## 🌐 Akses Aplikasi

| URL | Keterangan |
|-----|------------|
| `http://YOUR_SERVER_IP` | Halaman utama (ambil nomor antrian) |
| `http://YOUR_SERVER_IP/teller/teller.php?teller=1` | Loket Teller 1 |
| `http://YOUR_SERVER_IP/teller/teller.php?teller=2` | Loket Teller 2 |
| `http://YOUR_SERVER_IP/teller/teller.php?teller=3` | Loket Teller 3 |
| `ws://YOUR_SERVER_IP:8080` | WebSocket server |
| `http://YOUR_SERVER_IP/api/` | REST API |

> Ganti `YOUR_SERVER_IP` dengan IP atau domain server Anda.

---

## 📡 REST API

Base URL: `http://YOUR_SERVER_IP/api`

### Antrian

| Method | Endpoint | Deskripsi |
|--------|----------|-----------|
| `GET` | `/antrian` | Status antrian (jumlah, sisa, nomor terakhir) |
| `POST` | `/antrian/enqueue` | Tambah nasabah baru ke antrian |
| `POST` | `/antrian/dequeue` | Teller proses (hapus) nomor antrian |
| `POST` | `/antrian/call` | Teller panggil nomor berikutnya |
| `POST` | `/antrian/recall` | Teller ulangi panggilan |
| `POST` | `/antrian/reset` | Reset counter antrian ke 1 |

### Nasabah

| Method | Endpoint | Deskripsi |
|--------|----------|-----------|
| `GET` | `/nasabah` | Daftar semua nasabah |
| `GET` | `/nasabah/{no}` | Data nasabah berdasarkan nomor antrian |
| `DELETE` | `/nasabah` | Hapus semua data nasabah & antrian |

### Teller

| Method | Endpoint | Deskripsi |
|--------|----------|-----------|
| `GET` | `/teller` | Status koneksi semua teller |

### Contoh Penggunaan (curl)

```bash
# Status antrian
curl http://YOUR_SERVER_IP/api/antrian

# Tambah nasabah baru
curl -X POST http://YOUR_SERVER_IP/api/antrian/enqueue \
  -H "Content-Type: application/json" \
  -d '{"nik":"1234567890","nama":"Budi Santoso"}'

# Proses nomor antrian (teller dequeue)
curl -X POST http://YOUR_SERVER_IP/api/antrian/dequeue \
  -H "Content-Type: application/json" \
  -d '{"no_antrian":1}'

# Panggil nomor berikutnya (teller 1)
curl -X POST http://YOUR_SERVER_IP/api/antrian/call \
  -H "Content-Type: application/json" \
  -d '{"teller":1}'

# Lihat semua nasabah
curl http://YOUR_SERVER_IP/api/nasabah

# Hapus semua data
curl -X DELETE http://YOUR_SERVER_IP/api/nasabah

# Reset counter
curl -X POST http://YOUR_SERVER_IP/api/antrian/reset
```

### Format Response

Semua response menggunakan format JSON:

```json
{
  "success": true,
  "message": "Pesan keterangan",
  "data": { ... }
}
```

---

## 🏗️ Arsitektur

```
Browser (Nasabah / Teller)
        │
        ├── HTTP :80  ──► Container: antrian_web  (PHP + Apache)
        │                    └── Baca/tulis data/ via volume
        │
        └── WS  :8080 ──► Container: antrian_ws   (PHP Ratchet)
                             └── Baca/tulis data/ via volume (sama)
```

Kedua container berbagi folder `data/` melalui **bind-mount volume** sehingga perubahan data antrian langsung terlihat oleh kedua service.

---

## 🛠️ Troubleshooting

**Port sudah digunakan:**
```bash
# Cek proses di port 80 / 8080
sudo lsof -i :80
sudo lsof -i :8080
```

**Permission error pada folder data:**
```bash
chmod -R 775 data/
```

**Rebuild ulang setelah perubahan kode:**
```bash
docker compose up -d --build
```

**Masuk ke dalam container:**
```bash
docker exec -it antrian_web bash
docker exec -it antrian_ws bash
```

---

## 📦 Teknologi

| Komponen | Teknologi |
|----------|-----------|
| Web server | PHP 7.4 + Apache |
| WebSocket | [Ratchet](http://socketo.me/) (PHP) |
| Frontend | jQuery, Bootstrap 3, HTML5 |
| QR Code | phpqrcode |
| REST API | PHP 7.4 (vanilla) |
| Container | Docker + Docker Compose |
