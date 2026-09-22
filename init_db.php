<?php
$host = 'localhost';
$username = 'root';
$password = '';

try {
    // Connect without database first
    $pdo = new PDO("mysql:host=$host;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Read SQL file
    $sql = file_get_contents(__DIR__ . '/setup.sql');
    
    // Execute SQL
    $pdo->exec($sql);
    echo "Database setup completed successfully.\n";
} catch(PDOException $e) {
    echo "Failed to set up database: " . $e->getMessage() . "\n";
}
?>
