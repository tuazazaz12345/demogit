<?php
class Home extends Controller {

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    // ====================== HIỂN THỊ TRANG CHÍNH ======================
    public function show() {
        $productModel = $this->model("AdProducModel");
        $productTypeModel = $this->model("AdProductTypeModel");
        $promotionModel = $this->model("PromotionModel");
        
        // Lấy số trang từ URL, mặc định là trang 1
        $currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        if ($currentPage < 1) $currentPage = 1;
        
        // Lấy sản phẩm với phân trang
        $paginatedProducts = $productModel->getAllProductsPaginated($currentPage, 8);
        $newProducts = $productModel->getNewProducts();
        $productTypes = $productTypeModel->all("tblloaisp");
        $bestSellers = $productModel->getBestSellers();
        $priceRange = $productModel->getPriceRange();
        
        // Lấy các khuyến mại hoạt động kèm sản phẩm
        $promotions = $promotionModel->getActive();
        $promotionProducts = [];
        foreach ($promotions as $promo) {
            $products = $promotionModel->getPromotionProducts($promo['id']);
            foreach ($products as $product) {
                $promotionProducts[] = [
                    'product_id' => $product['masp'],
                    'discount_percent' => $promo['discount_percent']
                ];
            }
        }
        
        $this->view("homePage", [
            "page" => "HomeView",
            "productList" => $paginatedProducts['products'],
            "totalPages" => $paginatedProducts['totalPages'],
            "currentPage" => $paginatedProducts['currentPage'],
            "productTypes" => $productTypes,
            "bestSellingProducts" => $bestSellers,
            "newProducts" => $newProducts,
            "promotions" => $promotionProducts,
            "priceRange" => $priceRange
        ]);
    }
// ====================== TÌM KIẾM SẢN PHẨM ======================
public function search() {
    if (isset($_GET['keyword'])) {
        $keyword = trim($_GET['keyword']);
        $productModel = $this->model("AdProducModel");
        $productTypeModel = $this->model("AdProductTypeModel");
        $promotionModel = $this->model("PromotionModel");

        $products = $productModel->searchProducts($keyword);
        $productTypes = $productTypeModel->all("tblloaisp");
        
        // Lấy các khuyến mại hoạt động
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

        $this->view("homePage", [
            "page" => "HomeView",
            "productList" => $products,
            "productTypes" => $productTypes,
            "searchKeyword" => $keyword,
            "message" => empty($products) ? "Không có kết quả cho: $keyword" : null,
            "promotions" => $promotionProducts
        ]);
    } else {
        header("Location: " . APP_URL . "Home/show");
    }
}


    // ====================== HIỂN THỊ SẢN PHẨM THEO LOẠI ======================
    public function showByType($maLoaiSP) {
        error_log("----------------");
        error_log("START showByType");
        error_log("maLoaiSP received: " . $maLoaiSP);
        
        $productModel = $this->model("AdProducModel");
        $productTypeModel = $this->model("AdProductTypeModel");
        $promotionModel = $this->model("PromotionModel");
        
        // Lấy tham số lọc và sắp xếp
        $minPrice = isset($_GET['minPrice']) ? (int)$_GET['minPrice'] : 0;
        $maxPrice = isset($_GET['maxPrice']) ? (int)$_GET['maxPrice'] : 999999999;
        $sortBy = isset($_GET['sort']) ? $_GET['sort'] : 'price_asc';
        
        // Validate sort parameter
        $validSortOptions = ['price_asc', 'price_desc', 'popularity', 'rating'];
        if (!in_array($sortBy, $validSortOptions)) {
            $sortBy = 'price_asc';
        }
        
        $allProducts = $productModel->all("tblsanpham");
        error_log("Total products in database: " . count($allProducts));
        
        // Sử dụng filter method nếu có bộ lọc, nếu không dùng getByType
        if (isset($_GET['minPrice']) || isset($_GET['maxPrice']) || isset($_GET['sort'])) {
            $products = $productModel->filterByTypeAndPrice($maLoaiSP, $minPrice, $maxPrice, $sortBy);
        } else {
            $products = $productModel->getByType($maLoaiSP);
        }
        
        error_log("Products found for type " . $maLoaiSP . ": " . count($products));
        
        $productType = $productTypeModel->find("tblloaisp", $maLoaiSP);
        error_log("Product type info: " . print_r($productType, true));
        
        $productTypes = $productTypeModel->all("tblloaisp");
        error_log("Total product types: " . count($productTypes));
        
        // Lấy khoảng giá để hiển thị trong filter
        $priceRange = $productModel->getPriceRange();
        
        // Lấy các khuyến mại hoạt động
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
        
        if (empty($products)) {
            $this->view("homePage", [
                "page" => "HomeView",
                "productList" => [],
                "productTypes" => $productTypes,
                "currentType" => $productType,
                "message" => "Không có sản phẩm nào trong danh mục này" . (isset($_GET['minPrice']) ? " hoặc không khớp với bộ lọc" : ""),
                "promotions" => $promotionProducts,
                "priceRange" => $priceRange,
                "filterMinPrice" => $minPrice,
                "filterMaxPrice" => $maxPrice,
                "sortBy" => $sortBy
            ]);
            return;
        }
        
        $this->view("homePage", [
            "page" => "HomeView",
            "productList" => $products,
            "productTypes" => $productTypes,
            "currentType" => $productType,
            "promotions" => $promotionProducts,
            "priceRange" => $priceRange,
            "filterMinPrice" => $minPrice,
            "filterMaxPrice" => $maxPrice,
            "sortBy" => $sortBy
        ]);
    }

