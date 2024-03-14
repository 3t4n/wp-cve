<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * WC_Product_Extra_Service_Assembly class. - Fork of https://wordpress.org/plugins/woocommerce-product-gift-wrap/
 */
class WC_Product_Extra_Service_Assembly {

	/* Single instance */
	protected static $_instance = null;

	public function __construct() {
		$default_message       = '{checkbox} '. sprintf( __( 'Request assembly of this item for %s?', 'product-assembly-cost' ), '{price}' );
		$default_service       = __( 'Assembly', 'product-assembly-cost' );
		$this->enabled_default = get_option( 'product_assembly_enabled' ) == 'yes' ? true : false;
		$this->default_cost    = get_option( 'product_assembly_cost', 0 );
		$this->cost_multiply   = get_option( 'product_assembly_cost_multiply', 'yes' ) == 'yes' ? true : false;
		$this->message         = get_option( 'product_assembly_message' );
		$this->service_name    = get_option( 'product_assembly_service' );
		$this->cost_mode       = get_option( 'product_assembly_cost_mode' ) == 'yes' ? 'subtotal' : 'product';
		$this->taxable         = wc_tax_enabled() && get_option( 'product_assembly_tax_status', 'yes' ) == 'yes' ? true : false;
		$this->tax_class       = wc_tax_enabled() ? get_option( 'product_assembly_tax_class', '' ) : '';
		$this->add_to_name     = get_option( 'product_assembly_add_to_name' ) == 'yes';
		$this->fee_name        = '';
		if ( ! $this->message ) {
			$this->message = $default_message;
		}
		if ( ! $this->service_name ) {
			$this->service_name = $default_service;
		}
		add_option( 'product_assembly_enabled', 'no' );
		add_option( 'product_assembly_cost', '0' );
		add_option( 'product_assembly_message', $default_message );
		add_option( 'product_assembly_service', $default_service );
		add_option( 'product_assembly_cost_mode', 'no' );
		// Init settings
		$this->settings = array(
			array(
				'title' => __( 'Product Assembly / Gift Wrap / ...', 'woocommerce' ),
				'type'  => 'title',
				'desc'  => '',
				'id'    => 'product_assembly_cost_title',
			),
			array(
				'title'   => __( 'Enabled by default?', 'product-assembly-cost' ),
				'desc'    => __( 'Activate the service by default for all products', 'product-assembly-cost' ),
				'id'      => 'product_assembly_enabled',
				'type'    => 'select',
				'options' => array(
					''    => __( 'No (you should enable it for each product individually)', 'product-assembly-cost' ),
					'yes' => __( 'Yes (you can disable it for each product individually)', 'product-assembly-cost' )
				)
			),
			array(
				'title' => __( 'Service', 'product-assembly-cost' ),
				'id'    => 'product_assembly_service',
				'desc'  => __( 'The service you are offering', 'product-assembly-cost' ),
				'type'  => 'text'
			),
			array(
				'title'    => __( 'Default cost', 'product-assembly-cost' ),
				'desc'     => sprintf(
					__( 'The cost of the service (%s), per product, unless overridden at the product level', 'product-assembly-cost' ),
					wc_prices_include_tax()
					?
					__( 'inclusive of tax' )
					:
					__( 'exclusive of tax' )
				),
				'id'       => 'product_assembly_cost',
				'type'     => 'number',
				'custom_attributes' => array(
					'step' => 0.01,
				),
			)
		);
		if ( wc_tax_enabled() ) $this->settings = array_merge( $this->settings, array(
			array(
				'title'   => __( 'Tax status', 'product-assembly-cost' ),
				'desc'    => __( 'Whether the cost is subject to taxes or not', 'product-assembly-cost' ),
				'id'      => 'product_assembly_tax_status',
				'type'    => 'select',
				'options' => array(
					'yes' => __( 'Taxable', 'product-assembly-cost' ),
					''    => __( 'None (are you sure?)', 'product-assembly-cost' )
				),
				'default' => 'yes'
			),
			array(
				'title'   => __( 'Tax class', 'product-assembly-cost' ),
				'desc'    => __( 'If taxable', 'product-assembly-cost' ),
				'id'      => 'product_assembly_tax_class',
				'type'    => 'select',
				'options' => wc_get_product_tax_class_options()
			),
		) );
		$this->settings = array_merge( $this->settings, array(
			array(
				'title'   => __( 'Multiply cost?', 'product-assembly-cost' ),
				'desc'    => __( 'Multiply service cost by the quantity of product purchased', 'product-assembly-cost' ),
				'id'      => 'product_assembly_cost_multiply',
				'type'    => 'select',
				'options' => array(
					'yes' => __( 'Yes', 'product-assembly-cost' ),
					'no'  => __( 'No (charge only once per cart line)', 'product-assembly-cost' )
				)
			),
			array(
				'title'    => __( 'Message', 'product-assembly-cost' ),
				'id'       => 'product_assembly_message',
				'desc'     => __( 'Change this text according to the service you are offering', 'product-assembly-cost' ).
								'<br/>'.
								__( '<code>{checkbox}</code> will be replaced with a checkbox and <code>{price}</code> will be replaced with the service cost', 'product-assembly-cost' ),
				'type'     => 'text',
				'desc_tip' => __( 'The checkbox and label shown to the user on the frontend', 'product-assembly-cost' )
			),
			array(
				'title' => __( 'Show cost as a global fee?', 'product-assembly-cost' ),
				'desc'  => __( 'Enable this to show the cost as a global fee on the cart subtotals, instead of adding the cost to the product itself - Recommended if you’re not multiplying the cost and to avoid product discounts applying to it', 'product-assembly-cost' ),
				'id'    => 'product_assembly_cost_mode',
				'type'  => 'checkbox',
			),
			//Add service name to cart/order item - Still not done
			array(
				'title' => __( 'Add service name to product', 'product-assembly-cost' ),
				'desc'  => __( 'Enable this to add the service name to the product line on the cart/order', 'product-assembly-cost' ),
				'id'    => 'product_assembly_add_to_name',
				'type'  => 'checkbox',
			),
			array(
				'type' => 'sectionend',
				'id'   => 'product_assembly_cost_title',
			),
		) );
		//Localisation
		add_action( 'plugins_loaded', array( $this, 'load_textdomain' ) );
		// Display on the front end
		add_action( 'after_setup_theme', function() {
			add_action(
				apply_filters( 'product_assembly_cost_frontend_hook', 'woocommerce_after_add_to_cart_button' ),
				array( $this, 'assembly_option_html' ),
				apply_filters( 'product_assembly_cost_frontend_priority', 10 )
			);
		} );
		// Filters for cart actions
		add_filter( 'woocommerce_add_cart_item_data', array( $this, 'add_cart_item_data' ), 10, 3 );
		add_filter( 'woocommerce_get_cart_item_from_session', array( $this, 'get_cart_item_from_session' ), 10, 2 );
		add_filter( 'woocommerce_get_item_data', array( $this, 'get_item_data' ), 10, 2 );
		add_filter( 'woocommerce_add_cart_item', array( $this, 'add_cart_item' ), 10, 1 ); //With price on the product itself
		add_action( 'woocommerce_checkout_create_order_line_item', array( $this, 'add_order_item_meta' ), 10, 3 );
		add_action( 'woocommerce_cart_calculate_fees', array( $this, 'assembly_fee' ) );
		add_action( 'woocommerce_before_calculate_totals', array( $this, 'cart_item_name' ) );
		// Admin
		add_filter( 'woocommerce_product_settings', array( $this, 'woocommerce_product_settings' ) );
		add_filter( 'plugin_action_links_'.PRODUCT_ASSEMBLY_COST_BASENAME, array( $this, 'add_settings_link' ) );
		// Edit product options
		add_action( 'woocommerce_product_options_pricing', array( $this, 'woocommerce_product_options_pricing' ), 999 );
		add_action( 'woocommerce_variation_options_pricing', array( $this, 'woocommerce_variation_options_pricing' ), 10, 3 );
		add_action( 'woocommerce_process_product_meta', array( $this, 'woocommerce_process_product_meta' ) );
		add_action( 'woocommerce_save_product_variation', array( $this, 'woocommerce_save_product_variation' ), 10, 2 );
	}

