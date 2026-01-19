<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Kết quả thanh toán</title>
        <!-- Bootstrap core CSS -->
        <link href="../public/css/bootstrap.min.css" rel="stylesheet"/>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet">
        <style>
            body { 
                padding-top: 40px; 
                background-color: #f8f9fa;
            }
            .container { 
                max-width: 800px; 
            }
            .result-box { 
                padding: 20px;
                border-radius: 5px;
                margin-bottom: 20px;
            }
            .success { 
                background-color: #d4edda; 
                border-color: #c3e6cb; 
                color: #155724; 
            }
            .error { 
                background-color: #f8d7da; 
                border-color: #f5c6cb; 
                color: #721c24; 
            }
            .form-group { 
                margin-bottom: 1rem; 
                padding: 10px;
                background: white;
                border-radius: 5px;
            }
            .form-group label { 
                font-weight: bold;
                min-width: 200px;
                display: inline-block;
            }
            .card {
                box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            }
        </style>
    </head>
    <body>
        <?php
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        require_once("./config.php");
        require_once("../app/config.php");
        require_once("../app/DB.php");
        require_once("../models/BaseModel.php");
        require_once("../models/OrderModel.php");

        // Debug: Log the received data
        error_log("VNPAY Return Page Loaded - APP_URL: " . APP_URL);
        error_log("VNPAY Response: " . json_encode($_GET));
        
        // Lấy các tham số từ URL
        $vnp_ResponseCode = isset($_GET['vnp_ResponseCode']) ? $_GET['vnp_ResponseCode'] : '';
        $vnp_TxnRef = isset($_GET['vnp_TxnRef']) ? $_GET['vnp_TxnRef'] : '';
        $vnp_Amount = isset($_GET['vnp_Amount']) ? $_GET['vnp_Amount'] : 0;
        $vnp_OrderInfo = isset($_GET['vnp_OrderInfo']) ? $_GET['vnp_OrderInfo'] : '';
        $vnp_TransactionNo = isset($_GET['vnp_TransactionNo']) ? $_GET['vnp_TransactionNo'] : '';
        $vnp_BankCode = isset($_GET['vnp_BankCode']) ? $_GET['vnp_BankCode'] : '';
        $vnp_PayDate = isset($_GET['vnp_PayDate']) ? $_GET['vnp_PayDate'] : date('YmdHis');
        $vnp_SecureHash = isset($_GET['vnp_SecureHash']) ? $_GET['vnp_SecureHash'] : '';
        
        // Tạo OrderModel để lấy order ID từ code
        $orderModel = new OrderModel();
        $orderByCode = null;
        $orderId = null;

        // Xử lý chữ ký
        $inputData = array();
        foreach ($_GET as $key => $value) {
            if (substr($key, 0, 4) == "vnp_") {
                $inputData[$key] = $value;
            }
        }
        unset($inputData['vnp_SecureHash']);
        ksort($inputData);
        
        $hashData = "";
        $i = 0;
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashData .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashData .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
        }

        // Tạo secure hash mới để xác thực
        $secureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);
        
        // Debug: Log hashes để so sánh
        error_log("Generated Hash: " . $secureHash);
        error_log("Received Hash: " . $vnp_SecureHash);
        
        // Lưu thông tin thanh toán vào session (bất kể kết quả)
        $_SESSION['payment_info'] = [
            'order_id' => $vnp_TxnRef,
            'amount' => $vnp_Amount,
            'order_info' => $vnp_OrderInfo,
            'transaction_no' => $vnp_TransactionNo,
            'bank_code' => $vnp_BankCode,
            'pay_date' => $vnp_PayDate,
            'response_code' => $vnp_ResponseCode
        ];
        
        // Kiểm tra response code và cập nhật trạng thái
        if ($vnp_ResponseCode == '00') {
            $_SESSION['payment_success'] = true;
            
            try {
                $result = $orderModel->updatePaymentStatus($vnp_TxnRef, 'đã thanh toán');
                if ($result) {
                    error_log("Order status updated successfully for order: " . $vnp_TxnRef);
                    
                    // Tìm order ID từ order code
                    $orderByCode = $orderModel->getOrderByCode($vnp_TxnRef);
                    if ($orderByCode && isset($orderByCode['id'])) {
                        $orderId = $orderByCode['id'];
                        $_SESSION['payment_info']['order_database_id'] = $orderId;
                        
                        // ✅ GỬI EMAIL XÁC NHẬN ĐƠN HÀNG
                        try {
                            $orderModel->sendOrderConfirmationEmail($orderId);
                            error_log("Order confirmation email sent for order ID: " . $orderId);
                        } catch (Exception $e) {
                            error_log("Error sending order confirmation email: " . $e->getMessage());
                        }
                    }
                    }
                    
                    // ✅ XÓA GIỎ HÀNG TỪ SESSION VÀ DATABASE SAU KHI THANH TOÁN THÀNH CÔNG
                    unset($_SESSION['cart']);
                    unset($_SESSION['pending_order']);
                    
                    if (isset($_SESSION['user'])) {
                        try {
                            require_once("../models/ShoppingCartModel.php");
                            $cartModel = new ShoppingCartModel();
                            $cartModel->clearCart($_SESSION['user']['id']);
                            error_log('Cart cleared from database after VNPAY payment: User ' . $_SESSION['user']['id']);
                        } catch (Exception $e) {
                            error_log('Error clearing cart from database: ' . $e->getMessage());
                        }
                    }
                } else {
                    error_log("Failed to update order status for order: " . $vnp_TxnRef);
                }
            } catch (Exception $e) {
                error_log("Error updating order status: " . $e->getMessage());
            }
        } else {
            $_SESSION['payment_success'] = false;
            error_log("Payment failed with response code: " . $vnp_ResponseCode);
        }
            
        
        ?>
        <div class="container">
            <div class="card">
                <div class="card-header">
                    <h3 class="text-center mb-0">Kết quả thanh toán</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <?php if (isset($_SESSION['payment_info'])): 
                            $payment_info = $_SESSION['payment_info'];
                        ?>
                        <div class="form-group">
                            <label>Mã đơn hàng:</label>
                            <span><?php echo $payment_info['order_id']; ?></span>
                        </div>    
                        <div class="form-group">
                            <label>Số tiền:</label>
                            <span><?php echo number_format($payment_info['amount']/100, 0, ',', '.'); ?> VNĐ</span>
                        </div>  
                        <div class="form-group">
                            <label>Nội dung thanh toán:</label>
                            <span><?php echo $payment_info['order_info']; ?></span>
                        </div> 
                        <div class="form-group">
                            <label>Mã GD Tại VNPAY:</label>
                            <span><?php echo $payment_info['transaction_no']; ?></span>
                        </div> 
                        <div class="form-group">
                            <label>Mã Ngân hàng:</label>
                            <span><?php echo $payment_info['bank_code']; ?></span>
                        </div> 
                        <div class="form-group">
                            <label>Thời gian thanh toán:</label>
                            <span><?php 
                                $payDate = $payment_info['pay_date'];
                                $formattedDate = date('d-m-Y H:i:s', strtotime(
                                    substr($payDate, 0, 4) . '-' . 
                                    substr($payDate, 4, 2) . '-' . 
                                    substr($payDate, 6, 2) . ' ' . 
                                    substr($payDate, 8, 2) . ':' . 
                                    substr($payDate, 10, 2) . ':' . 
                                    substr($payDate, 12, 2)
                                ));
                                echo $formattedDate;
                            ?></span>
                        </div>
                        <?php endif; ?> 
                        <?php
                        // Debug payment info
                        error_log("Payment Info in Session: " . json_encode($_SESSION['payment_info']));
                        ?>
                        <div class="result-box <?php echo ($vnp_ResponseCode == '00' || (isset($_SESSION['payment_success']) && $_SESSION['payment_success'])) ? 'success' : 'error' ?>">
                            <h4 class="text-center mb-3">Kết quả giao dịch</h4>
                            <?php
                            // Check if payment was successful
                            if ($vnp_ResponseCode == '00' || (isset($_SESSION['payment_success']) && $_SESSION['payment_success'])) {
                                $orderModel = new OrderModel();
                                try {
                                    // Update payment status if not already updated
                                    $orderModel->updatePaymentStatus($vnp_TxnRef, 'đã thanh toán');
                                    
                                    echo "<div class='text-center'>";
                                    echo "<h5 class='text-success mb-4'><i class='bi bi-check-circle'></i> Giao dịch thành công</h5>";
                                    echo "<p>Cảm ơn bạn đã thanh toán đơn hàng!</p>";
                                    echo "<p>Mã đơn hàng của bạn là: <strong>" . $vnp_TxnRef . "</strong></p>";
                                    echo "<p>Số tiền đã thanh toán: <strong>" . number_format($vnp_Amount/100, 0, ',', '.') . " VNĐ</strong></p>";
                                    if ($vnp_TransactionNo) {
                                        echo "<p>Mã giao dịch VNPAY: <strong>" . $vnp_TransactionNo . "</strong></p>";
                                    }
                                    echo "</div>";
                                } catch (Exception $e) {
                                    error_log("Error updating payment status: " . $e->getMessage());
                                }
                            } else {
                                echo "<div class='text-center'>";
                                if ($vnp_ResponseCode == '24') {
                                    echo "<h5 class='text-danger mb-3'><i class='bi bi-x-circle'></i> Giao dịch không thành công</h5>";
                                    echo "<p>Giao dịch bị hủy bởi người dùng.</p>";
                                } else {
                                    echo "<h5 class='text-danger mb-3'><i class='bi bi-exclamation-triangle'></i> Giao dịch thất bại</h5>";
                                    if ($vnp_ResponseCode) {
                                        echo "<p>Mã lỗi: " . $vnp_ResponseCode . "</p>";
                                    }
                                    echo "<p>Vui lòng kiểm tra lại thông tin thanh toán hoặc thử lại sau.</p>";
                                }
                                echo "</div>";
                            }
                            ?>
                        </div>
                    </div>

                    <!-- ========== CHI TIẾT HÓA ĐƠN ========== -->
                    <?php
                    if (($vnp_ResponseCode == '00' || (isset($_SESSION['payment_success']) && $_SESSION['payment_success'])) && $vnp_TxnRef) {
                        $orderModel = new OrderModel();
                        try {
                            $order = $orderModel->getOrderById($vnp_TxnRef);
                            $details = $orderModel->getOrderDetails($vnp_TxnRef);
                            
                            if ($order && !empty($details)) {
                                echo '<div class="mt-5 pt-4 border-top">';
                                echo '<h4 class="mb-4"><i class="bi bi-receipt"></i> Chi tiết hóa đơn</h4>';
                                
                                // Thông tin khách hàng
                                echo '<div class="row mb-4">';
                                echo '<div class="col-md-6">';
                                echo '<h6 class="text-muted mb-3">Thông tin khách hàng</h6>';
                                echo '<p><strong>Tên:</strong> ' . (isset($order['customer_name']) ? htmlspecialchars($order['customer_name']) : 'N/A') . '</p>';
                                echo '<p><strong>Email:</strong> ' . (isset($order['email']) ? htmlspecialchars($order['email']) : 'N/A') . '</p>';
                                echo '<p><strong>Điện thoại:</strong> ' . (isset($order['phone']) ? htmlspecialchars($order['phone']) : 'N/A') . '</p>';
                                echo '</div>';
                                echo '<div class="col-md-6">';
                                echo '<h6 class="text-muted mb-3">Địa chỉ giao hàng</h6>';
                                echo '<p>' . (isset($order['address']) ? htmlspecialchars($order['address']) : 'N/A') . '</p>';
                                echo '</div>';
                                echo '</div>';
                                
                                // Bảng chi tiết sản phẩm
                                echo '<div class="table-responsive mt-4">';
                                echo '<table class="table table-sm table-hover">';
                                echo '<thead class="table-light">';
                                echo '<tr>';
                                echo '<th>Sản phẩm</th>';
                                echo '<th class="text-end">Số lượng</th>';
                                echo '<th class="text-end">Đơn giá</th>';
                                echo '<th class="text-end">Thành tiền</th>';
                                echo '</tr>';
                                echo '</thead>';
                                echo '<tbody>';
                                
                                $totalAmount = 0;
                                foreach ($details as $detail) {
                                    $subtotal = $detail['quantity'] * $detail['price'];
                                    $totalAmount += $subtotal;
                                    echo '<tr>';
                                    echo '<td>';
                                    echo '<strong>' . htmlspecialchars($detail['product_name']) . '</strong>';
                                    if (isset($detail['description'])) {
                                        echo '<br><small class="text-muted">' . htmlspecialchars(substr($detail['description'], 0, 50)) . '...</small>';
                                    }
                                    echo '</td>';
                                    echo '<td class="text-end">' . intval($detail['quantity']) . '</td>';
                                    echo '<td class="text-end">' . number_format($detail['price'], 0, ',', '.') . ' ₫</td>';
                                    echo '<td class="text-end"><strong>' . number_format($subtotal, 0, ',', '.') . ' ₫</strong></td>';
                                    echo '</tr>';
                                }
                                echo '</tbody>';
                                echo '</table>';
                                echo '</div>';
                                
                                // Tóm tắt đơn hàng
                                echo '<div class="row mt-4">';
                                echo '<div class="col-md-6 ms-auto">';
                                echo '<div class="bg-light p-3 rounded">';
                                echo '<div class="d-flex justify-content-between mb-2">';
                                echo '<span>Tổng tiền hàng:</span>';
                                echo '<strong>' . number_format($totalAmount, 0, ',', '.') . ' ₫</strong>';
                                echo '</div>';
                                
                                // Phí vận chuyển (nếu có)
                                if (isset($order['shipping_fee']) && $order['shipping_fee'] > 0) {
                                    echo '<div class="d-flex justify-content-between mb-2">';
                                    echo '<span>Phí vận chuyển:</span>';
                                    echo '<strong>' . number_format($order['shipping_fee'], 0, ',', '.') . ' ₫</strong>';
                                    echo '</div>';
                                }
                                
                                // Chiết khấu/Mã giảm giá (nếu có)
                                if (isset($order['discount_amount']) && $order['discount_amount'] > 0) {
                                    echo '<div class="d-flex justify-content-between mb-2 text-success">';
                                    echo '<span>Mã giảm giá:</span>';
                                    echo '<strong>-' . number_format($order['discount_amount'], 0, ',', '.') . ' ₫</strong>';
                                    echo '</div>';
                                }
                                
                                // Tổng tiền cuối cùng
                                $finalAmount = isset($order['total_amount']) ? $order['total_amount'] : $totalAmount;
                                echo '<hr>';
                                echo '<div class="d-flex justify-content-between">';
                                echo '<h5>Tổng cộng:</h5>';
                                echo '<h5 class="text-success">' . number_format($finalAmount, 0, ',', '.') . ' ₫</h5>';
                                echo '</div>';
                                echo '</div>';
                                echo '</div>';
                                echo '</div>';
                                
                                // Ghi chú
                                if (isset($order['note']) && !empty($order['note'])) {
                                    echo '<div class="alert alert-info mt-4">';
                                    echo '<strong>Ghi chú:</strong> ' . htmlspecialchars($order['note']);
                                    echo '</div>';
                                }
                                
                                echo '</div>';
                            }
                        } catch (Exception $e) {
                            error_log("Error displaying invoice: " . $e->getMessage());
                        }
                    }
                    ?>
                    
                    <div class="text-center mt-4">
                        <a href="<?php echo APP_URL; ?>/Home" class="btn btn-primary me-2">
                            <i class="bi bi-house-door"></i> Quay lại trang chủ
                        </a>
                        <?php if (isset($_SESSION['payment_success']) && $_SESSION['payment_success']): ?>
                            <a href="<?php echo APP_URL; ?>/OrderController/orderHistory" class="btn btn-info">
                                <i class="bi bi-clock-history"></i> Xem lịch sử đơn hàng
                            </a>
                            <?php if (isset($orderId) && $orderId): ?>
                            <a href="<?php echo APP_URL; ?>/OrderController/detail/<?php echo $orderId; ?>" class="btn btn-warning">
                                <i class="bi bi-file-earmark-text"></i> Xem đầy đủ hóa đơn
                            </a>
                            <?php endif; ?>
                        <?php else: ?>
                            <a href="<?php echo APP_URL; ?>/CartController" class="btn btn-secondary">
                                <i class="bi bi-cart"></i> Quay lại giỏ hàng
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <footer class="text-center mt-4">
                <p>&copy; <?php echo date('Y')?> | Cửa hàng sách</p>
            </footer>
        </div>
        <?php
        // KHÔNG xóa session ngay, để người dùng có thể nhìn thấy hóa đơn
        // Chỉ xóa khi người dùng tải lại trang hoặc quay lại trang khác
        // if (isset($_SESSION['payment_success'])) {
        //     unset($_SESSION['payment_success']);
        //     unset($_SESSION['payment_info']);
        // }
        ?>
    </body>
</html>
