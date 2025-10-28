<?php
/*
This is user_delete.php
It has NO HTML.
Its job is to:
1. Include security and database connection.
2. Check if an 'id' was sent in the URL.
3. Add a safety check: make sure the admin isn't deleting *themselves*.
4. If safe, run a DELETE SQL query for that ID.
5. Redirect the user back to the manage_users.php list.
*/

// 1. Include security and database connection
session_start();
if( !isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true ) {
    die("Access Denied. You must be logged in.");
}
// Double-check: only Admins can do this.
if ($_SESSION['admin_role'] !== 'Admin') {
    die("Access Denied. You do not have permission to manage users.");
}

include '../db_connect.php';

// 2. Check if an 'id' was sent in the URL
if (isset($_GET['id'])) {
    
    $user_id_to_delete = $_GET['id'];
    $current_user_id = $_SESSION['admin_id']; // Get the logged-in user's ID

    // 3. SAFETY CHECK: Don't let a user delete their own account.
    if ($user_id_to_delete == $current_user_id) {
        // Redirect back with an error
        header("Location: manage_users.php?error=CannotDeleteSelf");
        exit(); // Stop the script
    }
    
    // 4. If safe, run a DELETE SQL query
    $sql = "DELETE FROM admin_users WHERE admin_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id_to_delete);
    
    if ($stmt->execute()) {
        // 5. Redirect back to the user list on success
        header("Location: manage_users.php?success=deleted");
    } else {
        echo "Error deleting record: " . $stmt->error;
    }
    
    $stmt->close();
    
} else {
    // If no ID was sent, just go back
    header("Location: manage_users.php?error=NoID");
}

$conn->close();
?>