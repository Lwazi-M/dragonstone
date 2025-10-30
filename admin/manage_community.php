<?php
/*
==================================================================
FILE: admin/manage_community.php
PURPOSE: This page displays a list of all customer forum posts
         and allows certain roles to delete them.
HOW IT WORKS:
1. It includes the 'admin_header.php' (security/layout).
2. It does a [CRITICAL] role-based security check, allowing
   *only* 'Admin' or 'CommunityModerator' to proceed.
   An 'OrderManager' will be denied access.
3. It connects to the database.
4. It runs a 'SELECT' query *with a JOIN* to get all posts
   AND the first/last name of the user who wrote them.
5. It displays this data in an HTML table.
6. It provides a 'Delete' link for each post.
==================================================================
*/

// 1. Include the security header
include 'admin_header.php';

// 2. Include the database connection
include '../db_connect.php'; // Go "up" one folder

/*
==================================================================
3. [CRITICAL REQUIREMENT CHECK]
   This is the *role-based* security check.
   We allow 'Admin' OR 'CommunityModerator' to see this page.
   An 'OrderManager' will be stopped by the die() command.
==================================================================
*/
if ($_SESSION['admin_role'] !== 'Admin' && $_SESSION['admin_role'] !== 'CommunityModerator') {
    die("Access Denied. You do not have permission to manage the community.");
}

/*
==================================================================
4. [IMPROVEMENT] Fetch all forum posts *and* their authors.
   We use an "INNER JOIN" to connect 'forum_posts' (aliased as 'p')
   with the 'users' table (aliased as 'u') where the user IDs match.
   This lets us get the author's name for the table.
==================================================================
*/
$sql = "SELECT p.post_id, p.title, p.category, p.created_at, u.firstname, u.lastname 
        FROM forum_posts AS p
        JOIN users AS u ON p.user_id = u.user_id 
        ORDER BY p.created_at DESC";

$result = $conn->query($sql);
?>

<!-- This is the main content area -->
<div class="page-content">
    <div class="page-header">
        <h2>Manage Community Posts</h2>
        <!-- 
        No "Add New" button here, because admins don't create
        customer posts. They only moderate.
        -->
    </div>

    <!-- This table will display all the posts -->
    <table class="data-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Author</th> <!-- Added this new column -->
                <th>Category</th>
                <th>Date Posted</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // 5. Loop through the results and display each post
            if ($result && $result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row['post_id'] . "</td>";
                    echo "<td>" . htmlspecialchars($row['title']) . "</td>";
                    // Display the author's name from the JOIN
                    echo "<td>" . htmlspecialchars($row['firstname']) . " " . htmlspecialchars($row['lastname']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['category']) . "</td>";
                    echo "<td>" . $row['created_at'] . "</td>";
                    echo "<td class='table-actions'>";
                    // Admins and Mods can only delete posts
                    echo "    <a href='post_delete.php?id=" . $row['post_id'] . "' class='btn-delete'>Delete</a>";
                    echo "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='6'>No community posts found.</td></tr>";
            }
            // 6. Close the database connection
            $conn->close();
            ?>
        </tbody>
    </table>

</div>

<?php
// 7. Include the reusable admin footer
// This closes the </body> and </html> tags
include 'admin_footer.php'; 
?>
