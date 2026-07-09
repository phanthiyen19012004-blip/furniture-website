<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
  header("Location: login.php");
  exit;
}

$admin_level = $_SESSION['admin_level'] ?? 2;
$allowed_pages_for_staff = ['index.php', 'category.php', 'product.php', 'slider.php'];
$current_page = basename($_SERVER['PHP_SELF']);

// Nếu là nhân viên và truy cập vào trang không được phép => chặn
if ($admin_level == 2 && !in_array($current_page, $allowed_pages_for_staff)) {
  header("Location: index.php");
  exit;
}
?>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>

<nav class="navbar navbar-dark bg-dark px-4">
  <span class="navbar-brand mb-0 h1">ADMIN</span>
  <div class="ml-auto dropdown">
    <span class="text-white mr-2"><?= $_SESSION['admin_name'] ?? 'Tài khoản' ?></span>
    <a href="#" class="text-white dropdown-toggle" id="userDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
      <i class="fas fa-user-circle fa-2x"></i>
    </a>
    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
      <a class="dropdown-item" href="logout.php">Đăng xuất</a>
    </div>
  </div>
</nav>
