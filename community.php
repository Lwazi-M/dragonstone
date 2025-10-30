<?php
/*
==================================================================
FILE: community.php
PURPOSE: This is the main hub for logged-in customers.
==================================================================
*/

// 1. Set the page title *before* including the header
$pageTitle = "Community Hub - DragonStone";

// 2. Include the reusable header
// This file *MUST* be included first.
// It calls session_start() for us, which fixes the error.
include 'header.php';

// 3. SECURITY CHECK: Is the user logged in?
if( !isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true ) {
    header("Location: login.php?error=NotLoggedIn");
    exit(); // Stop the script
}

// 4. Connect to the database
include 'db_connect.php';

// 5. Get the user's ID from the session
$user_id = $_SESSION['user_id'];

/*
==================================================================
SECTION 5: FETCH ALL DATA FOR THE PAGE
==================================================================
*/

// --- Query 1: Fetch all *available* Eco Challenges ---
$sql_challenges = "SELECT * FROM challenges ORDER BY ecopoints_reward DESC";
$result_challenges = $conn->query($sql_challenges);
$all_challenges = array();
while($row = $result_challenges->fetch_assoc()) {
    $all_challenges[] = $row;
}

// --- Query 2: Fetch all challenges this *user* has *joined* ---
// We just need the IDs for a quick check.
$sql_joined = "SELECT challenge_id FROM user_challenges WHERE user_id = ?";
$stmt_joined = $conn->prepare($sql_joined);
$stmt_joined->bind_param("i", $user_id);
$stmt_joined->execute();
$result_joined = $stmt_joined->get_result();

$joined_challenge_ids = array();
while($row = $result_joined->fetch_assoc()) {
    // We add the ID as a "key" for easy lookup
    $joined_challenge_ids[$row['challenge_id']] = true;
}
$stmt_joined->close();


// --- Query 3: Fetch all *forum posts* with author names ---
$sql_posts = "SELECT forum_posts.*, users.firstname, users.lastname 
              FROM forum_posts 
              JOIN users ON forum_posts.user_id = users.user_id 
              ORDER BY forum_posts.created_at DESC";
$result_posts = $conn->query($sql_posts);

// We are done fetching data for now
$conn->close();
?>

<main>
    <div class="community-header">
        <div class="welcome-message">
            <h2>Welcome back, <?php echo htmlspecialchars($_SESSION['user_firstname']); ?>!</h2>
            <p>Keep making a difference in your community</p>
        </div>
        <div class="ecopoints-display">
            <span class="points-icon">🏆</span>
            Your EcoPoints
            <span class="points-balance"><?php echo htmlspecialchars($_SESSION['user_ecopoints']); ?></span>
        </div>
    </div>

    <div class="community-container">
        
        <div class="community-tabs">
            <button class="tab-link active" onclick="openTab('Challenges')">Eco Challenges</button>
            <button class="tab-link" onclick="openTab('Forum')">Community Forum</button>
        </div>

        <div id="Challenges" class="tab-content" style="display:block;">
            <h3>Active Challenges</h3>
            <p>Join challenges to earn EcoPoints and make an impact.</p>
            <br>
            <div class="challenge-grid">
                <?php
                // Loop through all available challenges
                if (empty($all_challenges)) {
                    echo "<p>No Eco Challenges are active right now. Check back soon!</p>";
                } else {
                    foreach ($all_challenges as $challenge) {
                ?>
                        <div class="challenge-card">
                            <div class="challenge-points">+ <?php echo $challenge['ecopoints_reward']; ?> pts</div>
                            <h3><?php echo htmlspecialchars($challenge['title']); ?></h3>
                            <p><?php echo htmlspecialchars($challenge['description']); ?></p>
                            <div class="challenge-meta">
                                <span><?php echo $challenge['duration_days']; ?> days</span>
                            </div>
                            
                            <?php
                            // This is the "Join" button logic
                            // We check if the challenge ID exists in the
                            // $joined_challenge_ids array we made earlier.
                            if (isset($joined_challenge_ids[$challenge['challenge_id']])) {
                                // IF YES: Show a disabled "Joined" button
                                echo '<button class="btn btn-disabled" disabled>Joined</button>';
                            } else {
                                // IF NO: Show the "Join Challenge" button
                                // This is a link to the "brain" file.
                                echo '<a href="challenge_join.php?id=' . $challenge['challenge_id'] . '" class="btn btn-primary">Join Challenge</a>';
                            }
                            ?>
                        </div>
                <?php
                    } // end foreach
                } // end if/else
                ?>
            </div>
        </div>

        <div id="Forum" class="tab-content">
            <div class="forum-header">
                <h3>Community Posts</h3>
                <a href="post_create.php" class="btn btn-primary">+ New Post</a>
            </div>
            
            <div class="forum-post-list">
                <?php
                // 4. Loop through the forum posts and display them
                if ($result_posts->num_rows > 0) {
                    while($row = $result_posts->fetch_assoc()) {
                        echo '<div class="post-card">';
                        echo '  <div class="post-category">' . htmlspecialchars($row['category']) . '</div>';
                        echo '  <h4 class="post-title">' . htmlspecialchars($row['title']) . '</h4>';
                        echo '  <p class="post-content">' . nl2br(htmlspecialchars($row['content'])) . '</p>';
                        echo '  <div class="post-meta">';
                        echo '      by ' . htmlspecialchars($row['firstname']) . ' ' . htmlspecialchars($row['lastname']);
                        echo '      <span>' . date('F j, Y, g:i a', strtotime($row['created_at'])) . '</span>';
                        echo '  </div>';
                        echo '</div>';
                    }
                } else {
                    echo "<p>No community posts yet. Be the first to post!</p>";
                }
                ?>
            </div>
        </div>
    </div>
</main>
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