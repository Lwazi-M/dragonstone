<?php
/*
This is login_process.php. It has NO HTML.
Its only job is to process data and redirect the user.

1. Start a "session" (a memory for the server).
2. Get the username/password from the form.
3. Connect to the database.
4. Find a user with that username.
5. Check if the password hash matches.
6. If yes, save their "login" status in the session
   and send them to the dashboard.
7. If no, send them back to the login page.
*/

// 1. Start a session
// A session is how the server "remembers" you are logged in
// as you move from page to page.
session_start();

// 3. Connect to the database
// We are inside the 'admin' folder, so we must go
// 'up' one level ('../') to find the db_connect.php file.
include '../db_connect.php';

// 2. Get the username/password from the form
// We check if the 'username' and 'password' were
// actually sent before we try to use them.
if(isset($_POST['username']) && isset($_POST['password'])) {

    $username = $_POST['username'];
    $password = $_POST['password'];

    // 4. Find a user with that username
    // We use a "prepared statement" (?) to be safe from
    // SQL injection.
    $sql = "SELECT * FROM admin_users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username); // "s" means the data is a string
    $stmt->execute();
    $result = $stmt->get_result();

    // 5. Check if a user was found
    if ($result->num_rows == 1) {
        // A user was found! Let's get their data.
        $user = $result->fetch_assoc();

        // 6. Check if the password hash matches
        // password_verify() is a secure PHP function.
        // It compares the plain-text $password from the form
        // with the secure $user['password'] hash from the database.
        if (password_verify($password, $user['password'])) {
            // === PASSWORD IS CORRECT! ===

            // 7. Save their login status in the session
            // We store this data in the server's "memory"
            // so we can check it on every other admin page.
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_id'] = $user['admin_id'];
            $_SESSION['admin_username'] = $user['username'];
            $_SESSION['admin_role'] = $user['role']; // This is critical!

            // 8. Send them to the dashboard
            // We haven't created this page yet, but we will next.
            header("Location: dashboard.php");
            exit(); // Always 'exit()' after a redirect

        } else {
            // === PASSWORD IS WRONG ===
            // Send them back to the login page
            header("Location: login.php?error=IncorrectPassword");
            exit();
        }

    } else {
        // === USERNAME NOT FOUND ===
        // Send them back to the login page
        header("Location: login.php?error=UserNotFound");
        exit();
    }

} else {
    // If someone tries to visit this page directly
    // without sending data, just send them away.
    header("Location: login.php");
    exit();
}

?>