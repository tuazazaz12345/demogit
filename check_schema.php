<?php
// Check tblreview schema
require_once 'app/Config.php';

try {
    $conn = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME,
        DB_USER,
        DB_PASS
    );
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "=== TABLE REVIEW SCHEMA ===<br><br>";
    
    $stmt = $conn->query("DESCRIBE tblreview");
    $columns = $stmt->fetchAll();
    
    echo "<table border='1' style='border-collapse:collapse'>";
    echo "<tr style='background:#f0f0f0'><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
    foreach ($columns as $col) {
        echo "<tr>";
        echo "<td>" . $col['Field'] . "</td>";
        echo "<td>" . $col['Type'] . "</td>";
        echo "<td>" . $col['Null'] . "</td>";
        echo "<td>" . $col['Key'] . "</td>";
        echo "<td>" . ($col['Default'] ?? 'NULL') . "</td>";
        echo "<td>" . $col['Extra'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    echo "<br><br>=== TABLE INDEXES ===<br>";
    $stmt = $conn->query("SHOW INDEXES FROM tblreview");
    $indexes = $stmt->fetchAll();
    echo "<pre>";
    print_r($indexes);
    echo "</pre>";
    
    echo "<br>=== SAMPLE INSERT TEST ===<br>";
    $testData = [
        'masp' => 'TEST_SP_' . time(),
        'ten' => 'Test User',
        'email' => 'test_' . time() . '@example.com',
        'noidung' => 'This is a test review content with minimum 10 characters',
        'sosao' => 5,
        'order_id' => null,
        'trangthai' => 'chờ duyệt'
    ];
    
    $stmt = $conn->prepare("
        INSERT INTO tblreview (masp, ten, email, noidung, sosao, order_id, trangthai)
        VALUES (:masp, :ten, :email, :noidung, :sosao, :order_id, :trangthai)
    ");
    
    $result = $stmt->execute($testData);
    $insertId = $conn->lastInsertId();
    
    echo "Test Insert Result: " . ($result ? "SUCCESS" : "FAILED") . "<br>";
    echo "Inserted ID: " . $insertId . "<br>";
    
    // Verify insert
    if ($insertId > 0) {
        $stmt = $conn->prepare("SELECT * FROM tblreview WHERE id = :id");
        $stmt->execute(['id' => $insertId]);
        $record = $stmt->fetch();
        echo "<br>Inserted Record:<br>";
        echo "<pre>";
        print_r($record);
        echo "</pre>";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