    // ====================== XEM CHI TIẾT SẢN PHẨM ======================
    public function detail($masp) {
        $obj = $this->model("AdProducModel");
        $data = $obj->find("tblsanpham", $masp);
        
        // Lấy sản phẩm liên quan (cùng danh mục) - giới hạn 4 sản phẩm
        $relatedProducts = [];
        if ($data && isset($data['maLoaiSP'])) {
            $relatedProducts = $obj->getRelatedProducts($masp, $data['maLoaiSP'], 4);
        }

        // Lấy đánh giá đã duyệt
        $reviewModel = $this->model("ReviewModel");
        $reviews = $reviewModel->select(
            "SELECT * FROM tblreview WHERE masp = ? AND trangthai = 'đã duyệt' ORDER BY ngaygui DESC",
            [$masp]
        );

        $averageResult = $reviewModel->select(
            "SELECT AVG(sosao) as average FROM tblreview WHERE masp = ? AND trangthai = 'đã duyệt'",
            [$masp]
        );
        $average = $averageResult[0]['average'] ?? 0;
        
        // Lấy error/success messages từ session
        $error_message = $_SESSION['error_message'] ?? null;
        $success_message = $_SESSION['success_message'] ?? null;
        
        // Xóa messages khỏi session sau khi lấy
        unset($_SESSION['error_message']);
        unset($_SESSION['success_message']);
        
        $this->view("homePage", [
            "page" => "DetailView", 
            "product" => $data,
            "relatedProducts" => $relatedProducts,
            "reviews" => $reviews ?? [],
            "average" => $average,
            "masp" => $masp,
            "error_message" => $error_message,
            "success_message" => $success_message
        ]);
    }

    // ====================== GIỎ HÀNG ======================
    public function addtocard($masp = null) {
        // ✅ Nếu từ trang khuyến mại (POST), lấy từ POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $masp = $_POST['masp'] ?? null;
            $quantity = (int)($_POST['quantity'] ?? 1);
            $quantity = max(1, $quantity); // Đảm bảo quantity >= 1
            $promotionalPrice = $_POST['promotional_price'] ?? null;
            $fromPromotion = ($_POST['from_promotion'] ?? 'false') === 'true'; // ✅ Kiểm tra flag
        } else {
            // ✅ Nếu từ trang chi tiết (GET), lấy từ URL params
            $quantity = 1;
            $fromPromotion = false;
            $promotionalPrice = null;
        }

        if (!$masp) {
            header('Location: ' . APP_URL . '/Home/show');
            exit();
        }

        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        $obj = $this->model("AdProducModel");
        $data = $obj->find("tblsanpham", $masp);

