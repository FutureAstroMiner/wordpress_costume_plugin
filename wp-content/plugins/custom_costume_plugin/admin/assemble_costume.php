<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

// page to assemble pieces into a costume
function customcostume_posts() {
// need to fill out options from database http://codex.wordpress.org/Class_Reference/wpdb
	global $wpdb;
	$heads = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}shopsdb AS s "
			. "INNER JOIN {$wpdb->prefix}piecesdb AS p ON p.shop_id = s.id WHERE location = 'head'", ARRAY_A );
	$hands = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}shopsdb AS s "
			. "INNER JOIN {$wpdb->prefix}piecesdb AS p ON p.shop_id = s.id WHERE location = 'hand'", ARRAY_A );
	$bodys = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}shopsdb AS s "
			. "INNER JOIN {$wpdb->prefix}piecesdb AS p ON p.shop_id = s.id WHERE location = 'body'", ARRAY_A );
	$legs = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}shopsdb AS s "
			. "INNER JOIN {$wpdb->prefix}piecesdb AS p ON p.shop_id = s.id WHERE location = 'legs'", ARRAY_A );
	$feets = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}shopsdb AS s "
			. "INNER JOIN {$wpdb->prefix}piecesdb AS p ON p.shop_id = s.id WHERE p.location = 'feet'", ARRAY_A );
	wp_reset_query();
//	echo $_GET["page"];
//	echo `whoami`;
	?>
	<!DOCTYPE html>

	<form id="createacostume" action= "">
		Costume Name: <input type="text" name="cname"><br>
		Head: <select name="head">
			<?php foreach ( $heads as $head ) { ?>
				<option value="<?php echo $head['id'] ?>"><?php echo $head['shopName'] . " - " . $head['pieceName'] ?></option>
			<?php } ?>
		</select><br>
		Right Hand: <select name="rightHand">
			<?php foreach ( $hands as $hand ) { ?>
				<option value="<?php echo $hand['id'] ?>"><?php echo $hand['shopName'] . " - " . $hand['pieceName'] ?></option>
			<?php } ?>
		</select><br>
		Left Hand: <select name="leftHand">
			<?php foreach ( $hands as $hand ) { ?>
				<option value="<?php echo $hand['id'] ?>"><?php echo $hand['shopName'] . " - " . $hand['pieceName'] ?></option>
			<?php } ?>
		</select><br>
		Body: <select name="body">
			<?php foreach ( $bodys as $body ) { ?>
				<option value="<?php echo $body['id'] ?>"><?php echo $body['shopName'] . " - " . $body['pieceName'] ?></option>
			<?php } ?>
		</select><br>
		Legs: <select name="legs">
			<?php foreach ( $legs as $leg ) { ?>
				<option value="<?php echo $leg['id'] ?>"><?php echo $leg['shopName'] . " - " . $leg['pieceName'] ?></option>
			<?php } ?>
		</select><br>
		Feet: <select name="feet">
			<?php foreach ( $feets as $feet ) { ?>
				<option value="<?php echo $feet['id'] ?>"><?php echo $feet['shopName'] . " - " . $feet['pieceName'] ?></option>
			<?php } ?>
		</select>                <br>
		<input type="submit" name="submit" class="button" id="submit_btn"/>
	</form>

	<!--<div id='spinner' class='spinner' style="display:none;">
	<img id="img-spinner" src="head.jpg" alt="Loading"/></div>-->

	<?php
	$wpdb->flush();
}

add_action( 'admin_footer', 'post_new_costume' );
add_action( 'wp_ajax_post_new_costume', 'post_new_costume_callback' );

function post_new_costume() {
	?>
	<script type="text/javascript" >
		jQuery('#createacostume').submit(function (event) { //so on click of the button with id = clickME

			event.preventDefault();

			var the_form = jQuery(this);
			var action = {'action': 'post_new_costume'};

			var data = jQuery.param(action) + '&' + the_form.serialize();
			
			console.log("----------------------------------");
				console.log("Data: " + data);
				console.log("----------------------------------");

			// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
			jQuery.post(ajaxurl, data, function (response) {
				//			On complete update DIV element
	//				document.getElementById("results").innerHTML = response;
				console.log("----------------------------------");
				console.log("Response: " + response);
				console.log("----------------------------------");
			});

		});
	</script> <?php
}

function post_new_costume_callback2() {
	wp_die('called a function');
}

