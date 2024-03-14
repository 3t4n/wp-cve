<?php

/**
 * Plugin Name: Default Attributes for WooCommerce
 * Plugin URI: https://en.condless.com/default-attributes-for-woocommerce/
 * Description: WooCommerce plugin that sets default attribute for variable products automatically if only 1 option is in-stock.
 * Version: 1.1.7
 * Author: Condless
 * Author URI: https://en.condless.com/
 * Developer: Condless
 * Developer URI: https://en.condless.com/
 * Contributors: condless
 * Text Domain: default-attributes-for-woocommerce
 * Domain Path: /i18n/languages
 * License: GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 * Requires at least: 5.2
 * Tested up to: 6.5
 * Requires PHP: 7.0
 * WC requires at least: 3.4
 * WC tested up to: 8.7
 */

/**
 * Exit if accessed directly
 */
defined( 'ABSPATH' ) || exit;

/**
 * Check if WooCommerce is active
 */
if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) || get_site_option( 'active_sitewide_plugins') && array_key_exists( 'woocommerce/woocommerce.php', get_site_option( 'active_sitewide_plugins' ) ) ) {

	/**
	 * Default Attributes for WooCommerce Class.
	 */
	class WC_DAW {

		/**
		 * Construct class
		 */
		public function __construct() {
			add_action( 'before_woocommerce_init', function() {
				if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
					\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
				}
			} );
			add_action( 'plugins_loaded', [ $this, 'init' ] );
		}

		/**
		 * WC init
		 */
		public function init() {
			$this->init_textdomain();
			$this->init_settings();
			$this->init_functions();
		}

		/**
		 * Loads text domain for internationalization
		 */
		public function init_textdomain() {
			load_plugin_textdomain( 'default-attributes-for-woocommerce', false, dirname( plugin_basename( __FILE__ ) ) . '/i18n/languages' );
		}

		/**
		 * WC settings init
		 */
		public function init_settings() {
			add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), [ $this, 'wc_update_settings_link' ] );
			add_filter( 'plugin_row_meta', [ $this, 'wc_add_plugin_links' ], 10, 4 );
			add_filter( 'woocommerce_settings_tabs_array', [ $this, 'wc_add_settings_tab' ], 50 );
			add_action( 'woocommerce_settings_tabs_daw', [ $this, 'wc_settings_tab' ] );
			add_action( 'woocommerce_update_options_daw', [ $this, 'wc_update_settings' ] );
			add_filter( 'woocommerce_admin_settings_sanitize_option_wc_daw_max_variations', [ $this, 'wc_sanitize_option_wc_daw_max_variations' ], 10, 2 );
			add_filter( 'woocommerce_admin_settings_sanitize_option_wc_daw_first_attribute', [ $this, 'wc_sanitize_option_wc_daw_first_attribute' ], 10, 2 );
			add_action( 'woocommerce_after_edit_attribute_fields', [ $this, 'wc_edit_attribute_default' ] );
			add_action( 'woocommerce_attribute_updated', [ $this, 'wc_save_attribute_default' ] );
			add_action( 'woocommerce_attribute_deleted', [ $this, 'wc_delete_attribute_default' ] );
		}

		/**
		 * WC functions init
		 */
		public function init_functions() {
			add_filter( 'woocommerce_product_get_default_attributes', [ $this, 'wc_product_get_default_attributes' ], 99, 2 );
			if ( 'yes' === get_option( 'wc_daw_disable_options' ) ) {
				add_filter( 'woocommerce_variation_is_active', [ $this, 'wc_grey_out_variations_when_out_of_stock' ], 10, 2 );
				add_filter( 'woocommerce_ajax_variation_threshold', [ $this, 'wc_ajax_variation_threshold' ], 10, 2 );
			}
			if ( 'yes' === get_option( 'wc_daw_select_attribute' ) ) {
				add_filter( 'woocommerce_product_add_to_cart_text', [ $this, 'wc_add_to_cart_text' ], 9999 );
			}
			if ( 'yes' === get_option( 'wc_daw_single_select' ) ) {
				add_filter( 'woocommerce_after_variations_form', [ $this, 'wc_set_button_text' ] );
			}
			if ( 'yes' === get_option( 'wc_daw_remove_text' ) ) {
				add_filter( 'woocommerce_dropdown_variation_attribute_options_args', [ $this, 'wc_remove_options_text' ] );
			}
		}

		/**
		 * Add plugin links to the plugin menu
		 * @param mixed $links
		 * @return mixed
		 */
		public function wc_update_settings_link( $links ) {
			array_unshift( $links, '<a href=' . esc_url( add_query_arg( 'page', 'wc-settings&tab=daw', get_admin_url() . 'admin.php' ) ) . '>' . __( 'Settings' ) . '</a>' );
			return $links;
		}

		/**
		 * Add plugin meta links to the plugin menu
		 * @param mixed $links_array
		 * @param mixed $plugin_file_name
		 * @param mixed $plugin_data
		 * @param mixed $status
		 * @return mixed
		 */
		public function wc_add_plugin_links( $links_array, $plugin_file_name, $plugin_data, $status ) {
			if ( strpos( $plugin_file_name, basename( __FILE__ ) ) ) {
				$sub_domain = 'he_IL' === get_locale() ? 'www' : 'en';
				$links_array[] = "<a href=https://$sub_domain.condless.com/default-attributes-for-woocommerce/>" . __( 'Docs', 'woocommerce' ) . '</a>';
				$links_array[] = "<a href=https://$sub_domain.condless.com/contact/>" . _x( 'Contact', 'Theme starter content' ) . '</a>';
			}
			return $links_array;
		}

		/**
		 * Add a new settings tab to the WooCommerce settings tabs array
		 * @param array $settings_tabs
		 * @return array
		 */
		public function wc_add_settings_tab( $settings_tabs ) {
			$settings_tabs['daw'] = __( 'Default Attributes', 'default-attributes-for-woocommerce' );
			return $settings_tabs;
		}

		/**
		 * Use the WooCommerce admin fields API to output settings via the @see woocommerce_admin_fields() function
		 * @uses woocommerce_admin_fields()
		 * @uses self::wc_get_settings()
		 */
		public function wc_settings_tab() {
			woocommerce_admin_fields( self::wc_get_settings() );
		}

		/**
		 * Use the WooCommerce options API to save settings via the @see woocommerce_update_options() function
		 * @uses woocommerce_update_options()
		 * @uses self::wc_get_settings()
		 */
		public function wc_update_settings() {
			woocommerce_update_options( self::wc_get_settings() );
		}

		/**
		 * Get all the settings for this plugin for @see woocommerce_admin_fields() function
		 * @return array Array of settings for @see woocommerce_admin_fields() function
		 */
		public function wc_get_settings() {
			$settings = [
				'section_title'	=> [
					'name'	=> __( 'Settings' ),
					'type'	=> 'title',
					'id'	=> 'wc_daw_section_title'
				],
				'max_variations'	=> [
					'name'		=> __( 'Max variation for by-stock calculation', 'woocommerce' ),
					'desc_tip'	=> __( 'Set the number of product variations that above it the default attributes will not be calculated by stock. Set 0 to disable this feature completely.', 'default-attributes-for-woocommerce' ),
					'type'		=> 'number',
					'default'	=> '50',
					'id'		=> 'wc_daw_max_variations'
				],
				'first_attribute'	=> [
					'name'		=> __( 'Top' ),
					'desc'		=> __( 'Set as default the first option of each attribute', 'default-attributes-for-woocommerce' ),
					'desc_tip'	=> __( 'If not all of the calculated default attributes are selectable they are all will be unset.', 'default-attributes-for-woocommerce' ),
					'type'		=> 'checkbox',
					'default'	=> 'no',
					'id'		=> 'wc_daw_first_attribute'
				],
				'disable_options'	=> [
					'name'		=> __( 'Out of stock', 'woocommerce' ),
					'desc'		=> __( 'Disable out of stock variations', 'default-attributes-for-woocommerce' ),
					'type'		=> 'checkbox',
					'default'	=> 'no',
					'id'		=> 'wc_daw_disable_options'
				],
				'select_attribute'	=> [
					'name'		=> __( 'Add to Cart button', 'woocommerce' ),
					'desc'		=> __( 'In archive pages display for variable products the attribute name instead of', 'default-attributes-for-woocommerce' ) . ': "' . __( 'Select options', 'woocommerce' ) . '"',
					'type'		=> 'checkbox',
					'default'	=> 'no',
					'id'		=> 'wc_daw_select_attribute'
				],
				'single_select'	=> [
					'name'		=> __( 'Single Product', 'woocommerce' ),
					'desc'		=> __( 'Replace the add to cart button text to Select and the attribute name in single product pages of variable product untill the variation is selected', 'default-attributes-for-woocommerce' ),
					'type'		=> 'checkbox',
					'default'	=> 'no',
					'id'		=> 'wc_daw_single_select'
				],
				'remove_text'	=> [
					'name'		=> __( 'Select options', 'woocommerce' ),
					'desc'		=> __( 'Remove the select options text from the attributes dropdown if attribute is set', 'default-attributes-for-woocommerce' ),
					'type'		=> 'checkbox',
					'default'	=> 'no',
					'id'		=> 'wc_daw_remove_text'
				],
				'section_end'	=> [
					'type'	=> 'sectionend',
					'id'	=> 'wc_daw_section_end'
				],
			];
			return apply_filters( 'wc_daw_settings', $settings );
		}

		/**
		 * Sanitize the max variations option
		 * @param mixed $value
		 * @param mixed $option
		 * @return mixed
		 */
		public function wc_sanitize_option_wc_daw_max_variations( $value, $option ) {
			if ( $value !== get_option( $option['id'] ) && apply_filters( 'daw_transients_enabled', true ) && ! has_filter( 'daw_transient_expiration' ) ) {
				WC_Admin_Settings::add_message( __( 'Please allow 1 minute for the settings to take effect', 'default-attributes-for-woocommerce' ) );
			}
			return $value;
		}

		/**
		 * Sanitize the top attribute option
		 * @param mixed $value
		 * @param mixed $option
		 * @return mixed
		 */
		public function wc_sanitize_option_wc_daw_first_attribute( $value, $option ) {
			if ( $value !== get_option( $option['id'] ) && apply_filters( 'daw_transients_enabled', true ) && ! has_filter( 'daw_transient_expiration' ) ) {
				WC_Admin_Settings::add_message( __( 'Please allow 1 minute for the settings to take effect', 'default-attributes-for-woocommerce' ) );
			}
			return $value;
		}

		/**
		 * Add option to define a default per attribute
		 */
		public function wc_edit_attribute_default() {
			if ( isset( $_GET['edit'] ) ) {
				$id = absint( $_GET['edit'] );
				$value = $id ? get_option( "wc_daw_attribute_default-$id" ) : '';
				?>
				<tr class="form-field form-required">
					<th scope="row" valign="top">
						<label for="attribute_default"><?php esc_html_e( 'Default Form Values', 'woocommerce' ); ?></label>
					</th>
					<td>
						<select name="attribute_default" id="attribute_default">
							<option value="none" <?php selected( $value, 'none' ); ?>><?php esc_html_e( 'None' ); ?></option>
							<option value="first" <?php selected( $value, 'first' ); ?>><?php esc_html_e( 'Top' ); ?></option>
							<?php foreach ( get_terms( wc_get_attribute( $id )->slug ) as $term ) : ?>
								<option value="<?php echo $term->name; ?>" <?php selected( $value, $term->name ); ?>><?php echo esc_html( $term->name ); ?></option>
							<?php endforeach; ?>
						</select>
						<p class="description"><?php esc_html_e( 'It defines this attribute default value in variable products', 'default-attributes-for-woocommerce' ); echo '. '; esc_html_e( 'If not all of the product calculated default attributes are selectable it will be unset', 'default-attributes-for-woocommerce' ); echo '.'; ?></p>
					</td>
				</tr>
			<?php
			}
		}

		/**
		 * Save the attribute default field
		 * @param mixed $id
		 */
		public function wc_save_attribute_default( $id ) {
			if ( is_admin() && isset( $_POST['attribute_default'] ) ) {
				update_option( "wc_daw_attribute_default-$id", wc_clean( wp_unslash( $_POST['attribute_default'] ) ) );
			}
		}

		/**
		 * Erase the attribute default field
		 * @param mixed $id
		 */
		public function wc_delete_attribute_default( $id ) {
			delete_option( "wc_daw_attribute_default-$id" );
		}

		/**
		 * Set the default attributes based on the settings
		 * @param mixed $default_attributes
		 * @param mixed $product
		 * @return mixed
		 */
		public function wc_product_get_default_attributes( $default_attributes, $product ) {
			if ( ! is_admin() && apply_filters( 'daw_defaults_enabled', is_single( $product->get_id() ) && is_product() ) ) {
				$transient = get_transient( 'daw_default_attributes_' . $product->get_id() );
				if ( false === $transient || ! apply_filters( 'daw_transients_enabled', true ) ) {
					$variations = $product->get_available_variations();
					$stock_default_attributes = [];
					$force_first = apply_filters( 'daw_force_first_in_stock_default', false, $product ) && 1 === count( $product->get_variation_attributes() );
					foreach ( $product->get_attributes() as $attribute_slug => $attribute ) {
						if ( $attribute['variation'] ) {
							$attr_options = [];
							if ( taxonomy_exists( $attribute_slug ) ) {
								foreach ( $attribute['options'] as $option ) {
									$attr_options[] = get_term( $option, $attribute_slug )->slug;
								}
							} else {
								$attr_options = $attribute['options'];
							}
							if ( count( $variations ) <= apply_filters( 'daw_max_variations', get_option( 'wc_daw_max_variations', '50' ), $attribute ) ) {
								foreach ( $attr_options as $attr_option ) {
									foreach ( $variations as $variation ) {
										if ( ( $variation['is_in_stock'] || $variation['backorders_allowed'] ) && ( empty( $variation['attributes']["attribute_$attribute_slug"] ) || $attr_option === $variation['attributes']["attribute_$attribute_slug"] ) ) {
											if ( ! isset( $stock_default_attributes[ $attribute_slug ] ) ) {
												$stock_default_attributes[ $attribute_slug ] = $attr_option;
												if ( $force_first ) {
													break 2;
												}
												break;
											} else {
												unset( $stock_default_attributes[ $attribute_slug ] );
												break 2;
											}
										}
									}
								}
							}
							if ( isset( $stock_default_attributes[ $attribute_slug ] ) ) {
								$default_attributes[ $attribute_slug ] = $stock_default_attributes[ $attribute_slug ];
							} elseif ( ! isset( $default_attributes[ $attribute_slug ] ) ) {
								$attribute_default = apply_filters( 'daw_global_attribute_default', get_option( 'wc_daw_attribute_default-' . $attribute['id'] ), $attribute );
								if ( $attribute_default && ! in_array( $attribute_default, [ 'none', 'first' ] ) ) {
									$default_attributes[ $attribute_slug ] = $attribute_default;
								} elseif ( $attribute_default && 'first' === $attribute_default || 'yes' === get_option( 'wc_daw_first_attribute' ) ) {
									$default_attributes[ $attribute_slug ] = $attr_options[0];
								}
							}
						}
					}
					$default_attributes = apply_filters( 'daw_product_get_default_attributes', $default_attributes, $product );
					set_transient( 'daw_default_attributes_' . $product->get_id(), $default_attributes, apply_filters( 'daw_transient_expiration', MINUTE_IN_SECONDS, $product ) );
				} else {
					return $transient;
				}
			}
			return $default_attributes;
		}

		/**
		 * Disable out of stock variations
		 * @param mixed $grey_out
		 * @param mixed $variation
		 * @return bool
		 */
		public function wc_grey_out_variations_when_out_of_stock( $grey_out, $variation ) {
			return $variation->is_in_stock();
		}

		/**
		 * Increase the ajax variation threshold so the disable options will work on more variations
		 * @param mixed $grey_out
		 * @param mixed $variation
		 * @return bool
		 */
		public function wc_ajax_variation_threshold( $qty, $product ) {
			return 50;
		}

		/**
		 * Create custom select attributes button text on archive page
		 * @param mixed $args
		 * @return mixed
		 */
		public function wc_add_to_cart_text( $text ) {
			global $product;
			if ( is_a( $product, 'WC_Product' ) && $product->is_type( 'variable' ) && $product->is_purchasable() ) {
				$text = __( 'Select', 'woocommerce' ) . ' ' . wc_attribute_label( array_key_first( $product->get_variation_attributes() ) );
			}
			return $text;
		}

		/**
		 * Set dynamically the add to cart button text for single pages of variable products
		 * @param mixed $args
		 * @return mixed
		 */
		public function wc_set_button_text() {
			global $product;
			if ( is_single( $product->get_id() ) && is_product() && apply_filters( 'daw_custom_button_text', true, $product ) && 1 === count( $product->get_variation_attributes() ) ) {
				?>
				<script type="text/javascript">
				jQuery( function( $ ) {
					$( '.variations_form' ).on( 'show_variation', function( event, data ) {
						$( '.single_add_to_cart_button' ).text( '<?php _e( 'Add to cart', 'woocommerce' ) ?>' );
					} );
					$( '.variations_form' ).on( 'update_variation_values', function( event ) {
						$( '.single_add_to_cart_button' ).text( '<?php echo __( 'Select', 'woocommerce' ) . ' ' . wc_attribute_label( array_key_first( $product->get_variation_attributes() ) ) ?>' );
					} );
				} );
				</script>
			<?php
			}
		}

		/**
		 * Remove the select options text when there is default
		 * @param mixed $args
		 * @return mixed
		 */
		public function wc_remove_options_text( $args ) {
			$args['show_option_none'] = false;
			return $args;
		}
	}

	/**
	 * Instantiate class
	 */
	$default_attributes_for_woocommerce = new WC_DAW();
};
