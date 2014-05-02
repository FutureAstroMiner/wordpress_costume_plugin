<?php

ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
error_reporting(-1);

echo('Starting script');

//var_dump(gd_info());
phpinfo();

echo getcwd() . "<br>";

//    $im = imagecreatefromjpeg(getcwd() . '/images/background.jpg');
$insertfile_id = imageCreateFromJPEG(getcwd() . '/images/head.jpg');
$sourcefile_id = imageCreateFromJPEG(getcwd() . '/images/background.jpg');

$sourcefile_id2 = imagescale($sourcefile_id, 1500, 1190, IMG_BICUBIC_FIXED);

//    $im2 = imagecreatefromjpeg(getcwd() . '/images/head.jpg');
$sourcefile_width = imageSX($sourcefile_id2);
$sourcefile_height = imageSY($sourcefile_id2);
$insertfile_width = imageSX($insertfile_id);
$insertfile_height = imageSY($insertfile_id);

$max_size = 160;
$aspect = $insertfile_width / $insertfile_height;
if ($aspect > 1) {
    $width = $max_size;
    $height = $max_size / $aspect;
} elseif ($aspect < 1) {
    $width = $max_width * $aspect;
    $height = $max_height;
} else {
    $width = $max_size;
    $height = $max_size;
}


$insertfile_id2 = imagescale($insertfile_id, $width, $height, IMG_BICUBIC_FIXED);

//    imagecopymerge( $im , $im2 , 31 , 400 , 0 , 0 , imagesx($im2) , imagesy($im2) , 100 );
$dest_x = ( $sourcefile_width / 2 ) - ( $insertfile_width / 2 );
$dest_y = ( $sourcefile_height / 4 ) - ( $insertfile_height / 2 );

imageCopyMerge($sourcefile_id2, $insertfile_id2, $dest_x, $dest_y, 0, 0, $width, $height, 100);

echo('Image merged ');

$success = imagejpeg($sourcefile_id2, getcwd() . '/images/test.jpg', 75);

echo('Image saved <br>');
print "original:<hr><img src=\"$sourcefile_id\" width=\"300\"><br><br><br>new:<hr><img src=\"getcwd() . '/images/test.jpg'\">
<br>source width = $sourcefile_width
<br>source height = $sourcefile_height
<br>insert width = $width
<br>insert height = $height

<br>start point x = $dest_x
<br>start point y = $dest_y";


imagedestroy($sourcefile_id);
imagedestroy($sourcefile_id2);
imagedestroy($insertfile_id);

die(0);
?>