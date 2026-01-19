<?php
class VnpayReturnController extends Controller {
    
    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * Xử lý kết quả thanh toán từ VNPAY
     */
    public function index() {
        require_once(__DIR__ . "/../vnpay_php/config.php");
        require_once(__DIR__ . "/../models/OrderModel.php");

        // Debug: Log the received data
        error_log("VNPAY Return Controller - Response: " . json_encode($_GET));
        
        // Lấy các tham số từ URL
        $vnp_ResponseCode = isset($_GET['vnp_ResponseCode']) ? $_GET['vnp_ResponseCode'] : '';
        $vnp_TxnRef = isset($_GET['vnp_TxnRef']) ? $_GET['vnp_TxnRef'] : '';
        $vnp_Amount = isset($_GET['vnp_Amount']) ? $_GET['vnp_Amount'] : 0;
        $vnp_OrderInfo = isset($_GET['vnp_OrderInfo']) ? $_GET['vnp_OrderInfo'] : '';
        $vnp_TransactionNo = isset($_GET['vnp_TransactionNo']) ? $_GET['vnp_TransactionNo'] : '';
        $vnp_BankCode = isset($_GET['vnp_BankCode']) ? $_GET['vnp_BankCode'] : '';
        $vnp_PayDate = isset($_GET['vnp_PayDate']) ? $_GET['vnp_PayDate'] : date('YmdHis');
        $vnp_SecureHash = isset($_GET['vnp_SecureHash']) ? $_GET['vnp_SecureHash'] : '';

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
            
            // Cập nhật trạng thái đơn hàng
            $orderModel = $this->model("OrderModel");
            try {
                $result = $orderModel->updatePaymentStatus($vnp_TxnRef, 'đã thanh toán');
                if ($result) {
                    error_log("Order status updated successfully for order: " . $vnp_TxnRef);
                    
                    // ✅ XÓA GIỎ HÀNG TỪ SESSION VÀ DATABASE SAU KHI THANH TOÁN THÀNH CÔNG
                    unset($_SESSION['cart']);
                    unset($_SESSION['pending_order']);
                    
                    if (isset($_SESSION['user'])) {
                        try {
                            require_once(__DIR__ . "/../models/ShoppingCartModel.php");
                            $cartModel = $this->model("ShoppingCartModel");
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

        // Hiển thị trang kết quả
        $this->view("homePage", [
            "page" => "VnpayReturnView",
            "vnp_ResponseCode" => $vnp_ResponseCode,
            "vnp_TxnRef" => $vnp_TxnRef,
            "vnp_Amount" => $vnp_Amount,
            "vnp_OrderInfo" => $vnp_OrderInfo,
            "vnp_TransactionNo" => $vnp_TransactionNo,
            "vnp_BankCode" => $vnp_BankCode,
            "vnp_PayDate" => $vnp_PayDate,
            "orderModel" => new OrderModel()
        ]);
    }
}
?>
