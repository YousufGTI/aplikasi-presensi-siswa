<?php
require_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../layouts/navbar.php';
require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/../../models/Presensi.php';

$db = (new Database())->getConnection();
$presensiModel = new Presensi($db);

$alert_type = '';$alert_msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nisn = trim($_POST['nisn'] ?? '');

    if ($nisn === '') {
        $alert_type = 'danger';$alert_msg = 'Silakan tempelkan kartu atau masukkan 10 digit NISN.';
    } else {
        $hasil = $presensiModel->tapKartu($nisn);
        $alert_type =$hasil['status'] ? 'success' : 'danger';
        $alert_msg =$hasil['message'];
    }
}
?>

<div class="card" style="max-width: 500px; margin: 20px auto;">
    <h2>Simulasi Tap Kartu RFID</h2>
    <p style="margin-bottom: 15px; color: #64748b; font-size: 0.9rem;">
        Tempelkan kartu RFID atau ketik NISN siswa untuk mencatat kehadiran hari ini.
    </p>

    <?php if ($alert_msg): ?>
        <div class="alert alert-<?= $alert_type ?>">
            <?= htmlspecialchars($alert_msg) ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="">
        <div class="form-group">
            <label for="nisn">NISN Siswa</label>
            <input type="text" id="nisn" name="nisn" maxlength="10" class="form-control" placeholder="Masukkan 10 digit NISN..." required autofocus autocomplete="off">
        </div>
        <button type="submit" class="btn btn-primary" style="width: 100%;">Tap Kartu</button>
    </form>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>