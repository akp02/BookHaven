# 📚 BookHaven

BookHaven is a full-stack bookstore web application built with PHP and MySQL. The application allows users to create accounts, browse and search a book catalog, manage a shopping cart, and complete simulated orders. Administrative users can also manage products and users.

The project was originally developed as a university web development project and was later expanded with a complete checkout and order-processing system.

## Features

### User Features
- User registration and authentication
- Secure session-based access
- Browse available books
- Search books by keyword
- Filter books by category
- Search by price range
- Add products to a shopping cart
- Remove products or clear the cart
- Review an order before checkout
- Complete simulated purchases
- Order confirmation with generated order numbers
- Password update functionality

### Checkout & Inventory
- Server-side order processing
- MySQL transaction-based checkout
- Inventory availability validation
- Automatic inventory updates after purchases
- Order and order-item persistence
- Quantity handling for duplicate cart items
- Transaction rollback if checkout fails
- User-friendly out-of-stock handling

### Administrative Features
- Administrative account access
- View registered users
- View product inventory
- Add new products
- Edit existing products
- Delete products

## Technologies

- PHP
- MySQL
- HTML5
- CSS3
- MySQLi
- SQL
- PHP Sessions
- Git / GitHub

## Database Structure

BookHaven uses a relational MySQL database containing tables for:

- `users` — user accounts and administrator status
- `product` — bookstore inventory and product information
- `orders` — completed customer orders
- `order_items` — individual products associated with each order

Foreign-key relationships connect orders to users and order items to their corresponding orders and products.

## Project Structure

Key files include:

- `login_form.php` — user login interface
- `signup_form.php` — account registration
- `secret_page.php` — authenticated bookstore interface
- `view_cart.php` — shopping cart
- `checkout.php` — order review and checkout
- `process_checkout.php` — server-side order processing
- `order_confirmation.php` — successful order confirmation
- `secret_page_admin.php` — administrative interface
- `schema.sql` — database schema and sample data
- `style.css` — application styling
- `mysqli_connect.example.php` — example database configuration

## Local Setup

1. Clone the repository.
2. Create a MySQL database.
3. Import `schema.sql`.
4. Copy `mysqli_connect.example.php` and rename the copy to:

   `mysqli_connect.php`

5. Replace the placeholder values with your MySQL database credentials.
6. Run the application through a PHP-compatible web server.

The real `mysqli_connect.php` file is excluded from version control to prevent database credentials from being published.

## What I Learned

Building and expanding BookHaven provided hands-on experience with full-stack web development, relational database design, authentication, session management, server-side validation, SQL queries, transactional order processing, inventory management, and maintaining a multi-page PHP application.

A major expansion of the original project involved designing the checkout workflow, including persistent orders, order-item relationships, inventory validation, database transactions, rollback behavior, and order confirmation.

## Author

**Alan Peter**

Information Science & Technology  
University of Wisconsin–Milwaukee
