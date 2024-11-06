
# Tracer Study

Tracer Study adalah aplikasi interaktif berbasis web yang dibangun menggunakan PHP dan TailwindCSS. Aplikasi ini bertujuan untuk melacak lulusan dan mengumpulkan data mengenai riwayat pendidikan dan pekerjaan mereka, menyediakan antarmuka pengguna yang intuitif dan fungsional.

## Daftar Isi

- [Pendahuluan](#pendahuluan)
- [Fitur](#fitur)
- [Teknologi yang Digunakan](#teknologi-yang-digunakan)
- [Instalasi](#instalasi)
- [Penggunaan](#penggunaan)
- [Penulis](#penulis)
- [Lisensi](#lisensi)
- [Umpan Balik](#umpan-balik)

## Pendahuluan

**Tracer Study** dirancang untuk mempermudah institusi dalam mengumpulkan data dari lulusan. Aplikasi ini menampilkan antarmuka pengguna yang dinamis, termasuk animasi dan validasi untuk pengalaman pengguna yang lebih baik.

## Fitur

- **Animasi Preloader**: Menyajikan animasi saat halaman dimuat untuk meningkatkan pengalaman pengguna.
- **Validasi Formulir**: Menyediakan validasi di sisi klien untuk memastikan semua input yang dibutuhkan terisi dengan benar.
- **Interaksi Tombol Radio & Checkbox**: Memungkinkan pilihan yang interaktif untuk menyesuaikan input teks yang terkait.

## Teknologi yang Digunakan

- **Frontend**: PHP, TailwindCSS
- **Backend**: PHP Native
- **Database**: MySQL
- **Library dan Tools**: Composer, Dotenv

## Instalasi

Untuk menjalankan aplikasi ini secara lokal, ikuti langkah-langkah berikut:

1. Clone repositori:
   ```bash
   git clone https://github.com/maousamaNatz/quizmahaphp.git
   ```
2. Pindah ke direktori proyek:
   ```bash
   cd quizmahaphp
   ```
3. Install composer 
4. buat file .env dan isi dengan benar. (isi sesuai dengan [env](#env-lengkap)).
5. Jalankan server PHP lokal (contoh: `php -S localhost:8000` di dalam direktori proyek) atau yang penting apache dan mysql aktif pada komputer anda.

## ENV LENGKAP

```
DB_HOST=localhost
DB_NAME=apacona
DB_USER=root
DB_PASSWORD=

```

## Penggunaan

Setelah server aktif, buka browser dan akses `http://localhost:8000` untuk menggunakan aplikasi Tracer Study.

## Penulis

- [@maousamaNatz](https://github.com/maousamaNatz)

## Lisensi

Proyek ini dilisensikan di bawah [Lisensi MIT](https://choosealicense.com/licenses/mit/).

## Umpan Balik

Jika Anda memiliki umpan balik atau saran, silakan hubungi melalui `nat0zxn99@gmail.com`.
