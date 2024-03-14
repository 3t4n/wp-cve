<?php
/**
 * The file used to display the "Options" menu in the admin area.
 *
 * @package hreflang-manager-lite
 */

if ( ! current_user_can( 'manage_options' ) ) {
	wp_die( esc_html__( 'You do not have sufficient capabilities to access this page.' ) );
}

// Sanitization -------------------------------------------------------------------------------------------------------.
$data['settings_updated'] = isset( $_GET['settings-updated'] ) ? sanitize_key( $_GET['settings-updated'], 10 ) : null;
$data['active_tab']       = isset( $_GET['tab'] ) ? sanitize_key( $_GET['tab'] ) : 'general_options';

?>

<div class="wrap">

	<h2><?php esc_html_e( 'Hreflang Manager - Options', 'hreflang-manager-lite' ); ?></h2>

	<?php

	// settings errors.
	if ( 'true' === $data['settings_updated'] ) {
		settings_errors();
	}

	?>

	<div id="daext-options-wrapper">

		<div class="nav-tab-wrapper">
			<a href="?page=daexthrmal_options&tab=general_options" class="nav-tab <?php echo 'general_options' === $data['active_tab'] ? 'nav-tab-active' : ''; ?>"><?php esc_html_e( 'General', 'hreflang-manager-lite' ); ?></a>
			<a href="?page=daexthrmal_options&tab=defaults_options" class="nav-tab <?php echo 'defaults_options' === $data['active_tab'] ? 'nav-tab-active' : ''; ?>"><?php esc_html_e( 'Defaults', 'hreflang-manager-lite' ); ?></a>
		</div>

		<form method='post' action='options.php'>

			<?php

			if ( 'general_options' === $data['active_tab'] ) {

				settings_fields( $this->shared->get( 'slug' ) . '_general_options' );
				do_settings_sections( $this->shared->get( 'slug' ) . '_general_options' );

			}

			if ( 'defaults_options' === $data['active_tab'] ) {

				settings_fields( $this->shared->get( 'slug' ) . '_defaults_options' );
				do_settings_sections( $this->shared->get( 'slug' ) . '_defaults_options' );

			}

			?>

			<div class="daext-options-action">
				<input type="submit" name="submit" id="submit" class="button" value="<?php esc_attr_e( 'Save Changes', 'hreflang-manager-lite' ); ?>">
			</div>

		</form>

	</div>

</div>