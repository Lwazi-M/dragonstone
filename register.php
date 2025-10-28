<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - DragonStone</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <header>
        <nav class="navbar"> 
            <a href="index.php" class="nav-logo">DragonStone</a>
            <ul class="nav-menu">
                <li class="nav-item"><a href="index.php" class="nav-link">Shop</a></li>
                <li class="nav-item"><a href="community.php" class="nav-link">Community</a></li>
                <li class="nav-item"><a href="about.php" class="nav-link">About</a></li>
            </ul>
            <div class="nav-icons">
                <a href="login.php" class="icon-link">UserIcon</a>
                <a href="cart.php" class="icon-link">CartIcon</a>
            </div>
        </nav>
    </header>

    <main>
        <div class="form-container">
            <form action="register_process.php" method="POST">
                <h2>Create Your Account</h2>
                
                <div class="form-group">
                    <label for="firstname">First Name</label>
                    <input type="text" id="firstname" name="firstname" required>
                </div>

                <div class="form-group">
                    <label for="lastname">Last Name</label>
                    <input type="text" id="lastname" name="lastname" required>
                </div>
                
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" required>
                </div>
                
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>
                
                <button type="submit" class="btn btn-primary form-btn">Create Account</button>

                <p class="form-switch">
                    Already have an account? <a href="login.php">Login here</a>
                </p>
            </form>
        </div>
    </main>

    <footer>
        <p>&copy; 2025 DragonStone. All rights reserved.</p>
    </footer>

</body>
</html>