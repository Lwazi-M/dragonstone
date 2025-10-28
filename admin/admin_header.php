<?php
/*
This is admin_header.php.
Its job is to:
1. Start the session (to remember the user).
2. Check if the user is *actually* logged in.
3. If not, kick them out to the login page.
4. If they *are* logged in, show the admin navigation.
*/

// 1. Start the session
session_start();

// 2. Check if the 'admin_logged_in' session variable exists AND is true
if( !isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true ) {
    
    // 3. If they are NOT logged in, kick them out
    header("Location: login.php?error=NotLoggedIn");
    exit();
}

// 4. If they ARE logged in, we continue and show the HTML.
// The script 'remembers' who they are using $_SESSION['admin_username']
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - DragonStone</title>
    <link rel="stylesheet" href="style_admin.css">
</head>
<body>

    <div class="admin-wrapper">
        
        <nav class="admin-sidebar">
            <h3 class="sidebar-title">DragonStone Admin</h3>
            <ul>
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="manage_products.php">Manage Products</a></li>
                <li><a href="manage_users.php">Manage Users</a></li>
                <li><a href="manage_community.php">Manage Community</a></li>
            </ul>
            <div class="sidebar-footer">
                <a href="logout.php" class="logout-button">Logout</a>
            </div>
        </nav>

        <main class="admin-main">
            <header class="admin-header">
                <p>Welcome, <strong><?php echo $_SESSION['admin_username']; ?></strong> (Role: <?php echo $_SESSION['admin_role']; ?>)</p>
            </header>
            
            ```

---

### Step 12: Create the `dashboard.php` Page

Now, creating the dashboard is incredibly simple. We just include our header and add a little content.

1.  Inside your `admin` folder, create a new file named `dashboard.php`.
2.  Paste this code into it.

```php
<?php
// This is the first line of the file.
// It runs the "security check" header we just made.
include 'admin_header.php';
?>

<div class="page-content">
    <h2>Dashboard</h2>
    <p>Welcome to the admin panel. From here you can manage all parts of the website.</p>

    <div class="dashboard-stats">
        <div class="stat-card">
            <h4>Total Products</h4>
            <p>4</p> </div>
        <div class="stat-card">
            <h4>Total Admin Users</h4>
            <p>1</p> </div>
        <div class="stat-card">
            <h4>Community Posts</h4>
            <p>0</p> </div>
    </div>

</div>

</main> </div> </body>
</html>