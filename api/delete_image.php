<?php
    header('Content-Type: application/json');
    $data = json_decode(file_get_contents('php://input'), true);

    if (isset($data['id']) && isset($data['image'])) {
        $id = $data['id'];
        $image = $data['image'];

        // Đường dẫn tới ảnh
        $imagePath = __DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "statics" . DIRECTORY_SEPARATOR . $id . DIRECTORY_SEPARATOR .$image;

        // Kiểm tra và xóa ảnh
        if (file_exists($imagePath)) {
            if (unlink($imagePath)) {
                echo json_encode(["status" => "success", "message" => "Ảnh đã được xóa."]);
            } else {
                echo json_encode(["status" => "error", "message" => "Không thể xóa ảnh."]);
            }
        } else {
            echo json_encode(["status" => "error", "message" => "Ảnh không tồn tại."]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Dữ liệu không hợp lệ."]);
    }
?>
