<?php
/**
 * Render Customer edit
 * Page: Sales
 * Tab: Customers
 *
 * @since       1.0.2
 * @subpackage  Admin/Views/Customers
 * @package     EverAccounting
 *
 * @var int $customer_id
 */

defined( 'ABSPATH' ) || exit();

try {
	$customer = new \EverAccounting\Models\Customer( $customer_id );
} catch ( Exception $e ) {
	wp_safe_redirect( admin_url( 'admin.php?page=ea-sales&tab=customers' ) );
}
$title = $customer->exists() ? esc_html__( 'Update Customer', 'wp-ever-accounting' ) : esc_html__( 'Add Customer', 'wp-ever-accounting' );
?>
<div class="ea-title-section">
	<div>
		<h1 class="wp-heading-inline"><?php esc_html_e( 'Customers', 'wp-ever-accounting' ); ?></h1>
		<?php if ( $customer->exists() ) : ?>
			<a href="
			<?php
			echo esc_url(
				add_query_arg(
					array(
						'tab'    => 'customers',
						'page'   => 'ea-sales',
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
			<a href="<?php echo esc_url( remove_query_arg( array( 'action', 'id' ) ) ); ?>" class="page-title-action">
				<?php esc_html_e( 'View All', 'wp-ever-accounting' ); ?>
			</a>
		<?php endif; ?>
	</div>
</div>
<hr class="wp-header-end">

<form id="ea-customer-form" method="post" enctype="multipart/form-data">
	<div class="ea-card">
		<div class="ea-card__header">
			<h3 class="ea-card__title"><?php echo esc_html( $title ); ?></h3>
			<?php if ( $customer->exists() ) : ?>
			<div>
				<a href="<?php echo esc_url( add_query_arg( 'action', 'view' ) ); ?>" class="button-secondary">
					<?php esc_html_e( 'View Customer', 'wp-ever-accounting' ); ?>
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
							'label'         => __( 'Name', 'wp-ever-accounting' ),
							'name'          => 'name',
							'placeholder'   => __( 'Enter name', 'wp-ever-accounting' ),
							'value'         => $customer->get_name(),
							'required'      => true,
						)
					);
					eaccounting_currency_dropdown(
						array(
							'wrapper_class' => 'ea-col-6',
							'label'         => __( 'Currency', 'wp-ever-accounting' ),
							'name'          => 'currency_code',
							'value'         => $customer->get_currency_code(),
							'required'      => true,
							'creatable'     => true,
						)
					);
					eaccounting_text_input(
						array(
							'wrapper_class' => 'ea-col-6',
							'label'         => __( 'Company', 'wp-ever-accounting' ),
							'name'          => 'company',
							'value'         => $customer->get_company(),
							'required'      => false,
						)
					);

					eaccounting_text_input(
						array(
							'wrapper_class' => 'ea-col-6',
							'label'         => __( 'Email', 'wp-ever-accounting' ),
							'name'          => 'email',
							'placeholder'   => __( 'Enter email', 'wp-ever-accounting' ),
							'data_type'     => 'email',
							'value'         => $customer->get_email(),
						)
					);
					eaccounting_text_input(
						array(
							'wrapper_class' => 'ea-col-6',
							'label'         => __( 'Phone', 'wp-ever-accounting' ),
							'name'          => 'phone',
							'placeholder'   => __( 'Enter phone', 'wp-ever-accounting' ),
							'value'         => $customer->get_phone(),
						)
					);
					eaccounting_text_input(
						array(
							'wrapper_class' => 'ea-col-6',
							'label'         => __( 'VAT Number', 'wp-ever-accounting' ),
							'name'          => 'vat_number',
							'placeholder'   => __( 'Enter vat number', 'wp-ever-accounting' ),
							'value'         => $customer->get_vat_number(),
						)
					);
					eaccounting_text_input(
						array(
							'wrapper_class' => 'ea-col-6',
							'label'         => __( 'Website', 'wp-ever-accounting' ),
							'name'          => 'website',
							'placeholder'   => __( 'Enter website', 'wp-ever-accounting' ),
							'data_type'     => 'url',
							'value'         => $customer->get_website(),
						)
					);
					eaccounting_text_input(
						array(
							'wrapper_class' => 'ea-col-6',
							'label'         => __( 'Birth Date', 'wp-ever-accounting' ),
							'name'          => 'birth_date',
							'placeholder'   => __( 'Enter birth date', 'wp-ever-accounting' ),
							'data_type'     => 'date',
							'value'         => $customer->get_birth_date() ? $customer->get_birth_date() : null,
						)
					);
					eaccounting_text_input(
						array(
							'wrapper_class' => 'ea-col-6',
							'label'         => __( 'Street', 'wp-ever-accounting' ),
							'name'          => 'street',
							'placeholder'   => __( 'Enter street', 'wp-ever-accounting' ),
							'value'         => $customer->get_street(),
						)
					);
					eaccounting_text_input(
						array(
							'wrapper_class' => 'ea-col-6',
							'label'         => __( 'City', 'wp-ever-accounting' ),
							'name'          => 'city',
							'placeholder'   => __( 'Enter city', 'wp-ever-accounting' ),
							'value'         => $customer->get_city(),
						)
					);
					eaccounting_text_input(
						array(
							'wrapper_class' => 'ea-col-6',
							'label'         => __( 'State', 'wp-ever-accounting' ),
							'name'          => 'state',
							'placeholder'   => __( 'Enter state', 'wp-ever-accounting' ),
							'value'         => $customer->get_state(),
						)
					);
					eaccounting_text_input(
						array(
							'wrapper_class' => 'ea-col-6',
							'label'         => __( 'Postcode', 'wp-ever-accounting' ),
							'name'          => 'postcode',
							'placeholder'   => __( 'Enter postcode', 'wp-ever-accounting' ),
							'value'         => $customer->get_postcode(),
						)
					);
					eaccounting_country_dropdown(
						array(
							'wrapper_class' => 'ea-col-6',
							'label'         => __( 'Country', 'wp-ever-accounting' ),
							'name'          => 'country',
							'value'         => $customer->get_country(),
						)
					);
					eaccounting_file_input(
						array(
							'wrapper_class' => 'ea-col-6',
							'label'         => __( 'Photo', 'wp-ever-accounting' ),
							'name'          => 'thumbnail_id',
							'allowed-types' => 'jpg,jpeg,png',
							'value'         => $customer->get_thumbnail_id(),
						)
					);
					eaccounting_hidden_input(
						array(
							'name'  => 'id',
							'value' => $customer->get_id(),
						)
					);
					eaccounting_hidden_input(
						array(
							'name'  => 'action',
							'value' => 'eaccounting_edit_customer',
						)
					);
					?>
				</div>
			</div>
		</div>
		<div class="ea-card__footer">
			<?php wp_nonce_field( 'ea_edit_customer' ); ?>
			<?php submit_button( __( 'Submit', 'wp-ever-accounting' ), 'primary', 'submit' ); ?>
		</div>

	</div>
</form>
