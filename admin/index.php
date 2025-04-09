<?php
require_once '../config/config.php';
require_once '../models/Admin.php';
require_once '../models/Product.php';
require_once '../models/Order.php';
require_once '../models/BlogPost.php';

$pageTitle = 'Dashboard';

$adminModel = new Admin();
$stats = $adminModel->getDashboardStats();

$productModel = new Product();
$orderModel = new Order();
$blogModel = new BlogPost();

$recentOrders = array_slice($orderModel->getAll(), 0, 5);
$recentProducts = array_slice($productModel->getAll(), 0, 5);
$recentPosts = array_slice($blogModel->getAll(), 0, 5);

include 'includes/header.php';
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-4">
            <div class="stats-card">
                <i class="fas fa-tshirt"></i>
                <h3><?php echo $stats['total_products']; ?></h3>
                <p>Produits</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stats-card">
                <i class="fas fa-shopping-cart"></i>
                <h3><?php echo $stats['total_orders']; ?></h3>
                <p>Commandes</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stats-card">
                <i class="fas fa-blog"></i>
                <h3><?php echo $stats['total_blog_posts']; ?></h3>
                <p>Articles de blog</p>
            </div>
        </div>
    </div>
    
    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Commandes récentes</span>
                    <a href="orders.php" class="btn btn-sm btn-outline-primary">Voir tout</a>
                </div>
                <div class="card-body">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Client</th>
                                <th>Produit</th>
                                <th>Date</th>
                                <th>Statut</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($recentOrders as $order): ?>
                            <tr>
                                <td><?php echo $order['id']; ?></td>
                                <td><?php echo $order['name']; ?></td>
                                <td><?php echo $order['product_name']; ?></td>
                                <td><?php echo date('d/m/Y', strtotime($order['created_at'])); ?></td>
                                <td>
                                    <span class="badge bg-<?php echo $order['status'] == 'pending' ? 'warning' : 'success'; ?>">
                                        <?php echo $order['status'] == 'pending' ? 'En attente' : 'Livré'; ?>
                                    </span>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                            <?php if(count($recentOrders) == 0): ?>
                            <tr>
                                <td colspan="5" class="text-center">Aucune commande pour le moment</td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Produits récents</span>
                    <a href="products.php" class="btn btn-sm btn-outline-primary">Voir tout</a>
                </div>
                <div class="card-body">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nom</th>
                                <th>Catégorie</th>
                                <th>Prix</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($recentProducts as $product): ?>
                            <tr>
                                <td><?php echo $product['id']; ?></td>
                                <td><?php echo $product['name']; ?></td>
                                <td><?php echo $product['category']; ?></td>
                                <td><?php echo number_format($product['price'], 2); ?> MAD</td>
                            </tr>
                            <?php endforeach; ?>
                            <?php if(count($recentProducts) == 0): ?>
                            <tr>
                                <td colspan="4" class="text-center">Aucun produit pour le moment</td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Articles de blog récents</span>
                    <a href="blog-posts.php" class="btn btn-sm btn-outline-primary">Voir tout</a>
                </div>
                <div class="card-body">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Titre</th>
                                <th>Date de publication</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($recentPosts as $post): ?>
                            <tr>
                                <td><?php echo $post['id']; ?></td>
                                <td><?php echo $post['title']; ?></td>
                                <td><?php echo date('d/m/Y', strtotime($post['created_at'])); ?></td>
                            </tr>
                            <?php endforeach; ?>
                            <?php if(count($recentPosts) == 0): ?>
                            <tr>
                                <td colspan="3" class="text-center">Aucun article de blog pour le moment</td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
