<?php

ini_set('display_startup_errors',1);
ini_set('display_errors',1);
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
    $sourcefile_width=imageSX($sourcefile_id2);
    $sourcefile_height=imageSY($sourcefile_id2);
    $insertfile_width=imageSX($insertfile_id);
    $insertfile_height=imageSY($insertfile_id); 
    
    $max_height = 80;
    $max_width = 80;
    if($insertfile_width > $max_width || $insertfile_height > $max_width) {
    $aspect = $max_width / $max_height;
           $width = intval($insertfile_width * $aspect);
        $height = intval($insertfile_width / $aspect);
  }
    $insertfile_id2 = imagescale($insertfile_id, $width, $height, IMG_BICUBIC_FIXED);
  
//    imagecopymerge( $im , $im2 , 31 , 400 , 0 , 0 , imagesx($im2) , imagesy($im2) , 100 );
    $dest_x = ( $sourcefile_width / 2 ) - ( $insertfile_width / 2 );
    $dest_y = ( $sourcefile_height / 2 ) - ( $insertfile_height / 2 ); 
    imageCopyMerge($sourcefile_id2, $insertfile_id2, 31, 400, 0, 0, $insertfile_width, $insertfile_height,100);
    
    echo('Image merged ');  
    
    $success = imagejpeg($sourcefile_id2, getcwd() . '/images/test.jpg', 75);

echo('Image saved <br>');    
print "original:<hr><img src=\"$sourcefile_id\" width=\"300\"><br><br><br>new:<hr><img src=\"getcwd() . '/images/test.jpg'\">
<br>source width = $sourcefile_width
<br>source height = $sourcefile_height
<br>insert width = $insertfile_width
<br>insert height = $insertfile_height

<br>start point x = $dest_x
<br>start point y = $dest_y";

    
    imagedestroy($sourcefile_id);
    imagedestroy($sourcefile_id2);
    imagedestroy($insertfile_id);
       
    die(0);
    ?>