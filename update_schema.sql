-- Add verification token column
ALTER TABLE reservations ADD COLUMN verification_token VARCHAR(255) NULL AFTER email;

-- Modify enum to support the new unverified status
ALTER TABLE reservations MODIFY COLUMN status ENUM('unverified', 'pending', 'approved', 'rejected') NOT NULL DEFAULT 'unverified';
