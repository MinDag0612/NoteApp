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

function save_note($id, $title, $content)
{
    global $conn;

    $query = $conn->prepare('UPDATE notes SET title = ?, content = ? WHERE id = ?');
    $query->bind_param('ssi', $title, $content, $id); 
    if ($query->execute()) {
        return true;
    } else {
        return false;
    }
}

function new_note($email){
    global $conn;

    $title = "Title";
    $content = "Content";

    $query = $conn->prepare('INSERT INTO notes (title, content, email) VALUES (?, ?, ?)');
    $query->bind_param('sss', $title, $content, $email); 

    if ($query->execute()) {
        $id = $conn->insert_id; // Lấy id vừa chèn
        return [
            'id' => $id,
            'title' => $title,
            'content' => $content
        ];
    } else {
        return false;
    }
}

function deleteFolder($folder) {
    if (!is_dir($folder)) return false;

    $files = array_diff(scandir($folder), ['.', '..']);
    foreach ($files as $file) {
        $filePath = $folder . DIRECTORY_SEPARATOR . $file;
        if (is_dir($filePath)) {
            deleteFolder($filePath); // gọi đệ quy nếu là thư mục con
        } else {
            unlink($filePath);
        }
    }
    return rmdir($folder); // cuối cùng xóa thư mục chính
}

function remove_note($id) {
    global $conn;
    $folderPath = __DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "statics" . DIRECTORY_SEPARATOR . $id;

    $query = $conn->prepare("DELETE FROM `notes` WHERE id = ?");
    $query->bind_param("i", $id);
    if ($query->execute()) {
        if (file_exists($folderPath) && is_dir($folderPath)) {
            if (deleteFolder($folderPath)) {
                echo json_encode(["status" => "success", "message" => "Thư mục đã được xóa."]);
            } else {
                echo json_encode(["status" => "error", "message" => "Không thể xóa thư mục."]);
            }
        } else {
            echo json_encode(["status" => "error", "message" => "Thư mục không tồn tại."]);
        }
    } else {
        return false;
    }
}

?>
