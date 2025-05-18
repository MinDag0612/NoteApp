<?php
    require_once 'notes.php';

    if ($_SERVER['REQUEST_METHOD'] != "POST"){
        echo "REQUEST METHOD NOT ACCESS";
        return;
    }

    $id = $_POST['id'] ?? null;

    remove_note($id);
?>
