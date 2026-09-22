<?php
require_once 'db.php';

$token = $_GET['token'] ?? '';

if (empty($token)) {
    die("Neplatný nebo chybějící token.");
}

// Find reservation
$stmt = $pdo->prepare("SELECT * FROM reservations WHERE verification_token = ? AND status = 'unverified'");
$stmt->execute([$token]);
$reservation = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$reservation) {
    die("Neplatný token nebo rezervace již byla potvrzena.");
}

// Update status to pending and clear token
$updateStmt = $pdo->prepare("UPDATE reservations SET status = 'pending', verification_token = NULL WHERE id = ?");
$updateStmt->execute([$reservation['id']]);

// Send admin email
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

$mail = new PHPMailer\PHPMailer\PHPMailer(true);
try {
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'terracomplexap@gmail.com';
    $mail->Password = 'qyrp uwjj ajjl doza';
    $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_SMTPS;
    $mail->Port = 465;
    $mail->CharSet = 'UTF-8';

    $mail->setFrom('terracomplexap@gmail.com', 'Terra Complex Rezervace');
    $mail->addReplyTo($reservation['email'], $reservation['name']);
    $mail->addAddress('terracomplexap@gmail.com');

    $mail->isHTML(true);
    $mail->Subject = 'Nová rezervace apartmánu (Terra Complex)';

    $mail->Body = "
    <!DOCTYPE html>
    <html lang='cs'>
    <head>
        <meta charset='UTF-8'>
        <style>
            body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; background-color: #f8fafc; margin: 0; padding: 0; }
            .email-container { max-width: 600px; margin: 40px auto; background-color: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); border: 1px solid #e2e8f0; }
            .header { background-color: #0d9488; color: #ffffff; padding: 30px 40px; text-align: center; }
            .header h1 { margin: 0; font-size: 24px; font-weight: 700; letter-spacing: 0.5px; }
            .content { padding: 40px; }
            .content h2 { color: #0f172a; font-size: 20px; margin-top: 0; margin-bottom: 24px; border-bottom: 2px solid #f1f5f9; padding-bottom: 12px; }
            .info-group { margin-bottom: 20px; }
            .info-label { font-size: 13px; text-transform: uppercase; color: #64748b; font-weight: 600; margin-bottom: 4px; display: block; }
            .info-value { font-size: 16px; color: #1e293b; font-weight: 500; margin: 0; background-color: #f8fafc; padding: 12px 16px; border-radius: 8px; border: 1px solid #e2e8f0; }
            .date-row { display: flex; gap: 20px; margin-bottom: 24px; }
            .date-col { flex: 1; min-width: 200px; }
            .footer { background-color: #f1f5f9; color: #64748b; text-align: center; padding: 24px; font-size: 14px; border-top: 1px solid #e2e8f0; }
            .action-btn { display: inline-block; background-color: #0f172a; color: #ffffff; text-decoration: none; padding: 12px 24px; border-radius: 6px; font-weight: 600; margin-top: 10px; }
        </style>
    </head>
    <body>
        <div class='email-container'>
            <div class='header'>
                <h1>Ověřená žádost o rezervaci</h1>
            </div>
            <div class='content'>
                <h2>Detaily klienta</h2>
                <div class='info-group'>
                    <span class='info-label'>Jméno a příjmení</span>
                    <p class='info-value'>" . htmlspecialchars($reservation['name']) . "</p>
                </div>
                <div class='info-group'>
                    <span class='info-label'>Telefonní číslo</span>
                    <p class='info-value'>" . htmlspecialchars($reservation['phone']) . "</p>
                </div>
                <div class='info-group'>
                    <span class='info-label'>E-mailová adresa</span>
                    <p class='info-value'>" . htmlspecialchars($reservation['email']) . "</p>
                </div>
                <h2 style='margin-top: 32px;'>Požadovaný termín</h2>
                <div class='date-row'>
                    <div class='date-col'>
                        <span class='info-label'>Od</span>
                        <p class='info-value' style='border-color: #5eead4; background-color: #f0fdfa;'>" . htmlspecialchars($reservation['start_date']) . "</p>
                    </div>
                    <div class='date-col'>
                        <span class='info-label'>Do</span>
                        <p class='info-value' style='border-color: #fda4af; background-color: #fff1f2;'>" . htmlspecialchars($reservation['end_date']) . "</p>
                    </div>
                </div>
            </div>
            <div class='footer'>
                <p style='margin: 0 0 12px 0;'>Klient právě potvrdil svůj e-mail. Tato žádost čeká na zpracování.</p>
                <a href='https://terracomplexapartment.com/admin/login.php' class='action-btn'>Přejít do administrace</a>
            </div>
        </div>
    </body>
    </html>
    ";

    $mail->AltBody = "Ověřená žádost o rezervaci\n\nDetaily klienta:\nJméno a příjmení: " . $reservation['name'] . "\nTelefonní číslo: " . $reservation['phone'] . "\nE-mailová adresa: " . $reservation['email'] . "\n\nPožadovaný termín:\nOd: " . $reservation['start_date'] . "\nDo: " . $reservation['end_date'] . "\n\nKlient právě potvrdil svůj e-mail. Tato žádost čeká na zpracování.\nPřejděte do administrace pro schválení nebo zamítnutí rezervace.";

    $mail->send();
} catch (Exception $e) {
}

// Redirect to success msg saying "Waiting for admin approval"
header("Location: index.php?success=1#success-msg");
exit;
?>