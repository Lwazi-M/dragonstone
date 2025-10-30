<?php
/*
==================================================================
FILE: admin/manage_products.php
PURPOSE: This is the main page for managing products.
HOW IT WORKS:
1. It includes the 'admin_header.php' to check if the user
   is logged in and to show the admin layout.
2. It includes the 'db_connect.php' to get the database connection.
3. It fetches *all* products from the 'products' table.
4. It displays each product as a row in an HTML table.
5. It provides "Edit" and "Delete" links for each product,
   which are key parts of the project's CRUD requirement.
==================================================================
*/

// 1. Include the security header (checks login, shows sidebar)
include 'admin_header.php';

// 2. Include the database connection
// We use '../' to go "up" one folder level to find the file.
include '../db_connect.php';

// 3. Fetch all products from the database
// We 'ORDER BY product_id DESC' to show the newest products first.
$sql = "SELECT * FROM products ORDER BY product_id DESC";
$result = $conn->query($sql);
?>

<!-- 
This is the main content area for *this* page.
The 'page-content' class adds padding.
-->
<div class="page-content">
    
    <!-- This is the header bar for the page content -->
    <div class="page-header">
        <h2>Manage Products</h2>
        <!-- This is the "Create" button (the "C" in CRUD) -->
        <a href="product_form.php?action=add" class="btn-add">+ Add New Product</a>
    </div>

    <!-- 
    This table will display all the product data.
    The 'data-table' class will be styled in 'style_admin.css'.
    -->
    <table class="data-table">
        <!-- 'thead' is the table header -->
        <thead>
            <tr>
                <th>ID</th>
                <th>Image</th>
                <th>Name</th>
                <th>Price</th>
                <th>Stock</th>
                <th>Actions</th>
            </tr>
        </thead>
        <!-- 'tbody' is the table body where the data goes -->
        <tbody>
            <?php
            // 4. Loop through the results and display each product
            
            // First, check if the database returned at least 1 product
            if ($result->num_rows > 0) {
                
                // 'while' loop: It fetches one product row at a time
                // and puts its data into the '$row' variable.
                while($row = $result->fetch_assoc()) {
                    
                    // 'echo' just means "print this HTML to the page"
                    echo "<tr>";
                    echo "<td>" . $row['product_id'] . "</td>";
                    
                    // Show a small thumbnail of the image.
                    // We use '../' again to tell the HTML to go "up"
                    // one folder to find the 'images' folder.
                    echo "<td><img src='../" . $row['image_url'] . "' alt='" . $row['name'] . "' class='table-thumbnail'></td>";
                    
                    echo "<td>" . $row['name'] . "</td>";
                    echo "<td>R " . $row['price'] . "</td>";
                    echo "<td>" . $row['stock_quantity'] . "</td>";
                    
                    // This cell holds the "Edit" and "Delete" buttons
                    echo "<td class='table-actions'>";
                    
                    // This link builds the "Update" (U) part of CRUD.
                    // It passes '?action=edit' and the specific product's ID in the URL.
                    echo "    <a href='product_form.php?action=edit&id=" . $row['product_id'] . "' class='btn-edit'>Edit</a>";
                    
                    // This link builds the "Delete" (D) part of CRUD.
                    // It passes the specific product's ID in the URL.
                    echo "    <a href='product_delete.php?id=" . $row['product_id'] . "' class='btn-delete'>Delete</a>";
                    
                    echo "</td>";
                    echo "</tr>";
                }
            } else {
                // If there are no products in the table, show this message
                echo "<tr><td colspan='6'>No products found.</td></tr>";
            }
            
            // 5. Close the database connection (good practice)
            $conn->close();
            ?>
        </tbody>
    </table>

</div>

<?php
// 6. Close the HTML tags that were opened in 'admin_header.php'
// This closes the '</main>' and '</div>' tags.
?>
        <?php include 'admin_footer.php'; ?>
