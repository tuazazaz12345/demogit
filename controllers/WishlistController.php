<?php
class WishlistController extends Controller {
    
    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * Hiển thị danh sách wishlist
     */
    public function index() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Kiểm tra user đã login
        if (!isset($_SESSION['user'])) {
            $_SESSION['redirect_url'] = $_SERVER['REQUEST_URI'];
            header("Location: " . APP_URL . "/AuthController/ShowLogin");
            exit;
        }

        $wishlistModel = $this->model("WishlistModel");
        $wishlistItems = $wishlistModel->getByEmail($_SESSION['user']['email']);

        $this->view("homePage", [
            "page" => "WishlistView",
            "wishlist" => $wishlistItems,
            "totalItems" => count($wishlistItems)
        ]);
    }

    /**
     * Thêm sản phẩm vào wishlist (AJAX)
     */
    public function add() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        header('Content-Type: application/json');

        // Kiểm tra user đã login
        if (!isset($_SESSION['user'])) {
            echo json_encode([
                'success' => false,
                'message' => 'Vui lòng đăng nhập',
                'redirect' => APP_URL . '/AuthController/ShowLogin'
            ]);
            exit;
        }

        $masp = $_POST['masp'] ?? null;

        if (!$masp) {
            echo json_encode([
                'success' => false,
                'message' => 'Mã sản phẩm không hợp lệ'
            ]);
            exit;
        }

        try {
            $wishlistModel = $this->model("WishlistModel");
            
            // Kiểm tra đã có trong wishlist chưa
            if ($wishlistModel->exists($_SESSION['user']['email'], $masp)) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Sản phẩm đã có trong danh sách yêu thích'
                ]);
                exit;
            }

            // Thêm vào wishlist
            $wishlistModel->add($_SESSION['user']['email'], $masp);

            echo json_encode([
                'success' => true,
                'message' => 'Đã thêm vào danh sách yêu thích'
            ]);
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => 'Lỗi: ' . $e->getMessage()
            ]);
        }
        exit;
    }

    /**
     * Xóa sản phẩm khỏi wishlist (AJAX)
     */
    public function remove() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        header('Content-Type: application/json');

        // Kiểm tra user đã login
        if (!isset($_SESSION['user'])) {
            echo json_encode([
                'success' => false,
                'message' => 'Vui lòng đăng nhập'
            ]);
            exit;
        }

        $masp = $_POST['masp'] ?? null;

        if (!$masp) {
            echo json_encode([
                'success' => false,
                'message' => 'Mã sản phẩm không hợp lệ'
            ]);
            exit;
        }

        try {
            $wishlistModel = $this->model("WishlistModel");
            $wishlistModel->remove($_SESSION['user']['email'], $masp);

            echo json_encode([
                'success' => true,
                'message' => 'Đã xóa khỏi danh sách yêu thích'
            ]);
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => 'Lỗi: ' . $e->getMessage()
            ]);
        }
        exit;
    }

    /**
     * Lấy số lượng item trong wishlist (AJAX)
     */
    public function count() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        header('Content-Type: application/json');

        if (!isset($_SESSION['user'])) {
            echo json_encode(['count' => 0]);
            exit;
        }

        try {
            $wishlistModel = $this->model("WishlistModel");
            $count = $wishlistModel->countByEmail($_SESSION['user']['email']);
            echo json_encode(['count' => $count]);
        } catch (Exception $e) {
            echo json_encode(['count' => 0]);
        }
        exit;
    }

    /**
     * Kiểm tra sản phẩm có trong wishlist không (AJAX)
     */
    public function checkExists() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        header('Content-Type: application/json');

        $masp = $_POST['masp'] ?? null;

        if (!isset($_SESSION['user']) || !$masp) {
            echo json_encode(['exists' => false]);
            exit;
        }

        try {
            $wishlistModel = $this->model("WishlistModel");
            $exists = $wishlistModel->exists($_SESSION['user']['email'], $masp);
            echo json_encode(['exists' => $exists]);
        } catch (Exception $e) {
            echo json_encode(['exists' => false]);
        }
        exit;
    }

    /**
     * Chuyển wishlist thành order
     */
    public function addToCart($masp) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user'])) {
            $_SESSION['redirect_url'] = $_SERVER['REQUEST_URI'];
            header("Location: " . APP_URL . "/AuthController/ShowLogin");
            exit;
        }

        // Lấy thông tin sản phẩm
        $productModel = $this->model("AdProducModel");
        $data = $productModel->find("tblsanpham", $masp);

        if (!$data) {
            $_SESSION['error'] = 'Sản phẩm không tồn tại';
            header("Location: " . APP_URL . "/WishlistController/index");
            exit;
        }

        // Thêm vào cart theo cách giống Home.php (dùng masp làm key)
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        $quantity = 1;

        if (isset($_SESSION['cart'][$masp])) {
            $_SESSION['cart'][$masp]['qty'] += $quantity;
        } else {
            $_SESSION['cart'][$masp] = [
                'qty' => $quantity,
                'masp' => $data['masp'],
                'tensp' => $data['tensp'],
                'hinhanh' => $data['hinhanh'],
                'giaxuat' => $data['giaXuat'],
                'khuyenmai' => $data['khuyenmai'],
                'from_promotion' => false
            ];
        }

        // Xóa khỏi wishlist
        try {
            $wishlistModel = $this->model("WishlistModel");
            $wishlistModel->remove($_SESSION['user']['email'], $masp);
        } catch (Exception $e) {
            // Silent fail
        }

        // ✅ TỰ ĐỘNG LƯU GIỎ HÀNG VÀO DATABASE
        try {
            $userId = (int)$_SESSION['user']['id'];
            $cartModel = $this->model('ShoppingCartModel');
            $result = $cartModel->saveCart($userId, $_SESSION['cart']);
            error_log('Cart auto-saved after add from wishlist: User ' . $userId . ' Result: ' . ($result ? 'SUCCESS' : 'FAILED'));
        } catch (Exception $e) {
            error_log('Error auto-saving cart: ' . $e->getMessage());
        }

        $_SESSION['success'] = 'Đã thêm sản phẩm vào giỏ hàng';
        header("Location: " . APP_URL . "/Home/order");
        exit;
    }
}
