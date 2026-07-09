<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
$level = $_SESSION['admin_level'] ?? 2;
$page = basename($_SERVER['PHP_SELF']);
?>


<aside class="sidebar">
<ul class="sidebar-menu">
  <li><a href="index.php" class="<?= $page == 'index.php' ? 'active' : '' ?>"><i class="fas fa-home"></i> Trang chủ</a></li>
  <li><a href="category.php" class="<?= $page == 'category.php' ? 'active' : '' ?>"><i class="fas fa-list"></i> Danh mục</a></li>
  <li><a href="product.php" class="<?= $page == 'product.php' ? 'active' : '' ?>"><i class="fas fa-box"></i> Sản phẩm</a></li>
  <li><a href="slider.php" class="<?= $page == 'slider.php' ? 'active' : '' ?>"><i class="fas fa-sliders-h"></i> Slider</a></li>
  <li><a href="menu_footer_messages.php" class="<?= $page == 'menu_footer_messages.php' ? 'active' : '' ?>"><i class="fa-list-alt"></i> Menu & Footer</a></li>

  <?php if ($level == 1): ?>
    <li><a href="orders.php" class="<?= $page == 'orders.php' ? 'active' : '' ?>"><i class="fas fa-receipt"></i> Đơn hàng</a></li>
    <li><a href="customers.php" class="<?= $page == 'customers.php' ? 'active' : '' ?>"><i class="fas fa-user-friends"></i> Khách hàng</a></li>
    <li><a href="staff.php" class="<?= $page == 'staff.php' ? 'active' : '' ?>"><i class="fas fa-users-cog"></i> Nhân viên</a></li>
    <li><a href="shipping.php" class="<?= $page == 'shipping.php' ? 'active' : '' ?>"><i class="fas fa-shipping-fast"></i> Vận chuyển</a></li>
    <li><a href="comments.php" class="<?= $page == 'comments.php' ? 'active' : '' ?>"><i class="fas fa-comment"></i> Bình luận</a></li>
   <li><a href="coupon_manage.php" class="<?= $page == 'coupon_manage.php' ? 'active' : '' ?>"><i class="fas fa-tag"></i> Mã giảm giá</a></li>
    <li><a href="contact_list.php" class="<?= $page == 'contact_list.php' ? 'active' : '' ?>"><i class="fas fa-envelope"></i> Liên hệ</a></li> 
    <li><a href="admin_models.php" class="<?= $page == 'admin_models.php' ? 'active' : '' ?>"><i class="fas fa-cubes"></i> Quản lý Models</a></li>
    <?php endif; ?>
</ul>


</aside>

<style>
/* Sidebar cơ bản */
/* Sidebar trắng, text đen */
.sidebar {
  width: 250px;
  min-height: 100vh;
  background-color: #ffffff;
  padding: 1rem;
  border-right: 1px solid #e0e0e0;
}

.sidebar-menu {
  list-style: none;
  padding-left: 0;
  margin: 0;
}

.sidebar-menu li {
  margin-bottom: 0.5rem;
}

.sidebar-menu a {
  display: block;
  color: #212529;
  padding: 10px 15px;
  text-decoration: none;
  border-radius: 5px;
  font-weight: 500;
  transition: background-color 0.2s ease, color 0.2s ease;
}

.sidebar-menu a i {
  margin-right: 10px;
}

.sidebar-menu a:hover {
  background-color: #f0f0f0;
}

.sidebar-menu a.active {
  background-color: #007bff;
  color: #fff;
}

</style>