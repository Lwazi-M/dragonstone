<?php
/*
==================================================================
FILE: admin/user_form.php
PURPOSE: This is a "smart" form for both Creating and
         Updating *admin users*.
HOW IT WORKS:
1. It includes the 'admin_header.php' (security/layout).
2. It includes a *second* security check to ensure
   ONLY the 'Admin' role can see this page.
3. It initializes empty variables (e.g., $username = "").
4. It checks the URL for "?action=edit".
5. IF "EDIT":
   - It fetches that user's data from 'admin_users'.
   - It fills the variables ($username, $role) with the
     data from the database.
   - It changes the page title to "Edit User".
   - It changes the form's 'action' attribute to point to
     'user_process.php?action=edit&id=...'.
6. IF "ADD" (default):
   - The variables stay empty.
   - The title stays "Add New User".
   - The form 'action' stays 'user_process.php?action=add'.
7. The HTML form then pre-fills all the fields.
==================================================================
*/

// 1. Include the security header and database connection
include 'admin_header.php';
include '../db_connect.php';

/*
==================================================================
2. [CRITICAL REQUIREMENT CHECK]
   This is the *role-based* security check.
   Only a user with the 'Admin' role can create or edit
   other users.
==================================================================
*/
if ($_SESSION['admin_role'] !== 'Admin') {
    die("Access Denied. You do not have permission to manage users.");
}

// 3. Initialize variables
$username = "";
$role = ""; // Default role
$user_id = 0;
$form_action = "user_process.php?action=add"; // Default action is "add"
$page_title = "Add New Admin User";
$action = "add"; // This variable helps us change the <input> tag

// 4. Check if this is an "edit" action
if (isset($_GET['action']) && $_GET['action'] == 'edit' && isset($_GET['id'])) {
    
    $action = "edit"; // We are in 'edit' mode
    $user_id = $_GET['id'];
    $page_title = "Edit Admin User";
    $form_action = "user_process.php?action=edit&id=" . $user_id;

    // 5. Fetch the existing user data from the database
    // We *do not* fetch the password. Only username and role.
    $sql = "SELECT username, role FROM admin_users WHERE admin_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        // 6. Assign database values to our variables
        $username = $user['username'];
        $role = $user['role'];
    }
    $stmt->close();
}
// We are done with the database, so we close the connection.
$conn->close();
?>

<!-- This is the main content area -->
<div class="page-content">
    <div class="page-header">
        <!-- The title is dynamic: "Add" or "Edit" -->
        <h2><?php echo $page_title; ?></h2>
    </div>

    <!-- 
    This form sends its data to the $form_action URL.
    This is the "data-form" we styled in style_admin.css
    -->
    <form action="<?php echo $form_action; ?>" method="POST" class="data-form">
        
        <div class="form-group">
            <label for="username">Username</label>
            <!-- 
            SECURITY FIX: We use htmlspecialchars() to prevent XSS attacks,
            just like on the product form.
            -->
            <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($username); ?>" required>
        </div>

        <div class="form-group">
            <label for="password">Password</label>
            <!-- 
            This is a "smart" input.
            If 'action' is "add", we make the field 'required'.
            If 'action' is "edit", it's *not* required.
            -->
            <input type="password" id="password" name="password" <?php echo ($action == 'add') ? 'required' : ''; ?>>
            
            <?php if ($action == 'edit'): // Show this help text *only* on the edit page ?>
                <small>Leave blank to keep the current password.</small>
            <?php endif; ?>
        </div>

        <!-- 
        =========================================
        CRITICAL FEATURE: Role Management
        =========================================
        This dropdown fulfills the requirement to assign
        different roles to different users. [cite: ITECA3-34 – Project – Deliverable 1 – Project Proposal Block 3 2025 (V1.0)READY.docx]
        -->
        <div class="form-group">
            <label for="role">Role</label>
            <select id="role" name="role" required>
                <!-- 
                The PHP 'if' statements inside the <option> tags
                will 'select' the correct role when editing.
                e.g., if $role == 'Admin', it will print 'selected'.
                -->
                <option value="Admin" <?php echo ($role == 'Admin') ? 'selected' : ''; ?>>
                    Admin (Full Access)
                </option>
                <option value="OrderManager" <?php echo ($role == 'OrderManager') ? 'selected' : ''; ?>>
                    Order Manager (Manages orders/products)
                </option>
                <option value="CommunityModerator" <?php echo ($role == 'CommunityModerator') ? 'selected' : ''; ?>>
                    Community Moderator (Manages forum)
                </option>
            </select>
        </div>

        <!-- The "Submit" and "Cancel" buttons -->
        <div class="form-actions">
            <button type="submit" class="btn-add">Save User</button>
            <a href="manage_users.php" class="btn-cancel">Cancel</a>
        </div>
        
    </form>
</div>

<?php
// 7. Include the reusable admin footer
// This closes the </body> and </html> tags
include 'admin_footer.php'; 
?>
