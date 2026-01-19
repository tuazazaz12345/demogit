<?php
/**
 * ContentModerationController - Quản lý kiểm duyệt nội dung
 */
class ContentModerationController extends Controller {
    private $reviewModel;
    
    public function __construct() {
        $this->reviewModel = $this->model('ReviewModel');
    }
    
    /**
     * Hiển thị dashboard kiểm duyệt
     */
    public function index() {
        // Kiểm tra admin
        if (!$this->isAdmin()) {
            header('Location: ' . APP_URL . '/Home');
            exit();
        }
        
        $stats = $this->reviewModel->getModerationStats();
        $pendingCount = $stats['pending'] ?? 0;
        
        $this->view('Back_end/ContentModerationDashboard', [
            'stats' => $stats,
            'page_title' => 'Quản Lý Kiểm Duyệt Nội Dung'
        ]);
    }
    
    /**
     * Danh sách review chờ duyệt
     */
    public function pending() {
        if (!$this->isAdmin()) {
            header('Location: ' . APP_URL . '/Home');
            exit();
        }
        
        $reviews = $this->reviewModel->getPendingReviews();
        $stats = $this->reviewModel->getModerationStats();
        
        $this->view('Back_end/ContentModerationPending', [
            'reviews' => $reviews,
            'stats' => $stats,
            'page_title' => 'Đánh Giá Chờ Duyệt'
        ]);
    }
    
    /**
     * Danh sách review bị từ chối/spam
     */
    public function rejected() {
        if (!$this->isAdmin()) {
            header('Location: ' . APP_URL . '/Home');
            exit();
        }
        
        $reviews = $this->reviewModel->getRejectedReviews();
        $stats = $this->reviewModel->getModerationStats();
        
        $this->view('Back_end/ContentModerationRejected', [
            'reviews' => $reviews,
            'stats' => $stats,
            'page_title' => 'Đánh Giá Bị Từ Chối/Spam'
        ]);
    }
    
    /**
     * Danh sách review đã duyệt
     */
    public function approved() {
        if (!$this->isAdmin()) {
            header('Location: ' . APP_URL . '/Home');
            exit();
        }
        
        $reviews = $this->reviewModel->getApprovedReviews();
        $stats = $this->reviewModel->getModerationStats();
        
        $this->view('Back_end/ContentModerationApproved', [
            'reviews' => $reviews,
            'stats' => $stats,
            'page_title' => 'Đánh Giá Đã Duyệt'
        ]);
    }
    
    /**
     * Xem chi tiết và kiểm duyệt một review
     */
    public function review($reviewId) {
        if (!$this->isAdmin()) {
            header('Location: ' . APP_URL . '/Home');
            exit();
        }
        
        $review = $this->reviewModel->getReviewDetail($reviewId);
        
        if (!$review) {
            $_SESSION['error_message'] = 'Không tìm thấy đánh giá';
            header('Location: ' . APP_URL . '/ContentModerationController/pending');
            exit();
        }
        
        // Phân tích nội dung
        require_once __DIR__ . '/../app/ContentModerationService.php';
        $analysis = ContentModerationService::analyzeContent($review['noidung'], $review['sosao']);
        
        $this->view('Back_end/ContentModerationReview', [
            'review' => $review,
            'analysis' => $analysis,
            'page_title' => 'Kiểm Duyệt Đánh Giá #' . $reviewId
        ]);
    }
    
