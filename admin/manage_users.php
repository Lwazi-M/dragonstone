<?php
// 1. Include the security header
include 'admin_header.php';

// 2. Include the database connection
include '../db_connect.php';

/*
[CRITICAL REQUIREMENT CHECK]
This page is for 'Admin' roles *only*.
An 'OrderManager' should NOT be able to create new users.
We add this extra security check.
*/
if ($_SESSION['admin_role'] !== 'Admin') {
    die("Access Denied. You do not have permission to manage users.");
}

// 3. Fetch all admin users from the database
$sql = "SELECT admin_id, username, role, created_at FROM admin_users ORDER BY admin_id";
$result = $conn->query($sql);
?>

<div class="page-content">
    <div class="page-header">
        <h2>Manage Admin Users</h2>
        <a href="user_form.php?action=add" class="btn-add">+ Add New User</a>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Role</th>
                <th>Member Since</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // 4. Loop through the results and display each user
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row['admin_id'] . "</td>";
                    echo "<td>" . $row['username'] . "</td>";
                    echo "<td>" . $row['role'] . "</td>";
                    echo "<td>" . $row['created_at'] . "</td>";
                    echo "<td class='table-actions'>";
                    // Links for editing and deleting
                    echo "    <a href='user_form.php?action=edit&id=" . $row['admin_id'] . "' class='btn-edit'>Edit</a>";
                    echo "    <a href='user_delete.php?id=" . $row['admin_id'] . "' class='btn-delete'>Delete</a>";
                    echo "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='5'>No admin users found.</td></tr>";
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