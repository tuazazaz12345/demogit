<?php
class FeedbackController extends Controller {
    private $feedbackModel;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $this->feedbackModel = $this->model('FeedbackModel');
    }

    // Hiển thị form góp ý cho người dùng
    public function showForm() {
        $this->view("homePage", [
            "page" => "FeedbackView"
        ]);
    }

    // Xử lý thêm góp ý
    public function add() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!isset($_SESSION['user'])) {
                $_SESSION['error_message'] = "Vui lòng đăng nhập để gửi góp ý!";
                header('Location: ' . APP_URL . '/AuthController/showLogin');
                exit();
            }

            $data = [
                'user_email' => $_SESSION['user']['email'],
                'fullname' => $_SESSION['user']['fullname'],
                'content' => $_POST['content']
            ];

            if ($this->feedbackModel->addFeedback($data)) {
                $_SESSION['success_message'] = "Cảm ơn bạn đã gửi góp ý! Chúng tôi sẽ xem xét và phản hồi sớm nhất.";
            } else {
                $_SESSION['error_message'] = "Có lỗi xảy ra khi gửi góp ý!";
            }
            header('Location: ' . APP_URL . '/FeedbackController/showForm');
            exit();
        }
    }

    // Admin: Hiển thị danh sách góp ý
    public function manage() {
        if (!isset($_SESSION['admin'])) {
            header('Location: ' . APP_URL . '/AuthController/showLoginAdmin');
            exit();
        }

        $feedbacks = $this->feedbackModel->getAllFeedback();
        $this->view("adminPage", [
            "page" => "FeedbackView",
            "feedbacks" => $feedbacks
        ]);
    }

    // Admin: Cập nhật trạng thái góp ý
    public function updateStatus() {
        if (!isset($_SESSION['admin'])) {
            header('Location: ' . APP_URL . '/AuthController/showLoginAdmin');
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
            $id = $_POST['id'];
            $status = $_POST['status'];

            if ($this->feedbackModel->updateStatus($id, $status)) {
                $_SESSION['success_message'] = "Cập nhật trạng thái góp ý thành công!";
            } else {
                $_SESSION['error_message'] = "Có lỗi xảy ra khi cập nhật trạng thái!";
            }
            header('Location: ' . APP_URL . '/FeedbackController/manage');
            exit();
        }
    }

    // Admin: Xóa góp ý
    public function delete() {
        if (!isset($_SESSION['admin'])) {
            header('Location: ' . APP_URL . '/AuthController/showLoginAdmin');
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
            $id = $_POST['id'];

            if ($this->feedbackModel->deleteFeedback($id)) {
                $_SESSION['success_message'] = "Xóa góp ý thành công!";
            } else {
                $_SESSION['error_message'] = "Có lỗi xảy ra khi xóa góp ý!";
            }
            header('Location: ' . APP_URL . '/FeedbackController/manage');
            exit();
        }
    }
}