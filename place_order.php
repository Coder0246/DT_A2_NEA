<?php
session_start();
include_once("db/connection.php");

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION["user_id"])) {
    echo "<div class='alert alert-danger'>You must be logged in to place an order.</div>";
    exit();
}

$user_id = $_SESSION["user_id"];

// Fetch items from cart
$stmt = $pdo->prepare("SELECT product_id, quantity FROM cart WHERE user_id = :user_id");
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$cart_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (empty($cart_items)) {
    echo "<div class='alert alert-danger'>Your cart is empty.</div>";
    exit();
}

// Insert order into orders table
$stmt = $pdo->prepare("INSERT INTO orders (user_id, order_date) VALUES (:user_id, NOW())");
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$order_id = $pdo->lastInsertId();

// Insert order items into order_items table
$stmt = $pdo->prepare("INSERT INTO order_items (order_id, product_id, quantity) VALUES (:order_id, :product_id, :quantity)");
$stmt->bindParam(':order_id', $order_id, PDO::PARAM_INT);
$stmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);
$stmt->bindParam(':quantity', $quantity, PDO::PARAM_INT);

try {
    foreach ($cart_items as $item) {
        $product_id = $item['product_id'];
        $quantity = $item['quantity'];
        $stmt->execute();
    }

    // Clear the cart
    $stmt = $pdo->prepare("DELETE FROM cart WHERE user_id = :user_id");
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();

    echo "<div class='alert alert-success'>Order placed successfully!</div>";
} catch (PDOException $e) {
    echo "<div class='alert alert-danger'>Error placing order: " . $e->getMessage() . "</div>";
}
?>