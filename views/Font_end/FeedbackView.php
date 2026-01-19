<div class="container mt-5">
    <?php if (isset($_SESSION['success_message'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php 
                echo $_SESSION['success_message'];
                unset($_SESSION['success_message']);
            ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['error_message'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?php 
                echo $_SESSION['error_message'];
                unset($_SESSION['error_message']);
            ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Góp ý - Phản hồi</h4>
        </div>
        <div class="card-body">
            <form action="<?php echo APP_URL;?>/FeedbackController/add" method="POST">
                <div class="mb-3">
                    <label for="content" class="form-label">Nội dung góp ý</label>
                    <textarea class="form-control" id="content" name="content" rows="5" required 
                              placeholder="Hãy chia sẻ ý kiến của bạn để chúng tôi có thể phục vụ tốt hơn..."></textarea>
                </div>
                <div class="text-end">
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="bi bi-send-fill me-2"></i>Gửi góp ý
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.card {
    border-radius: 10px;
    box-shadow: 0 0 15px rgba(0,0,0,0.1);
}

.card-header {
    border-radius: 10px 10px 0 0;
    border-bottom: none;
}

textarea.form-control {
    border: 1px solid #ddd;
    border-radius: 8px;
}

textarea.form-control:focus {
    border-color: #86b7fe;
    box-shadow: 0 0 0 0.25rem rgba(13,110,253,.25);
}

.btn-primary {
    padding: 10px 25px;
    border-radius: 8px;
    transition: all 0.3s ease;
}

.btn-primary:hover {
    transform: translateY(-2px);
}
</style>