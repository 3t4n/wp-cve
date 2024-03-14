<?php
/**
 * Admin Export Page.
 * Page: Tools
 * Tab: Export
 *
 * @package     EverAccounting
 * @subpackage  Admin/View/Tools
 * @since       1.0.2
 */

defined( 'ABSPATH' ) || exit();
?>

<div class="ea-card">
	<div class="ea-card__header is-compact">
		<h3 class="ea-card__title"><?php esc_html_e( 'Export Customers', 'wp-ever-accounting' ); ?></h3>
	</div>

	<div class="ea-card__inside">
		<form method="post" class="ea-exporter ea-batch" data-type="export-customers" data-nonce="<?php echo esc_attr( wp_create_nonce( 'export-customers_exporter_nonce' ) ); ?>">
			<p><?php esc_html_e( 'Export customers from this site as CSV file. Exported file can be imported into other site.', 'wp-ever-accounting' ); ?></p>
			<?php submit_button( esc_html__( 'Export', 'wp-ever-accounting' ), 'secondary', null, true ); ?>
		</form>
	</div>
</div>

<div class="ea-card">
	<div class="ea-card__header is-compact">
		<h3 class="ea-card__title"><?php esc_html_e( 'Export Vendors', 'wp-ever-accounting' ); ?></h3>
	</div>

	<div class="ea-card__inside">
		<form method="post" class="ea-exporter ea-batch" data-type="export-vendors" data-nonce="<?php echo esc_attr( wp_create_nonce( 'export-vendors_exporter_nonce' ) ); ?>">
			<p><?php esc_html_e( 'Export vendors from this site as CSV file. Exported file can be imported into other site.', 'wp-ever-accounting' ); ?></p>
			<?php submit_button( esc_html__( 'Export', 'wp-ever-accounting' ), 'secondary', null, true ); ?>
		</form>
	</div>
</div>

<div class="ea-card">
	<div class="ea-card__header is-compact">
		<h3 class="ea-card__title"><?php esc_html_e( 'Export Revenues', 'wp-ever-accounting' ); ?></h3>
	</div>

	<div class="ea-card__inside">
		<form method="post" class="ea-exporter ea-batch" data-type="export-revenues" data-nonce="<?php echo esc_attr( wp_create_nonce( 'export-revenues_exporter_nonce' ) ); ?>">
			<p><?php esc_html_e( 'Export revenues from this site as CSV file. Exported file can be imported into other site.', 'wp-ever-accounting' ); ?></p>
			<?php submit_button( esc_html__( 'Export', 'wp-ever-accounting' ), 'secondary', null, true ); ?>
		</form>
	</div>
</div>

<div class="ea-card">
	<div class="ea-card__header is-compact">
		<h3 class="ea-card__title"><?php esc_html_e( 'Export Payments', 'wp-ever-accounting' ); ?></h3>
	</div>

	<div class="ea-card__inside">
		<form method="post" class="ea-exporter ea-batch" data-type="export-payments" data-nonce="<?php echo esc_attr( wp_create_nonce( 'export-payments_exporter_nonce' ) ); ?>">
			<p><?php esc_html_e( 'Export payments from this site as CSV file. Exported file can be imported into other site.', 'wp-ever-accounting' ); ?></p>
			<?php submit_button( esc_html__( 'Export', 'wp-ever-accounting' ), 'secondary', null, true ); ?>
		</form>
	</div>
</div>

<div class="ea-card">
	<div class="ea-card__header is-compact">
		<h3 class="ea-card__title"><?php esc_html_e( 'Export Accounts', 'wp-ever-accounting' ); ?></h3>
	</div>

	<div class="ea-card__inside">
		<form method="post" class="ea-exporter ea-batch" data-type="export-accounts" data-nonce="<?php echo esc_attr( wp_create_nonce( 'export-accounts_exporter_nonce' ) ); ?>">
			<p><?php esc_html_e( 'Export accounts from this site as CSV file. Exported file can be imported into other site.', 'wp-ever-accounting' ); ?></p>
			<?php submit_button( esc_html__( 'Export', 'wp-ever-accounting' ), 'secondary', null, true ); ?>
		</form>
	</div>
</div>

<div class="ea-card">
	<div class="ea-card__header is-compact">
		<h3 class="ea-card__title"><?php esc_html_e( 'Export Items', 'wp-ever-accounting' ); ?></h3>
	</div>

	<div class="ea-card__inside">
		<form method="post" class="ea-exporter ea-batch" data-type="export-items" data-nonce="<?php echo esc_attr( wp_create_nonce( 'export-items_exporter_nonce' ) ); ?>">
			<p><?php esc_html_e( 'Export items from this site as CSV file. Exported file can be imported into other site.', 'wp-ever-accounting' ); ?></p>
			<?php submit_button( esc_html__( 'Export', 'wp-ever-accounting' ), 'secondary', null, true ); ?>
		</form>
	</div>
</div>

<div class="ea-card">
	<div class="ea-card__header is-compact">
		<h3 class="ea-card__title"><?php esc_html_e( 'Export Currencies', 'wp-ever-accounting' ); ?></h3>
	</div>

	<div class="ea-card__inside">
		<form method="post" class="ea-exporter ea-batch" data-type="export-currencies" data-nonce="<?php echo esc_attr( wp_create_nonce( 'export-currencies_exporter_nonce' ) ); ?>">
			<p><?php esc_html_e( 'Export currencies from this site as CSV file. Exported file can be imported into other site.', 'wp-ever-accounting' ); ?></p>
			<?php submit_button( esc_html__( 'Export', 'wp-ever-accounting' ), 'secondary', null, true ); ?>
		</form>
	</div>
</div>

<div class="ea-card">
	<div class="ea-card__header is-compact">
		<h3 class="ea-card__title"><?php esc_html_e( 'Export Categories', 'wp-ever-accounting' ); ?></h3>
	</div>

	<div class="ea-card__inside">
		<form method="post" class="ea-exporter ea-batch" data-type="export-categories" data-nonce="<?php echo esc_attr( wp_create_nonce( 'export-categories_exporter_nonce' ) ); ?>">
			<p><?php esc_html_e( 'Export categories from this site as CSV file. Exported file can be imported into other site.', 'wp-ever-accounting' ); ?></p>
			<?php submit_button( esc_html__( 'Export', 'wp-ever-accounting' ), 'secondary', null, true ); ?>
		</form>
	</div>
</div>
