<?php
session_start();
require_once 'mysqli_connect.php';

$user_name = isset($_POST['user_name']) ? trim($_POST['user_name']) : '';
$email     = isset($_POST['email']) ? trim($_POST['email']) : '';
$first     = isset($_POST['first_name']) ? trim($_POST['first_name']) : '';
$last      = isset($_POST['last_name']) ? trim($_POST['last_name']) : '';
$password  = isset($_POST['password']) ? $_POST['password'] : '';
$confirm   = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : '';

if ($user_name === '' || $email === '' || $first === '' || $last === '' || $password === '' || $confirm === '') {
    mysqli_close($dbc);
    header('Location: signup_form.php?error=missing');
    exit;
}
if (strlen($password) < 6) {
    mysqli_close($dbc);
    header('Location: signup_form.php?error=short');
    exit;
}
if ($password !== $confirm) {
    mysqli_close($dbc);
    header('Location: signup_form.php?error=mismatch');
    exit;
}

$check = mysqli_prepare($dbc, 'SELECT id FROM users WHERE user_name = ?');
mysqli_stmt_bind_param($check, 's', $user_name);
mysqli_stmt_execute($check);
mysqli_stmt_store_result($check);
if (mysqli_stmt_num_rows($check) > 0) {
    mysqli_stmt_close($check);
    mysqli_close($dbc);
    header('Location: signup_form.php?error=taken');
    exit;
}
mysqli_stmt_close($check);

$hashed   = hash('sha512', $password);
$is_admin = 'no';
$stmt = mysqli_prepare($dbc, 'INSERT INTO users (user_name, password, email, first_name, last_name, is_admin) VALUES (?, ?, ?, ?, ?, ?)');
mysqli_stmt_bind_param($stmt, 'ssssss', $user_name, $hashed, $email, $first, $last, $is_admin);
$ok = mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);
mysqli_close($dbc);

if ($ok) {
    header('Location: login_form.php?signup=ok');
    exit;
}
header('Location: signup_form.php?error=failed');
exit;
