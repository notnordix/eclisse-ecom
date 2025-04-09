<?php
require_once '../config/config.php';
require_once '../models/Order.php';

if (!isset($_GET['id'])) {
    redirect('admin/orders.php');
}

$id = (int)$_GET['id'];
$orderModel = new Order();
$order = $orderModel->getById($id);

if (!$order) {
    redirect('admin/orders.php');
}

$pageTitle = 'Détails de la commande #' . $id;

include 'includes/header.php';
?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Détails de la commande #<?php echo $id; ?></h2>
        <a href="orders.php" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i> Retour aux commandes
        </a>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Informations client</h5>
                </div>
                <div class="card-body">
                    <p><strong>Nom:</strong> <?php echo $order['name']; ?></p>
                    <p><strong>Téléphone:</strong> <?php echo $order['phone']; ?></p>
                    <p><strong>Adresse:</strong> <?php echo nl2br($order['address']); ?></p>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Détails de la commande</h5>
                </div>
                <div class="card-body">
                    <p><strong>Produit:</strong> <?php echo $order['product_name']; ?></p>
                    <p><strong>Quantité:</strong> <?php echo $order['quantity']; ?></p>
                    <p><strong>Prix unitaire:</strong> <?php echo number_format($order['price'], 2); ?> MAD</p>
                    <p><strong>Total:</strong> <?php echo number_format($order['price'] * $order['quantity'], 2); ?> MAD</p>
                    <p><strong>Date de commande:</strong> <?php echo date('d/m/Y H:i', strtotime($order['created_at'])); ?></p>
                    <p>
                        <strong>Statut:</strong>
                        <span class="badge bg-<?php echo $order['status'] == 'pending' ? 'warning' : 'success'; ?>">
                            <?php echo $order['status'] == 'pending' ? 'En attente' : 'Livré'; ?>
                        </span>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Actions</h5>
        </div>
        <div class="card-body">
            <?php if ($order['status'] == 'pending'): ?>
                <a href="orders.php?action=update-status&id=<?php echo $order['id']; ?>&status=delivered" class="btn btn-success" onclick="return confirm('Marquer cette commande comme livrée?')">
                    <i class="fas fa-check me-2"></i> Marquer comme livrée
                </a>
            <?php else: ?>
                <a href="orders.php?action=update-status&id=<?php echo $order['id']; ?>&status=pending" class="btn btn-warning" onclick="return confirm('Marquer cette commande comme en attente?')">
                    <i class="fas fa-undo me-2"></i> Marquer comme en attente
                </a>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>