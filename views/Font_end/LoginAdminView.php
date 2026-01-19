<div class="container mt-5" style="max-width: 450px;">
    <h3 class="text-center mb-4">Đăng nhập quản trị</h3>
    <form method="POST" action="<?php echo APP_URL; ?>/AuthController/AdminLogin">
        <div class="mb-3">
            <label for="username">Tên đăng nhập</label>
            <input type="text" class="form-control" id="username" name="username" required>
        </div>
        <div class="mb-3">
            <label for="password">Mật khẩu</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">Đăng nhập</button>
        <p class="text-center mt-3">
            Chưa có tài khoản?
            <a href="<?php echo APP_URL; ?>/AuthController/ShowAdminRegister">Đăng ký</a>
        </p>
    </form>
</div>