<?php
// Simple review count check
require_once 'app/Config.php';

try {
    $conn = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME,
        DB_USER,
        DB_PASS
    );
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<h2>📊 QUICK REVIEW COUNT</h2>";
    
    // Total count
    $stmt = $conn->query("SELECT COUNT(*) as cnt FROM tblreview");
    $result = $stmt->fetch();
    echo "<strong>Total reviews in database:</strong> " . $result['cnt'] . "<br><br>";
    
    // By status
    echo "<strong>Breakdown by status:</strong><br>";
    $stmt = $conn->query("SELECT trangthai, COUNT(*) as cnt FROM tblreview GROUP BY trangthai");
    $results = $stmt->fetchAll();
    
    if (empty($results)) {
        echo "❌ <strong style='color:red'>NO REVIEWS FOUND</strong><br>";
    } else {
        foreach ($results as $row) {
            echo "- " . $row['trangthai'] . ": " . $row['cnt'] . "<br>";
        }
    }
    
    // Latest 3 reviews
    echo "<br><strong>Latest 3 reviews:</strong><br>";
    $stmt = $conn->query("SELECT id, masp, email, sosao, trangthai, ngaygui FROM tblreview ORDER BY id DESC LIMIT 3");
    $results = $stmt->fetchAll();
    
    if (empty($results)) {
        echo "❌ No reviews at all<br>";
    } else {
        foreach ($results as $row) {
            echo "ID:" . $row['id'] . " | Product:" . $row['masp'] . " | Rating:" . $row['sosao'] . " | Status:" . $row['trangthai'] . "<br>";
        }
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage();
}
?>
