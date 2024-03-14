<?php
/**
 * Render the frontend content.
 *
 * @package Beaver Builder plugins
 * @sub-package Advance InfoBox module
 *
 * @since 1.0.25
 */

$infobox_class = 'tnit-infoBox';

if ( 'none' !== $settings->image_type && ( 'left' === $settings->imgicon_position || 'right' === $settings->imgicon_position ) ) {
	$infobox_class .= ' tnit-img-position tnit-img-position-' . $settings->imgicon_position;
}

?>

<div class="tnit-infobox-holder">
	<div class="<?php echo esc_attr( $infobox_class ); ?>">
		<?php
		$module->render_box_link();
		if ( 'none' !== $settings->image_type ) {
			?>

			<div class="tnit-infobox-imgicon-wrap">

				<?php
				$module->render_icon();
				$module->render_photo();
				?>

			</div>

		<?php } ?>

		<?php

		if ( 'none' !== $settings->image_type && ( 'left' === $settings->imgicon_position || 'right' === $settings->imgicon_position ) ) {
			echo '<div class="tnit-infobox-content">';
		}

			$module->render_separator( 'above_prefix' );
			$module->render_title_prefix();
			$module->render_separator( 'below_prefix' );
			$module->render_title();
			$module->render_separator( 'below_title' );
			$module->render_title_postfix();
			$module->render_separator( 'below_postfix' );
			$module->render_description();
			$module->render_separator( 'below_desc' );
			$module->render_button();

		if ( 'none' !== $settings->image_type && ( 'left' === $settings->imgicon_position || 'right' === $settings->imgicon_position ) ) {
			echo '</div>';
		}
		?>

	</div>
</div>
