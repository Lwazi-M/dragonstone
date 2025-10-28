<?php
// 1. Include the security header
include 'admin_header.php';

// 2. Include the database connection
include '../db_connect.php';

/*
[CRITICAL REQUIREMENT CHECK]
This page is for 'Admin' or 'CommunityModerator' roles.
An 'OrderManager' should NOT be able to see this.
*/
if ($_SESSION['admin_role'] !== 'Admin' && $_SESSION['admin_role'] !== 'CommunityModerator') {
    die("Access Denied. You do not have permission to manage the community.");
}

// 3. Fetch all forum posts
// We'll add a JOIN later to get the username, but for now, this is fine.
$sql = "SELECT * FROM forum_posts ORDER BY created_at DESC";
$result = $conn->query($sql);
?>

<div class="page-content">
    <div class="page-header">
        <h2>Manage Community Posts</h2>
        </div>

    <table class="data-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Category</th>
                <th>Date Posted</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // 4. Loop through the results
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row['post_id'] . "</td>";
                    echo "<td>" . $row['title'] . "</td>";
                    echo "<td>" . $row['category'] . "</td>";
                    echo "<td>" . $row['created_at'] . "</td>";
                    echo "<td class='table-actions'>";
                    // Admins and Mods can only delete posts
                    echo "    <a href='post_delete.php?id=" . $row['post_id'] . "' class='btn-delete'>Delete</a>";
                    echo "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='5'>No community posts found.</td></tr>";
            }
            // 5. Close the database connection
            $conn->close();
            ?>
        </tbody>
    </table>

</div>

<?php
// 6. Close the HTML tags from the header
?>
        </main> </div> </body>
</html>