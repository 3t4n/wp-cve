<?php

/**
 * Class BWFAN_WC_Order_Shipping_Country
 *
 * Merge tag outputs order shipping country
 *
 * Since 2.0.6
 */
class BWFAN_WC_Order_Shipping_Country extends BWFAN_Merge_Tag {

	private static $instance = null;

	public function __construct() {
		$this->tag_name        = 'order_shipping_country';
		$this->tag_description = __( 'Order Shipping Country', 'wp-marketing-automations' );
		add_shortcode( 'bwfan_order_shipping_country', array( $this, 'parse_shortcode' ) );
	}

	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Show the html in popup for the merge tag.
	 */
	public function get_view() {
		$this->get_back_button();
		$this->get_country_format_html();

		if ( $this->support_fallback ) {
			$this->get_fallback();
		}

		$this->get_preview();
		$this->get_copy_button();
	}

	public function get_country_format_html() {
		$templates = array(
			'iso'  => __( 'ISO code 2 digit', 'wp-marketing-automations' ),
			'full' => __( 'Nice name', 'wp-marketing-automations' ),
		);
		?>
        <label for="" class="bwfan-label-title"><?php esc_html_e( 'Format', 'wp-marketing-automations' ); ?></label>
        <select id="" class="bwfan-input-wrapper bwfan-mb-15 bwfan_tag_select" style="padding-left:10px;" name="format" required>
			<?php
			foreach ( $templates as $slug => $name ) {
				echo '<option value="' . esc_attr__( $slug ) . '">' . esc_html__( $name ) . '</option>';
			}
			?>
        </select>
		<?php
	}

	/**
	 * Parse the merge tag and return its value.
	 *
	 * @param $attr
	 *
	 * @return mixed|string|void
	 */
	public function parse_shortcode( $attr ) {
		if ( true === BWFAN_Merge_Tag_Loader::get_data( 'is_preview' ) ) {
			return $this->parse_shortcode_output( $this->get_dummy_preview(), $attr );
		}

		$order_id = BWFAN_Merge_Tag_Loader::get_data( 'order_id' );
		$order 	= wc_get_order( $order_id );

		if ( ! $order instanceof WC_Order ) {
			return $this->parse_shortcode_output( '', $attr );
		}

		$country_slug = BWFAN_Woocommerce_Compatibility::get_shipping_country_from_order( $order );
		$format       = isset( $attr['format'] ) ? $attr['format'] : 'iso';
		if ( 'iso' === $format ) {
			return $this->parse_shortcode_output( $country_slug, $attr );
		}

		$countries = WC()->countries->get_countries();
		$country   = isset( $countries[ $country_slug ] ) ? $countries[ $country_slug ] : $country_slug;

		return $this->parse_shortcode_output( $country, $attr );
	}

	/**
	 * Show dummy value of the current merge tag.
	 *
	 * @return string
	 */
	public function get_dummy_preview() {
		return 'US';
	}
}

/**
 * Register this merge tag to a group.
 */
if ( bwfan_is_woocommerce_active() ) {
	BWFAN_Merge_Tag_Loader::register( 'wc_order', 'BWFAN_WC_Order_Shipping_Country', null, 'Order' );
}
