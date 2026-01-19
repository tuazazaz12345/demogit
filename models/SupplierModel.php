<?php
require_once(__DIR__ . '/BaseModel.php');

class SupplierModel extends BaseModel {
    protected $table = 'ncc_sanpham';
    
    public function __construct() {
        parent::__construct();
    }
    
    // Lấy tất cả nhà cung cấp
    public function getAllSuppliers() {
        $sql = "SELECT * FROM " . $this->table . " WHERE trang_thai = 1 ORDER BY ngay_tao DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Lấy tất cả nhà cung cấp kể cả đã tắt
    public function getAllSuppliersWithInactive() {
        $sql = "SELECT * FROM " . $this->table . " ORDER BY ngay_tao DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Lấy chi tiết nhà cung cấp
    public function getSupplierById($id) {
        $sql = "SELECT * FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    // Tìm kiếm nhà cung cấp
    public function searchSuppliers($keyword) {
        $keyword = '%' . trim($keyword) . '%';
        $sql = "SELECT * FROM " . $this->table . " 
                WHERE ten_ncc LIKE :keyword 
                   OR dia_chi LIKE :keyword 
                   OR dien_thoai LIKE :keyword 
                   OR email LIKE :keyword
                ORDER BY ngay_tao DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':keyword', $keyword);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Thêm nhà cung cấp mới
    public function addSupplier($data) {
        try {
            // Kiểm tra tên nhà cung cấp đã tồn tại chưa
            $checkSql = "SELECT COUNT(*) FROM " . $this->table . " WHERE ten_ncc = :ten_ncc";
            $checkStmt = $this->db->prepare($checkSql);
            $checkStmt->bindParam(':ten_ncc', $data['ten_ncc']);
            $checkStmt->execute();
            
            if ($checkStmt->fetchColumn() > 0) {
                return [
                    'success' => false,
                    'message' => 'Tên nhà cung cấp đã tồn tại!'
                ];
            }
            
            $sql = "INSERT INTO " . $this->table . " 
                    (ten_ncc, dia_chi, dien_thoai, email, han_hop_dong, trang_thai) 
                    VALUES 
                    (:ten_ncc, :dia_chi, :dien_thoai, :email, :han_hop_dong, :trang_thai)";
            
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':ten_ncc', $data['ten_ncc']);
            $stmt->bindParam(':dia_chi', $data['dia_chi']);
            $stmt->bindParam(':dien_thoai', $data['dien_thoai']);
            $stmt->bindParam(':email', $data['email']);
            $stmt->bindParam(':han_hop_dong', $data['han_hop_dong']);
            $stmt->bindParam(':trang_thai', $data['trang_thai']);
            $stmt->execute();
            
            return [
                'success' => true,
                'message' => 'Thêm nhà cung cấp thành công!',
                'id' => $this->db->lastInsertId()
            ];
        } catch (PDOException $e) {
            return [
                'success' => false,
                'message' => 'Lỗi: ' . $e->getMessage()
            ];
        }
    }
    
    // Cập nhật nhà cung cấp
    public function updateSupplier($id, $data) {
        try {
            // Kiểm tra tên nhà cung cấp đã tồn tại (ngoại trừ bản hiện tại)
            $checkSql = "SELECT COUNT(*) FROM " . $this->table . " WHERE ten_ncc = :ten_ncc AND id != :id";
            $checkStmt = $this->db->prepare($checkSql);
            $checkStmt->bindParam(':ten_ncc', $data['ten_ncc']);
            $checkStmt->bindParam(':id', $id);
            $checkStmt->execute();
            
            if ($checkStmt->fetchColumn() > 0) {
                return [
                    'success' => false,
                    'message' => 'Tên nhà cung cấp đã tồn tại!'
                ];
            }
            
            $sql = "UPDATE " . $this->table . " 
                    SET ten_ncc = :ten_ncc, 
                        dia_chi = :dia_chi, 
                        dien_thoai = :dien_thoai, 
                        email = :email, 
                        han_hop_dong = :han_hop_dong, 
                        trang_thai = :trang_thai 
                    WHERE id = :id";
            
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':ten_ncc', $data['ten_ncc']);
            $stmt->bindParam(':dia_chi', $data['dia_chi']);
            $stmt->bindParam(':dien_thoai', $data['dien_thoai']);
            $stmt->bindParam(':email', $data['email']);
            $stmt->bindParam(':han_hop_dong', $data['han_hop_dong']);
            $stmt->bindParam(':trang_thai', $data['trang_thai']);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            
            return [
                'success' => true,
                'message' => 'Cập nhật nhà cung cấp thành công!'
            ];
        } catch (PDOException $e) {
            return [
                'success' => false,
                'message' => 'Lỗi: ' . $e->getMessage()
            ];
        }
    }
    
    // Xóa nhà cung cấp
    public function deleteSupplier($id) {
        try {
            // Kiểm tra xem nhà cung cấp có sản phẩm không
            $checkSql = "SELECT COUNT(*) FROM tblsanpham WHERE supplier_id = :id";
            $checkStmt = $this->db->prepare($checkSql);
            $checkStmt->bindParam(':id', $id);
            $checkStmt->execute();
            
            if ($checkStmt->fetchColumn() > 0) {
                return [
                    'success' => false,
                    'message' => 'Không thể xóa! Nhà cung cấp này đang có sản phẩm.'
                ];
            }
            
            $sql = "DELETE FROM " . $this->table . " WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            
            return [
                'success' => true,
                'message' => 'Xóa nhà cung cấp thành công!'
            ];
        } catch (PDOException $e) {
            return [
                'success' => false,
                'message' => 'Lỗi: ' . $e->getMessage()
            ];
        }
    }
    
    // Lấy số lượng sản phẩm của nhà cung cấp
    public function getProductCount($supplierId) {
        $sql = "SELECT COUNT(*) as count FROM tblsanpham WHERE supplier_id = :supplier_id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':supplier_id', $supplierId);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    }
    
    // Lấy tất cả nhà cung cấp cho dropdown
    public function getForDropdown() {
        $sql = "SELECT id, ten_ncc FROM " . $this->table . " 
                WHERE trang_thai = 1 
                ORDER BY ten_ncc ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
