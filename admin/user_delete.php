<?php
/*
==================================================================
FILE: admin/user_delete.php
PURPOSE: This script Deletes an admin user from the
         'admin_users' table. It has NO HTML.
HOW IT WORKS:
1. It starts the session and does a full security check to
   ensure the user is a logged-in 'Admin'.
2. It gets the 'id' of the user to-be-deleted from the URL.
3. It gets the 'id' of the *currently logged in* user from
   the session.
4. It compares them. If they are the SAME, it stops
   and sends an error (Safety Check).
5. If they are different, it runs a 'DELETE' query using
   the ID from the URL.
6. It redirects the admin back to the 'manage_users.php' list.
==================================================================
*/

// 1. Include security
// We must start the session to check the 'admin_role' and 'admin_id'
session_start();
if( !isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true ) {
    die("Access Denied. You must be logged in.");
}
// This is the CRITICAL role-based security check
if ($_SESSION['admin_role'] !== 'Admin') {
    die("Access Denied. You do not have permission to manage users.");
}

// 1b. Include database connection
include '../db_connect.php'; // Go "up" one folder for the file

// 2. Check if an 'id' was sent in the URL
if (isset($_GET['id'])) {
    
    // This is the ID of the user we are *trying* to delete
    $user_id_to_delete = $_GET['id'];
    
    // This is the ID of the admin who is *currently* logged in
    $current_user_id = $_SESSION['admin_id'];

    /*
    ==================================================================
    3. [CRITICAL SAFETY CHECK]
       We check if the ID to delete is the *same* as the
       currently logged-in admin's ID. If it is, we stop.
       This prevents an admin from accidentally deleting their
       own account and getting locked out.
    ==================================================================
    */
    if ($user_id_to_delete == $current_user_id) {
        // Redirect back with an error message
        header("Location: manage_users.php?error=CannotDeleteSelf");
        exit(); // Stop the script immediately
    }
    
    // 4. If it's safe, run a DELETE SQL query
    // We use a '?' placeholder for security (prevents SQL injection)
    $sql = "DELETE FROM admin_users WHERE admin_id = ?";
    $stmt = $conn->prepare($sql);
    
    // 'bind_param' securely attaches our variable to the '?'
    // "i" means the variable is an "integer"
    $stmt->bind_param("i", $user_id_to_delete);
    
    // 5. Execute the delete query
    if ($stmt->execute()) {
        // 6. Redirect back to the user list on success
        header("Location: manage_users.php?success=deleted");
    } else {
        // If the database has an error
        echo "Error deleting record: " . $stmt->error;
    }
    
    // Close the statement
    $stmt->close();
    
} else {
    // If no 'id=' was in the URL, just go back to the list.
    header("Location: manage_users.php?error=NoID");
}

// Close the database connection
$conn->close();
?>
