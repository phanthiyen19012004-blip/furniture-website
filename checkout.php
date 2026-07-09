<?php
session_start();
$conn = new mysqli("localhost", "root", "", "website");
$conn->set_charset("utf8");

// Lấy transaction id nếu có (nếu cần)
$transaction_id = $_GET['id'] ?? 0;

// Lấy danh sách dịch vụ vận chuyển
$shipping_result = $conn->query("SELECT * FROM shipping");

$total = 0;
$cart = $_SESSION['cart'] ?? [];

// Tính tổng tiền hàng
if (!empty($cart)) {
    $ids = array_filter(array_keys($cart), 'is_numeric');
    if (!empty($ids)) {
        $ids_str = implode(',', $ids);
        $result = $conn->query("SELECT * FROM product WHERE id IN ($ids_str)");
        while ($row = $result->fetch_assoc()) {
            $qty = $cart[$row['id']]['qty'];
            $price = $row['price'];
            $discount = $row['discount'];
            $final_price = ($discount > 0) ? $discount : $price;
            $subtotal = $qty * $final_price;
            $total += $subtotal;
        }
    }
} else {
    echo "<script>alert('Giỏ hàng trống!'); window.location.href='cart.php';</script>";
    exit;
}

// Lưu tổng tiền vào session để dùng khi áp dụng coupon
$_SESSION['checkout_total'] = $total;

