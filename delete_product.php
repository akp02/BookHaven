<?php
session_start();

if (!isset($_SESSION['user_name'])) {
    header('Location: login_form.php');
    exit;
}

if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== 'yes') {
    header('Location: secret_page.php');
    exit;
}

require_once 'mysqli_connect.php';

if (!isset($_GET['id'])) {
    header('Location: admin_view_all_products.php');
    exit;
}

$id = (int) $_GET['id'];

$stmt = mysqli_prepare($dbc, "DELETE FROM product WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);

mysqli_stmt_close($stmt);
mysqli_close($dbc);

header('Location: admin_view_all_products.php');
exit;
?>