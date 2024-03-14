<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @category Smartbill_Woocommerce_Public
 * @package    smartbill-facturare-si-gestiune
 * @subpackage Smartbill_Woocommerce/public
 * @author    Intelligent IT SRL <vreauapi@smartbill.ro>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 */

/**
 * The public-facing functionality of the plugin.
 *
 * @category Smartbill_Woocommerce_Public
 * @package    smartbill-facturare-si-gestiune
 * @subpackage Smartbill_Woocommerce/public
 * @author    Intelligent IT SRL <vreauapi@smartbill.ro>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 */
class Smartbill_Woocommerce_Public {

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
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string $plugin_name       The name of the plugin.
	 * @param      string $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Smartbill_Woocommerce_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Smartbill_Woocommerce_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		$options = get_option( 'smartbill_plugin_options_settings' );
		$billing = array();
		if ( isset( $options['custom_checkout'] ) && $options['custom_checkout'] && is_checkout() ) {
			$billing['billing'] = $options['custom_checkout'];
		} else {
			$billing['billing'] = false;
		}

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/smartbill-woocommerce-public.js', array( 'jquery' ), $this->version, false );
		wp_localize_script( $this->plugin_name, 'smartbill_billing', $billing );
	}


	/**
	 * Add billing/shipping custom fields to frontend checkout.
	 *
	 * @param WC_Checkout $checkout Woocommerce checkout.
	 *
	 * @return void
	 */
	public function smartbill_billing_fields( $checkout ) {
		$admin_settings = new Smartbill_Woocommerce_Admin_Settings_Fields();
		if ( $admin_settings->get_custom_checkout() ) {
			$custom_checkout_options = $admin_settings->get_custom_checkout_options();

			woocommerce_form_field(
				'smartbill_billing_type',
				array(
					'type'     => 'select',
					'class'    => array( 'form-row-wide' ),
					'label'    => $custom_checkout_options['client_type_trans'],
					'required' => true,
					'options'  => array(
						'pf' => $custom_checkout_options['client_individual_trans'],
						'pj' => $custom_checkout_options['client_entity_trans'],
					),
					'default'  => 'pf',
				),
				$checkout->get_value( 'smartbill_billing_type' )
			);
			?>
			<div id='smartbill-show-pj'>
			<?php
			if ( $custom_checkout_options['cif_vis'] ) {
				woocommerce_form_field(
					'smartbill_billing_cif',
					array(
						'type'     => 'text',
						'class'    => array( 'form-row-wide' ),
						'required' => $custom_checkout_options['cif_req'],
						'label'    => $custom_checkout_options['cif_trans'],
					),
					$checkout->get_value( 'smartbill_billing_cif' )
				);
			}
			if ( $custom_checkout_options['company_name_vis'] ) {
				woocommerce_form_field(
					'smartbill_billing_company_name',
					array(
						'type'     => 'text',
						'class'    => array( 'form-row-wide' ),
						'required' => $custom_checkout_options['company_name_req'],
						'label'    => $custom_checkout_options['company_name_trans'],
					),
					$checkout->get_value( 'smartbill_billing_company_name' )
				);
			}
			if ( $custom_checkout_options['reg_com_vis'] ) {
				woocommerce_form_field(
					'smartbill_billing_nr_reg_com',
					array(
						'type'     => 'text',
						'class'    => array( 'form-row-wide' ),
						'required' => $custom_checkout_options['reg_com_req'],
						'label'    => $custom_checkout_options['reg_com_trans'],
					),
					$checkout->get_value( 'smartbill_billing_nr_reg_com' )
				);
			}
			?>
			</div>
			<?php
		}
	}


	/**
	 * Save billing/shipping custom fields values on user meta.
	 *
	 * @param int $user_id user id.
	 *
	 * @return void
	 */
	public function smartbill_billing_fields_update_user_meta( $user_id ) {
		if ( $user_id && isset( $_POST['billing_smartbill_billing_type'] ) ) {
			update_user_meta( $user_id, 'billing_smartbill_billing_type', sanitize_text_field( wp_unslash( $_POST['billing_smartbill_billing_type'] ) ) );
			if ( 'pj' == $_POST['billing_smartbill_billing_type'] ) {
				if ( $user_id && isset( $_POST['billing_smartbill_billing_company_name'] ) ) {
					update_user_meta( $user_id, 'billing_smartbill_billing_company_name', sanitize_text_field( wp_unslash( $_POST['billing_smartbill_billing_company_name'] ) ) );
				}
				if ( $user_id && isset( $_POST['billing_smartbill_billing_cif'] ) ) {
					update_user_meta( $user_id, 'billing_smartbill_billing_cif', sanitize_text_field( wp_unslash( $_POST['billing_smartbill_billing_cif'] ) ) );
				}
				if ( $user_id && isset( $_POST['billing_smartbill_billing_nr_reg_com'] ) ) {
					update_user_meta( $user_id, 'billing_smartbill_billing_nr_reg_com', sanitize_text_field( wp_unslash( $_POST['billing_smartbill_billing_nr_reg_com'] ) ) );
				}
			}
		}
	}

	/**
	 * Save billing/shipping custom fields values on postmeta durring checkout.
	 *
	 * @return void
	 */
	public function smartbill_billing_fields_update_order_meta($order_id) {
		$order = new WC_Order($order_id);

		if ( isset( $_POST['smartbill_billing_type'] ) ) {
			update_post_meta( $order_id, 'smartbill_billing_type', sanitize_text_field( wp_unslash( $_POST['smartbill_billing_type'] ) ) );
			$order->update_meta_data( 'smartbill_billing_type',trim( sanitize_text_field( wp_unslash( $_POST['smartbill_billing_type'] ) ) ) );
			
			if ( ! isset( $_POST['ship_to_different_address'] ) ) {
				update_post_meta( $order_id, 'smartbill_shipping_type', sanitize_text_field( wp_unslash( $_POST['smartbill_billing_type'] ) ) );
				$order->update_meta_data( 'smartbill_shipping_type',trim( sanitize_text_field( wp_unslash( $_POST['smartbill_shipping_type'] ) ) ) );
			} else {
				if ( isset( $_POST['smartbill_shipping_type'] ) ) {
					update_post_meta( $order_id, 'smartbill_shipping_type', sanitize_text_field( wp_unslash( $_POST['smartbill_shipping_type'] ) ) );
					$order->update_meta_data( 'smartbill_shipping_type',trim( sanitize_text_field( wp_unslash( $_POST['smartbill_shipping_type'] ) ) ) );
				}
			}

			if ( isset( $_POST['smartbill_billing_type'] ) && 'pj' == $_POST['smartbill_billing_type'] ) {
				if ( isset( $_POST['smartbill_billing_company_name'] ) ) {
					update_post_meta( $order_id, 'smartbill_billing_company_name', sanitize_text_field( wp_unslash( $_POST['smartbill_billing_company_name'] ) ) );
					$order->update_meta_data( 'smartbill_billing_company_name',trim( sanitize_text_field( wp_unslash( $_POST['smartbill_billing_company_name'] ) ) ) );
					$company_name = get_post_meta( $order_id, 'smartbill_billing_company_name', true );
					
					$order->set_billing_company($company_name);
					if ( ! isset( $_POST['ship_to_different_address'] ) ) {
						$order->set_shipping_company($company_name);
					}
				}

				if ( isset( $_POST['smartbill_billing_cif'] ) ) {
					update_post_meta( $order_id, 'smartbill_billing_cif', sanitize_text_field( wp_unslash( $_POST['smartbill_billing_cif'] ) ) );
					$order->update_meta_data( 'smartbill_billing_cif',trim( sanitize_text_field( wp_unslash( $_POST['smartbill_billing_cif'] ) ) ) );
					if ( ! isset( $_POST['ship_to_different_address'] ) ) {
						update_post_meta( $order_id, 'smartbill_shipping_cif', sanitize_text_field( wp_unslash( $_POST['smartbill_billing_cif'] ) ) );
						$order->update_meta_data( 'smartbill_shipping_cif',trim( sanitize_text_field( wp_unslash( $_POST['smartbill_shipping_cif'] ) ) ) );
					}
				}

				if ( isset( $_POST['smartbill_billing_nr_reg_com'] ) ) {
					update_post_meta( $order_id, 'smartbill_billing_nr_reg_com', sanitize_text_field( wp_unslash( $_POST['smartbill_billing_nr_reg_com'] ) ) );
					$order->update_meta_data( 'smartbill_billing_nr_reg_com',trim( sanitize_text_field( wp_unslash( $_POST['smartbill_billing_nr_reg_com'] ) ) ) );
					if ( ! isset( $_POST['ship_to_different_address'] ) ) {
						update_post_meta( $order_id, 'smartbill_shipping_nr_reg_com', sanitize_text_field( wp_unslash( $_POST['smartbill_billing_nr_reg_com'] ) ) );
						$order->update_meta_data( 'smartbill_shipping_nr_reg_com',trim( sanitize_text_field( wp_unslash( $_POST['smartbill_shipping_nr_reg_com'] ) ) ) );
					}
				}
			}

			if ( isset( $_POST['smartbill_shipping_type'] ) && 'pj' == $_POST['smartbill_shipping_type'] ) {
				if ( isset( $_POST['smartbill_shipping_nr_reg_com'] ) && isset( $_POST['ship_to_different_address'] ) ) {
					update_post_meta( $order_id, 'smartbill_shipping_nr_reg_com', sanitize_text_field( wp_unslash( $_POST['smartbill_shipping_nr_reg_com'] ) ) );
					$order->update_meta_data( 'smartbill_shipping_nr_reg_com',trim( sanitize_text_field( wp_unslash( $_POST['smartbill_shipping_nr_reg_com'] ) ) ) );
					
				}

				if ( isset( $_POST['smartbill_shipping_cif'] ) && isset( $_POST['ship_to_different_address'] ) ) {
					update_post_meta( $order_id, 'smartbill_shipping_cif', sanitize_text_field( wp_unslash( $_POST['smartbill_shipping_cif'] ) ) );
					$order->update_meta_data( 'smartbill_shipping_cif',trim( sanitize_text_field( wp_unslash( $_POST['smartbill_shipping_cif'] ) ) ) );
				}

				if ( isset( $_POST['smartbill_shipping_company_name'] ) && isset( $_POST['ship_to_different_address'] ) ) {
					update_post_meta( $order_id, 'smartbill_shipping_company_name', sanitize_text_field( wp_unslash( $_POST['smartbill_shipping_company_name'] ) ) );
					$order->update_meta_data( 'smartbill_shipping_company_name',trim( sanitize_text_field( wp_unslash( $_POST['smartbill_shipping_company_name'] ) ) ) );
					$company_name = get_post_meta( $order_id, 'smartbill_shipping_company_name', true );
					$order->set_shipping_company($company_name);
				}
			}
		}
		$order->save();
	}

	/**
	 * Add billing/shipping user defined errors.
	 *
	 * @param Array $fields woocommerce checkout fields.
	 * @param Array $errors woocommerce checkout notices.
	 *
	 * @return Array $fields
	 */
	public function smartbill_checkout_validation( $fields, $errors ) {
		$admin_settings = new Smartbill_Woocommerce_Admin_Settings_Fields();
		$errors->remove( 'smartbill_billing_cif_required' );
		$errors->remove( 'smartbill_billing_company_name_required' );
		$errors->remove( 'smartbill_billing_nr_reg_com_required' );
		$errors->remove( 'smartbill_shipping_cif_required' );
		$errors->remove( 'smartbill_shipping_company_name_required' );
		$errors->remove( 'smartbill_shipping_nr_reg_com_required' );
		if ( $admin_settings->get_custom_checkout() ) {
			$custom_checkout_options = $admin_settings->get_custom_checkout_options();
			if ( isset( $fields['smartbill_billing_type'] ) && 'pj' == $fields['smartbill_billing_type'] ) {
				if ( isset( $_POST['smartbill_billing_cif'] ) && empty( $_POST['smartbill_billing_cif'] ) && true == $custom_checkout_options['cif_req'] ) {
					$errors->add( 'smartbill_billing_cif_required', $custom_checkout_options['cif_error_trans'] );
				}
				if ( isset( $_POST['smartbill_billing_company_name'] ) && empty( $_POST['smartbill_billing_company_name'] ) && true == $custom_checkout_options['company_name_req'] ) {
					$errors->add( 'smartbill_billing_company_name_required', $custom_checkout_options['company_name_error_trans'] );
				}
				if ( isset( $_POST['smartbill_billing_nr_reg_com'] ) && empty( $_POST['smartbill_billing_nr_reg_com'] ) && true == $custom_checkout_options['reg_com_req'] ) {
					$errors->add( 'smartbill_billing_cif_required', $custom_checkout_options['reg_com_error_trans'] );
				}
			}
			if ( isset( $fields['smartbill_shipping_type'] ) && 'pj' == $fields['smartbill_shipping_type'] ) {
				if ( isset( $_POST['smartbill_shipping_cif'] ) && empty( $_POST['smartbill_shipping_cif'] ) && true == $custom_checkout_options['cif_req'] ) {
					$errors->add( 'smartbill_shipping_cif_required', $custom_checkout_options['cif_error_trans'] );
				}
				if ( isset( $_POST['smartbill_shipping_company_name'] ) && empty( $_POST['smartbill_shipping_company_name'] ) && true == $custom_checkout_options['company_name_req'] ) {
					$errors->add( 'smartbill_shipping_company_name_required', $custom_checkout_options['company_name_error_trans'] );
				}
				if ( isset( $_POST['smartbill_shipping_nr_reg_com'] ) && empty( $_POST['smartbill_shipping_nr_reg_com'] ) && true == $custom_checkout_options['reg_com_req'] ) {
					$errors->add( 'smartbill_shipping_cif_required', $custom_checkout_options['reg_com_error_trans'] );
				}
			}
		}
		return $fields;
	}

	/**
	 * Remove billing/shipping errors when fields are hidden.
	 *
	 * @return void
	 */
	public function smartbill_address_validation() {
		$admin_settings = new Smartbill_Woocommerce_Admin_Settings_Fields();
		$notices        = WC()->session->get( 'wc_notices' );
		if ( $notices && isset( $notices['error'] ) && $admin_settings->get_custom_checkout() ) {

			$billing_type  = isset( $_POST['smartbill_billing_type'] ) ? sanitize_text_field( wp_unslash( $_POST['smartbill_billing_type'] ) ) : '';
			$shipping_type = isset( $_POST['smartbill_shipping_type'] ) ? sanitize_text_field( wp_unslash( $_POST['smartbill_shipping_type'] ) ) : '';
			if ( 'pf' == $billing_type || 'pf' == $shipping_type ) {

				foreach ( $notices['error'] as $index => $error ) {
					if ( 'smartbill_billing_cif' == $error['data']['id'] || 'smartbill_billing_company_name' == $error['data']['id'] || 'smartbill_billing_nr_reg_com' == $error['data']['id'] ) {
						unset( $notices['error'][ $index ] );
					}

					if ( 'smartbill_shipping_cif' == $error['data']['id'] || 'smartbill_shipping_company_name' == $error['data']['id'] || 'smartbill_shipping_nr_reg_com' == $error['data']['id'] ) {
						unset( $notices['error'][ $index ] );
					}
				}
				WC()->session->set( 'wc_notices', $notices );

			}
		}

	}

	/**
	 * Add billing fields in checkout page
	 *
	 * @param Array $fields woocommerce checkout fields.
	 *
	 * @return Array $fields
	 */
	public function smartbill_custom_billing_fields( $fields ) {
		$admin_settings = new Smartbill_Woocommerce_Admin_Settings_Fields();
		if ( $admin_settings->get_custom_checkout() ) {
			$custom_checkout_options = $admin_settings->get_custom_checkout_options();

			$fields['smartbill_billing_type'] = array(
				'label'    => $custom_checkout_options['client_type_trans'],
				'required' => true,
				'class'    => array( 'form-row-wide', 'select' ),
				'priority' => 1,
				'type'     => 'select',
				'options'  => array(
					'pf' => $custom_checkout_options['client_individual_trans'],
					'pj' => $custom_checkout_options['client_entity_trans'],
				),
			);

			if ( $custom_checkout_options['cif_vis'] ) {
				$fields['smartbill_billing_cif'] = array(
					'label'    => $custom_checkout_options['cif_trans'],
					'required' => $custom_checkout_options['cif_req'],
					'class'    => array( 'form-row-wide', 'my-custom-class' ),
					'priority' => 2,
				);
			}

			if ( $custom_checkout_options['company_name_vis'] ) {
				$fields['smartbill_billing_company_name'] = array(
					'label'    => $custom_checkout_options['company_name_trans'],
					'required' => $custom_checkout_options['company_name_req'],
					'class'    => array( 'form-row-wide', 'my-custom-class' ),
					'priority' => 3,
				);
			}

			if ( $custom_checkout_options['reg_com_vis'] ) {
				$fields['smartbill_billing_nr_reg_com'] = array(
					'label'    => $custom_checkout_options['reg_com_trans'],
					'required' => $custom_checkout_options['reg_com_req'],
					'class'    => array( 'form-row-wide', 'my-custom-class' ),
					'priority' => 4,
				);
			}
		};

		return $fields;

	}

	/**
	 * Add shipping fields in checkout page
	 *
	 * @param Array $fields woocommerce checkout fields.
	 *
	 * @return Array $fields
	 */
	public function smartbill_custom_shipping_fields( $fields ) {
		$admin_settings = new Smartbill_Woocommerce_Admin_Settings_Fields();
		if ( $admin_settings->get_custom_checkout() ) {
			$custom_checkout_options = $admin_settings->get_custom_checkout_options();

			$fields['smartbill_shipping_type'] = array(
				'label'    => $custom_checkout_options['client_type_trans'],
				'required' => true,
				'class'    => array( 'form-row-wide', 'select' ),
				'priority' => 1,
				'type'     => 'select',
				'options'  => array(
					'pf' => $custom_checkout_options['client_individual_trans'],
					'pj' => $custom_checkout_options['client_entity_trans'],
				),
			);

			if ( $custom_checkout_options['cif_vis'] ) {
				$fields['smartbill_shipping_cif'] = array(
					'label'    => $custom_checkout_options['cif_trans'],
					'required' => $custom_checkout_options['cif_req'],
					'class'    => array( 'form-row-wide', 'my-custom-class' ),
					'priority' => 2,
				);
			}

			if ( $custom_checkout_options['company_name_vis'] ) {
				$fields['smartbill_shipping_company_name'] = array(
					'label'    => $custom_checkout_options['company_name_trans'],
					'required' => $custom_checkout_options['company_name_req'],
					'class'    => array( 'form-row-wide', 'my-custom-class' ),
					'priority' => 3,
				);
			}

			if ( $custom_checkout_options['reg_com_vis'] ) {
				$fields['smartbill_shipping_nr_reg_com'] = array(
					'label'    => $custom_checkout_options['reg_com_trans'],
					'required' => $custom_checkout_options['reg_com_req'],
					'class'    => array( 'form-row-wide', 'my-custom-class' ),
					'priority' => 4,
				);
			}
		};

		return $fields;

	}

	/**
	 * Add fields in billing address
	 *
	 * @param Array $address billing address fields.
	 * @param Order $order Woocommerce order.
	 *
	 * @return Array $address
	 */
	public function smartbill_formatted_billing_address( $address, $order ) {
		$admin_settings = new Smartbill_Woocommerce_Admin_Settings_Fields();
		if ( $admin_settings->get_custom_checkout() ) {
			$custom_checkout_options = $admin_settings->get_custom_checkout_options();
			$order_id                = $order->get_id();

			$reg_com = get_post_meta( $order_id, 'smartbill_billing_nr_reg_com', true );
			$cif     = get_post_meta( $order_id, 'smartbill_billing_cif', true );
			$type    = get_post_meta( $order_id, 'smartbill_billing_type', true );

			if ( 'pf' == $type ) {
				$type = $custom_checkout_options['client_individual_trans'];
			}
			if ( 'pj' == $type ) {
				$type = $custom_checkout_options['client_entity_trans'];
			}

			$address['smartbill_billing_type']       = $type;
			$address['smartbill_billing_cif']        = $cif;
			$address['smartbill_billing_nr_reg_com'] = $reg_com;
		}
		return $address;

	}

	/**
	 * Add fields in shipping address
	 *
	 * @param Array $address shipping address fields.
	 * @param Order $order Woocommerce order.
	 *
	 * @return Array $address
	 */
	public function smartbill_formatted_shipping_address( $address, $order ) {
		$admin_settings = new Smartbill_Woocommerce_Admin_Settings_Fields();
		if ( $admin_settings->get_custom_checkout() ) {
			$custom_checkout_options = $admin_settings->get_custom_checkout_options();
			$order_id                = $order->get_id();
			$reg_com                 = get_post_meta( $order_id, 'smartbill_shipping_nr_reg_com', true );
			$cif                     = get_post_meta( $order_id, 'smartbill_shipping_cif', true );
			$type                    = get_post_meta( $order_id, 'smartbill_shipping_type', true );

			if ( 'pf' == $type ) {
				$type = $custom_checkout_options['client_individual_trans'];
			}
			if ( 'pj' == $type ) {
				$type = $custom_checkout_options['client_entity_trans'];
			}

			$address['smartbill_shipping_type']       = $type;
			$address['smartbill_shipping_cif']        = $cif;
			$address['smartbill_shipping_nr_reg_com'] = $reg_com;
		}
		return $address;

	}

	/**
	 * Add fields in billing address
	 *
	 * @param Array $replacements formated billing address.
	 * @param Array $args billing fields.
	 *
	 * @return Array $replacements
	 */
	public function smartbill_billing_fields_replacements( $replacements, $args ) {
		$admin_settings = new Smartbill_Woocommerce_Admin_Settings_Fields();
		if ( $admin_settings->get_custom_checkout() ) {

			$replacements['{smartbill_billing_type}']       = isset( $args['smartbill_billing_type'] ) ? $args['smartbill_billing_type'] : '';
			$replacements['{smartbill_billing_cif}']        = isset( $args['smartbill_billing_cif'] ) ? $args['smartbill_billing_cif'] : '';
			$replacements['{smartbill_billing_nr_reg_com}'] = isset( $args['smartbill_billing_nr_reg_com'] ) ? $args['smartbill_billing_nr_reg_com'] : '';
		}
		return $replacements;
	}

	/**
	 * Change billing address format
	 *
	 * @param string $address_formats formated billing address.

	 * @return string $address_formats
	 */
	public function smartbill_address_formats( $address_formats ) {
		$admin_settings = new Smartbill_Woocommerce_Admin_Settings_Fields();
		if ( $admin_settings->get_custom_checkout() ) {
			$address_formats['default'] = "{smartbill_billing_type}\n{smartbill_billing_cif}\n{smartbill_billing_nr_reg_com}\n" . $address_formats['default'];
		}
		return $address_formats;

	}

	/**
	 * Change shipping address format
	 *
	 * @param string $address formated shipping address.
	 * @param Array  $raw_address array with address fields.
	 * @param Order  $order Woocommerce order.
	 *
	 * @return string $address
	 */
	public function smartbill_shipping_address_format( $address, $raw_address, $order ) {
		$admin_settings = new Smartbill_Woocommerce_Admin_Settings_Fields();
		if ( $admin_settings->get_custom_checkout() ) {
			$raw_address['smartbill_shipping_type']       = empty( $raw_address['smartbill_shipping_type'] ) ? '' : $raw_address['smartbill_shipping_type'] . '<br/>';
			$raw_address['smartbill_shipping_cif']        = empty( $raw_address['smartbill_shipping_cif'] ) ? '' : $raw_address['smartbill_shipping_cif'] . '<br/>';
			$raw_address['smartbill_shipping_nr_reg_com'] = empty( $raw_address['smartbill_shipping_nr_reg_com'] ) ? '' : $raw_address['smartbill_shipping_nr_reg_com'] . '<br/>';

			$address = $raw_address['smartbill_shipping_type'] . $raw_address['smartbill_shipping_cif'] . $raw_address['smartbill_shipping_nr_reg_com'] . $address;
		}
		return $address;
	}

	/**
	 * Function for `woocommerce_order_status_changed` action-hook.
	 * Create smartbill document on status change.
	 *
	 * @param int    $id order id.
	 * @param string $status_transition_from order status key.
	 * @param string $status_transition_to order status key.
	 *
	 * @return void
	 */
	public function smartbill_woocommerce_automatically_issue_document_by_status( $id, $status_transition_from, $status_transition_to ) {
		$options = get_option( 'smartbill_plugin_options_settings' );
		if ( ! empty( $options ) && is_array( $options ) && isset( $options['order_status'] ) && !empty($options['order_status']) ) {
			if ( ! is_array( $options['order_status'] ) ) {
				$options['order_status'] = array( $options['order_status'] );
			}
			if ( in_array( 'wc-' . $status_transition_to, $options['order_status'] ) ) {
				//Check if current screen/page is not shop_order or edit-shop_order. Those cases are treated separately. 
				$currentScreen = function_exists('get_current_screen') ? get_current_screen(): "";
		        $currentScreen = isset($currentScreen->id)?$currentScreen->id:"";

		        if("woocommerce_page_wc-orders" !== $currentScreen && "edit-shop_order" !== $currentScreen && "shop_order" !== $currentScreen){
					//Check if order has invoice. 
					$smartbill_private_link = get_post_meta($id, 'smartbill_private_link');
					if ( empty( $smartbill_private_link ) ) {
						$return = smartbill_create_document( $id );
						if ( ! $return['status'] ) {
							$order = wc_get_order( $id );
							// The text for the note.
							$note = __( 'Eroare! ', 'smartbill-woocommerce' ) . $return['error'];
							// Add the note.
							$order->add_order_note( $note );
						}
					}
				}
			}
		}
	}

	/**
	 * Create public route for updating woocomemrce stock.
	 *
	 * @return void
	 */
	public function smartbill_sync_stock_route() {
		register_rest_route(
			'smartbill_woocommerce/v1',
			'/stocks',
			array(
				'methods'             => 'POST',
				'callback'            => array( $this, 'smartbill_woocommerce_sync' ),
				'permission_callback' => '__return_true',
			)
		);
	}

	/**
	 * Sincronyze woocommerce stocks with smartbill stocks.
	 *
	 * @param WP_REST_Request $request POST with stocks.
	 *
	 * @return WP_REST_Response|false
	 */
	public function smartbill_woocommerce_sync( WP_REST_Request $request ) {
		$options = get_option( 'smartbill_plugin_options_settings' );
		if ( ! empty( $options ) && is_array( $options ) && isset( $options['sync_stock'] ) ) {
			if ( 0 == $options['sync_stock'] ) {
				return new WP_REST_Response( __( 'Sincronizarea stocului este oprita. Porneste sincronizarea stocurilor din setarile modulului SmartBill apoi incearca din nou.', 'smartbill-woocommerce' ), 400 );
			}
		} else {
			return new WP_REST_Response( __( 'Sincronizarea stocului este oprita. Porneste sincronizarea stocurilor din setarile modulului SmartBill apoi incearca din nou.', 'smartbill-woocommerce' ), 400 );
		}
		$options = get_option( 'smartbill_plugin_options' );
		$body    = json_decode( $request->get_body(), 1 );
		if ( is_null( $body ) ) {
			return new WP_REST_Response( __( 'Eroare sintaxa. Verifica valididatea JSON-ului trimis.', 'smartbill-woocommerce' ), 400 );
		}

		if ( ! empty( $options ) && is_array( $options ) && isset( $options['password'] ) ) {
			// if authorization.
			$authorization = $request->get_header( 'authorization' );
			if ( $authorization ) {
				$details  = substr( $authorization, strlen( 'Bearer ' ) );
				$db_token = $options['password'];
				if ( strtolower( $details ) == $db_token ) {
					$this->smartbill_woocommerce_stocks_update( $body );
				} else {
					$file = __DIR__ . DIRECTORY_SEPARATOR . 'admin' . DIRECTORY_SEPARATOR . 'smartbill_sincronizare_stocuri.log';
					if ( file_exists( $file ) ) {
						error_log( '======================================================================================================================================' . PHP_EOL, 3, $file );
					}
					$this->smartbill_woocommerce_log( $body, 'Authentication failed when PRODUCTS RECEIVED' );
					return new WP_REST_Response( __( 'Autentificare esuata. Asigura-te ca tokenul folosit pentru trimiterea notificarii de stoc este corect si ca serverul tau permite autentificarea prin headers.', 'smartbill-woocommerce' ), 403 );
				}
			} else {
				// else skip.
				$file = __DIR__ . DIRECTORY_SEPARATOR . 'admin' . DIRECTORY_SEPARATOR . 'smartbill_sincronizare_stocuri.log';
				if ( file_exists( $file ) ) {
					error_log( '======================================================================================================================================' . PHP_EOL, 3, $file );
				}
				$this->smartbill_woocommerce_log( $body, 'Authentication failed when PRODUCTS RECEIVED' );
				return new WP_REST_Response( __( 'Autentificare esuata. Asigura-te ca tokenul folosit pentru trimiterea notificarii de stoc este corect si ca serverul tau permite autentificarea prin headers.', 'smartbill-woocommerce' ), 403 );
			}
		} else {
			$file = __DIR__ . DIRECTORY_SEPARATOR . 'admin' . DIRECTORY_SEPARATOR . 'smartbill_sincronizare_stocuri.log';
			if ( file_exists( $file ) ) {
				error_log( '======================================================================================================================================' . PHP_EOL, 3, $file );
			}
			$this->smartbill_woocommerce_log( $body, 'Authentication failed when PRODUCTS RECEIVED' );
			return new WP_REST_Response( __( 'Autentificare esuata. Asigura-te ca tokenul folosit pentru trimiterea notificarii de stoc este corect si ca serverul tau permite autentificarea prin headers.', 'smartbill-woocommerce' ), 403 );
		}
	}

	/**
	 * Search product by name.
	 *
	 * @param string $product_name product name.
	 *
	 * @return wc_Product|null $woocommerce_product
	 */
	public function smartbill_woocommerce_get_product_by_name( $product_name ) {

		$query = new WP_Query(
			array(
				's'           => $product_name,
				'post_type'   => array( 'product', 'product_variation' ),
				'post_status' => 'publish',
			)
		);

		$product_name = sanitize_title( $product_name );
		$p_id         = null;
		if ( $query->have_posts() ) {
			while ( $query->have_posts() ) {
				$query->the_post();
				$title = sanitize_title( get_the_title() );
				if ( $product_name == $title ) {
					$p_id = get_the_ID();
				}
			}
		}
		wp_reset_postdata();
		if ( $p_id ) {
			$woocommerce_product = wc_get_product( $p_id );
			return $woocommerce_product;
		} else {
			return null;
		}
	}

	/**
	 * Search product by code.
	 *
	 * @param string $product_code product sku.
	 *
	 * @return wc_Product|null $woocommerce_product
	 */
	public function smartbill_woocommerce_get_product_by_code( $product_code ) {
		$query = new WP_Query(
			array(
				'post_type'   => array( 'product', 'product_variation' ),
				'post_status' => 'publish',
				'meta_query'  => array(
					array(
						'key'     => '_sku',
						'value'   => $product_code,
						'compare' => '=',
					),
				),
			)
		);

		$p_id = null;
		if ( $query->have_posts() ) {
			while ( $query->have_posts() ) {
				$query->the_post();
				$p_id = get_the_ID();
				// we take the first element assuming the sku is unique.
				break;
			}
		}
		wp_reset_postdata();
		if ( $p_id ) {
			$woocommerce_product = wc_get_product( $p_id );
			return $woocommerce_product;
		} else {
			return null;
		}

	}

	/**
	 * Update woocomemrce stocks if possible.
	 *
	 * @param array $products the text messages.
	 *
	 * @return void // echo text messages.
	 */
	public function smartbill_woocommerce_stocks_update( $products ) {
		$options = get_option( 'smartbill_plugin_options_settings' );
		if ( ! empty( $options ) && is_array( $options ) && isset( $options['used_stock'] ) ) {
			$selected_stock = $options['used_stock'];
			if ( 'fara-gestiune' == $selected_stock  ) {
				echo( '"Eroare actualizare stoc. Gestiunea monitorizata nu a fost setata in modulul SmartBill."' . PHP_EOL );
				$selected_stock = false;
			}
		} else {
			echo( '"Eroare actualizare stoc. Gestiunea monitorizata nu a fost setata in modulul SmartBill."' . PHP_EOL );
			$selected_stock = false;
		}

		if ( isset( $products['products'] ) ) {
			$products = $products['products'];
		}
		$file = __DIR__ . DIRECTORY_SEPARATOR . 'admin' . DIRECTORY_SEPARATOR . 'smartbill_sincronizare_stocuri.log';
		if ( file_exists( $file ) ) {
			error_log( '======================================================================================================================================' . PHP_EOL, 3, $file );
		}

		$this->smartbill_woocommerce_log( $products, 'PRODUCTS RECEIVED' );

		if ( is_array( $products ) ) {
			if ( 0 == count( $products ) ) {
				echo( '"Testul de sincronizare stoc a fost facut cu succes!"' . PHP_EOL );
			}
			$this->smartbill_woocommerce_log( 'START STOCK SYNC', 'INFO' );
			foreach ( $products as $product ) {
				unset( $db_product );
				if ( $selected_stock && strtolower( $product['warehouse'] ) == strtolower( $selected_stock ) ) {
					if ( isset( $product['productCode'] ) && trim( $product['productCode'] ) != '' ) {
						$db_product = $this->smartbill_woocommerce_get_product_by_code( $product['productCode'] );
						if ( is_null( $db_product ) ) {
							echo( '"Eroare actualizare stoc. Produsul cu codul ' . esc_attr( $product['productCode'] ) . ' nu a fost gasit in nomenclatorul WooCommerce."' . PHP_EOL );
							$this->smartbill_woocommerce_log( 'Product with product code ' . $product['productCode'] . ' not found!', 'ERROR' );
							unset( $db_product );
						}
					} else {
						$db_product = $this->smartbill_woocommerce_get_product_by_name( $product['productName'] );
						if ( is_null( $db_product ) ) {
							echo( '"Eroare actualizare stoc. Produsul cu numele ' . esc_attr( $product['productName'] ) . ' nu a fost gasit in nomenclatorul WooCommerce."' . PHP_EOL );
							$this->smartbill_woocommerce_log( 'Product with product name ' . $product['productName'] . ' not found!', 'ERROR' );
							unset( $db_product );
						}
					}

					if ( isset( $db_product ) ) {
						// update stock.
						if ( isset( $product['productCode'] ) && trim( $product['productCode'] ) != '' ) {
							$this->smartbill_woocommerce_log( 'Product ' . esc_attr( $product['productName'] ) . ' has been found by sku: ' . esc_attr( $product['productCode'] ) . '. Attempting to update stocks.', 'INFO' );
						} else {
							$this->smartbill_woocommerce_log( 'Product ' . esc_attr( $product['productName'] ) . ' has been found by name. Attempting to update stocks.', 'INFO' );
						}

						try {
							$stock_update = wc_update_product_stock( $db_product->get_id(), $product['quantity'] );
							if(!empty($stock_update)){
								$date=new DateTime('now');
								$date->setTimezone(new DateTimeZone(wp_timezone_string()));
								update_option('smartbill_stock_update',serialize($date));
								$this->smartbill_woocommerce_log( 'Quantity of product ' . esc_attr( $product['productName'] ) . ' with id ' . esc_attr( $stock_update ) . ' has been updated to ' . esc_attr( $product['quantity'] ) . '.', 'INFO' );
							}
							if ( isset( $product['productCode'] ) && trim( $product['productCode'] ) != '' ) {
								echo( '"Stoc actualizat pentru produsul cu id-ul ' . esc_attr( $db_product->get_id() ) . ' si codul ' . esc_attr( $product['productCode'] ) . '. Stoc nou: ' . esc_attr( $product['quantity'] ) . '"' . PHP_EOL );
							} else {
								echo( '"Stoc actualizat pentru produsul cu id-ul ' . esc_attr( $db_product->get_id() ) . ' si numele ' . esc_attr( $product['productName'] ) . '. Stoc nou: ' . esc_attr( $product['quantity'] ) . '"' . PHP_EOL );
							}
						} catch ( Exception $e ) {
							if ( isset( $product['productCode'] ) && trim( $product['productCode'] ) != '' ) {
								$this->smartbill_woocommerce_log( "Couldn't update stocks for product " . esc_attr( $product['productName'] ) . ' with sku: ' . esc_attr( $product['productCode'] ) . '!', 'ERROR' );
							} else {
								$this->smartbill_woocommerce_log( "Couldn't update stocks for product " . esc_attr( $product['productName'] ) . '!', 'ERROR' );
							}
							$this->smartbill_woocommerce_log( $e->getMessage(), 'ERROR' );
							echo( '"Eroare actualizare stoc. Stocul produsului ' . esc_attr( $product['productName'] ) . ' nu a fost putut fi actualizat."' . PHP_EOL );
							echo( '"' . esc_attr( $e->getMessage() ) . '"' . PHP_EOL );
						}
					}
				} else {
					$this->smartbill_woocommerce_log( 'Plugin configured warehouse: ' . esc_attr( $selected_stock ) . " doesn't match product warehouse : " . esc_attr( $product['warehouse'] ), 'ERROR' );
					echo( '"Eroare actualizare stoc. Gestiune configurata in modul: ' . esc_attr( $selected_stock ) . '. Gestiunea produsului ' . esc_attr( $product['productName'] ) . ': ' . esc_attr( $product['warehouse'] ) . '"' . PHP_EOL );
				}
			}
			$this->smartbill_woocommerce_log( 'STOP STOCK SYNC', 'INFO' );
		}

	}

	/**
	 * Save information into a file.
	 *
	 * @param string $message the text messeage.
	 * @param string $type the type of message.
	 *
	 * @return void
	 */
	public function smartbill_woocommerce_log( $message, $type ) {
		$file = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'admin' . DIRECTORY_SEPARATOR . 'smartbill_sincronizare_stocuri.log';
		if ( file_exists( $file ) ) {
			$format_message = gmdate( 'Y-m-d H:i:sO' ) . ';' . $type . ';' . wp_json_encode( $message ) . PHP_EOL;
			error_log( $format_message, 3, $file );
		}
	}

}
