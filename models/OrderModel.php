<?php
require_once 'BaseModel.php';
require_once __DIR__ . '/AdProducModel.php';

class OrderModel extends BaseModel {
    protected $table = 'orders';
    
    public function getRevenueStats($period = 'month', $startDate = null, $endDate = null) {
        $groupBy = '';
        $dateFormat = '';
        
        switch($period) {
            case 'day':
                $groupBy = "DATE(created_at)";
                $dateFormat = "%Y-%m-%d";
                break;
            case 'month':
                $groupBy = "MONTH(created_at), YEAR(created_at)";
                $dateFormat = "%Y-%m";
                break;
            case 'year':
                $groupBy = "YEAR(created_at)";
                $dateFormat = "%Y";
                break;
        }

        try {
            $sql = "SELECT 
                        DATE_FORMAT(created_at, '$dateFormat') as period,
                        COUNT(*) as order_count,
                        SUM(total_amount) as total_revenue
                    FROM {$this->table}
                    WHERE trangthai = 'đã thanh toán'
                        AND created_at BETWEEN ? AND ?
                    GROUP BY $groupBy
                    ORDER BY created_at ASC";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Debug log
            error_log("Revenue stats query: " . $sql);
            error_log("Parameters: " . $startDate . " to " . $endDate);
            error_log("Results: " . print_r($results, true));
            
            return $results;
        } catch (PDOException $e) {
            error_log("Error in getRevenueStats: " . $e->getMessage());
            throw $e;
        }
    }
    
    // Tính tăng trưởng doanh thu
    public function getRevenueGrowth($startDate, $endDate, $period = 'month') {
        try {
            $groupBy = '';
            $dateFormat = '';
            
            switch($period) {
                case 'day':
                    $groupBy = "DATE(created_at)";
                    $dateFormat = "%Y-%m-%d";
                    break;
                case 'month':
                    $groupBy = "MONTH(created_at), YEAR(created_at)";
                    $dateFormat = "%Y-%m";
                    break;
                case 'year':
                    $groupBy = "YEAR(created_at)";
                    $dateFormat = "%Y";
                    break;
            }
            
            $sql = "SELECT 
                        DATE_FORMAT(created_at, '$dateFormat') as period,
                        SUM(total_amount) as revenue,
                        COUNT(*) as order_count
                    FROM {$this->table}
                    WHERE trangthai = 'đã thanh toán'
                        AND created_at BETWEEN ? AND ?
                    GROUP BY $groupBy
                    ORDER BY created_at ASC";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Tính tăng trưởng
            $growthData = [];
            for ($i = 0; $i < count($results); $i++) {
                $current = $results[$i];
                $growth = 0;
                
                if ($i > 0) {
                    $previous = $results[$i - 1];
                    $growth = (($current['revenue'] - $previous['revenue']) / $previous['revenue']) * 100;
                }
                
                $growthData[] = [
                    'period' => $current['period'],
                    'revenue' => $current['revenue'],
                    'order_count' => $current['order_count'],
                    'growth' => $growth
                ];
            }
            
            return $growthData;
        } catch (PDOException $e) {
            error_log("Error in getRevenueGrowth: " . $e->getMessage());
            return [];
        }
    }

    // ====================== PHẦN NGƯỜI DÙNG ======================
    public function getOrderDetailsByOrderId($orderId) {
        $sql = "SELECT * FROM order_details WHERE order_id = ?";
        return $this->select($sql, [$orderId]);
    }

    public function createOrder($userId, $orderCode, $totalAmount) {
        $sql = "INSERT INTO {$this->table} (user_id, order_code, total_amount, created_at) 
                VALUES (?, ?, ?, NOW())";
        $this->query($sql, [$userId, $orderCode, $totalAmount]);
        return $this->getLastInsertId();
    }

    public function createOrderWithShipping($userEmail, $orderCode, $totalAmount, $receiver, $phone, $address, $discountCode = null, $discountAmount = 0, $shippingMethodId = null, $shippingFee = 0) {
        $sql = "INSERT INTO {$this->table} 
                (user_email, order_code, total_amount, receiver, phone, address, discount_code, discount_amount, shipping_method_id, shipping_fee, created_at) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";
        $this->query($sql, [$userEmail, $orderCode, $totalAmount, $receiver, $phone, $address, $discountCode, $discountAmount, $shippingMethodId, $shippingFee]);
        return $this->getLastInsertId();
    }

