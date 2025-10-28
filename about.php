<?php
// Set the page title *before* including the header
$pageTitle = "About Us - DragonStone";

// 1. Include the reusable header
include 'header.php';
?>

<main class="page-container">
    <div class="about-hero">
        <h1>About DragonStone</h1>
        <p>We're on a mission to make sustainable living simple and beautiful.</p>
    </div>
    
    <div class="about-content">
        <h2>Our Story</h2>
        <p>DragonStone was started by three founders - Aegon, Visenya, and Rhaenys - who wanted to make it easier for people to buy products that are good for the planet, look nice, and are affordable.</p>
        
        <h2>Our Values</h2>
        <ul>
            <li><strong>Sustainability:</strong> Every product is sourced and verified to be eco-friendly.</li>
            <li><strong>Community:</strong> We believe in the power of sharing. Our Community Hub is a place to learn, grow, and earn EcoPoints.</li>
            <li><strong>Small Suppliers:</strong> We support small businesses and artisans who share our values.</li>
        </ul>
    </div>
</main>

<?php
// 2. Include the reusable footer
include 'footer.php';
?>