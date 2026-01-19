-- Add moderation fields to tblreview table
-- Fields to add for content moderation system

ALTER TABLE tblreview ADD COLUMN IF NOT EXISTS ly_do_tu_choi VARCHAR(500) NULL COMMENT 'Reason for rejection';
ALTER TABLE tblreview ADD COLUMN IF NOT EXISTS moderation_status ENUM('pending', 'approved', 'rejected', 'spam') DEFAULT 'pending' COMMENT 'Moderation status';
ALTER TABLE tblreview ADD COLUMN IF NOT EXISTS moderated_by INT NULL COMMENT 'Admin user ID who moderated';
ALTER TABLE tblreview ADD COLUMN IF NOT EXISTS moderation_date DATETIME NULL COMMENT 'When content was moderated';
ALTER TABLE tblreview ADD COLUMN IF NOT EXISTS flagged_as_spam INT DEFAULT 0 COMMENT 'Auto-detected spam score';
ALTER TABLE tblreview ADD COLUMN IF NOT EXISTS contains_prohibited_words INT DEFAULT 0 COMMENT 'Contains prohibited words';
ALTER TABLE tblreview ADD COLUMN IF NOT EXISTS moderation_notes TEXT NULL COMMENT 'Additional notes from moderator';

-- Create index for better query performance
CREATE INDEX IF NOT EXISTS idx_moderation_status ON tblreview(moderation_status);
CREATE INDEX IF NOT EXISTS idx_moderated_date ON tblreview(moderation_date);
