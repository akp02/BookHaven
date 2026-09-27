<?php
session_start();

if (!isset($_SESSION['user_name'])) {
    header('Location: login_form.php');
    exit;
}

if (!isset($_SESSION['last_order_id'])) {
    header('Location: secret_page.php');
    exit;
}

$order_id = (int)$_SESSION['last_order_id'];

// Optional: prevent refresh from reusing the same confirmation state
unset($_SESSION['last_order_id']);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>BookHaven — Order Confirmed</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

<header class="topbar">
    <h1>BookHaven — Order Confirmed</h1>

    <nav>
        <a href="secret_page.php">Continue Shopping</a>
        <a href="logout.php" class="btn-logout">Log out</a>
    </nav>
</header>

<main class="results">

    <h2>Thank you for your order.</h2>

    <p>
        Your order has been successfully placed.
    </p>

    <p>
        <strong>Order #<?php echo $order_id; ?></strong>
    </p>

   <div class="continue-shopping-area">
    <a href="secret_page.php" class="btn-primary">
        Continue Shopping
    </a>
</div>
    

</main>

</body>
</html>