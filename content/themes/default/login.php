<?php
if (!defined('BOLTIX_RUNNING')) die('Direct access forbidden.');

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $identity = trim($_POST['identity']);
    $password = trim($_POST['password']);

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
    $stmt->execute([$identity, $identity]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password_hash'])) {
        $_SESSION['user_id'] = $user['id'];
        header("Location: " . $site_url . "/profile");
        exit;
    } else {
        $error = "Invalid Username/Email or Password.";
    }
}
?>

<div class="form-card">
    <h2 class="form-title">Login to Boltix</h2>
    <?php if ($error): ?>
        <p style="color: red; font-size: 12px; margin-bottom: 10px; text-align: center;"><?php echo boltix_clean($error); ?></p>
    <?php endif; ?>
    <form action="" method="POST">
        <div class="form-group">
            <label>Username or Email Address</label>
            <input type="text" name="identity" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <button type="submit" class="btn-primary">Sign In</button>
    </form>
    <p style="text-align: center; margin-top: 15px; font-size: 12px;">
        Don't have an account? <a href="<?php echo $site_url; ?>/signup">Register here</a>
    </p>
</div>
