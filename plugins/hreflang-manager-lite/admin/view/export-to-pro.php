<?php
/**
 * The file used to display the "Export to Pro" menu in the admin area.
 *
 * @package hreflang-manager-lite
 */

if ( ! current_user_can( 'manage_options' ) ) {
	wp_die( esc_html__( 'You do not have sufficient permissions to access this page.', 'hreflang-manager-lite' ) );
}

?>

<!-- output -->

<div class="wrap">

	<h2><?php esc_html_e( 'Hreflang Manager - Export to Pro', 'hreflang-manager-lite' ); ?></h2>

	<div id="daext-menu-wrapper">

		<p><?php esc_html_e( 'Click the Export button to generate an XML file that includes all your connections.', 'hreflang-manager-lite' ); ?></p>
		<p><?php esc_html_e( 'Note that you can import the resulting file in the Import menu of the ', 'hreflang-manager-lite' ); ?>
			<a href="https://daext.com/hreflang-manager/"><?php esc_html_e( 'Pro Version', 'hreflang-manager-lite' ); ?></a>.</p>

		<!-- the data sent through this form are handled by the export_xml_controller() method called with the WordPress init action -->
		<form method="POST" action="admin.php?page=daexthrmal_export_to_pro">

			<div class="daext-widget-submit">
				<input name="daexthrmal_export" class="button button-primary" type="submit"
						value="<?php esc_attr_e( 'Export', 'hreflang-manager-lite' ); ?>" 
												<?php
												if ( $this->shared->number_of_connections() === 0 ) {
													echo 'disabled="disabled"';
												}
												?>
				>
			</div>

		</form>

	</div>

</div>