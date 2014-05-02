<?php

ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
error_reporting(-1);

echo('Starting script');

//var_dump(gd_info());
phpinfo();

echo getcwd() . "<br>";

$head_file = imageCreateFromJPEG(getcwd() . '/images/head.jpg');
$background_file = imageCreateFromJPEG(getcwd() . '/images/background.jpg');
//body, feet, legs, left hand, right hand
$body_file = imageCreateFromJPEG(getcwd() . '/images/body.jpg');
$feet_file = imageCreateFromJPEG(getcwd() . '/images/feet.jpg');
$legs_file = imageCreateFromJPEG(getcwd() . '/images/legs.jpg');
$left_hand_file = imageCreateFromJPEG(getcwd() . '/images/lefthand.jpg');
$right_hand_file = imageCreateFromJPEG(getcwd() . '/images/righthand.jpg');

$background_scaled = scale_image($background_file, 1150, 1500);

$background_width = imageSX($background_scaled);
$background_height = imageSY($background_scaled);

$head_scaled = scale_image($head_file, 160, 160);
$head_width = imageSX($head_scaled);
$head_height = imageSY($head_scaled);

$dest_x = ( $background_width / 2 ) - ( $head_width / 2 );
$dest_y = ( $background_height / 9 ) - ( $head_height / 2 );

imageCopyMerge($background_scaled, $head_scaled, $dest_x, $dest_y, 0, 0, $head_width, $head_height, 100);

echo('Image merged ');

$success = imagejpeg($background_scaled, getcwd() . '/images/test.jpg', 75);

echo('Image saved <br>');
print "original:<hr><img src=\"$background_file\" width=\"300\"><br><br><br>new:<hr><img src=\"getcwd() . '/images/test.jpg'\">
<br>source width = $background_width
<br>source height = $background_height

<br>start point x = $dest_x
<br>start point y = $dest_y";


imagedestroy($background_file);
imagedestroy($background_scaled);
imagedestroy($head_file);
imagedestroy($body_file);
imagedestroy($legs_file);
imagedestroy($feet_file);
imagedestroy($left_hand_file);
imagedestroy($right_hand_file);

function scale_image($image, $max_width, $max_height) {
    $aspect = imagesx($image) / imagesy($image);
    if ($aspect > 1) {
    $width = $max_width;
    $height = $max_height / $aspect;
} elseif ($aspect < 1) {
    $width = $max_width * $aspect;
    $height = $max_height;
} else {
    $width = $max_width;
    $height = $max_height;
}
return imagescale($image, $width, $height, IMG_BICUBIC_FIXED);
}

die(0);
?>