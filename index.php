<?php
// Bắt đầu session (nếu chưa có)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Debug: nếu muốn xem tham số GET, bật dòng dưới
// echo "<pre>"; print_r($_GET); echo "</pre>";

require_once "./app/config.php";
require_once "./app/DB.php";
require_once "./app/Controller.php";
require_once "./app/App.php";

// Khởi tạo ứng dụng MVC
$app = new App();
