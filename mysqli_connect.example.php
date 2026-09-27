<?php

// Copy this file to mysqli_connect.php and replace the placeholders
// with your own MySQL database credentials.

$dbc = mysqli_connect(
    'YOUR_DATABASE_HOST',
    'YOUR_DATABASE_USERNAME',
    'YOUR_DATABASE_PASSWORD',
    'YOUR_DATABASE_NAME'
);

if (!$dbc) {
    die('Database connection failed: ' . mysqli_connect_error());
}
?>