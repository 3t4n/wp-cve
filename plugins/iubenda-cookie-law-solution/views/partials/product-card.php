<?php
/**
 * Product card - global - partial page.
 *
 * @package  Iubenda
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Check if $service_key is defined before using it.
if ( ! isset( $service_key ) ) {
	return;
}

// Check if $service_options is defined before using it.
if ( empty( $service_options ) ) {
	return;
}
?>
<div class="service-card">

	<div class="flex-fill d-flex flex-direction-column">

		<div class="d-flex justify-content-end p-3">
			<a target="_blank" href="<?php echo esc_url( iubenda()->settings->links[ "about_{$service_key}" ] ); ?>" class="tooltip-icon">?</a>
		</div>

		<div class="text-center pb-4 flex-fill d-flex align-items-center justify-content-center flex-direction-column">
			<img src="<?php echo esc_url( IUBENDA_PLUGIN_URL ); ?>/assets/images/service_cards/<?php echo esc_html( $service_key ); ?>_icon.svg">
			<h3 class="text-regular text-bold text-gray m-0"><?php echo esc_html( $service_options['label'] ); ?></h3>
		</div>

		<?php
		// Check if the site_id is not entered before.
		$site_id = iub_array_get( iubenda()->options['global_options'], 'site_id' );
		if ( isset( $service_options['settings'] ) && ! empty( $site_id ) ) :
			$service_custom_style = 'none';
			if ( 'true' === (string) $service_options['status'] ) {
				$service_custom_style = 'inline';
			}
			?>

			<ul id="configiration-iubenda-<?php echo esc_html( $service_key ); ?>" class="service-on text-gray text-xs "  id="toggleServiceOn" style="display: <?php echo esc_html( $service_custom_style ); ?>">
				<?php
				foreach ( (array) iub_array_get( $service_options, 'settings' ) as $setting ) :
					$value = '';
					if ( 'Version' === (string) $setting['label'] ) {
						continue;
					}

					if ( 'black' === (string) $setting['value'] ) {
						$value = 'Dark';
					} elseif ( 'white' === (string) $setting['value'] ) {
						$value = 'Light';
					} else {
						$value = $setting['value'];
					}
					?>
					<li class="mr-3"><span class="text-bold"><?php echo esc_html( ucfirst( $setting['label'] ) ); ?>:</span> <?php echo esc_html( ucfirst( $value ) ); ?></li>
				<?php endforeach; ?>
			</ul>
		<?php endif; ?>

		<hr>

		<div class="d-flex align-items-center justify-content-between p-3">
			<div class="switch align-items-center">
				<input type="checkbox" class="service-checkbox" data-redirect="<?php echo esc_url( add_query_arg( array( 'view' => "$service_key-configuration" ), iubenda()->base_url ) ); ?>" data-service-key="iubenda-<?php echo esc_html( $service_key ); ?>" data-service-name="iubenda_<?php echo esc_html( $service_options['name'] ); ?>_solution" id="toggle-<?php echo esc_html( $service_key ); ?>" <?php echo esc_html( 'true' === (string) $service_options['status'] ? 'checked' : '' ); ?> />
				<label for="toggle-<?php echo esc_html( $service_key ); ?>"></label>
				<p class="notification text-xs text-bold text-gray-lighter ml-2" id="<?php echo esc_html( "iubenda-{$service_key}-status-label" ); ?>" data-status-label-off="<?php esc_html_e( 'Service off', 'iubenda' ); ?>"><?php 'true' === (string) $service_options['status'] ? esc_html_e( 'Service on', 'iubenda' ) : esc_html_e( 'Service off', 'iubenda' ); ?></p>
			</div>
			<a class="btn btn-gray-lighter btn-xs" href="<?php echo esc_url( add_query_arg( array( 'view' => "$service_key-configuration" ), iubenda()->base_url ) ); ?>"><?php esc_html_e( 'Configure', 'iubenda' ); ?></a>
		</div>

	</div>
</div>
