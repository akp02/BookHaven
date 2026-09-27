<?php
session_start();
if (!isset($_SESSION['user_name'])) {
    header('Location: login_form.php');
    exit;
}
require_once 'mysqli_connect.php';

$current = isset($_POST['current_password']) ? $_POST['current_password'] : '';
$new_pw  = isset($_POST['new_password']) ? $_POST['new_password'] : '';
$confirm = isset($_POST['confirm_new_password']) ? $_POST['confirm_new_password'] : '';

if ($current === '' || $new_pw === '' || $confirm === '') {
    mysqli_close($dbc);
    header('Location: update_password.php?error=missing');
    exit;
}
if (strlen($new_pw) < 6) {
    mysqli_close($dbc);
    header('Location: update_password.php?error=short');
    exit;
}
if ($new_pw !== $confirm) {
    mysqli_close($dbc);
    header('Location: update_password.php?error=mismatch');
    exit;
}

$hashed_current = hash('sha512', $current);
$stmt = mysqli_prepare($dbc, 'SELECT id FROM users WHERE user_name = ? AND password = ?');
mysqli_stmt_bind_param($stmt, 'ss', $_SESSION['user_name'], $hashed_current);
mysqli_stmt_execute($stmt);
mysqli_stmt_store_result($stmt);
if (mysqli_stmt_num_rows($stmt) !== 1) {
    mysqli_stmt_close($stmt);
    mysqli_close($dbc);
    header('Location: update_password.php?error=current');
    exit;
}
mysqli_stmt_close($stmt);

$hashed_new = hash('sha512', $new_pw);
$upd = mysqli_prepare($dbc, 'UPDATE users SET password = ? WHERE user_name = ?');
mysqli_stmt_bind_param($upd, 'ss', $hashed_new, $_SESSION['user_name']);
$ok = mysqli_stmt_execute($upd);
mysqli_stmt_close($upd);
mysqli_close($dbc);

if ($ok) {
    header('Location: update_password.php?ok=1');
    exit;
}
header('Location: update_password.php?error=failed');
exit;
