<?php
/*
Plugin Name: Custom Costume Plugin
Plugin URI:  https://some.unique.URI.to.where.the.plugin.home.page.is.com
Description: Basic WordPress Plugin Header Comment
Version:     0.1
Author:      Adam
Author URI:  https://developer.wordpress.org/
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: wporg
Domain Path: /languages
 * 
 * 
*/

require_once ('initilize.php');


register_activation_hook( __FILE__, 'on_activate' );

function on_activate() {
	//create the database
	//What fields will I need?
	global $wpdb;
	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' ); // required for dbDelta()
	$table_name = $wpdb->prefix . "costumesdb";

	$sql = "CREATE TABLE $table_name (
  wp_posts_id mediumint(9),
  piece_db_id mediumint(9)
);";
	
	dbDelta( $sql );

	$table_name = $wpdb->prefix . "piecedb";
	$piece_sql = "CREATE TABLE $table_name (
  id mediumint(9) NOT NULL AUTO_INCREMENT,
  shop_id mediumint(9),
  pieceName text NOT NULL,
  location text NOT NULL,
  pictUrl VARCHAR(128) DEFAULT '' NOT NULL,
  UNIQUE KEY id (id)
);";
	dbDelta($piece_sql);
	
	$table_name = $wpdb->prefix . "shopdb";
	$shop_sql = "CREATE TABLE $table_name (
  id mediumint(9) NOT NULL AUTO_INCREMENT,
  shopName text NOT NULL,
  wp_user_id mediumint(9),
  shopUrl VARCHAR(128) DEFAULT '' NOT NULL,
  UNIQUE KEY id (id)
);";
	dbDelta($shop_sql);
}



register_uninstall_hook( __FILE__, 'on_uninstall' );

function on_uninstall() {
	if ( !current_user_can( 'activate_plugins' ) ) {
		return;
	}
	check_admin_referer( 'bulk-plugins' );
	if ( __FILE__ != WP_UNINSTALL_PLUGIN ) {
		return;
	}
}

// When admin_menu action is triggered the function in the second param is run
add_action( 'admin_menu', 'customcostume_admin_actions' );
add_action( 'admin_menu', 'customcostume_posts_actions' );

//Add page to "settings" to manage options in admin.php
function customcostume_admin_actions() {
	add_options_page( 'Custom Costume Admin Options', 'Custom Costume Admin Options', manage_options, __FILE__, 'customcostume_admin' );
}

// register and enqueue scripts  for the upload page upload.php
add_action( 'admin_enqueue_scripts', 'my_admin_scripts' );

function my_admin_scripts() {
	if ( isset( $_GET['page'] ) && $_GET['page'] == 'upload_costume' ) {
		wp_enqueue_media();
		wp_register_script( 'my-admin.js', WP_PLUGIN_URL . '/custom_costume_plugin/my-admin.js', array( 'jquery' ) );
		wp_enqueue_script( 'my-admin.js' ); //Cant find what this was ment to point to.
	}
}

//creates menu pages in the menu bar
// contained in assemble_costume.php and upload.php
function customcostume_posts_actions() {
	add_menu_page( 'Create a new custom costume', 'Create a costume', publish_posts, 'create_costume', 'customcostume_posts', 0 );
	add_submenu_page( 'create_costume', 'Upload costume pieces', 'Upload', manage_options, 'upload_costume', 'customcostume_handle_upload' );
}



// add java script depending on if it is the upload or create page
function add_myjavascript() {
//	wp_enqueue_script( 'spin.js', plugins_url() . "/custom_costume_plugin/spin.js", array( 'jquery' ) );
	if ( $_GET["page"] == 'create_costume' ) {
		wp_enqueue_script( 'ajax-implementation.js', WP_PLUGIN_DIR . "/custom_costume_plugin/ajax-implementation.js", array( 'jquery' ) );
	} else {
		wp_enqueue_script( 'ajax-upload.js', WP_PLUGIN_DIR . "/custom_costume_plugin/ajax-upload.js", array( 'jquery' ) );
	}
}

//add_action( 'wp_print_scripts', 'add_myjavascript' );



//// creating Ajax call for WordPress  
//add_action( 'wp_ajax_nopriv_myAjaxFunction', 'myAjaxFunction' );
//add_action( 'wp_ajax_myAjaxFunction', 'myAjaxFunction' );

//Why load_jquery ???
add_action( 'wp_enqueue_script', 'load_jquery' );

//Ajax function to upload a costume piece in upload.php
add_action( 'wp_ajax_nopriv_uploadAjaxFunction', 'uploadAjaxFunction' );
add_action( 'wp_ajax_uploadAjaxFunction', 'uploadAjaxFunction' );

function load_jquery() {
	wp_enqueue_script( 'jquery' );
}

// Register style sheet.
add_action( 'wp_enqueue_scripts', 'register_plugin_styles' );

/**
 * Register style sheet.
 */
function register_plugin_styles() {
	//No used for anything just yet
	wp_register_style( 'custom_costume_plugin', plugins_url() . 'custom_costume_plugin/style.css' );
	wp_enqueue_style( 'custom_costume_plugin' );
}



//function add_piece_to_db($pieceName, $location, $shopURL, $pictURL) {
//    $shopName = 'A Shop';
//
//    $wpdb->insert($table_name, array('shopName' => $shopName,
//        'pieceName' => $piece_name,
//        'location' => $location,
//        'shopURL' => $shopURL,
//        'pictURL' => $pictURL));
//}
?>