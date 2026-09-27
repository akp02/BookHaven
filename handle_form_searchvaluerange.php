<?php
session_start();
if (!isset($_SESSION['user_name'])) {
    header('Location: login_form.php');
    exit;
}
require_once 'mysqli_connect.php';

$min_price = isset($_POST['min_price']) ? (float)$_POST['min_price'] : -1.0;
$max_price = isset($_POST['max_price']) ? (float)$_POST['max_price'] : -1.0;
$error = '';
$results = array();

if ($min_price < 0 || $max_price < 0) {
    $error = 'Both prices must be non-negative numbers.';
} elseif ($min_price > $max_price) {
    $error = 'Minimum price cannot be greater than maximum price.';
} else {
    $stmt = mysqli_prepare($dbc, 'SELECT id, name, category, price, quantity, description FROM product WHERE price BETWEEN ? AND ? ORDER BY price ASC');
    mysqli_stmt_bind_param($stmt, 'dd', $min_price, $max_price);
    mysqli_stmt_execute($stmt);
    $rs = mysqli_stmt_get_result($stmt);
    while ($row = mysqli_fetch_assoc($rs)) {
        $results[] = $row;
    }
    mysqli_stmt_close($stmt);
}
mysqli_close($dbc);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>BookHaven &mdash; Search Results</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header class="topbar">
        <h1>BookHaven</h1>
        <nav>
            <a href="secret_page.php">&larr; Back to search</a>
            <a href="view_cart.php">View Cart</a>
            <a href="logout.php" class="btn-logout">Log out</a>
        </nav>
    </header>
    <main class="results">
        <?php if ($error !== ''): ?>
            <div class="message error"><?php echo htmlspecialchars($error); ?></div>
            <p><a href="secret_page.php">&larr; Back to search</a></p>
        <?php else: ?>
            <h2>Results for price range: $<?php echo number_format($min_price, 2); ?> &mdash; $<?php echo number_format($max_price, 2); ?></h2>
            <p class="count"><?php echo count($results); ?> book(s) found</p>
            <?php if (count($results) === 0): ?>
                <div class="empty">No books match this price range. <a href="secret_page.php">Try another search</a>.</div>
            <?php else: ?>
                <table class="result-table">
                    <thead>
                        <tr><th>Title</th><th>Category</th><th>Price</th><th>In Stock</th><th>Description</th><th></th></tr>
                    </thead>
                    <tbody>
                        <?php foreach ($results as $r): ?>
                            <tr>
                                <td><strong><?php echo htmlspecialchars($r['name']); ?></strong></td>
                                <td><?php echo htmlspecialchars($r['category']); ?></td>
                                <td>$<?php echo number_format((float)$r['price'], 2); ?></td>
                                <td><?php echo (int)$r['quantity']; ?></td>
                                <td><?php echo htmlspecialchars($r['description']); ?></td>
                                <td>
                                    <form action="handle_form_add_to_cart.php" method="post" class="inline-form">
                                        <input type="hidden" name="product_id" value="<?php echo (int)$r['id']; ?>">
                                        <button type="submit" class="btn-cart">Add to cart</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        <?php endif; ?>
    </main>
</body>
</html>
