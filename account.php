<?php
session_start();
require __DIR__ . '/config/db.php';

// Kiểm tra đăng nhập
if (!isset($_SESSION['user'])) {
  header("Location: login.php");
  exit;
}

// Gán thông tin user từ session
$user = $_SESSION['user'];
$userId = $user['id'];
$name = $user['name'] ?? 'Chưa có tên';
$email = $user['email'] ?? 'Chưa có email';
$phone = $user['phone'] ?? 'Chưa có số điện thoại';
$address = $user['address'] ?? 'Chưa có địa chỉ';

// Lấy đơn hàng theo transaction
$sql = "
  SELECT 
    t.id AS transaction_id,
    t.created AS transaction_date,
    t.status,
    p.id AS product_id,
    p.name AS product_name,
    o.qty
  FROM transaction t
  JOIN `order` o ON t.id = o.transaction_id
  JOIN product p ON o.product_id = p.id
  WHERE t.user_id = ?
  ORDER BY t.created DESC
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

$orderedProducts = [];
while ($row = $result->fetch_assoc()) {
    $tid = $row['transaction_id'];
    $orderedProducts[$tid]['date'] = $row['transaction_date'];
    $orderedProducts[$tid]['status'] = $row['status'];
    $orderedProducts[$tid]['items'][] = [
       'id'   => $row['product_id'], 
        'name' => $row['product_name'],
        'qty'  => $row['qty']
    ];
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Tài khoản của bạn</title>
    <!-- Favicon SVG dạng data URI -->
  <link rel="icon" type="image/svg+xml" href='data:image/svg+xml;utf8,
  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 180 180" fill="none">
    <rect x="50" y="70" width="80" height="70" fill="%233c7189" rx="12" ry="12"/>
    <polygon points="45,70 90,30 135,70" fill="%23f26d25"/>
    <rect x="80" y="110" width="20" height="20" fill="%23f9f3f0" rx="4" ry="4"/>
    <rect x="85" y="90" width="10" height="20" fill="%23f9f3f0" rx="3" ry="3"/>
  </svg>' />
  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body {
      font-family: Arial, sans-serif;
      background: #fff;
    }
    .container {
      max-width: 1200px;
      margin: 120px auto 50px;
      display: flex;
      gap: 50px;
      padding: 0 30px;
    }
    .sidebar {
      width: 220px;
    }
    .sidebar h3 {
      font-size: 16px;
      color: #555;
      text-transform: uppercase;
      margin-bottom: 25px;
    }
    .sidebar ul {
      list-style: none;
    }
    .sidebar li {
      margin-bottom: 15px;
      color: #666;
      font-size: 15px;
    }
    .sidebar li::before {
      content: "• ";
      color: #999;
      margin-right: 8px;
    }
    .sidebar a {
      color: #333;
      text-decoration: none;
    }
    .sidebar a:hover {
      text-decoration: underline;
    }
    .main {
      flex: 1;
    }
    .main h1 {
      font-size: 28px;
      font-weight: 600;
      color: #444;
      text-align: center;
      margin-bottom: 10px;
    }
    .main hr {
      width: 60px;
      border: 2px solid #333;
      margin: 0 auto 30px;
    }
    .account-info {
      border-bottom: 1px solid #eee;
      padding-bottom: 20px;
      margin-bottom: 30px;
    }
    .account-info p {
      font-size: 16px;
      color: #333;
      margin: 8px 0;
    }
    .account-info a {
      color: #0073aa;
      text-decoration: none;
      font-size: 14px;
    }
    .account-info a:hover {
      text-decoration: underline;
    }
    .highlight-box {
      border: 1px solid #e2e6ed;
      background-color: #f7f9fc;
      padding: 20px;
      font-size: 15px;
      color: #444;
    }
    .item-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 10px;
  }

    .review-btn {
      padding: 4px 10px;
      font-size: 14px;
      color: #007bff;
      border: 1px solid #007bff;
      border-radius: 4px;
      background-color: #fff;
      text-decoration: none;
      transition: all 0.2s ease-in-out;
    }

    .review-btn:hover {
      background-color: #007bff;
      color: #fff;
      text-decoration: none;
    }
    
  </style>
</head>
<body>
<?php include('includes/header.php'); ?>
<?php include('includes/top-menu.php'); ?>

<div class="container">
  <div class="sidebar">
    <h3>Tài khoản</h3>
    <ul>
      <li><a href="#">Thông tin tài khoản</a></li>
      <li><a href="address.php">Danh sách địa chỉ</a></li>
      <li><a href="logout.php">Đăng xuất</a></li>
    </ul>
  </div>

  <div class="main">
    <h1>Tài khoản của bạn</h1>
    <hr>
    <div class="account-info">
      <p><strong>Họ tên: <?= htmlspecialchars($name) ?></strong></p>
      <p>Email: <?= htmlspecialchars($email) ?></p>
      <p>Số điện thoại: <?= htmlspecialchars($phone) ?></p>
      <p>Địa chỉ: <?= htmlspecialchars($address) ?></p>
      <p><a href="address.php">Chỉnh sửa địa chỉ</a></p>
    </div>

    <div class="highlight-box">
      <?php if (empty($orderedProducts)): ?>
        <p>Bạn chưa đặt mua sản phẩm nào.</p>
      <?php else: ?>
        <h3 style="margin-bottom: 20px;">Lịch sử đơn hàng</h3>
        <?php
          $statusMap = [
            0 => 'Chờ xác nhận',
            1 => 'Đã xác nhận',
            2 => 'Đang giao',
            3 => 'Đã giao',
            4 => 'Đã hủy'
          ];
        ?>
        <?php foreach ($orderedProducts as $tid => $data): ?>
          <div style="border: 1px solid #ddd; background: #fff; margin-bottom: 20px; border-radius: 6px; overflow: hidden;">
            <div style="background: #f5f5f5; padding: 12px 20px; border-bottom: 1px solid #ddd;">
              <strong>Đơn hàng #<?= $tid ?></strong> 
              <span style="float: right;">
                Ngày đặt: <?= date('d/m/Y H:i', $data['date']) ?> | 
                Trạng thái: <strong style="color: #007bff;">
                  <?= $statusMap[$data['status']] ?? 'Không xác định' ?>
                </strong>
              </span>
            </div>
            <div style="padding: 15px 20px;">
              <ul style="margin-left: 20px;">
                <?php foreach ($data['items'] as $item): ?>
                  <li class="item-row">
                    <span>
                      <?= htmlspecialchars($item['name']) ?> 
                      (Số lượng: <?= $item['qty'] ?>)
                    </span>

              <?php if ($data['status'] == 3): ?>
                <?php
                  // Kiểm tra đã đánh giá chưa
                  $checkStmt = $conn->prepare("SELECT id FROM comment WHERE user_id = ? AND product_id = ?");
                  $checkStmt->bind_param("ii", $userId, $item['id']);
                  $checkStmt->execute();
                  $checkResult = $checkStmt->get_result();
                  $hasReviewed = $checkResult->num_rows > 0;
                  $checkStmt->close();
                ?>

                <?php if ($hasReviewed): ?>
                  <span style="color: green; font-size: 14px;">✅ Đã đánh giá</span>
                <?php else: ?>
                  <a href="review.php?product_id=<?= $item['id'] ?>" class="review-btn">
                    Đánh giá sản phẩm
                  </a>
                <?php endif; ?>
              <?php endif; ?>
                  </li>
                <?php endforeach; ?>
              </ul>
            </div>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
  </div>
</div>

<?php include('includes/footer.php'); ?>
</body>
</html>
