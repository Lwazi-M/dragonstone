<?php
/*
==================================================================
FILE: admin/dashboard.php
PURPOSE: This is the "home base" for the admin panel.
HOW IT WORKS:
1. It includes the 'admin_header.php' file. This is CRITICAL.
   - The header file runs the security check to make sure the
     user is logged in.
   - The header file also displays the sidebar and top nav bar.
2. It then displays the unique content for this page (the
   "Dashboard" title and the stat cards).
3. Finally, it closes all the HTML tags that were opened
   by the header file.
==================================================================
*/

// 1. This is the first and most important line.
// It includes the admin header, which runs the security check
// and shows the sidebar/top bar.
include 'admin_header.php';
?>

<!--
This is the unique content for the dashboard page.
This HTML is placed *inside* the <main class="admin-main">
tag that was opened in 'admin_header.php'.
-->
<div class="page-content">
    <h2>Dashboard</h2>
    <p>Welcome to the admin panel. From here you can manage all parts of the website.</p>

    <!-- 
    This section will hold "at a glance" information.
    For the project, these are just "dummy" numbers.
    A more advanced site would use PHP to count the
    rows in the 'products' or 'users' tables.
    -->
    <div class="dashboard-stats">
        
        <div class="stat-card">
            <h4>Total Products</h4>
            <!-- This is a placeholder number -->
            <p>4</p>
        </div>

        <div class="stat-card">
            <h4>Total Admin Users</h4>
            <!-- This is a placeholder number -->
            <p>1</p>
        </div>

        <div class="stat-card">
            <h4>Community Posts</h4>
            <!-- This is a placeholder number -->
            <p>0</p>
        </div>

    </div>

</div> <!-- This closes the 'page-content' div -->


<?php include 'admin_footer.php'; ?>

