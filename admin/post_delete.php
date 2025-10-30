<?php
/*
==================================================================
FILE: admin/post_delete.php
PURPOSE: This script Deletes a customer post from the
         'forum_posts' table. It has NO HTML.
HOW IT WORKS:
1. It starts the session and does a full security check.
2. It does a [CRITICAL] role check to ensure the user is
   an 'Admin' or 'CommunityModerator'.
3. It gets the 'id' of the post to-be-deleted from the URL.
4. It runs a 'DELETE' query using the post's ID.
5. It redirects the admin back to the 'manage_community.php' list.
==================================================================
*/

// 1. Include security
// We must start the session to check the 'admin_role'
session_start();
if( !isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true ) {
    die("Access Denied. You must be logged in.");
}

/*
==================================================================
2. [CRITICAL REQUIREMENT CHECK]
   This is the role-based security check. We allow 'Admin'
   OR 'CommunityModerator' to delete posts.
   An 'OrderManager' would be stopped here.
==================================================================
*/
if ($_SESSION['admin_role'] !== 'Admin' && $_SESSION['admin_role'] !== 'CommunityModerator') {
    die("Access Denied. You do not have permission to manage the community.");
}

// 1b. Include database connection
include '../db_connect.php'; // Go "up" one folder

// 3. Check if an 'id' was sent in the URL (e.g., ...?id=7)
if (isset($_GET['id'])) {
    
    // Get the ID of the post we want to delete
    $post_id_to_delete = $_GET['id'];
    
    // 4. Run a DELETE SQL query
    // We use a '?' placeholder for security
    $sql = "DELETE FROM forum_posts WHERE post_id = ?";
    $stmt = $conn->prepare($sql);
    
    // 'bind_param' securely attaches our variable to the '?'
    // "i" means the variable is an "integer"
    $stmt->bind_param("i", $post_id_to_delete);
    
    // 5. Execute the delete query
    if ($stmt->execute()) {
        // 6. Redirect back to the community list on success
        header("Location: manage_community.php?success=deleted");
    } else {
        // If the database has an error
        echo "Error deleting record: " . $stmt->error;
    }
    
    // Close the statement
    $stmt->close();
    
} else {
    // If no 'id=' was in the URL, just go back to the list.
    header("Location: manage_community.php?error=NoID");
}

// Close the database connection
$conn->close();
?>
