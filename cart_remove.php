<?php
/*
==================================================================
FILE: cart_remove.php
PURPOSE: This script removes a product from the cart.
         It has NO HTML.
HOW IT WORKS:
1. It starts the session to access the cart.
2. It gets the 'id' of the product to remove from the URL
   (e.g., ...?id=5).
3. If the 'id' is present and the item exists in the cart,
   it 'unsets' (removes) that item from the $_SESSION['cart'] array.
4. It redirects the user back to the 'cart.php' page.
==================================================================
*/

// 1. Start the session
session_start();

// 2. Check if an 'id' was sent in the URL
if (isset($_GET['id'])) {
    
    $product_id = (int)$_GET['id'];
    
    // 3. Check if that item actually exists in the cart
    if (isset($_SESSION['cart'][$product_id])) {
        // If yes, remove it
        unset($_SESSION['cart'][$product_id]);
    }
}

// 4. Redirect back to the cart page
// The cart page will automatically recalculate the total.
header("Location: cart.php?success=removed");
exit();
?>
