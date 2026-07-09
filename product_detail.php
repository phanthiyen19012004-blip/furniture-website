<?php
session_start();
$conn = new mysqli("localhost", "root", "", "website");
$conn->set_charset("utf8");

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$sql = "SELECT * FROM product WHERE id = $id";
$result = $conn->query($sql);
if (!$result || $result->num_rows === 0) {
    echo "Sản phẩm không tồn tại.";
    exit;
}
$product = $result->fetch_assoc();

// Xử lý mảng ảnh từ image_list hoặc image_link
$images = [];
if (!empty($product['image_list'])) {
    $images = explode(',', $product['image_list']);
    $images = array_map('trim', $images);
} elseif (!empty($product['image_link'])) {
    $images = [trim($product['image_link'])];
}

// Lấy bình luận
$sqlComment = "SELECT c.*, u.name AS user_name FROM comment c LEFT JOIN user u ON c.user_id = u.id WHERE c.product_id = ? ORDER BY c.date DESC";
$stmt = $conn->prepare($sqlComment);
$stmt->bind_param("i", $id);
$stmt->execute();
$comments = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8" />
  <title><?php echo htmlspecialchars($product['name']); ?></title>
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
      margin: 0;
    }
    .product-container {
      display: flex;
      gap: 40px;
      margin: 40px 0;
      padding-left: 30px;
    }
    /* Phần ảnh */
    .product-images {
      display: flex;
      gap: 10px;
    }
    .thumbnails {
      display: flex;
      flex-direction: column;
      gap: 10px;
      max-width: 80px;
    }
    .thumbnails img.thumbnail {
      cursor: pointer;
      border: 2px solid transparent;
      border-radius: 4px;
      width: 80px;
      height: 80px;
      object-fit: cover;
      transition: border-color 0.3s;
    }
    .thumbnails img.thumbnail.active {
      border-color: #f26d25;
    }
    .main-image img {
      width: 650px;
      max-width: 100%;
      border-radius: 12px;
      object-fit: contain;
      box-shadow: 0 6px 18px rgba(0, 0, 0, 0.2);
    }
    /* Thông tin sản phẩm */
    .product-info {
      max-width: 600px;
    }
    .product-info h1 {
      font-size: 24px;
      margin-bottom: 20px;
    }
    .product-info .price {
      font-size: 22px;
      color: red;
      margin: 15px 0;
      font-weight: bold;
      display: flex;
      align-items: center;
    }
    .price-old {
      color: #999;
      text-decoration: line-through;
      font-size: 14px;
      margin-left: 10px;
    }
    .sale-badge {
      color: white;
      background: red;
      font-size: 14px;
      padding: 3px 8px;
      border-radius: 4px;
      margin-left: 10px;
      vertical-align: middle;
    }
    .quantity {
      display: flex;
      align-items: center;
      margin: 20px 0;
    }
    .quantity input {
      width: 50px;
      text-align: center;
      font-size: 16px;
      border: 1px solid #ccc;
      border-radius: 8px;
      padding: 4px 0;
      margin: 0 8px;
    }
    .quantity button {
      width: 32px;
      height: 32px;
      font-size: 18px;
      background: #eee;
      border: none;
      cursor: pointer;
      border-radius: 8px; /* Bo tròn góc */
      transition: background-color 0.3s;
    }
    .quantity button:hover {
      background-color: #ddd;
    }
    .add-to-cart button {
      background: red;
      color: white;
      border: none;
      padding: 12px 30px;
      font-size: 16px;
      cursor: pointer;
      border-radius: 12px; /* Bo tròn góc lớn hơn */
      transition: background-color 0.3s;
    }
    .add-to-cart button:hover {
      background: #c62828;
    }
    .product-detail {
      margin-top: 20px;
    }
    .comment-section {
      margin-top: 30px;
      max-height: 400px;
      overflow-y: auto;
    }
    .comment {
      margin-bottom: 20px;
      padding: 15px;
      border-bottom: 1px solid #eee;
    }
    .comment img {
      max-width: 200px;
      border: 1px solid #ccc;
      border-radius: 8px;
      margin-top: 8px;
    }
    .comment i {
      color: orange;
    }
    .comment-replies {
      margin-left: 30px; 
      margin-top: 15px;
      border-left: 2px solid #ccc;
      padding-left: 15px;
      background: #fafafa;
      border-radius: 4px;
    }
    .comment-reply {
      margin-bottom: 15px;
      padding-bottom: 10px;
      border-bottom: 1px solid #ddd;
    }
  </style>
