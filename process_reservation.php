<?php
require_once 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $email = $_POST['email'] ?? '';
    $dateRange = $_POST['dateRange'] ?? '';
    // Guests input removed from frontend
    $status = 'unverified'; // Status is unverified until they click the email link
    $token = bin2hex(random_bytes(16)); // Generate verification token

    // Basic validation
    if (empty($name) || empty($phone) || empty($email) || empty($dateRange)) {
        header("Location: index.php?error=validation_failed");
        exit;
    }

    // Normalize range separator to parse correctly regardless of locale (cs: 'do/až', en: 'to', bg: 'до', defaults: '-')
    $normalizedDateRange = str_replace(
        [' to ', ' - ', ' do ', ' až ', ' до '],
        '|',
        $dateRange
    );
    $dates = explode('|', $normalizedDateRange);

    $startDate = $dates[0] ?? null;
    $endDate = $dates[1] ?? $dates[0]; // If only one day selected, end_date = start_date

    // Optional: Check if dates interleave with already approved ones
    // But since Flatpickr disabled them, it should be mostly fine. Admin can reject later anyway.

    try {
        $stmt = $pdo->prepare("INSERT INTO reservations (name, email, verification_token, phone, start_date, end_date, status) VALUES (?, ?, ?, ?, ?, ?, 'unverified')");
        $stmt->execute([$name, $email, $token, $phone, $startDate, $endDate]);

        // --- SEND EMAIL VIA PHPMAILER ---
        require 'PHPMailer/src/Exception.php';
        require 'PHPMailer/src/PHPMailer.php';
        require 'PHPMailer/src/SMTP.php';

        // 1) SEND CONFIRMATION EMAIL TO THE CLIENT
        $clientMail = new PHPMailer\PHPMailer\PHPMailer(true);
        try {
            $clientMail->isSMTP();
            $clientMail->Host = 'smtp.gmail.com';
            $clientMail->SMTPAuth = true;
            $clientMail->Username = 'terracomplexap@gmail.com';
            $clientMail->Password = 'qyrp uwjj ajjl doza'; // App Password
            $clientMail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_SMTPS;
            $clientMail->Port = 465;
            $clientMail->CharSet = 'UTF-8';

            $clientMail->setFrom('terracomplexap@gmail.com', 'Terra Complex');
            $clientMail->addReplyTo('terracomplexap@gmail.com', 'Terra Complex');
            $clientMail->addAddress($email, $name);

            $clientMail->isHTML(true);
            $clientMail->Subject = 'Příjetí žádosti o rezervaci | Terra Complex';

            $clientMail->Body = "
            <!DOCTYPE html>
            <html lang='cs'>
            <head>
                <meta charset='UTF-8'>
                <style>
                    body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; background-color: #f8fafc; color: #334155; margin: 0; padding: 0; }
                    .email-container { max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); margin-top: 40px; margin-bottom: 40px; border: 1px solid #e2e8f0; }
                    .header { background-color: #0f766e; color: #ffffff; padding: 30px 40px; text-align: center; }
                    .header h1 { margin: 0; font-size: 22px; font-weight: 700; letter-spacing: 0.5px; }
                    .content { padding: 40px; }
                    .content p { line-height: 1.6; margin-bottom: 20px; }
                    .info-box { background-color: #f8fafc; padding: 20px; border-radius: 8px; border: 1px solid #e2e8f0; margin-bottom: 20px; }
                    .info-group { margin-bottom: 15px; }
                    .info-group:last-child { margin-bottom: 0; }
                    .info-label { font-size: 13px; text-transform: uppercase; color: #64748b; font-weight: 600; display: block; margin-bottom: 4px; }
                    .info-value { font-size: 16px; color: #1e293b; font-weight: 500; margin: 0; }
                    .footer { background-color: #f1f5f9; color: #64748b; text-align: center; padding: 24px; font-size: 14px; border-top: 1px solid #e2e8f0; }
                    .btn { display: inline-block; padding: 14px 28px; background-color: #0f766e; color: white !important; font-weight: bold; text-decoration: none; border-radius: 6px; text-align: center; margin-top: 10px; }
                </style>
            </head>
            <body>
                <div class='email-container'>
                    <div class='header'>
                        <h1>Potvrzení rezervace</h1>
                    </div>
                    <div class='content'>
                        <p>Vážený/á <strong>" . htmlspecialchars($name) . "</strong>,</p>
                        <p>přijali jsme Vaši předběžnou žádost o rezervaci v apartmánu Terra Complex. <strong>Pro odeslání žádosti ke schválení prosím potvrďte svou e-mailovou adresu kliknutím na odkaz níže:</strong></p>
                        
                        <div style='text-align: center; margin: 30px 0;'>
                            <a href='https://terracomplexapartment.com/verify.php?token=" . urlencode($token) . "' class='btn'>Potvrdit můj e-mail</a>
                        </div>
                        
                        <div class='info-box'>
                            <div class='info-group'>
                                <span class='info-label'>Požadovaný termín rezervace</span>
                                <p class='info-value'>Od: " . htmlspecialchars($startDate) . " <br> Do: " . htmlspecialchars($endDate) . "</p>
                            </div>
                        </div>

                        <p>Jakmile e-mail potvrdíte, Vaše žádost bude předána ke schválení administrátorovi.</p>
                        <p>S přátelským pozdravem,</p>
                        <p><strong>Tým Terra Complex</strong></p>
                    </div>
                    <div class='footer'>
                        <p style='margin: 0;'>Toto je automatická zpráva, prosím, neodpovídejte na ni.</p>
                    </div>
                </div>
            </body>
            </html>
            ";

            $clientMail->AltBody = "Vážený/á " . $name . ",\n\npřijali jsme Vaši předběžnou žádost o rezervaci v apartmánu Terra Complex. \nPro odeslání žádosti ke schválení prosím potvrďte svou e-mailovou adresu zkopírováním následujícího odkazu do prohlížeče:\n\nhttps://terracomplexapartment.com/verify.php?token=" . urlencode($token) . "\n\nPožadovaný termín rezervace: Od " . $startDate . " Do " . $endDate . "\n\nJakmile e-mail potvrdíte, Vaše žádost bude předána ke schválení administrátorovi.\n\nS přátelským pozdravem,\n\nTým Terra Complex\n\nToto je automatická zpráva, prosím, neodpovídejte na ni.";

            $clientMail->send();
        } catch (Exception $e) {
            die("Chyba při odesílání e-mailu: " . $clientMail->ErrorInfo);
        }

        // Do NOT send the admin notification email yet. It will be sent via verify.php

        // Success - redirect customer to check email
        header("Location: index.php?success=verify#success-msg");
        exit;
    } catch (PDOException $e) {
        // Error
        header("Location: index.php?error=database_error#booking");
        exit;
    }
} else {
    // If accessed directly without POST
    header("Location: index.php");
    exit;
}
?>