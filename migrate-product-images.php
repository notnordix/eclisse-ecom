<?php
require_once 'config/config.php';
require_once 'models/Product.php';

// This script will migrate existing product images to the new product_images table
echo "Starting migration of product images...\n";

$productModel = new Product();
$conn = getDbConnection();

// Get all products
$sql = "SELECT id, image FROM products";
$result = $conn->query($sql);

$count = 0;
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $product_id = $row['id'];
        $image_path = $row['image'];

        // Add the main image as primary
        $sql = "INSERT INTO product_images (product_id, image_path, is_primary, sort_order) VALUES (?, ?, 1, 0)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("is", $product_id, $image_path);

        if ($stmt->execute()) {
            $count++;
            echo "Migrated primary image for product ID: $product_id\n";
        } else {
            echo "Error migrating image for product ID: $product_id\n";
        }
    }
}

echo "Migration complete. $count images migrated.\n";
$conn->close();
