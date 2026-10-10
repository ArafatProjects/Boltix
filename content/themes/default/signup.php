<?php
if (!defined('BOLTIX_RUNNING')) die('Direct access forbidden.');

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username  = trim($_POST['username']);
    $full_name = trim($_POST['full_name']);
    $email     = trim($_POST['email']);
    $phone     = trim($_POST['phone']);
    $password  = trim($_POST['password']);

    // Validate duplicate user
    $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
    $stmt->execute([$username, $email]);
    if ($stmt->fetch()) {
        $error = "Username or Email is already registered.";
    } else {
        $hash = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $pdo->prepare("INSERT INTO users (username, full_name, email, phone, password_hash, created_at) VALUES (?, ?, ?, ?, ?, NOW())");
        $stmt->execute([$username, $full_name, $email, $phone, $hash]);
        
        $_SESSION['user_id'] = $pdo->lastInsertId();
        header("Location: " . $site_url . "/profile");
        exit;
    }
}
?>

<div class="form-card">
    <h2 class="form-title">Create Boltix Account</h2>
    <?php if ($error): ?>
        <p style="color: red; font-size: 12px; margin-bottom: 10px; text-align: center;"><?php echo boltix_clean($error); ?></p>
    <?php endif; ?>
    <form action="" method="POST">
        <div class="form-group">
            <label>Username</label>
            <input type="text" name="username" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Full Name</label>
            <input type="text" name="full_name" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Email Address (Gmail, Outlook, etc.)</label>
            <input type="email" name="email" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Phone Number</label>
            <input type="text" name="phone" class="form-control">
        </div>
        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <button type="submit" class="btn-primary">Create Account</button>
    </form>
</div>
