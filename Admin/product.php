<?php
include('../config/db.php');
$page = basename($_SERVER['PHP_SELF']);
$keyword = isset($_GET['keyword']) ? $conn->real_escape_string($_GET['keyword']) : '';

$products = $conn->query("
SELECT p.*, c.name AS catalog_name 
FROM product p 
LEFT JOIN catalog c ON p.catalog_id = c.id 
WHERE p.name LIKE '%$keyword%' 
ORDER BY p.id DESC
");

// Thêm sản phẩm
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add'])) {
    $name = $_POST['name'];
    $catalog_id = (int)$_POST['catalog_id'];
    $price = (int)$_POST['price'];
    $discount = (int)$_POST['discount'];
    $content = $_POST['content'];

    $uploadedImages = [];
    if (!empty($_FILES['images']['name'][0])) {
        foreach ($_FILES['images']['name'] as $key => $filename) {
            $tmpName = $_FILES['images']['tmp_name'][$key];
            $filename = preg_replace('/[^a-zA-Z0-9_\.-]/', '_', $filename);
            $targetPath = '../assets/images/Product/' . $filename;
            if (move_uploaded_file($tmpName, $targetPath)) {
                $uploadedImages[] = $filename;
            }
        }
    }
    $image_list_str = implode(',', $uploadedImages);

    $stmt = $conn->prepare("INSERT INTO product (name, catalog_id, price, discount, content, image_list, created) VALUES (?, ?, ?, ?, ?, ?, NOW())");
    $stmt->bind_param("siiiss", $name, $catalog_id, $price, $discount, $content, $image_list_str);
    $stmt->execute();
    header("Location: product.php");
    exit;
}

// Sửa sản phẩm
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_inline'])) {
    $id = (int)$_POST['id'];
    $name = $_POST['name'];
    $catalog_id = (int)$_POST['catalog_id'];
    $price = (int)$_POST['price'];
    $discount = (int)$_POST['discount'];
    $content = $_POST['content'];

    // Lấy ảnh cũ
    $stmtGet = $conn->prepare("SELECT image_list FROM product WHERE id=?");
    $stmtGet->bind_param("i", $id);
    $stmtGet->execute();
    $resultGet = $stmtGet->get_result();
    $oldImages = [];
    if ($rowGet = $resultGet->fetch_assoc()) {
        if (!empty($rowGet['image_list'])) {
            $oldImages = explode(',', $rowGet['image_list']);
        }
    }

    // Upload thêm ảnh mới nếu có
    $uploadedImages = [];
    if (!empty($_FILES['images']['name'][0])) {
        foreach ($_FILES['images']['name'] as $key => $filename) {
            $tmpName = $_FILES['images']['tmp_name'][$key];
            $filename = preg_replace('/[^a-zA-Z0-9_\.-]/', '_', $filename);
            $targetPath = '../assets/images/Product/' . $filename;
            if (move_uploaded_file($tmpName, $targetPath)) {
                $uploadedImages[] = $filename;
            }
        }
    }
    // Gộp ảnh cũ và mới
    $finalImages = array_merge($oldImages, $uploadedImages);
    $image_list_str = implode(',', $finalImages);

    $stmt = $conn->prepare("UPDATE product SET name=?, catalog_id=?, price=?, discount=?, content=?, image_list=? WHERE id=?");
    $stmt->bind_param("siiissi", $name, $catalog_id, $price, $discount, $content, $image_list_str, $id);
    $stmt->execute();

    header("Location: product.php");
    exit;
}

