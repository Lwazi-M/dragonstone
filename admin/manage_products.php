<?php
// 1. Include the security header
include 'admin_header.php';

// 2. Include the database connection (we're in 'admin', so we go '../')
include '../db_connect.php';

// 3. Fetch all products from the database
$sql = "SELECT * FROM products ORDER BY product_id DESC"; // Get newest first
$result = $conn->query($sql);
?>

<div class="page-content">
    <div class="page-header">
        <h2>Manage Products</h2>
        <a href="product_form.php?action=add" class="btn-add">+ Add New Product</a>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Image</th>
                <th>Name</th>
                <th>Price</th>
                <th>Stock</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // 4. Loop through the results and display each product
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row['product_id'] . "</td>";
                    // Show a small thumbnail of the image
                    echo "<td><img src='../" . $row['image_url'] . "' alt='" . $row['name'] . "' class='table-thumbnail'></td>";
                    echo "<td>" . $row['name'] . "</td>";
                    echo "<td>R " . $row['price'] . "</td>";
                    echo "<td>" . $row['stock_quantity'] . "</td>";
                    echo "<td class='table-actions'>";
                    // These links will let us edit or delete a product
                    // We pass the ID in the URL so the next page knows *which* product
                    echo "    <a href='product_form.php?action=edit&id=" . $row['product_id'] . "' class='btn-edit'>Edit</a>";
                    echo "    <a href='product_delete.php?id=" . $row['product_id'] . "' class='btn-delete'>Delete</a>";
                    echo "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='6'>No products found.</td></tr>";
            }
            // 5. Close the database connection
            $conn->close();
            ?>
        </tbody>
    </table>

</div>

<?php
// 6. Include the footer (to close the HTML tags)
// We need to create this file, but for now, it's just closing tags.
?>
        </main> </div> </body>
</html>