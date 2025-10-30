<?php
/*
==================================================================
FILE: admin/reports.php
PURPOSE: This page fulfills the "Report Generation" requirement.
         It shows aggregated data for admin-level users.
HOW IT WORKS:
1. It includes the 'admin_header.php' (security/layout).
2. It runs a role-check (only 'Admin' can see this).
3. It connects to the database.
4. It runs several "aggregate" SQL queries (COUNT, SUM)
   to get statistics about the site.
5. It displays this data in "stat card" boxes.
==================================================================
*/

// 1. Include the security header
include 'admin_header.php';

// 2. [CRITICAL REQUIREMENT CHECK]
// This is a high-level report, so only 'Admin' should see it.
if ($_SESSION['admin_role'] !== 'Admin') {
    die("Access Denied. You do not have permission to view reports.");
}

// 3. Include the database connection
include '../db_connect.php';

/*
==================================================================
SECTION 4: AGGREGATE QUERIES
This is the core of the "report generation".
We're asking the database to *calculate* data for us.
==================================================================
*/

// Query 1: Count total number of products
$sql_products = "SELECT COUNT(product_id) AS total_products FROM products";
$result_products = $conn->query($sql_products);
// We use 'fetch_assoc()' to get the result row
$stats_products = $result_products->fetch_assoc();
// We store the result in a variable
$total_products = $stats_products['total_products'];


// Query 2: Count total number of customers
$sql_users = "SELECT COUNT(user_id) AS total_users FROM users";
$result_users = $conn->query($sql_users);
$stats_users = $result_users->fetch_assoc();
$total_users = $stats_users['total_users'];


// Query 3: Count total number of community posts
$sql_posts = "SELECT COUNT(post_id) AS total_posts FROM forum_posts";
$result_posts = $conn->query($sql_posts);
$stats_posts = $result_posts->fetch_assoc();
$total_posts = $stats_posts['total_posts'];


// Query 4: Sum all stock to find total inventory
$sql_stock = "SELECT SUM(stock_quantity) AS total_stock FROM products";
$result_stock = $conn->query($sql_stock);
$stats_stock = $result_stock->fetch_assoc();
$total_stock = $stats_stock['total_stock'];

// We are done with the database
$conn->close();
?>

<!-- This is the unique HTML content for this page -->
<div class="page-content">
    <div class="page-header">
        <h2>Site Reports</h2>
    </div>

    <!-- 
    We re-use the "dashboard-stats" CSS from 'style_admin.css'
    to display our new, *real* data.
    -->
    <div class="dashboard-stats">
        
        <div class="stat-card">
            <h4>Total Products</h4>
            <!-- We 'echo' the result from our SQL query -->
            <p><?php echo $total_products; ?></p>
        </div>

        <div class="stat-card">
            <h4>Total Customers</h4>
            <!-- We 'echo' the result from our SQL query -->
            <p><?php echo $total_users; ?></p>
        </div>

        <div class="stat-card">
            <h4>Community Posts</h4>
            <!-- We 'echo' the result from our SQL query -->
            <p><?php echo $total_posts; ?></p>
        </div>

        <div class="stat-card">
            <h4>Total Items in Stock</h4>
            <!-- We 'echo' the result from our SQL query -->
            <p><?php echo $total_stock; ?></p>
        </div>

    </div>

</div>

<?php
// 6. Include the reusable admin footer
include 'admin_footer.php';
?>
