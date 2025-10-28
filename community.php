<?php
/*
This is community.php
Its job is to:
1. Start the customer session.
2. Check if the user is logged in. If not, kick them to login.php.
3. Fetch all posts from the 'forum_posts' table.
4. Display the community hub page.
*/

// 1. Start the session
session_start();

// 2. Check if the user is logged in
if( !isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true ) {
    // If not, redirect to the login page
    header("Location: login.php?error=NotLoggedIn");
    exit();
}

// 3. Connect to the database to fetch posts
include 'db_connect.php';

// We need to get the post AND the user's name who wrote it.
// We use a "JOIN" to connect 'forum_posts' with the 'users' table.
$sql = "SELECT forum_posts.*, users.firstname, users.lastname 
        FROM forum_posts 
        JOIN users ON forum_posts.user_id = users.user_id 
        ORDER BY forum_posts.created_at DESC";

$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Community Hub - DragonStone</title>
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
        <div class="community-header">
            <div class="welcome-message">
                <h2>Welcome back, <?php echo $_SESSION['user_firstname']; ?>!</h2>
                <p>Keep making a difference in your community</p>
            </div>
            <div class="ecopoints-display">
                <span class="points-icon">🏆</span>
                Your EcoPoints
                <span class="points-balance"><?php echo $_SESSION['user_ecopoints']; ?></span>
            </div>
        </div>

        <div class="community-container">
            <div class="community-tabs">
                <button class="tab-link" onclick="openTab('Challenges')">Eco Challenges</button>
                <button class="tab-link active" onclick="openTab('Forum')">Community Forum</button>
            </div>

            <div id="Challenges" class="tab-content">
                <h3>Active Challenges</h3>
                <div class="challenge-grid">
                    <div class="challenge-card">
                        </div>
                </div>
            </div>

            <div id="Forum" class="tab-content" style="display:block;">
                <div class="forum-header">
                    <h3>Community Posts</h3>
                    <a href="post_create.php" class="btn btn-primary">+ New Post</a>
                </div>
                
                <div class="forum-post-list">
                    <?php
                    // 4. Loop through the posts and display them
                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            echo '<div class="post-card">';
                            echo '  <div class="post-category">' . $row['category'] . '</div>';
                            echo '  <h4 class="post-title">' . $row['title'] . '</h4>';
                            echo '  <p class="post-content">' . $row['content'] . '</p>';
                            echo '  <div class="post-meta">';
                            echo '      by ' . $row['firstname'] . ' ' . $row['lastname'];
                            echo '      <span>' . $row['created_at'] . '</span>';
                            echo '  </div>';
                            echo '</div>';
                        }
                    } else {
                        echo "<p>No community posts yet. Be the first to post!</p>";
                    }
                    $conn->close();
                    ?>
                </div>
            </div>
        </div>
    </main>

    <footer>
        <p>&copy; 2025 DragonStone. All rights reserved.</p>
    </footer>

    <script>
        function openTab(tabName) {
            var i;
            var tabs = document.getElementsByClassName("tab-content");
            for (i = 0; i < tabs.length; i++) {
                tabs[i].style.display = "none";
            }
            var tabLinks = document.getElementsByClassName("tab-link");
            for (i = 0; i < tabLinks.length; i++) {
                tabLinks[i].className = tabLinks[i].className.replace(" active", "");
            }
            document.getElementById(tabName).style.display = "block";
            event.currentTarget.className += " active";
        }
    </script>

</body>
</html>