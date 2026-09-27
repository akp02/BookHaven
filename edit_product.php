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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $category = trim($_POST['category']);
    $price = (float) $_POST['price'];
    $quantity = (int) $_POST['quantity'];
    $description = trim($_POST['description']);

    $stmt = mysqli_prepare($dbc, "UPDATE product SET name = ?, category = ?, price = ?, quantity = ?, description = ? WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "ssdiss", $name, $category, $price, $quantity, $description, $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    mysqli_close($dbc);

    header('Location: admin_view_all_products.php');
    exit;
}

$stmt = mysqli_prepare($dbc, "SELECT id, name, category, price, quantity, description FROM product WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$product = mysqli_fetch_assoc($result);

if (!$product) {
    header('Location: admin_view_all_products.php');
    exit;
}

mysqli_stmt_close($stmt);
mysqli_close($dbc);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Book</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<header class="topbar admin">
    <h1>BookHaven &mdash; Edit Book</h1>
    <nav>
        <a href="admin_view_all_products.php">&larr; Back to Product Catalog</a>
        <a href="logout.php" class="btn-logout">Log out</a>
    </nav>
</header>

<main class="results">
    <form method="post">
        <label>Title</label>
        <input type="text" name="name" value="<?php echo htmlspecialchars($product['name']); ?>" required>

        <label>Category</label>
        <input type="text" name="category" value="<?php echo htmlspecialchars($product['category']); ?>" required>

        <label>Price</label>
        <input type="number" step="0.01" name="price" value="<?php echo htmlspecialchars($product['price']); ?>" required>

        <label>Quantity</label>
        <input type="number" name="quantity" value="<?php echo htmlspecialchars($product['quantity']); ?>" required>

        <label>Description</label>
        <textarea name="description" required><?php echo htmlspecialchars($product['description']); ?></textarea>

        <button type="submit">Update Book</button>
    </form>
</main>

</body>
</html>