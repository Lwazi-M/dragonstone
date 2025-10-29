<?php
/*
==================================================================
FILE: community.php
PURPOSE: This is the main hub for logged-in customers.
HOW IT WORKS:
1. It starts the customer session.
2. It CHECKS if the user is logged in. If not, it redirects
   them to login.php. This is a "protected" page.
3. It connects to the database and fetches all forum posts,
   using a "JOIN" to get the authors' names.
4. It sets the custom page title.
5. It includes 'header.php'.
6. It displays the EcoPoints header and the forum posts.
7. It includes 'footer.php'.
==================================================================
*/

// 1. Start the session
// This *must* come first to access session variables.
session_start();

// 2. SECURITY CHECK: Is the user logged in?
// 'isset' checks if the 'user_logged_in' variable even exists.
if( !isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true ) {
    // If it doesn't exist, or isn't 'true', the user is not logged in.
    // Kick them to the login page.
    header("Location: login.php?error=NotLoggedIn");
    exit(); // Stop the script
}

// 3. Connect to the database to fetch posts
include 'db_connect.php';

// This SQL query is more advanced. It "JOINS" two tables:
// 'forum_posts' and 'users'.
// It connects them 'ON' the 'user_id' column, which exists in both.
// This lets us get the post's title AND the user's firstname/lastname
// all in one query.
$sql = "SELECT forum_posts.*, users.firstname, users.lastname 
        FROM forum_posts 
        JOIN users ON forum_posts.user_id = users.user_id 
        ORDER BY forum_posts.created_at DESC"; // Get newest posts first

$result = $conn->query($sql);

// 4. Set the page title
$pageTitle = "Community Hub - DragonStone";

// 5. Include the reusable header
// This prints the <head> section and the smart navigation bar.
include 'header.php';
?>

<!--
The <main> tag holds all the content that is *unique* to this page.
-->
<main>
    <!-- 
    =========================================
    CRITICAL FEATURE: EcoPoints Header
    [cite: user's uploaded image 'WhatsApp Image 2025-10-20 at 16.19.45_a31ca8e9.jpg']
    =========================================
    This section uses the session variables we saved during login
    to personalize the page for the user.
    -->
    <div class="community-header">
        <div class="welcome-message">
            <!-- We "echo" (print) the user's name from the session -->
            <h2>Welcome back, <?php echo htmlspecialchars($_SESSION['user_firstname']); ?>!</h2>
            <p>Keep making a difference in your community</p>
        </div>
        <div class="ecopoints-display">
            <span class="points-icon">🏆</span> <!-- Simple emoji icon -->
            Your EcoPoints
            <!-- We "echo" (print) the user's points from the session -->
            <span class="points-balance"><?php echo htmlspecialchars($_SESSION['user_ecopoints']); ?></span>
        </div>
    </div>

    <!-- This container holds the tabs and the content -->
    <div class="community-container">
        
        <!-- Tab Navigation -->
        <div class="community-tabs">
            <!-- This button calls the JavaScript function 'openTab' -->
            <button class="tab-link" onclick="openTab('Challenges')">Eco Challenges</button>
            <button class="tab-link active" onclick="openTab('Forum')">Community Forum</button>
        </div>

        <!-- Tab Content: Eco Challenges -->
        <!-- This tab is hidden by default with CSS ('display: none') -->
        <div id="Challenges" class="tab-content">
            <h3>Active Challenges</h3>
            <div class="challenge-grid">
                <!-- 
                This is a placeholder based on your Figma design.
                [cite: user's uploaded image 'WhatsApp Image 2025-10-20 at 16.19.45_7e4bc52d.jpg']
                Your friend could build this out later by creating
                a 'challenges' table in the database.
                -->
                <div class="challenge-card">
                    <p>Eco Challenges (like "Waste-Free Week") will be shown here.</p>
                </div>
            </div>
        </div>

        <!-- Tab Content: Community Forum -->
        <!-- This tab is SHOWN by default with 'style="display:block;"' -->
        <div id="Forum" class="tab-content" style="display:block;">
            <div class="forum-header">
                <h3>Community Posts</h3>
                <!-- This link goes to the page that lets users create a post -->
                <a href="post_create.php" class="btn btn-primary">+ New Post</a>
            </div>
            
            <div class="forum-post-list">
                <?php
                // 4. Loop through the database results and display them
                if ($result->num_rows > 0) {
                    // 'fetch_assoc()' grabs one post at a time
                    while($row = $result->fetch_assoc()) {
                        // We "echo" (print) the HTML for each post card,
                        // filling it with data from the $row variable.
                        // 'htmlspecialchars()' is a security function to
                        // prevent XSS attacks (it stops users from
                        // injecting bad <script> tags into their posts).
                        echo '<div class="post-card">';
                        echo '  <div class="post-category">' . htmlspecialchars($row['category']) . '</div>';
                        echo '  <h4 class="post-title">' . htmlspecialchars($row['title']) . '</h4>';
                        // 'nl2br()' is a nice function that turns line breaks
                        // (when a user hits "Enter") into <br> tags.
                        echo '  <p class="post-content">' . nl2br(htmlspecialchars($row['content'])) . '</p>';
                        echo '  <div class="post-meta">';
                        // We can show the author's name because of our JOIN
                        echo '      by ' . htmlspecialchars($row['firstname']) . ' ' . htmlspecialchars($row['lastname']);
                        // 'date()' formats the timestamp into a nicer format
                        echo '      <span>' . date('F j, Y, g:i a', strtotime($row['created_at'])) . '</span>';
                        echo '  </div>';
                        echo '</div>';
                    }
                } else {
                    // This message shows if the 'forum_posts' table is empty.
                    echo "<p>No community posts yet. Be the first to post!</p>";
                }
                // We're done with the database, so we close the connection.
                $conn->close();
                ?>
            </div>
        </div>
    </div>
</main>
<!-- This is the end of the unique content for this page. -->

<!-- 
This JavaScript provides the functionality for the tabs.
It's placed at the bottom so the HTML elements
(like the buttons) exist *before* the script tries to find them.
-->
<script>
    function openTab(tabName) {
        var i;
        var tabs = document.getElementsByClassName("tab-content");
        // Loop 1: Hide all tab content
        for (i = 0; i < tabs.length; i++) {
            tabs[i].style.display = "none";
        }
        var tabLinks = document.getElementsByClassName("tab-link");
        // Loop 2: Remove the "active" class from all tab buttons
        for (i = 0; i < tabLinks.length; i++) {
            tabLinks[i].className = tabLinks[i].className.replace(" active", "");
        }
        // Step 3: Show the *one* tab you clicked on
        document.getElementById(tabName).style.display = "block";
        // Step 4: Add the "active" class to the *one* button you clicked
        event.currentTarget.className += " active";
    }
</script>

<?php
// 7. Include the reusable footer
include 'footer.php';
?>
