<?php
// to check whether accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once( WP_PLUGIN_DIR . '/woocommerce/includes/admin/settings/class-wc-settings-page.php' );

class Elex_CM_Pricing_Discount_Settings extends WC_Settings_Page {
	public $user_adjustment_price;
	public function __construct() {
		global $user_adjustment_price;
		$this->init();
		$this->id = 'elex_catalog_mode';
	}

	public function init() {
		$this->user_adjustment_price = get_option( 'eh_pricing_discount_price_adjustment_options', array() );
		add_filter( 'woocommerce_settings_tabs_array', array( $this, 'elex_cm_add_settings_tab' ), 50 );
		add_action( 'woocommerce_update_options_elex_catalog_mode', array( $this, 'elex_cm_update_settings' ) );
		add_filter( 'woocommerce_product_data_tabs', array( $this, 'elex_cm_add_product_tab' ) );
		add_action( 'woocommerce_product_data_panels', array( $this, 'elex_cm_add_price_adjustment_data_fields' ) );
		add_action( 'woocommerce_process_product_meta', array( $this, 'elex_cm_add_custom_general_fields_save' ) );
		add_filter( 'woocommerce_sections_elex_catalog_mode', array( $this, 'output_sections' ) );
		add_filter( 'woocommerce_settings_elex_catalog_mode', array( $this, 'elex_cm_output_settings' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'elex_cm_include_js' ) );
	}

	public function elex_cm_include_js() {
			global $woocommerce;
			$woocommerce_version = function_exists( 'WC' ) ? WC()->version : $woocommerce->version;
			wp_enqueue_script( 'jquery' );
			wp_enqueue_script( 'elex-catalog-mode', ELEX_CATALOG_MODE_MAIN_URL_PATH . 'includes/elex-html-catalog-adjustment.js', array(), $woocommerce_version );
	}
	public function get_sections() {
		
		$sections = array(
			'' => __( 'Catalog Mode', 'elex-catmode-rolebased-price' ),
			'to-go-premium' => __( '<li><strong><font color="red">Go Premium!</font></strong></li>', 'elex-catmode-rolebased-price' ),
		);
		/**
		 * To get subsection of setting tab
		 * 
		 * @since 1.0.0
		 */
		return apply_filters( 'woocommerce_get_sections_elex_catalog_mode', $sections );
	}

	public function output_sections() {
		global $current_section;
		$sections = $this->get_sections();
		if ( empty( $sections ) || 1 === count( $sections ) ) {
			return;
		}
		echo '<ul class="subsubsub">';
		$array_keys = array_keys( $sections );
		foreach ( $sections as $id => $label ) {
			echo '<li><a href="' . esc_url( admin_url( 'admin.php?page=wc-settings&tab=elex_catalog_mode&section=' . sanitize_title( $id ) ) ) . '" class="' . ( $current_section == $id ? 'current' : '' ) . '">' . wp_kses_post( $label ) . '</a> ' . ( end( $array_keys ) == $id ? '' : '|' ) . ' </li>';
		}
		echo '</ul><br class="clear" />';
	}

	public static function elex_cm_add_settings_tab( $settings_tabs ) {
		$settings_tabs['elex_catalog_mode'] = __( 'Catalog Mode', 'elex-catmode-rolebased-price' );
		return $settings_tabs;
	}

	public function elex_cm_output_settings() {
		global $current_section, $woocommerce;
		$woocommerce_version = function_exists( 'WC' ) ? WC()->version : $woocommerce->version;
		if ( '' == $current_section ) {
		$settings = $this->elex_cm_get_catalog_settings();
			WC_Admin_Settings::output_fields( $settings );
		}
		if ( 'to-go-premium' == $current_section ) {
			wp_enqueue_style( 'eh-catalog-bootstrap', ELEX_CATALOG_MODE_MAIN_URL_PATH . 'resources/css/bootstrap.css', array(), $woocommerce_version );
			include_once( 'market.php' );
		}
	}

	public function elex_cm_update_settings( $current_section ) {
		$options = $this->elex_cm_get_catalog_settings();
		woocommerce_update_options( $options );
	}

