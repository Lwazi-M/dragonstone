<?php
/*
This is a PHP script. The '<?php ... ?>' tags tell the server
that this code needs to be "run" on the server, not just sent
to the user's browser.

This file's only job is to connect to our MySQL database.
We will "include" this file in all other PHP pages
that need to talk to the database.
*/

// === DATABASE LOGIN DETAILS ===
// These are the default login details for XAMPP.
// When we move to InfinityFree (the live host),
// we will have to change these details.

$servername = "localhost"; // The server is "localhost" (our own computer)
$username = "root";        // The default XAMPP username is "root"
$password = "";            // The default XAMPP password is "" (nothing)
$dbname = "dragonstone_db"; // The database name we created in phpMyAdmin

// === CREATE THE CONNECTION ===
// The line below tries to create a new "connection" object
// using the login details we just defined.
$conn = new mysqli($servername, $username, $password, $dbname);

// === CHECK THE CONNECTION ===
// This 'if' statement checks if the connection failed.
if ($conn->connect_error) {
    /*
    If the connection *did* fail (e.g., wrong password),
    the 'die()' function will stop the entire website from loading
    and show an error message. This is good for development
    so we can see what broke.
    */
    die("Database Connection Failed: " . $conn->connect_error);
}

/*
If the script gets this far, it means the connection
was successful! It doesn't show anything, but the
variable '$conn' is now our "golden ticket"
to the database.
*/

?>