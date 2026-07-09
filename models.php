<?php
// Kết nối database
include 'config/db.php';

if ($conn->connect_error) {
    die("Lỗi kết nối database: " . $conn->connect_error);
}

// Lấy danh sách model mới nhất
$sql = "SELECT id, name, file_path, created_at FROM models ORDER BY created_at DESC";
$result = $conn->query($sql);

$models = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $models[] = $row;
    }
} else {
    die("Lỗi truy vấn database: " . $conn->error);
}
?>

<?php include 'includes/header.php'; ?>
<?php include 'includes/top-menu.php'; ?>

<style>
  .collection-concept {
    max-width: 1140px;
    margin: 40px auto 60px;
    font-family: 'Arial', sans-serif;
  }
  .collection-concept h2 {
    text-align: center;
    font-weight: 400;
    letter-spacing: 0.08em;
    font-size: 18px;
    margin-bottom: 30px;
    color: #000;
  }
.model-list {
  display: flex;
  flex-wrap: wrap;
  gap: 20px; /* Khoảng cách giữa các item */
  justify-content: center; /* Căn giữa toàn bộ hàng */
  max-width: 800px; /* Giới hạn chiều rộng tổng */
  margin: 0 auto;
}

.model-item {
  flex: 0 1 calc(50% - 20px); /* Mỗi item chiếm 50% trừ khoảng cách */
  background: #fff;
  border-radius: 12px;
  box-shadow: 0 4px 10px rgb(0 0 0 / 0.1);
  padding: 16px;
  user-select: none;
  display: flex;
  flex-direction: column;
  align-items: center;
}


  .model-item model-viewer {
    width: 100%;
    height: 320px;
    border-radius: 8px;
  }
  .model-name {
    margin-top: 12px;
    font-weight: 700;
    font-size: 16px;
    text-align: center;
  }
  @media (max-width: 992px) {
    .model-item {
      flex: 0 1 calc(50% - 16px);
    }
  }
  @media (max-width: 576px) {
    .model-item {
      flex: 0 1 100%;
    }
    .model-item model-viewer {
      height: 240px;
    }
  }
</style>
<script type="module" src="https://unpkg.com/@google/model-viewer/dist/model-viewer.min.js"></script>

<div class="collection-concept">
  <h2>COLLECTION CONCEPT</h2>

  <?php if (empty($models)): ?>
    <p style="text-align:center;">Chưa có mô hình nào được tải lên.</p>
  <?php else: ?>
    <div class="model-list" id="modelList">
      <?php foreach ($models as $model): 
        $modelFilePath = 'assets/images/models/' . $model['file_path'];
      ?>
        <div class="model-item">
          <?php if (file_exists($modelFilePath)): ?>
            <model-viewer
              src="<?= htmlspecialchars($modelFilePath) ?>"
              alt="<?= htmlspecialchars($model['name']) ?>"
              camera-controls
              auto-rotate
              loading="lazy"
              style="width: 100%; height: 320px;"
              tabindex="0"
            ></model-viewer>
          <?php else: ?>
            <p style="color: red; text-align:center;">
              File mô hình không tồn tại: <?= htmlspecialchars($model['file_path']) ?>
            </p>
          <?php endif; ?>

          <div class="model-name"><?= htmlspecialchars($model['name']) ?></div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>
