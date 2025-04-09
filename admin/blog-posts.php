<?php
require_once '../config/config.php';
require_once '../models/BlogPost.php';

$pageTitle = 'Gestion du blog';

$blogModel = new BlogPost();
$posts = $blogModel->getAll();

// Handle delete action
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $blogModel->delete($id);
    redirect('admin/blog-posts.php');
}

include 'includes/header.php';
?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Articles de blog</h2>
        <a href="blog-post-form.php" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i> Ajouter un article
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <table class="table table-striped datatable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Image</th>
                        <th>Titre</th>
                        <th>Date de publication</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($posts as $post): ?>
                        <tr>
                            <td><?php echo $post['id']; ?></td>
                            <td>
                                <img src="<?php echo '../' . $post['image']; ?>" alt="<?php echo $post['title']; ?>" width="50" height="50" class="img-thumbnail">
                            </td>
                            <td><?php echo $post['title']; ?></td>
                            <td><?php echo date('d/m/Y', strtotime($post['created_at'])); ?></td>
                            <td>
                                <a href="blog-post-form.php?id=<?php echo $post['id']; ?>" class="btn btn-sm btn-primary">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="blog-posts.php?action=delete&id=<?php echo $post['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet article?')">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>