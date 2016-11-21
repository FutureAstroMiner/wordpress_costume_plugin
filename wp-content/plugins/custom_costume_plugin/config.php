<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

//Error reporting level - ALL
error_reporting(E_ALL);

// Database Constants
//defined('DB_SERVER') ? null : define("DB_SERVER", "localhost");
//defined('DB_USER')   ? null : define("DB_USER", "root");
//defined('DB_PASS')   ? null : define("DB_PASS", "root");
//defined('DB_NAME')   ? null : define("DB_NAME", "test");

if ( !defined( 'MYPLUGIN_THEME_DIR' ) ) {
	define( 'MYPLUGIN_THEME_DIR', ABSPATH . 'wp-content/themes/' . get_template() );
}
if ( !defined( 'MYPLUGIN_PLUGIN_NAME' ) ) {
	define( 'MYPLUGIN_PLUGIN_NAME', trim( dirname( plugin_basename( __FILE__ ) ), '/' ) );
}
if ( !defined( 'MYPLUGIN_PLUGIN_DIR' ) ) {
	define( 'MYPLUGIN_PLUGIN_DIR', WP_PLUGIN_DIR . '/' . MYPLUGIN_PLUGIN_NAME );
}
if ( !defined( 'MYPLUGIN_PLUGIN_URL' ) ) {
	define( 'MYPLUGIN_PLUGIN_URL', WP_PLUGIN_URL . '/' . MYPLUGIN_PLUGIN_NAME );
}
//define( $table_name, $wpdb->base_prefix . "costumesdb" );