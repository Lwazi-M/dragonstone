<?php
/*
==================================================================
FILE: register.php
PURPOSE: This page shows a form for new customers to create an account.
HOW IT WORKS:
1. It defines a custom page title ("Register").
2. It includes the 'header.php' template.
3. It shows the main HTML <form> for registration.
4. It includes the 'footer.php' template.
==================================================================
*/

// 1. Set the page title
// This $pageTitle variable will be "read" by header.php
$pageTitle = "Register - DragonStone";

// 2. Include the reusable header
// This prints the <head> section and the smart navigation bar.
include 'header.php';
?>

<!--
The <main> tag holds all the content that is *unique* to this page.
-->
<main>
    <!--
    This 'form-container' <div> is a wrapper for our form.
    We already created styles for '.form-container' in 'style.css',
    so this form will look good automatically.
    -->
    <div class="form-container">
        
        <!--
        This is the HTML <form> element.
        'action="register_process.php"' tells the browser to send
        all the data to the 'register_process.php' file when
        the "Create Account" button is clicked.
        
        'method="POST"' is a security measure. It sends the form data
        in the background, NOT in the URL (which 'GET' would do).
        You should ALWAYS use "POST" for passwords and personal info.
        -->
        <form action="register_process.php" method="POST">
            <h2>Create Your Account</h2>
            
            <!-- This is a standard form group for the First Name -->
            <div class="form-group">
                <label for="firstname">First Name</label>
                <!--
                'id="firstname"' links this input to the <label>
                'name="firstname"' is the *key* that PHP will use to
                get the value (e.g., $_POST['firstname']).
                'required' is an HTML5 rule that stops the user
                from submitting the form if this field is empty.
                -->
                <input type="text" id="firstname" name="firstname" required>
            </div>

            <!-- Form group for the Last Name -->
            <div class="form-group">
                <label for="lastname">Last Name</label>
                <input type="text" id="lastname" name="lastname" required>
            </div>
            
            <!-- Form group for the Email -->
            <div class="form-group">
                <label for="email">Email Address</label>
                <!-- 'type="email"' makes mobile phones show the '@' key -->
                <input type="email" id="email" name="email" required>
            </div>
            
            <!-- Form group for the Password -->
            <div class="form-group">
                <label for="password">Password</label>
                <!-- 'type="password"' hides the characters as the user types -->
                <input type="password" id="password" name="password" required>
            </div>
            
            <!--
            'type="submit"' tells the browser that this button
            submits the <form> it is inside of.
            -->
            <button type="submit" class="btn btn-primary form-btn">Create Account</button>

            <!-- This is a simple link to the login page -->
            <p class="form-switch">
                Already have an account? <a href="login.php">Login here</a>
            </p>
        </form>
    </div>
</main>
<!-- This is the end of the unique content for this page. -->

<?php
// 3. Include the reusable footer
// This prints the <footer> and closes the <body> and <html> tags.
include 'footer.php';
?>