    public function getOrdersByUser($userId) {
        $sql = "SELECT * FROM {$this->table} WHERE user_id = ? ORDER BY created_at DESC";
        return $this->select($sql, [$userId]);
    }

    public function getOrdersByEmail($email) {
        $sql = "SELECT * FROM {$this->table} WHERE user_email = ? ORDER BY created_at DESC";
        return $this->select($sql, [$email]);
    }

    // ====================== PHẦN ADMIN ======================
    public function getAllOrders() {
        $sql = "SELECT * FROM {$this->table} ORDER BY created_at DESC";
        return $this->select($sql);
    }

    public function getOrderById($id) {
        $sql = "SELECT * FROM {$this->table} WHERE id = ?";
        $result = $this->select($sql, [$id]);
        return $result ? $result[0] : null;
    }
    
    /**
     * Lấy đơn hàng theo mã đơn hàng
     * @param string $orderCode - Mã đơn hàng
     * @return array|null
     */
    public function getOrderByCode($orderCode) {
        $sql = "SELECT * FROM {$this->table} WHERE order_code = ?";
        $result = $this->select($sql, [$orderCode]);
        return $result ? $result[0] : null;
    }

    public function getOrderDetails($orderId) {
        $sql = "SELECT od.*, p.tensp 
                FROM order_details od
                LEFT JOIN tblsanpham p ON od.product_id = p.masp
                WHERE od.order_id = ?";
        return $this->select($sql, [$orderId]);
    }

    public function updateStatus($id, $trangthai) {
        try {
            // Bắt đầu transaction
            $this->db->beginTransaction();

            // Cập nhật trạng thái đơn hàng
            $sql = "UPDATE {$this->table} SET trangthai = ? WHERE id = ?";
            $stmt = $this->db->prepare($sql);
            $result = $stmt->execute([$trangthai, $id]);
            
            if ($result) {
                // Nếu trạng thái là đã thanh toán hoặc đã giao hàng, cập nhật số lượng sản phẩm
                if ($trangthai === 'đã thanh toán' || $trangthai === 'đã giao hàng') {
                    try {
                        // Lấy chi tiết đơn hàng
                        $orderDetails = $this->getOrderDetails($id);
                        if (!empty($orderDetails)) {
                            $productModel = new AdProducModel();
                            
                            foreach ($orderDetails as $detail) {
                                $updateResult = $productModel->updateQuantity(
                                    $detail['product_id'], 
                                    $detail['quantity']
                                );
                                if (!$updateResult) {
                                    throw new Exception("Không thể cập nhật số lượng cho sản phẩm: " . $detail['product_id']);
                                }
                            }
                        }
                    } catch (Exception $e) {
                        $this->db->rollBack();
                        error_log("Lỗi cập nhật số lượng sản phẩm: " . $e->getMessage());
                        throw $e;
                    }
                }
                
                // Commit transaction nếu mọi thứ OK
                $this->db->commit();
                return true;
            }
            
            $this->db->rollBack();
            return false;
        } catch (Exception $e) {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }
            error_log("Lỗi cập nhật trạng thái đơn hàng: " . $e->getMessage());
            throw $e;
        }
    }

    public function updatePaymentStatus($orderCode, $paymentStatus) {
        // Cập nhật cả trạng thái và phương thức thanh toán
        $sql = "UPDATE {$this->table} SET trangthai = 'đã thanh toán', payment_method = 'Đã thanh toán VNPAY' WHERE order_code = ?";
        return $this->query($sql, [$orderCode]);
    }

