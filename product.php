<?php
/*
This page's PHP logic must run *before* the header is included,
so we can get the product name and set the $pageTitle.
*/

// 1. Get the ID from the URL
if(isset($_GET['id'])) {
    $product_id = $_GET['id'];
} else {
    die("Error: No product ID specified.");
}

// 2. Connect to the database
include 'db_connect.php';

// 3. Fetch the product
$sql = "SELECT * FROM products WHERE product_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $product = $result->fetch_assoc();
} else {
    die("Error: Product not found.");
}

// 4. SET THE DYNAMIC PAGE TITLE
$pageTitle = $product['name'] . " - DragonStone";

// 5. We're done, close the connection
$stmt->close();
$conn->close();

// 6. Now that all logic is done, include the header
include 'header.php';
?>

<main class="product-page-container">
    
    <a href="index.php" class="back-link">&larr; Back to Shop</a>

    <div class="product-detail-layout">

        <div class="product-image-column">
            <img src="<?php echo $product['image_url']; ?>" alt="<?php echo $product['name']; ?>" class="pdp-image">
        </div>

        <div class="product-details-column">
            <span class="pdp-category"><?php echo $product['category']; ?></span>
            <h1><?php echo $product['name']; ?></h1>
            <p class="pdp-description"><?php echo $product['description']; ?></p>
            
            <span class="pdp-stock">In Stock (<?php echo $product['stock_quantity']; ?>)</span>

            <div class="carbon-footprint-box">
                <span class="carbon-icon">Icon</span>
                <div class="carbon-text">
                    <strong>Carbon Footprint: <?php echo $product['carbon_footprint_value']; ?>kg CO2e</strong>
                    <span>Equivalent to <?php echo $product['carbon_footprint_value'] * 4.5; ?>km of driving</span>
                </div>
            </div>

            <div class="subscription-options">
                <div class="option-box active">
                    <label><input type="radio" name="purchase-type" checked> One-time Purchase</label>
                </div>
                <div class="option-box">
                    <label><input type="radio" name="purchase-type"> Subscribe & Save 10%</label>
                </div>
            </div>

            <div class="pdp-price">
                <h2>R <?php echo $product['price']; ?></h2>
            </div>
            
            <div class="pdp-actions">
                <div class="quantity-selector">
                    <button>-</button>
                    <span>1</span>
                    <button>+</button>
                </div>
                <button class="btn btn-primary btn-add-to-cart">Add to Cart</button>
            </div>

        </div>
    </div>
</main>

<?php
// 7. Include the reusable footer
include 'footer.php';
?>