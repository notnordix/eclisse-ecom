<?php
require_once 'config/config.php';
require_once 'models/BlogPost.php';

$pageTitle = 'Blog';

$blogModel = new BlogPost();
$posts = $blogModel->getAll();

include 'includes/header.php';
?>

<div class="page-hero blog-hero">
    <div class="page-hero-overlay"></div>
    <div class="container">
        <div class="page-hero-content text-center">
            <h1 class="fade-in">Notre Blog</h1>
            <div class="hero-divider fade-in delay-1"></div>
            <p class="lead fade-in delay-2">Inspirations, conseils et actualités</p>
        </div>
    </div>
</div>

<section class="blog-listing py-5">
    <div class="container">
        <?php if (count($posts) > 0): ?>
            <div class="row">
                <?php
                // Display the first post in a larger format
                $firstPost = $posts[0];
                ?>
                <div class="col-12 mb-5 fade-in">
                    <div class="featured-post">
                        <div class="row g-0">
                            <div class="col-md-6">
                                <div class="featured-post-image">
                                    <img src="<?php echo $firstPost['image']; ?>" class="img-fluid" alt="<?php echo $firstPost['title']; ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="featured-post-content">
                                    <span class="post-date"><?php echo date('d F Y', strtotime($firstPost['created_at'])); ?></span>
                                    <h2><?php echo $firstPost['title']; ?></h2>
                                    <div class="content-divider"></div>
                                    <p><?php echo substr(strip_tags($firstPost['content']), 0, 300); ?>...</p>
                                    <a href="blog-post.php?id=<?php echo $firstPost['id']; ?>" class="btn btn-outline-primary">Lire l'article</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <?php
                // Display the rest of the posts in a grid
                $otherPosts = array_slice($posts, 1);
                ?>
                <?php if (count($otherPosts) > 0): ?>
                    <div class="col-12 mb-4">
                        <h3 class="blog-section-title fade-in">Articles récents</h3>
                        <div class="content-divider fade-in"></div>
                    </div>

                    <div class="row">
                        <?php foreach ($otherPosts as $index => $post): ?>
                            <div class="col-md-4 fade-in <?php echo $index > 0 ? 'delay-' . min($index, 2) : ''; ?>">
                                <div class="blog-card">
                                    <div class="blog-card-image">
                                        <img src="<?php echo $post['image']; ?>" alt="<?php echo $post['title']; ?>">
                                        <div class="blog-card-date">
                                            <span><?php echo date('d', strtotime($post['created_at'])); ?></span>
                                            <span><?php echo date('M', strtotime($post['created_at'])); ?></span>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <h5 class="card-title"><?php echo $post['title']; ?></h5>
                                        <p class="card-text"><?php echo substr(strip_tags($post['content']), 0, 150); ?>...</p>
                                    </div>
                                    <div class="card-footer">
                                        <a href="blog-post.php?id=<?php echo $post['id']; ?>" class="blog-read-more">
                                            Lire plus <i class="fas fa-arrow-right"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <div class="row">
                <div class="col-12">
                    <div class="alert alert-info fade-in">
                        Aucun article de blog pour le moment.
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php include 'includes/footer.php'; ?>