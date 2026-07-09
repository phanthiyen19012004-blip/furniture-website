<?php
session_start();
$product_id = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;
$action = $_POST['action'] ?? '';
$qty = isset($_POST['qty']) ? (int)$_POST['qty'] : 1;

if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];

switch ($action) {
  case 'update':
    if ($qty < 1) $qty = 1;
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['qty'] = $qty;
    }
    break;

  case 'delete':
    unset($_SESSION['cart'][$product_id]);
    break;
}

$total_items = array_sum(array_column($_SESSION['cart'], 'qty'));
echo json_encode(['success' => true, 'total_items' => $total_items]);
