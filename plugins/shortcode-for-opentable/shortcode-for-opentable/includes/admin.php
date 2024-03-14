<?php
/**
 * Admin
 */

( defined( 'ABSPATH' ) || is_admin() ) || exit;

/**
 * Register admin scripts.
 */
function sot_admin_assets() {

	wp_register_script(
		'sot-admin',
		esc_url( SOT_URL . 'shortcode-for-opentable/assets/js/admin.js' ),
		array( 'jquery' ),
		SOT_VERSION,
		true
	);

}
add_action( 'admin_enqueue_scripts', 'sot_admin_assets' );

/**
 * Shortcode insert button.
 */
function sot_insert_button() {

	// Enqueue ThickBox styles and scripts
	add_thickbox();

	// Enqueue SOT admin JS
	wp_enqueue_script( 'sot-admin' );

	?>

	<a href="#TB_inline&inlineId=sot-modal" class="button thickbox" data-editor="content"><span class="wp-media-buttons-icon dashicons dashicons-feedback"></span> <?php esc_html_e( 'Insert OpenTable Widget', 'shortcode-for-opentable' ); ?></a>

	<?php

}
add_action( 'media_buttons', 'sot_insert_button' );

/**
 * Shortcode generator modal.
 */
function sot_modal() {

	// Save allowed markup for radio labels
	$allowed_label_markup = array( 'em' => array(), 'strong' => array() );

	?>

	<div id="sot-modal" style="display: none;">
		<h2><?php echo esc_html_x( 'Insert OpenTable Widget', 'widget config modal heading', 'shortcode-for-opentable' ); ?></h2>
		<p><?php esc_html_e( 'Use the form below to configure your OpenTable Widget.', 'shortcode-for-opentable' ); ?></p>

		<table id="sot-form" class="form-table">
			<tbody>
				<tr>
					<th scope="row"><?php echo esc_html_x( 'Restaurant ID', 'widget restaurant heading', 'shortcode-for-opentable' ); ?></th>

					<td>
						<input type="text" id="sot-restaurant-id" class="regular-text" value="">
					</td>
				</tr>

				<tr>
					<th scope="row"><?php echo esc_html_x( 'Language', 'widget language heading', 'shortcode-for-opentable' ); ?></th>

					<td>
						<select id="sot-language">
							<option value="en" selected>English</option>
							<option value="fr">Français</option>
							<option value="es">Español</option>
							<option value="de">Deutsch</option>
							<option value="ja">日本語</option>
						</select>
					</td>
				</tr>

				<tr>
					<th scope="row"><?php echo esc_html_x( 'Type', 'widget type heading', 'shortcode-for-opentable' ); ?></th>

					<td>
						<fieldset id="sot-type">
							<legend class="screen-reader-text"><?php echo esc_html_x( 'Type', 'widget type heading', 'shortcode-for-opentable' ); ?></legend>

							<label>
								<input type="radio" name="sot-type" value="standard" checked>

								<?php echo wp_kses(
									_x( '<strong>Standard</strong> (224&times;289 pixels)', 'standard widget type label', 'shortcode-for-opentable' ),
									$allowed_label_markup
								); ?>
							</label>

							<br>

							<label>
								<input type="radio" name="sot-type" value="tall">

								<?php echo wp_kses(
									_x( '<strong>Tall</strong> (280&times;477 pixels)', 'tall widget type label', 'shortcode-for-opentable' ),
									$allowed_label_markup
								); ?>
							</label>

							<br>

							<label>
								<input type="radio" name="sot-type" value="wide">

								<?php echo wp_kses(
									_x( '<strong>Wide</strong> (832&times;154 pixels)', 'wide widget type label', 'shortcode-for-opentable' ),
									$allowed_label_markup
								); ?>
							</label>

							<br>

							<label>
								<input type="radio" name="sot-type" value="button">

								<?php echo wp_kses(
									_x( '<strong>Button</strong> (210&times;106 pixels)', 'button widget type label', 'shortcode-for-opentable' ),
									$allowed_label_markup
								); ?>
							</label>
						</fieldset>
					</td>
				</tr>
			</tbody>
		</table>

		<p><button id="sot-insert" class="button button-primary"><?php echo esc_html_x( 'Insert Shortcode', 'insert shortcode button label', 'shortcode-for-opentable' ); ?></button></p>
	</div>

	<?php

}
add_action( 'admin_footer', 'sot_modal' );
