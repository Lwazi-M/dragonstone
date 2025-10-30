<?php
/*
==================================================================
FILE: challenge_join.php
PURPOSE: This script "joins" a user to a challenge.
         It has NO HTML.
HOW IT WORKS:
1. It starts the session and checks if the user is logged in.
2. It gets the 'challenge_id' from the URL.
3. It gets the 'user_id' from the session.
4. It checks if the user has *already* joined this challenge.
5. If not, it runs an 'INSERT' query to link the
   user and the challenge in the 'user_challenges' table.
6. It redirects the user back to the community page.
==================================================================
*/

// 1. Start the session and check login
session_start();
if( !isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true ) {
    die("Access Denied. You must be logged in to join.");
}

// 2. Get Challenge ID from URL
if (isset($_GET['id'])) {
    
    $challenge_id = (int)$_GET['id'];
    $user_id = (int)$_SESSION['user_id'];

    // 3. Connect to database
    include 'db_connect.php';

    // 4. Check if user has *already* joined this challenge
    $sql_check = "SELECT user_challenge_id FROM user_challenges 
                  WHERE user_id = ? AND challenge_id = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("ii", $user_id, $challenge_id);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();

    if ($result_check->num_rows == 0) {
        // 5. IF NOT JOINED: Insert the new link
        
        $sql_insert = "INSERT INTO user_challenges (user_id, challenge_id, status) 
                       VALUES (?, ?, 'joined')";
        $stmt_insert = $conn->prepare($sql_insert);
        $stmt_insert->bind_param("ii", $user_id, $challenge_id);
        $stmt_insert->execute();
        $stmt_insert->close();
    }
    // If they *have* already joined, we just ignore it.
    
    $stmt_check->close();
    $conn->close();

    // 6. Redirect back to the community page
    header("Location: community.php?success=joined_challenge");
    exit();

} else {
    // If no ID was sent, just go back.
    header("Location: community.php");
    exit();
}
?>