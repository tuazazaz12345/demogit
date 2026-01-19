<?php
class OrderController extends Controller {
    
    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }
    
    // Trang mặc định
    public function index() {
        if (!isset($_SESSION['user'])) {
            header('Location: ' . APP_URL . '/AuthController/showLogin');
            exit();
        }
        $this->orderHistory();
    }

    // Xem lịch sử đơn hàng
    public function orderHistory() {
        if (!isset($_SESSION['user'])) {
            header('Location: ' . APP_URL . '/AuthController/showLogin');
            exit();
        }
        
        $orderModel = $this->model("OrderModel");
        $orders = $orderModel->getOrdersByEmail($_SESSION['user']['email']);
        $this->view("homePage", [
            "page" => "OrderHistoryView",
            "orders" => $orders
        ]);
    }

    // Xem danh sách đơn hàng của người dùng theo email
    public function myOrders($email) {
        $orderModel = $this->model("OrderModel");
        $orders = $orderModel->getOrdersByEmail($email);
        $this->view("Font_end/OrderHistoryView", ["orders" => $orders]);
    }

    // Xem chi tiết 1 đơn hàng cụ thể
    public function detail($id) {
        if (!isset($_SESSION['user'])) {
            header('Location: ' . APP_URL . '/AuthController/showLogin');
            exit();
        }
        
        $orderModel = $this->model("OrderModel");
        $order = $orderModel->getOrderById($id);
        $details = $orderModel->getOrderDetails($id);
        
        // Lấy dữ liệu review
        $reviews = [];
        if ($order && isset($order['id'])) {
            $reviewModel = $this->model("ReviewModel");
            $reviews = $reviewModel->getReviewsByOrder($order['id']);
        }

        $this->view("homePage", [
            "page" => "OrderDetailView",
            "order" => $order,
            "details" => $details,
            "reviews" => $reviews
        ]);
    }

    // Người dùng xác nhận đã nhận hàng
    public function confirmReceived() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['id'])) {
            $orderId = $_POST['id'];
            $orderModel = $this->model("OrderModel");

            // Cập nhật trạng thái thành "đã thanh toán"
            $orderModel->updateStatus($orderId, 'đã thanh toán');

            echo "<script>
                alert('Cảm ơn bạn! Đơn hàng đã được xác nhận.');
                window.location.href='" . APP_URL . "/OrderController/detail/" . $orderId . "';
            </script>";
        }
    }
}
