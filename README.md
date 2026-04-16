# Tugas LKM 01 - REST API Perpustakaan Digital

## Deskripsi Singkat Project

Project ini adalah REST API bertema Perpustakaan Digital yang dibangun dengan Laravel. API menyediakan operasi CRUD untuk data buku, serta relasi data antar tabel kategori, author, buku, dan peminjaman.

## Teknologi Yang Digunakan

- Bahasa: PHP 8+
- Framework: Laravel 12
- Database: MySQL atau MariaDB
- Dokumentasi API: Swagger (L5 Swagger)
- Auth API: JWT (tymon/jwt-auth) + Role-based access

## Langkah Instalasi Dan Menjalankan Project

1. Clone repository.
2. Install dependency backend.
   composer install
3. Copy file environment dan generate app key.
   copy .env.example .env
   php artisan key:generate
4. Atur konfigurasi database di file .env.
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=nama_database
   DB_USERNAME=root
   DB_PASSWORD=
5. Jalankan migration.
   php artisan migrate
6. Jalankan server Laravel.
   php artisan serve --no-reload

Server default berjalan di http://127.0.0.1:8000

## Cara Import Database

File SQL tersedia pada root project: database.sql

Contoh import di MySQL/MariaDB:

1. Buat database baru, misal: lkm_api
2. Import file SQL:
   mysql -u root -p lkm_api < database.sql

Catatan:

- File database.sql berisi DDL CREATE TABLE dan sample data minimal 5 baris per tabel untuk 4 tabel berelasi.
- Tabel pada SQL: categories, authors, books, borrowings.

## Dokumentasi Swagger

- URL Swagger UI: http://127.0.0.1:8000/api/documentation
- Regenerate dokumentasi jika ada perubahan endpoint:
  php artisan l5-swagger:generate

## Daftar Endpoint API

| Method    | URL                         | Keterangan                              |
| --------- | --------------------------- | --------------------------------------- |
| POST      | /api/register               | Register akun baru (default role: user) |
| POST      | /api/login                  | Login dan mendapatkan JWT token         |
| GET       | /api/books                  | Lihat daftar buku (admin, user)         |
| GET       | /api/books/{book}           | Lihat detail buku (admin, user)         |
| POST      | /api/books                  | Tambah buku (admin only)                |
| PUT/PATCH | /api/books/{book}           | Ubah buku (admin only)                  |
| DELETE    | /api/books/{book}           | Hapus buku (admin only)                 |
| GET       | /api/borrowings             | Lihat daftar peminjaman (admin, user)   |
| GET       | /api/borrowings/{borrowing} | Lihat detail peminjaman (admin, user)   |
| POST      | /api/borrowings             | Tambah peminjaman (admin only)          |
| PUT/PATCH | /api/borrowings/{borrowing} | Ubah peminjaman (admin only)            |
| DELETE    | /api/borrowings/{borrowing} | Hapus peminjaman (admin only)           |

## Aturan Akses Endpoint

- Semua endpoint selain `register` dan `login` wajib menggunakan JWT token (`Authorization: Bearer <token>`).
- Role `admin`: dapat melakukan CRUD pada books dan borrowings.
- Role `user`: hanya dapat membaca data books dan borrowings (GET).

## Format Response JSON

Semua endpoint menggunakan format JSON konsisten:

- Sukses:
  {
  "success": true,
  "message": "...",
  "data": {...}
  }
- Error validasi:
  {
  "success": false,
  "message": "Validation error.",
  "errors": {...}
  }

## Keamanan Query

Operasi database menggunakan Eloquent ORM Laravel yang menerapkan parameter binding (prepared statement) untuk mencegah SQL Injection.

## Link Video Presentasi

Isi link video presentasi pada bagian ini:
https://youtu.be/ganti-dengan-link-video-presentasi
