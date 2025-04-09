<?php
require_once '../config/config.php';
require_once '../models/BlogPost.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$pageTitle = $id ? 'Modifier un article' : 'Ajouter un article';

$blogModel = new BlogPost();
$post = $id ? $blogModel->getById($id) : null;

$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = sanitize($_POST['title'] ?? '');
    $content = $_POST['content'] ?? '';
    
    // Validate inputs
    if (empty($title)) {
        $errors[] = 'Le titre est requis';
    }
    
    if (empty($content)) {
        $errors[] = 'Le contenu est requis';
    }
    
    // Handle image upload
    $image = $post ? $post['image'] : '';
    
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $filename = $_FILES['image']['name'];
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        
        if (!in_array(strtolower($ext), $allowed)) {
            $errors[] = 'Format d\'image non valide. Formats acceptés: ' . implode(', ', $allowed);
        } else {
            $upload_dir = '../uploads/blog/';
            
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            
            $new_filename = uniqid() . '.' . $ext;
            $destination = $upload_dir . $new_filename;
            
            if (move_uploaded_file($_FILES['image']['tmp_name'], $destination)) {
                $image = 'uploads/blog/' . $new_filename;
            } else {
                $errors[] = 'Erreur lors du téléchargement de l\'image';
            }
        }
    } elseif (!$post) {
        $errors[] = 'L\'image est requise';
    }
    
    if (empty($errors)) {
        if ($id) {
            $result = $blogModel->update($id, $title, $content, $image);
        } else {
            $result = $blogModel->create($title, $content, $image);
        }
        
        if ($result) {
            $success = true;
        } else {
            $errors[] = 'Une erreur est survenue lors de l\'enregistrement de l\'article';
        }
    }
}

include 'includes/header.php';
?>

<div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <?php if ($success): ?>
                <div class="alert alert-success">
                    L'article a été <?php echo $id ? 'modifié' : 'ajouté'; ?> avec succès.
                    <a href="blog-posts.php" class="alert-link">Retour à la liste des articles</a>
                </div>
            <?php endif; ?>
            
            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        <?php foreach($errors as $error): ?>
                            <li><?php echo $error; ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
            
            <form method="post" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="title" class="form-label">Titre</label>
                    <input type="text" class="form-control" id="title" name="title" value="<?php echo $post ? $post['title'] : ''; ?>" required>
                </div>
                
                <div class="mb-3">
                    <label for="content" class="form-label">Contenu</label>
                    <textarea class="form-control" id="content" name="content" rows="10" required><?php echo $post ? $post['content'] : ''; ?></textarea>
                </div>
                
                <div class="mb-3">
                    <label for="image" class="form-label">Image de couverture</label>
                    <?php if ($post && $post['image']): ?>
                        <div class="mb-2">
                            <img src="<?php echo '../' . $post['image']; ?>" alt="<?php echo $post['title']; ?>" class="img-thumbnail" style="max-height: 200px;">
                        </div>
                    <?php endif; ?>
                    <input type="file" class="form-control" id="image" name="image" <?php echo !$post ? 'required' : ''; ?>>
                    <?php if ($post): ?>
                        <small class="form-text text-muted">Laissez vide pour conserver l'image actuelle</small>
                    <?php endif; ?>
                </div>
                
                <div class="mt-3">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i> <?php echo $id ? 'Modifier' : 'Ajouter'; ?>
                    </button>
                    <a href="blog-posts.php" class="btn btn-secondary ms-2">
                        <i class="fas fa-times me-2"></i> Annuler
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
