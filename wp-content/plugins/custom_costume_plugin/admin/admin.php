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

	print_r( $user_shop );

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
			<th></th>
		</tr>
		<tr>
			<td><input id="shop_name" type="text" value="<?php echo $user_shop->shopName ?>" /></td>
			<td><input id="shop_URL" type="text" value="<?php echo $user_shop->shopUrl ?>" /></td>
			<td><input id="save_shop_changes_to_db" type="button" value="save" /></td>
			<td><input id="shop_id" type="hidden" value="<?php echo $user_shop->id ?>" /></td>
		</tr>
	</table> 

	<?php
	//TODO
	//Add editing of pieces here

	$table_name = $wpdb->prefix . "piecesdb";
	$piecesdb_sql = "SELECT * FROM $table_name WHERE shop_id = $user_shop->id";
	$user_pieces = $wpdb->get_results( $piecesdb_sql );
	?>
	<table id="pieces">
		<tr>
			<th>Piece name</th>
			<th>Location</th>
			<th>URL</th>
			<th>Edit</th>
			<th></th>
		</tr>
		<?php
		foreach ( $user_pieces as $piece ) {
			
			//TODO
			// Make the user pust the edit button to be able to edit 1 row
			?>
			<tr>
				<td><?php echo $piece->pieceName ?></td>
				<td><?php echo $piece->location ?></td>
				<td><?php echo $piece->pictUrl ?></td>
				<td><input id="<?php echo $piece->id ?>" type="button" value="Edit" /></td>
				<td hidden="true"><?php echo $piece->id ?></td>
			</tr>
			<?php
		}
		?>
	</table> 

	<?php
	require_once ('db_dummy_data.php');
	echo 'Some output <br><br>';

// remove all data from databases and recreate them
	echo '<input id="cleandb" type="button" value="Clean DB" />';

	//add data to the databases
	echo '<input id="populatedb" type="button" value="Add data to DB" />';

	//DIV for results and use AJAX to update with data
	echo '<div id="results"></div>';
	
	//DIV for area to update piece
	echo '<div id="results"></div>';
	
	//need to display table
	//input areas with save button
	//hide old table
	// or do a dropdown of all the pieces and populate the fields based on the dropdown?
}

add_action( 'admin_footer', 'save_shop_changes_to_db' ); // Write our JS below here

