<?php
/*
 * wordpress_costume_plugin.php
 * 
 * Copyright 2013 adam 
 * 
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
 * MA 02110-1301, USA.
 * 
 * Plugin Name: Wordpress Costume Plugin
 * Plugin URI:
 * Description: Create a custome costume
 * Version: 1.0
 * Author: Adam Taylor
 * Author URI: adam-taylor.me.uk
 * 
 * 
 */

ini_set('display_startup_errors',1);
ini_set('display_errors',1);
error_reporting(-1);

if (!defined('MYPLUGIN_THEME_DIR'))
    define('MYPLUGIN_THEME_DIR', ABSPATH . 'wp-content/themes/' . get_template());

if (!defined('MYPLUGIN_PLUGIN_NAME'))
    define('MYPLUGIN_PLUGIN_NAME', trim(dirname(plugin_basename(__FILE__)), '/'));

if (!defined('MYPLUGIN_PLUGIN_DIR'))
    define('MYPLUGIN_PLUGIN_DIR', WP_PLUGIN_DIR . '/' . MYPLUGIN_PLUGIN_NAME);

if (!defined('MYPLUGIN_PLUGIN_URL'))
    define('MYPLUGIN_PLUGIN_URL', WP_PLUGIN_URL . '/' . MYPLUGIN_PLUGIN_NAME);

//global $cname;
//global $head;
//global $righthand;
//global $lefthand;
//global $body;
//global $legs;
//global $feet;
//global $table_name;
define($table_name, $wpdb->base_prefix . "costumesdb");

register_activation_hook(__FILE__, 'on_activate');

function on_activate() {
    //create the database
    //What fields will I need?
    global $wpdb;
    $table_name = $wpdb->prefix . "costumesdb";
//Add fields approved, price, user name, used in posts
    $sql = "CREATE TABLE $table_name (
  id mediumint(9) NOT NULL AUTO_INCREMENT,
  shopName text NOT NULL,
  pieceName text NOT NULL,
  location text NOT NULL,
  shopUrl VARCHAR(55) DEFAULT '' NOT NULL,
    pictUrl VARCHAR(55) DEFAULT '' NOT NULL,
  UNIQUE KEY id (id)
);";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta($sql);
}

register_uninstall_hook(__FILE__, 'on_uninstall');


function on_uninstall() {
    if (!current_user_can('activate_plugins'))
        return;
    check_admin_referer('bulk-plugins');

    if (__FILE__ != WP_UNINSTALL_PLUGIN)
        return;

}

add_action('admin_menu', 'customcostume_admin_actions');
add_action('admin_menu', 'customcostume_posts_actions');

//The page to manage options 
function customcostume_admin_actions() {
    add_options_page('Custom Costume Admin Options', 'Custom Costume Admin Options', manage_options, __FILE__, 'customcostume_admin');
}

function customcostume_admin() {
    /** future featuers
     * Approve database rows
     * allow users to edit previous uploads
     */
    echo 'Some output';
}

//Handle the upload

function customcostume_handle_upload() {
    if ($_GET["page"] == 'upload_costume') {
        echo 'Success<br>';
    }
    echo $wpdb->base_prefix;
    ?>

    <!DOCTYPE html>
    <html>
        <body>
            <form id="uploadapiece" action= "">

                Piece name: <input type="text" name="pieceName" cols="50">
                <br>
                Location: <select name="location">
                    <option value="head">Head</option>
                    <option value="hand">Hand</option>
                    <option value="body">Body</option>
                    <option value="legs">Legs</option>
                    <option value="feet">Feet</option>
                </select>
                <br>
                Shop URL: <input type="text" name="shopURL">
                <br>
                Picture URL: <input type="text" name="pictURL">
                <br>

                <input type="submit" name="submit" class="button" id="submit_btn"/>
            </form>

        </body>
    </html> <?php
}

function customcostume_posts_actions() {
    add_menu_page('Create a new custom costume', 'Create a costume', publish_posts, 'create_costume', 'customcostume_posts', 0);
    add_submenu_page('create_costume', 'Upload costume pieces', 'Upload', manage_options, 'upload_costume', 'customcostume_handle_upload');
}

function customcostume_posts() { // need to fill out options from database http://codex.wordpress.org/Class_Reference/wpdb
    global $wpdb;

    $heads = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}costumesdb WHERE location = 'head'", ARRAY_A);
    $hands = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}costumesdb WHERE location = 'hand'", ARRAY_A);
    $bodys = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}costumesdb WHERE location = 'body'", ARRAY_A);
    $legs = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}costumesdb WHERE location = 'legs'", ARRAY_A);
    $feets = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}costumesdb WHERE location = 'feet'", ARRAY_A);
