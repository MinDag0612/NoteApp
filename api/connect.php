<?php
    $host = getenv('MYSQL_HOST') ?: 'localhost';
    $db   = getenv('MYSQL_DATABASE') ?: 'noteapp_db';
    $user = getenv('MYSQL_USER') ?: 'root';
    $pass = getenv('MYSQL_PASSWORD') ?: '';

    // $host = 'db'; // không cần getenv nếu cố định
    // $db   = 'noteapp_db';
    // $user = 'noteapp_user';
    // $pass = 'noteapp_password';


    $conn = new mysqli($host, $user, $pass, $db);

    if ($conn->connect_error){
        die("Connect faile". $conn -> connect_error);
    }

    // echo("Connect success");
?>