</head>
<?php include('includes/header.php'); ?>
<?php include('includes/top-menu.php'); ?>
<body>

<!-- Main product section -->
<div class="product-container">

  <!-- Ảnh sản phẩm -->
  <div class="product-images">
    <div class="thumbnails">
      <?php foreach ($images as $img): ?>
        <img src="assets/images/product/<?php echo htmlspecialchars($img); ?>" alt="thumbnail" class="thumbnail" />
      <?php endforeach; ?>
    </div>
    <div class="main-image">
      <img src="assets/images/product/<?php echo htmlspecialchars($images[0] ?? ''); ?>" alt="Ảnh sản phẩm" id="mainProductImage" />
    </div>
  </div>

  <!-- Thông tin sản phẩm -->
  <div class="product-info">
    <h1>
      <?php echo htmlspecialchars($product['name']); ?>
      <?php if ($product['discount'] > 0): ?>
        <span class="sale-badge">SALE</span>
      <?php endif; ?>
    </h1>

    <?php if ($product['price'] == 0): ?>
      <div class="price">Liên hệ nhận báo giá</div>
    <?php elseif ($product['discount'] > 0): ?>
      <div class="price">
        <?php echo number_format($product['discount'], 0, ',', '.') . ' VND'; ?>
        <span class="price-old"><?php echo number_format($product['price'], 0, ',', '.') . ' VND'; ?></span>
      </div>
    <?php else: ?>
      <div class="price"><?php echo number_format($product['price'], 0, ',', '.') . ' VND'; ?></div>
    <?php endif; ?>

    <?php if ($product['price'] > 0): ?>
      <form class="add-to-cart-form" data-id="<?= $product['id'] ?>">
        <div class="quantity">
          <button type="button" onclick="changeQty(-1)">-</button>
          <input type="number" name="qty" id="qty" value="1" min="1" />
          <button type="button" onclick="changeQty(1)">+</button>
        </div>
        <div class="add-to-cart">
          <button type="submit"><i class="fas fa-shopping-cart"></i> Thêm vào giỏ</button>
        </div>

        <?php if (!empty($product['content'])): ?>
          <div class="product-detail" style="margin-top: 20px;">
            <h3>Chi tiết sản phẩm</h3>
            <p><?= nl2br(htmlspecialchars($product['content'])) ?></p>
          </div>
        <?php endif; ?>
      </form>
    <?php else: ?>
      <?php if (!empty($product['content'])): ?>
        <div class="product-detail" style="margin-top: 20px;">
          <h3>Chi tiết sản phẩm</h3>
          <p><?= nl2br(htmlspecialchars($product['content'])) ?></p>
        </div>
      <?php endif; ?>
    <?php endif; ?>

    <!-- Hiển thị bình luận -->
    <div class="comment-section">
      <h2>Bình luận</h2>
      <?php if ($comments->num_rows > 0): ?>
        <?php while ($row = $comments->fetch_assoc()): ?>
          <div class="comment" style="margin-bottom: 30px; padding-bottom: 15px; border-bottom: 1px solid #eee;">
            <strong><?php echo htmlspecialchars($row['user_name']); ?></strong>
            <span style="color: #999; font-size: 13px;"> - <?php echo date('d/m/Y H:i', $row['date']); ?></span>
            <div style="margin-top: 8px;">
              <?php echo nl2br(htmlspecialchars($row['content'])); ?>
            </div>
            <?php if (!empty($row['image_link'])): ?>
              <div>
                <img src="assets/images/reviews/<?php echo htmlspecialchars($row['image_link']); ?>" alt="Ảnh bình luận" />
              </div>
            <?php endif; ?>
            <div style="margin-top: 8px;">
              <?php for ($i = 1; $i <= 5; $i++): ?>
                <i class="fa<?= $i <= $row['rate'] ? 's' : 'r' ?> fa-star"></i>
              <?php endfor; ?>
            </div>

            <!-- Hiển thị reply -->
            <?php
              $commentId = $row['id'];
              $sqlReply = "SELECT cr.*, u.name AS user_name FROM comment_reply cr LEFT JOIN user u ON cr.user_id = u.id WHERE cr.comment_id = ? ORDER BY cr.created ASC";
              $stmtReply = $conn->prepare($sqlReply);
              $stmtReply->bind_param("i", $commentId);
              $stmtReply->execute();
              $replies = $stmtReply->get_result();
            ?>
            <?php if ($replies->num_rows > 0): ?>
              <div class="comment-replies">
                <?php while ($reply = $replies->fetch_assoc()): ?>
                  <div class="comment-reply" style="margin-bottom: 15px; padding-bottom: 10px; border-bottom: 1px solid #ddd;">
                    <strong><?php echo htmlspecialchars($reply['user_name']); ?></strong>
                    <span style="color: #999; font-size: 12px;"> - <?php echo date('d/m/Y H:i', strtotime($reply['created'])); ?></span>
                    <div style="margin-top: 6px;">
                      <?php echo nl2br(htmlspecialchars($reply['content'])); ?>
                    </div>
                  </div>
                <?php endwhile; ?>
              </div>
            <?php endif; ?>
          </div>
        <?php endwhile; ?>
      <?php else: ?>
        <p>Chưa có bình luận nào cho sản phẩm này.</p>
      <?php endif; ?>
    </div>

  </div>
