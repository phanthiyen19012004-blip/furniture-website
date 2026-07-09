<?php

// Tính tổng số sản phẩm trong giỏ
$totalItems = 0;
if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        $totalItems += $item['qty'];
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8" />
    <title>Trang web của tôi</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <style>
        * {
            margin: 0; padding: 0; box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        header {
            background-color: #fff;
            padding: 10px 20px;
            position: relative;
            border-bottom: 1px solid #eee;
        }
        .header-container {
            max-width: 1200px;
            margin: 0 auto;
            height: 70px;
            display: flex;
            align-items: center;
            justify-content: flex-start;
            position: relative;
            padding: 0 20px;
            box-sizing: border-box;
        }

        /* Logo bên trái: SVG */
        .logo-left {
            width: 80px;
            cursor: pointer;
            user-select: none;
        }
        .logo-left svg {
            height: 60px;
            width: auto;
            display: block;
        }

        /* Tên HomeStyle ở giữa */
        .logo-center {
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
            font-weight: 900;
            font-size: 28px;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #3c7189;
            letter-spacing: -1px;
            user-select: none;
            cursor: pointer;
            white-space: nowrap;
            z-index: 10;
        }
        .logo-center .dot {
            width: 12px;
            height: 12px;
            background-color: #f26d25;
            border-radius: 50%;
            display: inline-block;
            margin-left: 6px;
            vertical-align: middle;
            position: relative;
            top: -2px;
        }

        .btn-3dhouse {
            position: absolute;
            left: calc(50% + 130px);
            top: 50%;
            transform: translateY(-50%);
            user-select: none;
            cursor: pointer;
            white-space: nowrap;
            z-index: 10;
            transition: background-color 0.3s, box-shadow 0.3s;
            box-shadow: 0 2px 6px rgba(242, 109, 37, 0.5);
            border-radius: 6px;
        }
        .btn-3dhouse a {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 18px;
            border: none;
            background-color: #f26d25;
            color: white;
            border-radius: 6px;
            font-weight: 700;
            font-size: 16px;
            text-decoration: none;
            user-select: none;
            cursor: pointer;
            box-shadow: none;
            transition: background-color 0.3s;
        }

        /* Phần tài khoản và giỏ hàng - position absolute sát phải */
        .header-icons {
            position: absolute;
            right: 0;
            top: 50%;
            transform: translateY(-50%);
            display: flex;
            align-items: center;
            gap: 30px;
            color: #333;
            font-size: 16px;
            user-select: none;
            margin-left: 0;
            z-index: 10;
        }
        .header-icons a {
            display: flex;
            align-items: center;
            color: #333;
            text-decoration: none;
            font-weight: 500;
            gap: 6px;
            position: relative;
            transition: color 0.2s;
        }
        .header-icons a:hover {
            color: #3c7189;
        }
        .header-icons i {
            font-size: 22px;
            min-width: 24px;
            text-align: center;
        }
        .cart-count {
            position: absolute;
            top: -7px;
            right: -10px;
            background-color: #f26d25;
            color: white;
            font-size: 12px;
            width: 18px;
            height: 18px;
            border-radius: 50%;
            text-align: center;
            line-height: 18px;
            font-weight: 700;
            pointer-events: none;
            user-select: none;
        }
        /* Text nhỏ bên dưới phần tài khoản */
        .account-text {
            display: flex;
            flex-direction: column;
            line-height: 1.1;
        }
        .account-text .main-text {
            font-size: 14px;
        }
        .account-text .sub-text {
            font-size: 12px;
            color: #666;
        }

        @media (max-width: 768px) {
            .header-container {
            flex-direction: row;
            justify-content: space-between;
            padding: 0 20px;
            height: auto;
            gap: 10px;
          }

          .logo-left {
            width: 60px;
          }

          .logo-left svg {
            height: 48px;
          }

          .logo-center {
            position: static;
            transform: none;
            font-size: 20px;
            text-align: center;
            flex-grow: 1;
            padding-left: 10px;
          }

          .btn-3dhouse {
            position: static;
            transform: none;
            margin-left: 10px;
            box-shadow: none;
          }

          .header-icons {
            position: static;
            transform: none;
            margin-left: auto;
            gap: 15px;
            font-size: 14px;
          }

          .header-icons i {
            font-size: 18px;
          }

          .account-text .main-text {
            font-size: 12px;
          }

          .account-text .sub-text {
            font-size: 10px;
            display: none;
          }

          .cart-count {
            font-size: 10px;
            width: 16px;
            height: 16px;
            line-height: 16px;
            top: -6px;
            right: -8px;
          }

          .header-icons span {
            display: none;
          }
            .btn-3dhouse {
            display: block;
            margin: 10px auto;
            position: static;
            transform: none;
            box-shadow: none;
            border-radius: 4px;
        }
        .btn-3dhouse a {
            padding: 6px 12px;
            font-size: 14px;
            gap: 4px;
        }
        .btn-3dhouse i {
            font-size: 18px;
        }
        }
    </style>
</head>
<body>

<header>
    <div class="header-container">

        <!-- Logo bên trái: SVG -->
        <div class="logo-left" title="HomeStyle - Nội thất">
            <a href="index.php" style="display:block;">
                <svg viewBox="0 0 180 180" xmlns="http://www.w3.org/2000/svg" aria-label="Logo HomeStyle" role="img" >
                  <!-- Ngôi nhà -->
                  <rect x="50" y="70" width="80" height="70" fill="#3c7189" rx="12" ry="12" />
                  <polygon points="45,70 90,30 135,70" fill="#f26d25" />
                  <!-- Ghế nội thất đơn giản -->
                  <rect x="80" y="110" width="20" height="20" fill="#f9f3f0" rx="4" ry="4" />
                  <rect x="85" y="90" width="10" height="20" fill="#f9f3f0" rx="3" ry="3" />
                </svg>
            </a>
        </div>

        <!-- Tên HomeStyle ở giữa -->
        <div class="logo-center" title="moho">
            <a href="index.php" style="color: #3c7189; text-decoration:none; user-select:none;">
                HomeStyle<span class="dot"></span>
            </a>
        </div>

        <!-- Nút 3D House bên phải tên -->
        <div class="btn-3dhouse" title="3D House Viewer">
          <a href="models.php" target="_self" rel="noopener noreferrer">
            <i class="fas fa-house"></i>
            <span>3D HOUSE</span>
          </a>
        </div>

        <!-- Phần tài khoản và giỏ hàng -->
        <div class="header-icons">

            <?php if (isset($_SESSION['user'])): ?>
                <a href="account.php" title="Tài khoản của tôi">
                    <i class="fas fa-user"></i>
                    <div class="account-text">
                        <div class="main-text"><?= htmlspecialchars($_SESSION['user']['name'] ?? 'Tài khoản của tôi') ?></div>
                        <div class="sub-text">Tài khoản của tôi <i class="fas fa-chevron-down" style="font-size:10px; margin-left:2px;"></i></div>
                    </div>
                </a>
            <?php else: ?>
                <a href="login.php" title="Đăng nhập / Đăng ký">
                    <i class="fas fa-user"></i>
                    <div class="account-text">
                        <div class="main-text">Đăng nhập / Đăng ký</div>
                    </div>
                </a>
            <?php endif; ?>

            <a href="cart.php" title="Giỏ hàng">
                <i class="fas fa-shopping-cart"></i>
                <span>Giỏ hàng</span>
                <?php if ($totalItems > 0): ?>
                    <span class="cart-count"><?= $totalItems ?></span>
                <?php endif; ?>
            </a>

        </div>
    </div>
</header>

</body>
</html>
