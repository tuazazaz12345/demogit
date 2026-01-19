<?php

class ChatboxController extends Controller {
    
    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }
    
    /**
     * Hiển thị trang chatbox
     */
    public function index() {
        $messages = [];
        
        // Nếu user đã login, lấy tin nhắn của user
        if (isset($_SESSION['user'])) {
            $chatboxModel = $this->model('ChatboxModel');
            $messages = $chatboxModel->getByEmail($_SESSION['user']['email']);
        }
        
        $this->view('homePage', [
            'page' => 'ChatboxView',
            'messages' => $messages,
            'title' => 'Hỗ Trợ Khách Hàng'
        ]);
    }
    
    /**
     * Gửi tin nhắn mới (AJAX)
     */
    public function send() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            exit();
        }
        
        // Lấy JSON data
        $data = json_decode(file_get_contents('php://input'), true);
        
        $email = $data['email'] ?? null;
        $name = $data['name'] ?? null;
        $message = $data['message'] ?? null;
        
        // Validate
        if (!$email || !$name || !$message) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Vui lòng điền đầy đủ thông tin']);
            exit();
        }
        
        // Validate email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Email không hợp lệ']);
            exit();
        }
        
        // Lưu tin nhắn
        $chatboxModel = $this->model('ChatboxModel');
        $result = $chatboxModel->sendMessage($email, $name, $message);
        
        http_response_code($result['success'] ? 201 : 500);
        echo json_encode($result);
    }
    
    /**
     * Kiểm tra trạng thái đăng nhập của user (AJAX)
     */
    public function checkLogin() {
        header('Content-Type: application/json');
        
        if (isset($_SESSION['user'])) {
            echo json_encode([
                'success' => true,
                'isLoggedIn' => true,
                'email' => $_SESSION['user']['email'],
                'name' => $_SESSION['user']['name'] ?? $_SESSION['user']['email']
            ]);
        } else {
            echo json_encode([
                'success' => true,
                'isLoggedIn' => false
            ]);
        }
    }
    
    /**
     * Lấy tin nhắn của user (AJAX)
     */
    public function getMessages() {
        // Lấy email từ session hoặc từ query parameter
        $email = null;
        
        if (isset($_SESSION['user'])) {
            $email = $_SESSION['user']['email'];
        } elseif (isset($_GET['email'])) {
            $email = $_GET['email'];
        } else {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Email không được cung cấp']);
            exit();
        }
        
        // Validate email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Email không hợp lệ']);
            exit();
        }
        
        $chatboxModel = $this->model('ChatboxModel');
        $messages = $chatboxModel->getByEmail($email);
        
        echo json_encode([
            'success' => true,
            'messages' => $messages
        ]);
    }
}
