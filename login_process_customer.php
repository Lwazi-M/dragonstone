<?php
/*
This is login_process_customer.php
It has NO HTML.
Its job is to:
1. Start a session (to remember the customer).
2. Get the email/password from the form.
3. Connect to the database.
4. Find a user with that email in the 'users' table.
5. Check if the password hash matches.
6. If yes, save their "login" status in the session
   and send them to the community hub.
7. If no, send them back to the login page.
*/

// 1. Start a session
// This is a *different* session from the admin one.
session_start();

// 3. Connect to the database
include 'db_connect.php';

// 2. Get the email/password from the form
if(isset($_POST['email']) && isset($_POST['password'])) {

    $email = $_POST['email'];
    $password = $_POST['password'];

    // 4. Find a user with that email
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    // 5. Check if a user was found
    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();

        // 6. Check if the password hash matches
        if (password_verify($password, $user['password'])) {
            // === PASSWORD IS CORRECT! ===

            // 7. Save their login status in the session
            $_SESSION['user_logged_in'] = true;
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['user_firstname'] = $user['firstname'];
            $_SESSION['user_ecopoints'] = $user['ecopoints'];

            // 8. Send them to the community hub!
            // We'll create this page next.
            header("Location: community.php");
            exit();

        } else {
            // === PASSWORD IS WRONG ===
            header("Location: login.php?error=IncorrectPassword");
            exit();
        }

    } else {
        // === EMAIL NOT FOUND ===
        header("Location: login.php?error=UserNotFound");
        exit();
    }

} else {
    // If someone tries to visit this page directly
    header("Location: login.php");
    exit();
}
?>