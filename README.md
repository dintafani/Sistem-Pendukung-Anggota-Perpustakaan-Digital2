# Sistem Pendukung Anggota Perpustakaan Digital

Aplikasi perpustakaan digital berbasis web yang dikembangkan untuk mendukung pengelolaan keanggotaan dan pelayanan perpustakaan di **SMA Negeri 03 Kotabumi**. Sistem ini dibangun menggunakan framework Laravel dengan penerapan Quick Response Code (QR Code) untuk membantu proses identifikasi anggota dan transaksi perpustakaan.

## Deskripsi Proyek

Sistem ini dikembangkan untuk membantu proses pengelolaan perpustakaan yang sebelumnya dilakukan secara manual menjadi lebih terkomputerisasi dan terintegrasi secara online.

Aplikasi menyediakan layanan bagi siswa sebagai anggota perpustakaan dan petugas sebagai pengelola sistem. Melalui aplikasi ini, proses pendaftaran anggota, pengelolaan koleksi buku, peminjaman, pengembalian, serta pemantauan aktivitas perpustakaan dapat dilakukan secara digital.

## Fitur Utama

### 1. Fitur Siswa
- Registrasi akun anggota perpustakaan.
- Verifikasi dan persetujuan keanggotaan oleh petugas.
- Dashboard anggota.
- Melihat, mencari, dan membaca informasi koleksi buku.
- QR Code sebagai identitas anggota perpustakaan.
- Pengajuan peminjaman buku secara online.
- Riwayat peminjaman dan pengembalian.
- Notifikasi aktivitas perpustakaan.
### 2. Fitur Petugas
- Dashboard pengelolaan perpustakaan.
- Verifikasi dan pengelolaan anggota.
- Pengelolaan data buku dan koleksi.
- Pengelolaan transaksi peminjaman dan pengembalian.
- Pemindaian QR Code anggota.
- Pengelolaan notifikasi.
- Pengelolaan poster informasi.
- Pengelolaan inventaris perpustakaan.
- Pengarsipan data dan aktivitas transaksi.
- Pengaturan tahun ajaran dan semester.
- Pencatatan kunjungan perpustakaan.

### 3. Fitur Pendukung
- Sistem poin sebagai pendukung aktivitas literasi.
- Pengelolaan data secara terpusat melalui basis data.
- Pemberian rating dan ulasan buku.
- Jurnal literasi.
- Sistem poin dan leaderboard.

## Teknologi yang Digunakan

| Teknologi | Kegunaan |
|---|---|
| PHP 8.1 | Bahasa pemrograman backend |
| Laravel 10 | Framework aplikasi web |
| MySQL | Sistem manajemen basis data |
| HTML, CSS, JavaScript | Pengembangan antarmuka |
| Bootstrap / Tailwind CSS | Pendukung tampilan antarmuka |
| Node.js | Menjalankan layanan WhatsApp Bridge |
| Git & GitHub | Pengelolaan versi kode program |

## Metode Pengembangan

Pengembangan aplikasi menggunakan metode **Waterfall**, yang terdiri dari lima tahapan:

1. Perencanaan (*Planning*)
2. Analisis (*Analysis*)
3. Perancangan (*Design*)
4. Implementasi (*Implementation*)


## Persyaratan Sistem

Sebelum menjalankan aplikasi, pastikan perangkat telah memiliki:

- PHP 8.1 atau versi yang sesuai.
- Composer.
- MySQL.
- Node.js dan NPM.
- Web browser.
- Git (opsional, untuk mengambil kode dari repository).

## Instalasi dan Menjalankan Aplikasi

### 1. Clone Repository

```bash
git clone https://github.com/dintafani/Sistem-Pendukung-Anggota-Perpustakaan-Digital2.git
```

Masuk ke direktori proyek:

```bash
cd Sistem-Pendukung-Anggota-Perpustakaan-Digital2
```

### 2. Instal Dependensi Laravel

```bash
composer install
```

### 3. Konfigurasi Environment

Salin file `.env.example` menjadi `.env`:

```bash
cp .env.example .env
```

Pada Windows PowerShell:

```powershell
Copy-Item .env.example .env
```

Sesuaikan konfigurasi basis data pada file `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nama_database
DB_USERNAME=root
DB_PASSWORD=
```

Buat application key:

```bash
php artisan key:generate
```

### 4. Migrasi Basis Data

Buat database MySQL terlebih dahulu, kemudian jalankan:

```bash
php artisan migrate
```

Perintah ini membuat struktur tabel berdasarkan file migrasi yang tersedia.

### 5. Instal Dependensi Frontend

```bash
npm install
```

Kemudian jalankan proses build:

```bash
npm run build
```

### 6. Jalankan Aplikasi

```bash
php artisan serve
```

Buka aplikasi melalui browser:

```text
http://127.0.0.1:8000
```

## WhatsApp Bridge

Aplikasi menyediakan layanan integrasi WhatsApp menggunakan Node.js dan WhatsApp Bridge.

Untuk menjalankan layanan tersebut, masuk ke direktori:

```bash
cd scripts/wa_bridge
```

Instal dependensi:

```bash
npm install
```

Jalankan layanan:

```bash
npm start
```

Penggunaan fitur WhatsApp memerlukan konfigurasi layanan dan proses penautan akun WhatsApp.

## Struktur Proyek

```text
├── app/
│   ├── Console/
│   ├── Http/
│   │   ├── Controllers/
│   │   └── Middleware/
│   ├── Models/
│   └── Services/
├── bootstrap/
├── config/
├── database/
│   ├── factories/
│   └── migrations/
├── public/
│   ├── css/
│   └── js/
├── resources/
│   └── views/
├── routes/
├── scripts/
│   └── wa_bridge/
├── storage/
├── tests/
├── .env.example
├── composer.json
├── package.json
└── README.md
```

## Informasi Repository

**Nama Proyek:** Sistem Pendukung Anggota Perpustakaan Digital

**Lokasi Penelitian:** SMA Negeri 03 Kotabumi

**Repository:** [GitHub - Sistem Pendukung Anggota Perpustakaan Digital2](https://github.com/dintafani/Sistem-Pendukung-Anggota-Perpustakaan-Digital2)

## Catatan

Repository ini berisi source code aplikasi dan file migrasi basis data. Data asli anggota, nomor telepon, riwayat transaksi, serta informasi internal perpustakaan tidak disertakan.

Konfigurasi lingkungan aplikasi harus disesuaikan dengan perangkat atau server yang digunakan sebelum aplikasi dijalankan.
