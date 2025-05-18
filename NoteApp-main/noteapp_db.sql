-- Tạo cơ sở dữ liệu nếu chưa tồn tại
CREATE DATABASE IF NOT EXISTS `noteapp_db`;
USE `noteapp_db`;

-- Tạo bảng `account`
CREATE TABLE `account` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `verify` tinyint(1) NOT NULL DEFAULT 0,
  `token` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Chèn dữ liệu vào bảng `account`
INSERT INTO `account` (`name`, `email`, `password`, `verify`, `token`) VALUES
('Tôn Minh Đăng', 'tonminhdang9@gmail.com', '$2y$10$JUiHz43/SRCkli7VeH.Epe3Ju9JAjRTBts2CiKSHmlePZ6wgosyEi', 1, '0');

-- Tạo bảng `notes`
CREATE TABLE `notes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `content` text DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `is_pin` tinyint(1) NOT NULL DEFAULT 0,
   `is_protected` Boolean DEFAULT 0,
  `password_hash` varchar(255),
  `share` boolean default 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `share_notes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `share_email` varchar(255) NOT NULL,
  `edit` boolean default 0,
  `note_id` int(11),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Chèn dữ liệu vào bảng `notes`
INSERT INTO `notes` (`title`, `content`, `email`, `created_at`, `updated_at`, `is_pin`) VALUES
('Mua sắm 1 2', 'Mua sữa, trứng, bánh mì và rau xanh.', 'tonminhdang9@gmail.com', '2025-05-12 02:17:29', '2025-05-13 22:55:59', 0),
('Lịch họp', 'Họp nhóm với team vào lúc 14:00 tại phòng Zoom.', 'tonminhdang9@gmail.com', '2025-05-12 02:17:29', '2025-05-13 22:56:38', 0),
('Ý tưởng mới', 'Tạo một app nhắc nhở học tập cho sinh viên kèm phân tích dữ liệu.', 'tonminhdang9@gmail.com', '2025-05-12 02:17:29', '2025-05-13 22:56:39', 0),
('Chế độ ăn 12', 'Ăn kiêng low-carb, uống đủ nước, tránh đồ ngọt.', 'tonminhdang9@gmail.com', '2025-05-12 02:17:29', '2025-05-13 22:56:43', 0),
('Lịch tập gym', 'Thứ 2: Ngực, Thứ 4: Lưng, Thứ 6: Chân.', 'tonminhdang9@gmail.com', '2025-05-12 02:17:29', '2025-05-13 22:56:40', 0);

-- Lưu ý: Không cần `ALTER TABLE` thêm khóa chính nữa, vì đã khai báo khóa chính ngay trong `CREATE TABLE`.

COMMIT;

