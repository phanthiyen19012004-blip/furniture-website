<?php
session_start();
$conn = new mysqli("localhost", "root", "", "website");
$conn->set_charset("utf8");

$code = $_POST['coupon_code'] ?? '';
$response = ['success' => false];

if (!$code) {
  $response['message'] = 'Vui lòng nhập mã giảm giá';
  echo json_encode($response);
  exit;
}

// Tìm mã giảm giá hợp lệ trong DB
$stmt = $conn->prepare("SELECT discount_percent, expiry_date FROM coupons WHERE code = ? AND active = 1 LIMIT 1");
$stmt->bind_param("s", $code);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
  $now = date('Y-m-d');
  if ($row['expiry_date'] < $now) {
    $response['message'] = 'Mã giảm giá đã hết hạn';
  } else {
    $total = $_SESSION['checkout_total'] ?? 0;

    $discount = $row['discount_percent'];
    $newTotal = $total * (1 - $discount / 100);

    $_SESSION['applied_coupon'] = $code;
    $_SESSION['discount_percent'] = $discount;
    $_SESSION['discounted_total'] = $newTotal;

    $response['success'] = true;
    $response['discount_percent'] = $discount;
    $response['new_total'] = round($newTotal);
  }
} else {
  $response['message'] = 'Mã giảm giá không tồn tại hoặc không hợp lệ';
}

$stmt->close();
echo json_encode($response);
