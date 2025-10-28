<?php
/*
This is post_delete.php
It has NO HTML.
Its job is to:
1. Include security and database connection.
2. Check for the correct role ('Admin' or 'CommunityModerator').
3. Check if an 'id' was sent in the URL.
4. If yes, run a DELETE SQL query for that post ID.
5. Redirect the user back to the manage_community.php list.
*/

// 1. Include security and database connection
session_start();
if( !isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true ) {
    die("Access Denied. You must be logged in.");
}

// 2. [CRITICAL REQUIREMENT CHECK]
// Only 'Admin' or 'CommunityModerator' can delete posts.
if ($_SESSION['admin_role'] !== 'Admin' && $_SESSION['admin_role'] !== 'CommunityModerator') {
    die("Access Denied. You do not have permission to manage the community.");
}

include '../db_connect.php';

// 3. Check if an 'id' was sent in the URL
if (isset($_GET['id'])) {
    
    $post_id_to_delete = $_GET['id'];
    
    // 4. Run a DELETE SQL query
    $sql = "DELETE FROM forum_posts WHERE post_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $post_id_to_delete);
    
    if ($stmt->execute()) {
        // 5. Redirect back to the community list on success
        header("Location: manage_community.php?success=deleted");
    } else {
        echo "Error deleting record: " . $stmt->error;
    }
    
    $stmt->close();
    
} else {
    // If no ID was sent, just go back
    header("Location: manage_community.php?error=NoID");
}

$conn->close();
?>