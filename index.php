<?php
require_once 'config/config.php';
require_once 'models/Product.php';

$pageTitle = 'Accueil';

$productModel = new Product();
$kimonos = $productModel->getAll('Kimonos');
$ensembles = $productModel->getAll('Ensembles');
$limitedEditions = $productModel->getAll('Éditions limitées');

include 'includes/header.php';
?>

<section class="hero">
    <!-- Hero Slides - Will transition automatically -->
    <div class="hero-slide active" style="background-image: url('assets/images/hero.jpeg');"></div>
    <div class="hero-slide" style="background-image: url('assets/images/hero2.jpeg');"></div>
    <div class="hero-slide" style="background-image: url('assets/images/hero3.jpeg');"></div>

    <!-- Hero Overlay -->
    <div class="hero-overlay"></div>



    <div class="container d-flex align-items-center justify-content-center h-100">
        <div class="hero-content text-center">
            <div class="fade-in">
                <img src="assets/images/hero-logo.png" alt="Éclisse" class="hero-logo">
            </div>
            <p class="hero-subtitle fade-in delay-1">La lumière de votre féminité</p>
            <div class="hero-divider fade-in delay-1"></div>
            <div class="hero-buttons fade-in delay-2">
                <a href="products.php" class="hero-btn primary">
                    <span>Découvrir nos collections</span>
                    <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Scroll Down Indicator -->
    <div class="scroll-down">
        <span>Découvrir</span>
        <i class="fas fa-chevron-down"></i>
    </div>
</section>

<section class="our-story">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-5 mb-4 mb-lg-0">
                <div class="our-story-image-wrapper">
                    <div class="our-story-image slide-in-left">
                        <img src="assets/images/hero3.jpeg" alt="Éclisse Story" class="img-fluid">
                    </div>
                    <div class="our-story-accent"></div>
                </div>
            </div>
            <div class="col-lg-7">
                <div class="our-story-content slide-in-right">
                    <span class="section-subtitle">Notre Histoire</span>
                    <h2>Éclisse – La lumière de votre féminité</h2>
                    <div class="content-divider"></div>
                    <p class="lead">
                        Chaque pièce est une étreinte de lumière, un souffle d'élégance qui célèbre la féminité dans toute sa splendeur.
                    </p>
                    <a href="about.php" class="btn btn-outline-primary our-story-btn">
                        <span>Découvrir notre histoire</span>
                        <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="section-padding bg-light-custom">
    <div class="container">
        <h2 class="section-title fade-in">Our Collections</h2>

        <?php if (count($kimonos) > 0): ?>
            <h3 class="mb-4 fade-in">Elegant Kimonos</h3>
            <div class="row">
                <?php foreach (array_slice($kimonos, 0, 4) as $index => $product): ?>
                    <?php
                    $secondaryImage = $productModel->getSecondaryImage($product['id']);
                    $delay = $index % 3; // 0, 1, or 2
                    ?>
                    <div class="col-6 col-lg-3 col-md-6 fade-in <?php echo $delay > 0 ? 'delay-' . $delay : ''; ?>">
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
        <?php endif; ?>

        <?php if (count($ensembles) > 0): ?>
            <h3 class="mb-4 mt-5 fade-in">Modern and Modest Ensembles</h3>
            <div class="row">
                <?php foreach (array_slice($ensembles, 0, 4) as $index => $product): ?>
                    <?php
                    $secondaryImage = $productModel->getSecondaryImage($product['id']);
                    $delay = $index % 3; // 0, 1, or 2
                    ?>
                    <div class="col-6 col-lg-3 col-md-6 fade-in <?php echo $delay > 0 ? 'delay-' . $delay : ''; ?>">
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
        <?php endif; ?>

        <?php if (count($limitedEditions) > 0): ?>
            <h3 class="mb-4 mt-5 fade-in">Limited Editions & Favorites</h3>
            <div class="row">
                <?php foreach (array_slice($limitedEditions, 0, 4) as $index => $product): ?>
                    <?php
                    $secondaryImage = $productModel->getSecondaryImage($product['id']);
                    $delay = $index % 3; // 0, 1, or 2
                    ?>
                    <div class="col-6 col-lg-3 col-md-6 fade-in <?php echo $delay > 0 ? 'delay-' . $delay : ''; ?>">
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
        <?php endif; ?>
    </div>
</section>

<section class="py-5 bg-custom-primary text-white">
    <div class="container text-center">
        <h2 class="mb-4 fade-in">Our Philosophy</h2>
        <p class="lead mb-4 fade-in delay-1">Wearing Éclisse means wrapping yourself in a subtle light, one that reveals without unveiling, that enhances without artifice. It's embodying a femininity that is free, gentle, and powerful at the same time, like a morning that is reborn, bursting with promises.</p>
        <a href="about.php" class="btn btn-outline-light fade-in delay-2">Learn more</a>
    </div>
</section>

<?php include 'includes/footer.php'; ?>