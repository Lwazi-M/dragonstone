<?php
/*
==================================================================
FILE: admin/admin_header.php
PURPOSE: This is the reusable header for the *entire* admin panel.
HOW IT WORKS:
1. It is included at the top of EVERY secure admin page (dashboard, products, etc.).
2. It starts the admin session to "remember" the logged-in admin.
3. It checks if the user is *actually* logged in.
4. If they are NOT logged in, it immediately kicks them
   back to the 'login.php' page and stops the rest of
   the page from loading. This is the main security check.
5. If they *are* logged in, it shows the admin sidebar
   and the top welcome bar.
==================================================================
*/

// 1. Start the session
// This *must* be at the very top of the script to access
// the $_SESSION variables.
session_start();

// 2. Check if the 'admin_logged_in' session variable exists AND is true
// We set this variable to 'true' in 'login_process.php'
if( !isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true ) {
    
    // 3. If they are NOT logged in, kick them out
    // 'header()' redirects the user to another page.
    header("Location: login.php?error=NotLoggedIn");
    
    // 'exit()' is crucial. It stops PHP from running any
    // more code on this page, which prevents a logged-out
    // user from seeing the admin content.
    exit();
}

// 4. If they ARE logged in, we continue and show the HTML.
// The script 'remembers' who they are using the variables
// we saved in the session, like $_SESSION['admin_username']
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - DragonStone</title>
    
    <!-- 
    This links to the *separate* stylesheet for the admin panel.
    This keeps the admin styles from mixing with the customer styles.
    -->
    <link rel="stylesheet" href="style_admin.css">
</head>
<body>

    <!-- This 'wrapper' uses Flexbox to hold the sidebar and main content side-by-side -->
    <div class="admin-wrapper">
        
        <!-- This is the sidebar navigation -->
        <nav class="admin-sidebar">
            <h3 class="sidebar-title">DragonStone Admin</h3>
            <!-- This is the list of links to all our admin pages -->
            <ul>
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="manage_products.php">Manage Products</a></li>
                <li><a href="manage_users.php">Manage Users</a></li>
                <li><a href="manage_community.php">Manage Community</a></li>
            </ul>
            <!-- This footer stays at the bottom of the sidebar -->
            <div class="sidebar-footer">
                <a href="logout.php" class="logout-button">Logout</a>
            </div>
        </nav>

        <!-- 
        This is the main content area (the large part on the right).
        We do NOT close this tag here. The content page (e.g., dashboard.php)
        will add its content, and then *it* will close the tag.
        -->
        <main class="admin-main">
            
            <!-- This is the little header bar at the top of the main content -->
            <header class="admin-header">
                <!-- 
                This is a key requirement: showing the user's role.
                We fetch the user's name and role directly from the $_SESSION
                where we saved it during login.
                -->
                <p>Welcome, <strong><?php echo $_SESSION['admin_username']; ?></strong> (Role: <?php echo $_SESSION['admin_role']; ?>)</p>
            </header>
            
            <!-- 
            The content for each specific page (like the dashboard)
            will be 'echoed' right here by the file that includes this header.
            -->

         <?php include 'admin_footer.php'; ?   