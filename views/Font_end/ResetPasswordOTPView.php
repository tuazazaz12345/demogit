<style>
    .otp-container {
        max-width: 500px;
        margin: 80px auto;
        padding: 40px;
        background: white;
        border-radius: 15px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.1);
    }
    
    .otp-header {
        text-align: center;
        margin-bottom: 30px;
    }
    
    .otp-header i {
        font-size: 60px;
        color: #27ae60;
        margin-bottom: 15px;
    }
    
    .otp-header h2 {
        color: #2c3e50;
        font-weight: 700;
        margin-bottom: 10px;
    }
    
    .otp-header p {
        color: #7f8c8d;
        font-size: 14px;
    }
    
    .email-info {
        background: #ecf0f1;
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 25px;
        text-align: center;
    }
    
    .email-info strong {
        color: #2c3e50;
        font-size: 16px;
    }
    
    .otp-input-group {
        display: flex;
        gap: 10px;
        justify-content: center;
        margin: 25px 0;
    }
    
    .otp-input {
        width: 50px;
        height: 60px;
        text-align: center;
        font-size: 24px;
        font-weight: bold;
        border: 2px solid #ecf0f1;
        border-radius: 8px;
        transition: all 0.3s ease;
    }
    
    .otp-input:focus {
        border-color: #27ae60;
        box-shadow: 0 0 0 3px rgba(39, 174, 96, 0.1);
        outline: none;
    }
    
    .otp-timer {
        text-align: center;
        color: #e74c3c;
        font-weight: 600;
        margin: 15px 0;
    }
    
    .btn-verify-otp {
        width: 100%;
        padding: 14px;
        background: linear-gradient(135deg, #27ae60 0%, #229954 100%);
        border: none;
        color: white;
        font-weight: 600;
        border-radius: 8px;
        font-size: 16px;
        transition: all 0.3s ease;
    }
    
    .btn-verify-otp:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(39, 174, 96, 0.4);
    }
    
    .resend-otp {
        text-align: center;
        margin-top: 20px;
    }
    
    .resend-otp button {
        background: none;
        border: none;
        color: #3498db;
        font-weight: 500;
        cursor: pointer;
        text-decoration: underline;
    }
    
    .resend-otp button:disabled {
        color: #95a5a6;
        cursor: not-allowed;
        text-decoration: none;
    }
</style>

<div class="otp-container">
    <div class="otp-header">
        <i class="bi bi-shield-check"></i>
        <h2>Xác thực OTP</h2>
        <p>Vui lòng nhập mã OTP đã được gửi đến email của bạn</p>
    </div>
    
    <div class="email-info">
        <i class="bi bi-envelope-fill me-2"></i>
        <strong><?php echo isset($data['email']) ? htmlspecialchars($data['email']) : ''; ?></strong>
    </div>
    
    <form action="<?php echo APP_URL; ?>/AuthController/verifyResetOTP" method="POST" id="otpForm">
        <div class="otp-input-group">
            <input type="text" class="otp-input" maxlength="1" pattern="[0-9]" required>
            <input type="text" class="otp-input" maxlength="1" pattern="[0-9]" required>
            <input type="text" class="otp-input" maxlength="1" pattern="[0-9]" required>
            <input type="text" class="otp-input" maxlength="1" pattern="[0-9]" required>
            <input type="text" class="otp-input" maxlength="1" pattern="[0-9]" required>
            <input type="text" class="otp-input" maxlength="1" pattern="[0-9]" required>
        </div>
        
        <input type="hidden" name="otp" id="otp_combined">
        
        <div class="otp-timer">
            <i class="bi bi-clock me-1"></i>
            Mã OTP sẽ hết hạn sau: <span id="timer">5:00</span>
        </div>
        
        <button type="submit" class="btn btn-verify-otp">
            <i class="bi bi-check-circle me-2"></i>Xác nhận OTP
        </button>
    </form>
    
    <div class="resend-otp">
        <p class="mb-2">Không nhận được mã?</p>
        <button id="resendBtn" disabled>
            Gửi lại mã (<span id="resendTimer">60</span>s)
        </button>
    </div>
</div>

<script>
// OTP Input handling
const otpInputs = document.querySelectorAll('.otp-input');
const otpCombined = document.getElementById('otp_combined');
const otpForm = document.getElementById('otpForm');

otpInputs.forEach((input, index) => {
    input.addEventListener('input', (e) => {
        if (e.target.value.length === 1) {
            if (index < otpInputs.length - 1) {
                otpInputs[index + 1].focus();
            }
        }
    });
    
    input.addEventListener('keydown', (e) => {
        if (e.key === 'Backspace' && e.target.value === '') {
            if (index > 0) {
                otpInputs[index - 1].focus();
            }
        }
    });
    
    input.addEventListener('paste', (e) => {
        e.preventDefault();
        const pasteData = e.clipboardData.getData('text');
        const digits = pasteData.match(/\d/g);
        
        if (digits) {
            digits.forEach((digit, i) => {
                if (index + i < otpInputs.length) {
                    otpInputs[index + i].value = digit;
                }
            });
            
            const nextIndex = Math.min(index + digits.length, otpInputs.length - 1);
            otpInputs[nextIndex].focus();
        }
    });
});

// Combine OTP before submit
otpForm.addEventListener('submit', (e) => {
    let otp = '';
    otpInputs.forEach(input => {
        otp += input.value;
    });
    otpCombined.value = otp;
});

// Timer countdown (5 minutes)
let timeLeft = 300;
const timerElement = document.getElementById('timer');

const countdown = setInterval(() => {
    timeLeft--;
    const minutes = Math.floor(timeLeft / 60);
    const seconds = timeLeft % 60;
    timerElement.textContent = `${minutes}:${seconds.toString().padStart(2, '0')}`;
    
    if (timeLeft <= 0) {
        clearInterval(countdown);
        alert('Mã OTP đã hết hạn. Vui lòng yêu cầu gửi lại!');
        window.location.href = '<?php echo APP_URL; ?>/AuthController/forgotPassword';
    }
}, 1000);

// Resend OTP countdown
let resendTime = 60;
const resendBtn = document.getElementById('resendBtn');
const resendTimer = document.getElementById('resendTimer');

const resendCountdown = setInterval(() => {
    resendTime--;
    resendTimer.textContent = resendTime;
    
    if (resendTime <= 0) {
        clearInterval(resendCountdown);
        resendBtn.disabled = false;
        resendBtn.textContent = 'Gửi lại mã OTP';
    }
}, 1000);

resendBtn.addEventListener('click', () => {
    if (!resendBtn.disabled) {
        window.location.href = '<?php echo APP_URL; ?>/AuthController/sendResetOTP';
    }
});
</script>
