<?php
/**
 * Auto detect forms - cons - partial page.
 *
 * @package  Iubenda
 */

$cons_page_configuration = get_option( 'iubenda_consent_solution' );

if ( $cons_page_configuration && iub_array_get( $cons_page_configuration, 'public_api_key' ) ) {

	$form_id = absint( iub_get_request_parameter( 'form_id', 0 ) );
	$form    = ! empty( $form_id ) ? iubenda()->forms->get_form( $form_id ) : false;

	$supported_forms = iubenda()->forms->sources;

	// list screen.
	if ( ! class_exists( 'WP_List_Table' ) ) {
		include_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
	}

	include_once IUBENDA_PLUGIN_PATH . '/includes/class-iubenda-list-table-forms.php';

	$list_table = new Iubenda_List_Table_Forms();

	echo '
                <div id="iubenda-consent-forms">';
	$list_table->views();
	$list_table->prepare_items();
	$list_table->display();

	echo '
                </div>';
} else {
	?>
	<p><?php esc_html_e( 'This section lists the forms available for field mapping. The plugin currently supports & detects: WordPress Comment, Contact Form 7, WooCommerce Checkout and WP Forms.', 'iubenda' ); ?></p>

	<?php

}
