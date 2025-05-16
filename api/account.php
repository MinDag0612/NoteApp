<?php
    require_once 'connect.php';


    function checkMail($email){
        global $conn;
        $queryMail = $conn->prepare('SELECT * FROM account WHERE email = ?');
        $queryMail->bind_param("s", $email);
        $queryMail->execute();

        $queryMail->store_result(); 
        return $queryMail->num_rows > 0;
    }

    function storeData($infor, $token){
        global $conn;
        $hashed = password_hash($infor['pass'], PASSWORD_BCRYPT);

        $queryStore = $conn->prepare('INSERT INTO account (name, email, password, token) VALUES (?, ?, ?, ?)');
        $queryStore->bind_param('ssss', $infor['name'], $infor['email'], $hashed, $token);

        if ($queryStore->execute()) {
            // sendVerify($infor['email'], $infor['name'], $token);
            return true;
        } else {
            return false;
        }
    }

    function checkLogin($email, $pass){
        global $conn;
        
        $query = $conn->prepare('SELECT * FROM account WHERE email = ?');
        $query->bind_param('s', $email);
        $query->execute();

        $result = $query->get_result();
        $row = $result->fetch_assoc();
        
        if(!$row){
            return false;
        }

        $passFromDB = $row['password'];

        if(password_verify($pass, $passFromDB)){
            return true;
        }
        return false;
        
    }

    function getData($email){
        global $conn;
        
        $query = $conn->prepare('SELECT * FROM account WHERE email = ?');
        $query->bind_param('s', $email);
        $query->execute();

        $result = $query->get_result();
        $row = $result->fetch_assoc();
        return $row;
    }

    function updatePassword($email, $newPass){
        global $conn;
        $hashed = password_hash($newPass, PASSWORD_BCRYPT);

        $query = $conn->prepare('UPDATE account SET password = ? WHERE email = ?');
        $query->bind_param('ss', $hashed, $email);
        if ($query->execute()) {
            return true;
        } else {
            return false;
        }
    }

    function updateProfile($currEmail, $email, $name) {
        global $conn;

        $check = $conn->prepare('SELECT COUNT(*) FROM account WHERE email = ? AND email != ?');
        $check->bind_param('ss', $email, $currEmail);
        $check->execute();
        $check->bind_result($count);
        $check->fetch();
        $check->close();

        if ($count > 0) {
            echo 'Email đã tồn tại.';
        } else {
            $query = $conn->prepare('UPDATE account SET name = ?, email = ? WHERE email = ?');
            $query->bind_param('sss', $name, $email, $currEmail);
            if ($query->execute()) {
                echo 'Cập nhật thành công.';
            } else {
                echo 'Có lỗi xảy ra khi cập nhật.';
            }
            $query->close();
        }
    }
?>