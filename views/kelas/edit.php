<?php
require_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../layouts/navbar.php';
require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/../../models/Kelas.php';

$db = (new Database())->getConnection();
$kelasModel = new Kelas($db);

$id = (int) ($_GET['id'] ?? 0);
$data_lama = $kelasModel->getById($id);

if (!$data_lama) {
    header("Location: index.php");
    exit;
}

$errors = [];
$nama_kelas = $data_lama['nama_kelas'];
$jurusan = $data_lama['jurusan'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_kelas = trim($_POST['nama_kelas'] ?? '');
    $jurusan = trim($_POST['jurusan'] ?? '');

    if ($nama_kelas === '') {
        $errors['nama_kelas'] = "Nama kelas wajib diisi.";
    } elseif ($kelasModel->isNamaKelasExist($nama_kelas, $id)) {
        $errors['nama_kelas'] = "Nama kelas sudah digunakan oleh kelas lain.";
    }

    if ($jurusan === '') {
        $errors['jurusan'] = "Jurusan wajib diisi.";
    }

    if (empty($errors)) {
        $kelasModel->setIdKelas($id);
        $kelasModel->setNamaKelas($nama_kelas);
        $kelasModel->setJurusan($jurusan);
        if ($kelasModel->update()) {
            header("Location: index.php?msg=updated");
            exit;
        }
    }
}
?>

<div class="card" style="max-width: 600px;">
    <h2>Ubah Data Kelas</h2>
    <form method="POST" action="">
        <div class="form-group">
            <label for="nama_kelas">Nama Kelas</label>
            <input type="text" id="nama_kelas" name="nama_kelas" class="form-control" value="<?= htmlspecialchars($nama_kelas) ?>">
            <?php if (isset($errors['nama_kelas'])): ?>
                <span class="error-text"><?= htmlspecialchars($errors['nama_kelas']) ?></span>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label for="jurusan">Jurusan</label>
            <input type="text" id="jurusan" name="jurusan" class="form-control" value="<?= htmlspecialchars($jurusan) ?>">
            <?php if (isset($errors['jurusan'])): ?>
                <span class="error-text"><?= htmlspecialchars($errors['jurusan']) ?></span>
            <?php endif; ?>
        </div>

        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        <a href="index.php" class="btn btn-secondary">Batal</a>
    </form>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>