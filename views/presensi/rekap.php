<?php
// Set zona waktu WITA (Samarinda) agar filter tanggal hari ini akurat
date_default_timezone_set('Asia/Makassar');

require_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../layouts/navbar.php';
require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/../../models/Presensi.php';
require_once __DIR__ . '/../../models/Kelas.php';

$db = (new Database())->getConnection();
$presensiModel = new Presensi($db);
$kelasModel = new Kelas($db);

$alert_success = '';
if (isset($_GET['hapus'])) {
    $id_hapus = (int)$_GET['hapus'];
    if ($presensiModel->delete($id_hapus)) {$alert_success = "Log presensi berhasil dihapus.";
    }
}

// Secara bawaan menampilkan presensi hari ini
$tanggal =$_GET['tanggal'] ?? date('Y-m-d');
$id_kelas =$_GET['id_kelas'] ?? '';
$keyword = trim($_GET['q'] ?? '');

$daftar_kelas = $kelasModel->getAll();$data_presensi = $presensiModel->getAll($tanggal, $id_kelas,$keyword);
?>

<div class="card">
    <div class="header-action">
        <h2>Rekap Presensi Siswa</h2>
        <a href="tap.php" class="btn btn-primary">+ Simulasi Tap Kartu</a>
    </div>

    <?php if ($alert_success): ?>
        <div class="alert alert-success"><?= htmlspecialchars($alert_success) ?></div>
    <?php endif; ?>

    <form method="GET" action="" class="filter-bar">
        <div class="form-group">
            <label for="tanggal">Tanggal</label>
            <input type="date" id="tanggal" name="tanggal" class="form-control" value="<?= htmlspecialchars($tanggal) ?>">
        </div>

        <div class="form-group">
            <label for="id_kelas">Kelas</label>
            <select id="id_kelas" name="id_kelas" class="form-control">
                <option value="">-- Semua Kelas --</option>
                <?php foreach ($daftar_kelas as$k): ?>
                    <option value="<?= $k['id_kelas'] ?>" <?= ($id_kelas ==$k['id_kelas']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($k['nama_kelas']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="q">Cari Nama / NISN</label>
            <input type="text" id="q" name="q" class="form-control" placeholder="Ketik nama atau NISN..." value="<?= htmlspecialchars($keyword) ?>">
        </div>

        <button type="submit" class="btn btn-primary">Filter</button>
        <a href="rekap.php" class="btn btn-secondary">Hari Ini</a>
    </form>

    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th style="width: 50px;">No</th>
                    <th>NISN</th>
                    <th>Nama Siswa</th>
                    <th>Kelas</th>
                    <th>Waktu Tap</th>
                    <th style="width: 100px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($data_presensi)): ?>
                    <tr><td colspan="6" style="text-align:center;">Tidak ada data presensi pada filter yang dipilih.</td></tr>
                <?php else: ?>
                    <?php $no = 1; foreach ($data_presensi as$row): ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= htmlspecialchars($row['nisn']) ?></td>
                        <td><?= htmlspecialchars($row['nama']) ?></td>
                        <td><?= htmlspecialchars($row['nama_kelas']) ?></td>
                        <td><?= htmlspecialchars($row['tanggal_waktu']) ?></td>
                        <td>
                            <a href="rekap.php?hapus=<?= $row['id'] ?>&tanggal=<?= urlencode($tanggal) ?>&id_kelas=<?= urlencode($id_kelas) ?>&q=<?= urlencode($keyword) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Hapus log presensi ini?')">Hapus</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>