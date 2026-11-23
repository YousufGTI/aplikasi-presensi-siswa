<?php
require_once __DIR__ . '/../core/BaseModel.php';

class Siswa extends BaseModel {
    private $nisn;
    private $nama;
    private $alamat;
    private $id_kelas;

    public function __construct($db) {
        parent::__construct($db);
    }

    public function setNisn($nisn) { $this->nisn = trim($nisn); }
    public function getNisn() { return $this->nisn; }

    public function setNama($nama) { $this->nama = trim($nama); }
    public function getNama() { return $this->nama; }

    public function setAlamat($alamat) { $this->alamat = trim($alamat); }
    public function getAlamat() { return $this->alamat; }

    public function setIdKelas($id_kelas) { $this->id_kelas = $id_kelas; }
    public function getIdKelas() { return $this->id_kelas; }

    public function getAll($keyword = '') {
        $sql = "SELECT siswa.*, kelas.nama_kelas, kelas.jurusan 
                FROM siswa 
                INNER JOIN kelas ON siswa.id_kelas = kelas.id_kelas";
        
        if (!empty($keyword)) {
            $sql .= " WHERE siswa.nama LIKE :keyword OR siswa.nisn LIKE :keyword";
        }
        $sql .= " ORDER BY kelas.nama_kelas ASC, siswa.nama ASC";

        $stmt = $this->db->prepare($sql);
        if (!empty($keyword)) {
            $stmt->execute([':keyword' => "%$keyword%"]);
        } else {
            $stmt->execute();
        }
        return $stmt->fetchAll();
    }

    public function getByNisn($nisn) {
        $stmt = $this->db->prepare("SELECT * FROM siswa WHERE nisn = :nisn");
        $stmt->execute([':nisn' => $nisn]);
        return $stmt->fetch();
    }

    public function isNisnExist($nisn, $exclude_nisn = null) {
        if ($exclude_nisn) {
            $stmt = $this->db->prepare("SELECT COUNT(*) FROM siswa WHERE nisn = :nisn AND nisn != :exclude");
            $stmt->execute([':nisn' => $nisn, ':exclude' => $exclude_nisn]);
        } else {
            $stmt = $this->db->prepare("SELECT COUNT(*) FROM siswa WHERE nisn = :nisn");
            $stmt->execute([':nisn' => $nisn]);
        }
        return $stmt->fetchColumn() > 0;
    }

    public function create() {
        $stmt = $this->db->prepare("INSERT INTO siswa (nisn, nama, alamat, id_kelas) VALUES (:nisn, :nama, :alamat, :id_kelas)");
        return $stmt->execute([
            ':nisn'     => $this->nisn,
            ':nama'     => $this->nama,
            ':alamat'   => $this->alamat,
            ':id_kelas' => $this->id_kelas
        ]);
    }

    public function update($old_nisn) {
        $stmt = $this->db->prepare("UPDATE siswa SET nisn = :nisn, nama = :nama, alamat = :alamat, id_kelas = :id_kelas WHERE nisn = :old_nisn");
        return $stmt->execute([
            ':nisn'     => $this->nisn,
            ':nama'     => $this->nama,
            ':alamat'   => $this->alamat,
            ':id_kelas' => $this->id_kelas,
            ':old_nisn' => $old_nisn
        ]);
    }

    public function delete($nisn) {
        $stmt = $this->db->prepare("DELETE FROM siswa WHERE nisn = :nisn");
        return $stmt->execute([':nisn' => $nisn]);
    }
}