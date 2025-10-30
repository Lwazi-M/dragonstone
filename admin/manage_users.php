<?php
/*
==================================================================
FILE: admin/manage_users.php
PURPOSE: This page displays a list of all *admin staff*
         (from the 'admin_users' table).
         This is the "Read" part of your User CRUD.
==================================================================
*/

// 1. Include the security header
// This file does two things:
// - Starts the session
// - Checks if an admin is logged in (if not, kicks to login.php)
// - Displays the admin sidebar and header
include 'admin_header.php';

// 2. Include the database connection
// We are in the 'admin' folder, so we go "up" one level ('../')
include '../db_connect.php';

/*
==================================================================
3. [CRITICAL REQUIREMENT CHECK]
This is the *role-based* security check.
The project requires "proper restrictions" for different roles.
This 'if' statement ensures that ONLY a user with the
role of 'Admin' (which is stored in the session) can
view this page. An 'OrderManager' or 'CommunityModerator'
will be stopped by the 'die()' function.
==================================================================
*/
if ($_SESSION['admin_role'] !== 'Admin') {
    die("Access Denied. You do not have permission to manage users.");
}

// 4. Fetch all admin users from the database
// We select all columns we need.
// Note: We are *not* selecting the password, which is good practice.
$sql = "SELECT admin_id, username, role, created_at FROM admin_users ORDER BY admin_id";
$result = $conn->query($sql);
?>

<!-- 
This is the main content area for this specific page.
The 'admin_header.php' file already opened the
<div class="admin-wrapper"> and <main class="admin-main"> tags.
-->
<div class="page-content">
    
    <!-- This is the title bar for the page -->
    <div class="page-header">
        <h2>Manage Admin Users</h2>
        <!-- This button links to the 'user_form.php' page,
             which is used to create a new user. -->
        <a href="user_form.php?action=add" class="btn-add">+ Add New User</a>
    </div>

    <!-- This table will display all the admin users -->
    <!-- The CSS in 'style_admin.css' will style this table -->
    <table class="data-table">
        <!-- Table Header Row -->
        <thead>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Role</th>
                <th>Member Since</th>
                <th>Actions</th>
            </tr>
        </thead>
        <!-- Table Body (where the data goes) -->
        <tbody>
            <?php
            // 5. Loop through the results and display each user
            // We check if the database returned at least 1 row
            if ($result->num_rows > 0) {
                // The 'while' loop goes through each user, one by one
                while($row = $result->fetch_assoc()) {
                    // 'echo' just means "print this HTML"
                    echo "<tr>";
                    echo "    <td>" . $row['admin_id'] . "</td>";
                    echo "    <td>" . $row['username'] . "</td>";
                    echo "    <td>" . $row['role'] . "</td>";
                    echo "    <td>" . $row['created_at'] . "</td>";
                    echo "    <td class='table-actions'>";
                    
                    // This link goes to the "edit" form, passing the user's ID
                    // so the form knows *who* to edit.
                    echo "        <a href='user_form.php?action=edit&id=" . $row['admin_id'] . "' class='btn-edit'>Edit</a>";
                    
                    // This link goes to the "delete" script, passing the user's ID
                    // so the script knows *who* to delete.
                    echo "        <a href='user_delete.php?id=" . $row['admin_id'] . "' class='btn-delete'>Delete</a>";
                    
                    echo "    </td>";
                    echo "</tr>";
                }
            } else {
                // If there are no users in the database
                echo "<tr><td colspan='5'>No admin users found.</td></tr>";
            }
            // 6. Close the database connection (good practice)
            $conn->close();
            ?>
        </tbody>
    </table>

</div>

<?php
// 7. Include the reusable admin footer
// This file just closes the HTML tags opened in 'admin_header.php'
include 'admin_footer.php'; 
?>
