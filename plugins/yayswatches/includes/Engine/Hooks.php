<?php
namespace Yay_Swatches\Engine;

use Yay_Swatches\Utils\SingletonTrait;
use Yay_Swatches\Helpers\Helper;

class Hooks {
	use SingletonTrait;

	protected function __construct() {

		// Hooks for Attribute Type
		add_action( 'yay_swatches_attribute_radio_type', array( $this, 'attribute_radio_type' ), 10, 1 );
		add_action( 'yay_swatches_attribute_button_type', array( $this, 'attribute_button_type' ), 10, 2 );
		add_action( 'yay_swatches_attribute_custom_type', array( $this, 'attribute_custom_type' ), 10, 4 );
		add_action( 'yay_swatches_attribute_variant_image_type', array( $this, 'attribute_variant_image_type' ), 10, 4 );

	}

	public function attribute_radio_type( $attribute_data ) {
		if ( 'radio' === $attribute_data['attribute_type'] ) {
			$default_active_class = ! empty( $attribute_data['term_active_class'] ) ? $attribute_data['term_active_class'] : false;
			$radio_auto_checked   = $default_active_class ? 'checked="checked"' : '';
			$product_ID           = $attribute_data['product_id'];
			$product_term_slug    = 'yayswatches-radio-attr-' . $attribute_data['term_slug'] . '-' . $product_ID;
			?>
			<input id="<?php echo esc_attr( $product_term_slug ); ?>" data-type="radio" type="radio" <?php echo esc_attr( $radio_auto_checked ); ?> class='yay-swatches-attribute-term yay-swatches-swatch-radio <?php echo esc_attr( $default_active_class ); ?>' data-product_id='<?php echo esc_attr( $product_ID ); ?>' data-attribute='<?php echo esc_attr( $attribute_data['attribute_slug'] ); ?>' name="yay-swatches-radio-<?php echo esc_attr( $attribute_data['attribute_slug'] ); ?>" data-term="<?php echo esc_attr( $attribute_data['term_slug'] ); ?>" >
			<span class='yay-swatches-radio-label'><?php echo esc_html( $attribute_data['term_name'] ); ?></span>
			<?php
		}

	}

	public function attribute_button_type( $attribute_data, $data_styles ) {
		if ( 'button' === $attribute_data['attribute_type'] ) {
			$default_active_class = $attribute_data['term_active_class'];
			?>
				<span style="<?php echo esc_attr( $data_styles ); ?>" class="yay-swatches-attribute-term yay-swatches-button <?php echo esc_attr( $default_active_class ); ?>" data-product_id="<?php echo esc_attr( $attribute_data['product_id'] ); ?>" data-attribute="<?php echo esc_attr( $attribute_data['attribute_slug'] ); ?>" data-term="<?php echo esc_attr( $attribute_data['term_slug'] ); ?>" data-label-text="<?php echo esc_attr( $attribute_data['term_name'] ); ?>"><?php echo esc_html( $attribute_data['term_name'] ); ?></span>
			<?php
		}
	}

	public function attribute_custom_type( $term_id, $attribute_data, $data_styles, $styles ) {
		if ( 'custom' === $attribute_data['attribute_type'] ) {
			$swatch_color       = get_option( 'yay-swatches-swatch-color-' . $term_id, '#2271b1' );
			$swatch_show_hide   = get_option( 'yay-swatches-show-hide-color-' . $term_id, false );
			$swatch_dual_color  = get_option( 'yay-swatches-swatch-dual-color-' . $term_id, '#fcd00a' );
			$swatch_image       = get_option( 'yay-swatches-swatch-image-' . $term_id, '' );
			$active_dual_color  = ( '1' === strtolower( $swatch_show_hide ) || 'true' === strtolower( $swatch_show_hide ) ) ? ' has-dual-color' : '';
			$data_custom_type   = array(
				'swatch_image'      => $swatch_image,
				'swatch_show_hide'  => $swatch_show_hide,
				'swatch_color'      => $swatch_color,
				'swatch_dual_color' => $swatch_dual_color,
			);
			$custom_data_styles = $data_styles . Helper::get_color_style( $styles, $data_custom_type );
			?>
			<span style="<?php echo esc_attr( $custom_data_styles ); ?>" class="yay-swatches-attribute-term yay-swatches-swatch <?php echo esc_attr( $attribute_data['term_active_class'] ); ?> <?php echo esc_attr( $active_dual_color ); ?>" data-product_id="<?php echo esc_attr( $attribute_data['product_id'] ); ?>" data-attribute="<?php echo esc_attr( $attribute_data['attribute_slug'] ); ?>" data-term="<?php echo esc_attr( $attribute_data['term_slug'] ); ?>" data-color="<?php echo esc_attr( $swatch_color ); ?>" data-show-hide-dual="<?php echo esc_attr( $swatch_show_hide ); ?>" data-dual-color="<?php echo esc_attr( $swatch_dual_color ); ?>" data-image="<?php echo esc_attr( $swatch_image ); ?>"  data-tippy-content="<?php echo esc_attr( $attribute_data['term_name'] ); ?>"  data-label-text="<?php echo esc_attr( $attribute_data['term_name'] ); ?>"></span>
			<?php
		}
	}

	public function attribute_variant_image_type( $attribute_data, $data_styles, $variations, $swatch_customize_settings ) {
		if ( 'variant_image' === $attribute_data['attribute_type'] ) {
			$terms_img_id       = Helper::get_image_id_by_variation_id( $variations, $attribute_data );
			$variant_image_size = $swatch_customize_settings['imageSize'];
			$variant_image_url  = $terms_img_id ? wp_get_attachment_image_url( $terms_img_id, $variant_image_size ) : ( get_the_post_thumbnail_url( $attribute_data['product_id'], $variant_image_size ) ? get_the_post_thumbnail_url( $attribute_data['product_id'], $variant_image_size ) : wc_placeholder_img_src( $variant_image_size ) );
			?>
			<span style="<?php echo esc_attr( $data_styles ); ?>" class="yay-swatches-attribute-term yay-swatches-swatch-variant-image <?php echo esc_attr( $attribute_data['term_active_class'] ); ?>" data-product_id="<?php echo esc_attr( $attribute_data['product_id'] ); ?>" data-attribute="<?php echo esc_attr( $attribute_data['attribute_slug'] ); ?>" data-term="<?php echo esc_attr( $attribute_data['term_slug'] ); ?>" data-tippy-content="<?php echo esc_attr( $attribute_data['term_name'] ); ?>" data-label-text="<?php echo esc_attr( $attribute_data['term_name'] ); ?>"><img class="yay-swatches-variant-img"  alt="<?php echo esc_attr( $attribute_data['term_name'] ); ?>" src="<?php echo esc_url( $variant_image_url ); ?>" ></span>
			<?php
		}
	}

}
