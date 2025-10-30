<?php
/*
==================================================================
FILE: admin/manage_challenges.php
PURPOSE: Displays a list of all Eco Challenges (Read in CRUD).
         Only the 'Admin' can access this.
==================================================================
*/

// 1. Include the security header
include 'admin_header.php';

// 2. Include the database connection
include '../db_connect.php';

// 3. [CRITICAL REQUIREMENT CHECK]
// Only 'Admin' role can create or manage challenges.
if ($_SESSION['admin_role'] !== 'Admin') {
    die("Access Denied. You do not have permission to manage challenges.");
}

// 4. Fetch all challenges from the new 'challenges' table
$sql = "SELECT * FROM challenges ORDER BY challenge_id DESC";
$result = $conn->query($sql);
?>

<div class="page-content">
    <div class="page-header">
        <h2>Manage Eco Challenges</h2>
        <a href="challenge_form.php?action=add" class="btn-add">+ Add New Challenge</a>
    </div>

    <!-- This table will display all the challenges -->
    <table class="data-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Reward (Points)</th>
                <th>Duration (Days)</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // 5. Loop through the results and display each challenge
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row['challenge_id'] . "</td>";
                    echo "<td>" . htmlspecialchars($row['title']) . "</td>";
                    echo "<td>" . $row['ecopoints_reward'] . "</td>";
                    echo "<td>" . $row['duration_days'] . "</td>";
                    echo "<td class='table-actions'>";
                    // Links for editing and deleting
                    echo "    <a href='challenge_form.php?action=edit&id=" . $row['challenge_id'] . "' class='btn-edit'>Edit</a>";
                    echo "    <a href='challenge_delete.php?id=" . $row['challenge_id'] . "' class='btn-delete'>Delete</a>";
                    echo "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='5'>No challenges found. Create one!</td></tr>";
            }
            // 6. Close the database connection
            $conn->close();
            ?>
        </tbody>
    </table>

</div>

<?php
// 7. Include the reusable admin footer
include 'admin_footer.php'; 
?>
