<?php
require_once 'notes.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
    return;
}

$email = $_POST['email'] ?? null;

if ($email) {
    $note = new_note($email); // Trả về mảng chứa id, title, content

    if ($note) {
        echo json_encode([
            'success' => true,
            'id' => $note['id'],
            'title' => $note['title'],
            'content' => $note['content']
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to create note']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Missing email']);
}
