<?php

require_once 'connect.php';

function delete_note_and_shares($note_id, $email) {
    global $conn;

    // Xoá ghi chú (nếu là chủ sở hữu)
    $stmt = $conn->prepare("DELETE FROM notes WHERE id = ? AND email = ?");
    $stmt->bind_param("is", $note_id, $email);
    $stmt->execute();

    // Tự tay xoá các bản ghi share tương ứng
    $stmt2 = $conn->prepare("DELETE FROM share_notes WHERE note_id = ?");
    $stmt2->bind_param("i", $note_id);
    $stmt2->execute();
}

function get_notes($user_id)
{
    global $conn;

    $query = $conn->prepare("SELECT * FROM notes WHERE user_id = ? ORDER BY is_pin DESC, id DESC");
    $query->bind_param('i', $user_id);  // 'i' cho số nguyên
    $query->execute();

    $result = $query->get_result();
    $data = $result->fetch_all(MYSQLI_ASSOC);

    return $data;
}
function share($share_email,$email,$id){
    global $conn;
    $query = $conn->prepare('INSERT INTO share_notes (share_email,email,share_id) VALUES ( ? ? ? )');
    $query->bind_param('ssi',$share_email, $email, $id);
        
    if ($query->execute()) {
        $id = $conn->insert_id;
        return;
    } else {
        return false;
    }
}
?>