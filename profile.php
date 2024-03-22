<?php
session_start(); // Start the session
include_once("includes/header.php"); // Include header

// Check if the user is logged in, if not, redirect to login page
if(!isset($_SESSION["user_id"])){
    header("location: login.php");
    exit;
}

// Include database connection
include_once("db/connection.php");

// Retrieve user information from the database
$user_id = $_SESSION["user_id"];

$stmt = $pdo->prepare("SELECT * FROM users WHERE id = :user_id");
$stmt->bindParam(":user_id", $user_id, PDO::PARAM_INT);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Close connection
unset($pdo);
?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <h2 class="mt-4">Welcome, <?php echo $user['username']; ?>!</h2>
            <?php if(!empty($user['image_path'])): ?>
                <img src="<?php echo $user['image_path']; ?>" alt="User Image" class="img-fluid rounded-circle my-3" style="max-width: 200px;">
            <?php else: ?>
                <p class="lead">You don't have a profile image yet. Upload one now!</p>
            <?php endif; ?>
            <div class="row">
                <div class="col-md-6">
                    <a href="upload_image.php" class="btn btn-primary btn-block">Upload Image</a>
                </div>
                <div class="col-md-6">
                    <a href="create_review.php" class="btn btn-success btn-block">Create Review</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include_once("includes/footer.php"); ?>
