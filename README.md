# Aplikasi Presensi Siswa RFID - SMK TI Airlangga Samarinda

Aplikasi web berbasis **PHP Native (OOP & PDO)** dengan database **MariaDB** untuk mencatat dan merekap kehadiran siswa secara otomatis melalui pemindaian kartu RFID (NISN) di gerbang sekolah[cite: 1].

## Fitur Utama
1. **Autentikasi Administrator** (Login & Logout terproteksi Session)[cite: 1, 2]
2. **Kelola Kelas** (CRUD lengkap dengan validasi nama kelas unik dan proteksi relasi data siswa)[cite: 1, 2]
3. **Kelola Siswa** (CRUD lengkap dengan validasi NISN 10 digit angka, pencarian nama/NISN, dan relasi kelas)[cite: 1, 2, 3]
4. **Simulasi Tap Kartu RFID** (Input NISN otomatis mencatat waktu kehadiran dan menolak tap ganda di hari yang sama)[cite: 1, 2]
5. **Rekap Presensi** (Tampilan bawaan presensi hari ini dengan `INNER JOIN` 3 tabel, filter tanggal, filter kelas, pencarian nama/NISN, dan hapus log)[cite: 1, 2, 3]

## Cara Instalasi (Laragon)
1. Clone atau salin folder proyek ini ke direktori `C:\laragon\www\aplikasi-presensi`.
2. Jalankan Laragon (**Start All**).
3. Buka **HeidiSQL** atau **phpMyAdmin**, lalu jalankan file `schema.sql` untuk membuat database `db_presensi_siswa`, struktur tabel, serta data awal (*seeder*)[cite: 1, 2].
4. Buka browser dan akses `http://localhost/aplikasi-presensi`.

## Akun Demo Administrator
* **Username:** `admin`
* **Password:** `password`

## Entity Relationship Diagram (ERD)
```text
[ kelas ] 1 ────< anggota >──── N [ siswa ] 1 ────< melakukan >──── N [ presensi ]
- id_kelas (PK)                   - nisn (PK)                         - id (PK)
- nama_kelas (UNIQUE)             - nama                              - nisn (FK)
- jurusan                         - alamat                            - tanggal_waktu
                                  - id_kelas (FK)