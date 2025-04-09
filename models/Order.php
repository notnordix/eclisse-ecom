<?php
class Order {
    private $conn;
    
    public function __construct() {
        $this->conn = getDbConnection();
    }
    
    public function getAll() {
        $sql = "SELECT o.*, p.name as product_name, p.price 
                FROM orders o 
                JOIN products p ON o.product_id = p.id 
                ORDER BY o.created_at DESC";
        $result = $this->conn->query($sql);
        
        $orders = [];
        while ($row = $result->fetch_assoc()) {
            $orders[] = $row;
        }
        
        return $orders;
    }
    
    public function getById($id) {
        $sql = "SELECT o.*, p.name as product_name, p.price 
                FROM orders o 
                JOIN products p ON o.product_id = p.id 
                WHERE o.id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        
        return null;
    }
    
    public function create($name, $phone, $address, $product_id, $quantity) {
        $sql = "INSERT INTO orders (name, phone, address, product_id, quantity) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("sssii", $name, $phone, $address, $product_id, $quantity);
        
        return $stmt->execute();
    }
    
    public function updateStatus($id, $status) {
        $sql = "UPDATE orders SET status = ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("si", $status, $id);
        
        return $stmt->execute();
    }
    
    public function delete($id) {
        $sql = "DELETE FROM orders WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        
        return $stmt->execute();
    }
    
    public function getTotal() {
        $sql = "SELECT COUNT(*) as total FROM orders";
        $result = $this->conn->query($sql);
        $row = $result->fetch_assoc();
        
        return $row['total'];
    }
    
    public function __destruct() {
        $this->conn->close();
    }
}
?>
