<?php
session_start(); // Start the session

include_once("includes/header.php"); 
include_once("db/connection.php"); // Include database connection

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if the user is logged in, if not, show an error message
if (!isset($_SESSION["user_id"])) {
    echo "<div class='alert alert-danger'>You must be logged in to add items to the cart.</div>";
    include_once("includes/footer.php");
    exit();
}

// Check if the product ID is provided in the query parameter
if (isset($_GET["product_id"])) {
    $product_id = $_GET["product_id"];
    $quantity = 1; // Default quantity to 1
    $user_id = $_SESSION["user_id"];

    // Debugging statement
    echo "Product ID: $product_id, Quantity: $quantity, User ID: $user_id<br>";

    // Add item to cart
    $stmt = $pdo->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (:user_id, :product_id, :quantity)
                           ON DUPLICATE KEY UPDATE quantity = quantity + :quantity");
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);
    $stmt->bindParam(':quantity', $quantity, PDO::PARAM_INT);

    try {
        $stmt->execute();
        echo "<div class='alert alert-success'>Item added to cart successfully!</div>";
    } catch (PDOException $e) {
        echo "<div class='alert alert-danger'>Error adding item to cart: " . $e->getMessage() . "</div>";
    }
} else {
    // Debugging statement
    echo "Product ID not provided.<br>";
}

include_once("includes/footer.php");
?>
