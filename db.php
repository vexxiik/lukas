<?php

// Aiven.io databáze
$host = getenv('DB_HOST') ?: 'mysql-36c5d8dc-vexx-6ac1.d.aivencloud.com';
$port = getenv('DB_PORT') ?: 25115;
$dbname = getenv('DB_NAME') ?: 'defaultdb';
$username = getenv('DB_USER') ?: 'avnadmin';

// Na Vercelu se heslo načítá z Environment Variables z bezpečnostních důvodů.
// GitHub by navíc nahrání hesla přímo v kódu zablokoval.
$password = getenv('DB_PASS'); 

try {
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::MYSQL_ATTR_SSL_CA => true,
        PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false
    ];
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname;charset=utf8", $username, $password, $options);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

?>