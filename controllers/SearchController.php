<?php
class SearchController extends Controller {
    private $searchModel;
    private $productTypeModel;
    private $productModel;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $this->searchModel = $this->model("SearchModel");
        $this->productTypeModel = $this->model("AdProductTypeModel");
        $this->productModel = $this->model("AdProducModel");
    }

    public function index() {
        // Lấy từ khóa từ GET
        $keyword = trim($_GET['keyword'] ?? '');
        $products = [];
        $productTypes = [];
        
        // Lấy tham số lọc và sắp xếp
        $minPrice = isset($_GET['minPrice']) ? (int)$_GET['minPrice'] : 0;
        $maxPrice = isset($_GET['maxPrice']) ? (int)$_GET['maxPrice'] : 999999999;
        $sortBy = isset($_GET['sort']) ? $_GET['sort'] : 'price_asc';
        
        // Validate sort parameter
        $validSortOptions = ['price_asc', 'price_desc', 'popularity', 'rating'];
        if (!in_array($sortBy, $validSortOptions)) {
            $sortBy = 'price_asc';
        }

        if (!empty($keyword)) {
            // Sử dụng model mới với filter và sort
            $products = $this->productModel->searchWithFilter($keyword, $minPrice, $maxPrice, $sortBy);
        }

        // Lấy loại sản phẩm (nếu cần hiển thị menu)
        try {
            $productTypes = $this->productTypeModel->all("tblloaisp");
        } catch (Exception $e) {
            error_log("[SearchController] productTypeModel error: " . $e->getMessage());
        }
        
        // Lấy khoảng giá để hiển thị trong filter
        $priceRange = $this->productModel->getPriceRange();

        // Lấy các khuyến mại hoạt động
        $promotionModel = $this->model("PromotionModel");
        $promotions = $promotionModel->getActive();
        $promotionProducts = [];
        foreach ($promotions as $promo) {
            $promoProducts = $promotionModel->getPromotionProducts($promo['id']);
            foreach ($promoProducts as $product) {
                $promotionProducts[] = [
                    'product_id' => $product['masp'],
                    'discount_percent' => $promo['discount_percent']
                ];
            }
        }

        // Gọi view đúng đường dẫn
        $this->view("homePage", [
            "page" => "HomeView",
            "productList" => $products,
            "productTypes" => $productTypes,
            "keyword" => $keyword,
            "isSearchResult" => true,
            "totalPages" => 1,
            "currentPage" => 1,
            "priceRange" => $priceRange,
            "filterMinPrice" => $minPrice,
            "filterMaxPrice" => $maxPrice,
            "sortBy" => $sortBy,
            "promotions" => $promotionProducts
        ]);
    }

    public function suggest() {
        $term = trim($_GET['term'] ?? '');
        if ($term === '') {
            echo json_encode([]);
            return;
        }

        $products = $this->searchModel->searchProducts($term);
        $suggestions = array_map(function ($p) {
            return [
                'id' => $p['masp'],
                'label' => $p['tensp'],
                'value' => $p['tensp']
            ];
        }, $products);

        header('Content-Type: application/json');
        echo json_encode($suggestions);
    }
}
