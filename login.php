<?php
session_start();

require __DIR__ . '/config/db.php'; // Kết nối CSDL

// Nếu đã đăng nhập → chuyển tới account
if (isset($_SESSION['user'])) {
  header("Location: account.php");
  exit;
}

$error = '';

// Xử lý khi submit form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $username = trim($_POST['username'] ?? '');
  $password = trim($_POST['password'] ?? '');

  if ($username && $password) {
    $stmt = $conn->prepare("SELECT * FROM user WHERE name = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

if ($result && $result->num_rows === 1) {
  $user = $result->fetch_assoc();

  if (password_verify($password, $user['password'])) {
    $_SESSION['user'] = [
      'id' => $user['id'],
      'name' => $user['name'],
      'email' => $user['email'] ?? '',
      'phone' => $user['phone'] ?? '',
      'address' => $user['address'] ?? ''
    ];

    header("Location: account.php");
    exit;
  } else {
    $error = "❌ Mật khẩu không đúng.";
  }
}


    $stmt->close();
  } else {
    $error = "❗ Vui lòng điền đầy đủ thông tin.";
  }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Đăng nhập</title>
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
    body {
      font-family: Arial, sans-serif;
      background: linear-gradient(to right, #74ebd5, #ACB6E5);
      height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    .login-container {
      background-color: white;
      padding: 40px;
      border-radius: 12px;
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
      width: 100%;
      max-width: 400px;
      position: relative;
    }
    .login-container h2 {
      text-align: center;
      margin-bottom: 30px;
      color: #333;
    }
    .login-container input {
      width: 100%;
      padding: 12px;
      margin-bottom: 20px;
      border: 1px solid #ccc;
      border-radius: 6px;
    }
    .login-container button {
      width: 100%;
      padding: 12px;
      background-color: #333;
      color: white;
      border: none;
      border-radius: 6px;
      font-size: 16px;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }
    .login-container button:hover {
      background-color: #555;
    }
    .login-container .options {
      text-align: center;
      margin-top: 15px;
    }
    .login-container .options a {
      color: #333;
      text-decoration: none;
      font-size: 14px;
    }
    .login-container .options a:hover {
      text-decoration: underline;
    }
    .login-container .icon {
      text-align: center;
      margin-bottom: 20px;
      font-size: 40px;
      color: #333;
    }
    .error {
      color: red;
      text-align: center;
      margin-bottom: 15px;
      font-size: 14px;
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

  <div class="login-container">
<div class="close-btn" onclick="goBack()"><i class="fas fa-times"></i></div>
    <div class="icon"><i class="fas fa-user-circle"></i></div>
    <h2>Đăng nhập</h2>

    <?php if ($error): ?>
      <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="post">
      <input type="text" name="username" placeholder="Tên đăng nhập" required>
      <input type="password" name="password" placeholder="Mật khẩu" required>
      <button type="submit">Đăng nhập</button>
    </form>

    <div class="options">
      <a href="register.php">Đăng ký tài khoản</a>
    </div>
  </div>
<script>
  function goBack() {
    window.history.back(); 
  }
</script>

</body>
</html>
