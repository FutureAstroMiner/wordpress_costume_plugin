<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 * 
 * Plugin Name: Wordpress test Plugin
 * Plugin URI:
 * Description: Trying out stuff
 * Version: 1.0
 * Author: Adam Taylor
 * Author URI: adam-taylor.me.uk
 */

//register_activation_hook(__FILE__, 'on_activate');

add_action('admin_menu', 'test_posts_actions');

function test_posts_actions() {
    add_menu_page('Create a new test', 'Create test', publish_posts, 'create_test', 'customcostume_posts_test', 0);
    add_submenu_page('create_test', 'Upload test files', 'Upload', manage_options, 'upload_test', 'handle_upload');
}

function handle_upload() {
    ?>
    <!DOCTYPE html>
    <html>
        <body>
            <p>
                <label for="example-jpg-file">
                    Select File To Upload:
                </label>
                <input type="file" id="example-jpg-file" name="example-jpg-file" value="" />
                <?php wp_nonce_field(plugin_basename(__FILE__), 'example-jpg-nonce'); ?>
            </p>
        </body>
    </html>
    <?php
    if (user_can_save_test($post_id, plugin_basename(__FILE__), 'example-jpg-nonce')) {

        if (has_files_to_upload_test('example-jpg-file')) {
            if (isset($_FILES['example-jpg-file'])) {
                $file = wp_upload_bits($_FILES['example-jpg-file']['name'], null, @file_get_contents($_FILES['example-jpg-file']['tmp_name']));
                if (FALSE === $file['error']) {
// TODO
                }
            }
        }
    }
}

function user_can_save_test($post_id, $plugin_file, $nonce) {

    $is_autosave = wp_is_post_autosave($post_id);
    $is_revision = wp_is_post_revision($post_id);
    $is_valid_nonce = ( isset($_POST[$nonce]) && wp_verify_nonce($_POST[$nonce], $plugin_file) );
// Return true if the user is able to save; otherwise, false.
    return !( $is_autosave || $is_revision ) && $is_valid_nonce;
}

function has_files_to_upload_test($id) {
    return (!empty($_FILES) ) && isset($_FILES[$id]);
}

function customcostume_posts_test() {
    echo 'test';
}
?>
