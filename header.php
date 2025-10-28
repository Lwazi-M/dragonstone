<?php
/*
This is header.php
It will be INCLUDED at the top of every customer page.
Its jobs are:
1. Start the session (to remember the customer's login).
2. Set a default page title.
3. Show the HTML head section.
4. Show the navigation bar.
5. "Smartly" change the nav links based on login status.
*/

// 1. Start the session on every page
session_start();

// 2. Set a default title.
// Other pages can "override" this by setting a $pageTitle variable
// *before* they include this file.
if (!isset($pageTitle)) {
    $pageTitle = "DragonStone - Sustainable Living";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle; ?></title>
    
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="product.css"> </head>
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
                <?php
                // 5. This is the "smart" part.
                // Check if the user is logged in (if the session variable exists)
                if (isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] === true) {
                    // --- USER IS LOGGED IN ---
                    // Show links to their profile and to logout
                    echo '<a href="profile.php" class="icon-link">Profile</a>'; // We'll make profile.php later
                    echo '<a href="cart.php" class="icon-link">Cart</a>';
                    echo '<a href="logout_customer.php" class="icon-link">Logout</a>';
                } else {
                    // --- USER IS A GUEST ---
                    // Show links to login and register
                    echo '<a href="login.php" class="icon-link">Login</a>';
                    echo '<a href="register.php" class="icon-link">Register</a>';
                    echo '<a href="cart.php" class="icon-link">Cart</a>';
                }
                ?>
            </div>
        </nav>
    </header>