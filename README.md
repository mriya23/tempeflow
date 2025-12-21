<p align="center">
  <img src="public/images/tempe_hero.png" width="400" alt="TempeFlow Logo">
</p>

<h1 align="center">🌿 TempeFlow</h1>

<p align="center">
  <strong>Platform E-Commerce Modern untuk Tempe Jaya Mandiri</strong>
</p>

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-12.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel">
  <img src="https://img.shields.io/badge/TailwindCSS-3.x-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white" alt="Tailwind CSS">
  <img src="https://img.shields.io/badge/Vite-6.x-646CFF?style=for-the-badge&logo=vite&logoColor=white" alt="Vite">
  <img src="https://img.shields.io/badge/Midtrans-Payment-00D4AA?style=for-the-badge&logo=stripe&logoColor=white" alt="Midtrans">
</p>

<p align="center">
  <img src="https://img.shields.io/badge/PHP-8.2+-777BB4?style=flat-square&logo=php&logoColor=white" alt="PHP">
  <img src="https://img.shields.io/badge/MySQL-Database-4479A1?style=flat-square&logo=mysql&logoColor=white" alt="MySQL">
  <img src="https://img.shields.io/badge/License-MIT-green?style=flat-square" alt="License">
</p>

---

## 📋 Tentang Proyek

**TempeFlow** adalah platform e-commerce modern yang dikembangkan untuk **Tempe Jaya Mandiri**, sebuah UMKM yang memproduksi tempe berkualitas tinggi. Platform ini dirancang untuk memudahkan pelanggan dalam memesan berbagai varian tempe secara online dengan pengalaman belanja yang seamless.

### 🎯 Tujuan

-   Memperluas jangkauan pasar produk tempe berkualitas
-   Memberikan kemudahan pemesanan online bagi pelanggan
-   Menyediakan dashboard admin untuk manajemen produk dan pesanan
-   Mengintegrasikan sistem pembayaran online yang aman

---

## ✨ Fitur Utama

### 👥 Untuk Pelanggan

| Fitur                    | Deskripsi                                           |
| ------------------------ | --------------------------------------------------- |
| 🏪 **Landing Page**      | Halaman utama yang menarik dengan informasi produk  |
| 📦 **Katalog Produk**    | Tampilan produk dengan kategori dan filter          |
| 🛒 **Keranjang Belanja** | Kelola item sebelum checkout                        |
| 💳 **Pembayaran Online** | Integrasi Midtrans untuk berbagai metode pembayaran |
| 📍 **Tracking Pesanan**  | Lacak status pesanan secara real-time               |
| 📜 **Riwayat Pesanan**   | Lihat semua pesanan yang pernah dilakukan           |

### 🔧 Untuk Admin

| Fitur                      | Deskripsi                               |
| -------------------------- | --------------------------------------- |
| 📊 **Dashboard Analytics** | Statistik penjualan dan performa bisnis |
| 📦 **Manajemen Produk**    | CRUD produk dengan upload gambar        |
| 📋 **Manajemen Pesanan**   | Kelola dan update status pesanan        |
| 👤 **Manajemen User**      | Kelola akun pelanggan                   |
| 📄 **Laporan PDF**         | Generate laporan penjualan              |
| 🧾 **Invoice**             | Cetak invoice untuk setiap transaksi    |

---

## 🏗️ Tech Stack

```
┌─────────────────────────────────────────────────────────┐
│                     FRONTEND                             │
├─────────────────────────────────────────────────────────┤
│  🎨 Blade Templates   │  🌊 TailwindCSS  │  ⚡ Vite     │
└─────────────────────────────────────────────────────────┘
                            │
                            ▼
┌─────────────────────────────────────────────────────────┐
│                     BACKEND                              │
├─────────────────────────────────────────────────────────┤
│  🔥 Laravel 12        │  🔐 Laravel Breeze (Auth)       │
│  📄 DomPDF            │  💰 Midtrans Payment Gateway    │
└─────────────────────────────────────────────────────────┘
                            │
                            ▼
┌─────────────────────────────────────────────────────────┐
│                     DATABASE                             │
├─────────────────────────────────────────────────────────┤
│  🗄️ MySQL / SQLite                                      │
└─────────────────────────────────────────────────────────┘
```

