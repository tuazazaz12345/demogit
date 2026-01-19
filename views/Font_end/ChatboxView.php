<!-- Trang Hỗ Trợ Khách Hàng - Chatbox -->
<div class="container mt-5 mb-5">
    <div class="row">
        <div class="col-12">
            <h2 class="fw-bold mb-4">
                <i class="bi bi-chat-dots"></i> Hỗ Trợ Khách Hàng
            </h2>
            <p class="text-muted">
                Bạn có câu hỏi hoặc cần hỗ trợ? Hãy gửi tin nhắn cho chúng tôi. 
                Chúng tôi sẽ phản hồi trong vòng 24 giờ.
            </p>
        </div>
    </div>
    
    <!-- Nút Gửi Tin Nhắn -->
    <div class="row mb-4">
        <div class="col-12">
            <button class="btn btn-primary btn-lg" data-bs-toggle="modal" data-bs-target="#sendMessageModal">
                <i class="bi bi-chat-quote"></i> Gửi Tin Nhắn
            </button>
        </div>
    </div>
    
    <!-- Danh Sách Tin Nhắn -->
    <?php if (empty($data['messages'])): ?>
        <div class="row">
            <div class="col-12">
                <div class="alert alert-info text-center py-5">
                    <i class="bi bi-inbox" style="font-size: 2rem;"></i>
                    <h5 class="mt-3">Bạn chưa có tin nhắn nào</h5>
                    <p class="text-muted">Gửi tin nhắn cho chúng tôi để nhận hỗ trợ</p>
                </div>
            </div>
        </div>
    <?php else: ?>
        <div class="row g-3">
            <?php foreach ($data['messages'] as $msg): ?>
                <div class="col-12">
                    <div class="card shadow-sm">
                        <div class="card-header bg-light d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-1"><?= htmlspecialchars($msg['user_name']) ?></h6>
                                <small class="text-muted">
                                    <i class="bi bi-calendar"></i> 
                                    <?= date('d/m/Y H:i', strtotime($msg['created_at'])) ?>
                                </small>
                            </div>
                            <div>
                                <?php if ($msg['status'] === 'pending'): ?>
                                    <span class="badge bg-warning">
                                        <i class="bi bi-hourglass-split"></i> Chờ phản hồi
                                    </span>
                                <?php elseif ($msg['status'] === 'responded'): ?>
                                    <span class="badge bg-success">
                                        <i class="bi bi-check-circle"></i> Đã phản hồi
                                    </span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">
                                        <i class="bi bi-lock"></i> Đã đóng
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <div class="card-body">
                            <!-- Tin Nhắn Của User -->
                            <div class="mb-3">
                                <h6 class="fw-bold text-primary">Tin nhắn của bạn:</h6>
                                <p class="text-dark"><?= nl2br(htmlspecialchars($msg['message'])) ?></p>
                            </div>
                            
                            <!-- Phản Hồi (nếu có) -->
                            <?php if ($msg['status'] === 'responded' && $msg['response_text']): ?>
                                <hr>
                                <div class="alert alert-success alert-dismissible fade show">
                                    <h6 class="fw-bold text-success">
                                        <i class="bi bi-chat-left-text"></i> Phản hồi từ chúng tôi:
                                    </h6>
                                    <p class="mb-0 mt-2">
                                        <?= nl2br(htmlspecialchars($msg['response_text'])) ?>
                                    </p>
                                    <?php if ($msg['responded_at']): ?>
                                        <small class="text-muted d-block mt-2">
                                            <i class="bi bi-clock"></i> 
                                            <?= date('d/m/Y H:i', strtotime($msg['responded_at'])) ?>
                                        </small>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<!-- Modal Gửi Tin Nhắn -->
<div class="modal fade" id="sendMessageModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="bi bi-chat-quote"></i> Gửi Tin Nhắn
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            
            <div class="modal-body">
                <form id="chatboxForm">
                    <div class="mb-3">
                        <label class="form-label">Tên của bạn <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="userName" 
                               value="<?= isset($_SESSION['user']) ? htmlspecialchars($_SESSION['user']['name'] ?? '') : '' ?>"
                               required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" class="form-control" id="userEmail"
                               value="<?= isset($_SESSION['user']) ? htmlspecialchars($_SESSION['user']['email'] ?? '') : '' ?>"
                               required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Tin nhắn <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="userMessage" rows="5" 
                                  placeholder="Nhập nội dung tin nhắn..." required></textarea>
                        <small class="text-muted">Tối đa 5000 ký tự</small>
                    </div>
                </form>
            </div>
            
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <button type="button" class="btn btn-primary" id="sendBtn" onclick="sendChatMessage()">
                    <i class="bi bi-send"></i> Gửi Tin Nhắn
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    // Gửi tin nhắn
    function sendChatMessage() {
        const name = document.getElementById('userName').value.trim();
        const email = document.getElementById('userEmail').value.trim();
        const message = document.getElementById('userMessage').value.trim();
        
        // Validate
        if (!name || !email || !message) {
            alert('Vui lòng điền đầy đủ thông tin!');
            return;
        }
        
        if (message.length > 5000) {
            alert('Tin nhắn quá dài (tối đa 5000 ký tự)');
            return;
        }
        
        const btn = document.getElementById('sendBtn');
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Đang gửi...';
        
        fetch('<?= APP_URL ?>/ChatboxController/send', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                name: name,
                email: email,
                message: message
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                document.getElementById('chatboxForm').reset();
                
                // Đóng modal
                const modal = bootstrap.Modal.getInstance(document.getElementById('sendMessageModal'));
                modal.hide();
                
                // Reload trang để hiển thị tin nhắn mới
                setTimeout(() => {
                    location.reload();
                }, 1000);
            } else {
                alert('Lỗi: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Có lỗi xảy ra. Vui lòng thử lại!');
        })
        .finally(() => {
            btn.disabled = false;
            btn.innerHTML = '<i class="bi bi-send"></i> Gửi Tin Nhắn';
        });
    }
</script>

<style>
    .card {
        border-left: 4px solid #0d6efd;
        transition: all 0.3s ease;
    }
    
    .card:hover {
        box-shadow: 0 10px 25px rgba(0,0,0,0.1) !important;
        transform: translateY(-2px);
    }
    
    .badge {
        font-weight: 500;
        padding: 6px 12px;
    }
</style>
