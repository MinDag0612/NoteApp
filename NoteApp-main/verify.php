<?php
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    require_once 'api/account.php';

    $token = $_GET['token'] ?? '';

    if ($token) {
        $stmt = $conn->prepare("UPDATE account SET verify = 1 WHERE token = ?");
        $stmt->bind_param("s", $token);
        $stmt->execute();

        $stmt1 = $conn->prepare("UPDATE account SET token = 0 WHERE token = ?");
        $stmt1->bind_param("s", $token);
        $stmt1->execute();

        if ($stmt->affected_rows > 0) {
            echo "Tài khoản đã được xác thực!";
        } else {
            echo "Token không hợp lệ hoặc đã xác thực trước đó.";
        }
    } else {
        echo "Không tìm thấy token.";
    }
?>
