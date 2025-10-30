<?php
/*
==================================================================
FILE: order_process.php
PURPOSE: This script "completes" the checkout.
         It has NO HTML.
HOW IT WORKS:
1. Starts the session and checks if the user is logged in.
2. Checks if the cart is empty.
3. Calculates the subtotal (for security, we re-calculate it here).
4. Calculates the EcoPoints to award (e.g., 1 point per R10).
5. Runs an UPDATE query to add the points to the user's account.
6. Updates the session with the new point total.
7. Clears the cart from the session.
8. Redirects to a "Thank You" page.
==================================================================
*/

// 1. Start the session
session_start();

// 2. Security Check: Is the user logged in?
// A guest can't get EcoPoints, so we force a login for now.
if( !isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true ) {
    header("Location: login.php?error=MustBeLoggedInToCheckout");
    exit();
}

// 2b. Check if the cart is empty
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    header("Location: cart.php?error=CartEmpty");
    exit();
}

// 3. Get the User ID and Cart
$user_id = $_SESSION['user_id'];
$cart = $_SESSION['cart'];

// 4. Securely recalculate the subtotal
$subtotal = 0;
foreach ($cart as $product_id => $item) {
    $subtotal += $item['price'] * $item['quantity'];
}

// 5. Calculate EcoPoints to award
// Let's give 1 point for every R10 spent.
// 'floor()' rounds down, so R59.99 = 5 points.
$points_to_add = floor($subtotal / 10);

if ($points_to_add > 0) {
    // 6. Connect to DB and add the points
    include 'db_connect.php';
    
    $sql_points = "UPDATE users SET ecopoints = ecopoints + ? WHERE user_id = ?";
    $stmt_points = $conn->prepare($sql_points);
    $stmt_points->bind_param("ii", $points_to_add, $user_id);
    $stmt_points->execute();
    $stmt_points->close();
    $conn->close();

    // 7. Update the session "memory"
    $_SESSION['user_ecopoints'] += $points_to_add;
}

// 8. Clear the shopping cart
// The order is "complete", so we empty the cart.
unset($_SESSION['cart']);

// 9. Redirect to a "Thank You" page
header("Location: order_success.php");
exit();
?>