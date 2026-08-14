ALTER TABLE `student_fees_management`
ADD `is_skipped` TINYINT(1) NOT NULL DEFAULT 0 AFTER `is_monthly`;
