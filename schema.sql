DROP DATABASE IF EXISTS db_presensi_siswa;
CREATE DATABASE db_presensi_siswa;
USE db_presensi_siswa;

CREATE TABLE kelas (
    id_kelas INT AUTO_INCREMENT PRIMARY KEY,
    nama_kelas VARCHAR(20) NOT NULL UNIQUE,
    jurusan VARCHAR(50) NOT NULL
) ENGINE=InnoDB;

CREATE TABLE siswa (
    nisn CHAR(10) PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    alamat VARCHAR(255),
    id_kelas INT NOT NULL,
    CONSTRAINT fk_siswa_kelas FOREIGN KEY (id_kelas) REFERENCES kelas(id_kelas) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB;

CREATE TABLE presensi (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nisn CHAR(10) NOT NULL,
    tanggal_waktu DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_presensi_siswa FOREIGN KEY (nisn) REFERENCES siswa(nisn) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;

CREATE TABLE users (
    id_user INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
) ENGINE=InnoDB;

-- Seeder Admin (username: admin | password: password)
INSERT INTO users (username, password) VALUES 
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

-- Seeder Kelas
INSERT INTO kelas (nama_kelas, jurusan) VALUES 
('X PPLG 1', 'Pengembangan Perangkat Lunak dan Gim'),
('XI PPLG 1', 'Pengembangan Perangkat Lunak dan Gim'),
('XII PPLG 1', 'Pengembangan Perangkat Lunak dan Gim'),
('XI TJKT 1', 'Teknik Jaringan Komputer dan Telekomunikasi');

-- Seeder Siswa (keduanya masuk ke id_kelas = 2 yaitu XI PPLG 1)
INSERT INTO siswa (nisn, nama, alamat, id_kelas) VALUES 
('0081234567', 'Ahmad Fauzi', 'Jl. Pahlawan No. 12, Samarinda', 2),
('0087654321', 'Siti Aminah', 'Jl. Lambung Mangkurat, Samarinda', 2);