<?php
if (!isset($_SESSION['user'])) {
    header('Location: ' . APP_URL . '/AuthController/showLogin');
    exit();
}
?>

<style>
    .password-card {
        max-width: 600px;
        margin: 0 auto;
    }
    
    .password-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 25px;
        border-radius: 10px 10px 0 0;
    }
    
    .password-body {
        padding: 30px;
        background: white;
        border-radius: 0 0 10px 10px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.1);
    }
    
    .form-label {
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 8px;
    }
    
    .password-input-wrapper {
        position: relative;
    }
    
    .password-input-wrapper input {
        padding-right: 45px;
    }
    
    .toggle-password {
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
        border: none;
        background: none;
        cursor: pointer;
        color: #7f8c8d;
        padding: 5px 10px;
    }
    
    .toggle-password:hover {
        color: #2c3e50;
    }
    
    .password-requirements {
        background: #f8f9fa;
        border-left: 4px solid #3498db;
        padding: 15px;
        margin: 20px 0;
        border-radius: 5px;
    }
    
    .password-requirements ul {
        margin: 0;
        padding-left: 20px;
    }
    
    .password-requirements li {
        color: #7f8c8d;
        margin: 5px 0;
    }
    
    .password-strength {
        height: 5px;
        border-radius: 3px;
        margin-top: 5px;
        transition: all 0.3s ease;
    }
    
    .strength-weak { background: #e74c3c; width: 33%; }
    .strength-medium { background: #f39c12; width: 66%; }
    .strength-strong { background: #27ae60; width: 100%; }
    
    .btn-change-password {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        padding: 12px 30px;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    
    .btn-change-password:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
    }
</style>

<div class="container mt-5">
    <div class="password-card">
        <div class="password-header">
            <h3 class="mb-1"><i class="bi bi-shield-lock me-2"></i>Đổi mật khẩu</h3>
            <p class="mb-0" style="font-size: 14px; opacity: 0.9;">Bảo mật tài khoản của bạn</p>
        </div>
        
        <div class="password-body">
            <?php if(isset($data['success'])): ?>
                <div class="alert alert-success alert-dismissible fade show">
                    <i class="bi bi-check-circle me-2"></i><?php echo $data['success']; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            
            <?php if(isset($data['error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show">
                    <i class="bi bi-exclamation-triangle me-2"></i><?php echo $data['error']; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <div class="password-requirements">
                <strong><i class="bi bi-info-circle me-2"></i>Yêu cầu mật khẩu:</strong>
                <ul class="mb-0 mt-2">
                    <li>Tối thiểu 6 ký tự</li>
                    <li>Nên có chữ hoa, chữ thường và số</li>
                    <li>Không trùng với mật khẩu cũ</li>
                </ul>
            </div>

            <form action="<?php echo APP_URL; ?>/Home/changePassword" method="POST" id="changePasswordForm" class="needs-validation" novalidate>
                <!-- Mật khẩu hiện tại -->
                <div class="mb-4">
                    <label for="current_password" class="form-label">
                        <i class="bi bi-lock me-1"></i>Mật khẩu hiện tại *
                    </label>
                    <div class="password-input-wrapper">
                        <input type="password" class="form-control" id="current_password" name="current_password" required>
                        <button type="button" class="toggle-password" onclick="togglePasswordVisibility('current_password')">
                            <i class="bi bi-eye" id="current_password_icon"></i>
                        </button>
                    </div>
                    <div class="invalid-feedback">
                        Vui lòng nhập mật khẩu hiện tại
                    </div>
                </div>

                <!-- Mật khẩu mới -->
                <div class="mb-4">
                    <label for="new_password" class="form-label">
                        <i class="bi bi-key me-1"></i>Mật khẩu mới *
                    </label>
                    <div class="password-input-wrapper">
                        <input type="password" class="form-control" id="new_password" name="new_password" 
                            minlength="6" required oninput="checkPasswordStrength()">
                        <button type="button" class="toggle-password" onclick="togglePasswordVisibility('new_password')">
                            <i class="bi bi-eye" id="new_password_icon"></i>
                        </button>
                    </div>
                    <div id="password_strength" class="password-strength"></div>
                    <small id="password_strength_text" class="text-muted"></small>
                    <div class="invalid-feedback">
                        Mật khẩu phải có ít nhất 6 ký tự
                    </div>
                </div>

                <!-- Xác nhận mật khẩu mới -->
                <div class="mb-4">
                    <label for="confirm_password" class="form-label">
                        <i class="bi bi-key-fill me-1"></i>Xác nhận mật khẩu mới *
                    </label>
                    <div class="password-input-wrapper">
                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                        <button type="button" class="toggle-password" onclick="togglePasswordVisibility('confirm_password')">
                            <i class="bi bi-eye" id="confirm_password_icon"></i>
                        </button>
                    </div>
                    <div class="invalid-feedback" id="confirm_password_error">
                        Vui lòng xác nhận mật khẩu mới
                    </div>
                </div>

                <div class="d-grid gap-2 mt-4">
                    <button type="submit" class="btn btn-primary btn-change-password">
                        <i class="bi bi-shield-check me-2"></i>Đổi mật khẩu
                    </button>
                    <a href="<?php echo APP_URL; ?>/Home/profile" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-2"></i>Quay lại
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Toggle password visibility
function togglePasswordVisibility(fieldId) {
    const field = document.getElementById(fieldId);
    const icon = document.getElementById(fieldId + '_icon');
    
    if (field.type === 'password') {
        field.type = 'text';
        icon.classList.remove('bi-eye');
        icon.classList.add('bi-eye-slash');
    } else {
        field.type = 'password';
        icon.classList.remove('bi-eye-slash');
        icon.classList.add('bi-eye');
    }
}

// Check password strength
function checkPasswordStrength() {
    const password = document.getElementById('new_password').value;
    const strengthBar = document.getElementById('password_strength');
    const strengthText = document.getElementById('password_strength_text');
    
    if (password.length === 0) {
        strengthBar.className = 'password-strength';
        strengthText.textContent = '';
        return;
    }
    
    let strength = 0;
    if (password.length >= 6) strength++;
    if (password.length >= 8) strength++;
    if (/[a-z]/.test(password) && /[A-Z]/.test(password)) strength++;
    if (/\d/.test(password)) strength++;
    if (/[^a-zA-Z\d]/.test(password)) strength++;
    
    if (strength <= 2) {
        strengthBar.className = 'password-strength strength-weak';
        strengthText.textContent = 'Mật khẩu yếu';
        strengthText.style.color = '#e74c3c';
    } else if (strength <= 4) {
        strengthBar.className = 'password-strength strength-medium';
        strengthText.textContent = 'Mật khẩu trung bình';
        strengthText.style.color = '#f39c12';
    } else {
        strengthBar.className = 'password-strength strength-strong';
        strengthText.textContent = 'Mật khẩu mạnh';
        strengthText.style.color = '#27ae60';
    }
}

// Form validation
(function () {
    'use strict'
    
    const form = document.getElementById('changePasswordForm');
    
    form.addEventListener('submit', function (event) {
        const newPassword = document.getElementById('new_password').value;
        const confirmPassword = document.getElementById('confirm_password').value;
        const confirmPasswordField = document.getElementById('confirm_password');
        const confirmPasswordError = document.getElementById('confirm_password_error');
        
        // Check if passwords match
        if (newPassword !== confirmPassword) {
            event.preventDefault();
            event.stopPropagation();
            confirmPasswordField.setCustomValidity('Mật khẩu không khớp');
            confirmPasswordError.textContent = 'Mật khẩu xác nhận không khớp';
        } else {
            confirmPasswordField.setCustomValidity('');
        }
        
        if (!form.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();
        }
        
        form.classList.add('was-validated');
    }, false);
    
    // Reset custom validity when user types
    document.getElementById('confirm_password').addEventListener('input', function() {
        this.setCustomValidity('');
    });
})();
</script>
