# 🌾 AgroStock

**AgroStock** adalah sistem manajemen inventaris pertanian yang dibangun menggunakan **Laravel 12**, **Bootstrap 5**, dan **Fetch API (Vanilla JavaScript)**. Proyek ini dikembangkan sebagai **Tugas Akhir Mata Kuliah Pemrograman Web**.

## Tujuan Proyek

Aplikasi ini dibuat untuk mendemonstrasikan penguasaan teknis dalam pengembangan web modern, meliputi:

- **Backend**: Implementasi RESTful Controller menggunakan Laravel dan relasi database MySQL.
- **Frontend**: Interaksi data secara asinkron menggunakan Fetch API (Vanilla JavaScript), Bootstrap 5, serta integrasi Public API.
- **Keamanan**: Sistem autentikasi dan otorisasi menggunakan Middleware berbasis Multi Role (Admin & User).

## Panduan Instalasi

### Clone repository

```bash
git clone https://github.com/And9k4-ai/IF0224010_AgroStock.git
cd IF0224010_AgroStock
```

### Install dependensi

```bash
composer install
```

### Salin file environment

Linux / macOS

```bash
cp .env.example .env
```

Windows

```cmd
copy .env.example .env
```

### Konfigurasi Database

Sesuaikan file `.env`

```env
DB_DATABASE=agrostock
DB_USERNAME=root
DB_PASSWORD=
```

### Generate Application Key

```bash
php artisan key:generate
```

### Jalankan Migrasi & Seeder

```bash
php artisan migrate --seed
```

### Jalankan Server

```bash
php artisan serve
```

Buka browser

```
http://localhost:8000
```

## Login Default

Email

```text
admin@agrostock.com
```

Password

```text
12345678
```

---

**Dibuat untuk memenuhi Tugas Akhir Mata Kuliah Pemrograman Web.**