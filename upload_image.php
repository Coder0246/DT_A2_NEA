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

// Handle image upload
if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["image"])){
    $user_id = $_SESSION["user_id"];
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["image"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
    
    // Check if image file is a actual image or fake image
    $check = getimagesize($_FILES["image"]["tmp_name"]);
    if($check !== false) {
        $uploadOk = 1;
    } else {
        echo "File is not an image.";
        $uploadOk = 0;
    }

    // Check file size
    if ($_FILES["image"]["size"] > 500000) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    // Allow certain file formats
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
    && $imageFileType != "gif" ) {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
    // if everything is ok, try to upload file
    } else {
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            // Update user's image path in the database
            $image_path = $target_file;
            $stmt = $pdo->prepare("UPDATE users SET image_path = :image_path WHERE id = :user_id");
            $stmt->bindParam(":image_path", $image_path, PDO::PARAM_STR);
            $stmt->bindParam(":user_id", $user_id, PDO::PARAM_INT);
            $stmt->execute();

            // Redirect to profile page after successful upload
            header("location: profile.php");
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }
}

// Close connection
unset($pdo);
?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <h2 class="mt-4">Upload Profile Image</h2>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="image">Select Image:</label>
                    <input type="file" class="form-control-file" id="image" name="image">
                </div>
                <button type="submit" class="btn btn-primary">Upload</button>
            </form>
        </div>
    </div>
</div>

<?php include_once("includes/footer.php"); ?>
