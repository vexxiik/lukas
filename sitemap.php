<?php
header("Content-Type: application/xml; charset=utf-8");
echo '<?xml version="1.0" encoding="UTF-8"?>';

$baseUrl = 'https://terracomplexapartment.com';
$languages = ['cs', 'en', 'bg'];
$pages = ['index.php', 'galerie.php'];

// Define modification time (you could make this dynamic based on filemtime if needed)
$lastMod = date('c', filemtime(__FILE__));
?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xhtml="http://www.w3.org/1999/xhtml">
    <?php foreach ($pages as $page): ?>
        <?php foreach ($languages as $lang): ?>
            <?php
            $pageUrl = $baseUrl . '/' . ($page === 'index.php' ? '' : $page) . '?lang=' . $lang;
            // If index.php with cs language, we can also consider the bare root as cs for x-default but let's be explicit
            ?>
            <url>
                <loc>
                    <?= htmlspecialchars($pageUrl) ?>
                </loc>
                <lastmod>
                    <?= $lastMod ?>
                </lastmod>
                <changefreq>weekly</changefreq>
                <priority>
                    <?= $page === 'index.php' ? '1.0' : '0.8' ?>
                </priority>

                <!-- Hreflang alternates -->
                <?php foreach ($languages as $altLang): ?>
                    <?php $altUrl = $baseUrl . '/' . ($page === 'index.php' ? '' : $page) . '?lang=' . $altLang; ?>
                    <xhtml:link rel="alternate" hreflang="<?= $altLang ?>" href="<?= htmlspecialchars($altUrl) ?>" />
                <?php endforeach; ?>
                <!-- Default language fallback -->
                <xhtml:link rel="alternate" hreflang="x-default"
                    href="<?= htmlspecialchars($baseUrl . '/' . ($page === 'index.php' ? '' : $page) . '?lang=cs') ?>" />
            </url>
        <?php endforeach; ?>
    <?php endforeach; ?>
</urlset>