<?php
require_once 'notes.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(400);
    echo "Invalid request";
    exit;
}

$id = $_POST['id'] ?? null;

if ($id) {
    $stmt = $conn->prepare("UPDATE notes SET is_pin = NOT is_pin WHERE id = ?");
    $stmt->bind_param('i', $id);
    if ($stmt->execute()) {
        echo "Toggled pin state";
    } else {
        echo "Failed to update";
    }
} else {
    echo "Missing ID";
}
?>