<?php
session_start();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Tin tức</title>
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
      max-width: 1200px;
      margin: 0 auto;
    }

    h1 {
      font-size: 28px;
      margin-bottom: 30px;
      text-align: center;
    }

    .news-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
      gap: 30px;
    }

    .news-card {
      background: #fff;
      border-radius: 8px;
      overflow: hidden;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
      transition: transform 0.3s ease;
    }

    .news-card:hover {
      transform: translateY(-5px);
    }

    .news-card img {
      width: 100%;
      height: 180px;
      object-fit: cover;
    }

    .news-content {
      padding: 20px;
    }

    .news-title {
      font-size: 18px;
      font-weight: bold;
      margin-bottom: 10px;
      color: #333;
    }

    .news-excerpt {
      font-size: 14px;
      color: #666;
      line-height: 1.6;
    }

    .read-more {
      display: inline-block;
      margin-top: 10px;
      font-size: 14px;
      color: #007bff;
      text-decoration: none;
    }

    .read-more:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>
<?php include('includes/header.php'); ?>
<?php include('includes/top-menu.php'); ?>
<div class="page-wrapper">
  <div class="content-wrapper">
    <div class="container">
      <h1>Tin tức & Bài viết</h1>

      <div class="news-grid">
        <!-- Bài viết 1 -->
        <div class="news-card">
          <img src="assets/images/news/news1.jpg" alt="Tối ưu không gian sống">
          <div class="news-content">
            <div class="news-title">5 mẹo tối ưu không gian sống nhỏ</div>
            <div class="news-excerpt">Nếu bạn đang sống trong một căn hộ nhỏ, đừng bỏ qua những gợi ý thiết kế giúp tiết kiệm diện tích mà vẫn sang trọng...</div>
            <a href="tin-chi-tiet.php?id=1" class="read-more">Xem chi tiết</a>
          </div>
        </div>

        <!-- Bài viết 2 -->
        <div class="news-card">
          <img src="assets/images/news/news2.jpg" alt="Phong cách Bắc Âu">
          <div class="news-content">
            <div class="news-title">Phong cách Bắc Âu: đơn giản mà tinh tế</div>
            <div class="news-excerpt">Scandinavian không chỉ là phong cách thiết kế, mà còn là triết lý sống hướng tới sự tối giản, hài hòa và gần gũi với thiên nhiên.</div>
            <a href="tin-chi-tiet.php?id=2" class="read-more">Xem chi tiết</a>
          </div>
        </div>

        <!-- Bài viết 3 -->
        <div class="news-card">
          <img src="assets/images/news/news3.jpg" alt="Chọn sofa đúng cách">
          <div class="news-content">
            <div class="news-title">Hướng dẫn chọn sofa phù hợp cho gia đình</div>
            <div class="news-excerpt">Từ chất liệu, kiểu dáng đến màu sắc – đây là những điều bạn nên biết trước khi mua sofa mới cho phòng khách.</div>
            <a href="tin-chi-tiet.php?id=3" class="read-more">Xem chi tiết</a>
          </div>
        </div>

        <!-- Có thể thêm nhiều bài viết khác ở đây -->
      </div>
    </div>
  </div>

  <?php include('includes/footer.php'); ?>
</div>
</body>
</html>
