<?php

/**
 * Class WOO_F_LOOKBOOK_Frontend_Product
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WOO_F_LOOKBOOK_Frontend_Product {
	protected $settings;
	protected $data;

	public function __construct() {
		add_action( 'wp_head', array( $this, 'load' ) );
	}

	public function load() {

		if ( get_post_type() == 'product' && is_single() ) {
			global $post;
			$enable = $this->get_data( $post->ID, 'enable', 0 );
			if ( ! $enable ) {
				return;
			}
			$position = $this->get_data( $post->ID, 'position', 0 );
			switch ( $position ) {
				case 1:
					add_action( 'woocommerce_after_single_product_summary', array( $this, 'show_lookbooks_html' ), 11 );
					break;
				default:
					add_action( 'woocommerce_after_single_product_summary', array( $this, 'show_lookbooks_html' ), 9 );
			}
		}
	}

	/**
	 * Show lookbook HTML on product page
	 */
	public function show_lookbooks_html() {
		global $post;
		$lookbooks = $this->get_data( $post->ID, 'lookbooks', array() );
		if ( is_array( $lookbooks ) && count( array_filter( $lookbooks ) ) ) {
			/*Check Algin center or left or right*/
			$align = $this->get_data( $post->ID, 'align', 0 );
			switch ( $align ) {
				case 1:
					$class = 'wlb-align-left';
					break;
				case 2:
					$class = 'wlb-align-right';
					break;
				default:
					$class = 'wlb-align-center';
			}
			/*Check Shortcode Single image or Slides*/
			$shortcode_type = $this->get_data( $post->ID, 'shortcode_type', 0 );

			switch ( $shortcode_type ) {
				case 1:
					?>
					<div class="<?php echo esc_attr( $class ) ?>">
						<?php echo do_shortcode( '[woocommerce_lookbook_slide id="' . implode( ',', $lookbooks ) . '"]' ); ?>
					</div>
					<?php break;
				default:
					?>
					<div class="wlb-single-lookbook <?php echo esc_attr( $class ) ?>">
						<?php echo do_shortcode( '[woocommerce_lookbook id="' . implode( ',', $lookbooks ) . '"]' ); ?>
					</div>
				<?php }
		}
	}

	/**
	 * Get Post Meta
	 *
	 * @param $field
	 *
	 * @return bool
	 */
	private function get_data( $post_id, $field, $default = '' ) {

		if ( $this->data ) {
			$params = $this->data;
		} else {
			$this->data = get_post_meta( $post_id, 'wlb_params', true );
			$params     = $this->data;
		}
		if ( isset( $params[$field] ) && $field ) {
			return $params[$field];
		} else {
			return $default;
		}
	}
}