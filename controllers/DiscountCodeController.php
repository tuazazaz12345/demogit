<?php
class DiscountCodeController extends Controller {
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
        // ✅ Không kiểm tra session ở đây - để cho verify() hoạt động
        // checkAdminSession() sẽ được gọi riêng cho mỗi method
    }

    // ====================== QUẢN LÝ MÃ GIẢM GIÁ ======================
    
    // Hiển thị danh sách mã giảm giá
    public function index() {
        $this->checkAdminSession();  // ✅ Kiểm tra session
        $discountModel = $this->model("DiscountCodeModel");
        $discounts = $discountModel->getAll();
        
        $this->view("adminPage", [
            "page" => "DiscountCodeView",
            "discounts" => $discounts
        ]);
    }

    // Hiển thị form thêm mã giảm giá
    public function create() {
        $this->checkAdminSession();  // ✅ Kiểm tra session
        $this->view("adminPage", [
            "page" => "DiscountCodeCreateView"
        ]);
    }

    // Xử lý thêm mã giảm giá
    public function store() {
        $this->checkAdminSession();  // ✅ Kiểm tra session
        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            header("Location: " . APP_URL . "/DiscountCodeController/index");
            return;
        }

        $discountModel = $this->model("DiscountCodeModel");
        
        $data = [
            'code' => $_POST['code'] ?? '',
            'description' => $_POST['description'] ?? '',
            'discount_type' => $_POST['discount_type'] ?? 'percentage',
            'discount_value' => $_POST['discount_value'] ?? 0,
            'min_order_value' => $_POST['min_order_value'] ?? 0,
            'max_discount' => $_POST['max_discount'] ?? null,
            'start_date' => $_POST['start_date'] ?? '',
            'end_date' => $_POST['end_date'] ?? '',
            'usage_limit' => $_POST['usage_limit'] ?? null,
            'status' => isset($_POST['status']) ? 1 : 0
        ];

        // Validate
        if (empty($data['code']) || empty($data['discount_value']) || empty($data['start_date']) || empty($data['end_date'])) {
            $_SESSION['error'] = "Vui lòng điền đầy đủ thông tin";
            header("Location: " . APP_URL . "/DiscountCodeController/create");
            return;
        }

        // ✅ KIỂM TRA MÃ ĐÃ TỒN TẠI CHƯA
        $codeUpper = strtoupper($data['code']);
        try {
            if ($discountModel->codeExists($codeUpper)) {
                $_SESSION['error'] = "Mã giảm giá '<strong>" . htmlspecialchars($codeUpper) . "</strong>' đã tồn tại! Vui lòng dùng mã khác.";
                header("Location: " . APP_URL . "/DiscountCodeController/create");
                return;
            }
        } catch (Exception $e) {
            $_SESSION['error'] = "Lỗi kiểm tra mã: " . $e->getMessage();
            header("Location: " . APP_URL . "/DiscountCodeController/create");
            return;
        }

        try {
            if ($discountModel->addCode($data)) {
                $_SESSION['success'] = "Thêm mã giảm giá thành công";
            } else {
                $_SESSION['error'] = "Lỗi khi thêm mã giảm giá";
            }
        } catch (PDOException $e) {
            // Nếu vẫn lỗi duplicate (thường do race condition), hiển thị thông báo
            if (strpos($e->getMessage(), 'Duplicate') !== false) {
                $_SESSION['error'] = "Mã giảm giá '<strong>" . htmlspecialchars($codeUpper) . "</strong>' đã tồn tại! Vui lòng dùng mã khác.";
            } else {
                $_SESSION['error'] = "Lỗi database: " . $e->getMessage();
            }
        } catch (Exception $e) {
            $_SESSION['error'] = "Lỗi: " . $e->getMessage();
        }
        
        header("Location: " . APP_URL . "/DiscountCodeController/index");
    }

    // Hiển thị form chỉnh sửa mã giảm giá
    public function edit($id) {
        $this->checkAdminSession();  // ✅ Kiểm tra session
        $discountModel = $this->model("DiscountCodeModel");
        $discount = $discountModel->getById($id);
        
        if (empty($discount)) {
            $_SESSION['error'] = "Mã giảm giá không tồn tại";
            header("Location: " . APP_URL . "/DiscountCodeController/index");
            return;
        }

        $discount = $discount[0];
        
        $this->view("adminPage", [
            "page" => "DiscountCodeEditView",
            "discount" => $discount
        ]);
    }

    // Xử lý cập nhật mã giảm giá
    public function update($id) {
        $this->checkAdminSession();  // ✅ Kiểm tra session
        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            header("Location: " . APP_URL . "/DiscountCodeController/index");
            return;
        }

        $discountModel = $this->model("DiscountCodeModel");
        
        $data = [
            'code' => $_POST['code'] ?? '',
            'description' => $_POST['description'] ?? '',
            'discount_type' => $_POST['discount_type'] ?? 'percentage',
            'discount_value' => $_POST['discount_value'] ?? 0,
            'min_order_value' => $_POST['min_order_value'] ?? 0,
            'max_discount' => $_POST['max_discount'] ?? null,
            'start_date' => $_POST['start_date'] ?? '',
            'end_date' => $_POST['end_date'] ?? '',
            'usage_limit' => $_POST['usage_limit'] ?? null,
            'status' => isset($_POST['status']) ? 1 : 0
        ];

        // ✅ KIỂM TRA MÃ MỚI ĐÃ TỒN TẠI CHƯA (nếu đổi mã)
        $oldCode = $discountModel->getById($id);
        if (!empty($oldCode)) {
            $oldCode = strtoupper($oldCode[0]['code']);
            $newCode = strtoupper($data['code']);
            
            // Nếu đổi mã, kiểm tra mã mới có trùng không
            if ($oldCode !== $newCode) {
                if ($discountModel->codeExists($newCode)) {
                    $_SESSION['error'] = "Mã giảm giá '<strong>" . htmlspecialchars($newCode) . "</strong>' đã tồn tại! Vui lòng dùng mã khác.";
                    header("Location: " . APP_URL . "/DiscountCodeController/edit/" . $id);
                    return;
                }
            }
        }

        try {
            if ($discountModel->editCode($id, $data)) {
                $_SESSION['success'] = "Cập nhật mã giảm giá thành công";
            } else {
                $_SESSION['error'] = "Lỗi khi cập nhật mã giảm giá";
            }
        } catch (PDOException $e) {
            if (strpos($e->getMessage(), 'Duplicate') !== false) {
                $_SESSION['error'] = "Mã giảm giá đã tồn tại! Vui lòng dùng mã khác.";
            } else {
                $_SESSION['error'] = "Lỗi database: " . $e->getMessage();
            }
        }
        
        header("Location: " . APP_URL . "/DiscountCodeController/index");
    }

    // Xóa mã giảm giá
    public function delete($id) {
        $this->checkAdminSession();  // ✅ Kiểm tra session
        $discountModel = $this->model("DiscountCodeModel");
        
        if ($discountModel->removeCode($id)) {
            $_SESSION['success'] = "Xóa mã giảm giá thành công";
        } else {
            $_SESSION['error'] = "Lỗi khi xóa mã giảm giá";
        }
        
        header("Location: " . APP_URL . "/DiscountCodeController/index");
    }

    // ✅ API: Kiểm tra mã giảm giá (KHÔNG cần session - công khai)
    public function verify() {
        error_log("verify() called - REQUEST_METHOD: " . $_SERVER['REQUEST_METHOD']);
        
        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            http_response_code(400);
            echo json_encode(['valid' => false, 'message' => 'Invalid request']);
            return;
        }

        $code = $_POST['code'] ?? '';
        $totalAmount = (float)($_POST['total'] ?? 0);  // ✅ Cast thành float
        
        error_log("verify() - code: '$code', total: $totalAmount");

        $discountModel = $this->model("DiscountCodeModel");
        $result = $discountModel->calculateDiscount($code, $totalAmount);
        
        error_log("verify() - result: " . print_r($result, true));

        header('Content-Type: application/json');
        echo json_encode($result);
    }
}
?>
