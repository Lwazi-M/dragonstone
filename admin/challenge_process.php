<?php
/*
==================================================================
FILE: admin/challenge_process.php
PURPOSE: This script saves new or updated challenges.
         It has NO HTML.
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

include '../db_connect.php'; // Get the database connection

// 2. Get all the data from the form
$title = $_POST['title'];
$description = $_POST['description'];
$reward = (int)$_POST['reward'];
$duration = (int)$_POST['duration'];

// 3. Check if the action is "add" or "edit"
if (isset($_GET['action'])) {
    
    if ($_GET['action'] == 'add') {
        // 4. If "add", run an INSERT SQL query
        
        $sql = "INSERT INTO challenges (title, description, ecopoints_reward, duration_days) 
                VALUES (?, ?, ?, ?)";
        
        $stmt = $conn->prepare($sql);
        // "ssii" = string, string, integer, integer
        $stmt->bind_param("ssii", $title, $description, $reward, $duration);

    } elseif ($_GET['action'] == 'edit' && isset($_GET['id'])) {
        // 5. If "edit", run an UPDATE SQL query
        
        $challenge_id = (int)$_GET['id'];
        
        $sql = "UPDATE challenges SET 
                    title = ?, 
                    description = ?, 
                    ecopoints_reward = ?, 
                    duration_days = ? 
                WHERE challenge_id = ?";
        
        $stmt = $conn->prepare($sql);
        // "ssiii" = string, string, integer, integer, integer (for challenge_id)
        $stmt->bind_param("ssiii", $title, $description, $reward, $duration, $challenge_id);
    }

    // 6. Execute the query
    if ($stmt->execute()) {
        // 7. Redirect back to the list
        header("Location: manage_challenges.php?success=true");
    } else {
        echo "Error: " . $stmt->error;
    }
    
    $stmt->close();
}

$conn->close();
?>
