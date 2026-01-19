<?php
require_once(__DIR__ . '/BaseModel.php');

class CustomerModel extends BaseModel {
    public function __construct() {
        parent::__construct();
    }
    
    public function getAllCustomers() {
        $sql = "SELECT * FROM tbluser ORDER BY created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function deleteCustomer($id) {
        try {
            // Kiểm tra xem khách hàng có đơn hàng không
            $checkOrders = "SELECT COUNT(*) FROM orders WHERE user_email = (SELECT email FROM tbluser WHERE user_id = :id)";
            $stmt = $this->db->prepare($checkOrders);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            
            if ($stmt->fetchColumn() > 0) {
                return [
                    'success' => false,
                    'message' => 'Không thể xóa khách hàng này vì đã có đơn hàng!'
                ];
            }
            
                        // Thực hiện xóa nếu không có đơn hàng
            $sql = "DELETE FROM tbluser WHERE user_id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            
            return [
                'success' => true,
                'message' => 'Xóa khách hàng thành công!'
            ];
        } catch (PDOException $e) {
            return [
                'success' => false,
                'message' => 'Lỗi khi xóa khách hàng: ' . $e->getMessage()
            ];
        }
    }
    
    public function getCustomerDetails($id) {
        $sql = "SELECT * FROM tbluser WHERE user_id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function getTotalCustomers() {
        $sql = "SELECT COUNT(*) as total FROM tbluser";
        $result = $this->db->query($sql);
        return $result->fetch(PDO::FETCH_ASSOC)['total'];
    }
    
    public function searchCustomers($keyword) {
        $keyword = '%' . trim($keyword) . '%';
        $sql = "SELECT * FROM tbluser 
                WHERE fullname LIKE :keyword 
                   OR email LIKE :keyword 
                   OR phone LIKE :keyword
                ORDER BY created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':keyword', $keyword);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getCustomerStats($id) {
        // Lấy email của khách hàng
        $sqlEmail = "SELECT email FROM tbluser WHERE user_id = :id LIMIT 1";
        $stmtEmail = $this->db->prepare($sqlEmail);
        $stmtEmail->bindParam(':id', $id);
        $stmtEmail->execute();
        $customerEmail = $stmtEmail->fetch(PDO::FETCH_ASSOC);
        
        if (!$customerEmail) {
            return ['total_orders' => 0, 'total_spent' => 0];
        }
        
        // Lấy số lượng đơn hàng và tổng chi tiêu dựa trên email
        $sql = "SELECT 
                    COUNT(id) as total_orders,
                    COALESCE(SUM(total_amount), 0) as total_spent
                FROM orders
                WHERE user_email = :email";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':email', $customerEmail['email']);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function toggleLock($id, $isLocked) {
        try {
            $sql = "UPDATE tbluser SET is_locked = :is_locked WHERE user_id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':is_locked', $isLocked);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            
            return [
                'success' => true,
                'message' => $isLocked ? 'Khóa tài khoản thành công!' : 'Mở khóa tài khoản thành công!'
            ];
        } catch (PDOException $e) {
            return [
                'success' => false,
                'message' => 'Lỗi khi cập nhật: ' . $e->getMessage()
            ];
        }
    }
}