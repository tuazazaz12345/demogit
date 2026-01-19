<?php
// ✅ Load PHPMailer autoload
require_once __DIR__ . '/../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class EmailService {
    private $mail;
    private $smtpHost;
    private $smtpUser;
    private $smtpPass;
    private $smtpPort;
    private $fromEmail;
    private $fromName;
    
    public function __construct() {
        $this->mail = new PHPMailer(true);
        
        // 📧 Cấu hình email từ environment variables hoặc hardcode
        // ⚠️ LƯU Ý: Thay đổi các giá trị dưới đây theo email của bạn!
        
        // Có thể cấu hình bằng environment variables:
        // SMTP_HOST=smtp.gmail.com
        // SMTP_USER=your-email@gmail.com  
        // SMTP_PASS=your-app-password
        // SMTP_PORT=587
        
        // Hoặc hardcode ở đây (không an toàn, chỉ để test):
        $this->smtpHost = getenv('SMTP_HOST') ?: 'smtp.gmail.com';
        $this->smtpUser = getenv('SMTP_USER') ?: 'zerohn889@gmail.com';  // ✅ Email của bạn
        $this->smtpPass = getenv('SMTP_PASS') ?: 'rtgm zzto djjy oigp';     // ✅ App password Gmail
        $this->smtpPort = getenv('SMTP_PORT') ?: 587;
        $this->fromEmail = getenv('FROM_EMAIL') ?: 'zerohn889@gmail.com';
        $this->fromName = getenv('FROM_NAME') ?: 'Cửa Hàng Sách';
        
        // Cấu hình PHPMailer
        $this->mail->CharSet = 'UTF-8';
        $this->mail->isSMTP();
        $this->mail->Host = $this->smtpHost;
        $this->mail->SMTPAuth = true;
        $this->mail->Username = $this->smtpUser;
        $this->mail->Password = $this->smtpPass;
        $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $this->mail->Port = $this->smtpPort;
    }
    
    /**
     * Gửi email xác nhận đơn hàng
     * @param string $toEmail - Email người nhận
     * @param string $toName - Tên người nhận
     * @param array $orderData - Dữ liệu đơn hàng
     * @return bool - True nếu gửi thành công
     */
    public function sendOrderConfirmation($toEmail, $toName, $orderData) {
        try {
            // Xóa các người nhận/CC/BCC cũ
            $this->mail->clearAllRecipients();
            $this->mail->clearAddresses();
            $this->mail->clearCCs();
            $this->mail->clearBCCs();
            
            // Thiết lập người gửi
            $this->mail->setFrom($this->fromEmail, $this->fromName);
            
            // Thiết lập người nhận
            $this->mail->addAddress($toEmail, $toName);
            
            // Tiêu đề email
            $this->mail->Subject = 'Xác Nhận Đơn Hàng #' . htmlspecialchars($orderData['order_code']);
            
            // Nội dung HTML
            $htmlContent = $this->buildOrderConfirmationHTML($orderData);
            
            $this->mail->msgHTML($htmlContent);
            $this->mail->AltBody = strip_tags($htmlContent);
            
            // Gửi email
            return $this->mail->send();
        } catch (Exception $e) {
            error_log("Lỗi gửi email xác nhận đơn hàng: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Xây dựng HTML nội dung email xác nhận đơn hàng
     * @param array $orderData - Dữ liệu đơn hàng
     * @return string - HTML content
     */
    private function buildOrderConfirmationHTML($orderData) {
        $orderCode = htmlspecialchars($orderData['order_code'] ?? 'N/A');
        $totalAmount = isset($orderData['total_amount']) ? number_format($orderData['total_amount'], 0, ',', '.') : '0';
        $receiver = htmlspecialchars($orderData['receiver'] ?? '');
        $phone = htmlspecialchars($orderData['phone'] ?? '');
        $address = htmlspecialchars($orderData['address'] ?? '');
        $createdAt = isset($orderData['created_at']) ? date('d/m/Y H:i', strtotime($orderData['created_at'])) : date('d/m/Y H:i');
        
        $html = <<<HTML
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            color: #333;
            background-color: #f5f5f5;
        }
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px 20px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
        }
        .content {
            padding: 30px 20px;
        }
        .section {
            margin-bottom: 25px;
        }
        .section-title {
            font-size: 16px;
            font-weight: bold;
            color: #667eea;
            border-bottom: 2px solid #667eea;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }
        .order-info {
            background-color: #f9f9f9;
            padding: 15px;
            border-radius: 5px;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #eee;
        }
        .info-row:last-child {
            border-bottom: none;
        }
        .info-label {
            font-weight: bold;
            color: #555;
        }
        .info-value {
            color: #333;
        }
        .products-table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }
        .products-table th {
            background-color: #667eea;
            color: white;
            padding: 12px;
            text-align: left;
            border: none;
        }
        .products-table td {
            padding: 12px;
            border-bottom: 1px solid #eee;
        }
        .products-table tr:last-child td {
            border-bottom: none;
        }
        .total-row {
            background-color: #f0f0f0;
            font-weight: bold;
            font-size: 18px;
        }
        .status-badge {
            display: inline-block;
            background-color: #4CAF50;
            color: white;
            padding: 8px 15px;
            border-radius: 20px;
            font-size: 14px;
            margin: 10px 0;
        }
        .footer {
            background-color: #f5f5f5;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #777;
            border-top: 1px solid #eee;
        }
        .button {
            display: inline-block;
            background-color: #667eea;
            color: white;
            padding: 12px 30px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <h1>📦 Xác Nhận Đơn Hàng</h1>
            <p style="margin: 5px 0; font-size: 14px;">Cảm ơn bạn đã đặt hàng tại Book Store!</p>
        </div>
        
        <div class="content">
            <!-- Thông tin đơn hàng -->
            <div class="section">
                <div class="section-title">📋 Thông Tin Đơn Hàng</div>
                <div class="order-info">
                    <div class="info-row">
                        <span class="info-label">Mã Đơn Hàng:</span>
                        <span class="info-value"><strong>$orderCode</strong></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Ngày Đặt:</span>
                        <span class="info-value">$createdAt</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Trạng Thái:</span>
                        <span class="info-value"><span class="status-badge">✓ Đơn Hàng Đã Xác Nhận</span></span>
                    </div>
                </div>
            </div>
            
            <!-- Thông tin người nhận -->
            <div class="section">
                <div class="section-title">📍 Thông Tin Giao Hàng</div>
                <div class="order-info">
                    <div class="info-row">
                        <span class="info-label">Người Nhận:</span>
                        <span class="info-value">$receiver</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Số Điện Thoại:</span>
                        <span class="info-value">$phone</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Địa Chỉ:</span>
                        <span class="info-value">$address</span>
                    </div>
                </div>
            </div>
            
            <!-- Chi tiết sản phẩm -->
            <div class="section">
                <div class="section-title">📦 Chi Tiết Sản Phẩm</div>
                <table class="products-table">
                    <thead>
                        <tr>
                            <th style="width: 40%;">Tên Sản Phẩm</th>
                            <th style="width: 15%;">Số Lượng</th>
                            <th style="width: 20%;">Giá</th>
                            <th style="width: 25%;">Thành Tiền</th>
                        </tr>
                    </thead>
                    <tbody>
HTML;
        
        // Thêm thông tin sản phẩm
        if (isset($orderData['items']) && is_array($orderData['items'])) {
            foreach ($orderData['items'] as $item) {
                $tensp = htmlspecialchars($item['tensp'] ?? 'Sản phẩm');
                $qty = isset($item['qty']) ? intval($item['qty']) : 0;
                $price = isset($item['giaxuat']) ? number_format($item['giaxuat'], 0, ',', '.') : '0';
                $total = isset($item['thanhtien']) ? number_format($item['thanhtien'], 0, ',', '.') : '0';
                
                $html .= <<<HTML
                        <tr>
                            <td>$tensp</td>
                            <td>$qty</td>
                            <td>$price ₫</td>
                            <td>$total ₫</td>
                        </tr>
HTML;
            }
        }
        
        $html .= <<<HTML
                        <tr class="total-row">
                            <td colspan="3">TỔNG CỘNG</td>
                            <td>$totalAmount ₫</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            
            <!-- Các bước tiếp theo -->
            <div class="section">
                <div class="section-title">📌 Các Bước Tiếp Theo</div>
                <ol style="line-height: 1.8;">
                    <li>Chúng tôi sẽ chuẩn bị hàng hóa và liên lạc với bạn trong 24 giờ</li>
                    <li>Bạn sẽ nhận được thông báo vận chuyển khi hàng được giao cho đơn vị logistics</li>
                    <li>Kiểm tra hàng khi nhận và xác nhận với nhân viên giao hàng</li>
                    <li>Nếu có bất kỳ vấn đề gì, vui lòng liên hệ với chúng tôi</li>
                </ol>
            </div>
            
            <!-- Liên hệ -->
            <div class="section">
                <div class="section-title">💬 Hỗ Trợ Khách Hàng</div>
                <p style="line-height: 1.6;">
                    📞 Hotline: 1900 1234<br>
                    📧 Email: support@bookstore.com<br>
                    ⏰ Giờ làm việc: 8:00 - 22:00 (Thứ 2 - Chủ Nhật)<br>
                </p>
            </div>
        </div>
        
        <div class="footer">
            <p>© 2024 Book Store. Tất cả quyền được bảo lưu.</p>
            <p>Email này được gửi tự động. Vui lòng không trả lời.</p>
        </div>
    </div>
</body>
</html>
HTML;
        
        return $html;
    }
    
    /**
     * Gửi email thông báo
     * @param string $toEmail
     * @param string $subject
     * @param string $htmlContent
     * @return bool
     */
    public function sendCustomEmail($toEmail, $subject, $htmlContent) {
        try {
            $this->mail->clearAllRecipients();
            $this->mail->setFrom($this->fromEmail, $this->fromName);
            $this->mail->addAddress($toEmail);
            $this->mail->Subject = $subject;
            $this->mail->msgHTML($htmlContent);
            
            return $this->mail->send();
        } catch (Exception $e) {
            error_log("Lỗi gửi email: " . $e->getMessage());
            return false;
        }
    }
}
?>
