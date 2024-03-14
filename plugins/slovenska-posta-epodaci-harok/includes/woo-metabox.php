<?php

/*
* Add metabox to Woocommerce order page
*/
function tsseph_add_meta_boxes()
{
	add_meta_box( 'tsseph_meta_box', __('ePodací hárok','spirit-eph'), 'tsseph_meta_box_details', 'shop_order', 'side', 'core' );
	add_meta_box( 'tsseph_meta_box', __('ePodací hárok','spirit-eph'), 'tsseph_meta_box_details', 'woocommerce_page_wc-orders', 'side', 'core' );
}
add_action( 'add_meta_boxes', 'tsseph_add_meta_boxes' );

/*
* Body of metabox
*/
function tsseph_meta_box_details($order)
{

	if (isset($order->post_type)) {
		$order =  new WC_Order($order->ID); 
	}

	$tsseph_weight = tsseph_calculate_weight($order->get_id());
	$tsseph_fragile = !empty($order->get_meta('tsseph_fragile', true )) ? $order->get_meta('tsseph_fragile', true ) : '';
	$tsseph_tracking_no = tsseph_get_tracking_code($order->get_id());

	?>
		<ul class="tsseph_meta">
			<li>
				<?php wp_nonce_field( 'tsseph_meta_box', 'tsseph_meta_box_nonce' ); ?>
				<label for="tsseph_weight"><?php _e('Váha objednávky (kg)','spirit-eph'); ?></label>
				<input type="text" name="tsseph_weight" id="tsseph_weight" value="<?php echo $tsseph_weight; ?>" />
			</li>
			<li>
				<label for="tsseph_fragile">
					<input type="checkbox" value="1" name="tsseph_fragile" id="tsseph_fragile" <?php checked($tsseph_fragile,"1",true); ?>/>
					<?php _e('Pozor krehké','spirit-eph') ?>
				</label>
			</li>	
			<li style="margin-top: 40px;">
				<label for="tsseph_tsseph_tracking_no" style="display:block;"><?php _e('Číslo zásielky','spirit-eph'); ?></label>
				<input disabled type="text" name="tsseph_tsseph_tracking_no" id="tsseph_tsseph_tracking_no" value="<?php echo $tsseph_tracking_no; ?>" />
				<div class="tooltip">
					<span class="dashicons dashicons-info"></span>
					<span class="tooltiptext" style="width:150px;">
						<?php _e('Po odoslaní objednávky do EPH cez API a vygenerovaní adresných štítkov, sa na tomto mieste zobrazí číslo zásielky (tracking number).', 'spirit-eph'); ?>
					</span>
                </div>  
				<?php if ($tsseph_tracking_no != '') { ?>
					<a href="https://tandt.posta.sk/zasielky/<?php echo $tsseph_tracking_no; ?>" target="_blank">Sledovať zásielku.</a>
				<?php } ?>				
			</li>						
		</ul>
	<?php
}

/*
* Hook on save order action
*/
function tsseph_save_meta_box($order_id) {

	// Check if our nonce is set.
	if ( ! isset( $_POST['tsseph_meta_box_nonce'] ) ) {
		return $order_id;
	}

	$nonce = $_POST['tsseph_meta_box_nonce'];

	// Verify that the nonce is valid.
	if ( ! wp_verify_nonce( $nonce, 'tsseph_meta_box' ) ) {
		return $order_id;
	}	

	// If this is an autosave, don't do anything
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return $order_id;
	}

	//Sanitize
	if (isset( $_POST['tsseph_meta_box_nonce'])) {
		$tsseph_weight = tsseph_only_number($_POST['tsseph_weight']);
	}
	else {
		$tsseph_weight = 1;
	}

	if (isset( $_POST['tsseph_fragile'])) {
		$tsseph_fragile = absint($_POST['tsseph_fragile']);
	}
	else {
		$tsseph_fragile = 0;
	}	

	$order = wc_get_order( $order_id );
	$order->update_meta_data( 'tsseph_weight', $tsseph_weight );
	$order->update_meta_data( 'tsseph_fragile', $tsseph_fragile );
	$order->save();
}
//add_action( 'save_post', 'tsseph_save_meta_box', 999999, 1 );
add_action( 'woocommerce_process_shop_order_meta', 'tsseph_save_meta_box', 10, 1 );
