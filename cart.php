<?php
/*
==================================================================
FILE: cart.php
PURPOSE: This page displays all items in the shopping cart
         and also shows the checkout/shipping form.
==================================================================
*/

// Set the page title
$pageTitle = "Shopping Cart - DragonStone";

// ==========================================================
// *** BUG FIX ***
// We MUST include the header *first* so that session_start()
// is called.
// ==========================================================
include 'header.php';

// NOW we can safely access the $_SESSION variable.
$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : array();

// Initialize total variables
$subtotal = 0;
$shipping_cost = 50.00; // A flat rate, as seen in the Figma design

/*
------------------------------------------------------------------
SECTION 1: Calculate Totals
------------------------------------------------------------------
*/
if (!empty($cart)) {
    // Loop through each item in the cart array
    foreach ($cart as $product_id => $item) {
        // Calculate the total for this line item
        // (e.g., price of $10 * quantity of 3 = $30)
        $line_total = $item['price'] * $item['quantity'];
        
        // Add it to the grand subtotal
        $subtotal += $line_total;
    }
}

// Calculate the final total (Subtotal + Shipping)
$total = $subtotal + $shipping_cost;

?>

<!-- 'main' tag holds the unique content for *this* page -->
<main class="page-container">
    <div class="page-header">
        <h1>Shopping Cart</h1>
    </div>

    <!-- 
    We create one big form that includes the shipping details
    and the order summary. When the user clicks "Proceed to Payment",
    it sends all this data to 'order_process.php' (which we will
    build later if we want to complete the checkout).
    -->
    <form action="order_process.php" method="POST" class="cart-layout">

        <!-- 
        =========================================
        LEFT COLUMN: Cart Items & Shipping
        =========================================
        -->
        <div class="cart-left-column">
            
            <a href="index.php" class="back-link" style="margin-bottom: 2rem;">&larr; Continue Shopping</a>

            <!-- This is the list of items in the cart -->
            <div class="cart-items-list">
                <?php
                // Check if the cart array is empty
                if (empty($cart)) {
                    // If it is, show a message
                    echo "<p>Your cart is empty.</p>";
                } else {
                    // If it's NOT empty, loop through the cart
                    // and display each item
                    foreach ($cart as $product_id => $item) {
                ?>
                        <!-- This is a single row for one cart item -->
                        <div class="cart-item">
                            
                            <?php $image_url = isset($item['image_url']) ? $item['image_url'] : 'images/placeholder.jpg'; ?>
                            
                            <img src="<?php echo htmlspecialchars($image_url); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>" class="cart-item-image">
                            
                            <div class="cart-item-details">
                                <h3><?php echo htmlspecialchars($item['name']); ?></h3>
                                <p>Quantity: <?php echo $item['quantity']; ?></p>
                                <strong>R <?php echo number_format($item['price'] * $item['quantity'], 2); ?></strong>
                            </div>
                            
                            <a href="cart_remove.php?id=<?php echo $product_id; ?>" class="cart-remove-link" title="Remove Item">
                                &#128465; <!-- This is a trash can emoji icon -->
                            </a>
                        </div>
                <?php
                    } // End foreach loop
                } // End if/else
                ?>
            </div>

            <hr class="cart-divider">

            <!-- Shipping Details Form (from Figma) -->
            <div class="shipping-form">
                <h2>Shipping Details</h2>
                <div class="form-group-row">
                    <div class="form-group">
                        <label for="firstname">First Name</label>
                        <input type="text" id="firstname" name="firstname" required>
                    </div>
                    <div class="form-group">
                        <label for="lastname">Last Name</label>
                        <input type="text" id="lastname" name="lastname" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="address">Address</label>
                    <input type="text" id="address" name="address" required>
                </div>
                <div class="form-group-row">
                    <div class="form-group">
                        <label for="city">City</label>
                        <input type="text" id="city" name="city" required>
                    </div>
                    <div class="form-group">
                        <label for="postal_code">Postal Code</label>
                        <input type="text" id="postal_code" name="postal_code" required>
                    </div>
                </div>
            </div>
        </div>

        <!-- 
        =========================================
        RIGHT COLUMN: Order Summary
        =========================================
        -->
        <div class="cart-right-column">
            <!-- This box holds the summary, as seen in the Figma design -->
            <div class="order-summary-box">
                <h2>Order Summary</h2>
                
                <div class="summary-row">
                    <span>Currency</span>
                    <span>ZAR (R)</span>
                </div>
                
                <div class="summary-row">
                    <span>Subtotal</span>
                    <strong>R <?php echo number_format($subtotal, 2); ?></strong>
                </div>

                <div class="summary-row">
                    <span>Shipping</span>
                    <strong>R <?php echo number_format($shipping_cost, 2); ?></strong>
                </div>

                <!-- 
                =========================================
                CRITICAL FEATURE: EcoPoints
                =========================================
                -->
                <div class="ecopoints-redeem">
                    <label for="ecopoints">Redeem EcoPoints</label>
                    <?php if (isset($_SESSION['user_logged_in'])): ?>
                        <small>Available: <?php echo $_SESSION['user_ecopoints']; ?> points</small>
                        <input type="number" id="ecopoints" name="ecopoints" value="0" max="<?php echo $_SESSION['user_ecopoints']; ?>">
                    <?php else: ?>
                        <small><a href="login.php">Log in</a> to redeem points.</small>
                        <input type="number" id="ecopoints" name="ecopoints" value="0" readonly>
                    <?php endif; ?>
                </div>

                <hr>

                <div class="summary-row summary-total">
                    <strong>Total</strong>
                    <strong>R <?php echo number_format($total, 2); ?></strong>
                </div>

                <div class="payment-methods">
                    <p>Payment Methods</p>
                    <div class="payment-icons">
                        <span>CC</span>
                        <span>PP</span>
                        <span>AP</span>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary form-btn">Proceed to Payment</button>
            </div>
        </div>

    </form> <!-- End of the main cart form -->
</main>

<?php
// 2. Include the reusable footer
include 'footer.php';
?>

