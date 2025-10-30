<?php
/*
==================================================================
FILE: admin/admin_header.php
PURPOSE: This is the reusable "template" for the admin panel.
HOW IT WORKS:
1. It starts the *admin* session.
2. It checks if an admin is logged in. If not, it
   redirects them to the login page (this is the security).
3. It displays the HTML <head>, the sidebar navigation,
   and the top bar with the "Welcome" message.
==================================================================
*/

// 1. Start the admin session
// This is a different session from the customer one.
session_start();

// 2. SECURITY CHECK: Is the admin logged in?
// 'isset' checks if the 'admin_logged_in' variable exists
if( !isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true ) {
    
    // 3. If they are NOT logged in, kick them out
    // 'header()' sends them to a new page.
    header("Location: login.php?error=NotLoggedIn");
    exit(); // 'exit()' stops the rest of the page from loading
}

// 4. If they ARE logged in, we continue and show the HTML.
// The script 'remembers' who they are from the session.
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- The <title> will be set by each page -->
    <title>Admin Dashboard - DragonStone</title>
    <!-- We link to the admin-specific stylesheet -->
    <link rel="stylesheet" href="style_admin.css">
</head>
<body>
    <!-- This 'wrapper' holds the sidebar and main content -->
    <div class="admin-wrapper">
        
        <!-- This is the sidebar navigation -->
        <nav class="admin-sidebar">
            <h3 class="sidebar-title">DragonStone Admin</h3>
            <!-- This list contains all the main admin pages -->
            <ul>
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="manage_products.php">Manage Products</a></li>
                <li><a href="manage_users.php">Manage Users</a></li>
                <li><a href="manage_community.php">Manage Community</a></li>
                <!-- ================================== -->
                <!-- *** NEW LINK ADDED HERE *** -->
                <li><a href="manage_challenges.php">Manage Challenges</a></li>
                <!-- ================================== -->
                <li><a href="reports.php">Reports</a></li>
            </ul>
            <div class="sidebar-footer">
                <!-- This link goes to the logout script -->
                <a href="logout.php" class="logout-button">Logout</a>
            </div>
        </nav>

        <!-- This is the main content area -->
        <!-- The <main> tag is closed by 'admin_footer.php' -->
        <main class="admin-main">
            <!-- This is the top bar that says "Welcome" -->
            <header class="admin-header">
                <!-- We greet the admin by name and show their role -->
                <p>Welcome, <strong><?php echo htmlspecialchars($_SESSION['admin_username']); ?></strong> (Role: <?php echo htmlspecialchars($_SESSION['admin_role']); ?>)</p>
            </header>
            
            <!--
            The 'admin_header.php' file ends here.
            The page that includes this file (e.g., 'dashboard.php')
            will add its own content right after this.
            -->

