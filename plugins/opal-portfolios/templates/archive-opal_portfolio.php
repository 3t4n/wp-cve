	<?php
/**
 * The template for displaying Category pages
 * @package WpOpal
 * @subpackage Crewservice
 * @since Crewservice 1.0
 */

get_header();


$column = get_theme_mod('opalportfolio_column_archive_position') ? get_theme_mod('opalportfolio_column_archive_position') : 3;
$colclass = floor(12/$column); 
$showmode = get_theme_mod('opalportfolio_layout_archive_position') ? get_theme_mod('opalportfolio_layout_archive_position') : "classic";
$show_category = get_theme_mod('opalportfolio_category_archive_position') ? 'yes' : 'no' ;
$show_description = get_theme_mod('opalportfolio_description_archive_position') ? 'yes' : 'no' ;
$show_readmore = get_theme_mod('opalportfolio_readmore_archive_position') ? 'yes' : 'no' ;
//test
$args_template = array(
	'show_category'		=> $show_category,
	'show_description'	=> $show_description,
	'show_readmore'		=> $show_readmore,
);
?>
<section id="main-container" class="container">
	<div class="row">
		<?php echo Opalportfolio_Template_Loader::get_template_part( 'sidebar/left-sidebar-check'); ?>
			<div id="content" class="site-content portfolio-main-wrapper lightgallery-detect-container" role="main">
				<?php if ( have_posts() ) : ?>
					<div class="service-archive-services grid-style-<?php echo esc_attr($showmode); ?>">
						<div class="row">
							<?php $cnt=0; while ( have_posts() ) : the_post();
							$cls = '';

							if( $cnt++%$column==0 ){
								$cls .= ' first-child';
							}
							if ($showmode === "classic") : ?>
								<div class="grid-item wp-col-lg-<?php echo esc_attr($colclass); ?> wp-col-md-<?php echo esc_attr($colclass); ?> wp-col-sm-<?php echo esc_attr($colclass); ?> <?php echo esc_attr($cls); ?>">
									<?php echo Opalportfolio_Template_Loader::get_template_part( 'content-portfolio-classic', $args_template ); ?>
								</div>
							<?php elseif($showmode === "boxed") : ?>
								<div class="grid-item wp-col-lg-<?php echo esc_attr($colclass); ?> wp-col-md-<?php echo esc_attr($colclass); ?> wp-col-sm-<?php echo esc_attr($colclass); ?> <?php echo esc_attr($cls); ?>">
									<?php echo Opalportfolio_Template_Loader::get_template_part( 'content-portfolio-boxed', $args_template ); ?>
								</div>
							<?php else: ?>
								<div class="grid-item wp-col-md-12">
									<?php echo Opalportfolio_Template_Loader::get_template_part( 'content-portfolio-list', $args_template ); ?>
								</div>
							<?php endif; ?>
							<?php endwhile; ?>
						</div>
					</div>
			<?php  //opalportfolio_pagination(); ?>
			<?php else : ?>
				<?php echo Opalportfolio_Template_Loader::get_template_part( 'content-data-none' ); ?>
			<?php endif; ?>

			</div><!-- #content -->
		<?php echo Opalportfolio_Template_Loader::get_template_part( 'sidebar/right-sidebar-check'); ?>
	</div>
<?php get_sidebar( 'content' ); ?>
</section>
<?php
get_footer();

