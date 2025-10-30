<?php
/*
==================================================================
FILE: admin/product_process.php
PURPOSE: This script handles the 'Add' and 'Edit' logic for
         products. It has NO HTML.
HOW IT WORKS:
1. It starts the session and checks if an admin is logged in.
2. It includes the database connection.
3. It gets all the submitted data (name, price, etc.)
   from the $_POST global variable.
4. It checks the URL for "?action=add" or "?action=edit".
5. IF "ADD":
   - It runs an 'INSERT' SQL query with all the new data.
6. IF "EDIT":
   - It runs an 'UPDATE' SQL query, using the 'product_id'
     from the URL to update the correct row.
7. It uses 'bind_param' for both queries, which is a
   critical security feature to prevent SQL Injection.
8. It redirects the admin back to the 'manage_products.php' list.
==================================================================
*/

// 1. Include security and database connection
// We start the session to make sure an admin is logged in
session_start();
if( !isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true ) {
    die("Access Denied. You must be logged in."); // Simple security check
}

include '../db_connect.php'; // Get the database connection (go "up" one folder)

// 3. Get all the data from the $_POST form
// $_POST is a PHP global variable that holds all data
// sent using the 'method="POST"' from the HTML form.
$name = $_POST['name'];
$description = $_POST['description'];
$price = $_POST['price'];
$stock = $_POST['stock'];
$category = $_POST['category'];
$carbon = $_POST['carbon'];
$image = $_POST['image']; // This is the image URL from the text input

// 2. Check if the action is "add" or "edit" from the URL
if (isset($_GET['action'])) {
    
    // Check if the action is 'add'
    if ($_GET['action'] == 'add') {
        // 4. If "add", run an INSERT SQL query
        
        // The '?' are placeholders for our variables.
        $sql = "INSERT INTO products (name, description, price, stock_quantity, category, carbon_footprint_value, image_url) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        
        // 'prepare' the SQL query for the database
        $stmt = $conn->prepare($sql);
        
        /*
        'bind_param' securely attaches our variables to the '?'.
        "ssdiids" is the "type" for each variable, in order:
        s = string (name)
        s = string (description)
        d = double/decimal (price)
        i = integer (stock)
        i = integer (category - wait, should be string)
        d = double/decimal (carbon)
        s = string (image)
        */
        // Corrected: category is a string (s)
        $stmt->bind_param("ssdisds", $name, $description, $price, $stock, $category, $carbon, $image);

    // Check if the action is 'edit' and an 'id' is also in the URL
    } elseif ($_GET['action'] == 'edit' && isset($_GET['id'])) {
        // 5. If "edit", run an UPDATE SQL query
        
        $product_id = $_GET['id']; // Get the ID from the URL
        
        // The SQL is different: UPDATE... SET... WHERE...
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
        
        // The types are the same, but we add one more at the end for the WHERE clause:
        // i = integer (product_id)
        // Corrected: category is a string (s)
        $stmt->bind_param("ssdisdsi", $name, $description, $price, $stock, $category, $carbon, $image, $product_id);
    }

    // Execute the query (either the INSERT or the UPDATE)
    if (isset($stmt) && $stmt->execute()) {
        // 6. Redirect back to the product list
        header("Location: manage_products.php?success=true");
    } else {
        // If the query fails, show an error
        echo "Error: " . $stmt->error;
    }
    
    // Close the statement
    $stmt->close();

} else {
    // If no action was specified in the URL, do nothing
    // and just close the connection.
}

// Close the database connection
$conn->close();
?>

