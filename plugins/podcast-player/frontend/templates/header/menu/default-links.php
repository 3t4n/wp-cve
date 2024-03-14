<?php
/**
 * Podcast pod entry for episode entry list.
 *
 * This template can be overridden by copying it to yourtheme/podcast-player/header/menu/default-links.php.
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

use Podcast_Player\Helper\Functions\Validation as Validation_Fn;

?>

<nav id="podcast-menu-<?php echo absint( $this->instance ); ?>" class="podcast-menu-<?php echo absint( $this->instance ); ?> podcast-menu" aria-label="<?php esc_html_e( 'Podcast Subscription Menu', 'podcast-player' ); ?>">
	<h2 class="ppjs__offscreen">
		<?php esc_html_e( 'Podcast Subscription Menu', 'podcast-player' ); ?>
	</h2>
	<ul class="pod-menu">
		<?php if ( Validation_Fn::is_valid_url( $this->info['link'] ) ) : ?>
			<li class="menu-item"><a href="<?php echo esc_url( $this->info['link'] ); ?>" target="_blank"><?php esc_html_e( 'Visit Website', 'podcast-player' ); ?></a></li>
		<?php endif; ?>
		<?php if ( Validation_Fn::is_valid_url( $this->args['url'] ) ) : ?>
			<li class="menu-item"><a href="<?php echo esc_url( $this->args['url'] ); ?>" target="_blank"><?php esc_html_e( 'RSS Feed', 'podcast-player' ); ?></a></li>
		<?php endif; ?>
	</ul>
</nav>
