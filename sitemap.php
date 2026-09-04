<?php
// sitemap.php
// Digital History - Dynamic Sitemap Generator

require_once 'includes/config.php';

// Set content type to XML
header('Content-Type: text/xml; charset=utf-8');

// Get all eras, events, people, technologies, languages
$eras = getEras();
$events = getTimelineEvents();
$people = getPeople();
$technologies = getTechnologies();
$languages = getProgrammingLanguages();

// Static pages
$staticPages = [
    '',
    'timeline',
    'computing',
    'programming',
    'internet',
    'web',
    'cybersecurity',
    'ai',
    'people',
    'museum',
    'lab',
    'games',
    'future',
    'about',
    'contact',
    'privacy',
    'terms',
    'sources',
    'cookie-policy',
    'disclaimer'
];

echo '<?xml version="1.0" encoding="UTF-8"?>';
?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    
    <!-- Static Pages -->
    <?php foreach ($staticPages as $page): ?>
    <url>
        <loc><?php echo SITE_URL . ($page ? $page . '.php' : ''); ?></loc>
        <lastmod><?php echo date('Y-m-d'); ?></lastmod>
        <changefreq><?php echo $page ? 'weekly' : 'daily'; ?></changefreq>
        <priority><?php echo $page ? '0.8' : '1.0'; ?></priority>
    </url>
    <?php endforeach; ?>
    
    <!-- Eras -->
    <?php foreach ($eras as $era): ?>
    <url>
        <loc><?php echo SITE_URL . 'era.php?slug=' . $era['slug']; ?></loc>
        <lastmod><?php echo date('Y-m-d'); ?></lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.7</priority>
    </url>
    <?php endforeach; ?>
    
    <!-- Events -->
    <?php foreach ($events as $event): ?>
    <url>
        <loc><?php echo SITE_URL . 'event.php?slug=' . $event['slug']; ?></loc>
        <lastmod><?php echo date('Y-m-d'); ?></lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.6</priority>
    </url>
    <?php endforeach; ?>
    
    <!-- People -->
    <?php foreach ($people as $person): ?>
    <url>
        <loc><?php echo SITE_URL . 'person.php?slug=' . $person['slug']; ?></loc>
        <lastmod><?php echo date('Y-m-d'); ?></lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.6</priority>
    </url>
    <?php endforeach; ?>
    
    <!-- Technologies -->
    <?php foreach ($technologies as $tech): ?>
    <url>
        <loc><?php echo SITE_URL . 'technology.php?slug=' . $tech['slug']; ?></loc>
        <lastmod><?php echo date('Y-m-d'); ?></lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.6</priority>
    </url>
    <?php endforeach; ?>
    
    <!-- Programming Languages -->
    <?php foreach ($languages as $lang): ?>
    <url>
        <loc><?php echo SITE_URL . 'language.php?slug=' . $lang['slug']; ?></loc>
        <lastmod><?php echo date('Y-m-d'); ?></lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.6</priority>
    </url>
    <?php endforeach; ?>
    
</urlset>