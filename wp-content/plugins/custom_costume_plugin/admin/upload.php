<?php

//page where a user can choose a piece to upload
function customcostume_handle_upload() {
	// ref https://codex.wordpress.org/Function_Reference/media_handle_upload
	// On page
	// Fields that the user has to fill in
	// Media upload box that does not allow them to see other authors content
	// Save user fields as metadata? Definatly want to do minimum of location and approved or mot
	?>
	<!DOCTYPE html>
	<html>
		<body>
			<form id="upload_new_piece_to_db" action= "" enctype="multipart/form-data">
				Piece name: <input type="text" name="pieceName" cols="50">
				<br>
				Location: <select name="location">
					<option value="head">Head</option>
					<option value="hand">Hand</option>
					<option value="body">Body</option>
					<option value="legs">Legs</option>
					<option value="feet">Feet</option>
				</select>                <br>
				Picture: <!--<input type="text" name="pictURL">-->   
				<!--<input id="upload_image" type="text" size="36" name="ad_image" value="http://" />-->
				<input type="file" name="my_image_upload" id="my_image_upload"  multiple="false" /><br>
				<input type="button" name="upload_image" class="button" id="upload_image_button" value="Upload Image"/><br>

				<?php wp_nonce_field( 'my_image_upload', 'my_image_upload_nonce' ); ?>

				<!--				<label for="upload_image">
									<input id="upload_image" type="text" size="36" name="pictURL" value="http://" />
									<input id="upload_image_button" class="button" type="button" value="Upload Image" /></label> <br>-->
				<input type="submit" name="submit" class="button" id="submit_btn" value="Submit"/> 
			</form>
			<!--			<br>Enter a URL or upload an image<br>
						<p>
							<label for="example-jpg-file">
								Select File To Upload:
							</label>
							<input type="file" id="example-jpg-file" name="example-jpg-file" value="" />
			<?php wp_nonce_field( plugin_basename( __FILE__ ) . 'upload_new_piece_to_db', 'example-jpg-nonce' ); ?>
							<input type="submit" name="submit" class="button" id="submit_btn"/>
						</p>-->
		</body>    </html> <?php
}

// Add a script to plug into the WP Uploder
//add_action( 'admin_enqueue_scripts', 'wp_uploder_script' );

function wp_uploder_script() {
	?>
	<script type="text/javascript" >

	    var _validFileExtensions = [".jpg", ".jpeg", ".bmp", ".gif", ".png"];
	    var blnValid = false;

	    jQuery(document).ready(function ($) {
	        // When the button is clicked
	        // Grab file
	        // Check file is image
	        // Grab other fields
	        // post file to server

	        jQuery('#upload_image_button').on('click', function (event) {
	            event.preventDefault();

	            var the_file = jQuery("#my_image_upload");

	            var the_file_extension = the_file.substr((~-the_file.lastIndexOf(".") >>> 0) + 2);

	            for (var j = 0; j < _validFileExtensions.length; j++) {
	                var sCurExtension = _validFileExtensions[j];
	                if ((the_file_extension).toLowerCase() === sCurExtension.toLowerCase()) {
	                    blnValid = true;
	                    break;
	                }
	            }

	            if (!blnValid) {
	                alert("Sorry, " + the_file + " is invalid, allowed extensions are: " + _validFileExtensions.join(", "));
	                return false;
	            }

	            var piece_name = jQuery("#pieceName");
	            var location = jQuery("#location");

	            var data = {
	                'action': 'upload_new_piece_to_db',
	                'location': location,
	                'piece_name': piece_name
	            };

	            jQuery.post(ajaxurl, data, function (response) {
	                //			On complete update DIV element
	                document.getElementById("results").innerHTML = response;
	            });

	        });

	    });
	</script><?php
}

add_action( 'admin_footer', 'upload_new_piece_to_db' ); // Write our JS below here

