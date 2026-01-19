Dự án website bán sách là một ứng dụng web thương mại điện tử chuyên nghiệp dành riêng cho việc mua sách được xây dựng bằng kiến ​​trúc MVC sử dụng ngôn ngữ PHP thuần túy. 
Sao chép/Tải xuống dự án: Đặt nó vào htdocsthư mục của XAMPP.
Nhập cơ sở dữ liệu:



Mở PHPMyAdmin.


Tạo một cơ sở dữ liệu có tên là website (6)


Nhập tệp tin database/website (6)


Cấu hình:


Cơ sở dữ liệu & URL: Chỉnh sửa trong app/config/config.php. Lưu ý cập nhật BASE_URLliên kết ngrok của bạn.


Máy chủ thư: Chỉnh sửa app/config/mail_config.phpđể gửi mã OTP/thông báo.


VNPay: Cập nhật thông tin người bán trong vnpay_php/config.php.


Chạy ứng dụng:
Mở XAMPP và khởi động Apache & MySQL.


Sử dụng ngrok: ngrok http 80(nếu cần liên kết công khai).


Truy cập thông qua URL đã được cấu hình.



