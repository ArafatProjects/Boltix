<?php
/**
 * Boltix Core Functions & Database Utilities
 */

if (!defined('BOLTIX_RUNNING')) {
    define('BOLTIX_RUNNING', true);
}

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Sanitize output string
 */
function boltix_clean($data) {
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

/**
 * Check if user is logged in
 */
function is_logged_in() {
    return isset($_SESSION['user_id']);
}

/**
 * Get current logged-in user record
 */
function get_current_user_data($pdo) {
    if (!is_logged_in()) return null;
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    return $stmt->fetch();
}

/**
 * Get dynamic site option from database
 */
function get_option($pdo, $key, $default = '') {
    try {
        $stmt = $pdo->prepare("SELECT option_value FROM options WHERE option_key = ?");
        $stmt->execute([$key]);
        $val = $stmt->fetchColumn();
        return $val !== false ? $val : $default;
    } catch (\PDOException $e) {
        return $default;
    }
}

/**
 * Get custom menu links configured in Admin Panel
 */
function get_menu_links($pdo, $location) {
    try {
        $stmt = $pdo->prepare("SELECT * FROM menu_links WHERE location = ? ORDER BY position ASC");
        $stmt->execute([$location]);
        return $stmt->fetchAll();
    } catch (\PDOException $e) {
        return [];
    }
}

/**
 * Get all active portal categories
 */
function get_categories($pdo) {
    try {
        $stmt = $pdo->query("SELECT * FROM categories ORDER BY name ASC");
        return $stmt->fetchAll();
    } catch (\PDOException $e) {
        return [];
    }
}

/**
 * Fetch articles with pagination and category filtering
 */
function get_articles($pdo, $category_id = null, $limit = 8, $offset = 0) {
    try {
        if ($category_id) {
            $stmt = $pdo->prepare("SELECT * FROM posts WHERE category_id = ? ORDER BY created_at DESC LIMIT ? OFFSET ?");
            $stmt->bindValue(1, (int)$category_id, PDO::PARAM_INT);
            $stmt->bindValue(2, (int)$limit, PDO::PARAM_INT);
            $stmt->bindValue(3, (int)$offset, PDO::PARAM_INT);
            $stmt->execute();
        } else {
            $stmt = $pdo->prepare("SELECT * FROM posts ORDER BY created_at DESC LIMIT ? OFFSET ?");
            $stmt->bindValue(1, (int)$limit, PDO::PARAM_INT);
            $stmt->bindValue(2, (int)$offset, PDO::PARAM_INT);
            $stmt->execute();
        }
        return $stmt->fetchAll();
    } catch (\PDOException $e) {
        return [];
    }
}

/**
 * Get total article count for pagination
 */
function get_articles_count($pdo, $category_id = null) {
    try {
        if ($category_id) {
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM posts WHERE category_id = ?");
            $stmt->execute([$category_id]);
        } else {
            $stmt = $pdo->query("SELECT COUNT(*) FROM posts");
        }
        return (int)$stmt->fetchColumn();
    } catch (\PDOException $e) {
        return 0;
    }
}

/**
 * Fetch top 5 carousel articles for homepage header
 */
function get_carousel_articles($pdo) {
    try {
        $stmt = $pdo->query("SELECT * FROM posts ORDER BY created_at DESC LIMIT 5");
        return $stmt->fetchAll();
    } catch (\PDOException $e) {
        return [];
    }
}

/**
 * Get trending search topics for sidebar
 */
function get_trending_topics($pdo) {
    try {
        $stmt = $pdo->query("SELECT query FROM search_logs GROUP BY query ORDER BY COUNT(query) DESC LIMIT 10");
        $topics = $stmt->fetchAll();
        if (empty($topics)) {
            // Default trending topics if log is empty
            return [
                ['query' => 'FIFA World Cup'],
                ['query' => 'Technology Trends 2026'],
                ['query' => 'Global Space Missions'],
                ['query' => 'Artificial Intelligence'],
                ['query' => 'Premier League Highlights']
            ];
        }
        return $topics;
    } catch (\PDOException $e) {
        return [['query' => 'FIFA World Cup'], ['query' => 'Technology Trends']];
    }
}
?>
