<?php
session_start();
require_once("./config.php");
require_once "../models/BaseModel.php";
require_once "../models/OrderModel.php";

if (isset($_POST['order_code'])) {
    $orderModel = new OrderModel();
    $orderCode = $_POST['order_code'];
    
    // Lấy thông tin đơn hàng để có ID
    $order = $orderModel->getOrderByCode($orderCode);
    
    // Cập nhật trạng thái đơn hàng
    $orderModel->updateStatus($orderCode, 'đã thanh toán');
    
    // ✅ GỬI EMAIL THÔNG BÁO THANH TOÁN THÀNH CÔNG
    if ($order && isset($order['id'])) {
        try {
            $orderModel->sendPaymentConfirmationEmail($order['id']);
        } catch (Exception $e) {
            error_log("Lỗi gửi email thanh toán: " . $e->getMessage());
        }
    }
    
    // Xóa session
    unset($_SESSION['cart']);
    unset($_SESSION['pending_order']);
    
    // Trả về kết quả
    echo json_encode(['success' => true]);
    exit;
}

// Hiển thị form xác nhận
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Xác nhận thanh toán</title>
    <link href="/phpnangcao/MVC/public/css/bootstrap.min.css" rel="stylesheet"/>
    <link href="assets/vnpay.css" rel="stylesheet"/>
</head>
<body>
    <div class="container mt-5">
        <div class="card">
            <div class="card-header">
                <h3 class="text-center">Xác nhận thanh toán</h3>
            </div>
            <div class="card-body">
                <div class="text-center">
                    <h4>Mã đơn hàng: <?php echo $_GET['order_code'] ?? ''; ?></h4>
                    <p>Số tiền: <?php echo number_format(($_GET['amount'] ?? 0)/100, 0, ',', '.'); ?> VNĐ</p>
                    <hr>
                    <form id="confirmForm">
                        <input type="hidden" name="order_code" value="<?php echo $_GET['order_code'] ?? ''; ?>">
                        <button type="submit" class="btn btn-success btn-lg">Xác nhận đã thanh toán</button>
                    </form>
                    <a href="/phpnangcao/MVC/CartController" class="btn btn-secondary mt-3">Quay lại giỏ hàng</a>
                </div>
            </div>
        </div>
    </div>

    <script src="/phpnangcao/MVC/public/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('confirmForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            fetch('confirm_payment.php', {
                method: 'POST',
                body: new FormData(this)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Đã xác nhận thanh toán thành công!');
                    window.location.href = '/phpnangcao/MVC/OrderController/myOrders/<?php echo $_SESSION['user']['email'] ?? ''; ?>';
                }
            });
        });
    </script>
</body>
</html>