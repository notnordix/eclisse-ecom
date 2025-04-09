<?php
// Create directories if they don't exist
if (!file_exists('assets/images')) {
    mkdir('assets/images', 0777, true);
}

// Generate logo
$logo_content = file_get_contents('assets/images/logo.php');
$logo_output = 'assets/images/logo.png';
file_put_contents($logo_output, shell_exec('php -r "' . addslashes($logo_content) . '" > ' . $logo_output));

// Generate white logo for footer
$logo_white_content = file_get_contents('assets/images/logo-white.php');
$logo_white_output = 'assets/images/logo-white.png';
file_put_contents($logo_white_output, shell_exec('php -r "' . addslashes($logo_white_content) . '" > ' . $logo_white_output));

// Generate favicon
$favicon_content = file_get_contents('assets/images/favicon.php');
$favicon_output = 'assets/images/favicon.ico';
file_put_contents($favicon_output, shell_exec('php -r "' . addslashes($favicon_content) . '" > ' . $favicon_output));

// Generate pattern
$pattern_content = file_get_contents('assets/images/pattern.php');
$pattern_output = 'assets/images/pattern.png';
file_put_contents($pattern_output, shell_exec('php -r "' . addslashes($pattern_content) . '" > ' . $pattern_output));

// Generate hero slide 2
$hero2_content = file_get_contents('assets/images/hero2.php');
$hero2_output = 'assets/images/hero2.jpg';
file_put_contents($hero2_output, shell_exec('php -r "' . addslashes($hero2_content) . '" > ' . $hero2_output));

// Generate hero slide 3
$hero3_content = file_get_contents('assets/images/hero3.php');
$hero3_output = 'assets/images/hero3.jpg';
file_put_contents($hero3_output, shell_exec('php -r "' . addslashes($hero3_content) . '" > ' . $hero3_output));

echo "Images generated successfully!";
