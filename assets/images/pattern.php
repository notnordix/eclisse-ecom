<?php
// Create a simple pattern placeholder
header('Content-Type: image/png');
$width = 200;
$height = 200;

$img = imagecreatetruecolor($width, $height);
$bg = imagecolorallocate($img, 255, 255, 255);
$dot_color = imagecolorallocate($img, 138, 109, 59);

// Fill background
imagefilledrectangle($img, 0, 0, $width, $height, $bg);

// Draw pattern
for ($x = 0; $x < $width; $x += 10) {
    for ($y = 0; $y < $height; $y += 10) {
        imagefilledellipse($img, $x, $y, 2, 2, $dot_color);
    }
}

// Output image
imagepng($img);
imagedestroy($img);
?>
