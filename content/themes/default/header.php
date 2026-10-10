<?php
if (!defined('BOLTIX_RUNNING')) die('Direct access forbidden.');

$logo_url = get_option($pdo, 'site_logo', '');
$site_name = get_option($pdo, 'site_title', 'Boltix');
$top_links = get_menu_links($pdo, 'top_menu');
$categories = get_categories($pdo);
$user = get_current_user_data($pdo);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? boltix_clean($page_title) . ' - ' . boltix_clean($site_name) : boltix_clean($site_name); ?></title>
    <link rel="stylesheet" href="<?php echo $theme_assets; ?>css/main.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

<!-- Custom Configurable Top Navigation Bar -->
<div class="top-nav">
    <div class="top-links">
        <?php foreach ($top_links as $link): ?>
            <a href="<?php echo boltix_clean($link['url']); ?>" target="_blank"><?php echo boltix_clean($link['title']); ?></a>
        <?php endforeach; ?>
    </div>
    <div class="user-actions">
        <?php if ($user): ?>
            <span>Welcome, <strong><?php echo boltix_clean($user['username']); ?></strong></span>
            <a href="<?php echo $site_url; ?>/profile"><i class="fa fa-user"></i> My Account</a>
            <a href="<?php echo $site_url; ?>/logout.php"><i class="fa fa-sign-out-alt"></i> Logout</a>
        <?php else: ?>
            <a href="<?php echo $site_url; ?>/login"><i class="fa fa-lock"></i> Login</a>
            <a href="<?php echo $site_url; ?>/signup"><i class="fa fa-user-plus"></i> Register</a>
        <?php endif; ?>
    </div>
</div>

<!-- Header & Search Engine Input Area -->
<header class="main-header">
    <div class="logo-container">
        <a href="<?php echo $site_url; ?>">
            <?php if (!empty($logo_url)): ?>
                <img src="<?php echo boltix_clean($logo_url); ?>" alt="<?php echo boltix_clean($site_name); ?>">
            <?php else: ?>
                <span class="logo-text"><?php echo boltix_clean($site_name); ?></span>
            <?php endif; ?>
        </a>
    </div>
    <div class="search-box-container">
        <form action="<?php echo $site_url; ?>/search" method="GET" class="search-form">
            <input type="text" name="q" class="search-input" placeholder="Search the web or articles..." value="<?php echo isset($_GET['q']) ? boltix_clean($_GET['q']) : ''; ?>" required>
            <button type="submit" class="search-btn">Search Web</button>
        </form>
    </div>
</header>
