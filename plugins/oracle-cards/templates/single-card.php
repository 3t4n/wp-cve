<?php
/**
 * The template for displaying all single cards
 *
 * This template can be overridden by copying it to yourtheme/oracle-cards/single-card.php.
 *
 * @package Oracle Cards
 * @subpackage Templates
 * @since 1.0
 * @version 1.0
 */

?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
	<head>
		<meta charset="<?php bloginfo( 'charset' ); ?>">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="profile" href="http://gmpg.org/xfn/11">
		<?php wp_head(); ?>
	</head>
	<body <?php body_class(); ?>>
		<div id="page" class="site">
			<div class="site-content-contain">
				<div id="content" class="site-content">
					<div id="primary" class="content-area" style="position:relative">
						<main id="main" class="site-main" style="margin-left:0;position:relative;left:50%;max-width:400px;-o-transform:translateX(-50%);-ms-transform:translateX(-50%);-moz-transform:translateX(-50%);-webkit-transform:translateX(-50%);transform:translateX(-50%);">
							<?php
							/* Start the Loop */
							while ( have_posts() ) : the_post();
								?>
								<h1 class="eos-card-title"><?php the_title(); ?></h1>
								<div class="eos-card-img"><?php the_post_thumbnail(); ?></div>
								<div class="eos-card-description"><?php the_content(); ?></div>
								
								<nav class="nav-single">
									<h3 class="assistive-text"><?php _e( 'Post navigation', 'oracle-cards' ); ?></h3>
									<span class="nav-previous"><?php previous_post_link( '%link','<span class="meta-nav"><i style="margin-right:10px;" class="fa fa-angle-double-left"></i></span>'.__( 'Previous','oracle-cards' ) ); ?></span>
									<span class="nav-next"><?php next_post_link( '%link', __( 'Next','oracle-cards' ).'<span class="meta-nav"><i style="margin-left:10px;" class="fa fa-angle-double-right"></i></span>' ); ?></span>
								</nav><!-- .nav-single -->						
								<?php

							endwhile; // End of the loop.
							?>

						</main><!-- #main -->
					</div><!-- #primary -->
				</div><!-- #content -->
			</div><!-- .site-content-contain -->
		</div><!-- #page -->
		<?php wp_footer(); ?>
	</body>
</html>