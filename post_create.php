<?php
// We need to start the session to make sure
// a customer is logged in before they can post.
session_start();

// Check if the user is logged in
if( !isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true ) {
    // If not, redirect to the login page
    header("Location: login.php?error=NotLoggedIn");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create New Post - DragonStone</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <header>
        <nav class="navbar"> 
            <a href="index.php" class="nav-logo">DragonStone</a>
            <ul class="nav-menu">
                <li class="nav-item"><a href="index.php" class="nav-link">Shop</a></li>
                <li class="nav-item"><a href="community.php" class="nav-link">Community</a></li>
                <li class="nav-item"><a href="about.php" class="nav-link">About</a></li>
            </ul>
            <div class="nav-icons">
                <a href="profile.php" class="icon-link">UserIcon</a> 
                <a href="cart.php" class="icon-link">CartIcon</a>
                <a href="logout_customer.php" class="icon-link">Logout</a>
            </div>
        </nav>
    </header>

    <main>
        <div class="form-container">
            <form action="post_process.php" method="POST">
                <h2>Create a New Community Post</h2>
                
                <div class="form-group">
                    <label for="title">Post Title</label>
                    <input type="text" id="title" name="title" required>
                </div>

                <div class="form-group">
                    <label for="category">Category</label>
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
                    <textarea id="content" name="content" rows="8" required></textarea>
                </div>
                
                <button type="submit" class="btn btn-primary form-btn">Submit Post</button>
            </form>
        </div>
    </main>

    <footer>
        <p>&copy; 2025 DragonStone. All rights reserved.</p>
    </footer>

</body>
</html>