//    print_r($hands);
//$result = mysql_fetch_array($hands);
//    echo "</br>";
//    foreach ($hands as $hand) {
//        print_r($hand);
//        echo "</br>";
//        echo $hand['pieceName'];
//        echo "</br>";
//    }//http://stackoverflow.com/questions/5967322/calling-a-php-function-from-an-html-form-in-the-same-file
// another method of submitting the form <?php echo MYPLUGIN_PLUGIN_DIR; (?end PHP)/custom_costume_createpost.php    

    wp_reset_query();
    if ($_GET["page"] == 'create_costume') {
        echo 'Success';
    } else {
        echo 'Nope<br>';
    };
    echo $_GET["page"];
    ?>

    <!DOCTYPE html>
    <html>
        <head>

        </head>
        <body>

            <form id="createacostume" action= "">
                Costume Name: <input type="text" name="cname"><br>

                Head: <select name="head">
                    <?php foreach ($heads as $head) { ?>
                        <option value="<?php echo $head['id'] ?>"><?php echo $head['shopName'] . " - " . $head['pieceName'] ?></option>
                    <?php } ?>
                </select><br>

                Right Hand: <select name="rightHand">
                    <?php foreach ($hands as $hand) { ?>
                        <option value="<?php echo $hand['id'] ?>"><?php echo $hand['shopName'] . " - " . $hand['pieceName'] ?></option>
                    <?php } ?>
                </select><br>

                Left Hand: <select name="leftHand">
                    <?php foreach ($hands as $hand) { ?>
                        <option value="<?php echo $hand['id'] ?>"><?php echo $hand['shopName'] . " - " . $hand['pieceName'] ?></option>
                    <?php } ?>
                </select><br>

                Body: <select name="body">
                    <?php foreach ($bodys as $body) { ?>
                        <option value="<?php echo $body['id'] ?>"><?php echo $body['shopName'] . " - " . $body['pieceName'] ?></option>
                    <?php } ?>
                </select><br>

                Legs: <select name="legs">
                    <?php foreach ($legs as $leg) { ?>
                        <option value="<?php echo $leg['id'] ?>"><?php echo $leg['shopName'] . " - " . $leg['pieceName'] ?></option>
                    <?php } ?>
                </select><br>

                Feet: <select name="feet">
                    <?php foreach ($feets as $feet) { ?>
                        <option value="<?php echo $feet['id'] ?>"><?php echo $feet['shopName'] . " - " . $feet['pieceName'] ?></option>
                    <?php } ?>
                </select>

                <br>
                <input type="submit" name="submit" class="button" id="submit_btn"/>
            </form>

        </body>
    </html> 
    <?php
    $wpdb->flush();
}

function add_myjavascript() {
    if ($_GET["page"] == 'create_costume') {
        wp_enqueue_script('ajax-implementation.js', plugins_url() . "/custom_costume_plugin/ajax-implementation.js", array('jquery'));
    } else {
        wp_enqueue_script('ajax-upload.js', plugins_url() . "/custom_costume_plugin/ajax-upload.js", array('jquery'));
    }
}

add_action('wp_print_scripts', 'add_myjavascript');

//Create the post
function myAjaxFunction() {
    global $wpdb;

    //get the data from ajax() call  
//    echo "Data returned!";

    $cname = $_POST['cname'];
    $head = (int) ( $_POST['head'] ); //tested and is an int
    $righthand = (int) ( $_POST['rightHand'] );
    $lefthand = (int) ( $_POST['leftHand'] );
    $body = (int) ( $_POST['body'] );
    $legs = (int) ( $_POST['legs'] );
    $feet = (int) ( $_POST['feet'] );

    $heads = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}costumesdb WHERE id = '$head'", ARRAY_A);
    $rhands = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}costumesdb WHERE id = '$righthand'", ARRAY_A);
    $lhands = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}costumesdb WHERE id = '$lefthand'", ARRAY_A);
    $bodys = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}costumesdb WHERE id = '$body'", ARRAY_A);
    $legss = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}costumesdb WHERE id = '$legs'", ARRAY_A);
    $feets = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}costumesdb WHERE id = '$feet'", ARRAY_A);

