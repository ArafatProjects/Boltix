<?php
if (!defined('BOLTIX_RUNNING')) die('Direct access forbidden.');
?>
<div class="portal-container" style="grid-template-columns: 1fr;">
    <div style="background: #fff; border: 1px solid var(--boltix-border); padding: 40px; text-align: center; border-radius: 3px;">
        <h1 style="font-size: 48px; color: var(--boltix-green);">404</h1>
        <h2>Page Not Found</h2>
        <p style="margin: 15px 0;">The page or article you are looking for does not exist or has been moved.</p>
        <a href="<?php echo $site_url; ?>" class="btn-primary" style="display: inline-block; width: auto; padding: 8px 20px;">Return to Homepage</a>
    </div>
</div>
