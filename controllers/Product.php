<?php
class Product extends Controller{
    public function index() {
        // Chuyển hướng đến phương thức show
        $this->show();
    }

    public function show(){
        $obj=$this->model("AdProducModel");
        
        // Get search parameters
        $search = isset($_GET['search']) ? trim($_GET['search']) : '';
        $code = isset($_GET['code']) ? trim($_GET['code']) : '';
        
        if ($search || $code) {
            // Filter data based on search criteria
            $allProducts = $obj->all("tblsanpham");
            $data = array_filter($allProducts, function($product) use ($search, $code) {
                $matchSearch = empty($search) || stripos($product['tensp'], $search) !== false;
                $matchCode = empty($code) || stripos($product['masp'], $code) !== false;
                return $matchSearch && $matchCode;
            });
            $data = array_values($data); // Reset array keys
        } else {
            $data = $obj->all("tblsanpham");
        }
        
        $this->view("adminPage",[
            "page"=>"ProductListView",
            "productList"=>$data
        ]);
    }
    public function delete($id){
        try {
            $obj = $this->model("AdProducModel");
            
            // ✅ SỬA LỖI FK: Xóa dữ liệu liên quan TRƯỚC
            // Xóa review
            $reviewModel = $this->model("ReviewModel");
            $reviewModel->deleteByProductId($id);
            
            // ✅ Xóa order details (dùng Model thay vì $this->db)
            $orderDetailModel = $this->model("OrderDetailModel");
            $orderDetailModel->deleteByProductId($id);
            
            // ✅ Xóa product_promotions (dùng Model thay vì $this->db)
            $promotionModel = $this->model("PromotionModel");
            $promotionModel->deleteProductPromotions($id);
            
            // Xóa feedback
            $feedbackModel = $this->model("FeedbackModel");
            $feedbackModel->deleteByProductId($id);
            
            // Cuối cùng: Xóa sản phẩm
            $obj->delete("tblsanpham", $id);
            
            $_SESSION['success'] = "Xóa sản phẩm thành công!";
        } catch (Exception $e) {
            $_SESSION['error'] = "Lỗi xóa sản phẩm: " . $e->getMessage();
            error_log("Delete product error: " . $e->getMessage());
        }
        
        header("Location:".APP_URL."/Product/");    
        exit();
    }
    public function create(){
        $obj = $this->model("AdProducModel");
        $obj2 = $this->model("AdProductTypeModel");
        $supplierModel = $this->model("SupplierModel");
        $producttype = $obj2->all("tblloaisp");
        $suppliers = $supplierModel->getForDropdown();
        
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $masp_goc = $_POST["txt_masp"];
            $masp = preg_replace('/\s+/', '', $masp_goc);
            $tensp = $_POST["txt_tensp"];
            $maloaisp = $_POST["txt_maloaisp"];
            $soluong = $_POST["txt_soluong"];
            $gianhap = $_POST["txt_gianhap"];
            $giaxuat = $_POST["txt_giaxuat"];
            $khuyenmai = $_POST["txt_khuyenmai"];
            $mota = $_POST["txt_mota"];
            $ngaytao = $_POST["create_date"];
            $supplier_id = isset($_POST["txt_supplier_id"]) && !empty($_POST["txt_supplier_id"]) ? $_POST["txt_supplier_id"] : NULL;
            $hinhanh = "";
            if (!empty($_FILES["uploadfile"]["name"])) {
                $hinhanh = $_FILES["uploadfile"]["name"];
                $file_tmp = $_FILES["uploadfile"]["tmp_name"];
                move_uploaded_file($file_tmp, "./public/images/" . $hinhanh);
            }

            $obj->insert($maloaisp,$masp, $tensp, $hinhanh, $soluong, $gianhap, $giaxuat, $khuyenmai, $mota, $ngaytao, $supplier_id);
            header("Location: " . APP_URL . "/Product/");
            exit();
        }
        $this->view("adminPage", ["page" => "ProductView", "producttype" => $producttype, "suppliers" => $suppliers]);
    }
   public function edit($masp){
        $obj = $this->model("AdProducModel");
        $obj2 = $this->model("AdProductTypeModel");
        $supplierModel = $this->model("SupplierModel");
        $producttype = $obj2->all("tblloaisp");
        $suppliers = $supplierModel->getForDropdown();
        $product = $obj->find("tblsanpham", $masp);
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $masp = $_POST["txt_masp"];
            $tensp = $_POST["txt_tensp"];
            $maloaisp = $_POST["txt_maloaisp"];
            $soluong = $_POST["txt_soluong"];
            $gianhap = $_POST["txt_gianhap"];
            $giaxuat = $_POST["txt_giaxuat"];
            $khuyenmai = $_POST["txt_khuyenmai"];
            $mota = $_POST["txt_mota"];
            $ngaytao = $_POST["create_date"];
            $supplier_id = isset($_POST["txt_supplier_id"]) && !empty($_POST["txt_supplier_id"]) ? $_POST["txt_supplier_id"] : NULL;
            $hinhanh = $product['hinhanh'];
            if (!empty($_FILES["uploadfile"]["name"])) {
                $hinhanh = $_FILES["uploadfile"]["name"];
                $file_tmp = $_FILES["uploadfile"]["tmp_name"];
                move_uploaded_file($file_tmp, "./public/images/" . $hinhanh);
            }
            $data = [
                'maloaisp' => $maloaisp,
                'tensp' => $tensp,
                'hinhanh' => $hinhanh,
                'soluong' => $soluong,
                'giaNhap' => $gianhap,
                'giaXuat' => $giaxuat,
                'khuyenmai' => $khuyenmai,
                'mota' => $mota,
                'createDate' => $ngaytao,
                'supplier_id' => $supplier_id
            ];
            $obj->update('tblsanpham', $masp, $data);
            header("Location: " . APP_URL . "/Product/");
            exit();
        }
        $this->view("adminPage", [
            "page" => "ProductView", //ProductView
            "producttype" => $producttype,
            "suppliers" => $suppliers,
            "editItem" => $product
        ]);
    }
}
