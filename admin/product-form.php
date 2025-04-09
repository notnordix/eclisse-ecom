<?php
require_once '../config/config.php';
require_once '../models/Product.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$pageTitle = $id ? 'Modifier un produit' : 'Ajouter un produit';

$productModel = new Product();
$product = $id ? $productModel->getById($id) : null;

$errors = [];
$success = false;
$debug_info = []; // For debugging purposes

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = sanitize($_POST['name'] ?? '');
    $short_description = sanitize($_POST['short_description'] ?? '');
    $description = sanitize($_POST['description'] ?? '');
    $price = (float)($_POST['price'] ?? 0);
    $category = sanitize($_POST['category'] ?? '');

    // Validate inputs
    if (empty($name)) {
        $errors[] = 'Le nom est requis';
    }

    if (empty($short_description)) {
        $errors[] = 'La description courte est requise';
    }

    if (empty($description)) {
        $errors[] = 'La description est requise';
    }

    if ($price <= 0) {
        $errors[] = 'Le prix doit être supérieur à 0';
    }

    if (empty($category)) {
        $errors[] = 'La catégorie est requise';
    }

    // Define allowed extensions globally
    $allowed = ['jpg', 'jpeg', 'png', 'gif'];
    $upload_dir = '../uploads/products/';

    // Create upload directory if it doesn't exist
    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    // Handle main image upload
    $image = $product ? $product['image'] : '';

    if (isset($_FILES['main_image']) && $_FILES['main_image']['error'] == 0) {
        $filename = $_FILES['main_image']['name'];
        $ext = pathinfo($filename, PATHINFO_EXTENSION);

        if (!in_array(strtolower($ext), $allowed)) {
            $errors[] = 'Format d\'image non valide. Formats acceptés: ' . implode(', ', $allowed);
        } else {
            $new_filename = uniqid() . '.' . $ext;
            $destination = $upload_dir . $new_filename;

            if (move_uploaded_file($_FILES['main_image']['tmp_name'], $destination)) {
                $image = 'uploads/products/' . $new_filename;
            } else {
                $errors[] = 'Erreur lors du téléchargement de l\'image principale';
            }
        }
    } elseif (!$product) {
        $errors[] = 'L\'image principale est requise';
    }

    if (empty($errors)) {
        if ($id) {
            $result = $productModel->update($id, $name, $short_description, $description, $price, $image, $category);

            // Delete existing additional images if requested
            if (isset($_POST['delete_images']) && is_array($_POST['delete_images'])) {
                foreach ($_POST['delete_images'] as $image_id) {
                    $productModel->deleteProductImage($image_id);
                }
            }
        } else {
            $result = $productModel->create($name, $short_description, $description, $price, $image, $category);
            $id = $result; // Get the new product ID
        }

        if ($result) {
            // Handle additional images upload
            $additional_images_uploaded = false;

            if (isset($_FILES['additional_images']) && is_array($_FILES['additional_images']['name'])) {
                $file_count = count($_FILES['additional_images']['name']);

                for ($i = 0; $i < $file_count; $i++) {
                    // Check if a file was actually uploaded
                    if ($_FILES['additional_images']['error'][$i] == 0 && $_FILES['additional_images']['size'][$i] > 0) {
                        $filename = $_FILES['additional_images']['name'][$i];
                        $ext = pathinfo($filename, PATHINFO_EXTENSION);

                        if (in_array(strtolower($ext), $allowed)) {
                            $new_filename = uniqid() . '.' . $ext;
                            $destination = $upload_dir . $new_filename;

                            if (move_uploaded_file($_FILES['additional_images']['tmp_name'][$i], $destination)) {
                                $image_path = 'uploads/products/' . $new_filename;
                                $is_primary = 0; // Additional images are never primary

                                if ($productModel->addProductImage($id, $image_path, $is_primary, $i)) {
                                    $additional_images_uploaded = true;
                                    $debug_info[] = "Image uploaded successfully: " . $image_path;
                                } else {
                                    $debug_info[] = "Failed to save image to database: " . $image_path;
                                }
                            } else {
                                $debug_info[] = "Failed to move uploaded file: " . $_FILES['additional_images']['tmp_name'][$i] . " to " . $destination;
                            }
                        } else {
                            $debug_info[] = "Invalid file extension: " . $ext;
                        }
                    } else {
                        $debug_info[] = "File upload error or empty file at index " . $i . ": " . $_FILES['additional_images']['error'][$i];
                    }
                }
            } else {
                $debug_info[] = "No additional images found in request";
            }

            $success = true;
        } else {
            $errors[] = 'Une erreur est survenue lors de l\'enregistrement du produit';
        }
    }
}

