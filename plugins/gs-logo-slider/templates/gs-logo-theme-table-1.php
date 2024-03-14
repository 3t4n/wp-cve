<?php
namespace GSLOGO;

/**
 * GS Logo Slider - Table Layout 1
 * @author GS Plugins <hello@gsplugins.com>
 * 
 * This template can be overridden by copying it to yourtheme/gs-logo/gs-logo-theme-table-1.php
 * 
 * @package GS_Logo_Slider/Templates
 * @version 1.0.0
 */

global $gs_logo_loop;

?>

<div class="gs_logo_container gs-logos-table gs_logo_table1">

	<?php if ( $gs_logo_loop->have_posts() ) : ?>

		<!-- Logo Table Header -->
		<?php include Template_Loader::locate_template( 'partials/gs-logo-table-header.php' ); ?>

		<?php while ( $gs_logo_loop->have_posts() ) : $gs_logo_loop->the_post(); ?>

			<div class="gs-logos-table-row">

				<!-- Logo Image -->
				<div class="gs-logos-table-cell gsc-image">
					<?php include Template_Loader::locate_template( 'partials/gs-logo-layout-image.php' ); ?>
				</div>

				<!-- Logo Title -->
				<div class="gs-logos-table-cell gsc-name">
					<?php include Template_Loader::locate_template( 'partials/gs-logo-layout-title.php' ); ?>
				</div>

				<!-- Logo Details -->
				<div class="gs-logos-table-cell gsc-desc">
					<?php include Template_Loader::locate_template( 'partials/gs-logo-layout-details.php' ); ?>
				</div>

			</div>

		<?php endwhile; ?>
		
	<?php else: ?>

		<?php include Template_Loader::locate_template( 'partials/gs-logo-empty.php' ); ?>
		
	<?php endif; ?>

</div>