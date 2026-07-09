<?php
$conn = new mysqli("localhost", "root", "", "website");
$conn->set_charset("utf8");
if ($conn->connect_error) {
    die("Lỗi kết nối: " . $conn->connect_error);
}
?>

<head>
  <style>
    .footer {
      background-color: #111;
      color: #eee;
      padding: 20px 30px;
      font-size: 14px;
      line-height: 1.4;
    }

    .footer-container {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
      gap: 40px 20px;
    }

    .footer-col h4 {
      font-size: 15px;
      text-transform: uppercase;
      margin-bottom: 15px;
      color: #ffffff;
      position: relative;
    }

    .footer-col h4::after {
      content: "";
      display: block;
      width: 300px;
      height: 2px;
      background-color: #fff;
      margin-top: 8px;
    }

    .footer-col ul {
      list-style: none;
      padding: 0;
      margin: 0;
    }

    .footer-col ul li {
      margin-bottom: 10px;
      color: #ccc;
      line-height: 1.6;
    }

    .footer-col ul li a {
      color: #ccc;
      text-decoration: none;
      transition: color 0.3s;
    }

    .footer-col ul li a:hover {
      color: #fff;
      text-decoration: underline;
    }

    @media (max-width: 768px) {
      .footer {
        padding: 20px 15px;
        font-size: 13px;
      }

      .footer-col h4::after {
        width: 100%;
      }
    }
  </style>
</head>

<footer class="footer">
  <div class="footer-container">
    <?php
    $sql = "SELECT * FROM footer_column ORDER BY sort_order ASC";
    $columns = $conn->query($sql);
    while ($col = $columns->fetch_assoc()):
    ?>
      <div class="footer-col">
        <h4><?= htmlspecialchars($col['title']) ?></h4>
        <ul>
          <?php
          $col_id = (int)$col['id'];
          $links = $conn->query("SELECT * FROM footer_link WHERE column_id = $col_id");
          while ($link = $links->fetch_assoc()):
          ?>
            <?php if (!empty($link['link'])): ?>
              <li><a href="<?= htmlspecialchars($link['link']) ?>"><?= htmlspecialchars($link['name']) ?></a></li>
            <?php else: ?>
              <li><?= htmlspecialchars($link['name']) ?></li>
            <?php endif; ?>
          <?php endwhile; ?>
        </ul>
      </div>
    <?php endwhile; ?>
  </div>
</footer>
