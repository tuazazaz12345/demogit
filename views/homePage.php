<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="Cửa hàng sách online với đa dạng thể loại sách và tài liệu">
    <meta name="author" content="Bookstore Team">
    <title>Cửa Hàng Sách - Khám phá thế giới qua từng trang sách</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?php echo APP_URL;?>/public/images/favicon.ico">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Crimson+Text:ital,wght@0,400;0,600;0,700;1,400&family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap -->
    <link href="<?php echo APP_URL;?>/public/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link href="<?php echo APP_URL;?>/public/css/bookstore.css" rel="stylesheet">
    <link href="<?php echo APP_URL;?>/public/css/widgets.css" rel="stylesheet">
    <link href="<?php echo APP_URL;?>/public/css/user-sakura-theme.css" rel="stylesheet">
    
    <!-- Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Scripts -->
    <script defer src="<?php echo APP_URL;?>/public/js/bootstrap.bundle.min.js"></script>
    <script defer src="<?php echo APP_URL;?>/public/js/modern.js"></script>
    <script defer src="<?php echo APP_URL;?>/public/js/widgets.js"></script>
    <script defer src="<?php echo APP_URL;?>/public/js/sakura-animation.js"></script>

    <!-- Cross-browser compatibility -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>
    <header>
        <nav class="navbar navbar-expand-lg">
            <div class="container">
                <a class="navbar-brand" href="<?php echo APP_URL;?>/Home/">
                    <i class="bi bi-book"></i>
                    Cửa Hàng Sách
                </a>
                
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link active" href="<?php echo APP_URL;?>/Home/">Trang Chủ</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo APP_URL;?>/PromotionalProductsController/index">
                                <i class="bi bi-fire"></i> Khuyến Mại
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo APP_URL;?>/NewsController/showNews">Tin Tức</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                                Thể Loại Sách
                            </a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a class="dropdown-item fw-bold" href="<?php echo APP_URL;?>/Home/showAllProducts">
                                        <i class="bi bi-grid"></i> Tất Cả Sản Phẩm
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <?php if(isset($data["productTypes"]) && !empty($data["productTypes"])): ?>
                                    <?php foreach($data["productTypes"] as $type): ?>
                                        <li>
                                            <a class="dropdown-item" href="<?php echo APP_URL;?>/Home/showByType/<?php echo htmlspecialchars($type['maLoaiSP']);?>">
                                                <?php echo htmlspecialchars($type['tenLoaiSP']);?>
                                            </a>
                                        </li>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <li><span class="dropdown-item">Chưa có danh mục</span></li>
                                <?php endif; ?>
                            </ul>
                        </li>
                    </ul>

                    <form class="d-flex me-3" action="<?php echo APP_URL;?>/SearchController/index" method="GET">
                        <input class="form-control me-2" type="search" name="keyword" 
                               placeholder="Tìm theo tên sách, mã sách..." 
                               value="<?php echo htmlspecialchars($_GET['keyword'] ?? '');?>"
                               required>
                        <button class="btn btn-outline-primary" type="submit">
                            <i class="bi bi-search"></i>
                        </button>
                    </form>

                    <div class="d-flex align-items-center">
                        
                        <!-- Nút giỏ hàng -->
                        <a href="<?php echo APP_URL;?>/Home/order" class="btn btn-outline-custom me-3 position-relative">
                            <i class="bi bi-cart3"></i> Giỏ hàng
                            <?php if(isset($_SESSION['cart']) && count($_SESSION['cart']) > 0): ?>
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                    <?php echo count($_SESSION['cart']); ?>
                                </span>
                            <?php endif; ?>
                        </a>

                        <!-- Nút yêu thích -->
                        <?php if(isset($_SESSION['user'])): ?>
                            <a href="<?php echo APP_URL;?>/WishlistController/index" class="btn btn-outline-custom me-3 position-relative">
                                <i class="bi bi-heart"></i> Yêu thích
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" id="wishlistCount" style="display:none;">0</span>
                            </a>
                        <?php endif; ?>

                        <?php if(isset($_SESSION['user'])): ?>
                            <div class="dropdown me-3">
                                <button class="btn btn-outline-custom dropdown-toggle" type="button" id="userMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="bi bi-person-circle me-1"></i><?php echo htmlspecialchars($_SESSION['user']['fullname']);?>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userMenuButton">
                                    <li><a class="dropdown-item" href="<?php echo APP_URL;?>/Home/profile"><i class="bi bi-person me-2"></i>Thông tin cá nhân</a></li>
                                    <li><a class="dropdown-item" href="<?php echo APP_URL;?>/Home/showChangePassword"><i class="bi bi-shield-lock me-2"></i>Đổi mật khẩu</a></li>
                                    <li><a class="dropdown-item" href="<?php echo APP_URL;?>/Home/orderHistory"><i class="bi bi-clock-history me-2"></i>Lịch sử đơn hàng</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item text-danger" href="<?php echo APP_URL;?>/AuthController/logout"><i class="bi bi-box-arrow-right me-2"></i>Đăng xuất</a></li>
                                </ul>
                            </div>
                        <?php else: ?>
                            <a href="<?php echo APP_URL;?>/AuthController/showLogin" class="btn btn-outline-custom">Đăng Nhập</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </nav>
    </header>

    <main>
        <?php if (!isset($data['showAllProducts'])): ?>
        <!-- Hero Section -->
        <section class="hero-section">
            <div class="container">
                <h1 class="hero-title animate-fade-in">Khám Phá Thế Giới Qua Từng Trang Sách</h1>
                <p class="lead mb-4 animate-fade-in">Tận hưởng không gian đọc sách tuyệt vời với bộ sưu tập đa dạng và phong phú</p>
                <div class="animate-fade-in">
                    <a href="#featured-books" class="btn btn-custom me-3">Khám phá ngay</a>
                    <a href="#categories" class="btn btn-outline-custom">Danh mục sách</a>
                </div>
            </div>
        </section>

        <?php 
        // Chỉ hiển thị widget khi ở trang chủ chính 
        // (không có currentType, không phải trang tìm kiếm, không phải trang chi tiết, và page phải là HomeView)
        if (!isset($data['currentType']) && !isset($data['isSearchResult']) && 
            $data["page"] === "HomeView" && !isset($data['product'])): 
        ?>
            <!-- Reading Progress Bar -->
            <div class="reading-progress"></div>

            <!-- Floating Books Animation -->
            <div class="floating-books"></div>

            <!-- Category Stats -->
            <section class="container my-5">
                <div class="category-stats">
                    <div class="stat-box">
                        <div class="category-counter" data-target="1500">0</div>
                        <div class="counter-label">Đầu Sách</div>
                    </div>
                    <div class="stat-box">
                        <div class="category-counter" data-target="25">0</div>
                        <div class="counter-label">Thể Loại</div>
                    </div>
                    <div class="stat-box">
                        <div class="category-counter" data-target="10000">0</div>
                        <div class="counter-label">Độc Giả</div>
                    </div>
                    <div class="stat-box">
                        <div class="category-counter" data-target="50000">0</div>
                        <div class="counter-label">Sách Đã Bán</div>
                    </div>
                </div>
            </section>

            <!-- Book Quote -->
            <section class="container my-5">
                <div class="book-quote">
                    <div class="quote-text"></div>
                    <div class="quote-author"></div>
                </div>
            </section>
        <?php endif; ?>
        <?php endif; ?>

        <?php 
            if(isset($data["page"])) {
                $viewPath = "./views/Font_end/" . $data["page"] . ".php";
                if(file_exists($viewPath)) {
                    require_once $viewPath;
                } else {
                    echo "Không tìm thấy trang yêu cầu";
                }
            }
        ?>
    </main>

    <footer class="bg-light py-5 mt-auto">
        <div class="container">
            <div class="row g-4">
                <!-- Giới thiệu -->
                <div class="col-lg-3">
                    <h5 class="mb-3">Về Cửa Hàng Sách</h5>
                    <p class="text-muted">Chúng tôi là địa chỉ tin cậy cho những người yêu sách, cung cấp đa dạng các đầu sách chất lượng với giá cả hợp lý nhất.</p>
                    <div class="social-links">
                        <a href="#" class="text-decoration-none me-2"><i class="bi bi-facebook fs-5"></i></a>
                        <a href="#" class="text-decoration-none me-2"><i class="bi bi-twitter fs-5"></i></a>
                        <a href="#" class="text-decoration-none me-2"><i class="bi bi-instagram fs-5"></i></a>
                    </div>
                </div>

                <!-- Liên kết nhanh -->
                <div class="col-lg-3">
                    <h5 class="mb-3">Liên Kết Nhanh</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="<?php echo APP_URL;?>/Home" class="text-decoration-none text-muted"><i class="bi bi-chevron-right"></i> Trang chủ</a></li>
                        <li class="mb-2"><a href="<?php echo APP_URL;?>/Home/order" class="text-decoration-none text-muted"><i class="bi bi-chevron-right"></i> Giỏ hàng</a></li>
                        <li class="mb-2"><a href="<?php echo APP_URL;?>/Home/orderHistory" class="text-decoration-none text-muted"><i class="bi bi-chevron-right"></i> Lịch sử đơn hàng</a></li>
                        <li class="mb-2"><a href="<?php echo APP_URL;?>/NewsController/showNews" class="text-decoration-none text-muted"><i class="bi bi-chevron-right"></i> Tin Tức</a></li>
                    </ul>
                </div>

                <!-- Hỗ Trợ & Góp Ý -->
                <div class="col-lg-3">
                    <h5 class="mb-3">Hỗ Trợ & Góp Ý</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="<?php echo APP_URL;?>/FeedbackController/showForm" class="text-decoration-none text-muted"><i class="bi bi-chat-dots me-2"></i> Gửi Góp Ý</a></li>
                        <li class="mb-2"><a href="<?php echo APP_URL;?>/ChatboxController/index" class="text-decoration-none text-muted"><i class="bi bi-chat-text me-2"></i> Hỗ Trợ Online</a></li>
                        <li class="mb-2"><a href="<?php echo APP_URL;?>/AboutController/index" class="text-decoration-none text-muted"><i class="bi bi-info-circle me-2"></i> Về Chúng Tôi</a></li>
                    </ul>
                </div>

                <!-- Chính Sách -->
                <div class="col-lg-3">
                    <h5 class="mb-3">Chính Sách & Điều Khoản</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="<?php echo APP_URL;?>/PolicyController/warranty" class="text-decoration-none text-muted"><i class="bi bi-chevron-right"></i> Bảo Hành & Đổi Trả</a></li>
                        <li class="mb-2"><a href="<?php echo APP_URL;?>/PolicyController/shipping" class="text-decoration-none text-muted"><i class="bi bi-chevron-right"></i> Giao Hàng</a></li>
                        <li class="mb-2"><a href="<?php echo APP_URL;?>/PolicyController/terms" class="text-decoration-none text-muted"><i class="bi bi-chevron-right"></i> Điều Khoản</a></li>
                        <li class="mb-2"><a href="<?php echo APP_URL;?>/PolicyController/privacy" class="text-decoration-none text-muted"><i class="bi bi-chevron-right"></i> Bảo Mật</a></li>
                    </ul>
                </div>

                <!-- Thông tin liên hệ -->
                <div class="col-lg-3">
                    <h5 class="mb-3">Thông Tin Liên Hệ</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><i class="bi bi-geo-alt me-2"></i> 123 Đường ABC, Quận XYZ, TP.HCM</li>
                        <li class="mb-2"><i class="bi bi-telephone me-2"></i> (84) 123-456-789</li>
                        <li class="mb-2"><i class="bi bi-envelope me-2"></i> contact@cuahangsach.com</li>
                        <li class="mb-2"><i class="bi bi-clock me-2"></i> Thứ 2 - Chủ nhật: 8:00 - 22:00</li>
                    </ul>
                </div>
            </div>

            <hr class="my-4">

            <!-- Copyright -->
            <div class="row">
                <div class="col-12 text-center">
                    <p class="mb-0">&copy; <?php echo date('Y'); ?> Cửa Hàng Sách. Đã đăng ký bản quyền.</p>
                </div>
            </div>
        </div>
    </footer>
    
    <!-- Nút quay lại đầu trang -->
    <button id="backToTop" class="btn btn-primary rounded-circle position-fixed bottom-0 end-0 m-4" style="display: none;">
        <i class="bi bi-arrow-up"></i>
    </button>

    <script>
        // Hiển thị nút back-to-top khi cuộn xuống
        window.onscroll = function() {
            if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
                document.getElementById("backToTop").style.display = "block";
            } else {
                document.getElementById("backToTop").style.display = "none";
            }
            updateReadingProgress();
        };

        // Cuộn lên đầu trang khi click nút
        document.getElementById("backToTop").addEventListener("click", function() {
            document.body.scrollTop = 0; // For Safari
            document.documentElement.scrollTop = 0; // For Chrome, Firefox, IE and Opera
        });

        // Sakura Petals Animation
        function createPetal() {
            const petal = document.createElement('div');
            petal.classList.add('petal');
            petal.style.left = Math.random() * 100 + '%';
            petal.style.width = Math.random() * 15 + 10 + 'px';
            petal.style.height = Math.random() * 15 + 10 + 'px';
            petal.style.opacity = Math.random() * 0.6 + 0.4;
            petal.style.animationDuration = Math.random() * 4 + 6 + 's';
            
            document.querySelector('.floating-books').appendChild(petal);
            
            setTimeout(() => {
                petal.remove();
            }, 10000);
        }

        // Update Wishlist Count
        function updateWishlistCount() {
            fetch('<?= APP_URL ?>/WishlistController/count', {
                method: 'POST'
            })
            .then(response => response.json())
            .then(data => {
                const countBadge = document.getElementById('wishlistCount');
                if (countBadge) {
                    if (data.count > 0) {
                        countBadge.textContent = data.count;
                        countBadge.style.display = 'inline-block';
                    } else {
                        countBadge.style.display = 'none';
                    }
                }
            })
            .catch(err => console.error('Lỗi khi cập nhật số lượng yêu thích:', err));
        }

        // Load wishlist count on page load
        if (document.getElementById('wishlistCount')) {
            updateWishlistCount();
            // Refresh count every 30 seconds
            setInterval(updateWishlistCount, 30000);
        }

        // Create petals periodically
        setInterval(createPetal, 300);

        // Reading Progress Bar
        function updateReadingProgress() {
            const winScroll = document.body.scrollTop || document.documentElement.scrollTop;
            const height = document.documentElement.scrollHeight - document.documentElement.clientHeight;
            const scrolled = (winScroll / height) * 100;
            document.querySelector('.reading-progress').style.width = scrolled + '%';
        }

        // Book Quotes
        const quotes = [
            { text: "Một cuốn sách hay là một người bạn quý.", author: "Ngạn ngữ" },
            { text: "Đọc sách hay, tâm hồn trong sáng.", author: "Khuyết danh" },
            { text: "Sách là cửa sổ của tri thức.", author: "Khuyết danh" }
        ];

        function updateQuote() {
            const quote = quotes[Math.floor(Math.random() * quotes.length)];
            const quoteText = document.querySelector('.quote-text');
            const quoteAuthor = document.querySelector('.quote-author');
            
            if(quoteText && quoteAuthor) {
                quoteText.textContent = quote.text;
                quoteAuthor.textContent = "- " + quote.author;
            }
        }

        // Update quote every 10 seconds
        updateQuote();
        setInterval(updateQuote, 10000);

        // Category Counter Animation
        function animateCounter(element) {
            const target = parseInt(element.getAttribute('data-target'));
            const duration = 2000; // 2 seconds
            const step = target / (duration / 16); // Update every 16ms
            let current = 0;
            
            const timer = setInterval(() => {
                current += step;
                if (current >= target) {
                    element.textContent = target.toLocaleString();
                    clearInterval(timer);
                } else {
                    element.textContent = Math.floor(current).toLocaleString();
                }
            }, 16);
        }

        // Start counter animation when element is in view
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    animateCounter(entry.target);
                    observer.unobserve(entry.target);
                }
            });
        });

        document.querySelectorAll('.category-counter').forEach(counter => {
            observer.observe(counter);
        });
    </script>

    <!-- Start of Tawk.to Script -->
    <script type="text/javascript">
    var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
    (function(){
    var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
    s1.async=true;
    s1.src='https://embed.tawk.to/6935c7a84699f5197df50351/1jbt17r7v';
    s1.charset='UTF-8';
    s1.setAttribute('crossorigin','*');
    s0.parentNode.insertBefore(s1,s0);
    })();
    </script>
    <!-- End of Tawk.to Script -->
</body>
</html>