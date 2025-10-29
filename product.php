<?php
/*
==================================================================
FILE: product.php
PURPOSE: This page displays the details for a *single* product.
HOW IT WORKS:
1. It reads a product "id" from the URL (e.g., product.php?id=1).
2. It "fetches" only that one product's data from the database.
3. It uses that data to set a custom $pageTitle.
4. It includes the 'header.php' template.
5. It displays all the product's details (image, price, etc.).
6. It includes the 'footer.php' template.
==================================================================
*/

/*
------------------------------------------------------------------
SECTION 1: PHP Data Fetching
------------------------------------------------------------------
This PHP block must run *before* the header is included,
so we can get the product name and set the $pageTitle variable.
*/

// 1. Get the 'id' from the URL
// 'isset($_GET['id'])' checks if 'id=' exists in the URL.
if(isset($_GET['id'])) {
    // If it exists, store it in a variable.
    $product_id = $_GET['id'];
} else {
    // If no 'id=' is found, stop the page with an error.
    // 'die()' is a PHP function that stops all code execution.
    die("Error: No product ID specified.");
}

// 2. Connect to the database
// This gives us the '$conn' variable.
include 'db_connect.php';

// 3. Fetch the product using a SECURE method
// We use a "Prepared Statement" (the '?') to prevent
// a type of hacking called "SQL Injection".
$sql = "SELECT * FROM products WHERE product_id = ?";

// 'prepare' gets the database ready for the question.
$stmt = $conn->prepare($sql);

// 'bind_param' securely attaches our variable to the '?'.
// "i" tells the database that the variable is an Integer (a number).
$stmt->bind_param("i", $product_id);

// 'execute' runs the query.
$stmt->execute();

// 'get_result' gets the "answer" back from the database.
$result = $stmt->get_result();

// Check if the database found exactly 1 product
if ($result->num_rows > 0) {
    // If yes, 'fetch_assoc()' pulls all its data into a
    // variable called '$product'.
    $product = $result->fetch_assoc();
} else {
    // If no product was found (e.g., bad ID), stop the page.
    die("Error: Product not found.");
}

// 4. SET THE DYNAMIC PAGE TITLE
// We use the 'name' field from the $product data we just fetched.
// This $pageTitle variable will be used by header.php.
$pageTitle = $product['name'] . " - DragonStone";

// 5. We're done with the database, so we close the connections.
$stmt->close();
$conn->close();

// 6. Now that all PHP logic is done, include the header.
// This will print the <head> section and the navigation bar.
include 'header.php';
?>

<!--
The <main> tag holds all the content that is *unique* to this page.
-->
<main class="product-page-container">
    
    <!-- '&larr;' is the HTML code for the ← arrow -->
    <a href="index.php" class="back-link">&larr; Back to Shop</a>

    <!-- This 'div' holds our two-column layout (image and text) -->
    <div class="product-detail-layout">

        <!-- Left Column (Image) -->
        <div class="product-image-column">
            <!-- We 'echo' the product's image URL and name
                 to fill the 'src' (source) and 'alt' (alternative text) -->
            <img src="<?php echo $product['image_url']; ?>" alt="<?php echo $product['name']; ?>" class="pdp-image">
        </div>

        <!-- Right Column (Details) -->
        <div class="product-details-column">
            <!-- We 'echo' all the product data into the HTML -->
            <span class="pdp-category"><?php echo $product['category']; ?></span>
            <h1><?php echo $product['name']; ?></h1>
            <p class="pdp-description"><?php echo $product['description']; ?></p>
            
            <span class="pdp-stock">In Stock (<?php echo $product['stock_quantity']; ?>)</span>

            <!-- 
            =========================================
            CRITICAL FEATURE: Carbon Footprint
            =========================================
            This section displays the 'carbon_footprint_value' from the database.
            This is a key requirement from Deliverable 1.
            [cite: ITECA3-34 – Project – Deliverable 1 – Project Proposal Block 3 2025 (V1.0)READY.docx, WhatsApp Image 2025-10-20 at 16.19.45_caff32fb.jpg]
            -->
            <div class="carbon-footprint-box">
                <span class="carbon-icon">Icon</span> <!-- We can replace this with a real icon later -->
                <div class="carbon-text">
                    <strong>Carbon Footprint: <?php echo $product['carbon_footprint_value']; ?>kg CO2e</strong>
                    <!-- This is a simple (dummy) calculation for display -->
                    <span>Equivalent to <?php echo $product['carbon_footprint_value'] * 4.5; ?>km of driving</span>
                </div>
            </div>

            <!-- 
            =========================================
            CRITICAL FEATURE: Subscription Options
            =========================================
            This section shows the subscription option, a key requirement.
            [cite: ITECA3-34 – Project – Deliverable 1 – Project Proposal Block 3 2025 (V1.0)READY.docx]
            -->
            <div class="subscription-options">
                <div class="option-box active">
                    <!-- 'checked' makes this the default selected option -->
                    <label><input type="radio" name="purchase-type" checked> One-time Purchase</label>
                </div>
                <div class="option-box">
                    <label><input type="radio" name="purchase-type"> Subscribe & Save 10%</label>
                </div>
            </div>

            <div class="pdp-price">
                <h2>R <?php echo $product['price']; ?></h2>
            </div>
            
            <!-- This section holds the "Add to Cart" and quantity buttons -->
            <div class="pdp-actions">
                <div class="quantity-selector">
                    <button>-</button>
                    <span>1</span>
                    <button>+</button>
                </div>
                <!-- 
                This button doesn't do anything *yet*.
                We need to build the Cart functionality for it.
                -->
                <button class="btn btn-primary btn-add-to-cart">Add to Cart</button>
            </div>

        </div>
    </div>
</main>

<?php
// 7. Include the reusable footer
// This prints the <footer> and closes the <body> and <html> tags.
include 'footer.php';
?>
