<?php
/*
==================================================================
FILE: post_process.php
PURPOSE: This is the "brain" for the post_create.php form.
         It has NO HTML. It only processes data.
HOW IT WORKS:
1. It starts the session to find out *who* is posting.
2. It CHECKS if the user is logged in (security).
3. It connects to the database.
4. It gets the post data (title, category, content) from the $_POST array.
5. It gets the logged-in user's ID from the $_SESSION array.
6. It runs a secure "INSERT" query to add the post to the 'forum_posts' table.
7. It redirects the user back to the community page.
==================================================================
*/

// 1. Start the session
// This *must* come first to access session variables.
session_start();

// 2. SECURITY CHECK: Is the user logged in?
// This stops anyone who isn't logged in from posting.
if( !isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true ) {
    // 'die()' is a hard stop. It ends the script and prints a message.
    die("Access Denied. You must be logged in to post.");
}

// 3. Connect to the database
// This gives us the '$conn' variable.
include 'db_connect.php';

// 4. Get the post data from the form
// This 'if' block checks that the data was actually sent from the form.
if (isset($_POST['title']) && isset($_POST['category']) && isset($_POST['content'])) {
    
    // Store the data from the form into variables.
    $title = $_POST['title'];
    $category = $_POST['category'];
    $content = $_POST['content'];
    
    // 5. Get the user's ID from the session
    // This is the "login ticket" we saved when they logged in.
    // This is how we link the post to the user.
    $user_id = $_SESSION['user_id'];

    // 6. Insert the new post into the 'forum_posts' table
    // We use a "Prepared Statement" (?) to prevent SQL injection.
    $sql = "INSERT INTO forum_posts (user_id, title, category, content) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    
    // "isss" tells the database the "type" of data we are sending:
    // i = integer (for $user_id)
    // s = string (for $title)
    // s = string (for $category)
    // s = string (for $content)
    $stmt->bind_param("isss", $user_id, $title, $category, $content);

    // 7. 'execute()' runs the query.
    if ($stmt->execute()) {
        // --- SUCCESS ---
        // We could also add EcoPoints here in a future update!
        // (e.g., UPDATE users SET ecopoints = ecopoints + 10 WHERE user_id = ?)
        
        // Redirect them back to the community hub.
        header("Location: community.php?success=Posted");
        exit();
    } else {
        // --- FAILURE ---
        // Something went wrong with the database.
        // Send them back to the create page with an error.
        header("Location: post_create.php?error=DatabaseError");
        exit();
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();

} else {
    // If someone tries to visit this page directly without
    // sending form data, just send them back to the form.
    header("Location: post_create.php");
    exit();
}
?>
