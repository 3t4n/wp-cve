<?php
namespace GSLOGO;

/**
 * GS Logo Slider - List Layout 1
 * @author GS Plugins <hello@gsplugins.com>
 * 
 * This template can be overridden by copying it to yourtheme/gs-logo/gs-logo-theme-list-1.php
 * 
 * @package GS_Logo_Slider/Templates
 * @version 1.0.0
 */

global $gs_logo_loop;

?>

<div class="gs_logo_container gs_logo_container_list">

	<?php if ( $gs_logo_loop->have_posts() ) : ?>

		<?php while ( $gs_logo_loop->have_posts() ) : $gs_logo_loop->the_post(); ?>

			<div class="gs_logo_single--wrapper">
				<div class="gs_logo_single">
					
					<div class="gs_logo--image-area">
						<!-- Logo Image -->
						<?php include Template_Loader::locate_template( 'partials/gs-logo-layout-image.php' ); ?>
					</div>

					<div class="gs_logo--details-area">
						<!-- Logo Title -->
						<?php include Template_Loader::locate_template( 'partials/gs-logo-layout-title.php' ); ?>
						<!-- Logo Category -->
						<?php include Template_Loader::locate_template( 'partials/gs-logo-layout-cat.php' ); ?>
						<!-- Logo Details -->
						<?php include Template_Loader::locate_template( 'partials/gs-logo-layout-details.php' ); ?>
					</div>

				</div>
			</div>

		<?php endwhile; ?>
		
	<?php else: ?>

		<?php include Template_Loader::locate_template( 'partials/gs-logo-empty.php' ); ?>
		
	<?php endif; ?>

</div>