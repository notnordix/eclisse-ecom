<?php
class Admin {
    private $conn;
    
    public function __construct() {
        $this->conn = getDbConnection();
    }
    
    public function login($username, $password) {
        $sql = "SELECT id, username, password FROM admin_users WHERE username = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            
            if (password_verify($password, $user['password'])) {
                $_SESSION['admin_id'] = $user['id'];
                $_SESSION['admin_username'] = $user['username'];
                return true;
            }
        }
        
        return false;
    }
    
    public function logout() {
        unset($_SESSION['admin_id']);
        unset($_SESSION['admin_username']);
        session_destroy();
    }
    
    public function changePassword($id, $newPassword) {
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        
        $sql = "UPDATE admin_users SET password = ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("si", $hashedPassword, $id);
        
        return $stmt->execute();
    }
    
    public function getDashboardStats() {
        $stats = [];
        
        // Get total products
        $sql = "SELECT COUNT(*) as total FROM products";
        $result = $this->conn->query($sql);
        $row = $result->fetch_assoc();
        $stats['total_products'] = $row['total'];
        
        // Get total orders
        $sql = "SELECT COUNT(*) as total FROM orders";
        $result = $this->conn->query($sql);
        $row = $result->fetch_assoc();
        $stats['total_orders'] = $row['total'];
        
        // Get total blog posts
        $sql = "SELECT COUNT(*) as total FROM blog_posts";
        $result = $this->conn->query($sql);
        $row = $result->fetch_assoc();
        $stats['total_blog_posts'] = $row['total'];
        
        return $stats;
    }
    
    public function __destruct() {
        $this->conn->close();
    }
}
?>
