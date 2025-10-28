<?php
/*
This is a temporary helper script.
Its only job is to create a secure, hashed password
that we can copy and paste into our database.
*/

// CHOOSE YOUR PASSWORD HERE:
$plainTextPassword = 'admin_password_123'; // Change this to a strong password

// This is the modern, secure way to hash a password in PHP.
$hashedPassword = password_hash($plainTextPassword, PASSWORD_DEFAULT);

// This will print the hash to the screen.
echo 'Your plain password is: ' . $plainTextPassword . '<br>';
echo 'Your SECURE HASH (copy this) is: <br>';
echo $hashedPassword;

?>