	public function elex_cm_get_catalog_settings() {
		global $wp_roles;

		$user_roles = $wp_roles->role_names;
		$settings = array(
			'catalog_settings_section_title' => array(
				'name' => __( 'Catalog Mode Option:', 'elex-catmode-rolebased-price' ),
				'type' => 'title',
				'desc' => __( 'The changes you make here will be applicable across the site. You can exclude Administrator role from these changes.', 'elex-catmode-rolebased-price' ),
				'id' => 'eh_pricing_discount_catalog_section_title',
			),
			'cart_catalog_mode' => array(
				'title' => __( 'Remove Add to Cart', 'elex-catmode-rolebased-price' ),
				'type' => 'checkbox',
				'desc' => __( 'Enable', 'elex-catmode-rolebased-price' ),
				'css' => 'width:100%',
				'id' => 'eh_pricing_discount_cart_catalog_mode',
				'desc_tip' => __( 'Check to remove Add to Cart option.', 'elex-catmode-rolebased-price' ),
			),
			'cart_catalog_mode_shop' => array(
				'desc' => __( 'Shop Page', 'elex-catmode-rolebased-price' ),
				'id' => 'elex_catalog_remove_addtocart_shop',
				'default' => 'yes',
				'type' => 'checkbox',
				'checkboxgroup' => 'start',
				'autoload' => false,
			),
			'cart_catalog_mode_product' => array(
				'desc' => __( 'Product Page', 'elex-catmode-rolebased-price' ),
				'id' => 'elex_catalog_remove_addtocart_product',
				'default' => 'yes',
				'type' => 'checkbox',
				'checkboxgroup' => 'end',
				'autoload' => false,
			),
			'cart_catalog_mode_text' => array(
				'title' => __( 'Placeholder Text', 'elex-catmode-rolebased-price' ),
				'type' => 'textarea',
				'desc' => __( "Enter a text or html content to display when Add to Cart button is removed. Leave it empty if you don't want to show any content.", 'elex-catmode-rolebased-price' ),
				'css' => 'width:350px',
				'id' => 'eh_pricing_discount_cart_catalog_mode_text',
				'desc_tip' => true,
			),
			
			'replace_cart_catalog_mode' => array(
				'title' => __( 'Customize Add to Cart', 'elex-catmode-rolebased-price' ),
				'type' => 'checkbox',
				'desc' => __( 'Enable', 'elex-catmode-rolebased-price' ),
				'css' => 'width:100%',
				'id' => 'eh_pricing_discount_replace_cart_catalog_mode',
				'desc_tip' => __( 'Check to customize Add to Cart.', 'elex-catmode-rolebased-price' ),
			),
			'replace_cart_catalog_mode_text_product' => array(
				'title' => __( 'Change Button Text (Product Page)', 'elex-catmode-rolebased-price' ),
				'type' => 'text',
				'desc' => __( 'Enter a text to replace the existing Add to Cart button text on the product page.', 'elex-catmode-rolebased-price' ),
				'css' => 'width:350px',
				'id' => 'eh_pricing_discount_replace_cart_catalog_mode_text_product',
				'desc_tip' => true,
			),
			'replace_cart_catalog_mode_text_shop' => array(
				'title' => __( 'Change Button Text (Shop Page)', 'elex-catmode-rolebased-price' ),
				'type' => 'text',
				'desc' => __( 'Enter a text to replace the existing Add to Cart button text on the shop page.', 'elex-catmode-rolebased-price' ),
				'css' => 'width:350px',
				'id' => 'eh_pricing_discount_replace_cart_catalog_mode_text_shop',
				'desc_tip' => true,
			),
			'replace_cart_catalog_mode_url_shop' => array(
				'title' => __( 'Change Button URL', 'elex-catmode-rolebased-price' ),
				'type' => 'text',
				'desc' => __( 'Enter a url to redirect customers from Add to Cart button. Leave this field empty to not change the button functionality. Make sure to enter a text in the above fields to apply these changes.', 'elex-catmode-rolebased-price' ),
				'css' => 'width:350px',
				'id' => 'eh_pricing_discount_replace_cart_catalog_mode_url_shop',
				'desc_tip' => true,
			),
			
			'price_catalog_mode' => array(
				'title' => __( 'Hide Price', 'elex-catmode-rolebased-price' ),
				'type' => 'checkbox',
				'desc' => __( 'Enable', 'elex-catmode-rolebased-price' ),
				'css' => 'width:100%',
				'id' => 'eh_catalog_pricing_discount_price_catalog_mode',
				'desc_tip' => __( 'Check to hide product price. This will also remove Add to Cart button.', 'elex-catmode-rolebased-price' ),
			),
			'price_catalog_mode_text' => array(
				'title' => __( 'Placeholder Text', 'elex-catmode-rolebased-price' ),
				'type' => 'textarea',
				'desc' => __( "Enter the text you want to display when price is removed. Leave it empty if you don't want to show any placeholder text.", 'elex-catmode-rolebased-price' ),
				'css' => 'width:350px',
				'id' => 'eh_catalog_pricing_discount_price_catalog_mode_text',
				'desc_tip' => true,
			),
			'cart_catalog_mode_remove_cart_checkout' => array(
				'title' => __( 'Hide Cart and Checkout Page', 'elex-catmode-rolebased-price' ),
				'type' => 'checkbox',
				'desc' => __( 'Enable', 'elex-catmode-rolebased-price' ),
				'css' => 'width:100%',
				'id' => 'eh_pricing_discount_cart_catalog_mode_remove_cart_checkout',
				'desc_tip' => __( 'Check to disable access to Cart and Checkout pages.', 'elex-catmode-rolebased-price' ),
			),
			'price_catalog_exclude_admin' => array(
				'title' => __( 'Exclude Administrator', 'elex-catmode-rolebased-price' ),
				'type' => 'checkbox',
				'desc' => __( 'Enable', 'elex-catmode-rolebased-price' ),
				'css' => 'width:100%',
				'id' => 'eh_pricing_discount_price_catalog_mode_exclude_admin',
				'desc_tip' => __( 'Check to exclude Administrator role from the above catalog mode settings', 'elex-catmode-rolebased-price' ),
			),
			'hide_place_order_catalog'               => array(
				'title'    => __( 'Hide Place Order Button', 'elex-catmode-rolebased-price' ),
				'type'     => 'checkbox',
				'desc'     => __( 'Enable', 'elex-catmode-rolebased-price' ),
				'css'      => 'width:100%',
				'default'  => '',
				'id'       => 'eh_pricing_discount_hide_place_order_catalog',
				'desc_tip' => __( 'Check to hide Place Order button.', 'elex-catmode-rolebased-price' ),
				'custom_attributes' => array( 'disabled' => 'disabled' ),
			),
			'replace_place_order_catalog'            => array(
				'title'    => __( 'Replace Place Order Button Text ', 'elex-catmode-rolebased-price' ),
				'type'     => 'checkbox',
				'desc'     => __( 'Enable', 'elex-catmode-rolebased-price' ),
				'css'      => 'width:100%',
				'id'       => 'eh_pricing_discount_replace_place_order_catalog',
				'desc_tip' => __( 'Check to replace Place Order button. Also, please provide text to be replaced in below textbox', 'elex-catmode-rolebased-price' ),
				'custom_attributes' => array( 'disabled' => 'disabled' ),
			),
			'hide_payment_gateways_catalog'          => array(
				'title'    => __( 'Hide Payment Gateways', 'elex-catmode-rolebased-price' ),
				'type'     => 'multiselect',
				'desc'     => __( 'Select the payment gateway(s) which you want to hide in checkout page', 'elex-catmode-rolebased-price' ),
				'class'    => 'chosen_select',
				'id'       => 'eh_pricing_discount_hide_payment_gateways_catalog',
				'options'  => array(),
				'desc_tip' => true,
				'custom_attributes' => array( 'disabled' => 'disabled' ),
			),
			'catalog_settings_section_title_end' => array(
				'type' => 'sectionend',
				'id' => 'eh_pricing_discount_catalog_section_title',
			),
		);
		/**
		 * To add settings fields
		 * 
		 * @since 1.0.0
		 */
		return apply_filters( 'eh_pricing_discount_catalog_settings', $settings );
	}
	
