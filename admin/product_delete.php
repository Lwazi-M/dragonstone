<?php
/*
This is product_delete.php
It has NO HTML.
Its job is to:
1. Include security and database connection.
2. Check if an 'id' was sent in the URL.
3. If yes, run a DELETE SQL query for that ID.
4. Redirect the user back to the manage_products.php list.
*/

// 1. Include security and database connection
session_start();
if( !isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true ) {
    die("Access Denied. You must be logged in.");
}

include '../db_connect.php';

// 2. Check if an 'id' was sent in the URL
if (isset($_GET['id'])) {
    
    $product_id = $_GET['id'];
    
    // 3. Run a DELETE SQL query
    // We use a prepared statement to be secure.
    $sql = "DELETE FROM products WHERE product_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $product_id); // "i" for integer
    
    if ($stmt->execute()) {
        // 4. Redirect back to the product list on success
        header("Location: manage_products.php?success=deleted");
    } else {
        echo "Error deleting record: " . $stmt->error;
    }
    
    $stmt->close();
    
} else {
    // If no ID was sent, just go back
    header("Location: manage_products.php?error=NoID");
}

$conn->close();
?>