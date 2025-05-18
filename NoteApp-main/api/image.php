<?php
// Đường dẫn thư mục đích
function handleFileUpload(string $inputName = 'fileUpload', string $subFolder = 'uploads'): array
{
    // Đường dẫn vật lý đến thư mục đích (2 tầng: static/<subFolder>/)
    $targetDir = __DIR__ . '/../statics/' . $subFolder . '/';

    // Tạo thư mục nếu chưa tồn tại
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0755, true);
    }

    // Kiểm tra file hợp lệ
    if (!isset($_FILES[$inputName]) || $_FILES[$inputName]['error'] !== UPLOAD_ERR_OK) {
        return ['success' => false, 'message' => 'Không có file hoặc xảy ra lỗi khi upload.'];
    }

    // Tạo tên file duy nhất
    $originalName = basename($_FILES[$inputName]['name']);
    $extension = pathinfo($originalName, PATHINFO_EXTENSION);
    $newFileName = uniqid('file_', true) . '.' . $extension;

    // Đường dẫn vật lý và URL tương đối
    $targetFile = $targetDir . $newFileName;
    $relativePath = "static/{$subFolder}/{$newFileName}";

    // Di chuyển file
    if (move_uploaded_file($_FILES[$inputName]['tmp_name'], $targetFile)) {
        return [
            'success' => true,
            'message' => 'Lưu thành công.',
            'filename' => $newFileName,
            'path' => $relativePath
        ];
    } else {
        return ['success' => false, 'message' => 'Lỗi khi lưu file.'];
    }
}

function getImagesFromFolder(string $subFolder): array
{
    $baseDir = __DIR__ . '/../statics/' . $subFolder . '/';

    // Kiểm tra tồn tại
    if (!is_dir($baseDir)) {
        return ['success' => false, 'message' => "Thư mục không tồn tại."];
    }

    // Lấy danh sách file ảnh
    $files = glob($baseDir . '*.{jpg,jpeg,png,gif,webp}', GLOB_BRACE);

    // Chuyển về đường dẫn tương đối để hiển thị
    $images = array_map(function ($filePath) use ($subFolder) {
        $fileName = basename($filePath);
        return "static/{$subFolder}/{$fileName}";
    }, $files);

    return [
        'success' => true,
        'count' => count($images),
        'images' => $images
    ];
}

echo json_encode(getImagesFromFolder("1"));

?>
