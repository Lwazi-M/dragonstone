<?php
/*
==================================================================
FILE: admin/user_process.php
PURPOSE: This script Creates or Updates an admin user in the
         database. It has NO HTML.
HOW IT WORKS:
1. It starts the session and does a full security check to
   ensure the user is a logged-in 'Admin'.
2. It includes the database connection.
3. It gets the form data (username, role, password) from $_POST.
4. It checks the URL for "?action=add" or "?action=edit".
5. IF "ADD":
   - It securely hashes the new password.
   - It runs an 'INSERT' query to create the user.
6. IF "EDIT":
   - It checks if the password field was left blank.
   - If *blank*, it runs an 'UPDATE' query for *only*
     the username and role.
   - If *filled*, it hashes the *new* password and runs an
     'UPDATE' query for username, role, *and* password.
7. It redirects the admin back to the 'manage_users.php' list.
==================================================================
*/

// 1. Include security
// We must start the session to check the 'admin_role'
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

// 3. Get all the data from the $_POST form
$username = $_POST['username'];
$role = $_POST['role'];
$password = $_POST['password']; // This might be blank

// 2. Check if the action is "add" or "edit"
if (isset($_GET['action'])) {
    
    // --- THIS IS THE "ADD" LOGIC ---
    if ($_GET['action'] == 'add') {
        
        // 4. Action is "add". We MUST have a password.
        if (empty($password)) {
            // Stop the script if the password field was empty
            die("Error: Password is required when adding a new user.");
        }
        
        // Securely hash the new password. This is a one-way
        // scramble. You can't un-hash it.
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        
        // 5. Run an INSERT SQL query
        // We use '?' placeholders for security
        $sql = "INSERT INTO admin_users (username, password, role) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        
        // 'bind_param' securely attaches our variables to the '?'
        // "sss" means all three variables are "strings"
        $stmt->bind_param("sss", $username, $hashedPassword, $role);

    // --- THIS IS THE "EDIT" LOGIC ---
    } elseif ($_GET['action'] == 'edit' && isset($_GET['id'])) {
        
        $user_id = $_GET['id']; // Get the ID from the URL
        
        // 4. Action is "edit". Password is OPTIONAL.
        // We check if the user typed anything in the password box.
        if (!empty($password)) {
            // --- A NEW PASSWORD *WAS* PROVIDED ---
            
            // Hash the *new* password
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            
            // 6. Run an UPDATE query *with* the new password
            $sql = "UPDATE admin_users SET username = ?, password = ?, role = ? WHERE admin_id = ?";
            $stmt = $conn->prepare($sql);
            // "sssi" = string, string, string, integer (for the user_id)
            $stmt->bind_param("sssi", $username, $hashedPassword, $role, $user_id);
            
        } else {
            // --- THE PASSWORD FIELD WAS *BLANK* ---
            
            // 6. Run an UPDATE query *without* changing the password
            // We *only* update the username and role.
            $sql = "UPDATE admin_users SET username = ?, role = ? WHERE admin_id = ?";
            $stmt = $conn->prepare($sql);
            // "ssi" = string, string, integer
            $stmt->bind_param("ssi", $username, $role, $user_id);
        }
    }

    // Execute the query (whichever one we built: INSERT, UPDATE, or UPDATE-no-password)
    if ($stmt->execute()) {
        // 7. Redirect back to the user list
        header("Location: manage_users.php?success=true");
    } else {
        echo "Error: " . $stmt->error;
    }
    
    // Close the statement
    $stmt->close();
}

// Close the database connection
$conn->close();
?>
