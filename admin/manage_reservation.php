<?php
$token = hash('sha256', 'admin_logged_in_secret_123!');
if (!isset($_COOKIE['admin_auth']) || $_COOKIE['admin_auth'] !== $token) {
    header("HTTP/1.1 403 Forbidden");
    exit;
}

require_once '../db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = $_POST['action'] ?? '';
    $id = $_POST['id'] ?? 0;

    if ($id && in_array($action, ['approve', 'reject', 'delete'])) {
        try {
            if ($action === 'delete') {
                $stmt = $pdo->prepare("DELETE FROM reservations WHERE id = ?");
                $stmt->execute([$id]);
            } else {
                $status = ($action === 'approve') ? 'approved' : 'rejected';
                $stmt = $pdo->prepare("UPDATE reservations SET status = ? WHERE id = ?");
                $stmt->execute([$status, $id]);

                // Fetch reservation to send notification to customer
                $stmtRes = $pdo->prepare("SELECT name, email, start_date, end_date FROM reservations WHERE id = ?");
                $stmtRes->execute([$id]);
                $reservation = $stmtRes->fetch(PDO::FETCH_ASSOC);

                if ($reservation && !empty($reservation['email'])) {
                    require '../PHPMailer/src/Exception.php';
                    require '../PHPMailer/src/PHPMailer.php';
                    require '../PHPMailer/src/SMTP.php';

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

                        $mail->setFrom('terracomplexap@gmail.com', 'Terra Complex');
                        $mail->addReplyTo('terracomplexap@gmail.com', 'Terra Complex');
                        $mail->addAddress($reservation['email']);
                        $mail->isHTML(true);

                        if ($status === 'approved') {
                            $mail->Subject = 'Rezervace schválena | Terra Complex';
                            $messageTitle = 'Vaše rezervace byla schválena!';
                            $messageBody = 'Skvělé zprávy! Vaše rezervace v apartmánu Terra Complex byla úspěšně schválena administrátorem. Těšíme se na vaši návštěvu.';
                            $boxColor = '#0f766e';
                        } else {
                            $mail->Subject = 'Rezervace zamítnuta | Terra Complex';
                            $messageTitle = 'Změna stavu rezervace';
                            $messageBody = 'Je nám velmi líto, ale Vaše žádost o rezervaci v apartmánu Terra Complex musela být zamítnuta z důvodu obsazenosti. Děkujeme za pochopení.';
                            $boxColor = '#be123c';
                        }

                        $mail->Body = "
                        <!DOCTYPE html>
                        <html lang='cs'>
                        <head>
                            <meta charset='UTF-8'>
                            <style>
                                body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; background-color: #f8fafc; color: #334155; margin: 0; padding: 0; }
                                .email-container { max-width: 600px; margin: 40px auto; background-color: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); border: 1px solid #e2e8f0; }
                                .header { background-color: $boxColor; color: #ffffff; padding: 30px 40px; text-align: center; }
                                .header h1 { margin: 0; font-size: 22px; font-weight: 700; letter-spacing: 0.5px; }
                                .content { padding: 40px; }
                                .content p { line-height: 1.6; margin-bottom: 20px; font-size: 16px; }
                                .info-box { background-color: #f8fafc; padding: 20px; border-radius: 8px; border: 1px solid #e2e8f0; margin-bottom: 20px; }
                                .info-group { margin-bottom: 15px; }
                                .info-label { font-size: 13px; text-transform: uppercase; color: #64748b; font-weight: 600; display: block; margin-bottom: 4px; }
                                .info-value { font-size: 16px; color: #1e293b; font-weight: 500; margin: 0; }
                                .footer { background-color: #f1f5f9; color: #64748b; text-align: center; padding: 24px; font-size: 14px; border-top: 1px solid #e2e8f0; }
                            </style>
                        </head>
                        <body>
                            <div class='email-container'>
                                <div class='header'>
                                    <h1>$messageTitle</h1>
                                </div>
                                <div class='content'>
                                    <p>Vážený/á <strong>" . htmlspecialchars($reservation['name']) . "</strong>,</p>
                                    <p>$messageBody</p>
                                    <div class='info-box'>
                                        <div class='info-group'>
                                            <span class='info-label'>Detail termínu</span>
                                            <p class='info-value'>Od: " . htmlspecialchars($reservation['start_date']) . " <br> Do: " . htmlspecialchars($reservation['end_date']) . "</p>
                                        </div>
                                    </div>
                                    <p>V případě jakýchkoliv dotazů nás neváhejte kontaktovat.</p>
                                    <p>S pozdravem,<br><strong>Tým Terra Complex</strong></p>
                                </div>
                                <div class='footer'>
                                    <p style='margin: 0;'>Toto je automatická zpráva, prosím, neodpovídejte na ni.</p>
                                </div>
                            </div>
                        </body>
                        </html>
                        ";

                        $mail->AltBody = "Vážený/á " . $reservation['name'] . ",\n\n" . $messageBody . "\n\nDetail termínu:\nOd: " . $reservation['start_date'] . "\nDo: " . $reservation['end_date'] . "\n\nV případě jakýchkoliv dotazů nás neváhejte kontaktovat.\n\nS pozdravem,\nTým Terra Complex\n\nToto je automatická zpráva, prosím, neodpovídejte na ni.";

                        $mail->send();
                    } catch (Exception $e) {
                    }
                }
            }
            header("Location: index.php?success=1");
            exit;
        } catch (PDOException $e) {
            header("Location: index.php?error=1");
            exit;
        }
    }
}
header("Location: index.php");
exit;
?>