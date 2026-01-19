<?php
/**
 * Content Moderation - Admin Menu Item
 * Thêm phần này vào admin menu/sidebar
 */

// Thêm vào menu admin (trong layout hoặc sidebar)
?>

<!-- Content Moderation Menu Item -->
<li class="nav-item">
    <a href="<?= APP_URL ?>/ContentModerationController" 
       class="nav-link <?= strpos($_SERVER['REQUEST_URI'], 'ContentModeration') !== false ? 'active' : '' ?>">
        <i class="nav-icon bi bi-shield-check"></i>
        <p>
            Kiểm Duyệt Nội Dung
            <?php 
            // Hiển thị số review chờ duyệt
            if (isset($pendingCount) && $pendingCount > 0):
            ?>
            <span class="badge bg-warning">
                <?= $pendingCount ?>
            </span>
            <?php endif; ?>
        </p>
    </a>
</li>

<!-- Alternative: Nested Menu (nếu sử dụng submenu) -->
<li class="nav-item">
    <a href="#" class="nav-link" data-bs-toggle="collapse" data-bs-target="#contentModerationMenu">
        <i class="nav-icon bi bi-shield-check"></i>
        <p>
            Kiểm Duyệt Nội Dung
            <i class="right fas fa-angle-left"></i>
            <?php if (isset($pendingCount) && $pendingCount > 0): ?>
            <span class="badge bg-warning float-right">
                <?= $pendingCount ?>
            </span>
            <?php endif; ?>
        </p>
    </a>
    <ul class="nav nav-treeview" id="contentModerationMenu">
        <li class="nav-item">
            <a href="<?= APP_URL ?>/ContentModerationController" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Dashboard</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="<?= APP_URL ?>/ContentModerationController/pending" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>
                    Chờ Duyệt
                    <?php if (isset($pendingCount) && $pendingCount > 0): ?>
                    <span class="badge bg-warning">
                        <?= $pendingCount ?>
                    </span>
                    <?php endif; ?>
                </p>
            </a>
        </li>
        <li class="nav-item">
            <a href="<?= APP_URL ?>/ContentModerationController/approved" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Đã Duyệt</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="<?= APP_URL ?>/ContentModerationController/rejected" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Spam / Từ Chối</p>
            </a>
        </li>
    </ul>
</li>

<?php
/**
 * HƯỚNG DẪN THÊM VÀO MENU
 * 
 * 1. Tìm file layout admin (thường là views/Back_end/sidebar.php hoặc layout.php)
 * 2. Tìm vị trí <ul class="nav nav-pills nav-sidebar"> hoặc tương tự
 * 3. Thêm code ở trên vào vị trí phù hợp (thường sau "Quản Lý Đơn Hàng", "Quản Lý Sản Phẩm", v.v.)
 * 4. Cập nhật controller để truyền $pendingCount vào view
 * 
 * Ví dụ trong Admin.php controller:
 * 
 * public function index() {
 *     $reviewModel = $this->model('ReviewModel');
 *     $stats = $reviewModel->getModerationStats();
 *     $pendingCount = $stats['pending'] ?? 0;
 *     
 *     $this->view('Back_end/admin_dashboard', [
 *         'pendingCount' => $pendingCount,
 *         // ... other data
 *     ]);
 * }
 */
?>
