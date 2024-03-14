<?php
/**
 * @link       https://webyx.it/wfe-guide
 * @since      1.0.0
 * @package    webyx-fe
 * @subpackage webyx-fe/templates
 */
	if ( ! defined( 'WPINC' ) ) {
		die;
	} ?>  
	<!doctype html>
	<html <?php language_attributes(); ?>>
		<head>
			<meta charset="<?php bloginfo( 'charset' ); ?>">
			<meta name="viewport" content="width=device-width, initial-scale=1">
			<?php wp_head(); ?>
		</head>
		<body <?php body_class( 'webyx-menu' ); ?>>
		<?php wp_body_open(); ?>
		<?php if ( has_nav_menu( 'webyx-menu' ) ) : ?>
			<header class="webyx-header">
				<nav id="webyx-nav" class="webyx-nav webyx-nav-hide">
					<?php echo get_custom_logo(); ?>
					<div id="webyx-toggle-btn" class="webyx-toggle-btn">
						<div class="webyx-bar"></div>
						<div class="webyx-bar"></div>
						<div class="webyx-bar"></div>
					</div>
					<?php 
						wp_nav_menu( 
							array( 
								'menu'       => get_nav_menu_locations()[ 'webyx-menu' ],
								'items_wrap' => '<ul id="webyx-nav-container" class="webyx-nav-container webyx-scrollbar-menu">%3$s</ul>',
								'after'      => '<span aria-hidden="true" class="webyx-menu-arrow"></span>',
							) 
						); ?>
				</nav>
			</header>
		<?php endif; ?>