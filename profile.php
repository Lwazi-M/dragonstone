<?php
/*
==================================================================
FILE: profile.php
PURPOSE: This is the customer's personal profile page.
HOW IT WORKS:
1. It sets the custom page title.
2. It includes 'header.php' (which starts the session).
3. It does a security check to ensure the user is logged in.
4. It fetches *all* of that user's info from the database.
5. It displays that info in a clean layout.
6. It includes 'footer.php'.
==================================================================
*/

// 1. Set the page title *before* including the header
$pageTitle = "My Profile - DragonStone";

// 2. Include the reusable header
// This file calls session_start() and displays the nav bar
include 'header.php';

// 3. SECURITY CHECK: Is the user logged in?
// This is a protected page.
// We do this check *after* the header is included.
if( !isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true ) {
    header("Location: login.php?error=NotLoggedIn");
    exit(); // Stop the script
}

// 4. Connect to the database to get *fresh* user data
// We get the user's ID from the session we saved at login
$user_id = $_SESSION['user_id'];

include 'db_connect.php';

// Fetch the user's details (name, email, points)
$sql_user = "SELECT firstname, lastname, email, ecopoints, created_at FROM users WHERE user_id = ?";
$stmt_user = $conn->prepare($sql_user);
$stmt_user->bind_param("i", $user_id);
$stmt_user->execute();
$result_user = $stmt_user->get_result();
$user = $result_user->fetch_assoc();
$stmt_user->close();

// Fetch all the user's community posts
$sql_posts = "SELECT * FROM forum_posts WHERE user_id = ? ORDER BY created_at DESC";
$stmt_posts = $conn->prepare($sql_posts);
$stmt_posts->bind_param("i", $user_id);
$stmt_posts->execute();
$result_posts = $stmt_posts->get_result();

$conn->close();
?>

<main class="page-container">

    <div class="page-header">
        <h1>My Profile</h1>
    </div>

    <div class="profile-layout">
        
        <div class="profile-sidebar">
            <div class="profile-card">
                <h3><?php echo htmlspecialchars($user['firstname']) . ' ' . htmlspecialchars($user['lastname']); ?></h3>
                <p><?php echo htmlspecialchars($user['email']); ?></p>
                <p>Member Since: <?php echo date('F j, Y', strtotime($user['created_at'])); ?></p>
                <hr>
                <div class="ecopoints-display-small">
                    <span class="points-icon">🏆</span>
                    <span class="points-balance"><?php echo htmlspecialchars($user['ecopoints']); ?></span>
                    EcoPoints
                </div>
            </div>
            
            <a href="logout_customer.php" class="btn-logout-profile">Logout</a>
        </div>

        <div class="profile-content">
            
            <div class="profile-section">
                <h2>My Community Posts</h2>
                <div class="forum-post-list">
                    <?php
                    // Loop through the posts we fetched
                    if ($result_posts->num_rows > 0) {
                        while($row = $result_posts->fetch_assoc()) {
                            echo '<div class="post-card">';
                            echo '  <div class="post-category">' . htmlspecialchars($row['category']) . '</div>';
                            echo '  <h4 class="post-title">' . htmlspecialchars($row['title']) . '</h4>';
                            echo '  <p class="post-content">' . nl2br(htmlspecialchars($row['content'])) . '</p>';
                            echo '  <div class="post-meta">';
                            echo '      <span>' . date('F j, Y, g:i a', strtotime($row['created_at'])) . '</span>';
                            echo '  </div>';
                            echo '</div>';
                        }
                    } else {
                        // This message shows if the user has no posts
                        echo "<p>You have not made any community posts yet.</p>";
                    }
                    ?>
                </div>
            </div>

            <div class="profile-section">
                <h2>My Order History</h2>
                <p>Your past orders will be displayed here.</p>
            </div>

        </div>
    </div>
</main>
<?php
// 7. Include the reusable footer
include 'footer.php';
?>