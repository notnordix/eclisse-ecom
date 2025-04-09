<?php
require_once 'config/config.php';
require_once 'models/Product.php';
require_once 'models/Order.php';

if (!isset($_GET['product_id'])) {
    redirect('products.php');
}

$product_id = (int)$_GET['product_id'];
$productModel = new Product();
$product = $productModel->getById($product_id);

if (!$product) {
    redirect('products.php');
}

$pageTitle = 'Commander ' . $product['name'];

$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = sanitize($_POST['name'] ?? '');
    $phone = sanitize($_POST['phone'] ?? '');
    $address = sanitize($_POST['address'] ?? '');
    $quantity = (int)($_POST['quantity'] ?? 1);

    // Validate inputs
    if (empty($name)) {
        $errors[] = 'Le nom est requis';
    }

    if (empty($phone)) {
        $errors[] = 'Le numéro de téléphone est requis';
    }

    if (empty($address)) {
        $errors[] = 'L\'adresse est requise';
    }

    if ($quantity < 1) {
        $errors[] = 'La quantité doit être au moins 1';
    }

    if (empty($errors)) {
        $orderModel = new Order();
        $result = $orderModel->create($name, $phone, $address, $product_id, $quantity);

        if ($result) {
            $success = true;

            // Préparer l'e-mail (mais ne pas l'envoyer en environnement de développement)
            $to = 'admin@eclisse.com';
            $subject = 'Nouvelle commande - ' . $product['name'];
            $message = "Nouvelle commande reçue:\n\n";
            $message .= "Produit: " . $product['name'] . "\n";
            $message .= "Quantité: " . $quantity . "\n";
            $message .= "Prix unitaire: " . number_format($product['price'], 2) . " MAD\n";
            $message .= "Total: " . number_format($product['price'] * $quantity, 2) . " MAD\n\n";
            $message .= "Informations client:\n";
            $message .= "Nom: " . $name . "\n";
            $message .= "Téléphone: " . $phone . "\n";
            $message .= "Adresse: " . $address . "\n";

            $headers = 'From: noreply@eclisse.com' . "\r\n";

            // Vérifier si nous sommes en environnement de production avant d'envoyer l'e-mail
            if (defined('ENVIRONMENT') && ENVIRONMENT === 'production') {
                mail($to, $subject, $message, $headers);
            } else {
                // En développement, enregistrer l'e-mail dans un fichier de log
                $log_file = 'logs/emails.log';

                // Créer le répertoire logs s'il n'existe pas
                if (!file_exists('logs')) {
                    mkdir('logs', 0777, true);
                }

                // Ajouter l'e-mail au fichier de log
                file_put_contents(
                    $log_file,
                    date('Y-m-d H:i:s') . " - À: $to - Sujet: $subject - Message: " . str_replace("\n", " ", $message) . "\n",
                    FILE_APPEND
                );
            }
        } else {
            $errors[] = 'Une erreur est survenue lors de la création de la commande';
        }
    }
}

include 'includes/header.php';
?>

<div class="container py-5 mt-5">
    <nav aria-label="breadcrumb" class="mb-4 fade-in">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php">Accueil</a></li>
            <li class="breadcrumb-item"><a href="products.php?category=<?php echo urlencode($product['category']); ?>"><?php echo $product['category']; ?></a></li>
            <li class="breadcrumb-item"><a href="product-details.php?id=<?php echo $product['id']; ?>"><?php echo $product['name']; ?></a></li>
            <li class="breadcrumb-item active" aria-current="page">Commander</li>
        </ol>
    </nav>

    <h1 class="mb-4 fade-in product-page-title">Commander <?php echo $product['name']; ?></h1>

    <?php if ($success): ?>
        <div class="alert alert-success fade-in">
            <h4>Commande reçue avec succès!</h4>
            <p>Merci pour votre commande. Nous vous contacterons bientôt pour confirmer les détails de livraison.</p>
            <a href="index.php" class="btn btn-primary mt-3">Retour à l'accueil</a>
        </div>
    <?php else: ?>
        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger fade-in">
                <ul class="mb-0">
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo $error; ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <div class="row">
            <div class="col-md-5 mb-4 fade-in">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="product-image-small">
                                <img src="<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>" class="img-fluid rounded">
                            </div>
                            <div class="ms-4">
                                <h5 class="product-title-small"><?php echo $product['name']; ?></h5>
                                <p class="product-category"><?php echo $product['category']; ?></p>
                                <div class="product-detail-price-container">
                                    <span class="product-detail-price"><?php echo number_format($product['price'], 0); ?> MAD</span>
                                </div>
                            </div>
                        </div>

                        <div class="product-summary mt-4">
                            <h6>Description</h6>
                            <p class="text-muted"><?php echo substr(strip_tags($product['short_description']), 0, 150); ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-7">
                <div class="card fade-in delay-1">
                    <div class="card-body">
                        <h5 class="card-title mb-4">Informations de commande</h5>
                        <form method="post">
                            <div class="mb-3">
                                <label for="name" class="form-label">Nom complet</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>
                            <div class="mb-3">
                                <label for="phone" class="form-label">Numéro de téléphone</label>
                                <input type="tel" class="form-control" id="phone" name="phone" required>
                            </div>
                            <div class="mb-3">
                                <label for="address" class="form-label">Adresse de livraison</label>
                                <textarea class="form-control" id="address" name="address" rows="3" required></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="quantity" class="form-label">Quantité</label>
                                <div class="quantity-selector">
                                    <button type="button" class="btn btn-outline-secondary quantity-btn" onclick="decrementQuantity()">-</button>
                                    <input type="number" class="form-control quantity-input" id="quantity" name="quantity" min="1" value="1" required>
                                    <button type="button" class="btn btn-outline-secondary quantity-btn" onclick="incrementQuantity()">+</button>
                                </div>
                            </div>

                            <div class="order-summary mt-4 mb-4">
                                <h6>Résumé de la commande</h6>
                                <div class="d-flex justify-content-between">
                                    <span>Prix unitaire:</span>
                                    <span><?php echo number_format($product['price'], 0); ?> MAD</span>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span>Quantité:</span>
                                    <span id="summary-quantity">1</span>
                                </div>
                                <div class="d-flex justify-content-between fw-bold mt-2">
                                    <span>Total:</span>
                                    <span id="summary-total"><?php echo number_format($product['price'], 0); ?> MAD</span>
                                </div>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-lg product-action-button">
                                    <i class="fas fa-shopping-cart me-2"></i> Confirmer la commande
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<script>
    function incrementQuantity() {
        const quantityInput = document.getElementById('quantity');
        const currentValue = parseInt(quantityInput.value);
        quantityInput.value = currentValue + 1;
        updateOrderSummary();
    }

    function decrementQuantity() {
        const quantityInput = document.getElementById('quantity');
        const currentValue = parseInt(quantityInput.value);
        if (currentValue > 1) {
            quantityInput.value = currentValue - 1;
            updateOrderSummary();
        }
    }

    function updateOrderSummary() {
        const quantity = parseInt(document.getElementById('quantity').value);
        const unitPrice = <?php echo $product['price']; ?>;
        const total = quantity * unitPrice;

        document.getElementById('summary-quantity').textContent = quantity;
        document.getElementById('summary-total').textContent = total.toLocaleString() + ' MAD';
    }

    // Update when quantity input changes directly
    document.getElementById('quantity').addEventListener('change', function() {
        if (this.value < 1) this.value = 1;
        updateOrderSummary();
    });

    // Initialize
    document.addEventListener('DOMContentLoaded', updateOrderSummary);
</script>

<?php include 'includes/footer.php'; ?>