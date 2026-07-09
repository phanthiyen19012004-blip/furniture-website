  <?php
  include('../config/db.php');

  // Thống kê
  $orderCount        = $conn->query("SELECT COUNT(*) FROM transaction WHERE status = 1")->fetch_row()[0];
  $commentCount      = $conn->query("SELECT COUNT(*) FROM comment")->fetch_row()[0];
  $discountCount     = $conn->query("SELECT COUNT(*) FROM product WHERE discount > 0")->fetch_row()[0];
  $commentedProduct  = $conn->query("SELECT COUNT(DISTINCT product_id) FROM comment")->fetch_row()[0];
  $totalRevenueRow   = $conn->query("SELECT SUM(amount) FROM transaction WHERE status = 1")->fetch_row();
  $totalRevenue      = $totalRevenueRow[0] ?? 0;

  // Doanh thu theo tháng
  $currentYear = date('Y');
  $monthlyRevenue = array_fill(1, 12, 0);
  $query = "SELECT MONTH(FROM_UNIXTIME(created)) AS month, SUM(amount) AS total 
            FROM transaction 
            WHERE status = 3 AND YEAR(FROM_UNIXTIME(created)) = $currentYear 
            GROUP BY month";
  $result = $conn->query($query);
  while ($row = $result->fetch_assoc()) {
    $monthlyRevenue[(int)$row['month']] = (float)$row['total'];
  }

  $page = basename($_SERVER['PHP_SELF']);
  ?>

  <!DOCTYPE html>
  <html lang="vi">
  <head>
    <meta charset="UTF-8">
    <title>Trang chủ Admin</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
      .sidebar {
        width: 250px;
        min-height: 100vh;
        background-color: #fff;
        padding: 1rem;
        border-right: 1px solid #e0e0e0;
      }
      .sidebar-menu { list-style: none; padding-left: 0; }
      .sidebar-menu li { margin-bottom: 0.5rem; }
      .sidebar-menu a {
        display: block; color: #212529; padding: 10px 15px;
        text-decoration: none; border-radius: 5px; font-weight: 500;
      }
      .sidebar-menu a:hover { background-color: #f0f0f0; }
      .sidebar-menu a.active { background-color: #007bff; color: white !important; }

      .stat-box {
        border-radius: 10px; padding: 20px; text-align: center;
        background-color: #f8f9fa; box-shadow: 0 0 10px rgba(0,0,0,0.05);
      }
    </style>
  </head>
  <body>

  <?php include('includes/header.php'); ?>

  <div class="d-flex">
    <?php include('includes/sidebar.php'); ?>

    <div class="p-4" style="flex-grow: 1;">
      <h4 class="mb-4">Trang chủ</h4>
  <!-- THỐNG KÊ MỚI -->
  <div class="row">
    <div class="col-md-3 mb-4">
      <div class="stat-box">
        <i class="fas fa-shopping-cart fa-2x text-primary mb-2"></i>
        <h5>
          <?php
            $result = $conn->query("SELECT COUNT(*) FROM transaction");
            echo $result->fetch_row()[0];
          ?> Đơn hàng
        </h5>
      </div>
    </div>
    <div class="col-md-3 mb-4">
      <div class="stat-box">
        <i class="fas fa-comments fa-2x text-warning mb-2"></i>
        <h5>
          <?php
            $result = $conn->query("SELECT COUNT(*) FROM comment");
            echo $result->fetch_row()[0];
          ?> Bình luận
        </h5>
      </div>
    </div>
    <div class="col-md-3 mb-4">
      <div class="stat-box">
        <i class="fas fa-users fa-2x text-info mb-2"></i>
        <h5>
          <?php
            $result = $conn->query("SELECT COUNT(*) FROM user");
            echo $result->fetch_row()[0];
          ?> Khách hàng
        </h5>
      </div>
    </div>
    <div class="col-md-3 mb-4">
      <div class="stat-box">
        <i class="fas fa-user-shield fa-2x text-success mb-2"></i>
        <h5>
          <?php
            $result = $conn->query("SELECT COUNT(*) FROM admin WHERE level = 2");
            echo $result->fetch_row()[0];
          ?> Nhân viên
        </h5>
      </div>
    </div>
  </div>


      <!-- DOANH THU -->
      <div class="card mb-5">
        <div class="card-body">
          <h5>Thống kê doanh thu</h5>
          <div class="form-inline mb-3">
            <label class="mr-2">Năm:</label>
            <select class="form-control"><option><?= $currentYear ?></option></select>
            <p class="ml-3">Tổng doanh thu: <?= number_format($totalRevenue, 0, ',', '.') ?> VNĐ</p>
          </div>
          <canvas id="chart"></canvas>
        </div>
      </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script>
  const ctx = document.getElementById('chart').getContext('2d');
  new Chart(ctx, {
    type: 'line',
    data: {
      labels: ['Tháng 1','2','3','4','5','6','7','8','9','10','11','12'],
      datasets: [{
        label: 'Doanh thu',
        data: <?= json_encode(array_values($monthlyRevenue)) ?>,
        borderColor: 'blue',
        borderWidth: 2,
        fill: false
      }]
    },
    options: {
      responsive: true,
      scales: { y: { beginAtZero: true } }
    }
  });
  </script>
  </body>
  </html>
