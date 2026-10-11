<?php
if (!defined('BOLTIX_RUNNING')) die('Direct access forbidden.');

if (!is_logged_in()) {
    header("Location: " . $site_url . "/login");
    exit;
}

$user = get_current_user_data($pdo);
$msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_profile'])) {
        $full_name = trim($_POST['full_name']);
        $phone     = trim($_POST['phone']);
        $avatar    = trim($_POST['avatar']);

        $stmt = $pdo->prepare("UPDATE users SET full_name = ?, phone = ?, avatar = ? WHERE id = ?");
        $stmt->execute([$full_name, $phone, $avatar, $user['id']]);
        $msg = "Account details updated successfully.";
        $user = get_current_user_data($pdo);
    } elseif (isset($_POST['delete_account'])) {
        $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
        $stmt->execute([$user['id']]);
        unset($_SESSION['user_id']);
        header("Location: " . $site_url);
        exit;
    }
}
?>

<div class="form-card" style="max-width: 600px;">
    <h2 class="form-title">Account Settings</h2>
    <?php if ($msg): ?>
        <p style="color: green; font-size: 12px; margin-bottom: 10px; text-align: center;"><?php echo boltix_clean($msg); ?></p>
    <?php endif; ?>

    <div style="text-align: center; margin-bottom: 20px;">
        <img src="<?php echo !empty($user['avatar']) ? boltix_clean($user['avatar']) : $site_url . '/content/themes/default/assets/avatar.png'; ?>" style="width: 80px; height: 80px; border-radius: 50%; object-fit: cover; border: 2px solid var(--boltix-green);">
        <h3 style="margin-top: 5px;"><?php echo boltix_clean($user['username']); ?></h3>
        <p style="font-size: 11px; color: #666;"><?php echo boltix_clean($user['email']); ?></p>
    </div>

    <form action="" method="POST">
        <div class="form-group">
            <label>Full Name</label>
            <input type="text" name="full_name" class="form-control" value="<?php echo boltix_clean($user['full_name']); ?>" required>
        </div>
        <div class="form-group">
            <label>Phone Number</label>
            <input type="text" name="phone" class="form-control" value="<?php echo boltix_clean($user['phone']); ?>">
        </div>
        <div class="form-group">
            <label>Profile Picture URL</label>
            <input type="text" name="avatar" class="form-control" value="<?php echo boltix_clean($user['avatar']); ?>">
        </div>
        <button type="submit" name="update_profile" class="btn-primary" style="margin-bottom: 15px;">Save Changes</button>
    </form>

    <div style="border-top: 1px solid #eee; padding-top: 15px; display: flex; justify-content: space-between; align-items: center;">
        <a href="<?php echo $site_url; ?>/logout.php" style="color: #666; font-weight: bold;"><i class="fa fa-sign-out-alt"></i> Logout</a>
        <form action="" method="POST" onsubmit="return confirm('Are you sure you want to permanently delete your Boltix Account? This action cannot be undone.');">
            <button type="submit" name="delete_account" class="btn-danger"><i class="fa fa-trash"></i> Delete Account</button>
        </form>
    </div>
</div>