	/* Ensures only one instance of our plugin is loaded or can be loaded */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/* Localisation */
	public function load_textdomain() {
		load_plugin_textdomain( 'product-assembly-cost' );
	}

	/* Admin - settings */
	public function woocommerce_product_settings( $settings ) {
		foreach ( $this->settings as $setting ) {
			$settings[] = $setting;
		}
		return $settings;
	}

	/* Settings link on the plugins list */
	public function add_settings_link( $links ) {
		$action_links = array(
			sprintf(
				'<a href="admin.php?page=wc-settings&tab=products#product_assembly_enabled">%s</a>',
				__( 'Settings', 'product-assembly-cost' )
			)
		);
		return array_merge( $links, $action_links );
	}

	/* Admin - Show fields on the product edit screen - not variable */
	public function woocommerce_product_options_pricing() {
		global $post;
		$product = wc_get_product( $post->ID );
		$has_assembly     = trim( $product->get_meta( '_has_assembly' ) ) == 'yes';
		$disable_assembly = trim( $product->get_meta( '_disable_assembly' ) ) == 'yes';
		echo '</div><div class="options_group show_if_simple">';
		if ( $this->enabled_default ) {
			woocommerce_wp_checkbox( array(
				'id'            => '_has_assembly',
				'name'          => '_has_assembly',
				'wrapper_class' => '',
				'value'         => 'yes',
				'label'         => sprintf(
									__( '%s available', 'product-assembly-cost' ),
									$this->service_name
								),
				'desc_tip'      => false,
				'description'   => __( 'Enabled by default', 'product-assembly-cost' ),
				'custom_attributes' => array(
					'disabled' => 'disabled'
				)
			) );
			woocommerce_wp_checkbox( array(
				'id'            => '_disable_assembly',
				'name'          => '_disable_assembly',
				'wrapper_class' => '',
				'class'         => 'checkbox webdados_assembly_toggle',
				'value'         => $disable_assembly ? 'yes' : 'no',
				'label'         => sprintf(
									__( 'Disable %s', 'product-assembly-cost' ),
									$this->service_name
								),
				'desc_tip'      => false,
				'description'   => sprintf(
									__( 'Explicitly disable %s for this product', 'product-assembly-cost' ),
									$this->service_name
								),
			) );
		} else {
			woocommerce_wp_checkbox( array(
				'id'            => '_has_assembly',
				'name'          => '_has_assembly',
				'wrapper_class' => '',
				'class'         => 'checkbox webdados_assembly_toggle',
				'value'         => $has_assembly ? 'yes' : 'no',
				'label'         => sprintf(
									__( '%s available', 'product-assembly-cost' ),
									$this->service_name
								),
				'desc_tip'      => false,
				'description'   => sprintf(
									__( 'Enable this option if the customer can request the %s service for this product', 'product-assembly-cost' ),
									$this->service_name
								),
			) );
		}
		woocommerce_wp_text_input( array(
			'id'          => '_assembly_cost',
			'name'        => '_assembly_cost',
			'label'       => sprintf(
								__( '%s cost', 'product-assembly-cost' ),
								$this->service_name
							),
			'placeholder' => $this->default_cost,
			'desc_tip'    => true,
			'description' => sprintf(
				__( 'Override the default cost by inputting a value here (%s)', 'product-assembly-cost' ),
				wc_prices_include_tax()
				?
				__( 'inclusive of tax' )
				:
				__( 'exclusive of tax' )
			),
		) );
		wc_enqueue_js( "
			/* Single */
			function webdados_assembly_toggle() {
				jQuery( '._assembly_cost_field' ).hide();
				var show_assembly_fields = false;
				if ( jQuery( '#_has_assembly' ).is( ':checked' ) ) {
					show_assembly_fields = true;
					if ( jQuery( '#_disable_assembly' ).length ) {
						if ( jQuery( '#_disable_assembly' ).is( ':checked' ) ) {
							show_assembly_fields = false;
						}
					}
				}
				if ( show_assembly_fields ) {
					jQuery( '._assembly_cost_field' ).show();
				}
			}
			jQuery( 'input.webdados_assembly_toggle' ).change( function() {
				webdados_assembly_toggle();
			} );
			jQuery( document ).ready(function() {
				webdados_assembly_toggle();
			} );
		" );
	}
	public function woocommerce_variation_options_pricing( $loop, $variation_data, $variation ) {
		$variation        = wc_get_product( $variation->ID );
		//$product          = wc_get_product( $variation->get_parent_id() );
		$has_assembly     = trim( $variation->get_meta( '_has_assembly' ) ) == 'yes';
		$disable_assembly = trim( $variation->get_meta( '_disable_assembly' ) ) == 'yes';
		echo '</div><div class="variable_assembly_cost">';
		if ( $this->enabled_default ) {
			$next_field_row = 'first';
			woocommerce_wp_checkbox( array(
				'id'            => "_has_assembly{$loop}",
				'name'          => "_has_assembly[{$loop}]",
				'wrapper_class' => '',
				'value'         => 'yes',
				'label'         => '&nbsp;'.sprintf(
									__( '%s enabled by default', 'product-assembly-cost' ),
									$this->service_name
								),
				'desc_tip'      => false,
				'custom_attributes' => array(
					'disabled'  => 'disabled',
					'data-loop' => $loop
				),
				'wrapper_class' => 'form-row form-row-first',
			) );
			woocommerce_wp_checkbox( array(
				'id'            => "_disable_assembly{$loop}",
				'name'          => "_disable_assembly[{$loop}]",
				'wrapper_class' => '',
				'class'         => "checkbox webdados_assembly_toggle_variation{$loop}",
				'value'         => $disable_assembly ? 'yes' : 'no',
				'label'         => '&nbsp;'.sprintf(
									__( 'Explicitly disable %s for this product', 'product-assembly-cost' ),
									$this->service_name
								),
				'desc_tip'      => false,
				'custom_attributes' => array(
					'data-loop' => $loop
				),
				'wrapper_class' => 'form-row form-row-last',
			) );
		} else {
			$next_field_row = 'last';
			woocommerce_wp_checkbox( array(
				'id'            => "_has_assembly{$loop}",
				'name'          => "_has_assembly[{$loop}]",
				'wrapper_class' => '',
				'class'         => "checkbox webdados_assembly_toggle_variation{$loop}",
				'value'         => $has_assembly ? 'yes' : 'no',
				'label'         => '&nbsp;'.sprintf(
									__( 'Enable this option if the customer can request the %s service for this product', 'product-assembly-cost' ),
									$this->service_name
								),
				'desc_tip'      => false,
				'custom_attributes' => array(
					'data-loop' => $loop
				),
				'wrapper_class' => 'form-row form-row-first',
			) );
		}
		woocommerce_wp_text_input( array(
			'id'          => "_assembly_cost{$loop}",
			'name'        => "_assembly_cost[{$loop}]",
			'label'       => sprintf(
								__( '%s cost', 'product-assembly-cost' ),
								$this->service_name
							),
			'value'       => $variation->get_meta( '_assembly_cost' ),
			'placeholder' => $this->default_cost,
			'desc_tip'    => true,
			'description' => sprintf(
				__( 'Override the default cost by inputting a value here (%s)', 'product-assembly-cost' ),
				wc_prices_include_tax()
				?
				__( 'inclusive of tax' )
				:
				__( 'exclusive of tax' )
			),
			'custom_attributes' => array(
				'data-loop' => $loop
			),
			'wrapper_class' => 'form-row form-row-'.$next_field_row,
		) );
		echo "
		<script type='text/javascript'>
			function webdados_assembly_toggle_variations{$loop}() {
				jQuery( '._assembly_cost{$loop}_field' ).hide();
				var show_assembly_fields = false;
				if ( jQuery( '#_has_assembly{$loop}' ).is( ':checked' ) ) {
					show_assembly_fields = true;
					if ( jQuery( '#_disable_assembly{$loop}' ).length ) {
						if ( jQuery( '#_disable_assembly{$loop}' ).is( ':checked' ) ) {
							show_assembly_fields = false;
						}
					}
				}
				if ( show_assembly_fields ) {
					jQuery( '._assembly_cost{$loop}_field' ).show();
				}
			}
			jQuery( 'input.webdados_assembly_toggle_variation{$loop}' ).change( function() {
				webdados_assembly_toggle_variations{$loop}();
			} );
			webdados_assembly_toggle_variations{$loop}();
		</script>
		<div style='clear: both'></div>
		";
	}
	/* Admin - Save fields */
	public function woocommerce_process_product_meta( $post_id ) {
		$product = wc_get_product( $post_id );
		if ( $product->get_type() != 'variable' ) {
			$meta = array();
			if ( $this->enabled_default ) {
				$meta['_disable_assembly']  = ! empty( $_POST['_disable_assembly'] ) ? 'yes' : 'no';
			} else {
				$meta['_has_assembly']  = ! empty( $_POST['_has_assembly'] ) ? 'yes' : 'no';
			}
			$meta['_assembly_cost'] = ! empty( $_POST['_assembly_cost'] ) ? wc_clean( $_POST['_assembly_cost'] ) : '';
			//Update meta
			foreach ( $meta as $key => $value ) {
				$product->update_meta_data( $key, $value );
			}
			$product->save();
		}
	}
	public function woocommerce_save_product_variation( $variation_id, $index ) {
		$variation = wc_get_product( $variation_id );
		$meta = array();
		if ( $this->enabled_default ) {
			$meta['_disable_assembly']  = ! empty( $_POST['_disable_assembly'][ $index ] ) ? 'yes' : 'no';
		} else {
			$meta['_has_assembly']  = ! empty( $_POST['_has_assembly'][ $index ] ) ? 'yes' : 'no';
		}
		$meta['_assembly_cost'] = ! empty( $_POST['_assembly_cost'][ $index ] ) ? wc_clean( $_POST['_assembly_cost'][ $index ] ) : '';
		//Update meta
		foreach ( $meta as $key => $value ) {
			$variation->update_meta_data( $key, $value );
		}
		$variation->save();
	}

	/* Frontend - option on the product */
	public function assembly_option_html() {
		global $post;
		$show_assembly            = false;
		$product                  = wc_get_product( $post->ID );
		if ( $product->get_type() != 'variable' ) {
			$product_has_assembly     = trim( $product->get_meta( '_has_assembly' ) ) == 'yes';
			$product_disable_assembly = trim( $product->get_meta( '_disable_assembly' ) ) == 'yes';
			if ( $this->enabled_default ) {
				if ( ! $product_disable_assembly ) {
					$show_assembly = true;
				}
			} else {
				if ( $product_has_assembly ) {
					$show_assembly = true;
				}
			}
			if ( apply_filters( 'product_assembly_show_assembly', $show_assembly, $product ) ) {
				$current_value = ! empty( $_REQUEST['assembly'] ) ? 1 : 0;
				$cost          = apply_filters( 'product_assembly_cost', floatval( $product->get_meta( '_assembly_cost' ) ), $product );
				if ( $cost == 0 ) $cost = $this->default_cost; //Default
				$cost       = $this->show_cost_with_or_without_vat( $cost, $product );
				$price_text = $cost > 0 ? strip_tags( wc_price( $cost ) ) : __( 'free', 'product-assembly-cost' );
				$checkbox   = '<input type="checkbox" name="assembly" id="assembly-message-checkbox" value="yes" ' . checked( $current_value, 1, false ) . ' />';
				//Replace price
				$message = str_replace( '{price}', '<span id="assembly-message-price">'.$price_text.'</span>', wp_kses_post( $this->message ) );
				//Replace (or add) checkbox
				if ( stristr( $message, '{checkbox}' ) ) {
					$message = str_replace( '{checkbox}', $checkbox, $message );
				} else {
					$message = $checkbox.' '.$message;
				}
				?>
				<div id="product-assembly-container" style="clear:both; padding-top: .5em;">
					<?php do_action( 'product_assembly_cost_before_field' ); ?>
					<p class="product-assembly" style="clear:both; padding-top: .5em;">
						<label><?php echo apply_filters( 'product_assembly_cost_message', $message, $product ); ?></label>
					</p>
					<?php do_action( 'product_assembly_cost_after_field' ); ?>
				</div>
				<?php
			}
		} else {
			$current_value = ! empty( $_REQUEST['assembly'] ) ? 1 : 0;
			$checkbox   = '<input type="checkbox" name="assembly" id="assembly-message-checkbox" value="yes" ' . checked( $current_value, 1, false ) . ' />';
			//Replace price
			$message = str_replace( '{price}', '<span id="assembly-message-price">_</span>', wp_kses_post( $this->message ) );
			//Replace (or add) checkbox
			if ( stristr( $message, '{checkbox}' ) ) {
				$message = str_replace( '{checkbox}', $checkbox, $message );
			} else {
				$message = $checkbox.' '.$message;
			}
			?>
			<div id="product-assembly-container" style="clear:both; padding-top: .5em;">
				<?php do_action( 'product_assembly_cost_before_field' ); ?>
				<p class="product-assembly">
					<label><?php echo apply_filters( 'product_assembly_cost_message', $message, $product ); ?></label>
				</p>
				<?php do_action( 'product_assembly_cost_after_field' ); ?>
			</div>
			<?php
			$variations = $product->get_available_variations();
			foreach ( $variations as $variation ) {
				$variation_object         = wc_get_product( $variation['variation_id'] );
				$product_has_assembly     = trim( $variation_object->get_meta( '_has_assembly' ) ) == 'yes';
				$product_disable_assembly = trim( $variation_object->get_meta( '_disable_assembly' ) ) == 'yes';
				$cost                     = apply_filters( 'product_assembly_cost', floatval( $variation_object->get_meta( '_assembly_cost' ) ), $variation_object );
				if ( $cost == 0 ) $cost   = apply_filters( 'product_assembly_cost', floatval( $product->get_meta( '_assembly_cost' ) ), $product ); //Backwards compatibility - get from product
				if ( $cost == 0 ) $cost   = $this->default_cost; //Default
				$cost                     = $this->show_cost_with_or_without_vat( $cost, $product );
				$show_assembly            = 0;
				if ( $this->enabled_default ) {
					if ( ! $product_disable_assembly ) {
						$show_assembly = 1;
					}
				} else {
					if ( $product_has_assembly ) {
						$show_assembly = 1;
					}
				}
				?>
				<input type="hidden" id="variation-assembly-<?php echo $variation['variation_id']; ?>" value="<?php echo apply_filters( 'product_assembly_show_assembly_variation', $show_assembly, $product, $variation ); ?>" data-cost="<?php echo esc_attr( strip_tags( wc_price( $cost ) ) ); ?>"/>
				<?php
			}
			?>
			<script type="text/javascript">
				jQuery( document ).ready( function( $ ){
					function webdados_assembly_show_hide( variation ) {
						$( '#product-assembly-container' ).hide();
						$( '#assembly-message-checkbox' ).prop( 'checked', false );
						$( '#assembly-message-price' ).html( '_' );
						if ( variation ) {
							console.log(variation.variation_id);
							if ( $( '#variation-assembly-'+variation.variation_id ).val() == '1' ) {
								$( '#assembly-message-price' ).html( $( '#variation-assembly-'+variation.variation_id ).data( 'cost' ) );
								$( '#product-assembly-container' ).show();
							}
						}
					}
					$( ".single_variation_wrap" ).on( "show_variation", function ( event, variation ) {
						webdados_assembly_show_hide( variation );
					} );
					webdados_assembly_show_hide( null );
				} );
			</script>
			<?php
		}
	}

	/* Show cost with or without VAT */
	private function show_cost_with_or_without_vat( $cost, $product, $location = 'shop' ) {
		if ( wc_tax_enabled() && $cost > 0 && $this->taxable ) {
			if ( 'incl' == get_option( 'woocommerce_tax_display_'.$location ) ) {
				if ( ! wc_prices_include_tax() ) {
					if ( $taxes = WC_Tax::calc_tax( $cost, WC_Tax::get_rates( $this->tax_class, WC()->cart->get_customer() ), wc_prices_include_tax() ) ) {
						foreach( $taxes as $tax ) {
							$cost += $tax;
						}
					}
				}
			} else {
				if ( wc_prices_include_tax() ) {
					if ( $taxes = WC_Tax::calc_tax( $cost, WC_Tax::get_rates( $this->tax_class, WC()->cart->get_customer() ), wc_prices_include_tax() ) ) {
						foreach( $taxes as $tax ) {
							$cost -= $tax;
						}
					}
				}
			}
		}
		return $cost;
	}

	/* Get cost without VAT */
	private function get_cost_without_vat( $cost ) {
		if ( wc_tax_enabled() && $this->taxable && wc_prices_include_tax() ) {
			$tax_rates = WC_Tax::get_base_tax_rates( $this->tax_class );
			$taxes     = WC_Tax::calc_tax( $cost, $tax_rates, true ); 
			$cost      = WC_Tax::round( $cost - array_sum( $taxes ) );
		}
		return $cost;
	}

	/* Frontend - When added to cart, save assembly data */
	public function add_cart_item_data( $cart_item_meta, $product_id, $variation_id ) {
		$show_assembly            = false;
		$product                  = wc_get_product( $variation_id ? $variation_id : $product_id );
		$product_has_assembly     = trim( $product->get_meta( '_has_assembly' ) ) == 'yes';
		$product_disable_assembly = trim( $product->get_meta( '_disable_assembly' ) ) == 'yes';
		if ( $this->enabled_default ) {
			if ( ! $product_disable_assembly ) {
				$show_assembly = true;
			}
		} else {
			if ( $product_has_assembly ) {
				$show_assembly = true;
			}
		}
		if ( $show_assembly && ! empty( $_POST['assembly'] ) ) {
			$cart_item_meta['assembly'] = true;
		}
		return $cart_item_meta;
	}

	/* Get cost for cart item */
	public function get_cost_for_cart_item( $cart_item, $display = false ) {
		$cost = '';
		if ( isset( $cart_item['data'] ) ) {
			$product_type = $cart_item['data']->get_type(); /* get_type() on NULL with AutomateWoo?!? */
			switch( $product_type ) {
				case 'variation':
					$product_id = $cart_item['data']->get_id();
					$product = wc_get_product( $product_id );
					$cost    = apply_filters( 'product_assembly_cost', floatval( $product->get_meta( '_assembly_cost' ) ), $product );
					if ( $cost == 0 ) {
						//Backwards compatibility - get from product
						$product_id = $cart_item['data']->get_parent_id();
						$product = wc_get_product( $product_id );
						$cost    = apply_filters( 'product_assembly_cost', floatval( $product->get_meta( '_assembly_cost' ) ), $product );
					}
					break;
				default:
					$product_id = $cart_item['data']->get_id();
					$product = wc_get_product( $product_id );
					$cost    = apply_filters( 'product_assembly_cost', floatval( $product->get_meta( '_assembly_cost' ) ), $product );
					break;
			}
		}
		if ( $cost == '' || $cost == 0 ) { //Default
			$cost = floatval( $this->default_cost );
		}
		if ( $display ) $cost = $this->show_cost_with_or_without_vat( $cost, $product, 'cart' );
		return $cost;
	}

	/* Frontend - Get the assembly data from the session on page load */
	public function get_cart_item_from_session( $cart_item, $values ) {
		if ( $this->cost_mode == 'product' ) {
			if ( ! empty( $values['assembly'] ) ) {
				$cart_item['assembly'] = true;
				$cost = $this->get_cost_for_cart_item( $cart_item );
				//With price on the product itself
				$cost = $this->cost_multiply ? $cost : $cost / $cart_item['quantity'];
				$cart_item['data']->set_price( $cart_item['data']->get_price() + $cost );
			}
		}
		return $cart_item;
	}

	/* Frontend - Display assembly data if present in the cart */
	public function get_item_data( $item_data, $cart_item ) {
		if ( ! empty( $cart_item['assembly'] ) && ! empty( $cart_item['data'] ) ) { // ! empty( $cart_item['data'] ) because of AutomateWoo
			$cost_display = $this->get_cost_for_cart_item( $cart_item, true );
			$data         = array(
				'name'    => $this->service_name,
				'value'   => __( 'Yes', 'product-assembly-cost' ).( $cost_display > 0 ? ' ('.sprintf( $this->cost_multiply ? __( '%s / unit', 'product-assembly-cost' ) : '%s', wc_price( $cost_display ) ).')' : '' ),
			);
			$data['display'] = $data['value'];
			$item_data[] = apply_filters( 'product_assembly_cart_data', $data, $this->service_name, $cost_display, $this->cost_multiply, $item_data, $cart_item ) ;
		}
		return $item_data;
	}

	/* Frontend - Adjust price after adding to cart */
	public function add_cart_item( $cart_item ) {
		if ( $this->cost_mode == 'product' ) {
			if ( ! empty( $cart_item['assembly'] ) ) {
				$cost = $this->get_cost_for_cart_item( $cart_item );
				//With price on the product itself
				$cost = $this->cost_multiply ? $cost : $cost / $cart_item['quantity'];
				$cart_item['data']->set_price( $cart_item['data']->get_price() + $cost );
			}
		}
		return $cart_item;
	}

	/* Frontend - After ordering, add the data to the order line items */
	public function add_order_item_meta( $item, $cart_item_key, $values ) {
		if ( ! empty( $values['assembly'] ) ) {
			$cost = $this->get_cost_for_cart_item( $values );
			$item->add_meta_data( $this->service_name, __( 'Yes', 'product-assembly-cost' ).( $cost > 0 ? ' ('.strip_tags( wc_price( $cost ) ).')' : '' ) );
		}
	}

	/* Frontend - Add assembly fee to the cart */
	public function assembly_fee() {
		if ( $this->cost_mode == 'subtotal' ) {
			if ( is_admin() && ! defined( 'DOING_AJAX' ) ) {
				return;
			}
			$amount = 0;
			$items  = 0;
			foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
				if ( ! empty( $cart_item['assembly'] ) ) {
					$cost = $this->get_cost_for_cart_item( $cart_item );
					$cost = $this->get_cost_without_vat( $cost );
					if ( $cart_item['quantity'] > 0 ) {
						$items += $this->cost_multiply ? $cart_item['quantity'] : 1;
						$amount += $this->cost_multiply ? $cost * $cart_item['quantity'] : $cost;
					}
				}
			}
			if ( $amount > 0 ) {
				$this->fee_name =
					$items > 1
					?
					sprintf(
						__( '%s (%d items)', 'product-assembly-cost' ),
						$this->service_name,
						$items
					)
					:
					$this->service_name
					;
				WC()->cart->add_fee( $this->fee_name , $amount, $this->taxable, $this->tax_class );
				add_action( 'woocommerce_cart_totals_fee_html', array( $this, 'woocommerce_cart_totals_fee_html' ), 10, 2 );
			}
		}
	}
	public function woocommerce_cart_totals_fee_html( $cart_totals_fee_html, $fee ) {
		if ( $fee->id == sanitize_title( $this->fee_name ) ) {
			if ( wc_tax_enabled() && WC()->cart->display_prices_including_tax() && $fee->taxable ) {
				$tax = WC_Tax::get_rates( $fee->tax_class );
				if ( is_array( $tax ) && count( $tax ) > 0 ) {
					foreach ( $tax as $tax1 ) {
						break; //Only one tax(?)
					}
					$cart_totals_fee_html .= ' <small class="includes_tax">'.sprintf( __( '(incl. %s)', 'product-assembly-cost' ), wc_price( $fee->tax ).' '.$tax1['label'] ).'</small>';
				}
			}
		}
		return $cart_totals_fee_html;
	}

	/* Frontend - Cart item name */
	public function cart_item_name( $cart ) {
		if ( ! $this->add_to_name )
			return;
		if ( is_admin() && ! defined( 'DOING_AJAX' ) )
			return;
		if ( did_action( 'woocommerce_before_calculate_totals' ) >= 2 )
			return;
		foreach ( $cart->get_cart() as $cart_item ) {
			if ( ! empty( $cart_item['assembly'] ) ) {
				$product       = $cart_item['data'];
				$original_name = method_exists( $product, 'get_name' ) ? $product->get_name() : $product->post->post_title;
				$new_name      = trim( trim( $original_name ).' + '.trim( $this->service_name ) );
				if ( method_exists( $product, 'set_name' ) ) {
					$product->set_name( $new_name );
				} else {
					$product->post->post_title = $new_name;
				}
			}
		}
	}

}

/* If you're reading this you must know what you're doing ;-) Greetings from sunny Portugal! */

