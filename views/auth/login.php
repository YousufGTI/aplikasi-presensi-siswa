<?php
$is_login_page = true;
require_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../layouts/navbar.php';
require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/../../models/User.php';

if (isset($_SESSION['id_user'])) {
    header("Location: $base_url/views/presensi/rekap.php");
    exit;
}

$error = '';
$username_val = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username_val = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    $db = (new Database())->getConnection();
    $userModel = new User($db);

    if ($userModel->login($username_val, $password)) {
        $_SESSION['id_user'] = $userModel->getIdUser();
        $_SESSION['username'] = $userModel->getUsername();
        header("Location: $base_url/views/presensi/rekap.php");
        exit;
    } else {
        $error = "Username atau password tidak valid.";
    }
}
?>

<div class="card login-box">
    <h2>Login Administrator</h2>
    <?php if ($error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" action="">
        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" class="form-control" value="<?= htmlspecialchars($username_val) ?>" required autofocus>
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary" style="width: 100%;">Masuk</button>
    </form>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>