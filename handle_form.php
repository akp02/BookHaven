<?php
session_start();
require_once 'mysqli_connect.php';

if (!isset($_POST['user_name'], $_POST['password'])
    || trim($_POST['user_name']) === ''
    || $_POST['password'] === '') {
    mysqli_close($dbc);
    header('Location: login_form.php?error=1');
    exit;
}

$user_name = trim($_POST['user_name']);
$hashed_pw = hash('sha512', $_POST['password']);

$stmt = mysqli_prepare($dbc, 'SELECT user_name, is_admin FROM users WHERE user_name = ? AND password = ?');
mysqli_stmt_bind_param($stmt, 'ss', $user_name, $hashed_pw);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) === 1) {
    $row = mysqli_fetch_assoc($result);
    $_SESSION['user_name'] = $row['user_name'];
    $_SESSION['is_admin']  = $row['is_admin'];
    mysqli_stmt_close($stmt);
    mysqli_close($dbc);
    if ($row['is_admin'] === 'yes') {
        header('Location: secret_page_admin.php');
    } else {
        header('Location: secret_page.php');
    }
    exit;
}

mysqli_stmt_close($stmt);
mysqli_close($dbc);
header('Location: login_form.php?error=1');
exit;
