<?php
require_once 'notes.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(400);
    echo "Invalid request";
    exit;
}

$id = $_POST['id'] ?? null;

?>