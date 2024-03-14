<?php
/**
 * Template file to display popup single pages
 * Removes clutter and all theme elements from the page, leaving just the content area
 */

?><!DOCTYPE html>
<html <?php language_attributes(); ?> style="background:#ccc;padding-top:50px">
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?> style="background:#fff;width:<?php echo themify_popup_get( 'popup_width' ), themify_popup_get( 'popup_width_unit', 'px' ); ?>;min-height:<?php echo themify_popup_get( 'popup_height' ) , themify_popup_get( 'popup_height_unit', 'px' ); ?>;margin:auto" id="themify-popup-<?php the_id(); ?>">

	<?php if( have_posts() ) : while( have_posts() ) : the_post(); ?>
		<?php the_content(); ?>

		<style><?php echo themify_popup_get_custom_css(); ?></style>

	<?php endwhile; endif; ?>

	<?php wp_footer(); ?>
</body>
</html>