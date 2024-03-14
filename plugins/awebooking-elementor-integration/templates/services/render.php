<?php
$args = [
	'orderby'        => $settings['orderby'],
	'order'          => $settings['order'],
	'posts_per_page' => $settings['per_page'],
];

$services = abrs_list_services( $args );

if ( ! $services ) {
	return;
}
?>
<div class="awebooking-block">
	<?php foreach ( $services as $service ) : ?>
		<article id="service-<?php echo absint( $service->get_id() ); ?>" class="list-service list-room">
			<div class="list-room__wrap">
				<div class="list-room__media">
					<?php print abrs_get_thumbnail( $service->get_id(), 'awebooking_thumbnail' ); // WPCS: XSS OK. ?>
				</div>

				<div class="list-room__info">
					<header class="list-room__header">
						<h2 class="list-room__title"><?php echo esc_html( $service->get( 'name' ) ); ?></h2>
						<?php if ( $service->get_amount() ) : ?>
							<p class="list-room__price">
								<?php abrs_price( $service->get_amount() ); ?>
							</p>
						<?php endif; ?>
					</header>

					<div class="list-room__container">
						<?php if ( $service->get( 'description' ) ) : ?>
							<div class="list-room__desc">
								<?php echo esc_html( $service->get( 'description' ) ); ?>
							</div>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</article>
	<?php endforeach; ?>
</div>
