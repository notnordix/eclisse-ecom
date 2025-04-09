<?php
// Create a simple logo placeholder
header('Content-Type: image/png');
$width = 200;
$height = 80;

$img = imagecreatetruecolor($width, $height);
// Make background transparent
imagealphablending($img, false);
imagesavealpha($img, true);
$transparent = imagecolorallocatealpha($img, 0, 0, 0, 127);
imagefill($img, 0, 0, $transparent);

$text_color = imagecolorallocate($img, 255, 0, 0); // Pure red

// Draw text
$font_size = 5;
$text = "ECLISSE";
$text_width = imagefontwidth($font_size) * strlen($text);
$text_height = imagefontheight($font_size);
$x = ($width - $text_width) / 2;
$y = ($height - $text_height) / 2;

imagestring($img, $font_size, $x, $y, $text, $text_color);

// Output image
imagepng($img);
imagedestroy($img);
