<?php
// IMPORTANT: This plugin is dynamically updated - MODIFICATIONS WILL BE OVERWRITTEN

/** @var Nutshell_Analytics $this */

// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals
// - this file is included in a function, and no globals are being set here

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>
<div class="wrap">
	<h2><?php esc_html_e( 'Nutshell Analytics Settings', 'nutshell' ); ?></h2>
	<form method="post" action="options.php">
		<?php settings_fields( 'mcfx_wp_settings' ); ?>
		<table class="form-table">

			<tr valign="top">
				<th scope="row">
					<label for="mcfx_id">
						<?php esc_html_e( 'Account ID', 'nutshell' ); ?>
					</label>
				</th>
				<td>
					<input type="text"
						name="mcfx_id"
						id="mcfx_id"
						value="<?php echo esc_attr( $this->get_nutshell_instance_id() ); ?>"
						placeholder="ns-1234"
						style="width: 100%; max-width: 500px" />
					<br/>
					Copy your Account ID number from the WordPress integration in settings.
				</td>
			</tr>

			<tr valign="top">
				<td></td>
				<td scope="row" style="padding-bottom: 0">
					<input type="checkbox"
							name="mcfx_script_active"
							id="mcfx_script_active"
							value="1"
							<?php checked( get_option( 'mcfx_script_active' ) ); ?> />
					<label for="mcfx_script_active">
						<b><?php esc_html_e( 'Enable Nutshell Analytics scripts', 'nutshell' ); ?></b>
					</label>
				</td>
			</tr>

		<?php $integrations = $this->get_nutshell_integrations(); ?>
		<?php if ( ! empty( $integrations ) ) : ?>
			<tr valign="top" class="js-nutshell-config">
				<th scope="row">Integrations</th>
				<td scope="row" style="padding-bottom: 20px">
				<?php foreach ( $integrations as $slug => $integration ) : ?>
					<input type="checkbox"
							name="mcfx_integrations[<?php echo esc_attr( $slug ); ?>][enabled]"
							id="mcfx_integrations[<?php echo esc_attr( $slug ); ?>][enabled]"
							value="1"
							<?php checked( $integration['enabled'] ); ?>>
					<label for="mcfx_integrations">
						<b><?php echo esc_html( $integration['name'] ); ?>:</b>
						<?php echo esc_html( $integration['description'] ); ?>
					</label><br>
				<?php endforeach ?>
				</td>
			</tr>
		<?php endif ?>

			<tr valign="top" class="js-default js-nutshell-config hidden"> <!-- Hide if Custom Configuration -->
				<td></td>
				<td scope="row" style="padding-bottom: 20px">
					<b><?php esc_html_e( 'The base Nutshell Analytics scripts will be automatically output', 'nutshell' ); ?></b>
				</td>
			</tr>

		</table>
		<?php submit_button(); ?>
	</form>
</div>
<script type='text/javascript' >

	/**
	 * Show/hide configuration type custom vs. default & Nutshell Analytics options
	 *
	 */
	const mcfx_script_active = document.getElementById('mcfx_script_active');
	const updateConfigView = function() {

		const hide_nutshell_config = ! mcfx_script_active.checked;

		// Toggle elements with class: js-nutshell-config FIRST
		document.querySelectorAll('.js-nutshell-config').forEach(el => {
			el.classList.toggle('hidden', hide_nutshell_config);
		});

	}
	updateConfigView(); // Update immediately on load
	mcfx_script_active.addEventListener('change', updateConfigView); // Update on Nutshell Analytics Enable/Disable

	/**
	 * Set up WP Plugin Editor support for syntax and linting
	 * - Dependency scripts loaded in footer, so wait for window load
	 */
	jQuery(document).ready(function($) {

		$('.js-code-editor').each(function() {
			wp.codeEditor.initialize($(this), cm_settings);
		})
	});

</script>

<?php // IMPORTANT: This plugin is dynamically updated - MODIFICATIONS WILL BE OVERWRITTEN ?>
