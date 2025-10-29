<?php
/*
==================================================================
FILE: post_create.php
PURPOSE: Shows a form for a logged-in user to create a new post.
HOW IT WORKS:
1. It starts the session and checks if the user is logged in.
2. If not, it redirects them to login.php.
3. If they *are* logged in, it sets the page title.
4. It includes the reusable 'header.php'.
5. It shows the HTML form.
6. The form sends its data to 'post_process.php'.
7. It includes the reusable 'footer.php'.
==================================================================
*/

// 1. Start the session
// This *must* come first to access session variables.
session_start();

// 2. SECURITY CHECK: Is the user logged in?
// This protects the page from guests.
if( !isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true ) {
    // If not, redirect to the login page
    header("Location: login.php?error=NotLoggedIn");
    exit(); // Stop the script
}

// 3. Set the page title
// This variable will be used by 'header.php'
$pageTitle = "Create New Post - DragonStone";

// 4. Include the reusable header
// This prints the DOCTYPE, <head>, and the nav bar.
// The nav bar will correctly show "Profile" and "Logout"
// because the session is already started.
include 'header.php';
?>

<!--
The <main> tag holds all the content that is *unique* to this page.
The '.form-container' class is a reusable style we
created for the login/register forms, and it works perfectly here.
-->
<main>
    <div class="form-container">
        
        <!-- 
        This form sends its data to 'post_process.php' using the 'POST' method.
        'POST' is more secure than 'GET' because the data is sent in the
        background, not in the URL.
        -->
        <form action="post_process.php" method="POST">
            <h2>Create a New Community Post</h2>
            
            <div class="form-group">
                <label for="title">Post Title</label>
                <!-- 
                'name="title"' is the "key" that 'post_process.php'
                will use to get the value from this input.
                'required' makes the browser stop submission if it's empty.
                -->
                <input type="text" id="title" name="title" required>
            </div>

            <div class="form-group">
                <label for="category">Category</label>
                <!-- A <select> tag is a dropdown menu -->
                <select id="category" name="category" required>
                    <option value="">-- Select a Category --</option>
                    <option value="Kitchen">Kitchen</option>
                    <option value="Cleaning">Cleaning</option>
                    <option value="DIY Projects">DIY Projects</option>
                    <option value="Lifestyle">Lifestyle</option>
                    <option value="Other">Other</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="content">Your Post</label>
                <!-- A <textarea> is used for longer blocks of text -->
                <textarea id="content" name="content" rows="8" required></textarea>
            </div>
            
            <!-- 'type="submit"' tells the browser this button submits the form -->
            <button type="submit" class="btn btn-primary form-btn">Submit Post</button>
        </form>
    </div>
</main>
<!-- This is the end of the unique content for this page. -->

<?php
// 7. Include the reusable footer
// This will print the <footer> and close the <body> and <html> tags.
include 'footer.php';
?>
