<?php
/**
 * Admin Tools Page.
 *
 * @since       1.0.2
 * @subpackage  Admin/Tools
 * @package     EverAccounting
 */

defined( 'ABSPATH' ) || exit();

require_once EACCOUNTING_ABSPATH . '/includes/admin/tools/system-info.php';
/**
 * render tools page.
 *
 * @since 1.0.2
 */
function eaccounting_admin_tools_page() {
	$tabs       = eaccounting_get_tools_tabs();
	$active_tab = eaccounting_get_active_tab( $tabs, 'import' );
	?>
	<div class="wrap">
		<h2 class="nav-tab-wrapper">
			<?php eaccounting_navigation_tabs( $tabs, $active_tab ); ?>
		</h2>
		<div id="tab_container">
			<?php
			/**
			 * Fires in the Tabs screen tab.
			 *
			 * The dynamic portion of the hook name, `$active_tab`, refers to the slug of
			 * the currently active tools tab.
			 *
			 * @since 1.0.2
			 */
			do_action( 'eaccounting_tools_tab_' . $active_tab );
			?>
		</div><!-- #tab_container-->
	</div><!-- .wrap -->
	<?php
}

/**
 * Retrieve tools tabs
 *
 * @return array $tabs
 * @since 1.0.2
 */
function eaccounting_get_tools_tabs() {
	$tabs = array();
	if ( current_user_can( 'ea_import' ) ) {
		$tabs['import'] = __( 'Import', 'wp-ever-accounting' );
	}
	if ( current_user_can( 'ea_export' ) ) {
		$tabs['export'] = __( 'Export', 'wp-ever-accounting' );
	}
	$tabs['system_info'] = __( 'System Info', 'wp-ever-accounting' );

	return apply_filters( 'eaccounting_tools_tabs', $tabs );
}

/**
 * Setup tools pages.
 *
 * @since 1.0.2
 */
function eaccounting_load_tools_page() {
	$tab  = eaccounting_get_current_tab();
	$tabs = eaccounting_get_tools_tabs();
	if ( empty( $tab ) && $tabs ) {
		wp_safe_redirect( add_query_arg( array( 'tab' => current( array_keys( $tabs ) ) ) ) );
		exit();
	}

	do_action( 'eaccounting_load_tools_page_tab' . $tab );
}

/**
 * Export page.
 */
