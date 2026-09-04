<?php
// api/search-autocomplete.php
// Digital History - Search Autocomplete API

require_once '../includes/config.php';

header('Content-Type: application/json');

$query = $_GET['q'] ?? '';
$limit = intval($_GET['limit'] ?? 10);

if (strlen($query) < 2) {
    echo json_encode([]);
    exit;
}

$searchTerm = '%' . $query . '%';

$results = [];

// Search events
$events = db()->fetchAll(
    "SELECT id, title, 'event' as type, slug FROM timeline_events 
     WHERE is_active = 1 AND title LIKE ? 
     LIMIT ?",
    [$searchTerm, $limit]
);

foreach ($events as $event) {
    $results[] = [
        'id' => $event['id'],
        'title' => $event['title'],
        'type' => 'event',
        'url' => SITE_URL . 'event.php?slug=' . $event['slug']
    ];
}

// Search people
$people = db()->fetchAll(
    "SELECT id, full_name as title, 'person' as type, slug FROM people 
     WHERE is_active = 1 AND full_name LIKE ? 
     LIMIT ?",
    [$searchTerm, $limit]
);

foreach ($people as $person) {
    $results[] = [
        'id' => $person['id'],
        'title' => $person['title'],
        'type' => 'person',
        'url' => SITE_URL . 'person.php?slug=' . $person['slug']
    ];
}

// Search technologies
$techs = db()->fetchAll(
    "SELECT id, name as title, 'technology' as type, slug FROM technologies 
     WHERE is_active = 1 AND name LIKE ? 
     LIMIT ?",
    [$searchTerm, $limit]
);

foreach ($techs as $tech) {
    $results[] = [
        'id' => $tech['id'],
        'title' => $tech['title'],
        'type' => 'technology',
        'url' => SITE_URL . 'technology.php?slug=' . $tech['slug']
    ];
}

// Search programming languages
$langs = db()->fetchAll(
    "SELECT id, name as title, 'language' as type, slug FROM programming_languages 
     WHERE is_active = 1 AND name LIKE ? 
     LIMIT ?",
    [$searchTerm, $limit]
);

foreach ($langs as $lang) {
    $results[] = [
        'id' => $lang['id'],
        'title' => $lang['title'],
        'type' => 'language',
        'url' => SITE_URL . 'language.php?slug=' . $lang['slug']
    ];
}

// Sort by relevance (exact matches first)
usort($results, function($a, $b) use ($query) {
    $aScore = stripos($a['title'], $query) === 0 ? 0 : 1;
    $bScore = stripos($b['title'], $query) === 0 ? 0 : 1;
    return $aScore - $bScore;
});

echo json_encode(array_slice($results, 0, $limit));
?>