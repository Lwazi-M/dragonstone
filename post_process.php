<?php
/*
This is post_process.php
It has NO HTML.
Its job is to:
1. Start the session to find out *who* is posting.
2. Check if the user is actually logged in.
3. Connect to the database.
4. Get the post data (title, category, content) from the form.
5. Get the user's ID from the session.
6. Insert the new post into the 'forum_posts' table.
7. Redirect the user back to the community page.
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
    $sql = "INSERT INTO forum_posts (user_id, title, category, content) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    // "isss" = integer (user_id), string, string, string
    $stmt->bind_param("isss", $user_id, $title, $category, $content);

    if ($stmt->execute()) {
        // 7. Success! Redirect them back to the community hub.
        // We could also add EcoPoints here! (We'll do that later)
        header("Location: community.php?success=Posted");
        exit();
    } else {
        // Something went wrong
        header("Location: post_create.php?error=DatabaseError");
        exit();
    }

    $stmt->close();
    $conn->close();

} else {
    // If data wasn't sent, just go back to the form.
    header("Location: post_create.php");
    exit();
}
?>