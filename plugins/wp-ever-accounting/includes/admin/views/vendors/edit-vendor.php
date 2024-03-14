<?php
/**
 * Admin Vendor Edit Page.
 * Page: Expenses
 * Tab: Vendors
 *
 * @since       1.0.2
 * @subpackage  Admin/Views/Vendors
 * @package     EverAccounting
 *
 * @var int $vendor_id
 */

defined( 'ABSPATH' ) || exit();

try {
	$vendor = new \EverAccounting\Models\Vendor( $vendor_id );
} catch ( Exception $e ) {
	wp_die( esc_html( $e->getMessage() ) );
}
if ( $vendor->exists() && 'vendor' !== $vendor->get_type() ) {
	echo esc_html__( 'Unknown vendor ID', 'wp-ever-accounting' );
	exit();
}

$title = $vendor->exists() ? esc_html__( 'Update Vendor', 'wp-ever-accounting' ) : esc_html__( 'Add Vendor', 'wp-ever-accounting' );
?>
<div class="ea-title-section">
	<div>
		<h1 class="wp-heading-inline"><?php esc_html_e( 'Vendors', 'wp-ever-accounting' ); ?></h1>
		<?php if ( $vendor->exists() ) : ?>
			<a href="
			<?php
			echo esc_url(
				add_query_arg(
					array(
						'tab'    => 'vendors',
						'page'   => 'ea-expenses',
						'action' => 'add',
					),
					admin_url( 'admin.php' )
				)
			);
			?>
						" class="page-title-action">
				<?php esc_html_e( 'Add New', 'wp-ever-accounting' ); ?>
			</a>
		<?php else : ?>
			<a href="<?php echo esc_url( remove_query_arg( array( 'action', 'id' ) ) ); ?>" class="page-title-action"><?php esc_html_e( 'View All', 'wp-ever-accounting' ); ?></a>
		<?php endif; ?>
	</div>
</div>
<hr class="wp-header-end">

