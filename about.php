<?php
/*
==================================================================
FILE: about.php
PURPOSE: Shows information about the DragonStone company.
HOW IT WORKS:
1. It sets the $pageTitle variable for this specific page.
2. It includes the reusable 'header.php' (which prints the top part).
3. It shows the unique HTML content for the 'About Us' page.
4. It includes the reusable 'footer.php' (which prints the bottom part).
==================================================================
*/

// 1. Set the page title
// This variable MUST be set *before* including header.php
$pageTitle = "About Us - DragonStone";

// 2. Include the reusable header
// This prints the DOCTYPE, <head>, and the nav bar.
include 'header.php';
?>

<!--
The <main> tag holds all the content that is *unique* to this page.
The '.page-container' class provides basic centering and padding.
We added styles for '.about-hero' and '.about-content' in style.css.
-->
<main class="page-container">
    
    <!-- This is a simple banner at the top of the About page -->
    <div class="about-hero">
        <h1>About DragonStone</h1>
        <p>We're on a mission to make sustainable living simple and beautiful.</p>
    </div>
    
    <!-- This holds the main text content -->
    <div class="about-content">
        <h2>Our Story</h2>
        <!-- This text comes directly from the Project Proposal [cite: ITECA3-34 – Project – Deliverable 1 – Project Proposal Block 3 2025 (V1.0)READY.docx] -->
        <p>DragonStone was started by three founders—Aegon, Visenya, and Rhaenys—who wanted to make it easier for people to buy products that are good for the planet, look nice, and are affordable.</p>
        
        <h2>Our Values</h2>
        <!-- Using a <ul> (unordered list) for the values -->
        <ul>
            <li><strong>Sustainability:</strong> Every product is sourced and verified to be eco-friendly.</li>
            <li><strong>Community:</strong> We believe in the power of sharing. Our Community Hub is a place to learn, grow, and earn EcoPoints.</li>
            <li><strong>Small Suppliers:</strong> We support small businesses and artisans who share our values.</li>
        </ul>
    </div>
</main>
<!-- This is the end of the unique content for this page. -->

<?php
// 3. Include the reusable footer
// This will print the <footer> and close the <body> and <html> tags.
include 'footer.php';
?>
