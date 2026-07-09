<?php
include('../config/db.php');
$page = basename($_SERVER['PHP_SELF']);

// Xoá đơn hàng
if (isset($_GET['delete'])) {
  $id = (int)$_GET['delete'];
  $conn->query("DELETE FROM `order` WHERE transaction_id = $id");
  $conn->query("DELETE FROM transaction WHERE id = $id");
  header("Location: orders.php");
  exit;
}

// Cập nhật trạng thái đơn hàng
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
  $id = (int)$_POST['id'];
  $status = (int)$_POST['status'];
  $conn->query("UPDATE transaction SET status = $status WHERE id = $id");
  header("Location: orders.php");
  exit;
}

// Lấy danh sách đơn hàng
$orders = $conn->query("SELECT t.*, IFNULL(u.name, t.user_name) AS customer_name FROM transaction t LEFT JOIN user u ON t.user_id = u.id ORDER BY t.id DESC");

// Map trạng thái
$statusMap = [
  0 => 'Chờ xác nhận',
  1 => 'Đã xác nhận',
  2 => 'Đang giao',
  3 => 'Đã giao',
  4 => 'Đã hủy'
];
?>

<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Quản lý đơn hàng</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    .order-details { display: none; }
  </style>
</head>
<body>
<?php include('includes/header.php'); ?>
<div class="d-flex">
  <?php include('includes/sidebar.php'); ?>

  <div class="p-4" style="flex-grow: 1;">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h4 class="mb-0">Quản lý đơn hàng</h4>
    </div>

    <table class="table table-bordered table-hover table-sm">
      <thead class="thead-light">
        <tr>
          <th>ID</th>
          <th>Khách hàng</th>
          <th>Email</th>
          <th>SĐT</th>
          <th>Địa chỉ</th>
          <th>Số tiền</th>
          <th>PT thanh toán</th>
          <th>Ngày tạo</th>
          <th>Trạng thái</th>
          <th>Thao tác</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($row = $orders->fetch_assoc()): ?>
        <tr>
          <form method="post">
            <input type="hidden" name="id" value="<?= $row['id'] ?>">
            <td><?= $row['id'] ?></td>
            <td><?= htmlspecialchars($row['customer_name']) ?></td>
            <td><?= $row['user_email'] ?></td>
            <td><?= $row['user_phone'] ?></td>
            <td><?= $row['user_address'] ?></td>
            <td><?= number_format($row['amount'], 2) ?> đ</td>
            <td><?= $row['payment'] ?? '---' ?></td>
            <td><?= $row['created'] ? date('d-m-Y H:i', $row['created']) : '---' ?></td>
            <td>
              <select name="status" class="form-control form-control-sm">
                <?php foreach ($statusMap as $code => $label): ?>
                  <option value="<?= $code ?>" <?= $row['status'] == $code ? 'selected' : '' ?>><?= $label ?></option>
                <?php endforeach; ?>
              </select>
            </td>
            <td>
              <button type="submit" name="update_status" class="btn btn-sm btn-success" title="Cập nhật trạng thái">
                <i class="fas fa-save"></i>
              </button>
              <button type="button" class="btn btn-sm btn-info toggle-details" data-id="<?= $row['id'] ?>" title="Xem chi tiết">
                <i class="fas fa-eye"></i>
              </button>
              <a href="?delete=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Xóa đơn hàng này?');">
                <i class="fas fa-trash"></i>
              </a>
            </td>
          </form>
        </tr>
        <tr id="details-<?= $row['id'] ?>" class="order-details">
          <td colspan="10">
            <strong>Sản phẩm trong đơn:</strong>
            <ul class="mb-0">
              <?php
              $orderItems = $conn->query("SELECT o.qty, p.name FROM `order` o JOIN product p ON o.product_id = p.id WHERE o.transaction_id = {$row['id']}");
              while ($item = $orderItems->fetch_assoc()): ?>
                <li><?= $item['name'] ?> - SL: <?= $item['qty'] ?></li>
              <?php endwhile; ?>
            </ul>
          </td>
        </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
<script>
  $(document).ready(function () {
    $('.toggle-details').click(function () {
      const id = $(this).data('id');
      $('#details-' + id).slideToggle();
    });
  });
</script>
</body>
</html>
