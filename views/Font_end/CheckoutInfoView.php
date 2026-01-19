<?php
if (!isset($_SESSION)) {
    session_start();
}

// ✅ Tính subtotal trước để sử dụng cho shipping methods
$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
$subtotal = 0;
foreach ($cart as $item) {
    if (isset($item['from_promotion']) && $item['from_promotion'] && isset($item['promotional_price'])) {
        $gia = $item['promotional_price'];
    } else {
        $gia = $item['giaxuat'] - ($item['giaxuat'] * $item['khuyenmai'] / 100);
    }
    $subtotal += $gia * $item['qty'];
}
?>

<style>
    .checkout-container {
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        padding: 40px 0;
        min-height: 100vh;
    }

    .checkout-wrapper {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 30px;
        margin-bottom: 30px;
    }

    .checkout-form-section {
        background: white;
        border-radius: 15px;
        padding: 35px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.1);
    }

    .checkout-summary {
        background: white;
        border-radius: 15px;
        padding: 30px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.1);
        height: fit-content;
        position: sticky;
        top: 20px;
    }

    .section-title {
        font-size: 18px;
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 25px;
        padding-bottom: 15px;
        border-bottom: 3px solid #e74c3c;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 8px;
        display: block;
        font-size: 14px;
    }

    .form-group input,
    .form-group textarea {
        border: 2px solid #ecf0f1;
        border-radius: 8px;
        padding: 12px 15px;
        font-size: 14px;
        transition: all 0.3s ease;
    }

    .form-group input:focus,
    .form-group textarea:focus {
        border-color: #e74c3c;
        box-shadow: 0 0 0 3px rgba(231, 76, 60, 0.1);
        outline: none;
    }

    .form-group input[readonly] {
        background-color: #f8f9fa;
        cursor: not-allowed;
    }

    .discount-section {
        background: linear-gradient(135deg, #f5f7fa 0%, #ecf0f1 100%);
        border-radius: 10px;
        padding: 20px;
        margin: 25px 0;
    }

    .discount-group {
        display: grid;
        grid-template-columns: 1fr auto;
        gap: 10px;
    }

    .discount-group input {
        border: 2px solid #ecf0f1;
        border-radius: 8px;
        padding: 12px 15px;
    }

    .discount-group button {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        border-radius: 8px;
        padding: 12px 25px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .discount-group button:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
    }

    .discount-message {
        font-size: 13px;
        margin-top: 10px;
        animation: slideIn 0.3s ease;
    }

    @keyframes slideIn {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .payment-methods {
        background: #f8f9fa;
        border-radius: 10px;
        padding: 20px;
        margin: 25px 0;
    }

    .payment-option {
        display: flex;
        align-items: center;
        padding: 15px;
        margin-bottom: 12px;
        border: 2px solid #ecf0f1;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .payment-option:last-child {
        margin-bottom: 0;
    }

    .payment-option input[type="radio"] {
        width: 20px;
        height: 20px;
        cursor: pointer;
        margin-right: 15px;
        accent-color: #e74c3c;
    }

    .payment-option:has(input:checked) {
        background: #fff3f1;
        border-color: #e74c3c;
    }

    .payment-label {
        flex: 1;
        cursor: pointer;
        font-weight: 500;
        color: #2c3e50;
    }

    .shipping-methods {
        background: #f8f9fa;
        border-radius: 10px;
        padding: 20px;
        margin: 25px 0;
    }

    .shipping-option {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 15px;
        margin-bottom: 12px;
        border: 2px solid #ecf0f1;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .shipping-option:last-child {
        margin-bottom: 0;
    }

    .shipping-option input[type="radio"] {
        width: 20px;
        height: 20px;
        cursor: pointer;
        margin-right: 15px;
        accent-color: #3498db;
    }

    .shipping-option:has(input:checked) {
        background: #e8f4f8;
        border-color: #3498db;
    }

    .shipping-info {
        flex: 1;
        cursor: pointer;
    }

    .shipping-name {
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 3px;
    }

    .shipping-desc {
        font-size: 12px;
        color: #7f8c8d;
    }

    .shipping-price {
        font-weight: 700;
        color: #3498db;
        font-size: 16px;
    }

    .shipping-price.free {
        color: #27ae60;
    }

    .summary-title {
        font-size: 20px;
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 25px;
        padding-bottom: 15px;
        border-bottom: 3px solid #e74c3c;
    }

    .summary-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 15px;
        font-size: 14px;
        color: #555;
    }

    .summary-row.divider {
        border-top: 2px solid #ecf0f1;
        padding-top: 15px;
    }

    .summary-row.total {
        font-size: 18px;
        font-weight: 700;
        color: #2c3e50;
        margin-top: 20px;
    }

    .total-amount {
        color: #e74c3c;
        font-size: 22px;
        font-weight: 800;
    }

    .discount-badge {
        display: inline-block;
        background: #27ae60;
        color: white;
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
        margin-top: 5px;
    }

    .submit-button {
        width: 100%;
        background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
        color: white;
        border: none;
        padding: 15px;
        border-radius: 8px;
        font-size: 16px;
        font-weight: 700;
        cursor: pointer;
        margin-top: 30px;
        transition: all 0.3s ease;
        box-shadow: 0 5px 15px rgba(231, 76, 60, 0.3);
    }

    .submit-button:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(231, 76, 60, 0.4);
    }

    .submit-button:active {
        transform: translateY(0);
    }

    .page-header {
        text-align: center;
        margin-bottom: 40px;
    }

    .page-header h1 {
        font-size: 32px;
        font-weight: 800;
        color: #2c3e50;
        margin-bottom: 10px;
    }

    .page-header p {
        color: #7f8c8d;
        font-size: 16px;
    }

    @media (max-width: 768px) {
        .checkout-wrapper {
            grid-template-columns: 1fr;
        }

        .checkout-summary {
            position: relative;
            top: 0;
        }

        .page-header h1 {
            font-size: 24px;
        }
    }
