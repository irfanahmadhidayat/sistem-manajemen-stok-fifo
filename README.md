# 📦 Aplikasi Manajemen Stok (FIFO)

Aplikasi **Manajemen Stok Barang berbasis Laravel 11** dengan penerapan **algoritma FIFO (First In First Out)**.  
Tujuan aplikasi ini adalah membantu mengelola stok barang agar tidak terjadi kehabisan stok dan menghindari penumpukan barang kadaluarsa.


![alt text](https://github.com/irfanahmadhidayat/sistem-manajemen-stok-fifo/blob/main/web-image/dashboard.jpeg?raw=true)

---

## 🚀 Fitur Utama
- 🔑 **Login Multi User**  
  - **Admin** → akses penuh (data master, transaksi, laporan).  
  - **Karyawan** → hanya akses transaksi & laporan.  

- 📊 **Dashboard** → ringkasan stok & transaksi.  
- 🛠️ **CRUD Master Barang** → tambah, ubah, hapus data barang & satuan.
- 🔔 **Notifikasi** → notifikasi jika stok barang menipis, habis, mendekati kadaluarsa dan kadaluarsa
- 📥 **Transaksi Barang Masuk** → catat barang baru yang masuk.  
- 📤 **Transaksi Barang Keluar** → catat barang keluar dengan sistem FIFO.  
- 🧾 **Cetak Laporan**  
  - Laporan stok barang  
  - Laporan penjualan  
  - Laporan barang masuk  
  - Laporan barang keluar  

---

## 🛠️ Tech Stack
- **Framework**: Laravel 11
- **Database**: MySQL
- **Authentication**: Laravel Breeze  
- **PDF Export**: barryvdh/laravel-dompdf  
- **Frontend**: Blade, Bootstrap/JS  

---

## ⚙️ Requirements
- PHP ^8.2
- Composer
- Node.js & NPM
- MySQL

---

## 📌 Instalasi & Menjalankan
1. Clone repo:
   ```sh
   git clone https://github.com/[YourGitHub]/[YourRepository].git
   ```
2. Arahkan folder projek:
   ```sh
   cd [YourRepository]
   ```
3. Install dependencies:
   ```sh
   composer install
   ```
4. Copy .env:
   ```sh
   cp .env.example .env
   ```
5. Konfigurasu file `.env`.
6. Generate application key:
   ```sh
   php artisan key:generate
   ```
7. Jalankan migrations database:
   ```sh
   php artisan migrate --seed
   ```
8. Run projek:
   ```sh
   php artisan serve
   ```

---

## 🧑‍💻 Akun Default (Seeder)

Admin
```sh
Email: admin@gmail.com
Password: Admin#1234
```
Karyawan
```sh
Email: karyawan@gmail.com
Password: Karyawan#1234
```
---

![alt text](https://github.com/irfanahmadhidayat/sistem-manajemen-stok-fifo/blob/main/web-image/barang-masuk.jpeg?raw=true)

![alt text](https://github.com/irfanahmadhidayat/sistem-manajemen-stok-fifo/blob/main/web-image/barang-keluar.jpeg?raw=true)

![alt text](https://github.com/irfanahmadhidayat/sistem-manajemen-stok-fifo/blob/main/web-image/fifo.jpeg?raw=true)

![alt text](https://github.com/irfanahmadhidayat/sistem-manajemen-stok-fifo/blob/main/web-image/notifikasi.jpeg?raw=true)
