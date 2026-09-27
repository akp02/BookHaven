<?php
session_start();

if (!isset($_SESSION['user_name'])) {
    header('Location: login_form.php');
    exit;
}

if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart']) || count($_SESSION['cart']) === 0) {
    header('Location: view_cart.php');
    exit;
}

require_once 'mysqli_connect.php';

$product_counts = array_count_values($_SESSION['cart']);

$items = array();
$total = 0.00;

foreach ($product_counts as $product_id => $cart_quantity) {

    $stmt = mysqli_prepare(
        $dbc,
        "SELECT id, name, category, price, quantity
         FROM product
         WHERE id = ?"
    );

    mysqli_stmt_bind_param($stmt, 'i', $product_id);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {

        $row['cart_quantity'] = $cart_quantity;
        $row['subtotal'] =
            (float)$row['price'] * $cart_quantity;

        $items[] = $row;
        $total += $row['subtotal'];
    }

    mysqli_stmt_close($stmt);
}

mysqli_close($dbc);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>BookHaven — Checkout</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

<header class="topbar">
    <h1>BookHaven — Checkout</h1>

    <nav>
        <a href="view_cart.php">← Back to Cart</a>
        <a href="logout.php" class="btn-logout">Log out</a>
    </nav>
</header>

<main class="results">

    <h2>Order Summary</h2>

    <table class="result-table">

        <thead>
        <tr>
            <th>Title</th>
            <th>Price</th>
            <th>Quantity</th>
            <th>Subtotal</th>
        </tr>
        </thead>

        <tbody>

        <?php foreach ($items as $item): ?>

            <tr>

                <td>
                    <strong>
                        <?php
                        echo htmlspecialchars($item['name']);
                        ?>
                    </strong>
                </td>

                <td>
                    $<?php
                    echo number_format(
                        (float)$item['price'],
                        2
                    );
                    ?>
                </td>

                <td>
                    <?php
                    echo (int)$item['cart_quantity'];
                    ?>
                </td>

                <td>
                    $<?php
                    echo number_format(
                        (float)$item['subtotal'],
                        2
                    );
                    ?>
                </td>

            </tr>

        <?php endforeach; ?>

        <tr class="total-row">

            <td colspan="3">
                <strong>Total</strong>
            </td>

            <td>
                <strong>
                    $<?php
                    echo number_format($total, 2);
                    ?>
                </strong>
            </td>

        </tr>

        </tbody>

    </table>

    <form action="process_checkout.php" method="post">

        <button type="submit" class="btn-primary">
            Place Order
        </button>

    </form>

</main>

</body>
</html>