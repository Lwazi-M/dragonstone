<?php
/*
==================================================================
FILE: post_process.php
PURPOSE: This is the "brain" for the post_create.php form.
         It has NO HTML.
HOW IT WORKS:
1. It starts the session to find out *who* is posting.
2. It checks if the user is actually logged in (security).
3. It connects to the database.
4. It gets the post data (title, category, content) from the form.
5. It gets the user's ID from the session.
6. It runs a secure "INSERT" query to add the post.
7. *** IT ADDS ECOPOINTS TO THE USER'S ACCOUNT. ***
8. It redirects the user back to the community page.
==================================================================
*/

// 1. Start the session
session_start();

// 2. Check if the user is logged in
if( !isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true ) {
    die("Access Denied. You must be logged in to post.");
}

// 3. Connect to the database
include 'db_connect.php';

// 4. Get the post data from the form
if (isset($_POST['title']) && isset($_POST['category']) && isset($_POST['content'])) {
    
    $title = $_POST['title'];
    $category = $_POST['category'];
    $content = $_POST['content'];
    
    // 5. Get the user's ID from the session
    $user_id = $_SESSION['user_id'];

    // 6. Insert the new post into the 'forum_posts' table
    $sql_insert = "INSERT INTO forum_posts (user_id, title, category, content) VALUES (?, ?, ?, ?)";
    $stmt_insert = $conn->prepare($sql_insert);
    // "isss" = integer (user_id), string, string, string
    $stmt_insert->bind_param("isss", $user_id, $title, $category, $content);

    // 7. Execute the INSERT query
    if ($stmt_insert->execute()) {
        // --- SUCCESS! The post is saved. ---
        
        // ==========================================================
        // *** NEW ECOPOINTS LOGIC ***
        //
        // Now, let's award points for posting.
        // Let's give 10 points for a new post.
        // ==========================================================
        $points_for_post = 10;
        
        // This query finds the user and adds 10 to their current points
        $sql_points = "UPDATE users SET ecopoints = ecopoints + ? WHERE user_id = ?";
        
        $stmt_points = $conn->prepare($sql_points);
        // "ii" = integer (points_for_post), integer (user_id)
        $stmt_points->bind_param("ii", $points_for_post, $user_id);
        $stmt_points->execute();
        $stmt_points->close();

        // **IMPORTANT**: We must also update the session "memory",
        // otherwise the user won't see their new points until
        // they log out and log back in.
        $_SESSION['user_ecopoints'] += $points_for_post;
        
        // 8. Redirect them back to the community hub.
        header("Location: community.php?success=Posted");
        exit();

    } else {
        // Something went wrong
        header("Location: post_create.php?error=DatabaseError");
        exit();
    }

    $stmt_insert->close();
    $conn->close();

} else {
    // If data wasn't sent, just go back to the form.
    header("Location: post_create.php");
    exit();
}
?>