<?php
class Product
{
    private $conn;

    public function __construct()
    {
        $this->conn = getDbConnection();
    }

    public function getAll($category = null, $includeDeleted = false)
    {
        $sql = "SELECT * FROM products";
        $conditions = [];
        $params = [];
        $types = "";

        if (!$includeDeleted) {
            $conditions[] = "is_deleted = 0";
        }

        if ($category) {
            $conditions[] = "category = ?";
            $params[] = $category;
            $types .= "s";
        }

        if (!empty($conditions)) {
            $sql .= " WHERE " . implode(" AND ", $conditions);
        }

        $stmt = $this->conn->prepare($sql);

        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }

        $stmt->execute();
        $result = $stmt->get_result();

        $products = [];
        while ($row = $result->fetch_assoc()) {
            $products[] = $row;
        }

        return $products;
    }

    public function getById($id, $includeDeleted = false)
    {
        $sql = "SELECT * FROM products WHERE id = ?";

        if (!$includeDeleted) {
            $sql .= " AND is_deleted = 0";
        }

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $product = $result->fetch_assoc();

            // Get all images for this product
            $product['images'] = $this->getProductImages($id);

            return $product;
        }

        return null;
    }

    public function getProductImages($product_id)
    {
        $sql = "SELECT * FROM product_images WHERE product_id = ? ORDER BY is_primary DESC, sort_order ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $product_id);
        $stmt->execute();
        $result = $stmt->get_result();

        $images = [];
        while ($row = $result->fetch_assoc()) {
            $images[] = $row;
        }

        return $images;
    }

    public function create($name, $short_description, $description, $price, $image, $category)
    {
        $sql = "INSERT INTO products (name, short_description, description, price, image, category) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("sssdss", $name, $short_description, $description, $price, $image, $category);

        if ($stmt->execute()) {
            return $this->conn->insert_id;
        }

        return false;
    }

    public function addProductImage($product_id, $image_path, $is_primary = 0, $sort_order = 0)
    {
        $sql = "INSERT INTO product_images (product_id, image_path, is_primary, sort_order) VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("isii", $product_id, $image_path, $is_primary, $sort_order);

        return $stmt->execute();
    }

    public function deleteProductImage($image_id)
    {
        // First get the image path to delete the file
        $sql = "SELECT image_path FROM product_images WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $image_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $image_path = '../' . $row['image_path'];

            // Delete the file if it exists
            if (file_exists($image_path)) {
                unlink($image_path);
            }
        }

        // Delete the database record
        $sql = "DELETE FROM product_images WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $image_id);

        return $stmt->execute();
    }

    public function deleteProductImages($product_id)
    {
        // First get all image paths to delete the files
        $sql = "SELECT image_path FROM product_images WHERE product_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $product_id);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            $image_path = '../' . $row['image_path'];

            // Delete the file if it exists
            if (file_exists($image_path)) {
                unlink($image_path);
            }
        }

        // Delete all database records
        $sql = "DELETE FROM product_images WHERE product_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $product_id);

        return $stmt->execute();
    }

    public function update($id, $name, $short_description, $description, $price, $image, $category)
    {
        $sql = "UPDATE products SET name = ?, short_description = ?, description = ?, price = ?, image = ?, category = ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("sssdssi", $name, $short_description, $description, $price, $image, $category, $id);

        return $stmt->execute();
    }

    public function softDelete($id)
    {
        $sql = "UPDATE products SET is_deleted = 1 WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);

        return $stmt->execute();
    }

    public function restore($id)
    {
        $sql = "UPDATE products SET is_deleted = 0 WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);

        return $stmt->execute();
    }

    public function delete($id)
    {
        // This is now a hard delete, use with caution
        // First delete all product images
        $this->deleteProductImages($id);

        // Then delete the product
        $sql = "DELETE FROM products WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);

        return $stmt->execute();
    }

    public function getCategories()
    {
        $sql = "SELECT DISTINCT category FROM products WHERE is_deleted = 0";
        $result = $this->conn->query($sql);

        $categories = [];
        while ($row = $result->fetch_assoc()) {
            $categories[] = $row['category'];
        }

        return $categories;
    }

    public function getSecondaryImage($product_id)
    {
        $sql = "SELECT image_path FROM product_images WHERE product_id = ? AND is_primary = 0 ORDER BY sort_order ASC LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $product_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['image_path'];
        }

        return null;
    }

    public function __destruct()
    {
        $this->conn->close();
    }
}
