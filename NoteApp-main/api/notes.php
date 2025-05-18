<?php
require_once 'connect.php';
// require 'vendor/autoload.php'; 

function get_notes($email)
{
    global $conn;

    $query = $conn->prepare("SELECT * FROM notes WHERE email = ? ORDER BY is_pin DESC, id DESC");
    $query->bind_param('s', $email);
    $query->execute();

    $result = $query->get_result();
    $data = $result->fetch_all(MYSQLI_ASSOC);  // Mảng các ghi chú

    return  $data;
}

function save_note($id, $title, $content, $is_protected = null, $password = null) {
    global $conn;

    if ($is_protected !== null) {
        // Nếu muốn thay đổi bảo vệ hoặc mật khẩu
        $password_hash = $is_protected && $password ? password_hash($password, PASSWORD_DEFAULT) : null;
        $query = $conn->prepare('UPDATE notes SET title = ?, content = ?, is_protected = ?, password_hash = ? WHERE id = ?');
        $query->bind_param('ssisi', $title, $content, $is_protected, $password_hash, $id);
    } else {
        // Không thay đổi bảo vệ/mật khẩu
        $query = $conn->prepare('UPDATE notes SET title = ?, content = ? WHERE id = ?');
        $query->bind_param('ssi', $title, $content, $id);
    }

    return $query->execute();
}

function new_note($email, $is_protected = 0, $password = null) {
    global $conn;

    $title = "Title";
    $content = "Content";
    $password_hash = $is_protected && $password ? password_hash($password, PASSWORD_DEFAULT) : null;

    $query = $conn->prepare('INSERT INTO notes (title, content, email, is_protected, password_hash) VALUES (?, ?, ?, ?, ?)');
    $query->bind_param('sssis', $title, $content, $email, $is_protected, $password_hash);

    if ($query->execute()) {
        $id = $conn->insert_id;
        return [
            'id' => $id,
            'title' => $title,
            'content' => $content
        ];
    } else {
        return false;
    }
}

function check_password($id, $input_password) {
    global $conn;

    $query = $conn->prepare('SELECT password_hash FROM notes WHERE id = ?');
    $query->bind_param('i', $id);
    $query->execute();
    $result = $query->get_result()->fetch_assoc();

    if (!$result) return false;

    return password_verify($input_password, $result['password_hash']);
}

function remove_note($id) {
    global $conn;

    $query = $conn->prepare("DELETE FROM `notes` WHERE id = ?");
    $query->bind_param("i", $id);
    $query->execute();
    if ($query->execute()) {
        return true;
        echo "Đã xóa:" . $id;
    } else {
        return false;
    }
}

?>
