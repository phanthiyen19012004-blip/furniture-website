<?php
session_start();
require __DIR__ . '/config/db.php';

// Kiểm tra đăng nhập
$user = $_SESSION['user'] ?? null;
if (!$user || !isset($user['id'])) {
    header("Location: login.php");
    exit;
}
$userId = $user['id'];

// Xử lý xóa địa chỉ
if (isset($_GET['action']) && $_GET['action'] === 'delete') {
    $sql = "UPDATE user SET address = '', phone = '' WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userId);
    $stmt->execute();

    // Cập nhật session
    $_SESSION['user']['address'] = '';
    $_SESSION['user']['phone'] = '';

    header("Location: address.php?deleted=1");
    exit;
}

// Xử lý cập nhật
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name     = $_POST['firstname'] ?? '';
    $address1 = $_POST['address1'] ?? '';
    $phone    = $_POST['phone'] ?? '';

    $sql = "UPDATE user SET name = ?, address = ?, phone = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssi", $name, $address1, $phone, $userId);
    $stmt->execute();

    // Cập nhật session
    $_SESSION['user']['name']    = $name;
    $_SESSION['user']['address'] = $address1;
    $_SESSION['user']['phone']   = $phone;

    $update_success = true;
}

// Lấy lại thông tin hiển thị
$name    = $_SESSION['user']['name'] ?? 'Chưa có tên';
$address = $_SESSION['user']['address'] ?? '';
$phone   = $_SESSION['user']['phone'] ?? '';
?>

<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Thông tin địa chỉ</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <!-- Favicon SVG dạng data URI -->
  <link rel="icon" type="image/svg+xml" href='data:image/svg+xml;utf8,
  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 180 180" fill="none">
    <rect x="50" y="70" width="80" height="70" fill="%233c7189" rx="12" ry="12"/>
    <polygon points="45,70 90,30 135,70" fill="%23f26d25"/>
    <rect x="80" y="110" width="20" height="20" fill="%23f9f3f0" rx="4" ry="4"/>
    <rect x="85" y="90" width="10" height="20" fill="%23f9f3f0" rx="3" ry="3"/>
  </svg>' />
  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: Arial, sans-serif; background: #fff; }

    .container {
      max-width: 1200px;
      margin: 40px auto;
      display: flex;
      gap: 50px;
      padding: 0 30px;
    }

    .sidebar {
      width: 220px;
    }

    .sidebar h3 {
      font-size: 16px;
      color: #555;
      text-transform: uppercase;
      margin-bottom: 25px;
    }

    .sidebar ul { list-style: none; }
    .sidebar li { margin-bottom: 15px; font-size: 15px; color: #666; }
    .sidebar li::before {
      content: "• ";
      color: #999;
      margin-right: 8px;
    }
    .sidebar a { color: #333; text-decoration: none; }
    .sidebar a:hover { text-decoration: underline; }

    .main { flex: 1; }
    .main h1 {
      font-size: 28px;
      font-weight: 600;
      text-align: center;
      color: #333;
      margin-bottom: 10px;
    }

    .main hr {
      width: 60px;
      border: 2px solid #333;
      margin: 0 auto 30px;
    }

    .address-box {
      background: #f1f9fc;
      border: 1px solid #d1e6f3;
      padding: 20px;
      margin-top: 20px;
    }

    .address-header {
      background: #d9edf7;
      padding: 10px;
      font-weight: bold;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .address-body {
      padding: 15px 10px;
      background: #f7f9fc;
    }

    .address-body p {
      margin: 6px 0;
    }

    .edit-icons {
      font-size: 14px;
    }

    .edit-icons a {
      margin-left: 10px;
      text-decoration: none;
      color: #333;
    }

    .edit-icons a:hover {
      color: #000;
    }

    .btn-new {
      margin-top: 20px;
      display: inline-block;
      background: #333;
      color: white;
      padding: 10px 20px;
      text-decoration: none;
      font-size: 14px;
      border-radius: 4px;
    }

    .btn-new:hover {
      background: #111;
    }

    .address-edit-form input {
      width: 100%;
      padding: 10px;
      margin-bottom: 10px;
    }

    .address-edit-form {
      display: none;
      padding: 20px;
      background: #fff;
      border: 1px solid #ccc;
      margin-top: 15px;
    }

    .success-message {
      background: #d4edda;
      border: 1px solid #c3e6cb;
      padding: 10px;
      margin-bottom: 15px;
      color: #155724;
    }

    .form-actions {
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .btn-submit {
      background: #333;
      color: white;
      border: none;
      padding: 10px 20px;
      cursor: pointer;
      font-weight: bold;
    }

    .btn-submit:hover {
      background: #111;
    }

    .cancel-text {
      font-size: 14px;
      color: #666;
    }

    .cancel-text a {
      color: #666;
      text-decoration: none;
    }

    .cancel-text a:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>
<?php include('includes/header.php'); ?>
<?php include('includes/top-menu.php'); ?>

<div class="container">
  <div class="sidebar">
    <h3>Tài khoản</h3>
    <ul>
      <li><a href="account.php">Thông tin tài khoản</a></li>
      <li><a href="address.php">Danh sách địa chỉ</a></li>
      <li><a href="logout.php">Đăng xuất</a></li>
    </ul>
  </div>

  <div class="main">
    <h1>Thông tin địa chỉ</h1>
    <hr>

    <?php if (!empty($update_success)): ?>
      <div class="success-message">Địa chỉ đã được cập nhật thành công!</div>
    <?php endif; ?>

    <div class="address-box">
      <div class="address-header">
        <?= htmlspecialchars($name) ?> 
        <div class="edit-icons">
          <a href="#" onclick="toggleEdit(true); return false;"><i class="fas fa-edit"></i></a>
          <a href="?action=delete" onclick="return confirm('Bạn có chắc muốn xóa địa chỉ không?');"><i class="fas fa-times"></i></a>
        </div>
      </div>
      <div class="address-body">
        <p><strong><?= htmlspecialchars($name) ?></strong></p>
        <p>Địa chỉ: <?= htmlspecialchars($address) ?>, Vietnam</p>
        <p>Số điện thoại: <?= htmlspecialchars($phone) ?></p>
      </div>

      <div class="address-edit-form">
        <form method="post" action="">
          <input type="text" name="firstname" placeholder="Tên" value="<?= htmlspecialchars($name) ?>">
          <input type="text" name="address1" placeholder="Địa chỉ" value="<?= htmlspecialchars($address) ?>">
          <input type="text" name="phone" placeholder="Số điện thoại" value="<?= htmlspecialchars($phone) ?>">
          <div class="form-actions">
            <button type="submit" class="btn-submit">CẬP NHẬT</button>
            <span class="cancel-text">hoặc <a href="#" onclick="toggleEdit(false); return false;">Hủy</a></span>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
<script>
  function toggleEdit(show) {
    const form = document.querySelector('.address-edit-form');
    form.style.display = show ? 'block' : 'none';
  }
</script>
</body>
</html>
