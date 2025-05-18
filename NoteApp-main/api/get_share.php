<?php
require_once 'share_note.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        'success' => false,
        'message' => 'Phương thức yêu cầu không hợp lệ. Vui lòng sử dụng POST.'
    ]);
    return;
}

if (!isset($_POST['email']) || empty(trim($_POST['email']))) {
    echo json_encode([
        'success' => false,
        'message' => 'Email không được gửi hoặc bị rỗng.'
    ]);
    return;
}

$email = trim($_POST['email']);
$data = get_notes($email);

echo json_encode([
    'success' => true,
    'notes' => $data
]);