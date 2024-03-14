<?php
/**
 * Template: Single Food Menu.
 *
 * @package RT_FoodMenu
 */

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

get_header();

while ( have_posts() ) :
	the_post();
	?>
	<main id="main" class="site-main" rol="main">
		<article id="post-<?php the_ID(); ?>" <?php post_class( 'fmp' ); ?>>
			<div class="fmp-container fmp-wrapper fmp-single-food">
				<div class="fmp-row">
					<?php
					do_action( 'fmp_single_summery' );
					?>
				</div><!-- fmp-row  -->

				<?php
				do_action( 'fmp_single_details' );
				?>
			</div> <!-- fmp-wrapper  -->
		</article>
	</main>
	<?php
endwhile;

get_footer();
