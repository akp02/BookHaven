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

$categories = array();
$cat_q = mysqli_query($dbc, 'SELECT DISTINCT category FROM product ORDER BY category');
while ($row = mysqli_fetch_assoc($cat_q)) {
    $categories[] = $row['category'];
}
mysqli_close($dbc);

$status_msg = '';
if (isset($_GET['added'])) {
    $status_msg = 'Book "' . htmlspecialchars($_GET['added']) . '" has been successfully added to the catalog.';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>BookHaven &mdash; Admin Panel</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header class="topbar admin">
        <h1>BookHaven &mdash; Admin Panel</h1>
        <nav>
            <span class="welcome">Admin: <?php echo htmlspecialchars($_SESSION['user_name']); ?></span>
            <a href="admin_view_all_users.php">View All Users</a>
            <a href="admin_view_all_products.php">View All Products</a>
            <a href="logout.php" class="btn-logout">Log out</a>
        </nav>
    </header>
    <main class="storefront">
        <?php if ($status_msg !== ''): ?>
            <div class="message success"><?php echo $status_msg; ?></div>
        <?php endif; ?>
        <section class="search-card admin-card">
            <h2>Add a New Book</h2>
            <form action="handle_admin_add_new_product.php" method="post">
                <div class="field">
                    <label for="name">Title</label>
                    <input type="text" id="name" name="name" maxlength="100" required>
                </div>
                <div class="field">
                    <label for="category">Category</label>
                    <select name="category" id="category" required>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?php echo htmlspecialchars($cat); ?>"><?php echo htmlspecialchars($cat); ?></option>
                        <?php endforeach; ?>
                        <option value="Other">Other</option>
                    </select>
                </div>
                <div class="field-row">
                    <div class="field">
                        <label for="price">Price ($)</label>
                        <input type="number" id="price" name="price" step="0.01" min="0" required>
                    </div>
                    <div class="field">
                        <label for="quantity">Quantity</label>
                        <input type="number" id="quantity" name="quantity" min="0" required>
                    </div>
                </div>
                <div class="field">
                    <label for="description">Description</label>
                    <textarea id="description" name="description" rows="3" maxlength="500" required></textarea>
                </div>
                <button type="submit" class="btn-primary">Submit Book Information</button>
            </form>
        </section>
    </main>
</body>
</html>
