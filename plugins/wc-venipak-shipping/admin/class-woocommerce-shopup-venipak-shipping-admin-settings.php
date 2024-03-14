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
class Woocommerce_Shopup_Venipak_Shipping_Admin_Settings {

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
	 * Settings of plugin
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      array    $settings    Settings of plugin
	 */
	private $settings;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

		$this->set_settings();
	}

	/**
	 *
	 *
	 * @since    1.0.0
	 */
	public function set_settings() {
		$this->settings = (array) get_option( 'shopup_venipak_shipping_settings' );
	}

	/**
	 *
	 *
	 * @since    1.5.4
	 */
	public function update_last_pack_number($last_pack_number) {
		$this->settings['shopup_venipak_shipping_field_firstpacknumber'] = $last_pack_number;
		update_option('shopup_venipak_shipping_settings', $this->settings);
		$this->set_settings();
	}

	/**
	 *
	 *
	 * @since    1.0.0
	 */
	public function get_option_by_key($key) {
		return array_key_exists($key, $this->settings)
			? esc_attr($this->settings[$key])
			: null;
	}

	/**
	 *
	 *
	 * @since    1.0.0
	 */
	public function add_menu() {
		add_submenu_page(
			'woocommerce',
			'Venipak',
			'Venipak',
			'manage_options',
			'shopup_venipak_shipping',
			array($this, 'shopup_venipak_shipping_settings_page_cb')
		);
	}

	/**
	 *
	 *
	 * @since    1.0.0
	 */
	public function register_settings() {
		register_setting( 'shopup_venipak_shipping_settings_group', 'shopup_venipak_shipping_settings' );
	}

	/**
	 *
	 *
	 * @since    1.0.0
	 */
	public function register_sections() {
		add_settings_section(
			'shopup_venipak_shipping_section_venipak',
			__( 'Venipak settings', 'woocommerce-shopup-venipak-shipping' ),
			array($this, 'shopup_venipak_shipping_section_venipak_description_cb'),
			'shopup_venipak_shipping'
		);

		add_settings_section(
			'shopup_venipak_shipping_section_products',
			__( 'Products settings', 'woocommerce-shopup-venipak-shipping' ),
			array($this, 'shopup_venipak_shipping_section_products_description_cb'),
			'shopup_venipak_shipping'
		);

		add_settings_section(
			'shopup_venipak_shipping_section_checkout',
			__( 'Checkout page settings', 'woocommerce-shopup-venipak-shipping' ),
			array($this, 'shopup_venipak_shipping_section_checkout_description_cb'),
			'shopup_venipak_shipping'
		);
		add_settings_section(
			'shopup_venipak_shipping_section_sender',
			__( 'Sender settings', 'woocommerce-shopup-venipak-shipping' ),
			array($this, 'shopup_venipak_shipping_section_sender_description_cb'),
			'shopup_venipak_shipping'
		);
	}

	/**
	 *
	 *
	 * @since    1.0.0
	 */
	public function register_fields() {
		add_settings_field(
			'shopup_venipak_shipping_field_userid',
			__( 'Venipak user id', 'woocommerce-shopup-venipak-shipping' ),
			array($this, 'shopup_venipak_shipping_field_text_cb'),
			'shopup_venipak_shipping',
			'shopup_venipak_shipping_section_venipak',
			array( 'field' => 'shopup_venipak_shipping_field_userid', 'required' => true )
		);
		add_settings_field(
			'shopup_venipak_shipping_field_username',
			__( 'Venipak username', 'woocommerce-shopup-venipak-shipping' ),
			array($this, 'shopup_venipak_shipping_field_text_cb'),
			'shopup_venipak_shipping',
			'shopup_venipak_shipping_section_venipak',
			array( 'field' => 'shopup_venipak_shipping_field_username', 'required' => true )
		);
		add_settings_field(
			'shopup_venipak_shipping_field_password',
			__( 'Venipak user password', 'woocommerce-shopup-venipak-shipping' ),
			array($this, 'shopup_venipak_shipping_field_password_cb'),
			'shopup_venipak_shipping',
			'shopup_venipak_shipping_section_venipak',
			array( 'field' => 'shopup_venipak_shipping_field_password', 'required' => true )
		);
		add_settings_field(
			'shopup_venipak_shipping_field_firstpacknumber',
			__( 'First pack number', 'woocommerce-shopup-venipak-shipping' ),
			array($this, 'shopup_venipak_shipping_field_text_cb'),
			'shopup_venipak_shipping',
			'shopup_venipak_shipping_section_venipak',
			array( 'field' => 'shopup_venipak_shipping_field_firstpacknumber', 'default' => 1000001, 'required' => true )
		);
		add_settings_field(
			'shopup_venipak_shipping_field_manifest',
			__( 'Manifest', 'woocommerce-shopup-venipak-shipping' ),
			array($this, 'shopup_venipak_shipping_field_text_cb'),
			'shopup_venipak_shipping',
			'shopup_venipak_shipping_section_venipak',
			array( 'field' => 'shopup_venipak_shipping_field_manifest', 'default' => '001', 'required' => true )
		);
		add_settings_field(
			'shopup_venipak_shipping_field_forcedispatch',
			__( 'Enable venipak for all orders', 'woocommerce-shopup-venipak-shipping' ),
			array($this, 'shopup_venipak_shipping_field_checkbox_cb'),
			'shopup_venipak_shipping',
			'shopup_venipak_shipping_section_venipak',
			array( 'field' => 'shopup_venipak_shipping_field_forcedispatch')
		);
		add_settings_field(
			'shopup_venipak_shipping_field_labelformat',
			__( 'Label format', 'woocommerce-shopup-venipak-shipping' ),
			array($this, 'shopup_venipak_shipping_field_radio_cb'),
			'shopup_venipak_shipping',
			'shopup_venipak_shipping_section_venipak',
			array(
				'field' => 'shopup_venipak_shipping_field_labelformat',
				'options' => array(
					'a4' => __( 'A4', 'woocommerce-shopup-venipak-shipping' ),
					'sticker' => __( 'Sticker', 'woocommerce-shopup-venipak-shipping' ),
				),
				'default' => 'a4'
			)
		);
		add_settings_field(
			'shopup_venipak_shipping_field_return_service',
			__( 'Return expiration days count', 'woocommerce-shopup-venipak-shipping' ),
			array($this, 'shopup_venipak_shipping_field_text_cb'),
			'shopup_venipak_shipping',
			'shopup_venipak_shipping_section_checkout',
			array( 'field' => 'shopup_venipak_shipping_field_return_service', 'default' => 0, 'required' => false )
		);

		add_settings_field(
			'shopup_venipak_shipping_field_isstatuschangedisabled',
			__( 'Disable order status change', 'woocommerce-shopup-venipak-shipping' ),
			array($this, 'shopup_venipak_shipping_field_checkbox_cb'),
			'shopup_venipak_shipping',
			'shopup_venipak_shipping_section_checkout',
			array( 'field' => 'shopup_venipak_shipping_field_isstatuschangedisabled')
		);

		add_settings_field(
			'shopup_venipak_shipping_field_maxpackproducts',
			__( 'Max products in one pack', 'woocommerce-shopup-venipak-shipping' ),
			array($this, 'shopup_venipak_shipping_field_text_cb'),
			'shopup_venipak_shipping',
			'shopup_venipak_shipping_section_products',
			array( 'field' => 'shopup_venipak_shipping_field_maxpackproducts', 'default' => 1, 'required' => true )
		);
		add_settings_field(
			'shopup_venipak_shipping_field_ismapenabled',
			__( 'Show google map', 'woocommerce-shopup-venipak-shipping' ),
			array($this, 'shopup_venipak_shipping_field_checkbox_cb'),
			'shopup_venipak_shipping',
			'shopup_venipak_shipping_section_checkout',
			array( 'field' => 'shopup_venipak_shipping_field_ismapenabled')
		);
		add_settings_field(
			'shopup_venipak_shipping_field_googlemapapikey',
			__( 'Google map API_KEY', 'woocommerce-shopup-venipak-shipping' ),
			array($this, 'shopup_venipak_shipping_field_text_cb'),
			'shopup_venipak_shipping',
			'shopup_venipak_shipping_section_checkout',
			array( 'field' => 'shopup_venipak_shipping_field_googlemapapikey', 'default' => 'AIzaSyBsi7bs2XiqmUJ1wFyTg3nCVN2TZAAghko')
		);
		add_settings_field(
			'shopup_venipak_shipping_field_pickuptype',
			__( 'Pickup options', 'woocommerce-shopup-venipak-shipping' ),
			array($this, 'shopup_venipak_shipping_field_radio_cb'),
			'shopup_venipak_shipping',
			'shopup_venipak_shipping_section_checkout',
			array(
				'field' => 'shopup_venipak_shipping_field_pickuptype',
				'options' => array(
					'all' => __( 'All', 'woocommerce-shopup-venipak-shipping' ),
					'1' => __( 'Only pickup', 'woocommerce-shopup-venipak-shipping' ), // pickup, using numbers because venipak api use it
					'3' => __( 'Only locker', 'woocommerce-shopup-venipak-shipping' ), // locker
				),
				'default' => 'all'
			)
		);
		add_settings_field(
			'shopup_venipak_shipping_field_isdoorcodeenabled',
			__( 'Show door code option', 'woocommerce-shopup-venipak-shipping' ),
			array($this, 'shopup_venipak_shipping_field_checkbox_cb'),
			'shopup_venipak_shipping',
			'shopup_venipak_shipping_section_checkout',
			array('field' => 'shopup_venipak_shipping_field_isdoorcodeenabled')
		);
		add_settings_field(
			'shopup_venipak_shipping_field_isofficenoenabled',
			__( 'Show office number option', 'woocommerce-shopup-venipak-shipping' ),
			array($this, 'shopup_venipak_shipping_field_checkbox_cb'),
			'shopup_venipak_shipping',
			'shopup_venipak_shipping_section_checkout',
			array( 'field' => 'shopup_venipak_shipping_field_isofficenoenabled')
		);
		add_settings_field(
			'shopup_venipak_shipping_field_isdeliverytimenabled',
			__( 'Show delivery time option', 'woocommerce-shopup-venipak-shipping' ),
			array($this, 'shopup_venipak_shipping_field_checkbox_cb'),
			'shopup_venipak_shipping',
			'shopup_venipak_shipping_section_checkout',
			array( 'field' => 'shopup_venipak_shipping_field_isdeliverytimenabled')
		);
		add_settings_field(
			'shopup_venipak_shipping_field_sendername',
			__( 'Company name', 'woocommerce-shopup-venipak-shipping' ),
			array($this, 'shopup_venipak_shipping_field_text_cb'),
			'shopup_venipak_shipping',
			'shopup_venipak_shipping_section_sender',
			array( 'field' => 'shopup_venipak_shipping_field_sendername', 'required' => true )
		);

		add_settings_field(
			'shopup_venipak_shipping_field_sendercompanycode',
			__( 'Company code', 'woocommerce-shopup-venipak-shipping' ),
			array($this, 'shopup_venipak_shipping_field_text_cb'),
			'shopup_venipak_shipping',
			'shopup_venipak_shipping_section_sender',
			array( 'field' => 'shopup_venipak_shipping_field_sendercompanycode', 'required' => true )
		);

		add_settings_field(
			'shopup_venipak_shipping_field_sendercountry',
			__( 'Country', 'woocommerce-shopup-venipak-shipping' ),
			array($this, 'shopup_venipak_shipping_field_select_cb'),
			'shopup_venipak_shipping',
			'shopup_venipak_shipping_section_sender',
			array( 'field' => 'shopup_venipak_shipping_field_sendercountry', 'required' => true )
		);

		add_settings_field(
			'shopup_venipak_shipping_field_sendercity',
			__( 'City', 'woocommerce-shopup-venipak-shipping' ),
			array($this, 'shopup_venipak_shipping_field_text_cb'),
			'shopup_venipak_shipping',
			'shopup_venipak_shipping_section_sender',
			array( 'field' => 'shopup_venipak_shipping_field_sendercity', 'required' => true )
		);

		add_settings_field(
			'shopup_venipak_shipping_field_senderaddress',
			__( 'Address', 'woocommerce-shopup-venipak-shipping' ),
			array($this, 'shopup_venipak_shipping_field_text_cb'),
			'shopup_venipak_shipping',
			'shopup_venipak_shipping_section_sender',
			array( 'field' => 'shopup_venipak_shipping_field_senderaddress', 'required' => true )
		);

		add_settings_field(
			'shopup_venipak_shipping_field_senderpostcode',
			__( 'Post code', 'woocommerce-shopup-venipak-shipping' ),
			array($this, 'shopup_venipak_shipping_field_text_cb'),
			'shopup_venipak_shipping',
			'shopup_venipak_shipping_section_sender',
			array( 'field' => 'shopup_venipak_shipping_field_senderpostcode', 'required' => true )
		);

		add_settings_field(
			'shopup_venipak_shipping_field_sendercontactperson',
			__( 'Contact person', 'woocommerce-shopup-venipak-shipping' ),
			array($this, 'shopup_venipak_shipping_field_text_cb'),
			'shopup_venipak_shipping',
			'shopup_venipak_shipping_section_sender',
			array( 'field' => 'shopup_venipak_shipping_field_sendercontactperson', 'required' => true )
		);

		add_settings_field(
			'shopup_venipak_shipping_field_sendercontacttel',
			__( 'Contact phone number', 'woocommerce-shopup-venipak-shipping' ),
			array($this, 'shopup_venipak_shipping_field_text_cb'),
			'shopup_venipak_shipping',
			'shopup_venipak_shipping_section_sender',
			array( 'field' => 'shopup_venipak_shipping_field_sendercontacttel', 'required' => true )
		);

		add_settings_field(
			'shopup_venipak_shipping_field_sendercontactemail',
			__( 'Contact email address', 'woocommerce-shopup-venipak-shipping' ),
			array($this, 'shopup_venipak_shipping_field_text_cb'),
			'shopup_venipak_shipping',
			'shopup_venipak_shipping_section_sender',
			array( 'field' => 'shopup_venipak_shipping_field_sendercontactemail', 'required' => true )
		);
	}


	/**
	 *
	 *
	 * @since    1.3.2
	 */
	public function shopup_venipak_shipping_field_select_cb($params) {
		$field = $params['field'];
		$required = $params['required'] ? 'required' : '';
		$value = $this->get_option_by_key($field);
		$items = array(
			array("value" => "LT", "name" => "Lithuania"),
			array("value" => "LV", "name" => "Latvia"),
			array("value" => "EE", "name" => "Estonia"),
			array("value" => "BE", "name" => "Belgium"),
			array("value" => "BG", "name" => "Bulgaria"),
			array("value" => "CZ", "name" => "Czechia"),
			array("value" => "DK", "name" => "Denmark"),
			array("value" => "DE", "name" => "Germany"),
			array("value" => "IE", "name" => "Ireland"),
			array("value" => "EL", "name" => "Greece"),
			array("value" => "ES", "name" => "Spain"),
			array("value" => "FR", "name" => "France"),
			array("value" => "HR", "name" => "Croatia"),
			array("value" => "IT", "name" => "Italy"),
			array("value" => "CY", "name" => "Cyprus"),
			array("value" => "LU", "name" => "Luxembourg"),
			array("value" => "HU", "name" => "Hungary"),
			array("value" => "MT", "name" => "Malta"),
			array("value" => "NL", "name" => "Netherlands"),
			array("value" => "AT", "name" => "Austria"),
			array("value" => "PL", "name" => "Poland"),
			array("value" => "PT", "name" => "Portugal"),
			array("value" => "RO", "name" => "Romania"),
			array("value" => "SI", "name" => "Slovenia"),
			array("value" => "SK", "name" => "Slovakia"),
			array("value" => "FI", "name" => "Finland"),
			array("value" => "SE", "name" => "Sweden")
		);
		echo "<select name='shopup_venipak_shipping_settings[$field]' {$required}>";
		echo "<option/>";
		foreach($items as $item) {
			$selected = ($value == $item['value']) ? 'selected="selected"' : '';
			echo "<option value='" . $item['value'] . "' $selected>" . $item['name'] . "</option>";
		}
		echo "</select>";
	}

	/**
	 *
	 *
	 * @since    1.0.0
	 */
	public function shopup_venipak_shipping_field_text_cb($params) {
		$field = array_key_exists('field', $params) ? $params['field'] : null;
		$default_value = array_key_exists('default', $params) ? $params['default'] : null;
		$required = array_key_exists('required', $params) ? 'required' : '';
		$value = $this->get_option_by_key($field) ? $this->get_option_by_key($field) : $default_value;

		echo "<input type='text' name='shopup_venipak_shipping_settings[$field]' value='$value' {$required} />";
	}


	/**
	 *
	 *
	 * @since    1.0.0
	 */
	public function shopup_venipak_shipping_field_password_cb($params) {
		$field = array_key_exists('field', $params) ? $params['field'] : null;
		$default_value = array_key_exists('default', $params) ? $params['default'] : null;
		$value = $this->get_option_by_key($field) ? $this->get_option_by_key($field) : $default_value;

		echo "<input type='password' name='shopup_venipak_shipping_settings[$field]' value='$value' required/>";
	}

	/**
	 *
	 *
	 * @since    1.0.0
	 */
	public function shopup_venipak_shipping_field_checkbox_cb($params) {
		$field = array_key_exists('field', $params) ? $params['field'] : null;
		$default_value = array_key_exists('default', $params) ? $params['default'] : null;
		$value = $this->get_option_by_key($field);
		printf(
		'<input name="shopup_venipak_shipping_settings[%1$s]" type="checkbox" %2$s />',
		$field,
		checked( !!$value, true, false )
		);
	}

	/**
	 *
	 *
	 * @since    1.2.0
	 */
	public function shopup_venipak_shipping_field_radio_cb($params) {
		$field = array_key_exists('field', $params) ? $params['field'] : null;
		$default_value = array_key_exists('default', $params) ? $params['default'] : null;
		$options = $params['options'];
		$field_value = $this->get_option_by_key($field) ? $this->get_option_by_key($field) : $default_value;

		foreach ($options as $key => $value) {
			printf(
				'<input name="shopup_venipak_shipping_settings[%1$s]" id="radio_%1$s_%2$s" value="%2$s" type="radio" %3$s /><label for="radio_%1$s_%2$s">%4$s</label><br />',
				$field,
				$key,
				checked( $key == $field_value, true, false ),
				$value
			);
		}
	}

	/**
	 *
	 *
	 * @since    1.0.0
	 */
	public function shopup_venipak_shipping_settings_page_cb() {
	  if ( ! current_user_can( 'manage_options' ) ) {
	    return;
	  }
	  ?>
	  <div class="wrap">
	    <?php if( isset($_GET['settings-updated']) ) { ?>
	      <div class="notice notice-success is-dismissible">
	          <p><?php _e( 'Settings saved', 'woocommerce-shopup-venipak-shipping' ); ?></p>
	      </div>
	    <?php } ?>
	    <form action="options.php" method="POST">
	      <?php settings_fields('shopup_venipak_shipping_settings_group'); ?>
	      <?php do_settings_sections('shopup_venipak_shipping'); ?>
	      <?php submit_button(); ?>
	    </form>
	  </div>
	  <?php
	}

	/**
	 *
	 *
	 * @since    1.0.0
	 */
	public function format_pack_number($id) {
		return 'V' . $this->get_option_by_key('shopup_venipak_shipping_field_userid') . 'E' . str_pad(strval($id), 7, "0", STR_PAD_LEFT);
	}

	/**
	 *
	 *
	 * @since    1.0.0
	 */
	public function shopup_venipak_shipping_section_venipak_description_cb() {
		return;
	}

	/**
	 *
	 *
	 * @since    1.0.0
	 */
	public function shopup_venipak_shipping_section_products_description_cb() {
		return;
	}

	/**
	 *
	 *
	 * @since    1.0.0
	 */
	public function shopup_venipak_shipping_section_checkout_description_cb() {
		return;
	}

	/**
	 *
	 *
	 * @since    1.0.0
	 */
	public function shopup_venipak_shipping_section_sender_description_cb() {
		return;
	}

}