function upload_new_piece_to_db() {
	?>
	<script type="text/javascript" >

	    var _validFileExtensions = [".jpg", ".jpeg", ".bmp", ".gif", ".png"];
	    var blnValid = false;

	    jQuery(document).ready(function ($) {
	        // When the button is clicked
	        // Grab file
	        // Check file is image
	        // Grab other fields
	        // post file to server

	        jQuery('#upload_image_button').on('click', function (event) {
	            event.preventDefault();

	            var the_file = jQuery("#my_image_upload");

	            var the_file_extension = the_file.val().substr((the_file.val().lastIndexOf(".") >>> 0) + 2);

	            for (var j = 0; j < _validFileExtensions.length; j++) {
	                var sCurExtension = _validFileExtensions[j];
	                if ((the_file_extension).toLowerCase() === sCurExtension.toLowerCase()) {
	                    blnValid = true;
	                    break;
	                }
	            }

	            if (!blnValid) {
	                alert("Sorry, " + the_file.val() + " is invalid, allowed extensions are: " + _validFileExtensions.join(", "));
	                return false;
	            }

	            var piece_name = jQuery("#pieceName");
	            var location = jQuery("#location");

	            var data = {
	                'action': 'upload_new_piece_to_db',
	                'location': location,
	                'piece_name': piece_name,
	                'the_file': the_file
	            };

	            jQuery.post(ajaxurl, data, function (response) {
	                //			On complete update DIV element
	                document.getElementById("results").innerHTML = response;
	            });

	        });

	    });
	</script> <?php
}

add_action( 'wp_ajax_upload_new_piece_to_db', 'upload_new_piece_to_db_callback' );

function upload_new_piece_to_db_callback() {
	global $wpdb; // this is how you get access to the database
	$wpdb->show_errors( true );

	//following info needed to add piece into db
	//shop id to be found with user ID get_current_user_id()
	//piece name
	//location
	//URL to the file


	$table_name = $wpdb->prefix . "piecessdb";

	$location = $_POST['location'];
	$piece_name = $_POST['piece_name'];
	$the_file = $_FILES['file'];

	// Get the type of the uploaded file. This is returned as "type/extension"
	$arr_file_type = wp_check_filetype( basename( $the_file['name'] ) );
	$uploaded_file_type = $arr_file_type['type'];

	// Set an array containing a list of acceptable formats
	$allowed_file_types = array( 'image/jpg', 'image/jpeg', 'image/gif', 'image/png' );

	// If the uploaded file is the right format
	if ( in_array( $uploaded_file_type, $allowed_file_types ) ) {

		// Options array for the wp_handle_upload function. 'test_upload' => false
		$upload_overrides = array( 'test_form' => false );

		// Handle the upload using WP's wp_handle_upload function. Takes the posted file and an options array
		$uploaded_file = wp_handle_upload( $the_file, $upload_overrides );

		// If the wp_handle_upload call returned a local path for the image
		if ( isset( $uploaded_file['file'] ) ) {

			// The wp_insert_attachment function needs the literal system path, which was passed back from wp_handle_upload
			$file_name_and_location = $uploaded_file['file'];

			// Generate a title for the image that'll be used in the media library
			$file_title_for_media_library = $piece_name;

			// Set up options array to add this file as an attachment
			$attachment = array(
				'post_mime_type' => $uploaded_file_type,
				'post_title' => 'Uploaded image ' . addslashes( $file_title_for_media_library ),
				'post_content' => '',
				'post_status' => 'inherit'
			);

			// Run the wp_insert_attachment function. This adds the file to the media library and generates the thumbnails. If you wanted to attch this image to a post, you could pass the post id as a third param and it'd magically happen.
			$attach_id = wp_insert_attachment( $attachment, $file_name_and_location );
			require_once(ABSPATH . "wp-admin" . '/includes/image.php');
			$attach_data = wp_generate_attachment_metadata( $attach_id, $file_name_and_location );
			wp_update_attachment_metadata( $attach_id, $attach_data );

			
			$upload_feedback = false;
		} else { // wp_handle_upload returned some kind of error. the return does contain error details, so you can use it here if you want.
			$upload_feedback = 'There was a problem with your upload.';
		}
	} else { // wrong file type
		$upload_feedback = 'Please upload only image files (jpg, gif or png).';
	}

	
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
