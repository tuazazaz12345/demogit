<?php

class PermissionMiddleware {
    private $roleModel;
    private $adminId;

    public function __construct($db, $adminId) {
        $this->adminId = $adminId;
        require_once 'models/RoleModel.php';
        $this->roleModel = new RoleModel($db);
    }

    /**
     * Kiểm tra quản trị viên có quyền hạn
     */
    public function hasPermission($resource, $action) {
        if (!$this->adminId) {
            return false;
        }

        return $this->roleModel->hasPermission($this->adminId, $resource, $action);
    }

    /**
     * Require permission - Dừng nếu không có quyền
     */
    public function requirePermission($resource, $action) {
        if (!$this->hasPermission($resource, $action)) {
            http_response_code(403);
            die("
                <div style='text-align: center; padding: 50px;'>
                    <h1>❌ Truy Cập Bị Từ Chối</h1>
                    <p>Bạn không có quyền truy cập chức năng này.</p>
                    <a href='index.php' style='padding: 10px 20px; background: #0d6efd; color: white; text-decoration: none; border-radius: 4px;'>
                        Quay Về Trang Chủ
                    </a>
                </div>
            ");
        }
    }

    /**
     * Lấy tất cả quyền hạn của quản trị viên
     */
    public function getPermissions() {
        if (!$this->adminId) {
            return array();
        }

        return $this->roleModel->getAdminPermissions($this->adminId);
    }

    /**
     * Kiểm tra một trong nhiều quyền hạn
     */
    public function hasAnyPermission($permissions) {
        if (!$this->adminId) {
            return false;
        }

        if (!is_array($permissions)) {
            $permissions = array($permissions);
        }

        foreach ($permissions as $perm) {
            list($resource, $action) = explode(':', $perm);
            if ($this->hasPermission($resource, $action)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Kiểm tra tất cả quyền hạn
     */
    public function hasAllPermissions($permissions) {
        if (!$this->adminId) {
            return false;
        }

        if (!is_array($permissions)) {
            $permissions = array($permissions);
        }

        foreach ($permissions as $perm) {
            list($resource, $action) = explode(':', $perm);
            if (!$this->hasPermission($resource, $action)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Kiểm tra quyền và trả về true/false (không dừng)
     */
    public function can($resource, $action) {
        return $this->hasPermission($resource, $action);
    }

    /**
     * Kiểm tra quyền và trả về true/false (không dừng)
     */
    public function cannot($resource, $action) {
        return !$this->hasPermission($resource, $action);
    }

    /**
     * Ghi log truy cập bị từ chối
     */
    public function logDeniedAccess($resource, $action) {
        $logMessage = "[" . date('Y-m-d H:i:s') . "] Admin #{$this->adminId} tried to access {$resource}:{$action} without permission";
        error_log($logMessage);
    }
}
?>
