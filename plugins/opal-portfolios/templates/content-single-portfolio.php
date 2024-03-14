<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$layout = get_theme_mod( 'opalportfolio_layout_single_position' );
$navigation = get_theme_mod( 'opalportfolio_navigation_single_position' ) ? 'yes' : 'no' ;
?>
<article id="post-<?php the_ID(); ?>" itemscope itemtype="http://schema.org/Portfolio" <?php post_class(); ?>>
	<div class="row single-<?php echo esc_attr($layout); ?>">
		
		<?php echo Opalportfolio_Template_Loader::get_template_part( 'sidebar/left-sidebar-check'); ?>

		<?php 
			if(  $layout === 'layout_1') {
				echo Opalportfolio_Template_Loader::get_template_part( 'single-portfolios/layout-1'); 
			}else {
				echo Opalportfolio_Template_Loader::get_template_part( 'single-portfolios/layout-2'); 
			}
			if($navigation === 'no') : 
				opalportfolio_single_navigation();
			endif;	
		?>
		<?php echo Opalportfolio_Template_Loader::get_template_part( 'sidebar/right-sidebar-check'); ?>

		

	</div> <!-- //.row -->
	<meta itemprop="url" content="<?php the_permalink(); ?>" />
</article><!-- #post-## -->

