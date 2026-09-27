<?php
session_start();
if (!isset($_SESSION['user_name'])) {
    header('Location: login_form.php');
    exit;
}
if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}
if (isset($_POST['product_id'])) {
    $product_id = (int)$_POST['product_id'];
    if ($product_id > 0) {
        $_SESSION['cart'][] = $product_id;
    }
}
header('Location: view_cart.php');
exit;