	//function to add a prodcut tab in product page
	public function elex_cm_add_product_tab( $product_data_tabs ) {
		$product_data_tabs['product_price_adjustment_catalog'] = array(
			'label' => __( 'Catalog Mode', 'elex-catmode-rolebased-price' ),
			'target' => 'product_price_adjustment_data_catalog',
			'class' => array(),
		);
		return $product_data_tabs;
	}

	public function elex_cm_add_price_adjustment_data_fields() {
	   
		?>
		<div id="product_price_adjustment_data_catalog" class="panel woocommerce_options_panel hidden">
			<?php include( 'elex-html-product-price-adjustment_catalog.php' ); ?>
		</div>
		<?php
	}

	public function elex_cm_add_custom_general_fields_save( $post_id ) {
		$product = wc_get_product( $post_id );
		//catalog mode individual products
		if ( ! ( isset( $_POST['woocommerce_meta_nonce'] ) || wp_verify_nonce( sanitize_key( $_POST['woocommerce_meta_nonce'] ), 'woocommerce_save_data' ) ) ) { // Input var okay.
			return false;
		}
		$catalog_mode_addtocart = ( isset( $_POST['product_adjustment_hide_addtocart_catalog'] ) && ( 'on' == $_POST['product_adjustment_hide_addtocart_catalog'] ) ) ? 'yes' : 'no';
		if ( ! empty( $catalog_mode_addtocart ) ) {
			$product->update_meta_data( 'product_adjustment_hide_addtocart_catalog', $catalog_mode_addtocart );
		}
		$hide_addtocart_shop_catalog = ( isset( $_POST['product_adjustment_hide_addtocart_catalog_shop'] ) && ( 'on' == $_POST['product_adjustment_hide_addtocart_catalog_shop'] ) ) ? 'yes' : 'no';
		if ( ! empty( $hide_addtocart_shop_catalog ) ) {
			$product->update_meta_data( 'product_adjustment_hide_addtocart_catalog_shop', $hide_addtocart_shop_catalog );
		}
		$hide_addtocart_product_catalog = ( isset( $_POST['product_adjustment_hide_addtocart_catalog_product'] ) && ( 'on' == $_POST['product_adjustment_hide_addtocart_catalog_product'] ) ) ? 'yes' : 'no';
		if ( ! empty( $hide_addtocart_product_catalog ) ) {
			$product->update_meta_data( 'product_adjustment_hide_addtocart_catalog_product', $hide_addtocart_product_catalog );
		}
		$this->elex_cm_catalog_default_check_for_hide_addtocart( $post_id, 'product_adjustment_hide_addtocart_catalog_shop', 'product_adjustment_hide_addtocart_catalog_product' );
		
		if ( isset( $_POST['product_adjustment_hide_addtocart_placeholder_catalog'] ) ) {
			$product->update_meta_data( 'product_adjustment_hide_addtocart_placeholder_catalog', wp_kses_post( $_POST['product_adjustment_hide_addtocart_placeholder_catalog'] ) );
		}
		
		$customize_checkbox_catalog = ( isset( $_POST['product_adjustment_customize_addtocart_catalog'] ) && ( 'on' == $_POST['product_adjustment_customize_addtocart_catalog'] ) ) ? 'yes' : 'no';
		if ( ! empty( $customize_checkbox_catalog ) ) {
			$product->update_meta_data( 'product_adjustment_customize_addtocart_catalog', $customize_checkbox_catalog );
		}
		if ( isset( $_POST['product_adjustment_customize_addtocart_prod_btn_text_catalog'] ) ) {
			$product->update_meta_data( 'product_adjustment_customize_addtocart_prod_btn_text_catalog', sanitize_text_field( $_POST['product_adjustment_customize_addtocart_prod_btn_text_catalog'] ) );
		}
		if ( isset( $_POST['product_adjustment_customize_addtocart_shop_btn_text_catalog'] ) ) {
			$product->update_meta_data( 'product_adjustment_customize_addtocart_shop_btn_text_catalog', sanitize_text_field( $_POST['product_adjustment_customize_addtocart_shop_btn_text_catalog'] ) );
		}
		if ( isset( $_POST['product_adjustment_customize_addtocart_btn_url_catalog'] ) ) {
			$product->update_meta_data( 'product_adjustment_customize_addtocart_btn_url_catalog', sanitize_text_field( $_POST['product_adjustment_customize_addtocart_btn_url_catalog'] ) );
		}
		
		//to update product hide price for catalog
		$catalog_price = ( isset( $_POST['product_adjustment_hide_price_catalog'] ) && ( 'on' == $_POST['product_adjustment_hide_price_catalog'] ) ) ? 'yes' : 'no';
		if ( ! empty( $catalog_price ) ) {
			$product->update_meta_data( 'product_adjustment_hide_price_catalog', $catalog_price );
		}
		//to update hide price placeholder for catalog
		if ( isset( $_POST['product_adjustment_hide_price_placeholder_catalog'] ) ) {
			$product->update_meta_data( 'product_adjustment_hide_price_placeholder_catalog', wp_kses_post( $_POST['product_adjustment_hide_price_placeholder_catalog'] ) );
		}
		
		$exlude_admin_catalog = ( isset( $_POST['product_adjustment_exclude_admin_catalog'] ) && ( 'on' == $_POST['product_adjustment_exclude_admin_catalog'] ) ) ? 'yes' : 'no';
		if ( ! empty( $exlude_admin_catalog ) ) {
			$product->update_meta_data( 'product_adjustment_exclude_admin_catalog', $exlude_admin_catalog );
		}
		$product->save();
		//--------------------------------------------------------
	}
	
	public function elex_cm_catalog_default_check_for_hide_addtocart( $post_id, $meta_key_shop, $meta_key_product ) {
		$product = wc_get_product( $post_id );
		$default_check = false;
		if ( ! ( isset( $_POST['woocommerce_meta_nonce'] ) || wp_verify_nonce( sanitize_key( $_POST['woocommerce_meta_nonce'] ), 'woocommerce_save_data' ) ) ) { // Input var okay.
			return false;
		}
		if ( array_key_exists( $meta_key_shop, $_POST ) ) {
			$default_check = true;
		}
		if ( ! $default_check ) {
			$product->update_meta_data( $meta_key_shop, 'yes' );
			$product->update_meta_data( $meta_key_product, 'yes' );
		}
		$product->save();
	}

	
}

new Elex_CM_Pricing_Discount_Settings();
