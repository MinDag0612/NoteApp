<?php
require_once 'connect.php'; // Kết nối CSDL

header('Content-Type: application/json');

$q = $_GET['q'] ?? '';

if ($q === '') {
    echo json_encode([]);
    exit;
}

$stmt = $conn->prepare("SELECT id, title, content FROM notes WHERE title LIKE CONCAT('%', ?, '%') OR content LIKE CONCAT('%', ?, '%') LIMIT 10");
$stmt->bind_param("ss", $q, $q);
$stmt->execute();

$result = $stmt->get_result();
$notes = [];

while ($row = $result->fetch_assoc()) {
    $notes[] = $row;
}

echo json_encode($notes);
?>
