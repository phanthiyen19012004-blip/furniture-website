<?php
include('../config/db.php');
$page = basename($_SERVER['PHP_SELF']);

if (isset($_GET['delete'])) {
  $id = (int)$_GET['delete'];

  // Xoá tất cả comment liên quan trước
  $conn->query("DELETE FROM comment WHERE user_id = $id");

  // Sau đó mới xoá user
  $conn->query("DELETE FROM user WHERE id = $id");

  header("Location: customers.php");
  exit;
}

// Cập nhật thông tin khách hàng
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_inline'])) {
  $id = (int)$_POST['id'];
  $name = $_POST['name'];
  $email = $_POST['email'];
  $phone = $_POST['phone'];
  $address = $_POST['address'];

  $stmt = $conn->prepare("UPDATE user SET name=?, email=?, phone=?, address=? WHERE id=?");
  $stmt->bind_param("ssssi", $name, $email, $phone, $address, $id);
  $stmt->execute();

  header("Location: customers.php");
  exit;
}

$customers = $conn->query("SELECT * FROM user ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Quản lý khách hàng</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
<?php include('includes/header.php'); ?>
<div class="d-flex">
  <?php include('includes/sidebar.php'); ?>

  <div class="p-4" style="flex-grow: 1;">
    <h4 class="mb-4">Quản lý khách hàng</h4>

    <table class="table table-bordered table-hover table-sm">
      <thead class="thead-light">
        <tr>
          <th>ID</th>
          <th>Họ tên</th>
          <th>Email</th>
          <th>SĐT</th>
          <th>Địa chỉ</th>
          <th>Ngày tạo</th>
          <th>Thao tác</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($row = $customers->fetch_assoc()): ?>
        <tr>
          <form method="post">
            <input type="hidden" name="id" value="<?= $row['id'] ?>">
            <td><?= $row['id'] ?></td>
            <td><input type="text" name="name" value="<?= htmlspecialchars($row['name']) ?>" class="form-control form-control-sm"></td>
            <td><input type="email" name="email" value="<?= $row['email'] ?>" class="form-control form-control-sm"></td>
            <td><input type="text" name="phone" value="<?= $row['phone'] ?>" class="form-control form-control-sm"></td>
            <td><input type="text" name="address" value="<?= $row['address'] ?>" class="form-control form-control-sm"></td>
            <td><?= $row['created'] ?? '---' ?></td>
            <td>
              <button type="submit" name="edit_inline" class="btn btn-sm btn-success" title="Lưu">
                <i class="fas fa-save"></i>
              </button>
              <a href="?delete=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Xóa khách hàng này?');">
                <i class="fas fa-trash"></i>
              </a>
            </td>
          </form>
        </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
</body>
</html>
