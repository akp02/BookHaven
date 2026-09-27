<?php
session_start();
if (!isset($_SESSION['user_name'])) {
    header('Location: login_form.php');
    exit;
}
require_once 'mysqli_connect.php';

$categories = array();
$cat_q = mysqli_query($dbc, 'SELECT DISTINCT category FROM product ORDER BY category');
while ($row = mysqli_fetch_assoc($cat_q)) {
    $categories[] = $row['category'];
}
mysqli_close($dbc);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>BookHaven &mdash; Storefront</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header class="topbar">
        <h1>BookHaven</h1>
        <nav>
            <span class="welcome">Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?></span>
            <a href="view_cart.php">View Cart</a>
            <a href="update_password.php">Change Password</a>
            <a href="logout.php" class="btn-logout">Log out</a>
        </nav>
    </header>
    <main class="storefront">
        <section class="search-card">
            <h2>Search by Category</h2>
            <form action="handle_form_searchtype.php" method="post">
                <div class="field">
                    <label for="category">Category</label>
                    <select name="category" id="category" required>
                        <option value="">&mdash; Choose a category &mdash;</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?php echo htmlspecialchars($cat); ?>"><?php echo htmlspecialchars($cat); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="submit" class="btn-primary">Search</button>
            </form>
        </section>
        <section class="search-card">
            <h2>Search by Price Range</h2>
            <form action="handle_form_searchvaluerange.php" method="post">
                <div class="field-row">
                    <div class="field">
                        <label for="min_price">Min ($)</label>
                        <input type="number" id="min_price" name="min_price" step="0.01" min="0" required>
                    </div>
                    <div class="field">
                        <label for="max_price">Max ($)</label>
                        <input type="number" id="max_price" name="max_price" step="0.01" min="0" required>
                    </div>
                </div>
                <button type="submit" class="btn-primary">Search</button>
            </form>
        </section>
        <section class="search-card">
            <h2>Search by Keyword</h2>
            <form action="handle_form_searchkeyword.php" method="post">
                <div class="field">
                    <label for="keyword">Keyword</label>
                    <input type="text" id="keyword" name="keyword" placeholder="e.g. history, dragon, mystery" maxlength="100" required>
                </div>
                <button type="submit" class="btn-primary">Search</button>
            </form>
        </section>
    </main>
</body>
</html>
