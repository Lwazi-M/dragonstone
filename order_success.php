<?php
/*
==================================================================
FILE: order_success.php
PURPOSE: This is the "Thank You" page after a successful order.
==================================================================
*/

// 1. Set the page title
$pageTitle = "Order Successful - DragonStone";

// 2. Include the header (this starts the session)
include 'header.php';

// 3. Security Check: Must be logged in
if( !isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true ) {
    header("Location: login.php");
    exit();
}
?>

<main class="page-container" style="text-align: center;">

    <div class="page-header">
        <h1>Thank You, <?php echo htmlspecialchars($_SESSION['user_firstname']); ?>!</h1>
    </div>
    
    <div class="order-success-content">
        <h2>Your order has been placed!</h2>
        <p>You've earned new EcoPoints for your purchase.</p>
        <p>Your new balance is: <strong><?php echo $_SESSION['user_ecopoints']; ?> points</strong></p>
        <br>
        <a href="community.php" class="btn btn-primary" style="margin-right: 1rem;">Go to Community Hub</a>
        <a href="index.php" class="btn btn-secondary">Continue Shopping</a>
    </div>

</main>

<?php
// 4. Include the reusable footer
include 'footer.php';
?>