// ====================== LỌC ĐƠN HÀNG THEO TRẠNG THÁI ======================
public function getOrdersByStatus($status) {
    if ($status === 'all' || $status === '') {
        return $this->getAllOrders();
    }
    $sql = "SELECT * FROM {$this->table} WHERE trangthai = ? ORDER BY created_at DESC";
    return $this->select($sql, [$status]);
}


    // ====================== TÌM KIẾM ĐƠN HÀNG ======================
    public function searchOrders($keyword, $status = null) {
        $params = [];
        $conditions = [];
        
        // Tìm theo ID hoặc mã đơn
        if (is_numeric($keyword)) {
            $conditions[] = "id = ?";
            $params[] = $keyword;
        } else {
            $conditions[] = "order_code LIKE ?";
            $params[] = "%{$keyword}%";
        }
        
        // Lọc theo trạng thái nếu có
        if ($status && $status !== 'all') {
            $conditions[] = "trangthai = ?";
            $params[] = $status;
        }
        
        $whereClause = !empty($conditions) ? "WHERE " . implode(" AND ", $conditions) : "";
        $sql = "SELECT * FROM {$this->table} {$whereClause} ORDER BY created_at DESC";
        return $this->select($sql, $params);
    }
    
    /**
     * Gửi email xác nhận đơn hàng
     * @param int $orderId - ID của đơn hàng
     * @return bool - True nếu gửi thành công
     */
    public function sendOrderConfirmationEmail($orderId) {
        try {
            require_once __DIR__ . '/../app/EmailService.php';
            
            // Lấy thông tin đơn hàng
            $order = $this->getOrderById($orderId);
            if (!$order) {
                error_log("Không tìm thấy đơn hàng với ID: $orderId");
                return false;
            }
            
            // Lấy chi tiết sản phẩm trong đơn hàng
            $details = $this->getOrderDetails($orderId);
            
            // Chuẩn bị dữ liệu để gửi email
            $orderData = [
                'order_code' => $order['order_code'],
                'total_amount' => $order['total_amount'],
                'receiver' => $order['receiver'],
                'phone' => $order['phone'],
                'address' => $order['address'],
                'created_at' => $order['created_at'],
                'items' => $details
            ];
            
            // Khởi tạo EmailService và gửi
            $emailService = new EmailService();
            $result = $emailService->sendOrderConfirmation(
                $order['user_email'],
                $order['receiver'],
                $orderData
            );
            
            if ($result) {
                // Cập nhật cờ email đã gửi (nếu có field này)
                $sql = "UPDATE {$this->table} SET email_sent = 1, email_sent_at = NOW() WHERE id = ?";
                try {
                    $this->query($sql, [$orderId]);
                } catch (Exception $e) {
                    // Field có thể không tồn tại, bỏ qua
                }
            }
            
            return $result;
        } catch (Exception $e) {
            error_log("Lỗi gửi email xác nhận đơn hàng: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Gửi email thông báo thanh toán thành công
     * @param int $orderId - ID của đơn hàng
     * @return bool
     */
    public function sendPaymentConfirmationEmail($orderId) {
        try {
            require_once __DIR__ . '/../app/EmailService.php';
            
            $order = $this->getOrderById($orderId);
            if (!$order) {
                return false;
            }
            
            $emailService = new EmailService();
            $subject = 'Xác Nhận Thanh Toán - Đơn Hàng #' . $order['order_code'];
            
            $htmlContent = <<<HTML
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; color: #333; }
        .container { max-width: 600px; margin: 0 auto; background: white; padding: 20px; border-radius: 5px; }
        .header { background: #4CAF50; color: white; padding: 20px; text-align: center; border-radius: 5px; }
        .content { padding: 20px; }
        .info { background: #f5f5f5; padding: 15px; margin: 15px 0; border-left: 4px solid #4CAF50; }
        .label { font-weight: bold; color: #555; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>✓ Thanh Toán Thành Công</h1>
        </div>
        <div class="content">
            <p>Xin chào {$order['receiver']},</p>
            <p>Cảm ơn bạn! Thanh toán cho đơn hàng của bạn đã được xác nhận thành công.</p>
            <div class="info">
                <p><span class="label">Mã Đơn Hàng:</span> {$order['order_code']}</p>
                <p><span class="label">Số Tiền:</span> {$order['total_amount']} ₫</p>
                <p><span class="label">Trạng Thái:</span> Đã Thanh Toán</p>
            </div>
            <p>Đơn hàng của bạn sẽ sớm được chuẩn bị và giao đến địa chỉ: <strong>{$order['address']}</strong></p>
            <p>Nếu bạn có bất kỳ câu hỏi nào, vui lòng liên hệ với chúng tôi.</p>
            <p>Cảm ơn bạn đã mua sắm tại Book Store!</p>
        </div>
    </div>
</body>
</html>
HTML;
            
            return $emailService->sendCustomEmail($order['user_email'], $subject, $htmlContent);
        } catch (Exception $e) {
            error_log("Lỗi gửi email thanh toán: " . $e->getMessage());
            return false;
        }
    }
}

