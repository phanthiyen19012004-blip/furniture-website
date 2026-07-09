<?php


include('../config/db.php');
$page = basename($_SERVER['PHP_SELF']);

$error = '';

// Thêm model mới
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add'])) {
    $name = trim($_POST['name']);
    $file = $_FILES['model_file'] ?? null;

    if ($name && $file && $file['error'] === 0) {
        $uploadDir = '../assets/images/models/';
        $fileName = basename($file['name']);
        $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $allowedTypes = ['glb', 'gltf'];

        if (!in_array($fileExt, $allowedTypes)) {
            $error = "Chỉ cho phép file định dạng .glb hoặc .gltf";
        } else {
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
            $targetFilePath = $uploadDir . $fileName;

            if (file_exists($targetFilePath)) {
                $fileName = pathinfo($fileName, PATHINFO_FILENAME) . '_' . time() . '.' . $fileExt;
                $targetFilePath = $uploadDir . $fileName;
            }

            if (move_uploaded_file($file['tmp_name'], $targetFilePath)) {
                $stmt = $conn->prepare("INSERT INTO models (name, file_path, created_at) VALUES (?, ?, NOW())");
                $stmt->bind_param("ss", $name, $fileName);
                $stmt->execute();
                $stmt->close();
                header("Location: admin_models.php");
                exit;
            } else {
                $error = "Lỗi khi tải file lên server.";
            }
        }
    } else {
        $error = "Vui lòng nhập tên model và chọn file hợp lệ.";
    }
}

// Sửa model (đổi tên + đổi file nếu có)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_inline'])) {
    $id = (int) $_POST['id'];
    $name = trim($_POST['name']);
    $file = $_FILES['model_file'] ?? null;

    // Lấy file cũ
    $stmt = $conn->prepare("SELECT file_path FROM models WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($old_file_path);
    $stmt->fetch();
    $stmt->close();

    $uploadDir = '../assets/images/models/';
    $fileNameToUpdate = $old_file_path;

    if ($file && $file['error'] == 0) {
        $fileExt = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $allowedTypes = ['glb', 'gltf'];

        if (!in_array($fileExt, $allowedTypes)) {
            $error = "Chỉ cho phép file .glb hoặc .gltf khi sửa file";
        } else {
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

            // Xóa file cũ
            $oldFile = $uploadDir . $old_file_path;
            if (file_exists($oldFile)) unlink($oldFile);

            // Upload file mới
            $fileName = basename($file['name']);
            $targetFilePath = $uploadDir . $fileName;

            if (file_exists($targetFilePath)) {
                $fileName = pathinfo($fileName, PATHINFO_FILENAME) . '_' . time() . '.' . $fileExt;
                $targetFilePath = $uploadDir . $fileName;
            }
            if (move_uploaded_file($file['tmp_name'], $targetFilePath)) {
                $fileNameToUpdate = $fileName;
            } else {
                $error = "Lỗi khi upload file mới.";
            }
        }
    }

    if (empty($error)) {
        $stmt = $conn->prepare("UPDATE models SET name = ?, file_path = ? WHERE id = ?");
        $stmt->bind_param("ssi", $name, $fileNameToUpdate, $id);
        $stmt->execute();
        $stmt->close();
        header("Location: admin_models.php");
        exit;
    }
}

// Xóa model
if (isset($_GET['delete'])) {
    $id = (int) $_GET['delete'];

    // Lấy file để xóa
    $stmt = $conn->prepare("SELECT file_path FROM models WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($file_path);
    $stmt->fetch();
    $stmt->close();

    if ($file_path) {
        $fileToDelete = '../assets/images/models/' . $file_path;
        if (file_exists($fileToDelete)) unlink($fileToDelete);
    }

    // Xóa bản ghi
    $stmt = $conn->prepare("DELETE FROM models WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();

    header("Location: admin_models.php");
    exit;
}

// Lấy danh sách models
$models = $conn->query("SELECT * FROM models ORDER BY id DESC");

?>

<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8" />
  <title>Quản lý Models 3D</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
</head>
<body>
<?php include('includes/header.php'); ?>
<div class="d-flex">
  <?php include('includes/sidebar.php'); ?>

  <div class="p-4" style="flex-grow: 1;">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h4 class="mb-0">Quản lý Models 3D</h4>
      <button class="btn btn-primary" data-toggle="modal" data-target="#addModal">
        <i class="fas fa-plus"></i> Thêm model
      </button>
    </div>

    <?php if (!empty($error)): ?>
      <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <table class="table table-bordered table-hover table-sm">
      <thead class="thead-light">
        <tr>
          <th>ID</th>
          <th>Tên model</th>
          <th>File</th>
          <th>Ngày tạo</th>
          <th>Thao tác</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($row = $models->fetch_assoc()): ?>
        <tr>
          <form method="post" enctype="multipart/form-data" style="margin:0;">
            <input type="hidden" name="id" value="<?= $row['id'] ?>">
            <td><?= $row['id'] ?></td>
            <td>
              <input type="text" name="name" value="<?= htmlspecialchars($row['name']) ?>" class="form-control form-control-sm" required>
            </td>
            <td>
              <a href="../assets/images/models/<?= htmlspecialchars($row['file_path']) ?>" target="_blank"><?= htmlspecialchars($row['file_path']) ?></a>
              <input type="file" name="model_file" accept=".glb,.gltf" class="form-control-file mt-1" style="font-size: 0.8rem;">
              <small class="text-muted">Chọn file để thay thế (không bắt buộc)</small>
            </td>
            <td><?= $row['created_at'] ?></td>
            <td>
              <button type="submit" name="edit_inline" class="btn btn-sm btn-success" title="Lưu thay đổi">
                <i class="fas fa-save"></i>
              </button>
              <a href="?delete=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Xóa model này?')">
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

<!-- Modal thêm model -->
<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form method="post" enctype="multipart/form-data" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addModalLabel">Thêm model mới</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Đóng">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <label>Tên model</label>
          <input type="text" name="name" class="form-control" required>
        </div>
        <div class="form-group">
          <label>File model (.glb, .gltf)</label>
          <input type="file" name="model_file" accept=".glb,.gltf" class="form-control-file" required>
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