        if (isset($_SESSION['cart'][$masp])) {
            $_SESSION['cart'][$masp]['qty'] += $quantity;
        } else {
            // ✅ NẾU TỪ TRANG KHUYẾN MẠI: Lưu giá khuyến mãi thực tế
            if ($fromPromotion && $promotionalPrice) {
                $giaKhuyenMai = (float)$promotionalPrice;
                $khuyenMaiPercent = 0; // Set to 0 vì đã có giá rồi
                
                $_SESSION['cart'][$masp] = [
                    'qty' => $quantity,
                    'masp' => $data['masp'],
                    'tensp' => $data['tensp'],
                    'hinhanh' => $data['hinhanh'],
                    'giaxuat' => $data['giaXuat'],
                    'khuyenmai' => $khuyenMaiPercent,  // Set to 0
                    'promotional_price' => $giaKhuyenMai,  // ✅ Lưu giá khuyến mãi thực tế
                    'from_promotion' => true
                ];
            } else {
                // ✅ NẾU TỪ TRANG CHỦ (GET): Kiểm tra xem có khuyến mãi active không
                $promotionModel = $this->model("PromotionModel");
                $activePromotion = $promotionModel->hasPromotion($masp);
                
                if ($activePromotion) {
                    // Có khuyến mãi active - tính giá khuyến mãi
                    $khuyenMaiPercent = (float)$activePromotion['discount_percent'];
                    $giaKhuyenMai = $data['giaXuat'] - ($data['giaXuat'] * $khuyenMaiPercent / 100);
                    
                    $_SESSION['cart'][$masp] = [
                        'qty' => $quantity,
                        'masp' => $data['masp'],
                        'tensp' => $data['tensp'],
                        'hinhanh' => $data['hinhanh'],
                        'giaxuat' => $data['giaXuat'],
                        'khuyenmai' => 0,  // Set to 0 vì dùng promotional_price
                        'promotional_price' => $giaKhuyenMai,  // ✅ Lưu giá khuyến mãi từ hệ thống
                        'from_promotion' => true
                    ];
                } else {
                    // Không có khuyến mãi active - dùng khuyến mại % cơ bản
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
            }
        }

        $_SESSION['cart_success'] = "Sản phẩm đã được thêm vào giỏ hàng";
        
        // ✅ TỰ ĐỘNG LƯU GIỎ HÀNG VÀO DATABASE (nếu user đã login)
        if (isset($_SESSION['user'])) {
            try {
                $userId = (int)$_SESSION['user']['id'];
                error_log('Saving cart for user_id: ' . $userId . ' with ' . count($_SESSION['cart']) . ' items');
                $cartModel = $this->model('ShoppingCartModel');
                $result = $cartModel->saveCart($userId, $_SESSION['cart']);
                error_log('Cart auto-saved after add: User ' . $userId . ' Result: ' . ($result ? 'SUCCESS' : 'FAILED'));
            } catch (Exception $e) {
                error_log('Error auto-saving cart: ' . $e->getMessage());
            }
        } else {
            error_log('Not saving cart: User not logged in');
        }
        
        // ✅ REDIRECT: Nếu từ khuyến mại (from_promotion=true), về trang khuyến mại
        // Nếu từ chi tiết (from_promotion=false), hiển thị giỏ hàng
        if ($fromPromotion && $_SERVER['REQUEST_METHOD'] === 'POST') {
            header('Location: ' . APP_URL . '/PromotionalProductsController/index');
        } else {
            // ✅ Hiển thị giỏ hàng
            $this->view("homePage", [
                "page" => "OrderView", 
                "listProductOrder" => $_SESSION['cart'],
                "success" => $_SESSION['cart_success']
            ]);
        }
    }

    public function delete($masp) {
        if (isset($_SESSION['cart'][$masp])) {
            unset($_SESSION['cart'][$masp]);
        }

        // ✅ TỰ ĐỘNG LƯU GIỎ HÀNG VÀO DATABASE (nếu user đã login)
        if (isset($_SESSION['user'])) {
            try {
                $userId = (int)$_SESSION['user']['id'];
                $cartModel = $this->model('ShoppingCartModel');
                $cartModel->saveCart($userId, $_SESSION['cart']);
                error_log('Cart auto-saved after delete: User ' . $userId);
            } catch (Exception $e) {
                error_log('Error auto-saving cart: ' . $e->getMessage());
            }
        }

        $this->view("homePage", ["page" => "OrderView", "listProductOrder" => $_SESSION['cart']]);
    }

