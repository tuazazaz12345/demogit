<?php
require_once __DIR__ . "/BaseModel.php";

class PromotionModel extends BaseModel {
    
    public function __construct() {
        parent::__construct();
    }

    // Lấy tất cả khuyến mại
    public function getAll() {
        $sql = "SELECT * FROM promotions ORDER BY created_at DESC";
        return $this->select($sql);
    }

    // Lấy khuyến mại theo ID
    public function getById($id) {
        $sql = "SELECT * FROM promotions WHERE id = :id";
        return $this->select($sql, [':id' => $id]);
    }

    // Lấy khuyến mại hoạt động
    public function getActive() {
        $sql = "SELECT * FROM promotions 
                WHERE status = 1 
                AND start_date <= NOW() 
                AND end_date >= NOW()
                ORDER BY created_at DESC";
        return $this->select($sql);
    }

    // Thêm khuyến mại
    public function addPromotion($data) {
        $sql = "INSERT INTO promotions (name, description, discount_percent, start_date, end_date, status) 
                VALUES (:name, :description, :discount_percent, :start_date, :end_date, :status)";
        return $this->query($sql, [
            ':name' => $data['name'],
            ':description' => $data['description'],
            ':discount_percent' => $data['discount_percent'],
            ':start_date' => $data['start_date'],
            ':end_date' => $data['end_date'],
            ':status' => $data['status'] ?? 1
        ])->execute();
    }

    // Cập nhật khuyến mại
    public function editPromotion($id, $data) {
        $sql = "UPDATE promotions 
                SET name = :name, 
                    description = :description, 
                    discount_percent = :discount_percent, 
                    start_date = :start_date, 
                    end_date = :end_date, 
                    status = :status 
                WHERE id = :id";
        return $this->query($sql, [
            ':id' => $id,
            ':name' => $data['name'],
            ':description' => $data['description'],
            ':discount_percent' => $data['discount_percent'],
            ':start_date' => $data['start_date'],
            ':end_date' => $data['end_date'],
            ':status' => $data['status']
        ])->execute();
    }

    // Xóa khuyến mại
    public function removePromotion($id) {
        // Xóa các sản phẩm liên kết trước
        $sql = "DELETE FROM product_promotions WHERE promotion_id = :id";
        $this->query($sql, [':id' => $id])->execute();
        
        // Xóa khuyến mại
        $sql = "DELETE FROM promotions WHERE id = :id";
        return $this->query($sql, [':id' => $id])->execute();
    }

    // Gán khuyến mại cho sản phẩm
    public function assignProducts($promotionId, $productIds) {
        // Xóa các gán cũ
        $sql = "DELETE FROM product_promotions WHERE promotion_id = :promotion_id";
        $this->query($sql, [':promotion_id' => $promotionId])->execute();
        
        // Thêm các gán mới
        foreach ($productIds as $productId) {
            $sql = "INSERT INTO product_promotions (product_id, promotion_id) VALUES (:product_id, :promotion_id)";
            $this->query($sql, [
                ':product_id' => $productId,
                ':promotion_id' => $promotionId
            ])->execute();
        }
        return true;
    }

    // Lấy các sản phẩm của khuyến mại
    public function getPromotionProducts($promotionId) {
        $sql = "SELECT sp.* FROM tblsanpham sp 
                INNER JOIN product_promotions pp ON sp.masp = pp.product_id 
                WHERE pp.promotion_id = :promotion_id";
        return $this->select($sql, [':promotion_id' => $promotionId]);
    }

    // Lấy tất cả sản phẩm để gán khuyến mại
    public function getAllProducts() {
        $sql = "SELECT masp, tensp FROM tblsanpham ORDER BY tensp";
        return $this->select($sql);
    }

    // Kiểm tra sản phẩm có khuyến mại hay không
    public function hasPromotion($productId) {
        $sql = "SELECT p.* FROM promotions p
                INNER JOIN product_promotions pp ON p.id = pp.promotion_id
                WHERE pp.product_id = :product_id 
                AND p.status = 1 
                AND p.start_date <= NOW() 
                AND p.end_date >= NOW()
                LIMIT 1";
        $result = $this->select($sql, [':product_id' => $productId]);
        return !empty($result) ? $result[0] : null;
    }

    // ✅ Xóa product_promotions theo product_id (dùng khi xóa sản phẩm)
    public function deleteProductPromotions($productId) {
        $sql = "DELETE FROM product_promotions WHERE product_id = ?";
        return $this->query($sql, [$productId]);
    }
}
?>
