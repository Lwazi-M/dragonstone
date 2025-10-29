<?php
/*
==================================================================
FILE: footer.php
PURPOSE: This is the reusable footer for all customer-facing pages.
HOW IT WORKS:
1. It is "included" at the *bottom* of all other pages
   (like index.php, about.php, etc.).
2. It displays the <footer> element with copyright info.
3. It provides the *closing* </body> and </html> tags
   that were *opened* in header.php. This completes the HTML document.
==================================================================
*/
?>

    <!-- 
    The <footer> tag is a "semantic" HTML tag.
    It tells the browser and search engines that this
    is the footer content for the page.
    -->
    <footer>
        <p>
            <!-- 
            '&copy;' is an "HTML entity".
            It's a special code that the browser
            converts into the © copyright symbol.
            -->
            &copy; 2025 DragonStone. All rights reserved.
        </p>
    </footer>

<!-- 
This closes the <body> tag that was opened in header.php.
All of your visible page content (from index.php, etc.)
sits between the <body> in header.php and this </body>.
-->
</body>

<!-- 
This closes the <html> tag that was opened in header.php.
This is always the very last line of an HTML document.
-->
</html>
