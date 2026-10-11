<?php
/**
 * Boltix Wikipedia Knowledge Panel Entity Fetcher
 */

if (!defined('BOLTIX_RUNNING')) {
    define('BOLTIX_RUNNING', true);
}

/**
 * Fetch entity Knowledge Panel data from Wikipedia REST API
 */
function fetch_wikipedia_knowledge_panel($query) {
    if (empty($query)) return null;

    // Clean and format query for Wikipedia REST endpoint
    $formatted_query = str_replace(' ', '_', ucwords(trim($query)));
    $endpoint = "https://en.wikipedia.org/api/rest_v1/page/summary/" . urlencode($formatted_query);

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $endpoint);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERAGENT, 'BoltixPortalEngine/1.0 (AdminPortal)');
    curl_setopt($ch, CURLOPT_TIMEOUT, 4);
    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($http_code === 200 && $response) {
        $data = json_decode($response, true);
        
        // Ensure it's a valid article summary and not a disambiguation page
        if (isset($data['title']) && isset($data['extract']) && (!isset($data['type']) || $data['type'] !== 'disambiguation')) {
            return [
                'title'       => $data['title'],
                'description' => isset($data['description']) ? $data['description'] : 'Encyclopedic Reference Entity',
                'extract'     => $data['extract'],
                'image'       => isset($data['thumbnail']['source']) ? $data['thumbnail']['source'] : '',
                'wiki_url'    => isset($data['content_urls']['desktop']['page']) ? $data['content_urls']['desktop']['page'] : 'https://en.wikipedia.org'
            ];
        }
    }

    // Secondary fallback: Try searching via Wikipedia OpenSearch if direct REST page summary fails
    return fetch_wikipedia_opensearch_panel($query);
}

/**
 * Fallback OpenSearch entity resolver
 */
function fetch_wikipedia_opensearch_panel($query) {
    $encoded = urlencode($query);
    $endpoint = "https://en.wikipedia.org/w/api.php?action=opensearch&search={$encoded}&limit=1&namespace=0&format=json";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $endpoint);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERAGENT, 'BoltixPortalEngine/1.0');
    curl_setopt($ch, CURLOPT_TIMEOUT, 3);
    $response = curl_exec($ch);
    curl_close($ch);

    if ($response) {
        $json = json_decode($response, true);
        if (isset($json[1][0]) && isset($json[3][0])) {
            $title = $json[1][0];
            $url = $json[3][0];
            $desc = isset($json[2][0]) && !empty($json[2][0]) ? $json[2][0] : 'Verified encyclopedic entry on Wikipedia.';
            
            return [
                'title'       => $title,
                'description' => 'Featured Web Entity',
                'extract'     => $desc,
                'image'       => '',
                'wiki_url'    => $url
            ];
        }
    }

    return null;
}
?>
