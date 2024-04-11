<?php
include_once("includes/header.php");
include_once("db/connection.php");

// Pagination setup
$limit = 10;
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$start = ($page - 1) * $limit;

// Fetch products
$stmt_products = $pdo->prepare("SELECT * FROM products LIMIT :start, :limit");
$stmt_products->bindParam(':start', $start, PDO::PARAM_INT);
$stmt_products->bindParam(':limit', $limit, PDO::PARAM_INT);
$stmt_products->execute();
$products = $stmt_products->fetchAll(PDO::FETCH_ASSOC);

// Fetch site reviews
$stmt_site_reviews = $pdo->prepare("SELECT * FROM site_reviews ORDER BY review_date DESC LIMIT 5");
$stmt_site_reviews->execute();
$siteReviews = $stmt_site_reviews->fetchAll(PDO::FETCH_ASSOC);

// Fetch product reviews
$stmt_product_reviews = $pdo->prepare("SELECT pr.*, u.username FROM product_reviews pr JOIN users u ON pr.user_id = u.id ORDER BY pr.review_date DESC LIMIT 5");
$stmt_product_reviews->execute();
$productReviews = $stmt_product_reviews->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container mt-5">
    <h2>Products</h2>
    <div class="row">
        <?php foreach ($products as $product): ?>
            <div class="col-md-4 mb-4">
                <div class="card">
                    <img src="images/<?php echo $product['image']; ?>" class="card-img-top" alt="<?php echo $product['name']; ?>">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $product['name']; ?></h5>
                        <p class="card-text"><?php echo $product['description'];?><br>
                        price: £ <?php echo $product['price'];?></p>
                        

                        <a href="review_product.php?id=<?php echo $product['id']; ?>" class="btn btn-primary">Review</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    
    <!-- Pagination -->
    <!-- Add pagination logic here -->

    <hr>

    <h2>Site Reviews</h2>
    <div id="carouselSiteReviews" class="carousel slide" data-ride="carousel">
        <!-- Site reviews carousel code here -->
    </div>

    <hr>

    <h2>Product Reviews</h2>
    <?php foreach ($productReviews as $review): ?>
        <div class="card mb-3">
            <div class="card-body">
                <h5 class="card-title"><?php echo $review['username']; ?></h5>
                <p class="card-text"><?php echo $review['review_text']; ?></p>
                <small class="text-muted"><?php echo date('F j, Y', strtotime($review['review_date'])); ?></small>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<?php include_once("includes/footer.php"); ?>
