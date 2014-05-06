<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

$cname = $_POST['cname'];
$head = (int) $_POST['head'];
$righthand = (int) $_POST['rightHand'];
$lefthand = (int) $_POST['leftHand'];
$body = (int) $_POST['body'];
$legs = (int) $_POST['legs'];
$feet = (int) $_POST['feet'];

echo "Data Returned<br/> Name $cname <br/> head $head <br/> Right hand $righthand <br/> 
    Left hand $lefthand<br/> Body $body<br/> Legs $legs<br/> Feet $feet <br/>";

global $wpdb;

//$heads = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}costumesdb WHERE id = $head", ARRAY_A);
//$hands = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}costumesdb WHERE id = $hand", ARRAY_A);
//$bodys = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}costumesdb WHERE id = $body", ARRAY_A);
//$legs = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}costumesdb WHERE id = $legs", ARRAY_A);
//$feets = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}costumesdb WHERE id = $feet", ARRAY_A);
echo 'afterdb';
//echo $heads['id'];
//print_r($heads);


$post = array(
    'ping_status' => get_option('default_ping_status'),
    'post_author' => $user_ID, //The user ID number of the author.
//'post_category' => [array(1)] //post_category no longer exists, try wp_set_post_terms() for setting a post's categories
    'post_content' => '<div id="costume"> 
    <img src="background.jpg" width="1000" height="1000" alt="Left Hand">
    <div id="lefthand"> 
        <a href="http://www.lefthand.com">
            <img src="lefthand.jpg" width="145" height="126" alt="Left Hand"></a>
    </div>
    <div id="righthand"> 
        <a href="http://www.lefthand.com">
            <img src="righthand.jpg" width="145" height="126" alt="Left Hand"></a>
    </div>
    <div id="head"> 
        <a href="http://www.lefthand.com">
            <img src="head.jpg" width="145" height="126" alt="Left Hand"></a>
    </div>
    <div id="body"> 
        <a href="http://www.lefthand.com">
            <img src="body.jpg" width="145" height="126" alt="Left Hand"></a>
    </div>
    <div id="legs"> 
        <a href="http://www.lefthand.com">
            <img src="legs.jpg" width="145" height="126" alt="Left Hand"></a>
    </div>
    <div id="feet"> 
        <a href="http://www.lefthand.com">
            <img src="feet.jpg" width="145" height="126" alt="Left Hand"></a>
    </div>
</div>',
//The full text of the post.

    'post_name' => $cname, // The name (slug) for your post

    'post_status' => 'draft', //Set the status of the new post.
    'post_title' => $cname, //The title of your post.
    'post_type' => 'post', //You may want to insert a regular post, page, link, a menu item or some custom post type
    'tags_input' => 'Custom Costume', //For tags.
);

wp_insert_post($post);
