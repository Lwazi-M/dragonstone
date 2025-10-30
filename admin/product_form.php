<?php
/*
==================================================================
FILE: admin/product_form.php
PURPOSE: This is a "smart" form for both Creating and
         Updating products.
HOW IT WORKS:
1. It includes the 'admin_header.php' (security/layout)
   and 'db_connect.php'.
2. It initializes empty variables (e.g., $product_name = "").
3. It checks the URL for "?action=edit".
4. IF "EDIT":
   - It gets the product 'id' from the URL.
   - It fetches that product's data from the database.
   - It fills the variables ($product_name, etc.) with the
     data from the database.
   - It changes the page title to "Edit Product".
   - It changes the form's 'action' attribute to point to
     'product_process.php?action=edit&id=...'.
5. IF "ADD" (default):
   - The variables stay empty.
   - The title stays "Add New Product".
   - The form 'action' stays 'product_process.php?action=add'.
6. The HTML form then uses 'echo' in the 'value'
   attribute to pre-fill all the fields. (They'll be
   empty for "add" and full for "edit").
==================================================================
*/

// 1. Include the security header and database connection
include 'admin_header.php';
include '../db_connect.php';

// 2. Initialize variables
// We set default empty values for all our form fields.
// This prevents errors on the "add" page.
$product_name = "";
$description = "";
$price = "";
$stock = "";
$image = "";
$carbon = "";
$category = "";
$product_id = 0;
// We set the form's target URL. Default is "add".
$form_action = "product_process.php?action=add";
$page_title = "Add New Product";
$action = "add"; // For our password field logic

// 3. Check if this is an "edit" action
// We look at the URL for "?action=edit"
if (isset($_GET['action']) && $_GET['action'] == 'edit' && isset($_GET['id'])) {
    
    $action = "edit"; // Update our logic variable
    
    // Get the product ID from the URL (e.g., ...?id=3)
    $product_id = $_GET['id'];
    
    // Change the page title and form action URL
    $page_title = "Edit Product";
    $form_action = "product_process.php?action=edit&id=" . $product_id;

    // 4. Fetch the existing product data from the database
    // We use a secure prepared statement
    $sql = "SELECT * FROM products WHERE product_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $product_id); // 'i' for integer
    $stmt->execute();
    $result = $stmt->get_result();
    
    // If we found the product (1 row)
    if ($result->num_rows == 1) {
        $product = $result->fetch_assoc();
        
        // 5. Assign database values to our variables
        // Now $product_name is no longer "", it's e.g., "Bamboo Toothbrush"
        $product_name = $product['name'];
        $description = $product['description'];
        $price = $product['price'];
        $stock = $product['stock_quantity'];
        $image = $product['image_url'];
        $carbon = $product['carbon_footprint_value'];
        $category = $product['category'];
    }
    $stmt->close();
}
// We are done with the database, so we close the connection.
$conn->close();
?>

<!-- 
This is the main content area.
The 'page-content' class adds padding.
-->
<div class="page-content">
    <div class="page-header">
        <!-- The title is dynamic! It's either "Add" or "Edit" -->
        <h2><?php echo $page_title; ?></h2>
    </div>

    <!-- 
    This form sends its data to the $form_action URL (which
    is dynamic: either "add" or "edit").
    
    'enctype="multipart/form-data"' is *required* if you
    ever want to change the 'image' field to a real file upload.
    For now, we are just using a text input, but it's good
    practice to include it.
    -->
    <form action="<?php echo $form_action; ?>" method="POST" class="data-form">
        
        <div class="form-group">
            <label for="name">Product Name</label>
            <!-- 
            This is the magic: 'value' is set by PHP.
            On "add", it's: value=""
            On "edit", it's: value="Bamboo Toothbrush"
            
            SECURITY FIX: We wrap the variable in 'htmlspecialchars()'.
            This prevents XSS attacks if a product name has
            HTML characters in it (e.g., <script>).
            -->
            <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($product_name); ?>" required>
        </div>

        <div class="form-group">
            <label for="description">Description</label>
            <!-- 
            Textareas are special. The value goes *between*
            the tags, not in a 'value' attribute.
            We also use htmlspecialchars() here for security.
            -->
            <textarea id="description" name="description" rows="5" required><?php echo htmlspecialchars($description); ?></textarea>
        </div>

        <div class="form-group">
            <label for="price">Price (e.g., 129.99)</label>
            <!-- 'step="0.01"' allows for decimal values (cents) -->
            <input type="number" id="price" name="price" step="0.01" value="<?php echo htmlspecialchars($price); ?>" required>
        </div>

        <div class="form-group">
            <label for="stock">Stock Quantity</label>
            <input type="number" id="stock" name="stock" value="<?php echo htmlspecialchars($stock); ?>" required>
        </div>

        <div class="form-group">
            <label for="category">Category</label>
            <input type="text" id="category" name="category" value="<?php echo htmlspecialchars($category); ?>" required>
        </div>

        <div class="form-group">
            <label for="carbon">Carbon Footprint (kg CO2e)</label>
            <!-- This field is for your project requirement -->
            <input type="number" id="carbon" name="carbon" step="0.01" value="<?php echo htmlspecialchars($carbon); ?>" required>
        </div>

        <div class="form-group">
            <label for="image">Image URL (e.g., images/bottle.jpg)</label>
            <!-- 
            For this project, we just ask for the *path* to the image.
            A real file upload is more complex, but this meets the
            requirement of being able to add/update the image.
            -->
            <input type="text" id="image" name="image" value="<?php echo htmlspecialchars($image); ?>" required>
            
            <?php if ($image): // This PHP 'if' statement only runs if $image is NOT empty ?>
                <!-- If we are editing and an image exists, show a preview -->
                <!-- We use htmlspecialchars() on the 'src' and 'alt' attributes for security -->
                <img src="../<?php echo htmlspecialchars($image); ?>" alt="<?php echo htmlspecialchars($product_name); ?>" class="table-thumbnail" style="margin-top: 10px;">
            <?php endif; // End the 'if' statement ?>
        </div>

        <!-- These are the "Submit" and "Cancel" buttons -->
        <div class="form-actions">
            <button type="submit" class="btn-add">Save Product</button>
            <a href="manage_products.php" class="btn-cancel">Cancel</a>
        </div>
        
    </form>
</div>

<?php
/*
==================================================================
6. INCLUDE THE REUSABLE ADMIN FOOTER
This file (admin_footer.php) just contains the closing tags:
- </main>
- </div>
- </body>
- </html>
This keeps our code clean and organized.
==================================================================
*/
include 'admin_footer.php';
?>
