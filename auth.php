<?php
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    $reqVerify = null;

    if (!isset($_SESSION['login']) || $_SESSION['login'] != 'done') {
        $_SESSION['regLogin'] = "Please login first";
        header("Location: login.php");
        exit();
    }
    
    if ($_SESSION['infor']['verify'] == 0){
        $_SESSION['reqVerify'] = 'none';
    }
    else{
        unset($_SESSION['reqVerify']);
    }
?>