<?php
session_start();
$conn = new mysqli("localhost", "root", "", "website");
$conn->set_charset("utf8");
$is_logged_in = isset($_SESSION['user']);

$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
$total = 0;
$cart_items = [];

if (!empty($cart)) {
    $ids = implode(',', array_keys($cart));
    $result = $conn->query("SELECT * FROM product WHERE id IN ($ids)");

    while ($row = $result->fetch_assoc()) {
        $qty = $cart[$row['id']]['qty'];
        $price = $row['price'];
        $discount = $row['discount'];
        $final_price = ($discount > 0) ? $discount : $price;
        $subtotal = $qty * $final_price;
        $total += $subtotal;

        $row['qty'] = $qty;
        $row['subtotal'] = $subtotal;
        $row['final_price'] = $final_price;
        $cart_items[] = $row;
    }
}

if (isset($_POST['submit_order']) && !empty($cart_items)) {
    if (!$is_logged_in) {
        echo "<script>alert('Bạn cần đăng nhập để thanh toán'); window.location.href='login.php';</script>";
        exit;
    }

    $user = $_SESSION['user'];
    $user_name = $user['name'];
    $user_email = $user['email'] ?? '';
    $message = $_POST['note'] ?? '';
    $total_amount = $total;
    $created = time();

    $stmt = $conn->prepare("INSERT INTO transaction (user_name, user_email, message, amount, status, created)
                            VALUES (?, ?, ?, ?, 0, ?)");
    $stmt->bind_param("sssdd", $user_name, $user_email, $message, $total_amount, $created);
    $stmt->execute();
    $transaction_id = $stmt->insert_id;
    $stmt->close();

    foreach ($cart_items as $item) {
        $product_id = $item['id'];
        $qty = $item['qty'];
        $amount = $item['final_price'];
        $stmt = $conn->prepare("INSERT INTO `order` (transaction_id, product_id, qty, amount, status)
                                VALUES (?, ?, ?, ?, 0)");
        $stmt->bind_param("iiid", $transaction_id, $product_id, $qty, $amount);
        $stmt->execute();
        $stmt->close();
    }

    // ❗ KHÔNG xóa giỏ hàng ở đây
    header("Location: checkout.php?id=" . $transaction_id);
    exit;
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Giỏ hàng của bạn</title>
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
      background: #fff;
    }
    h2 {
      margin-top: 10px;
      text-align: center;
      margin-bottom: 20px;
      font-size: 24px;
    }
    .cart-container {
      display: flex;
      justify-content: center;
      gap: 40px;
      max-width: 1200px;
      margin: auto;
    }
    .cart-left {
      flex: 2;
      background: #fafafa;
      padding: 30px;
      border-radius: 8px;
    }
    .cart-right {
      flex: 1;
      background: #fff;
      padding: 25px;
      border: 1px solid #ddd;
      border-radius: 6px;
      height: fit-content;
      display: flex;
      flex-direction: column;
      gap: 15px;
    }
    .item {
      display: flex;
      justify-content: space-between;
      align-items: flex-start;
      gap: 20px;
      padding: 20px;
      background: #fff;
      border: 1px solid #eee;
      margin-bottom: 10px;
    }
    .item img {
      width: 80px;
      height: 80px;
      object-fit: contain;
      flex-shrink: 0;
      border-radius: 6px;
    }
    .item-info {
      flex: 1;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
    }
    .item-name {
      font-size: 15px;
      font-weight: 500;
      margin-bottom: 10px;
    }
    .item-qty-price {
      display: flex;
      align-items: center;
      gap: 20px;
    }
    .item-qty {
      display: flex;
      align-items: center;
      gap: 8px;
    }
    .item-qty button {
      width: 28px;
      height: 28px;
      border: 1px solid #ccc;
      background: #f0f0f0;
      cursor: pointer;
      border-radius: 6px;
      transition: background-color 0.3s;
    }
    .item-qty button:hover {
      background-color: #ddd;
    }
    .item-qty input {
      width: 35px;
      text-align: center;
      border: 1px solid #ccc;
      height: 28px;
      border-radius: 6px;
      user-select: none;
      background-color: #fff;
    }
    .item-price {
      font-size: 14px;
      color: #333;
    }
    .item-sub {
      text-align: right;
      min-width: 140px; /* Tăng rộng đủ chỗ */
      display: flex;
      flex-direction: column;
      align-items: flex-end;
      gap: 6px;
      position: relative; /* giữ để icon có thể position nếu cần */
      padding-right: 0; /* bỏ padding bên phải vì dùng flex */
    }

    .item-sub > div:first-child {
      font-size: 13px;
      color: #444;
    }

    .item-subtotal {
      color: red;
      font-weight: bold;
      margin: 5px 0 0 0;
    }

    .trash {
      font-size: 18px;
      cursor: pointer;
      color: #888;
      align-self: flex-end;
      transition: color 0.3s;
      margin-top: 4px;
    }

    .trash:hover {
      color: red;
    }

    .policy {
      margin-top: 30px;
    }
    textarea {
      width: 100%;
      height: 100px;
      padding: 10px;
      border-radius: 6px;
      border: 1px solid #ccc;
      resize: vertical;
    }
    .cart-right h3 {
      font-size: 16px;
      font-weight: bold;
      margin-bottom: 5px;
    }
    .summary-row {
      display: flex;
      justify-content: space-between;
      font-size: 15px;
      margin: 10px 0;
    }
    .cart-right .note {
      font-size: 13px;
      color: #777;
      margin-bottom: 10px;
    }
    .checkout-btn {
      background: black;
      color: white;
      padding: 12px;
      font-size: 16px;
      border: none;
      width: 100%;
      cursor: pointer;
      font-weight: bold;
      transition: background 0.3s ease;
      border-radius: 6px;
    }
    .checkout-btn:hover {
      background: #222;
    }
  </style>
</head>
<body>
  <?php include('includes/header.php'); ?>
  <?php include('includes/top-menu.php'); ?>
<h2>GIỎ HÀNG CỦA BẠN</h2>
<div class="cart-container">
  <div class="cart-left">
    <?php if (empty($cart_items)): ?>
      <p>Bạn chưa có sản phẩm nào trong giỏ.</p>
    <?php else: ?>
      <p>Bạn đang có <?= count($cart_items) ?> sản phẩm trong giỏ hàng</p>

      <?php foreach ($cart_items as $item): ?>
      <div class="item">
        <?php
          $images = [];
          if (!empty($item['image_list'])) {
            $images = explode(',', $item['image_list']);
            $images = array_map('trim', $images);
          }
          $imageFile = $images[0] ?? $item['image_link'] ?? 'default.png';
          $imagePath = 'assets/images/product/' . $imageFile;
        ?>
        <img src="<?= htmlspecialchars($imagePath) ?>" alt="<?= htmlspecialchars($item['name']) ?>">
        <div class="item-info">
          <div class="item-name"><?= htmlspecialchars($item['name']) ?></div>
          <div class="item-qty-price">
            <div class="item-qty">
              <button onclick="changeQty(<?= $item['id'] ?>, -1)">-</button>
              <input type="text" id="qty-<?= $item['id'] ?>" value="<?= $item['qty'] ?>" readonly>
              <button onclick="changeQty(<?= $item['id'] ?>, 1)">+</button>
            </div>
            <div class="item-price"><?= number_format($item['final_price'], 0, ',', '.') ?> VNĐ</div>
          </div>
        </div>
        <div class="item-sub">
          <div>Thành tiền:</div>
          <div class="item-subtotal"><?= number_format($item['subtotal'], 0, ',', '.') ?> VNĐ</div>
          <span class="trash" onclick="removeItem(<?= $item['id'] ?>)"><i class="fas fa-trash"></i></span>
        </div>
      </div>
      <?php endforeach; ?>

      <div class="note">
        <label for="note">Ghi chú đơn hàng</label>
        <textarea name="note" id="note" placeholder="Ví dụ: Giao vào giờ hành chính, kiểm tra trước khi nhận..."></textarea>
      </div>

      <div class="policy">
        <h4>Chính sách Đổi/Trả</h4>
        <ul>
          <li>Sản phẩm được đổi 1 lần duy nhất, không hỗ trợ trả.</li>
          <li>Sản phẩm còn đủ tem mác, chưa qua sử dụng.</li>
          <li>Đổi trong 30 ngày tại hệ thống cửa hàng toàn quốc.</li>
          <li>Sản phẩm sale chỉ đổi trong 7 ngày nếu còn hàng.</li>
        </ul>
      </div>
    <?php endif; ?>
  </div>

  <div class="cart-right">
    <h3>Thông tin đơn hàng</h3>
    <hr>
    <div class="summary-row">
      <span>Tổng tiền:</span>
      <strong><?= number_format($total, 0, ',', '.') ?> VNĐ</strong>
    </div>
    <form method="post" onsubmit="copyNote()">
      <input type="hidden" name="note" id="note-hidden">
      <button type="submit" name="submit_order" class="checkout-btn">THANH TOÁN</button>
    </form>
  </div>
</div>

<script>
function changeQty(id, delta) {
  const input = document.getElementById('qty-' + id);
  let qty = parseInt(input.value) + delta;
  if (qty < 1) qty = 1;

  fetch('cart_action.php', {
    method: 'POST',
    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
    body: 'product_id=' + id + '&qty=' + qty + '&action=update'
  })
  .then(res => res.json())
  .then(data => {
    if (data.success) {
      input.value = qty;
      location.reload();
    }
  });
}

function removeItem(id) {
  if (!confirm('Bạn có chắc muốn xoá sản phẩm này?')) return;

  fetch('cart_action.php', {
    method: 'POST',
    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
    body: 'product_id=' + id + '&action=delete'
  })
  .then(res => res.json())
  .then(data => {
    if (data.success) {
      location.reload();
    }
  });
}

function copyNote() {
  const noteValue = document.getElementById('note').value;
  document.getElementById('note-hidden').value = noteValue;
}
</script>

<?php $conn->close(); ?>
</body>
</html>
