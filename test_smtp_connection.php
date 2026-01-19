<?php
// ✅ Test Email Configuration
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Set up error logging to file
ini_set('log_errors', 1);
ini_set('error_log', dirname(__FILE__) . '/php_error.log');

require_once 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

echo "<h2>Testing PHPMailer SMTP Configuration</h2>";

try {
    $mail = new PHPMailer(true);
    
    // Enable debug logging
    $mail->SMTPDebug = SMTP::DEBUG_SERVER;
    $mail->Debugoutput = 'html';
    
    echo "<h3>1. SMTP Configuration</h3>";
    echo "<p>Setting up SMTP with Gmail credentials...</p>";
    
    // SMTP Configuration
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'zerohn889@gmail.com';
    $mail->Password = 'rtgm zzto djjy oigp';  // App Password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;
    $mail->CharSet = 'UTF-8';
    
    echo "<p style='color: green;'>✓ SMTP Configuration Set</p>";
    
    echo "<h3>2. Email Content Setup</h3>";
    
    $mail->setFrom('zerohn889@gmail.com', 'Cửa Hàng Sách Test');
    $mail->addAddress('zerohn889@gmail.com', 'Test Recipient');
    
    $mail->Subject = 'PHPMailer Test - ' . date('Y-m-d H:i:s');
    $mail->Body = '<h1>Test Email</h1><p>This is a test email from PHPMailer SMTP configuration.</p><p>Time: ' . date('Y-m-d H:i:s') . '</p>';
    $mail->AltBody = 'Test Email - This is a test email from PHPMailer SMTP configuration.';
    
    echo "<p style='color: green;'>✓ Email Content Set</p>";
    
    echo "<h3>3. Attempting to Send Email</h3>";
    echo "<p>Sending test email...</p>";
    
    if ($mail->send()) {
        echo "<p style='color: green;'><strong>✓ SUCCESS: Email sent successfully!</strong></p>";
        echo "<p>Email sent to: zerohn889@gmail.com</p>";
        echo "<p>Subject: " . htmlspecialchars($mail->Subject) . "</p>";
    } else {
        echo "<p style='color: red;'><strong>✗ FAILED: Could not send email</strong></p>";
        echo "<p>Error: " . htmlspecialchars($mail->ErrorInfo) . "</p>";
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'><strong>✗ Exception Error:</strong></p>";
    echo "<pre style='color: red;'>" . htmlspecialchars($e->getMessage()) . "</pre>";
    echo "<pre style='color: red;'>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
}

echo "<h3>4. Debug Output Below</h3>";
echo "<hr>";
?>