</style>

<div class="checkout-container">
    <div class="container">
        <div class="page-header">
            <h1>🛒 Thông tin giao hàng</h1>
            <p>Vui lòng điền đầy đủ thông tin để hoàn tất đơn hàng</p>
        </div>

        <div class="checkout-wrapper">
            <!-- Form phần -->
            <div class="checkout-form-section">
                <form action="<?php echo APP_URL; ?>/CartController/processPayment" method="POST">
                    <!-- Thông tin người nhận -->
                    <div class="section-title">👤 Thông tin người nhận</div>
                    
                    <div class="form-group">
                        <label for="receiver">Tên người nhận *</label>
                        <input type="text" class="form-control" id="receiver" name="receiver" 
                            value="<?php echo isset($_SESSION['user']['fullname']) ? htmlspecialchars($_SESSION['user']['fullname']) : ''; ?>" 
                            placeholder="Nhập họ tên đầy đủ" required>
                    </div>

                    <div class="form-group">
                        <label for="phone">Số điện thoại *</label>
                        <input type="tel" class="form-control" id="phone" name="phone" 
                            placeholder="Nhập số điện thoại (10-11 số)" required>
                    </div>

                    <div class="form-group">
                        <label for="email">Email *</label>
                        <input type="email" class="form-control" id="email" name="email" 
                            value="<?php echo isset($_SESSION['user']['email']) ? htmlspecialchars($_SESSION['user']['email']) : ''; ?>" 
                            readonly>
                    </div>

                    <!-- Địa chỉ giao hàng -->
                    <div class="section-title">📍 Địa chỉ giao hàng</div>
                    
                    <div class="form-group">
                        <label for="address">Địa chỉ chi tiết *</label>
                        <textarea class="form-control" id="address" name="address" rows="3" 
                            placeholder="Vd: Số nhà, tên đường, quận/huyện, tỉnh/thành phố" required></textarea>
                    </div>

                    <!-- Phương thức vận chuyển -->
                    <div class="section-title">🚚 Phương thức vận chuyển</div>
                    
                    <div class="shipping-methods">
                        <?php
                        require_once 'models/ShippingMethodModel.php';
                        $shippingModel = new ShippingMethodModel();
                        $shippingMethods = $shippingModel->getActiveShippingMethods();
                        $freeShippingCheck = $shippingModel->checkFreeShippingEligibility($subtotal);
                        
                        $firstChecked = true;
                        foreach ($shippingMethods as $method):
                            // Bỏ qua miễn phí ship nếu không đủ điều kiện
                            if ($method['name'] === 'Miễn phí vận chuyển' && !$freeShippingCheck['eligible']) {
                                continue;
                            }
                        ?>
                        <label class="shipping-option">
                            <input type="radio" 
                                   name="shipping_method" 
                                   value="<?php echo $method['id']; ?>" 
                                   data-price="<?php echo $method['price']; ?>"
                                   <?php echo $firstChecked ? 'checked' : ''; ?>
                                   onchange="updateShippingFee()">
                            <div class="shipping-info">
                                <div class="shipping-name"><?php echo $method['icon']; ?> <?php echo htmlspecialchars($method['name']); ?></div>
                                <div class="shipping-desc">
                                    <?php echo htmlspecialchars($method['description']); ?>
                                    <?php if ($method['estimated_days']): ?>
                                        <br>⏱️ Giao trong: <strong><?php echo $method['estimated_days']; ?></strong>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <span class="shipping-price <?php echo $method['price'] == 0 ? 'free' : ''; ?>">
                                <?php 
                                if ($method['price'] == 0) {
                                    echo 'MIỄN PHÍ';
                                } else {
                                    echo number_format($method['price'], 0, ',', '.') . ' ₫';
                                }
                                ?>
                            </span>
                        </label>
                        <?php 
                            $firstChecked = false;
                        endforeach; 
                        ?>
                        
                        <?php if (!$freeShippingCheck['eligible']): ?>
                        <div style="background: #fff3cd; border-left: 4px solid #ffc107; padding: 10px; margin-top: 10px; border-radius: 4px; font-size: 13px;">
                            🎁 <?php echo $freeShippingCheck['message']; ?>
                        </div>
                        <?php endif; ?>
                    </div>

                    <!-- Mã giảm giá -->
                    <div class="discount-section">
                        <label style="font-weight: 600; color: #2c3e50; margin-bottom: 12px; display: block;">🎁 Mã giảm giá</label>
                        <div class="discount-group">
                            <input type="text" class="form-control" id="discount_code" name="discount_code" 
                                placeholder="Nhập mã giảm giá (nếu có)" maxlength="50">
                            <button type="button" onclick="applyDiscount()">Áp dụng</button>
                        </div>
                        <div id="discount_message" class="discount-message"></div>
                    </div>

                    <!-- Phương thức thanh toán -->
                    <div class="section-title">💳 Phương thức thanh toán</div>
                    
                    <div class="payment-methods">
                        <label class="payment-option">
                            <input type="radio" name="payment_method" value="cod">
                            <span class="payment-label">💰 Thanh toán khi nhận hàng (COD)</span>
                        </label>
                        <label class="payment-option">
                            <input type="radio" name="payment_method" value="vnpay" checked>
                            <span class="payment-label">🏦 Thanh toán qua VNPAY (QR Code)</span>
                        </label>
                    </div>

                    <!-- Hidden inputs -->
                    <input type="hidden" id="applied_discount_code" name="applied_discount_code" value="">
                    <input type="hidden" id="applied_discount_amount" name="applied_discount_amount" value="0">

                    <button type="submit" class="submit-button">Xác nhận đặt hàng</button>
                </form>
            </div>

            <!-- Tóm tắt đơn hàng -->
            <div class="checkout-summary">
                <div class="summary-title">📦 Tóm tắt đơn hàng</div>
                
                <div class="summary-row">
                    <span>Tổng tiền sản phẩm:</span>
                    <span id="subtotal_display"><?php 
                        // Subtotal đã được tính ở đầu file
                        echo number_format($subtotal, 0, ',', '.'); 
                    ?> ₫</span>
                </div>

                <div class="summary-row">
                    <span>Tiền giảm:</span>
                    <span id="discount_display" class="text-success" style="color: #27ae60; font-weight: 600;">0 ₫</span>
                </div>

                <div class="summary-row">
                    <span>Phí vận chuyển:</span>
                    <span id="shipping_display" style="color: #3498db; font-weight: 600;">
                        <?php 
                        // Hiển thị phí ship mặc định (phương thức đầu tiên)
                        if (!empty($shippingMethods)) {
                            $defaultMethod = $shippingMethods[0];
                            if ($defaultMethod['price'] == 0) {
                                echo 'MIỄN PHÍ';
                            } else {
                                echo number_format($defaultMethod['price'], 0, ',', '.') . ' ₫';
                            }
                        } else {
                            echo '0 ₫';
                        }
                        ?>
                    </span>
                </div>

                <div class="summary-row divider">
                    <span style="font-weight: 600; color: #2c3e50;">Tổng tiền thanh toán:</span>
                    <span class="total-amount" id="total_display"><?php 
                        echo number_format($subtotal, 0, ',', '.'); 
                    ?> ₫</span>
                </div>

                <div style="background: #ecf0f1; border-radius: 8px; padding: 15px; margin-top: 20px; text-align: center;">
                    <p style="margin: 0; color: #7f8c8d; font-size: 12px;">✅ Giao hàng nhanh chóng<br>🔒 Thanh toán an toàn<br>📞 Hỗ trợ 24/7</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Biến lưu trữ subtotal (đã tính ở PHP)
