<?php
/*
This is product_process.php
It has NO HTML.
Its job is to:
1. Include security and database connection.
2. Check if the action is "add" or "edit".
3. Get all the data from the $_POST form.
4. If "add", run an INSERT SQL query.
5. If "edit", run an UPDATE SQL query.
6. Redirect the user back to the manage_products.php list.
*/

// 1. Include security and database connection
// We start the session to make sure an admin is logged in
session_start();
if( !isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true ) {
    die("Access Denied. You must be logged in."); // Simple security check
}

include '../db_connect.php'; // Get the database connection

// 3. Get all the data from the $_POST form
// We use $_POST[] to get the data sent from our form
$name = $_POST['name'];
$description = $_POST['description'];
$price = $_POST['price'];
$stock = $_POST['stock'];
$category = $_POST['category'];
$carbon = $_POST['carbon'];
$image = $_POST['image']; // This is the image URL from the text input

// 2. Check if the action is "add" or "edit"
if (isset($_GET['action'])) {
    
    if ($_GET['action'] == 'add') {
        // 4. If "add", run an INSERT SQL query
        
        $sql = "INSERT INTO products (name, description, price, stock_quantity, category, carbon_footprint_value, image_url) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $conn->prepare($sql);
        // "ssdiiss" is the "type" for each variable:
        // s = string (name)
        // s = string (description)
        // d = double/decimal (price)
        // i = integer (stock)
        // s = string (category)
        // d = double/decimal (carbon)
        // s = string (image)
        $stmt->bind_param("ssdiids", $name, $description, $price, $stock, $category, $carbon, $image);

    } elseif ($_GET['action'] == 'edit' && isset($_GET['id'])) {
        // 5. If "edit", run an UPDATE SQL query
        
        $product_id = $_GET['id']; // Get the ID from the URL
        
        $sql = "UPDATE products SET 
                    name = ?, 
                    description = ?, 
                    price = ?, 
                    stock_quantity = ?, 
                    category = ?, 
                    carbon_footprint_value = ?, 
                    image_url = ? 
                WHERE product_id = ?"; // We add the WHERE clause
        
        $stmt = $conn->prepare($sql);
        // The types are the same, but we add one more at the end:
        // i = integer (product_id)
        $stmt->bind_param("ssdiidsi", $name, $description, $price, $stock, $category, $carbon, $image, $product_id);
    }

    // Execute the query (either INSERT or UPDATE)
    if ($stmt->execute()) {
        // 6. Redirect back to the product list
        header("Location: manage_products.php?success=true");
    } else {
        echo "Error: " . $stmt->error;
    }
    
    $stmt->close();

}

$conn->close();
?>