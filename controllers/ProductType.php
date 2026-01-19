<?php
class ProductType extends Controller{
    public function index() {
        $this->show();
    }

    public function show(){
        $obj=$this->model("AdProductTypeModel");
        $data=$obj->all("tblloaisp");
        //$this->view("admin",["page"=>"listProduct","productList"=>$data]);
        $this->view("adminPage",["page"=>"ProductTypeView","productList"=>$data]);
    }
    public function delete($id){
        $obj=$this->model("AdProductTypeModel");
        $obj->delete("tblloaisp",$id);
        header("Location:" . APP_URL . "/ProductType/show");    
        exit();
    }
    public function create(){
        if (!isset($_SESSION['admin'])) {
            header("Location:" . APP_URL . "/AuthController/ShowAdminLogin");
            exit();
        }

        $txt_maloaisp = isset($_POST["txt_maloaisp"]) ? trim($_POST["txt_maloaisp"]) : "";
        $txt_tenloaisp = isset($_POST["txt_tenloaisp"]) ? trim($_POST["txt_tenloaisp"]) : "";
        $txt_motaloaisp = isset($_POST["txt_motaloaisp"]) ? trim($_POST["txt_motaloaisp"]) : "";

        if (empty($txt_maloaisp) || empty($txt_tenloaisp)) {
            $_SESSION['error_message'] = "Vui lòng điền đầy đủ thông tin bắt buộc!";
            header("Location:" . APP_URL . "/ProductType/show");
            exit();
        }

        $obj = $this->model("AdProductTypeModel");
        try {
            $obj->insert($txt_maloaisp, $txt_tenloaisp, $txt_motaloaisp);
            $_SESSION['success_message'] = "Thêm loại sản phẩm thành công!";
        } catch (Exception $e) {
            $_SESSION['error_message'] = "Có lỗi xảy ra: " . $e->getMessage();
        }
        
        header("Location:" . APP_URL . "/ProductType/show");    
        exit();
    }
    public function edit($maLoaiSP)
    {
        $obj=$this->model("AdProductTypeModel");
        $product = $obj->find("tblloaisp",$maLoaiSP);
        $productList = $obj->all("tblloaisp"); // Lấy lại toàn bộ danh sách
        $this->view("adminPage",["page"=>"ProductTypeView",
                            'productList' => $productList,
                            'editItem' => $product]);
    }
    public function update($maLoaiSP)
    {
        if (!isset($_SESSION['admin'])) {
            header("Location:" . APP_URL . "/AuthController/ShowAdminLogin");
            exit();
        }

        $tenLoaiSP = isset($_POST['txt_tenloaisp']) ? trim($_POST['txt_tenloaisp']) : "";
        $moTaLoaiSP = isset($_POST['txt_motaloaisp']) ? trim($_POST['txt_motaloaisp']) : "";

        if (empty($tenLoaiSP)) {
            $_SESSION['error_message'] = "Tên loại sản phẩm không được để trống!";
            header("Location:" . APP_URL . "/ProductType/edit/" . $maLoaiSP);
            exit();
        }

        $obj = $this->model("AdProductTypeModel");
        try {
            $obj->update($maLoaiSP, $tenLoaiSP, $moTaLoaiSP);
            $_SESSION['success_message'] = "Cập nhật loại sản phẩm thành công!";
        } catch (Exception $e) {
            $_SESSION['error_message'] = "Có lỗi xảy ra: " . $e->getMessage();
            header("Location:" . APP_URL . "/ProductType/edit/" . $maLoaiSP);
            exit();
        }

        header("Location:" . APP_URL . "/ProductType/show");    
        exit();
    }

}
