<?php
ob_start();
session_start();
include('config/db.php');

function format_price($price) {
  return number_format($price, 0, ',', '.') . ' VND';
}

// Lấy sản phẩm mới
$sql_new = "SELECT * FROM product ORDER BY created DESC LIMIT 8";
$result_new = mysqli_query($conn, $sql_new);

// Lấy sản phẩm bán chạy
$sql_best_seller = "
  SELECT p.*, SUM(o.qty) as total_sold
  FROM product p
  JOIN `order` o ON p.id = o.product_id
  GROUP BY p.id
  ORDER BY total_sold DESC
  LIMIT 8
";
$result_best_seller = mysqli_query($conn, $sql_best_seller);

// Lấy sản phẩm gợi ý (ngẫu nhiên 8 sản phẩm)
$sql_suggested = "SELECT * FROM product ORDER BY RAND() LIMIT 8";
$result_suggested = mysqli_query($conn, $sql_suggested);

// Lấy Hot Deal (8 sản phẩm có discount lớn nhất)
$sql_hotdeal = "SELECT * FROM product WHERE discount > 0 ORDER BY discount DESC LIMIT 8";
$result_hotdeal = mysqli_query($conn, $sql_hotdeal);

// Lấy 4 bình luận có ảnh gần đây nhất, nối với user lấy tên
$sql_comments = "
  SELECT c.content, c.image_link, c.rate, c.date, u.name AS user_name
  FROM comment c
  LEFT JOIN user u ON c.user_id = u.id
  WHERE c.image_link IS NOT NULL AND c.image_link <> ''
  ORDER BY c.date DESC
  LIMIT 4
";
$result_comments = mysqli_query($conn, $sql_comments);
?>

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>HomeStyle - Bán hàng nội thất chất lượng cao</title>
  <meta name="description" content="HomeStyle cung cấp nội thất đẹp, chất lượng với giá tốt nhất. Sản phẩm đa dạng, giao hàng nhanh chóng." />
  <meta name="keywords" content="nội thất, bán nội thất, đồ gỗ, bàn ghế, trang trí nhà cửa" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />

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
      margin: 0; padding: 0;
      background-color: #fff;
    }
    
    .section-wrapper {
      text-align: center;
      margin: 30px 0 20px;
    }
    .section-title {
      background-color: #000;
      color: #fff;
      text-transform: uppercase;
      font-weight: 500;
      font-size: 14px;
      padding: 6px 12px;
      display: inline-block;
      letter-spacing: 0.5px;
      border-radius: 2px;
    }
    .product-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
      gap: 25px;
      padding: 0 20px;
    }
    .product-item {
      border: 1px solid #f0f0f0;
      border-radius: 4px;
      padding: 15px;
      text-align: center;
      position: relative;
      transition: box-shadow 0.3s;
      background-color: #fff;
    }
    .product-item:hover {
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    .product-item img {
      width: 100%;
      height: 200px;
      object-fit: contain;
      margin-bottom: 10px;
      border-radius: 6px;
      background-color: #f9f9f9;
    }
    .product-item .name {
      font-size: 15px;
      color: #222;
      margin: 10px 0 8px;
      height: 42px;
      overflow: hidden;
      text-overflow: ellipsis;
      display: -webkit-box;
      -webkit-line-clamp: 2;
      -webkit-box-orient: vertical;
    }
    .price.sale {
      font-weight: 700;
      font-size: 16px;
      color: #e53935;
    }
    .price.normal {
      font-weight: 700;
      font-size: 16px;
      color: #2e7d32;
    }
    .price-old {
      font-size: 14px;
      color: #888;
      text-decoration: line-through;
      margin-top: 3px;
    }
    .icons {
      position: absolute;
      top: 10px;
      right: 10px;
      display: flex;
      gap: 10px;
      font-size: 14px;
      color: #888;
    }
    .sale-tag {
      position: absolute;
      top: 10px;
      left: 10px;
      background-color: red;
      color: white;
      font-size: 12px;
      padding: 4px 6px;
      font-weight: bold;
      border-radius: 0 0 4px 4px;
      z-index: 10;
    }
    .comment-marquee-wrapper {
      padding: 0 20px 20px 20px;
      background: #fff;
    }

    .comment-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
      gap: 20px;
    }
    @keyframes marquee-left {
      0% {
        transform: translateX(0%);
      }
      100% {
        transform: translateX(-50%);
      }
    }
    .comment-item {
      min-width: 200px;
      flex-shrink: 0;
      border: 1px solid #eee;
      border-radius: 8px;
      padding: 10px;
      background: #fff;
      box-shadow: 0 0 6px rgba(0,0,0,0.05);
      text-align: left;
      font-size: 14px;
    }
    .comment-item img {
      width: 100%;
      height: 130px;
      object-fit: cover;
      border-radius: 8px;
      margin-bottom: 8px;
    }
    .comment-user {
      font-weight: 600;
      margin-bottom: 4px;
      color: #222;
      text-align: center;
    }
    .comment-rating {
      color: #f4b942;
      margin-bottom: 6px;
      text-align: center;
    }
    .comment-content {
      color: #555;
      line-height: 1.3;
      height: 3.9em;
      overflow: hidden;
      text-overflow: ellipsis;
    }
    @media (max-width: 768px) {
  .product-grid {
    grid-template-columns: repeat(2, 1fr);
    gap: 15px;
    padding: 0 10px;
  }

  .product-item img {
    height: 150px;
  }

  .product-item .name {
    font-size: 14px;
    -webkit-line-clamp: 3;
    height: auto;
  }

  .price.sale, .price.normal {
    font-size: 15px;
  }

  .price-old {
    font-size: 13px;
  }

  .sale-tag {
    font-size: 10px;
    padding: 2px 5px;
  }

  .product-item {
    padding: 10px;
  }

  .comment-item {
    min-width: 160px;
    font-size: 13px;
    padding: 8px;
  }

  .comment-item img {
    height: 100px;
  }

  .comment-content {
    font-size: 12px;
    height: 3em;
  }

  .comment-user, .comment-rating {
    font-size: 13px;
  }

  .icons {
    top: 6px;
    right: 6px;
    font-size: 13px;
  }

  .comment-grid {
    gap: 12px;
  }

  .add-to-cart-form button {
    font-size: 18px;
    padding: 6px 10px;
  }

  body {
    padding: 0 10px;
  }
}

