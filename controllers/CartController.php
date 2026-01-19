<?php
require_once 'models/OrderModel.php';
require_once 'models/OrderDetailModel.php';
require_once 'models/UserModel.php';
require_once 'models/ShippingMethodModel.php';

class CartController extends Controller {
    public function index() {
        // Hiển thị trang giỏ hàng
        $cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
      //  $this->render('Font_end/OrderView.php', ['cart' => $cart]);
       $this->view("homePage",["page"=>"OrderView",'listProductOrder' =>$cart]);
    }

    public function checkout() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if (!isset($_SESSION['user'])) {
            $_SESSION['return_url'] = APP_URL . '/CartController/checkout';
            header('Location: ' . APP_URL . '/AuthController/showLogin');
            exit();
        }
        
        $cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
        if (empty($cart)) {
            header('Location: ' . APP_URL . '/CartController/index');
            exit();
        }
        
        // Hiển thị form chọn phương thức thanh toán
        $totalAmount = 0;
        foreach ($cart as $item) {
            $totalAmount += ($item['sale_price'] ?? $item['price']) * $item['quantity'];
        }
        
        $this->view("homePage", [
            "page" => "CheckoutInfoView",
            'cart' => $cart,
            'totalAmount' => $totalAmount
        ]);
    }

    public function confirmPayment() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user'])) {
            $_SESSION['return_url'] = $_SERVER['REQUEST_URI'];
            header('Location: ' . APP_URL . 'AuthController/login');
            exit();
        }

        if (isset($_GET['order_id'])) {
            $orderCode = $_GET['order_id'];
            
            // Cập nhật trạng thái đơn hàng thành đã thanh toán
            $orderModel = $this->model("OrderModel");
            $result = $orderModel->updatePaymentStatus($orderCode, 1); // 1 = đã thanh toán
            
            if ($result) {
                // Xóa thông tin thanh toán tạm thời
                if (isset($_SESSION['payment_info'])) {
                    unset($_SESSION['payment_info']);
                }
                
                // Xóa giỏ hàng sau khi thanh toán thành công
                unset($_SESSION['cart']);
                
                // Chuyển hướng đến trang vnpay_return
                header('Location: ' . APP_URL . '/vnpay_php/vnpay_return.php');
                exit();
            }
        }
        
        header('Location: ' . APP_URL . '/CartController/index');
        exit();
    }
    public function processPayment() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user'])) {
            // Lưu thông tin form trước khi chuyển đến trang đăng nhập
            $_SESSION['checkout_data'] = $_POST;
            $_SESSION['return_url'] = APP_URL . '/CartController/checkout';
            
            error_log('User not logged in. Saving return URL: ' . $_SESSION['return_url']);
            header('Location: ' . APP_URL . '/AuthController/showLogin');
            exit();
        }

        if (empty($_SESSION['cart'])) {
            header('Location: ' . APP_URL . '/CartController/index');
            exit();
        }

        // Lấy thông tin user hiện tại
        $userModel = $this->model("UserModel");
        $stmt = $userModel->findByEmail($_SESSION['user']['email']);
        $userData = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$userData) {
            header('Location: /AuthController/login');
            exit();
        }

        $paymentMethod = $_POST['payment_method'] ?? '';
        $cart = $_SESSION['cart'];
        $user = array_merge($_SESSION['user'], ['id' => $userData['user_id']]);

        // Debug thông tin
        error_log("User in processPayment: " . print_r($user, true));
        if (!isset($user['id'])) {
            // Nếu không có ID, lấy lại thông tin user từ database
            $userModel = $this->model("UserModel");
            $stmt = $userModel->findByEmail($user['email']);
            $userData = $stmt->fetch(PDO::FETCH_ASSOC);
            $user['id'] = $userData['id'];
            $_SESSION['user'] = $user; // Cập nhật lại session
        }

        $totalAmount = 0;
        foreach ($cart as $item) {
            // ✅ NẾU TỪ TRANG KHUYẾN MẠI: Dùng giá khuyến mãi đã lưu
            if (isset($item['from_promotion']) && $item['from_promotion'] && isset($item['promotional_price'])) {
                $gia = $item['promotional_price'];
            } else {
                // ✅ BÌNH THƯỜNG: Tính từ khuyến mại %
                $gia = $item['giaxuat'] - ($item['giaxuat'] * $item['khuyenmai'] / 100);
            }
            $totalAmount += $gia * $item['qty'];
        }

        // ✅ XỬ LÝ MÃ GIẢM GIÁ NẾU CÓ
        $discountCode = $_POST['applied_discount_code'] ?? '';
        $discountAmount = 0;
        $finalTotal = $totalAmount;
        
        error_log("CartController::processPayment - totalAmount: $totalAmount, discountCode: $discountCode");
        
        if ($discountCode) {
            $discountCodeModel = $this->model("DiscountCodeModel");
            $discountResult = $discountCodeModel->calculateDiscount($discountCode, $totalAmount);
            
            if ($discountResult['valid']) {
                $discountAmount = $discountResult['discount_amount'];
                $finalTotal = $discountResult['final_total'];
                
                // Tăng lượt sử dụng mã giảm giá
                $discountCodeModel->incrementUsage($discountCode);
            }
        }

        $orderModel = $this->model("OrderModel");
        $orderDetailModel = $this->model("OrderDetailModel");
        $shippingMethodModel = $this->model("ShippingMethodModel");
        $orderCode = 'HD' . time();
        
        // Lấy thông tin giao hàng từ form
        $receiver = $_POST['receiver'] ?? $user['fullname'];
        $phone = $_POST['phone'] ?? '';
        $address = $_POST['address'] ?? '';
        
        // ✅ XỬ LÝ PHƯƠNG THỨC VẬN CHUYỂN VÀ PHÍ SHIP
        $shippingMethodId = $_POST['shipping_method'] ?? null;
        $shippingFee = 0;
        
        if ($shippingMethodId) {
            $shippingResult = $shippingMethodModel->calculateShippingFee($shippingMethodId, $totalAmount);
            
            if ($shippingResult['success']) {
                $shippingFee = $shippingResult['shipping_fee'];
                error_log("CartController::processPayment - Shipping fee: $shippingFee for method ID: $shippingMethodId");
            } else {
                // Nếu không đủ điều kiện miễn phí ship, chuyển về phương thức mặc định
                $defaultMethod = $shippingMethodModel->getDefaultShippingMethod();
                if ($defaultMethod) {
                    $shippingMethodId = $defaultMethod['id'];
                    $shippingFee = $defaultMethod['price'];
                }
            }
        } else {
            // Nếu không chọn, dùng phương thức mặc định
            $defaultMethod = $shippingMethodModel->getDefaultShippingMethod();
            if ($defaultMethod) {
                $shippingMethodId = $defaultMethod['id'];
                $shippingFee = $defaultMethod['price'];
            }
        }
        
        // ✅ TÍNH TỔNG TIỀN CUỐI CÙNG (BAO GỒM PHÍ SHIP)
        $finalTotal = $finalTotal + $shippingFee;
        
        error_log("CartController::processPayment - Final total with shipping: $finalTotal (shipping: $shippingFee)");
        
        // ✅ TẠO ĐƠN HÀNG VỚI TỔNG TIỀN ĐÃ GIẢM, MÃ GIẢM GIÁ & PHÍ SHIP
        $orderId = $orderModel->createOrderWithShipping(
            $user['email'],
            $orderCode,
            $finalTotal,        // ✅ Tổng tiền cuối cùng (đã bao gồm ship)
            $receiver,
            $phone,
            $address,
            $discountCode,      // ✅ Truyền mã giảm giá
            $discountAmount,    // ✅ Truyền tiền giảm
            $shippingMethodId,  // ✅ Truyền ID phương thức vận chuyển
            $shippingFee        // ✅ Truyền phí ship
        );
        foreach ($cart as $item) {
            // ✅ NẾU TỪ TRANG KHUYẾN MẠI: Dùng giá khuyến mãi đã lưu
            if ($item['from_promotion'] && isset($item['promotional_price'])) {
                $gia = $item['promotional_price'];
            } else {
                // ✅ BÌNH THƯỜNG: Tính từ khuyến mại %
                $gia = $item['giaxuat'] - ($item['giaxuat'] * $item['khuyenmai'] / 100);
            }
            
            // ✅ TÍnh toán giá sau giảm từ mã giảm giá
            $discountedPrice = $gia;
            $discountedTotal = $gia * $item['qty'];
            
            if ($discountCode && $discountAmount > 0) {
                // ✅ Tính tỷ lệ giảm cho sản phẩm này (dùng totalAmount trước giảm mã)
                $productRatio = $discountedTotal / $totalAmount;  // ✅ Tỷ lệ của sản phẩm so với tổng TRƯỚC giảm mã
                $productDiscount = $discountAmount * $productRatio;
                $discountedPrice = $gia - ($productDiscount / $item['qty']);
                $discountedTotal = $discountedPrice * $item['qty'];
            }
            
            // Thêm chi tiết đơn hàng với giá đã giảm
            $orderDetailModel->addOrderDetail(
                $orderId,
                $item['masp'],
                $item['qty'],
                $item['giaxuat'],
                $discountedPrice,  // ✅ Giá sau giảm
                $discountedTotal,   // ✅ Thành tiền sau giảm
                $item['hinhanh'],
                '',
                $item['tensp']
            );
            
            // Cập nhật số lượng sản phẩm trong kho
            $productModel = $this->model("AdProducModel");
            $productModel->updateQuantity($item['masp'], $item['qty']);
        }

        // Lưu thông tin đơn hàng vào session để xử lý sau khi thanh toán
        $_SESSION['pending_order'] = [
            'order_id' => $orderId,
            'order_code' => $orderCode,
            'amount' => $finalTotal,  // ✅ Dùng finalTotal
            'discount_code' => $discountCode,  // ✅ Lưu mã giảm giá
            'discount_amount' => $discountAmount  // ✅ Lưu tiền giảm
        ];

        if ($paymentMethod === 'vnpay') {
            // Chuyển hướng đến trang thanh toán VNPAY
            $vnp_TxnRef = $orderCode;
            $vnp_OrderInfo = 'Thanh toan don hang ' . $orderCode;
            $vnp_OrderType = 'billpayment';
            $vnp_Amount = $finalTotal * 100;  // ✅ Dùng finalTotal
            $vnp_Locale = 'vn';
            $vnp_BankCode = '';
            $vnp_IpAddr = $_SERVER['REMOTE_ADDR'];
            
            // Khởi tạo đơn hàng với VNPAY
            require_once('./vnpay_php/config.php');
            
            $inputData = array(
                "vnp_Version" => "2.1.0",
                "vnp_TmnCode" => $vnp_TmnCode,
                "vnp_Amount" => $vnp_Amount,
                "vnp_Command" => "pay",
                "vnp_CreateDate" => date('YmdHis'),
                "vnp_CurrCode" => "VND",
                "vnp_IpAddr" => $_SERVER['REMOTE_ADDR'],
                "vnp_Locale" => $vnp_Locale,
                "vnp_OrderInfo" => $vnp_OrderInfo,
                "vnp_OrderType" => $vnp_OrderType,
                "vnp_ReturnUrl" => $vnp_Returnurl,
                "vnp_TxnRef" => $vnp_TxnRef,
                "vnp_ExpireDate" => date('YmdHis', strtotime('+15 minutes'))
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

            // Thêm nút xác nhận thủ công
            $_SESSION['payment_confirmation'] = [
                'order_code' => $orderCode,
                'amount' => $vnp_Amount
            ];
            
            // Cho phép người dùng chọn: thanh toán VNPAY hoặc xác nhận thủ công
            echo '
            <div class="container mt-5">
                <div class="card">
                    <div class="card-header">
                        <h3 class="text-center">Phương thức xác nhận thanh toán</h3>
                    </div>
                    <div class="card-body text-center">
                        <a href="'.$vnp_Url.'" class="btn btn-primary btn-lg m-2">Thanh toán qua VNPAY</a>
                        <a href="'.APP_URL.'/vnpay_php/confirm_payment.php?order_code='.$orderCode.'&amount='.$vnp_Amount.'" 
                           class="btn btn-success btn-lg m-2">Xác nhận đã thanh toán</a>
                    </div>
                </div>
            </div>';
            exit();
        } else {
            // Thanh toán khi nhận hàng (COD)
            $_SESSION['order_success'] = "Đặt hàng thành công! Mã đơn hàng: " . $orderCode;
            
            // ✅ GỬI EMAIL XÁC NHẬN ĐƠN HÀNG
            try {
                $orderModel->sendOrderConfirmationEmail($orderId);
            } catch (Exception $e) {
                error_log("Lỗi gửi email xác nhận: " . $e->getMessage());
            }
            
            // ✅ XÓA GIỎ HÀNG TỪ SESSION VÀ DATABASE
            unset($_SESSION['cart']);
            unset($_SESSION['pending_order']);
            
            // ✅ XÓA GIỎ HÀNG TỪ DATABASE
            if (isset($_SESSION['user'])) {
                try {
                    $cartModel = $this->model('ShoppingCartModel');
                    $cartModel->clearCart($_SESSION['user']['id']);
                    error_log('Cart cleared from database after checkout: User ' . $_SESSION['user']['id']);
                } catch (Exception $e) {
                    error_log('Error clearing cart from database: ' . $e->getMessage());
                }
            }
            
            // Chuyển hướng đến trang lịch sử đơn hàng
            header('Location: ' . APP_URL . '/Home/orderHistory');
            exit();
        }
    }
}
