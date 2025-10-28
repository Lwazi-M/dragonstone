'' blocks are comments. The browser ignores them,
    but they help humans understand the code.
-->

<!DOCTYPE html> 
<html lang="en"> 
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DragonStone - Sustainable Living, Made Simple</title>
    <link rel="stylesheet" href="style.css">
    </head>

<body>
    <header>
        <nav class="navbar"> 
            <a href="index.php" class="nav-logo">
                DragonStone
            </a>

            <ul class="nav-menu">
                <li class="nav-item"><a href="index.php" class="nav-link">Shop</a></li>
                <li class="nav-item"><a href="community.php" class="nav-link">Community</a></li>
                <li class="nav-item"><a href="about.php" class="nav-link">About</a></li>
            </ul>

            <div class="nav-icons">
                <a href="login.php" class="icon-link">UserIcon</a> <a href="cart.php" class="icon-link">CartIcon</a> </div>
        </nav>
    </header>

    <main>

        <section class="hero-section">
            <h1>Sustainable Living, Made Simple</h1>
            <p>Discover eco-friendly products from small suppliers, join our community, and make a positive impact on the planet.</p>
            <a href="index.php" class="btn btn-primary">Shop Now</a>
            <a href="index.php" class="btn btn-learn-more">Learn More</a>
        </section>

        <section class="value-section">
            <div class="value-item">
                <h3>Eco-Friendly</h3>
                <p>All products are sustainably sourced and verified.</p>
            </div>
            <div class="value-item">
                <h3>Small Suppliers</h3>
                <p>Support local and small businesses making a difference.</p>
            </div>
            <div class="value-item">
                <h3>Community Tips</h3>
                <p>Join challenges, earn EcoPoints, and learn from members.</p>
            </div>
        </section>

        <section class="product-section">
            <h2>Best Sellers</h2>
            <div class="product-grid">

                <?php
                /*
                This is our first "real" PHP block.
                1. We'll include our "key" (the db_connect.php file).
                2. We'll write a SQL "question" (the query).
                3. We'll loop through the "answers" (the products).
                4. We'll "print" (echo) HTML for each one.
                */

                // 1. Include our database connection "key"
                include 'db_connect.php';

                // 2. Write our SQL query "question"
                // We ask it to "SELECT" all columns (*) "FROM" the 'products' table.
                $sql = "SELECT * FROM products";
                $result = $conn->query($sql);

                // 3. Loop through the results
                // We check if we got at least one row of data
                if ($result->num_rows > 0) {
                    // $result->fetch_assoc() grabs one product row at a time
                    // and puts it into a variable called '$row'
                    while($row = $result->fetch_assoc()) {
                        /*
                        4. "Print" the HTML.
                        This is the *exact same* HTML as our placeholder card,
                        but we are "echoing" it and replacing the hard-coded text
                        with our database variables (like $row['name']).

                        The 'images/' part is just a guess for now. We need
                        to create an 'images' folder and put files in it
                        that match the 'image_url' in the database.
                        */

                        // This line creates a link to product.php and passes the product's ID in the URL
                        // e.g., product.php?id=1
                        echo '<a href="product.php?id=' . $row['product_id'] . '" class="product-card-link">';
                        echo '  <img src="' . $row['image_url'] . '" alt="' . $row['name'] . '" class="product-image">'; // We'll style .product-image next
                        echo '  <h3>' . $row['name'] . '</h3>';
                        echo '  <p>' . $row['description'] . '</p>';
                        echo '  <span>R ' . $row['price'] . '</span>';
                        echo '</a>'; // We close the link tag here
                    }
                } else {
                    // If the database is empty, show a message.
                    echo "<p>No products found.</p>";
                }

                // 5. Close the "filing cabinet"
                // This is good practice.
                $conn->close();

                ?>

            </div>
        </section>

        <section class="cta-section cta-community">
            <h2>Join the DragonStone Community</h2>
            <p>Participate in Eco Challenges, earn EcoPoints, and connect with like-minded individuals.</p>
            <a href="community.php" class="btn btn-secondary">Explore Community</a>
        </section>

        <section class="cta-section cta-subscribe">
            <h2>Subscribe & Save</h2>
            <p>Never run out of your favorite eco-friendly products. Subscribe and save up to 15% on every order.</p>
            <a href="index.php" class="btn btn-primary-outline">Learn More</a>
        </section>

    </main>

    <footer>
        <p>&copy; 2025 DragonStone. All rights reserved.</p>
        </footer>

</body>
</html>