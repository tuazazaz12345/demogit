<?php
class StatisticsController extends Controller {
    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Kiểm tra đăng nhập admin
        if (!isset($_SESSION['admin'])) {
            header('Location: ' . APP_URL . '/AuthController/ShowAdminLogin');
            exit();
        }
    }

    public function inventory() {
        $productModel = $this->model("AdProducModel");
        $products = $productModel->getInventoryStatus();
        
        // Get search parameters
        $search = isset($_GET['search']) ? trim($_GET['search']) : '';
        $code = isset($_GET['code']) ? trim($_GET['code']) : '';
        $status = isset($_GET['status']) ? trim($_GET['status']) : '';
        
        // Filter data based on search criteria
        if ($search || $code || $status) {
            $products = array_filter($products, function($product) use ($search, $code, $status) {
                $matchSearch = empty($search) || stripos($product['tensp'], $search) !== false;
                $matchCode = empty($code) || stripos($product['masp'], $code) !== false;
                
                $productStatus = '';
                if ($product['soluong'] <= 5) {
                    $productStatus = 'critical';
                } elseif ($product['soluong'] <= 10) {
                    $productStatus = 'low';
                } else {
                    $productStatus = 'good';
                }
                $matchStatus = empty($status) || $productStatus === $status;
                
                return $matchSearch && $matchCode && $matchStatus;
            });
            $products = array_values($products); // Reset array keys
        }
        
        $this->view("adminPage", [
            "page" => "InventoryView",
            "products" => $products
        ]);
    }

    public function revenue() {
        $orderModel = $this->model("OrderModel");
        $productModel = $this->model("AdProducModel");
        
        $period = isset($_GET['period']) ? $_GET['period'] : 'month';
        $startDate = isset($_GET['start_date']) ? $_GET['start_date'] : date('Y-m-01');
        $endDate = isset($_GET['end_date']) ? $_GET['end_date'] : date('Y-m-t');

        $revenueData = $orderModel->getRevenueStats($period, $startDate, $endDate);
        
        // Lấy sản phẩm bán chạy nhất
        $topProducts = $productModel->getTopSellingProducts($startDate, $endDate, 10);
        
        // Lấy sản phẩm bán chậm nhất
        $slowProducts = $productModel->getSlowSellingProducts($startDate, $endDate, 10);
        
        // Tính tăng trưởng doanh thu
        $growthData = $orderModel->getRevenueGrowth($startDate, $endDate, $period);
        
        $this->view("adminPage", [
            "page" => "RevenueView",
            "revenueData" => $revenueData,
            "topProducts" => $topProducts,
            "slowProducts" => $slowProducts,
            "growthData" => $growthData,
            "period" => $period,
            "startDate" => $startDate,
            "endDate" => $endDate
        ]);
    }
}