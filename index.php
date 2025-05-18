<?php
    require 'vendor/autoload.php';
    require_once 'api/account.php';
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    session_start();
    function sendVerify($toEmail, $toName, $token) {
        $mail = new PHPMailer(true);

        try {
            // Cấu hình SMTP
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com'; 
            $mail->SMTPAuth = true;
            $mail->Username = 'tonminhdang9@gmail.com';
            $mail->Password = 'vccc wyda qmpc tucj';
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;
            $mail->CharSet = 'UTF-8';
            $mail->Encoding = 'base64';

            // Gửi từ đâu
            $mail->setFrom('tonminhdang9@gmail.com', 'NOTE APP');
            $mail->addAddress($toEmail, $toName);

            // Tự động lấy đường dẫn hiện tại
            $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';
            $host = $_SERVER['HTTP_HOST']; // ví dụ: localhost:8000 hoặc domain.com
            $scriptDir = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
            $verifyLink = $protocol . $host . $scriptDir . '/verify.php?token=' . urlencode($token);

            // Nội dung
            $mail->isHTML(true);
            $mail->Subject = 'NOTE APP: Xác thực tài khoản';
            $mail->Body = '
                <div style="font-family: Arial, sans-serif; font-size: 16px; color: #333;">
                <p>Chào ' . htmlspecialchars($toName) . ',</p>
                <p>Nhấn vào liên kết sau để xác thực tài khoản của bạn:</p>
                <p><a href="' . $verifyLink . '" style="color: #1a73e8;">Xác thực</a></p>
                <p>Trân trọng,<br>NOTE APP</p>
            </div>';
            $mail->send();
            return true;
        } catch (Exception $e) {
            // Ghi log nếu cần: error_log($mail->ErrorInfo);
            return false;
        }
    }


    $loader = new \Twig\Loader\FilesystemLoader('templates');
    $twig = new \Twig\Environment($loader);

    $data = [
        'name' => $_POST['name'] ?? '',
        'email' => $_POST['email'] ?? '',
        'pass' =>  $_POST['password'] ?? '',
        'conPass' => $_POST['conPass'] ?? ''
    ];

    $error = null;
    $success = null;

    if ($_SERVER['REQUEST_METHOD'] === 'POST'){
        if ($_POST['password'] != $_POST['conPass']){
            $error = "Passwords do not match !";
        }
        elseif (checkMail($data['email'])){
            $error = "Email already exist !";
        }

        if ($error === null){
            $token = bin2hex(random_bytes(16));
            if (storeData($data, $token)){
                sendVerify($data['email'], $data['name'], $token);

                $_SESSION['login'] = "done";
                $_SESSION['infor'] = getData($data['email']);
                header('location: home.php');
                exit();
            }
        }

        echo $twig->render('index.html.twig', [
            'error' => $error,
            'success' => $success,
            'data' => $data
        ]);
    }
    else{
        echo $twig->render('index.html.twig');
    }
?>