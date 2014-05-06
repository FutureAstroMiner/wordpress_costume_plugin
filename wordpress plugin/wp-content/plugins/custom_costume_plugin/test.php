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

echo('Head merged <br>');

$body_scaled = scale_image($body_file, 160, 160);
$body_width = imagesx($body_scaled);
$body_height = imagesy($body_scaled);

imageCopyMerge($background_scaled, $body_scaled, $background_width /2, $background_height /2, 0, 0, $body_width, $body_height, 100);

echo('Body merged <br>');

$feet_scaled = scale_image($feet_file, 160, 160);
$feet_width = imagesx($feet_scaled);
$feet_height = imagesy($feet_scaled);

$feetx = ( $background_width / 2 ) - ( $feet_width / 2 );
$feety = ( $background_height ) - ( $feet_height);

imageCopyMerge($background_scaled, $feet_scaled, $feetx, $feety, 0, 0, $feet_width, $feet_height, 100);

echo('Feet merged <br>');

$legs_scaled = scale_image($legs_file, 160, 160);
$legs_width = imagesx($legs_scaled);
$legs_height = imagesy($legs_scaled);

$legsx = ( $background_width / 2) - ( $legs_width / 2 );
$legsy = ( 3*$background_height / 4 ) - ( $feet_height / 2 );

imageCopyMerge($background_scaled, $legs_scaled, $legsx, $legsy, 0, 0, $legs_width, $legs_height, 100);

echo('Legs merged <br>');

$left_hand_scaled = scale_image($left_hand_file, 160, 160);
$left_hand_width = imagesx($left_hand_scaled);
$left_hand_height = imagesy($left_hand_scaled);

$left_handx = ( $background_width / 4) - ( $left_hand_width / 2 );
$left_handy = ( $background_height / 2 ) - ( $left_hand_height / 2 );

imageCopyMerge($background_scaled, $left_hand_scaled, $left_handx, $left_handy, 0, 0, $left_hand_width, $left_hand_height, 100);

echo('Left hand merged <br>');

$right_hand_scaled = scale_image($right_hand_file, 160, 160);
$right_hand_width = imagesx($right_hand_scaled);
$right_hand_height = imagesy($right_hand_scaled);

$right_handx = ( 3*$background_width / 4) - ( $right_hand_width / 2 );
$right_handy = ( $background_height / 2 ) - ( $right_hand_height / 2 );

imageCopyMerge($background_scaled, $right_hand_scaled, $right_handx, $right_handy, 0, 0, $right_hand_width, $left_hand_height, 100);

echo('Right hand merged <br>');

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