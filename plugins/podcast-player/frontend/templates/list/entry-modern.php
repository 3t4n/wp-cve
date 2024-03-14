<?php
/**
 * Podcast pod entry for episode entry list.
 *
 * This template can be overridden by copying it to yourtheme/podcast-player/list/entry.php.
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

<div id="ppe-<?php echo esc_html( $ppe_id ); ?>" class="episode-list__entry pod-entry" data-search-term="<?php echo esc_attr( strtolower( $item['title'] ) ); ?>" data-cats="<?php echo isset( $item['categories'] ) && is_array( $item['categories'] ) ? implode( ' ', array_keys( $item['categories'] ) ) : ''; ?>">
	<div class="pod-entry__wrapper">
		<div class="pod-entry__content">
			<a class="pod-entry__mplay" href="#">
				<span class="ppjs__offscreen"><?php esc_html_e( 'Episode play icon', 'podcast-player' ); ?></span>
				<?php Markup_Fn::the_icon( array( 'icon' => 'pp-play' ) ); ?>
				<?php Markup_Fn::the_icon( array( 'icon' => 'pp-pause' ) ); ?>
			</a>
			<div class="pod-entry__title">
				<a href="<?php echo esc_url( $item['link'] ); ?>"><?php echo esc_html( $item['title'] ); ?></a>
			</div>
			<?php if ( 'post' === $this->args['fetch-method'] ) : ?>
			<a class="pod-entry__mscript pp-entry__mpost" href="<?php echo esc_url( $item['link'] ); ?>" target="_blank">
				<span class="ppjs__offscreen"><?php esc_html_e( 'Episode Description', 'podcast-player' ); ?></span>
				<?php Markup_Fn::the_icon( array( 'icon' => 'pp-tab' ) ); ?>
			</a>	
			<?php elseif ( isset( $item['description'] ) && $item['description'] ) : ?>
			<a class="pod-entry__mscript" href="#">
				<span class="ppjs__offscreen"><?php esc_html_e( 'Episode Description', 'podcast-player' ); ?></span>
				<?php Markup_Fn::the_icon( array( 'icon' => 'pp-text' ) ); ?>
			</a>
			<?php endif; ?>
		</div>
	</div>
</div>
