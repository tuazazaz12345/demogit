<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đăng nhập hệ thống</title>
    <link href="<?php echo APP_URL; ?>/public/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .login-container {
            max-width: 420px;
            margin-top: 60px;
            background: #fff;
            border-radius: 10px;
            padding: 30px 25px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
    </style>
</head>
<body>

<div class="container">
    <div class="login-container mx-auto">
        <h3 class="text-center mb-4 text-primary">Đăng nhập tài khoản</h3>
        <form action="<?php echo APP_URL; ?>/AuthController/login" method="POST">
            <!-- Email / Username -->
            <div class="mb-3">
                <label for="email" class="form-label">Email hoặc tên đăng nhập</label>
                <input type="text" class="form-control" id="email" name="email" required placeholder="Nhập email hoặc username">
            </div>

            <!-- Password -->
            <div class="mb-3">
                <label for="password" class="form-label">Mật khẩu</label>
                <input type="password" class="form-control" id="password" name="password" required placeholder="Nhập mật khẩu">
            </div>

            <!-- Vai trò -->
            <div class="mb-3">
                <label for="role" class="form-label">Đăng nhập với tư cách:</label>
                <select class="form-select" id="role" name="role" required>
                    <option value="user" selected>Khách hàng</option>
                    <option value="admin">Quản trị viên</option>
                </select>
            </div>

            <!-- Buttons -->
            <div class="d-grid">
                <button type="submit" class="btn btn-success mb-2">Đăng nhập</button>
            </div>

            <div class="text-center mt-3">
                <a href="<?php echo APP_URL; ?>/AuthController/Show" class="btn btn-outline-primary btn-sm">Đăng ký thành viên</a>
                <a href="<?php echo APP_URL; ?>/AuthController/ShowAdminRegister" class="btn btn-outline-warning btn-sm">Đăng ký Admin</a>
                <div class="mt-2">
                    <a href="<?php echo APP_URL; ?>/AuthController/forgotPassword" class="text-decoration-none">Quên mật khẩu?</a>
                </div>
            </div>
        </form>
    </div>
</div>

</body>
</html>
