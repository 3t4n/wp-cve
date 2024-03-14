<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://shopup.lt/
 * @since      1.0.0
 *
 * @package    Woocommerce_Shopup_Venipak_Shipping
 * @subpackage Woocommerce_Shopup_Venipak_Shipping/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Woocommerce_Shopup_Venipak_Shipping
 * @subpackage Woocommerce_Shopup_Venipak_Shipping/admin
 * @author     ShopUp <info@shopup.lt>
 */
class Woocommerce_Shopup_Venipak_Shipping_Admin_Order_Edit {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 *
	 *
	 * @since    1.0.0
	 */
	private $settings;

	/**
	 *
	 *
	 * @since    1.2.0
	 */
	private $pickup_type;

	private $shopup_venipak_shipping_field_forcedispatch;

	private $shopup_venipak_shipping_field_maxpackproducts;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version, $settings ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->settings = $settings;
		$this->pickup_type = $settings->get_option_by_key('shopup_venipak_shipping_field_pickuptype');
		$this->shopup_venipak_shipping_field_forcedispatch = $settings->get_option_by_key('shopup_venipak_shipping_field_forcedispatch');
		$optionValue = $settings->get_option_by_key('shopup_venipak_shipping_field_maxpackproducts');
		if ($optionValue !== null) {
			$this->shopup_venipak_shipping_field_maxpackproducts = $optionValue;
		} else {
			$this->shopup_venipak_shipping_field_maxpackproducts = 1000;
		}
	}

	/**
	 *
	 *
	 * @since    1.0.0
	 */
	public function add_venipak_shipping_order_edit( $order ) {

		$shipping_method = @array_shift($order->get_shipping_methods());
    	$shipping_method_id = $shipping_method['method_id'];
    	
		if (!$this->shopup_venipak_shipping_field_forcedispatch && $shipping_method_id !== 'shopup_venipak_shipping_courier_method' && $shipping_method_id !== 'shopup_venipak_shipping_pickup_method') return;

		$venipak_pickup_point_id = $order->get_meta('venipak_pickup_point', true );
		$venipak_pickup_point_title = $this->get_venipak_point_title_by_id($venipak_pickup_point_id);
		$status = $this->get_venipak_status($order);
		$status_title = $this->get_venipak_status_title($status);
		$tracking_code = $this->get_venipak_tracking_code($order);
		$manifest = $this->get_venipak_manifest($order);
		$pack_collection = $this->get_venipak_packs($order);
		$pack_count = sizeof($pack_collection);
		$weight = $this->get_venipak_weight($order);
		$product_count = $this->get_product_count($order);
		$error_message = $this->get_venipak_error($order);

		?>
		<br class="clear" />
		<h4><?php echo __( 'Venipak shipping', 'woocommerce-shopup-venipak-shipping' ); ?> <a href="#" class="edit_address"></a></h4>
		<div class="address">
			<p><strong><?php echo __( 'Total weight', 'woocommerce-shopup-venipak-shipping' ); ?></strong><?php echo $weight . ' ' . __( 'kg.', 'woocommerce-shopup-venipak-shipping' ) ?></p>
			<p><strong><?php echo __( 'Products count', 'woocommerce-shopup-venipak-shipping' ); ?></strong><?php echo $product_count . ' ' . __( 'vnt.', 'woocommerce-shopup-venipak-shipping' ) ?></p>
			<p><strong><?php echo __( 'Packages count', 'woocommerce-shopup-venipak-shipping' ); ?></strong><?php echo $pack_count . ' ' . __( 'vnt.', 'woocommerce-shopup-venipak-shipping' )?></p>
		<?php if ($venipak_pickup_point_title) { ?>
			<p><strong><?php echo __( 'Pickup point', 'woocommerce-shopup-venipak-shipping' ); ?></strong><?php echo $venipak_pickup_point_title ?></p>
		<?php } ?>
		<?php if ($tracking_code) { ?>
			<p><strong><?php echo __( 'Tracking number', 'woocommerce-shopup-venipak-shipping' ); ?></strong><?php echo $tracking_code ?></p>
		<?php } ?>
			<p><strong><?php echo __( 'Dispatch status', 'woocommerce-shopup-venipak-shipping' ); ?></strong><?php echo $status_title ?></p>
			<div>
				<p><?php echo __( 'Packages count', 'woocommerce-shopup-venipak-shipping' ); ?>: <button onclick="addPackage();" type="button"><?php echo __( 'Add package', 'woocommerce-shopup-venipak-shipping' ); ?></button></p>
				<table id="packages-table">
					<tr>
						<th><?php echo __( 'Width', 'woocommerce-shopup-venipak-shipping' ); ?></th>
						<th><?php echo __( 'Height', 'woocommerce-shopup-venipak-shipping' ); ?></th>
						<th><?php echo __( 'Length', 'woocommerce-shopup-venipak-shipping' ); ?></th>
						<th><?php echo __( 'Weight', 'woocommerce-shopup-venipak-shipping' ); ?></th>
						<th><?php echo __( 'Description', 'woocommerce-shopup-venipak-shipping' ); ?></th>
						<th><?php echo __( 'Remove', 'woocommerce-shopup-venipak-shipping' ); ?></th>
					</tr>
					<?php for ($i = 0; $i < $pack_count; $i++) { ?>
					<tr class="venipak-pack">
						<td><input class="venipak-pack-width" style="width: 70px;" type="text" name="width[]" value="<?php echo $pack_collection[$i]['width']; ?>" /></td>
						<td><input class="venipak-pack-height" style="width: 70px;" type="text" name="height[]" value="<?php echo $pack_collection[$i]['height']; ?>" /></td>
						<td><input class="venipak-pack-length" style="width: 70px;" type="text" name="length[]" value="<?php echo $pack_collection[$i]['length']; ?>" /></td>
						<td><input class="venipak-pack-weight" style="width: 70px;" type="text" name="weight[]" value="<?php echo $pack_collection[$i]['weight']; ?>" /></td>
						<td><textarea class="venipak-pack-description" name="description[]"><?php echo $pack_collection[$i]['description']; ?></textarea></td>
						<td><button onclick="removePackage(this)" type="button"><?php echo __( 'Remove package', 'woocommerce-shopup-venipak-shipping' ); ?></button></td>
					</tr>
					<?php }	?>
				</table>
				<div>
					<input id="shopup_venipak_shipping_global" type="checkbox" name="is_global" />
					<label for="shopup_venipak_shipping_global"><?php echo __( 'Global shipment', 'woocommerce-shopup-venipak-shipping' ) ?></label>
				</div><br />
			<?php if ($status !== 'sent') { ?>
				<span class="button button-primary" onclick="event.stopPropagation(); shopup_venipak_shipping_dispatch_order_by_id({ id: <?php echo $order->get_id(); ?> });"><?php echo __( 'Dispatch', 'woocommerce-shopup-venipak-shipping' ) ?></span>
			<?php } ?>
			<?php if ($status === 'sent') { ?>
				<span class="button button-primary" onclick="event.stopPropagation(); shopup_venipak_shipping_dispatch_order_by_id({ id: <?php echo $order->get_id(); ?>, newDispatch: true });"><?php echo __( 'Dispatch one more time', 'woocommerce-shopup-venipak-shipping' ) ?></span>
			<?php } ?>
			</div>
			<div id="shopup_venipak_shipping_wrapper_order_<?php echo $order->get_id(); ?>" style="margin-top: 10px;">
			<?php if ($status === 'error') { ?>
				<p style="color: red;"><?php echo $error_message ?></p>
			<?php } ?>
			<?php if ($status === 'sent') { ?>
				<div>
					<a class="button button-primary" target="_blank" href="<?php echo admin_url('admin-ajax.php'); ?>?action=woocommerce_shopup_venipak_shipping_get_label_pdf&order_id=<?php echo $order->get_id(); ?>"><?php echo __( 'Print labels', 'woocommerce-shopup-venipak-shipping' ) ?></a>
					<a class="button button-primary" target="_blank" href="<?php echo admin_url('admin-ajax.php'); ?>?action=woocommerce_shopup_venipak_shipping_get_manifest_pdf&order_id=<?php echo $order->get_id(); ?>"><?php echo sprintf( __( 'Print manifest (%s)', 'woocommerce-shopup-venipak-shipping' ), $manifest) ?></a>
				</div>
			<?php } ?>
			</div>
		</div>
		<div class="edit_address">
		<?php
		$default_options = $venipak_pickup_point_id ? [$venipak_pickup_point_id => $venipak_pickup_point_title] : [];
		woocommerce_wp_select( array(
			'id' => 'venipak_pickup_point',
			'label' => __( 'Pickup point', 'woocommerce-shopup-venipak-shipping' ),
			'class' => 'venipak_pickup_point',
			'wrapper_class' => 'form-field-wide',
			'options' => $default_options
		) );
		?>
			<button type="button" onclick="jQuery('.venipak_pickup_point').val(null).trigger('change');"><?php echo __( 'Remove', 'woocommerce-shopup-venipak-shipping' ); ?></button>
		</div>
		<script>
		jQuery(document).ready(function($) {
			$.get('admin-ajax.php', { 'action': 'woocommerce_venipak_shipping_pickup_points' }, function(data) {
			$('.venipak_pickup_point').select2({
				data: data.map(value => ({
					id: value.id,
					text: `${value.name}, ${value.address}, ${value.city}, ${value.zip}`,
				})),
		    });
	      	$('.venipak_pickup_point').val("<?php echo $venipak_pickup_point_id; ?>").trigger('change');
      }, 'json');
  	});

		function addPackage() {
			jQuery('#packages-table tr:last').after('<tr class="venipak-pack"><td><input class="venipak-pack-width" style="width: 70px;" type="text" name="width[]" /></td><td><input class="venipak-pack-height" style="width: 70px;" type="text" name="height[]" /></td><td><input class="venipak-pack-length" style="width: 70px;" type="text" name="length[]" /></td><td><input class="venipak-pack-weight" style="width: 70px;" type="text" name="weight[]" /></td><td><textarea class="venipak-pack-description" name="description[]"></textarea></td><td><button onclick="removePackage(this)" type="button"><?php echo __( "Remove package", "woocommerce-shopup-venipak-shipping" ); ?></button></td></tr>');
		}

		function removePackage(cb) {
			jQuery(cb).closest('tr').remove();
		}
		</script>
		<?php
	}

	/**
	 *
	 *
	 * @since    1.0.0
	 */
	public function add_venipak_shipping_order_save( $order_id ) {
		$order = wc_get_order($order_id);
		if ( isset( $_POST['venipak_pickup_point'] )) {
			$order->update_meta_data('venipak_pickup_point', wc_clean( $_POST[ 'venipak_pickup_point' ] ) );
		} else {
			$order->delete_meta_data('venipak_pickup_point');
		}
		$order->save();
	}

	public function get_venipak_point_title_by_id($point_id) {
		if (!$point_id) {
			return null;
		}
		$collection = venipak_fetch_pickups();
		foreach ($collection as $key => $value) {
			if ($value['id'] == $point_id) {
				$venipak_pickup_entity = $value;
				break;
			}
		}
		if (!$venipak_pickup_entity) {
			return null;
		}
		return $venipak_pickup_entity['name'] . ' ' . $venipak_pickup_entity['address'] . ' ' . $venipak_pickup_entity['city'];
	}

	public function get_venipak_status($order) {
		$order_data = json_decode($order->get_meta('venipak_shipping_order_data'), true);
		if ($order_data) {
			return $order_data['status'];
		}
		return null;
	}

	public function get_venipak_tracking_code($order) {
		$order_data = json_decode($order->get_meta('venipak_shipping_order_data'), true);
		if ($order_data) {
			return $order_data['pack_numbers'] ? implode('<br/>', $order_data['pack_numbers']) : '';
		}
		return null;
	}

	public function get_venipak_manifest($order) {
		$order_data = json_decode($order->get_meta('venipak_shipping_order_data'), true);
		if ($order_data) {
			return $order_data['manifest'];
		}
		return null;
	}

	public function get_venipak_error($order) {
		$order_data = json_decode($order->get_meta('venipak_shipping_order_data'), true);
		if ($order_data) {
			return $order_data['error_message'];
		}
		return null;
	}


	public function get_venipak_packs($order) {
		$order_products = [];
		$order_description = '';
		$weight = 0;
		foreach ( $order->get_items() as $item_id => $product_item ) {
			$product = $product_item->get_product();
			if (!$product) continue;
			$product_weight = $this->get_product_weight($product);
			$product_quantity = $product_item->get_quantity();
			$order_description .= $product_item->get_product()->get_title() . PHP_EOL;
			for ($i = 0; $i < $product_quantity; $i++) {
				$order_products[] = $product;
				$weight += $product_weight;
			}
		}
		$weight = wc_get_weight($weight, 'kg');
		$pack_collection = array();
		if ($order->get_meta('venipak_pickup_point', true )) {
			$pack_collection[] = array(
				'length' => 0,
				'width' => 0,
				'height' => 0,
				'weight' => $weight,
				'description' => $order_description,
			);
			return $pack_collection;
		}

		$pack_count = ceil(sizeof($order_products) / $this->shopup_venipak_shipping_field_maxpackproducts);


		for ($i = 0; $i < $pack_count; $i++) {
			$range_from = $i * $this->shopup_venipak_shipping_field_maxpackproducts;
			$range_to = $range_from + $this->shopup_venipak_shipping_field_maxpackproducts;
			$pack_weight = 0;
			$pack_description = '';
			$prev_title = 'no-repeat';

			for ($y = $range_from; $y < $range_to; $y++) {
				if (!array_key_exists($y, $order_products)) break;
				$pack_weight += $this->get_product_weight($order_products[$y]);
				if ($prev_title !== $order_products[$y]->get_title()) {
					$pack_description .= $order_products[$y]->get_title() . PHP_EOL;
					$prev_title = $order_products[$y]->get_title();
				}
			}
			$pack_weight = wc_get_weight($pack_weight, 'kg');
			$pack_collection[] = array(
				'length' => 0,
				'width' => 0,
				'height' => 0,
				'weight' => $pack_weight,
				'description' => $pack_description,
			);
		}

		return $pack_collection;
	}

	public function get_venipak_weight($order) {
		$order_data = json_decode($order->get_meta('venipak_shipping_order_data'), true);
		if ($order_data && $order_data['status'] === 'sent') {
			return $order_data['weight'];
		}

		$weight = 0;
		foreach ( $order->get_items() as $item_id => $product_item ) {
			$product = $product_item->get_product();
			if (!$product) continue;
			$weight += $this->get_product_weight($product) * $product_item->get_quantity();
		}

		return wc_get_weight($weight, 'kg');
	}

	public function get_product_weight($product) {
		if (!$product->get_virtual()) {
			$weight = $product->get_weight();
			if ($weight) {
				return $weight;
			}
		}

		return 0;
	}

	public function get_venipak_status_title($status) {
		switch($status) {
			case "waiting":
				return __( 'Waiting', 'woocommerce-shopup-venipak-shipping' );
			case "sent":
				return __( 'Sent', 'woocommerce-shopup-venipak-shipping' );
			case "error":
				return __( 'Error', 'woocommerce-shopup-venipak-shipping' );
		}
		return null;
	}

	public function get_product_count($order) {
		$order_data = json_decode($order->get_meta('venipak_shipping_order_data'), true);
		if ($order_data) {
			return $order_data['products_count'];
		}
		$count = 0;
		foreach ( $order->get_items() as $item_id => $product_item ) {
			$product_quantity = $product_item->get_quantity();
			$count += $product_quantity;
		}
		return $count;
	}
}
