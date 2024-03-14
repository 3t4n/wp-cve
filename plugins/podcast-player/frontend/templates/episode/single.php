<?php
/**
 * Podcast pod entry for episode entry list.
 *
 * This template can be overridden by copying it to yourtheme/podcast-player/episode/single.php.
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

$item = $this->items[0];

?>
<div class="episode-single__wrapper">
	<div class="episode-single__header">
		<div class="episode-single__title"><?php echo esc_html( $item['title'] ); ?></div>
		<?php if ( isset( $item['author'] ) && $item['author'] ) : ?>
			<div class="episode-single__author">
				<span class="byname"><?php esc_html_e( 'by', 'podcast-player' ); ?></span>
				<span class="single-author"><?php echo esc_html( $item['author'] ); ?></span>
			</div>
		<?php endif; ?>
	</div>
	<?php if ( isset( $item['description'] ) && $item['description'] ) : ?>
		<div class="episode-single__description">
			<?php echo $item['description']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		</div>
	<?php endif; ?>
</div>
