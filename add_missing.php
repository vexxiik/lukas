<?php
require_once __DIR__ . '/db.php';

$reservations = [
    [
        'name' => 'Frank Mäke Booking',
        'email' => 'unknown@example.com',
        'phone' => 'unknown',
        'start_date' => '2026-07-18',
        'end_date' => '2026-07-24',
        'status' => 'approved'
    ],
    [
        'name' => 'shabtai shmueli Booking',
        'email' => 'lukas.sok@seznam.cz',
        'phone' => '+420737921581',
        'start_date' => '2026-12-19',
        'end_date' => '2026-12-23',
        'status' => 'approved'
    ]
];

try {
    foreach ($reservations as $res) {
        $stmt = $pdo->prepare("INSERT INTO reservations (name, email, phone, start_date, end_date, status) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $res['name'],
            $res['email'],
            $res['phone'],
            $res['start_date'],
            $res['end_date'],
            $res['status']
        ]);
    }
    echo "<h1>Rezervace úspěšně vloženy do databáze!</h1>";
} catch (PDOException $e) {
    echo "Chyba: " . $e->getMessage();
}
