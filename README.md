<div align="center">
    <img src="assets/img/logo.png" alt="Logo" width="100">
</div>

# Tracer Study

Tracer Study adalah aplikasi interaktif berbasis web yang dibangun menggunakan PHP dan TailwindCSS. Aplikasi ini bertujuan untuk melacak lulusan dan mengumpulkan data mengenai riwayat pendidikan dan pekerjaan mereka, menyediakan antarmuka pengguna yang intuitif dan fungsional.

## Daftar Isi

- [Pendahuluan](#pendahuluan)
- [Fitur](#fitur)
- [Teknologi yang Digunakan](#teknologi-yang-digunakan)
- [Instalasi](#instalasi)
- [Struktur Database](#struktur-database)
- [Penggunaan](#penggunaan)
- [Keamanan](#keamanan)
- [Logging](#logging)
- [Penulis](#penulis)
- [Lisensi](#lisensi)
- [Umpan Balik](#umpan-balik)

## Pendahuluan

**Tracer Study** dirancang untuk mempermudah institusi dalam mengumpulkan data dari lulusan. Aplikasi ini menampilkan antarmuka pengguna yang dinamis, termasuk animasi dan validasi untuk pengalaman pengguna yang lebih baik.

## Fitur

- **Animasi Preloader**: Menyajikan animasi saat halaman dimuat untuk meningkatkan pengalaman pengguna
- **Validasi Formulir**: Menyediakan validasi di sisi klien untuk memastikan semua input yang dibutuhkan terisi dengan benar
- **Interaksi Tombol Radio & Checkbox**: Memungkinkan pilihan yang interaktif untuk menyesuaikan input teks yang terkait
- **Dashboard Admin**: Panel admin untuk melihat dan mengelola data responden
- **Sistem Autentikasi**: Manajemen sesi pengguna yang aman
- **Export Data**: Kemampuan untuk melihat dan mengekspor jawaban responden

## Teknologi yang Digunakan

- **Frontend**: PHP, TailwindCSS, GSAP (animasi)
- **Backend**: PHP Native dengan arsitektur MVC
- **Database**: MySQL
- **Library dan Tools**: 
  - Composer untuk manajemen dependensi
  - Dotenv untuk konfigurasi environment
  - Symfony Routing untuk manajemen route
  - PDO untuk koneksi database

## Instalasi

1. Clone repositori:
   ```bash
   git clone https://github.com/maousamaNatz/quizmahaphp.git
   ```
2. Pindah ke direktori proyek:
   ```bash
   cd quizmahaphp
   ```
3. Install dependensi dengan Composer:
   ```bash
   composer install
   ```
4. Buat file .env dengan konfigurasi berikut:
   ```
   DB_HOST=localhost
   DB_USER=root
   DB_PASS=
   DB_NAME=nama_database
   ```
5. Import struktur database dari file `cik.sql`
6. Jalankan server PHP lokal atau gunakan Apache

## Struktur Database

Aplikasi menggunakan beberapa tabel utama:
- users: Menyimpan data pengguna
- questions: Menyimpan pertanyaan survei
- sub_questions: Menyimpan sub-pertanyaan
- question_options: Menyimpan pilihan jawaban
- user_answers: Menyimpan jawaban responden dalam format JSON

## Keamanan

- Implementasi CSRF protection
- Password hashing menggunakan bcrypt
- Validasi input dan sanitasi data
- Manajemen sesi yang aman

## Logging

Sistem logging terintegrasi untuk:
- Error logging
- Activity logging
- Database error tracking

## Lisensi

Proyek ini dilisensikan di bawah [Lisensi MIT](LICENSE).

## Umpan Balik

Untuk pertanyaan dan umpan balik, silakan hubungi melalui `nat0zxn99@gmail.com`.
