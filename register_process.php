<?php
/*
==================================================================
FILE: register_process.php
PURPOSE: This is the "brain" for the register.php form.
         It has NO HTML. It only processes data.
HOW IT WORKS:
1. It "includes" the database connection file.
2. It checks if the form was actually submitted (using 'isset').
3. It gets the data (name, email, password) from the '$_POST' variable.
4. It checks if the email *already exists* in the 'users' table.
5. If it's a new email, it "hashes" (encrypts) the password.
6. It 'INSERTs' the new user into the 'users' table.
7. It "redirects" the user to the login page so they can sign in.
==================================================================
*/

// 1. Connect to the database
// This gives us the '$conn' variable to talk to our database.
include 'db_connect.php';

// 2. Get the form data
// 'isset($_POST['firstname'])' checks if the 'firstname' data was
// sent from the form. This whole 'if' block prevents someone
// from visiting this .php file directly in their browser.
if (isset($_POST['firstname']) && isset($_POST['lastname']) && isset($_POST['email']) && isset($_POST['password'])) {
    
    // Store the data from the form into simple variables
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];
    $plainPassword = $_POST['password']; // The password as the user typed it

    /*
    ------------------------------------------------------------------
    SECTION 3: Check if Email is Already in Use
    ------------------------------------------------------------------
    We need to do this because our 'email' column is set to 'UNIQUE'.
    If we try to INSERT a duplicate, the database will give an error.
    */
    
    // 'SELECT user_id FROM users WHERE email = ?' is our SQL question.
    // The '?' is a secure placeholder.
    $sql_check = "SELECT user_id FROM users WHERE email = ?";
    $stmt_check = $conn->prepare($sql_check);
    
    // 'bind_param("s", $email)' securely attaches the $email variable
    // to the '?' placeholder. "s" means the variable is a "string" (text).
    $stmt_check->bind_param("s", $email);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();

    // 'num_rows' is the number of rows (users) the database found.
    // If it's > 0, it means that email is already taken.
    if ($result_check->num_rows > 0) {
        // Email already exists.
        // 'header()' redirects the user back to the register page.
        // We add '?error=EmailTaken' to the URL so the page
        // could (in the future) show a "That email is taken" message.
        header("Location: register.php?error=EmailTaken");
        exit(); // 'exit()' stops the script from running any more.
    }
    // We're done with this check, so we close it.
    $stmt_check->close();

    /*
    ------------------------------------------------------------------
    SECTION 4: Create the New User
    ------------------------------------------------------------------
    If the script gets this far, the email is new and we can
    create the account.
    */

    // 4. If email is new, securely hash the password
    // This is the MOST IMPORTANT security part.
    // 'password_hash()' turns 'password123' into a long,
    // unreadable string like '$2y$10$...'
    // We store the HASH in the database, NEVER the plain password.
    $hashedPassword = password_hash($plainPassword, PASSWORD_DEFAULT);

    // 5. Insert the new user into the 'users' table
    // Note: Their 'ecopoints' will default to 0, as we set in the database.
    $sql_insert = "INSERT INTO users (firstname, lastname, email, password) VALUES (?, ?, ?, ?)";
    $stmt_insert = $conn->prepare($sql_insert);
    
    // 'bind_param("ssss", ...)' means we are binding 4 strings.
    $stmt_insert->bind_param("ssss", $firstname, $lastname, $email, $hashedPassword);

    // 6. 'execute()' runs the 'INSERT' command.
    if ($stmt_insert->execute()) {
        // 7. Success! Redirect them to the login page.
        // We add '?success=Registered' to the URL.
        header("Location: login.php?success=Registered");
        exit();
    } else {
        // Something went wrong with the database
        header("Location: register.php?error=DatabaseError");
        exit();
    }

    // Close our connections
    $stmt_insert->close();
    $conn->close();

} else {
    // If data wasn't sent (e.g., direct URL visit),
    // just send them back to the register form.
    header("Location: register.php");
    exit();
}
?>
