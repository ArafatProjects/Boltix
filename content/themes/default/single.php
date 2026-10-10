<?php
if (!defined('BOLTIX_RUNNING')) die('Direct access forbidden.');

// If post is a Linked Article, redirect outward to original publisher immediately
if ($article['post_type'] === 'linked' && !empty($article['external_url'])) {
    header("Location: " . $article['external_url']);
    exit;
}
?>

<div class="portal-container" style="grid-template-columns: 1fr 300px;">
    <main class="single-article-container">
        <!-- Article Structure: [Title] -> [Author/Meta] -> [Media] -> [Body] -->
        <h1 class="single-title"><?php echo boltix_clean($article['title']); ?></h1>
        <div class="single-meta">
            <span>By <strong><?php echo boltix_clean($article['author_name']); ?></strong></span> | 
            <span>Published on <?php echo date('F d, Y', strtotime($article['created_at'])); ?></span>
        </div>

        <?php if (!empty($article['thumbnail'])): ?>
            <div class="single-media">
                <img src="<?php echo boltix_clean($article['thumbnail']); ?>" alt="">
            </div>
        <?php endif; ?>

        <div class="single-content">
            <?php echo $article['content']; // Native published HTML markup ?>
        </div>
    </main>

    <aside class="right-sidebar">
        <div class="widget-box">
            <h4 class="widget-title">Related Stories</h4>
            <?php $related = get_articles($pdo, $article['category_id'], 5, 0); ?>
            <ul style="list-style: none;">
                <?php foreach ($related as $rel): ?>
                    <li style="margin-bottom: 8px;">
                        <a href="<?php echo $site_url; ?>/article/<?php echo $rel['slug']; ?>"><?php echo boltix_clean($rel['title']); ?></a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </aside>
</div>
