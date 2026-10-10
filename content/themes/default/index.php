<?php
if (!defined('BOLTIX_RUNNING')) die('Direct access forbidden.');

$active_cat = isset($_GET['cat']) ? (int)$_GET['cat'] : null;
$page_num = isset($_GET['p']) ? (int)$_GET['p'] : 1;
$limit = 8;
$offset = ($page_num - 1) * $limit;

$carousel_posts = get_carousel_articles($pdo);
$articles = get_articles($pdo, $active_cat, $limit, $offset);
$total_articles = get_articles_count($pdo, $active_cat);
$total_pages = ceil($total_articles / $limit);
?>

<div class="portal-container">
    <!-- Left Sidebar: Categories list with icons -->
    <aside class="left-sidebar">
        <ul class="cat-list">
            <li class="cat-item"><a href="<?php echo $site_url; ?>"><span class="cat-icon"><i class="fa fa-home"></i></span> All Stories</a></li>
            <?php foreach ($categories as $cat): ?>
                <li class="cat-item">
                    <a href="<?php echo $site_url; ?>/?cat=<?php echo $cat['id']; ?>">
                        <span class="cat-icon"><i class="fa <?php echo !empty($cat['icon']) ? boltix_clean($cat['icon']) : 'fa-folder'; ?>"></i></span>
                        <?php echo boltix_clean($cat['name']); ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </aside>

    <!-- Center Column Feed & Carousel -->
    <main class="center-content">
        <!-- 8-Second Moving Hero Article Carousel -->
        <?php if (!empty($carousel_posts) && !$active_cat && $page_num === 1): ?>
            <div class="carousel-box">
                <?php foreach ($carousel_posts as $idx => $c_post): ?>
                    <div class="carousel-slide <?php echo $idx === 0 ? 'active' : ''; ?>">
                        <img src="<?php echo !empty($c_post['thumbnail']) ? boltix_clean($c_post['thumbnail']) : $site_url . '/content/themes/default/assets/placeholder.jpg'; ?>" alt="">
                        <div class="carousel-caption">
                            <h2>
                                <?php if ($c_post['post_type'] === 'linked'): ?>
                                    <a href="<?php echo boltix_clean($c_post['external_url']); ?>" target="_blank" rel="noopener"><?php echo boltix_clean($c_post['title']); ?> <i class="fa fa-external-link-alt" style="font-size: 12px;"></i></a>
                                <?php else: ?>
                                    <a href="<?php echo $site_url; ?>/article/<?php echo $c_post['slug']; ?>"><?php echo boltix_clean($c_post['title']); ?></a>
                                <?php endif; ?>
                            </h2>
                        </div>
                    </div>
                <?php endforeach; ?>
                <div class="carousel-thumbs">
                    <?php foreach ($carousel_posts as $idx => $c_post): ?>
                        <div class="thumb-item <?php echo $idx === 0 ? 'active' : ''; ?>">
                            <?php echo boltix_clean($c_post['title']); ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

        <!-- Category Horizontal Filter Bar -->
        <div class="category-tab-bar">
            <a href="<?php echo $site_url; ?>" class="<?php echo !$active_cat ? 'active' : ''; ?>">All Stories</a>
            <?php foreach ($categories as $cat): ?>
                <a href="<?php echo $site_url; ?>/?cat=<?php echo $cat['id']; ?>" class="<?php echo $active_cat === (int)$cat['id'] ? 'active' : ''; ?>"><?php echo boltix_clean($cat['name']); ?></a>
            <?php endforeach; ?>
        </div>

        <!-- Articles Feed List (Own + Linked) -->
        <div class="articles-feed">
            <?php if (!empty($articles)): ?>
                <?php foreach ($articles as $art): ?>
                    <article class="article-card">
                        <img src="<?php echo !empty($art['thumbnail']) ? boltix_clean($art['thumbnail']) : $site_url . '/content/themes/default/assets/placeholder.jpg'; ?>" class="article-thumb" alt="">
                        <div class="article-info">
                            <h3 class="article-title">
                                <?php if ($art['post_type'] === 'linked'): ?>
                                    <a href="<?php echo boltix_clean($art['external_url']); ?>" target="_blank" rel="noopener"><?php echo boltix_clean($art['title']); ?> <i class="fa fa-external-link-alt" style="font-size: 11px;"></i></a>
                                <?php else: ?>
                                    <a href="<?php echo $site_url; ?>/article/<?php echo $art['slug']; ?>"><?php echo boltix_clean($art['title']); ?></a>
                                <?php endif; ?>
                            </h3>
                            <div class="article-meta">
                                <span><?php echo date('M d, Y', strtotime($art['created_at'])); ?></span> | 
                                <span><?php echo $art['post_type'] === 'linked' ? 'External Source' : 'Boltix Original'; ?></span>
                            </div>
                            <p class="article-snippet"><?php echo boltix_clean(mb_strimwidth(strip_tags($art['content']), 0, 160, '...')); ?></p>
                        </div>
                    </article>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No articles found in this section.</p>
            <?php endif; ?>
        </div>

        <!-- 8-Item Pagination Bar -->
        <?php if ($total_pages > 1): ?>
            <div class="pagination-bar">
                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <a href="<?php echo $site_url; ?>/?<?php echo $active_cat ? 'cat='.$active_cat.'&' : ''; ?>p=<?php echo $i; ?>" class="<?php echo $page_num === $i ? 'active' : ''; ?>"><?php echo $i; ?></a>
                <?php endfor; ?>
            </div>
        <?php endif; ?>
    </main>

    <!-- Right Sidebar Widgets -->
    <aside class="right-sidebar">
        <div class="widget-box">
            <h4 class="widget-title">Trending Topics</h4>
            <?php $trending = get_trending_topics($pdo); ?>
            <ol style="padding-left: 20px;">
                <?php foreach ($trending as $topic): ?>
                    <li style="margin-bottom: 6px;"><a href="<?php echo $site_url; ?>/search?q=<?php echo urlencode($topic['query']); ?>"><?php echo boltix_clean($topic['query']); ?></a></li>
                <?php endforeach; ?>
            </ol>
        </div>

        <!-- Custom HTML Sidebar Container from Admin Panel -->
        <div class="widget-box">
            <?php echo get_option($pdo, 'sidebar_html_widget', '<p>Custom sidebar widget area configured from Admin Panel.</p>'); ?>
        </div>
    </aside>
</div>
