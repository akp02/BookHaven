<?php
session_start();
if (!isset($_SESSION['user_name'])) {
    header('Location: login_form.php');
    exit;
}
require_once 'mysqli_connect.php';

if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

if (isset($_POST['remove_index'])) {
    $idx = (int)$_POST['remove_index'];
    if (isset($_SESSION['cart'][$idx])) {
        array_splice($_SESSION['cart'], $idx, 1);
    }
}
if (isset($_POST['clear_cart'])) {
    $_SESSION['cart'] = array();
}

$items = array();
$total = 0.0;
foreach ($_SESSION['cart'] as $idx => $pid) {
    $stmt = mysqli_prepare($dbc, 'SELECT id, name, category, price FROM product WHERE id = ?');
    mysqli_stmt_bind_param($stmt, 'i', $pid);
    mysqli_stmt_execute($stmt);
    $rs = mysqli_stmt_get_result($stmt);
    if ($row = mysqli_fetch_assoc($rs)) {
        $row['cart_index'] = $idx;
        $items[] = $row;
        $total += (float)$row['price'];
    }
    mysqli_stmt_close($stmt);
}
mysqli_close($dbc);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>BookHaven &mdash; Cart</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header class="topbar">
        <h1>BookHaven &mdash; Your Cart</h1>
        <nav>
            <a href="secret_page.php">&larr; Continue shopping</a>
            <a href="logout.php" class="btn-logout">Log out</a>
        </nav>
    </header>
    <main class="results">
        <?php if (count($items) === 0): ?>
            <div class="empty">Your cart is empty. <a href="secret_page.php">Find some books</a>.</div>
        <?php else: ?>
            <p class="count"><?php echo count($items); ?> item(s) in your cart</p>
            <table class="result-table">
                <thead>
                    <tr><th>Title</th><th>Category</th><th>Price</th><th></th></tr>
                </thead>
                <tbody>
                    <?php foreach ($items as $it): ?>
                        <tr>
                            <td><strong><?php echo htmlspecialchars($it['name']); ?></strong></td>
                            <td><?php echo htmlspecialchars($it['category']); ?></td>
                            <td>$<?php echo number_format((float)$it['price'], 2); ?></td>
                            <td>
                                <form action="view_cart.php" method="post" class="inline-form">
                                    <input type="hidden" name="remove_index" value="<?php echo (int)$it['cart_index']; ?>">
                                    <button type="submit" class="btn-remove">Remove</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <tr class="total-row">
                        <td colspan="2"><strong>Total</strong></td>
                        <td colspan="2"><strong>$<?php echo number_format($total, 2); ?></strong></td>
                    </tr>
                </tbody>
            </table>
            <form action="view_cart.php" method="post" class="inline-form clear-cart-form">
                <input type="hidden" name="clear_cart" value="1">
                <button type="submit" class="btn-remove">Clear cart</button>
            </form>
            
            <a href="checkout.php" class="btn-primary">Proceed to Checkout</a>
            
        <?php endif; ?>
    </main>
</body>
</html>