function post_new_costume_callback() {
//Create the post by pulling in all the images and creating a post
	global $wpdb;
	$wpdb->show_errors( true );
	global $current_user;
	get_currentuserinfo();
	$cname = $_POST['cname'];
	$head = (int) ( $_POST['head'] );
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

	//What will be the next post id?
	$latest_post = $wpdb->get_row( "SELECT * FROM {$wpdb->prefix}posts ORDER BY ID DESC LIMIT 1", ARRAY_A );

	$post_id = $latest_post["ID"];
	wp_die($post_id);
	
	$post_id++;

	$wpdb->flush();
//	wp_die('Ran DB query');
	
//	if ( is_readable(PLUGIN_ROOT . DS . 'images' . DS . 'background.jpg') ) {
//		wp_die('is readable');
//	} else {
//		wp_die('is not readable');
//	}
	
	
	//TODO Get image locations from database and load them as image resourses
	//Load image resources
	$background_file = imageCreateFromJPEG( PLUGIN_ROOT . DS . 'images' . DS . 'background.jpg' );
//	wp_die('I made the image');
	$background_scaled = scale_image( $background_file, 1500, 1500 );
//	wp_die('I scaled the image');
	imagedestroy( $background_file );
	$background_width = imageSX( $background_scaled );
	$background_height = imageSY( $background_scaled );
	
//	wp_die('Did background image stuff');
		
	//$file = imageCreateFromJPEG($hands['pictURL']);
	if ( substr( $heads["pictUrl"], 0, 1 ) == "/" ) {
		$head_file = imageCreateFromJPEG( PLUGIN_ROOT . $heads["pictUrl"] );
	} else {
		$head_file = $heads["pictUrl"];
	}
	$head_scaled = scale_image( $head_file, 180, 180 );
	imagedestroy( $head_file );
	//Dimentions of new scaled images
	$head_width = imageSX( $head_scaled );
	$head_height = imageSY( $head_scaled );
	//Locations of where the images go on the background. Use them in the image map?
	$head_x = intval( ( $background_width / 2 ) - ( $head_width / 2 ) );
	$head_y = 5;
	//Merg the images
	imageCopyMerge( $background_scaled, $head_scaled, $head_x, $head_y, 0, 0, $head_width, $head_height, 100 );
	imagedestroy( $head_scaled );
	
//	wp_die('Did head image stuff');
	
	$body_file = imageCreateFromJPEG( PLUGIN_ROOT . $bodys["pictUrl"] );
	$body_scaled = scale_image( $body_file, 450, 450 );
	imagedestroy( $body_file );
	$body_width = imagesx( $body_scaled );
	$body_height = imagesy( $body_scaled );
	$body_x = intval( ($background_width / 2) - ($body_width / 2) );
	$body_y = intval( ($background_height / 3) - ($body_height / 2) );
	imageCopyMerge( $background_scaled, $body_scaled, $body_x, $body_y, 0, 0, $body_width, $body_height, 100 );
	imagedestroy( $body_scaled );
	
//	wp_die('Did body image stuff');
	
	$feet_file = imageCreateFromJPEG( PLUGIN_ROOT . $feets["pictUrl"] );
	$feet_scaled = scale_image( $feet_file, 300, 300 );
	imagedestroy( $feet_file );
	$feet_width = imagesx( $feet_scaled );
	$feet_height = imagesy( $feet_scaled );
	$feetx = intval( ( $background_width / 2 ) - ( $feet_width / 2 ) );
	$feety = intval( ( $background_height ) - ( $feet_height) - 5 );
	imageCopyMerge( $background_scaled, $feet_scaled, $feetx, $feety, 0, 0, $feet_width, $feet_height, 100 );
imagedestroy( $feet_scaled );

//wp_die('Did feet image stuff');

	$legs_file = imageCreateFromJPEG( PLUGIN_ROOT . $legss["pictUrl"] );
	$legs_scaled = scale_image( $legs_file, 300, 300 );
	imagedestroy( $legs_file );
	$legs_width = imagesx( $legs_scaled );
	$legs_height = imagesy( $legs_scaled );
	$legsx = intval( ( $background_width / 2) - ( $legs_width / 2 ) );
	$legsy = intval( ( 2 * $background_height / 3 ) - ( $legs_height / 2 ) );
	imageCopyMerge( $background_scaled, $legs_scaled, $legsx, $legsy, 0, 0, $legs_width, $legs_height, 100 );
imagedestroy( $legs_scaled );

//wp_die('Did leg image stuff');
	
	$left_hand_file = imageCreateFromJPEG( PLUGIN_ROOT . $lhands["pictUrl"] );
	$left_hand_scaled = scale_image( $left_hand_file, 250, 250 );
	imagedestroy( $left_hand_file );
	$left_hand_width = imagesx( $left_hand_scaled );
	$left_hand_height = imagesy( $left_hand_scaled );
	$left_handx = 5;
	$left_handy = intval( ( $background_height / 2 ) - ( $left_hand_height / 2 ) );
	imageCopyMerge( $background_scaled, $left_hand_scaled, $left_handx, $left_handy, 0, 0, $left_hand_width, $left_hand_height, 100 );
imagedestroy( $left_hand_scaled );

//wp_die('Did Lhand image stuff');

	$right_hand_file = imageCreateFromJPEG( PLUGIN_ROOT . $rhands["pictUrl"] );
	$right_hand_scaled = scale_image( $right_hand_file, 250, 250 );
	imagedestroy( $right_hand_file );
	$right_hand_width = imagesx( $right_hand_scaled );
	$right_hand_height = imagesy( $right_hand_scaled );
	$right_handx = intval( ( $background_width) - ( $right_hand_width ) - 5 );
	$right_handy = intval( ( $background_height / 2 ) - ( $right_hand_height / 2 ) );
	imageCopyMerge( $background_scaled, $right_hand_scaled, $right_handx, $right_handy, 0, 0, $right_hand_width, $left_hand_height, 100 );
	imagedestroy( $right_hand_scaled );
	
//	wp_die('Did Rhand image stuff');
	
	if ( is_writable( PLUGIN_ROOT . '/images/' ) ) {
		$success = imagejpeg( $background_scaled, PLUGIN_ROOT . '/images/' . $post_id . '.jpeg', 75 );
	} else {
		$success = PLUGIN_ROOT;
	}
	imagedestroy( $background_scaled );
	
//	wp_die('Did image stuff');
	
	$image_file = WP_PLUGIN_URL .DS.'custom_costume_plugin' . '/images/' . $post_id . '.jpeg'; //Points to a url rather than a dir.
	$head_position = strval( $head_x ) . ', ' . strval( $head_y ) . ', ' . strval( $head_x + $head_width ) . ', ' . strval( $head_y + $head_height );
	$body_position = strval( $body_x ) . ', ' . strval( $body_y ) . ', ' . strval( $body_x + $body_width ) . ', ' . strval( $body_y + $body_height );
	$feet_position = strval( $feetx ) . ', ' . strval( $feety ) . ', ' . strval( $feetx + $feet_width ) . ', ' . strval( $feety + $feet_height );
	$legs_position = strval( $legsx ) . ', ' . strval( $legsy ) . ', ' . strval( $legsx + $legs_width ) . ', ' . strval( $legsy + $legs_height );
	$left_hand_position = strval( $left_handx ) . ', ' . strval( $left_handy ) . ', ' . strval( $left_handx + $left_hand_width ) . ', ' . strval( $left_handy + $left_hand_height );
	$right_hand_position = strval( $right_handx ) . ', ' . strval( $right_handy ) . ', ' . strval( $right_handx + $right_hand_width ) . ', ' . strval( $right_handy + $right_hand_height );

//The full text of the post.
//TODO change href of image maps to point to the correct place
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

	$post = array(
		'ping_status' => get_option( 'default_ping_status' ),
		'post_author' => $current_user->display_name, //The user ID number of the author.
		'post_content' => $content,

		'post_name' => $cname, // The name (slug) for your post
		'post_status' => 'draft', //Set the status of the new post.
		'post_title' => $cname, //The title of your post.
		'post_type' => 'post', //You may want to insert a regular post, page, link, a menu item or some custom post type
		'tags_input' => 'Custom Costume', //For tags.
	);

	wp_insert_post( $post );

	$url = strval( 'http://localhost/wp-admin/post.php?post=' . $post_id . '&action=edit' );
//    wp_redirect($url);
	wp_die( $success );
}

//Generic function to scale an image to fit in a box keeping the aspect ratio of the original image
function scale_image( $image, $max_width, $max_height ) {
	$aspect = imagesx( $image ) / imagesy( $image );
//	wp_die('I found the aspect ratio of the image');
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
//	wp_die('h&w set');
//	wp_die($width);
	return imagescale( $image, intval($width), intval($height) );
}

//<pre><?php print_r($bodys); 