//echo "Name ' $cname 'head ' $head 'Right hand ' $righthand 'Left hand ' $lefthand 'Body ' $body 'Legs ' $legs 'Feet ' $feet";
//$headPictURL = $heads['pictUrl'];
//
//gettype($hands);
//print_r($heads,true);
//count($heads);
//$testContent = ;
    //Build up the post content from the values

    $content = '<div id="costume"> 
    <div id="lefthand"> 
        <a href="http://' . $lhands['shopUrl'] . '">
            <img src="' . $lhands['pictUrl'] . '" alt="Left Hand"></a>
    </div>
    <div id="righthand"> 
        <a href="http://' . $rhands['shopUrl'] . '">
            <img src="' . $rhands['pictUrl'] . '" alt="Right Hand"></a>
    </div>
    <div id="head"> 
        <a href= "http://' . $heads['shopUrl'] . '">
            <img src= "' . $heads['pictUrl'] . '" alt="Head"></a>
    </div>
    <div id="chest"> 
        <a href="http://' . $bodys['shopUrl'] . '">
            <img src="' . $bodys['pictUrl'] . '" alt="Body"></a>
    </div>
    <div id="legs"> 
        <a href="http://' . $legss['shopUrl'] . '">
            <img src="' . $legss['pictUrl'] . '" alt="Legs"></a>
    </div>
    <div id="feet"> 
        <a href="http://' . $feets['shopUrl'] . '">
            <img src="' . $feets['pictUrl'] . '" alt="Feet"></a>
    </div>
</div>';
    $post = array(
        'ping_status' => get_option('default_ping_status'),
        'post_author' => $user_ID, //The user ID number of the author.
//TODO dynamically create the content and correct formatting.
        'post_content' => $content,
//The full text of the post.
        'post_name' => $cname, // The name (slug) for your post
        'post_status' => 'draft', //Set the status of the new post.
        'post_title' => $cname, //The title of your post.
        'post_type' => 'post', //You may want to insert a regular post, page, link, a menu item or some custom post type
        'tags_input' => 'Custom Costume', //For tags.
    );

    $post_id = wp_insert_post($post);
    $wpdb->flush();

    /* Attempt to open image */
    $im = imagecreatefromjpeg('http://localhost/wp-content/plugins/custom_costume_plugin/images/background.jpg');
    
    $im = imagescale($im, 1500,1190, IMG_BICUBIC_FIXED);
    
    imagecopymerge ( $im , $heads['shopUrl'] , 31 , 400 , 0 , 0 , imagesx($heads['shopUrl']) , imagesy($heads['shopUrl']) , 100 );
    
    imagejpeg($im, 'http://localhost/wp-content/plugins/custom_costume_plugin/images/test.jpeg');
    
    imagedestroy($im);
    
    die($post_id);
}

// creating Ajax call for WordPress  
add_action('wp_ajax_nopriv_myAjaxFunction', 'myAjaxFunction');
add_action('wp_ajax_myAjaxFunction', 'myAjaxFunction');
add_action('wp_enqueue_script', 'load_jquery');
add_action('wp_ajax_nopriv_uploadAjaxFunction', 'uploadAjaxFunction');
add_action('wp_ajax_uploadAjaxFunction', 'uploadAjaxFunction');

function load_jquery() {
    wp_enqueue_script('jquery');
}

// Register style sheet.
add_action('wp_enqueue_scripts', 'register_plugin_styles');

/**
 * Register style sheet.
 */
function register_plugin_styles() {
    wp_register_style('custom_costume_plugin', plugins_url('custom_costume_plugin/style.css'));
    wp_enqueue_style('custom_costume_plugin');
}

//Not currently working
function uploadAjaxFunction() {
    global $table_prefix;
    $table_name = $table_prefix . "costumesdb";
    global $wpdb;
    $result;
    $resp;
    $pieceName = $_POST['pieceName'];
    $location = $_POST['location'];
    $shopURL = $_POST['shopURL'];
    $pictURL = $_POST['pictURL'];
    $shopName = 'A Shop';
    $result = $wpdb->insert($table_name, array('shopName' => $shopName,
        'pieceName' => $pieceName,
        'location' => $location,
        'shopURL' => $shopURL,
        'pictURL' => $pictURL), array(
        '%s',
        '%s',
        '%s',
        '%s',
        '%s',
            ));
//    if ($result === FALSE) {
//        die(0);
//    } else {
//        die(1);
//    }

// "Name $pieceName location $location Shop $shopName shopURL $shopURL pictURL $pictURL"
//    add_piece_to_db($pieceName, $location, $shopURL, $pictURL);
    die($result);
}

function add_piece_to_db($pieceName, $location, $shopURL, $pictURL) {
    $shopName = 'A Shop';

    $wpdb->insert($table_name, array('shopName' => $shopName,
        'pieceName' => $piece_name,
        'location' => $location,
        'shopURL' => $shopURL,
        'pictURL' => $pictURL));
}
?>