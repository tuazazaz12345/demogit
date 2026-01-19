<?php
require_once 'BaseModel.php';

/**
 * Model quản lý phương thức vận chuyển
 */
class ShippingMethodModel extends BaseModel {
    protected $table = 'shipping_methods';

    /**
     * Lấy tất cả phương thức vận chuyển đang hoạt động
     * @return array
     */
    public function getActiveShippingMethods() {
        $sql = "SELECT * FROM {$this->table} WHERE is_active = 1 ORDER BY price ASC";
        return $this->select($sql);
    }

    /**
     * Lấy thông tin phương thức vận chuyển theo ID
     * @param int $id
     * @return array|false
     */
    public function getShippingMethodById($id) {
        $sql = "SELECT * FROM {$this->table} WHERE id = ? AND is_active = 1";
        $result = $this->select($sql, [$id]);
        return $result ? $result[0] : false;
    }

    /**
     * Tính phí vận chuyển dựa trên tổng tiền đơn hàng
     * @param int $shippingMethodId - ID phương thức vận chuyển
     * @param float $orderTotal - Tổng tiền đơn hàng
     * @return array ['shipping_fee' => float, 'method' => array]
     */
    public function calculateShippingFee($shippingMethodId, $orderTotal) {
        $method = $this->getShippingMethodById($shippingMethodId);
        
        if (!$method) {
            return [
                'success' => false,
                'message' => 'Phương thức vận chuyển không hợp lệ'
            ];
        }

        $shippingFee = $method['price'];

        // Miễn phí ship cho đơn hàng trên 500,000đ (nếu chọn phương thức miễn phí)
        if ($method['name'] === 'Miễn phí vận chuyển' && $orderTotal < 500000) {
            return [
                'success' => false,
                'message' => 'Đơn hàng phải trên 500.000đ để được miễn phí vận chuyển',
                'min_order' => 500000
            ];
        }

        return [
            'success' => true,
            'shipping_fee' => $shippingFee,
            'method' => $method,
            'method_name' => $method['name'],
            'estimated_days' => $method['estimated_days']
        ];
    }

    /**
     * Kiểm tra xem đơn hàng có đủ điều kiện miễn phí ship không
     * @param float $orderTotal
     * @return array
     */
    public function checkFreeShippingEligibility($orderTotal) {
        $freeShippingThreshold = 500000;
        
        return [
            'eligible' => $orderTotal >= $freeShippingThreshold,
            'threshold' => $freeShippingThreshold,
            'remaining' => max(0, $freeShippingThreshold - $orderTotal),
            'message' => $orderTotal >= $freeShippingThreshold 
                ? 'Đơn hàng của bạn được miễn phí vận chuyển!' 
                : 'Mua thêm ' . number_format($freeShippingThreshold - $orderTotal, 0, ',', '.') . 'đ để được miễn phí vận chuyển'
        ];
    }

    /**
     * Lấy phương thức vận chuyển mặc định (giá rẻ nhất)
     * @return array|false
     */
    public function getDefaultShippingMethod() {
        $sql = "SELECT * FROM {$this->table} WHERE is_active = 1 ORDER BY price ASC LIMIT 1";
        $result = $this->select($sql);
        return $result ? $result[0] : false;
    }

    /**
     * Admin: Tạo phương thức vận chuyển mới
     * @param array $data
     * @return bool
     */
    public function createShippingMethod($data) {
        $sql = "INSERT INTO {$this->table} (name, description, price, estimated_days, is_active, icon) 
                VALUES (?, ?, ?, ?, ?, ?)";
        
        return $this->query($sql, [
            $data['name'],
            $data['description'] ?? '',
            $data['price'],
            $data['estimated_days'] ?? '',
            $data['is_active'] ?? 1,
            $data['icon'] ?? ''
        ]);
    }

    /**
     * Admin: Cập nhật phương thức vận chuyển
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function updateShippingMethod($id, $data) {
        $sql = "UPDATE {$this->table} 
                SET name = ?, description = ?, price = ?, estimated_days = ?, is_active = ?, icon = ? 
                WHERE id = ?";
        
        return $this->query($sql, [
            $data['name'],
            $data['description'] ?? '',
            $data['price'],
            $data['estimated_days'] ?? '',
            $data['is_active'] ?? 1,
            $data['icon'] ?? '',
            $id
        ]);
    }

    /**
     * Admin: Xóa phương thức vận chuyển
     * @param int $id
     * @return bool
     */
    public function deleteShippingMethod($id) {
        $sql = "DELETE FROM {$this->table} WHERE id = ?";
        return $this->query($sql, [$id]);
    }

    /**
     * Admin: Kích hoạt/vô hiệu hóa phương thức vận chuyển
     * @param int $id
     * @param int $status
     * @return bool
     */
    public function toggleStatus($id, $status) {
        $sql = "UPDATE {$this->table} SET is_active = ? WHERE id = ?";
        return $this->query($sql, [$status, $id]);
    }
}
