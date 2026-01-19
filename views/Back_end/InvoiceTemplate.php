<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hóa Đơn - <?php echo htmlspecialchars($order['order_code']); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f5f5;
            padding: 20px;
        }

        .invoice-container {
            max-width: 900px;
            margin: 0 auto;
            background: white;
            padding: 40px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            border-radius: 5px;
        }

        .invoice-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 40px;
            border-bottom: 2px solid #007bff;
            padding-bottom: 20px;
        }

        .company-info h1 {
            color: #007bff;
            font-size: 28px;
            margin-bottom: 10px;
        }

        .company-info p {
            color: #666;
            font-size: 13px;
            line-height: 1.6;
        }

        .invoice-title {
            text-align: right;
        }

        .invoice-title h2 {
            color: #333;
            font-size: 24px;
            margin-bottom: 10px;
        }

        .invoice-details {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
            margin-bottom: 40px;
        }

        .invoice-details-section {
            font-size: 13px;
            line-height: 1.8;
        }

        .invoice-details-section h5 {
            color: #007bff;
            font-weight: bold;
            margin-bottom: 10px;
            text-transform: uppercase;
        }

        .invoice-details-section p {
            color: #555;
            margin-bottom: 5px;
        }

        .detail-label {
            font-weight: bold;
            color: #333;
            width: 100px;
            display: inline-block;
        }

        .detail-value {
            color: #666;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        thead {
            background: #f8f9fa;
            border-top: 2px solid #007bff;
            border-bottom: 2px solid #007bff;
        }

        th {
            padding: 15px;
            text-align: left;
            font-weight: bold;
            color: #333;
            font-size: 13px;
            text-transform: uppercase;
        }

        td {
            padding: 12px 15px;
            border-bottom: 1px solid #eee;
            font-size: 13px;
        }

        tbody tr:hover {
            background: #f9f9f9;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .amount-column {
            text-align: right;
            width: 15%;
        }

        .quantity-column {
            text-align: center;
            width: 10%;
        }

        .price-column {
            text-align: right;
            width: 15%;
        }

        .total-section {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 40px;
        }

        .total-table {
            width: 350px;
        }

        .total-table .row {
            display: flex;
            justify-content: space-between;
            padding: 8px 15px;
            border-bottom: 1px solid #eee;
            font-size: 13px;
        }

        .total-table .row.subtotal {
            color: #666;
        }

        .total-table .row.total {
            background: #f8f9fa;
            font-weight: bold;
            border: 2px solid #007bff;
            color: #007bff;
            font-size: 16px;
            padding: 12px 15px;
        }

        .payment-info {
            display: flex;
            gap: 40px;
            margin-bottom: 40px;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 5px;
        }

        .payment-info-col {
            flex: 1;
            font-size: 13px;
        }

        .payment-info-col h5 {
            color: #007bff;
            font-weight: bold;
            margin-bottom: 10px;
            text-transform: uppercase;
        }

        .payment-info-col p {
            color: #666;
            margin-bottom: 8px;
        }

        .footer {
            text-align: center;
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            color: #999;
            font-size: 12px;
        }

        .status-badge {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 3px;
            font-weight: bold;
            font-size: 12px;
        }

        .status-pending {
            background: #fff3cd;
            color: #856404;
        }

        .status-completed {
            background: #d4edda;
            color: #155724;
        }

        .status-cancelled {
            background: #f8d7da;
            color: #721c24;
        }

        .no-print {
            margin-bottom: 20px;
            text-align: center;
        }

        @media print {
            body {
                background: white;
                padding: 0;
            }

            .invoice-container {
                box-shadow: none;
                padding: 0;
            }

            .no-print {
                display: none;
            }

            a {
                color: inherit;
                text-decoration: none;
            }
        }
    </style>
