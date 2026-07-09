<?php
session_start();

$product_id = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;
$qty = isset($_POST['qty']) ? max(1, (int)$_POST['qty']) : 1;

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id]['qty'] += $qty;
} else {
    $_SESSION['cart'][$product_id] = ['qty' => $qty];
}

// Tính tổng số lượng sản phẩm trong giỏ
$total_items = array_sum(array_column($_SESSION['cart'], 'qty'));

echo json_encode(['success' => true, 'total_items' => $total_items]);
