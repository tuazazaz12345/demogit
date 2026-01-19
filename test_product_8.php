<?php
// Quick check tblreview status enum values
require_once 'app/Config.php';

try {
    $conn = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME,
        DB_USER,
        DB_PASS
    );
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "=== TBLREVIEW STATUS VALUES ===<br><br>";
    
    // Get column info
    $stmt = $conn->query("SHOW FULL COLUMNS FROM tblreview");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($columns as $col) {
        if ($col['Field'] === 'trangthai') {
            echo "<strong>Column: trangthai</strong><br>";
            echo "Type: " . $col['Type'] . "<br>";
            echo "Null: " . $col['Null'] . "<br>";
            echo "Default: " . ($col['Default'] ?? 'NO DEFAULT') . "<br>";
            echo "Collation: " . $col['Collation'] . "<br>";
            
            // Parse enum values
            if (preg_match("/enum\('([^']*)'\)/i", $col['Type'], $matches)) {
                $enumValues = explode("','", $matches[1]);
                echo "<br>Allowed Values:<br>";
                foreach ($enumValues as $val) {
                    echo "- " . $val . "<br>";
                }
            }
        }
    }
    
    echo "<br>=== COUNT BY STATUS ===<br>";
    $stmt = $conn->query("SELECT trangthai, COUNT(*) as count FROM tblreview GROUP BY trangthai");
    $statusCounts = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($statusCounts as $row) {
        echo "Status '" . $row['trangthai'] . "': " . $row['count'] . " reviews<br>";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
echo "Product data: " . json_encode($product, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n";
echo "maloaisp value: " . ($product['maloaisp'] ?? 'NULL') . "\n";

if ($product && isset($product['maloaisp']) && $product['maloaisp']) {
    echo "\nLooking for related products...\n";
    $related = $model->getRelatedProducts('8', $product['maloaisp'], 4);
    echo "Found: " . count($related) . " related products\n";
    echo "Related data: " . json_encode($related, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n";
} else {
    echo "\nERROR: No maloaisp found!\n";
}
?>
