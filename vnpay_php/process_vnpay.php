<?php
require_once("./config.php");
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user'])) {
    header('Location: /AuthController/login');
    exit();
}

// Lấy thông tin thanh toán từ session
$orderId = $_SESSION['pending_order']['order_id'] ?? '';
$orderCode = $_SESSION['pending_order']['order_code'] ?? '';
$amount = $_SESSION['pending_order']['amount'] ?? 0;

// Tạo URL thanh toán VNPAY
$vnp_TxnRef = $orderCode;
$vnp_OrderInfo = 'Thanh toan don hang ' . $orderCode;
$vnp_OrderType = 'billpayment';
$vnp_Amount = $amount * 100; // Nhân 100 vì VNPAY yêu cầu số tiền x100
$vnp_Locale = 'vn';
$vnp_IpAddr = $_SERVER['REMOTE_ADDR'];
$vnp_ExpireDate = $expire;

$inputData = array(
    "vnp_Version" => "2.1.0",
    "vnp_TmnCode" => $vnp_TmnCode,
    "vnp_Amount" => $vnp_Amount,
    "vnp_Command" => "pay",
    "vnp_CreateDate" => date('YmdHis'),
    "vnp_CurrCode" => "VND",
    "vnp_IpAddr" => $vnp_IpAddr,
    "vnp_Locale" => $vnp_Locale,
    "vnp_OrderInfo" => $vnp_OrderInfo,
    "vnp_OrderType" => $vnp_OrderType,
    "vnp_ReturnUrl" => $vnp_Returnurl,
    "vnp_TxnRef" => $vnp_TxnRef,
    "vnp_ExpireDate" => $vnp_ExpireDate
);

ksort($inputData);
$query = "";
$i = 0;
$hashdata = "";
foreach ($inputData as $key => $value) {
    if ($i == 1) {
        $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
    } else {
        $hashdata .= urlencode($key) . "=" . urlencode($value);
        $i = 1;
    }
    $query .= urlencode($key) . "=" . urlencode($value) . '&';
}

$vnp_Url = $vnp_Url . "?" . $query;
if (isset($vnp_HashSecret)) {
    $vnpSecureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret);
    $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
}

// Chuyển hướng đến trang thanh toán VNPAY
header('Location: ' . $vnp_Url);
die();