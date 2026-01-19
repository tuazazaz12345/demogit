<?php
class PromotionController extends Controller {
    private function checkAdminSession() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['admin'])) {
            $_SESSION['redirect_url'] = $_SERVER['REQUEST_URI'];
            header("Location: " . APP_URL . "/AuthController/ShowAdminLogin");
            exit;
        }
    }

    public function __construct() {
        $this->checkAdminSession();
    }

    // ====================== QUẢN LÝ KHUYẾN MẠI ======================
    
    // Hiển thị danh sách khuyến mại
    public function index() {
        $promotionModel = $this->model("PromotionModel");
        $promotions = $promotionModel->getAll();
        
        $this->view("adminPage", [
            "page" => "PromotionView",
            "promotions" => $promotions
        ]);
    }

    // Hiển thị form thêm khuyến mại
    public function create() {
        $promotionModel = $this->model("PromotionModel");
        $products = $promotionModel->getAllProducts();
        
        $this->view("adminPage", [
            "page" => "PromotionCreateView",
            "products" => $products
        ]);
    }

    // Xử lý thêm khuyến mại
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            header("Location: " . APP_URL . "/PromotionController/index");
            return;
        }

        $promotionModel = $this->model("PromotionModel");
        
        $data = [
            'name' => $_POST['name'] ?? '',
            'description' => $_POST['description'] ?? '',
            'discount_percent' => $_POST['discount_percent'] ?? 0,
            'start_date' => $_POST['start_date'] ?? '',
            'end_date' => $_POST['end_date'] ?? '',
            'status' => isset($_POST['status']) ? 1 : 0
        ];

        // Validate
        if (empty($data['name']) || empty($data['discount_percent']) || empty($data['start_date']) || empty($data['end_date'])) {
            $_SESSION['error'] = "Vui lòng điền đầy đủ thông tin";
            header("Location: " . APP_URL . "/PromotionController/create");
            return;
        }

        if ($promotionModel->addPromotion($data)) {
            $promotionId = $promotionModel->getLastInsertId();
            
            // Gán sản phẩm nếu có
            if (isset($_POST['products']) && is_array($_POST['products'])) {
                $promotionModel->assignProducts($promotionId, $_POST['products']);
            }
            
            $_SESSION['success'] = "Thêm khuyến mại thành công";
        } else {
            $_SESSION['error'] = "Lỗi khi thêm khuyến mại";
        }
        
        header("Location: " . APP_URL . "/PromotionController/index");
    }

    // Hiển thị form chỉnh sửa khuyến mại
    public function edit($id) {
        $promotionModel = $this->model("PromotionModel");
        $promotion = $promotionModel->getById($id);
        
        if (empty($promotion)) {
            $_SESSION['error'] = "Khuyến mại không tồn tại";
            header("Location: " . APP_URL . "/PromotionController/index");
            return;
        }

        $promotion = $promotion[0];
        $products = $promotionModel->getAllProducts();
        $assignedProducts = $promotionModel->getPromotionProducts($id);
        $assignedProductIds = array_column($assignedProducts, 'masp');
        
        $this->view("adminPage", [
            "page" => "PromotionEditView",
            "promotion" => $promotion,
            "products" => $products,
            "assignedProductIds" => $assignedProductIds
        ]);
    }

    // Xử lý cập nhật khuyến mại
    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            header("Location: " . APP_URL . "/PromotionController/index");
            return;
        }

        $promotionModel = $this->model("PromotionModel");
        
        $data = [
            'name' => $_POST['name'] ?? '',
            'description' => $_POST['description'] ?? '',
            'discount_percent' => $_POST['discount_percent'] ?? 0,
            'start_date' => $_POST['start_date'] ?? '',
            'end_date' => $_POST['end_date'] ?? '',
            'status' => isset($_POST['status']) ? 1 : 0
        ];

        if ($promotionModel->editPromotion($id, $data)) {
            // Cập nhật sản phẩm
            if (isset($_POST['products']) && is_array($_POST['products'])) {
                $promotionModel->assignProducts($id, $_POST['products']);
            } else {
                $promotionModel->assignProducts($id, []);
            }
            
            $_SESSION['success'] = "Cập nhật khuyến mại thành công";
        } else {
            $_SESSION['error'] = "Lỗi khi cập nhật khuyến mại";
        }
        
        header("Location: " . APP_URL . "/PromotionController/index");
    }

    // Xóa khuyến mại
    public function delete($id) {
        $promotionModel = $this->model("PromotionModel");
        
        if ($promotionModel->removePromotion($id)) {
            $_SESSION['success'] = "Xóa khuyến mại thành công";
        } else {
            $_SESSION['error'] = "Lỗi khi xóa khuyến mại";
        }
        
        header("Location: " . APP_URL . "/PromotionController/index");
    }
}
?>