function eaccounting_export_tab() {
	if ( ! current_user_can( 'ea_export' ) ) {
		return;
	}
	?>
	<div class="ea-form-card">
		<div class="ea-card ea-form-card__header is-compact">
			<h3 class="ea-form-card__header-title"><?php esc_html_e( 'Export Customers', 'wp-ever-accounting' ); ?></h3>
		</div>

		<div class="ea-card">
			<form method="post" class="ea-exporter ea-batch" data-type="export-customers" data-nonce="<?php echo esc_attr( wp_create_nonce( 'export-customers_exporter_nonce' ) ); ?>">
				<p><?php esc_html_e( 'Export customers from this site as CSV file. Exported file can be imported into other site.', 'wp-ever-accounting' ); ?></p>
				<?php submit_button( esc_html__( 'Export', 'wp-ever-accounting' ), 'secondary', null, true ); ?>
			</form>
		</div>
	</div>

	<div class="ea-form-card">
		<div class="ea-card ea-form-card__header is-compact">
			<h3 class="ea-form-card__header-title"><?php esc_html_e( 'Export Vendors', 'wp-ever-accounting' ); ?></h3>
		</div>

		<div class="ea-card">
			<form method="post" class="ea-exporter ea-batch" data-type="export-vendors" data-nonce="<?php echo esc_attr( wp_create_nonce( 'export-vendors_exporter_nonce' ) ); ?>">
				<p><?php esc_html_e( 'Export vendors from this site as CSV file. Exported file can be imported into other site.', 'wp-ever-accounting' ); ?></p>
				<?php submit_button( esc_html__( 'Export', 'wp-ever-accounting' ), 'secondary', null, true ); ?>
			</form>
		</div>
	</div>

	<div class="ea-form-card">
		<div class="ea-card ea-form-card__header is-compact">
			<h3 class="ea-form-card__header-title"><?php esc_html_e( 'Export Revenues', 'wp-ever-accounting' ); ?></h3>
		</div>

		<div class="ea-card">
			<form method="post" class="ea-exporter ea-batch" data-type="export-revenues" data-nonce="<?php echo esc_attr( wp_create_nonce( 'export-revenues_exporter_nonce' ) ); ?>">
				<p><?php esc_html_e( 'Export revenues from this site as CSV file. Exported file can be imported into other site.', 'wp-ever-accounting' ); ?></p>
				<?php submit_button( esc_html__( 'Export', 'wp-ever-accounting' ), 'secondary', null, true ); ?>
			</form>
		</div>
	</div>

	<div class="ea-form-card">
		<div class="ea-card ea-form-card__header is-compact">
			<h3 class="ea-form-card__header-title"><?php esc_html_e( 'Export Payments', 'wp-ever-accounting' ); ?></h3>
		</div>

		<div class="ea-card">
			<form method="post" class="ea-exporter ea-batch" data-type="export-payments" data-nonce="<?php echo esc_attr( wp_create_nonce( 'export-payments_exporter_nonce' ) ); ?>">
				<p><?php esc_html_e( 'Export payments from this site as CSV file. Exported file can be imported into other site.', 'wp-ever-accounting' ); ?></p>
				<?php submit_button( esc_html__( 'Export', 'wp-ever-accounting' ), 'secondary', null, true ); ?>
			</form>
		</div>
	</div>

	<div class="ea-form-card">
		<div class="ea-card ea-form-card__header is-compact">
			<h3 class="ea-form-card__header-title"><?php esc_html_e( 'Export Accounts', 'wp-ever-accounting' ); ?></h3>
		</div>

		<div class="ea-card">
			<form method="post" class="ea-exporter ea-batch" data-type="export-accounts" data-nonce="<?php echo esc_attr( wp_create_nonce( 'export-accounts_exporter_nonce' ) ); ?>">
				<p><?php esc_html_e( 'Export accounts from this site as CSV file. Exported file can be imported into other site.', 'wp-ever-accounting' ); ?></p>
				<?php submit_button( esc_html__( 'Export', 'wp-ever-accounting' ), 'secondary', null, true ); ?>
			</form>
		</div>
	</div>

	<div class="ea-form-card">
		<div class="ea-card ea-form-card__header is-compact">
			<h3 class="ea-form-card__header-title"><?php esc_html_e( 'Export Currencies', 'wp-ever-accounting' ); ?></h3>
		</div>

		<div class="ea-card">
			<form method="post" class="ea-exporter ea-batch" data-type="export-currencies" data-nonce="<?php echo esc_attr( wp_create_nonce( 'export-currencies_exporter_nonce' ) ); ?>">
				<p><?php esc_html_e( 'Export currencies from this site as CSV file. Exported file can be imported into other site.', 'wp-ever-accounting' ); ?></p>
				<?php submit_button( esc_html__( 'Export', 'wp-ever-accounting' ), 'secondary', null, true ); ?>
			</form>
		</div>
	</div>

	<div class="ea-form-card">
		<div class="ea-card ea-form-card__header is-compact">
			<h3 class="ea-form-card__header-title"><?php esc_html_e( 'Export Categories', 'wp-ever-accounting' ); ?></h3>
		</div>

		<div class="ea-card">
			<form method="post" class="ea-exporter ea-batch" data-type="export-categories" data-nonce="<?php echo esc_attr( wp_create_nonce( 'export-categories_exporter_nonce' ) ); ?>">
				<p><?php esc_html_e( 'Export categories from this site as CSV file. Exported file can be imported into other site.', 'wp-ever-accounting' ); ?></p>
				<?php submit_button( esc_html__( 'Export', 'wp-ever-accounting' ), 'secondary', null, true ); ?>
			</form>
		</div>
	</div>

	<?php
}

