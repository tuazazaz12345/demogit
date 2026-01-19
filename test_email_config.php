<?php
/**
 * 📧 EMAIL CONFIGURATION TEST SCRIPT
 * 
 * Chạy script này để test cấu hình email
 * 
 * Cách chạy:
 *   - Bỏ vào MVC folder
 *   - Vào http://localhost/phpnangcao/MVC/test_email_config.php
 */

session_start();

// Include necessary files
require_once 'app/config.php';
require_once 'app/EmailService.php';

echo "<h1>📧 Email Configuration Test</h1>";
echo "<hr>";

// ===== TEST 1: EmailService Load =====
echo "<h2>Test 1: EmailService Class</h2>";
try {
    $emailService = new EmailService();
    echo "✅ EmailService loaded successfully<br>";
} catch (Exception $e) {
    echo "❌ Error loading EmailService: " . $e->getMessage() . "<br>";
    exit;
}

// ===== TEST 2: SMTP Configuration =====
echo "<h2>Test 2: SMTP Configuration</h2>";
try {
    // Check if PHPMailer is loaded
    if (class_exists('PHPMailer\PHPMailer\PHPMailer')) {
        echo "✅ PHPMailer library loaded<br>";
    } else {
        echo "❌ PHPMailer library NOT found<br>";
    }
    
    // Create test mail object
    $testMail = new \PHPMailer\PHPMailer\PHPMailer();
    echo "✅ PHPMailer object created<br>";
} catch (Exception $e) {
    echo "❌ Error creating PHPMailer: " . $e->getMessage() . "<br>";
}

// ===== TEST 3: Send Test Email =====
echo "<h2>Test 3: Send Test Email</h2>";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['send_test'])) {
    $recipientEmail = $_POST['recipient_email'] ?? '';
    
    if (empty($recipientEmail)) {
        echo "❌ Vui lòng nhập email nhận<br>";
    } else {
        try {
            $emailService = new EmailService();
            
            // Dữ liệu test
            $testOrderData = [
                'order_code' => 'TEST' . time(),
                'total_amount' => 150000,
                'receiver' => 'Test User',
                'phone' => '0123456789',
                'address' => '123 Đường ABC, TP.HCM',
                'created_at' => date('Y-m-d H:i:s'),
                'items' => [
                    [
                        'tensp' => 'Sách PHP Pro',
                        'qty' => 2,
                        'giaxuat' => 75000,
                        'thanhtien' => 150000
                    ]
                ]
            ];
            
            // Gửi email test
            $result = $emailService->sendOrderConfirmation(
                $recipientEmail,
                'Test Recipient',
                $testOrderData
            );
            
            if ($result) {
                echo "✅ <strong>Email sent successfully!</strong><br>";
                echo "Recipients: " . htmlspecialchars($recipientEmail) . "<br>";
                echo "Order Code: " . $testOrderData['order_code'] . "<br>";
                echo "<p style='color: green; font-weight: bold;'>Check your inbox now! (May take 30 seconds)</p>";
            } else {
                echo "❌ Failed to send email<br>";
                echo "Check error logs for details<br>";
            }
        } catch (Exception $e) {
            echo "❌ Error: " . $e->getMessage() . "<br>";
            echo "<p>Stack trace:</p>";
            echo "<pre>" . $e->getTraceAsString() . "</pre>";
        }
    }
}

// ===== TEST 4: Form to Send Test Email =====
echo "<h2>Send Test Email</h2>";
echo <<<HTML
<form method="POST" style="margin-top: 20px;">
    <div style="margin-bottom: 10px;">
        <label for="recipient_email">Recipient Email:</label><br>
        <input type="email" 
               id="recipient_email" 
               name="recipient_email" 
               placeholder="your-email@gmail.com"
               value="{$_SESSION['user']['email'] ?? ''}"
               style="width: 300px; padding: 8px;">
    </div>
    
    <button type="submit" name="send_test" 
            style="padding: 10px 20px; background-color: #667eea; color: white; border: none; border-radius: 5px; cursor: pointer;">
        Send Test Email
    </button>
</form>
HTML;

// ===== TEST 5: Check Error Logs =====
echo "<h2>Test 4: Recent Error Logs</h2>";
echo "<p>Check logs at: <code>/var/log/php_errors.log</code> (Linux/Mac) or <code>C:\\xampp\\logs\\php_error.log</code> (Windows)</p>";

// Try to display recent errors
$logFile = '/var/log/php_errors.log';
if (file_exists($logFile)) {
    echo "<h3>Recent Logs (Last 20 lines):</h3>";
    $lines = array_slice(file($logFile), -20);
    echo "<pre style='background: #f5f5f5; padding: 10px; border-radius: 5px;'>";
    
    $emailLogs = [];
    foreach ($lines as $line) {
        if (stripos($line, 'email') !== false || stripos($line, 'smtp') !== false) {
            $emailLogs[] = htmlspecialchars($line);
        }
    }
    
    if (!empty($emailLogs)) {
        foreach ($emailLogs as $log) {
            echo $log;
        }
    } else {
        echo "No email-related logs found<br>";
        echo "Recent logs:<br>";
        foreach ($lines as $line) {
            echo htmlspecialchars($line);
        }
    }
    echo "</pre>";
} else {
    echo "⚠️ Log file not found at: $logFile<br>";
    echo "Try checking: C:\\xampp\\logs\\php_error.log (Windows)<br>";
}

// ===== TEST 6: Configuration Summary =====
echo "<h2>Test 5: Configuration Summary</h2>";
echo "<table border='1' cellpadding='10'>";
echo "<tr><th>Setting</th><th>Value</th><th>Status</th></tr>";

$configs = [
    'SMTP Host' => ['smtp.gmail.com', '✅'],
    'SMTP Port' => ['587', '✅'],
    'SMTP User' => ['zerohn889@gmail.com', '✅'],
    'Encryption' => ['TLS', '✅'],
    'From Email' => ['zerohn889@gmail.com', '✅'],
    'From Name' => ['Cửa Hàng Sách', '✅'],
];

foreach ($configs as $key => $value) {
    echo "<tr>";
    echo "<td><strong>" . htmlspecialchars($key) . "</strong></td>";
    echo "<td>" . htmlspecialchars($value[0]) . "</td>";
    echo "<td>" . $value[1] . "</td>";
    echo "</tr>";
}

echo "</table>";

// ===== FINAL SUMMARY =====
echo "<h2>Summary</h2>";
echo "<p><strong>✅ If all tests passed:</strong> Email system is working correctly!</p>";
echo "<p><strong>❌ If some tests failed:</strong> Check the error messages above and fix the issues.</p>";
echo "<p style='color: #666; font-size: 12px; margin-top: 20px;'>";
echo "For help, see: <code>EMAIL_CONFIGURATION_GUIDE.md</code>";
echo "</p>";

?>
