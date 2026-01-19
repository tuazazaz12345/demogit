<?php
/**
 * LOCAL CONFIG - VNPAY Testing
 * Thay đổi các giá trị dưới đây cho từng lần chạy ngrok
 */

// ⚠️ Cập nhật URL ngrok của bạn ở đây (không được có /Home/ ở cuối)
define("NGROK_URL", "https://zain-ungrumpy-kena.ngrok-free.dev/phpnangcao/MVC");

// ⚠️ VNPAY Merchant Account
// Lấy từ https://sandbox.vnpayment.vn
define("VNPAY_TMN_CODE", "D57Y6YEK");
define("VNPAY_HASH_SECRET", "TLWV344RIWUM86UF0ATX7RE5VBV2VFJP");

// Return URLs sẽ dùng MVC Controller thay vì file tĩnh
// Format: /index.php?url=VnpayReturnController/index
define("VNPAY_RETURN_URL", NGROK_URL . "/index.php?url=VnpayReturnController");
define("VNPAY_NOTIFY_URL", NGROK_URL . "/vnpay_php/vnpay_ipn.php");
