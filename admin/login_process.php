<?php
/*
==================================================================
FILE: admin/login_process.php
PURPOSE: This is the "brain" for the admin login form.
         It has NO HTML. It only processes data.
HOW IT WORKS:
1. Starts a "session" (the server's memory).
2. Gets the 'username' and 'password' from the form.
3. Connects to the database.
4. Securely finds a user in the 'admin_users' table
   that matches the provided 'username'.
5. If a user is found, it securely compares their
   hashed password with the password that was typed in.
6. If the password is correct, it "logs them in" by
   saving their info (like ID and role) to the session.
7. It then redirects them to the 'dashboard.php'.
8. If the username or password is wrong, it redirects
   them back to 'login.php' with an error message.
==================================================================
*/

// 1. Start a session
// A session is how the server "remembers" you are logged in
// as you move from page to page.
session_start();

// 3. Connect to the database
// We are inside the 'admin' folder, so we must go "up"
// one level ('../') to find the 'db_connect.php' file
// in the main (root) folder.
include '../db_connect.php';

// 2. Get the username/password from the form
// We first check if the data 'is set' (if it was actually sent).
// This prevents errors if someone visits this file directly.
if (isset($_POST['username']) && isset($_POST['password'])) {

    // Store the form data in simple variables
    $username = $_POST['username'];
    $password = $_POST['password'];

    // 4. Find a user with that username
    // We write our SQL query using a '?' as a placeholder.
    // This is a "prepared statement" and is a critical
    // security feature to prevent SQL Injection attacks.
    $sql = "SELECT * FROM admin_users WHERE username = ?";
    
    // Tell the database to "prepare" this query
    $stmt = $conn->prepare($sql);
    
    // "Bind" our $username variable to the '?'
    // The "s" means the variable is a "string".
    $stmt->bind_param("s", $username);
    
    // "Execute" (run) the query
    $stmt->execute();
    
    // Get the results from the database
    $result = $stmt->get_result();

    // 5. Check if a user was found (if we got 1 row back)
    if ($result->num_rows == 1) {
        
        // A user was found! Get all their data from that row.
        $user = $result->fetch_assoc();

        // 6. Check if the password hash matches
        // This is the *second* critical security feature.
        // 'password_verify()' securely compares the
        // plain-text password from the form ($password)
        // with the secure hash stored in the database ($user['password']).
        if (password_verify($password, $user['password'])) {
            
            // === PASSWORD IS CORRECT! ===

            // 7. Save their login status in the session "memory"
            // We can now access these variables on any other admin page.
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_id'] = $user['admin_id'];
            $_SESSION['admin_username'] = $user['username'];
            
            // [PROJECT REQUIREMENT]
            // This is the most important session variable.
            // It stores the user's role ('Admin', 'OrderManager', etc.)
            // so we can check permissions on other pages.
            $_SESSION['admin_role'] = $user['role'];

            // 8. Send them to the admin dashboard
            // 'header()' is a PHP function that redirects the browser.
            header("Location: dashboard.php");
            exit(); // 'exit()' is crucial. It stops the script
                    // from running any further after a redirect.

        } else {
            // === PASSWORD IS WRONG ===
            // Redirect back to the login page with an error message
            // in the URL (?error=...).
            header("Location: login.php?error=IncorrectPassword");
            exit();
        }

    } else {
        // === USERNAME NOT FOUND ===
        // We send the *same* error message as above.
        // This is a security best-practice: never tell the
        // user *which* part was wrong (username or password).
        header("Location: login.php?error=UserNotFound");
        exit();
    }

} else {
    // If someone tries to visit this page directly
    // without sending data, just send them away.
    header("Location: login.php");
    exit();
}

// We don't need to close the connection, as 'exit()'
// will stop the script.
?>