    public function update() {
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['qty'])) {
            foreach ($_POST['qty'] as $k => $v) {
                $_SESSION['cart'][$k]['qty'] = $v;
            }
        }

        // ✅ TỰ ĐỘNG LƯU GIỎ HÀNG VÀO DATABASE (nếu user đã login)
        if (isset($_SESSION['user'])) {
            try {
                $userId = (int)$_SESSION['user']['id'];
                $cartModel = $this->model('ShoppingCartModel');
                $cartModel->saveCart($userId, $_SESSION['cart']);
                error_log('Cart auto-saved after update: User ' . $userId);
            } catch (Exception $e) {
                error_log('Error auto-saving cart: ' . $e->getMessage());
            }
        }

        $this->view("homePage", [
            "page" => "OrderView",
            "listProductOrder" => $_SESSION['cart']
        ]);
    }

    public function order() {
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
        $this->view("homePage", ["page" => "OrderView", "listProductOrder" => $_SESSION['cart']]);
    }

    // ====================== ĐẶT HÀNG NHANH ======================
    public function checkout() {
        if (!isset($_SESSION['user'])) {
            header('Location: ' . APP_URL . '/AuthController/ShowLogin');
            exit();
        }

        $cart = $_SESSION['cart'] ?? [];
        if (empty($cart)) {
            $this->view("homePage", [
                "page" => "OrderView",
                "listProductOrder" => [],
                "success" => "Giỏ hàng trống!"
            ]);
            return;
        }

        $orderModel = $this->model("OrderModel");
        $orderDetailModel = $this->model("OrderDetailModel");
        $user = $_SESSION['user'];

        $orderCode = 'HD' . time();
        $totalAmount = 0;

        foreach ($cart as $item) {
            // ✅ NẾU TỪ TRANG KHUYẾN MẠI: Dùng giá khuyến mãi đã lưu
            if (isset($item['from_promotion']) && $item['from_promotion'] && isset($item['promotional_price'])) {
                $gia = $item['promotional_price'];
            } else {
                // ✅ BÌNH THƯỜNG: Tính từ khuyến mại %
                $gia = $item['giaxuat'] - ($item['giaxuat'] * $item['khuyenmai'] / 100);
            }
            $thanhtien = $gia * $item['qty'];
            $totalAmount += $thanhtien;
        }

        $orderId = $orderModel->createOrderWithShipping($user['email'], $orderCode, $totalAmount, '', '', '');

        foreach ($cart as $item) {
            // ✅ NẾU TỪ TRANG KHUYẾN MẠI: Dùng giá khuyến mãi đã lưu
            if (isset($item['from_promotion']) && $item['from_promotion'] && isset($item['promotional_price'])) {
                $gia = $item['promotional_price'];
            } else {
                // ✅ BÌNH THƯỜNG: Tính từ khuyến mại %
                $gia = $item['giaxuat'] - ($item['giaxuat'] * $item['khuyenmai'] / 100);
            }
            $thanhtien = $gia * $item['qty'];
            $orderDetailModel->addOrderDetail(
                $orderId,
                $item['masp'],
                $item['qty'],
                $item['giaxuat'],
                $gia,
                $thanhtien,
                $item['hinhanh'],
                '',
                $item['tensp']
            );
        }

        // Lưu thông tin đơn hàng vào session để sử dụng trong trang thanh toán
        $_SESSION['pending_order'] = [
            'order_id' => $orderId,
            'order_code' => $orderCode,
            'amount' => $totalAmount
        ];
        
        // Chuyển đến trang chọn phương thức thanh toán
        header('Location: ' . APP_URL . '/vnpay_php/index.php');
        exit();
    }

    // ====================== ĐẶT HÀNG ĐẦY ĐỦ THÔNG TIN ======================
    public function checkoutSave() {
        if (!isset($_SESSION['user'])) {
            header('Location: ' . APP_URL . '/AuthController/ShowLogin');
            exit();
        }

        $cart = $_SESSION['cart'] ?? [];
        if (empty($cart)) {
            $this->view("homePage", [
                "page" => "OrderView",
                "listProductOrder" => [],
                "success" => "Giỏ hàng trống!"
            ]);
            return;
        }

        $receiver = trim($_POST['receiver'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $address = trim($_POST['address'] ?? '');

        if ($receiver === '' || $phone === '' || $address === '') {
            echo '<div class="alert alert-danger">Vui lòng nhập đầy đủ thông tin giao hàng!</div>';
            $this->view("homePage", ["page" => "CheckoutInfoView"]);
            return;
        }

        $orderModel = $this->model("OrderModel");
        $orderDetailModel = $this->model("OrderDetailModel");
        $user = $_SESSION['user'];

        $orderCode = 'HD' . time();
        $totalAmount = 0;

        foreach ($cart as $item) {
            // ✅ NẾU TỪ TRANG KHUYẾN MẠI: Dùng giá khuyến mãi đã lưu
            if (isset($item['from_promotion']) && $item['from_promotion'] && isset($item['promotional_price'])) {
                $gia = $item['promotional_price'];
            } else {
                // ✅ BÌNH THƯỜNG: Tính từ khuyến mại %
                $gia = $item['giaxuat'] - ($item['giaxuat'] * $item['khuyenmai'] / 100);
            }
            $thanhtien = $gia * $item['qty'];
            $totalAmount += $thanhtien;
        }

        $orderId = $orderModel->createOrderWithShipping($user['email'], $orderCode, $totalAmount, $receiver, $phone, $address);

        foreach ($cart as $item) {
            // ✅ NẾU TỪ TRANG KHUYẾN MẠI: Dùng giá khuyến mãi đã lưu
            if (isset($item['from_promotion']) && $item['from_promotion'] && isset($item['promotional_price'])) {
                $gia = $item['promotional_price'];
            } else {
                // ✅ BÌNH THƯỜNG: Tính từ khuyến mại %
                $gia = $item['giaxuat'] - ($item['giaxuat'] * $item['khuyenmai'] / 100);
            }
            $thanhtien = $gia * $item['qty'];
            $orderDetailModel->addOrderDetail(
                $orderId,
                $item['masp'],
                $item['qty'],
                $item['giaxuat'],
                $gia,
                $thanhtien,
                $item['hinhanh'],
                '',
                $item['tensp']
            );
        }

        // Lưu thông tin đơn hàng vào session để sử dụng trong trang thanh toán
        $_SESSION['pending_order'] = [
            'order_id' => $orderId,
            'order_code' => $orderCode,
            'amount' => $totalAmount
        ];
        
        $_SESSION['cart'] = [];
        
        // Chuyển đến trang chọn phương thức thanh toán
        header('Location: ' . APP_URL . '/vnpay_php/index.php');
        exit();
    }

    // ====================== HIỂN THỊ FORM NHẬP THÔNG TIN GIAO HÀNG ======================
    public function checkoutInfo() {
        if (!isset($_SESSION['user'])) {
            header('Location: ' . APP_URL . '/AuthController/showLogin');
            exit();
        }
        $this->view("homePage", ["page" => "CheckoutInfoView"]);
    }

    // ====================== XEM LỊCH SỬ ĐƠN HÀNG ======================
    public function orderHistory() {
        if (!isset($_SESSION['user'])) {
            header('Location: ' . APP_URL . '/AuthController/showLogin');
            exit();
        }

        $orderModel = $this->model('OrderModel');
        $orders = $orderModel->getOrdersByEmail($_SESSION['user']['email']);

        // Kiểm tra và lấy thông báo đặt hàng thành công nếu có
        $success = null;
        if (isset($_SESSION['order_success'])) {
            $success = $_SESSION['order_success'];
            unset($_SESSION['order_success']);
        }

        $this->view('homePage', [
            'page' => 'OrderHistoryView',
            'orders' => $orders,
            'success' => $success
        ]);
    }

    // ====================== MẶC ĐỊNH GỌI TRANG CHỦ ======================
    public function index() {
        $this->show();
    }

    // ====================== HIỂN THỊ TẤT CẢ SẢN PHẨM ======================
    public function showAllProducts() {
        $productModel = $this->model("AdProducModel");
        $productTypeModel = $this->model("AdProductTypeModel");
        $promotionModel = $this->model("PromotionModel");
        
        // Lấy số trang từ URL, mặc định là trang 1
        $currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        if ($currentPage < 1) $currentPage = 1;
        
        // Lấy tham số lọc và sắp xếp
        $minPrice = isset($_GET['minPrice']) ? (int)$_GET['minPrice'] : 0;
        $maxPrice = isset($_GET['maxPrice']) ? (int)$_GET['maxPrice'] : 999999999;
        $sortBy = isset($_GET['sort']) ? $_GET['sort'] : 'price_asc';
        
        // Validate sort parameter
        $validSortOptions = ['price_asc', 'price_desc', 'popularity', 'rating'];
        if (!in_array($sortBy, $validSortOptions)) {
            $sortBy = 'price_asc';
        }
        
        // Lấy khoảng giá để hiển thị trong filter
        $priceRange = $productModel->getPriceRange();
        
        // Lấy tất cả sản phẩm với phân trang và lọc (12 sản phẩm mỗi trang)
        $paginatedProducts = $productModel->filterAndSort($minPrice, $maxPrice, $sortBy, $currentPage, 12);
        $productTypes = $productTypeModel->all("tblloaisp");
        
        // Lấy các khuyến mại hoạt động
        $promotions = $promotionModel->getActive();
        $promotionProducts = [];
        foreach ($promotions as $promo) {
            $products = $promotionModel->getPromotionProducts($promo['id']);
            foreach ($products as $product) {
                $promotionProducts[] = [
                    'product_id' => $product['masp'],
                    'discount_percent' => $promo['discount_percent']
                ];
            }
        }
        
        $this->view("homePage", [
            "page" => "HomeView",
            "productList" => $paginatedProducts['products'],
            "totalPages" => $paginatedProducts['totalPages'],
            "currentPage" => $paginatedProducts['currentPage'],
            "productTypes" => $productTypes,
            "showAllProducts" => true,
            "priceRange" => $priceRange,
            "filterMinPrice" => $minPrice,
            "filterMaxPrice" => $maxPrice,
            "sortBy" => $sortBy,
            "promotions" => $promotionProducts
        ]);
    }

    // ====================== HIỂN THỊ SẢN PHẨM THEO LOẠI VỚI LỌC ======================
    public function showByTypeWithFilter($maLoaiSP) {
        error_log("------------ showByTypeWithFilter START ------------");
        error_log("maLoaiSP received: " . $maLoaiSP);
        
        $productModel = $this->model("AdProducModel");
        $productTypeModel = $this->model("AdProductTypeModel");
        $promotionModel = $this->model("PromotionModel");
        
        // Lấy tham số lọc và sắp xếp
        $minPrice = isset($_GET['minPrice']) ? (int)$_GET['minPrice'] : 0;
        $maxPrice = isset($_GET['maxPrice']) ? (int)$_GET['maxPrice'] : 999999999;
        $sortBy = isset($_GET['sort']) ? $_GET['sort'] : 'price_asc';
        
        // Validate sort parameter
        $validSortOptions = ['price_asc', 'price_desc', 'popularity', 'rating'];
        if (!in_array($sortBy, $validSortOptions)) {
            $sortBy = 'price_asc';
        }
        
        // Lấy thông tin loại sản phẩm
        $productType = $productTypeModel->find("tblloaisp", $maLoaiSP);
        error_log("Product type info: " . print_r($productType, true));
        
        // Lấy khoảng giá để hiển thị trong filter
        $priceRange = $productModel->getPriceRange();
        
        // Lấy sản phẩm theo loại với lọc giá
        $products = $productModel->filterByTypeAndPrice($maLoaiSP, $minPrice, $maxPrice, $sortBy);
        error_log("Products found: " . count($products));
        
        // Lấy tất cả loại sản phẩm
        $productTypes = $productTypeModel->all("tblloaisp");
        
        // Lấy các khuyến mại hoạt động
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
        
        if (empty($products)) {
            $this->view("homePage", [
                "page" => "HomeView",
                "productList" => [],
                "productTypes" => $productTypes,
                "currentType" => $productType,
                "message" => "Không có sản phẩm nào trong danh mục này hoặc không khớp với bộ lọc",
                "priceRange" => $priceRange,
                "filterMinPrice" => $minPrice,
                "filterMaxPrice" => $maxPrice,
                "sortBy" => $sortBy,
                "promotions" => $promotionProducts
            ]);
            return;
        }
        
        $this->view("homePage", [
            "page" => "HomeView",
            "productList" => $products,
            "productTypes" => $productTypes,
            "currentType" => $productType,
            "priceRange" => $priceRange,
            "filterMinPrice" => $minPrice,
            "filterMaxPrice" => $maxPrice,
            "sortBy" => $sortBy,
            "promotions" => $promotionProducts
        ]);
        
        error_log("------------ showByTypeWithFilter END ------------");
    }

    // ====================== TÌM KIẾM VỚI LỌC VÀ SẮP XẾP ======================
    public function searchWithFilter() {
        if (!isset($_GET['keyword'])) {
            header("Location: " . APP_URL . "Home/show");
            exit();
        }
        
        $keyword = trim($_GET['keyword']);
        $productModel = $this->model("AdProducModel");
        $productTypeModel = $this->model("AdProductTypeModel");
        $promotionModel = $this->model("PromotionModel");
        
        // Lấy tham số lọc và sắp xếp
        $minPrice = isset($_GET['minPrice']) ? (int)$_GET['minPrice'] : 0;
        $maxPrice = isset($_GET['maxPrice']) ? (int)$_GET['maxPrice'] : 999999999;
        $sortBy = isset($_GET['sort']) ? $_GET['sort'] : 'price_asc';
        
        // Validate sort parameter
        $validSortOptions = ['price_asc', 'price_desc', 'popularity', 'rating'];
        if (!in_array($sortBy, $validSortOptions)) {
            $sortBy = 'price_asc';
        }
        
        // Lấy khoảng giá để hiển thị trong filter
        $priceRange = $productModel->getPriceRange();
        
        // Tìm kiếm và lọc sản phẩm
        $products = $productModel->searchWithFilter($keyword, $minPrice, $maxPrice, $sortBy);
        $productTypes = $productTypeModel->all("tblloaisp");
        
        // Lấy các khuyến mại hoạt động
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
        
        $this->view("homePage", [
            "page" => "HomeView",
            "productList" => $products,
            "productTypes" => $productTypes,
            "keyword" => $keyword,
            "message" => empty($products) ? "Không có kết quả cho: $keyword" : null,
            "priceRange" => $priceRange,
            "filterMinPrice" => $minPrice,
            "filterMaxPrice" => $maxPrice,
            "sortBy" => $sortBy,
            "promotions" => $promotionProducts,
            "isSearchResult" => true
        ]);
    }

    // ====================== THÔNG TIN CÁ NHÂN ======================
    public function profile() {
        if (!isset($_SESSION['user'])) {
            header('Location: ' . APP_URL . '/AuthController/showLogin');
            exit();
        }

        $userModel = $this->model("UserModel");
        $user = $userModel->findByEmail($_SESSION['user']['email'])->fetch(PDO::FETCH_ASSOC);

        $this->view("homePage", [
            "page" => "ProfileView",
            "user" => $user
        ]);
    }

    public function updateProfile() {
        if (!isset($_SESSION['user'])) {
            header('Location: ' . APP_URL . '/AuthController/showLogin');
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . APP_URL . '/Home/profile');
            exit();
        }

        $userModel = $this->model("UserModel");
        $email = $_SESSION['user']['email'];
        $fullname = trim($_POST['fullname']);
        $phone = trim($_POST['phone']);
        $address = trim($_POST['address']);

        // Validate dữ liệu
        if (empty($fullname)) {
            $this->view("homePage", [
                "page" => "ProfileView",
                "error" => "Họ và tên không được để trống"
            ]);
            return;
        }

        if (!empty($phone) && !preg_match('/^[0-9]{10}$/', $phone)) {
            $this->view("homePage", [
                "page" => "ProfileView",
                "error" => "Số điện thoại không hợp lệ"
            ]);
            return;
        }

        // Cập nhật thông tin cơ bản
        $result = $userModel->updateProfile($email, $fullname, $phone, $address);

        // Cập nhật session
        $_SESSION['user']['fullname'] = $fullname;

        // Lấy thông tin user mới nhất
        $user = $userModel->findByEmail($email)->fetch(PDO::FETCH_ASSOC);

        $this->view("homePage", [
            "page" => "ProfileView",
            "user" => $user,
            "success" => "Cập nhật thông tin thành công!"
        ]);
    }

    // ====================== TRANG ĐỔI MẬT KHẨU ======================
    public function showChangePassword() {
        if (!isset($_SESSION['user'])) {
            header('Location: ' . APP_URL . '/AuthController/showLogin');
            exit();
        }

        $this->view("homePage", [
            "page" => "ChangePasswordView"
        ]);
    }

    // ====================== XỬ LÝ ĐỔI MẬT KHẨU ======================
    public function changePassword() {
        if (!isset($_SESSION['user'])) {
            header('Location: ' . APP_URL . '/AuthController/showLogin');
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . APP_URL . '/Home/showChangePassword');
            exit();
        }

        $userModel = $this->model("UserModel");
        $email = $_SESSION['user']['email'];
        
        // Lấy dữ liệu từ form
        $current_password = trim($_POST['current_password'] ?? '');
        $new_password = trim($_POST['new_password'] ?? '');
        $confirm_password = trim($_POST['confirm_password'] ?? '');

        // Validate input
        if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
            $this->view("homePage", [
                "page" => "ChangePasswordView",
                "error" => "Vui lòng điền đầy đủ thông tin"
            ]);
            return;
        }

        // Kiểm tra mật khẩu mới có đủ độ dài
        if (strlen($new_password) < 6) {
            $this->view("homePage", [
                "page" => "ChangePasswordView",
                "error" => "Mật khẩu mới phải có ít nhất 6 ký tự"
            ]);
            return;
        }

        // Kiểm tra mật khẩu mới và xác nhận có khớp không
        if ($new_password !== $confirm_password) {
            $this->view("homePage", [
                "page" => "ChangePasswordView",
                "error" => "Mật khẩu mới và xác nhận không khớp"
            ]);
            return;
        }

        // Lấy thông tin user từ database
        $stmt = $userModel->findByEmail($email);
        $user = $stmt ? $stmt->fetch(PDO::FETCH_ASSOC) : null;

        if (!$user) {
            $this->view("homePage", [
                "page" => "ChangePasswordView",
                "error" => "Không tìm thấy thông tin tài khoản"
            ]);
            return;
        }

        // Kiểm tra mật khẩu hiện tại có đúng không
        if (!password_verify($current_password, $user['password'])) {
            $this->view("homePage", [
                "page" => "ChangePasswordView",
                "error" => "Mật khẩu hiện tại không đúng"
            ]);
            return;
        }

        // Kiểm tra mật khẩu mới không trùng với mật khẩu cũ
        if (password_verify($new_password, $user['password'])) {
            $this->view("homePage", [
                "page" => "ChangePasswordView",
                "error" => "Mật khẩu mới không được trùng với mật khẩu cũ"
            ]);
            return;
        }

        // Cập nhật mật khẩu mới
        $hashedPassword = password_hash($new_password, PASSWORD_DEFAULT);
        $result = $userModel->updatePassword($email, $hashedPassword);

        if ($result) {
            $this->view("homePage", [
                "page" => "ChangePasswordView",
                "success" => "Đổi mật khẩu thành công! Vui lòng sử dụng mật khẩu mới cho lần đăng nhập sau."
            ]);
        } else {
            $this->view("homePage", [
                "page" => "ChangePasswordView",
                "error" => "Có lỗi xảy ra, vui lòng thử lại sau"
            ]);
        }
    }
}
