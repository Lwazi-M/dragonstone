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
$pageTitle = htmlspecialchars($product['name']) . " - DragonStone";

// 5. We're done with the database, so we close the connections.
$stmt->close();
$conn->close();

// 6. Now that all PHP logic is done, include the header.
// This will print the <head> section and the navigation bar.
include 'header.php';
?>

<main class="product-page-container">
    
    <a href="index.php" class="back-link">&larr; Back to Shop</a>

    <div class="product-detail-layout">

        <div class="product-image-column">
            <img src="<?php echo htmlspecialchars($product['image_url']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" class="pdp-image">
        </div>

        <div class="product-details-column">
            <span class="pdp-category"><?php echo htmlspecialchars($product['category']); ?></span>
            <h1><?php echo htmlspecialchars($product['name']); ?></h1>
            <p class="pdp-description"><?php echo nl2br(htmlspecialchars($product['description'])); ?></p>
            
            <span class="pdp-stock">In Stock (<?php echo htmlspecialchars($product['stock_quantity']); ?>)</span>

            <div class="carbon-footprint-box">
                <span class="carbon-icon">Icon</span> <div class="carbon-text">
                    <strong>Carbon Footprint: <?php echo htmlspecialchars($product['carbon_footprint_value']); ?>kg CO2e</strong>
                    <span>Equivalent to <?php echo htmlspecialchars($product['carbon_footprint_value'] * 4.5); ?>km of driving</span>
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

            <form action="cart_add.php" method="POST">
            
                <div class="pdp-price">
                    <h2>R <?php echo htmlspecialchars($product['price']); ?></h2>
                </div>
                
                <div class="pdp-actions">
                    <div class="quantity-selector">
                        <button type="button" id="quantity-minus">-</button>
                        
                        <input type="number" id="quantity-input" name="quantity" value="1" min="1" readonly>
                        
                        <button type="button" id="quantity-plus">+</button>
                    </div>

                    <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">
                    
                    <button type="submit" class="btn btn-primary btn-add-to-cart">Add to Cart</button>
                </div>

            </form> </div>
    </div>
</main>

    <script>
        // Wait for the page to load
        document.addEventListener("DOMContentLoaded", function() {
            // Get the three elements
            const minusButton = document.getElementById('quantity-minus');
            const plusButton = document.getElementById('quantity-plus');
            const quantityInput = document.getElementById('quantity-input');

            // Add a click listener to the '+' button
            plusButton.addEventListener('click', function() {
                // Convert the current value to a number and add 1
                let currentValue = parseInt(quantityInput.value);
                quantityInput.value = currentValue + 1;
            });

            // Add a click listener to the '-' button
            minusButton.addEventListener('click', function() {
                let currentValue = parseInt(quantityInput.value);
                // Only subtract if the value is more than 1
                if (currentValue > 1) {
                    quantityInput.value = currentValue - 1;
                }
            });
        });
    </script>

<?php
// 7. Include the reusable footer
// This prints the <footer> and closes the <body> and <html> tags.
include 'footer.php';
?>
