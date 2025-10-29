<?php
/*
==================================================================
FILE: login.php
PURPOSE: Shows a login form for *customers*.
HOW IT WORKS:
1. It sets the $pageTitle variable (which 'header.php' will use).
2. It includes the reusable 'header.php'.
3. It shows the HTML login form.
4. The form sends its data to 'login_process_customer.php'.
5. It includes the reusable 'footer.php'.
==================================================================
*/

// 1. Set the page title
// This variable will be used by 'header.php'
$pageTitle = "Login - DragonStone";

// 2. Include the reusable header
// This prints the DOCTYPE, <head>, and the nav bar.
// Because the user is not logged in yet, the header
// will *automatically* show "Login" and "Register".
include 'header.php';
?>

<!--
The <main> tag holds all the content that is *unique* to this page.
The '.form-container' class is a reusable style we
created for the login/register forms.
-->
<main>
    <div class="form-container">
        
        <!-- 
        This form sends its data to 'login_process_customer.php'
        using the secure 'POST' method.
        -->
        <form action="login_process_customer.php" method="POST">
            <h2>Login to Your Account</h2>
            
            <?php
            // This is a small bonus script.
            // If the login fails (from 'login_process_customer.php'),
            // it redirects back here with a message in the URL, like "?error=UserNotFound".
            // This code checks for that error and shows a friendly message.
            if (isset($_GET['error'])) {
                if ($_GET['error'] == 'IncorrectPassword') {
                    echo '<p class="form-error">Error: Incorrect password. Please try again.</p>';
                } elseif ($_GET['error'] == 'UserNotFound') {
                    echo '<p class="form-error">Error: No account found with that email address.</p>';
                }
            }
            // This checks for a *success* message from registering
            if (isset($_GET['success']) && $_GET['success'] == 'Registered') {
                 echo '<p class="form-success">Registration successful! Please log in.</p>';
            }
            ?>
            
            <div class="form-group">
                <label for="email">Email Address</label>
                <!-- 
                'name="email"' is the "key" that 'login_process_customer.php'
                will use to get the value from this input.
                'required' makes the browser stop submission if it's empty.
                -->
                <input type="email" id="email" name="email" required>
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <!-- 'type="password"' hides the characters as the user types. -->
                <input type="password" id="password" name="password" required>
            </div>
            
            <!-- 'type="submit"' tells the browser this button submits the form -->
            <button type="submit" class="btn btn-primary form-btn">Login</button>

            <p class="form-switch">
                Don't have an account? <a href="register.php">Register here</a>
            </p>
        </form>
    </div>
</main>
<!-- This is the end of the unique content for this page. -->

<?php
// 3. Include the reusable footer
// This will print the <footer> and close the <body> and <html> tags.
include 'footer.php';
?>
