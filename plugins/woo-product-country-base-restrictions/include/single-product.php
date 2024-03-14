<?php
/**
 * CBR Setting 
 *
 * @class   CBR_Single_Product
 * @package WooCommerce/Classes
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * CBR_Single_Product class
 *
 * @since 1.0.0
 */
class CBR_Single_Product {
	
	/**
	 * Get the class instance
	 *
	 * @since  1.0.0
	 * @return CBR_Single_Product
	*/
	public static function get_instance() {

		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Instance of this class.
	 *
	 * @since 1.0.0
	 * @var object Class Instance
	*/
	private static $instance;
	
	/*
	* construct function
	*
	* @since 1.0.0
	*/
	public function __construct() {
		$this->init();
	}

	/*
	* init function
	*
	* @since 1.0.0
	*/
	public function init() {
		
		//hook for custom product meta field
		add_action( 'woocommerce_process_product_meta', array( $this, 'save_custom_product_fields' ) );
		add_action( 'woocommerce_product_data_panels', array( $this, 'add_custom_product_fields' ) );
		add_filter( 'woocommerce_product_data_tabs', array( $this, 'add_cbr_product_data_tab'), 99 , 1 );
		
		add_action( 'woocommerce_product_after_variable_attributes', array( $this, 'add_custom_variation_fields'), 10, 3 );
		add_action( 'woocommerce_save_product_variation', array( $this, 'save_custom_variation_fields'), 10, 2 );
			
	}
	
	/*
	* Adding a CBR settings tab to the Products Metabox
	*
	* @since 1.0.0
	*/
	public function add_cbr_product_data_tab( $product_data_tabs ) {
		?>
		<style>
		#woocommerce-product-data ul.wc-tabs li.cbr_tab a::before {
			content: "\f319";
		}
		.woocommerce_options_panel .restricted_countries .select2-container {
			max-width: 350px !important;
		}
		.woocommerce_options_panel .restricted_countries .select2-selection {
			min-height: 63px;
		}
		</style>
		<?php
		$product_data_tabs['cbr'] = array(
			'label' => __( 'Country Restrictions', 'woo-product-country-base-restrictions' ), // translatable
			'target' => 'cbr_product_data', // translatable
		);
		return $product_data_tabs;
	}
	
	/*
	* Adding a CBR settings fields to the CBR settings for all product type
	*
	* @since 1.0.0
	* @para $post
	*/
	public function add_custom_product_fields() {
		global $post;
		echo '<div id="cbr_product_data" class="panel woocommerce_options_panel hidden">';
		
		echo '<div class="options_group"><h4 style="padding-left: 12px;font-size: 14px;">Country Based Restrictions</h4>';

		woocommerce_wp_select(
			array(
				'id'      => '_fz_country_restriction_type',
				'label'   => __( 'Restriction rule', 'woo-product-country-base-restrictions' ),
				'default'       => 'all',
				'style'			=> 'max-width:350px;width:100%;',
				'class'         => 'availability cbr_restricted_type',
				'options'       => array(
					'all'       => __( 'Product Available for all countries', 'woo-product-country-base-restrictions' ),
					'specific'  => __( 'Product Available for selected countries', 'woo-product-country-base-restrictions' ),
					'excluded'  => __( 'Product not Available for selected countries', 'woo-product-country-base-restrictions' ),
				)
			)
		);

		$selections = get_post_meta( $post->ID, '_restricted_countries', true );

		if (empty($selections) || ! is_array($selections)) { 
			$selections = array(); 
		}
		$countries_obj   = new WC_Countries();
		$countries   = $countries_obj->__get('countries');
		asort( $countries );
		?>
		<p class="form-field forminp restricted_countries">
		<label for="_restricted_countries"><?php echo esc_html( 'Select countries', 'woo-product-country-base-restrictions' ); ?></label>
		<select id="_restricted_countries" multiple="multiple" name="_restricted_countries[]" style="width:100%;max-width: 350px;"
			data-placeholder="<?php esc_attr_e( 'Choose countries&hellip;', 'woocommerce' ); ?>" title="<?php esc_attr_e( 'Country', 'woocommerce' ); ?>"
			class="wc-enhanced-select" >
			<?php
			if ( ! empty( $countries ) ) {
				foreach ( $countries as $key => $val ) {
					echo '<option value="' . esc_attr( $key ) . '" ' . selected( in_array( $key, $selections ), true, false ) . '>' . esc_html($val) . '</option>';
				}
			}
			?>
		</select>
		</p>
		<?php
		if ( empty( $countries ) ) {
			echo '<p><b>' . esc_html( 'You need to setup shipping locations in WooCommerce settings ', 'woo-product-country-base-restrictions') . " <a href='admin.php?page=wc-settings'> " . esc_html( 'HERE', 'woo-product-country-base-restrictions' ) . '</a> ' . esc_html( 'before you can choose country restrictions', 'woo-product-country-base-restrictions' ) . '</b></p>';
		}
		echo "<p>You can set the general products visibility rules on the <a href='" . esc_url(admin_url('admin.php?page=woocommerce-product-country-base-restrictions&tab=settings')) . "' target='_blank'>[CBR settings]</a></p>";
		echo '</div>';
		echo '</div>';
	}
	
