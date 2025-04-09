<?php
// Create a simple hero placeholder image
header('Content-Type: image/jpeg');
$width = 1920;
$height = 1080;

$img = imagecreatetruecolor($width, $height);
$bg = imagecolorallocate($img, 30, 30, 30); // Dark background
$text_color = imagecolorallocate($img, 255, 255, 255); // White text

// Fill background
imagefilledrectangle($img, 0, 0, $width, $height, $bg);

// Draw text
$font_size = 5;
$text = "HERO SLIDE 2";
$text_width = imagefontwidth($font_size) * strlen($text);
$text_height = imagefontheight($font_size);
$x = ($width - $text_width) / 2;
$y = ($height - $text_height) / 2;

imagestring($img, $font_size, $x, $y, $text, $text_color);

// Output image
imagejpeg($img);
imagedestroy($img);