<form id="ea-vendor-form" method="post" enctype="multipart/form-data">
	<div class="ea-card">
		<div class="ea-card__header">
			<h3 class="ea-card__title"><?php echo esc_html( $title ); ?></h3>
			<?php if ( $vendor->exists() ) : ?>
				<div>
					<a href="<?php echo esc_url( add_query_arg( 'action', 'view' ) ); ?>" class="button-secondary">
						<?php esc_html_e( 'View Vendor', 'wp-ever-accounting' ); ?>
					</a>
				</div>
			<?php endif; ?>
		</div>
		<div class="ea-card__body">
			<div class="ea-card__inside">
				<div class="ea-row">
					<?php
					eaccounting_text_input(
						array(
							'wrapper_class' => 'ea-col-6',
							'label'         => esc_html__( 'Name', 'wp-ever-accounting' ),
							'name'          => 'name',
							'placeholder'   => esc_html__( 'Enter name', 'wp-ever-accounting' ),
							'value'         => $vendor->get_name(),
							'required'      => true,
						)
					);

					eaccounting_currency_dropdown(
						array(
							'wrapper_class' => 'ea-col-6',
							'label'         => esc_html__( 'Currency', 'wp-ever-accounting' ),
							'name'          => 'currency_code',
							'value'         => $vendor->get_currency_code(),
							'required'      => true,
							'creatable'     => true,
						)
					);
					eaccounting_text_input(
						array(
							'wrapper_class' => 'ea-col-6',
							'label'         => esc_html__( 'Company', 'wp-ever-accounting' ),
							'name'          => 'company',
							'value'         => $vendor->get_company(),
							'required'      => false,
						)
					);
					eaccounting_text_input(
						array(
							'wrapper_class' => 'ea-col-6',
							'label'         => esc_html__( 'Email', 'wp-ever-accounting' ),
							'name'          => 'email',
							'placeholder'   => esc_html__( 'Enter email', 'wp-ever-accounting' ),
							'data_type'     => 'email',
							'value'         => $vendor->get_email(),
						)
					);
					eaccounting_text_input(
						array(
							'wrapper_class' => 'ea-col-6',
							'label'         => esc_html__( 'Phone', 'wp-ever-accounting' ),
							'name'          => 'phone',
							'placeholder'   => esc_html__( 'Enter phone', 'wp-ever-accounting' ),
							'value'         => $vendor->get_phone(),
						)
					);
					eaccounting_text_input(
						array(
							'wrapper_class' => 'ea-col-6',
							'label'         => esc_html__( 'VAT Number', 'wp-ever-accounting' ),
							'name'          => 'vat_number',
							'placeholder'   => esc_html__( 'Enter vat number', 'wp-ever-accounting' ),
							'value'         => $vendor->get_vat_number(),
						)
					);
					eaccounting_text_input(
						array(
							'wrapper_class' => 'ea-col-6',
							'label'         => esc_html__( 'Website', 'wp-ever-accounting' ),
							'name'          => 'website',
							'placeholder'   => esc_html__( 'Enter website', 'wp-ever-accounting' ),
							'data_type'     => 'url',
							'value'         => $vendor->get_website(),
						)
					);
					eaccounting_text_input(
						array(
							'wrapper_class' => 'ea-col-6',
							'label'         => esc_html__( 'Birth Date', 'wp-ever-accounting' ),
							'name'          => 'birth_date',
							'placeholder'   => esc_html__( 'Enter birth date', 'wp-ever-accounting' ),
							'data_type'     => 'date',
							'value'         => $vendor->get_birth_date() ? $vendor->get_birth_date() : null,
						)
					);
					eaccounting_text_input(
						array(
							'wrapper_class' => 'ea-col-6',
							'label'         => esc_html__( 'Street', 'wp-ever-accounting' ),
							'name'          => 'street',
							'placeholder'   => esc_html__( 'Enter street', 'wp-ever-accounting' ),
							'value'         => $vendor->get_street(),
						)
					);
					eaccounting_text_input(
						array(
							'wrapper_class' => 'ea-col-6',
							'label'         => esc_html__( 'City', 'wp-ever-accounting' ),
							'name'          => 'city',
							'placeholder'   => esc_html__( 'Enter city', 'wp-ever-accounting' ),
							'value'         => $vendor->get_city(),
						)
					);
					eaccounting_text_input(
						array(
							'wrapper_class' => 'ea-col-6',
							'label'         => esc_html__( 'State', 'wp-ever-accounting' ),
							'name'          => 'state',
							'placeholder'   => esc_html__( 'Enter state', 'wp-ever-accounting' ),
							'value'         => $vendor->get_state(),
						)
					);
					eaccounting_text_input(
						array(
							'wrapper_class' => 'ea-col-6',
							'label'         => esc_html__( 'Postcode', 'wp-ever-accounting' ),
							'name'          => 'postcode',
							'placeholder'   => esc_html__( 'Enter postcode', 'wp-ever-accounting' ),
							'value'         => $vendor->get_postcode(),
						)
					);
					eaccounting_country_dropdown(
						array(
							'wrapper_class' => 'ea-col-6',
							'label'         => esc_html__( 'Country', 'wp-ever-accounting' ),
							'name'          => 'country',
							'value'         => $vendor->get_country(),
						)
					);
					eaccounting_file_input(
						array(
							'wrapper_class' => 'ea-col-6',
							'label'         => esc_html__( 'Photo', 'wp-ever-accounting' ),
							'name'          => 'thumbnail_id',
							'allowed-types' => 'jpg,jpeg,png',
							'value'         => $vendor->get_thumbnail_id(),
						)
					);
					eaccounting_hidden_input(
						array(
							'name'  => 'id',
							'value' => $vendor->get_id(),
						)
					);
					eaccounting_hidden_input(
						array(
							'name'  => 'type',
							'value' => 'vendor',
						)
					);
					eaccounting_hidden_input(
						array(
							'name'  => 'action',
							'value' => 'eaccounting_edit_vendor',
						)
					);
					?>
				</div>
			</div>
		</div>
		<div class="ea-card__footer">
			<?php wp_nonce_field( 'ea_edit_vendor' ); ?>
			<?php submit_button( esc_html__( 'Submit', 'wp-ever-accounting' ), 'primary', 'submit' ); ?>
		</div>

	</div>
</form>
