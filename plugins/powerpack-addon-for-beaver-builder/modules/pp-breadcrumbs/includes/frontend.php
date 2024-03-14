<div class="pp-breadcrumbs pp-breadcrumbs-<?php echo $settings->seo_type; ?>">
	<?php
	if ( 'yoast' === $settings->seo_type && function_exists( 'yoast_breadcrumb' ) ) {
		yoast_breadcrumb( '<nav id="breadcrumbs" class="breadcrumbs" aria-label="' . __( 'Breadcrumbs', 'bb-powerpack' ) . '">','</nav>' );
	} elseif ( 'rankmath' === $settings->seo_type && function_exists( 'rank_math_the_breadcrumbs' ) ) {
		rank_math_the_breadcrumbs();
	} elseif ( 'navxt' == $settings->seo_type && function_exists( 'bcn_display' ) ) {
		bcn_display();
	} elseif ( 'seopress' == $settings->seo_type && function_exists( 'seopress_display_breadcrumbs' ) ) {
		seopress_display_breadcrumbs();
	}
	?>
</div>