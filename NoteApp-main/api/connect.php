<?php
    // $host = getenv('DB_HOST') ?: 'localhost';
    // $db   = getenv('DB_NAME') ?: 'noteapp_db';
    // $user = getenv('DB_USER') ?: 'root';
    // $pass = getenv('DB_PASS') ?: '';

    $host = 'localhost'; // không cần getenv nếu cố định
    $db   = 'my_database';
    $user = 'root';
    $pass = '';


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