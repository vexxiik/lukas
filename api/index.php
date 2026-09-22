<?php
// Vercel Single-Entrypoint Router pro PHP
// Přepne pracovní adresář do kořenové složky (aby fungovaly relativní cesty jako require_once '../db.php')
chdir(__DIR__ . '/../');

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Pokud uživatel navštíví hlavní stranu
if ($uri === '/' || $uri === '') {
    $uri = '/index.php';
}

$file = __DIR__ . '/..' . $uri;

// Pokud soubor existuje a je to PHP soubor, načti ho
if (file_exists($file) && is_file($file) && pathinfo($file, PATHINFO_EXTENSION) === 'php') {
    require $file;
} else {
    // Pokud soubor neexistuje nebo to není PHP, vrátíme 404
    http_response_code(404);
    echo "404 Not Found - Soubor neexistuje";
}
