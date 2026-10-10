<?php
if (!defined('BOLTIX_RUNNING')) die('Direct access forbidden.');
?>
<div class="portal-container" style="grid-template-columns: 1fr 300px;">
    <main class="single-article-container">
        <h1 class="single-title"><?php echo boltix_clean($page_data['title']); ?></h1>
        <div class="single-content">
            <?php echo $page_data['content']; ?>
        </div>
    </main>

    <aside class="right-sidebar">
        <div class="widget-box">
            <h4 class="widget-title">Information</h4>
            <p style="font-size: 12px; color: #666;">Official portal page for <?php echo boltix_clean(get_option($pdo, 'site_title', 'Boltix')); ?>.</p>
        </div>
    </aside>
</div>
