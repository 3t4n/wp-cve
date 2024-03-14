<?php

if ( ! function_exists( 'storefront_credit' ) ) {
	function storefront_credit() {
		?>
		<div class="site-info">
			<?php
			echo wp_kses_post( $GLOBALS['storefront_footer']['footer_credit'] );
			?>
		</div><!-- .site-info -->
		<?php
	}
}
