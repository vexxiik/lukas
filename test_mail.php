<?php
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

$mail = new PHPMailer\PHPMailer\PHPMailer(true);

try {
    echo "<h1>Test odesílání e-mailů (PHPMailer)</h1>";
    echo "<pre style='background:#111; color:#0f0; padding:20px; overflow:auto;'>";
    
    $mail->SMTPDebug = 2; // 2 = messages only, 3 = messages + connection
    $mail->Debugoutput = 'html';
    
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'terracomplexap@gmail.com';
    $mail->Password = 'qyrp uwjj ajjl doza';
    $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_SMTPS;
    $mail->Port = 465;
    $mail->CharSet = 'UTF-8';

    $mail->setFrom('terracomplexap@gmail.com', 'Terra Complex Test');
    // Pošleme to na stejný e-mail, abychom otestovali, že se to doručí
    $mail->addAddress('terracomplexap@gmail.com', 'Lukas Admin');

    $mail->isHTML(true);
    $mail->Subject = 'Test ze serveru Vercel';
    $mail->Body = 'Toto je testovací zpráva pro ověření funkčnosti SMTP přes Gmail.';

    $mail->send();
    echo "\n\n=========================================\n";
    echo "VÝSLEDEK: E-MAIL BYL ÚSPĚŠNĚ ODESLÁN GMAILU!\n";
    echo "Zkontroluj schránku terracomplexap@gmail.com (i složku Spam)\n";
    echo "=========================================\n";
    echo "</pre>";
} catch (Exception $e) {
    echo "\n\n=========================================\n";
    echo "VÝSLEDEK: CHYBA PŘI ODESÍLÁNÍ!\n";
    echo "Mailer Error: " . $mail->ErrorInfo . "\n";
    echo "=========================================\n";
    echo "</pre>";
}
?>
