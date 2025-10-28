<?php
// 1. Set the page title
$pageTitle = "Home - DragonStone";

// 2. Include the reusable header
include 'header.php';
?>

<main>

    <section class="hero-section">
        <h1>Sustainable Living, Made Simple</h1>
        <p>Discover eco-friendly products from small suppliers, join our community, and make a positive impact on the planet.</p>
        <a href="index.php" class="btn btn-primary">Shop Now</a> <a href="about.php" class="btn btn-secondary">Learn More</a>
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
            This PHP block is the same as before.
            It fetches and displays products.
            */
            
            // 1. Include our database connection "key"
            include 'db_connect.php';

            // 2. Write our SQL query "question"
            $sql = "SELECT * FROM products";
            $result = $conn->query($sql);

            // 3. Loop through the results
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    /*
                    4. "Print" the HTML.
                    The 'images/' folder we created in Step 1
                    makes the image links work now!
                    */
                    
                    // This line creates a link to product.php and passes the product's ID
                    echo '<a href="product.php?id=' . $row['product_id'] . '" class="product-card-link">';
                    echo '  <div class="product-card">'; // We add the div *inside* the link
                    echo '    <img src="' . $row['image_url'] . '" alt="' . $row['name'] . '" class="product-image">';
                    echo '    <h3>' . $row['name'] . '</h3>';
                    echo '    <p>' . substr($row['description'], 0, 75) . '...</p>'; // Shorten description
                    echo '    <span>R ' . $row['price'] . '</span>';
                    echo '  </div>'; // Close the card div
                    echo '</a>'; // Close the link
                }
            } else {
                echo "<p>No products found.</p>";
            }

            // 5. Close the "filing cabinet"
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
        <a href="index.php" class="btn btn-learn-more">Learn More</a>
    </section>

</main>

<?php
// 3. Include the reusable footer
include 'footer.php';
?>