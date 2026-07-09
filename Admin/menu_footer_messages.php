<?php
include('../config/db.php');
$page = basename($_SERVER['PHP_SELF']);

// ====== TOP MENU ======
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_top'])) {
  $name = $_POST['name'];
  $link = $_POST['link'];
  $sort = (int)$_POST['sort_order'];
  $stmt = $conn->prepare("INSERT INTO top_menu (name, link, sort_order) VALUES (?, ?, ?)");
  $stmt->bind_param("ssi", $name, $link, $sort);
  $stmt->execute();
  header("Location: $page");
  exit;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_top'])) {
  $id = (int)$_POST['id'];
  $name = $_POST['name'];
  $link = $_POST['link'];
  $sort = (int)$_POST['sort_order'];
  $stmt = $conn->prepare("UPDATE top_menu SET name=?, link=?, sort_order=? WHERE id=?");
  $stmt->bind_param("ssii", $name, $link, $sort, $id);
  $stmt->execute();
  header("Location: $page");
  exit;
}
if (isset($_GET['delete_top'])) {
  $id = (int)$_GET['delete_top'];
  $conn->query("DELETE FROM top_menu WHERE id = $id");
  header("Location: $page");
  exit;
}
$topMenus = $conn->query("SELECT * FROM top_menu ORDER BY sort_order");

// ====== FOOTER LINK ======
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_footer'])) {
  $name = $_POST['name'];
  $link = $_POST['link'];
  $column_id = (int)$_POST['column_id'];
  $stmt = $conn->prepare("INSERT INTO footer_link (name, link, column_id) VALUES (?, ?, ?)");
  $stmt->bind_param("ssi", $name, $link, $column_id);
  $stmt->execute();
  header("Location: $page");
  exit;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_footer'])) {
  $id = (int)$_POST['id'];
  $name = $_POST['name'];
  $link = $_POST['link'];
  $stmt = $conn->prepare("UPDATE footer_link SET name=?, link=? WHERE id=?");
  $stmt->bind_param("ssi", $name, $link, $id);
  $stmt->execute();
  header("Location: $page");
  exit;
}
if (isset($_GET['delete_footer'])) {
  $id = (int)$_GET['delete_footer'];
  $conn->query("DELETE FROM footer_link WHERE id = $id");
  header("Location: $page");
  exit;
}
$footerColumns = $conn->query("SELECT * FROM footer_column ORDER BY sort_order");

// ====== MESSAGES ======
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_message'])) {
  $id = (int)$_POST['id'];
  $content = trim($_POST['content']);
  $stmt = $conn->prepare("UPDATE messages SET content=? WHERE id=?");
  $stmt->bind_param("si", $content, $id);
  $stmt->execute();
  header("Location: $page");
  exit;
}
$messages = mysqli_query($conn, "SELECT * FROM messages");
?>

