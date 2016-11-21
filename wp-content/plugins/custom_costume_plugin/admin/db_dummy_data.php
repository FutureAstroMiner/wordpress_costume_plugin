<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

// Populate the db with some dummy data


add_action( 'admin_footer', 'clean_db' ); // Write our JS below here

function clean_db() {
	?>
	<script type="text/javascript" >
	    jQuery('#cleandb').click(function () { //so on click of the button with id = clickME

	        var data = {
	            'action': 'clean_db'
	        };

	        // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
	        jQuery.post(ajaxurl, data, function (response) {
	//			On complete update DIV element
	            document.getElementById("results").innerHTML = response;
	        });


	    });
	</script> <?php

}

add_action( 'wp_ajax_clean_db', 'clean_db_callback' );

function clean_db_callback() {
	global $wpdb; // this is how you get access to the database
	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' ); // required for dbDelta()
	$table_name = $wpdb->prefix . "costumesdb";

	$drop_costumedb_sql = "DROP TABLE $table_name";
	$wpdb->query( $drop_costumedb_sql );
	$table_name = $wpdb->prefix . "costumesdb";

	$sql = "CREATE TABLE $table_name (
  wp_posts_id mediumint(9),
  piece_db_id mediumint(9)
);";

	dbDelta( $sql );

	$table_name = $wpdb->prefix . "piecesdb";
	$drop_piecedb_sql = "DROP TABLE $table_name";
	$wpdb->query( $drop_piecedb_sql );
	$piece_sql = "CREATE TABLE $table_name (
  id mediumint(9) NOT NULL AUTO_INCREMENT,
  shop_id mediumint(9),
  pieceName text NOT NULL,
  location text NOT NULL,
  pictUrl VARCHAR(128) DEFAULT '' NOT NULL,
  UNIQUE KEY id (id)
);";
	dbDelta( $piece_sql );

	$table_name = $wpdb->prefix . "shopsdb";
	$drop_shopdb_sql = "DROP TABLE $table_name";
	$wpdb->query( $drop_shopdb_sql );
	$shop_sql = "CREATE TABLE $table_name (
  id mediumint(9) NOT NULL AUTO_INCREMENT,
  shopName text NOT NULL,
  wp_user_id mediumint(9),
  shopUrl VARCHAR(128) DEFAULT '' NOT NULL,
  UNIQUE KEY id (id)
);";
	dbDelta( $shop_sql );

	$result = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}costumesdb", ARRAY_A );

	$wpdb->flush();

	wp_die( '<pre>' . print_r( $result ) . '</pre>' ); // this is required to terminate immediately and return a proper response
//	wp_die('A Result!');
}

add_action( 'admin_footer', 'populate_db' ); // Write our JS below here

function populate_db() {
	?>
	<script type="text/javascript" >
	    jQuery('#populatedb').click(function () { //so on click of the button with id = clickME

	        var data = {
	            'action': 'populate_db'
	        };

	        // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
	        jQuery.post(ajaxurl, data, function (response) {
	//			On complete update DIV element
	            document.getElementById("results").innerHTML = response;
	        });


	    });
	</script> <?php

}

add_action( 'wp_ajax_populate_db', 'populate_db_callback' );

function populate_db_callback() {
	global $wpdb; // this is how you get access to the database
	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' ); // required for dbDelta()


	$table_name = $wpdb->prefix . "piecesdb";

	dbDelta( "INSERT INTO $table_name (`id`, `pieceName`, `location`, `shop_id`, `pictUrl`) VALUES (NULL, 'head piece', 'head', '1', '/images/head.jpg')" );
	dbDelta( "INSERT INTO $table_name (`id`, `pieceName`, `location`, `shop_id`, `pictUrl`) VALUES (NULL, 'body piece', 'body', '1', '/images/body.jpg')" );
	dbDelta( "INSERT INTO $table_name (`id`, `pieceName`, `location`, `shop_id`, `pictUrl`) VALUES (NULL, 'legs piece', 'legs', '1', '/images/legs.jpg')" );
	dbDelta( "INSERT INTO $table_name (`id`, `pieceName`, `location`, `shop_id`, `pictUrl`) VALUES (NULL, 'feet piece', 'feet', '1', '/images/feet.jpg')" );
	dbDelta( "INSERT INTO $table_name (`id`, `pieceName`, `location`, `shop_id`, `pictUrl`) VALUES (NULL, 'lefthand piece', 'hand', '1', '/images/lefthand.jpg')" );
	dbDelta( "INSERT INTO $table_name (`id`, `pieceName`, `location`, `shop_id`, `pictUrl`) VALUES (NULL, 'righthand piece', 'hand', '1', '/images/righthand.jpg')" );


	$table_name = $wpdb->prefix . "shopsdb";
	dbDelta( "INSERT INTO $table_name (`id`, `shopName`, `wp_user_id`, `shopUrl`) VALUES (1, 'head shop', '1', 'head_shop_URL')" );
	dbDelta( "INSERT INTO $table_name (`id`, `shopName`, `wp_user_id`, `shopUrl`) VALUES (2, 'body shop', '1', 'body_shop_URL')" );
	dbDelta( "INSERT INTO $table_name (`id`, `shopName`, `wp_user_id`, `shopUrl`) VALUES (3, 'legs shop', '1', 'legs_shop_URL')" );
	dbDelta( "INSERT INTO $table_name (`id`, `shopName`, `wp_user_id`, `shopUrl`) VALUES (4, 'feet shop', '1', 'feet_shop_URL')" );
	dbDelta( "INSERT INTO $table_name (`id`, `shopName`, `wp_user_id`, `shopUrl`) VALUES (5, 'lefthand shop', '1', 'left_shop_URL')" );
	dbDelta( "INSERT INTO $table_name (`id`, `shopName`, `wp_user_id`, `shopUrl`) VALUES (6, 'righthand shop', '1', 'right_shop_URL')" );

	$result = $wpdb->get_results( "SELECT * FROM $table_name", ARRAY_A );

	$wpdb->flush();

	wp_die( '<pre>' . print_r( $result ) . '</pre>' ); // this is required to terminate immediately and return a proper response
//	wp_die('A Result!');
}