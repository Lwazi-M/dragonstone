<?php
/*
==================================================================
FILE: header.php
PURPOSE: This is a reusable template for the *customer-facing* site.
HOW IT WORKS:
This one file is included (using 'include') at the very top
of all other customer pages (like index.php, about.php, etc.).
This saves us from re-writing the navigation bar on every single page.
==================================================================
*/

/*
------------------------------------------------------------------
SECTION 1: PHP Session Logic
------------------------------------------------------------------
This PHP block must run *before* any HTML is sent to the browser.
*/

// session_start(); <-- This was a duplicated comment, I removed it.
// PURPOSE: This is a *critical* command. It tells the server
// to either start a new "session" (like a temporary memory file)
// or resume an existing one.
// This is how the server "remembers" who the customer is as they
// click from one page to another. This is what makes "login" work.
session_start();

// 2. Set a default page title.
// We create a variable called '$pageTitle'.
if (!isset($pageTitle)) {
    // '!isset' means "if the $pageTitle variable has NOT been set..."
    // This allows other pages (like about.php) to define their own
    // custom title *before* they include this file.
    // If they don't, we use this default title.
    $pageTitle = "DragonStone - Sustainable Living";
}
?>
<!DOCTYPE html>
<!-- This is the start of the HTML document. -->
<html lang="en">
<!-- 'lang="en"' tells the browser the page is in English. -->
<head>
    <!--
    The <head> section contains "meta" information (data about the page).
    It is not visible to the user, but it's very important for
    the browser and search engines.
    -->
    <meta charset="UTF-8">
    <!-- This line ensures all characters (like R, $, ©) display correctly. -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- This line is essential for the website to look good on mobile phones. -->
    
    <title><?php echo $pageTitle; ?></title>
    <!--
    This PHP 'echo' command prints the value of our $pageTitle variable.
    This is what makes the text in the browser tab change for each page.
    -->
    
    <!-- === STYLESHEETS === -->
    <!-- These <link> tags load our CSS files, which control all colors,
         fonts, and layouts. -->
    <link rel="stylesheet" href="style.css">
    <!-- We link to the main stylesheet for the whole site. -->
    <link rel="stylesheet" href="product.css">
    <!-- We also link to the product page stylesheet here. It's okay
         to load it on every page, even if it's not used. -->
</head>
<body>
    <!--
    The <body> tag is where all the *visible* content of the
    website (text, images, links) begins.
    -->

    <!-- === HEADER & NAVIGATION BAR === -->
    <header>
        <!-- The <header> tag is a semantic way to group the top of your page. -->
        <nav class="navbar">
            <!-- <nav> means "navigation". We give it a 'class' of "navbar"
                 so we can style it using 'style.css'. -->
            
            <a href="index.php" class="nav-logo">DragonStone</a>
            <!-- This is a link to the homepage, styled as the logo. -->
            
            <ul class="nav-menu">
                <!-- <ul> is an "Unordered List". This is the standard way to make a menu. -->
                <li class="nav-item"><a href="index.php" class="nav-link">Shop</a></li>
                <li class="nav-item"><a href="community.php" class="nav-link">Community</a></li>
                <li class="nav-item"><a href="about.php" class="nav-link">About</a></li>
            </ul>
            
            <div class="nav-icons">
                <!-- This <div> is a "box" to hold the icons on the right. -->
                
                <?php
                /*
                ------------------------------------------------------------------
                SECTION 2: "Smart" Navigation Logic
                ------------------------------------------------------------------
                This PHP 'if/else' block checks the session "memory"
                to see if the user is logged in.
                */
                
                // 5. Check if the 'user_logged_in' variable exists in the session
                //    (We created this variable in 'login_process_customer.php')
                if (isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] === true) {
                    
                    // --- IF THE USER *IS* LOGGED IN ---
                    // The server will print these three links:
                    echo '<a href="profile.php" class="icon-link">Profile</a>'; // (We'll make profile.php later)
                    echo '<a href="cart.php" class="icon-link">Cart</a>';
                    echo '<a href="logout_customer.php" class="icon-link">Logout</a>';
                
                } else {
                    
                    // --- IF THE USER *IS A GUEST* (not logged in) ---
                    // The server will print these three links instead:
                    echo '<a href="login.php" class="icon-link">Login</a>';
                    echo '<a href="register.php" class="icon-link">Register</a>';
                    echo '<a href="cart.php" class="icon-link">Cart</a>';
                }
                ?>
            </div>
        </nav>
    </header>
    <!--
    This is the *end* of the header.php file.
    The <main> content for each page will start right after this
    in the other files (like index.php).
    -->

