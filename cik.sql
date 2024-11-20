CREATE DATABASE IF NOT EXISTS tracer;
USE tracer;

-- Tabel Login untuk menyimpan informasi login
CREATE TABLE `login` (
  `id` INT(10) NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(250) NOT NULL,
  `password` VARCHAR(250) NOT NULL,
  `role` ENUM('admin', 'user') NOT NULL DEFAULT 'user',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Insert admin default dengan password yang dienkripsi base64 2x
INSERT INTO `login` (username, password, role) VALUES ('adminitesam', 'YWRtaW5pbWFqYXlh', 'admin'); -- password: adminimajaya

-- Tabel Users untuk menyimpan informasi pengguna
CREATE TABLE `users` (
  `id` INT(10) NOT NULL AUTO_INCREMENT,
  `nama` VARCHAR(250) NOT NULL,
  `nim` VARCHAR(250) NOT NULL UNIQUE,
  `email` VARCHAR(250) NOT NULL UNIQUE,
  `tgl_lahir` DATE NOT NULL,
  `thn_lulus` VARCHAR(250) NOT NULL,
  `perguruan` VARCHAR(250) NOT NULL,
  `nik` VARCHAR(250),
  `npwp` VARCHAR(250),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Tabel Questions untuk menyimpan pertanyaan utama
CREATE TABLE `questions` (
  `id` INT(10) NOT NULL AUTO_INCREMENT,
  `question_text` TEXT NOT NULL,
  `type` ENUM('scale', 'multiple_choice', 'paired_scale', 'text_input', 'double_text_input', 'scale_choice', 'text_choice') NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Tabel Sub Questions untuk menyimpan sub-pertanyaan (misal bagian A dan B)
CREATE TABLE `sub_questions` (
  `id` INT(10) NOT NULL AUTO_INCREMENT,
  `question_id` INT(10) NOT NULL,
  `sub_question_text` TEXT NOT NULL,
  `part` ENUM('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J') DEFAULT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`question_id`) REFERENCES `questions`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Tabel Question Options untuk menyimpan opsi pertanyaan (jika pertanyaan memiliki pilihan jawaban)
CREATE TABLE `question_options` (
  `id` INT(10) NOT NULL AUTO_INCREMENT,
  `sub_question_id` INT(10) NOT NULL,
  `option_label` CHAR(1) NOT NULL,
  `option_text` VARCHAR(250) NOT NULL,
  `option_value` INT(10) DEFAULT NULL,
  `is_text_input_required` BOOLEAN DEFAULT FALSE,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`sub_question_id`) REFERENCES `sub_questions`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
-- Tabel User Answers untuk menyimpan jawaban mahasiswa dalam format JSON
CREATE TABLE `user_answers` (
  `id` INT(10) NOT NULL AUTO_INCREMENT,
  `user_id` INT(10) NOT NULL,
  `answers` JSON NOT NULL,  -- Menyimpan seluruh jawaban dalam format JSON
  PRIMARY KEY (`id`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  UNIQUE KEY `unique_user_answer` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Insert untuk tabel Questions
INSERT INTO questions (question_text, type) VALUES
('Jelaskan status anda saat ini', 'scale'),
('Dalam beberapa bulan anda mendapatkan pekerjaan pertama?', 'text_input'),
('Berapa Rata-rata pendapatan anda per bulan? (take home pay)', 'text_input'),
('Dimana lokasi tempat anda bekerja sekarang?', 'double_text_input'), -- Pertanyaan diubah
('Apa jenis perusahaan/instansi/intitusi tempat anda bekerja sekarang?', 'scale'),
('Apa nama perusahaan/kantor tempat anda bekerja?', 'text_input'),
('Apa tingkat tempat kerja anda?', 'text_input'),
('Seberapa erat hubungan bidang studi dengan pekerjaan anda?', 'scale'),
('Tingkat pendidikan apa yang paling tepat/sesuai untuk pekerjaan anda saat ini?', 'scale'),
('Sebutkan sumber dana dalam pembiayaan kuliah? (bukan ketika studi lanjut)', 'scale'),
('Pada saat lulus dan saat ini, pada tingkat mana kompetensi anda kuasai? (A) pada saat ini, pada tingkat mana kompetensi di bawah ini di perlukan dalam pekerjaan? (B)', 'paired_scale'),
('Menurut anda seberapa besar penekanan pada metode pembelajaran dibawah ini dilaksanakan di program studi anda?', 'scale_choice'),
('Kapan anda mulai mencari pekerjaan? mohon pekerjaan sambilan jangan di masukkan', 'scale'),
('bagaimana cara anda mencari pekerjaan? jawaban bisa lebih dari satu', 'multiple_choice'),
('berapa perusahaan/instansi/intitusi yang sudah anda lamar?(lewat surat atau e-mail) sebelum anda memperoleh pekerjaan pertama', 'scale'),
('berapa banyak perusahaan/instansi/institusi yang merespon lamaran anda?', 'text_input'),
('Berapa banyak perusahaan/instansi/institusi yang mengundang anda untuk wawancara kerja?', 'text_input'),
('Apakah anda aktif mencari pekerjaan dalam 4 minggu terakhir?', 'text_input'),
('jika menurut anda pekerjaan anda saat ini tidak sesuai dengan pendidikan anda, pemngapa anda mengambilnya? jawaban bisa lebih dari satu', 'multiple_choice');

-- Insert untuk tabel Sub Questions
INSERT INTO sub_questions (question_id, sub_question_text, part) VALUES
(11, 'Keahlian berdasarkan bidang ilmu', 'A'),
(11, 'Bahasa Inggris', 'A'),
(11, 'Penggunaan teknologi informasi', 'A'),
(11, 'Komunikasi', 'A'),
(11, 'Kerja sama tim', 'A'),
(11, 'Pengembangan', 'A'),
(11, 'Etika', 'B'),
(11, 'Keahlian berdasarkan bidang ilmu', 'B'),
(11, 'Bahasa Inggris', 'B'),
(11, 'Penggunaan teknologi informasi', 'B'),
(11, 'Komunikasi', 'B'),
(11, 'Kerja sama tim', 'B'),
(11, 'Pengembangan', 'B'),
(12, 'Perkuliahan', NULL),
(12, 'Demonstrasi', NULL),
(12, 'partisipasi dalam proyek riset', NULL),
(12, 'magang', NULL),
(12, 'praktikum', NULL),
(12, 'Diskusi', NULL);

-- Insert untuk tabel Question Options
INSERT INTO question_options (sub_question_id, option_label, option_text, option_value, is_text_input_required) VALUES
(1, 'A', 'Bekerja (full time / part time)', 1, FALSE),
(1, 'B', 'Belum memungkinkan bekerja', 2, FALSE),
(1, 'C', 'Wiraswasta', 3, FALSE),
(1, 'D', 'Melanjutkan Pendidikan', 4, FALSE),
(1, 'E', 'Tidak kerja tetapi sedang mencari kerja', 5, FALSE),
(5, 'A', 'Instansi pemerintah', 1, FALSE),
(5, 'B', 'BUMN/BUMD', 2, FALSE),
(5, 'C', 'Institusi/organisasi multilateral', 3, FALSE),
(5, 'D', 'Organisasi non-profit/lembaga swadaya masyarakat', 4, FALSE),
(5, 'E', 'Perusahaan swasta', 5, FALSE),
(5, 'F', 'Wiraswasta/perusahaan sendiri', 6, FALSE),
(5, 'G', 'Lainnya', 7, TRUE),
(8, 'A', 'Sangat erat', 5, FALSE),
(8, 'B', 'Erat', 4, FALSE),
(8, 'C', 'Cukup erat', 3, FALSE),
(8, 'D', 'Kurang erat', 2, FALSE),
(8, 'E', 'Tidak sama sekali', 1, FALSE),
(9, 'A', 'Setingkat lebih tinggi', 4, FALSE),
(9, 'B', 'Tingkat yang sama', 3, FALSE),
(9, 'C', 'Setingkat lebih rendah', 2, FALSE),
(9, 'D', 'Tidak perlu pendidikan tinggi', 1, FALSE),
(10, 'A', 'Biaya sendiri/keluarga', 1, FALSE),
(10, 'B', 'Beasiswa ADIK', 2, FALSE),
(10, 'C', 'Beasiswa BIDIKMISI', 3, FALSE),
(10, 'D', 'Beasiswa PPA', 4, FALSE),
(10, 'E', 'Beasiswa AFIRMASI', 5, FALSE),
(10, 'F', 'Beasiswa perusahaan/swasta', 6, FALSE),
(10, 'G', 'Lainnya', 7, TRUE),
(13, 'A', 'kira kira berapa bulan sebelum lulus ', 1, TRUE),
(13, 'B', 'kira kira berapa bulan setelah lulus', 2, TRUE),
(13, 'C', 'saya tidak mencari kerja', 3, FALSE),
(14, 'A', 'Melalui iklan di koran/majalah, brosur', 1, FALSE),
(14, 'B', 'Melamar ke perusahaan tanpa mengetahui lowongan yang ada', 2, FALSE),
(14, 'C', 'Pergi ke bursa/pameran kerja', 3, FALSE),
(14, 'D', 'Mencari lewat internet/iklan online/milis', 4, FALSE),
(14, 'E', 'Dihubungi perusahaan', 5, FALSE),
(14, 'F', 'Menghubungi kemenakertrans', 6, FALSE),
(14, 'G', 'Menghubungi agen tenaga kerja komersial/swasta', 7, FALSE),
(14, 'H', 'Memperoleh informasi dari pusat/kantor pengembangan karir fakultas/universitas', 8, FALSE),
(14, 'I', 'Menghubungi kantor kemahasiswaan/hubungan alumni', 9, FALSE),
(14, 'J', 'Membangun jejaring (network) sejak masih kuliah', 10, FALSE),
(14, 'K', 'Melalui relasi (misal dosen, orang tua, saudara, dll)', 11, FALSE),
(14, 'L', 'Membangun bisnis sendiri', 12, FALSE),
(14, 'M', 'Melalui penempatan kerja atau magang', 13, FALSE),
(14, 'N', 'Bekerja di tempat yang sama dengan tempat kerja semasa kuliah', 14, FALSE),
(14, 'O', 'Lainnya', 15, TRUE),
(18, 'A', 'Sangat Puas', 5, FALSE),
(18, 'B', 'Puas', 4, FALSE),
(18, 'C', 'Cukup Puas', 3, FALSE),
(18, 'D', 'Kurang Puas', 2, FALSE),
(18, 'E', 'Tidak Puas', 1, FALSE),
(19, 'A', 'pertanyaan tidak sesuai, Pekerjaan saya sekarang sudah sesuai dengan pendidikan saya', 1, FALSE),
(19, 'B', 'Saya belum mendapatkan pekerjaan yang lebih sesuai', 2, FALSE),
(19, 'C', 'Di pekerjaan ini saya memperoleh prospek karir yang baik', 3, FALSE),
(19, 'D', 'saya lebih suka bekerja di area pekerjaan yang tidak sesuai dengan pendidikan saya', 4, FALSE),
(19, 'E', 'saya dipromosikan ke posisi yang kurang sesuai dengan pendidikan saya', 5, FALSE),
(19, 'F', 'saya dapat memperoleh pendapatan yang lebih tinggi di pekerjaan ini', 6, FALSE),
(19, 'G', 'pekerjaan saya saat ini lebih aman/terjamin/secure', 7, FALSE),
(19, 'H', 'pekerjaan saya saat ini lebih menarik', 8, FALSE),
(19, 'I', 'pekerjaan saya saat ini lebih memungkinkan saya mengambil pekerjaan tambahan/jadwal yang fleksibel, dll', 9, FALSE),
(19, 'J', 'pekerjaan saya saat ini lokasinya lebih dekat dari rumah', 10, FALSE),
(19, 'K', 'pekerjaan saya saat ini dapat lebih menjamin kebutuhan keluarga', 11, FALSE),
(19, 'L', 'pada awal meniti karir ini, saya harus menerima pekerjaan yang tidak berhubungan dengan pendidikan saya', 12, FALSE),
(19, 'M', 'Lainnya', 13, TRUE); 