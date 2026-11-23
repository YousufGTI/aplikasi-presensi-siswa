<?php
require_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../layouts/navbar.php';
require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/../../models/Siswa.php';

$db = (new Database())->getConnection();
$siswaModel = new Siswa($db);

$alert_success = '';
if (isset($_GET['hapus'])) {
    $nisn_hapus = trim($_GET['hapus']);
    if ($siswaModel->delete($nisn_hapus)) {
        $alert_success = "Data siswa berhasil dihapus.";
    }
}

if (isset($_GET['msg'])) {
    if ($_GET['msg'] === 'created') $alert_success = "Data siswa berhasil ditambahkan.";
    if ($_GET['msg'] === 'updated') $alert_success = "Data siswa berhasil diperbarui.";
}

$keyword = trim($_GET['q'] ?? '');
$daftar_siswa = $siswaModel->getAll($keyword);
?>

<div class="card">
    <div class="header-action">
        <h2>Data Siswa</h2>
        <a href="create.php" class="btn btn-primary">+ Tambah Siswa</a>
    </div>

    <?php if ($alert_success): ?>
        <div class="alert alert-success"><?= htmlspecialchars($alert_success) ?></div>
    <?php endif; ?>

    <form method="GET" action="" class="filter-bar">
        <div class="form-group">
            <input type="text" name="q" class="form-control" placeholder="Cari berdasarkan Nama atau NISN..." value="<?= htmlspecialchars($keyword) ?>">
        </div>
        <button type="submit" class="btn btn-primary">Cari</button>
        <?php if ($keyword !== ''): ?>
            <a href="index.php" class="btn btn-secondary">Reset</a>
        <?php endif; ?>
    </form>

    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th style="width: 50px;">No</th>
                    <th>NISN</th>
                    <th>Nama Lengkap</th>
                    <th>Kelas</th>
                    <th>Alamat</th>
                    <th style="width: 150px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($daftar_siswa)): ?>
                    <tr><td colspan="6" style="text-align:center;">Data siswa tidak ditemukan.</td></tr>
                <?php else: ?>
                    <?php $no = 1; foreach ($daftar_siswa as $s): ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= htmlspecialchars($s['nisn']) ?></td>
                        <td><?= htmlspecialchars($s['nama']) ?></td>
                        <td><?= htmlspecialchars($s['nama_kelas']) ?></td>
                        <td><?= htmlspecialchars($s['alamat'] ?? '-') ?></td>
                        <td>
                            <a href="edit.php?nisn=<?= urlencode($s['nisn']) ?>" class="btn btn-warning btn-sm">Ubah</a>
                            <a href="index.php?hapus=<?= urlencode($s['nisn']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus siswa ini?')">Hapus</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>