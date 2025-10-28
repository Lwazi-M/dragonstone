<?php
/*
This is the Product Detail Page (PDP).
Its job is to:
1. Get the 'id' from the URL (e.g., product.php?id=1)
2. Connect to the database.
3. Fetch *only* the product with that ID.
4. Display its details.
*/

// 1. Get the ID from the URL
// We use $_GET to read from the URL.
// We check if it 'is set' (isset). If not, we'll stop.
if(isset($_GET['id'])) {
    $product_id = $_GET['id'];
} else {
    // If no ID is provided, stop the page and show an error.
    die("Error: No product ID specified.");
}

// 2. Connect to the database
include 'db_connect.php';

// 3. Fetch the product
// We use a "prepared statement" (the ?) to prevent SQL injection.
// This is safer.
$sql = "SELECT * FROM products WHERE product_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $product_id); // "i" means the ID is an integer
$stmt->execute();
$result = $stmt->get_result();

// Check if we found a product
if ($result->num_rows > 0) {
    $product = $result->fetch_assoc();
} else {
    die("Error: Product not found.");
}

// We're done with the database for now
$stmt->close();
$conn->close();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?php echo $product['name']; ?> - DragonStone</title>

    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="product.css"> 
</head>
<body>

    <header>
        <nav class="navbar">
            <a href="index.php" class="nav-logo">DragonStone</a>
            <ul class="nav-menu">
                <li class="nav-item"><a href="index.php" class="nav-link">Shop</a></li>
                <li class="nav-item"><a href="community.php" class="nav-link">Community</a></li>
                <li class="nav-item"><a href="about.php" class="nav-link">About</a></li>
            </ul>
            <div class="nav-icons">
                <a href="login.php" class="icon-link">UserIcon</a>
                <a href="cart.php" class="icon-link">CartIcon</a>
            </div>
        </nav>
    </header>

    <main class="product-page-container">
        
        <a href="index.php" class="back-link">&larr; Back to Shop</a> <div class="product-detail-layout">

            <div class="product-image-column">
                <img src="<?php echo $product['image_url']; ?>" alt="<?php echo $product['name']; ?>" class="pdp-image">
            </div>

            <div class="product-details-column">
                <span class="pdp-category"><?php echo $product['category']; ?></span>
                <h1><?php echo $product['name']; ?></h1>
                <p class="pdp-description"><?php echo $product['description']; ?></p>
                
                <span class="pdp-stock">In Stock (<?php echo $product['stock_quantity']; ?>)</span>

                <div class="carbon-footprint-box">
                    <span class="carbon-icon">Icon</span> <div class="carbon-text">
                        <strong>Carbon Footprint: <?php echo $product['carbon_footprint_value']; ?>kg CO2e</strong>
                        <span>Equivalent to <?php echo $product['carbon_footprint_value'] * 4.5; ?>km of driving</span>
                    </div>
                </div>

                <div class="subscription-options">
                    <div class="option-box active">
                        <label>
                            <input type="radio" name="purchase-type" checked>
                            One-time Purchase
                        </label>
                    </div>
                    <div class="option-box">
                        <label>
                            <input type="radio" name="purchase-type">
                            Subscribe & Save 10%
                        </label>
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

    <footer>
        <p>&copy; 2025 DragonStone. All rights reserved.</p>
    </footer>

</body>
</html>