<?php
class BlogPost {
    private $conn;
    
    public function __construct() {
        $this->conn = getDbConnection();
    }
    
    public function getAll() {
        $sql = "SELECT * FROM blog_posts ORDER BY created_at DESC";
        $result = $this->conn->query($sql);
        
        $posts = [];
        while ($row = $result->fetch_assoc()) {
            $posts[] = $row;
        }
        
        return $posts;
    }
    
    public function getById($id) {
        $sql = "SELECT * FROM blog_posts WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        
        return null;
    }
    
    public function create($title, $content, $image) {
        $sql = "INSERT INTO blog_posts (title, content, image) VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("sss", $title, $content, $image);
        
        return $stmt->execute();
    }
    
    public function update($id, $title, $content, $image) {
        $sql = "UPDATE blog_posts SET title = ?, content = ?, image = ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("sssi", $title, $content, $image, $id);
        
        return $stmt->execute();
    }
    
    public function delete($id) {
        $sql = "DELETE FROM blog_posts WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        
        return $stmt->execute();
    }
    
    public function __destruct() {
        $this->conn->close();
    }
}
?>
