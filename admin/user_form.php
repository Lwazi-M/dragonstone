<?php
// 1. Include the security header and database connection
include 'admin_header.php';
include '../db_connect.php';

// [CRITICAL REQUIREMENT CHECK]
// Only 'Admin' role can access this page
if ($_SESSION['admin_role'] !== 'Admin') {
    die("Access Denied. You do not have permission to manage users.");
}

// 2. Initialize variables
$username = "";
$role = ""; // Default role
$user_id = 0;
$form_action = "user_process.php?action=add"; // Default action is "add"
$page_title = "Add New Admin User";

// 3. Check if this is an "edit" action
if (isset($_GET['action']) && $_GET['action'] == 'edit' && isset($_GET['id'])) {
    
    $user_id = $_GET['id'];
    $page_title = "Edit Admin User";
    $form_action = "user_process.php?action=edit&id=" . $user_id;

    // 4. Fetch the existing user data from the database
    $sql = "SELECT username, role FROM admin_users WHERE admin_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        // 5. Assign database values to our variables
        $username = $user['username'];
        $role = $user['role'];
    }
    $stmt->close();
}
$conn->close();
?>

<div class="page-content">
    <div class="page-header">
        <h2><?php echo $page_title; ?></h2>
    </div>

    <form action="<?php echo $form_action; ?>" method="POST" class="data-form">
        
        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" value="<?php echo $username; ?>" required>
        </div>

        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" <?php echo ($action == 'add') ? 'required' : ''; ?>>
            <?php if ($action == 'edit'):