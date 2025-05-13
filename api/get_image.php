<?php
    $id = $_GET['id']; 

    $dir = __DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "statics" . DIRECTORY_SEPARATOR . $id;

    // Tạo thư mục nếu chưa tồn tại
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }

    $images = [];
    $files = glob("$dir/*.{jpg,jpeg,png,gif}", GLOB_BRACE);
    foreach ($files as $file) {
        $images[] = basename($file);  
    }
    // echo $dir;
    // echo json_encode($files);

    echo json_encode($images);
?>
