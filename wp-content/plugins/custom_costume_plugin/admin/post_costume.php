<?php

//Create the post by pulling in all the images and creating a post
function myAjaxFunction() {
	global $wpdb;
	global $current_user;
	get_currentuserinfo();
	$cname = $_POST['cname'];
	$head = (int) ( $_POST['head'] ); //tested and is an int
	$righthand = (int) ( $_POST['rightHand'] );
	$lefthand = (int) ( $_POST['leftHand'] );
	$body = (int) ( $_POST['body'] );
	$legs = (int) ( $_POST['legs'] );
	$feet = (int) ( $_POST['feet'] );
//    $test = filter_input(INPUT_POST, 'cname');
	$heads = $wpdb->get_row( "SELECT * FROM {$wpdb->prefix}piecesdb WHERE id = '$head'", ARRAY_A );
	$rhands = $wpdb->get_row( "SELECT * FROM {$wpdb->prefix}piecesdb WHERE id = '$righthand'", ARRAY_A );
	$lhands = $wpdb->get_row( "SELECT * FROM {$wpdb->prefix}piecesdb WHERE id = '$lefthand'", ARRAY_A );
	$bodys = $wpdb->get_row( "SELECT * FROM {$wpdb->prefix}piecesdb WHERE id = '$body'", ARRAY_A );
	$legss = $wpdb->get_row( "SELECT * FROM {$wpdb->prefix}piecesdb WHERE id = '$legs'", ARRAY_A );
	$feets = $wpdb->get_row( "SELECT * FROM {$wpdb->prefix}piecesdb WHERE id = '$feet'", ARRAY_A );
	//Creating original post
	$post = array(
		'ping_status' => get_option( 'default_ping_status' ),
		'post_author' => $current_user->display_name, //The user ID number of the author.
//        'post_content' => $content,
//The full text of the post.
		'post_name' => $cname, // The name (slug) for your post
		'post_status' => 'draft', //Set the status of the new post.
		'post_title' => $cname, //The title of your post.
		'post_type' => 'post', //You may want to insert a regular post, page, link, a menu item or some custom post type
		'tags_input' => 'Custom Costume', //For tags.
	);
	$post_id = wp_insert_post( $post );
	$wpdb->flush();
	//TODO Get image locations from database and load them as image resourses
	//Load image resources
	//$file = imageCreateFromJPEG($hands['pictURL']);
	if ( substr( $heads["pictUrl"], 0, 1 ) == "/" ) {
		$head_file = imageCreateFromJPEG( dirname( __FILE__ ) . $heads["pictUrl"] );
	} else {
		$head_file = $heads["pictUrl"];
	}
	$background_file = imageCreateFromJPEG( dirname( __FILE__ ) . '/images/background.jpg' );
	$body_file = imageCreateFromJPEG( dirname( __FILE__ ) . $bodys["pictUrl"] );
	$feet_file = imageCreateFromJPEG( dirname( __FILE__ ) . $feets["pictUrl"] );
	$legs_file = imageCreateFromJPEG( dirname( __FILE__ ) . $legss["pictUrl"] );
	$left_hand_file = imageCreateFromJPEG( dirname( __FILE__ ) . $lhands["pictUrl"] );
	$right_hand_file = imageCreateFromJPEG( dirname( __FILE__ ) . $rhands["pictUrl"] );
	//Scale images
	$background_scaled = scale_image( $background_file, 1500, 1500 );
	$head_scaled = scale_image( $head_file, 180, 180 );
	$body_scaled = scale_image( $body_file, 450, 450 );
	$feet_scaled = scale_image( $feet_file, 300, 300 );
	$legs_scaled = scale_image( $legs_file, 300, 300 );
	$left_hand_scaled = scale_image( $left_hand_file, 250, 250 );
	$right_hand_scaled = scale_image( $right_hand_file, 250, 250 );
	//Scaled image sizes
	$background_width = imageSX( $background_scaled );
	$background_height = imageSY( $background_scaled );
	$head_width = imageSX( $head_scaled );
	$head_height = imageSY( $head_scaled );
	$body_width = imagesx( $body_scaled );
	$body_height = imagesy( $body_scaled );
	$feet_width = imagesx( $feet_scaled );
	$feet_height = imagesy( $feet_scaled );
	$legs_width = imagesx( $legs_scaled );
	$legs_height = imagesy( $legs_scaled );
	$left_hand_width = imagesx( $left_hand_scaled );
	$left_hand_height = imagesy( $left_hand_scaled );
	$right_hand_width = imagesx( $right_hand_scaled );
	$right_hand_height = imagesy( $right_hand_scaled );
	//Locations of where the images go on the background. Use them in the image map?
	$head_x = intval( ( $background_width / 2 ) - ( $head_width / 2 ) );
	$head_y = 5;
	$body_x = intval( ($background_width / 2) - ($body_width / 2) );
	$body_y = intval( ($background_height / 3) - ($body_height / 2) );
	$feetx = intval( ( $background_width / 2 ) - ( $feet_width / 2 ) );
	$feety = intval( ( $background_height ) - ( $feet_height) - 5 );
	$legsx = intval( ( $background_width / 2) - ( $legs_width / 2 ) );
	$legsy = intval( ( 2 * $background_height / 3 ) - ( $feet_height / 2 ) );
	$left_handx = 5;
	$left_handy = intval( ( $background_height / 2 ) - ( $left_hand_height / 2 ) );
	$right_handx = intval( ( $background_width) - ( $right_hand_width ) - 5 );
	$right_handy = intval( ( $background_height / 2 ) - ( $right_hand_height / 2 ) );
	//Merg the images
	imageCopyMerge( $background_scaled, $head_scaled, $head_x, $head_y, 0, 0, $head_width, $head_height, 100 );
	imageCopyMerge( $background_scaled, $body_scaled, $body_x, $body_y, 0, 0, $body_width, $body_height, 100 );
	imageCopyMerge( $background_scaled, $feet_scaled, $feetx, $feety, 0, 0, $feet_width, $feet_height, 100 );
	imageCopyMerge( $background_scaled, $legs_scaled, $legsx, $legsy, 0, 0, $legs_width, $legs_height, 100 );
	imageCopyMerge( $background_scaled, $left_hand_scaled, $left_handx, $left_handy, 0, 0, $left_hand_width, $left_hand_height, 100 );
	imageCopyMerge( $background_scaled, $right_hand_scaled, $right_handx, $right_handy, 0, 0, $right_hand_width, $left_hand_height, 100 );
	$success = imagejpeg( $background_scaled, dirname( __FILE__ ) . '/images/' . $post_id . '.jpeg', 75 );
	imagedestroy( $background_file );
	imagedestroy( $background_scaled );
	imagedestroy( $head_file );
	imagedestroy( $body_file );
	imagedestroy( $legs_file );
	imagedestroy( $feet_file );
	imagedestroy( $left_hand_file );
	imagedestroy( $right_hand_file );
	$image_file = \MYPLUGIN_PLUGIN_URL . '/images/' . $post_id . '.jpeg';
	$head_position = strval( $head_x ) . ', ' . strval( $head_y ) . ', ' . strval( $head_x + $head_width ) . ', ' . strval( $head_y + $head_height );
	$body_position = strval( $body_x ) . ', ' . strval( $body_y ) . ', ' . strval( $body_x + $body_width ) . ', ' . strval( $body_y + $body_height );
	$feet_position = strval( $feetx ) . ', ' . strval( $feety ) . ', ' . strval( $feetx + $feet_width ) . ', ' . strval( $feety + $feet_height );
	$legs_position = strval( $legsx ) . ', ' . strval( $legsy ) . ', ' . strval( $legsx + $legs_width ) . ', ' . strval( $legsy + $legs_height );
	$left_hand_position = strval( $left_handx ) . ', ' . strval( $left_handy ) . ', ' . strval( $left_handx + $left_hand_width ) . ', ' . strval( $left_handy + $left_hand_height );
	$right_hand_position = strval( $right_handx ) . ', ' . strval( $right_handy ) . ', ' . strval( $right_handx + $right_hand_width ) . ', ' . strval( $right_handy + $right_hand_height );
	//Modify post
	$content = '<img src="' . $image_file . '" alt="' . $cname . '" usemap="#costumemap">
            <map name="costumemap">
  <area shape="rect" coords="' . $head_position . '" alt="Head" href="head.htm">
<area shape="rect" coords="' . $body_position . '" alt="Body" href="body.htm">
<area shape="rect" coords="' . $legs_position . '" alt="legs" href="legs.htm">
<area shape="rect" coords="' . $feet_position . '" alt="Feet" href="feet.htm">
<area shape="rect" coords="' . $right_hand_position . '" alt="Right Hand" href="right_hand.htm">
<area shape="rect" coords="' . $left_hand_position . '" alt="Left Hand" href="left_hand.htm">
</map><br>
Head pict URL = ' . $heads["pictUrl"] . '<br>
Head position = ' . $head_position . '<br>
    Head x position = ' . $head_x . '<br>
        Head y position = ' . $head_y . '<br>
            Head width = ' . $head_width . '<br>
                Head height = ' . $head_height . '<br>
            Body pos = ' . $body_position . '<br>
            Leg pos = ' . $legs_position . '<br>
            Feet pos = ' . $feet_position . '<br>
            Right hand pos = ' . $right_hand_position . '<br>
           Left hand pos = ' . $left_hand_position . '<br>';
	$modified_post = array(
		'ID' => $post_id,
		'post_content' => $content,
	);
	wp_update_post( $modified_post );
	$url = strval( 'http://localhost/wp-admin/post.php?post=' . $post_id . '&action=edit' );
//    wp_redirect($url);
	die( $url );
}



//Generic function to scale an image to fit in a box keeping the aspect ratio of the original image
function scale_image( $image, $max_width, $max_height ) {
	$aspect = imagesx( $image ) / imagesy( $image );
	if ( $aspect > 1 ) {
		$width = $max_width;
		$height = $max_height / $aspect;
	} elseif ( $aspect < 1 ) {
		$width = $max_width * $aspect;
		$height = $max_height;
	} else {
		$width = $max_width;
		$height = $max_height;
	}
	return imagescale( $image, $width, $height, IMG_BICUBIC_FIXED );
}