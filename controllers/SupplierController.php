<?php
class SupplierController extends Controller {
    private function checkAdminSession() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['admin'])) {
            $_SESSION['redirect_url'] = $_SERVER['REQUEST_URI'];
            header("Location: " . APP_URL . "/AuthController/ShowAdminLogin");
            exit;
        }

        if (!isset($_SESSION['admin_token'])) {
            $_SESSION['admin_token'] = bin2hex(random_bytes(32));
        }
    }

    public function __construct() {
        $this->checkAdminSession();
    }

    // ====================== QUẢN LÝ NHÀ CUNG CẤP ======================
    
    // Hiển thị danh sách nhà cung cấp
    public function index() {
        $supplierModel = $this->model("SupplierModel");
        
        // Lấy keyword tìm kiếm nếu có
        $keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';
        
        // Nếu có keyword, tìm kiếm; nếu không, lấy tất cả
        if (!empty($keyword)) {
            $suppliers = $supplierModel->searchSuppliers($keyword);
        } else {
            $suppliers = $supplierModel->getAllSuppliersWithInactive();
        }
        
        $this->view("adminPage", [
            "page" => "SupplierView",
            "suppliers" => $suppliers,
            "keyword" => $keyword
        ]);
    }

    // Hiển thị form thêm nhà cung cấp
    public function create() {
        $this->view("adminPage", [
            "page" => "SupplierCreateView"
        ]);
    }

    // Xử lý thêm nhà cung cấp
    public function store() {
        $this->checkAdminSession();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'ten_ncc' => trim($_POST['ten_ncc'] ?? ''),
                'dia_chi' => trim($_POST['dia_chi'] ?? ''),
                'dien_thoai' => trim($_POST['dien_thoai'] ?? ''),
                'email' => trim($_POST['email'] ?? ''),
                'han_hop_dong' => trim($_POST['han_hop_dong'] ?? ''),
                'trang_thai' => isset($_POST['trang_thai']) ? 1 : 0
            ];

            // Kiểm tra dữ liệu
            if (empty($data['ten_ncc'])) {
                $_SESSION['error'] = "Tên nhà cung cấp không được trống!";
                header("Location: " . APP_URL . "/SupplierController/create");
                exit;
            }

            $supplierModel = $this->model("SupplierModel");
            $result = $supplierModel->addSupplier($data);

            if ($result['success']) {
                $_SESSION['success'] = $result['message'];
                header("Location: " . APP_URL . "/SupplierController/index");
            } else {
                $_SESSION['error'] = $result['message'];
                header("Location: " . APP_URL . "/SupplierController/create");
            }
            exit;
        }
    }

    // Hiển thị form sửa nhà cung cấp
    public function edit($id) {
        $supplierModel = $this->model("SupplierModel");
        $supplier = $supplierModel->getSupplierById($id);

        if (!$supplier) {
            $_SESSION['error'] = "Không tìm thấy nhà cung cấp!";
            header("Location: " . APP_URL . "/SupplierController/index");
            exit;
        }

        $this->view("adminPage", [
            "page" => "SupplierEditView",
            "supplier" => $supplier
        ]);
    }

    // Xử lý cập nhật nhà cung cấp
    public function update($id) {
        $this->checkAdminSession();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'ten_ncc' => trim($_POST['ten_ncc'] ?? ''),
                'dia_chi' => trim($_POST['dia_chi'] ?? ''),
                'dien_thoai' => trim($_POST['dien_thoai'] ?? ''),
                'email' => trim($_POST['email'] ?? ''),
                'han_hop_dong' => trim($_POST['han_hop_dong'] ?? ''),
                'trang_thai' => isset($_POST['trang_thai']) ? 1 : 0
            ];

            // Kiểm tra dữ liệu
            if (empty($data['ten_ncc'])) {
                $_SESSION['error'] = "Tên nhà cung cấp không được trống!";
                header("Location: " . APP_URL . "/SupplierController/edit/" . $id);
                exit;
            }

            $supplierModel = $this->model("SupplierModel");
            $result = $supplierModel->updateSupplier($id, $data);

            if ($result['success']) {
                $_SESSION['success'] = $result['message'];
                header("Location: " . APP_URL . "/SupplierController/index");
            } else {
                $_SESSION['error'] = $result['message'];
                header("Location: " . APP_URL . "/SupplierController/edit/" . $id);
            }
            exit;
        }
    }

    // Xóa nhà cung cấp
    public function delete($id) {
        $this->checkAdminSession();

        $supplierModel = $this->model("SupplierModel");
        $result = $supplierModel->deleteSupplier($id);

        if ($result['success']) {
            $_SESSION['success'] = $result['message'];
        } else {
            $_SESSION['error'] = $result['message'];
        }

        header("Location: " . APP_URL . "/SupplierController/index");
        exit;
    }
}
?>
