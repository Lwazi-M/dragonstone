<?php
/*
==================================================================
FILE: admin/product_delete.php
PURPOSE: This script deletes a product from the database.
         It has NO HTML.
HOW IT WORKS:
1. It includes the security check (we must check the session).
2. It includes the database connection.
3. It gets the 'id' of the product to delete from the URL
   (e.g., ...?id=5).
4. If an 'id' is present, it runs a secure 'DELETE'
   SQL query for that specific product.
5. It redirects the admin back to the 'manage_products.php'
   list, which will now be updated.
==================================================================
*/

// 1. Include security
// We must start the session to check if the user is logged in.
session_start();
if( !isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true ) {
    // If not logged in, stop the script.
    // This is a crucial security check.
    die("Access Denied. You must be logged in to perform this action.");
}

// 1b. Include database connection
// We are in the 'admin' folder, so we go "up" ('../')
include '../db_connect.php';

// 2. Check if an 'id' was sent in the URL
// This ensures we don't run the script by accident.
if (isset($_GET['id'])) {
    
    // Store the ID from the URL in a variable
    // (e.g., 5)
    $product_id = $_GET['id'];
    
    // 3. Run a DELETE SQL query
    // We use a prepared statement with '?' to prevent SQL injection.
    // This is the safest way to run queries with user data.
    $sql = "DELETE FROM products WHERE product_id = ?";
    
    // Prepare the query for the database
    $stmt = $conn->prepare($sql);
    
    // Bind the $product_id to the '?'
    // 'i' means the variable is an integer
    $stmt->bind_param("i", $product_id);
    
    // Execute the delete query
    if ($stmt->execute()) {
        // 4. Redirect back to the product list on success
        // We add "?success=deleted" to the URL, which
        // we could (optionally) use to show a green success message.
        header("Location: manage_products.php?success=deleted");
    } else {
        // If the query fails (e.g., a database error)
        echo "Error deleting record: " . $stmt->error;
    }
    
    // Close the statement
    $stmt->close();
    
} else {
    // If no 'id=' was in the URL, the user got here
    // by accident. Send them back to the main product list.
    header("Location: manage_products.php?error=NoID");
}

// Close the database connection
$conn->close();
?>

