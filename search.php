<?php
$conn = new mysqli("localhost", "root", "", "website");
$conn->set_charset("utf8");

if ($conn->connect_error) {
    die("Lỗi kết nối: " . $conn->connect_error);
}

$keyword = isset($_GET['keyword']) ? $conn->real_escape_string($_GET['keyword']) : '';
$sql = "SELECT * FROM product WHERE name LIKE '%$keyword%'";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Kết quả tìm kiếm</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="icon" type="image/svg+xml" href='data:image/svg+xml;utf8,
  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 180 180" fill="none">
    <rect x="50" y="70" width="80" height="70" fill="%233c7189" rx="12" ry="12"/>
    <polygon points="45,70 90,30 135,70" fill="%23f26d25"/>
    <rect x="80" y="110" width="20" height="20" fill="%23f9f3f0" rx="4" ry="4"/>
    <rect x="85" y="90" width="10" height="20" fill="%23f9f3f0" rx="3" ry="3"/>
  </svg>' />
  <style>
    * { box-sizing: border-box; }
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
      background-color: #fff;
    }
    .search-section {
      margin-top: 20px;
      padding-left: 20px;
      padding-right: 10px;
      margin-bottom: 20px; 
    }

    h2 {
      font-size: 20px;
      margin-bottom: 25px;
    }
    .product-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
      gap: 25px;
    }
    .product-item {
      border: 1px solid #f0f0f0;
      border-radius: 4px;
      padding: 15px;
      text-align: center;
      position: relative;
      transition: box-shadow 0.3s;
    }
    .product-item:hover {
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    .product-item img {
      width: 100%;
      height: 180px;
      object-fit: contain;
      margin-bottom: 10px;
    }
    .product-item .name {
      font-size: 14px;
      color: #333;
      margin: 8px 0;
    }
    .product-item .price {
      font-weight: bold;
      font-size: 15px;
    }
    .price.sale {
      color: #e53935;
    }
    .price.normal {
      color: #2e7d32;
    }
    .price-old {
      font-size: 13px;
      color: #888;
      text-decoration: line-through;
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
    .no-result {
      font-style: italic;
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
    .sale-tag.out-of-stock {
      background-color: #555;
    }
  </style>
</head>

<?php include('includes/header.php'); ?>
<?php include('includes/top-menu.php'); ?>

<body>
  <div class="search-section">
    <h2>Kết quả tìm kiếm cho "<strong><?= htmlspecialchars($keyword) ?></strong>"</h2>

    <?php if ($result->num_rows > 0): ?>
      <div class="product-grid">
        <?php while($row = $result->fetch_assoc()): ?>
          <?php
            $image = $row['image_link'];
            if (!$image && $row['image_list']) {
              $images = explode(',', $row['image_list']);
              $image = trim($images[0]);
            }
            if (!$image) $image = 'default.webp'; // fallback nếu không có ảnh nào
          ?>
          <div class="product-item">
            <?php if ($row['price'] == 0): ?>
              <div class="sale-tag out-of-stock">HẾT HÀNG</div>
            <?php elseif ($row['discount'] > 0): ?>
              <div class="sale-tag">SALE OFF</div>
            <?php endif; ?>

            <div class="icons">
              <form class="add-to-cart-form" data-id="<?= $row['id'] ?>" style="display:inline;">
                <button type="submit" style="background:none;border:none;"><i class="fas fa-shopping-cart"></i></button>
              </form>
            </div>

            <a href="product_detail.php?id=<?= $row['id'] ?>" style="text-decoration: none; color: inherit;">
              <img src="assets/images/product/<?= htmlspecialchars($image); ?>" alt="<?= htmlspecialchars($row['name']); ?>">
              <div class="name"><?= htmlspecialchars($row['name']); ?></div>
            </a>

            <?php if ($row['price'] == 0): ?>
              <div class="price normal">Liên hệ nhận báo giá</div>
            <?php elseif ($row['discount'] > 0): ?>
              <div class="price sale"><?= number_format($row['discount'], 0, ',', '.') ?> VND</div>
              <div class="price-old"><?= number_format($row['price'], 0, ',', '.') ?> VND</div>
            <?php else: ?>
              <div class="price normal"><?= number_format($row['price'], 0, ',', '.') ?> VND</div>
            <?php endif; ?>
          </div>
        <?php endwhile; ?>
      </div>
    <?php else: ?>
      <p class="no-result">Không tìm thấy sản phẩm nào phù hợp.</p>
    <?php endif; ?>
  </div>
</body>

<?php include('includes/footer.php'); ?>
</html>

<?php $conn->close(); ?>

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
          const span = document.createElement('span');
          span.className = 'cart-count';
          span.textContent = data.total_items;
          cartIcon.appendChild(span);
        }
      }
    });
  });
});
</script>
