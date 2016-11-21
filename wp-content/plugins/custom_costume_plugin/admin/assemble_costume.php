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
	$heads = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}piecesdb "
	. "INNER JOIN {$wpdb->prefix}shopsdb AS s ON shop_id = s.id WHERE location = 'head'", ARRAY_A );
	$hands = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}piecesdb "
	. "INNER JOIN {$wpdb->prefix}shopsdb AS s ON shop_id = s.id WHERE location = 'hand'", ARRAY_A );
	$bodys = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}piecesdb "
	. "INNER JOIN {$wpdb->prefix}shopsdb AS s ON shop_id = s.id WHERE location = 'body'", ARRAY_A );
	$legs = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}piecesdb "
	. "INNER JOIN {$wpdb->prefix}shopsdb AS s ON shop_id = s.id WHERE location = 'legs'", ARRAY_A );
	$feets = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}piecesdb "
	. "INNER JOIN {$wpdb->prefix}shopsdb AS s ON shop_id = s.id WHERE location = 'feet'", ARRAY_A );
	wp_reset_query();
//	echo $_GET["page"];
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