function save_shop_changes_to_db() {
	?>
	<script type="text/javascript" >
	    jQuery('#save_shop_changes_to_db').click(function () { //so on click of the button with id = clickME

	        var shop_name = jQuery("#shop_name");
	        var shop_URL = jQuery("#shop_URL");
	        var shop_id = jQuery("#shop_id").val();

	        var data = {
	            'action': 'save_shop_changes_to_db',
	            'shop_id': shop_id
	        };

	        if (shop_name.val() !== shop_name.prop('defaultValue')) {
	            data.shop_name = shop_name.val();
	        }



	        if (shop_URL.val() !== shop_URL.prop('defaultValue')) {
	            data.shop_URL = shop_URL.val();
	        }

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

	$table_name = $wpdb->prefix . "shopsdb";

	$shop_name = $_POST['shop_name'];
	$shop_URL = $_POST['shop_URL'];
	$shop_id = $_POST['shop_id'];

	$data = array();
	$format = array();
	
	if ( $shop_name != null ) {
		$data['shopName'] = $shop_name;
		array_push( $format, '%s' );
	}

	if ( $shop_URL != null ) {
		$data['shopURL'] = $shop_URL;
		array_push( $format, '%s' );
	}

	$where = array();

	if ( $shop_id === null ) {
		wp_die( "Not available to update" );
	} else {
		$where['id'] = $shop_id;
	}

	$where_format = array( '%d' );

	$temp = $wpdb->update( $table_name, $data, $where, $format, $where_format ); //temp is false if this fails


	$wpdb->flush();

	if ( !$temp ) {
		wp_die( "No shop updated" );
	} else {
		wp_die( "Changed to $shop_name" );
	}
}

add_action( 'admin_footer', 'make_piece_editable' ); // Write our JS below here

function make_piece_editable() {
	?>
	<script type="text/javascript" >
	    jQuery('#pieces :button').click(function () { //so on click of the button with id = clickME


	        var piece_id = this.id; //the id of the piece
			var selected_row = this.parentNode.parentNode; //the selected row?
//			var i = this.parentNode.parentNode.rowIndex; //the index of the selected row
			var children_of_selected_row = selected_row.children; //table data element
			var piece_name = children_of_selected_row.item(0).innerHTML;
			var piece_location = children_of_selected_row.item(1).innerHTML;
			var piece_URL = children_of_selected_row.item(2).innerHTML;
			var piece_edit_button_id = children_of_selected_row.item(4).innerHTML;
			
//			document.getElementById("results").innerHTML = document.getElementById("pieces").rows;
			
			//hide all rows except the one that has been clicked on
			//Need to iterare from the end as row index is dynamic
//			document.getElementById("results").innerHTML = piece_edit_button_id;
			
			for (var j = document.getElementById("pieces").rows.length - 1; j > 0 ; j--) {
				
//					document.getElementById("results").innerHTML += i;
					document.getElementById("pieces").deleteRow(j);
				
			}
			
			//Add values from children_of_selected_row into the table as inputs
			
			var new_row = document.getElementById("pieces").insertRow(1);
			var piece_name_cell = new_row.insertCell(0);
			var c0 = document.createElement("input");
			c0.setAttribute('type', 'text');
			c0.setAttribute('value', piece_name);
			c0.id = "piece_name";
			piece_name_cell.appendChild(c0);
			
			var piece_location_cell = new_row.insertCell(1); //make this a drop-down
			var c1 = document.createElement("select");
			var arr = ["head", "body", "legs", "feet", "hand"];
			for (var x = 0; x<5; x++) {
				if (piece_location === arr[x]) {
					var o1 = new Option(arr[x]);
					o1.selected = true;
					c1.options.add(o1);
				} else {
					c1.options.add(new Option(arr[x]));
				}
			}
			c1.id = "piece_location";
			piece_location_cell.appendChild(c1);
			
			var piece_URL_cell = new_row.insertCell(2);
			var c2 = document.createElement("input");
			c2.setAttribute('type', 'text');
			c2.setAttribute('value', piece_URL);
			c2.id = "piece_URL";
			piece_URL_cell.appendChild(c2);
			
			var piece_button_cell = new_row.insertCell(3); //make this a save button that calls more js
			var c3 = document.createElement("button");
			c3.value = piece_id;
			c3.id = "save_piece_changes_to_db";
			c3.innerHTML = "Save";
			piece_button_cell.appendChild(c3);
			
			var piece_name_id = new_row.insertCell(4);
			piece_name_id.setAttribute('hidden', 'true');
			piece_name_id.id = "piece_id";
			piece_name_id.innerHTML = piece_edit_button_id;

	    });
	</script> <?php
}

add_action( 'admin_footer', 'save_piece_changes_to_db' ); // Write our JS below here

function save_piece_changes_to_db() {
	?>
	<script type="text/javascript" >
		//have to use ".on" as the button is created after js loads
	    jQuery(document).on('click', '#save_piece_changes_to_db', function () { //so on click of the button with id = clickME

	        var piece_name = jQuery("#piece_name");
			var piece_location = jQuery("#piece_location");
	        var piece_URL = jQuery("#piece_URL");
//	        var piece_id = jQuery("#piece_id").innerHTML;
			var piece_id = this.value;

	        var data = {
	            'action': 'save_piece_changes_to_db',
	            'piece_id': piece_id
	        };

	        if (piece_name.val() !== piece_name.prop('defaultValue')) {
	            data.piece_name = piece_name.val();
	        }
			
			if (piece_location.val() !== piece_location.prop('defaultValue')) {
	            data.piece_location = piece_location.val();
	        }

	        if (piece_URL.val() !== piece_URL.prop('defaultValue')) {
	            data.piece_URL = piece_URL.val();
	        }
			
//			document.getElementById("results").innerHTML = piece_id;

	        // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
	        jQuery.post(ajaxurl, data, function (response) {
	            //			On complete update DIV element
	            document.getElementById("results").innerHTML = response;
	        });

	    });
	</script> <?php
}

add_action( 'wp_ajax_save_piece_changes_to_db', 'save_piece_changes_to_db_callback' );

function save_piece_changes_to_db_callback() {
	global $wpdb; // this is how you get access to the database
	$wpdb->show_errors( true );

	$table_name = $wpdb->prefix . "piecesdb";

	$piece_name = $_POST['piece_name'];
	$location = $_POST['piece_location'];
	$piece_URL = $_POST['piece_URL'];
	$piece_id = $_POST['piece_id'];

	$data = array();

	$format = array();

	if ( $piece_name != null ) {
		$data['pieceName'] = $piece_name;
		array_push( $format, '%s' );
	}

	if ( $piece_URL != null ) {
		$data['pieceURL'] = $piece_URL;
		array_push( $format, '%s' );
	}

	if ( $location != null ) {
		$data['location'] = $location;
		array_push( $format, '%s' );
	}

	$where = array();

	if ( $piece_id === null ) {
		wp_die( "Not available to update" );
	} else {
		$where['id'] = $piece_id;
	}

	$where_format = array( '%d' );

	$temp = $wpdb->update( $table_name, $data, $where, $format, $where_format ); //temp is false if this fails


	$wpdb->flush();

	if ( !$temp ) {
		wp_die( "No piece updated" );
	} else {
		wp_die( "Changed to $piece_name" );
	}
}

$wpdb->flush();
