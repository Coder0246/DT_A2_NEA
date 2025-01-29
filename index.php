<?php
include_once("includes/header.php");
include_once("db/connection.php");

// Pagination setup
// new stuff exciting!!!!!

//new code in college
//blah
//blah 3

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
        <div class="col-md-4">
            <div class="card mb-3">
                <img src="images/<?php echo htmlspecialchars($product['image']); ?>" class="card-img-top"
                    alt="<?php echo htmlspecialchars($product['name']); ?>">
                <div class="card-body">
                    <h5 class="card-title"><?php echo htmlspecialchars($product['name']); ?></h5>
                    <p class="card-text"><?php echo htmlspecialchars($product['description']); ?><br>
                        price: £ <?php echo htmlspecialchars($product['price']); ?></p>

                    <a href="add_to_cart.php?product_id=<?php echo htmlspecialchars($product['id']); ?>" class="btn btn-primary">Add to cart</a>
                    <a href="create_product_review.php?product_id=<?php echo htmlspecialchars($product['id']); ?>" class="btn btn-primary">Review</a>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- Pagination -->
<!-- Add pagination logic here -->

<div class="container mt-5">
    <h2>Site Reviews</h2>
    <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-inner">
            <?php foreach ($siteReviews as $key => $review): ?>
            <div class="carousel-item <?php echo $key === 0 ? 'active' : ''; ?>">
                <h3><?php echo htmlspecialchars($review['review_text']); ?></h3>
            </div>
            <?php endforeach; ?>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators"
            data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators"
            data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>
</div>

<div class="container mt-5">
    <h2>Product Reviews</h2>
    <div class="row">
        <?php foreach ($productReviews as $review): ?>
        <div class="col-md-4">
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title"><?php echo htmlspecialchars($review['username']); ?></h5>
                    <p class="card-text"><?php echo htmlspecialchars($review['review_text']); ?></p>
                    <?php if (!empty($review['review_image'])): ?>
                    <img src="<?php echo htmlspecialchars($review['review_image']); ?>" class="card-img-top" alt="Review Image">
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>


<!-- all user images -->
<?php
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

// Fetch user images
$stmt_user_images = $pdo->query("SELECT profile_image FROM users");
$user_images = $stmt_user_images->fetchAll(PDO::FETCH_COLUMN);
?>

<!-- Your HTML code for products, site reviews, and product reviews -->

<!-- Display all user images -->
<div class="container mt-5">
    <h2>All User Images</h2>
    <div class="row">
        <?php foreach ($user_images as $image): ?>
        <div class="col-md-3">
            <img src="uploads/<?php echo htmlspecialchars($image); ?>" alt="User Image" class="img-fluid">
        </div>
        <?php endforeach; ?>
    </div>
</div>

<?php include_once("includes/footer.php"); ?>