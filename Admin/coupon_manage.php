<?php
include('../config/db.php');

$page = basename($_SERVER['PHP_SELF']);
$msg = '';

// Xóa mã giảm giá
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $conn->query("DELETE FROM coupons WHERE id = $id");
    header("Location: coupon_manage.php");
    exit;
}

// Thêm / Cập nhật mã giảm giá
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_inline'])) {
    $id = (int)($_POST['id'] ?? 0);
    $code = trim($_POST['code'] ?? '');
    $discount_percent = (int)($_POST['discount_percent'] ?? 0);
    $active = isset($_POST['active']) ? 1 : 0;
    $expiry_date = $_POST['expiry_date'] ?? '';

    if ($code === '' || $discount_percent <= 0 || !$expiry_date) {
        $msg = "Vui lòng nhập đầy đủ và hợp lệ thông tin mã giảm giá.";
    } else {
        if ($id > 0) {
            // Cập nhật
            $stmt = $conn->prepare("UPDATE coupons SET code=?, discount_percent=?, active=?, expiry_date=? WHERE id=?");
            $stmt->bind_param("siisi", $code, $discount_percent, $active, $expiry_date, $id);
            $stmt->execute();
            $stmt->close();
            $msg = "Cập nhật mã giảm giá thành công.";
        } else {
            // Thêm mới
            $stmt = $conn->prepare("INSERT INTO coupons (code, discount_percent, active, expiry_date) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("siss", $code, $discount_percent, $active, $expiry_date);
            $stmt->execute();
            $stmt->close();
            $msg = "Thêm mã giảm giá mới thành công.";
        }
    }
}

// Lấy danh sách mã giảm giá
$result = $conn->query("SELECT * FROM coupons ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8" />
  <title>Quản lý mã giảm giá</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
</head>
<body>

<?php include('includes/header.php'); ?>

<div class="d-flex">
  <?php include('includes/sidebar.php'); ?>

  <main class="content p-4" style="flex-grow: 1;">

    <h1>Quản lý mã giảm giá</h1>

    <?php if ($msg): ?>
      <div class="message"><?= htmlspecialchars($msg) ?></div>
    <?php endif; ?>

    <table class="table table-hover table-bordered table-sm">
      <thead>
        <tr>
          <th>ID</th>
          <th>Mã giảm giá</th>
          <th>Phần trăm giảm (%)</th>
          <th>Kích hoạt</th>
          <th>Ngày hết hạn</th>
          <th>Hành động</th>
        </tr>
      </thead>
      <tbody>
        <?php if ($result->num_rows === 0): ?>
          <tr><td colspan="6">Chưa có mã giảm giá nào</td></tr>
          <tr>
            <form method="post">
              <td>#</td>
              <td><input type="text" name="code" required></td>
              <td><input type="number" name="discount_percent" min="1" max="100" required></td>
              <td><input type="checkbox" name="active" checked></td>
              <td><input type="date" name="expiry_date" required></td>
              <td><button type="submit" name="edit_inline" class="btn btn-sm btn-success">Thêm</button></td>
            </form>
          </tr>
        <?php else: ?>
          <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
              <form method="post" class="mb-0">
                <input type="hidden" name="id" value="<?= $row['id'] ?>">
                <td><?= $row['id'] ?></td>
                <td><input type="text" name="code" value="<?= htmlspecialchars($row['code']) ?>" required></td>
                <td><input type="number" name="discount_percent" min="1" max="100" value="<?= $row['discount_percent'] ?>" required></td>
                <td><input type="checkbox" name="active" <?= $row['active'] ? 'checked' : '' ?>></td>
                <td><input type="date" name="expiry_date" value="<?= $row['expiry_date'] ?>" required></td>
                <td>
                  <button type="submit" name="edit_inline" class="btn btn-sm btn-primary" title="Lưu">
                    <i class="fas fa-save"></i>
                  </button>
                  <a href="?delete=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Bạn có chắc muốn xóa mã này?')" title="Xóa">
                    <i class="fas fa-trash"></i>
                  </a>
                </td>
              </form>
            </tr>
          <?php endwhile; ?>

          <!-- Dòng thêm mới -->
          <tr>
            <form method="post" class="mb-0">
              <td>#</td>
              <td><input type="text" name="code" required></td>
              <td><input type="number" name="discount_percent" min="1" max="100" required></td>
              <td><input type="checkbox" name="active" checked></td>
              <td><input type="date" name="expiry_date" required></td>
              <td><button type="submit" name="edit_inline" class="btn btn-sm btn-success">Thêm</button></td>
            </form>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>

  </main>
</div>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
</body>
</html>
