<?php
/*
==================================================================
FILE: cart_add.php
PURPOSE: This is the "brain" for adding products to the cart.
         It has NO HTML.
HOW IT WORKS:
1. It starts the customer session to access the 'cart'.
2. It gets the 'product_id' and 'quantity' from the form.
3. It initializes the cart ( `$_SESSION['cart']` ) as an
   empty array if it doesn't exist yet.
4. It checks if the product is *already* in the cart.
5. IF YES: It just updates the quantity.
6. IF NO: It fetches the product's name, price, AND IMAGE
   from the database (for security) and adds it to the cart.
7. It redirects the user back to the product page.
==================================================================
*/

// 1. Start the session
session_start();
include 'db_connect.php'; // We need to talk to the database

// 2. Get the data from the form
if (isset($_POST['product_id']) && isset($_POST['quantity'])) {
    
    $product_id = (int)$_POST['product_id'];
    $quantity = (int)$_POST['quantity'];
    
    // 3. Initialize the cart if it doesn't exist
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array();
    }

    // 4. Check if product is *already* in the cart
    if (isset($_SESSION['cart'][$product_id])) {
        
        // 5. IF YES: Update the quantity
        $_SESSION['cart'][$product_id]['quantity'] += $quantity;
        
    } else {
        // 6. IF NO: Fetch product details from DB
        
        // ==========================================================
        // *** BUG FIX ***
        // We must ALSO select the 'image_url' so we can show it
        // in the cart.
        // ==========================================================
        $sql = "SELECT name, price, image_url FROM products WHERE product_id = ?";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $product_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($product = $result->fetch_assoc()) {
            
            // ==========================================================
            // *** BUG FIX ***
            // We must ALSO save the 'image_url' to our session array.
            // ==========================================================
            $_SESSION['cart'][$product_id] = array(
                'name' => $product['name'],
                'price' => $product['price'],
                'quantity' => $quantity,
                'image_url' => $product['image_url'] // <-- THE FIX
            );
        }
        $stmt->close();
    }
    $conn->close();

    // 7. Redirect back to the product page
    // We add a success message to the URL
    header("Location: product.php?id=" . $product_id . "&success=AddedToCart");
    exit();

} else {
    // If no data was sent, just go back to the homepage.
    header("Location: index.php");
    exit();
}
?>

