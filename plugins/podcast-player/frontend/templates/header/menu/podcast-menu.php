<?php
/**
 * Podcast pod entry for episode entry list.
 *
 * This template can be overridden by copying it to yourtheme/podcast-player/header/menu/podcast-menu.php.
 *
 * HOWEVER, on occasion Podcast Player will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @package Podcast Player
 * @version 1.0.0
 * @since   1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

use Podcast_Player\Helper\Functions\Markup as Markup_Fn;

?>

<nav id="podcast-menu-<?php echo absint( $this->instance ); ?>" class="podcast-menu-<?php echo absint( $this->instance ); ?> podcast-menu" aria-label="<?php esc_html_e( 'Podcast Subscription Menu', 'podcast-player' ); ?>">
	<h2 class="ppjs__offscreen">
		<?php esc_html_e( 'Podcast Subscription Menu', 'podcast-player' ); ?>
	</h2>
	<?php
	wp_nav_menu(
		array(
			'menu_class'  => 'pod-menu',
			'menu'        => wp_get_nav_menu_object( $this->args['menu'] ),
			'depth'       => 1,
			'fallback_cb' => '',
			'link_before' => '<span class="subscribe-item">' . Markup_Fn::get_icon( array( 'icon' => 'pp-www' ) ) . '<span class="sub-text"><span class="sub-item-text">',
			'link_after'  => '</span></span></span>',
		)
	);
	?>
</nav>
