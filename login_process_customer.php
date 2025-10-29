<?php
/*
==================================================================
FILE: login_process_customer.php
PURPOSE: This is the "brain" for the login.php form.
         It has NO HTML. It only processes data.
HOW IT WORKS:
1. It starts a "session" (the server's memory).
2. It gets the email and password the user typed in the form.
3. It connects to the database.
4. It "SELECTs" the user from the 'users' table that matches the email.
5. If it finds the user, it uses 'password_verify()' to check if
   the typed password matches the secure hash stored in the database.
6. If the password is correct, it saves the user's info (like
   their ID and name) into the session, marking them as "logged in".
7. It "redirects" the user to the community hub.
==================================================================
*/

// 1. Start a session
// A session is how the server "remembers" who a user is
// as they move from page to page. This *must* be called
// before any session variables are used.
session_start();

// 3. Connect to the database
// This gives us the '$conn' variable to talk to our database.
include 'db_connect.php';

// 2. Get the email/password from the form
// This 'if' block checks that the data was actually sent via "POST"
// from our form.
if(isset($_POST['email']) && isset($_POST['password'])) {

    // Store the typed-in data into variables
    $email = $_POST['email'];
    $password = $_POST['password']; // This is the plain-text password

    // 4. Find a user with that email
    // We use a "Prepared Statement" (?) for security.
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    
    // "s" means we are binding a "string" (text).
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    // 5. Check if a user was found
    // If num_rows is 1, we found exactly one matching user.
    if ($result->num_rows == 1) {
        
        // 'fetch_assoc()' pulls all the user's data from the
        // database into a variable called '$user'.
        $user = $result->fetch_assoc();

        // 6. Check if the password hash matches
        // This is the *most important* security check.
        // 'password_verify()' securely compares the plain-text $password
        // from the form with the hashed password ($user['password'])
        // from the database.
        if (password_verify($password, $user['password'])) {
            // === PASSWORD IS CORRECT! ===

            // 7. Save their login status in the session
            // We are now saving their "login ticket" in the
            // server's memory.
            $_SESSION['user_logged_in'] = true;
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['user_firstname'] = $user['firstname'];
            
            // This pulls the EcoPoints from the database and saves
            // them, fulfilling a key project requirement.
            $_SESSION['user_ecopoints'] = $user['ecopoints'];

            // 8. Send them to the community hub!
            // 'header()' redirects the user to a new page.
            header("Location: community.php");
            exit(); // Always 'exit()' after a redirect.

        } else {
            // === PASSWORD IS WRONG ===
            // The email was right, but the password was wrong.
            // Send them back to the login page with an error message.
            header("Location: login.php?error=IncorrectPassword");
            exit();
        }

    } else {
        // === EMAIL NOT FOUND ===
        // 'num_rows' was 0, so no user with that email exists.
        header("Location: login.php?error=UserNotFound");
        exit();
    }

} else {
    // If someone tries to visit this page directly in their
    // browser, just send them back to the login page.
    header("Location: login.php");
    exit();
}
?>
