<?php
require_once 'BaseModel.php';

class WishlistModel extends BaseModel {
    protected $table = 'wishlist';
    
    /**
     * Lấy tất cả sản phẩm yêu thích của user
     */
    public function getByEmail($email) {
        $sql = "SELECT w.user_email, w.masp, w.created_at, 
                p.masp, p.tensp, p.hinhanh, p.giaXuat, p.mota, 
                p.soluong, p.khuyenmai, p.createDate
                FROM {$this->table} w
                INNER JOIN tblsanpham p ON w.masp = p.masp
                WHERE w.user_email = ?
                ORDER BY w.created_at DESC";
        $result = $this->select($sql, [$email]);
        return $result ?: [];
    }
    
    /**
     * Kiểm tra sản phẩm có trong wishlist không
     */
    public function exists($email, $masp) {
        try {
            $sql = "SELECT COUNT(*) as cnt FROM {$this->table} 
                    WHERE user_email = ? AND masp = ? LIMIT 1";
            $result = $this->select($sql, [$email, $masp]);
            if ($result && !empty($result)) {
                return intval($result[0]['cnt']) > 0;
            }
            return false;
        } catch (Exception $e) {
            error_log("WishlistModel exists error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Thêm sản phẩm vào wishlist
     */
    public function add($email, $masp) {
        try {
            // Kiểm tra đã tồn tại chưa
            if ($this->exists($email, $masp)) {
                return false;
            }
            
            $sql = "INSERT INTO {$this->table} (user_email, masp, created_at) 
                    VALUES (?, ?, NOW())";
            $this->query($sql, [$email, $masp]);
            return true;
        } catch (Exception $e) {
            error_log("WishlistModel add error: " . $e->getMessage());
            throw $e;
        }
    }
    
    /**
     * Xóa sản phẩm khỏi wishlist
     */
    public function remove($email, $masp) {
        try {
            $sql = "DELETE FROM {$this->table} 
                    WHERE user_email = ? AND masp = ?";
            $this->query($sql, [$email, $masp]);
            return true;
        } catch (Exception $e) {
            error_log("WishlistModel remove error: " . $e->getMessage());
            throw $e;
        }
    }
    
    /**
     * Lấy số lượng sản phẩm trong wishlist
     */
    public function countByEmail($email) {
        try {
            $sql = "SELECT COUNT(*) as cnt FROM {$this->table} 
                    WHERE user_email = ?";
            $result = $this->select($sql, [$email]);
            if ($result && !empty($result)) {
                return intval($result[0]['cnt']);
            }
            return 0;
        } catch (Exception $e) {
            error_log("WishlistModel count error: " . $e->getMessage());
            return 0;
        }
    }
    
    /**
     * Xóa tất cả wishlist của user
     */
    public function clearByEmail($email) {
        try {
            $sql = "DELETE FROM {$this->table} WHERE user_email = ?";
            $this->query($sql, [$email]);
            return true;
        } catch (Exception $e) {
            error_log("WishlistModel clear error: " . $e->getMessage());
            throw $e;
        }
    }
}