<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Quản lý Menu & Footer</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    body { background-color: #f8f9fa; }
    .layout-wrapper { display: flex; }
    .sidebar-wrapper {
      width: 220px;
      background: #fff;
      min-height: 100vh;
      border-right: 1px solid #ddd;
      box-shadow: 2px 0 5px rgba(0,0,0,0.05);
    }
    .main-content {
      flex-grow: 1;
      padding: 5px 5px 30px 30px;
    }

    .section-box {
      background: #fff;
      padding: 20px;
      border-radius: 6px;
      box-shadow: 0 0 10px rgba(0,0,0,0.05);
      margin-bottom: 30px;
    }
    input[name="content"] {
      min-width: 400px;
    }
  </style>
</head>
<body>

<?php include('includes/header.php'); ?>

<div class="layout-wrapper">
  <div class="sidebar-wrapper"><?php include('includes/sidebar.php'); ?></div>

  <div class="main-content">

    <!-- ===== TOP MENU ===== -->
    <div class="section-box">
      <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">Quản lý Top Menu</h4>
        <button class="btn btn-primary" data-toggle="modal" data-target="#addTopModal">
          <i class="fas fa-plus"></i> Thêm Top Menu
        </button>
      </div>
      <table class="table table-bordered table-hover table-sm">
        <thead class="thead-light">
          <tr><th>ID</th><th>Tên</th><th>Link</th><th>Thứ tự</th><th>Thao tác</th></tr>
        </thead>
        <tbody>
          <?php while ($row = $topMenus->fetch_assoc()): ?>
          <tr>
            <form method="post">
              <input type="hidden" name="id" value="<?= $row['id'] ?>">
              <td><?= $row['id'] ?></td>
              <td><input type="text" name="name" value="<?= htmlspecialchars($row['name']) ?>" class="form-control form-control-sm"></td>
              <td><input type="text" name="link" value="<?= htmlspecialchars($row['link']) ?>" class="form-control form-control-sm"></td>
              <td><input type="number" name="sort_order" value="<?= $row['sort_order'] ?>" class="form-control form-control-sm" style="width: 80px;"></td>
              <td>
                <button type="submit" name="edit_top" class="btn btn-sm btn-success"><i class="fas fa-save"></i></button>
                <a href="?delete_top=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Xoá menu này?');"><i class="fas fa-trash"></i></a>
              </td>
            </form>
          </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>

    <!-- ===== FOOTER LINK ===== -->
    <div class="section-box">
      <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">Quản lý Footer Link</h4>
        <button class="btn btn-primary" data-toggle="modal" data-target="#addFooterModal"><i class="fas fa-plus"></i> Thêm Footer Link</button>
      </div>
      <table class="table table-bordered table-hover table-sm">
        <thead class="thead-light"><tr><th>Cột</th><th>Tên Link</th><th>Link</th><th>Thao tác</th></tr></thead>
        <tbody>
          <?php
          $footerLinks = mysqli_query($conn, "
            SELECT fl.id, fc.title, fl.name, fl.link
            FROM footer_link fl
            JOIN footer_column fc ON fl.column_id = fc.id
            ORDER BY fc.sort_order, fl.id
          ");
          while($row = mysqli_fetch_assoc($footerLinks)): ?>
          <tr>
            <form method="post">
              <input type="hidden" name="id" value="<?= $row['id'] ?>">
              <td><?= htmlspecialchars($row['title']) ?></td>
              <td><input type="text" name="name" value="<?= htmlspecialchars($row['name']) ?>" class="form-control form-control-sm"></td>
              <td><input type="text" name="link" value="<?= htmlspecialchars($row['link']) ?>" class="form-control form-control-sm"></td>
              <td>
                <button type="submit" name="edit_footer" class="btn btn-sm btn-success"><i class="fas fa-save"></i></button>
                <a href="?delete_footer=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Xoá link này?');"><i class="fas fa-trash"></i></a>
              </td>
            </form>
          </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>

    <!-- ===== MESSAGES ===== -->
    <div class="section-box">
      <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">Quản lý Messages</h4>
      </div>
      <table class="table table-bordered table-hover table-sm">
        <thead class="thead-light"><tr><th>ID</th><th>Nội dung</th></tr></thead>
        <tbody>
          <?php while($row = mysqli_fetch_assoc($messages)): ?>
          <tr>
            <form method="post">
              <input type="hidden" name="id" value="<?= $row['id'] ?>">
              <td><?= $row['id'] ?></td>
              <td>
                <div class="input-group">
                  <input type="text" name="content" value="<?= htmlspecialchars($row['content']) ?>" class="form-control form-control-sm">
                  <div class="input-group-append">
                    <button type="submit" name="edit_message" class="btn btn-sm btn-success"><i class="fas fa-save"></i></button>
                  </div>
                </div>
              </td>
            </form>
          </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>

  </div>
</div>

<!-- Modal thêm Top Menu -->
<div class="modal fade" id="addTopModal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <form method="post" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Thêm Top Menu</h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <div class="form-group"><label>Tên menu</label><input type="text" class="form-control" name="name" required></div>
        <div class="form-group"><label>Link</label><input type="text" class="form-control" name="link" required></div>
        <div class="form-group"><label>Thứ tự</label><input type="number" class="form-control" name="sort_order" value="1" required></div>
      </div>
      <div class="modal-footer">
        <button type="submit" name="add_top" class="btn btn-primary">Thêm mới</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
      </div>
    </form>
  </div>
</div>

<!-- Modal thêm Footer Link -->
<div class="modal fade" id="addFooterModal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <form method="post" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Thêm Footer Link</h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <div class="form-group"><label>Tên Link</label><input type="text" class="form-control" name="name" required></div>
        <div class="form-group"><label>URL</label><input type="text" class="form-control" name="link" required></div>
        <div class="form-group">
          <label>Thuộc cột</label>
          <select name="column_id" class="form-control" required>
            <option value="">-- Chọn cột --</option>
            <?php mysqli_data_seek($footerColumns, 0); while($col = $footerColumns->fetch_assoc()): ?>
              <option value="<?= $col['id'] ?>"><?= htmlspecialchars($col['title']) ?></option>
            <?php endwhile; ?>
          </select>
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" name="add_footer" class="btn btn-primary">Thêm mới</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
      </div>
    </form>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
</body>
</html>
