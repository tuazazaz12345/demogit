<?php
class PromotionalProductsController extends Controller {
    
    public function index() {
        $promotionModel = $this->model("PromotionModel");
        
        // Lấy tất cả khuyến mại hoạt động
        $promotions = $promotionModel->getActive();
        
        $promotionalProducts = [];
        $seenProductIds = []; // ✅ Để loại bỏ trùng lặp
        
        // Lấy sản phẩm của từng khuyến mại
        if (!empty($promotions)) {
            foreach ($promotions as $promotion) {
                // ✅ getPromotionProducts trả về array các sản phẩm đầy đủ
                $products = $promotionModel->getPromotionProducts($promotion['id']);
                
                if (!empty($products) && is_array($products)) {
                    foreach ($products as $product) {
                        // ✅ CHỈ thêm nếu chưa thấy sản phẩm này trước đó
                        if (!isset($seenProductIds[$product['masp']])) {
                            $seenProductIds[$product['masp']] = true;
                            
                            // $product là array với toàn bộ thông tin sản phẩm
                            $promotionalProducts[] = [
                                'product' => $product,
                                'promotion' => $promotion,
                                'originalPrice' => $product['giaXuat'],
                                'discountPercent' => $promotion['discount_percent'],
                                'promotionalPrice' => $product['giaXuat'] * (1 - $promotion['discount_percent'] / 100)
                            ];
                        }
                    }
                }
            }
        }
        
        $this->view("homePage", [
            "page" => "PromotionalProductsView",
            "promotionalProducts" => $promotionalProducts
        ]);
    }
}
?>
