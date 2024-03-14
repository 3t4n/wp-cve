<?php

/**
 * This file should be used to render each module instance.
 * You have access to two variables in this file:
 *
 * $module An instance of your module class.
 * $settings The module's settings.
 */

if ( 'yes' === $settings->image_link_nofollow ) {
	$buttonnofollow = 'rel=nofollow';
} else {
	$buttonnofollow = '';
}

// Photo outer classes
$photo_outer_classes  = 'photo-outer';
$photo_outer_classes .= ( 'custom' === $settings->image_style ) ? ' photo-custom-style' : '';

// Figure classes
$figure_classes  = 'tnit-image-item';
$figure_classes .= ( ! empty( $settings->image_style ) ) ? ' tnit-photo-style-' . $settings->image_style : '';
$figure_classes .= ( 'style-1' === $settings->overlay_style ) ? ' tnit-image-item_effect2' : '';
$figure_classes .= ( 'style-2' === $settings->overlay_style ) ? ' tnit-image-item_effect1' : '';
$figure_classes .= ( 'style-3' === $settings->overlay_style ) ? ' tnit-image-item_effect3' : '';
$figure_classes .= ( 'fadeCenter' === $settings->overlay_style ) ? ' tnit-image-cricle_effect' : '';

?>


<div class="tnit-module tnit-module-image-icon">

	<?php if ( 'photo' === $settings->image_type ) { ?>
	<div class="<?php echo esc_attr( $photo_outer_classes ); ?>">
		<figure class="<?php echo esc_attr( $figure_classes ); ?>">

			<?php if ( ! empty( $settings->image_link ) ) { ?>
				<a href="<?php echo esc_url( $settings->image_link ); ?>" target="<?php echo esc_attr( $settings->image_link_target ); ?>" <?php echo esc_attr( $buttonnofollow ); ?>>
			<?php } ?>

			<?php $module->render_image(); ?>

			<?php if ( ! empty( $settings->image_link ) ) { ?>
				</a>
			<?php } ?>

		</figure><!--Image Item End-->
	</div>
	<?php } ?>

	<?php if ( 'icon' === $settings->image_type ) { ?>
	<!--Icon Outer Start-->
	<div class="tnit-photo-icon-wrapper">
		<?php if ( ! empty( $settings->image_link ) ) { ?>
			<a href="<?php echo esc_url( $settings->image_link ); ?>" target="<?php echo esc_attr( $settings->image_link_target ); ?>" <?php echo esc_attr( $buttonnofollow ); ?>>
		<?php } ?>

		<span class="tnit-photo-icon<?php echo esc_attr( ( ! empty( $settings->icon_style ) ) ? ' tnit-photo-icon-' . $settings->icon_style : '' ); ?>">
			<i class="<?php echo esc_attr( $settings->icon ); ?>" aria-hidden="true"></i>
		</span>

		<?php if ( ! empty( $settings->image_link ) ) { ?>
			</a>
		<?php } ?>
	</div>
	<!--Icon Outer End-->
	<?php } ?>
</div>
