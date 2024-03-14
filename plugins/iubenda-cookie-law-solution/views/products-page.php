<?php
/**
 * Products - global - page.
 *
 * @package  Iubenda
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
// Require once partial header.
require_once IUBENDA_PLUGIN_PATH . '/views/partials/header.php';
?>
<div class="main-box">
	<?php
	// Require once partial site-info.
	require_once IUBENDA_PLUGIN_PATH . '/views/partials/site-info.php';
	?>
	<div class="p-3 m-3">
		<?php
			$result = array_filter(
				array_column( iubenda()->settings->services, 'status' ),
				function ( $service ) {
					return ( stripos( $service, 'false' ) === false );
				}
			);
			if ( ! $result ) {
				?>
				<div class="alert alert--failure is-dismissible m-4">
					<div class="alert__icon p-4">
						<img src="<?php echo esc_url( IUBENDA_PLUGIN_URL ) . '/assets/images/banner_failure.svg'; ?>">
					</div>
					<p id="products-page-alert-text" class="text-regular"><?php esc_html_e( 'It seems that you have not activated any of our services, we recommend you to activate them and increase your level of compliance and avoid risking fines.', 'iubenda' ); ?></p>
					<button class="btn-close mr-3 notice-dismiss">×</button>
				</div>
				<?php
			}

			if ( iubenda()->notice->has_inside_plugin_notice() ) {
				iubenda()->notice->show_notice_inside_plugin();
			}
			?>
		<div class="configure-services-cards">
			<?php
			foreach ( iubenda()->settings->services as $service_key => $service_options ) :
				// Including partial product(s)-card.
				require IUBENDA_PLUGIN_PATH . '/views/partials/product-card.php';
			endforeach;
			?>
		</div>
	</div>
</div>
<?php
// Including partial footer.
require_once IUBENDA_PLUGIN_PATH . 'views/partials/footer.php';
?>
