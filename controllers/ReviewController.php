<?php
class ReviewController extends Controller {
    private $reviewModel;

    public function __construct() {
        // Gọi model từ thư mục models
        $this->reviewModel = $this->model('ReviewModel');
    }

    // Hiển thị danh sách đánh giá theo sản phẩm (gọi từ trang chi tiết)
    public function show($masp) {
        $reviews = $this->reviewModel->getReviewsByProduct($masp);
        $average = $this->reviewModel->getAverageRating($masp);
        $this->view('Font_end/DetailView', [
            'reviews' => $reviews,
            'average' => $average
        ]);
    }

    // Xử lý gửi đánh giá từ người dùng
    public function add() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Bắt đầu output buffering để tránh "headers already sent"
            if (ob_get_level() === 0) ob_start();
            
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }

            if (!isset($_SESSION['user'])) {
                echo "<script>alert('Vui lòng đăng nhập để đánh giá!');window.location.href='" . APP_URL . "/AuthController/showLogin';</script>";
                exit();
            }

            $orderId = $_POST['order_id'] ?? null;
            $masp = $_POST['masp'] ?? null;
            $noidung = trim($_POST['noidung'] ?? '');
            $sosao = (int)($_POST['sosao'] ?? 5);

            // ✅ Chỉ chấp nhận review từ đơn hàng (có order_id)
            if (!$orderId) {
                $_SESSION['error_message'] = 'Chỉ có thể đánh giá sản phẩm sau khi thanh toán';
                header("Location: " . APP_URL . "/Home/index");
                exit();
            }

            // Validate
            if (!$masp) {
                $_SESSION['error_message'] = 'Sản phẩm không tồn tại';
                header("Location: " . APP_URL . "/Home/orderDetail/" . $orderId);
                exit();
            }

            if (strlen($noidung) < 10) {
                $_SESSION['error_message'] = 'Nội dung đánh giá phải tối thiểu 10 ký tự';
                header("Location: " . APP_URL . "/Home/orderDetail/" . $orderId);
                exit();
            }

            if ($sosao < 1 || $sosao > 5) {
                $_SESSION['error_message'] = 'Đánh giá sao phải từ 1 đến 5';
                header("Location: " . APP_URL . "/Home/orderDetail/" . $orderId);
                exit();
            }

            // Kiểm tra xem đã đánh giá chưa (nếu có order_id)
            if ($orderId && $this->reviewModel->checkReviewExists($orderId, $masp)) {
                $_SESSION['error_message'] = 'Bạn đã đánh giá sản phẩm này trong đơn hàng!';
                header("Location: " . APP_URL . "/Home/orderDetail/" . $orderId);
                exit();
            }

            // ✅ KIỂM DUYỆT NỘI DUNG TỰ ĐỘNG
            require_once __DIR__ . '/../app/ContentModerationService.php';
            $cleanContent = ContentModerationService::sanitizeContent($noidung);
            $analysisResult = ContentModerationService::analyzeContent($cleanContent, $sosao);

            $data = [
                'masp' => $masp,
                'ten' => $_SESSION['user']['fullname'],
                'email' => $_SESSION['user']['email'],
                'noidung' => $cleanContent,
                'sosao' => $sosao,
                'order_id' => $orderId
            ];

            try {
                error_log("ReviewController::add() - Submitting review data: masp=$masp, email=" . $_SESSION['user']['email']);
                
                $reviewId = $this->reviewModel->addReview($data);
                
                if ($reviewId) {
                    error_log("ReviewController::add() - SUCCESS: reviewId=$reviewId");
                    
                    // ✅ GHI NHẬN KẾT QUẢ KIỂM DUYỆT TỰ ĐỘNG
                    try {
                        $prohibitedWordsCount = count($analysisResult['prohibited_words_found']);
                        $this->reviewModel->recordSpamAnalysis(
                            $reviewId,
                            $analysisResult['spam_score'],
                            $prohibitedWordsCount
                        );
                        
                        // Cập nhật trạng thái dựa vào kết quả kiểm duyệt
                        $predictedStatus = ContentModerationService::getPredictedStatus($analysisResult['spam_score']);
                        if ($predictedStatus === 'spam') {
                            $this->reviewModel->updateModerationStatus($reviewId, 'spam', null, 'Spam tự động phát hiện');
                            $_SESSION['warning_message'] = "Đánh giá của bạn đã gửi nhưng được đánh dấu là spam và sẽ không hiển thị cho đến khi được xem xét.";
                        } else if ($predictedStatus === 'pending') {
                            $this->reviewModel->updateModerationStatus($reviewId, 'pending');
                            $_SESSION['success_message'] = "Cảm ơn bạn đã gửi đánh giá! Admin sẽ kiểm duyệt sớm.";
                        } else {
                            $this->reviewModel->updateModerationStatus($reviewId, 'approved');
                            $_SESSION['success_message'] = "Cảm ơn bạn đã gửi đánh giá! Đánh giá của bạn đã được duyệt.";
                        }
                        
                        error_log("ReviewController::add() - Moderation analysis: spam_score=" . $analysisResult['spam_score'] . ", status=" . $predictedStatus);
                    } catch (Exception $e) {
                        error_log("ReviewController::add() - Error recording moderation: " . $e->getMessage());
                    }
                    
                    if (ob_get_level() > 0) ob_end_clean();
                    
                    if ($orderId) {
                        header("Location: " . APP_URL . "/Home/orderDetail/" . $orderId);
                    } else {
                        header("Location: " . APP_URL . "/Home/detail/" . $masp);
                    }
                    exit();
                } else {
                    error_log("ReviewController::add() - FAILED: addReview() returned false/null");
                    $_SESSION['error_message'] = "Có lỗi xảy ra khi gửi đánh giá! (addReview failed)";
                    
                    if (ob_get_level() > 0) ob_end_clean();
                    
                    header("Location: " . APP_URL . "/Home/detail/" . $masp);
                    exit();
                }
            } catch (Exception $e) {
                error_log("ReviewController::add() - Exception: " . $e->getMessage());
                $_SESSION['error_message'] = "Lỗi: " . $e->getMessage();
                
                if (ob_get_level() > 0) ob_end_clean();
                
                header("Location: " . APP_URL . "/Home/detail/" . $masp);
                exit();
            }
        }
    }

    // Hiển thị đánh giá theo đơn hàng
    public function showByOrder($orderId) {
        $reviews = $this->reviewModel->getReviewsByOrder($orderId);
        return $reviews;
    }


    // Hiển thị danh sách đánh giá đã duyệt
    public function list() {
        $masp = $_GET['masp'] ?? null;

        if (!$masp) {
            $this->view('Font_end/ReviewListView', [
                'reviews' => [],
                'average' => 0,
                'masp' => null
            ]);
            return;
        }

        $reviews = $this->reviewModel->select(
            "SELECT * FROM tblreview WHERE masp = ? AND trangthai = 'đã duyệt' ORDER BY ngaygui DESC",
            [$masp]
        );

        $averageResult = $this->reviewModel->select(
            "SELECT AVG(sosao) as average FROM tblreview WHERE masp = ? AND trangthai = 'đã duyệt'",
            [$masp]
        );

        $average = $averageResult[0]['average'] ?? 0;

        $this->view('Font_end/ReviewListView', [
            'reviews' => $reviews,
            'average' => $average,
            'masp' => $masp
        ]);
    }
}
