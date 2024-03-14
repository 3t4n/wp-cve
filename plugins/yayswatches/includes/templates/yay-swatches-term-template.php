<?php
defined( 'ABSPATH' ) || exit;
use Yay_Swatches\Helpers\Helper;
$data_styles               = '';
$slug_terms                = $terms_taxonomy ? $terms_taxonomy : Helper::get_terms_attribute_not_exists( $attribute, $args['options'], $product );
$swatch_customize_settings = get_option( 'yay-swatches-swatch-customize-settings', $this->default_swatch_customize_settings );
$button_customize_settings = get_option( 'yay-swatches-button-customize-settings', $this->default_button_customize_settings );
$default_selected_term     = $args['selected'];
$allow_html                = Helper::get_allow_html();
?>
<div class="yay-swatch-variant-default-wrapper"><?php echo wp_kses( $html, $allow_html ); ?></div>
<?php
$yay_swatches_class = '';
if ( 'button' === $attribute_type ) {
	$styles       = $button_customize_settings;
	$data_styles .= Helper::get_button_style( $styles );
}
if ( 'custom' === $attribute_type || 'variant_image' === $attribute_type ) {
	$styles             = $swatch_customize_settings;
	$data_styles       .= Helper::get_image_swatch_style( $styles );
	$yay_swatches_class = 'yay-swatch-wrapper-class';
}
?>
<div data-attribute_type="<?php echo esc_attr( $attribute_type ); ?>" data-show-tooltip='<?php echo ( isset( $styles['swatchTooltip'] ) && 'enable' === $styles['swatchTooltip'] ? 'yes' : 'no' ); ?>' class='yay-variant-wrapper <?php echo esc_attr( $yay_swatches_class ); ?>'>
<?php
$variations = $product->get_children();
foreach ( $slug_terms as $key => $yay_term ) {
	$yay_term_slug = $terms_taxonomy ? $yay_term->slug : $yay_term;
	if ( in_array( $yay_term_slug, $args['options'], true ) ) {
		$term_id = $terms_taxonomy ? ( isset( $yay_term->term_id ) ? $yay_term->term_id : false ) : $yay_term;

		$attribute_data = array(
			'product_id'        => $product_ID,
			'attribute_type'    => $attribute_type,
			'attribute_slug'    => $attribute_slug,
			'term_name'         => $terms_taxonomy ? $yay_term->name : $yay_term,
			'term_slug'         => $yay_term_slug,
			'term_active_class' => $default_selected_term === $yay_term_slug ? 'yay-swatches-active' : '',
		);

		if ( $term_id ) :

			// radio
			do_action( 'yay_swatches_attribute_radio_type', $attribute_data );

			// button
			do_action( 'yay_swatches_attribute_button_type', $attribute_data, $data_styles );

			// custom
			do_action( 'yay_swatches_attribute_custom_type', $term_id, $attribute_data, $data_styles, $styles );

			// variant image
			do_action( 'yay_swatches_attribute_variant_image_type', $attribute_data, $data_styles, $variations, $swatch_customize_settings );

		endif;
	}
}
?>
</div>
