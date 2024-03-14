<?php
/**
 * Render Single Vendor
 * Page: Expenses
 * Tab: Vendors
 *
 * @since       1.0.2
 * @subpackage  Admin/Views/Vendors
 * @package     EverAccounting
 * @var int $vendor_id
 */

defined( 'ABSPATH' ) || exit();

$vendor = eaccounting_get_vendor( $vendor_id );

if ( empty( $vendor ) || ! $vendor->exists() ) {
	wp_die( esc_html__( 'Sorry, Vendor does not exist', 'wp-ever-accounting' ) );
}

$sections = array(
	'transactions' => __( 'Transactions', 'wp-ever-accounting' ),
	'bills'        => __( 'Bills', 'wp-ever-accounting' ),
);

$sections        = apply_filters( 'eaccounting_vendor_sections', $sections );
$first_section   = current( array_keys( $sections ) );
$section         = filter_input( INPUT_GET, 'section', FILTER_SANITIZE_STRING );
$current_section = ! empty( $section ) && array_key_exists( $section, $sections ) ? sanitize_title( $section ) : $first_section;
$edit_url        = eaccounting_admin_url(
	array(
		'page'      => 'ea-expenses',
		'tab'       => 'vendors',
		'action'    => 'edit',
		'vendor_id' => $vendor->get_id(),
	)
);

?>
	<div class="ea-title-section">
		<div>
			<h1 class="wp-heading-inline"><?php esc_html_e( 'Vendors', 'wp-ever-accounting' ); ?></h1>
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
		</div>
	</div>

	<hr class="wp-header-end">

	<div class="ea-page-columns altered ea-single-vendor">
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
								<span class="ea-widget-card__amount"><?php echo esc_html( eaccounting_format_price( $vendor->get_total_paid(), $vendor->get_currency_code() ) ); ?></span>
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
								<span class="ea-widget-card__amount"><?php echo esc_html( eaccounting_format_price( $vendor->get_total_due(), $vendor->get_currency_code() ) ); ?></span>
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
								'tab'       => 'vendors',
								'action'    => 'view',
								'vendor_id' => $vendor_id,
								'section'   => $section_id,
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
						case 'bills':
							include dirname( __FILE__ ) . '/vendors-' . sanitize_file_name( $current_section ) . '.php';
							break;
						default:
							do_action( 'eaccounting_vendor_section_' . $current_section, $vendor );
							break;
					}
					?>
				</div>
			</div>

		</div>

		<div class="ea-page-columns__aside">
			<div class="ea-card">
				<div class="ea-card__header">
					<h3 class="ea-card__title"><?php esc_html_e( 'Vendor Details', 'wp-ever-accounting' ); ?></h3>
					<a href="<?php echo esc_url( $edit_url ); ?>" class="button-secondary"><?php esc_html_e( 'Edit', 'wp-ever-accounting' ); ?></a>
				</div>

				<div class="ea-card__inside">
					<div class="ea-avatar ea-center-block">
						<img src="<?php echo esc_url( $vendor->get_avatar_url() ); ?>" alt="<?php echo esc_html( $vendor->get_name() ); ?>">
					</div>
				</div>

				<div class="ea-list-group">
					<div class="ea-list-group__item">
						<div class="ea-list-group__title"><?php esc_html_e( 'Name', 'wp-ever-accounting' ); ?></div>
						<div class="ea-list-group__text"><?php echo esc_html( $vendor->get_name() ); ?></div>
					</div>
					<div class="ea-list-group__item">
						<div class="ea-list-group__title"><?php esc_html_e( 'Currency', 'wp-ever-accounting' ); ?></div>
						<div class="ea-list-group__text"><?php echo ! empty( $vendor->get_currency_code() ) ? esc_html( $vendor->get_currency_code() ) : '&mdash;'; ?></div>
					</div>
					<div class="ea-list-group__item">
						<div class="ea-list-group__title"><?php esc_html_e( 'Birthdate', 'wp-ever-accounting' ); ?></div>
						<div class="ea-list-group__text"><?php echo ! empty( $vendor->get_birth_date() ) ? esc_html( eaccounting_date( $vendor->get_birth_date() ) ) : '&mdash;'; ?></div>
					</div>
					<div class="ea-list-group__item">
						<div class="ea-list-group__title"><?php esc_html_e( 'Phone', 'wp-ever-accounting' ); ?></div>
						<div class="ea-list-group__text"><?php echo ! empty( $vendor->get_phone() ) ? esc_html( $vendor->get_phone() ) : '&mdash;'; ?></div>
					</div>
					<div class="ea-list-group__item">
						<div class="ea-list-group__title"><?php esc_html_e( 'Email', 'wp-ever-accounting' ); ?></div>
						<div class="ea-list-group__text"><?php echo ! empty( $vendor->get_email() ) ? esc_html( $vendor->get_email() ) : '&mdash;'; ?></div>
					</div>
					<div class="ea-list-group__item">
						<div class="ea-list-group__title"><?php esc_html_e( 'VAT Number', 'wp-ever-accounting' ); ?></div>
						<div class="ea-list-group__text"><?php echo ! empty( $vendor->get_vat_number() ) ? esc_html( $vendor->get_vat_number() ) : '&mdash;'; ?></div>
					</div>
					<div class="ea-list-group__item">
						<div class="ea-list-group__title"><?php esc_html_e( 'Website', 'wp-ever-accounting' ); ?></div>
						<div class="ea-list-group__text"><?php echo ! empty( $vendor->get_website() ) ? esc_html( $vendor->get_website() ) : '&mdash;'; ?></div>
					</div>
					<div class="ea-list-group__item">
						<div class="ea-list-group__title"><?php esc_html_e( 'Address', 'wp-ever-accounting' ); ?></div>
						<div class="ea-list-group__text">
							<?php
							$address = eaccounting_format_address(
								array(
									'street'   => $vendor->get_street(),
									'city'     => $vendor->get_city(),
									'state'    => $vendor->get_state(),
									'postcode' => $vendor->get_postcode(),
									'country'  => $vendor->get_country_nicename(),
								),
								','
							);
							echo ! empty( $address ) ? esc_html( $address ) : '&mdash;';
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
								esc_html__( 'The vendor was created at %1$s by %2$s', 'wp-ever-accounting' ),
								eaccounting_date( $vendor->get_date_created(), 'F m, Y H:i a' ),
								eaccounting_get_full_name( $vendor->get_creator_id() )
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