---

## 📁 Struktur Proyek

```
tempeflow/
├── 📂 app/
│   ├── Http/Controllers/
│   │   ├── Admin/           # Controller untuk admin dashboard
│   │   ├── Auth/            # Controller autentikasi
│   │   ├── StorefrontController.php
│   │   └── MidtransWebhookController.php
│   └── Models/              # Eloquent Models
├── 📂 database/
│   ├── migrations/          # Database migrations
│   └── seeders/             # Data seeders
├── 📂 public/
│   └── images/              # Assets gambar produk
├── 📂 resources/
│   └── views/
│       ├── admin/           # Views admin panel
│       ├── storefront/      # Views customer-facing
│       └── layouts/         # Layout templates
├── 📂 routes/
│   └── web.php              # Web routes
└── 📂 config/               # Konfigurasi aplikasi
```

---

## 🚀 Instalasi & Setup

### Prasyarat

-   PHP >= 8.2
-   Composer
-   Node.js >= 18
-   MySQL / SQLite

### Langkah Instalasi

```bash
# 1. Clone repository
git clone https://github.com/mriya23/tempeflow.git
cd tempeflow

# 2. Install dependencies PHP
composer install

# 3. Install dependencies Node.js
npm install

# 4. Salin file environment
cp .env.example .env

# 5. Generate application key
php artisan key:generate

# 6. Konfigurasi database di .env
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=tempeflow
# DB_USERNAME=root
# DB_PASSWORD=

# 7. Jalankan migrasi database
php artisan migrate

# 8. (Opsional) Jalankan seeder
php artisan db:seed

# 9. Build assets
npm run build

# 10. Jalankan development server
php artisan serve
```

### 🔧 Konfigurasi Midtrans

Tambahkan konfigurasi berikut di file `.env`:

```env
MIDTRANS_SERVER_KEY=your_server_key
MIDTRANS_CLIENT_KEY=your_client_key
MIDTRANS_IS_PRODUCTION=false
```

---

## 📸 Screenshot

<details>
<summary>🖥️ Lihat Screenshot</summary>

### Landing Page

> Halaman utama dengan hero section dan katalog produk

### Katalog Produk

> Tampilan grid produk dengan filter kategori

### Keranjang & Checkout

> Proses pembelian yang seamless

### Admin Dashboard

> Panel administrasi dengan analytics

</details>

---

## 🛣️ Roadmap

-   [x] Landing page dengan katalog produk
-   [x] Sistem keranjang belanja
-   [x] Integrasi pembayaran Midtrans
-   [x] Tracking pesanan
-   [x] Admin dashboard
-   [x] Generate laporan PDF
-   [ ] Notifikasi WhatsApp
-   [ ] Mobile app (Flutter)
-   [ ] Multi-vendor support

---

## 🤝 Kontribusi

Kontribusi sangat diterima! Silakan:

1. Fork repository ini
2. Buat branch fitur (`git checkout -b feature/AmazingFeature`)
3. Commit perubahan (`git commit -m 'Add some AmazingFeature'`)
4. Push ke branch (`git push origin feature/AmazingFeature`)
5. Buat Pull Request

---

## 📄 Lisensi

Proyek ini dilisensikan di bawah [MIT License](LICENSE).

---

## 👨‍💻 Tim Pengembang

<table>
  <tr>
    <td align="center">
      <strong>Tempe Jaya Mandiri Team</strong><br>
      <sub>Development</sub>
    </td>
  </tr>
</table>

---

<p align="center">
  <strong>Dibuat dengan ❤️ untuk UMKM Indonesia</strong>
</p>

<p align="center">
  <a href="#-tempeflow">⬆️ Kembali ke Atas</a>
</p>
