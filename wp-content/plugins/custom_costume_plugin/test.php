<?php

ini_set('display_startup_errors',1);
ini_set('display_errors',1);
error_reporting(-1);

    echo('Starting script');
    
    //var_dump(gd_info());
    phpinfo();
    
    echo getcwd() . "<br>";
    
    $im = imagecreatefromjpeg(getcwd() . '/images/background.jpg');
    
    
    
    $ims = imagescale($im, 1500, 1190, IMG_BICUBIC_FIXED);
    
    
    
    
    $im2 = imagecreatefromjpeg(getcwd() . '/images/head.jpg');
    
    imagecopymerge( $im , $im2 , 31 , 400 , 0 , 0 , imagesx($im2) , imagesy($im2) , 100 );
    
    echo('Image merged ');  
    
    $success = imagejpeg($im, getcwd() . '/images/test.jpg', 75);

echo('Image saved <br>');    
    
    imagedestroy($im);
    imagedestroy($ims);
    imagedestroy($im2);
       
    die(0);
    ?>