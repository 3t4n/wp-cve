<?php
/**
 * Rating - global - partial modal page.
 *
 * @package  Iubenda
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div>
	<div class="text-center mb-5">
		<h1 class="text-xl">
			<?php esc_html_e( 'Your rating', 'iubenda' ); ?>
		</h1>
		<div class="circularBar" id="iubendaRadarCircularBar" data-perc="<?php echo esc_attr( iubenda()->service_rating->services_percentage() ); ?>"></div>
		<p class="text-gray text-md"><?php esc_html_e( 'Here’s how we calculate your rating.', 'iubenda' ); ?></p>
	</div>
	<ul class="list_radar list_radar--block">
		<?php
		foreach ( iubenda()->service_rating->rating_calculation_components() as $rating_calculation_component_key => $rating_calculation_component_value ) :
			$_status = 'off';
			if ( (bool) $rating_calculation_component_value ) {
				$_status = 'on';
			}

			$_key = $rating_calculation_component_key;
			if ( 'cons' === $rating_calculation_component_key ) {
				$_key = 'cs';
			}
			?>
				<li class="list_radar__item mb-4 list_radar__item--<?php echo esc_attr( $_status ); ?> iubenda-<?php echo esc_attr( $_key ); ?>-item">
				<figure><img src="<?php echo esc_url( IUBENDA_PLUGIN_URL ); ?>/assets/images/list_radar_<?php echo esc_html( $rating_calculation_component_key ); ?>.svg"></figure>
				<div>
					<h2 class="m-0 mb-2"><?php echo esc_html( $rating_calculation_component_value['label'] ); ?></h2>
					<p><?php echo esc_html( $rating_calculation_component_value['paragraph'] ); ?> <a target="_blank" href="<?php echo esc_url( iubenda()->settings->links[ "how_{$rating_calculation_component_key}_rate" ] ); ?>" class="link-underline"><?php esc_html_e( 'Learn More', 'iubenda' ); ?></a></p>
				</div>
			</li>
		<?php endforeach; ?>
	</ul>
</div>