</div>

</body>
<?php include('includes/footer.php'); ?>
</html>

<script>
function changeQty(amount) {
  const input = document.getElementById('qty');
  let val = parseInt(input.value);
  if (!isNaN(val)) {
    val += amount;
    if (val < 1) val = 1;
    input.value = val;
  }
}

const thumbnails = document.querySelectorAll('.thumbnails img.thumbnail');
const mainImage = document.getElementById('mainProductImage');

thumbnails.forEach(thumbnail => {
  thumbnail.addEventListener('click', () => {
    mainImage.src = thumbnail.src;
    thumbnails.forEach(img => img.classList.remove('active'));
    thumbnail.classList.add('active');
  });
});

if (thumbnails.length > 0) {
  thumbnails[0].classList.add('active');
}

document.querySelector('.add-to-cart-form')?.addEventListener('submit', function(e) {
  e.preventDefault();
  const productId = this.getAttribute('data-id');
  const qty = document.getElementById('qty').value;

  fetch('add_to_cart_ajax.php', {
    method: 'POST',
    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
    body: 'product_id=' + productId + '&qty=' + qty
  })
  .then(res => res.json())
  .then(data => {
    if (data.success) {
      let badge = document.querySelector('.cart-count');
      if (badge) badge.textContent = data.total_items;
      else {
        const cartIcon = document.querySelector('.header-icons a[href="cart.php"]');
        if(cartIcon) {
          const span = document.createElement('span');
          span.className = 'cart-count';
          span.textContent = data.total_items;
          cartIcon.appendChild(span);
        }
      }
      alert('Đã thêm vào giỏ hàng!');
    } else {
      alert('Có lỗi xảy ra khi thêm vào giỏ.');
    }
  })
  .catch(() => alert('Không thể kết nối đến máy chủ.'));
});
</script>
