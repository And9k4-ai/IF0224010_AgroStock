# 🌾 AgroStock

**AgroStock** adalah sistem manajemen inventaris pertanian yang dibangun menggunakan **Laravel 12**, **Bootstrap 5**, dan **Fetch API (Vanilla JavaScript)**. Proyek ini dikembangkan sebagai **Tugas Akhir Mata Kuliah Pemrograman Web**.

## Tujuan Proyek

Aplikasi ini dibuat untuk mendemonstrasikan penguasaan teknis dalam pengembangan web modern, meliputi:

- **Backend**: Implementasi RESTful Controller menggunakan Laravel dan relasi database MySQL.
- **Frontend**: Interaksi data secara asinkron menggunakan Fetch API (Vanilla JavaScript), Bootstrap 5, serta integrasi Public API.
- **Keamanan**: Sistem autentikasi dan otorisasi menggunakan Middleware berbasis Multi Role (Admin & User).

## Panduan Instalasi (Menjalankan Secara Lokal)

Jika ingin menjalankan aplikasi ini di komputer Anda, ikuti langkah-langkah berikut.

### Clone repository

```bash
git clone <url-repository-anda>
cd agrostock
```

### Install dependensi PHP menggunakan Composer

```bash
composer install
```

### Salin file konfigurasi environment

Linux / macOS

```bash
cp .env.example .env
```

Windows (Command Prompt)

```cmd
copy .env.example .env
```

### Konfigurasi Database

Buka file **`.env`**, kemudian sesuaikan konfigurasi database seperti:

- `DB_DATABASE`
- `DB_USERNAME`
- `DB_PASSWORD`

### Generate Application Key

```bash
php artisan key:generate
```

### Jalankan Migrasi & Seeder Database

Perintah berikut akan membuat seluruh tabel database sekaligus mengisi data awal (akun administrator).

```bash
php artisan migrate --seed
```

### Jalankan Development Server

```bash
php artisan serve
```

Aplikasi dapat diakses melalui:

```text
http://localhost:8000
```

### Akun Administrator Default

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
