<?php
/**
 * Site info - global - partial page.
 *
 * @package  Iubenda
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="siteinfo p-4 d-block d-lg-flex justify-content-between">
	<div class="d-block d-lg-flex align-items-center text-center text-lg-left">
		<div class="siteinfo--icon"><img src="<?php echo esc_url( IUBENDA_PLUGIN_URL ); ?>/assets/images/pc_screen_icon.svg"></div>
		<?php
		$url = 'https://www.iubenda.com/account';

		if ( ! empty( iubenda()->settings->links['privacy_policy_generator_edit'] ) ) {
			$url = iubenda()->settings->links['privacy_policy_generator_edit'];
		}
		?>
		<div>
			<h1 class="text-bold text-lg m-0"><?php echo esc_html( wp_parse_url( home_url() )['host'] ); ?></h1>
			<?php if ( isset( $show_products_page ) && $show_products_page ) : ?>
				<a class="link-underline" href="<?php echo esc_url( add_query_arg( array( 'view' => 'plugin-settings' ), iubenda()->base_url ) ); ?>"><?php esc_html_e( 'Plugin settings', 'iubenda' ); ?></a>
			<?php endif; ?>
		</div>
	</div>
	<div class="d-block d-lg-flex mt-lg-0 mt-4 align-items-center text-center text-lg-right">
		<div class="mr-lg-3 mb-lg-0 mb-4">
			<p class="m-0 text-bold text-md"><?php esc_html_e( 'Your rating', 'iubenda' ); ?></p>
			<span class="btn-reset link-underline-dashed text-gray show-modal show-rating-modal"><?php esc_html_e( 'How is it calculated?', 'iubenda' ); ?></span>
		</div>
		<div class="circularBar sm show-modal show-rating-modal" id="iubendaRadarCircularBar" data-perc="<?php echo esc_attr( iubenda()->service_rating->services_percentage() ); ?>"></div>
	</div>
</div>
<hr>
<!-- Modal rating -->
<div id="modal-rating" class="modal">
	<div class="modal__window modal__window--md p-4 p-lg-5">
		<?php
		// Including partial modal-rating.
		require_once IUBENDA_PLUGIN_PATH . '/views/partials/modals/modal-rating.php';
		?>
	</div>
</div>