include 'includes/header.php';
?>

<div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <?php if ($success): ?>
                <div class="alert alert-success">
                    Le produit a été <?php echo $id ? 'modifié' : 'ajouté'; ?> avec succès.
                    <a href="products.php" class="alert-link">Retour à la liste des produits</a>
                </div>
            <?php endif; ?>

            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        <?php foreach ($errors as $error): ?>
                            <li><?php echo $error; ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <?php if (!empty($debug_info) && ENVIRONMENT === 'development'): ?>
                <div class="alert alert-info">
                    <h5>Debug Information:</h5>
                    <ul class="mb-0">
                        <?php foreach ($debug_info as $info): ?>
                            <li><?php echo $info; ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form method="post" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="name" class="form-label">Nom du produit</label>
                            <input type="text" class="form-control" id="name" name="name" value="<?php echo $product ? $product['name'] : ''; ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="short_description" class="form-label">Description courte</label>
                            <input type="text" class="form-control" id="short_description" name="short_description" value="<?php echo $product ? $product['short_description'] : ''; ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="price" class="form-label">Prix (MAD)</label>
                            <input type="number" step="0.01" class="form-control" id="price" name="price" value="<?php echo $product ? $product['price'] : ''; ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="category" class="form-label">Catégorie</label>
                            <select class="form-select" id="category" name="category" required>
                                <option value="">Sélectionner une catégorie</option>
                                <option value="Kimonos" <?php echo $product && $product['category'] == 'Kimonos' ? 'selected' : ''; ?>>Kimonos</option>
                                <option value="Ensembles" <?php echo $product && $product['category'] == 'Ensembles' ? 'selected' : ''; ?>>Ensembles</option>
                                <option value="Éditions limitées" <?php echo $product && $product['category'] == 'Éditions limitées' ? 'selected' : ''; ?>>Éditions limitées</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="description" class="form-label">Description complète</label>
                            <textarea class="form-control" id="description" name="description" rows="5" required><?php echo $product ? $product['description'] : ''; ?></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="main_image" class="form-label">Image principale</label>
                            <?php if ($product && $product['image']): ?>
                                <div class="mb-2">
                                    <img src="<?php echo '../' . $product['image']; ?>" alt="<?php echo $product['name']; ?>" class="img-thumbnail" style="max-height: 200px;">
                                </div>
                            <?php endif; ?>
                            <input type="file" class="form-control" id="main_image" name="main_image" <?php echo !$product ? 'required' : ''; ?>>
                            <?php if ($product): ?>
                                <small class="form-text text-muted">Laissez vide pour conserver l'image actuelle</small>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label for="additional_images" class="form-label">Images supplémentaires</label>
                            <input type="file" class="form-control" id="additional_images" name="additional_images[]" multiple accept="image/jpeg,image/png,image/gif">
                            <small class="form-text text-muted">Vous pouvez sélectionner plusieurs images à la fois (formats acceptés: jpg, jpeg, png, gif)</small>
                        </div>

                        <?php if ($product && isset($product['images']) && count($product['images']) > 0): ?>
                            <div class="mb-3">
                                <label class="form-label">Images existantes</label>
                                <div class="row">
                                    <?php foreach ($product['images'] as $img): ?>
                                        <div class="col-md-4 mb-2">
                                            <div class="card">
                                                <img src="<?php echo '../' . $img['image_path']; ?>" class="card-img-top" alt="Product Image" style="height: 120px; object-fit: cover;">
                                                <div class="card-body p-2">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="delete_images[]" value="<?php echo $img['id']; ?>" id="delete_image_<?php echo $img['id']; ?>">
                                                        <label class="form-check-label" for="delete_image_<?php echo $img['id']; ?>">
                                                            Supprimer
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="mt-3">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i> <?php echo $id ? 'Modifier' : 'Ajouter'; ?>
                    </button>
                    <a href="products.php" class="btn btn-secondary ms-2">
                        <i class="fas fa-times me-2"></i> Annuler
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>