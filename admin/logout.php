<?php
/*
==================================================================
FILE: admin/logout.php
PURPOSE: This script logs out the admin. It has NO HTML.
HOW IT WORKS:
1. It starts the session so it can find the
   session "memory" to destroy.
2. It "unsets" (clears) all session variables.
3. It fully "destroys" the session.
4. It redirects the user back to the admin login page.
==================================================================
*/

// 1. Start the session
// We MUST start the session to be able to access it and destroy it.
session_start();

// 2. "Unset" all session variables
// This empties the $_SESSION array.
$_SESSION = array();

// 3. "Destroy" the session
// This removes the session file from the server.
session_destroy();

// 4. Redirect to the login page
// We send the admin back to the login screen.
header("Location: login.php?success=loggedout");
exit(); // Always 'exit()' after a redirect to stop the script.
?>
