<?php
require_once 'connect.php'; // Kết nối CSDL

header('Content-Type: application/json');

$q = $_GET['q'] ?? '';
$email = $_GET['email'] ?? '';
// echo $email;
if ($q === '') {
    echo json_encode([]);
    exit;
}

$stmt = $conn->prepare("SELECT id, title, content FROM notes WHERE (title LIKE CONCAT('%', ?, '%') OR content LIKE CONCAT('%', ?, '%')) and email = ? LIMIT 10");
$stmt->bind_param("sss", $q, $q, $email);
$stmt->execute();

$result = $stmt->get_result();
$notes = [];

while ($row = $result->fetch_assoc()) {
    $notes[] = $row;
}

echo json_encode($notes);
?>
