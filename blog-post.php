<?php
require_once 'config/config.php';
require_once 'models/BlogPost.php';

if (!isset($_GET['id'])) {
    redirect('blog.php');
}

$id = (int)$_GET['id'];
$blogModel = new BlogPost();
$post = $blogModel->getById($id);

if (!$post) {
    redirect('blog.php');
}

$pageTitle = $post['title'];

// Get related posts (other posts)
$allPosts = $blogModel->getAll();
$relatedPosts = array_filter($allPosts, function ($p) use ($id) {
    return $p['id'] != $id;
});
$relatedPosts = array_slice($relatedPosts, 0, 3);

include 'includes/header.php';
?>

<div class="page-hero blog-post-header" style="background-image: url('<?php echo $post['image']; ?>')">
    <div class="page-hero-overlay"></div>
    <div class="container">
        <div class="page-hero-content text-center">
            <h1 class="fade-in"><?php echo $post['title']; ?></h1>
            <div class="hero-divider fade-in delay-1"></div>
            <div class="post-meta fade-in delay-2">
                <span><i class="far fa-calendar-alt"></i> <?php echo date('d F Y', strtotime($post['created_at'])); ?></span>
            </div>
        </div>
    </div>
</div>

<div class="container py-5">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <nav aria-label="breadcrumb" class="fade-in">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php">Accueil</a></li>
                    <li class="breadcrumb-item"><a href="blog.php">Blog</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?php echo $post['title']; ?></li>
                </ol>
            </nav>

            <div class="blog-content fade-in delay-1">
                <?php echo $post['content']; ?>
            </div>

            <div class="blog-post-share fade-in delay-2">
                <span>Partager cet article:</span>
                <div class="social-share-icons">
                    <a href="#" aria-label="Partager sur Facebook"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" aria-label="Partager sur Twitter"><i class="fab fa-twitter"></i></a>
                    <a href="#" aria-label="Partager sur Pinterest"><i class="fab fa-pinterest-p"></i></a>
                </div>
            </div>

            <div class="blog-post-navigation fade-in delay-2">
                <a href="blog.php" class="btn btn-outline-primary">
                    <i class="fas fa-arrow-left me-2"></i> Retour au blog
                </a>
            </div>
        </div>
    </div>

    <?php if (count($relatedPosts) > 0): ?>
        <div class="related-posts mt-5 pt-4">
            <div class="row">
                <div class="col-12 mb-4">
                    <h3 class="related-posts-title text-center fade-in">Articles similaires</h3>
                    <div class="content-divider mx-auto fade-in"></div>
                </div>
            </div>

            <div class="row">
                <?php foreach ($relatedPosts as $index => $relatedPost): ?>
                    <div class="col-md-4 fade-in <?php echo $index > 0 ? 'delay-' . $index : ''; ?>">
                        <div class="blog-card">
                            <div class="blog-card-image">
                                <img src="<?php echo $relatedPost['image']; ?>" alt="<?php echo $relatedPost['title']; ?>">
                                <div class="blog-card-date">
                                    <span><?php echo date('d', strtotime($relatedPost['created_at'])); ?></span>
                                    <span><?php echo date('M', strtotime($relatedPost['created_at'])); ?></span>
                                </div>
                            </div>
                            <div class="card-body">
                                <h5 class="card-title"><?php echo $relatedPost['title']; ?></h5>
                                <p class="card-text"><?php echo substr(strip_tags($relatedPost['content']), 0, 100); ?>...</p>
                            </div>
                            <div class="card-footer">
                                <a href="blog-post.php?id=<?php echo $relatedPost['id']; ?>" class="blog-read-more">
                                    Lire plus <i class="fas fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>