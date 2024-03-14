<?php
namespace GSLOGO;

/**
 * GS Logo Slider - Grid Layout 1
 * @author GS Plugins <hello@gsplugins.com>
 * 
 * This template can be overridden by copying it to yourtheme/gs-logo/gs-logo-theme-grid-1.php
 * 
 * @package GS_Logo_Slider/Templates
 * @version 1.0.0
 */

global $gs_logo_loop;

?>

<div class="gs_logo_container gs_logo_container_grid gs_logo_fix_height_and_center" style="justify-content:<?php echo $gs_l_align; ?>">

	<?php if ( $gs_logo_loop->have_posts() ) : ?>

		<?php while ( $gs_logo_loop->have_posts() ) : $gs_logo_loop->the_post(); ?>

			<div class="gs_logo_single--wrapper">
				<div class="gs_logo_single">
					
					<!-- Logo Image -->
					<?php include Template_Loader::locate_template( 'partials/gs-logo-layout-image.php' ); ?>

					<!-- Logo Title -->
					<?php include Template_Loader::locate_template( 'partials/gs-logo-layout-title.php' ); ?>

					<!-- Logo Category -->
					<?php include Template_Loader::locate_template( 'partials/gs-logo-layout-cat.php' ); ?>

				</div>
			</div>

		<?php endwhile; ?>
		
	<?php else: ?>

		<?php include Template_Loader::locate_template( 'partials/gs-logo-empty.php' ); ?>
		
	<?php endif; ?>

</div>