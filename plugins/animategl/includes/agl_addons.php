<div class="wrap">
	<h1><?php esc_html_e( 'AnimateGL Add-ons', 'animate-gl' ); ?></h1>
	<div class="agl-addons">
		<div class="agl-addon">
			<div class="agl-addon-content">
				<h2><?php esc_html_e( 'Entrance Pack', 'animate-gl' ); ?></h2>
				<p><?php esc_html_e( 'Add more animations to AnimateGL with Entrance Pack Add-on.', 'animate-gl' ); ?></p>
				<p>
					<?php
					if (!class_exists("AGL_Entrance_Pack")) {
						$url = esc_url( 'https://codecanyon.net/item/animategl-animations-for-wordpress-entrance-pack/45375689?ref=creativeinteractivemedia&ref2=admin_addons' );
						$label = esc_html__( 'Buy Now $13', 'animate-gl' );
						printf( '<a class="button button-primary button-large addons-button" href="%1$s" target="_blank">%2$s</a>', $url, $label );
					} else {
						$label = esc_html__( 'Installed', 'animate-gl' );
						printf( '<span class="button disabled button-primary button-large addons-button">%s</span>', $label );
					}
					$url = esc_url( 'https://animategl.com/entrance-pack/' );
					$label = esc_html__( 'Live Demo', 'animate-gl' );
					printf( '<a class="button button-secondary button-large addons-button" href="%1$s" target="_blank">%2$s</a>', $url, $label );
					?>

				</p>
			</div>
		</div>
	</div>
</div>

<?php

wp_enqueue_style('agl-admin');