<?php
require_once '../config/config.php';
require_once '../models/Order.php';

$pageTitle = 'Gestion des commandes';

$orderModel = new Order();
$orders = $orderModel->getAll();

// Handle status update
if (isset($_GET['action']) && $_GET['action'] == 'update-status' && isset($_GET['id']) && isset($_GET['status'])) {
    $id = (int)$_GET['id'];
    $status = $_GET['status'] == 'delivered' ? 'delivered' : 'pending';
    $orderModel->updateStatus($id, $status);
    redirect('admin/orders.php');
}

include 'includes/header.php';
?>

<div class="container-fluid">
    <h2 class="mb-4">Liste des commandes</h2>
    
    <div class="card">
        <div class="card-body">
            <table class="table table-striped datatable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Client</th>
                        <th>Téléphone</th>
                        <th>Produit</th>
                        <th>Quantité</th>
                        <th>Total</th>
                        <th>Date</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($orders as $order): ?>
                    <tr>
                        <td><?php echo $order['id']; ?></td>
                        <td><?php echo $order['name']; ?></td>
                        <td><?php echo $order['phone']; ?></td>
                        <td><?php echo $order['product_name']; ?></td>
                        <td><?php echo $order['quantity']; ?></td>
                        <td><?php echo number_format($order['price'] * $order['quantity'], 2); ?> MAD</td>
                        <td><?php echo date('d/m/Y', strtotime($order['created_at'])); ?></td>
                        <td>
                            <span class="badge bg-<?php echo $order['status'] == 'pending' ? 'warning' : 'success'; ?>">
                                <?php echo $order['status'] == 'pending' ? 'En attente' : 'Livré'; ?>
                            </span>
                        </td>
                        <td>
                            <a href="order-details.php?id=<?php echo $order['id']; ?>" class="btn btn-sm btn-info">
                                <i class="fas fa-eye"></i>
                            </a>
                            <?php if ($order['status'] == 'pending'): ?>
                            <a href="orders.php?action=update-status&id=<?php echo $order['id']; ?>&status=delivered" class="btn btn-sm btn-success" onclick="return confirm('Marquer cette commande comme livrée?')">
                                <i class="fas fa-check"></i>
                            </a>
                            <?php else: ?>
                            <a href="orders.php?action=update-status&id=<?php echo $order['id']; ?>&status=pending" class="btn btn-sm btn-warning" onclick="return confirm('Marquer cette commande comme en attente?')">
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
