<?php

//page where a user can choose a piece to upload
function customcostume_handle_upload() {
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
				</select>                <br>
				Shop URL: <input type="text" name="shopURL">                <br>
				Picture URL: <!--<input type="text" name="pictURL">-->                <br>
				<label for="upload_image">
					<input id="upload_image" type="text" size="36" name="pictURL" value="http://" />
					<input id="upload_image_button" class="button" type="button" value="Upload Image" /></label> <br>
				<input type="submit" name="submit" class="button" id="submit_btn"/> </form>
			<br>Enter a URL or upload an image<br>
			<p>
				<label for="example-jpg-file">
					Select File To Upload:
				</label>
				<input type="file" id="example-jpg-file" name="example-jpg-file" value="" />
	<?php wp_nonce_field( plugin_basename( __FILE__ ) . 'uploadAjaxFunction', 'example-jpg-nonce' ); ?>
				<input type="submit" name="submit" class="button" id="submit_btn"/>
			</p>
		</body>    </html> <?php
}

//Upload the image
function uploadAjaxFunction() {
	global $current_user;
	get_currentuserinfo();
	if ( user_can_save( plugin_basename( __FILE__ ), 'example-jpg-nonce' ) ) {
		if ( has_files_to_upload( 'example-jpg-file' ) ) {
			if ( isset( $_FILES['example-jpg-file'] ) ) {
				$file = wp_upload_bits( $_FILES['example-jpg-file']['name'], null, @file_get_contents( $_FILES['example-jpg-file']['tmp_name'] ) );
//                if (FALSE === $file['error']) {
// Delete the old file
//                    if ('' !== ( $fs_url = trim(get_post_meta($post_id, 'example-jpg-file-fs', true)) )) {
//                        unlink($fs_url);
//                    } // end if
// Now update with a path to the new file URL and the filesystem path
//                    update_post_meta($post_id, 'example-jpg-file', $file['url']);
//                    update_post_meta($post_id, 'example-jpg-file-fs', $file['file']);
//                }
			}
		}
	}
	global $table_prefix;
	$table_name = $table_prefix . "costumesdb";
	global $wpdb;
	$pieceName = $_POST['pieceName'];
	$location = $_POST['location'];
	$shopURL = $_POST['shopURL'];
	$pictURL = $_POST['pictURL'];
	$shopName = $current_user->display_name;
	// Create the user folder if it does not exist
	if ( !file_exists( '\\images\\' . $shopName . '\\' ) ) {
		mkdir( '\\images\\' . $shopName . '\\', 0777, true );
	}
	// This is not copying the file. I think it is the If statement above.
	copy( $pictURL, dirname( __FILE__ ) . '\\images\\' . $shopName . '\\' . $pieceName . '_' . basename( $pictURL ) );
	$result = $wpdb->insert( $table_name, array( 'shopName' => $shopName,
		'pieceName' => $pieceName,
		'location' => $location,
		'shopURL' => $shopURL,
		'pictURL' => '\\images\\' . $shopName . '\\' . $pieceName . '_' . basename( $pictURL ) ), array(
		'%s',
		'%s',
		'%s',
		'%s',
		'%s',
	) );
	$toremove = $wpdb->get_results( 'SELECT * FROM wp_posts WHERE guid = ' . $pictURL );
	wp_delete_post( $toremove );
//    if ($result === FALSE) {
//        die(0);
//    } else {
//        die(1);
//    }
// "Name $pieceName location $location Shop $shopName shopURL $shopURL pictURL $pictURL"
//    add_piece_to_db($pieceName, $location, $shopURL, $pictURL);
//    die($result);
	wp_die( 'I removed post with ID ' . $toremove );
}

//Upload outside a function?
// Helper functions
function user_can_save( $plugin_file, $nonce ) {
//    $is_autosave = wp_is_post_autosave($post_id);
//    $is_revision = wp_is_post_revision($post_id);
	$is_valid_nonce = ( isset( $_POST[$nonce] ) && wp_verify_nonce( $_POST[$nonce], $plugin_file ) );
// Return true if the user is able to save; otherwise, false.
//    return !( $is_autosave || $is_revision ) && $is_valid_nonce;
	return $is_valid_nonce;
}

function has_files_to_upload( $id ) {
	return (!empty( $_FILES ) ) && isset( $_FILES[$id] );
}