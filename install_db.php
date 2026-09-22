<?php
require_once __DIR__ . '/db.php';
$sql = file_get_contents(__DIR__ . '/setup.sql');
try {
    $pdo->exec("DROP TABLE IF EXISTS reservations;");
    $pdo->exec($sql);
    echo "<h1>Struktura databaze byla uspesne vytvorena! Vse bezi na 155%!</h1>";
    echo "<p><a href='/'>Zpet na web</a></p>";
} catch(PDOException $e) {
    echo "Chyba pri vytvareni tabulek: " . $e->getMessage();
}
