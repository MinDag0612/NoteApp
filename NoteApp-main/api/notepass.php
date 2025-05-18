<?php

require_once 'connect.php';

// Chỉ chấp nhận POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Phương thức không hợp lệ']);
    exit;
}

// Nhận dữ liệu từ form
$id = $_POST['id'] ?? null;
$password = $_POST['password'] ?? '';
$action = $_POST['action'] ?? ''; // "lock" hoặc "unlock"

if (!$id || !$password || !in_array($action, ['lock', 'unlock'])) {
    echo json_encode(['success' => false, 'message' => 'Thiếu dữ liệu đầu vào']);
    exit;
}

// Xử lý tùy theo action
if ($action === 'lock') {
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("UPDATE notes SET is_protected = 1, password_hash = ? WHERE id = ?");
    $stmt->bind_param("si", $passwordHash, $id);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Ghi chú đã được khóa']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Không thể khóa ghi chú']);
    }

    $stmt->close();
    exit;
}

if ($action === 'unlock') {
    // Lấy hash hiện tại từ DB
    $stmt = $conn->prepare("SELECT password_hash FROM notes WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 0) {
        echo json_encode(['success' => false, 'message' => 'Ghi chú không tồn tại']);
        $stmt->close();
        exit;
    }

    $stmt->bind_result($storedHash);
    $stmt->fetch();

    // So sánh mật khẩu
    if (!password_verify($password, $storedHash)) {
        echo json_encode(['success' => false, 'message' => 'Mật khẩu không đúng']);
        $stmt->close();
        exit;
    }

    $stmt->close();

    // Mật khẩu đúng => mở khóa ghi chú
    $stmt = $conn->prepare("UPDATE notes SET is_protected = 0, password_hash = NULL WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Ghi chú đã được mở khóa']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Không thể mở khóa ghi chú']);
    }

    $stmt->close();
    exit;
}
?>