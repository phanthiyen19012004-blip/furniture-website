<?php
// Kết nối CSDL
$conn = new mysqli("localhost", "root", "", "website");
$conn->set_charset("utf8");
if ($conn->connect_error) {
    die("Lỗi kết nối: " . $conn->connect_error);
}

$sql = "SELECT content FROM messages ORDER BY id DESC LIMIT 1";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
?>

<marquee behavior="scroll" direction="left">
    <?php echo htmlspecialchars($row['content']); ?>
</marquee>
