<?php

// admin page
//require_once ('initilize.php');
function customcostume_admin() {
	/** future featuers
	 * Approve database rows
	 * allow users to edit previous uploads
	 */
	
	//TODO security
	// use current_user_can() to chect user can do action
	
	//TODO
	//Add editing of shop here
	global $wpdb;
	$wpdb->show_errors(); //used to show SQL errors
	
	$table_name = $wpdb->prefix . "shopsdb";
	$current_user = wp_get_current_user();
	$shopdb_sql = "SELECT * FROM $table_name WHERE wp_user_id = $current_user->ID LIMIT 1";
	$user_shop = $wpdb->get_row( $shopdb_sql );
	
	print_r($user_shop);
	
	//Table
	//Shop name, shop URL and save button colums
	//text boxes in the table with the values in them and a link to save the data
	
	//TODO security
	// add nonce to form submission
	?>
 <table>
  <tr>
    <th>Shop name</th>
    <th>URL</th>
    <th></th>
  </tr>
  <tr>
    <td><input id="shop_name" type="text" value="<?php echo $user_shop->shopName?>" /></td>
    <td><input id="shop_URL" type="text" value="<?php echo $user_shop->shopUrl?>" /></td>
    <td><input id="save_shop_changes_to_db" type="button" value="save" /></td>
	<td><input id="shop_id" type="hidden" value="<?php echo $user_shop->id?>" /></td>
  </tr>
 </table> 

<?php
	//TODO
	//Add editing of pieces here
	
	require_once ('db_dummy_data.php');
	echo 'Some output <br><br>';

// remove all data from databases and recreate them
	echo '<input id="cleandb" type="button" value="Clean DB" />';

	//add data to the databases
	echo '<input id="populatedb" type="button" value="Add data to DB" />';

	//DIV for results and use AJAX to update with data
	echo '<div id="results"></div>';
	
}

add_action( 'admin_footer', 'save_shop_changes_to_db' ); // Write our JS below here

function save_shop_changes_to_db() {
	?>
	<script type="text/javascript" >
	    jQuery('#save_shop_changes_to_db').click(function () { //so on click of the button with id = clickME

	    var shop_name = jQuery("#shop_name").val();
		var shop_URL = jQuery("#shop_URL").val();
		var shop_id = jQuery("#shop_id").val();
		
//		var name = document.getElementById("name");
//if (name.value != name.defaultValue) alert("#name has changed");
		
		var data = {
	            'action': 'save_shop_changes_to_db',
				'shop_name': shop_name,
				'shop_URL': shop_URL,
				'shop_id': shop_id
	        };

	        // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
	        jQuery.post(ajaxurl, data, function (response) {
	//			On complete update DIV element
	            document.getElementById("results").innerHTML = response;
	        });


	    });
	</script> <?php

}

add_action( 'wp_ajax_save_shop_changes_to_db', 'save_shop_changes_to_db_callback' );

function save_shop_changes_to_db_callback() {
	global $wpdb; // this is how you get access to the database
	$wpdb->show_errors( true );
//	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' ); // required for dbDelta()


	$table_name = $wpdb->prefix . "shopsdb";
	
	$shop_name = $_POST['shop_name'];
	$shop_URL = $_POST['shop_URL'];
	$shop_id = $_POST['shop_id'];
	
	$data = array('shopName'=>$shop_name, 'shopURL'=>$shop_URL);
	
	$where = array('id'=>$shop_id);
	
	$format = array('%s','%s');
	
	$where_format = array('%d');
	
	$temp = $wpdb->update($table_name, $data, $where, $format, $where_format); //temp is false if this fails

//	dbDelta( "UPDATE $table_name SET 'shopName' = '$shop_name', `shopUrl` = '$shop_URL' "
//			. "WHERE id = '$shop_id'" );
	
	$result = $wpdb->get_results( "SELECT * FROM $table_name", ARRAY_A );
		
	$wpdb->flush();
	
	if (!$temp) {
		wp_die("No rows updated");
	} else {
		wp_die("Changed $temp rows");
	}
	
//	wp_die(  var_dump( $temp));
//	wp_die( "Got values $shop_name, $shop_URL and $shop_id. Database affected $temp rows.<pre>" . print_r( $result ) . '</pre>' ); // this is required to terminate immediately and return a proper response

}


$wpdb->flush();