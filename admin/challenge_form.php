<?php
/*
==================================================================
FILE: admin/challenge_form.php
PURPOSE: This is the "smart" form for both Creating and
         Updating Eco Challenges.
==================================================================
*/

// 1. Include the security header and database connection
include 'admin_header.php';
include '../db_connect.php';

// 2. [CRITICAL REQUIREMENT CHECK]
// Only 'Admin' role can access this page
if ($_SESSION['admin_role'] !== 'Admin') {
    die("Access Denied. You do not have permission to manage challenges.");
}

// 3. Initialize variables
$title = "";
$description = "";
$reward = 50; // Default reward
$duration = 7; // Default duration
$challenge_id = 0;
$form_action = "challenge_process.php?action=add"; // Default action is "add"
$page_title = "Add New Challenge";

// 4. Check if this is an "edit" action
if (isset($_GET['action']) && $_GET['action'] == 'edit' && isset($_GET['id'])) {
    
    $challenge_id = $_GET['id'];
    $page_title = "Edit Challenge";
    $form_action = "challenge_process.php?action=edit&id=" . $challenge_id;

    // 5. Fetch the existing challenge data
    $sql = "SELECT * FROM challenges WHERE challenge_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $challenge_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows == 1) {
        $challenge = $result->fetch_assoc();
        // 6. Fill variables with data from the database
        $title = $challenge['title'];
        $description = $challenge['description'];
        $reward = $challenge['ecopoints_reward'];
        $duration = $challenge['duration_days'];
    }
    $stmt->close();
}
$conn->close();
?>

<div class="page-content">
    <div class="page-header">
        <h2><?php echo $page_title; ?></h2>
    </div>

    <!-- This form sends its data to 'challenge_process.php' -->
    <form action="<?php echo $form_action; ?>" method="POST" class="data-form">
        
        <div class="form-group">
            <label for="title">Challenge Title</label>
            <!-- We use htmlspecialchars() for security when echoing values -->
            <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($title); ?>" required>
        </div>

        <div class="form-group">
            <label for="description">Description</label>
            <textarea id="description" name="description" rows="5" required><?php echo htmlspecialchars($description); ?></textarea>
        </div>

        <div class="form-group">
            <label for="reward">EcoPoints Reward</label>
            <input type="number" id="reward" name="reward" value="<?php echo htmlspecialchars($reward); ?>" required>
        </div>

        <div class="form-group">
            <label for="duration">Duration (in days)</label>
            <input type="number" id="duration" name="duration" value="<?php echo htmlspecialchars($duration); ?>" required>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-add">Save Challenge</button>
            <a href="manage_challenges.php" class="btn-cancel">Cancel</a>
        </div>
        
    </form>
</div>

<?php
// 7. Include the reusable admin footer
include 'admin_footer.php';
?>
