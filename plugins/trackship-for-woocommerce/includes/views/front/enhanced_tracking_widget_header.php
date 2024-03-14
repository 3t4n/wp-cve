<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="tracking_number_wrap <?php echo str_replace( ' ', '', $tracking_number ) == $url_tracking ? 'checked' : ''; ?>">
	<div style="display: flex;">
		<?php if ( ! $hide_tracking_provider_image && $provider_image ) { ?>
			<div class="provider_image_div" >
				<img class="provider_image" src="<?php echo esc_url( $provider_image ); ?>">
			</div>
		<?php } ?>

		<div class="tracking_number_div">
			<ul>
				<li>
					<span class="tracking_page_provider_name"><?php echo esc_html( trackship_for_woocommerce()->actions->get_provider_name( $provider_name ) ); ?></span>
				</li>
				<li>
					<?php if ( $ts_link_to_carrier && $tracking_link ) { ?>
						<a href="<?php echo esc_url( $tracking_link ); ?>" target="blank"><strong><?php esc_html_e( $tracking_number ); ?></strong></a>
					<?php } else { ?>
						<strong><?php esc_html_e( $tracking_number ); ?></strong>
					<?php } ?>
				</li>
			</ul>
		</div>
		<span class="accordian-arrow ts-right"></span>
	</div>
</div>
