<?php if (isset($_SESSION['id_user'])): ?>
<nav class="navbar">
    <div class="nav-container">
        <a href="<?= $base_url ?>/views/presensi/rekap.php" class="nav-brand">Presensi SMK TI Airlangga</a>
        <ul class="nav-menu">
            <li><a href="<?= $base_url ?>/views/presensi/rekap.php">Rekap Presensi</a></li>
            <li><a href="<?= $base_url ?>/views/presensi/tap.php">Simulasi Tap Kartu</a></li>
            <li><a href="<?= $base_url ?>/views/siswa/index.php">Data Siswa</a></li>
            <li><a href="<?= $base_url ?>/views/kelas/index.php">Data Kelas</a></li>
            <li><a href="<?= $base_url ?>/logout.php" class="btn-logout">Logout (<?= htmlspecialchars($_SESSION['username']) ?>)</a></li>
        </ul>
    </div>
</nav>
<?php endif; ?>
<main class="container">