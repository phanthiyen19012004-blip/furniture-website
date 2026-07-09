<?php
include('../config/db.php');

$filter_reply = isset($_GET['filter_reply']) ? $_GET['filter_reply'] : 'all';

$where_condition = '';
if ($filter_reply === 'replied') {
    $where_condition = "WHERE c.id IN (SELECT comment_id FROM comment_reply)";
} elseif ($filter_reply === 'not_replied') {
    $where_condition = "WHERE c.id NOT IN (SELECT comment_id FROM comment_reply)";
}

// Xử lý submit trả lời
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reply_submit'])) {
    $comment_id = (int)$_POST['comment_id'];
    $reply_content = trim($_POST['reply_content']);
    $user_id = 1; // Giả sử ID người trả lời, bạn thay theo user đang đăng nhập

    if ($reply_content !== '') {
        $stmt = $conn->prepare("INSERT INTO comment_reply (comment_id, user_id, content, created) VALUES (?, ?, ?, NOW())");
        $stmt->bind_param("iis", $comment_id, $user_id, $reply_content);
        $stmt->execute();
    }

    // Quay lại trang hiện tại (giữ query string)
    header("Location: " . $_SERVER['REQUEST_URI']);
    exit;
}

// Lấy danh sách bình luận theo điều kiện lọc
$sql = "SELECT c.id, c.content, c.image_link, c.rate, FROM_UNIXTIME(c.date, '%d-%m-%Y %H:%i') AS date_comment,
        p.name AS product_name, u.name AS user_name
        FROM comment c
        LEFT JOIN product p ON c.product_id = p.id
        LEFT JOIN user u ON c.user_id = u.id
        $where_condition
        ORDER BY c.id DESC";
$comments = $conn->query($sql);

// Lấy trả lời của các bình luận
$replies_sql = "SELECT r.*, u.name AS replier_name FROM comment_reply r LEFT JOIN user u ON r.user_id = u.id ORDER BY r.created ASC";
$replies_result = $conn->query($replies_sql);
$replies = [];
if ($replies_result) {
    while ($row = $replies_result->fetch_assoc()) {
        $replies[$row['comment_id']][] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8" />
  <title>Quản lý bình luận và trả lời</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <style>
    .reply-box { margin-left: 40px; margin-top: 10px; }
    .reply-item { border-left: 2px solid #ddd; padding-left: 10px; margin-bottom: 10px; }
  </style>
</head>
<body>
<?php include('includes/header.php'); ?>
<div class="d-flex">
  <?php include('includes/sidebar.php'); ?>

  <div class="p-4" style="flex-grow: 1;">
    <h4 class="mb-4">Danh sách bình luận</h4>

    <!-- Form lọc trạng thái trả lời -->
    <form method="get" class="form-inline mb-3">
      <label for="filter_reply" class="mr-2 font-weight-bold">Lọc trạng thái trả lời:</label>
      <select name="filter_reply" id="filter_reply" class="form-control form-control-sm mr-2" onchange="this.form.submit()">
        <option value="all" <?= $filter_reply === 'all' ? 'selected' : '' ?>>Tất cả bình luận</option>
        <option value="replied" <?= $filter_reply === 'replied' ? 'selected' : '' ?>>Đã trả lời</option>
        <option value="not_replied" <?= $filter_reply === 'not_replied' ? 'selected' : '' ?>>Chưa trả lời</option>
      </select>
      <noscript><button type="submit" class="btn btn-primary btn-sm">Lọc</button></noscript>
    </form>

    <?php if ($comments && $comments->num_rows > 0): ?>
      <?php while ($comment = $comments->fetch_assoc()): ?>
        <div class="card mb-4">
          <div class="card-body">
            <h5>Sản phẩm: <?= htmlspecialchars($comment['product_name']) ?></h5>
            <p><strong>Người dùng:</strong> <?= htmlspecialchars($comment['user_name']) ?></p>
            <p><strong>Nội dung:</strong> <?= nl2br(htmlspecialchars($comment['content'])) ?></p>
            <?php if (!empty($comment['image_link'])): ?>
              <p>
                <img src="/website/assets/images/reviews/<?php echo htmlspecialchars($comment['image_link']); ?>" alt="Ảnh bình luận" style="max-width: 200px; border: 1px solid #ccc;">
              </p>
            <?php endif; ?>
            <p><strong>Đánh giá:</strong> <?= (int)$comment['rate'] ?>/5</p>
            <p><small>Ngày bình luận: <?= $comment['date_comment'] ?></small></p>

            <!-- Hiển thị trả lời -->
            <div class="reply-box">
              <h6>Trả lời:</h6>
              <?php
                if (!empty($replies[$comment['id']])) {
                    foreach ($replies[$comment['id']] as $reply) {
                        echo '<div class="reply-item">';
                        echo '<p><strong>' . htmlspecialchars($reply['replier_name']) . ':</strong> ' . nl2br(htmlspecialchars($reply['content'])) . '</p>';
                        echo '<small>' . $reply['created'] . '</small>';
                        echo '</div>';
                    }
                } else {
                    echo '<p><em>Chưa có trả lời nào.</em></p>';
                }
              ?>
            </div>

            <!-- Form trả lời -->
            <form method="post" class="mt-3">
              <input type="hidden" name="comment_id" value="<?= $comment['id'] ?>">
              <div class="form-group">
                <label for="reply_content_<?= $comment['id'] ?>">Trả lời bình luận</label>
                <textarea id="reply_content_<?= $comment['id'] ?>" name="reply_content" class="form-control" rows="2" required></textarea>
              </div>
              <button type="submit" name="reply_submit" class="btn btn-primary btn-sm">Gửi trả lời</button>
            </form>
          </div>
        </div>
      <?php endwhile; ?>
    <?php else: ?>
      <p>Chưa có bình luận nào.</p>
    <?php endif; ?>

  </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
</body>
</html>
