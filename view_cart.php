<?php
session_start();
$total = 0;
$items = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
foreach ($items as $item) {
    $total += $item['price'] * $item['qty'];
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Giỏ hàng</title>
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
      margin: 0;
      overflow-x: hidden;
    }

    .cart-overlay {
      position: fixed;
      top: 0; left: 0;
      width: 100%; height: 100%;
      background: rgba(0,0,0,0.6);
      display: flex;
      justify-content: flex-end;
      z-index: 9999;
    }

    .cart-panel {
      background: #fff;
      width: 400px;
      height: 100%;
      padding: 20px;
      position: relative;
      overflow-y: auto;
    }

    .cart-header {
      font-weight: bold;
      font-size: 18px;
      margin-bottom: 15px;
    }

    .close-btn {
      position: absolute;
      top: 10px; right: 15px;
      font-size: 20px;
      cursor: pointer;
    }

    .cart-item {
      display: flex;
      align-items: center;
      margin-bottom: 15px;
      border-bottom: 1px solid #eee;
      padding-bottom: 10px;
    }

    .cart-item img {
      width: 60px;
      height: 60px;
      object-fit: contain;
      margin-right: 10px;
    }

    .cart-item-info {
      flex: 1;
    }

    .cart-item-info .name {
      font-size: 14px;
      font-weight: bold;
    }

    .cart-item-info .price {
      font-size: 13px;
      color: #444;
    }

    .cart-item input[type="number"] {
      width: 40px;
      text-align: center;
      margin-top: 5px;
    }

    .cart-total {
      font-weight: bold;
      font-size: 16px;
      margin-top: 20px;
      text-align: right;
    }

    .cart-buttons {
      margin-top: 20px;
      display: flex;
      justify-content: space-between;
    }

    .cart-buttons a {
      padding: 10px 20px;
      background: #000;
      color: #fff;
      text-decoration: none;
      border: none;
      font-size: 14px;
    }

    .cart-buttons a:hover {
      opacity: 0.8;
    }

    .remove-btn {
      background: none;
      border: none;
      color: #999;
      font-size: 16px;
      cursor: pointer;
    }
  </style>
</head>
<body>

<div class="cart-overlay" id="cartOverlay">
  <div class="cart-panel">
    <div class="close-btn" onclick="document.getElementById('cartOverlay').style.display='none'">&times;</div>
    <div class="cart-header">GIỎ HÀNG</div>

    <?php if (count($items) > 0): ?>
      <?php foreach ($items as $item): ?>
        <div class="cart-item">
          <img src="images/product/<?php echo $item['image']; ?>" alt="">
          <div class="cart-item-info">
            <div class="name"><?php echo $item['name']; ?></div>
            <div class="price"><?php echo number_format($item['price'], 0, ',', '.') . ' VND'; ?></div>
            <input type="number" value="<?php echo $item['qty']; ?>" min="1" readonly>
          </div>
          <form method="post" action="remove_item.php">
            <input type="hidden" name="product_id" value="<?php echo $item['id']; ?>">
            <button type="submit" class="remove-btn">&times;</button>
          </form>
        </div>
      <?php endforeach; ?>

      <div class="cart-total">TỔNG TIỀN: <?php echo number_format($total, 0, ',', '.') . ' VND'; ?></div>

      <div class="cart-buttons">
        <a href="cart.php">XEM GIỎ HÀNG</a>
        <a href="checkout.php">THANH TOÁN</a>
      </div>
    <?php else: ?>
      <p>Giỏ hàng của bạn đang trống.</p>
    <?php endif; ?>
  </div>
</div>

</body>
</html>