let subtotal = <?php echo $subtotal; ?>;

let currentDiscount = 0;
let currentShippingFee = <?php echo !empty($shippingMethods) ? $shippingMethods[0]['price'] : 0; ?>;

// Hàm format tiền
function formatCurrency(amount) {
    return new Intl.NumberFormat('vi-VN').format(amount) + ' ₫';
}

// Hàm cập nhật tổng tiền
function updateTotal() {
    const total = subtotal - currentDiscount + currentShippingFee;
    document.getElementById('total_display').textContent = formatCurrency(total);
}

// Hàm cập nhật phí ship khi thay đổi phương thức vận chuyển
function updateShippingFee() {
    const selectedShipping = document.querySelector('input[name="shipping_method"]:checked');
    if (selectedShipping) {
        currentShippingFee = parseFloat(selectedShipping.dataset.price) || 0;
        
        const shippingDisplay = document.getElementById('shipping_display');
        if (currentShippingFee === 0) {
            shippingDisplay.textContent = 'MIỄN PHÍ';
            shippingDisplay.style.color = '#27ae60';
        } else {
            shippingDisplay.textContent = formatCurrency(currentShippingFee);
            shippingDisplay.style.color = '#3498db';
        }
        
        updateTotal();
    }
}

// Khởi tạo tổng tiền ban đầu
updateTotal();

