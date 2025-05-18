<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $title = $_POST['title'];
    $content = $_POST['content'];

    // Lưu nội dung ghi chú vào database (nếu cần, không đề cập ở đây)

    // Lưu ảnh nếu có
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = __DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "statics" . DIRECTORY_SEPARATOR . $id;
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        // Lấy số lượng ảnh hiện tại trong thư mục để xác định thứ tự mới
        $existingImages = glob("$uploadDir/{$id}_*.{jpg,jpeg,png,gif}", GLOB_BRACE);
        $nextIndex = count($existingImages) + 1;

        // Lấy phần mở rộng file
        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $ext = strtolower($ext);

        $newFileName = "{$id}_{$nextIndex}." . $ext;
        $targetPath = "$uploadDir/$newFileName";

        // Di chuyển file
        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
            echo json_encode(['status' => 'success', 'filename' => $newFileName]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Không thể lưu ảnh']);
        }
    } else {
        // Không có ảnh, vẫn lưu ghi chú
        echo json_encode(['status' => 'success']);
    }
}
?>
