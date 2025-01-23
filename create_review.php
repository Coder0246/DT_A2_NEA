<?php
session_start();
include_once("db/connection.php"); 
include_once("includes/header.php"); 

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["review_text"])) {
    $review_text = $_POST["review_text"];
    $user_id = $_SESSION["user_id"]; 

    // Prepare the SQL INSERT statement
    $stmt = $pdo->prepare("INSERT INTO site_reviews (review_text, review_date, user_id) 
                           VALUES (:review_text, NOW(), :user_id)");
    $stmt->bindParam(':review_text', $review_text, PDO::PARAM_STR);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);

    try {
        $stmt->execute();
        echo "<div class='alert alert-success'>Review submitted successfully!</div>";
    } catch (PDOException $e) {
        echo "<div class='alert alert-danger'>Error submitting review: " . $e->getMessage() . "</div>";
    }
}
?>

<div class="container mt-5">
    <h2>Create a Site Review</h2>
    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <div class="mb-3">
            <label for="review_text" class="form-label">Your Review:</label>
            <textarea class="form-control" name="review_text" rows="4" cols="50" required></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Submit Review</button>
    </form>
</div>

<?php include_once("includes/footer.php"); ?>