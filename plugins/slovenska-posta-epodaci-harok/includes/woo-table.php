<?php

/*
*	Register new column to WooCommerce order table (eph_shipping)
*/
function tsseph_add_columns( $columns ) {
    $columns['eph_shipping'] = 'Druh zasielky';
	$columns['eph_parcel_number'] = 'Podacie číslo';
    return $columns;
}
add_filter( 'manage_edit-shop_order_columns', 'tsseph_add_columns' );
add_filter( 'woocommerce_shop_order_list_table_columns', 'tsseph_add_columns' );

/*
*	Add UI for the new column to WooCommerce order table (eph_shipping)
*/ 
function tsseph_add_shipping_method_col_admin( $column, $order ) {

	//Before HPOS $order is $PostID
	if (is_int($order)) {
		$order = new WC_Order($order); 
	}
   
    if ( 'eph_shipping' === $column ) {

    //Load selected eph shipping method
	$eph_shipping_method = $order->get_meta('tsseph_shipping_method_id', true); 

     if (empty($eph_shipping_method)) {

		foreach( $order->get_items( 'shipping' ) as $Shipping_item_obj ){
			$shipping_instance_id = $Shipping_item_obj->get_instance_id();
		}
		
		$tsseph_options= get_option( 'tsseph_options' );

		//Backward compatibility with version 1.0.9
		if (isset($tsseph_options['PredvolenyDruhZasielky']) && empty($tsseph_options['PredvolenyDruhZasielky_' . $shipping_instance_id])) {
			$eph_shipping_method = $tsseph_options['PredvolenyDruhZasielky'];
		} else {
			$eph_shipping_method = (isset($tsseph_options['PredvolenyDruhZasielky_' . $shipping_instance_id]) ? $tsseph_options['PredvolenyDruhZasielky_' . $shipping_instance_id] : 1); 
		}
    }

?>
    <div class="no-link">
		<select onchange="tsseph_shipping_change(this,'<?php echo wp_nonce_url( admin_url( 'admin-ajax.php?action=tsseph_set_shipping_method&order_id=' . $order->get_id() ), 'tsseph_set_shipping_method' );?>')">        

		<?php 
			foreach(tsseph_get_druh_zasielky_options() as $key => $druh_zasielky) {
				echo "<option value=\"" . $key ."\" " . selected( $eph_shipping_method, $key ) . ">" . $druh_zasielky . "</option>"; 
				} 
			?>
		
		</select>
		<div class="eph_save_status"><span class="dashicons dashicons-yes"></span></div>
    </div>
<?php
    }
}
add_action( 'manage_shop_order_posts_custom_column', 'tsseph_add_shipping_method_col_admin', 10, 2 );
add_action( 'woocommerce_shop_order_list_table_custom_column', 'tsseph_add_shipping_method_col_admin', 10, 2);

/*
*	Add UI for the new column to WooCommerce order table (eph_parcel_number)
*/ 
function tsseph_add_parcel_number_col_admin( $column, $order ) {
   
	$tsseph_bonus_options= get_option( 'tsseph_bonus_options' );

	//Before HPOS $order is $PostID
	if (is_int($order)) {
		$order = new WC_Order($order); 
	}

    if ( 'eph_parcel_number' === $column ) {

    //Load selected eph shipping method
	$eph_parcel_number = $order->get_meta('tsseph_tracking_no', true); 
	$eph_label = $order->get_meta('tsseph_labels', true); 

		if (!empty($eph_parcel_number)) {
			if (!empty($eph_label) && isset($tsseph_bonus_options[1563]) && $tsseph_bonus_options[1563]['Enabled']) {
				?>
					<div class="link">
						<a href="<?php echo $eph_label; ?>" target="_blank"><?php echo $eph_parcel_number; ?></a>
					</div>
				<?php
				}
				else {
				?>
					<div class="no-link">
						<span><?php echo $eph_parcel_number; ?></span>
					</div>
				<?php				
				}			
		}

	}
}
add_action( 'manage_shop_order_posts_custom_column', 'tsseph_add_parcel_number_col_admin', 10, 2 );
add_action( 'woocommerce_shop_order_list_table_custom_column', 'tsseph_add_parcel_number_col_admin', 10, 2);

/*
*	Ajax callback for saving eph shipping method id
*/ 
function tsseph_set_shipping_method() {
    if(isset($_POST['shipping_method_id'])) { $shipping_method_id = absint($_POST['shipping_method_id']);} else $shipping_method_id = 0;
    if(isset($_GET['order_id'])) { $order_id = absint($_GET['order_id']);} else $order_id = 0;

	$order = wc_get_order( $order_id );

    //Save shipping id 
	$order->update_meta_data( 'tsseph_shipping_method_id', $shipping_method_id );
	$order->save();

    wp_die();
}
add_action('wp_ajax_tsseph_set_shipping_method', 'tsseph_set_shipping_method');