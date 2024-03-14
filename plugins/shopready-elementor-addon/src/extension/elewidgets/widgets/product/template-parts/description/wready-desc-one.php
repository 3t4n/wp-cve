<?php

/**
 * Product Desc Default layouts
 *
 * @since 1.0
 * @author quomodosoft.com
 * shop_ready_is_elementor_mode()
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$tag = $settings['title_tag'];
$id  = get_the_id();

if ( shop_ready_is_elementor_mode() ) {

	if ( $settings['wready_product_id'] != '' ) {
		$id = $settings['wready_product_id'];
	}
}

global $product;
$product_instance = is_null( $product ) ? wc_get_product( $id ) : $product;
/*Icon Animation*/
if ( $settings['icon_hover_animation'] ) {
	$icon_animation = 'elementor-animation-' . $settings['icon_hover_animation'];
} else {
	$icon_animation = '';
}

/*Icon Condition*/
if ( 'yes' == $settings['show_icon'] ) {
	if ( 'font_icon' == $settings['icon_type'] && ! empty( $settings['font_icon'] ) ) {
		$icon = '<div class="area__icon ' . esc_attr( $icon_animation ) . '">' . shop_ready_render_icons( $settings['font_icon'] ) . '</div>';
	} elseif ( 'image_icon' == $settings['icon_type'] && ! empty( $settings['image_icon'] ) ) {
		$icon_array = $settings['image_icon'];
		$icon_link  = wp_get_attachment_image_url( $icon_array['id'], 'thumbnail' );
		$icon       = '<div class="area__icon ' . esc_attr( $icon_animation ) . '"><img src="' . esc_url( $icon_link ) . '" alt="" /></div>';
	}
} else {
	$icon = '';
}

/*Background Text*/
if ( ! empty( $settings['title_bg_text'] ) ) {
	$title_bg_text = '<div class="desc__bg__text">' . esc_html( $settings['title_bg_text'] ) . '</div>';
} else {
	$title_bg_text = '';
}

/*Tag*/
if ( ! empty( $settings['title_tag'] ) ) {
	$title_tag = $settings['title_tag'];
} else {
	$title_tag = 'div';
}

/*Description*/
if ( $product_instance && method_exists( $product_instance, 'get_short_description' ) ) {
	$description = '<' . $title_tag . ' class="area__desc">' . wpautop( wp_trim_words( $product_instance->get_short_description(), $settings['description_limit'], '' ) ) . '</' . $title_tag . '>';
} else {
	$description = '';
}

echo wp_kses_post('<div class="area__content">'); ?>
<?php if ( 'yes' == $settings['show_bg_icon'] ) : ?>
<?php if ( 'font_icon' == $settings['bg_icon_type'] && ! empty( $settings['bg_font_or_svg'] ) ) : ?>
<div class="desc__bg__icon">
    <?php echo wp_kses_post(shop_ready_render_icons( $settings['bg_font_or_svg'] )); ?>
</div>
<?php elseif ( 'image_icon' == $settings['bg_icon_type'] && ! empty( $settings['bg_image_icon'] ) ) : ?>
<?php
		$icon_array = $settings['bg_image_icon'];
		$icon_link  = wp_get_attachment_image_url( $icon_array['id'], 'thumbnail' );
		echo wp_kses_post( '<div class="title__bg__icon"><img src="' . esc_url( $icon_link ) . '" alt="" /></div>' );
		?>
<?php endif; ?>
<?php
endif;
echo wp_kses_post(
	'' . ( isset( $title_bg_text ) ? $title_bg_text : '' ) . '
				' . ( isset( $icon ) ? $icon : '' ) . '
				' . ( isset( $description ) ? $description : '' ) . ''
);
echo wp_kses_post('</div>');