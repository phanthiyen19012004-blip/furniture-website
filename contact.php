<?php
// Kết nối database
$conn = new mysqli("localhost", "root", "", "website");
$conn->set_charset("utf8");

// Lấy dữ liệu liên hệ
$info = $conn->query("SELECT phone, zalo, address FROM contact_info LIMIT 1")->fetch_assoc();

// Nếu không có dữ liệu thì dùng mặc định tránh lỗi
if (!$info) {
    $info = [
        'phone' => '0937999997',
        'zalo' => '0328104037',
        'address' => 'Chưa có địa chỉ cập nhật'
    ];
}
?>

<!-- Nút liên hệ nổi góc phải -->
<div class="floating-contact">
  <a href="tel:<?= htmlspecialchars($info['phone']) ?>" class="contact-btn phone"><i class="fas fa-phone-alt"></i></a>
  <a href="https://zalo.me/<?= htmlspecialchars($info['zalo']) ?>" target="_blank" class="contact-btn zalo">
    <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/9/91/Icon_of_Zalo.svg/512px-Icon_of_Zalo.svg.png" alt="Zalo">
  </a>
  <div class="contact-btn email" onclick="toggleModal(true)"><i class="fas fa-envelope"></i></div>
<a href="<?= htmlspecialchars($info['address'] ?: '#') ?>" target="_blank" class="contact-btn location">
  <i class="fas fa-map-marker-alt"></i>
</a>
</div>
<!-- Popup form liên hệ -->
<div class="modal-overlay" id="contactModal">
  <div class="modal-box">
    <span class="close-btn" onclick="toggleModal(false)">&times;</span>
    <h3>Để lại lời nhắn cho chúng tôi</h3>
    <form method="post" action="">
      <input type="hidden" name="contact_submit" value="1">
      <input type="text" name="name" placeholder="Tên của bạn" required>
      <input type="email" name="email" placeholder="Email của bạn" required>
      <input type="text" name="phone" placeholder="Số điện thoại của bạn" required>
      <textarea name="message" placeholder="Nội dung" rows="4" required></textarea>
      <button type="submit">GỬI CHO CHÚNG TÔI</button>
    </form>
  </div>
</div>

<style>
.floating-contact {
  position: fixed;
  right: 20px;
  bottom: 20px;
  display: flex;
  flex-direction: column;
  gap: 12px;
  z-index: 10000;
}

.contact-btn {
  width: 36px;
  height: 36px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  color: white;
  box-shadow: 0 4px 8px rgba(0,0,0,0.2);
  cursor: pointer;
  transition: transform 0.2s;
}

.contact-btn:hover {
  transform: scale(1.1);
}

.contact-btn.phone    { background: #e53935; }
.contact-btn.zalo     { background: #0077ff; }
.contact-btn.email    { background: #00e1ff; }
.contact-btn.location { background: #fbc02d; }

.contact-btn i {
  font-size: 14px;
}
.contact-btn.zalo img {
  width: 18px;
  height: 18px;
}

/* Popup modal */
.modal-overlay {
  position: fixed;
  top: 0; left: 0;
  width: 100%; height: 100%;
  background: rgba(0,0,0,0.5);
  display: none;
  align-items: center;
  justify-content: center;
  z-index: 99999;
}
.modal-box {
  background: #fff;
  padding: 30px;
  border-radius: 10px;
  width: 90%;
  max-width: 500px;
  position: relative;
}
.modal-box h3 {
  margin-bottom: 15px;
  font-size: 18px;
  text-align: center;
}
.modal-box input,
.modal-box textarea {
  width: 100%;
  padding: 10px;
  margin-bottom: 10px;
  border: 1px solid #ccc;
}
.modal-box button {
  width: 100%;
  padding: 12px;
  background: black;
  color: white;
  border: none;
  font-weight: bold;
  cursor: pointer;
}
.close-btn {
  position: absolute;
  right: 15px;
  top: 15px;
  font-size: 20px;
  cursor: pointer;
}
@media (max-width: 480px) {
  .floating-contact {
    right: 10px;
    bottom: 10px;
    gap: 8px;
  }
  .contact-btn {
    width: 32px;
    height: 32px;
  }
  .contact-btn i {
    font-size: 12px;
  }
  .contact-btn.zalo img {
    width: 16px;
    height: 16px;
  }
}

</style>

<script>
function toggleModal(show) {
  document.getElementById('contactModal').style.display = show ? 'flex' : 'none';
}
</script>

<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<?php
// Xử lý gửi form liên hệ nếu bạn cần, giữ nguyên như cũ
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['contact_submit'])) {
  $name = $_POST['name'] ?? '';
  $email = $_POST['email'] ?? '';
  $phone = $_POST['phone'] ?? '';
  $message = $_POST['message'] ?? '';

  $stmt = $conn->prepare("INSERT INTO contact_us (name, email, phone, message, created_at) VALUES (?, ?, ?, ?, NOW())");
  $stmt->bind_param("ssss", $name, $email, $phone, $message);
  $stmt->execute();
  $stmt->close();

  echo "<script>alert('Cảm ơn bạn đã gửi liên hệ!');</script>";
}
?>
