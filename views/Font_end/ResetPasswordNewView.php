<style>
    .reset-container {
        max-width: 500px;
        margin: 80px auto;
        padding: 40px;
        background: white;
        border-radius: 15px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.1);
    }
    
    .reset-header {
        text-align: center;
        margin-bottom: 30px;
    }
    
    .reset-header i {
        font-size: 60px;
        color: #9b59b6;
        margin-bottom: 15px;
    }
    
    .reset-header h2 {
        color: #2c3e50;
        font-weight: 700;
        margin-bottom: 10px;
    }
    
    .reset-header p {
        color: #7f8c8d;
        font-size: 14px;
    }
    
    .password-input-wrapper {
        position: relative;
        margin-bottom: 20px;
    }
    
    .password-input-wrapper input {
        padding-right: 45px;
        padding: 12px 45px 12px 15px;
        border: 2px solid #ecf0f1;
        border-radius: 8px;
        font-size: 15px;
        width: 100%;
    }
    
    .password-input-wrapper input:focus {
        border-color: #9b59b6;
        box-shadow: 0 0 0 3px rgba(155, 89, 182, 0.1);
        outline: none;
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
        border-left: 4px solid #9b59b6;
        padding: 15px;
        margin: 20px 0;
        border-radius: 5px;
        font-size: 13px;
    }
    
    .password-requirements ul {
        margin: 10px 0 0 0;
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
    
    .btn-reset-password {
        width: 100%;
        padding: 14px;
        background: linear-gradient(135deg, #9b59b6 0%, #8e44ad 100%);
        border: none;
        color: white;
        font-weight: 600;
        border-radius: 8px;
        font-size: 16px;
        transition: all 0.3s ease;
    }
    
    .btn-reset-password:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(155, 89, 182, 0.4);
    }
</style>

<div class="reset-container">
    <div class="reset-header">
        <i class="bi bi-lock-fill"></i>
        <h2>Đặt mật khẩu mới</h2>
        <p>Nhập mật khẩu mới cho tài khoản của bạn</p>
    </div>
    
    <div class="password-requirements">
        <strong><i class="bi bi-info-circle me-2"></i>Yêu cầu mật khẩu:</strong>
        <ul class="mb-0 mt-2">
            <li>Tối thiểu 6 ký tự</li>
            <li>Nên có chữ hoa, chữ thường và số</li>
            <li>Tránh sử dụng mật khẩu dễ đoán</li>
        </ul>
    </div>
    
    <form action="<?php echo APP_URL; ?>/AuthController/resetPasswordComplete" method="POST" id="resetPasswordForm" class="needs-validation" novalidate>
        <!-- Mật khẩu mới -->
        <div class="mb-4">
            <label for="new_password" class="form-label fw-bold">
                <i class="bi bi-key me-1"></i>Mật khẩu mới *
            </label>
            <div class="password-input-wrapper">
                <input type="password" 
                       class="form-control" 
                       id="new_password" 
                       name="new_password" 
                       minlength="6" 
                       required 
                       oninput="checkPasswordStrength()">
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
            <label for="confirm_password" class="form-label fw-bold">
                <i class="bi bi-key-fill me-1"></i>Xác nhận mật khẩu mới *
            </label>
            <div class="password-input-wrapper">
                <input type="password" 
                       class="form-control" 
                       id="confirm_password" 
                       name="confirm_password" 
                       required>
                <button type="button" class="toggle-password" onclick="togglePasswordVisibility('confirm_password')">
                    <i class="bi bi-eye" id="confirm_password_icon"></i>
                </button>
            </div>
            <div class="invalid-feedback" id="confirm_password_error">
                Vui lòng xác nhận mật khẩu mới
            </div>
        </div>

        <button type="submit" class="btn btn-reset-password">
            <i class="bi bi-check-circle me-2"></i>Đặt lại mật khẩu
        </button>
    </form>
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
    
    const form = document.getElementById('resetPasswordForm');
    
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
