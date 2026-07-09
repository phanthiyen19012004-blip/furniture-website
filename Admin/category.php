<?php
include('../config/db.php');
$page = basename($_SERVER['PHP_SELF']);

// Thêm danh mục
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add'])) {
  $name = $_POST['name'];
  $desc = $_POST['description'];
  $stmt = $conn->prepare("INSERT INTO catalog (name, description, created) VALUES (?, ?, NOW())");
  $stmt->bind_param("ss", $name, $desc);
  $stmt->execute();
  header("Location: category.php");
  exit;
}

// Sửa danh mục trực tiếp
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_inline'])) {
  $id = (int) $_POST['id'];
  $name = $_POST['name'];
  $desc = $_POST['description'];
  $stmt = $conn->prepare("UPDATE catalog SET name=?, description=? WHERE id=?");
  $stmt->bind_param("ssi", $name, $desc, $id);
  $stmt->execute();
  header("Location: category.php");
  exit;
}

// Xoá danh mục
if (isset($_GET['delete'])) {
  $id = (int) $_GET['delete'];
  $conn->query("DELETE FROM catalog WHERE id = $id");
  header("Location: category.php");
  exit;
}

$catalogs = $conn->query("SELECT * FROM catalog ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Danh mục sản phẩm</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
<?php include('includes/header.php'); ?>
<div class="d-flex">
  <?php include('includes/sidebar.php'); ?>

  <div class="p-4" style="flex-grow: 1;">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h4 class="mb-0">Quản lý Danh mục</h4>
      <button class="btn btn-primary" data-toggle="modal" data-target="#addModal">
        <i class="fas fa-plus"></i> Thêm danh mục
      </button>
    </div>

    <table class="table table-bordered table-hover table-sm">
      <thead class="thead-light">
        <tr>
          <th>ID</th>
          <th>Tên</th>
          <th>Mô tả</th>
          <th>Ngày tạo</th>
          <th>Thao tác</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($row = $catalogs->fetch_assoc()): ?>
        <tr>
          <form method="post">
            <input type="hidden" name="id" value="<?= $row['id'] ?>">
            <td><?= $row['id'] ?></td>
            <td><input type="text" name="name" value="<?= htmlspecialchars($row['name']) ?>" class="form-control form-control-sm"></td>
            <td><input type="text" name="description" value="<?= htmlspecialchars($row['description']) ?>" class="form-control form-control-sm"></td>
            <td><?= $row['created'] ?></td>
            <td>
              <button type="submit" name="edit_inline" class="btn btn-sm btn-success">
                <i class="fas fa-save"></i>
              </button>
              <a href="?delete=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Xoá danh mục này?');">
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

<!-- Modal thêm danh mục -->
<div class="modal fade" id="addModal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <form method="post" action="category.php" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Thêm danh mục</h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <label>Tên danh mục</label>
          <input type="text" class="form-control" name="name" required>
        </div>
        <div class="form-group">
          <label>Mô tả</label>
          <input type="text" class="form-control" name="description">
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" name="add" class="btn btn-primary">Thêm mới</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
      </div>
    </form>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
</body>
</html>
