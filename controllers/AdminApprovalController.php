<?php
/**
 * AdminApprovalController - Quản lý duyệt tài khoản admin
 * Chỉ Super Admin mới có thể truy cập
 */
class AdminApprovalController extends Controller {

    private function checkSuperAdminSession() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['admin'])) {
            $_SESSION['redirect_url'] = $_SERVER['REQUEST_URI'];
            header("Location: " . APP_URL . "/AuthController/ShowAdminLogin");
            exit;
        }

        // ✅ Kiểm tra xem admin có phải Super Admin không
        $adminModel = $this->model("AdminModel");
        
        // Kiểm tra bảng super_admins có tồn tại không
        try {
            if (!$adminModel->isSuperAdmin($_SESSION['admin']['id'])) {
                echo '<div class="container mt-5"><div class="alert alert-danger">
                    <strong>❌ Truy cập bị từ chối</strong><br>
                    Chỉ admin tổng (Super Admin) mới có thể truy cập trang này.
                </div></div>';
                exit;
            }
        } catch (Exception $e) {
            // ✅ Nếu bảng chưa tồn tại, hiển thị hướng dẫn thiết lập
            echo '<div class="container mt-5"><div class="alert alert-warning">
                <strong>⚠️ Hệ thống chưa được thiết lập</strong><br>
                Vui lòng chạy SQL từ file <code>ADMIN_APPROVAL_SYSTEM.sql</code> trước.<br>
                <a href="' . APP_URL . '" class="btn btn-primary mt-2">Quay lại</a>
            </div></div>';
            exit;
        }
    }

    public function __construct() {
        $this->checkSuperAdminSession();
    }

    // ====================== DANH SÁCH YÊU CẦU DUYỆT ======================
    public function index() {
        $adminModel = $this->model("AdminModel");
        
        // Lấy tất cả yêu cầu (pending, approved, rejected)
        $allRequests = $adminModel->getAllRequests();
        
        // Phân loại theo status
        $pendingRequests = array_filter($allRequests, function($r) { return $r['status'] === 'pending'; });
        $approvedRequests = array_filter($allRequests, function($r) { return $r['status'] === 'approved'; });
        $rejectedRequests = array_filter($allRequests, function($r) { return $r['status'] === 'rejected'; });
        
        $this->view("adminPage", [
            "page" => "AdminApprovalView",
            "pendingRequests" => array_values($pendingRequests),
            "approvedRequests" => array_values($approvedRequests),
            "rejectedRequests" => array_values($rejectedRequests),
            "totalPending" => count($pendingRequests)
        ]);
    }

    // ====================== CHI TIẾT YÊU CẦU ======================
    public function detail($requestId) {
        $adminModel = $this->model("AdminModel");
        $request = $adminModel->getRequestById($requestId);
        
        if (!$request) {
            $_SESSION['error'] = "Yêu cầu không tồn tại";
            header("Location: " . APP_URL . "/AdminApprovalController/index");
            exit;
        }
        
        // Lấy thông tin người phê duyệt
        $approvedByAdmin = null;
        if ($request['approved_by']) {
            $sql = "SELECT id, username, fullname FROM tbladmin WHERE id = ?";
            $result = $adminModel->select($sql, [$request['approved_by']]);
            $approvedByAdmin = $result ? $result[0] : null;
        }
        
        $this->view("adminPage", [
            "page" => "AdminApprovalDetailView",
            "request" => $request,
            "approvedByAdmin" => $approvedByAdmin
        ]);
    }

    // ====================== PHÊ DUYỆT ======================
    public function approve($requestId) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: " . APP_URL . "/AdminApprovalController/index");
            exit;
        }

        $adminModel = $this->model("AdminModel");
        $currentAdminId = $_SESSION['admin']['id'];
        
        // Phê duyệt admin
        if ($adminModel->approveAdmin($requestId, $currentAdminId)) {
            // ✅ Gửi email thông báo
            $request = $adminModel->getRequestById($requestId);
            if ($request) {
                $this->sendApprovalEmail($request['email'], $request['username']);
            }
            
            $_SESSION['success'] = "✅ Đã phê duyệt tài khoản admin thành công";
        } else {
            $_SESSION['error'] = "❌ Có lỗi xảy ra khi phê duyệt";
        }
        
        header("Location: " . APP_URL . "/AdminApprovalController/index");
        exit;
    }

    // ====================== TỪ CHỐI ======================
    public function reject($requestId) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: " . APP_URL . "/AdminApprovalController/index");
            exit;
        }

        $reason = isset($_POST['reason']) ? trim($_POST['reason']) : 'Không rõ lý do';
        $adminModel = $this->model("AdminModel");
        $currentAdminId = $_SESSION['admin']['id'];
        
        // Từ chối admin
        if ($adminModel->rejectAdmin($requestId, $currentAdminId, $reason)) {
            // ✅ Gửi email thông báo từ chối
            $request = $adminModel->getRequestById($requestId);
            if ($request) {
                $this->sendRejectionEmail($request['email'], $request['username'], $reason);
            }
            
            $_SESSION['success'] = "✅ Đã từ chối yêu cầu";
        } else {
            $_SESSION['error'] = "❌ Có lỗi xảy ra khi từ chối";
        }
        
        header("Location: " . APP_URL . "/AdminApprovalController/index");
        exit;
    }

    // ====================== GỬI EMAIL ======================
    private function sendApprovalEmail($email, $username) {
        require_once 'vendor/autoload.php';
        
        $mail = new \PHPMailer\PHPMailer\PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'zerohn889@gmail.com';
            $mail->Password = 'ijgl wiav jtpq nuto';
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            $mail->setFrom('zerohn889@gmail.com', 'Admin - Nhà Sách Online');
            $mail->addAddress($email);
            $mail->isHTML(true);
            $mail->Subject = "✅ Tài khoản admin của bạn đã được phê duyệt";
            
            $loginUrl = APP_URL . '/AuthController/ShowAdminLogin';
            $mail->Body = "
                <h3>Chào $username!</h3>
                <p>🎉 Tài khoản admin của bạn đã được admin tổng phê duyệt thành công!</p>
                <p>Bây giờ bạn có thể đăng nhập và sử dụng các tính năng admin.</p>
                <p><a href='$loginUrl' class='btn' style='background-color: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>Đăng nhập ngay</a></p>
                <p>---</p>
                <p>Nếu bạn không yêu cầu này, vui lòng liên hệ admin tổng.</p>
            ";

            $mail->send();
        } catch (Exception $e) {
            error_log("Error sending approval email: {$mail->ErrorInfo}");
        }
    }

    private function sendRejectionEmail($email, $username, $reason) {
        require_once 'vendor/autoload.php';
        
        $mail = new \PHPMailer\PHPMailer\PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'zerohn889@gmail.com';
            $mail->Password = 'ijgl wiav jtpq nuto';
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            $mail->setFrom('zerohn889@gmail.com', 'Admin - Nhà Sách Online');
            $mail->addAddress($email);
            $mail->isHTML(true);
            $mail->Subject = "❌ Yêu cầu tài khoản admin bị từ chối";
            
            $mail->Body = "
                <h3>Chào $username!</h3>
                <p>❌ Yêu cầu tạo tài khoản admin của bạn đã bị từ chối.</p>
                <p><strong>Lý do:</strong> $reason</p>
                <p>Vui lòng liên hệ với admin tổng để biết thêm chi tiết hoặc yêu cầu xem xét lại.</p>
            ";

            $mail->send();
        } catch (Exception $e) {
            error_log("Error sending rejection email: {$mail->ErrorInfo}");
        }
    }
}
?>
