<?php

    /**
    * Description : Define different shipping costs for products, based on customer location
    * Package : Innozilla Per Product Shipping WooCommerce
    * Version : 1.0.0
    * Author : Innozilla
    */


if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class IPPSW_Shipping_Per_Product_Admin {

	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
		add_action( 'woocommerce_product_options_shipping', array( $this, 'product_options' ) );
		add_action( 'woocommerce_variation_options', array( $this, 'variation_options' ), 10, 3 );
		add_action( 'woocommerce_product_after_variable_attributes', array( $this, 'product_after_variable_attributes' ), 10, 3 );
		add_action( 'woocommerce_process_product_meta', array( $this, 'save' ) );
		add_action( 'woocommerce_save_product_variation', array( $this, 'save_variation' ), 10, 2 );
	}

	/**
	 * Scripts and styles
	 */
	public function admin_enqueue_scripts() {
		wp_enqueue_style( 'wc-shipping-per-product-styles', plugins_url( 'assets/css/admin.css', IPPSW_PER_PRODUCT_SHIPPING_FILE ) );
		wp_register_script( 'wc-shipping-per-product', plugins_url( 'assets/js/shipping-per-product.min.js', IPPSW_PER_PRODUCT_SHIPPING_FILE ), array( 'jquery' ), IPPSW_PER_PRODUCT_SHIPPING_VERSION, true );

		wp_localize_script( 'wc-shipping-per-product', 'IPPSW_Shipping_Per_Product_params', array(
			'i18n_no_row_selected' => __( 'No row selected', 'woocommerce-shipping-per-product' ),
			'i18n_product_id'      => __( 'Product ID', 'woocommerce-shipping-per-product' ),
			'i18n_country_code'    => __( 'Country Code', 'woocommerce-shipping-per-product' ),
			'i18n_state'           => __( 'State Code', 'woocommerce-shipping-per-product' ),
			'i18n_postcode'        => __( 'Zip Code', 'woocommerce-shipping-per-product' ),
			'i18n_cost'            => __( 'Cost', 'woocommerce-shipping-per-product' ),
			'i18n_item_cost'       => __( 'Item Cost', 'woocommerce-shipping-per-product' )
		) );
	}

	/**
	 * Output product options
	 */
	public function product_options() {
		global $post, $wpdb;

		wp_enqueue_script( 'wc-shipping-per-product' );

		echo '</div><div class="options_group per_product_shipping">';

		woocommerce_wp_checkbox( array( 'id' => '_per_product_shipping', 'label' => __('Per-product shipping', 'woocommerce-shipping-per-product'), 'description' => __('Enable per-product shipping cost', 'woocommerce-shipping-per-product')  ) );

		$this->output_rules();
	}

	/**
	 * Output variation options
	 */
	public function variation_options( $loop, $variation_data, $variation ) {
		wp_enqueue_script( 'wc-shipping-per-product' );
		?>
		<label><input type="checkbox" class="checkbox enable_per_product_shipping" name="_per_variation_shipping[<?php echo $variation->ID; ?>]" <?php checked( get_post_meta( $variation->ID, '_per_product_shipping', true ), "yes" ); ?> /> <?php _e( 'Per-variation shipping', 'woocommerce-shipping-per-product' ); ?></label>
		<?php
	}

	/**
	 * Show Rules
	 */
	public function product_after_variable_attributes( $loop, $variation_data, $variation ) {
		echo '<tr class="per_product_shipping per_variation_shipping"><td colspan="2">';
		$this->output_rules( $variation->ID );
		echo '</td></tr>';
	}

	/**
	 * Output rules table
	 */
	public function output_rules( $post_id = 0 ) {
		global $post, $wpdb;

		if ( ! $post_id ) {
			$post_id = $post->ID;
		}
		?>
		<div class="rules per_product_shipping_rules inn_wrap">

			<?php woocommerce_wp_checkbox( array( 'id' => '_per_product_shipping_add_to_all[' . $post_id . ']', 'label' => __( 'Adjust Shipping Costs', 'woocommerce-shipping-per-product'), 'description' => __( 'Add per-product shipping cost to all shipping method rates?', 'woocommerce-shipping-per-product'), 'value' => get_post_meta( $post_id, '_per_product_shipping_add_to_all', true ) ) ); ?>
			<!-- version 1.0.3 update -->
			<p class="add-pro"><a href="https://innozilla.com/wordpress-plugins/woocommerce-per-product-shipping/#pro" target="_blank"><b>Click here</b> to upgrade to <b>PRO VERSION</b> and get the latest plugin UPDATES & PREMIUM FEATURES. <b>ONE TIME PAYMENT ONLY.</b></a></p>
			<!-- version 1.0.2 update -->
			<p class="add-info"><a href="https://innozilla.com/country-codes-state-codes-list-woocommerce" target="_blank">Click here to see the list of country & state codes.</a></p>
			<!-- end of version 1.0.2 update -->
			<table class="widefat">
				<thead>
					<tr>
						<th>&nbsp;</th>
						<th><?php _e( 'Country Code', 'woocommerce-shipping-per-product' ); ?>&nbsp;<a class="tips" data-tip="<?php _e('A 2 digit country code, e.g. US. Leave blank to apply to all.', 'woocommerce-shipping-per-product'); ?>">[?]</a></th>
						<th><?php _e( 'State Code', 'woocommerce-shipping-per-product' ); ?>&nbsp;<a class="tips" data-tip="<?php _e('A state code, e.g. AL. Leave blank to apply to all.', 'woocommerce-shipping-per-product'); ?>">[?]</a></th>
						<th><?php _e( 'Zip Code', 'woocommerce-shipping-per-product' ); ?>&nbsp;<a class="tips" data-tip="<?php _e('Postcode for this rule. Wildcards (*) can be used. Leave blank to apply to all areas.', 'woocommerce-shipping-per-product'); ?>">[?]</a></th>
						<th class="cost"><?php _e( 'Line Cost (Excl. Tax)', 'woocommerce-shipping-per-product' ); ?>&nbsp;<a class="tips" data-tip="<?php _e('Decimal cost for the line as a whole.', 'woocommerce-shipping-per-product'); ?>">[?]</a></th>
						<th class="item_cost"><?php _e( 'Item Cost (Excl. Tax)', 'woocommerce-shipping-per-product' ); ?>&nbsp;<a class="tips" data-tip="<?php _e('Decimal cost for the item (multiplied by qty).', 'woocommerce-shipping-per-product'); ?>">[?]</a></th>
					</tr>
				</thead>	
				<tfoot>
					<tr>
						<th colspan="6">
							<a href="#" class="button button-primary insert" data-postid="<?php echo $post_id; ?>"><?php _e( 'Add row', 'woocommerce-shipping-per-product' ); ?></a>
							<a href="#" class="button remove"><?php _e( 'Delete row', 'woocommerce-shipping-per-product' ); ?></a>

						</th>
					</tr>
					<tr>
						<th class="pro-validation" colspan="6">
							<span class="dashicons dashicons-lock"></span> Multiple Shipping is only available on our <b>PRO VERSION!</b> <span>Please <a href="https://innozilla.com/wordpress-plugins/woocommerce-per-product-shipping/#pro" target="_blank">click here</a> to purchase the PRO version of the plugin. (One time payment)</span>
						</th>
					</tr>
				</tfoot>
				<tbody>
					<?php
						$rules = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}innozilla_per_product_shipping_rules_woo WHERE product_id = %d ORDER BY iz_rule_order;", $post_id ) );

						foreach ( $rules as $rule ) {
							?>
							<tr>
								<td class="sort">&nbsp;</td>
								<td class="country"><input type="text" value="<?php echo esc_attr( $rule->iz_rule_country ); ?>" placeholder="*" name="per_product_country[<?php echo $post_id; ?>][<?php echo $rule->iz_rule_id ?>]" /></td>
								<td class="state"><input type="text" value="<?php echo esc_attr( $rule->iz_rule_state ); ?>" placeholder="*" name="per_product_state[<?php echo $post_id; ?>][<?php echo $rule->iz_rule_id ?>]" /></td>
								<td class="postcode"><input type="text" value="<?php echo esc_attr( $rule->iz_rule_postcode ); ?>" placeholder="*" name="per_product_postcode[<?php echo $post_id; ?>][<?php echo $rule->iz_rule_id ?>]" /></td>
								<td class="cost"><input type="text" value="<?php echo esc_attr( $rule->iz_rule_cost ); ?>" placeholder="0.00" name="per_product_cost[<?php echo $post_id; ?>][<?php echo $rule->iz_rule_id ?>]" /></td>
								<td class="item_cost"><input type="text" value="<?php echo esc_attr( $rule->iz_rule_item_cost ); ?>" placeholder="0.00" name="per_product_item_cost[<?php echo $post_id; ?>][<?php echo $rule->iz_rule_id ?>]" /></td>
							</tr>
							<?php
						}
					?>
				</tbody>
			</table>
		</div>
		<?php
	}

	/**
	 * Save
	 */
	public function save( $post_id ) {
		global $wpdb;

		// Enabled or Disabled
		if ( ! empty( $_POST['_per_product_shipping'] ) ) {
			update_post_meta( $post_id, '_per_product_shipping', 'yes' );
			update_post_meta( $post_id, '_per_product_shipping_add_to_all', ! empty( $_POST['_per_product_shipping_add_to_all'][ $post_id ] ) ? 'yes' : 'no' );
		} else {
			delete_post_meta( $post_id, '_per_product_shipping' );
			delete_post_meta( $post_id, '_per_product_shipping_add_to_all' );
		}

		$countries  = ! empty( $_POST['per_product_country'][ $post_id ] ) ? $_POST['per_product_country'][ $post_id ] : '';
		$states     = ! empty( $_POST['per_product_state'][ $post_id ] ) ? $_POST['per_product_state'][ $post_id ] : '';
		$postcodes  = ! empty( $_POST['per_product_postcode'][ $post_id ] ) ? $_POST['per_product_postcode'][ $post_id ] : '';
		$costs      = ! empty( $_POST['per_product_cost'][ $post_id ] ) ? $_POST['per_product_cost'][ $post_id ] : '';
		$item_costs = ! empty( $_POST['per_product_item_cost'][ $post_id ] ) ? $_POST['per_product_item_cost'][ $post_id ] : '';
		$i= 0;

		if ( $countries ) {
			foreach ( $countries as $key => $value ) {
				if ( $key == 'new' ) {
					foreach ( $value as $new_key => $new_value ) {
						if ( ! empty( $countries[ $key ][ $new_key ] ) || ! empty( $states[ $key ][ $new_key ] ) || ! empty( $postcodes[ $key ][ $new_key ] ) || ! empty( $costs[ $key ][ $new_key ] ) || ! empty( $item_costs[ $key ][ $new_key ] ) ) {
							$wpdb->insert(
								$wpdb->prefix . 'innozilla_per_product_shipping_rules_woo',
								array(
									'iz_rule_country' 		=> $this->replace_aseterisk( sanitize_text_field($countries[ $key ][ $new_key ]) ),
									'iz_rule_state' 		=> $this->replace_aseterisk( sanitize_text_field($states[ $key ][ $new_key ]) ),
									'iz_rule_postcode' 	=> $this->replace_aseterisk( sanitize_text_field($postcodes[ $key ][ $new_key ]) ),
									'iz_rule_cost' 		=> sanitize_text_field($costs[ $key ][ $new_key ]),
									'iz_rule_item_cost' 	=> sanitize_text_field($item_costs[ $key ][ $new_key ]),
									'iz_rule_order'		=> $i++,
									'product_id'		=> absint( $post_id )
								)
							);
						}
					}
				} else {
					if ( ! empty( $countries[ $key ] ) || ! empty( $states[ $key ] ) || ! empty( $postcodes[ $key ] ) || ! empty( $costs[ $key ] ) || ! empty( $item_costs[ $key ] ) ) {
						$wpdb->update(
							$wpdb->prefix . 'innozilla_per_product_shipping_rules_woo',
							array(
								'iz_rule_country' 		=> $this->replace_aseterisk( sanitize_text_field($countries[ $key ]) ),
								'iz_rule_state' 		=> $this->replace_aseterisk( sanitize_text_field($states[ $key ]) ),
								'iz_rule_postcode' 	=> $this->replace_aseterisk( sanitize_text_field($postcodes[ $key ]) ),
								'iz_rule_cost' 		=> sanitize_text_field($costs[ $key ]),
								'iz_rule_item_cost' 	=> sanitize_text_field($item_costs[ $key ]),
								'iz_rule_order'		=> $i++
							),
							array(
								'product_id' 		=> absint( $post_id ),
								'iz_rule_id'	 		=> absint( $key )
							)
						);
					} else {
						$wpdb->query( $wpdb->prepare( "DELETE FROM {$wpdb->prefix}innozilla_per_product_shipping_rules_woo WHERE product_id = %d AND iz_rule_id = %s;", absint( $post_id ), absint( $key ) ) );
					}
				}
			}
		}
	}

	/**
	 * Replaces the aseterisks with emtpy string
	 *
	 * @param string $rule
	 * @return string
	 */
	public function replace_aseterisk( $rule ) {
		if ( ! empty( $rule ) && '*' === $rule ) {
			return '';
		}

		return $rule;
	}

	/**
	 * Save a variation
	 * @param  int $post_id ID of the variation being saved
	 */
	public function save_variation( $post_id, $index ) {
		global $wpdb;

		$enabled    = isset( $_POST['_per_variation_shipping'][ $post_id ] );
		$countries  = ! empty( $_POST['per_product_country'][ $post_id ] ) ? $_POST['per_product_country'][ $post_id ] : '';
		$states     = ! empty( $_POST['per_product_state'][ $post_id ] ) ? $_POST['per_product_state'][ $post_id ] : '';
		$postcodes  = ! empty( $_POST['per_product_postcode'][ $post_id ] ) ? $_POST['per_product_postcode'][ $post_id ] : '';
		$costs      = ! empty( $_POST['per_product_cost'][ $post_id ] ) ? $_POST['per_product_cost'][ $post_id ] : '';
		$item_costs = ! empty( $_POST['per_product_item_cost'][ $post_id ] ) ? $_POST['per_product_item_cost'][ $post_id ] : '';
		$i          = 0;

		if ( $enabled ) {
			update_post_meta( $post_id, '_per_product_shipping', 'yes' );
			update_post_meta( $post_id, '_per_product_shipping_add_to_all', ! empty( $_POST['_per_product_shipping_add_to_all'][ $post_id ] ) ? 'yes' : 'no' );

			foreach ( $countries as $key => $value ) {
				if ( $key == 'new' ) {
					foreach ( $value as $new_key => $new_value ) {
						if ( ! empty( $countries[ $key ][ $new_key ] ) || ! empty( $states[ $key ][ $new_key ] ) || ! empty( $postcodes[ $key ][ $new_key ] ) || ! empty( $costs[ $key ][ $new_key ] ) || ! empty( $item_costs[ $key ][ $new_key ] ) ) {
							$wpdb->insert(
								$wpdb->prefix . 'innozilla_per_product_shipping_rules_woo',
								array(
									'iz_rule_country' 		=> $this->replace_aseterisk( sanitize_text_field($countries[ $key ][ $new_key ]) ) ,
									'iz_rule_state' 		=> $this->replace_aseterisk( sanitize_text_field($states[ $key ][ $new_key ]) ) ,
									'iz_rule_state' 		=> $this->replace_aseterisk( sanitize_text_field($states[ $key ][ $new_key ]) ) ,
									'iz_rule_postcode' 	=> $this->replace_aseterisk( sanitize_text_field($postcodes[ $key ][ $new_key ]) ) ,
									'iz_rule_cost' 		=> sanitize_text_field($costs[ $key ][ $new_key ]) ,
									'iz_rule_item_cost' 	=> sanitize_text_field($item_costs[ $key ][ $new_key ]) ,
									'iz_rule_order'		=> $i++,
									'product_id'		=> absint( $post_id )
								)
							);
						}
					}
				} else {
					if ( ! empty( $countries[ $key ] ) || ! empty( $states[ $key ] ) || ! empty( $postcodes[ $key ] ) || ! empty( $costs[ $key ] ) || ! empty( $item_costs[ $key ] ) ) {
						$wpdb->update(
							$wpdb->prefix . 'innozilla_per_product_shipping_rules_woo',
							array(
								'iz_rule_country' 		=> $this->replace_aseterisk( sanitize_text_field($countries[ $key ]) ),
								'iz_rule_state' 		=> $this->replace_aseterisk( sanitize_text_field($states[ $key ]) ),
								'iz_rule_postcode' 	=> $this->replace_aseterisk( sanitize_text_field($postcodes[ $key ]) ),
								'iz_rule_cost' 		=> sanitize_text_field($costs[ $key ]),
								'iz_rule_item_cost' 	=> sanitize_text_field($item_costs[ $key ]),
								'iz_rule_order'		=> $i++
							),
							array(
								'product_id' 		=> absint( $post_id ),
								'iz_rule_id'	 		=> absint( $key )
							)
						);
					} else {
						$wpdb->query( $wpdb->prepare( "DELETE FROM {$wpdb->prefix}innozilla_per_product_shipping_rules_woo WHERE product_id = %d AND iz_rule_id = %s;", absint( $post_id ), absint( $key ) ) );
					}
				}
			}
		} else {
			delete_post_meta( $post_id, '_per_product_shipping' );
			delete_post_meta( $post_id, '_per_product_shipping_add_to_all' );
		}
	}
}

new IPPSW_Shipping_Per_Product_Admin();
