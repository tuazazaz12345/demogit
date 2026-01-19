<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once("./config.php");

// Lưu thông tin thanh toán vào session
$_SESSION['payment_info'] = $_GET;

$vnp_SecureHash = $_GET['vnp_SecureHash'];
$inputData = array();
foreach ($_GET as $key => $value) {
    if (substr($key, 0, 4) == "vnp_") {
        $inputData[$key] = $value;
    }
}
unset($inputData['vnp_SecureHash']);
ksort($inputData);
$i = 0;
$hashData = "";
foreach ($inputData as $key => $value) {
    if ($i == 1) {
        $hashData = $hashData . '&' . urlencode($key) . "=" . urlencode($value);
    } else {
        $hashData = $hashData . urlencode($key) . "=" . urlencode($value);
        $i = 1;
    }
}

$secureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);

if ($secureHash == $vnp_SecureHash) {
    if ($_GET['vnp_ResponseCode'] == '00') {
        $order_id = $_GET['vnp_TxnRef'];
        $_SESSION['payment_success'] = true;
        $_SESSION['order_code'] = $order_id;
        
        if (!isset($_SESSION['user'])) {
            $_SESSION['return_url'] = "/phpnangcao/MVC/CartController/confirmPayment?order_id=" . $order_id;
            header('Location: ' . APP_URL . 'AuthController/login');
            exit();
        }
        
        header("Location: /phpnangcao/MVC/CartController/confirmPayment?order_id=" . $order_id);
        exit();
    } else {
        $_SESSION['payment_error'] = 'Thanh toán không thành công';
        header("Location: /phpnangcao/MVC/CartController");
        exit();
    }
} else {
    $_SESSION['payment_error'] = 'Chữ ký không hợp lệ';
    header("Location: /phpnangcao/MVC/CartController");
    exit();
}
?>