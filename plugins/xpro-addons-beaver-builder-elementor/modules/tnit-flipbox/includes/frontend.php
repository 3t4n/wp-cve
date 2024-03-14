<?php
/**
 * Render the frontend content.
 *
 * @package Xpro Addons
 * @sub-package Creative Flip Box module
 *
 * @since 1.0.22
 */

// General classes
$flip_classes  = 'tnit-flipbox text-center tnit-GreenColor';
$flip_classes .= ' tnit-flip-' . $settings->flip_type;
$flip_classes .= ( 'corners' === $settings->front_border_type ) ? ' flipBorderStyle' : '';

$flipbox_front_classes = 'flip-box-front';
if ( 'photo' === $settings->front_bg_type ) {
	$flipbox_front_classes .= ' flip-bgImg';
}

$flipbox_back_classes = 'flip-box-back';
if ( 'photo' === $settings->back_bg_type ) {
	$flipbox_back_classes .= ' flip-bgImg';
}
?>

<!--flipbox Start -->
<div class="<?php echo esc_attr( $flip_classes ); ?>">
	<!--Flip Box Inner Start-->
	<div class="flip-box-inner">
		<div class="<?php echo esc_attr( $flipbox_front_classes ); ?>">
			<?php
				$module->render_front_icon();
				$module->render_front_title();
				$module->render_front_description();
			?>
		</div>
		<div class="<?php echo esc_attr( $flipbox_back_classes ); ?>">
			<?php
				$module->render_back_title();
				$module->render_back_description();
				$module->render_back_button();
			?>
		</div>
	</div>
	<!--Flip Box Inner End-->

</div> <!--flipbox Start -->