	/*
	* Adding a CBR settings fields to the CBR settings for all variation type products	
	*
	* @since 1.0.0
	* @para $loop, $variation_data, $variation
	*/
	public function add_custom_variation_fields( $loop, $variation_data, $variation ) {

		woocommerce_wp_select(
			array(
				'id'      => '_fz_country_restriction_type[' . $variation->ID . ']',
				'label'   => __( 'Restriction rule', 'woo-product-country-base-restrictions' ),
				'default'       => 'all',
				'class'         => 'availability cbr_restricted_type wc-enhanced-select',
				'style'			=> 'max-width:350px;width:100%;',
				'value'         => get_post_meta( $variation->ID, '_fz_country_restriction_type', true ),
				'options'       => array(
					'all'       => __( 'Product Available for all countries', 'woo-product-country-base-restrictions' ),
					'specific'  => __( 'Product Available for selected countries', 'woo-product-country-base-restrictions' ),
					'excluded'  => __( 'Product not Available for selected countries', 'woo-product-country-base-restrictions' ),
				)
			) 
		);

		$selections = get_post_meta( $variation->ID, '_restricted_countries', true );
		if (empty($selections) || ! is_array($selections)) { 
			$selections = array(); 
		}
		$countries_obj   = new WC_Countries();
		$countries   = $countries_obj->__get('countries');
		asort( $countries );
		?>
		<p class="form-field forminp restricted_countries">
		<label for="_restricted_countries[<?php echo esc_html($variation->ID); ?>]"><?php echo esc_html( 'Select countries', 'woo-product-country-base-restrictions' ); ?></label>
		<select multiple="multiple" name="_restricted_countries[<?php echo esc_html($variation->ID); ?>][]" style="width:100%;max-width: 350px;"
			data-placeholder="<?php esc_attr_e( 'Choose countries&hellip;', 'woocommerce' ); ?>" title="<?php esc_attr_e( 'Country', 'woocommerce' ); ?>"
			class="wc-enhanced-select">
		<?php	
		if ( ! empty( $countries ) ) {
			foreach ( $countries as $key => $val ) {
				echo '<option value="' . esc_attr( $key ) . '" ' . selected( in_array( $key, $selections ), true, false ) . '>' . esc_html($val) . '</option>';
			}
		}
		?>
		</select>
		</p>
		<?php            
	}
	
	/*
	* Save the product meta settings for simple product 
	*
	* @since 1.0.0
	* @para $post_id
	*/
	public function save_custom_product_fields( $post_id ) {
		$restriction = isset($_POST['_fz_country_restriction_type']) ? sanitize_text_field($_POST['_fz_country_restriction_type']) : '';
		if (! is_array($restriction)) {

			if ( !isset( $_POST['_restricted_countries'] ) || empty( $_POST['_restricted_countries'] ) ) {
				update_post_meta( $post_id, '_fz_country_restriction_type', 'all' );
			} else {
				if ( !empty( $restriction ) ) {
					update_post_meta( $post_id, '_fz_country_restriction_type', $restriction );
				}
			}
			
			$countries = array();
			
			if (isset($_POST['_restricted_countries'])) {
				$countries = isset($_POST['_restricted_countries']) ? wc_clean( $_POST['_restricted_countries'] ) : '';
			}
			
			update_post_meta( $post_id, '_restricted_countries', $countries );
		}
	}
	
	/*
	* Save the product meta settings for variation product 
	*
	* @since 1.0.0
	* @para $post_id
	*/
	public function save_custom_variation_fields( $post_id ) {
		$restriction = isset($_POST['_fz_country_restriction_type'][ $post_id ]) ? sanitize_text_field($_POST['_fz_country_restriction_type'][ $post_id ]) : '';
		
		if ( !isset( $_POST['_restricted_countries'] ) || empty( $_POST['_restricted_countries'] ) ) {
			update_post_meta( $post_id, '_fz_country_restriction_type', 'all' );
		} else {
			if ( !empty( $restriction ) ) {
				update_post_meta( $post_id, '_fz_country_restriction_type', $restriction );
			}
		}

		$countries = array();
		if (isset($_POST['_restricted_countries'])) {
			$countries = isset( $_POST['_restricted_countries'][ $post_id ] ) ? wc_clean( $_POST['_restricted_countries'][ $post_id ] ) : '';
		}
		update_post_meta( $post_id, '_restricted_countries', $countries );
	}
	
}

