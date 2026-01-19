<?php
require_once 'BaseModel.php';

class AdminModel extends BaseModel {
    protected $table = 'tbladmin';

    // ====================== ĐĂNG KÝ ADMIN ======================
    public function register($username, $password, $email, $fullname) {
        // Mã hóa mật khẩu trước khi lưu
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        
        // Tạo tài khoản admin với status = pending
        $sql = "INSERT INTO {$this->table} (username, password, email, fullname, status) 
                VALUES (?, ?, ?, ?, 'pending')";
        $result = $this->query($sql, [$username, $hashed, $email, $fullname]);
        
        if ($result) {
            // Lấy ID admin vừa tạo
            $adminId = $this->getLastInsertId();
            
            // Lưu vào bảng admin_approval_requests
            $sql_request = "INSERT INTO admin_approval_requests (admin_id, username, email, fullname, status) 
                           VALUES (?, ?, ?, ?, 'pending')";
            $this->query($sql_request, [$adminId, $username, $email, $fullname]);
        }
        
        return $result;
    }
    
    // ====================== LẤY ID INSERT CUỐI CÙNG ======================
    public function getLastInsertId() {
        $sql = "SELECT LAST_INSERT_ID() as id";
        $result = $this->select($sql);
        return $result[0]['id'] ?? 0;
    }

    // ====================== ĐĂNG NHẬP ADMIN ======================
    public function login($username, $password) {
        $sql = "SELECT * FROM {$this->table} WHERE username = ?";
        $result = $this->select($sql, [$username]);

        if ($result && password_verify($password, $result[0]['password'])) {
            return $result[0];
        }

        return false;
    }

    // ====================== KIỂM TRA TÊN ĐĂNG NHẬP ======================
    public function exists($username) {
        $sql = "SELECT COUNT(*) AS total FROM {$this->table} WHERE username = ?";
        $result = $this->select($sql, [$username]);
        return $result[0]['total'] ?? 0;
    }

    // ====================== LẤY DANH SÁCH ADMIN (dành cho tương lai) ======================
    public function getAllAdmins() {
        $sql = "SELECT id, username, email, fullname, created_at FROM {$this->table} ORDER BY created_at DESC";
        return $this->select($sql);
    }

    // ====================== XOÁ ADMIN ======================
    public function deleteAdmin($id) {
        $sql = "DELETE FROM {$this->table} WHERE id = ?";
        return $this->query($sql, [$id]);
    }

    // ====================== DUYỆT ADMIN ======================
    /**
     * Phê duyệt yêu cầu tạo tài khoản admin
     */
    public function approveAdmin($requestId, $approvedByAdminId) {
        try {
            // 1. Cập nhật trạng thái trong admin_approval_requests
            $sql_approve = "UPDATE admin_approval_requests 
                           SET status = 'approved', approved_at = NOW(), approved_by = ? 
                           WHERE id = ?";
            $this->query($sql_approve, [$approvedByAdminId, $requestId]);
            
            // 2. Lấy admin_id từ request
            $sql_get = "SELECT admin_id FROM admin_approval_requests WHERE id = ?";
            $result = $this->select($sql_get, [$requestId]);
            $adminId = $result[0]['admin_id'] ?? 0;
            
            // 3. Cập nhật trạng thái admin thành 'approved'
            $sql_update = "UPDATE {$this->table} 
                          SET status = 'approved', approval_date = NOW(), approved_by = ? 
                          WHERE id = ?";
            $this->query($sql_update, [$approvedByAdminId, $adminId]);
            
            return true;
        } catch (Exception $e) {
            error_log("Error in approveAdmin: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Từ chối yêu cầu tạo tài khoản admin
     */
    public function rejectAdmin($requestId, $approvedByAdminId, $reason = '') {
        try {
            // 1. Cập nhật trạng thái trong admin_approval_requests
            $sql_reject = "UPDATE admin_approval_requests 
                          SET status = 'rejected', approved_at = NOW(), approved_by = ?, rejection_reason = ? 
                          WHERE id = ?";
            $this->query($sql_reject, [$approvedByAdminId, $reason, $requestId]);
            
            // 2. Lấy admin_id từ request
            $sql_get = "SELECT admin_id FROM admin_approval_requests WHERE id = ?";
            $result = $this->select($sql_get, [$requestId]);
            $adminId = $result[0]['admin_id'] ?? 0;
            
            // 3. Xóa tài khoản admin
            $this->deleteAdmin($adminId);
            
            return true;
        } catch (Exception $e) {
            error_log("Error in rejectAdmin: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Lấy danh sách yêu cầu duyệt admin
     */
    public function getPendingRequests() {
        $sql = "SELECT * FROM admin_approval_requests WHERE status = 'pending' ORDER BY requested_at DESC";
        return $this->select($sql);
    }

    /**
     * Lấy lịch sử duyệt admin
     */
    public function getApprovalHistory($limit = 50) {
        $sql = "SELECT 
                    ar.*,
                    ab.username as approved_by_name
                FROM admin_approval_requests ar
                LEFT JOIN {$this->table} ab ON ar.approved_by = ab.id
                WHERE ar.status IN ('approved', 'rejected')
                ORDER BY ar.approved_at DESC
                LIMIT ?";
        return $this->select($sql, [$limit]);
    }

    /**
     * Kiểm tra admin có phải Super Admin không
     */
    public function isSuperAdmin($adminId) {
        try {
            $sql = "SELECT COUNT(*) as count FROM super_admins WHERE admin_id = ?";
            $result = $this->select($sql, [$adminId]);
            return $result[0]['count'] > 0;
        } catch (Exception $e) {
            // ✅ Nếu bảng không tồn tại, trả về false (chưa setup)
            error_log("Error in isSuperAdmin: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Lấy yêu cầu duyệt theo ID
     */
    public function getRequestById($requestId) {
        $sql = "SELECT * FROM admin_approval_requests WHERE id = ?";
        $result = $this->select($sql, [$requestId]);
        return $result ? $result[0] : null;
    }

    /**
     * Lấy tất cả yêu cầu (phân trang)
     */
    public function getAllRequests($status = null) {
        if ($status) {
            $sql = "SELECT * FROM admin_approval_requests WHERE status = ? ORDER BY requested_at DESC";
            return $this->select($sql, [$status]);
        } else {
            $sql = "SELECT * FROM admin_approval_requests ORDER BY requested_at DESC";
            return $this->select($sql);
        }
    }
}