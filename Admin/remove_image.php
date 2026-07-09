<?php
include('../config/db.php');
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = (int)($_POST['product_id'] ?? 0);
    $filename = $_POST['filename'] ?? '';

    if ($product_id > 0 && $filename !== '') {
        // Lấy danh sách ảnh hiện tại
        $stmt = $conn->prepare("SELECT image_list FROM product WHERE id = ?");
        $stmt->bind_param("i", $product_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $image_list = '';
        if ($row = $result->fetch_assoc()) {
            $image_list = $row['image_list'];
        }
        if ($image_list) {
            $images = explode(',', $image_list);
            $images = array_map('trim', $images);
            // Loại bỏ ảnh cần xóa
            $key = array_search($filename, $images);
            if ($key !== false) {
                unset($images[$key]);
                $images = array_values($images);
                $new_image_list = implode(',', $images);
                // Cập nhật lại DB
                $stmtUpdate = $conn->prepare("UPDATE product SET image_list = ? WHERE id = ?");
                $stmtUpdate->bind_param("si", $new_image_list, $product_id);
                if ($stmtUpdate->execute()) {
                    // Xóa file ảnh vật lý nếu tồn tại
                    $filePath = '../assets/images/Product/' . $filename;
                    if (file_exists($filePath)) {
                        unlink($filePath);
                    }
                    echo json_encode(['success' => true]);
                    exit;
                }
            }
        }
    }
}

echo json_encode(['success' => false]);
