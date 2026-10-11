<?php
/**
 * Boltix Request Router & Front Controller Dispatcher
 */

if (!defined('BOLTIX_RUNNING')) {
    define('BOLTIX_RUNNING', true);
}

/**
 * Resolve active route and load appropriate theme template file
 */
function boltix_dispatch_route($pdo) {
    // Determine route from $_GET['route'] set by .htaccess
    $route = isset($_GET['route']) ? trim($_GET['route'], '/') : '';
    $theme_dir = 'content/themes/default/';
    $site_url = 'http://' . $_SERVER['HTTP_HOST'];

    // Define template and data variables for controller view
    $page_template = 'index.php';
    $page_data = null;
    $article = null;
    $cat_slug = '';

    if ($route === '' || $route === 'index.php') {
        $page_template = 'index.php';
    } elseif (strpos($route, 'search') === 0) {
        $page_template = 'search.php';
    } elseif (strpos($route, 'article/') === 0) {
        $page_template = 'single.php';
        $slug = str_replace('article/', '', $route);
        $stmt = $pdo->prepare("SELECT * FROM posts WHERE slug = ?");
        $stmt->execute([$slug]);
        $article = $stmt->fetch();
        if (!$article) {
            $page_template = '404.php';
        }
    } elseif (strpos($route, 'category/') === 0) {
        $page_template = 'category.php';
        $cat_slug = str_replace('category/', '', $route);
    } elseif ($route === 'login') {
        $page_template = 'login.php';
    } elseif ($route === 'signup') {
        $page_template = 'signup.php';
    } elseif ($route === 'profile') {
        $page_template = 'profile.php';
    } else {
        // Check custom pages table
        $stmt = $pdo->prepare("SELECT * FROM pages WHERE slug = ?");
        $stmt->execute([$route]);
        $page_data = $stmt->fetch();
        if ($page_data) {
            $page_template = 'page.php';
        } else {
            $page_template = '404.php';
        }
    }

    // Pass asset paths down to views
    $theme_assets = $site_url . '/' . $theme_dir;

    // Include theme view parts
    if (file_exists($theme_dir . $page_template)) {
        include $theme_dir . 'header.php';
        include $theme_dir . $page_template;
        include $theme_dir . 'footer.php';
    } else {
        include $theme_dir . '404.php';
    }
}
?>
