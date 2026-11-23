<?php
require_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../layouts/navbar.php';
require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/../../models/Kelas.php';

$db = (new Database())->getConnection();
$kelasModel = new Kelas($db);

$errors = [];
$nama_kelas = '';
$jurusan = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_kelas = trim($_POST['nama_kelas'] ?? '');
    $jurusan = trim($_POST['jurusan'] ?? '');

    if ($nama_kelas === '') {
        $errors['nama_kelas'] = "Nama kelas wajib diisi.";
    } elseif ($kelasModel->isNamaKelasExist($nama_kelas)) {
        $errors['nama_kelas'] = "Nama kelas sudah terdaftar, gunakan nama lain.";
    }

    if ($jurusan === '') {
        $errors['jurusan'] = "Jurusan wajib diisi.";
    }

    if (empty($errors)) {
        $kelasModel->setNamaKelas($nama_kelas);
        $kelasModel->setJurusan($jurusan);
        if ($kelasModel->create()) {
            header("Location: index.php?msg=created");
            exit;
        }
    }
}
?>

<div class="card" style="max-width: 600px;">
    <h2>Tambah Kelas</h2>
    <form method="POST" action="">
        <div class="form-group">
            <label for="nama_kelas">Nama Kelas</label>
            <input type="text" id="nama_kelas" name="nama_kelas" class="form-control" placeholder="Contoh: XI PPLG 2" value="<?= htmlspecialchars($nama_kelas) ?>">
            <?php if (isset($errors['nama_kelas'])): ?>
                <span class="error-text"><?= htmlspecialchars($errors['nama_kelas']) ?></span>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label for="jurusan">Jurusan</label>
            <input type="text" id="jurusan" name="jurusan" class="form-control" placeholder="Contoh: Pengembangan Perangkat Lunak dan Gim" value="<?= htmlspecialchars($jurusan) ?>">
            <?php if (isset($errors['jurusan'])): ?>
                <span class="error-text"><?= htmlspecialchars($errors['jurusan']) ?></span>
            <?php endif; ?>
        </div>

        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="index.php" class="btn btn-secondary">Batal</a>
    </form>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>