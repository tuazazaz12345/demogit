<?php
class App {
    protected $controller = "Home";
    protected $action = "index"; // ✅ đổi mặc định thành index()
    protected $param = [];

    public function __construct() {
        // Khởi tạo session cho toàn bộ ứng dụng
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $arr = [];

        // 1️⃣ Lấy URL
        if (isset($_GET["url"])) {
            $arr = $this->urlprocess();
        }

        // 2️⃣ Kiểm tra controller tồn tại
        if (isset($arr[0]) && file_exists("./controllers/" . $arr[0] . ".php")) {
            $this->controller = $arr[0];
            unset($arr[0]);
        }

        // 3️⃣ Nạp controller
        require_once "./controllers/" . $this->controller . ".php";
        $this->controller = new $this->controller;

        // 4️⃣ Kiểm tra action tồn tại
        if (isset($arr[1]) && method_exists($this->controller, $arr[1])) {
            $this->action = $arr[1];
            unset($arr[1]);
        }

        // 5️⃣ Lấy params (nếu có)
        $this->param = $arr ? array_values($arr) : [];

        // 6️⃣ Gọi action
        call_user_func_array([$this->controller, $this->action], $this->param);
    }

    public function urlprocess() {
        if (isset($_GET["url"])) {
            return explode('/', filter_var(trim($_GET["url"], '/')));
        }
        return [];
    }
}
