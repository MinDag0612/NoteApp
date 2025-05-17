<?php
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    require 'vendor/autoload.php';
    require "auth.php";
    require 'api/notes.php';
    // echo $reqVerify;

    $loader = new \Twig\Loader\FilesystemLoader('templates');
    $twig = new \Twig\Environment($loader);

    $name = null;
    $verify = $_SESSION['reqVerify'] ?? null;
    $name = $_SESSION['infor']['name'];
    $email = $_SESSION['infor']['email'];

    $id = $_SESSION['infor']['id'] ?? $_POST['id'];
    
    $folder = 'templates/style/image/' . $id . '/';
    

    $files = glob($folder . '*');
    $avatarPath = 'templates/style/image/default.jpg'; // fallback mặc định
    // echo json_encode($folder);

    if (!empty($files)) {
        $fileName = basename($files[0]);
        $avatarPath = 'templates/style/image/' . $id . '/' . $fileName;
        // echo  $avatarPath;
    }
    

    // $notes = [
    //     ['title' => 'Công việc hôm nay', 'content' => 'Hoàn thành bài toán phân tích hệ thống và gửi báo cáo.'],
    //     ['title' => 'Mua sắm', 'content' => 'Mua sữa, trứng, bánh mì và rau xanh.'],
    //     ['title' => 'Lịch họp', 'content' => 'Họp nhóm với team vào lúc 14:00 tại phòng Zoom.'],
    //     ['title' => 'Ý tưởng mới', 'content' => 'Tạo một app nhắc nhở học tập cho sinh viên kèm phân tích dữ liệu.'],
    //     ['title' => 'Chế độ ăn', 'content' => 'Ăn kiêng low-carb, uống đủ nước, tránh đồ ngọt.'],
    //     ['title' => 'Lịch tập gym', 'content' => 'Thứ 2: Ngực, Thứ 4: Lưng, Thứ 6: Chân.'],
    //     ['title' => 'Nhật ký', 'content' => 'Hôm nay trời nắng, đi dạo ở công viên rất thư giãn.']
    // ];
    $notes = get_notes($email);

    echo $twig->render('home.html.twig', [
        'name' => $_SESSION['infor']['name'],
        'verify' => $_SESSION['reqVerify'] ?? null,
        'email' => $email,
        'id' => $id,
        'avt' => $avatarPath,
        'notes' => $notes

    ]);
?>