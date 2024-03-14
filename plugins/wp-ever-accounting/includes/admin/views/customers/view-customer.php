<?php
/**
 * Render Single customer
 * Page: Sales
 * Tab: Customers
 *
 * @since       1.0.2
 * @subpackage  Admin/Views/Customers
 * @package     EverAccounting
 * @var int $customer_id
 */

defined( 'ABSPATH' ) || exit();

$customer = eaccounting_get_customer( $customer_id );

if ( empty( $customer ) || ! $customer->exists() ) {
	wp_die( esc_html__( 'Sorry, Customer does not exist', 'wp-ever-accounting' ) );
}

$sections        = array(
	'transactions' => __( 'Transactions', 'wp-ever-accounting' ),
	'invoices'     => __( 'Invoices', 'wp-ever-accounting' ),
);
$sections        = apply_filters( 'eaccounting_customer_sections', $sections );
$first_section   = current( array_keys( $sections ) );
$section         = filter_input( INPUT_GET, 'section', FILTER_SANITIZE_STRING );
$current_section = ! empty( $section ) && array_key_exists( $section, $sections ) ? sanitize_title( $section ) : $first_section;
$edit_url        = eaccounting_admin_url(
	array(
		'page'        => 'ea-sales',
		'tab'         => 'customers',
		'action'      => 'edit',
		'customer_id' => $customer->get_id(),
	)
);
?>
	<div class="ea-title-section">
		<div>
			<h1 class="wp-heading-inline"><?php esc_html_e( 'Customers', 'wp-ever-accounting' ); ?></h1>
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
				<?php echo esc_html__( 'Add New', 'wp-ever-accounting' ); ?>
			</a>

		</div>
	</div>
	<hr class="wp-header-end">

	<div class="ea-page-columns altered ea-single-customer">
		<div class="ea-page-columns__content ea-mt-20">
			<div class="ea-row">
				<div class="ea-col">
					<div class="ea-widget-card">
						<div class="ea-widget-card__icon">
							<span class="dashicons dashicons-money-alt"></span>
						</div>
						<div class="ea-widget-card__content">
							<div class="ea-widget-card__primary">
								<span class="ea-widget-card__title"><?php esc_html_e( 'Total Paid', 'wp-ever-accounting' ); ?></span>
								<span class="ea-widget-card__amount"><?php echo esc_html( eaccounting_format_price( $customer->get_total_paid(), $customer->get_currency_code() ) ); ?></span>
							</div>
						</div>
					</div><!--.ea-widget-card-->

				</div>

				<div class="ea-col">

					<div class="ea-widget-card alert">
						<div class="ea-widget-card__icon">
							<span class="dashicons dashicons-money-alt"></span>
						</div>
						<div class="ea-widget-card__content">
							<div class="ea-widget-card__primary">
								<span class="ea-widget-card__title"><?php esc_html_e( 'Total Due', 'wp-ever-accounting' ); ?></span>
								<span class="ea-widget-card__amount"><?php echo esc_html( eaccounting_format_price( $customer->get_total_due(), $customer->get_currency_code() ) ); ?></span>
							</div>
						</div>
					</div><!--.ea-widget-card-->

				</div>

			</div>
			<div class="ea-card">
				<nav class="ea-card__nav">
					<?php foreach ( $sections as $section_id => $section_title ) : ?>
						<?php
						$url = eaccounting_admin_url(
							array(
								'tab'         => 'customers',
								'action'      => 'view',
								'customer_id' => $customer_id,
								'section'     => $section_id,
							)
						);
						?>
						<a class="nav-tab <?php echo $section_id === $current_section ? 'nav-tab-active' : ''; ?>" href="<?php echo esc_url( $url ); ?>">
							<?php echo esc_html( $section_title ); ?>
						</a>
					<?php endforeach; ?>
				</nav>
				<div class="ea-card__inside">
					<?php
					switch ( $current_section ) {
						case 'transactions':
						case 'invoices':
							include dirname( __FILE__ ) . '/customers-' . sanitize_file_name( $current_section ) . '.php';
							break;
						default:
							do_action( 'eaccounting_customer_section_' . $current_section, $customer );
							break;
					}
					?>
				</div>
			</div>

		</div>

		<div class="ea-page-columns__aside">
			<div class="ea-card">
				<div class="ea-card__header">
					<h3 class="ea-card__title"><?php esc_html_e( 'Customer Details', 'wp-ever-accounting' ); ?></h3>
					<a href="<?php echo esc_url( $edit_url ); ?>" class="button-secondary"><?php esc_html_e( 'Edit', 'wp-ever-accounting' ); ?></a>
				</div>

				<div class="ea-card__inside">
					<div class="ea-avatar ea-center-block">
						<img src="<?php echo esc_url( $customer->get_avatar_url() ); ?>" alt="<?php echo esc_html( $customer->get_name() ); ?>">
					</div>
				</div>

				<div class="ea-list-group">
					<div class="ea-list-group__item">
						<div class="ea-list-group__title"><?php esc_html_e( 'Name', 'wp-ever-accounting' ); ?></div>
						<div class="ea-list-group__text"><?php echo esc_html( $customer->get_name() ); ?></div>
					</div>
					<div class="ea-list-group__item">
						<div class="ea-list-group__title"><?php esc_html_e( 'Currency', 'wp-ever-accounting' ); ?></div>
						<div class="ea-list-group__text">
							<?php echo ! empty( $customer->get_currency_code() ) ? esc_html( $customer->get_currency_code() ) : '&mdash;'; ?>
						</div>
					</div>
					<div class="ea-list-group__item">
						<div class="ea-list-group__title"><?php esc_html_e( 'Birthdate', 'wp-ever-accounting' ); ?></div>
						<div class="ea-list-group__text">
							<?php echo ! empty( $customer->get_birth_date() ) ? esc_html( eaccounting_date( $customer->get_birth_date() ) ) : '&mdash;'; ?>
						</div>
					</div>
					<div class="ea-list-group__item">
						<div class="ea-list-group__title"><?php esc_html_e( 'Phone', 'wp-ever-accounting' ); ?></div>
						<div class="ea-list-group__text">
							<?php echo ! empty( $customer->get_phone() ) ? esc_html( $customer->get_phone() ) : '&mdash;'; ?>
						</div>
					</div>
					<div class="ea-list-group__item">
						<div class="ea-list-group__title"><?php esc_html_e( 'Email', 'wp-ever-accounting' ); ?></div>
						<div class="ea-list-group__text">
							<?php echo ! empty( $customer->get_email() ) ? esc_html( $customer->get_email() ) : '&mdash;'; ?>
						</div>
					</div>
					<div class="ea-list-group__item">
						<div class="ea-list-group__title"><?php esc_html_e( 'VAT Number', 'wp-ever-accounting' ); ?></div>
						<div class="ea-list-group__text">
							<?php echo ! empty( $customer->get_vat_number() ) ? esc_html( $customer->get_vat_number() ) : '&mdash;'; ?>
						</div>
					</div>
					<div class="ea-list-group__item">
						<div class="ea-list-group__title"><?php esc_html_e( 'Website', 'wp-ever-accounting' ); ?></div>
						<div class="ea-list-group__text">
							<?php echo ! empty( $customer->get_website() ) ? esc_html( $customer->get_website() ) : '&mdash;'; ?>
						</div>
					</div>
					<div class="ea-list-group__item">
						<div class="ea-list-group__title"><?php esc_html_e( 'Address', 'wp-ever-accounting' ); ?></div>
						<div class="ea-list-group__text">
							<?php
							$address = eaccounting_format_address(
								array(
									'street'   => $customer->get_street(),
									'city'     => $customer->get_city(),
									'state'    => $customer->get_state(),
									'postcode' => $customer->get_postcode(),
									'country'  => $customer->get_country_nicename(),
								),
								','
							);
							echo ( '' !== $address ) ? wp_kses_post( $address ) : '&mdash;';
							?>
						</div>
					</div>
				</div>

				<div class="ea-card__footer">
					<p class="description">
						<?php
						echo wp_kses_post(
							sprintf(
								/* translators: %s date and %s name */
								esc_html__( 'The customer was created at %1$s by %2$s', 'wp-ever-accounting' ),
								esc_html( eaccounting_date( $customer->get_date_created(), 'F m, Y H:i a' ) ),
								esc_html( eaccounting_get_full_name( $customer->get_creator_id() ) )
							)
						);
						?>
					</p>
				</div>

			</div>
		</div>

	</div>
<?php
eaccounting_enqueue_js(
	"
	jQuery('.del').on('click',function(e){
		if(confirm('Are you sure you want to delete?')){
			return true;
		} else {
			return false;
		}
	});
"
);