    /**
     * Duyệt một review
     */
    public function approve() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !$this->isAdmin()) {
            header('Location: ' . APP_URL . '/Home');
            exit();
        }
        
        $reviewId = $_POST['review_id'] ?? null;
        $notes = trim($_POST['notes'] ?? '');
        
        if (!$reviewId) {
            $_SESSION['error_message'] = 'Invalid review ID';
            header('Location: ' . APP_URL . '/ContentModerationController/pending');
            exit();
        }
        
        try {
            $adminId = $_SESSION['user']['id'] ?? null;
            $this->reviewModel->updateModerationStatus($reviewId, 'approved', $adminId, '', $notes);
            
            error_log("ContentModerationController::approve() - Review $reviewId approved by admin $adminId");
            $_SESSION['success_message'] = 'Đánh giá đã được duyệt';
        } catch (Exception $e) {
            error_log("ContentModerationController::approve() - Error: " . $e->getMessage());
            $_SESSION['error_message'] = 'Lỗi khi duyệt đánh giá';
        }
        
        header('Location: ' . APP_URL . '/ContentModerationController/pending');
        exit();
    }
    
    /**
     * Từ chối một review
     */
    public function reject() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !$this->isAdmin()) {
            header('Location: ' . APP_URL . '/Home');
            exit();
        }
        
        $reviewId = $_POST['review_id'] ?? null;
        $reason = trim($_POST['reason'] ?? '');
        $notes = trim($_POST['notes'] ?? '');
        
        if (!$reviewId) {
            $_SESSION['error_message'] = 'Invalid review ID';
            header('Location: ' . APP_URL . '/ContentModerationController/pending');
            exit();
        }
        
        if (empty($reason)) {
            $_SESSION['error_message'] = 'Vui lòng nhập lý do từ chối';
            header('Location: ' . APP_URL . '/ContentModerationController/review/' . $reviewId);
            exit();
        }
        
        try {
            $adminId = $_SESSION['user']['id'] ?? null;
            $this->reviewModel->updateModerationStatus($reviewId, 'rejected', $adminId, $reason, $notes);
            
            error_log("ContentModerationController::reject() - Review $reviewId rejected by admin $adminId. Reason: $reason");
            $_SESSION['success_message'] = 'Đánh giá đã bị từ chối';
        } catch (Exception $e) {
            error_log("ContentModerationController::reject() - Error: " . $e->getMessage());
            $_SESSION['error_message'] = 'Lỗi khi từ chối đánh giá';
        }
        
        header('Location: ' . APP_URL . '/ContentModerationController/pending');
        exit();
    }
    
    /**
     * Đánh dấu spam
     */
    public function markSpam() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !$this->isAdmin()) {
            header('Location: ' . APP_URL . '/Home');
            exit();
        }
        
        $reviewId = $_POST['review_id'] ?? null;
        $notes = trim($_POST['notes'] ?? '');
        
        if (!$reviewId) {
            $_SESSION['error_message'] = 'Invalid review ID';
            header('Location: ' . APP_URL . '/ContentModerationController/pending');
            exit();
        }
        
        try {
            $adminId = $_SESSION['user']['id'] ?? null;
            $this->reviewModel->updateModerationStatus($reviewId, 'spam', $adminId, 'Spam thủ công', $notes);
            
            error_log("ContentModerationController::markSpam() - Review $reviewId marked as spam by admin $adminId");
            $_SESSION['success_message'] = 'Đánh giá được đánh dấu là spam';
        } catch (Exception $e) {
            error_log("ContentModerationController::markSpam() - Error: " . $e->getMessage());
            $_SESSION['error_message'] = 'Lỗi khi đánh dấu spam';
        }
        
        header('Location: ' . APP_URL . '/ContentModerationController/pending');
        exit();
    }
    
    /**
     * Bulk approve
     */
    public function bulkApprove() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !$this->isAdmin()) {
            header('Location: ' . APP_URL . '/Home');
            exit();
        }
        
        $reviewIds = $_POST['review_ids'] ?? [];
        
        if (empty($reviewIds)) {
            $_SESSION['error_message'] = 'Vui lòng chọn ít nhất một đánh giá';
            header('Location: ' . APP_URL . '/ContentModerationController/pending');
            exit();
        }
        
        try {
            $adminId = $_SESSION['user']['id'] ?? null;
            $this->reviewModel->bulkUpdateStatus($reviewIds, 'approved', $adminId);
            
            error_log("ContentModerationController::bulkApprove() - Approved " . count($reviewIds) . " reviews by admin $adminId");
            $_SESSION['success_message'] = 'Đã duyệt ' . count($reviewIds) . ' đánh giá';
        } catch (Exception $e) {
            error_log("ContentModerationController::bulkApprove() - Error: " . $e->getMessage());
            $_SESSION['error_message'] = 'Lỗi khi duyệt hàng loạt';
        }
        
        header('Location: ' . APP_URL . '/ContentModerationController/pending');
        exit();
    }
    
    /**
     * Kiểm tra xem user có phải admin không
     */
    private function isAdmin() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        return isset($_SESSION['user']) && isset($_SESSION['user']['role']) && 
               ($_SESSION['user']['role'] === 'admin' || $_SESSION['user']['role'] === 'superadmin');
    }
}
?>
