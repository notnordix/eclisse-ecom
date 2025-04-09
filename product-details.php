<?php
require_once 'config/config.php';
require_once 'models/Product.php';

if (!isset($_GET['id'])) {
    redirect('products.php');
}

$id = (int)$_GET['id'];
$productModel = new Product();
$product = $productModel->getById($id);

if (!$product) {
    redirect('products.php');
}

$pageTitle = $product['name'];

include 'includes/header.php';

// Prepare WhatsApp message
$whatsappMessage = "Bonjour je souhaite commander le produit " . $product['name'];
$whatsappUrl = "https://wa.me/" . WHATSAPP_NUMBER . "?text=" . urlencode($whatsappMessage);

// Get related products (same category)
$relatedProducts = $productModel->getAll($product['category']);
$relatedProducts = array_filter($relatedProducts, function ($p) use ($id) {
    return $p['id'] != $id;
});
$relatedProducts = array_slice($relatedProducts, 0, 4);
?>

<div class="container py-5 mt-5">
    <nav aria-label="breadcrumb" class="mb-4 fade-in">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php">Accueil</a></li>
            <li class="breadcrumb-item"><a href="products.php?category=<?php echo urlencode($product['category']); ?>"><?php echo $product['category']; ?></a></li>
            <li class="breadcrumb-item active" aria-current="page"><?php echo $product['name']; ?></li>
        </ol>
    </nav>

    <div class="product-details-modern">
        <div class="row">
            <!-- Product Gallery -->
            <div class="col-lg-7 mb-4 mb-lg-0 fade-in">
                <div class="product-gallery-modern">
                    <div class="main-image-container-modern">
                        <img id="main-product-image" src="<?php echo $product['image']; ?>" class="img-fluid" alt="<?php echo $product['name']; ?>">
                    </div>

                    <?php if (!empty($product['images'])): ?>
                        <div class="product-thumbnails-modern">
                            <div class="thumbnails-row">
                                <div class="thumbnail-item">
                                    <img src="<?php echo $product['image']; ?>" class="thumbnail-image-modern active" alt="<?php echo $product['name']; ?>" onclick="changeMainImage(this.src)">
                                </div>
                                <?php foreach ($product['images'] as $image): ?>
                                    <div class="thumbnail-item">
                                        <img src="<?php echo $image['image_path']; ?>" class="thumbnail-image-modern" alt="<?php echo $product['name']; ?>" onclick="changeMainImage(this.src)">
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Product Info -->
            <div class="col-lg-5">
                <div class="product-info-modern fade-in delay-1">
                    <div class="product-category-badge"><?php echo $product['category']; ?></div>
                    <h1 class="product-title-modern"><?php echo $product['name']; ?></h1>

                    <div class="product-price-modern">
                        <?php echo number_format($product['price'], 0); ?> MAD
                    </div>

                    <div class="product-short-description">
                        <p><?php echo $product['short_description']; ?></p>
                    </div>

                    <div class="product-divider"></div>

                    <div class="product-description-modern">
                        <h4>Description</h4>
                        <div class="description-content">
                            <?php echo nl2br($product['description']); ?>
                        </div>
                    </div>

                    <div class="product-actions-modern">
                        <a href="<?php echo $whatsappUrl; ?>" class="btn-whatsapp" target="_blank">
                            <i class="fab fa-whatsapp"></i>
                            <span>Commander via WhatsApp</span>
                        </a>
                        <a href="order-form.php?product_id=<?php echo $product['id']; ?>" class="btn-order">
                            <i class="fas fa-shopping-cart"></i>
                            <span>Commander (Paiement à la livraison)</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php if (count($relatedProducts) > 0): ?>
        <div class="related-products-modern mt-5 pt-5">
            <h3 class="section-title-modern text-center fade-in">Vous aimerez aussi</h3>

            <div class="row mt-4">
                <?php foreach (array_slice($relatedProducts, 0, 4) as $index => $relatedProduct): ?>
                    <?php $secondaryImage = $productModel->getSecondaryImage($relatedProduct['id']); ?>
                    <div class="col-6 col-lg-3 col-md-4 mb-4 fade-in <?php echo $index > 0 ? 'delay-' . min($index, 2) : ''; ?>">
                        <div class="product-card-modern" onclick="window.location.href='product-details.php?id=<?php echo $relatedProduct['id']; ?>'">
                            <div class="product-image-container-modern">
                                <img src="<?php echo $relatedProduct['image']; ?>" class="product-image-modern primary-image" alt="<?php echo $relatedProduct['name']; ?>">
                                <?php if ($secondaryImage): ?>
                                    <img src="<?php echo $secondaryImage; ?>" class="product-image-modern secondary-image" alt="<?php echo $relatedProduct['name']; ?>">
                                <?php endif; ?>
                                <div class="product-info-overlay-modern">
                                    <h5 class="product-title-card"><?php echo $relatedProduct['name']; ?></h5>
                                    <p class="product-price-card"><?php echo number_format($relatedProduct['price'], 0); ?> MAD</p>
                                </div>
                                <div class="product-actions-card">
                                    <a href="product-details.php?id=<?php echo $relatedProduct['id']; ?>" class="product-action-btn-modern">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>
</div>

<script>
    function changeMainImage(src) {
        document.getElementById('main-product-image').src = src;

        // Update active thumbnail
        const thumbnails = document.querySelectorAll('.thumbnail-image-modern');
        thumbnails.forEach(thumb => {
            if (thumb.src === src) {
                thumb.classList.add('active');
            } else {
                thumb.classList.remove('active');
            }
        });
    }
</script>

<?php include 'includes/footer.php'; ?>