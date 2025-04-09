<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? $pageTitle . ' - Éclisse' : 'Éclisse - La lumière de votre féminité'; ?></title>
    <link rel="icon" href="assets/images/favicon.ico" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cookie&family=Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;1,400&family=Raleway:wght@300;400;500;600;700&family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light <?php echo !isset($pageTitle) || $pageTitle === 'Accueil' ? '' : 'scrolled'; ?>">
        <div class="container">
            <!-- Mobile Logo (Left-aligned) -->
            <a class="navbar-brand mobile-logo" href="index.php">
                <img src="assets/images/logo.png" alt="Éclisse Logo" class="logo logo-default">
                <img src="assets/images/black-logo.png" alt="Éclisse Logo" class="logo logo-scrolled">
            </a>

            <!-- Mobile Toggle Button (Right-aligned) -->
            <button class="navbar-toggler" type="button" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Desktop Navigation -->
            <div class="navbar-wrapper">
                <!-- Left navigation -->
                <div class="navbar-left">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link" href="index.php">Accueil</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Collections
                                <span class="dropdown-icon"><i class="fas fa-chevron-down fa-xs"></i></span>
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <li><a class="dropdown-item" href="products.php?category=Kimonos">Kimonos élégants</a></li>
                                <li><a class="dropdown-item" href="products.php?category=Ensembles">Ensembles modernes</a></li>
                                <li><a class="dropdown-item" href="products.php?category=Éditions limitées">Éditions limitées</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>

                <!-- Center logo -->
                <div class="navbar-center">
                    <a class="navbar-brand" href="index.php">
                        <img src="assets/images/logo.png" alt="Éclisse Logo" class="logo logo-default">
                        <img src="assets/images/black-logo.png" alt="Éclisse Logo" class="logo logo-scrolled">
                    </a>
                </div>

                <!-- Right navigation -->
                <div class="navbar-right">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link" href="about.php">À propos</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="blog.php">Blog</a>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Mobile Navigation Menu (Slides from right) -->
            <div class="mobile-menu">
                <div class="mobile-menu-header">
                    <button class="mobile-menu-close">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <ul class="mobile-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Accueil</a>
                    </li>
                    <li class="nav-item mobile-dropdown">
                        <a class="nav-link mobile-dropdown-toggle" href="#">
                            Collections <i class="fas fa-chevron-down"></i>
                        </a>
                        <ul class="mobile-dropdown-menu">
                            <li><a class="dropdown-item" href="products.php?category=Kimonos">Kimonos élégants</a></li>
                            <li><a class="dropdown-item" href="products.php?category=Ensembles">Ensembles modernes</a></li>
                            <li><a class="dropdown-item" href="products.php?category=Éditions limitées">Éditions limitées</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="about.php">À propos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="blog.php">Blog</a>
                    </li>
                </ul>
            </div>

            <!-- Mobile Menu Overlay -->
            <div class="mobile-menu-overlay"></div>
        </div>
    </nav>