</head>
<body>
    <div class="invoice-container">
        <!-- Print & Close Buttons -->
        <div class="no-print">
            <button onclick="window.print()" class="btn btn-primary me-2">
                <i class="bi bi-printer"></i> In Hóa Đơn
            </button>
            <button onclick="window.history.back()" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Quay Lại
            </button>
        </div>

        <!-- Invoice Header -->
        <div class="invoice-header">
            <div class="company-info">
                <h1>🏪 Shop Online</h1>
                <p>
                    <strong>Địa chỉ:</strong> 123 Đường ABC, TP.HCM<br>
                    <strong>SĐT:</strong> (028) 1234 5678<br>
                    <strong>Email:</strong> contact@shopOnline.vn<br>
                    <strong>Website:</strong> www.shopOnline.vn
                </p>
            </div>
            <div class="invoice-title">
                <h2>HÓA ĐƠN</h2>
                <p style="color: #666; font-size: 13px;">
                    <strong>Mã đơn:</strong> <?php echo htmlspecialchars($order['order_code']); ?><br>
                    <strong>Ngày tạo:</strong> <?php echo date('d/m/Y H:i', strtotime($order['created_at'])); ?>
                </p>
            </div>
        </div>

        <!-- Customer & Order Details -->
        <div class="invoice-details">
            <div class="invoice-details-section">
                <h5>📦 Thông Tin Khách Hàng</h5>
                <p>
                    <span class="detail-label">Tên:</span>
                    <span class="detail-value"><?php echo htmlspecialchars($order['receiver']); ?></span>
                </p>
                <p>
                    <span class="detail-label">Email:</span>
                    <span class="detail-value"><?php echo htmlspecialchars($order['user_email']); ?></span>
                </p>
                <p>
                    <span class="detail-label">Điện thoại:</span>
                    <span class="detail-value"><?php echo htmlspecialchars($order['phone'] ?? '—'); ?></span>
                </p>
            </div>
            <div class="invoice-details-section">
                <h5>📍 Địa Chỉ Giao Hàng</h5>
                <p>
                    <span class="detail-label">Địa chỉ:</span>
                </p>
                <p style="margin-bottom: 15px;">
                    <span class="detail-value"><?php echo htmlspecialchars($order['address'] ?? '—'); ?></span>
                </p>
                <p>
                    <span class="detail-label">Trạng thái:</span>
                </p>
                <p>
                    <span class="status-badge <?php 
                        switch($order['trangthai']) {
                            case 'chờ xét duyệt': echo 'status-pending'; break;
                            case 'đã thanh toán':
                            case 'đang giao hàng': echo 'status-completed'; break;
                            case 'đã hủy': echo 'status-cancelled'; break;
                        }
                    ?>">
                        <?php echo ucfirst($order['trangthai']); ?>
                    </span>
                </p>
            </div>
        </div>

        <!-- Products Table -->
        <table>
            <thead>
                <tr>
                    <th>Sản Phẩm</th>
                    <th class="quantity-column">Số Lượng</th>
                    <th class="price-column">Đơn Giá</th>
                    <th class="amount-column">Thành Tiền</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($details)): ?>
                    <?php foreach ($details as $item): ?>
                        <tr>
                            <td>
                                <strong><?php echo htmlspecialchars($item['tensp'] ?: $item['product_name'] ?: 'Sản phẩm không xác định'); ?></strong>
                                <?php if (!empty($item['color'])): ?>
                                    <br><small class="text-muted">Màu: <?php echo htmlspecialchars($item['color']); ?></small>
                                <?php endif; ?>
                                <?php if (!empty($item['size'])): ?>
                                    <br><small class="text-muted">Size: <?php echo htmlspecialchars($item['size']); ?></small>
                                <?php endif; ?>
                            </td>
                            <td class="quantity-column"><?php echo intval($item['quantity']); ?></td>
                            <td class="price-column"><?php echo number_format($item['price'], 0, ',', '.'); ?> ₫</td>
                            <td class="amount-column">
                                <strong><?php echo number_format($item['quantity'] * $item['price'], 0, ',', '.'); ?> ₫</strong>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>

        <!-- Totals -->
        <div class="total-section">
            <div class="total-table">
                <div class="row subtotal">
                    <span>Tổng tiền hàng:</span>
                    <span><?php echo number_format($order['subtotal'] ?? 0, 0, ',', '.'); ?> ₫</span>
                </div>
                <?php if (!empty($order['discount'])): ?>
                    <div class="row subtotal">
                        <span>Giảm giá:</span>
                        <span>-<?php echo number_format($order['discount'], 0, ',', '.'); ?> ₫</span>
                    </div>
                <?php endif; ?>
                <?php if (!empty($order['shipping_fee'])): ?>
                    <div class="row subtotal">
                        <span>Phí vận chuyển:</span>
                        <span><?php echo number_format($order['shipping_fee'], 0, ',', '.'); ?> ₫</span>
                    </div>
                <?php endif; ?>
                <div class="row total">
                    <span>Tổng cộng:</span>
                    <span><?php echo number_format($order['total_amount'], 0, ',', '.'); ?> ₫</span>
                </div>
            </div>
        </div>

        <!-- Payment Info -->
        <div class="payment-info">
            <div class="payment-info-col">
                <h5>💳 Phương Thức Thanh Toán</h5>
                <p><?php echo htmlspecialchars($order['payment_method'] ?? 'Thanh toán khi nhận hàng'); ?></p>
            </div>
            <div class="payment-info-col">
                <h5>📅 Ngày Dự Kiến Giao Hàng</h5>
                <p><?php echo !empty($order['expected_delivery']) ? date('d/m/Y', strtotime($order['expected_delivery'])) : 'Chưa xác định'; ?></p>
            </div>
        </div>

        <!-- Notes -->
        <?php if (!empty($order['notes'])): ?>
            <div style="margin-bottom: 40px; padding: 15px; background: #f9f9f9; border-left: 4px solid #007bff;">
                <h5 style="color: #007bff; font-size: 13px; margin-bottom: 8px; text-transform: uppercase;">
                    📝 Ghi Chú
                </h5>
                <p style="color: #666; font-size: 13px; margin: 0;">
                    <?php echo nl2br(htmlspecialchars($order['notes'])); ?>
                </p>
            </div>
        <?php endif; ?>

        <!-- Footer -->
        <div class="footer">
            <p>Cảm ơn bạn đã mua sắm tại Shop Online. Nếu có thắc mắc, vui lòng liên hệ: (028) 1234 5678</p>
            <p>Hóa đơn này được tạo tự động từ hệ thống quản lý đơn hàng.</p>
            <p style="margin-top: 10px; color: #ccc;">
                © 2025 Shop Online. All Rights Reserved.
            </p>
        </div>
    </div>

    <script>
        // Auto-focus to print dialog on load
        window.onload = function() {
            // Uncomment to auto-print
            // window.print();
        };
    </script>
</body>
</html>
