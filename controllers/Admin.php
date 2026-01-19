<?php
class Admin extends Controller {
    private function checkAdminSession() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['admin'])) {
            if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
                // Nếu là AJAX request
                http_response_code(401);
                echo json_encode(['error' => 'Unauthorized']);
                exit;
            } else {
                // Nếu là regular request
                $_SESSION['redirect_url'] = $_SERVER['REQUEST_URI'];
                header("Location: " . APP_URL . "/AuthController/ShowAdminLogin");
                exit;
            }
        }

        // Tạo hoặc cập nhật token cho form submission
        if (!isset($_SESSION['admin_token'])) {
            $_SESSION['admin_token'] = bin2hex(random_bytes(32));
        }
    }

    public function __construct() {
        $this->checkAdminSession();
    }

    // ====================== QUẢN LÝ LOẠI SẢN PHẨM ======================
    public function show() {
        $obj = $this->model("AdProductTypeModel");
        $data = $obj->all("tblloaisp");
        $this->view("adminPage", [
            "page" => "ProductTypeView",
            "productList" => $data
        ]);
    }

    // ====================== QUẢN LÝ TIN TỨC ======================
    public function news() {
        $newsModel = $this->model("NewsModel");
        $news = $newsModel->getAll();
        $this->view("adminPage", [
            "page" => "NewsView",
            "news" => $news
        ]);
    }

    // Hiển thị form thêm tin tức
    public function createNews() {
        $this->view("adminPage", [
            "page" => "NewsCreateView"
        ]);
    }

    // Xử lý thêm tin tức
    public function storeNews() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if(!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
                $_SESSION['error'] = "Vui lòng chọn ảnh hợp lệ!";
                header("Location: " . APP_URL . "/Admin/createNews");
                return;
            }

            $newsModel = $this->model("NewsModel");
            $title = $_POST['title'];
            $content = $_POST['content'];
            
            // Xử lý upload hình ảnh
            $image = $_FILES['image'];
            $target_dir = $_SERVER['DOCUMENT_ROOT'] . "/phpnangcao/MVC/public/images/news/";
            
            // Debug log
            error_log("Upload path: " . $target_dir);
            
            // Tạo thư mục nếu chưa tồn tại
            if (!file_exists($target_dir)) {
                mkdir($target_dir, 0777, true);
            }
            
            // Tạo tên file ngẫu nhiên để tránh trùng lặp
            $imageFileType = strtolower(pathinfo($image["name"], PATHINFO_EXTENSION));
            $randomName = uniqid() . '.' . $imageFileType;

            // Kiểm tra loại file
            $allowedTypes = array('jpg', 'jpeg', 'png', 'gif');
            if (!in_array($imageFileType, $allowedTypes)) {
                $_SESSION['error'] = "Chỉ chấp nhận file ảnh JPG, JPEG, PNG & GIF!";
                header("Location: " . APP_URL . "/Admin/createNews");
                return;
            }

            // Set target file path with random name
            $target_file = $target_dir . $randomName;
            
            // Upload file
            if (move_uploaded_file($image["tmp_name"], $target_file)) {
                $data = [
                    'title' => $title,
                    'content' => $content,
                    'image' => 'public/images/news/' . $randomName
                ];
                
                if($newsModel->store($data)) {
                    $_SESSION['success'] = "Thêm tin tức thành công!";
                    header("Location: " . APP_URL . "/Admin/news");
                } else {
                    $_SESSION['error'] = "Có lỗi xảy ra khi thêm tin tức!";
                    header("Location: " . APP_URL . "/Admin/createNews");
                }
            } else {
                $_SESSION['error'] = "Có lỗi xảy ra khi upload ảnh!";
                header("Location: " . APP_URL . "/Admin/createNews");
            }
        }
    }

    // Hiển thị form sửa tin tức
    public function editNews($id) {
        $newsModel = $this->model("NewsModel");
        $news = $newsModel->findById($id);
        
        $this->view("adminPage", [
            "page" => "NewsEditView",
            "news" => $news
        ]);
    }

    // Xử lý cập nhật tin tức
    public function updateNews($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $newsModel = $this->model("NewsModel");
            $title = $_POST['title'];
            $content = $_POST['content'];
            
            $data = [
                'title' => $title,
                'content' => $content
            ];
            
            // Nếu có upload ảnh mới
            if (!empty($_FILES['image']['name'])) {
                $image = $_FILES['image'];
                $target_dir = "public/images/news/";
                $target_file = $target_dir . basename($image["name"]);
                move_uploaded_file($image["tmp_name"], $target_file);
                $data['image'] = $target_file;
            }
            
            $newsModel->updateNews($id, $data);
            header("Location: " . APP_URL . "/Admin/news");
        }
    }

    // Xóa tin tức
    public function deleteNews($id) {
        $newsModel = $this->model("NewsModel");
        $newsModel->deleteNews($id);
        header("Location: " . APP_URL . "/Admin/news");
    }

 public function listOrders() {
    $orderModel = $this->model("OrderModel");

    // Lấy giá trị tìm kiếm và trạng thái lọc
    $keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';
    $status = isset($_GET['status']) ? $_GET['status'] : 'all';

    // Tìm kiếm và lọc đơn hàng
    if (!empty($keyword) || $status !== 'all') {
        $orders = $orderModel->searchOrders($keyword, $status);
    } else {
        $orders = $orderModel->getAllOrders();
    }

    // Gửi dữ liệu sang view
    $this->view("adminPage", [
        "page" => "OrderListView",
        "orders" => $orders,
        "keyword" => $keyword,
        "status" => $status
    ]);
}


    // Chi tiết 1 đơn hàng
    public function orderDetail($id) {
        $orderModel = $this->model("OrderModel");
        $order = $orderModel->getOrderById($id);
        $details = $orderModel->getOrderDetails($id);

        $this->view("adminPage", [
            "page" => "OrderDetailView",
            "order" => $order,
            "details" => $details
        ]);
    }

    /**
     * In hóa đơn
     */
    public function printInvoice($id) {
        $orderModel = $this->model("OrderModel");
        $order = $orderModel->getOrderById($id);
        $details = $orderModel->getOrderDetails($id);

        if (empty($order)) {
            $_SESSION['error_message'] = 'Đơn hàng không tồn tại';
            header("Location: " . APP_URL . "/Admin/listOrders");
            exit;
        }

        // Load invoice template directly (không dùng wrapper)
        extract(['order' => $order, 'details' => $details]);
        require_once "./views/Back_end/InvoiceTemplate.php";
    }

    // Cập nhật trạng thái đơn hàng
    public function updateStatus() {
        $this->checkAdminSession();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Verify admin token
            if (!isset($_POST['admin_token']) || $_POST['admin_token'] !== $_SESSION['admin_token']) {
                $_SESSION['error_message'] = "Phiên làm việc không hợp lệ. Vui lòng thử lại!";
                header("Location: " . APP_URL . "/Admin/listOrders");
                exit;
            }

            $id = $_POST['id'] ?? null;
            $trangthai = $_POST['trangthai'] ?? null;

            if (!$id || !$trangthai) {
                $_SESSION['error_message'] = "Dữ liệu không hợp lệ!";
                header("Location: " . APP_URL . "/Admin/listOrders");
                exit;
            }

            try {
                $orderModel = $this->model("OrderModel");
                $success = $orderModel->updateStatus($id, $trangthai);

                if ($success) {
                    $_SESSION['success_message'] = "Cập nhật trạng thái đơn hàng thành công!";
                    
                    // Redirect back to order detail page
                    header("Location: " . APP_URL . "/Admin/orderDetail/" . $id);
                    exit;
                } else {
                    throw new Exception("Không thể cập nhật trạng thái đơn hàng.");
                }
            } catch (Exception $e) {
                error_log("Error updating order status: " . $e->getMessage());
                $_SESSION['error_message'] = "Có lỗi xảy ra khi cập nhật trạng thái đơn hàng!";
                header("Location: " . APP_URL . "/Admin/listOrders");
                exit;
            }
        }
    }

    // ====================== QUẢN LÝ KHÁCH HÀNG ======================
    public function customers() {
        $customerModel = $this->model("CustomerModel");
        
        // Lấy keyword tìm kiếm nếu có
        $keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';
        
        // Nếu có keyword, tìm kiếm; nếu không, lấy tất cả
        if (!empty($keyword)) {
            $customers = $customerModel->searchCustomers($keyword);
        } else {
            $customers = $customerModel->getAllCustomers();
        }
        
        $this->view("adminPage", [
            "page" => "CustomerView",
            "customers" => $customers,
            "keyword" => $keyword
        ]);
    }

    public function deleteCustomer($id) {
        $this->checkAdminSession();
        
        $customerModel = $this->model("CustomerModel");
        $result = $customerModel->deleteCustomer($id);
        
        if ($result['success']) {
            $_SESSION['success'] = $result['message'];
        } else {
            $_SESSION['error'] = $result['message'];
        }
        
        header("Location: " . APP_URL . "/Admin/customers");
        exit;
    }

    public function customerDetails($id) {
        $customerModel = $this->model("CustomerModel");
        $customer = $customerModel->getCustomerDetails($id);
        
        if (!$customer) {
            $_SESSION['error'] = "Không tìm thấy thông tin khách hàng!";
            header("Location: " . APP_URL . "/Admin/customers");
            exit;
        }
        
        // Lấy thống kê
        $stats = $customerModel->getCustomerStats($id);
        
        $this->view("adminPage", [
            "page" => "CustomerDetailView",
            "customer" => $customer,
            "stats" => $stats
        ]);
    }
    
    public function toggleLockCustomer($id) {
        $this->checkAdminSession();
        
        $customerModel = $this->model("CustomerModel");
        $customer = $customerModel->getCustomerDetails($id);
        
        if (!$customer) {
            $_SESSION['error'] = "Không tìm thấy khách hàng!";
            header("Location: " . APP_URL . "/Admin/customers");
            exit;
        }
        
        // Flip the lock status
        $newLockStatus = $customer['is_locked'] ? 0 : 1;
        $result = $customerModel->toggleLock($id, $newLockStatus);
        
        if ($result['success']) {
            $_SESSION['success'] = $result['message'];
        } else {
            $_SESSION['error'] = $result['message'];
        }
        
        header("Location: " . APP_URL . "/Admin/customerDetails/" . $id);
        exit;
    }

    // ====================== QUẢN LÝ QUẢN TRỊ VIÊN ======================
    public function manageAdmins() {
        $this->checkAdminSession();
        
        // Get admins from database using direct query
        try {
            $db = new DB();
            $pdo = $db->Connect();
            
            $sql = "SELECT id, username, email, fullname, created_at FROM tbladmin ORDER BY id DESC";
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            $admins = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            $admins = array();
        }
        
        $this->view("adminPage", [
            "page" => "AdminManagementView",
            "title" => "Quản Lý Tài Khoản Quản Trị Viên",
            "admins" => $admins
        ]);
    }

    public function createAdmin() {
        $this->checkAdminSession();
        
        $this->view("adminPage", [
            "page" => "AdminFormView",
            "title" => "Tạo Tài Khoản Quản Trị Viên Mới",
            "action" => "create"
        ]);
    }

    public function storeAdmin() {
        $this->checkAdminSession();
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = trim($_POST['username'] ?? '');
            $password = trim($_POST['password'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $fullname = trim($_POST['fullname'] ?? '');

            // Validation
            if (empty($username) || empty($password) || empty($email)) {
                $_SESSION['error'] = 'Vui lòng điền đầy đủ thông tin';
                header("Location: " . APP_URL . "/Admin/createAdmin");
                exit;
            }

            try {
                $db = new DB();
                $pdo = $db->Connect();

                // Check if username exists
                $stmt = $pdo->prepare("SELECT id FROM tbladmin WHERE username = ?");
                $stmt->execute([$username]);
                if ($stmt->fetch()) {
                    $_SESSION['error'] = 'Tên đăng nhập đã tồn tại';
                    header("Location: " . APP_URL . "/Admin/createAdmin");
                    exit;
                }

                // Check if email exists
                $stmt = $pdo->prepare("SELECT id FROM tbladmin WHERE email = ?");
                $stmt->execute([$email]);
                if ($stmt->fetch()) {
                    $_SESSION['error'] = 'Email đã tồn tại';
                    header("Location: " . APP_URL . "/Admin/createAdmin");
                    exit;
                }

                // Create admin
                $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
                $stmt = $pdo->prepare("INSERT INTO tbladmin (username, password, email, fullname, created_at) 
                                      VALUES (?, ?, ?, ?, NOW())");
                
                if ($stmt->execute([$username, $hashedPassword, $email, $fullname])) {
                    $_SESSION['success'] = 'Tạo tài khoản quản trị viên thành công';
                    header("Location: " . APP_URL . "/Admin/manageAdmins");
                } else {
                    $_SESSION['error'] = 'Lỗi khi tạo tài khoản';
                    header("Location: " . APP_URL . "/Admin/createAdmin");
                }
            } catch (Exception $e) {
                $_SESSION['error'] = 'Lỗi hệ thống: ' . $e->getMessage();
                header("Location: " . APP_URL . "/Admin/createAdmin");
            }
            exit;
        }
    }

    public function editAdmin($id) {
        $this->checkAdminSession();
        
        try {
            $db = new DB();
            $pdo = $db->Connect();
            
            $stmt = $pdo->prepare("SELECT id, username, email, fullname, created_at FROM tbladmin WHERE id = ?");
            $stmt->execute([$id]);
            $admin = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$admin) {
                $_SESSION['error'] = 'Không tìm thấy quản trị viên';
                header("Location: " . APP_URL . "/Admin/manageAdmins");
                exit;
            }
        } catch (Exception $e) {
            $_SESSION['error'] = 'Lỗi hệ thống';
            header("Location: " . APP_URL . "/Admin/manageAdmins");
            exit;
        }
        
        $this->view("adminPage", [
            "page" => "AdminFormView",
            "title" => "Chỉnh Sửa Tài Khoản Quản Trị Viên",
            "admin" => $admin,
            "action" => "edit"
        ]);
    }

    public function updateAdmin($id) {
        $this->checkAdminSession();
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $email = trim($_POST['email'] ?? '');
            $fullname = trim($_POST['fullname'] ?? '');
            $password = trim($_POST['password'] ?? '');

            // Validation
            if (empty($email)) {
                $_SESSION['error'] = 'Vui lòng điền đầy đủ thông tin';
                header("Location: " . APP_URL . "/Admin/editAdmin/$id");
                exit;
            }

            try {
                $db = new DB();
                $pdo = $db->Connect();
                
                if (!empty($password)) {
                    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
                    $stmt = $pdo->prepare("UPDATE tbladmin SET email = ?, fullname = ?, password = ? WHERE id = ?");
                    $stmt->execute([$email, $fullname, $hashedPassword, $id]);
                } else {
                    $stmt = $pdo->prepare("UPDATE tbladmin SET email = ?, fullname = ? WHERE id = ?");
                    $stmt->execute([$email, $fullname, $id]);
                }

                $_SESSION['success'] = 'Cập nhật thông tin quản trị viên thành công';
                header("Location: " . APP_URL . "/Admin/manageAdmins");
            } catch (Exception $e) {
                $_SESSION['error'] = 'Lỗi khi cập nhật: ' . $e->getMessage();
                header("Location: " . APP_URL . "/Admin/editAdmin/$id");
            }
            exit;
        }
    }

    public function deleteAdmin($id) {
        $this->checkAdminSession();
        
        if ($id == 1) {
            $_SESSION['error'] = 'Không thể xóa tài khoản Administrator gốc';
            header("Location: " . APP_URL . "/Admin/manageAdmins");
            exit;
        }

        try {
            $db = new DB();
            $pdo = $db->Connect();
            
            $stmt = $pdo->prepare("DELETE FROM tbladmin WHERE id = ?");
            if ($stmt->execute([$id])) {
                $_SESSION['success'] = 'Xóa tài khoản quản trị viên thành công';
            } else {
                $_SESSION['error'] = 'Lỗi khi xóa tài khoản';
            }
        } catch (Exception $e) {
            $_SESSION['error'] = 'Lỗi: ' . $e->getMessage();
        }

        header("Location: " . APP_URL . "/Admin/manageAdmins");
        exit;
    }

    // ====================== QUẢN LÝ ĐÁNH GIÁ - BÌNH LUẬN ======================

    /**
     * Danh sách đánh giá chờ duyệt
     */
    public function manageReviews() {
        $this->checkAdminSession();

        $reviewModel = $this->model("ReviewModel");
        
        // Lấy filter từ GET
        $status = $_GET['status'] ?? 'all'; // all, pending, approved, hidden

        try {
            if ($status === 'pending') {
                $reviews = $reviewModel->select(
                    "SELECT r.*, p.tensp FROM tblreview r 
                     LEFT JOIN tblsanpham p ON r.masp = p.masp 
                     WHERE r.trangthai = 'chờ duyệt' 
                     ORDER BY r.ngaygui DESC",
                    []
                );
            } elseif ($status === 'approved') {
                $reviews = $reviewModel->select(
                    "SELECT r.*, p.tensp FROM tblreview r 
                     LEFT JOIN tblsanpham p ON r.masp = p.masp 
                     WHERE r.trangthai = 'đã duyệt' 
                     ORDER BY r.ngaygui DESC",
                    []
                );
            } elseif ($status === 'hidden') {
                $reviews = $reviewModel->select(
                    "SELECT r.*, p.tensp FROM tblreview r 
                     LEFT JOIN tblsanpham p ON r.masp = p.masp 
                     WHERE r.trangthai = 'ẩn' 
                     ORDER BY r.ngaygui DESC",
                    []
                );
            } else {
                $reviews = $reviewModel->select(
                    "SELECT r.*, p.tensp FROM tblreview r 
                     LEFT JOIN tblsanpham p ON r.masp = p.masp 
                     ORDER BY r.trangthai DESC, r.ngaygui DESC",
                    []
                );
            }

            // Lấy thống kê
            try {
                $statsResult = $reviewModel->select(
                    "SELECT 
                        COUNT(*) as total,
                        SUM(CASE WHEN trangthai = 'chờ duyệt' THEN 1 ELSE 0 END) as pending,
                        SUM(CASE WHEN trangthai = 'đã duyệt' THEN 1 ELSE 0 END) as approved,
                        SUM(CASE WHEN trangthai = 'ẩn' THEN 1 ELSE 0 END) as hidden
                    FROM tblreview",
                    []
                );
                $stats = $statsResult ? $statsResult[0] : ['total' => 0, 'pending' => 0, 'approved' => 0, 'hidden' => 0];
            } catch (Exception $statsError) {
                error_log("Admin::manageReviews stats query error: " . $statsError->getMessage());
                $stats = ['total' => 0, 'pending' => 0, 'approved' => 0, 'hidden' => 0];
            }

            $this->view("adminPage", [
                "page" => "ReviewManagementView",
                "reviews" => $reviews ?? [],
                "stats" => $stats,
                "currentStatus" => $status
            ]);
        } catch (Exception $e) {
            error_log("Admin::manageReviews error: " . $e->getMessage());
            $_SESSION['error'] = 'Lỗi: ' . $e->getMessage();
            header("Location: " . APP_URL . "/Admin/show");
            exit;
        }
    }

    /**
     * Cập nhật trạng thái đánh giá (AJAX)
     */
    public function updateReviewStatus() {
        // ✅ LUÔN set JSON header TRƯỚC bất kỳ output nào
        if (ob_get_level() === 0) ob_start();
        header('Content-Type: application/json; charset=UTF-8');
        
        // Check session AFTER header
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if (!isset($_SESSION['admin'])) {
            http_response_code(401);
            echo json_encode([
                'success' => false,
                'message' => 'Unauthorized'
            ], JSON_UNESCAPED_UNICODE);
            if (ob_get_level() > 0) ob_end_flush();
            exit;
        }

        $reviewId = $_POST['id'] ?? null;
        $status = $_POST['status'] ?? null;

        // Map display status to moderation_status
        $statusMap = [
            'chờ duyệt' => 'pending',
            'đã duyệt' => 'approved',
            'ẩn' => 'rejected'
        ];

        if (!$reviewId || !in_array($status, array_keys($statusMap))) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => 'Dữ liệu không hợp lệ'
            ], JSON_UNESCAPED_UNICODE);
            if (ob_get_level() > 0) ob_end_flush();
            exit;
        }

        try {
            $reviewModel = $this->model("ReviewModel");
            $moderationStatus = $statusMap[$status];
            
            // ✅ Cập nhật CẢ 2 field cho consistency
            $reviewModel->query(
                "UPDATE tblreview SET trangthai = :status, moderation_status = :modStatus WHERE id = :id",
                [':status' => $status, ':modStatus' => $moderationStatus, ':id' => (int)$reviewId]
            );

            http_response_code(200);
            echo json_encode([
                'success' => true,
                'message' => 'Cập nhật trạng thái thành công'
            ], JSON_UNESCAPED_UNICODE);
        } catch (Exception $e) {
            error_log("UpdateReviewStatus Error: " . $e->getMessage());
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Lỗi: ' . $e->getMessage()
            ], JSON_UNESCAPED_UNICODE);
        }
        if (ob_get_level() > 0) ob_end_flush();
        exit;
    }

    /**
     * Xóa đánh giá
     */
    public function deleteReview($id) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Kiểm tra là admin (không dùng checkAdminSession vì nó redirect HTML)
        if (!isset($_SESSION['admin'])) {
            $_SESSION['error'] = 'Bạn không có quyền thực hiện hành động này';
            header("Location: " . APP_URL . "/AuthController/ShowAdminLogin");
            exit;
        }

        try {
            $reviewModel = $this->model("ReviewModel");
            $reviewModel->deleteReview((int)$id);

            $_SESSION['success'] = 'Xóa đánh giá thành công';
        } catch (Exception $e) {
            error_log("DeleteReview Error: " . $e->getMessage());
            $_SESSION['error'] = 'Lỗi: ' . $e->getMessage();
        }

        header("Location: " . APP_URL . "/Admin/manageReviews");
        exit;
    }

    /**
     * Xem chi tiết đánh giá
     */
    public function reviewDetail($id) {
        $this->checkAdminSession();

        try {
            $reviewModel = $this->model("ReviewModel");
            $review = $reviewModel->select(
                "SELECT r.*, p.tensp, p.masp FROM tblreview r 
                 LEFT JOIN tblsanpham p ON r.masp = p.masp 
                 WHERE r.id = :id",
                [':id' => (int)$id]
            );

            if (empty($review)) {
                $_SESSION['error'] = 'Đánh giá không tồn tại';
                header("Location: " . APP_URL . "/Admin/manageReviews");
                exit;
            }

            $this->view("adminPage", [
                "page" => "ReviewDetailView",
                "review" => $review[0]
            ]);
        } catch (Exception $e) {
            $_SESSION['error'] = 'Lỗi: ' . $e->getMessage();
            header("Location: " . APP_URL . "/Admin/manageReviews");
            exit;
        }
    }
}

