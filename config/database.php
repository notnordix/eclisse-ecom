<?php
// Database configuration from environment variables
define('DB_HOST', getenv('MYSQLHOST') ?: 'localhost');
define('DB_USER', getenv('MYSQLUSER') ?: 'root');
define('DB_PASS', getenv('MYSQLPASSWORD') ?: '');
define('DB_NAME', getenv('MYSQLDATABASE') ?: 'eclisse_db');
define('DB_PORT', getenv('MYSQLPORT') ?: '3306');

// Create connection
function getDbConnection()
{
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Create database if not exists
    $sql = "CREATE DATABASE IF NOT EXISTS " . DB_NAME;
    if ($conn->query($sql) === FALSE) {
        die("Error creating database: " . $conn->error);
    }

    // Select the database
    $conn->select_db(DB_NAME);

    return $conn;
}

// Initialize database tables
function initDatabase()
{
    $conn = getDbConnection();

    // Create products table
    $sql = "CREATE TABLE IF NOT EXISTS products (
        id INT(11) AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        short_description VARCHAR(255) NOT NULL,
        description TEXT NOT NULL,
        price DECIMAL(10,2) NOT NULL,
        image VARCHAR(255) NOT NULL,
        category VARCHAR(50) NOT NULL,
        is_deleted TINYINT(1) DEFAULT 0,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";

    if ($conn->query($sql) === FALSE) {
        die("Error creating products table: " . $conn->error);
    }

    // Add is_deleted column if it doesn't exist
    $result = $conn->query("SHOW COLUMNS FROM products LIKE 'is_deleted'");
    if ($result->num_rows == 0) {
        $conn->query("ALTER TABLE products ADD COLUMN is_deleted TINYINT(1) DEFAULT 0");
    }

    // Create product images table
    $sql = "CREATE TABLE IF NOT EXISTS product_images (
        id INT(11) AUTO_INCREMENT PRIMARY KEY,
        product_id INT(11) NOT NULL,
        image_path VARCHAR(255) NOT NULL,
        is_primary TINYINT(1) DEFAULT 0,
        sort_order INT(11) DEFAULT 0,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
    )";

    if ($conn->query($sql) === FALSE) {
        die("Error creating product_images table: " . $conn->error);
    }

    // Create blog posts table
    $sql = "CREATE TABLE IF NOT EXISTS blog_posts (
        id INT(11) AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(255) NOT NULL,
        content TEXT NOT NULL,
        image VARCHAR(255) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";

    if ($conn->query($sql) === FALSE) {
        die("Error creating blog_posts table: " . $conn->error);
    }

    // Create orders table
    $sql = "CREATE TABLE IF NOT EXISTS orders (
        id INT(11) AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        phone VARCHAR(50) NOT NULL,
        address TEXT NOT NULL,
        product_id INT(11) NOT NULL,
        quantity INT(11) NOT NULL,
        status VARCHAR(50) DEFAULT 'pending',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (product_id) REFERENCES products(id)
    )";

    if ($conn->query($sql) === FALSE) {
        die("Error creating orders table: " . $conn->error);
    }

    // Create admin users table
    $sql = "CREATE TABLE IF NOT EXISTS admin_users (
        id INT(11) AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";

    if ($conn->query($sql) === FALSE) {
        die("Error creating admin_users table: " . $conn->error);
    }

    // Insert default admin user if not exists
    $username = 'admin';
    $password = password_hash('admin123', PASSWORD_DEFAULT);

    $stmt = $conn->prepare("SELECT id FROM admin_users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        $stmt = $conn->prepare("INSERT INTO admin_users (username, password) VALUES (?, ?)");
        $stmt->bind_param("ss", $username, $password);
        $stmt->execute();
    }

    $conn->close();
}
