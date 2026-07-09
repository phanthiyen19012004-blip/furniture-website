<?php
include('../config/db.php');
error_reporting(E_ALL); ini_set('display_errors', 1);
$page = basename($_SERVER['PHP_SELF']);

// Xoá phương thức giao hàng
if (isset($_GET['delete'])) {
  $id = (int)$_GET['delete'];
  $conn->query("DELETE FROM shipping WHERE id = $id");
  header("Location: shipping.php");
  exit;
}

// Thêm phương thức giao hàng
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_shipping'])) {
  $name = $_POST['name'];
  $province = $_POST['province'];
  $district = $_POST['district'];
  $id_province = (int)$_POST['id_province'];
  $id_district = (int)$_POST['id_district'];

  $stmt = $conn->prepare("INSERT INTO shipping (name, id_province, id_district, name_province, name_district, created) VALUES (?, ?, ?, ?, ?, NOW())");
  $stmt->bind_param("siiss", $name, $id_province, $id_district, $province, $district);
  if (!$stmt->execute()) {
    echo "Lỗi khi thêm dữ liệu: " . $stmt->error;
  }
  header("Location: shipping.php");
  exit;
}

// Danh sách phương thức giao hàng
$shippingList = $conn->query("SELECT * FROM shipping ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Quản lý vận chuyển</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
<?php include('includes/header.php'); ?>
<div class="d-flex">
  <?php include('includes/sidebar.php'); ?>
  <div class="p-4" style="flex-grow: 1;">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h4 class="mb-0">Quản lý vận chuyển</h4>
      <button class="btn btn-primary" data-toggle="modal" data-target="#addModal">
        <i class="fas fa-plus"></i> Thêm đơn vị vận chuyển
      </button>
    </div>

    <table class="table table-bordered table-hover table-sm">
      <thead class="thead-light">
        <tr>
          <th>ID</th>
          <th>Tên đơn vị</th>
          <th>Tỉnh/Thành</th>
          <th>Quận/Huyện</th>
          <th>Ngày tạo</th>
          <th>Thao tác</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($row = $shippingList->fetch_assoc()): ?>
        <tr>
          <td><?= $row['id'] ?></td>
          <td><?= htmlspecialchars($row['name']) ?></td>
          <td><?= $row['name_province'] ?></td>
          <td><?= $row['name_district'] ?></td>
          <td><?= $row['created'] ?? '---' ?></td>
          <td>
            <a href="?delete=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Xóa đơn vị này?');">
              <i class="fas fa-trash"></i>
            </a>
          </td>
        </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- Modal thêm -->
<div class="modal fade" id="addModal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <form method="post" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Thêm đơn vị vận chuyển</h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <label>Tên đơn vị</label>
          <input type="text" class="form-control" name="name" required>
        </div>
        <div class="form-group">
          <label>Tỉnh/Thành</label>
          <select id="province" name="province" class="form-control" required>
            <option value="">-- Chọn Tỉnh/Thành phố --</option>
          </select>
          <input type="hidden" name="id_province" id="id_province">
        </div>
        <div class="form-group">
          <label>Quận/Huyện</label>
          <select id="district" name="district" class="form-control" required disabled>
            <option value="">-- Chọn Quận/Huyện --</option>
          </select>
          <input type="hidden" name="id_district" id="id_district">
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" name="add_shipping" class="btn btn-primary">Thêm</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
      </div>
    </form>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', async function () {
  const provinceSelect = document.getElementById('province');
  const districtSelect = document.getElementById('district');
  const idProvinceInput = document.getElementById('id_province');
  const idDistrictInput = document.getElementById('id_district');

  try {
    const res = await fetch('https://provinces.open-api.vn/api/?depth=2');
    const provinces = await res.json();

    provinces.forEach(p => {
      const opt = new Option(p.name, p.name);
      opt.dataset.code = p.code;
      provinceSelect.add(opt);
    });

    provinceSelect.addEventListener('change', async () => {
      const selectedOption = provinceSelect.options[provinceSelect.selectedIndex];
      const code = selectedOption.dataset.code;
      idProvinceInput.value = code;

      districtSelect.innerHTML = '<option value="">-- Chọn Quận/Huyện --</option>';
      districtSelect.disabled = true;

      const res = await fetch(`https://provinces.open-api.vn/api/p/${code}?depth=2`);
      const data = await res.json();

      data.districts.forEach(d => {
        const opt = new Option(d.name, d.name);
        opt.dataset.code = d.code;
        districtSelect.add(opt);
      });

      districtSelect.disabled = false;
    });

    districtSelect.addEventListener('change', () => {
      const selected = districtSelect.options[districtSelect.selectedIndex];
      idDistrictInput.value = selected.dataset.code;
    });

  } catch (err) {
    console.error("Lỗi khi tải dữ liệu tỉnh/thành:", err);
  }
});
</script>
</body>
</html>
