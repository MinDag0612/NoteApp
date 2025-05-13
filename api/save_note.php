<?php
    require_once 'notes.php';

    if ($_SERVER['REQUEST_METHOD'] != "POST"){
        echo "REQUEST METHOD NOT ACCESS";
        return;
    }

    $id = $_POST['id'] ?? null;
    $title = $_POST['title'] ?? null;
    $content = $_POST['content'] ?? null;

    save_note($id, $title, $content);
?>
