<?php
class NewsController extends Controller {
    private $newsModel;

    public function __construct() {
        $this->newsModel = $this->model("NewsModel");
    }

    // Hiển thị tin tức ở trang chủ
    public function showNews() {
        $news = $this->newsModel->getAll();
        $this->view("homePage", [
            "page" => "NewsView",
            "news" => $news
        ]);
    }

    // Hiển thị chi tiết tin tức
    public function detail($id) {
        $news = $this->newsModel->findById($id);
        if ($news) {
            $this->view("homePage", [
                "page" => "NewsDetailView",
                "news" => $news
            ]);
        } else {
            header("Location: " . APP_URL . "/NewsController/showNews");
        }
    }

    // Thêm tin tức mới
    public function create() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $title = $_POST['title'];
            $content = $_POST['content'];
            
            // Xử lý upload hình ảnh
            $image = $_FILES['image'];
            $imagePath = $this->uploadImage($image);
            
            $data = [
                'title' => $title,
                'content' => $content,
                'image' => $imagePath
            ];
            
            $this->newsModel->store($data);
            header("Location: " . APP_URL . "/Admin/news");
        }
        $this->view("Back_end/NewsCreateView");
    }

    // Sửa tin tức
    public function edit($id) {
        $news = $this->newsModel->findById($id);
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $title = $_POST['title'];
            $content = $_POST['content'];
            
            $data = [
                'title' => $title,
                'content' => $content
            ];
            
            // Nếu có upload ảnh mới
            if (!empty($_FILES['image']['name'])) {
                $image = $_FILES['image'];
                $imagePath = $this->uploadImage($image);
                $data['image'] = $imagePath;
            }
            
            $this->newsModel->updateNews($id, $data);
            header("Location: " . APP_URL . "/Admin/news");
        }
        
        $this->view("Back_end/NewsEditView", [
            "news" => $news
        ]);
    }

    // Xóa tin tức
    public function delete($id) {
        $this->newsModel->deleteNews($id);
        header("Location: " . APP_URL . "/Admin/news");
    }

    // Hàm hỗ trợ upload hình ảnh
    private function uploadImage($image) {
        $target_dir = $_SERVER['DOCUMENT_ROOT'] . "/phpnangcao/MVC/public/images/news/";
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        
        $imageFileType = strtolower(pathinfo($image["name"], PATHINFO_EXTENSION));
        $randomName = uniqid() . '.' . $imageFileType;
        $target_file = $target_dir . $randomName;
        $db_image_path = "public/images/news/" . $randomName;

        if (move_uploaded_file($image["tmp_name"], $target_file)) {
            return $db_image_path;
        }
        return false;
    }
}