// Xoá sản phẩm
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $conn->query("DELETE FROM product WHERE id = $id");
    header("Location: product.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8" />
  <title>Quản lý sản phẩm</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <style>
    .image-list-preview img {
      margin-right: 5px;
      border: 1px solid #ccc;
      border-radius: 4px;
      height: 60px;
      display: inline-block;
      position: relative;
    }
    .btn-remove-image {
      position: absolute;
      top: -8px;
      right: -8px;
      border-radius: 50%;
      padding: 0 6px;
      font-weight: bold;
      font-size: 14px;
      line-height: 1;
      cursor: pointer;
      border: none;
      color: white;
      background-color: red;
    }
    .image-wrapper {
      display: inline-block;
      position: relative;
      margin-right: 5px;
    }
  </style>
</head>
<body>
<?php include('includes/header.php'); ?>
<div class="d-flex">
<?php include('includes/sidebar.php'); ?>

<div class="p-4" style="flex-grow: 1;">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Quản lý sản phẩm</h4>
    <div class="d-flex align-items-center">
      <form method="get" class="form-inline mr-2">
        <input type="text" name="keyword" class="form-control form-control-sm" placeholder="Tìm sản phẩm..." value="<?= htmlspecialchars($keyword) ?>">
        <button type="submit" class="btn btn-sm btn-outline-light ml-1">
          <i class="fas fa-search"></i>
        </button>
      </form>
      <button class="btn btn-primary" data-toggle="modal" data-target="#addModal">
        <i class="fas fa-plus"></i> Thêm sản phẩm
      </button>
    </div>
  </div>

  <table class="table table-bordered table-hover table-sm">
    <thead class="thead-light">
      <tr>
        <th>ID</th>
        <th>Tên</th>
        <th>Danh mục</th>
        <th>Giá gốc</th>
        <th>Giá đã giảm</th>
        <th>Mô tả</th>
        <th>Ảnh</th>
        <th>Ngày tạo</th>
        <th>Thao tác</th>
      </tr>
    </thead>
<tbody>
  <?php while ($row = $products->fetch_assoc()): ?>
    <tr>
      <form method="post" enctype="multipart/form-data" class="edit-product-form" data-id="<?= $row['id'] ?>">
        <input type="hidden" name="id" value="<?= $row['id'] ?>">
        <td><?= $row['id'] ?></td>
        <td><input type="text" name="name" value="<?= htmlspecialchars($row['name']) ?>" class="form-control form-control-sm"></td>
        <td>
          <select name="catalog_id" class="form-control form-control-sm">
            <?php
            $cats = $conn->query("SELECT * FROM catalog");
            while ($cat = $cats->fetch_assoc()): ?>
              <option value="<?= $cat['id'] ?>" <?= $row['catalog_id'] == $cat['id'] ? 'selected' : '' ?>>
                <?= htmlspecialchars($cat['name']) ?>
              </option>
            <?php endwhile; ?>
          </select>
        </td>
        <td>
          <input type="number" name="price" value="<?= $row['price'] ?>" class="form-control form-control-sm">
          <small class="text-muted"><?= number_format($row['price'], 0, ',', '.') ?> ₫</small>
        </td>
        <td>
          <input type="number" name="discount" value="<?= $row['discount'] ?>" class="form-control form-control-sm">
          <small class="text-muted"><?= number_format($row['discount'], 0, ',', '.') ?> ₫</small>
        </td>
        <td><textarea name="content" class="form-control form-control-sm"><?= htmlspecialchars($row['content']) ?></textarea></td>
        <td>
          <?php
          $currentImages = [];
          if (!empty($row['image_list'])) {
            $currentImages = explode(',', $row['image_list']);
          }
          ?>
          <div class="image-list-preview" style="max-height: 100px; overflow-x:auto; white-space: nowrap; margin-bottom: 5px;">
            <?php foreach ($currentImages as $img): ?>
              <div class="image-wrapper">
                <img src="../assets/images/Product/<?= htmlspecialchars($img) ?>" alt="Ảnh sản phẩm" />
                <button type="button" class="btn-remove-image" data-filename="<?= htmlspecialchars($img) ?>" data-product-id="<?= $row['id'] ?>" title="Xóa ảnh này">×</button>
              </div>
            <?php endforeach; ?>
          </div>
          <input type="file" name="images[]" multiple class="form-control-file" />
          <small class="text-muted">Chọn ảnh mới để thêm vào bộ ảnh</small>
        </td>
        <td><?= $row['created'] ?></td>
        <td>
          <button type="submit" name="edit_inline" class="btn btn-sm btn-success">
            <i class="fas fa-save"></i>
          </button>
          <a href="?delete=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Xoá sản phẩm này?');">
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

<!-- Modal thêm sản phẩm -->
<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form method="post" enctype="multipart/form-data" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addModalLabel">Thêm sản phẩm</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Đóng">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <label>Tên sản phẩm</label>
          <input type="text" class="form-control" name="name" required />
        </div>
        <div class="form-group">
          <label>Danh mục</label>
          <select name="catalog_id" class="form-control" required>
            <?php
            $cats = $conn->query("SELECT * FROM catalog");
            while ($cat = $cats->fetch_assoc()): ?>
              <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
            <?php endwhile; ?>
          </select>
        </div>
        <div class="form-group">
          <label>Giá gốc</label>
          <input type="number" class="form-control" name="price" required />
        </div>
        <div class="form-group">
          <label>Giá đã giảm</label>
          <input type="number" class="form-control" name="discount" required />
        </div>
        <div class="form-group">
          <label>Mô tả sản phẩm</label>
          <textarea class="form-control" name="content" rows="3" required></textarea>
        </div>
        <div class="form-group">
          <label>Ảnh sản phẩm</label>
          <input type="file" class="form-control-file" name="images[]" multiple required />
          <small class="form-text text-muted">Bạn có thể chọn nhiều ảnh cùng lúc.</small>
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

<script>
// Xử lý nút xóa ảnh riêng biệt
$(document).on('click', '.btn-remove-image', function() {
  if (!confirm('Bạn có chắc muốn xóa ảnh này không?')) return;

  let btn = $(this);
  let filename = btn.data('filename');
  let productId = btn.data('product-id');

  $.ajax({
    url: 'remove_image.php',
    method: 'POST',
    data: {
      product_id: productId,
      filename: filename
    },
    dataType: 'json',
    success: function(res) {
      if (res.success) {
        btn.parent('.image-wrapper').remove();
      } else {
        alert('Xóa ảnh thất bại. Vui lòng thử lại.');
      }
    },
    error: function() {
      alert('Lỗi kết nối tới máy chủ.');
    }
  });
});
</script>
</body>
</html>
