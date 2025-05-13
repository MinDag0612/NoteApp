<?php
    require_once 'notes.php';

    if ($_SERVER['REQUEST_METHOD'] != "POST"){
        echo "REQUEST METHOD NOT ACCESS";
        return;
    }

    if (isset($_POST['email'])) {
        $email = $_POST['email'];
        
        echo json_encode(get_notes($email));
    } else {
        echo "Email không được gửi";
    }
?>
