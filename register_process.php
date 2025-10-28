<?php
/*
This is register_process.php
It has NO HTML.
Its job is to:
1. Connect to the database.
2. Get the form data (firstname, lastname, email, password).
3. Check if the email is already in use.
4. If not, securely hash the password.
5. Insert the new user into the 'users' table.
6. Redirect them to the login page.
*/

// 1. Connect to the database
include 'db_connect.php';

// 2. Get the form data
if (isset($_POST['firstname']) && isset($_POST['lastname']) && isset($_POST['email']) && isset($_POST['password'])) {
    
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];
    $plainPassword = $_POST['password'];

    // 3. Check if the email is already in use
    $sql_check = "SELECT user_id FROM users WHERE email = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("s", $email);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();

    if ($result_check->num_rows > 0) {
        // Email already exists
        header("Location: register.php?error=EmailTaken");
        exit();
    }
    $stmt_check->close();

    // 4. If email is new, securely hash the password
    $hashedPassword = password_hash($plainPassword, PASSWORD_DEFAULT);

    // 5. Insert the new user into the 'users' table
    // Note: Their 'ecopoints' will default to 0, as we set in the database.
    $sql_insert = "INSERT INTO users (firstname, lastname, email, password) VALUES (?, ?, ?, ?)";
    $stmt_insert = $conn->prepare($sql_insert);
    $stmt_insert->bind_param("ssss", $firstname, $lastname, $email, $hashedPassword);

    if ($stmt_insert->execute()) {
        // 6. Success! Redirect them to the login page.
        header("Location: login.php?success=Registered");
        exit();
    } else {
        // Something went wrong with the database
        header("Location: register.php?error=DatabaseError");
        exit();
    }

    $stmt_insert->close();
    $conn->close();

} else {
    // If data wasn't sent, just go back to the form.
    header("Location: register.php");
    exit();
}
?>