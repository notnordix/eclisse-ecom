<?php
// Create a simple logo placeholder with white text
header('Content-Type: image/png');
$width = 200;
$height = 80;

$img = imagecreatetruecolor($width, $height);
$bg = imagecolorallocate($img, 44, 44, 44); // Dark background
$text_color = imagecolorallocate($img, 255, 255, 255); // White text
$border_color = imagecolorallocate($img, 212, 193, 156);

// Fill background
imagefilledrectangle($img, 0, 0, $width, $height, $bg);

// Draw border
imagerectangle($img, 0, 0, $width-1, $height-1, $border_color);

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
imag  / 2;

imagestring($img, $font_size, $x, $y, $text, $text_color);

// Output image
imagepng($img);
imagedestroy($img);
?>
