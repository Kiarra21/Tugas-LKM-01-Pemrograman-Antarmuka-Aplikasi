-- database.sql
-- DDL + sample data untuk domain Perpustakaan Digital
-- Target DBMS: MySQL / MariaDB

SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS borrowings;
DROP TABLE IF EXISTS books;
DROP TABLE IF EXISTS authors;
DROP TABLE IF EXISTS categories;
DROP TABLE IF EXISTS users;
DROP TABLE IF EXISTS roles;

SET FOREIGN_KEY_CHECKS = 1;

CREATE TABLE categories (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE,
    description TEXT NULL,
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE roles (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL UNIQUE,
    description VARCHAR(255) NULL,
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE users (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    role_id BIGINT UNSIGNED NULL,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    email_verified_at TIMESTAMP NULL,
    password VARCHAR(255) NOT NULL,
    remember_token VARCHAR(100) NULL,
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_users_role FOREIGN KEY (role_id) REFERENCES roles(id) ON UPDATE CASCADE ON DELETE SET NULL,
    INDEX idx_users_role_id (role_id)
) ENGINE=InnoDB;

CREATE TABLE authors (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(120) NOT NULL,
    email VARCHAR(255) NULL UNIQUE,
    bio TEXT NULL,
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_authors_name (name)
) ENGINE=InnoDB;

CREATE TABLE books (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    category_id BIGINT UNSIGNED NOT NULL,
    author_id BIGINT UNSIGNED NOT NULL,
    title VARCHAR(150) NOT NULL,
    slug VARCHAR(170) NOT NULL UNIQUE,
    isbn VARCHAR(20) NOT NULL UNIQUE,
    published_year SMALLINT UNSIGNED NOT NULL,
    stock INT UNSIGNED NOT NULL DEFAULT 0,
    synopsis TEXT NULL,
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_books_category FOREIGN KEY (category_id) REFERENCES categories(id) ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT fk_books_author FOREIGN KEY (author_id) REFERENCES authors(id) ON UPDATE CASCADE ON DELETE RESTRICT,
    INDEX idx_books_title (title),
    INDEX idx_books_category_year (category_id, published_year)
) ENGINE=InnoDB;

CREATE TABLE borrowings (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    book_id BIGINT UNSIGNED NOT NULL,
    borrower_name VARCHAR(120) NOT NULL,
    borrower_email VARCHAR(255) NOT NULL,
    borrowed_at DATE NOT NULL,
    due_date DATE NOT NULL,
    returned_at DATE NULL,
    status ENUM('borrowed', 'returned', 'late') NOT NULL DEFAULT 'borrowed',
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_borrowings_book FOREIGN KEY (book_id) REFERENCES books(id) ON UPDATE CASCADE ON DELETE RESTRICT,
    INDEX idx_borrowings_book_status (book_id, status),
    INDEX idx_borrower_name (borrower_name),
    INDEX idx_borrower_email (borrower_email),
    INDEX idx_borrowed_at (borrowed_at),
    INDEX idx_due_date (due_date),
    INDEX idx_returned_at (returned_at),
    INDEX idx_status (status)
) ENGINE=InnoDB;

INSERT INTO categories (name, description) VALUES
('Web Development', 'Buku pengembangan website dan backend.'),
('Database', 'Buku basis data relasional dan NoSQL.'),
('Data Science', 'Buku analisis data dan machine learning.'),
('Mobile Development', 'Buku pengembangan aplikasi Android/iOS.'),
('Software Engineering', 'Buku desain sistem dan praktik rekayasa perangkat lunak.');

INSERT INTO roles (name, description) VALUES
('admin', 'Can perform full CRUD operations'),
('user', 'Can only read books and borrowings');

INSERT INTO users (role_id, name, email, password, created_at, updated_at) VALUES
(1, 'kiarra', 'kiarra@example.com', '$2y$10$5Zg0W0iYpzs1LOaD7DsbquoRviG5gvBMTsiZoWuaK4T7AXRKN31EW', NOW(), NOW()),
(2, 'Test User', 'user@example.com', '$2y$10$5Zg0W0iYpzs1LOaD7DsbquoRviG5gvBMTsiZoWuaK4T7AXRKN31EW', NOW(), NOW());

INSERT INTO authors (name, email, bio) VALUES
('Kiarra', 'kiarra@example.com', 'Penulis utama pada topik pengembangan aplikasi dan dokumentasi API.'),
('Farel', 'farel@example.com', 'Penulis yang fokus pada backend, database, dan integrasi sistem.'),
('Budi Santoso', 'budi.santoso@example.com', 'Praktisi software architecture.'),
('Dewi Lestari', 'dewi.lestari@example.com', 'Penulis mobile dan UI engineering.'),
('Rizky Maulana', 'rizky.maulana@example.com', 'Spesialis database dan query optimization.');

INSERT INTO books (category_id, author_id, title, slug, isbn, published_year, stock, synopsis) VALUES
(1, 1, 'Laravel API Praktis', 'laravel-api-praktis', '9780000000001', 2023, 10, 'Panduan membangun REST API dengan Laravel.'),
(2, 5, 'SQL dan Optimasi Query', 'sql-dan-optimasi-query', '9780000000002', 2021, 7, 'Teknik indexing dan tuning query untuk performa tinggi.'),
(3, 2, 'Pengantar Data Science', 'pengantar-data-science', '9780000000003', 2022, 8, 'Dasar statistik, Python, dan machine learning.'),
(4, 4, 'Flutter untuk Pemula', 'flutter-untuk-pemula', '9780000000004', 2024, 12, 'Membangun aplikasi mobile lintas platform.'),
(5, 3, 'Clean Architecture Indonesia', 'clean-architecture-indonesia', '9780000000005', 2020, 5, 'Prinsip arsitektur software yang maintainable.');

INSERT INTO borrowings (book_id, borrower_name, borrower_email, borrowed_at, due_date, returned_at, status) VALUES
(1, 'Farel', 'farel@student.ac.id', '2026-04-01', '2026-04-08', '2026-04-07', 'returned'),
(2, 'Kiarra', 'kiarra@student.ac.id', '2026-04-02', '2026-04-09', NULL, 'borrowed'),
(3, 'Raka', 'raka@student.ac.id', '2026-04-03', '2026-04-10', NULL, 'late'),
(4, 'Tania', 'tania@student.ac.id', '2026-04-05', '2026-04-12', '2026-04-11', 'returned'),
(5, 'Yusuf', 'yusuf@student.ac.id', '2026-04-06', '2026-04-13', NULL, 'borrowed');
