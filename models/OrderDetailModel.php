<?php
require_once 'BaseModel.php';
class OrderDetailModel extends BaseModel {
    protected $table = 'order_details';

    public function addOrderDetail($orderId, $productId, $quantity, $price, $salePrice, $total, $image, $productType, $productName) {
        $sql = "INSERT INTO $this->table (order_id, product_id, quantity, price, sale_price, total, image, product_type, product_name) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        return $this->query($sql, [$orderId, $productId, $quantity, $price, $salePrice, $total, $image, $productType, $productName]);
    }

    public function getOrderDetails($orderId) {
        $sql = "SELECT * FROM $this->table WHERE order_id = ?";
        return $this->select($sql, [$orderId]);
    }

    // ✅ Xóa order_details theo product_id (dùng khi xóa sản phẩm)
    public function deleteByProductId($productId) {
        $sql = "DELETE FROM $this->table WHERE product_id = ?";
        return $this->query($sql, [$productId]);
    }
}
