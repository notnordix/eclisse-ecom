<?php
require_once 'config/config.php';
require_once 'models/BlogPost.php';

// Create blog model
$blogModel = new BlogPost();

// Blog post 1: Kimono style advice
$title1 = "Conseils de Style – Comment porter un kimono avec élégance ?";
$content1 = "<p>Le kimono est bien plus qu'un vêtement : c'est une déclaration de style, un hommage à la fluidité et à l'élégance naturelle. Voici quelques conseils pour le porter avec grâce et distinction :</p>

<h3>1. Optez pour une coupe fluide et légère</h3>
<p>Un kimono bien choisi doit suivre les lignes du corps sans être trop serré. Choisissez un modèle qui glisse délicatement sur la silhouette, offrant une liberté de mouvement tout en préservant la fluidité du style.</p>

<h3>2. Associez-le à des pièces simples</h3>
<p>Pour garder l'équilibre dans votre tenue, associez votre kimono à des pièces plus minimalistes comme un pantalon noir ajusté ou une robe simple. Laissez le kimono être la star de la tenue, tout en mettant en valeur sa coupe et sa matière.</p>

<h3>3. Misez sur les accessoires</h3>
<p>Un kimono se prête parfaitement aux accessoires élégants. Ajoutez des boucles d'oreilles délicates, une montre raffinée ou un sac à main structuré pour apporter une touche de sophistication supplémentaire.</p>

<h3>4. Pour une touche bohème</h3>
<p>Pour un look plus décontracté mais tout aussi élégant, associez votre kimono à des sandales en cuir et une ceinture fine pour marquer la taille. Cela ajoutera une note de modernité tout en respectant le caractère intemporel du vêtement.</p>

<h3>5. N'ayez pas peur de jouer avec les couleurs</h3>
<p>Les kimonos peuvent être portés dans une palette de couleurs variées, mais optez pour des teintes douces et naturelles pour un look classique, ou des nuances plus vibrantes pour ajouter une touche de dynamisme.</p>";
$image1 = "uploads/blog/kimono-style.jpg";

// Blog post 2: Modest fashion
$title2 = "La Mode Modeste Aujourd'hui Stimule la Féminité et l'Énergie Féminine";
$content2 = "<p>Aujourd'hui, la mode modeste connaît un renouveau, en particulier grâce à des créateurs comme Éclisse, qui réinventent des pièces intemporelles avec des coupes modernes et des tissus de qualité. Les kimonos, abayas et robes longues ne sont plus simplement réservés à des occasions religieuses ou traditionnelles. Ils sont désormais des symboles de féminité et d'émancipation, parfaitement intégrés dans le paysage de la mode contemporaine.</p>

<p>Ce renouveau de la mode modeste est aussi un moyen de célébrer la féminité dans sa forme la plus authentique. Chaque vêtement incarne une énergie féminine douce mais forte, en harmonie avec la liberté d'expression de la femme moderne.</p>

<p>Chez Éclisse, nous croyons que l'habillement peut et doit être un reflet de l'individualité et de la force intérieure de chaque femme. Nos créations fusionnent la tradition et la modernité pour donner aux femmes une allure radieuse, tout en préservant leur dignité et leur élégance naturelle.</p>";
$image2 = "uploads/blog/modest-fashion.jpg";

// Create directory if it doesn't exist
if (!file_exists('uploads/blog')) {
    mkdir('uploads/blog', 0777, true);
}

// Create placeholder images if they don't exist
if (!file_exists($image1)) {
    // Create a simple placeholder image
    $img = imagecreatetruecolor(800, 500);
    $bg = imagecolorallocate($img, 245, 240, 230);
    $text_color = imagecolorallocate($img, 138, 109, 59);
    imagefilledrectangle($img, 0, 0, 800, 500, $bg);
    imagestring($img, 5, 300, 240, "Kimono Style", $text_color);
    imagejpeg($img, $image1);
    imagedestroy($img);
}

if (!file_exists($image2)) {
    // Create a simple placeholder image
    $img = imagecreatetruecolor(800, 500);
    $bg = imagecolorallocate($img, 245, 240, 230);
    $text_color = imagecolorallocate($img, 138, 109, 59);
    imagefilledrectangle($img, 0, 0, 800, 500, $bg);
    imagestring($img, 5, 300, 240, "Modest Fashion", $text_color);
    imagejpeg($img, $image2);
    imagedestroy($img);
}

// Add blog posts to database
$blogModel->create($title1, $content1, $image1);
$blogModel->create($title2, $content2, $image2);

echo "Blog posts added successfully!";
?>
