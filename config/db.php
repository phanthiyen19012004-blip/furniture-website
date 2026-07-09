<?php
$conn = new mysqli("localhost", "root", "", "website");

if ($conn->connect_error) {
    die("Kết nối CSDL thất bại: " . $conn->connect_error);
}
?>
