<?php
/*
This is user_process.php
It has NO HTML.
Its job is to:
1. Include security and database connection.
2. Check if the action is "add" or "edit".
3. Get all the data from the $_POST form.
4. Securely hash the password (if one was provided).
5. If "add", run an INSERT SQL query.
6. If "edit", run an UPDATE SQL query (maybe without the password).
7. Redirect the user back to the manage_users.php list.
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

include '../db_connect.php'; // Get the database connection

// 3. Get all the data from the $_POST form
$username = $_POST['username'];
$role = $_POST['role'];
$password = $_POST['password'];

// 2. Check if the action is "add" or "edit"
if (isset($_GET['action'])) {
    
    if ($_GET['action'] == 'add') {
        // 4. Action is "add". We MUST have a password.
        if (empty($password)) {
            die("Error: Password is required when adding a new user.");
        }
        
        // Securely hash the new password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        
        // 5. Run an INSERT SQL query
        $sql = "INSERT INTO admin_users (username, password, role) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $username, $hashedPassword, $role); // s = string

    } elseif ($_GET['action'] == 'edit' && isset($_GET['id'])) {
        // 4. Action is "edit". Password is OPTIONAL.
        
        $user_id = $_GET['id'];
        
        if (!empty($password)) {
            // A new password *was* provided. Hash it and update it.
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            
            // 6. Run an UPDATE query *with* the password
            $sql = "UPDATE admin_users SET username = ?, password = ?, role = ? WHERE admin_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssi", $username, $hashedPassword, $role, $user_id);
            
        } else {
            // No new password was provided. Do NOT update the password field.
            
            // 6. Run an UPDATE query *without* the password
            $sql = "UPDATE admin_users SET username = ?, role = ? WHERE admin_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssi", $username, $role, $user_id);
        }
    }

    // Execute the query (either INSERT or UPDATE)
    if ($stmt->execute()) {
        // 7. Redirect back to the user list
        header("Location: manage_users.php?success=true");
    } else {
        echo "Error: " . $stmt->error;
    }
    
    $stmt->close();

}

$conn->close();
?>