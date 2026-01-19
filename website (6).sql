-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th12 05, 2025 lúc 09:24 PM
-- Phiên bản máy phục vụ: 10.4.28-MariaDB
-- Phiên bản PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `website`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `chatbox_messages`
--

CREATE TABLE `chatbox_messages` (
  `id` int(11) NOT NULL,
  `user_email` varchar(255) NOT NULL,
  `user_name` varchar(100) DEFAULT NULL,
  `message` text NOT NULL,
  `response_text` longtext DEFAULT NULL,
  `status` enum('pending','responded','closed') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `responded_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `discount_codes`
--

CREATE TABLE `discount_codes` (
  `id` int(11) NOT NULL,
  `code` varchar(50) NOT NULL,
  `description` text DEFAULT NULL,
  `discount_type` enum('percentage','fixed') NOT NULL,
  `discount_value` decimal(10,2) NOT NULL,
  `min_order_value` decimal(10,2) DEFAULT 0.00,
  `max_discount` decimal(10,2) DEFAULT NULL,
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL,
  `usage_limit` int(11) DEFAULT NULL,
  `used_count` int(11) DEFAULT 0,
  `status` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `discount_codes`
--

INSERT INTO `discount_codes` (`id`, `code`, `description`, `discount_type`, `discount_value`, `min_order_value`, `max_discount`, `start_date`, `end_date`, `usage_limit`, `used_count`, `status`, `created_at`) VALUES
(1, 'GIAMGIA2025', 'giamgia', 'percentage', 10.00, 0.00, 0.00, '2025-11-01 01:05:00', '2025-12-07 01:05:00', 100, 0, 1, '2025-11-10 18:10:56'),
(3, 'GIAMGIA', 'giamgia', 'percentage', 10.00, 0.00, 0.00, '2025-11-01 01:05:00', '2025-12-07 01:05:00', 100, 0, 1, '2025-11-10 18:11:47'),
(5, 'KHUYENMAI2025', 'giamgia', 'percentage', 10.00, 0.00, 0.00, '2025-11-01 01:05:00', '2025-12-07 01:05:00', 100, 0, 1, '2025-11-10 18:12:02'),
(7, 'GIAMGIACUCSAU', 'giamgia ok', 'percentage', 10.00, 0.00, 0.00, '2025-11-01 01:05:00', '2025-12-07 01:05:00', 100, 0, 1, '2025-11-10 18:12:23'),
(9, 'KHACH2025', 'KHACH2025', 'percentage', 10.00, 0.00, 0.00, '2025-11-01 01:05:00', '2025-12-07 01:05:00', 100, 0, 1, '2025-11-10 18:12:48'),
(11, 'KHACH202526', 'KHACH2025', 'percentage', 10.00, 0.00, 0.00, '2025-11-01 01:05:00', '2025-12-07 01:05:00', 100, 0, 1, '2025-11-10 18:14:03'),
(13, 'GIASIUUUU', 'ok', 'percentage', 20.00, 0.00, 99999999.99, '2025-11-01 01:16:00', '2025-12-13 01:16:00', 100, 0, 1, '2025-11-10 18:16:45'),
(15, 'SUMMER2025', 'ok', 'percentage', 10.00, 0.00, 0.00, '2025-11-01 01:19:00', '2025-12-28 01:19:00', 100, 0, 1, '2025-11-10 18:19:31');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `feedback`
--

CREATE TABLE `feedback` (
  `id` int(11) NOT NULL,
  `user_email` varchar(100) NOT NULL,
  `fullname` varchar(100) NOT NULL,
  `content` text NOT NULL,
  `status` enum('pending','completed') NOT NULL DEFAULT 'pending',
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `ncc_sanpham`
--

CREATE TABLE `ncc_sanpham` (
  `id` int(11) NOT NULL,
  `ten_ncc` varchar(255) NOT NULL,
  `dia_chi` varchar(500) DEFAULT NULL,
  `dien_thoai` varchar(20) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `han_hop_dong` date DEFAULT NULL,
  `trang_thai` tinyint(1) DEFAULT 1,
  `ngay_tao` timestamp NOT NULL DEFAULT current_timestamp(),
  `ngay_cap_nhat` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `ncc_sanpham`
--

INSERT INTO `ncc_sanpham` (`id`, `ten_ncc`, `dia_chi`, `dien_thoai`, `email`, `han_hop_dong`, `trang_thai`, `ngay_tao`, `ngay_cap_nhat`) VALUES
(1, 'Nhã Nam', '34 võ thị sáu hai bà trưng hà nội', '123456789', 'xmeo2612x@gmail.com', '2025-12-28', 1, '2025-12-04 21:26:17', '2025-12-04 21:26:17');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `news`
--

CREATE TABLE `news` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `image` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `news`
--

INSERT INTO `news` (`id`, `title`, `content`, `image`, `created_at`) VALUES
(1, '\'Nhẹ nhàng mà sống\'', 'Từng là con nghiện để tìm lối thoát hiện thực, Yung Pueblo - tác giả cuốn \"Nhẹ nhàng mà sống\" - nhận ra yêu bản thân là cách để cứu chính mình.\r\n\r\nTác phẩm mở đầu bằng đêm hè năm 2011 khi Diego Perez (tên thật của tác giả) nằm trên sàn nhà, nước mắt tuôn trào vì sợ cơn nghiện sắp tước đi mạng sống. Ở tuổi 23, Perez chợt nhận ra đã phí hoài tuổi trẻ, tiềm năng và sự hy sinh của gia đình. Lần đầu anh hiểu thuốc phiện không giúp quên đi nỗi buồn, chỉ khiến con người tạm quên đau khổ như một cách trốn chạy thực tại. Từ giây phút ấy, Perez quyết tâm thay đổi cuộc đời.\r\n\r\nSau nhiều năm học cách yêu bản thân, Diego Perez bước ra khỏi vùng tối, biến trải nghiệm cá nhân thành câu chuyện truyền động lực. Năm 2022, anh xuất bản quyển Lighter (Nhẹ nhàng mà sống), kể lại hành trình vượt qua giai đoạn đầy tăm tối. Tác giả dùng bút danh Yung Pueblo với ý nghĩa \"những người trẻ tuổi\", cũng dùng nó khi viết nhiều tác phẩm khác.\r\nBản Việt mang tên \"Nhẹ nhàng mà sống\" do dịch giả Lâm Đặng Cam Thảo chuyển ngữ. Nhà xuất bản Dân Trí liên kết First News phát hành tháng 10. Ảnh: First News\r\n\r\nQua từng chương, sách giúp người đọc nhận ra những sai lầm trong việc yêu bản thân, hiểu đúng giá trị của việc chữa lành. Theo Yung Pueblo, những chuyến nghỉ mát hay quà tự mua cũng là hình thức yêu chính mình, nhưng tránh nhầm lẫn với chủ nghĩa vật chất. Thay vì tự huyễn hoặc bằng niềm vui sáo rỗng, tác giả cho rằng mỗi cá nhân cần nuôi dưỡng \"sự trung thực tuyệt đối, khả năng xây dựng thói quen tích cực và thái độ chấp nhận bản thân vô điều kiện\".\r\n\r\n\"Yêu bản thân là không còn nhìn nhận bản thân kém cỏi hơn người khác, đồng thời vẫn giữ được sự khiêm nhường để không thấy mình vượt trội hơn bất cứ ai. Lợi ích lớn nhất của việc yêu bản thân đến từ những tương tác tích cực giữa bạn với chính mình. Yêu bản thân không chỉ là một kiểu tư duy mà còn là một chuỗi hành động\", trích nhận định của tác giả.\r\n\r\nNhưng yêu chính mình vẫn chưa đủ, con người cần đối diện quá khứ để tìm ra gốc rễ khổ đau. Yung Pueblo cũng khuyên độc giả học cách buông bỏ gánh nặng hoặc thói quen không tốt, nhìn đời bằng đôi mắt của hiện tại. Trên con đường tìm kiếm sự bình yên, anh cho biết cuộc sống bước sang trang mới nhờ thiền định. Tuy nhiên, tác giả nhấn mạnh không có công thức chữa lành chung cho tất cả, mỗi người cần tìm một hướng đúng đắn cho riêng mình.\r\nCuối cùng, Yung Pueblo kết luận nếu càng có nhiều người được chữa lành, quyết định của con người càng trắc ẩn hơn. Nhờ đó, thế giới chan hòa tình yêu, mở ra một tương lai tươi sáng cho nhân loại. Trích lời giới thiệu của đơn vị xuất bản trong nước: \"Trong một thế giới cạnh tranh và áp lực như hiện nay, Nhẹ nhàng mà sống cho chúng ta thấy rằng, đôi khi sự nhẹ nhàng cũng là một loại sức mạnh\".\r\n\r\nSách lần lượt nhận 4,7/5 và 4/5 sao trên nền tảng Amazon và Goodreads. Nhiều độc giả quốc tế nhận xét văn phong hấp dẫn, gọi đây là tác phẩm giúp họ thay đổi cuộc đời. Một bạn đọc tại Mỹ bình luận: \"Yung Pueblo mang đến một thông điệp tích cực, đơn giản nhưng có sức chuyển hóa mạnh mẽ. Anh ấy viết bằng thứ ngôn ngữ giản dị nhưng truyền tải nhiều triết lý không thể chối cãi - ít nhất có một số điều trong đó khiến chúng ta suy ngẫm sâu sắc\".\r\n\r\nDiego Perez, 37 tuổi, là một thiền sư và tác giả người Mỹ gốc Ecuador. Theo Amazon, đến nay, anh bán hơn hai triệu quyển, với hơn 25 ngôn ngữ. Phần lớn tác phẩm tập trung vào sức mạnh chữa lành, xây dựng các mối quan hệ lành mạnh nhờ thấu hiểu bản thân. Quyển sách mới nhất của anh - How to Love Better - trở thành sách bán chạy trên bảng xếp hạng của New York Times, sau khi ra mắt ngày 11/3. Hiện tác giả sống cùng vợ tại bang Massachusetts, Mỹ.\r\n\r\nPhương Thảo', 'public/images/news/tải xuống.jpeg', '2025-11-06 18:34:14'),
(2, 'Gen Alpha và áp lực \'học cắm đầu cắm cổ\'', 'Jennifer B. Wallace gọi trẻ em là thế hệ \"học cắm đầu cắm cổ\", chịu nhiều kỳ vọng của người lớn, trong \"Gen Alpha và áp lực thành tích\".\r\n\r\nSách phát hành lần đầu năm 2023, tên gốc Never Enough, ra mắt độc giả Việt năm nay. Đây là kết quả nghiên cứu của tác giả Jennifer Breheny Wallace dựa vào cuộc phỏng vấn với các gia đình, nhà giáo dục và khảo sát gần 6.000 phụ huynh về căng thẳng học tập, rối loạn lo âu, trầm cảm của gen Alpha (thế hệ sinh từ năm 2010-2024).\r\nBìa \"Gen Alpha và áp lực thành tích\", sách do Lê Thanh Sơn dịch, 1980 Books và NXB Công Thương liên kết ấn hành. Ảnh: 1980 Books\r\n\r\nTrong sách, bà nhìn nhận lớp trẻ ngày nay là \"thế hệ học cắm đầu cắm cổ\", áp lực phải thể hiện bản thân, do xã hội ngày càng rộng lớn, thúc đẩy sự bất bình đẳng về thu nhập và thu hẹp cơ hội nghề nghiệp.\r\n\r\n\"Khi thế giới ngày càng trở nên cạnh tranh và chênh vênh hơn, các bậc phụ huynh tin rằng thành công thời thơ ấu - những điểm số, chiếc cúp, lý lịch - là con đường chắc chắn và an toàn nhất để có một cuộc sống trưởng thành hạnh phúc và ổn định\", sách nêu nhận định.\r\n\r\nHọc hành, thể thao và các hoạt động ngoại khóa ngày càng trở nên cạnh tranh, được người lớn dẫn dắt và mang tính đánh cược cao. Wallace chỉ ra \"những đứa trẻ này đang chạy theo một lộ trình đã được vạch sẵn mà không đủ thời gian nghỉ ngơi hay cơ hội để quyết định liệu đó có phải là cuộc đua mà chúng muốn tham gia hay không\". Kết quả, trẻ em tiếp nhận tư tưởng rằng giá trị của chúng phụ thuộc vào thành tích, điểm số GPA, số lượng người theo dõi trên mạng xã hội, thương hiệu trường đại học chứ không phải con người thật.\r\n\r\nNgoài ra, lớn lên trong một cộng đồng nhiều người thành công về mặt vật chất có thể làm tăng áp lực lên trẻ, khiến chúng cảm thấy cần phải có trách nhiệm duy trì vị thế của gia đình.\r\n\r\nSự căng thẳng trong học tập, cạnh tranh không lành mạnh dễ khiến trẻ mắc chứng rối loạn lo âu và trầm cảm. Sách đưa số liệu từ các cuộc khảo sát quốc gia tại Mỹ về thanh thiếu niên, cho thấy sự gia tăng về tỷ lệ mắc một số vấn đề sức khỏe tinh thần. Năm 2019, một phần ba số học sinh trung học và một nửa số nữ sinh được ghi nhận có cảm xúc buồn bã hoặc tuyệt vọng kéo dài. Tác phẩm nêu: \"Một học sinh ở New York nhớ lại đã bật khóc trong lớp 3 vì cô bé nghĩ rằng việc nhận điểm C trong bài kiểm tra toán làm hỏng cơ hội vào Harvard và sống một cuộc sống tốt đẹp\".\r\n\r\nVới câu hỏi làm sao để nuôi dưỡng sự xuất sắc mà không hủy hoại tinh thần con trẻ, tác giả gợi ý các giải pháp thực tiễn cho phụ huynh. Theo đó, cha mẹ cần chăm sóc sức khỏe thể chất và tinh thần của chính mình trước để trở thành nguồn lực cho con, \"không cần thiết phải trở thành hình mẫu hoàn hảo, con trẻ cần một người đủ tốt, yêu thương và dạy chúng yêu bản thân vô điều kiện, chấp nhận khuyết điểm của mình\".\r\n\r\nKhi trẻ đạt điểm kém, phụ huynh nên \"đặt điểm số xấu vào đúng góc nhìn\" bằng cách giải thích bản chất của chúng như một thước đo kiến thức của con ngày hôm đó. Điểm số xấu không quyết định con sẽ thể hiện tốt thế nào trong tương lai, giáo viên thích con nhiều hay ít hoặc cha mẹ coi trọng con như thế nào. Ngoài ra, cần cho trẻ làm việc nhà để có trách nhiệm hơn, dạy con cách nói cảm ơn không chỉ với người thân mà còn học được lòng tốt, sự đồng cảm với người lạ.\r\n\r\nSách đề xuất các giải pháp cho cơ sở giáo dục, giáo viên để giảm thiểu áp lực thành tích độc hại trong trường học. Đó là tổ chức hội thảo về sức khỏe, tinh thần và gắn kết giảng viên, nhân viên với học sinh để trẻ có \"ít nhất một người lớn ở trường mà các em cảm thấy mình có ý nghĩa với họ\", tìm cách khơi dậy tài năng tiềm ẩn từ những học sinh nhút nhát nhất.\r\n\r\nTác phẩm cung cấp nhiều dẫn chứng và giải pháp gần gũi với phụ huynh, giáo viên. Ned Johnson, tác giả cuốn The Self-Driven Child nhận xét: \"Tác giả mang đến một góc nhìn mới mẻ và dễ chịu, nhiều lời khuyên thực tế giúp các bậc cha mẹ nuôi dạy con cái, giúp con đạt được những điều quan trọng\". Amazon bình chọn Gen Alpha và áp lực thành tích là sách hay nhất năm, nằm trong danh sách bán chạy của New York Times.\r\nTác giả Jennifer Breheny Wallace. Ảnh: CNBC\r\n\r\nJennifer Breheny Wallace 53 tuổi, là tác giả, nhà báo, cộng tác tại The Wall Street Journal và The Washington Post. Bà tốt nghiệp đại học Harvard, đồng sáng lập phong trào Mattering về văn hóa coi trọng tại trường học, đồng thời là nghiên cứu viên tại Trung tâm Giao tiếp Phụ huynh và Thanh thiếu niên, bệnh viện Nhi Philadelphia.', 'public/images/news/690d078e65596.jpeg', '2025-11-06 20:39:42');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `order_code` varchar(50) NOT NULL,
  `total_amount` decimal(15,2) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `user_email` varchar(100) DEFAULT NULL,
  `receiver` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `trangthai` enum('chờ xét duyệt','đang giao hàng','đã thanh toán','đã hủy') DEFAULT 'chờ xét duyệt',
  `payment_method` varchar(50) DEFAULT 'Chưa thanh toán',
  `discount_code` varchar(50) DEFAULT NULL,
  `discount_amount` decimal(15,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `order_code`, `total_amount`, `created_at`, `user_email`, `receiver`, `phone`, `address`, `trangthai`, `payment_method`, `discount_code`, `discount_amount`) VALUES
(31, 0, 'HD1759311460', 27000000.00, '2025-10-01 16:37:40', 'vanntphpmailer@gmail.com', 'nguyenvan', '0985639084', 'hà nội', 'chờ xét duyệt', 'Chưa thanh toán', NULL, 0.00),
(32, 0, 'HD1759326771', 27000000.00, '2025-10-01 20:52:51', 'vanntphpmailer@gmail.com', 'nguyenvan', '0985639084', 'hà nội', 'đã thanh toán', 'Chưa thanh toán', NULL, 0.00),
(33, 0, 'HD1760748077', 0.00, '2025-10-18 07:41:17', 'zerohn889@gmail.com', 'Đoàn Đức Phúc', '12345678', '63 ngu', 'đã thanh toán', 'Chưa thanh toán', NULL, 0.00),
(34, 0, 'HD1760754978', 23750000.00, '2025-10-18 09:36:18', 'zerohn889@gmail.com', 'Đoàn Đức Phúc', '12345678', '1233123', 'chờ xét duyệt', 'Chưa thanh toán', NULL, 0.00),
(35, 0, 'HD1760758058', 54000000.00, '2025-10-18 10:27:38', 'zerohn889@gmail.com', 'Đoàn Đức Phúc', '1', '1', 'chờ xét duyệt', 'Chưa thanh toán', NULL, 0.00),
(36, 0, 'HD1760758882', 50750000.00, '2025-10-18 10:41:22', 'zerohn889@gmail.com', 'Đoàn Đức Phúc', '1233', '1233', 'đang giao hàng', 'Chưa thanh toán', NULL, 0.00),
(37, 0, 'HD1760760394', 27000000.00, '2025-10-18 11:06:34', 'zerohn889@gmail.com', 'Đoàn Đức Phúc', '122', '12', 'chờ xét duyệt', 'Chưa thanh toán', NULL, 0.00),
(38, 0, 'HD1760763405', 23750000.00, '2025-10-18 11:56:45', 'zerohn168@gmail.com', 'Đoàn Đức Phúcccc', '123333', '12333', 'đang giao hàng', 'Chưa thanh toán', NULL, 0.00),
(39, 0, 'HD1760763478', 74500000.00, '2025-10-18 11:57:58', 'zerohn168@gmail.com', 'Đoàn Đức Phúcccc', '123333', '333333', 'đang giao hàng', 'Chưa thanh toán', NULL, 0.00),
(40, 0, 'HD1762269057', 100000.00, '2025-11-04 22:10:57', 'zerohn889@gmail.com', 'Đoàn Đức Phúc', '12345678', '12333', 'chờ xét duyệt', 'Chưa thanh toán', NULL, 0.00),
(41, 0, 'HD1762270643', 100000.00, '2025-11-04 22:37:23', 'zerohn889@gmail.com', 'Đoàn Đức Phúc', '12345678', '123', 'chờ xét duyệt', 'Chưa thanh toán', NULL, 0.00),
(42, 0, 'HD1762270796', 100000.00, '2025-11-04 22:39:56', 'zerohn889@gmail.com', 'Đoàn Đức Phúc', '12345678', 'ngu', 'chờ xét duyệt', 'Chưa thanh toán', NULL, 0.00),
(43, 0, 'HD1762271108', 100000.00, '2025-11-04 22:45:08', 'zerohn889@gmail.com', 'Đoàn Đức Phúc', '12345678', 'ngu', 'chờ xét duyệt', 'Chưa thanh toán', NULL, 0.00),
(44, 0, 'HD1762271462', 100000.00, '2025-11-04 22:51:02', 'zerohn889@gmail.com', 'Đoàn Đức Phúc', '12345678', 'ngu', 'chờ xét duyệt', 'Chưa thanh toán', NULL, 0.00),
(45, 0, 'HD1762271578', 100000.00, '2025-11-04 22:52:58', 'zerohn889@gmail.com', 'Đoàn Đức Phúc', '12345678', 'ngu', 'chờ xét duyệt', 'Chưa thanh toán', NULL, 0.00),
(46, 0, 'HD1762271749', 100000.00, '2025-11-04 22:55:49', 'zerohn889@gmail.com', 'Đoàn Đức Phúc', '12345678', 'ngu', 'chờ xét duyệt', 'Chưa thanh toán', NULL, 0.00),
(47, 0, 'HD1762272079', 100000.00, '2025-11-04 23:01:19', 'zerohn889@gmail.com', 'Đoàn Đức Phúc', '12345678', 'ngu', 'chờ xét duyệt', 'Chưa thanh toán', NULL, 0.00),
(48, 0, 'HD1762272099', 100000.00, '2025-11-04 23:01:39', 'zerohn889@gmail.com', 'Đoàn Đức Phúc', '12345678', 'ngu', 'chờ xét duyệt', 'Chưa thanh toán', NULL, 0.00),
(49, 0, 'HD1762272107', 100000.00, '2025-11-04 23:01:47', 'zerohn889@gmail.com', 'Đoàn Đức Phúc', '12345678', 'ngu', 'chờ xét duyệt', 'Chưa thanh toán', NULL, 0.00),
(50, 0, 'HD1762272188', 100000.00, '2025-11-04 23:03:08', 'zerohn889@gmail.com', 'Đoàn Đức Phúc', '12345678', 'ngu', 'chờ xét duyệt', 'Chưa thanh toán', NULL, 0.00),
(51, 0, 'HD1762272194', 100000.00, '2025-11-04 23:03:14', 'zerohn889@gmail.com', 'Đoàn Đức Phúc', '12345678', 'ngu', 'chờ xét duyệt', 'Chưa thanh toán', NULL, 0.00),
(52, 0, 'HD1762272202', 100000.00, '2025-11-04 23:03:22', 'zerohn889@gmail.com', 'Đoàn Đức Phúc', '12345678', 'ngu', 'chờ xét duyệt', 'Chưa thanh toán', NULL, 0.00),
(53, 0, 'HD1762272337', 100000.00, '2025-11-04 23:05:37', 'zerohn889@gmail.com', 'Đoàn Đức Phúc', '1233', '1', 'chờ xét duyệt', 'Chưa thanh toán', NULL, 0.00),
(54, 0, 'HD1762272690', 100000.00, '2025-11-04 23:11:30', 'zerohn889@gmail.com', 'Đoàn Đức Phúc', '1233', '1', 'chờ xét duyệt', 'Chưa thanh toán', NULL, 0.00),
(55, 0, 'HD1762272961', 100000.00, '2025-11-04 23:16:01', 'zerohn889@gmail.com', 'Đoàn Đức Phúc', '1233', '1', 'chờ xét duyệt', 'Chưa thanh toán', NULL, 0.00),
(56, 0, 'HD1762273262', 200000.00, '2025-11-04 23:21:02', 'zerohn889@gmail.com', 'Đoàn Đức Phúc', '12345678', 'ngu', 'chờ xét duyệt', 'Chưa thanh toán', NULL, 0.00),
(57, 0, 'HD1762273425', 200000.00, '2025-11-04 23:23:45', 'zerohn889@gmail.com', 'Đoàn Đức Phúc', '12345678', 'ngu', 'chờ xét duyệt', 'Chưa thanh toán', NULL, 0.00),
(58, 0, 'HD1762273904', 100000.00, '2025-11-04 23:31:44', 'zerohn889@gmail.com', 'Đoàn Đức Phúc', '12345678', 'nguuu', 'chờ xét duyệt', 'Chưa thanh toán', NULL, 0.00),
(59, 0, 'HD1762274403', 100000.00, '2025-11-04 23:40:03', 'zerohn889@gmail.com', 'Đoàn Đức Phúc', '12345678', 'nguuu', 'chờ xét duyệt', 'Chưa thanh toán', NULL, 0.00),
(60, 0, 'HD1762274466', 100000.00, '2025-11-04 23:41:06', 'zerohn889@gmail.com', 'Đoàn Đức Phúc', '12345678', 'nguuu', 'chờ xét duyệt', 'Chưa thanh toán', NULL, 0.00),
(61, 0, 'HD1762275107', 100000.00, '2025-11-04 23:51:47', 'zerohn889@gmail.com', 'Đoàn Đức Phúc', '12345678', 'nguuu', 'chờ xét duyệt', 'Chưa thanh toán', NULL, 0.00),
(62, 0, 'HD1762275591', 100000.00, '2025-11-04 23:59:51', 'zerohn889@gmail.com', 'Đoàn Đức Phúc', '12345678', 'nguuu', 'chờ xét duyệt', 'Chưa thanh toán', NULL, 0.00),
(63, 0, 'HD1762275975', 100000.00, '2025-11-05 00:06:15', 'zerohn889@gmail.com', 'Đoàn Đức Phúc', '12345678', '123', 'chờ xét duyệt', 'Chưa thanh toán', NULL, 0.00),
(64, 0, 'HD1762276194', 100000.00, '2025-11-05 00:09:54', 'zerohn889@gmail.com', 'Đoàn Đức Phúc', '12345678', '12332g', 'chờ xét duyệt', 'Chưa thanh toán', NULL, 0.00),
(65, 0, 'HD1762276424', 100000.00, '2025-11-05 00:13:44', 'zerohn889@gmail.com', 'Đoàn Đức Phúc', '12345678', '123', 'chờ xét duyệt', 'Chưa thanh toán', NULL, 0.00),
(66, 0, 'HD1762276780', 100000.00, '2025-11-05 00:19:40', 'zerohn889@gmail.com', 'Đoàn Đức Phúc', '1233', '123', 'chờ xét duyệt', 'Chưa thanh toán', NULL, 0.00),
(67, 0, 'HD1762277660', 100000.00, '2025-11-05 00:34:20', 'zerohn889@gmail.com', 'Đoàn Đức Phúc', '12345678', '12', 'chờ xét duyệt', 'Chưa thanh toán', NULL, 0.00),
(68, 0, 'HD1762278158', 100000.00, '2025-11-05 00:42:38', 'zerohn889@gmail.com', 'Đoàn Đức Phúc', '12345678', '123', 'chờ xét duyệt', 'Chưa thanh toán', NULL, 0.00),
(69, 0, 'HD1762278353', 100000.00, '2025-11-05 00:45:53', 'zerohn889@gmail.com', 'Đoàn Đức Phúc', '12345', 'ngungungu', 'chờ xét duyệt', 'Chưa thanh toán', NULL, 0.00),
(70, 0, 'HD1762278436', 100000.00, '2025-11-05 00:47:16', 'zerohn889@gmail.com', 'Đoàn Đức Phúc', '123456', 'sfnajsfn', 'chờ xét duyệt', 'Chưa thanh toán', NULL, 0.00),
(71, 0, 'HD1762278732', 100000.00, '2025-11-05 00:52:12', 'zerohn889@gmail.com', 'Đoàn Đức Phúc', '1', 'sfas', 'chờ xét duyệt', 'Chưa thanh toán', NULL, 0.00),
(72, 0, 'HD1762279178', 200000.00, '2025-11-05 00:59:38', 'zerohn889@gmail.com', 'Đoàn Đức Phúc', '1233', 'ádsadasdsad', 'đã thanh toán', 'Đã thanh toán VNPAY', NULL, 0.00),
(73, 0, 'HD1762282665', 900010.00, '2025-11-05 01:57:45', 'zerohn889@gmail.com', 'Đoàn Đức Phúc', '123123', '1321312', 'đã thanh toán', 'Đã thanh toán VNPAY', NULL, 0.00),
(74, 0, 'HD1762282744', 10.00, '2025-11-05 01:59:04', 'zerohn889@gmail.com', 'Đoàn Đức Phúc', '12345678', '1233213', 'chờ xét duyệt', 'Chưa thanh toán', NULL, 0.00),
(75, 0, 'HD1762282791', 100000.00, '2025-11-05 01:59:51', 'zerohn889@gmail.com', 'Đoàn Đức Phúc', '123123', '123', 'chờ xét duyệt', 'Chưa thanh toán', NULL, 0.00),
(76, 0, 'HD1762282851', 100000.00, '2025-11-05 02:00:51', 'zerohn889@gmail.com', 'Đoàn Đức Phúc', '123123', '12312312', 'đã thanh toán', 'Chưa thanh toán', NULL, 0.00),
(77, 0, 'HD1762287931', 100000.00, '2025-11-05 03:25:31', 'zerohn889@gmail.com', 'Đoàn Đức Phúc', '12345678', '123', 'đã hủy', 'Chưa thanh toán', NULL, 0.00),
(78, 0, 'HD1762287943', 100000.00, '2025-11-05 03:25:43', 'zerohn889@gmail.com', 'Đoàn Đức Phúc', '123213123', '1233123', 'đã thanh toán', 'Đã thanh toán VNPAY', NULL, 0.00),
(79, 0, 'HD1762288196', 100000.00, '2025-11-05 03:29:56', 'zerohn889@gmail.com', 'Đoàn Đức Phúc', '123', '123123', 'đã thanh toán', 'Chưa thanh toán', NULL, 0.00),
(80, 0, 'HD1762288406', 100000.00, '2025-11-05 03:33:26', 'zerohn889@gmail.com', 'Đoàn Đức Phúc', '1233', '123', 'đã thanh toán', 'Chưa thanh toán', NULL, 0.00),
(81, 0, 'HD1762289768', 100000.00, '2025-11-05 03:56:08', 'zerohn889@gmail.com', 'Đoàn Đức Phúc', '12321312', '123123', 'đã thanh toán', 'Đã thanh toán VNPAY', NULL, 0.00),
(82, 0, 'HD1762446027', 10.00, '2025-11-06 23:20:27', 'zerohn889@gmail.com', 'Đoàn Đức Phúc', '1234566777', '1233456', 'chờ xét duyệt', 'Chưa thanh toán', NULL, 0.00),
(83, 0, 'HD1762446107', 100010.00, '2025-11-06 23:21:47', 'zerohn889@gmail.com', 'Đoàn Đức Phúc', '12345678', 'fgfgfgf', 'đã thanh toán', 'Đã thanh toán VNPAY', NULL, 0.00),
(84, 0, 'HD1762453432', 200000.00, '2025-11-07 01:23:52', 'zerohn889@gmail.com', 'Đoàn Đức Phúc', '12345678', 'cv', 'đã thanh toán', 'Chưa thanh toán', NULL, 0.00),
(85, 0, 'HD1762453450', 100000.00, '2025-11-07 01:24:10', 'zerohn889@gmail.com', 'Đoàn Đức Phúc', '12345678', 'dfg', 'đã thanh toán', 'Đã thanh toán VNPAY', NULL, 0.00),
(86, 0, 'HD1762460328', 100000.00, '2025-11-07 03:18:48', 'zerohn889@gmail.com', 'Đoàn Đức Phúc', '12334', '1234', 'đã thanh toán', 'Chưa thanh toán', NULL, 0.00),
(87, 0, 'HD1762460357', 100000.00, '2025-11-07 03:19:17', 'zerohn889@gmail.com', 'Đoàn Đức Phúc', '12345678', '123', 'đã thanh toán', 'Đã thanh toán VNPAY', NULL, 0.00),
(88, 0, 'HD1762462195', 100000.00, '2025-11-07 03:49:55', 'zerohn889@gmail.com', 'Đoàn Đức Phúc', '12345678', 'mki', 'đã thanh toán', 'Đã thanh toán VNPAY', NULL, 0.00),
(89, 0, 'HD1762568306', 100000.00, '2025-11-08 09:18:26', 'zerohn889@gmail.com', 'Đoàn Đức Phúc', '123', '12321', 'đã thanh toán', 'Đã thanh toán VNPAY', NULL, 0.00),
(90, 0, 'HD1762600845', 100000.00, '2025-11-08 18:20:45', 'zerohn889@gmail.com', 'Vũ Quang Long', '0867846211', 'avdvxc', 'chờ xét duyệt', 'Chưa thanh toán', NULL, 0.00),
(91, 0, 'HD1762601227', 100000.00, '2025-11-08 18:27:07', 'zerohn889@gmail.com', 'Lê A', '0867846211', 'scvazvab', 'chờ xét duyệt', 'Chưa thanh toán', NULL, 0.00),
(92, 0, 'HD1762601387', 100000.00, '2025-11-08 18:29:47', 'zerohn889@gmail.com', 'Đoàn Đức Phúc', '0867846211', 'CCCAC', 'đã thanh toán', 'Chưa thanh toán', NULL, 0.00),
(93, 0, 'HD1762628359', 100000.00, '2025-11-09 01:59:19', 'zerohn889@gmail.com', 'Đoàn Đức Phúc', '12312300', 'okoko', 'chờ xét duyệt', 'Chưa thanh toán', NULL, 0.00),
(94, 0, 'HD1762628373', 100000.00, '2025-11-09 01:59:33', 'zerohn889@gmail.com', 'Đoàn Đức Phúc', '12345678', 'okoko', 'đã thanh toán', 'Đã thanh toán VNPAY', NULL, 0.00),
(95, 0, 'HD1762792431', 180000.00, '2025-11-10 23:33:51', 'zerohn889@gmail.com', 'Đoàn Đức Phúc', '123123', 'koki', 'đã thanh toán', 'Đã thanh toán VNPAY', NULL, 0.00),
(96, 0, 'HD1762792552', 360000.00, '2025-11-10 23:35:52', 'zerohn889@gmail.com', 'Đoàn Đức Phúc', '123333', 'oki', 'chờ xét duyệt', 'Chưa thanh toán', NULL, 0.00),
(97, 0, 'HD1762799118', 180000.00, '2025-11-11 01:25:18', 'zerohn889@gmail.com', 'Đoàn Đức Phúc', '123123', '123123', 'chờ xét duyệt', 'Chưa thanh toán', NULL, 0.00),
(98, 0, 'HD1762800108', 300000.00, '2025-11-11 01:41:48', 'zerohn889@gmail.com', 'Đoàn Đức Phúc', '12321', '123123', 'chờ xét duyệt', 'Chưa thanh toán', NULL, 0.00),
(99, 0, 'HD1762800146', 300000.00, '2025-11-11 01:42:26', 'zerohn889@gmail.com', 'Đoàn Đức Phúc', '123', '123123', 'chờ xét duyệt', 'Chưa thanh toán', NULL, 0.00),
(100, 0, 'HD1762800229', 300000.00, '2025-11-11 01:43:49', 'zerohn889@gmail.com', 'Đoàn Đức Phúc', '100000', 'ádsdsa', 'chờ xét duyệt', 'Chưa thanh toán', NULL, 0.00),
(101, 0, 'HD1762800243', 300000.00, '2025-11-11 01:44:03', 'zerohn889@gmail.com', 'Đoàn Đức Phúc', '100000', 'ádsdsa', 'chờ xét duyệt', 'Chưa thanh toán', NULL, 0.00),
(102, 0, 'HD1762800601', 300000.00, '2025-11-11 01:50:01', 'zerohn889@gmail.com', 'Đoàn Đức Phúc', 'ghu', 'ádsd', 'chờ xét duyệt', 'Chưa thanh toán', '', 0.00),
(103, 0, 'HD1762800754', 300000.00, '2025-11-11 01:52:34', 'zerohn889@gmail.com', 'Đoàn Đức Phúc', '123', 'as', 'chờ xét duyệt', 'Chưa thanh toán', '', 0.00),
(104, 0, 'HD1762800915', 300000.00, '2025-11-11 01:55:15', 'zerohn889@gmail.com', 'Đoàn Đức Phúc', '10192', 'as', 'chờ xét duyệt', 'Chưa thanh toán', '', 0.00),
(105, 0, 'HD1762801062', 300000.00, '2025-11-11 01:57:42', 'zerohn889@gmail.com', 'Đoàn Đức Phúc', '122222', 'ádsad', 'chờ xét duyệt', 'Chưa thanh toán', '', 0.00),
(106, 0, 'HD1763041525', 100000.00, '2025-11-13 20:45:25', 'zerohn889@gmail.com', 'Đoàn Đức Phúc', '12345678', 'assd', 'chờ xét duyệt', 'Chưa thanh toán', '', 0.00),
(107, 0, 'HD1763041563', 100000.00, '2025-11-13 20:46:03', 'zerohn889@gmail.com', 'Đoàn Đức Phúc', '12345678', 'assd', 'chờ xét duyệt', 'Chưa thanh toán', '', 0.00),
(108, 0, 'HD1763042112', 200000.00, '2025-11-13 20:55:12', 'zerohn889@gmail.com', 'Đoàn Đức Phúc', '123213', 'asd', 'chờ xét duyệt', 'Chưa thanh toán', '', 0.00),
(109, 0, 'HD1763043487', 200000.00, '2025-11-13 21:18:07', 'zerohn889@gmail.com', 'Đoàn Đức Phúc', '1', 'kok', 'chờ xét duyệt', 'Chưa thanh toán', '', 0.00),
(110, 0, 'HD1763043549', 200000.00, '2025-11-13 21:19:09', 'zerohn889@gmail.com', 'Đoàn Đức Phúc', '1', 'kok', 'chờ xét duyệt', 'Chưa thanh toán', '', 0.00),
(111, 0, 'HD1763047816', 100000.00, '2025-11-13 22:30:16', 'zerohn889@gmail.com', 'Đoàn Đức Phúc', '12312', 'ádasd', 'chờ xét duyệt', 'Chưa thanh toán', '', 0.00),
(112, 0, 'HD1763048420', 100000.00, '2025-11-13 22:40:20', 'zerohn889@gmail.com', 'Đoàn Đức Phúc', '1233', 'ádasd', 'chờ xét duyệt', 'Chưa thanh toán', '', 0.00),
(113, 0, 'HD1763103991', 100000.00, '2025-11-14 14:06:31', 'zerohn889@gmail.com', 'Đoàn Đức Phúc', '1233', 'asd', 'đã thanh toán', 'Đã thanh toán VNPAY', '', 0.00),
(114, 0, 'HD1764864639', 100000.00, '2025-12-04 23:10:39', 'zerohn889@gmail.com', 'Đoàn Đức Phúc', '12345678', '123123', 'chờ xét duyệt', 'Chưa thanh toán', '', 0.00),
(115, 0, 'HD1764864737', 100000.00, '2025-12-04 23:12:17', 'zerohn889@gmail.com', 'Đoàn Đức Phúc', '12345678', '123123', 'chờ xét duyệt', 'Chưa thanh toán', '', 0.00),
(116, 0, 'HD1764864776', 100000.00, '2025-12-04 23:12:56', 'zerohn889@gmail.com', 'Đoàn Đức Phúc', '12345678', '123123', 'chờ xét duyệt', 'Chưa thanh toán', '', 0.00),
(117, 0, 'HD1764865153', 180000.00, '2025-12-04 23:19:13', 'zerohn889@gmail.com', 'Đoàn Đức Phúc', '12345678', '123123', 'đã thanh toán', 'Chưa thanh toán', '', 0.00),
(118, 0, 'HD1764865564', 180000.00, '2025-12-04 23:26:04', 'zerohn889@gmail.com', 'Đoàn Đức Phúc', '123', '123123', 'chờ xét duyệt', 'Chưa thanh toán', '', 0.00),
(119, 0, 'HD1764865701', 360000.00, '2025-12-04 23:28:21', 'zerohn889@gmail.com', 'Đoàn Đức Phúc', '12345678', '123123', 'đã thanh toán', 'Chưa thanh toán', '', 0.00),
(120, 0, 'HD1764869976', 100000.00, '2025-12-05 00:39:36', 'zerohn889@gmail.com', 'Đoàn Đức Phúc', '123321', '123312', 'chờ xét duyệt', 'Chưa thanh toán', '', 0.00),
(121, 0, 'HD1764878543', 100000.00, '2025-12-05 03:02:23', 'zerohn889@gmail.com', 'Đoàn Đức Phúc', 'sdasd', '12313', 'chờ xét duyệt', 'Chưa thanh toán', '', 0.00),
(122, 0, 'HD1764879830', 400000.00, '2025-12-05 03:23:50', 'zerohn889@gmail.com', 'Đoàn Đức Phúc', '123123', '123123', 'chờ xét duyệt', 'Chưa thanh toán', '', 0.00),
(123, 0, 'HD1764880232', 700000.00, '2025-12-05 03:30:32', 'zerohn889@gmail.com', 'Đoàn Đức Phúc', '123', '123', 'chờ xét duyệt', 'Chưa thanh toán', '', 0.00);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `order_details`
--

CREATE TABLE `order_details` (
  `id` int(11) NOT NULL,
  `order_id` varchar(20) NOT NULL,
  `product_id` varchar(50) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(15,2) NOT NULL,
  `sale_price` decimal(15,2) NOT NULL,
  `total` decimal(15,2) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `product_type` varchar(100) DEFAULT NULL,
  `product_name` varchar(255) DEFAULT NULL,
  `order_status` varchar(50) DEFAULT 'Chờ xử lý'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `order_details`
--

INSERT INTO `order_details` (`id`, `order_id`, `product_id`, `quantity`, `price`, `sale_price`, `total`, `image`, `product_type`, `product_name`, `order_status`) VALUES
(35, '32', 'Iphone16', 1, 30000000.00, 27000000.00, 27000000.00, 'iphone17_1.png', '', 'Iphone 16 ', 'Chờ xử lý'),
(36, '33', '003', 1, 0.00, 0.00, 0.00, '4', '', 'đt', 'Chờ xử lý'),
(37, '34', 'Iphone15', 1, 25000000.00, 23750000.00, 23750000.00, 'iphone14.png', '', 'Iphone 15 Promax', 'Chờ xử lý'),
(38, '35', 'Iphone16', 1, 30000000.00, 27000000.00, 27000000.00, 'iphone17_1.png', '', 'Iphone 16 ', 'Chờ xử lý'),
(39, '35', 'Iphone17', 1, 30000000.00, 27000000.00, 27000000.00, 'onway.png', '', 'Iphone 17', 'Chờ xử lý'),
(40, '36', 'Iphone15', 1, 25000000.00, 23750000.00, 23750000.00, 'iphone14.png', '', 'Iphone 15 Promax', 'Chờ xử lý'),
(41, '36', 'Iphone16', 1, 30000000.00, 27000000.00, 27000000.00, 'iphone17_1.png', '', 'Iphone 16 ', 'Chờ xử lý'),
(42, '37', 'Iphone16', 1, 30000000.00, 27000000.00, 27000000.00, 'iphone17_1.png', '', 'Iphone 16 ', 'Chờ xử lý'),
(43, '38', 'Iphone15', 1, 25000000.00, 23750000.00, 23750000.00, 'iphone14.png', '', 'Iphone 15 Promax', 'Chờ xử lý'),
(44, '39', 'Iphone15', 2, 25000000.00, 23750000.00, 47500000.00, 'iphone14.png', '', 'Iphone 15 Promax', 'Chờ xử lý'),
(45, '39', 'Iphone16', 1, 30000000.00, 27000000.00, 27000000.00, 'iphone17_1.png', '', 'Iphone 16 ', 'Chờ xử lý'),
(46, '40', '1', 1, 100000.00, 100000.00, 100000.00, 'Screenshot 2025-11-02 000451.png', '', 'ngu', 'Chờ xử lý'),
(47, '41', '1', 1, 100000.00, 100000.00, 100000.00, 'Screenshot 2025-11-02 000451.png', '', 'ngu', 'Chờ xử lý'),
(48, '42', '1', 1, 100000.00, 100000.00, 100000.00, 'Screenshot 2025-11-02 000451.png', '', 'ngu', 'Chờ xử lý'),
(49, '43', '1', 1, 100000.00, 100000.00, 100000.00, 'Screenshot 2025-11-02 000451.png', '', 'ngu', 'Chờ xử lý'),
(50, '44', '1', 1, 100000.00, 100000.00, 100000.00, 'Screenshot 2025-11-02 000451.png', '', 'ngu', 'Chờ xử lý'),
(51, '45', '1', 1, 100000.00, 100000.00, 100000.00, 'Screenshot 2025-11-02 000451.png', '', 'ngu', 'Chờ xử lý'),
(52, '46', '1', 1, 100000.00, 100000.00, 100000.00, 'Screenshot 2025-11-02 000451.png', '', 'ngu', 'Chờ xử lý'),
(53, '47', '1', 1, 100000.00, 100000.00, 100000.00, 'Screenshot 2025-11-02 000451.png', '', 'ngu', 'Chờ xử lý'),
(54, '48', '1', 1, 100000.00, 100000.00, 100000.00, 'Screenshot 2025-11-02 000451.png', '', 'ngu', 'Chờ xử lý'),
(55, '49', '1', 1, 100000.00, 100000.00, 100000.00, 'Screenshot 2025-11-02 000451.png', '', 'ngu', 'Chờ xử lý'),
(56, '50', '1', 1, 100000.00, 100000.00, 100000.00, 'Screenshot 2025-11-02 000451.png', '', 'ngu', 'Chờ xử lý'),
(57, '51', '1', 1, 100000.00, 100000.00, 100000.00, 'Screenshot 2025-11-02 000451.png', '', 'ngu', 'Chờ xử lý'),
(58, '52', '1', 1, 100000.00, 100000.00, 100000.00, 'Screenshot 2025-11-02 000451.png', '', 'ngu', 'Chờ xử lý'),
(59, '53', '1', 1, 100000.00, 100000.00, 100000.00, 'Screenshot 2025-11-02 000451.png', '', 'ngu', 'Chờ xử lý'),
(60, '54', '1', 1, 100000.00, 100000.00, 100000.00, 'Screenshot 2025-11-02 000451.png', '', 'ngu', 'Chờ xử lý'),
(61, '55', '1', 1, 100000.00, 100000.00, 100000.00, 'Screenshot 2025-11-02 000451.png', '', 'ngu', 'Chờ xử lý'),
(62, '56', '1', 2, 100000.00, 100000.00, 200000.00, 'Screenshot 2025-11-02 000451.png', '', 'ngu', 'Chờ xử lý'),
(63, '57', '1', 2, 100000.00, 100000.00, 200000.00, 'Screenshot 2025-11-02 000451.png', '', 'ngu', 'Chờ xử lý'),
(64, '58', '1', 1, 100000.00, 100000.00, 100000.00, 'Screenshot 2025-11-02 000451.png', '', 'ngu', 'Chờ xử lý'),
(65, '59', '1', 1, 100000.00, 100000.00, 100000.00, 'Screenshot 2025-11-02 000451.png', '', 'ngu', 'Chờ xử lý'),
(66, '60', '1', 1, 100000.00, 100000.00, 100000.00, 'Screenshot 2025-11-02 000451.png', '', 'ngu', 'Chờ xử lý'),
(67, '61', '1', 1, 100000.00, 100000.00, 100000.00, 'Screenshot 2025-11-02 000451.png', '', 'ngu', 'Chờ xử lý'),
(68, '62', '1', 1, 100000.00, 100000.00, 100000.00, 'Screenshot 2025-11-02 000451.png', '', 'ngu', 'Chờ xử lý'),
(69, '63', '1', 1, 100000.00, 100000.00, 100000.00, 'Screenshot 2025-11-02 000451.png', '', 'ngu', 'Chờ xử lý'),
(70, '64', '1', 1, 100000.00, 100000.00, 100000.00, 'Screenshot 2025-11-02 000451.png', '', 'ngu', 'Chờ xử lý'),
(71, '65', '1', 1, 100000.00, 100000.00, 100000.00, 'Screenshot 2025-11-02 000451.png', '', 'ngu', 'Chờ xử lý'),
(72, '66', '1', 1, 100000.00, 100000.00, 100000.00, 'Screenshot 2025-11-02 000451.png', '', 'ngu', 'Chờ xử lý'),
(73, '67', '1', 1, 100000.00, 100000.00, 100000.00, 'Screenshot 2025-11-02 000451.png', '', 'ngu', 'Chờ xử lý'),
(74, '68', '1', 1, 100000.00, 100000.00, 100000.00, 'Screenshot 2025-11-02 000451.png', '', 'ngu', 'Chờ xử lý'),
(75, '69', '1', 1, 100000.00, 100000.00, 100000.00, 'Screenshot 2025-11-02 000451.png', '', 'ngu', 'Chờ xử lý'),
(76, '70', '1', 1, 100000.00, 100000.00, 100000.00, 'Screenshot 2025-11-02 000451.png', '', 'ngu', 'Chờ xử lý'),
(77, '71', '1', 1, 100000.00, 100000.00, 100000.00, 'Screenshot 2025-11-02 000451.png', '', 'ngu', 'Chờ xử lý'),
(78, '72', '1', 2, 100000.00, 100000.00, 200000.00, 'Screenshot 2025-11-02 000451.png', '', 'ngu', 'Chờ xử lý'),
(79, '73', '1', 9, 100000.00, 100000.00, 900000.00, 'Screenshot 2025-11-02 000451.png', '', 'ngu', 'Chờ xử lý'),
(80, '73', '2', 1, 10.00, 10.00, 10.00, 'Screenshot 2025-10-21 193020.png', '', 'nguck', 'Chờ xử lý'),
(81, '74', '2', 1, 10.00, 10.00, 10.00, 'Screenshot 2025-10-21 193020.png', '', 'nguck', 'Chờ xử lý'),
(82, '75', '1', 1, 100000.00, 100000.00, 100000.00, 'Screenshot 2025-11-02 000451.png', '', 'ngu', 'Chờ xử lý'),
(83, '76', '1', 1, 100000.00, 100000.00, 100000.00, 'Screenshot 2025-11-02 000451.png', '', 'ngu', 'Chờ xử lý'),
(84, '77', '1', 1, 100000.00, 100000.00, 100000.00, 'Screenshot 2025-11-02 000451.png', '', 'ngu', 'Chờ xử lý'),
(85, '78', '1', 1, 100000.00, 100000.00, 100000.00, 'Screenshot 2025-11-02 000451.png', '', 'ngu', 'Chờ xử lý'),
(86, '79', '1', 1, 100000.00, 100000.00, 100000.00, 'Screenshot 2025-11-02 000451.png', '', 'ngu', 'Chờ xử lý'),
(87, '80', '4', 1, 100000.00, 100000.00, 100000.00, 'Screenshot 2025-10-21 201230.png', '', 'ngu123', 'Chờ xử lý'),
(88, '81', '4', 1, 100000.00, 100000.00, 100000.00, 'Screenshot 2025-10-21 201230.png', '', 'ngu123', 'Chờ xử lý'),
(89, '82', '2', 1, 10.00, 10.00, 10.00, 'Screenshot 2025-10-21 193020.png', '', 'nguck', 'Chờ xử lý'),
(90, '83', '2', 1, 10.00, 10.00, 10.00, 'Screenshot 2025-10-21 193020.png', '', 'nguck', 'Chờ xử lý'),
(91, '83', '1', 1, 100000.00, 100000.00, 100000.00, 'Screenshot 2025-11-02 000451.png', '', 'ngu', 'Chờ xử lý'),
(92, '84', '1', 2, 100000.00, 100000.00, 200000.00, 'Screenshot 2025-11-02 000451.png', '', 'ngu', 'Chờ xử lý'),
(93, '85', '1', 1, 100000.00, 100000.00, 100000.00, 'Screenshot 2025-11-02 000451.png', '', 'ngu', 'Chờ xử lý'),
(94, '86', '7', 1, 100000.00, 100000.00, 100000.00, 'Screenshot 2025-11-07 025655.png', '', 'Làm Ra Làm, Chơi Ra ', 'Chờ xử lý'),
(95, '87', '7', 1, 100000.00, 100000.00, 100000.00, 'Screenshot 2025-11-07 025655.png', '', 'Làm Ra Làm, Chơi Ra ', 'Chờ xử lý'),
(96, '88', '7', 1, 100000.00, 100000.00, 100000.00, 'Screenshot 2025-11-07 025655.png', '', 'Làm Ra Làm, Chơi Ra ', 'Chờ xử lý'),
(97, '89', '7', 1, 100000.00, 100000.00, 100000.00, 'Screenshot 2025-11-07 025655.png', '', 'Làm Ra Làm, Chơi Ra ', 'Chờ xử lý'),
(98, '90', '7', 1, 100000.00, 100000.00, 100000.00, 'Screenshot 2025-11-07 025655.png', '', 'Làm Ra Làm, Chơi Ra ', 'Chờ xử lý'),
(99, '91', '7', 1, 100000.00, 100000.00, 100000.00, 'Screenshot 2025-11-07 025655.png', '', 'Làm Ra Làm, Chơi Ra ', 'Chờ xử lý'),
(100, '92', '7', 1, 100000.00, 100000.00, 100000.00, 'Screenshot 2025-11-07 025655.png', '', 'Làm Ra Làm, Chơi Ra ', 'Chờ xử lý'),
(101, '93', '7', 1, 100000.00, 100000.00, 100000.00, 'Screenshot 2025-11-07 025655.png', '', 'Làm Ra Làm, Chơi Ra ', 'Chờ xử lý'),
(102, '94', '7', 1, 100000.00, 100000.00, 100000.00, 'Screenshot 2025-11-07 025655.png', '', 'Làm Ra Làm, Chơi Ra ', 'Chờ xử lý'),
(103, '95', '8', 1, 200000.00, 180000.00, 180000.00, 'anh-sach-15574716905351357655289.jpg', '', 'Sách Người Hùng Ý Tư', 'Chờ xử lý'),
(104, '96', '8', 2, 200000.00, 180000.00, 360000.00, 'anh-sach-15574716905351357655289.jpg', '', 'Sách Người Hùng Ý Tư', 'Chờ xử lý'),
(105, '97', '8', 1, 200000.00, 180000.00, 180000.00, 'anh-sach-15574716905351357655289.jpg', '', 'Sách Người Hùng Ý Tư', 'Chờ xử lý'),
(106, '98', '200', 1, 300000.00, 300000.00, 300000.00, '1743619892666090_vi_20250412224745.png', '', '123', 'Chờ xử lý'),
(107, '99', '200', 1, 300000.00, 300000.00, 300000.00, '1743619892666090_vi_20250412224745.png', '', '123', 'Chờ xử lý'),
(108, '100', '200', 1, 300000.00, 300000.00, 300000.00, '1743619892666090_vi_20250412224745.png', '', '123', 'Chờ xử lý'),
(109, '101', '200', 1, 300000.00, 300000.00, 300000.00, '1743619892666090_vi_20250412224745.png', '', '123', 'Chờ xử lý'),
(110, '102', '200', 1, 300000.00, 300000.00, 300000.00, '1743619892666090_vi_20250412224745.png', '', '123', 'Chờ xử lý'),
(111, '103', '200', 1, 300000.00, 300000.00, 300000.00, '1743619892666090_vi_20250412224745.png', '', '123', 'Chờ xử lý'),
(112, '104', '200', 1, 300000.00, 300000.00, 300000.00, '1743619892666090_vi_20250412224745.png', '', '123', 'Chờ xử lý'),
(113, '105', '200', 1, 300000.00, 300000.00, 300000.00, '1743619892666090_vi_20250412224745.png', '', '123', 'Chờ xử lý'),
(114, '106', '7', 1, 100000.00, 100000.00, 100000.00, 'Screenshot 2025-11-07 025655.png', '', 'Làm Ra Làm, Chơi Ra ', 'Chờ xử lý'),
(115, '107', '7', 1, 100000.00, 100000.00, 100000.00, 'Screenshot 2025-11-07 025655.png', '', 'Làm Ra Làm, Chơi Ra ', 'Chờ xử lý'),
(116, '108', '7', 2, 100000.00, 100000.00, 200000.00, 'Screenshot 2025-11-07 025655.png', '', 'Làm Ra Làm, Chơi Ra ', 'Chờ xử lý'),
(117, '109', '7', 2, 100000.00, 100000.00, 200000.00, 'Screenshot 2025-11-07 025655.png', '', 'Làm Ra Làm, Chơi Ra ', 'Chờ xử lý'),
(118, '110', '7', 2, 100000.00, 100000.00, 200000.00, 'Screenshot 2025-11-07 025655.png', '', 'Làm Ra Làm, Chơi Ra ', 'Chờ xử lý'),
(119, '111', '7', 1, 100000.00, 100000.00, 100000.00, 'Screenshot 2025-11-07 025655.png', '', 'Làm Ra Làm, Chơi Ra ', 'Chờ xử lý'),
(120, '112', '7', 1, 100000.00, 100000.00, 100000.00, 'Screenshot 2025-11-07 025655.png', '', 'Làm Ra Làm, Chơi Ra ', 'Chờ xử lý'),
(121, '113', '7', 1, 100000.00, 100000.00, 100000.00, 'Screenshot 2025-11-07 025655.png', '', 'Làm Ra Làm, Chơi Ra ', 'Chờ xử lý'),
(122, '114', '7', 1, 100000.00, 100000.00, 100000.00, 'Screenshot 2025-11-07 025655.png', '', 'Làm Ra Làm, Chơi Ra ', 'Chờ xử lý'),
(123, '115', '7', 1, 100000.00, 100000.00, 100000.00, 'Screenshot 2025-11-07 025655.png', '', 'Làm Ra Làm, Chơi Ra ', 'Chờ xử lý'),
(124, '116', '7', 1, 100000.00, 100000.00, 100000.00, 'Screenshot 2025-11-07 025655.png', '', 'Làm Ra Làm, Chơi Ra ', 'Chờ xử lý'),
(125, '117', '8', 1, 200000.00, 180000.00, 180000.00, 'anh-sach-15574716905351357655289.jpg', '', 'Sách Người Hùng Ý Tư', 'Chờ xử lý'),
(126, '118', '8', 1, 200000.00, 180000.00, 180000.00, 'anh-sach-15574716905351357655289.jpg', '', 'Sách Người Hùng Ý Tư', 'Chờ xử lý'),
(127, '119', '8', 2, 200000.00, 180000.00, 360000.00, 'anh-sach-15574716905351357655289.jpg', '', 'Sách Người Hùng Ý Tư', 'Chờ xử lý'),
(128, '120', '7', 1, 100000.00, 100000.00, 100000.00, 'Screenshot 2025-11-07 025655.png', '', 'Làm Ra Làm, Chơi Ra ', 'Chờ xử lý'),
(129, '121', '7', 1, 100000.00, 100000.00, 100000.00, 'Screenshot 2025-11-07 025655.png', '', 'Làm Ra Làm, Chơi Ra ', 'Chờ xử lý'),
(130, '122', '7', 1, 100000.00, 100000.00, 100000.00, 'Screenshot 2025-11-07 025655.png', '', 'Làm Ra Làm, Chơi Ra ', 'Chờ xử lý'),
(131, '122', '200', 1, 300000.00, 300000.00, 300000.00, '1743619892666090_vi_20250412224745.png', '', '123', 'Chờ xử lý'),
(132, '123', '7', 1, 100000.00, 100000.00, 100000.00, 'Screenshot 2025-11-07 025655.png', '', 'Làm Ra Làm, Chơi Ra ', 'Chờ xử lý'),
(133, '123', '200', 2, 300000.00, 300000.00, 600000.00, '1743619892666090_vi_20250412224745.png', '', '123', 'Chờ xử lý');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `permissions`
--

CREATE TABLE `permissions` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `resource` varchar(50) NOT NULL,
  `action` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `product_promotions`
--

CREATE TABLE `product_promotions` (
  `id` int(11) NOT NULL,
  `product_id` varchar(50) NOT NULL,
  `promotion_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `product_promotions`
--

INSERT INTO `product_promotions` (`id`, `product_id`, `promotion_id`) VALUES
(1, '7', 2),
(2, '7', 2);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `promotions`
--

CREATE TABLE `promotions` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `discount_percent` decimal(5,2) NOT NULL,
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL,
  `status` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `promotions`
--

INSERT INTO `promotions` (`id`, `name`, `description`, `discount_percent`, `start_date`, `end_date`, `status`, `created_at`) VALUES
(2, 'GIAMGIA2025', 'giamgia', 10.00, '2025-11-07 01:04:00', '2025-11-30 01:04:00', 1, '2025-11-10 18:04:42');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tbladmin`
--

CREATE TABLE `tbladmin` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(150) DEFAULT NULL,
  `fullname` varchar(150) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `tbladmin`
--

INSERT INTO `tbladmin` (`id`, `username`, `password`, `email`, `fullname`, `created_at`) VALUES
(1, 'admin', '$2y$10$EBInMmfBuPlS91vDHi8wwuWOOYruFdhHMZCTpR6JqNDH9VfJ/nHyO', 'zerohn889@gmail.com', 'Đoàn Đức Phúc', '2025-10-18 09:10:04'),
(2, 'admin1', '$2y$10$JPUJtWVPvqTuKsd4D7sqAei2uZahhhTpodFc.xbnq9WcxYRNDB5se', 'zerohn889@gmail.com', 'admin1', '2025-10-18 09:10:57'),
(3, 'admin2', '$2y$10$wlMk0fKrc25nYYjpR4cFbuNGjBzEOTDYUsqui0x7buzQWFhO.6psW', 'zerohn889@gmail.com', 'Đoàn Đức Phúcdsadasd', '2025-10-18 10:55:25');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tblloaisp`
--

CREATE TABLE `tblloaisp` (
  `maLoaiSP` varchar(20) NOT NULL,
  `tenLoaiSP` varchar(50) NOT NULL,
  `moTaLoaiSP` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `tblloaisp`
--

INSERT INTO `tblloaisp` (`maLoaiSP`, `tenLoaiSP`, `moTaLoaiSP`) VALUES
('1', 'Tiểu thuyếtt', 'Tiểu thuyết,Sách'),
('2', 'thiếu nhi', 'nsd'),
('3', 'truyện tranh', 'truyện tranh,manga,....'),
('4', 'văn học', 'văn học'),
('5', 'tài chính', 'kinh tế');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tblreview`
--

CREATE TABLE `tblreview` (
  `id` int(11) NOT NULL,
  `masp` varchar(20) NOT NULL,
  `ten` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `noidung` text NOT NULL,
  `sosao` tinyint(4) DEFAULT NULL CHECK (`sosao` between 1 and 5),
  `trangthai` enum('chờ duyệt','đã duyệt','ẩn') DEFAULT 'chờ duyệt',
  `ngaygui` datetime DEFAULT current_timestamp(),
  `order_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `tblreview`
--

INSERT INTO `tblreview` (`id`, `masp`, `ten`, `email`, `noidung`, `sosao`, `trangthai`, `ngaygui`, `order_id`) VALUES
(1, '7', 'Đoàn Đức Phúc', 'zerohn889@gmail.com', 'ok', 4, 'đã duyệt', '2025-11-09 00:37:36', 92),
(2, '8', 'Đoàn Đức Phúc', 'zerohn889@gmail.com', 'ok', 4, 'đã duyệt', '2025-12-04 23:20:54', 117);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tblsanpham`
--

CREATE TABLE `tblsanpham` (
  `maLoaiSP` varchar(20) NOT NULL,
  `supplier_id` int(11) DEFAULT NULL,
  `masp` varchar(20) NOT NULL,
  `tensp` varchar(20) NOT NULL,
  `hinhanh` varchar(50) NOT NULL,
  `soluong` int(11) NOT NULL,
  `giaNhap` int(11) NOT NULL,
  `giaXuat` int(11) NOT NULL,
  `khuyenmai` int(11) NOT NULL,
  `mota` varchar(200) NOT NULL,
  `createDate` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `tblsanpham`
--

INSERT INTO `tblsanpham` (`maLoaiSP`, `supplier_id`, `masp`, `tensp`, `hinhanh`, `soluong`, `giaNhap`, `giaXuat`, `khuyenmai`, `mota`, `createDate`) VALUES
('3', NULL, '200', '123', '1743619892666090_vi_20250412224745.png', 112, 100000, 300000, 0, 'ok', '2025-11-01'),
('1', NULL, '7', 'Làm Ra Làm, Chơi Ra ', 'Screenshot 2025-11-07 025655.png', 71, 90000, 100000, 0, 'Đã bao giờ bạn ngồi xuống để làm việc và sau đó, không hề nhận ra mình lại kết thúc bằng việc dành một (vài) tiếng đồng hồ lướt Youtube, Facebook, tin tức? Tất cả chúng ta đều đã từng làm vậy. Có vẻ n', '2025-11-01'),
('1', NULL, '8', 'Sách Người Hùng Ý Tư', 'anh-sach-15574716905351357655289.jpg', 89, 150000, 200000, 10, 'Người Hùng Ý Tưởng\r\n\r\n“Cuộc thập tự chinh nào mà để nhắm đến nó ta lại chẳng cần đến tinh thần lạc quan và tham vọng?” – Paul Allen\r\n\r\nCuốn sách là bức chân dung tự họa đa màu về một doanh nhân đa tài', '2025-11-01');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tbluser`
--

CREATE TABLE `tbluser` (
  `user_id` int(11) NOT NULL,
  `fullname` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `is_verified` tinyint(1) DEFAULT 0,
  `verification_token` int(11) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `phone` varchar(15) DEFAULT NULL,
  `address` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `tbluser`
--

INSERT INTO `tbluser` (`user_id`, `fullname`, `email`, `password`, `is_verified`, `verification_token`, `created_at`, `phone`, `address`) VALUES
(13, 'nguyenvan', 'vanntphpmailer@gmail.com', '$2y$10$n2LdkhoSJQzxLKVCkTtete.Y77CWfJqCiKbCnljbQDnsS8ckZaPXa', 0, 42, '2025-10-01 20:52:47', NULL, NULL),
(14, 'Đoàn Đức Phúc', 'zerohn889@gmail.com', '$2y$10$TRwtrDo2pYfxBrRVKZebDu1xt2gcU36V2X1F5k1uOuYp7E1Qf/nb.', 0, 0, '2025-10-18 07:41:07', NULL, NULL),
(16, 'Đoàn Đức Phúcccc', 'zerohn168@gmail.com', '$2y$10$mtC3cPEd2dyj7IufkI6BV.N2tGS3ppfxW6FJYoYGM31jFAT9YOoXa', 0, 256, '2025-10-18 11:56:18', NULL, NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `wishlist`
--

CREATE TABLE `wishlist` (
  `id` int(11) NOT NULL,
  `user_email` varchar(255) NOT NULL,
  `masp` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `chatbox_messages`
--
ALTER TABLE `chatbox_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_email` (`user_email`),
  ADD KEY `idx_status` (`status`);

--
-- Chỉ mục cho bảng `discount_codes`
--
ALTER TABLE `discount_codes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Chỉ mục cho bảng `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `ncc_sanpham`
--
ALTER TABLE `ncc_sanpham`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ten_ncc` (`ten_ncc`),
  ADD KEY `idx_ten_ncc` (`ten_ncc`),
  ADD KEY `idx_trang_thai` (`trang_thai`);

--
-- Chỉ mục cho bảng `news`
--
ALTER TABLE `news`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `order_code` (`order_code`);

--
-- Chỉ mục cho bảng `order_details`
--
ALTER TABLE `order_details`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Chỉ mục cho bảng `product_promotions`
--
ALTER TABLE `product_promotions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `promotion_id` (`promotion_id`);

--
-- Chỉ mục cho bảng `promotions`
--
ALTER TABLE `promotions`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Chỉ mục cho bảng `tbladmin`
--
ALTER TABLE `tbladmin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Chỉ mục cho bảng `tblloaisp`
--
ALTER TABLE `tblloaisp`
  ADD PRIMARY KEY (`maLoaiSP`);

--
-- Chỉ mục cho bảng `tblreview`
--
ALTER TABLE `tblreview`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_tblreview_tblsanpham` (`masp`),
  ADD KEY `fk_review_order` (`order_id`);

--
-- Chỉ mục cho bảng `tblsanpham`
--
ALTER TABLE `tblsanpham`
  ADD PRIMARY KEY (`masp`),
  ADD KEY `fk_product_supplier` (`supplier_id`);

--
-- Chỉ mục cho bảng `tbluser`
--
ALTER TABLE `tbluser`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Chỉ mục cho bảng `wishlist`
--
ALTER TABLE `wishlist`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_user_product` (`user_email`,`masp`),
  ADD KEY `idx_user_email` (`user_email`),
  ADD KEY `idx_masp` (`masp`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `chatbox_messages`
--
ALTER TABLE `chatbox_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `discount_codes`
--
ALTER TABLE `discount_codes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT cho bảng `feedback`
--
ALTER TABLE `feedback`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT cho bảng `ncc_sanpham`
--
ALTER TABLE `ncc_sanpham`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT cho bảng `news`
--
ALTER TABLE `news`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=124;

--
-- AUTO_INCREMENT cho bảng `order_details`
--
ALTER TABLE `order_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=134;

--
-- AUTO_INCREMENT cho bảng `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `product_promotions`
--
ALTER TABLE `product_promotions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `promotions`
--
ALTER TABLE `promotions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `tbladmin`
--
ALTER TABLE `tbladmin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT cho bảng `tblreview`
--
ALTER TABLE `tblreview`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `tbluser`
--
ALTER TABLE `tbluser`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT cho bảng `wishlist`
--
ALTER TABLE `wishlist`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `product_promotions`
--
ALTER TABLE `product_promotions`
  ADD CONSTRAINT `product_promotions_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `tblsanpham` (`masp`),
  ADD CONSTRAINT `product_promotions_ibfk_2` FOREIGN KEY (`promotion_id`) REFERENCES `promotions` (`id`);

--
-- Các ràng buộc cho bảng `tblreview`
--
ALTER TABLE `tblreview`
  ADD CONSTRAINT `fk_review_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`),
  ADD CONSTRAINT `fk_tblreview_tblsanpham` FOREIGN KEY (`masp`) REFERENCES `tblsanpham` (`masp`);

--
-- Các ràng buộc cho bảng `tblsanpham`
--
ALTER TABLE `tblsanpham`
  ADD CONSTRAINT `fk_product_supplier` FOREIGN KEY (`supplier_id`) REFERENCES `ncc_sanpham` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
