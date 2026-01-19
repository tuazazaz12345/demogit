<?php
require_once __DIR__ . '/../app/Controller.php';

class AdminManagementController extends Controller {
    private $adminModel;
    private $roleModel;

    public function __construct() {
        // Check admin session
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['admin'])) {
            if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
                // Nếu là AJAX request
                http_response_code(401);
                echo json_encode(['error' => 'Unauthorized']);
                exit;
            } else {
                // Nếu là regular request
                $_SESSION['redirect_url'] = $_SERVER['REQUEST_URI'];
                header("Location: " . (defined('APP_URL') ? APP_URL : 'http://localhost/phpnangcao/MVC') . "/AuthController/ShowAdminLogin");
                exit;
            }
        }

        parent::__construct();
        require_once 'models/AdminUserModel.php';
        require_once 'models/RoleModel.php';
        $this->adminModel = new AdminUserModel($this->db);
        $this->roleModel = new RoleModel($this->db);
    }

    /**
     * Danh sách quản trị viên
     */
    public function index() {
        $admins = $this->adminModel->getAllAdmins();
        
        // Lấy vai trò cho mỗi quản trị viên
        foreach ($admins as &$admin) {
            $admin['roles'] = $this->roleModel->getAdminRoles($admin['id']);
        }

        $data = array(
            'title' => 'Quản Lý Tài Khoản Quản Trị Viên',
            'admins' => $admins
        );

        require_once 'views/Back_end/AdminManagementView.php';
    }

    /**
     * Hiển thị form tạo quản trị viên
     */
    public function create() {
        $roles = $this->roleModel->getAllRoles();
        
        $data = array(
            'title' => 'Tạo Tài Khoản Quản Trị Viên Mới',
            'roles' => $roles,
            'action' => 'create'
        );

        require_once 'views/Back_end/AdminFormView.php';
    }

    /**
     * Xử lý tạo quản trị viên
     */
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = trim($_POST['username'] ?? '');
            $password = trim($_POST['password'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $fullname = trim($_POST['fullname'] ?? '');
            $roles = $_POST['roles'] ?? array();

            // Validation
            if (empty($username) || empty($password) || empty($email)) {
                $_SESSION['error'] = 'Vui lòng điền đầy đủ thông tin';
                header("Location: index.php?url=admin_management/create");
                exit;
            }

            // Tạo quản trị viên
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
            $adminId = $this->adminModel->createAdmin($username, $hashedPassword, $email, $fullname);

            if ($adminId) {
                // Gán vai trò
                if (is_array($roles) && count($roles) > 0) {
                    $this->roleModel->assignRolesToAdmin($adminId, $roles);
                }
                $_SESSION['success'] = 'Tạo tài khoản quản trị viên thành công';
            } else {
                $_SESSION['error'] = 'Lỗi khi tạo tài khoản';
            }

            header("Location: index.php?url=admin_management");
            exit;
        }
    }

    /**
     * Hiển thị form chỉnh sửa
     */
    public function edit() {
        $adminId = $_GET['id'] ?? null;
        if (!$adminId) {
            $_SESSION['error'] = 'ID quản trị viên không hợp lệ';
            header("Location: index.php?url=admin_management");
            exit;
        }

        $admin = $this->adminModel->getAdminById($adminId);
        if (!$admin) {
            $_SESSION['error'] = 'Không tìm thấy quản trị viên';
            header("Location: index.php?url=admin_management");
            exit;
        }

        $admin['roles'] = $this->roleModel->getAdminRoles($adminId);
        $allRoles = $this->roleModel->getAllRoles();

        $data = array(
            'title' => 'Chỉnh Sửa Tài Khoản Quản Trị Viên',
            'admin' => $admin,
            'allRoles' => $allRoles,
            'action' => 'edit'
        );

        require_once 'views/Back_end/AdminFormView.php';
    }

    /**
     * Xử lý cập nhật quản trị viên
     */
    public function update() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $adminId = $_POST['id'] ?? null;
            $username = trim($_POST['username'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $fullname = trim($_POST['fullname'] ?? '');
            $password = trim($_POST['password'] ?? '');
            $roles = $_POST['roles'] ?? array();

            if (!$adminId) {
                $_SESSION['error'] = 'ID quản trị viên không hợp lệ';
                header("Location: index.php?url=admin_management");
                exit;
            }

            // Cập nhật thông tin
            if (!empty($password)) {
                $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
                $this->adminModel->updateAdmin($adminId, $username, $email, $fullname, $hashedPassword);
            } else {
                $this->adminModel->updateAdmin($adminId, $username, $email, $fullname);
            }

            // Cập nhật vai trò
            if (is_array($roles) && count($roles) > 0) {
                $this->roleModel->assignRolesToAdmin($adminId, $roles);
            } else {
                // Xóa tất cả vai trò
                $currentRoles = $this->roleModel->getAdminRoles($adminId);
                foreach ($currentRoles as $role) {
                    $this->roleModel->removeRoleFromAdmin($adminId, $role['id']);
                }
            }

            $_SESSION['success'] = 'Cập nhật quản trị viên thành công';
            header("Location: index.php?url=admin_management");
            exit;
        }
    }

    /**
     * Xóa quản trị viên
     */
    public function delete() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $adminId = $_POST['id'] ?? null;

            if (!$adminId) {
                $_SESSION['error'] = 'ID quản trị viên không hợp lệ';
                header("Location: index.php?url=admin_management");
                exit;
            }

            // Không cho xóa tài khoản Admin gốc
            if ($adminId == 1) {
                $_SESSION['error'] = 'Không thể xóa tài khoản Administrator gốc';
                header("Location: index.php?url=admin_management");
                exit;
            }

            if ($this->adminModel->deleteAdmin($adminId)) {
                $_SESSION['success'] = 'Xóa quản trị viên thành công';
            } else {
                $_SESSION['error'] = 'Lỗi khi xóa quản trị viên';
            }

            header("Location: index.php?url=admin_management");
            exit;
        }
    }

    /**
     * Quản lý vai trò
     */
    public function roleManagement() {
        $roles = $this->roleModel->getAllRoles();
        $permissions = $this->roleModel->groupPermissionsByResource();

        $data = array(
            'title' => 'Quản Lý Vai Trò & Quyền Hạn',
            'roles' => $roles,
            'permissions' => $permissions
        );

        require_once 'views/Back_end/RoleManagementView.php';
    }

    /**
     * Cập nhật quyền hạn cho vai trò
     */
    public function updateRolePermissions() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $roleId = $_POST['role_id'] ?? null;
            $permissions = $_POST['permissions'] ?? array();

            if (!$roleId) {
                $_SESSION['error'] = 'ID vai trò không hợp lệ';
                header("Location: index.php?url=admin_management/role_management");
                exit;
            }

            if ($this->roleModel->assignPermissionsToRole($roleId, $permissions)) {
                $_SESSION['success'] = 'Cập nhật quyền hạn thành công';
            } else {
                $_SESSION['error'] = 'Lỗi khi cập nhật quyền hạn';
            }

            header("Location: index.php?url=admin_management/role_management");
            exit;
        }
    }
}
?>
