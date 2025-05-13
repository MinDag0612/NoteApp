<?php
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    require 'vendor/autoload.php';
    require 'api/account.php';

    $loader = new \Twig\Loader\FilesystemLoader('templates');
    $twig = new \Twig\Environment($loader);

    $data = [
        'email' => $_POST['email'] ?? '',
        'pass' =>  $_POST['password'] ?? ''
    ];
    $error = $_SESSION['regLogin'] ?? null;

    if ($_SERVER['REQUEST_METHOD'] == "POST"){
        $email = $_POST['email'];
        $pass = $_POST['password'];

        if(!checkLogin($email, $pass)){
            $error = "Email or password is not correct";
            echo $twig->render('login.html.twig', [
                'error' => $error,
                'data' => $data
            ]);
            return;
        }
        
        $_SESSION['login'] = "done";
        $_SESSION['infor'] = getData($data['email']);
        header('location: home.php');
        exit();
    }
    else{
        echo $twig->render('login.html.twig', [
            'error' => $error,
            'data' => $data
        ]);
    }

    
?>