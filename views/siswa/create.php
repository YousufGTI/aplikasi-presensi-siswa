<?php
require_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../layouts/navbar.php';
require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/../../models/Siswa.php';
require_once __DIR__ . '/../../models/Kelas.php';

$db = (new Database())->getConnection();
$siswaModel = new Siswa($db);
$kelasModel = new Kelas($db);

$daftar_kelas = $kelasModel->getAll();

$errors = [];
$nisn = '';
$nama = '';
$alamat = '';
$id_kelas = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nisn = trim($_POST['nisn'] ?? '');
    $nama = trim($_POST['nama'] ?? '');
    $alamat = trim($_POST['alamat'] ?? '');
    $id_kelas = $_POST['id_kelas'] ?? '';

    // Validasi NISN: wajib 10 digit angka & unik
    if ($nisn === '') {
        $errors['nisn'] = "NISN wajib diisi.";
    } elseif (!ctype_digit($nisn) || strlen($nisn) !== 10) {
        $errors['nisn'] = "NISN wajib terdiri dari tepat 10 digit angka.";
    } elseif ($siswaModel->isNisnExist($nisn)) {
        $errors['nisn'] = "NISN sudah terdaftar di sistem.";
    }

    // Validasi Nama: wajib diisi
    if ($nama === '') {
        $errors['nama'] = "Nama siswa wajib diisi.";
    }

    // Validasi Kelas: wajib dipilih dari daftar yang ada
    if ($id_kelas === '' || !$kelasModel->getById((int)$id_kelas)) {
        $errors['id_kelas'] = "Silakan pilih kelas yang valid.";
    }

    if (empty($errors)) {
        $siswaModel->setNisn($nisn);
        $siswaModel->setNama($nama);
        $siswaModel->setAlamat($alamat);
        $siswaModel->setIdKelas((int)$id_kelas);

        if ($siswaModel->create()) {
            header("Location: index.php?msg=created");
            exit;
        }
    }
}
?>

<div class="card" style="max-width: 600px;">
    <h2>Tambah Siswa</h2>
    <form method="POST" action="">
        <div class="form-group">
            <label for="nisn">NISN (10 Digit Angka)</label>
            <input type="text" id="nisn" name="nisn" maxlength="10" class="form-control" placeholder="Contoh: 0081234567" value="<?= htmlspecialchars($nisn) ?>">
            <?php if (isset($errors['nisn'])): ?>
                <span class="error-text"><?= htmlspecialchars($errors['nisn']) ?></span>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label for="nama">Nama Lengkap</label>
            <input type="text" id="nama" name="nama" class="form-control" value="<?= htmlspecialchars($nama) ?>">
            <?php if (isset($errors['nama'])): ?>
                <span class="error-text"><?= htmlspecialchars($errors['nama']) ?></span>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label for="id_kelas">Kelas</label>
            <select id="id_kelas" name="id_kelas" class="form-control">
                <option value="">-- Pilih Kelas --</option>
                <?php foreach ($daftar_kelas as $k): ?>
                    <option value="<?= $k['id_kelas'] ?>" <?= ($id_kelas == $k['id_kelas']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($k['nama_kelas']) ?> - <?= htmlspecialchars($k['jurusan']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <?php if (isset($errors['id_kelas'])): ?>
                <span class="error-text"><?= htmlspecialchars($errors['id_kelas']) ?></span>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label for="alamat">Alamat (Opsional)</label>
            <textarea id="alamat" name="alamat" rows="3" class="form-control"><?= htmlspecialchars($alamat) ?></textarea>
        </div>

        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="index.php" class="btn btn-secondary">Batal</a>
    </form>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>