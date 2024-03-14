<?php

/**
 * Register the CB Change Mail Sender settings fields.
 *
 * @deprecated 1.3.0
 *
 * @return void
 */
function cb_mail_sender_register() {

	cb_change_mail_sender()->get_admin()->get_settings()->register_settings_fields();
}

/**
 * Settings page description.
 *
 * @deprecated 1.3.0
 *
 * @return void
 */
function cb_mail_sender_text() {

	cb_change_mail_sender()->get_admin()->get_settings()->settings_section_callback();
}

/**
 * Render the "Sender Name" field.
 *
 * @deprecated 1.3.0
 *
 * @return void
 */
function cb_mail_sender_function() {

	cb_change_mail_sender()->get_admin()->get_settings()->sender_id_field_callback();
}

/**
 * Render the "Sender Email" field.
 *
 * @deprecated 1.3.0
 *
 * @return void
 */
function cb_mail_sender_email() {

	cb_change_mail_sender()->get_admin()->get_settings()->sender_email_id_field_callback();
}

/**
 * Add CB Change Mail Sender admin menu.
 *
 * @deprecated 1.3.0
 *
 * @return void
 */
function cb_mail_sender_menu() {

	cb_change_mail_sender()->get_admin()->admin_menu();
}

/**
 * Render the Admin page.
 *
 * @deprecated 1.3.0
 *
 * @return void
 */
function cb_mail_sender_output() {

	cb_change_mail_sender()->get_admin()->render_admin_page();
}

/**
 * Return the saved "Sender Email" in CB Change Mail Sender settings page.
 *
 * @deprecated 1.3.0
 *
 * @param string $old Current "from email".
 *
 * @return string|false
 */
function cb_new_mail_from( $old ) {

	return get_option( 'cb_mail_sender_email_id' );
}

/**
 * Return the saved "Sender Name" in CB Change Mail Sender settings page.
 *
 * @deprecated 1.3.0
 *
 * @param string $old Current "from name".
 *
 * @return string|false
 */
function cb_new_mail_from_name( $old ) {

	return get_option( 'cb_mail_sender_id' );
}

/**
 * Load plugin textdomain.
 *
 * @deprecated 1.3.0
 *
 * @since 1.0.0
 */
function cb_mail_load_textdomain() {

	load_plugin_textdomain( 'cb-mail', false, plugin_basename( cb_change_mail_sender()->get_plugin_path() ) . '/assets/languages' );
}
