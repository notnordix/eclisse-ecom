<?php
require_once 'config/config.php';
require_once 'models/Product.php';

$category = isset($_GET['category']) ? $_GET['category'] : null;
$pageTitle = $category ? $category : 'Tous les produits';

$productModel = new Product();
$products = $productModel->getAll($category);
$categories = $productModel->getCategories();

include 'includes/header.php';
?>

<div class="page-hero products-hero">
    <div class="page-hero-overlay"></div>
    <div class="container">
        <div class="page-hero-content text-center">
            <h1 class="fade-in"><?php echo $pageTitle; ?></h1>
            <div class="hero-divider fade-in delay-1"></div>
            <p class="lead fade-in delay-2">Découvrez notre collection exclusive</p>
        </div>
    </div>
</div>

<section class="products-section py-5">
    <div class="container">
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="category-filter fade-in">
                    <div class="filter-title">Filtrer par catégorie:</div>
                    <div class="filter-buttons">
                        <a href="products.php" class="filter-btn <?php echo !$category ? 'active' : ''; ?>">Tous</a>
                        <?php foreach ($categories as $cat): ?>
                            <a href="products.php?category=<?php echo urlencode($cat); ?>" class="filter-btn <?php echo $category == $cat ? 'active' : ''; ?>"><?php echo $cat; ?></a>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>

        <?php if (count($products) > 0): ?>
            <div class="row products-grid">
                <?php foreach ($products as $index => $product): ?>
                    <?php
                    $secondaryImage = $productModel->getSecondaryImage($product['id']);
                    $delay = $index % 3; // 0, 1, or 2
                    ?>
                    <div class="col-6 col-lg-3 col-md-4 mb-4 fade-in <?php echo $delay > 0 ? 'delay-' . $delay : ''; ?>">
                        <div class="product-card" data-href="product-details.php?id=<?php echo $product['id']; ?>">
                            <div class="product-image-container">
                                <img src="<?php echo $product['image']; ?>" class="product-image primary-image" alt="<?php echo $product['name']; ?>">
                                <?php if ($secondaryImage): ?>
                                    <img src="<?php echo $secondaryImage; ?>" class="product-image secondary-image" alt="<?php echo $product['name']; ?>">
                                <?php endif; ?>
                                <div class="product-info-overlay">
                                    <h5 class="product-title"><?php echo $product['name']; ?></h5>
                                    <p class="product-price"><?php echo number_format($product['price'], 0); ?> MAD</p>
                                </div>
                                <div class="product-actions">
                                    <a href="product-details.php?id=<?php echo $product['id']; ?>" class="product-action-btn">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="row">
                <div class="col-12">
                    <div class="alert alert-info fade-in">
                        <i class="fas fa-info-circle me-2"></i> Aucun produit trouvé dans cette catégorie.
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php include 'includes/footer.php'; ?>