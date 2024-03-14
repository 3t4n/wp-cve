<?php
/**
 * Admin Import Page.
 * Page: Tools
 * Tab: Import
 *
 * @since       1.0.2
 * @subpackage  Admin/View/Tools
 * @package     EverAccounting
 */

defined( 'ABSPATH' ) || exit();
?>

<div class="ea-card">
	<div class="ea-card__header is-compact">
		<h3 class="ea-card__title">
			<?php esc_html_e( 'Import Customers', 'wp-ever-accounting' ); ?>
		</h3>
	</div>
	<div class="ea-card__inside">
		<form action="" method="post" enctype="multipart/form-data" class="ea-importer ea-batch" data-type="import-customers" data-nonce="<?php echo esc_attr( wp_create_nonce( 'import-customers_importer_nonce' ) ); ?>">
			<p>
				<?php
				/* translators: %s: link to the documentation */
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

<div class="ea-card">
	<div class="ea-card__header is-compact">
		<h3 class="ea-card__title"><?php esc_html_e( 'Import Vendors', 'wp-ever-accounting' ); ?></h3>
	</div>

	<div class="ea-card__inside">
		<form action="" method="post" enctype="multipart/form-data" class="ea-importer ea-batch" data-type="import-vendors" data-nonce="<?php echo esc_attr( wp_create_nonce( 'import-vendors_importer_nonce' ) ); ?>">
			<p>
				<?php
				/* translators: %s: link to the documentation */
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

<div class="ea-card">
	<div class="ea-card__header is-compact">
		<h3 class="ea-card__title"><?php esc_html_e( 'Import Accounts', 'wp-ever-accounting' ); ?></h3>
	</div>

	<div class="ea-card__inside">
		<form action="" method="post" enctype="multipart/form-data" class="ea-importer ea-batch" data-type="import-accounts" data-nonce="<?php echo esc_attr( wp_create_nonce( 'import-accounts_importer_nonce' ) ); ?>">
			<p>
				<?php
				/* translators: %s: link to the documentation */
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

<div class="ea-card">
	<div class="ea-card__header is-compact">
		<h3 class="ea-card__title"><?php esc_html_e( 'Import Items', 'wp-ever-accounting' ); ?></h3>
	</div>

	<div class="ea-card__inside">
		<form action="" method="post" enctype="multipart/form-data" class="ea-importer ea-batch" data-type="import-items" data-nonce="<?php echo esc_attr( wp_create_nonce( 'import-items_importer_nonce' ) ); ?>">
			<p>
				<?php
				/* translators: %s: link to the documentation */
				echo wp_kses_post( sprintf( __( 'Import items from CSV file. Download a <a href="%s"> sample </a> file to learn how to format the CSV file.', 'wp-ever-accounting' ), eaccounting()->plugin_url( '/sample-data/import/items.csv' ) ) );
				?>
			</p>

			<div class="ea-importer-top">
				<input name="upload" type="file" required="required" accept="text/csv">
				<?php submit_button( esc_html__( 'Import CSV', 'wp-ever-accounting' ), 'secondary', null, true ); ?>
			</div>

			<div class="ea-importer-bottom">
				<p>
					<?php esc_html_e( 'Each column loaded from the CSV may be mapped to a item field. Select the column that should be mapped to each field below. Any columns not needed, can be ignored.', 'wp-ever-accounting' ); ?>
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
					<?php eaccounting_do_import_fields( 'item' ); ?>
					</tbody>
				</table>

				<?php submit_button( esc_attr__( 'Process', 'wp-ever-accounting' ), 'primary', null, true ); ?>
			</div>
		</form>
	</div>
</div>

<div class="ea-card">
	<div class="ea-card__header is-compact">
		<h3 class="ea-card__title"><?php esc_html_e( 'Import Revenues', 'wp-ever-accounting' ); ?></h3>
	</div>

	<div class="ea-card__inside">
		<form action="" method="post" enctype="multipart/form-data" class="ea-importer ea-batch" data-type="import-revenues" data-nonce="<?php echo esc_attr( wp_create_nonce( 'import-revenues_importer_nonce' ) ); ?>">
			<p>
				<?php
				/* translators: %s: link to the documentation */
				echo wp_kses_post( sprintf( __( 'Import revenues from CSV file. Download a <a href="%s"> sample </a> file to learn how to format the CSV file.', 'wp-ever-accounting' ), eaccounting()->plugin_url( '/sample-data/import/revenues.csv' ) ) );
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


<div class="ea-card">
	<div class="ea-card__header is-compact">
		<h3 class="ea-card__title"><?php esc_html_e( 'Import Payments', 'wp-ever-accounting' ); ?></h3>
	</div>

	<div class="ea-card__inside">
		<form action="" method="post" enctype="multipart/form-data" class="ea-importer ea-batch" data-type="import-payments" data-nonce="<?php echo esc_attr( wp_create_nonce( 'import-payments_importer_nonce' ) ); ?>">
			<p>
				<?php
				echo wp_kses_post(
					sprintf(
						/* translators: %s: link to the documentation */
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

<div class="ea-card">
	<div class="ea-card__header is-compact">
		<h3 class="ea-card__title"><?php esc_html_e( 'Import Currencies', 'wp-ever-accounting' ); ?></h3>
	</div>

	<div class="ea-card__inside">
		<form action="" method="post" enctype="multipart/form-data" class="ea-importer ea-batch" data-type="import-currencies" data-nonce="<?php echo esc_attr( wp_create_nonce( 'import-currencies_importer_nonce' ) ); ?>">
			<p>
				<?php
				echo wp_kses_post(
					sprintf(
						/* translators: %s: link to the documentation */
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

<div class="ea-card">
	<div class="ea-card__header is-compact">
		<h3 class="ea-card__title"><?php esc_html_e( 'Import Categories', 'wp-ever-accounting' ); ?></h3>
	</div>

	<div class="ea-card__inside">
		<form action="" method="post" enctype="multipart/form-data" class="ea-importer ea-batch" data-type="import-categories" data-nonce="<?php echo esc_attr( wp_create_nonce( 'import-categories_importer_nonce' ) ); ?>">
			<p>
				<?php
				echo wp_kses_post(
					sprintf(
						/* translators: %s: link to the documentation */
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
