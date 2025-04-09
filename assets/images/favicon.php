<?php
// Create a simple favicon placeholder
header('Content-Type: image/png');
$width = 32;
$height = 32;

$img = imagecreatetruecolor($width, $height);
$bg = imagecolorallocate($img, 255, 0, 0); // Pure red
$text_color = imagecolorallocate($img, 255, 255, 255);

// Fill background
imagefilledrectangle($img, 0, 0, $width, $height, $bg);

// Draw text
$font_size = 2;
$text = "E";
$text_width = imagefontwidth($font_size) * strlen($text);
$text_height = imagefontheight($font_size);
$x = ($width - $text_width) / 2;
$y = ($height - $text_height) / 2;

imagestring($img, $font_size, $x, $y, $text, $text_color);

// Output image
imagepng($img);
imagedestroy($img);
?>
