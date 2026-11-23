<?php
require_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../layouts/navbar.php';
require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/../../models/Kelas.php';

$db = (new Database())->getConnection();
$kelasModel = new Kelas($db);

$alert_success = '';
$alert_error = '';

if (isset($_GET['hapus'])) {
    $id_hapus = (int) $_GET['hapus'];
    if ($kelasModel->hasSiswa($id_hapus)) {
        $alert_error = "Gagal menghapus! Kelas ini masih memiliki siswa aktif. Hapus atau pindahkan siswa terlebih dahulu.";
    } else {
        if ($kelasModel->delete($id_hapus)) {
            $alert_success = "Data kelas berhasil dihapus.";
        } else {
            $alert_error = "Gagal menghapus data kelas.";
        }
    }
}

if (isset($_GET['msg'])) {
    if ($_GET['msg'] === 'created') $alert_success = "Kelas baru berhasil ditambahkan.";
    if ($_GET['msg'] === 'updated') $alert_success = "Data kelas berhasil diperbarui.";
}

$daftar_kelas = $kelasModel->getAll();
?>

<div class="card">
    <div class="header-action">
        <h2>Data Kelas</h2>
        <a href="create.php" class="btn btn-primary">+ Tambah Kelas</a>
    </div>

    <?php if ($alert_success): ?>
        <div class="alert alert-success"><?= htmlspecialchars($alert_success) ?></div>
    <?php endif; ?>
    <?php if ($alert_error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($alert_error) ?></div>
    <?php endif; ?>

    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th style="width: 60px;">No</th>
                    <th>Nama Kelas</th>
                    <th>Jurusan</th>
                    <th style="width: 160px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($daftar_kelas)): ?>
                    <tr><td colspan="4" style="text-align:center;">Belum ada data kelas.</td></tr>
                <?php else: ?>
                    <?php $no = 1; foreach ($daftar_kelas as $k): ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= htmlspecialchars($k['nama_kelas']) ?></td>
                        <td><?= htmlspecialchars($k['jurusan']) ?></td>
                        <td>
                            <a href="edit.php?id=<?= $k['id_kelas'] ?>" class="btn btn-warning btn-sm">Ubah</a>
                            <a href="index.php?hapus=<?= $k['id_kelas'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus kelas ini?')">Hapus</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>