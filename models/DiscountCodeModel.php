<?php
require_once __DIR__ . "/BaseModel.php";

class DiscountCodeModel extends BaseModel {
    
    public function __construct() {
        parent::__construct();
    }

    // Lấy tất cả mã giảm giá
    public function getAll() {
        $sql = "SELECT * FROM discount_codes ORDER BY created_at DESC";
        return $this->select($sql);
    }

    // Lấy mã giảm giá theo ID
    public function getById($id) {
        $sql = "SELECT * FROM discount_codes WHERE id = :id";
        return $this->select($sql, [':id' => $id]);
    }

    // Lấy mã giảm giá theo code (chỉ active)
    public function getByCode($code) {
        // ✅ BYPASS kiểm tra ngày tạm thời để test
        $sql = "SELECT * FROM discount_codes 
                WHERE code = :code 
                AND status = 1
                LIMIT 1";
        $result = $this->select($sql, [':code' => strtoupper($code)]);
        error_log("getByCode($code) - result: " . ($result ? "FOUND" : "NOT FOUND"));
        return !empty($result) ? $result[0] : null;
    }

    // ✅ Kiểm tra xem mã có tồn tại không (bất kể status)
    public function codeExists($code) {
        try {
            $sql = "SELECT COUNT(*) as cnt FROM discount_codes WHERE code = :code";
            $result = $this->select($sql, [':code' => strtoupper($code)]);
            return !empty($result) && $result[0]['cnt'] > 0;
        } catch (Exception $e) {
            return false;
        }
    }

    // Lấy mã giảm giá hoạt động
    public function getActive() {
        $sql = "SELECT * FROM discount_codes 
                WHERE status = 1 
                AND start_date <= NOW() 
                AND end_date >= NOW()
                ORDER BY created_at DESC";
        return $this->select($sql);
    }

    // Thêm mã giảm giá
    public function addCode($data) {
        $sql = "INSERT INTO discount_codes 
                (code, description, discount_type, discount_value, min_order_value, max_discount, start_date, end_date, usage_limit, status) 
                VALUES (:code, :description, :discount_type, :discount_value, :min_order_value, :max_discount, :start_date, :end_date, :usage_limit, :status)";
        return $this->query($sql, [
            ':code' => strtoupper($data['code']),
            ':description' => $data['description'],
            ':discount_type' => $data['discount_type'],
            ':discount_value' => $data['discount_value'],
            ':min_order_value' => $data['min_order_value'] ?? 0,
            ':max_discount' => $data['max_discount'] ?? null,
            ':start_date' => $data['start_date'],
            ':end_date' => $data['end_date'],
            ':usage_limit' => $data['usage_limit'] ?? null,
            ':status' => $data['status'] ?? 1
        ])->execute();
    }

    // Cập nhật mã giảm giá
    public function editCode($id, $data) {
        $sql = "UPDATE discount_codes 
                SET code = :code, 
                    description = :description, 
                    discount_type = :discount_type, 
                    discount_value = :discount_value, 
                    min_order_value = :min_order_value, 
                    max_discount = :max_discount, 
                    start_date = :start_date, 
                    end_date = :end_date, 
                    usage_limit = :usage_limit, 
                    status = :status 
                WHERE id = :id";
        return $this->query($sql, [
            ':id' => $id,
            ':code' => strtoupper($data['code']),
            ':description' => $data['description'],
            ':discount_type' => $data['discount_type'],
            ':discount_value' => $data['discount_value'],
            ':min_order_value' => $data['min_order_value'] ?? 0,
            ':max_discount' => $data['max_discount'] ?? null,
            ':start_date' => $data['start_date'],
            ':end_date' => $data['end_date'],
            ':usage_limit' => $data['usage_limit'] ?? null,
            ':status' => $data['status']
        ])->execute();
    }

    // Xóa mã giảm giá
    public function removeCode($id) {
        $sql = "DELETE FROM discount_codes WHERE id = :id";
        return $this->query($sql, [':id' => $id])->execute();
    }

    // Cập nhật số lượng sử dụng
    public function incrementUsage($code) {
        $sql = "UPDATE discount_codes SET used_count = used_count + 1 WHERE code = :code";
        return $this->query($sql, [':code' => strtoupper($code)])->execute();
    }

    // Kiểm tra mã giảm giá còn được sử dụng không
    public function canUseCode($code) {
        $codeData = $this->getByCode($code);
        if (!$codeData) {
            error_log("canUseCode - Mã không tồn tại: $code");
            return ['valid' => false, 'message' => 'Mã không tồn tại hoặc đã hết hạn'];
        }

        error_log("canUseCode - codeData found: " . print_r($codeData, true));
        error_log("canUseCode - usage_limit: " . $codeData['usage_limit'] . ", used_count: " . $codeData['used_count']);

        if ($codeData['usage_limit'] && $codeData['used_count'] >= $codeData['usage_limit']) {
            error_log("canUseCode - Đã hết lượt sử dụng");
            return ['valid' => false, 'message' => 'Mã giảm giá đã hết lượt sử dụng'];
        }

        error_log("canUseCode - Valid!");
        return ['valid' => true, 'data' => $codeData];
    }

    // Tính tiền giảm
    public function calculateDiscount($code, $orderTotal) {
        // ✅ DEBUG: Log input
        error_log("calculateDiscount - code: $code, orderTotal: $orderTotal (type: " . gettype($orderTotal) . ")");
        
        $result = $this->canUseCode($code);
        if (!$result['valid']) {
            error_log("calculateDiscount - canUseCode failed: " . $result['message']);
            return ['valid' => false, 'message' => $result['message']];
        }

        $codeData = $result['data'];
        error_log("calculateDiscount - codeData: " . print_r($codeData, true));

        // ✅ CAST TẤT CẢ THÀNH FLOAT
        $orderTotal = (float)$orderTotal;
        $minOrderValue = (float)($codeData['min_order_value'] ?? 0);
        $discountValue = (float)$codeData['discount_value'];
        $maxDiscount = (float)($codeData['max_discount'] ?? 0);

        // Kiểm tra giá trị đơn hàng tối thiểu
        if ($orderTotal < $minOrderValue) {
            error_log("calculateDiscount - orderTotal ($orderTotal) < min_order_value ($minOrderValue)");
            return ['valid' => false, 'message' => 'Đơn hàng không đủ giá trị tối thiểu: ' . number_format($minOrderValue)];
        }

        $discount = 0;
        if ($codeData['discount_type'] === 'percentage') {
            $discount = ($orderTotal * $discountValue) / 100;
            error_log("calculateDiscount - discount_type: percentage, calculation: ($orderTotal * $discountValue) / 100 = $discount");
            
            // Áp dụng giới hạn giảm tối đa nếu có
            if ($maxDiscount > 0 && $discount > $maxDiscount) {
                error_log("calculateDiscount - applying max_discount: $discount -> $maxDiscount");
                $discount = $maxDiscount;
            }
        } else {
            $discount = $discountValue;
            error_log("calculateDiscount - discount_type: fixed, discount = $discountValue");
        }

        $finalTotal = max(0, $orderTotal - $discount);
        error_log("calculateDiscount - final result: discount=$discount, final_total=$finalTotal");
        
        return [
            'valid' => true,
            'discount_amount' => $discount,
            'final_total' => $finalTotal
        ];
    }
}
?>
