<?php
/*
==================================================================
FILE: admin/login.php
PURPOSE: This is the HTML form for the *admin* login.
HOW IT WORKS:
1. It's a simple HTML page with a form.
2. It does *not* include 'admin_header.php' because the
   user is NOT logged in yet.
3. The form's 'action' is 'login_process.php', which is
   the PHP script that will check the user's details.
4. The 'method="POST"' is important for security, as it
   hides the password from the browser's URL.
==================================================================
*/

// Start a session just to be able to show error messages
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DragonStone - Admin Login</title>
    <!-- 
    This links to the admin-specific stylesheet.
    It will use the .login-container styles.
    -->
    <link rel="stylesheet" href="style_admin.css">
</head>
<!-- 
We add the 'login-body' class here.
In 'style_admin.css', this class is used
to center the login form perfectly in the middle
of the screen (using flexbox).
-->
<body class="login-body">

    <!-- 
    This is the white box that holds the form.
    It is styled by '.login-container' in 'style_admin.css'.
    -->
    <div class="login-container">

        <!-- 
        This is the main login form.
        - 'action="login_process.php"' tells the form where to
          send the data when the "Login" button is clicked.
        - 'method="POST"' sends the data securely.
        -->
        <form action="login_process.php" method="POST">
            <h2>Admin Login</h2>

            <?php
            // This PHP block checks the URL for any error messages
            // that 'login_process.php' or 'admin_header.php' might have sent.
            // Example: ...login.php?error=NotLoggedIn
            if (isset($_GET['error'])) {
                $errorMsg = "";
                if ($_GET['error'] == "IncorrectPassword") {
                    $errorMsg = "Incorrect username or password.";
                } elseif ($_GET['error'] == "UserNotFound") {
                    $errorMsg = "Incorrect username or password.";
                } elseif ($_GET['error'] == "NotLoggedIn") {
                    $errorMsg = "You must be logged in to view that page.";
                }
                // We use our pre-styled 'form-error' class from style.css
                // (Whoops, we need to add that to style_admin.css!)
                echo '<div class="form-error">' . $errorMsg . '</div>';
            }
            
            // This shows a success message after logging out
            if (isset($_GET['success']) && $_GET['success'] == "loggedout") {
                echo '<div class="form-success">You have been logged out.</div>';
            }
            ?>
            
            <!-- This is the 'Username' field -->
            <div class="input-group">
                <label for="username">Username</label>
                <!-- 
                'name="username"' is the "key".
                'login_process.php' will use $_POST['username']
                to get the value the user typed here.
                'required' means the browser won't let the user
                submit the form if this field is empty.
                -->
                <input type="text" id="username" name="username" required>
            </div>
            
            <!-- This is the 'Password' field -->
            <!-- I fixed a typo here: it was 'classs' and is now 'class' -->
            <div class="input-group">
                <label for="password">Password</label>
                <!-- 
                'type="password"' hides the characters (shows ••••).
                'name="password"' is the "key" for the password.
                -->
                <input type="password" id="password" name="password" required>
            </div>
            
            <!-- 
            'type="submit"' tells the browser this button
            will submit the form it is inside of.
            -->
            <button type="submit" class="btn-login">Login</button>
        </form>
    </div>

</body>
</html>
