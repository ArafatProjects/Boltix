<?php
if (!defined('BOLTIX_RUNNING')) die('Direct access forbidden.');
$footer_links = get_menu_links($pdo, 'footer_menu');
?>
<footer class="portal-footer">
    <div class="container">
        <div id="backToTop" class="back-to-top"><i class="fa fa-arrow-up"></i> Back to Top</div>
        <div class="footer-links">
            <?php foreach ($footer_links as $link): ?>
                <a href="<?php echo boltix_clean($link['url']); ?>"><?php echo boltix_clean($link['title']); ?></a>
            <?php endforeach; ?>
        </div>
        <p>&copy; <?php echo date('Y'); ?> <?php echo boltix_clean(get_option($pdo, 'site_title', 'Boltix')); ?>. All rights reserved.</p>
    </div>
</footer>

<script src="<?php echo $theme_assets; ?>js/main.js"></script>
</body>
</html>
