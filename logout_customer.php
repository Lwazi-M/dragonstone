<?php
/*
This is logout_customer.php
Its job is to:
1. Start the session (so it can find it).
2. "Unset" all the session variables (log them out).
3. "Destroy" the session entirely (clean up).
4. Redirect the user to the homepage (index.php).
*/

// 1. Start the session
session_start();

// 2. "Unset" all session variables
$_SESSION = array();

// 3. "Destroy" the session
session_destroy();

// 4. Redirect to the homepage
header("Location: index.php?success=loggedout");
exit(); // Always exit after a redirect
?>