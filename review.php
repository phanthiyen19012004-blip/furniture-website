<?php
session_start();

require __DIR__ . '/config/db.php';

// Kiểm tra đăng nhập
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$productId = isset($_GET['product_id']) ? (int)$_GET['product_id'] : 0;
if (!$productId) die("Thiếu ID sản phẩm.");

// Lấy tên sản phẩm
$stmt = $conn->prepare("SELECT name FROM product WHERE id = ?");
$stmt->bind_param("i", $productId);
$stmt->execute();
$product = $stmt->get_result()->fetch_assoc();
if (!$product) die("Sản phẩm không tồn tại.");

// Kiểm tra user_id trong session có hợp lệ trong bảng user
if (!isset($_SESSION['user']) || !isset($_SESSION['user']['id'])) {
    die("⚠️ Bạn chưa đăng nhập hoặc session đã hết hạn.");
}

$userId = $_SESSION['user']['id'];

$stmt = $conn->prepare("SELECT id FROM user WHERE id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("⚠️ ID người dùng ($userId) không tồn tại trong CSDL.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $rate = (int)($_POST['rate'] ?? 0);
    $content = trim($_POST['content'] ?? '');
    $date = time();
    $image_link = null;

    // Xử lý upload ảnh
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = __DIR__ . '/assets/images/reviews/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

        $fileExt = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

        if (!in_array($fileExt, $allowed)) {
            die('❌ Chỉ cho phép ảnh JPG, JPEG, PNG, GIF, WEBP.');
        }

        if ($_FILES['image']['size'] > 5 * 1024 * 1024) {
            die('❌ Ảnh không được vượt quá 5MB.');
        }

        $newFileName = 'review_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $fileExt;
        $uploadPath = $uploadDir . $newFileName;

        if (!move_uploaded_file($_FILES['image']['tmp_name'], $uploadPath)) {
            die('❌ Lỗi khi lưu ảnh. Kiểm tra quyền ghi thư mục.');
        }

        $image_link = $newFileName;
    }

    // Thêm bình luận
    $stmt = $conn->prepare("INSERT INTO comment (product_id, content, image_link, rate, date, user_id) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("issiii", $productId, $content, $image_link, $rate, $date, $userId);
    $stmt->execute();

    header("Location: product_detail.php?id=$productId");
    exit;
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8" />
  <title>Đánh giá sản phẩm: <?= htmlspecialchars($product['name']) ?></title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" />
</head>
<body class="p-5">
  <div class="container">
    <h2>Đánh giá: <?= htmlspecialchars($product['name']) ?></h2>
    <form method="post" enctype="multipart/form-data">
      <div class="form-group">
        <label>Chấm điểm (1–5 sao):</label>
        <select name="rate" class="form-control" required>
          <option value="5">5 – Rất hài lòng</option>
          <option value="4">4 – Hài lòng</option>
          <option value="3">3 – Tạm ổn</option>
          <option value="2">2 – Không hài lòng</option>
          <option value="1">1 – Rất tệ</option>
        </select>
      </div>
      <div class="form-group">
        <label>Nội dung đánh giá:</label>
        <textarea name="content" class="form-control" rows="4" required></textarea>
      </div>
      <div class="form-group">
        <label>Ảnh minh họa (tuỳ chọn):</label>
        <input type="file" name="image" class="form-control-file" accept="image/*" />
      </div>
      <button type="submit" class="btn btn-primary">Gửi đánh giá</button>
      <a href="product_detail.php?id=<?= $productId ?>" class="btn btn-secondary ml-2">Quay lại</a>
    </form>
  </div>
</body>
</html>
