<?php

class AdminUserModel {
    private $conn;
    private $table = 'tbluser';

    public function __construct($db) {
        $this->conn = $db;
    }

    /**
     * Lấy tất cả quản trị viên (loại trừ khách hàng)
     */
    public function getAllAdmins() {
        try {
            $sql = "SELECT id, username, email, fullname, loaiuser, ngaytao FROM {$this->table} 
                    WHERE loaiuser = 'Admin' ORDER BY ngaytao DESC";
            $query = $this->conn->query($sql);
            return $query->fetch_all(MYSQLI_ASSOC);
        } catch (Exception $e) {
            error_log("Error in getAllAdmins: " . $e->getMessage());
            return array();
        }
    }

    /**
     * Lấy quản trị viên theo ID
     */
    public function getAdminById($adminId) {
        try {
            $sql = "SELECT id, username, email, fullname, loaiuser, ngaytao FROM {$this->table} 
                    WHERE id = ? AND loaiuser = 'Admin'";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("i", $adminId);
            $stmt->execute();
            $result = $stmt->get_result();
            return $result->fetch_assoc();
        } catch (Exception $e) {
            error_log("Error in getAdminById: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Lấy quản trị viên theo username
     */
    public function getAdminByUsername($username) {
        try {
            $sql = "SELECT id, username, email, fullname, loaiuser, password, ngaytao FROM {$this->table} 
                    WHERE username = ? AND loaiuser = 'Admin'";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();
            return $result->fetch_assoc();
        } catch (Exception $e) {
            error_log("Error in getAdminByUsername: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Tạo quản trị viên mới
     */
    public function createAdmin($username, $password, $email, $fullname) {
        try {
            $loaiuser = 'Admin';
            $ngaytao = date('Y-m-d H:i:s');

            $sql = "INSERT INTO {$this->table} (username, password, email, fullname, loaiuser, ngaytao) 
                    VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("ssssss", $username, $password, $email, $fullname, $loaiuser, $ngaytao);
            
            if ($stmt->execute()) {
                return $this->conn->insert_id;
            }
            return false;
        } catch (Exception $e) {
            error_log("Error in createAdmin: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Cập nhật quản trị viên
     */
    public function updateAdmin($adminId, $username, $email, $fullname, $password = null) {
        try {
            if ($password) {
                $sql = "UPDATE {$this->table} SET username = ?, email = ?, fullname = ?, password = ? 
                        WHERE id = ? AND loaiuser = 'Admin'";
                $stmt = $this->conn->prepare($sql);
                $stmt->bind_param("ssssi", $username, $email, $fullname, $password, $adminId);
            } else {
                $sql = "UPDATE {$this->table} SET username = ?, email = ?, fullname = ? 
                        WHERE id = ? AND loaiuser = 'Admin'";
                $stmt = $this->conn->prepare($sql);
                $stmt->bind_param("sssi", $username, $email, $fullname, $adminId);
            }
            
            return $stmt->execute();
        } catch (Exception $e) {
            error_log("Error in updateAdmin: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Xóa quản trị viên
     */
    public function deleteAdmin($adminId) {
        try {
            $sql = "DELETE FROM {$this->table} WHERE id = ? AND loaiuser = 'Admin'";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("i", $adminId);
            return $stmt->execute();
        } catch (Exception $e) {
            error_log("Error in deleteAdmin: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Tìm kiếm quản trị viên
     */
    public function searchAdmins($keyword) {
        try {
            $keyword = "%{$keyword}%";
            $sql = "SELECT id, username, email, fullname, loaiuser, ngaytao FROM {$this->table} 
                    WHERE loaiuser = 'Admin' AND (username LIKE ? OR email LIKE ? OR fullname LIKE ?) 
                    ORDER BY ngaytao DESC";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("sss", $keyword, $keyword, $keyword);
            $stmt->execute();
            $result = $stmt->get_result();
            return $result->fetch_all(MYSQLI_ASSOC);
        } catch (Exception $e) {
            error_log("Error in searchAdmins: " . $e->getMessage());
            return array();
        }
    }

    /**
     * Kiểm tra username đã tồn tại
     */
    public function usernameExists($username) {
        try {
            $sql = "SELECT id FROM {$this->table} WHERE username = ? AND loaiuser = 'Admin'";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();
            return $result->num_rows > 0;
        } catch (Exception $e) {
            error_log("Error in usernameExists: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Kiểm tra email đã tồn tại
     */
    public function emailExists($email, $excludeId = null) {
        try {
            if ($excludeId) {
                $sql = "SELECT id FROM {$this->table} WHERE email = ? AND loaiuser = 'Admin' AND id != ?";
                $stmt = $this->conn->prepare($sql);
                $stmt->bind_param("si", $email, $excludeId);
            } else {
                $sql = "SELECT id FROM {$this->table} WHERE email = ? AND loaiuser = 'Admin'";
                $stmt = $this->conn->prepare($sql);
                $stmt->bind_param("s", $email);
            }
            
            $stmt->execute();
            $result = $stmt->get_result();
            return $result->num_rows > 0;
        } catch (Exception $e) {
            error_log("Error in emailExists: " . $e->getMessage());
            return false;
        }
    }
}
?>
