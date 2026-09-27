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

$name        = isset($_POST['name']) ? trim($_POST['name']) : '';
$category    = isset($_POST['category']) ? trim($_POST['category']) : '';
$price       = isset($_POST['price']) ? (float)$_POST['price'] : -1.0;
$quantity    = isset($_POST['quantity']) ? (int)$_POST['quantity'] : -1;
$description = isset($_POST['description']) ? trim($_POST['description']) : '';

$errors = array();
if ($name === '')        $errors[] = 'Title is required.';
if ($category === '')    $errors[] = 'Category is required.';
if ($price < 0)          $errors[] = 'Price must be a non-negative number.';
if ($quantity < 0)       $errors[] = 'Quantity must be a non-negative integer.';
if ($description === '') $errors[] = 'Description is required.';

if (empty($errors)) {
    $stmt = mysqli_prepare($dbc, 'INSERT INTO product (name, category, price, quantity, description) VALUES (?, ?, ?, ?, ?)');
    mysqli_stmt_bind_param($stmt, 'ssdis', $name, $category, $price, $quantity, $description);
    $ok = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    mysqli_close($dbc);
    if ($ok) {
        header('Location: secret_page_admin.php?added=' . urlencode($name));
        exit;
    }
    $errors[] = 'Database error while inserting the new book.';
} else {
    mysqli_close($dbc);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>BookHaven &mdash; Admin</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header class="topbar admin">
        <h1>BookHaven &mdash; Admin</h1>
        <nav>
            <a href="secret_page_admin.php">&larr; Back to admin panel</a>
            <a href="logout.php" class="btn-logout">Log out</a>
        </nav>
    </header>
    <main class="results">
        <h2>Unable to add book</h2>
        <ul class="error-list">
            <?php foreach ($errors as $e): ?>
                <li><?php echo htmlspecialchars($e); ?></li>
            <?php endforeach; ?>
        </ul>
        <p><a href="secret_page_admin.php">&larr; Try again</a></p>
    </main>
</body>
</html>