// Hàm áp dụng mã giảm giá
function applyDiscount() {
    const code = document.getElementById('discount_code').value.trim();
    
    if (!code) {
        document.getElementById('discount_message').innerHTML = '<span class="text-warning">⚠️ Vui lòng nhập mã giảm giá</span>';
        return;
    }

    console.log('Áp dụng mã:', code, 'Tổng:', subtotal);

    // Gửi request để kiểm tra mã
    fetch('<?= APP_URL ?>/DiscountCodeController/verify', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'code=' + encodeURIComponent(code) + '&total=' + subtotal
    })
    .then(response => {
        console.log('Response status:', response.status);
        return response.json();
    })
    .then(data => {
        console.log('Response data:', data);
        if (data.valid) {
            // Mã hợp lệ
            const discountAmount = data.discount_amount;
            currentDiscount = discountAmount;
            
            document.getElementById('discount_message').innerHTML = 
                '<span class="text-success">✓ Áp dụng thành công! Giảm: ' + formatCurrency(discountAmount) + '</span>';
            
            document.getElementById('discount_display').innerHTML = formatCurrency(discountAmount);
            
            // ✅ Lưu mã giảm giá vào hidden input (đã có sẵn)
            document.getElementById('applied_discount_code').value = code;
            document.getElementById('applied_discount_amount').value = discountAmount;
            
            // ✅ Cập nhật tổng tiền
            updateTotal();
            
        } else {
            // Mã không hợp lệ
            document.getElementById('discount_message').innerHTML = 
                '<span class="text-danger">✗ ' + (data.message || 'Mã giảm giá không hợp lệ') + '</span>';
            
            currentDiscount = 0;
            document.getElementById('discount_display').innerHTML = '0 ₫';
            
            // ✅ Xóa mã giảm giá (reset về 0)
            document.getElementById('applied_discount_code').value = '';
            document.getElementById('applied_discount_amount').value = '0';
            
            // ✅ Cập nhật tổng tiền
            updateTotal();
        }
    })
    .catch(error => {
        console.error('Fetch error:', error);
        document.getElementById('discount_message').innerHTML = 
            '<span class="text-danger">✗ Lỗi kết nối server: ' + error.message + '</span>';
    });
}

// Cho phép nhấn Enter để áp dụng mã
document.getElementById('discount_code').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        e.preventDefault();
        applyDiscount();
    }
});
</script>
