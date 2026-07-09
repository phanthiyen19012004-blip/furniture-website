<?php // services.php
session_start();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Dịch vụ</title>
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
    html, body {
      height: 100%;
      margin: 0;
      padding: 0;
      font-family: Arial, sans-serif;
      background: #f9f9f9;
    }

    .page-wrapper {
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }

    .content-wrapper {
      flex: 1;
      padding: 40px 20px;
    }

    .container {
      display: flex;
      max-width: 1200px;
      margin: 0 auto;
      gap: 30px;
    }

    .sidebar {
      width: 280px;
      background: #fff;
      border: 1px solid #eee;
      padding: 20px;
      border-radius: 6px;
    }

    .sidebar h3 {
      text-align: center;
      font-size: 18px;
      margin-bottom: 20px;
      text-transform: uppercase;
      border-bottom: 1px solid #ccc;
      padding-bottom: 10px;
    }

    .sidebar ul {
      list-style: none;
      padding-left: 0;
    }

    .sidebar li {
      border-bottom: 1px dotted #ddd;
      padding: 10px 0;
    }

    .sidebar a {
      text-decoration: none;
      color: #333;
      font-size: 15px;
      display: block;
    }

    .sidebar a:hover {
      color: #007bff;
    }

    .main-content {
      flex: 1;
      background: #fff;
      padding: 30px;
      border-radius: 6px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }

    .main-content h1 {
      font-size: 28px;
      margin-bottom: 20px;
    }

    .main-content p {
      line-height: 1.8;
      margin-bottom: 15px;
      font-size: 16px;
    }

    .main-content ul {
      padding-left: 20px;
      margin-bottom: 20px;
    }

    .main-content li {
      margin-bottom: 8px;
    }
  </style>
</head>
<body>
<?php include('includes/header.php'); ?>
<?php include('includes/top-menu.php'); ?>
<div class="page-wrapper">
  <div class="content-wrapper">
    <div class="container">
      <!-- Sidebar -->
      <div class="sidebar">
        <h3>DANH MỤC TRANG</h3>
        <ul>
          <li><a href="#">Chính sách đổi trả</a></li>
          <li><a href="#">Chính sách bảo mật</a></li>
          <li><a href="#">Điều khoản dịch vụ</a></li>
          <li><a href="#">Chính sách bán hàng</a></li>
          <li><a href="#">Giao hàng & Nhận hàng</a></li>
          <li><a href="#">Phương thức thanh toán</a></li>
          <li><a href="#">Chính sách bảo hành</a></li>
        </ul>
      </div>

      <!-- Main Content -->
      <div class="main-content">
        <h1>Dịch vụ</h1>
        <p>Tại <strong>HomeStyle</strong>, chúng tôi cam kế mang đến cho khách hàng những trải nghiệm dịch vụ hoàn hảo nhất:</p>
        <ul>
          <li><strong>Tư vấn bố cụ không gian:</strong> Gời ý thiết kế nội thất theo phong cách cá nhân.</li>
          <li><strong>Thi công trọn gói:</strong> Hợp đồng trọn gói từ thiết kế đến lắp đặt.</li>
          <li><strong>Vận chuyển & Lắp ráp:</strong> Miễn phí trong khu vực TP.HCM, Hà Nội và Đà Nẵng.</li>
          <li><strong>Bảo hành tận tâm:</strong> Bảo hành tối thiểu 12 tháng, có đổi trả trong 7 ngày.</li>
          <li><strong>Chương trình đối tác/nhà thiết kế:</strong> Giá sỉ và hỗ trợ riêng dành cho đối tác chuyên nghiệp.</li>
        </ul>
        <p>Chúng tôi luôn lắng nghe và hỗ trợ bạn trên mỗi hành trình biến ngôi nhà thành tổ ấm mơ ước.</p>
      </div>
    </div>
  </div>

  <?php include('includes/footer.php'); ?>
</div>
</body>
</html>
