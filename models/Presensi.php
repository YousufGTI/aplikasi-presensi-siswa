<?php
require_once __DIR__ . '/../core/BaseModel.php';

class Presensi extends BaseModel {
    private $id;
    private $nisn;
    private $tanggal_waktu;

    public function __construct($db) {
        parent::__construct($db);
    }

    public function setId($id) { $this->id = $id; }
    public function getId() { return $this->id; }

    public function setNisn($nisn) { $this->nisn = trim($nisn); }
    public function getNisn() { return $this->nisn; }

    public function getTanggalWaktu() { return $this->tanggal_waktu; }

    public function tapKartu($nisn) {
        // 1. Cek apakah NISN terdaftar di tabel siswa
        $cek = $this->db->prepare("SELECT nama FROM siswa WHERE nisn = :nisn");
        $cek->execute([':nisn' => $nisn]);
        $siswa = $cek->fetch();

        if (!$siswa) {
            return ['status' => false, 'message' => 'NISN tidak terdaftar dalam sistem!'];
        }

        // 2. Cek apakah siswa sudah melakukan presensi hari ini (anti-spam)
        date_default_timezone_set('Asia/Makassar');
        $hari_ini = date('Y-m-d');
        
        $cek_absen = $this->db->prepare("SELECT COUNT(*) FROM presensi WHERE nisn = :nisn AND DATE(tanggal_waktu) = :hari_ini");
        $cek_absen->execute([
            ':nisn'     => $nisn,
            ':hari_ini' => $hari_ini
        ]);

        if ($cek_absen->fetchColumn() > 0) {
            return [
                'status'  => false,
                'message' => 'Ditolak! Siswa atas nama ' . $siswa['nama'] . ' sudah melakukan presensi hari ini.'
            ];
        }

        // 3. Insert hanya NISN; id & tanggal_waktu otomatis dari database
        $stmt = $this->db->prepare("INSERT INTO presensi (nisn) VALUES (:nisn)");
        $stmt->execute([':nisn' => $nisn]);

        return [
            'status'  => true,
            'message' => 'Berhasil! Presensi atas nama ' . $siswa['nama'] . ' tercatat.'
        ];
    
    }

    public function getAll($tanggal = '', $id_kelas = '', $keyword = '') {
        $sql = "SELECT presensi.id, presensi.nisn, presensi.tanggal_waktu, siswa.nama, kelas.nama_kelas 
                FROM presensi
                INNER JOIN siswa ON presensi.nisn = siswa.nisn
                INNER JOIN kelas ON siswa.id_kelas = kelas.id_kelas
                WHERE 1=1";
        
        $params = [];

        if (!empty($tanggal)) {
            $sql .= " AND DATE(presensi.tanggal_waktu) = :tanggal";
            $params[':tanggal'] = $tanggal;
        }
        if (!empty($id_kelas)) {
            $sql .= " AND siswa.id_kelas = :id_kelas";
            $params[':id_kelas'] = $id_kelas;
        }
        if (!empty($keyword)) {
            $sql .= " AND (siswa.nama LIKE :keyword OR presensi.nisn LIKE :keyword)";
            $params[':keyword'] = "%$keyword%";
        }

        $sql .= " ORDER BY presensi.tanggal_waktu DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM presensi WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
}