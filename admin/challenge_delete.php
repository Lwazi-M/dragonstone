<?php
/*
==================================================================
FILE: admin/challenge_delete.php
PURPOSE: This script deletes a challenge. It has NO HTML.
==================================================================
*/

// 1. Include security and database connection
session_start();
if( !isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true ) {
    die("Access Denied.");
}
if ($_SESSION['admin_role'] !== 'Admin') {
    die("Access Denied.");
}

include '../db_connect.php';

// 2. Check if an 'id' was sent in the URL
if (isset($_GET['id'])) {
    
    $challenge_id = (int)$_GET['id'];
    
    // 3. Run a DELETE query
    // We will also delete any 'user_challenges' links
    // (This is a good idea, but for simplicity, we'll just delete the main one)
    // NOTE: A better way is to set ON DELETE CASCADE in the database.
    // For now, we must delete from the "child" table first.
    
    $sql_child = "DELETE FROM user_challenges WHERE challenge_id = ?";
    $stmt_child = $conn->prepare($sql_child);
    $stmt_child->bind_param("i", $challenge_id);
    $stmt_child->execute();
    $stmt_child->close();
    
    // Now delete from the "parent" table
    $sql_parent = "DELETE FROM challenges WHERE challenge_id = ?";
    $stmt_parent = $conn->prepare($sql_parent);
    $stmt_parent->bind_param("i", $challenge_id);
    
    if ($stmt_parent->execute()) {
        // 4. Redirect back to the list
        header("Location: manage_challenges.php?success=deleted");
    } else {
        echo "Error deleting record: " . $stmt_parent->error;
    }
    
    $stmt_parent->close();
    
} else {
    header("Location: manage_challenges.php?error=NoID");
}

$conn->close();
?>
