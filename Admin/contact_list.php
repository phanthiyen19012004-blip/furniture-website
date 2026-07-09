<?php
include('../config/db.php');
$page = basename($_SERVER['PHP_SELF']);

// Xử lý cập nhật số điện thoại, zalo, địa chỉ
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_contact_info'])) {
    $phone = $_POST['phone'] ?? '';
    $zalo = $_POST['zalo'] ?? '';
    $address = $_POST['address'] ?? '';

    $result = $conn->query("SELECT id FROM contact_info LIMIT 1");
    if ($result->num_rows > 0) {
        $stmt = $conn->prepare("UPDATE contact_info SET phone=?, zalo=?, address=?, updated_at=NOW() WHERE id = (SELECT id FROM (SELECT id FROM contact_info LIMIT 1) AS tmp)");
        $stmt->bind_param("sss", $phone, $zalo, $address);
        $stmt->execute();
        $stmt->close();
    } else {
        $stmt = $conn->prepare("INSERT INTO contact_info (phone, zalo, address) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $phone, $zalo, $address);
        $stmt->execute();
        $stmt->close();
    }

    header("Location: contact_list.php");
    exit;
}

// Xử lý xóa liên hệ
if (isset($_GET['delete'])) {
  $id = (int) $_GET['delete'];
  $conn->query("DELETE FROM contact_us WHERE id = $id");
  header("Location: contact_list.php");
  exit;
}

// Lấy thông tin contact_info để hiển thị chỉnh sửa
$contactInfo = $conn->query("SELECT * FROM contact_info LIMIT 1")->fetch_assoc();

// Lấy danh sách liên hệ
$contacts = $conn->query("SELECT * FROM contact_us ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8" />
  <title>Quản lý liên hệ khách gửi & chỉnh sửa thông tin liên hệ</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
</head>
<body>
<?php include('includes/header.php'); ?>
<div class="d-flex">
  <?php include('includes/sidebar.php'); ?>

  <div class="p-4" style="flex-grow: 1;">

    <h4 class="mb-3">Chỉnh sửa số điện thoại, Zalo và địa chỉ hiển thị</h4>
    <form method="post" class="mb-4">
      <input type="hidden" name="update_contact_info" value="1" />
      <div class="form-row">
        <div class="form-group col-md-4">
          <label>Số điện thoại</label>
          <input type="text" name="phone" class="form-control" required
                 value="<?= htmlspecialchars($contactInfo['phone'] ?? '') ?>">
        </div>
        <div class="form-group col-md-4">
          <label>Số Zalo</label>
          <input type="text" name="zalo" class="form-control"
                 value="<?= htmlspecialchars($contactInfo['zalo'] ?? '') ?>">
        </div>
        <div class="form-group col-md-4">
          <label>Địa chỉ</label>
          <input type="text" name="address" class="form-control" required
                 value="<?= htmlspecialchars($contactInfo['address'] ?? '') ?>">
        </div>
      </div>
      <button type="submit" class="btn btn-primary">Cập nhật thông tin</button>
    </form>

    <h4 class="mb-3">Danh sách liên hệ khách gửi</h4>
    <table class="table table-bordered table-hover table-sm">
      <thead class="thead-light">
        <tr>
          <th>STT</th>
          <th>Tên</th>
          <th>Email</th>
          <th>Số điện thoại</th>
          <th>Lời nhắn</th>
          <th>Thời gian gửi</th>
          <th>Thao tác</th>
        </tr>
      </thead>
      <tbody>
        <?php if ($contacts && $contacts->num_rows > 0): 
          $i = 1;
          while ($row = $contacts->fetch_assoc()):
        ?>
          <tr>
            <td><?= $i++ ?></td>
            <td><?= htmlspecialchars($row['name']) ?></td>
            <td><?= htmlspecialchars($row['email']) ?></td>
            <td><?= htmlspecialchars($row['phone']) ?></td>
            <td><?= nl2br(htmlspecialchars($row['message'])) ?></td>
            <td><?= $row['created_at'] ?></td>
            <td>
              <a href="?delete=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Xóa liên hệ này?');">
                <i class="fas fa-trash"></i> Xóa
              </a>
            </td>
          </tr>
        <?php endwhile; else: ?>
          <tr><td colspan="7" class="text-center">Chưa có liên hệ nào.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
</body>
</html>
