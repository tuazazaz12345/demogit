<div class="container-fluid mt-4">
    <div class="row mb-4">
        <div class="col-md-12">
            <h2 class="mb-4">
                <i class="bi bi-shield-check"></i> Quản Lý Kiểm Duyệt Nội Dung
            </h2>
        </div>
    </div>
    
    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-primary">
                <div class="card-body text-center">
                    <h5 class="card-title text-muted">Tổng Đánh Giá</h5>
                    <h2 class="text-primary"><?= $data['stats']['total'] ?? 0 ?></h2>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card border-warning">
                <div class="card-body text-center">
                    <h5 class="card-title text-muted">Chờ Duyệt</h5>
                    <h2 class="text-warning"><?= $data['stats']['pending'] ?? 0 ?></h2>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card border-success">
                <div class="card-body text-center">
                    <h5 class="card-title text-muted">Đã Duyệt</h5>
                    <h2 class="text-success"><?= $data['stats']['approved'] ?? 0 ?></h2>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card border-danger">
                <div class="card-body text-center">
                    <h5 class="card-title text-muted">Spam/Từ Chối</h5>
                    <h2 class="text-danger"><?= ($data['stats']['spam'] ?? 0) + ($data['stats']['rejected'] ?? 0) ?></h2>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Quick Links -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="btn-group" role="group">
                <a href="<?= APP_URL ?>/ContentModerationController/pending" class="btn btn-warning">
                    <i class="bi bi-hourglass-split"></i> Chờ Duyệt (<?= $data['stats']['pending'] ?? 0 ?>)
                </a>
                <a href="<?= APP_URL ?>/ContentModerationController/approved" class="btn btn-success">
                    <i class="bi bi-check-circle"></i> Đã Duyệt (<?= $data['stats']['approved'] ?? 0 ?>)
                </a>
                <a href="<?= APP_URL ?>/ContentModerationController/rejected" class="btn btn-danger">
                    <i class="bi bi-x-circle"></i> Spam/Từ Chối (<?= ($data['stats']['spam'] ?? 0) + ($data['stats']['rejected'] ?? 0) ?>)
                </a>
            </div>
        </div>
    </div>
    
    <!-- Information -->
    <div class="row">
        <div class="col-md-12">
            <div class="alert alert-info">
                <h5><i class="bi bi-info-circle"></i> Hướng Dẫn Kiểm Duyệt</h5>
                <ul class="mb-0">
                    <li>Hệ thống tự động phân tích nội dung dựa trên spam patterns, từ cấm, và các quy tắc khác</li>
                    <li>Admin có thể xem chi tiết phân tích tự động và quyết định duyệt hoặc từ chối</li>
                    <li>Các đánh giá spam sẽ không hiển thị cho khách hàng</li>
                    <li>Đánh giá từ chối sẽ được giữ lại để tracking nhưng không hiển thị</li>
                </ul>
            </div>
        </div>
    </div>
</div>
