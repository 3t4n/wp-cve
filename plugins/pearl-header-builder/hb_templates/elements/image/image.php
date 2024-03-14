<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>

<?php
$image_id = $image = '';
/*Get image id*/
if(!empty($element['data']['uselogo']) and stm_hb_check_string($element['data']['uselogo'])) {
	$image_id = stm_hb_get_option('logo', false, $element['pearl_header_builder']);
} elseif(!empty($element['value'])) {
	$image_id = $element['value'];
}

/*Default width*/
$attrs = array();
$size = 'full';
if(!empty($element['data']['width'])) {
	$attrs['style'] = 'width:' . intval($element['data']['width']) . 'px';
}

/*Get image*/
$image = wp_get_attachment_image($image_id, 'full', false, $attrs);

/*Get url*/
$url = get_home_url();
if(!empty($element['data']['url'])) {
	$url = $element['data']['url'];
}



if(!empty($image)):
    $image_alt = get_post_meta( $image_id, '_wp_attachment_image_alt', true);
    ?>

	<div class="stm-logo">
		<a href="<?php echo esc_url($url); ?>" title="<?php echo esc_attr($image_alt); ?>">
			<?php echo wp_kses_post($image); ?>
		</a>
	</div>

<?php endif; ?>