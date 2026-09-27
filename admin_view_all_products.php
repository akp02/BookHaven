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

$count_q = mysqli_query($dbc, 'SELECT COUNT(*) AS total FROM product');
$count_row = mysqli_fetch_assoc($count_q);
$total = (int)$count_row['total'];

$products = array();
$prod_q = mysqli_query($dbc, 'SELECT id, name, category, price, quantity, description FROM product ORDER BY name');
while ($row = mysqli_fetch_assoc($prod_q)) {
    $products[] = $row;
}
mysqli_close($dbc);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>BookHaven &mdash; All Products</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header class="topbar admin">
        <h1>BookHaven &mdash; Product Catalog</h1>
        <nav>
            <a href="secret_page_admin.php">&larr; Back to admin panel</a>
            <a href="logout.php" class="btn-logout">Log out</a>
        </nav>
    </header>
    <main class="results">
        <p class="count">Total number of products in the catalog: <strong><?php echo $total; ?></strong></p>
        <table class="result-table">
            <thead>
                <tr><th>ID</th><th>Title</th><th>Category</th><th>Price</th><th>In Stock</th><th>Description</th></tr>Actions</th></tr>
            </thead>
            <tbody>
                <?php foreach ($products as $p): ?>
                    <tr>
                        <td><?php echo (int)$p['id']; ?></td>
                        <td><strong><?php echo htmlspecialchars($p['name']); ?></strong></td>
                        <td><?php echo htmlspecialchars($p['category']); ?></td>
                        <td>$<?php echo number_format((float)$p['price'], 2); ?></td>
                        <td><?php echo (int)$p['quantity']; ?></td>
                        <td><?php echo htmlspecialchars($p['description']); ?></td>
                        <td>
                            <a href="edit_product.php?id=<?php echo (int)$p['id']; ?>">Edit</a>
                            |
                            <a href="delete_product.php?id=<?php echo (int)$p['id']; ?>" onclick="return confirmDelete();">Delete</a>
                            
                            
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </main>
    
<script>
function confirmDelete() {
    return confirm("Are you sure you want to delete this book?");
}
</script>

</body>
</html>
