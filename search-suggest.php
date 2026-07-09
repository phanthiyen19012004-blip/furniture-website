<?php
$conn = new mysqli("localhost", "root", "", "website");
$conn->set_charset("utf8");

$term = $_GET['term'] ?? '';

if ($term !== '') {
  $stmt = $conn->prepare("SELECT id, name FROM product WHERE name LIKE CONCAT('%', ?, '%') LIMIT 10");
  $stmt->bind_param("s", $term);
  $stmt->execute();
  $result = $stmt->get_result();

  $suggestions = [];
  while ($row = $result->fetch_assoc()) {
    $suggestions[] = ['id' => $row['id'], 'name' => $row['name']];
  }

  header('Content-Type: application/json');
  echo json_encode($suggestions);
}
?>
