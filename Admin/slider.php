<?php
include('../config/db.php');
$page = basename($_SERVER['PHP_SELF']);

// Thêm slider
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add'])) {
  $name = $_POST['name'];
  $sort_order = (int) $_POST['sort_order'];
  $image = $_FILES['image']['name'];
  $path = '../assets/images/Slider/' . $image;
  move_uploaded_file($_FILES['image']['tmp_name'], $path);

  $stmt = $conn->prepare("INSERT INTO slider (name, image_link, sort_order, created) VALUES (?, ?, ?, NOW())");
  $stmt->bind_param("ssi", $name, $image, $sort_order);
  $stmt->execute();
  header("Location: slider.php");
  exit;
}

// Sửa slider trực tiếp
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_inline'])) {
  $id = (int) $_POST['id'];
  $name = $_POST['name'];
  $sort_order = (int) $_POST['sort_order'];

  if (!empty($_FILES['image']['name'])) {
    $image = $_FILES['image']['name'];
    $path = '../assets/images/Slider/' . $image;
    move_uploaded_file($_FILES['image']['tmp_name'], $path);
    $stmt = $conn->prepare("UPDATE slider SET name=?, image_link=?, sort_order=? WHERE id=?");
    $stmt->bind_param("ssii", $name, $image, $sort_order, $id);
  } else {
    $stmt = $conn->prepare("UPDATE slider SET name=?, sort_order=? WHERE id=?");
    $stmt->bind_param("sii", $name, $sort_order, $id);
  }
  $stmt->execute();
  header("Location: slider.php");
  exit;
}

// Xoá slider
if (isset($_GET['delete'])) {
  $id = (int) $_GET['delete'];
  $conn->query("DELETE FROM slider WHERE id = $id");
  header("Location: slider.php");
  exit;
}

$sliders = $conn->query("SELECT * FROM slider ORDER BY sort_order ASC, id DESC");
?>

<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Slider</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
<?php include('includes/header.php'); ?>
<div class="d-flex">
  <?php include('includes/sidebar.php'); ?>

  <div class="p-4" style="flex-grow: 1;">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h4 class="mb-0">Quản lý Slider</h4>
      <button class="btn btn-primary" data-toggle="modal" data-target="#addModal">
        <i class="fas fa-plus"></i> Thêm slider
      </button>
    </div>

    <table class="table table-bordered table-hover table-sm">
      <thead class="thead-light">
        <tr>
          <th>ID</th>
          <th>Tên</th>
          <th>Ảnh</th>
          <th>Thứ tự</th>
          <th>Ngày tạo</th>
          <th>Thao tác</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($row = $sliders->fetch_assoc()): ?>
        <tr>
          <form method="post" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?= $row['id'] ?>">
            <td><?= $row['id'] ?></td>
            <td>
              <input type="text" name="name" value="<?= htmlspecialchars($row['name']) ?>" class="form-control form-control-sm">
            </td>
            <td>
              <img src="../assets/images/Slider/<?= $row['image_link'] ?>" alt="" height="40"><br>
              <input type="file" name="image" class="form-control-file mt-1">
            </td>
            <td>
              <input type="number" name="sort_order" value="<?= $row['sort_order'] ?>" class="form-control form-control-sm">
            </td>
            <td><?= $row['created'] ?></td>
            <td>
              <button type="submit" name="edit_inline" class="btn btn-sm btn-success">
                <i class="fas fa-save"></i>
              </button>
              <a href="?delete=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Xoá slider này?');">
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

<!-- Modal thêm slider -->
<div class="modal fade" id="addModal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <form method="post" action="slider.php" enctype="multipart/form-data" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Thêm slider</h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <label>Tên slider</label>
          <input type="text" class="form-control" name="name" required>
        </div>
        <div class="form-group">
          <label>Chọn ảnh</label>
          <input type="file" class="form-control-file" name="image" required>
        </div>
        <div class="form-group">
          <label>Thứ tự hiển thị</label>
          <input type="number" class="form-control" name="sort_order" value="0">
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