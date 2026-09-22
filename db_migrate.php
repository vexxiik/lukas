<?php
require 'c:/wamp64/www/lukas/db.php';

try {
    // Add token column
    $pdo->exec("ALTER TABLE reservations ADD COLUMN verification_token VARCHAR(255) NULL AFTER email");
    echo "Added verification_token successfully.\n";
} catch (PDOException $e) {
    echo "Column verification_token might already exist: " . $e->getMessage() . "\n";
}

try {
    // Modify status enum
    $pdo->exec("ALTER TABLE reservations MODIFY COLUMN status ENUM('unverified', 'pending', 'approved', 'rejected') NOT NULL DEFAULT 'unverified'");
    echo "Modified status ENUM successfully.\n";
} catch (PDOException $e) {
    echo "Failed to modify status: " . $e->getMessage() . "\n";
}
?>