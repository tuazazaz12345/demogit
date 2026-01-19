<?php
/**
 * Debug Chatbox Connection
 * File này dùng để kiểm tra xem chatbox có kết nối được không
 */

// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

header('Content-Type: application/json');

// Test 1: Kiểm tra file config
echo json_encode([
    'test' => 'Config Files',
    'results' => [
        'app_config' => file_exists('./app/config.php') ? '✓ Exists' : '✗ Missing',
        'app_db' => file_exists('./app/DB.php') ? '✓ Exists' : '✗ Missing',
        'chatbox_controller' => file_exists('./controllers/ChatboxController.php') ? '✓ Exists' : '✗ Missing',
        'chatbox_model' => file_exists('./models/ChatboxModel.php') ? '✓ Exists' : '✗ Missing',
    ]
], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

// Test 2: Load config và kiểm tra database
if (file_exists('./app/config.php')) {
    require_once './app/config.php';
    
    try {
        require_once './app/DB.php';
        $db = new DB();
        
        // Kiểm tra table chatbox_messages
        $result = $db->query(
            "SHOW TABLES LIKE 'chatbox_messages'"
        );
        
        if ($result && $result->rowCount() > 0) {
            echo "\n✓ Table chatbox_messages exists\n";
        } else {
            echo "\n✗ Table chatbox_messages NOT found\n";
            echo "Please run: CREATE_CHATBOX_TABLE_UPDATED.sql\n";
        }
        
    } catch (Exception $e) {
        echo "\n✗ Database Error: " . $e->getMessage() . "\n";
    }
}
?>
