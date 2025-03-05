<?php
session_start(); // Start the session

include_once("db/connection.php"); // Include database connection
include_once("db/connection.php");
// Check if the user is logged in, if not, redirect to login page
if (!isset($_SESSION["user_id"])) {
    header("location: login.php");
    exit;
}

// Initialize cart items array
$cart_items = [];

// Check if the cart is not empty
if (!empty($_SESSION['cart'])) {
    // Fetch the products from the database based on the cart items
    $placeholders = implode(',', array_fill(0, count($_SESSION['cart']), '?'));
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id IN ($placeholders)");
    $stmt->execute($_SESSION['cart']);
    $cart_items = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Close connection
unset($pdo);?>

<!DOCTYPE html>
<html>
<head>
    <title>Cart</title>
    </head>
<body>

<?php include_once("includes/header.php");?>

<div class="container mt-5">
    <h2>Your Cart</h2>

    <?php if (!empty($cart_items)):?>
        <table class="table">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Total</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($cart_items as $item):?>
                    <tr>
                        <td><?php echo $item['name'];?></td>
                        <td><?php echo $item['price'];?></td>
                        <td>
                            1
                        </td>
                        <td><?php echo $item['price'];?></td>
                        <td>
                            <a href="remove_from_cart.php?id=<?php echo $item['id'];?>" class="btn btn-danger btn-sm">Remove</a>
                        </td>
                    </tr>
                <?php endforeach;?>
            </tbody>
        </table>

        <a href="checkout.php" class="btn btn-success">Checkout</a>

    <?php else:?>
        <p>Your cart is empty.</p>
    <?php endif;?>
</div>

<?php include_once("includes/footer.php");?>

</body>
</html>