add_action( 'eaccounting_tools_tab_export', 'eaccounting_export_tab' );

/**
 * Import tab.
 *
 * @since 1.0.0
 *
 * @return void
 */
function eaccounting_tools_import_tab() {
	if ( ! current_user_can( 'ea_import' ) ) {
		return;
	}
	?>
	<div class="ea-form-card">
		<div class="ea-card ea-form-card__header is-compact">
			<h3 class="ea-form-card__header-title"><?php esc_html_e( 'Import Customers', 'wp-ever-accounting' ); ?></h3>
		</div>

		<div class="ea-card">
			<form action="" method="post" enctype="multipart/form-data" class="ea-importer ea-batch" data-type="import-customers" data-nonce="<?php echo esc_attr( wp_create_nonce( 'import-customers_importer_nonce' ) ); ?>">
				<p>
					<?php
					/* translators: %s: CSV file */
					echo wp_kses_post( sprintf( __( 'Import customers from CSV file. Download a <a href="%s"> sample </a> file to learn how to format the CSV file.', 'wp-ever-accounting' ), eaccounting()->plugin_url( '/sample-data/import/customers.csv' ) ) );
					?>
				</p>

				<div class="ea-importer-top">
					<input name="upload" type="file" required="required" accept="text/csv">
					<?php submit_button( esc_html__( 'Import CSV', 'wp-ever-accounting' ), 'secondary', null, true ); ?>
				</div>

				<div class="ea-importer-bottom">
					<p>
						<?php esc_html_e( 'Each column loaded from the CSV may be mapped to a customer field. Select the column that should be mapped to each field below. Any columns not needed, can be ignored.', 'wp-ever-accounting' ); ?>
					</p>

					<table class="widefat striped fixed">
						<thead>
						<tr>
							<th><strong><?php esc_html_e( 'Column name', 'wp-ever-accounting' ); ?></strong></th>
							<th><strong><?php esc_html_e( 'Map to field', 'wp-ever-accounting' ); ?></strong></th>
							<th><strong><?php esc_html_e( 'Data Preview', 'wp-ever-accounting' ); ?></strong></th>
						</tr>
						</thead>
						<tbody>
						<?php eaccounting_do_import_fields( 'customer' ); ?>
						</tbody>
					</table>

					<?php submit_button( esc_attr__( 'Process', 'wp-ever-accounting' ), 'primary', null, true ); ?>
				</div>
			</form>
		</div>
	</div>

	<div class="ea-form-card">
		<div class="ea-card ea-form-card__header is-compact">
			<h3 class="ea-form-card__header-title"><?php esc_html_e( 'Import Vendors', 'wp-ever-accounting' ); ?></h3>
		</div>

		<div class="ea-card">
			<form action="" method="post" enctype="multipart/form-data" class="ea-importer ea-batch" data-type="import-vendors" data-nonce="<?php echo esc_attr( wp_create_nonce( 'import-vendors_importer_nonce' ) ); ?>">
				<p>
					<?php
					/* translators: %s: CSV file */
					echo wp_kses_post( sprintf( __( 'Import vendors from CSV file. Download a <a href="%s"> sample </a> file to learn how to format the CSV file.', 'wp-ever-accounting' ), eaccounting()->plugin_url( '/sample-data/import/vendors.csv' ) ) );
					?>
				</p>

				<div class="ea-importer-top">
					<input name="upload" type="file" required="required" accept="text/csv">
					<?php submit_button( esc_html__( 'Import CSV', 'wp-ever-accounting' ), 'secondary', null, true ); ?>
				</div>

				<div class="ea-importer-bottom">
					<p>
						<?php esc_html_e( 'Each column loaded from the CSV may be mapped to a vendor field. Select the column that should be mapped to each field below. Any columns not needed, can be ignored.', 'wp-ever-accounting' ); ?>
					</p>

					<table class="widefat striped fixed">
						<thead>
						<tr>
							<th><strong><?php esc_html_e( 'Column name', 'wp-ever-accounting' ); ?></strong></th>
							<th><strong><?php esc_html_e( 'Map to field', 'wp-ever-accounting' ); ?></strong></th>
							<th><strong><?php esc_html_e( 'Data Preview', 'wp-ever-accounting' ); ?></strong></th>
						</tr>
						</thead>
						<tbody>
						<?php eaccounting_do_import_fields( 'vendor' ); ?>
						</tbody>
					</table>

					<?php submit_button( esc_attr__( 'Process', 'wp-ever-accounting' ), 'primary', null, true ); ?>
				</div>
			</form>
		</div>
	</div>

	<div class="ea-form-card">
		<div class="ea-card ea-form-card__header is-compact">
			<h3 class="ea-form-card__header-title"><?php esc_html_e( 'Import Accounts', 'wp-ever-accounting' ); ?></h3>
		</div>

		<div class="ea-card">
			<form action="" method="post" enctype="multipart/form-data" class="ea-importer ea-batch" data-type="import-accounts" data-nonce="<?php echo esc_attr( wp_create_nonce( 'import-accounts_importer_nonce' ) ); ?>">
				<p>
					<?php
					/* translators: %s: CSV file */
					echo wp_kses_post( sprintf( __( 'Import accounts from CSV file. Download a <a href="%s"> sample </a> file to learn how to format the CSV file.', 'wp-ever-accounting' ), eaccounting()->plugin_url( '/sample-data/import/accounts.csv' ) ) );
					?>
				</p>

				<div class="ea-importer-top">
					<input name="upload" type="file" required="required" accept="text/csv">
					<?php submit_button( esc_html__( 'Import CSV', 'wp-ever-accounting' ), 'secondary', null, true ); ?>
				</div>

				<div class="ea-importer-bottom">
					<p>
						<?php esc_html_e( 'Each column loaded from the CSV may be mapped to a account field. Select the column that should be mapped to each field below. Any columns not needed, can be ignored.', 'wp-ever-accounting' ); ?>
					</p>

					<table class="widefat striped fixed">
						<thead>
						<tr>
							<th><strong><?php esc_html_e( 'Column name', 'wp-ever-accounting' ); ?></strong></th>
							<th><strong><?php esc_html_e( 'Map to field', 'wp-ever-accounting' ); ?></strong></th>
							<th><strong><?php esc_html_e( 'Data Preview', 'wp-ever-accounting' ); ?></strong></th>
						</tr>
						</thead>
						<tbody>
						<?php eaccounting_do_import_fields( 'account' ); ?>
						</tbody>
					</table>

					<?php submit_button( esc_attr__( 'Process', 'wp-ever-accounting' ), 'primary', null, true ); ?>
				</div>
			</form>
		</div>
	</div>

	<div class="ea-form-card">
		<div class="ea-card ea-form-card__header is-compact">
			<h3 class="ea-form-card__header-title"><?php esc_html_e( 'Import Revenues', 'wp-ever-accounting' ); ?></h3>
		</div>

		<div class="ea-card">
			<form action="" method="post" enctype="multipart/form-data" class="ea-importer ea-batch" data-type="import-revenues" data-nonce="<?php echo esc_attr( wp_create_nonce( 'import-revenues_importer_nonce' ) ); ?>">
				<p>
					<?php
					/* translators: %s: CSV file */
					$message = sprintf( __( 'Import revenues from CSV file. Download a <a href="%s"> sample </a> file to learn how to format the CSV file.', 'wp-ever-accounting' ), eaccounting()->plugin_url( '/sample-data/import/revenues.csv' ) );
					echo wp_kses_post( $message );
					?>
				</p>

				<div class="ea-importer-top">
					<input name="upload" type="file" required="required" accept="text/csv">
					<?php submit_button( esc_html__( 'Import CSV', 'wp-ever-accounting' ), 'secondary', null, true ); ?>
				</div>

				<div class="ea-importer-bottom">
					<p>
						<?php esc_html_e( 'Each column loaded from the CSV may be mapped to a revenue field. Select the column that should be mapped to each field below. Any columns not needed, can be ignored.', 'wp-ever-accounting' ); ?>
					</p>

					<table class="widefat striped fixed">
						<thead>
						<tr>
							<th><strong><?php esc_html_e( 'Column name', 'wp-ever-accounting' ); ?></strong></th>
							<th><strong><?php esc_html_e( 'Map to field', 'wp-ever-accounting' ); ?></strong></th>
							<th><strong><?php esc_html_e( 'Data Preview', 'wp-ever-accounting' ); ?></strong></th>
						</tr>
						</thead>
						<tbody>
						<?php eaccounting_do_import_fields( 'revenue' ); ?>
						</tbody>
					</table>

					<?php submit_button( esc_attr__( 'Process', 'wp-ever-accounting' ), 'primary', null, true ); ?>
				</div>
			</form>
		</div>
	</div>


	<div class="ea-form-card">
		<div class="ea-card ea-form-card__header is-compact">
			<h3 class="ea-form-card__header-title"><?php esc_html_e( 'Import Payments', 'wp-ever-accounting' ); ?></h3>
		</div>

		<div class="ea-card">
			<form action="" method="post" enctype="multipart/form-data" class="ea-importer ea-batch" data-type="import-payments" data-nonce="<?php echo esc_attr( wp_create_nonce( 'import-payments_importer_nonce' ) ); ?>">
				<p>
					<?php
					echo wp_kses_post(
						sprintf(
								/* translators: %s: CSV file */
							__( 'Import payments from CSV file. Download a <a href="%s"> sample </a> file to learn how to format the CSV file.', 'wp-ever-accounting' ),
							eaccounting()->plugin_url( '/sample-data/import/payments.csv' )
						)
					);
					?>
				</p>

				<div class="ea-importer-top">
					<input name="upload" type="file" required="required" accept="text/csv">
					<?php submit_button( esc_html__( 'Import CSV', 'wp-ever-accounting' ), 'secondary', null, true ); ?>
				</div>

				<div class="ea-importer-bottom">
					<p>
						<?php esc_html_e( 'Each column loaded from the CSV may be mapped to a payment field. Select the column that should be mapped to each field below. Any columns not needed, can be ignored.', 'wp-ever-accounting' ); ?>
					</p>

					<table class="widefat striped fixed">
						<thead>
						<tr>
							<th><strong><?php esc_html_e( 'Column name', 'wp-ever-accounting' ); ?></strong></th>
							<th><strong><?php esc_html_e( 'Map to field', 'wp-ever-accounting' ); ?></strong></th>
							<th><strong><?php esc_html_e( 'Data Preview', 'wp-ever-accounting' ); ?></strong></th>
						</tr>
						</thead>
						<tbody>
						<?php eaccounting_do_import_fields( 'payment' ); ?>
						</tbody>
					</table>

					<?php submit_button( esc_attr__( 'Process', 'wp-ever-accounting' ), 'primary', null, true ); ?>
				</div>
			</form>
		</div>
	</div>

	<div class="ea-form-card">
		<div class="ea-card ea-form-card__header is-compact">
			<h3 class="ea-form-card__header-title"><?php esc_html_e( 'Import Currencies', 'wp-ever-accounting' ); ?></h3>
		</div>

		<div class="ea-card">
			<form action="" method="post" enctype="multipart/form-data" class="ea-importer ea-batch" data-type="import-currencies" data-nonce="<?php echo esc_attr( wp_create_nonce( 'import-currencies_importer_nonce' ) ); ?>">
				<p>
					<?php
					echo wp_kses_post(
						sprintf(
								// translators: %s: sample file url.
							__( 'Import currencies from CSV file. Download a <a href="%s"> sample </a> file to learn how to format the CSV file.', 'wp-ever-accounting' ),
							eaccounting()->plugin_url( '/sample-data/import/currencies.csv' )
						)
					);
					?>
				</p>

				<div class="ea-importer-top">
					<input name="upload" type="file" required="required" accept="text/csv">
					<?php submit_button( esc_html__( 'Import CSV', 'wp-ever-accounting' ), 'secondary', null, true ); ?>
				</div>

				<div class="ea-importer-bottom">
					<p>
						<?php esc_html_e( 'Each column loaded from the CSV may be mapped to a currency field. Select the column that should be mapped to each field below. Any columns not needed, can be ignored.', 'wp-ever-accounting' ); ?>
					</p>

					<table class="widefat striped fixed">
						<thead>
						<tr>
							<th><strong><?php esc_html_e( 'Column name', 'wp-ever-accounting' ); ?></strong></th>
							<th><strong><?php esc_html_e( 'Map to field', 'wp-ever-accounting' ); ?></strong></th>
							<th><strong><?php esc_html_e( 'Data Preview', 'wp-ever-accounting' ); ?></strong></th>
						</tr>
						</thead>
						<tbody>
						<?php eaccounting_do_import_fields( 'currency' ); ?>
						</tbody>
					</table>

					<?php submit_button( esc_attr__( 'Process', 'wp-ever-accounting' ), 'primary', null, true ); ?>
				</div>
			</form>
		</div>
	</div>

	<div class="ea-form-card">
		<div class="ea-card ea-form-card__header is-compact">
			<h3 class="ea-form-card__header-title"><?php esc_html_e( 'Import Categories', 'wp-ever-accounting' ); ?></h3>
		</div>

		<div class="ea-card">
			<form action="" method="post" enctype="multipart/form-data" class="ea-importer ea-batch" data-type="import-categories" data-nonce="<?php echo esc_attr( wp_create_nonce( 'import-categories_importer_nonce' ) ); ?>">
				<p>
					<?php
					echo wp_kses_post(
						sprintf(
								// translators: %s: sample file url.
							__( 'Import categories from CSV file. Download a <a href="%s"> sample </a> file to learn how to format the CSV file.', 'wp-ever-accounting' ),
							eaccounting()->plugin_url( '/sample-data/import/categories.csv' )
						)
					);
					?>
				</p>

				<div class="ea-importer-top">
					<input name="upload" type="file" required="required" accept="text/csv">
					<?php submit_button( esc_html__( 'Import CSV', 'wp-ever-accounting' ), 'secondary', null, true ); ?>
				</div>

				<div class="ea-importer-bottom">
					<p>
						<?php esc_html_e( 'Each column loaded from the CSV may be mapped to a category field. Select the column that should be mapped to each field below. Any columns not needed, can be ignored.', 'wp-ever-accounting' ); ?>
					</p>

					<table class="widefat striped fixed">
						<thead>
						<tr>
							<th><strong><?php esc_html_e( 'Column name', 'wp-ever-accounting' ); ?></strong></th>
							<th><strong><?php esc_html_e( 'Map to field', 'wp-ever-accounting' ); ?></strong></th>
							<th><strong><?php esc_html_e( 'Data Preview', 'wp-ever-accounting' ); ?></strong></th>
						</tr>
						</thead>
						<tbody>
						<?php eaccounting_do_import_fields( 'category' ); ?>
						</tbody>
					</table>

					<?php submit_button( esc_attr__( 'Process', 'wp-ever-accounting' ), 'primary', null, true ); ?>
				</div>
			</form>
		</div>
	</div>
	<?php
}

add_action( 'eaccounting_tools_tab_import', 'eaccounting_tools_import_tab' );
