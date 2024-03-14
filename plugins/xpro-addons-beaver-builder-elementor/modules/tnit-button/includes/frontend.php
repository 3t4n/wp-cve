<?php
/**
 * Render the frontend content.
 *
 * $module An instance of your module class.
 * $settings The module's settings.
 *
 */

if ( 'yes' === $settings->button_link_nofollow ) {
	$buttonnofollow = 'rel=nofollow';
} else {
	$buttonnofollow = '';
}

// Button classes
$button_classes  = 'tnit-btn-standard tnit-bb-btn-bgRed';
$button_classes .= ( 'effect-1' === $settings->hover_effect_style ) ? ' tnit-btn-effect1' : '';
$button_classes .= ( 'effect-2' === $settings->hover_effect_style ) ? ' tnit-btn-effect2' : '';
$button_classes .= ( 'effect-3' === $settings->hover_effect_style ) ? ' tnit-btn-effect1 tnit-btn-fill-effect1' : '';
$button_classes .= ( 'effect-4' === $settings->hover_effect_style ) ? ' tnit-btn-effect2 tnit-btn-fill-effect2' : '';
$button_classes .= ' tnit-btn-bg-' . $settings->color_type;
$button_classes .= ' tnit-btn-bg-hover-' . $settings->hover_color_type;

// Icon classes
$icon_class  = 'tnit-btn-bb-icon';
$icon_class .= ( 'before' === $settings->icon_position ) ? ' tnit-btn-icon-before' : '';
$icon_class .= ( 'after' === $settings->icon_position ) ? ' tnit-btn-icon-after' : '';
$icon_class .= ( 'outer_left' === $settings->icon_position ) ? ' tnit-btn-bb-iconLeft' : '';
$icon_class .= ( 'outer_right' === $settings->icon_position ) ? ' tnit-btn-bb-iconRight' : '';
$icon_class .= ' tnit-button-icon-' . $settings->icon_style;

?>

<div class="tnit-bb-button-outer">
	<?php if ( 'outer_left' === $settings->icon_position || 'outer_right' === $settings->icon_position ) { ?>
		<div class="tnit-btn-standard-IconBox tnit-icon-bgBlueLight">
	<?php } ?>

		<a href="<?php echo esc_url( $settings->button_link ); ?>" target="<?php echo esc_attr( $settings->button_link_target ); ?>" <?php echo esc_attr( $buttonnofollow ); ?> class="<?php echo esc_attr( $button_classes ); ?>">

			<?php if ( 'yes' === $settings->icon_show && 'after' !== $settings->icon_position ) { ?>
				<span class="<?php echo esc_attr( $icon_class ); ?>">
					<i class="<?php echo esc_attr( $settings->icon ); ?>" aria-hidden="true"></i>
				</span>
			<?php } ?>

			<span class="btn-text"><?php echo esc_attr( $settings->button_text ); ?></span>

			<?php if ( 'yes' === $settings->icon_show && 'after' === $settings->icon_position ) { ?>
				<span class="<?php echo esc_attr( $icon_class ); ?>">
					<i class="<?php echo esc_attr( $settings->icon ); ?>" aria-hidden="true"></i>
				</span>
			<?php } ?>
		</a>
	<?php if ( 'outer_left' === $settings->icon_position || 'outer_right' === $settings->icon_position ) { ?>
		</div>
	<?php } ?>

</div>
