<?php

class RoleModel {
    private $conn;
    private $table_roles = 'roles';
    private $table_permissions = 'permissions';
    private $table_admin_roles = 'admin_roles';
    private $table_role_permissions = 'role_permissions';

    public function __construct($db) {
        $this->conn = $db;
    }

    /**
     * Lấy tất cả vai trò
     */
    public function getAllRoles() {
        try {
            $sql = "SELECT id, name, description FROM {$this->table_roles} ORDER BY id";
            $query = $this->conn->query($sql);
            return $query->fetch_all(MYSQLI_ASSOC);
        } catch (Exception $e) {
            error_log("Error in getAllRoles: " . $e->getMessage());
            return array();
        }
    }

    /**
     * Lấy vai trò theo ID
     */
    public function getRoleById($roleId) {
        try {
            $sql = "SELECT id, name, description FROM {$this->table_roles} WHERE id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("i", $roleId);
            $stmt->execute();
            $result = $stmt->get_result();
            return $result->fetch_assoc();
        } catch (Exception $e) {
            error_log("Error in getRoleById: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Tạo vai trò mới
     */
    public function createRole($name, $description) {
        try {
            $sql = "INSERT INTO {$this->table_roles} (name, description) VALUES (?, ?)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("ss", $name, $description);
            return $stmt->execute();
        } catch (Exception $e) {
            error_log("Error in createRole: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Cập nhật vai trò
     */
    public function updateRole($roleId, $name, $description) {
        try {
            $sql = "UPDATE {$this->table_roles} SET name = ?, description = ? WHERE id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("ssi", $name, $description, $roleId);
            return $stmt->execute();
        } catch (Exception $e) {
            error_log("Error in updateRole: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Xóa vai trò
     */
    public function deleteRole($roleId) {
        try {
            $sql = "DELETE FROM {$this->table_roles} WHERE id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("i", $roleId);
            return $stmt->execute();
        } catch (Exception $e) {
            error_log("Error in deleteRole: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Lấy tất cả quyền hạn
     */
    public function getAllPermissions() {
        try {
            $sql = "SELECT id, name, description, resource, action FROM {$this->table_permissions} ORDER BY resource, action";
            $query = $this->conn->query($sql);
            return $query->fetch_all(MYSQLI_ASSOC);
        } catch (Exception $e) {
            error_log("Error in getAllPermissions: " . $e->getMessage());
            return array();
        }
    }

    /**
     * Lấy quyền hạn của vai trò
     */
    public function getRolePermissions($roleId) {
        try {
            $sql = "SELECT p.id, p.name, p.description, p.resource, p.action 
                    FROM {$this->table_permissions} p
                    INNER JOIN {$this->table_role_permissions} rp ON p.id = rp.permission_id
                    WHERE rp.role_id = ?
                    ORDER BY p.resource, p.action";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("i", $roleId);
            $stmt->execute();
            $result = $stmt->get_result();
            return $result->fetch_all(MYSQLI_ASSOC);
        } catch (Exception $e) {
            error_log("Error in getRolePermissions: " . $e->getMessage());
            return array();
        }
    }

    /**
     * Gán quyền hạn cho vai trò
     */
    public function assignPermissionToRole($roleId, $permissionId) {
        try {
            $sql = "INSERT IGNORE INTO {$this->table_role_permissions} (role_id, permission_id) VALUES (?, ?)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("ii", $roleId, $permissionId);
            return $stmt->execute();
        } catch (Exception $e) {
            error_log("Error in assignPermissionToRole: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Gỡ quyền hạn khỏi vai trò
     */
    public function removePermissionFromRole($roleId, $permissionId) {
        try {
            $sql = "DELETE FROM {$this->table_role_permissions} WHERE role_id = ? AND permission_id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("ii", $roleId, $permissionId);
            return $stmt->execute();
        } catch (Exception $e) {
            error_log("Error in removePermissionFromRole: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Gán vai trò cho quản trị viên
     */
    public function assignRoleToAdmin($adminId, $roleId) {
        try {
            $sql = "INSERT IGNORE INTO {$this->table_admin_roles} (admin_id, role_id) VALUES (?, ?)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("ii", $adminId, $roleId);
            return $stmt->execute();
        } catch (Exception $e) {
            error_log("Error in assignRoleToAdmin: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Gỡ vai trò khỏi quản trị viên
     */
    public function removeRoleFromAdmin($adminId, $roleId) {
        try {
            $sql = "DELETE FROM {$this->table_admin_roles} WHERE admin_id = ? AND role_id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("ii", $adminId, $roleId);
            return $stmt->execute();
        } catch (Exception $e) {
            error_log("Error in removeRoleFromAdmin: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Lấy vai trò của quản trị viên
     */
    public function getAdminRoles($adminId) {
        try {
            $sql = "SELECT r.id, r.name, r.description 
                    FROM {$this->table_roles} r
                    INNER JOIN {$this->table_admin_roles} ar ON r.id = ar.role_id
                    WHERE ar.admin_id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("i", $adminId);
            $stmt->execute();
            $result = $stmt->get_result();
            return $result->fetch_all(MYSQLI_ASSOC);
        } catch (Exception $e) {
            error_log("Error in getAdminRoles: " . $e->getMessage());
            return array();
        }
    }

    /**
     * Lấy quyền hạn của quản trị viên
     */
    public function getAdminPermissions($adminId) {
        try {
            $sql = "SELECT DISTINCT p.id, p.name, p.resource, p.action 
                    FROM {$this->table_permissions} p
                    INNER JOIN {$this->table_role_permissions} rp ON p.id = rp.permission_id
                    INNER JOIN {$this->table_admin_roles} ar ON rp.role_id = ar.role_id
                    WHERE ar.admin_id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("i", $adminId);
            $stmt->execute();
            $result = $stmt->get_result();
            return $result->fetch_all(MYSQLI_ASSOC);
        } catch (Exception $e) {
            error_log("Error in getAdminPermissions: " . $e->getMessage());
            return array();
        }
    }

    /**
     * Kiểm tra quản trị viên có quyền hạn
     */
    public function hasPermission($adminId, $resource, $action) {
        try {
            $sql = "SELECT COUNT(*) as count 
                    FROM {$this->table_permissions} p
                    INNER JOIN {$this->table_role_permissions} rp ON p.id = rp.permission_id
                    INNER JOIN {$this->table_admin_roles} ar ON rp.role_id = ar.role_id
                    WHERE ar.admin_id = ? AND p.resource = ? AND p.action = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("iss", $adminId, $resource, $action);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            return $row['count'] > 0;
        } catch (Exception $e) {
            error_log("Error in hasPermission: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Gán multiple quyền hạn cho vai trò
     */
    public function assignPermissionsToRole($roleId, $permissionIds) {
        try {
            // Xóa quyền hạn cũ
            $sql = "DELETE FROM {$this->table_role_permissions} WHERE role_id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("i", $roleId);
            $stmt->execute();

            // Thêm quyền hạn mới
            if (is_array($permissionIds) && count($permissionIds) > 0) {
                $sql = "INSERT INTO {$this->table_role_permissions} (role_id, permission_id) VALUES (?, ?)";
                $stmt = $this->conn->prepare($sql);
                foreach ($permissionIds as $permissionId) {
                    $stmt->bind_param("ii", $roleId, $permissionId);
                    $stmt->execute();
                }
            }
            return true;
        } catch (Exception $e) {
            error_log("Error in assignPermissionsToRole: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Gán multiple vai trò cho quản trị viên
     */
    public function assignRolesToAdmin($adminId, $roleIds) {
        try {
            // Xóa vai trò cũ
            $sql = "DELETE FROM {$this->table_admin_roles} WHERE admin_id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("i", $adminId);
            $stmt->execute();

            // Thêm vai trò mới
            if (is_array($roleIds) && count($roleIds) > 0) {
                $sql = "INSERT INTO {$this->table_admin_roles} (admin_id, role_id) VALUES (?, ?)";
                $stmt = $this->conn->prepare($sql);
                foreach ($roleIds as $roleId) {
                    $stmt->bind_param("ii", $adminId, $roleId);
                    $stmt->execute();
                }
            }
            return true;
        } catch (Exception $e) {
            error_log("Error in assignRolesToAdmin: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Nhóm quyền hạn theo resource
     */
    public function groupPermissionsByResource() {
        try {
            $sql = "SELECT resource, name, id, action FROM {$this->table_permissions} ORDER BY resource, action";
            $query = $this->conn->query($sql);
            $permissions = $query->fetch_all(MYSQLI_ASSOC);
            
            $grouped = array();
            foreach ($permissions as $perm) {
                $grouped[$perm['resource']][] = $perm;
            }
            return $grouped;
        } catch (Exception $e) {
            error_log("Error in groupPermissionsByResource: " . $e->getMessage());
            return array();
        }
    }
}
?>
