<?php
if (!isset($_SESSION['login'])) {
    header("Location: index.php?url=login");
    exit;
}
?>
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-8">
            <h2><i class="bi bi-shield-lock me-2"></i><?= htmlspecialchars($data['title']) ?></h2>
        </div>
        <div class="col-md-4 text-end">
            <a href="index.php?url=admin_management" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-2"></i>Quay Lại
            </a>
        </div>
    </div>

    <!-- Alerts -->
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i><?= $_SESSION['success'] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-circle me-2"></i><?= $_SESSION['error'] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <!-- Vai trò và Quyền hạn -->
    <div class="row">
        <!-- Danh sách Vai trò -->
        <div class="col-lg-4">
            <div class="admin-card">
                <h5 class="card-title mb-4"><i class="bi bi-bookmark-fill me-2"></i>Danh Sách Vai Trò</h5>
                <div class="list-group">
                    <?php if (isset($data['roles']) && is_array($data['roles'])): ?>
                        <?php foreach ($data['roles'] as $role): ?>
                            <button type="button" class="list-group-item list-group-item-action role-item" 
                                    data-role-id="<?= $role['id'] ?>" data-role-name="<?= htmlspecialchars($role['name']) ?>">
                                <div class="d-flex justify-content-between">
                                    <strong><?= htmlspecialchars($role['name']) ?></strong>
                                    <span class="badge bg-primary">Chọn</span>
                                </div>
                                <small class="text-muted d-block mt-1"><?= htmlspecialchars($role['description']) ?></small>
                            </button>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Quyền hạn cho Vai trò -->
        <div class="col-lg-8">
            <div class="admin-card">
                <h5 class="card-title mb-4"><i class="bi bi-key-fill me-2"></i>Quản Lý Quyền Hạn</h5>
                
                <form method="POST" action="index.php?url=admin_management/update_role_permissions" id="rolePermissionsForm">
                    <input type="hidden" name="role_id" id="role_id" value="">
                    
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="alert alert-info" id="roleInfo" style="display: none;">
                                <h6 id="roleName"></h6>
                                <p id="roleDesc" class="mb-0"></p>
                            </div>
                        </div>
                    </div>

                    <div id="permissionsContainer">
                        <p class="text-muted text-center py-5">
                            <i class="bi bi-info-circle me-2"></i>Chọn một vai trò từ bên trái để xem và chỉnh sửa quyền hạn
                        </p>
                    </div>

                    <div id="formActions" style="display: none;">
                        <button type="submit" class="btn btn-primary me-2">
                            <i class="bi bi-check-circle me-2"></i>Lưu Quyền Hạn
                        </button>
                        <button type="reset" class="btn btn-secondary">
                            <i class="bi bi-arrow-counterclockwise me-2"></i>Đặt Lại
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    const allPermissions = <?= json_encode($data['permissions']) ?>;
    
    $('.role-item').click(function() {
        const roleId = $(this).data('role-id');
        const roleName = $(this).data('role-name');
        
        // Cập nhật active item
        $('.role-item').removeClass('active');
        $(this).addClass('active');
        
        // Cập nhật form
        $('#role_id').val(roleId);
        $('#roleInfo').show();
        $('#roleName').text(roleName);
        $('#formActions').show();
        
        // Render permissions
        renderPermissions(roleId);
    });

    function renderPermissions(roleId) {
        let html = '<div class="row">';
        
        for (const resource in allPermissions) {
            const perms = allPermissions[resource];
            html += '<div class="col-md-6 mb-4">';
            html += '<h6 class="text-uppercase fw-bold mb-3">' + resource + '</h6>';
            html += '<div class="permission-group">';
            
            perms.forEach(perm => {
                html += '<div class="form-check mb-2">';
                html += '<input class="form-check-input permission-checkbox" type="checkbox" ';
                html += 'id="perm_' + perm.id + '" name="permissions[]" value="' + perm.id + '">';
                html += '<label class="form-check-label" for="perm_' + perm.id + '">';
                html += perm.name + ' <small class="text-muted">(' + perm.action + ')</small>';
                html += '</label>';
                html += '</div>';
            });
            
            html += '</div></div>';
        }
        
        html += '</div>';
        $('#permissionsContainer').html(html);
        
        // Load quyền hạn hiện tại cho vai trò
        loadRolePermissions(roleId);
    }

    function loadRolePermissions(roleId) {
        // Sau khi render permissions, cần load current permissions từ server
        // Tạm thời sẽ hiển thị form trống để chọn
    }
});
</script>

<style>
.role-item {
    cursor: pointer;
    border: 1px solid #dee2e6;
    margin-bottom: 8px;
    transition: all 0.3s ease;
}

.role-item:hover {
    background-color: #f8f9fa;
    transform: translateX(5px);
}

.role-item.active {
    background-color: #0d6efd !important;
    color: white;
    border-color: #0d6efd;
}

.permission-group {
    padding: 10px;
    background-color: #f8f9fa;
    border-radius: 4px;
}
</style>
