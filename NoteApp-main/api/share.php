<?php
require_once 'connect.php'; // file kết nối DB

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        'success' => false,
        'message' => 'Chỉ chấp nhận yêu cầu POST.'
    ]);
    exit;
}

// Lấy dữ liệu từ form
$id = $_POST['note_id'] ?? '';
$email = $_POST['share_email'] ?? '';
$action = $_POST['action'] ?? '';

if (empty($id) || empty($email) || empty($action)) {
    echo json_encode([
        'success' => false,
        'message' => 'Thiếu dữ liệu yêu cầu.'
    ]);
    exit;
}

global $conn;

// Xử lý link (chia sẻ) hoặc unlink (bỏ chia sẻ)
if ($action === 'link') {
    // Kiểm tra đã tồn tại chưa
    $check = $conn->prepare("SELECT * FROM note_shares WHERE note_id = ? AND email = ?");
    $check->bind_param('is', $id, $email);
    $check->execute();
    $result = $check->get_result();
    $stmt = $conn->prepare("UPDATE notes SET share = 0 WHERE id = ?");
    $stmt->bind_param("i", $id);
    if ($result->num_rows === 0) {
            $stmt->bind_param("i", $id);
        // Chưa tồn tại -> thêm mới
        $insert = $conn->prepare("INSERT INTO note_shares (note_id, email) VALUES (?, ?)");
        $insert->bind_param('is', $id, $email);
        if ($insert->execute()) {
            echo json_encode(['success' => true, 'message' => 'Đã chia sẻ ghi chú.']);
                $stmt->close();
        } else {
            echo json_encode(['success' => false, 'message' => 'Không thể chia sẻ ghi chú.']);
                $stmt->close();
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Ghi chú đã được chia sẻ với email này.']);
    }
} elseif ($action === 'unlink') {
    // Xóa chia sẻ
    $stmt = $conn->prepare("UPDATE notes SET share = 1,  WHERE id = ?");
    $stmt->bind_param("i", $id);

    $delete = $conn->prepare("DELETE FROM note_shares WHERE note_id = ? AND email = ?");
    $delete->bind_param('is', $id, $email);
    if ($delete->execute()) {
        echo json_encode(['success' => true, 'message' => 'Đã hủy chia sẻ ghi chú.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Không thể hủy chia sẻ.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Hành động không hợp lệ.']);
}