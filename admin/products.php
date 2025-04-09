<?php
require_once '../config/config.php';
require_once '../models/Product.php';

$pageTitle = 'Gestion des produits';
$showDeleted = isset($_GET['show_deleted']) && $_GET['show_deleted'] == 1;

$productModel = new Product();
$products = $productModel->getAll(null, $showDeleted);

// Handle delete/restore actions
if (isset($_GET['action']) && isset($_GET['id'])) {
    $id = (int)$_GET['id'];

    if ($_GET['action'] == 'delete') {
        $productModel->softDelete($id);
        redirect('admin/products.php');
    } elseif ($_GET['action'] == 'restore') {
        $productModel->restore($id);
        redirect('admin/products.php');
    }
}

include 'includes/header.php';
?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Liste des produits</h2>
        <div>
            <?php if ($showDeleted): ?>
                <a href="products.php" class="btn btn-outline-primary me-2">
                    <i class="fas fa-eye me-2"></i> Afficher les produits actifs
                </a>
            <?php else: ?>
                <a href="products.php?show_deleted=1" class="btn btn-outline-secondary me-2">
                    <i class="fas fa-trash me-2"></i> Afficher les produits supprimés
                </a>
            <?php endif; ?>
            <a href="product-form.php" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i> Ajouter un produit
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <table class="table table-striped datatable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Image</th>
                        <th>Nom</th>
                        <th>Catégorie</th>
                        <th>Prix</th>
                        <th>Date d'ajout</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($products as $product): ?>
                        <tr <?php echo $product['is_deleted'] ? 'class="table-secondary"' : ''; ?>>
                            <td><?php echo $product['id']; ?></td>
                            <td>
                                <img src="<?php echo '../' . $product['image']; ?>" alt="<?php echo $product['name']; ?>" width="50" height="50" class="img-thumbnail">
                            </td>
                            <td><?php echo $product['name']; ?></td>
                            <td><?php echo $product['category']; ?></td>
                            <td><?php echo number_format($product['price'], 2); ?> MAD</td>
                            <td><?php echo date('d/m/Y', strtotime($product['created_at'])); ?></td>
                            <td>
                                <?php if ($product['is_deleted']): ?>
                                    <span class="badge bg-danger">Supprimé</span>
                                <?php else: ?>
                                    <span class="badge bg-success">Actif</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if (!$product['is_deleted']): ?>
                                    <a href="product-form.php?id=<?php echo $product['id']; ?>" class="btn btn-sm btn-primary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="products.php?action=delete&id=<?php echo $product['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce produit?')">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                <?php else: ?>
                                    <a href="products.php?action=restore&id=<?php echo $product['id']; ?>" class="btn btn-sm btn-success" onclick="return confirm('Voulez-vous restaurer ce produit?')">
                                        <i class="fas fa-undo"></i>
                                    </a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>