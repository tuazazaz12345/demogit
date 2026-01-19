-- ============================================
-- Content Moderation System - Database Migration
-- ============================================

-- Kiểm tra và thêm các cột vào bảng tblreview
-- Chạy file này để setup database cho hệ thống kiểm duyệt nội dung

SET FOREIGN_KEY_CHECKS=0;

-- Thêm các cột nếu chưa tồn tại
ALTER TABLE tblreview 
ADD COLUMN IF NOT EXISTS ly_do_tu_choi VARCHAR(500) NULL COMMENT 'Reason for rejection',
ADD COLUMN IF NOT EXISTS moderation_status ENUM('pending', 'approved', 'rejected', 'spam') DEFAULT 'pending' COMMENT 'Moderation status',
ADD COLUMN IF NOT EXISTS moderated_by INT NULL COMMENT 'Admin user ID who moderated',
ADD COLUMN IF NOT EXISTS moderation_date DATETIME NULL COMMENT 'When content was moderated',
ADD COLUMN IF NOT EXISTS flagged_as_spam INT DEFAULT 0 COMMENT 'Auto-detected spam score (0-100)',
ADD COLUMN IF NOT EXISTS contains_prohibited_words INT DEFAULT 0 COMMENT 'Contains prohibited words flag',
ADD COLUMN IF NOT EXISTS moderation_notes TEXT NULL COMMENT 'Additional notes from moderator';

-- Tạo indexes cho hiệu suất query
CREATE INDEX IF NOT EXISTS idx_moderation_status ON tblreview(moderation_status);
CREATE INDEX IF NOT EXISTS idx_moderated_date ON tblreview(moderation_date);
CREATE INDEX IF NOT EXISTS idx_trangthai ON tblreview(trangthai);
CREATE INDEX IF NOT EXISTS idx_masp_status ON tblreview(masp, moderation_status);

-- Cập nhật các review cũ để đặt trạng thái phù hợp
-- Nếu trangthai = 'đã duyệt' thì moderation_status = 'approved'
-- Nếu trangthai = 'chờ duyệt' thì moderation_status = 'pending'
UPDATE tblreview 
SET moderation_status = CASE 
    WHEN trangthai = 'đã duyệt' THEN 'approved'
    WHEN trangthai = 'chờ duyệt' THEN 'pending'
    ELSE 'pending'
END
WHERE moderation_status = 'pending';

SET FOREIGN_KEY_CHECKS=1;

-- Verify the changes
SELECT 
    COUNT(*) as total_reviews,
    SUM(CASE WHEN moderation_status = 'pending' THEN 1 ELSE 0 END) as pending,
    SUM(CASE WHEN moderation_status = 'approved' THEN 1 ELSE 0 END) as approved,
    SUM(CASE WHEN moderation_status = 'rejected' THEN 1 ELSE 0 END) as rejected,
    SUM(CASE WHEN moderation_status = 'spam' THEN 1 ELSE 0 END) as spam
FROM tblreview;
