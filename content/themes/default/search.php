<?php
if (!defined('BOLTIX_RUNNING')) die('Direct access forbidden.');

$query = isset($_GET['q']) ? trim($_GET['q']) : '';
$tab = isset($_GET['tab']) ? $_GET['tab'] : 'web';

$search_data = perform_web_search($pdo, $query, $tab);
$kp_data = fetch_wikipedia_knowledge_panel($query);
?>

<div class="portal-container" style="grid-template-columns: 1fr;">
    <!-- Multi-Tab Search Filter Bar -->
    <div class="search-tabs">
        <a href="<?php echo $site_url; ?>/search?q=<?php echo urlencode($query); ?>&tab=web" class="<?php echo $tab==='web'?'active':''; ?>">Web</a>
        <a href="<?php echo $site_url; ?>/search?q=<?php echo urlencode($query); ?>&tab=images" class="<?php echo $tab==='images'?'active':''; ?>">Images</a>
        <a href="<?php echo $site_url; ?>/search?q=<?php echo urlencode($query); ?>&tab=videos" class="<?php echo $tab==='videos'?'active':''; ?>">Videos</a>
        <a href="<?php echo $site_url; ?>/search?q=<?php echo urlencode($query); ?>&tab=news" class="<?php echo $tab==='news'?'active':''; ?>">News</a>
    </div>

    <div class="search-results-wrapper">
        <!-- Main Search Results Column -->
        <div class="search-results-list">
            <?php if (!empty($query)): ?>
                <p style="color: #666; font-size: 12px; margin-bottom: 15px;">Showing results for <strong><?php echo boltix_clean($query); ?></strong></p>
                
                <?php if ($tab === 'web'): ?>
                    <?php if (!empty($search_data['results'])): ?>
                        <?php foreach ($search_data['results'] as $res): ?>
                            <div class="search-item">
                                <div class="search-item-url"><?php echo boltix_clean($res['url']); ?></div>
                                <h3 class="search-item-title"><a href="<?php echo boltix_clean($res['url']); ?>" target="_blank" rel="noopener"><?php echo boltix_clean($res['title']); ?></a></h3>
                                <div class="search-item-snippet"><?php echo boltix_clean($res['snippet']); ?></div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>No results found matching your query.</p>
                    <?php endif; ?>
                <?php elseif ($tab === 'images'): ?>
                    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); gap: 10px;">
                        <?php foreach ($search_data['results'] as $img): ?>
                            <a href="<?php echo boltix_clean($img['url']); ?>" target="_blank" rel="noopener">
                                <img src="<?php echo boltix_clean($img['image_url']); ?>" style="width: 100%; height: 120px; object-fit: cover; border-radius: 2px;">
                            </a>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            <?php else: ?>
                <p>Please enter a keyword to search.</p>
            <?php endif; ?>
        </div>

        <!-- Conditional Wikipedia Knowledge Panel Sidebar (Famous Persons / Entity Trigger) -->
        <div class="knowledge-panel-area">
            <?php if ($kp_data && !empty($kp_data['title'])): ?>
                <div class="kp-card">
                    <?php if (!empty($kp_data['image'])): ?>
                        <img src="<?php echo boltix_clean($kp_data['image']); ?>" class="kp-img" alt="">
                    <?php endif; ?>
                    <h2 class="kp-title"><?php echo boltix_clean($kp_data['title']); ?></h2>
                    <div class="kp-subtitle"><?php echo boltix_clean($kp_data['description']); ?></div>
                    <p class="kp-desc"><?php echo boltix_clean($kp_data['extract']); ?></p>
                    <a href="<?php echo boltix_clean($kp_data['wiki_url']); ?>" target="_blank" rel="noopener" style="font-size: 11px; font-weight: bold;">View on Wikipedia &rarr;</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
