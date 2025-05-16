<?php
if (isset($_FILES['newAvt']) && isset($_POST['id'])) {
    $file = $_FILES['newAvt'];
    $id = trim($_POST['id']); // làm sạch ID

    // Lấy đuôi file
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $extension = strtolower($extension);
    $allowedExts = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

    if (!in_array($extension, $allowedExts)) {
        echo "Chỉ chấp nhận ảnh có định dạng: jpg, png, jpeg, gif, webp.";
        // exit;
    }

    // Đường dẫn thư mục và file
    $uploadDir = '../templates/style/image/' . $id .'/';
    $fileName = 'avt.' . $extension;
    $targetFile = $uploadDir . $fileName;
    // echo "-------------" . $targetFile . "_________________";

    // Tạo thư mục nếu chưa tồn tại
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    // ✅ XÓA CÁC FILE "avt.*" TRONG THƯ MỤC (nếu có)
    foreach (glob($uploadDir . 'avt.*') as $oldFile) {
        unlink($oldFile);
    }

    // Lưu file mới
    if (move_uploaded_file($file['tmp_name'], $targetFile)) {
        echo "Cập nhật thành công!";
    } else {
        echo "Không thể lưu ảnh vào thư mục $uploadDir.";
    }
} else {
    echo "Thiếu file hoặc ID.";
}

?>