@media (max-width: 480px) {
  .product-grid {
    grid-template-columns: 1fr;
    gap: 12px;
    padding: 0 8px;
  }

  .product-item img {
    height: 180px;
  }

  .comment-item {
    min-width: 140px;
    font-size: 12px;
  }

  body {
    padding: 0 5px;
  }
}

  </style>
</head>
<body>
  <?php include('includes/header.php'); ?>
  <?php include('includes/top-menu.php'); ?>
  <?php include('includes/marquee.php'); ?>
  <div class="custom-slider"><?php include('includes/slider.php'); ?></div>

  <!-- SẢN PHẨM MỚI -->
  <div class="section-wrapper">
    <div class="section-title">Sản phẩm mới</div>
  </div>
  <?php if (mysqli_num_rows($result_new) > 0): ?>
    <div class="product-grid">
      <?php while($row = mysqli_fetch_assoc($result_new)): ?>
        <?php
          $thumbImg = 'default.png';
          if (!empty($row['image_list'])) {
              $imgs = explode(',', $row['image_list']);
              $thumbImg = trim($imgs[0]);
          } elseif (!empty($row['image_link'])) {
              $thumbImg = $row['image_link'];
          }
        ?>
        <div class="product-item">
          <?php if ($row['discount'] > 0): ?>
            <div class="sale-tag">SALE OFF</div>
          <?php endif; ?>
          <div class="icons">
            <form class="add-to-cart-form" data-id="<?= $row['id'] ?>" method="post" style="display:inline;">
              <button type="submit" style="background:none;border:none;"><i class="fas fa-shopping-cart"></i></button>
            </form>
          </div>
          <a href="product_detail.php?id=<?= $row['id'] ?>" style="text-decoration: none; color: inherit;">
            <img src="assets/images/product/<?= htmlspecialchars($thumbImg) ?>" alt="<?= htmlspecialchars($row['name']); ?>">
            <div class="name"><?= htmlspecialchars($row['name']); ?></div>
          </a>
          <?php if ($row['price'] == 0): ?>
            <div class="price normal">Liên hệ</div>
          <?php elseif ($row['discount'] > 0): ?>
            <div class="price sale"><?= format_price($row['discount']); ?></div>
            <div class="price-old"><?= format_price($row['price']); ?></div>
          <?php else: ?>
            <div class="price normal"><?= format_price($row['price']); ?></div>
          <?php endif; ?>
        </div>
      <?php endwhile; ?>
    </div>
  <?php else: ?>
    <p style="padding: 0 20px; color: #888;">Không có sản phẩm mới.</p>
  <?php endif; ?>
  <!-- SẢN PHẨM BÁN CHẠY -->
  <div class="section-wrapper">
    <div class="section-title">Sản phẩm bán chạy</div>
  </div>
  <?php if (mysqli_num_rows($result_best_seller) > 0): ?>
    <div class="product-grid">
      <?php while($row = mysqli_fetch_assoc($result_best_seller)): ?>
        <?php
          $thumbImg = 'default.png';
          if (!empty($row['image_list'])) {
              $imgs = explode(',', $row['image_list']);
              $thumbImg = trim($imgs[0]);
          } elseif (!empty($row['image_link'])) {
              $thumbImg = $row['image_link'];
          }
        ?>
        <div class="product-item">
          <?php if ($row['discount'] > 0): ?>
            <div class="sale-tag">SALE OFF</div>
          <?php endif; ?>
          <div class="icons">
            <form class="add-to-cart-form" data-id="<?= $row['id'] ?>" method="post" style="display:inline;">
              <button type="submit" style="background:none;border:none;"><i class="fas fa-shopping-cart"></i></button>
            </form>
          </div>
          <a href="product_detail.php?id=<?= $row['id'] ?>" style="text-decoration: none; color: inherit;">
            <img src="assets/images/product/<?= htmlspecialchars($thumbImg) ?>" alt="<?= htmlspecialchars($row['name']); ?>">
            <div class="name"><?= htmlspecialchars($row['name']); ?></div>
          </a>
          <?php if ($row['price'] == 0): ?>
            <div class="price normal">Liên hệ</div>
          <?php elseif ($row['discount'] > 0): ?>
            <div class="price sale"><?= format_price($row['discount']); ?></div>
            <div class="price-old"><?= format_price($row['price']); ?></div>
          <?php else: ?>
            <div class="price normal"><?= format_price($row['price']); ?></div>
          <?php endif; ?>
        </div>
      <?php endwhile; ?>
    </div>
  <?php else: ?>
    <p style="padding: 0 20px; color: #888;">Không có sản phẩm bán chạy.</p>
  <?php endif; ?>

  <!-- GỢI Ý -->
  <div class="section-wrapper">
    <div class="section-title">Gợi ý dành cho bạn</div>
  </div>
  <?php if (mysqli_num_rows($result_suggested) > 0): ?>
    <div class="product-grid">
      <?php while($row = mysqli_fetch_assoc($result_suggested)): ?>
        <?php
          $thumbImg = 'default.png';
          if (!empty($row['image_list'])) {
              $imgs = explode(',', $row['image_list']);
              $thumbImg = trim($imgs[0]);
          } elseif (!empty($row['image_link'])) {
              $thumbImg = $row['image_link'];
          }
        ?>
        <div class="product-item">
          <?php if ($row['discount'] > 0): ?>
            <div class="sale-tag">SALE OFF</div>
          <?php endif; ?>
          <div class="icons">
            <form class="add-to-cart-form" data-id="<?= $row['id'] ?>" method="post" style="display:inline;">
              <button type="submit" style="background:none;border:none;"><i class="fas fa-shopping-cart"></i></button>
            </form>
          </div>
          <a href="product_detail.php?id=<?= $row['id'] ?>" style="text-decoration: none; color: inherit;">
            <img src="assets/images/product/<?= htmlspecialchars($thumbImg) ?>" alt="<?= htmlspecialchars($row['name']); ?>">
            <div class="name"><?= htmlspecialchars($row['name']); ?></div>
          </a>
          <?php if ($row['price'] == 0): ?>
            <div class="price normal">Liên hệ</div>
          <?php elseif ($row['discount'] > 0): ?>
            <div class="price sale"><?= format_price($row['discount']); ?></div>
            <div class="price-old"><?= format_price($row['price']); ?></div>
          <?php else: ?>
            <div class="price normal"><?= format_price($row['price']); ?></div>
          <?php endif; ?>
        </div>
      <?php endwhile; ?>
    </div>
  <?php else: ?>
    <p style="padding: 0 20px; color: #888;">Không có sản phẩm gợi ý.</p>
  <?php endif; ?>

  <!-- HOT DEAL -->
  <div class="section-wrapper">
    <div class="section-title">Hot Deal</div>
  </div>
  <?php if (mysqli_num_rows($result_hotdeal) > 0): ?>
    <div class="product-grid">
      <?php while($row = mysqli_fetch_assoc($result_hotdeal)): ?>
        <?php
          $thumbImg = 'default.png';
          if (!empty($row['image_list'])) {
              $imgs = explode(',', $row['image_list']);
              $thumbImg = trim($imgs[0]);
          } elseif (!empty($row['image_link'])) {
              $thumbImg = $row['image_link'];
          }
        ?>
        <div class="product-item">
          <div class="sale-tag">SALE OFF</div>
          <div class="icons">
            <form class="add-to-cart-form" data-id="<?= $row['id'] ?>" method="post" style="display:inline;">
              <button type="submit" style="background:none;border:none;"><i class="fas fa-shopping-cart"></i></button>
            </form>
          </div>
          <a href="product_detail.php?id=<?= $row['id'] ?>" style="text-decoration: none; color: inherit;">
            <img src="assets/images/product/<?= htmlspecialchars($thumbImg) ?>" alt="<?= htmlspecialchars($row['name']); ?>">
            <div class="name"><?= htmlspecialchars($row['name']); ?></div>
          </a>
          <div class="price sale"><?= format_price($row['discount']); ?></div>
          <div class="price-old"><?= format_price($row['price']); ?></div>
        </div>
      <?php endwhile; ?>
    </div>
  <?php else: ?>
    <p style="padding: 0 20px; color: #888;">Không có Hot Deal nào.</p>
  <?php endif; ?>

  <!-- ĐÁNH GIÁ THỰC TẾ VỚI HIỆU ỨNG CHẠY NGANG -->
  <div class="section-wrapper">
    <div class="section-title">Đánh giá thực tế</div>
  </div>
  <?php if (mysqli_num_rows($result_comments) > 0): ?>
