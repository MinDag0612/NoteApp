<?php
    require_once 'account.php';
    session_start();

    if ($_SERVER['REQUEST_METHOD'] == "POST"){
        $currEmail = $_POST['currEmail'] ?? '';
        $newName = $_POST['newName'] ?? '';
        $newEmail = $_POST['newEmail'] ?? '';

        if ($updated = updateProfile($currEmail, $newEmail, $newName)) {
            session_unset();
            session_destroy();
            echo 'Cập nhật thành công. Đăng nhập lại để xác nhận thay đổi';
        } else {
            // echo 'Email đã tồn tại hoặc cập nhật thất bại';
        }
    } else {
        echo 'Phương thức không hợp lệ';
    }
?>
