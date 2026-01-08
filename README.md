# 🛒 Arifa Jaya POS

<p align="center">
  <img src="public/images/logo.png" alt="Arifa Jaya Logo" width="200">
</p>

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-12.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel">
  <img src="https://img.shields.io/badge/PHP-8.2+-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP">
  <img src="https://img.shields.io/badge/TailwindCSS-4.0-06B6D4?style=for-the-badge&logo=tailwindcss&logoColor=white" alt="TailwindCSS">
  <img src="https://img.shields.io/badge/Bootstrap-5.3-7952B3?style=for-the-badge&logo=bootstrap&logoColor=white" alt="Bootstrap">
  <img src="https://img.shields.io/badge/MySQL-Database-4479A1?style=for-the-badge&logo=mysql&logoColor=white" alt="MySQL">
</p>

## 📋 Deskripsi

**Arifa Jaya POS** adalah sistem Point of Sale (POS) berbasis web yang dikembangkan menggunakan Laravel 12. Aplikasi ini dirancang untuk mengelola operasional toko retail dengan fitur multi-store (toko pusat dan cabang), manajemen inventori gudang, sistem membership, dan pelaporan penjualan.

## ✨ Fitur Utama

### 👤 Multi-Role User Management
- **Superadmin** - Mengelola seluruh sistem, karyawan, supplier, dan melihat laporan penjualan semua toko
- **Cashier (Kasir)** - Melakukan transaksi penjualan, mengelola membership, dan melihat riwayat transaksi
- **Storage (Gudang)** - Mengelola produk, stok gudang, dan permintaan stok

### 🏪 Multi-Store Support
- Dukungan untuk toko pusat (main store) dan toko cabang (branch store)
- Setiap toko memiliki gudang dan stok masing-masing
- Sistem permintaan stok dari cabang ke pusat

### 📦 Manajemen Inventori
- Pengelolaan produk dengan kode produk, harga beli, dan harga jual
- Manajemen stok gudang per toko
- Sistem permintaan stok (Stock Request) dengan alur approval
- Tracking supplier dan produk dari supplier

### 💳 Sistem Transaksi
- Keranjang belanja (Cart) untuk kasir
- Dukungan berbagai metode pembayaran
- Integrasi dengan sistem membership untuk diskon khusus

### 🎫 Sistem Membership
- Registrasi dan pengelolaan member
- Lookup member berdasarkan kode membership
- Status aktif/non-aktif member
- Transaksi khusus member

### 📊 Riwayat & Laporan
- Riwayat transaksi per kasir
- Detail transaksi lengkap
- Laporan penjualan per toko (Superadmin)

## 🛠️ Tech Stack

| Kategori | Teknologi |
|----------|-----------|
| **Backend** | Laravel 12, PHP 8.2+ |
| **Frontend** | Blade Templates, Tailwind CSS 4.0, Bootstrap 5.3, MDB UI Kit |
| **Database** | MySQL |
| **Build Tools** | Vite 7, NPM |
| **Testing** | PHPUnit 11 |

## 📁 Struktur Database

```
stores              - Data toko (pusat & cabang)
users               - Data pengguna (superadmin, cashier, storage)
products            - Data produk
suppliers           - Data supplier
warehouses          - Data gudang per toko
warehouse_stocks    - Stok produk di gudang
stock_requests      - Permintaan stok dari cabang
stock_request_details - Detail permintaan stok
memberships         - Data member
orders              - Data transaksi/order
order_details       - Detail item dalam order
```

## 🚀 Instalasi

### Prasyarat
- PHP >= 8.2
- Composer
- Node.js & NPM
- MySQL

### Langkah Instalasi

1. **Clone repository**
   ```bash
   git clone https://github.com/username/Arifa-Jaya-POS.git
   cd Arifa-Jaya-POS
   ```

2. **Install dependencies**
   ```bash
   composer install
   npm install
   ```

3. **Konfigurasi environment**
   ```bash
   cp env .env
   ```
   
   Sesuaikan konfigurasi database di file `.env`:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=db_arifa_pos
   DB_USERNAME=root
   DB_PASSWORD=
   ```

4. **Generate application key**
   ```bash
   php artisan key:generate
   ```

5. **Jalankan migrasi dan seeder**
   ```bash
   php artisan migrate
   php artisan db:seed
   ```

6. **Build assets**
   ```bash
   npm run build
   ```

7. **Jalankan aplikasi**
   ```bash
   php artisan serve
   ```

### Quick Setup (Alternatif)
```bash
composer setup
```

### Development Mode
```bash
composer dev
```
Perintah ini akan menjalankan Laravel server, queue listener, log viewer, dan Vite secara bersamaan.

## 📖 Penggunaan

### Login
Akses aplikasi melalui `http://localhost:8000/login`

### Role-based Dashboard
Setelah login, pengguna akan diarahkan ke dashboard sesuai role:
- **Superadmin**: `/superadmin`
- **Cashier**: `/cashier`
- **Storage (Main)**: `/warehouse`
- **Storage (Branch)**: `/warehouse/branch`

## 🗂️ Struktur Aplikasi

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── AuthController.php
│   │   ├── SuperadminController.php
│   │   ├── CashierController.php
│   │   └── StorageController.php
│   └── Middleware/
├── Models/
│   ├── User.php
│   ├── Store.php
│   ├── Product.php
│   ├── Supplier.php
│   ├── Warehouse.php
│   ├── WarehouseStock.php
│   ├── StockRequest.php
│   ├── StockRequestDetail.php
│   ├── Membership.php
│   ├── Order.php
│   └── OrderDetail.php
└── Providers/

resources/views/
├── auth/           - Halaman login
├── superadmin/     - Views untuk superadmin
├── cashier/        - Views untuk kasir
└── storage/        - Views untuk gudang
```

## 🧪 Testing

Jalankan test dengan perintah:
```bash
composer test
```
atau
```bash
php artisan test
```

## 📝 License

Project ini dilisensikan di bawah [MIT License](LICENSE).

<p align="center">
  Made with ❤️ using Laravel
</p>
