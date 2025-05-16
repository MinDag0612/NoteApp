<?php
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    function sendOTP($toEmail, $toName, $otp) {
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

            // Nội dung
            $mail->isHTML(true);
            $mail->Subject = 'NOTE APP: Mã xác thực OTP';
            $mail->Body = '
                <div style="font-family: Arial, sans-serif; font-size: 16px; color: #333;">
                    <p>Chào ' . htmlspecialchars($toName) . ',</p>
                    <p>Mã xác thực OTP của bạn là:</p>
                    <h2 style="color: #1a73e8;">' . htmlspecialchars($otp) . '</h2>
                    <p>Mã này sẽ hết hạn sau vài phút. Vui lòng không chia sẻ với bất kỳ ai.</p>
                    <p>Trân trọng,<br>NOTE APP</p>
                </div>';
            $mail->send();
            return true;
        } catch (Exception $e) {
            return false;
        }
    }


    require 'vendor/autoload.php';
    require_once 'api/account.php';

    $loader = new \Twig\Loader\FilesystemLoader('templates');
    $twig = new \Twig\Environment($loader);

    if($_SERVER['REQUEST_METHOD'] == "POST"){
        if (isset($_POST['send_otp'])) {
            $email = $_POST['email'];
            $user = getData($email);
            if ($user != null) {
                $otp = rand(100000, 999999);
                $_SESSION['otp'] = $otp;
                $_SESSION['reset_email'] = $email;
                $sent = sendOTP($email, $user['name'], $otp);
                if ($sent) {
                    $message = "OTP đã được gửi tới email của bạn.";
                    $showOtpForm = true;
                    echo $twig->render('forgot.html.twig', [
                        'show_otp_form' => 'true',
                        'email' => $email
                    ]);
                } else {
                    $message = "Không thể gửi email. Vui lòng thử lại.";
                    echo $twig->render('forgot.html.twig', [
                        'error' => $message
                    ]);
                }
            } else {
                $message = "Email không tồn tại trong hệ thống.";
                echo $twig->render('forgot.html.twig', [
                    'error' => $message
                ]);
            }
        }
        elseif (isset($_POST['verify_otp'])) {
            $email = $_POST['email'];
            $enteredOtp = $_POST['otp'];
            $newPass = $_POST['new_password'];
            $success = null;
            $error = null;

            if ($_SESSION['otp'] == $enteredOtp) {
                $email = $_SESSION['reset_email'];
                $success = "Mật khẩu đã được đặt lại thành công.";
                updatePassword($email, $newPass);
                unset($_SESSION['otp'], $_SESSION['reset_email']);
            } else {
                $error = "OTP không đúng. Vui lòng thử lại.";
                $showOtpForm = true;
            }
            echo $twig->render('forgot.html.twig', [
                'show_otp_form' => 'true',
                'email' => $email,
                'error' => $error,
                'success' => $success
            ]);
        }

        
    }
    else{
        echo $twig->render('forgot.html.twig');
    }

    
?>