// Xử lý submit đơn hàng
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullname = $_POST['fullname'];
    $phone = $_POST['phone'];
    $province = $_POST['province'];
    $district = $_POST['district'];
    $ward = $_POST['ward'];
    $address_detail = $_POST['address_detail'];
    $ship_fee = $_POST['ship_fee'] ?? 0;

    // Ưu tiên tổng tiền đã giảm nếu có coupon
    $discounted_total = $_SESSION['discounted_total'] ?? null;
    $amount = $_POST['final_total'] ?? $discounted_total ?? $total;

    $address = "$address_detail, $ward, $district, $province";

    $user_id = $_SESSION['user']['id'] ?? null;

    // Cập nhật thông tin đơn hàng vào DB
    $stmt = $conn->prepare("UPDATE transaction SET user_id = ?, user_name = ?, user_phone = ?, user_address = ?, amount = ? WHERE id = ?");
    $stmt->bind_param("isssdi", $user_id, $fullname, $phone, $address, $amount, $transaction_id);
    $stmt->execute();
    $stmt->close();

    $_SESSION['last_transaction_id'] = $transaction_id;
    unset($_SESSION['cart']); // Xóa giỏ hàng
    // Xóa session coupon
    unset($_SESSION['applied_coupon'], $_SESSION['discount_percent'], $_SESSION['discounted_total'], $_SESSION['checkout_total']);

    header("Location: payment-method.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8" />
  <title>Thanh toán</title>
  <style>
    body { font-family: Arial; background: #fff; }
    form { margin-left: 40px; max-width: 600px; }
    h2 {
      margin-left: 30px;
      margin-top: 30px;
      margin-bottom: 10px;
    }
    .form-group { margin-bottom: 15px; }
    label { display: block; font-weight: bold; }
    input, select {
      width: 95%;
      padding: 8px;
      margin-top: 5px;
      border: 1px solid #ccc;
      border-radius: 6px;
      box-sizing: border-box;
    }
    .summary { margin-top: 20px; }
    .summary table { width: 100%; border-collapse: collapse; }
    .summary td { padding: 5px; }
    .total { color: red; font-weight: bold; }
    .btn {
      margin-top: 20px;
      background: green;
      color: white;
      padding: 10px 20px;
      border: none;
      cursor: pointer;
      border-radius: 6px;
      font-size: 16px;
    }
    .btn:hover {
      background: #005900;
    }
    #coupon_message {
      margin-top: 5px;
      font-size: 14px;
      color: red;
    }
  </style>
</head>
<body>
  <?php include('includes/header.php'); ?>
  <?php include('includes/top-menu.php'); ?>

  <h2>Thông tin giao hàng</h2>
  <form action="checkout.php?id=<?= htmlspecialchars($transaction_id) ?>" method="post" onsubmit="return updateHiddenFields();">

    <div class="form-group">
      <label>Họ và tên</label>
      <input type="text" name="fullname" required />
    </div>

    <div class="form-group">
      <label>Số điện thoại</label>
      <input type="text" name="phone" required />
    </div>

    <div class="form-group">
      <label>Tỉnh, Thành phố</label>
      <select id="province" required>
        <option value="">--Chọn Tỉnh/Thành phố--</option>
      </select>
    </div>

    <div class="form-group">
      <label>Quận, Huyện</label>
      <select id="district" required disabled>
        <option value="">--Chọn Quận/Huyện--</option>
      </select>
    </div>

    <div class="form-group">
      <label>Phường, Xã</label>
      <select id="ward" required disabled>
        <option value="">--Chọn Phường/Xã--</option>
      </select>
    </div>

    <!-- Hidden inputs to send location names -->
    <input type="hidden" name="province" id="province_name" />
    <input type="hidden" name="district" id="district_name" />
    <input type="hidden" name="ward" id="ward_name" />

    <div class="form-group">
      <label>Địa chỉ cụ thể</label>
      <input type="text" name="address_detail" required />
    </div>

    <div class="form-group">
      <label>Dịch vụ vận chuyển (Khoảng cách ước tính)</label>
      <select name="shipping_id" id="shipping_id" onchange="calculateShipping()" required>
        <option value="">-- Chọn Dịch Vụ --</option>
        <?php while ($row = $shipping_result->fetch_assoc()): ?>
          <option value="<?= htmlspecialchars($row['id']) ?>" data-km="<?= rand(2, 10) ?>">
            <?= htmlspecialchars($row['name']) ?> (<?= htmlspecialchars($row['name_district']) ?>, <?= htmlspecialchars($row['name_province']) ?>)
          </option>
        <?php endwhile; ?>
      </select>
    </div>

    <!-- Mã giảm giá -->
    <div class="form-group">
      <label>Mã giảm giá (nếu có)</label>
      <input type="text" name="coupon_code" id="coupon_code" placeholder="Nhập mã giảm giá" />
      <button type="button" onclick="applyCoupon()" style="margin-top: 8px; padding: 6px 12px;">Áp dụng</button>
      <div id="coupon_message"></div>
    </div>

    <div class="summary">
      <table>
        <tr><td>Tổng tiền:</td><td><?= number_format($total, 0, ',', '.') ?> VND</td></tr>
        <tr><td>Phí ship:</td><td id="ship_fee">0 VND</td></tr>
        <tr><td><strong>Thành tiền:</strong></td><td class="total" id="final_amount"><?= number_format($total, 0, ',', '.') ?> VND</td></tr>
      </table>
    </div>

    <input type="hidden" name="base_total" value="<?= $total ?>" />
    <input type="hidden" name="ship_fee" id="ship_fee_hidden" />
    <input type="hidden" name="final_total" id="final_total_hidden" />

    <button class="btn" type="submit">Tiếp tục đến phương thức thanh toán</button>
  </form>

  <script>
    function calculateShipping() {
      const select = document.getElementById('shipping_id');
      const option = select.options[select.selectedIndex];
      const km = parseInt(option?.getAttribute('data-km')) || 0;
      const base = <?= $total ?>;
      const fee = km * 5000;

      document.getElementById('ship_fee').textContent = fee.toLocaleString() + ' VND';
      const discountedTotal = parseInt(document.getElementById('final_total_hidden').value) || base;
      document.getElementById('final_amount').textContent = (discountedTotal + fee).toLocaleString() + ' VND';

      document.getElementById('ship_fee_hidden').value = fee;
      document.getElementById('final_total_hidden').value = discountedTotal + fee;
    }

    function updateHiddenFields() {
      const provinceSelect = document.getElementById('province');
      const districtSelect = document.getElementById('district');
      const wardSelect = document.getElementById('ward');

      document.getElementById('province_name').value = provinceSelect.options[provinceSelect.selectedIndex]?.text || '';
      document.getElementById('district_name').value = districtSelect.options[districtSelect.selectedIndex]?.text || '';
      document.getElementById('ward_name').value = wardSelect.options[wardSelect.selectedIndex]?.text || '';

      if (!document.getElementById('ship_fee_hidden').value) {
        alert("Vui lòng chọn đơn vị vận chuyển!");
        return false;
      }
      return true;
    }

    async function loadLocationData() {
      const res = await fetch('https://provinces.open-api.vn/api/?depth=3');
      const data = await res.json();

      const provinceSelect = document.getElementById('province');
      const districtSelect = document.getElementById('district');
      const wardSelect = document.getElementById('ward');

      data.forEach(p => {
        const opt = new Option(p.name, p.code);
        provinceSelect.add(opt);
      });

      provinceSelect.addEventListener('change', () => {
        const selectedProvince = data.find(p => p.code == provinceSelect.value);
        districtSelect.innerHTML = '<option value="">--Chọn Quận/Huyện--</option>';
        wardSelect.innerHTML = '<option value="">--Chọn Phường/Xã--</option>';
        wardSelect.disabled = true;

        if (selectedProvince) {
          selectedProvince.districts.forEach(d => {
            const opt = new Option(d.name, d.code);
            districtSelect.add(opt);
          });
          districtSelect.disabled = false;
        } else {
          districtSelect.disabled = true;
          wardSelect.disabled = true;
        }
      });

      districtSelect.addEventListener('change', () => {
        const selectedProvince = data.find(p => p.code == provinceSelect.value);
        const selectedDistrict = selectedProvince?.districts.find(d => d.code == districtSelect.value);
        wardSelect.innerHTML = '<option value="">--Chọn Phường/Xã--</option>';

        if (selectedDistrict) {
          selectedDistrict.wards.forEach(w => {
            const opt = new Option(w.name, w.name);
            wardSelect.add(opt);
          });
          wardSelect.disabled = false;
        } else {
          wardSelect.disabled = true;
        }
      });

      wardSelect.addEventListener('change', () => {
        // nothing to do here because hidden inputs updated on submit
      });
    }

    loadLocationData();

    // Áp dụng mã giảm giá
    function applyCoupon() {
      const code = document.getElementById('coupon_code').value.trim();
      if (!code) {
        alert('Vui lòng nhập mã giảm giá');
        return;
      }

      fetch('apply_coupon.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: 'coupon_code=' + encodeURIComponent(code)
      })
      .then(res => res.json())
      .then(data => {
        const msg = document.getElementById('coupon_message');
        if (data.success) {
          msg.style.color = 'green';
          msg.textContent = `Mã giảm giá hợp lệ! Giảm ${data.discount_percent}%`;
          updateTotal(data.new_total);
          // Cập nhật phí ship nếu đã chọn dịch vụ vận chuyển
          calculateShipping();
        } else {
          msg.style.color = 'red';
          msg.textContent = data.message || 'Mã giảm giá không hợp lệ';
        }
      })
      .catch(() => alert('Lỗi kết nối server'));
    }

    // Cập nhật tổng tiền hiển thị
    function updateTotal(newTotal) {
      const finalAmount = document.getElementById('final_amount');
      finalAmount.textContent = newTotal.toLocaleString() + ' VND';
      document.getElementById('final_total_hidden').value = newTotal;
    }
  </script>

</body>
</html>
