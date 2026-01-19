<style>
    .forgot-password-container {
        max-width: 500px;
        margin: 80px auto;
        padding: 40px;
        background: white;
        border-radius: 15px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.1);
    }
    
    .forgot-header {
        text-align: center;
        margin-bottom: 30px;
    }
    
    .forgot-header i {
        font-size: 60px;
        color: #3498db;
        margin-bottom: 15px;
    }
    
    .forgot-header h2 {
        color: #2c3e50;
        font-weight: 700;
        margin-bottom: 10px;
    }
    
    .forgot-header p {
        color: #7f8c8d;
        font-size: 14px;
    }
    
    .form-control {
        padding: 12px 15px;
        border: 2px solid #ecf0f1;
        border-radius: 8px;
        font-size: 15px;
    }
    
    .form-control:focus {
        border-color: #3498db;
        box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.1);
    }
    
    .btn-send-otp {
        width: 100%;
        padding: 14px;
        background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
        border: none;
        color: white;
        font-weight: 600;
        border-radius: 8px;
        font-size: 16px;
        transition: all 0.3s ease;
    }
    
    .btn-send-otp:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(52, 152, 219, 0.4);
    }
    
    .back-to-login {
        text-align: center;
        margin-top: 20px;
    }
    
    .back-to-login a {
        color: #3498db;
        text-decoration: none;
        font-weight: 500;
    }
    
    .back-to-login a:hover {
        text-decoration: underline;
    }
</style>

<div class="forgot-password-container">
    <div class="forgot-header">
        <i class="bi bi-key"></i>
        <h2>Quên mật khẩu?</h2>
        <p>Nhập email của bạn để nhận mã OTP đặt lại mật khẩu</p>
    </div>
    
    <form action="<?php echo APP_URL; ?>/AuthController/sendResetOTP" method="POST">
        <div class="mb-4">
            <label for="email" class="form-label fw-bold">
                <i class="bi bi-envelope me-2"></i>Email đã đăng ký
            </label>
            <input type="email" 
                   class="form-control" 
                   id="email" 
                   name="email" 
                   placeholder="Nhập địa chỉ email của bạn"
                   required>
        </div>
        
        <button type="submit" class="btn btn-send-otp">
            <i class="bi bi-send me-2"></i>Gửi mã OTP
        </button>
    </form>
    
    <div class="back-to-login">
        <a href="<?php echo APP_URL; ?>/AuthController/showLogin">
            <i class="bi bi-arrow-left me-1"></i>Quay lại đăng nhập
        </a>
    </div>
</div>
