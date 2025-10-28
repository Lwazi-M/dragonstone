<?php
// 1. Include the security header and database connection
include 'admin_header.php';
include '../db_connect.php';

// 2. Initialize variables
$product_name = "";
$description = "";
$price = "";
$stock = "";
$image = "";
$carbon = "";
$category = "";
$product_id = 0;
$form_action = "product_process.php?action=add"; // Default action is "add"
$page_title = "Add New Product";

// 3. Check if this is an "edit" action
// We look at the URL for "?action=edit"
if (isset($_GET['action']) && $_GET['action'] == 'edit' && isset($_GET['id'])) {
    
    $product_id = $_GET['id'];
    $page_title = "Edit Product";
    $form_action = "product_process.php?action=edit&id=" . $product_id;

    // 4. Fetch the existing product data from the database
    $sql = "SELECT * FROM products WHERE product_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows == 1) {
        $product = $result->fetch_assoc();
        // 5. Assign database values to our variables
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
$conn->close();
?>

<div class="page-content">
    <div class="page-header">
        <h2><?php echo $page_title; ?></h2>
    </div>

    <form action="<?php echo $form_action; ?>" method="POST" class="data-form">
        
        <div class="form-group">
            <label for="name">Product Name</label>
            <input type="text" id="name" name="name" value="<?php echo $product_name; ?>" required>
        </div>

        <div class="form-group">
            <label for="description">Description</label>
            <textarea id="description" name="description" rows="5" required><?php echo $description; ?></textarea>
        </div>

        <div class="form-group">
            <label for="price">Price (e.g., 129.99)</label>
            <input type="number" id="price" name="price" step="0.01" value="<?php echo $price; ?>" required>
        </div>

        <div class="form-group">
            <label for="stock">Stock Quantity</label>
            <input type="number" id="stock" name="stock" value="<?php echo $stock; ?>" required>
        </div>

        <div class="form-group">
            <label for="category">Category</label>
            <input type="text" id="category" name="category" value="<?php echo $category; ?>" required>
        </div>

        <div class="form-group">
            <label for="carbon">Carbon Footprint (kg CO2e)</label>
            <input type="number" id="carbon" name="carbon" step="0.01" value="<?php echo $carbon; ?>" required>
        </div>

        <div class="form-group">
            <label for="image">Image URL (e.g., images/bottle.jpg)</label>
            <input type="text" id="image" name="image" value="<?php echo $image; ?>" required>
            <?php if ($image): // If we are editing and an image exists, show a preview ?>
                <img src="../<?php echo $image; ?>" alt="Current Image" class="table-thumbnail" style="margin-top: 10px;">
            <?php endif; ?>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-add">Save Product</button>
            <a href="manage_products.php" class="btn-cancel">Cancel</a>
        </div>
        
    </form>
</div>

<?php
// 6. Include the footer (to close the HTML tags)
?>
        </main> </div> </body>
</html>