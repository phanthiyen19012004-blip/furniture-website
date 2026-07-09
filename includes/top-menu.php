<?php
// Kết nối CSDL
$conn = new mysqli("localhost", "root", "", "website");
$conn->set_charset("utf8");
if ($conn->connect_error) {
    die("Lỗi kết nối: " . $conn->connect_error);
}
?>

<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
  * {
    box-sizing: border-box;
  }

  .top-menu-wrapper {
    background-color: #fff;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  }

  .top-menu {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0 20px;
    height: 50px;
    position: relative;
  }

  .left-group {
    display: flex;
    align-items: center;
    gap: 20px;
  }

  .menu-toggle {
    font-size: 20px;
    color: #444;
    cursor: pointer;
  }

  .menu-static a {
    text-decoration: none;
    color: #444;
    font-weight: bold;
    font-size: 14px;
    text-transform: uppercase;
    position: relative;
    padding-bottom: 5px;
    transition: color 0.3s ease;
  }

  .menu-static a:hover {
    color: #222;
    text-decoration: none;
  }

  .menu-static {
    display: flex;
    gap: 20px;
    align-items: center;
  }

  .menu-left {
    position: fixed;
    top: 0;
    left: -300px;
    width: 260px;
    height: 100vh;
    overflow-y: auto;
    background-color: #fff;
    box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
    z-index: 9999;
    padding: 20px;
    display: flex;
    flex-direction: column;
    transition: left 0.3s ease;
  }


  .menu-left.show {
    left: 0;
  }

  .menu-left .close-btn {
    align-self: flex-end;
    font-size: 20px;
    color: #999;
    cursor: pointer;
    margin-bottom: 10px;
  }

  .menu-item {
    margin-top: 15px;
  }

  .menu-left > .menu-item > a {
    font-weight: bold;
    text-transform: uppercase;
    font-size: 14px;
    color: #444;
    padding: 10px 0;
    display: block;
    text-decoration: none;
    border-bottom: 1px solid #eee;
  }

  .submenu {
    padding-left: 10px;
    margin-top: 5px;
  }

  .submenu a {
    font-size: 13px;
    color: #666;
    display: block;
    padding: 6px 0;
    text-decoration: none;
  }

  .submenu a:hover {
    color: #111;
  }

  .menu-right {
    display: flex;
    align-items: center;
    gap: 8px;
  }

  .menu-right form {
    display: flex;
    align-items: center;
    gap: 5px;
    position: relative;
  }

  .menu-right i {
    color: #999;
    font-size: 14px;
  }

  .menu-right input[type="text"] {
    border: none;
    border-bottom: 1px solid #ccc;
    outline: none;
    padding: 5px 0 3px 5px;
    font-size: 13px;
    color: #333;
    width: 200px;
    transition: width 0.3s ease;
  }

  .menu-right input[type="text"]:focus {
    border-color: #444;
  }

  .menu-right input::placeholder {
    color: #aaa;
  }

  .suggest-box {
    position: absolute;
    top: 35px;
    left: 25px;
    width: 220px;
    background: #fff;
    border: 1px solid #ccc;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
    z-index: 9999;
    display: none;
    max-height: 200px;
    overflow-y: auto;
    border-radius: 4px;
  }

  .suggest-item a {
    display: block;
    padding: 8px 12px;
    font-size: 13px;
    color: #333;
    text-decoration: none;
  }

  .suggest-item a:hover {
    background-color: #f2f2f2;
  }

  @media (max-width: 768px) {
    .top-menu {
      flex-direction: column;
      align-items: flex-start;
      gap: 10px;
      height: auto;
      padding: 10px;
    }

    .left-group {
      width: 100%;
      justify-content: space-between;
    }

    .menu-static {
      display: none;
    }

    .menu-toggle {
      font-size: 24px;
    }

    .menu-right {
      width: 100%;
      justify-content: space-between;
    }

    .menu-right input[type="text"] {
      width: 100%;
      font-size: 14px;
    }

    .suggest-box {
      width: 100%;
      left: 0;
    }

    .menu-left {
      width: 80%;
      max-width: 280px;
    }

    .submenu a {
      font-size: 14px;
    }

    .menu-left .menu-item > a {
      font-size: 15px;
    }
  }
</style>

<!-- TOP MENU -->
<div class="top-menu-wrapper">
  <div class="top-menu">
    <div class="left-group">
      <div class="menu-toggle" onclick="toggleMenu()">
        <i class="fas fa-bars"></i>
      </div>
      <div class="menu-static">
        <?php
        $top_menu = mysqli_query($conn, "SELECT * FROM top_menu ORDER BY sort_order ASC");
        while ($row = mysqli_fetch_assoc($top_menu)):
        ?>
          <a href="<?= htmlspecialchars($row['link']) ?>"><?= htmlspecialchars($row['name']) ?></a>
        <?php endwhile; ?>
      </div>
    </div>
    <div class="menu-right">
      <form action="search.php" method="get" class="search-wrapper">
        <i class="fas fa-search"></i>
        <input type="text" name="keyword" placeholder="Tìm sản phẩm..." required>
        <div class="suggest-box" id="suggestBox"></div>
      </form>
    </div>
  </div>
</div>

<div class="menu-left" id="menuLeft">
  <div class="close-btn" onclick="toggleMenu()">
    <i class="fas fa-times"></i>
  </div>
  <?php
  $main_menu = mysqli_query($conn, "SELECT * FROM side_menu");
  while ($menu = mysqli_fetch_assoc($main_menu)):
  ?>
    <div class="menu-item">
      <a href="<?= htmlspecialchars($menu['link']) ?>"><?= htmlspecialchars($menu['name']) ?></a>
      <div class="submenu">
        <?php
        $submenu = mysqli_query($conn, "SELECT * FROM side_submenu WHERE parent_id = " . (int)$menu['id']);
        while ($sub = mysqli_fetch_assoc($submenu)):
        ?>
          <a href="<?= htmlspecialchars($sub['link']) ?>"><?= htmlspecialchars($sub['name']) ?></a>
        <?php endwhile; ?>
      </div>
    </div>
  <?php endwhile; ?>
</div>

<script>
  function toggleMenu() {
    const menuLeft = document.getElementById('menuLeft');
    menuLeft.classList.toggle('show');
  }

  document.addEventListener('DOMContentLoaded', function () {
    const input = document.querySelector('input[name="keyword"]');
    const suggestBox = document.getElementById('suggestBox');

    input.addEventListener('input', function () {
      const keyword = this.value.trim();
      if (keyword.length < 1) {
        suggestBox.innerHTML = '';
        suggestBox.style.display = 'none';
        return;
      }

      fetch(`search-suggest.php?term=${encodeURIComponent(keyword)}`)
        .then(res => res.json())
        .then(data => {
          suggestBox.innerHTML = '';
          if (data.length === 0) {
            suggestBox.style.display = 'none';
            return;
          }

          data.forEach(item => {
            const div = document.createElement('div');
            div.className = 'suggest-item';
            div.innerHTML = `<a href="product.php?id=${item.id}">${item.name}</a>`;
            suggestBox.appendChild(div);
          });
          suggestBox.style.display = 'block';
        });
    });

    document.addEventListener('click', function (e) {
      if (!suggestBox.contains(e.target) && e.target !== input) {
        suggestBox.innerHTML = '';
        suggestBox.style.display = 'none';
      }
    });
  });
</script>