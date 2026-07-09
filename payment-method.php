<?php
session_start();
$conn = new mysqli("localhost", "root", "", "website");
$conn->set_charset("utf8");

// Kiểm tra nếu chưa có transaction_id thì quay về trang chủ
if (!isset($_SESSION['last_transaction_id'])) {
  header("Location: index.php");
  exit;
}
$transaction_id = $_SESSION['last_transaction_id'];

$selected_payment = $_POST['payment_method'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $selected_payment) {
  $payment = $selected_payment;

  $stmt = $conn->prepare("UPDATE transaction SET payment = ? WHERE id = ?");
  $stmt->bind_param("si", $payment, $transaction_id);
  $stmt->execute();
  $stmt->close();

  unset($_SESSION['last_transaction_id']);
  echo "<script>alert('Cảm ơn bạn đã đặt hàng!'); window.location.href = 'index.php';</script>";
  exit;
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8" />
  <title>Phương thức thanh toán</title>
  <!-- Favicon SVG dạng data URI -->
  <link rel="icon" type="image/svg+xml" href='data:image/svg+xml;utf8,
  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 180 180" fill="none">
    <rect x="50" y="70" width="80" height="70" fill="%233c7189" rx="12" ry="12"/>
    <polygon points="45,70 90,30 135,70" fill="%23f26d25"/>
    <rect x="80" y="110" width="20" height="20" fill="%23f9f3f0" rx="4" ry="4"/>
    <rect x="85" y="90" width="10" height="20" fill="%23f9f3f0" rx="3" ry="3"/>
  </svg>' />
  <style>
    body { font-family: Arial; padding: 40px; background: #f9f9f9; }
    .box { max-width: 600px; margin: auto; background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
    h2 { margin-bottom: 20px; font-size: 20px; }
    .method { border: 1px solid #ccc; border-radius: 6px; padding: 15px; margin-bottom: 15px; }
    label { display: flex; align-items: center; cursor: pointer; }
    input[type="radio"] { margin-right: 10px; }
    .info { margin-top: 10px; font-size: 14px; color: #444; }
    .submit-btn { margin-top: 20px; padding: 12px 20px; background: #007bff; color: white; border: none; border-radius: 4px; font-size: 16px; cursor: pointer; }
    .submit-btn:hover { background: #0056b3; }
    .qr-img img, .bank-transfer-info, .credit-card-info {
      max-width: 100%;
      height: auto;
      border: 1px solid #ccc;
      border-radius: 6px;
      margin-top: 10px;
    }
    .back { display: inline-block; margin-top: 15px; font-size: 14px; color: #007bff; text-decoration: none; }
  </style>
</head>
<body>
  <div class="box">
    <h2>Chọn phương thức thanh toán</h2>
    <form method="post" id="payment-form">

      <div class="method">
        <label>
          <input type="radio" name="payment_method" value="qr" onclick="togglePaymentInfo('qr')" <?= $selected_payment === 'qr' ? 'checked' : '' ?> required>
          <span>Thanh toán bằng mã QR (ví điện tử)</span>
        </label>
        <div id="qr-section" class="qr-img" style="display: <?= $selected_payment === 'qr' ? 'block' : 'none' ?>;">
          <img src="assets/images/qr_code/qr_code.jpg" alt="QR Code thanh toán">
          <div class="info">
            Quét mã QR để thanh toán bằng Momo, ZaloPay, hoặc ứng dụng ngân hàng.
          </div>
        </div>
      </div>

      <div class="method">
        <label>
          <input type="radio" name="payment_method" value="cod" onclick="togglePaymentInfo('cod')" <?= $selected_payment === 'cod' ? 'checked' : '' ?> required>
          <span>Thanh toán khi nhận hàng (COD)</span>
        </label>
        <div class="info" id="cod-info" style="display: <?= $selected_payment === 'cod' ? 'block' : 'none' ?>;">
          Bạn sẽ thanh toán trực tiếp cho nhân viên giao hàng.
        </div>
      </div>

      <div class="method">
        <label>
          <input type="radio" name="payment_method" value="bank_transfer" onclick="togglePaymentInfo('bank_transfer')" <?= $selected_payment === 'bank_transfer' ? 'checked' : '' ?> required>
          <span>Chuyển khoản ngân hàng</span>
        </label>
        <div class="bank-transfer-info info" style="display: <?= $selected_payment === 'bank_transfer' ? 'block' : 'none' ?>;">
          <p>Thông tin chuyển khoản:<br>
          Tên tài khoản: Công Ty Cổ Phần Hợp Tác Kinh Tế Và Xuất Nhập Khẩu HomeStyle<br>
            Số tài khoản: 0071001303667<br>
            Ngân hàng: Vietcombank – CN HN<br>
            Nội dung: Tên + SĐT đặt hàng
          </p>
        </div>
      </div>

      <div class="method">
        <label>
          <input type="radio" name="payment_method" value="credit_card" onclick="togglePaymentInfo('credit_card')" <?= $selected_payment === 'credit_card' ? 'checked' : '' ?> required>
          <span>Thanh toán thẻ tín dụng</span>
        </label>
        <div class="credit-card-info info" style="display: <?= $selected_payment === 'credit_card' ? 'block' : 'none' ?>;">
          Thanh toán qua cổng thẻ tín dụng (Visa, MasterCard).
        </div>
      </div>

      <button type="submit" class="submit-btn">Hoàn tất đơn hàng</button>
    </form>
    <a class="back" href="index.php">← Quay về trang chủ</a>
  </div>

  <script>
    function togglePaymentInfo(method) {
      document.getElementById('qr-section').style.display = (method === 'qr') ? 'block' : 'none';
      document.getElementById('cod-info').style.display = (method === 'cod') ? 'block' : 'none';
      document.querySelector('.bank-transfer-info').style.display = (method === 'bank_transfer') ? 'block' : 'none';
      document.querySelector('.credit-card-info').style.display = (method === 'credit_card') ? 'block' : 'none';
    }

    // Khởi tạo trạng thái hiển thị theo radio đã chọn (nếu có)
    window.onload = function () {
      const selected = document.querySelector('input[name="payment_method"]:checked');
      if (selected) togglePaymentInfo(selected.value);
    };
  </script>
</body>
</html>
