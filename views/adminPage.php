<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang Quản Trị</title>

    <!-- Bootstrap -->
    <link href="<?php echo APP_URL; ?>/public/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="<?php echo APP_URL; ?>/public/css/admin-modern.css" rel="stylesheet">
    <link href="<?php echo APP_URL; ?>/public/css/sakura-theme.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body class="admin-body">
    <div class="admin-wrapper">
        <!-- Sidebar -->
        <aside class="admin-sidebar">
            <div class="sidebar-header">
                <h3 class="text-white mb-0">
                    <i class="bi bi-book"></i> 
                    Cửa Hàng Sách
                </h3>
            </div>

            <nav class="admin-nav">
                <div class="nav-item">
                    <a href="<?php echo APP_URL; ?>/Admin/show" class="nav-link">
                        <i class="bi bi-grid-1x2"></i>
                        <span>Loại sản phẩm</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a href="<?php echo APP_URL; ?>/Product/" class="nav-link">
                        <i class="bi bi-box"></i>
                        <span>Sản phẩm</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a href="<?php echo APP_URL; ?>/Admin/news" class="nav-link">
                        <i class="bi bi-newspaper"></i>
                        <span>Tin tức</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a href="<?php echo APP_URL; ?>/Admin/listOrders" class="nav-link">
                        <i class="bi bi-cart"></i>
                        <span>Đơn hàng</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a href="<?php echo APP_URL; ?>/Admin/customers" class="nav-link">
                        <i class="bi bi-people"></i>
                        <span>Khách hàng</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a href="<?php echo APP_URL; ?>/StatisticsController/inventory" class="nav-link">
                        <i class="bi bi-box-seam"></i>
                        <span>Quản lý tồn kho</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a href="<?php echo APP_URL; ?>/StatisticsController/revenue" class="nav-link">
                        <i class="bi bi-graph-up"></i>
                        <span>Thống kê doanh thu</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a href="<?php echo APP_URL; ?>/FeedbackController/manage" class="nav-link">
                        <i class="bi bi-chat-dots"></i>
                        <span>Quản lý góp ý</span>
                        <?php if(isset($data['pendingFeedbacks']) && $data['pendingFeedbacks'] > 0): ?>
                            <span class="badge bg-danger rounded-pill ms-2"><?= $data['pendingFeedbacks'] ?></span>
                        <?php endif; ?>
                    </a>
                </div>
                <div class="nav-item">
                    <a href="<?php echo APP_URL; ?>/PromotionController/index" class="nav-link">
                        <i class="bi bi-tag"></i>
                        <span>Quản lý khuyến mại</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a href="<?php echo APP_URL; ?>/SupplierController/index" class="nav-link">
                        <i class="bi bi-building"></i>
                        <span>Quản lý nhà cung cấp</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a href="<?php echo APP_URL; ?>/DiscountCodeController/index" class="nav-link">
                        <i class="bi bi-percent"></i>
                        <span>Quản lý mã giảm giá</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a href="<?php echo APP_URL; ?>/Admin/manageReviews" class="nav-link">
                        <i class="bi bi-chat-left-dots"></i>
                        <span>Quản lý đánh giá</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a href="<?php echo APP_URL; ?>/Admin/manageAdmins" class="nav-link">
                        <i class="bi bi-shield-lock"></i>
                        <span>Quản lý Admin</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a href="<?php echo APP_URL; ?>/AdminApprovalController/index" class="nav-link">
                        <i class="bi bi-person-check"></i>
                        <span>Duyệt Admin</span>
                    </a>
                </div>
                <!-- Nút đăng xuất -->
                <div class="nav-item mt-auto" style="margin-top: auto;">
                    <a href="<?php echo APP_URL; ?>/AuthController/AdminLogout" class="nav-link text-danger">
                        <i class="bi bi-box-arrow-right"></i>
                        <span>Đăng xuất</span>
                    </a>
                </div>
            </nav>
        </aside>

        <!-- Main Content -->
        <div class="admin-main">
            <!-- Top Navbar -->
            <nav class="admin-navbar">
                <div class="container-fluid">
                    <button class="btn sidebar-toggle">
                        <i class="bi bi-list"></i>
                    </button>

                <button
                    class="navbar-toggler d-lg-none"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#navbarAdmin"
                    aria-controls="navbarAdmin"
                    aria-expanded="false"
                    aria-label="Toggle navigation"
                >
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarAdmin">
                    <!-- Menu trái -->
                    <ul class="navbar-nav me-auto mt-2 mt-lg-0">
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo APP_URL; ?>/Admin/show">Loại sản phẩm</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo APP_URL; ?>/Product/">Sản phẩm</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo APP_URL; ?>/Admin/news">Tin tức</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo APP_URL; ?>/Admin/listOrders">Đơn hàng</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo APP_URL; ?>/Admin/customers">Khách hàng</a>
                        </li>

                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="dropdownId" data-bs-toggle="dropdown">
                                Thống kê & Báo cáo
                            </a>
                            <div class="dropdown-menu bg-primary border-0 shadow-sm" aria-labelledby="dropdownId">
                                <a class="dropdown-item text-white" href="<?php echo APP_URL; ?>/StatisticsController/inventory">
                                    <i class="bi bi-box-seam"></i> Quản lý tồn kho
                                </a>
                                <a class="dropdown-item text-white" href="<?php echo APP_URL; ?>/StatisticsController/revenue">
                                    <i class="bi bi-graph-up"></i> Thống kê doanh thu
                                </a>
                            </div>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo APP_URL; ?>/Admin/manageAdmins">
                                <i class="bi bi-shield-lock"></i> Quản lý Admin
                            </a>
                        </li>
                    </ul>

                    <div class="ms-auto d-flex align-items-center">
                        <!-- Form tìm kiếm -->
                        <form class="search-form me-3" action="<?php echo APP_URL; ?>/Admin/listOrders" method="get">
                            <div class="input-group">
                                <input 
                                    type="text"
                                    class="form-control"
                                    name="keyword"
                                    placeholder="Tìm kiếm..."
                                    value="<?= htmlspecialchars($data['keyword'] ?? '') ?>">
                                <button class="btn btn-custom" type="submit">
                                    <i class="bi bi-search"></i>
                                </button>
                            </div>
                        </form>

                        <!-- User Menu -->
                        <?php if (isset($_SESSION['admin'])): ?>
                            <div class="dropdown">
                                <button class="btn btn-link dropdown-toggle user-menu" type="button" id="userDropdown" data-bs-toggle="dropdown">
                                    <i class="bi bi-person-circle"></i>
                                    <span><?= htmlspecialchars($_SESSION['admin']['fullname'] ?? $_SESSION['admin']['username']) ?></span>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a class="dropdown-item" href="<?php echo APP_URL; ?>/Admin/profile">
                                            <i class="bi bi-person me-2"></i>Thông tin cá nhân
                                        </a>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <a class="dropdown-item text-danger" href="<?php echo APP_URL; ?>/AuthController/AdminLogout">
                                            <i class="bi bi-box-arrow-right me-2"></i>Đăng xuất
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </nav>

            <!-- Main Content Area -->
            <div class="admin-content">
                <?php require_once "./views/Back_end/" . $data["page"] . ".php"; ?>
            </div>
        </div>
    </div>

    <!-- Sakura Container -->
    <div id="sakura-container"></div>

    <!-- Scripts -->
    <script src="<?php echo APP_URL; ?>/public/js/bootstrap.bundle.min.js"></script>
    <script src="<?php echo APP_URL; ?>/public/js/modern.js"></script>
    <script src="<?php echo APP_URL; ?>/public/js/charts.js"></script>

    <!-- Sakura Animation Script -->
    <script>
    function createSakura() {
        const container = document.getElementById('sakura-container');
        const petal = document.createElement('div');
        petal.className = 'sakura-petal';
        
        // Random position, size, and animation duration
        const startPos = Math.random() * window.innerWidth;
        const size = Math.random() * 10 + 10;
        const duration = Math.random() * 5 + 3;
        
        petal.style.cssText = `
            left: ${startPos}px;
            font-size: ${size}px;
            animation: float ${duration}s linear infinite;
        `;
        
        container.appendChild(petal);
        
        // Remove petal after animation
        setTimeout(() => {
            petal.remove();
        }, duration * 1000);
    }

    // Create new petals periodically
    setInterval(createSakura, 300);

    // Initial petals
    for(let i = 0; i < 10; i++) {
        setTimeout(createSakura, i * 300);
    }
    </script>
</body>
</html>
