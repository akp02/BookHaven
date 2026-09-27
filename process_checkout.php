<?php
session_start();

if (!isset($_SESSION['user_name'])) {
    header('Location: login_form.php');
    exit;
}

if (
    !isset($_SESSION['cart']) ||
    !is_array($_SESSION['cart']) ||
    count($_SESSION['cart']) === 0
) {
    header('Location: view_cart.php');
    exit;
}

require_once 'mysqli_connect.php';

mysqli_begin_transaction($dbc);

try {

    // Get the logged-in user's database ID
    $stmt = mysqli_prepare(
        $dbc,
        "SELECT id
         FROM users
         WHERE user_name = ?"
    );

    mysqli_stmt_bind_param(
        $stmt,
        's',
        $_SESSION['user_name']
    );

    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    $user = mysqli_fetch_assoc($result);

    mysqli_stmt_close($stmt);

    if (!$user) {
        throw new Exception('User account could not be found.');
    }

    $user_id = (int)$user['id'];

    // Convert repeated product IDs into quantities
    $product_counts = array_count_values($_SESSION['cart']);

    $items = array();
    $total = 0.00;

    // Read product data again from the database
    foreach ($product_counts as $product_id => $cart_quantity) {

        $stmt = mysqli_prepare(
            $dbc,
            "SELECT id, name, price, quantity
             FROM product
             WHERE id = ?"
        );

        mysqli_stmt_bind_param(
            $stmt,
            'i',
            $product_id
        );

        mysqli_stmt_execute($stmt);

        $result = mysqli_stmt_get_result($stmt);

        $product = mysqli_fetch_assoc($result);

        mysqli_stmt_close($stmt);

        if (!$product) {
            throw new Exception(
                "A product in your cart no longer exists."
            );
        }

        if ((int)$product['quantity'] < $cart_quantity) {
            throw new Exception(
                "Not enough inventory for " .
                $product['name'] . "."
            );
        }

        $subtotal =
            (float)$product['price'] * $cart_quantity;

        $total += $subtotal;

        $items[] = array(
            'id' => (int)$product['id'],
            'name' => $product['name'],
            'price' => (float)$product['price'],
            'quantity' => $cart_quantity
        );
    }

    // Create the order
    $status = 'completed';

    $stmt = mysqli_prepare(
        $dbc,
        "INSERT INTO orders
         (user_id, total_amount, status)
         VALUES (?, ?, ?)"
    );

    mysqli_stmt_bind_param(
        $stmt,
        'ids',
        $user_id,
        $total,
        $status
    );

    mysqli_stmt_execute($stmt);

    $order_id = mysqli_insert_id($dbc);

    mysqli_stmt_close($stmt);

    // Insert order items and reduce inventory
    foreach ($items as $item) {

        $stmt = mysqli_prepare(
            $dbc,
            "INSERT INTO order_items
             (order_id, product_id, quantity, price_at_purchase)
             VALUES (?, ?, ?, ?)"
        );

        mysqli_stmt_bind_param(
            $stmt,
            'iiid',
            $order_id,
            $item['id'],
            $item['quantity'],
            $item['price']
        );

        mysqli_stmt_execute($stmt);

        mysqli_stmt_close($stmt);

        $stmt = mysqli_prepare(
            $dbc,
            "UPDATE product
             SET quantity = quantity - ?
             WHERE id = ?"
        );

        mysqli_stmt_bind_param(
            $stmt,
            'ii',
            $item['quantity'],
            $item['id']
        );

        mysqli_stmt_execute($stmt);

        mysqli_stmt_close($stmt);
    }

    // Everything succeeded
    mysqli_commit($dbc);

    $_SESSION['cart'] = array();
    $_SESSION['last_order_id'] = $order_id;

    mysqli_close($dbc);

    header(
        'Location: order_confirmation.php'
    );

    exit;

} catch (Throwable $error) {

    mysqli_rollback($dbc);
    mysqli_close($dbc);

    $checkout_error = $error->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BookHaven - Checkout Error</title>
    <link rel="stylesheet" href="style.css?v=3">
</head>

<body>

<header>
    <h1>BookHaven — Checkout</h1>
</header>

<main class="results">

    <h2 class="checkout-error-title">Unable to Complete Order</h2>

    <p>
        <?php echo htmlspecialchars($checkout_error); ?>
    </p>

    <p>
        Your order has not been placed. Please return to your cart
        and adjust the quantity.
    </p>

    <a href="view_cart.php" class="btn-primary checkout-error-btn">
        Return to Cart
    </a>

</main>

</body>
</html>