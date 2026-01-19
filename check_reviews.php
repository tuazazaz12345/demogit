<?php
// Kiểm tra xem có record nào trong tblreview không
require_once 'app/Config.php';

try {
    $conn = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME,
        DB_USER,
        DB_PASS
    );
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "=== DATABASE CHECK ===<br>";
    
    // Check tblreview records
    $stmt = $conn->query("SELECT COUNT(*) as total FROM tblreview");
    $result = $stmt->fetch();
    echo "Total reviews in database: " . $result['total'] . "<br><br>";
    
    // List latest 10 reviews
    echo "Latest 10 reviews:<br>";
    $stmt = $conn->query("SELECT * FROM tblreview ORDER BY id DESC LIMIT 10");
    $reviews = $stmt->fetchAll();
    
    if (count($reviews) > 0) {
        echo "<table border='1'>";
        echo "<tr><th>ID</th><th>masp</th><th>ten</th><th>email</th><th>sosao</th><th>trangthai</th><th>ngaygui</th></tr>";
        foreach ($reviews as $review) {
            echo "<tr>";
            echo "<td>" . $review['id'] . "</td>";
            echo "<td>" . $review['masp'] . "</td>";
            echo "<td>" . $review['ten'] . "</td>";
            echo "<td>" . $review['email'] . "</td>";
            echo "<td>" . $review['sosao'] . "</td>";
            echo "<td>" . $review['trangthai'] . "</td>";
            echo "<td>" . $review['ngaygui'] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "No reviews found in database<br>";
    }
    
    // Check tblreview table structure
    echo "<hr>";
    echo "Table structure:<br>";
    $stmt = $conn->query("DESCRIBE tblreview");
    $columns = $stmt->fetchAll();
    echo "<pre>";
    print_r($columns);
    echo "</pre>";
    
} catch (Exception $e) {
    echo "Database Error: " . $e->getMessage();
}
?>