<div class="comment-marquee-wrapper">
  <div class="comment-grid">
    <?php while($comment = mysqli_fetch_assoc($result_comments)): ?>
      <div class="comment-item">
        <img src="assets/images/reviews/<?= htmlspecialchars($comment['image_link']); ?>" alt="Ảnh đánh giá" />
        <div class="comment-user"><?= htmlspecialchars($comment['user_name'] ?: 'Người dùng'); ?></div>
        <div class="comment-rating">
          <?php
            $fullStars = floor($comment['rate']);
            $halfStar = ($comment['rate'] - $fullStars) >= 0.5;
            for ($i=0; $i < $fullStars; $i++) echo '<i class="fas fa-star"></i>';
            if ($halfStar) echo '<i class="fas fa-star-half-alt"></i>';
            $emptyStars = 5 - $fullStars - ($halfStar ? 1 : 0);
            for ($i=0; $i < $emptyStars; $i++) echo '<i class="far fa-star"></i>';
          ?>
        </div>
        <div class="comment-content"><?= htmlspecialchars(mb_strimwidth($comment['content'], 0, 100, '...')); ?></div>
      </div>
    <?php endwhile; ?>
  </div>
</div>

  <?php else: ?>
    <p style="padding: 0 20px; color: #888;">Chưa có đánh giá thực tế nào.</p>
  <?php endif; ?>

  <?php include('includes/footer.php'); ?>
  <?php include('contact.php'); ?>

  <script>
  document.querySelectorAll('.add-to-cart-form').forEach(form => {
    form.addEventListener('submit', e => {
      e.preventDefault();
      const productId = form.getAttribute('data-id');
      fetch('add_to_cart_ajax.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: 'product_id=' + productId
      })
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          let badge = document.querySelector('.cart-count');
          if (badge) badge.textContent = data.total_items;
          else {
            const cartIcon = document.querySelector('.header-icons a[href="cart.php"]');
            if (cartIcon) {
              const span = document.createElement('span');
              span.className = 'cart-count';
              span.textContent = data.total_items;
              cartIcon.appendChild(span);
            }
          }
        } else {
          alert('Thêm sản phẩm vào giỏ hàng thất bại!');
        }
      })
      .catch(() => alert('Lỗi khi kết nối máy chủ.'));
    });
  });
  </script>
</body>
</html>
