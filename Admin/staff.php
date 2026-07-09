<?php
include('../config/db.php');
$page = basename($_SERVER['PHP_SELF']);

// Thêm nhân viên
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_staff'])) {
  $name = $_POST['name'];
  $username = $_POST['username'];
  $password = md5($_POST['password']);
  $level = (int)$_POST['level'];

  $stmt = $conn->prepare("INSERT INTO admin (name, username, password, level, created) VALUES (?, ?, ?, ?, NOW())");
  $stmt->bind_param("sssi", $name, $username, $password, $level);
  $stmt->execute();
  header("Location: staff.php");
  exit;
}

// Xoá nhân viên
if (isset($_GET['delete'])) {
  $id = (int)$_GET['delete'];
  $conn->query("DELETE FROM admin WHERE id = $id");
  header("Location: staff.php");
  exit;
}

// Cập nhật thông tin nhân viên
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_inline'])) {
  $id = (int)$_POST['id'];
  $name = $_POST['name'];
  $username = $_POST['username'];
  $level = (int)$_POST['level'];

  $stmt = $conn->prepare("UPDATE admin SET name=?, username=?, level=? WHERE id=?");
  $stmt->bind_param("ssii", $name, $username, $level, $id);
  $stmt->execute();
  header("Location: staff.php");
  exit;
}

// Lấy danh sách nhân viên
$staff = $conn->query("SELECT * FROM admin ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Quản lý nhân viên</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
<?php include('includes/header.php'); ?>
<div class="d-flex">
  <?php include('includes/sidebar.php'); ?>

  <div class="p-4" style="flex-grow: 1;">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h4 class="mb-0">Quản lý nhân viên</h4>
      <button class="btn btn-primary" data-toggle="modal" data-target="#addModal">
        <i class="fas fa-plus"></i> Thêm nhân viên
      </button>
    </div>

    <table class="table table-bordered table-hover table-sm">
      <thead class="thead-light">
        <tr>
          <th>ID</th>
          <th>Họ tên</th>
          <th>Tài khoản</th>
          <th>Level</th>
          <th>Ngày tạo</th>
          <th>Thao tác</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($row = $staff->fetch_assoc()): ?>
        <tr>
          <form method="post">
            <input type="hidden" name="id" value="<?= $row['id'] ?>">
            <td><?= $row['id'] ?></td>
            <td><input type="text" name="name" value="<?= htmlspecialchars($row['name']) ?>" class="form-control form-control-sm"></td>
            <td><input type="text" name="username" value="<?= $row['username'] ?>" class="form-control form-control-sm"></td>
            <td>
              <select name="level" class="form-control form-control-sm">
                <option value="1" <?= $row['level'] == 1 ? 'selected' : '' ?>>Admin</option>
                <option value="2" <?= $row['level'] == 2 ? 'selected' : '' ?>>Nhân viên</option>
              </select>
            </td>
            <td><?= $row['created'] ?? '---' ?></td>
            <td>
              <button type="submit" name="edit_inline" class="btn btn-sm btn-success" title="Lưu">
                <i class="fas fa-save"></i>
              </button>
              <a href="?delete=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Xóa nhân viên này?');">
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

<!-- Modal thêm nhân viên -->
<div class="modal fade" id="addModal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <form method="post" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Thêm nhân viên mới</h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <label>Họ tên</label>
          <input type="text" class="form-control" name="name" required>
        </div>
        <div class="form-group">
          <label>Tên đăng nhập</label>
          <input type="text" class="form-control" name="username" required>
        </div>
        <div class="form-group">
          <label>Mật khẩu</label>
          <input type="password" class="form-control" name="password" required>
        </div>
        <div class="form-group">
          <label>Level</label>
          <select name="level" class="form-control">
            <option value="1">Admin</option>
            <option value="2">Nhân viên</option>
          </select>
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" name="add_staff" class="btn btn-primary">Thêm</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
      </div>
    </form>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
</body>
</html>