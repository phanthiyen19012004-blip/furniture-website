
echo '<pre>';
print_r($_SESSION);
echo '</pre>';
exit;
<?php
session_start();
$conn = new mysqli("localhost", "root", "", "website");
$conn->set_charset("utf8");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Lấy thông tin từ form
    $fullname = $conn->real_escape_string($_POST['fullname']);
    $phone = $conn->real_escape_string($_POST['phone']);
    $province = $conn->real_escape_string($_POST['province']);
    $district = $conn->real_escape_string($_POST['district']);
    $ward = $conn->real_escape_string($_POST['ward']);
    $address_detail = $conn->real_escape_string($_POST['address_detail']);
    $note = isset($_POST['note']) ? $conn->real_escape_string($_POST['note']) : '';
    $ship_fee = isset($_POST['ship_fee']) ? intval($_POST['ship_fee']) : 0;
    $final_total = isset($_POST['final_total']) ? floatval($_POST['final_total']) : 0;

    // Địa chỉ đầy đủ
    $full_address = "$address_detail, $ward, $district, $province";

    $user_id = "NULL"; // hoặc 0
    $user_email = '';


    // Lưu đơn hàng vào bảng transaction
    $sql = "INSERT INTO transaction (
                user_id, user_name, user_email, user_phone, user_address,
                message, amount, status, created
            )
            VALUES (
                $user_id, '$fullname', '$user_email', '$phone', '$full_address',
                '$note', $final_total, 0, " . time() . ")";

    if ($conn->query($sql)) {
        $transaction_id = $conn->insert_id;

        // (Tuỳ chọn) Lưu sản phẩm chi tiết vào bảng order tại đây

        // Xoá giỏ hàng
        unset($_SESSION['cart']);

        echo "<script>alert('Đặt hàng thành công!'); window.location.href='index.php';</script>";
    } else {
        echo "Lỗi khi lưu đơn hàng: " . $conn->error;
    }
} else {
    echo "Phương thức không hợp lệ.";
}
?>
