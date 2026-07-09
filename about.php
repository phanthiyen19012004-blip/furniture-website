<?php session_start(); ?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Giới thiệu</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
      background: #f9f9f9;
    }

    .container {
      display: flex;
      max-width: 1200px;
      margin: 40px auto;
      gap: 30px;
      padding: 0 20px;
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

    .main-content strong {
      font-weight: bold;
    }

    .main-content iframe {
      width: 100%;
      height: 400px;
      margin-top: 20px;
      border-radius: 6px;
      border: none;
    }

    .showroom {
      margin-top: 30px;
    }

    .showroom h3 {
      font-size: 18px;
      font-weight: bold;
      margin-bottom: 10px;
      text-transform: uppercase;
    }

    .showroom ul {
      list-style: none;
      padding-left: 0;
    }

    .showroom li {
      margin-bottom: 8px;
    }

    .hotline {
      color: red;
      font-weight: bold;
      margin-top: 20px;
    }
    
html, body {
  height: 100%;
  margin: 0;
  padding: 0;
}

.page-wrapper {
  min-height: 100vh;
  display: flex;
  flex-direction: column;
}

.content-wrapper {
  flex: 1; /* Đảm bảo nội dung đẩy footer xuống đáy */
}

  </style>
</head>
<body>
<?php include('includes/header.php'); ?>
<?php include('includes/top-menu.php'); ?>
<div class="page-wrapper">
  <div class="content-wrapper"></div>
<div class="container">
  <!-- Sidebar -->
  <div class="sidebar">
    <h3>DANH MỤC TRANG</h3>
    <ul>
      <li><a href="#">Chính sách đổi trả</a></li>
      <li><a href="#">Chính sách bảo mật</a></li>
      <li><a href="#">Điều khoản dịch vụ</a></li>
      <li><a href="#">Chính sách bán hàng</a></li>
      <li><a href="#">Giao hàng & nhận hàng</a></li>
      <li><a href="#">Phương thức thanh toán</a></li>
      <li><a href="#">Chính sách bảo hành</a></li>
    </ul>
  </div>

<div class="main-content">
  <h1>Giới thiệu</h1>
  <p>Chào mừng bạn đến với <strong>HomeStyle</strong> – không gian mua sắm nội thất trực tuyến nơi mọi ý tưởng sống đẹp đều trở thành hiện thực. Chúng tôi tin rằng một ngôi nhà đẹp không cần quá xa hoa, chỉ cần đúng gu và phù hợp với cá tính của bạn.</p>

  <p>Ra đời với sứ mệnh <em>"Mang thẩm mỹ vào từng góc nhỏ"</em>, HomeStyle cung cấp hơn 1.000 sản phẩm nội thất và trang trí độc đáo, được tuyển chọn kỹ lưỡng từ các xưởng sản xuất uy tín trong và ngoài nước. Từ sofa, bàn ăn, giường ngủ đến đèn trang trí, thảm trải sàn và phụ kiện decor – bạn có thể dễ dàng phối hợp theo nhiều phong cách: tối giản, Bắc Âu, hiện đại, vintage, hoặc tropical.</p>

  <p>Chúng tôi cam kết mang lại trải nghiệm mua sắm tiện lợi, minh bạch và hỗ trợ tận tâm. Mọi đơn hàng đều được đóng gói cẩn thận, giao hàng nhanh chóng, và có chính sách đổi trả linh hoạt.</p>

  <iframe src="https://www.youtube.com/embed/kF_rXzG64Mw" title="Giới thiệu HomeStyle" allowfullscreen style="width: 100%; height: 400px; border: none; border-radius: 6px; margin: 20px 0;"></iframe>

  <p>Bạn có thể ghé thăm showroom của chúng tôi hoặc trải nghiệm toàn bộ sản phẩm online ngay tại nhà. Với đội ngũ thiết kế và tư vấn viên chuyên nghiệp, HomeStyle sẵn sàng đồng hành cùng bạn trong hành trình biến tổ ấm mơ ước thành hiện thực.</p>

  <div class="showroom">
    <h3>Hệ thống showroom HomeStyle:</h3>
    <ul style="list-style: none; padding-left: 0;">
      <li>▪ TP. HCM: 12 Nguyễn Văn Trỗi, P.15, Q. Phú Nhuận</li>
      <li>▪ Hà Nội: 88 Trần Duy Hưng, Cầu Giấy</li>
      <li>▪ Đà Nẵng: 45 Nguyễn Văn Linh, Q. Hải Châu</li>
    </ul>
  </div>

  <div class="hotline" style="color: red; font-weight: bold; margin-top: 20px;">
    Hotline: 1800 6868 – Email: support@homestyle.vn
  </div>
</div>
</div>

<?php include('includes/footer.php'); ?>
</body>
</html>
