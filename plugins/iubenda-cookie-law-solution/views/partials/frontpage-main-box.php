<?php
/**
 * Frontpage main box - global - partial page.
 *
 * @package  Iubenda
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div id="alert-div-container" class="hidden">
	<div id="alert-div" class="alert is-dismissible m-4">
		<div class="alert__icon p-4" style="display: flex; align-items:center; justify-content: center;">
			<img id="alert-image" src="<?php echo esc_url( IUBENDA_PLUGIN_URL ); ?>/assets/images/banner_failure.svg">
		</div>
		<p id="alert-message" class="text-regular text-left"></p>
		<button class="btn-close mr-3 notice-dismiss">×</button>
	</div>
</div>

<?php

// Including partial welcome-screen-header.
require_once IUBENDA_PLUGIN_PATH . 'views/partials/welcome-screen-header.php';

$radar = new Radar_Service();

if ( ! empty( $radar->api_configuration ) && 'completed' === (string) iub_array_get( $radar->api_configuration, 'status' ) ) {
	// Including partial header-scanned.
	require_once IUBENDA_PLUGIN_PATH . '/views/partials/header-scanned.php';
} else {
	?>
	<div class="p-4 my-5 text-center">
		<span class="inline-spinner lg text-gray"></span>
		<p class="m-0 mt-3 text-md"><?php esc_html_e( 'Analyzing your website', 'iubenda' ); ?>...</p>
	</div>

	<div class="mt-4 pt-4">
		<h2 class="text-md m-0 mb-4"><?php esc_html_e( 'This is what you may need to be compliant', 'iubenda' ); ?>:</h2>
	</div>
	<?php
}
?>


<div>
	<ul class="list_radar m-0 mt-4 px-4">
		<?php
		foreach ( iubenda()->service_rating->rating_calculation_components() as $key => $service ) :
			if ( 'completed' === (string) iub_array_get( $radar->api_configuration, 'status' ) ) {
				if ( true === (bool) iub_array_get( $service, 'status' ) ) {
					$_status = 'on';
				} else {
					$_status = 'off';
				}
			} else {
				$_status = null;
			}
			?>
			<li class="list_radar__item my-5 my-lg-0 mx-lg-3 list_radar__item--<?php echo esc_html( $_status ); ?> iubenda-<?php echo esc_html( $key ); ?>-item">
				<figure><img src="<?php echo esc_url( IUBENDA_PLUGIN_URL ); ?>/assets/images/list_radar_<?php echo esc_html( $key ); ?>.svg"></figure>
				<p class="text-bold m-0 mx-4"><?php echo esc_html( $service['label'] ); ?></p>
			</li>
		<?php endforeach; ?>
	</ul>
</div>
