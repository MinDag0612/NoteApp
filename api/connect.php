<?php
    $host = getenv('DB_HOST') ?: 'localhost';
    $db   = getenv('DB_NAME') ?: 'noteapp_db';
    $user = getenv('DB_USER') ?: 'root';
    $pass = getenv('DB_PASS') ?: '';

    // $host = 'db'; // không cần getenv nếu cố định
    // $db   = 'noteapp_db';
    // $user = 'noteapp_user';
    // $pass = 'noteapp_password';


    // $host = 'db';     
    // $db   = 'final_se';       
    // $user = 'user';           
    // $pass = 'password';

    $conn = new mysqli($host, $user, $pass, $db);

    if ($conn->connect_error){
        die("Connect faile". $conn -> connect_error);
    }

    // echo("Connect success");
?>