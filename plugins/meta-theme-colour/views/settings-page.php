<?php
/**
 * Created by PhpStorm.
 * User: Richard
 * Date: 2016/04/24
 * Time: 8:52 PM
 */
?>
<div class="wrap">
	<h2><?php _e( 'Meta Theme Colour Setter', 'meta-theme-colour' ); ?></h2>

	<p><?php
		_e(
			'This plugin allows you to set the colour of the address bar on mobile devices.',
			'meta-theme-colour'
		)
		?></p>

	<form method="post" action="options.php">
		<?php settings_fields( 'meta-theme-colour-group' ); ?>
		<?php do_settings_sections( 'meta-theme-colour-group' ); ?>
		<table class="form-table">
			<tr valign="top">
				<?php do_settings_fields( 'meta_theme_colour', 'main' ); ?>
			</tr>

		</table>

		<?php submit_button(); ?>

	</form>

</div>