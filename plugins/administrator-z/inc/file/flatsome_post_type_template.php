<?php
/**
 * The blog template file.
 *
 * @package          Flatsome\Templates
 * @flatsome-version 3.16.0
 */

get_header();

?>

<div id="content" class="<?php echo get_post_type(); ?>-wrapper <?php echo get_post_type(); ?>-single adminz_uxbuilder_template">
	<?php 
		// get_template_part( 'template-parts/posts/layout', get_theme_mod('blog_post_layout','right-sidebar') ); 
		if(isset($GLOBALS['adminz_template_block_id_for_'.get_the_ID()])){
			$template_block_id = $GLOBALS['adminz_template_block_id_for_'.get_the_ID()];
		}

		if($template_block_id){
			$_post_content = get_post_field( 'post_content', $template_block_id );
			if($_post_content){
				echo do_shortcode( $_post_content );
			}
		}
	?>
</div>

<?php get_footer();
