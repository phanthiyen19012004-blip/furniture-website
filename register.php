
<?php
require __DIR__ . '/config/db.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Xử lý khi submit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name     = $_POST['name'];
  $phone    = $_POST['phone'];
  $email    = $_POST['email'];
  $address  = $_POST['address'];
  $pass     = $_POST['password'];
  $confirm  = $_POST['confirm_password'];

  if ($pass !== $confirm) {
    echo "<script>alert('Mật khẩu không khớp'); window.history.back();</script>";
    exit;
  }

  // Kiểm tra trùng tên đăng nhập
  $check = $conn->prepare("SELECT id FROM user WHERE name = ?");
  $check->bind_param("s", $name);
  $check->execute();
  $checkResult = $check->get_result();

  if ($checkResult->num_rows > 0) {
    echo "<script>alert('Tên đăng nhập đã tồn tại'); history.back();</script>";
    exit;
  }

  $hashed = password_hash($pass, PASSWORD_DEFAULT);


  $stmt = $conn->prepare("INSERT INTO user (phone, name, email, password, address) VALUES (?, ?, ?, ?, ?)");
  $stmt->bind_param("sssss", $phone, $name, $email, $hashed, $address);

  if ($stmt->execute()) {
    echo "<script>alert('Đăng ký thành công!'); window.location.href='login.php';</script>";
    exit;
  } else {
    echo "<script>alert('Số điện thoại hoặc email đã tồn tại hoặc lỗi!'); window.history.back();</script>";
    exit;
  }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Đăng ký tài khoản</title>
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
    body {
      font-family: Arial, sans-serif;
      background: linear-gradient(to right, #74ebd5, #ACB6E5);
      height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    .register-container {
      background-color: white;
      padding: 40px;
      border-radius: 12px;
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
      width: 100%;
      max-width: 450px;
      position: relative;
    }
    .register-container h2 {
      text-align: center;
      margin-bottom: 30px;
      color: #333;
    }
    .register-container input {
      width: 100%;
      padding: 12px;
      margin-bottom: 20px;
      border: 1px solid #ccc;
      border-radius: 6px;
    }
    .register-container button {
      width: 100%;
      padding: 12px;
      background-color: #333;
      color: white;
      border: none;
      border-radius: 6px;
      font-size: 16px;
      cursor: pointer;
    }
    .register-container button:hover {
      background-color: #555;
    }
    .register-container .options {
      text-align: center;
      margin-top: 15px;
    }
    .register-container .options a {
      color: #333;
      text-decoration: none;
      font-size: 14px;
    }
    .register-container .options a:hover {
      text-decoration: underline;
    }
    .register-container .icon {
      text-align: center;
      margin-bottom: 20px;
      font-size: 40px;
      color: #333;
    }
    .close-btn {
  position: absolute;
  top: 20px;
  right: 25px;
  font-size: 18px;
  color: #888;
  cursor: pointer;
  transition: color 0.3s ease;
}
.close-btn:hover {
  color: #f00;
}

  </style>
</head>
<body>

  <div class="register-container">
    <div class="close-btn" onclick="goBack()"><i class="fas fa-times"></i></div>
    <div class="icon">
      <i class="fas fa-user-plus"></i>
    </div>
    <h2>Đăng ký tài khoản</h2>
    <form method="post">
      <input type="text" name="name" placeholder="Họ và tên" required>
      <input type="text" name="phone" placeholder="Số điện thoại" required>
      <input type="email" name="email" placeholder="Email" required>
      <input type="text" name="address" placeholder="Địa chỉ" required>
      <input type="password" name="password" placeholder="Mật khẩu" required>
      <input type="password" name="confirm_password" placeholder="Nhập lại mật khẩu" required>
      <button type="submit">Đăng ký</button>
    </form>
    <div class="options">
      <a href="login.php">Đã có tài khoản? Đăng nhập</a>
    </div>
  </div>
<script>
  function goBack() {
    window.history.back();
  }
</script>
</body>
</html>
