<?php
/*
==================================================================
FILE: logout_customer.php
PURPOSE: Securely logs out a *customer*.
HOW IT WORKS:
1. It has NO HTML. It only processes a request.
2. It starts the session so it can find the user's "login ticket".
3. It unsets all session variables (clears the "memory box").
4. It fully destroys the session (throws the "memory box" away).
5. It redirects the user back to the homepage.
==================================================================
*/

// 1. Start the session
// We MUST start the session so we can find the one
// we need to destroy.
session_start();

// 2. "Unset" all session variables
// This is a fast and secure way to wipe all data
// from the $_SESSION array (like $_SESSION['user_id'], etc.).
$_SESSION = array();

// 3. "Destroy" the session
// This removes the session from the server,
// fully completing the logout.
session_destroy();

// 4. Redirect to the homepage
// We send the user back to the index.php page.
// We add "?success=loggedout" to the URL, so in the future,
// index.php could (optionally) show a "You have been logged out" message.
header("Location: index.php?success=loggedout");

// 5. exit()
// This is a CRITICAL step. It stops the script from
// running any more code after sending the "Location" header.
exit();
?>
