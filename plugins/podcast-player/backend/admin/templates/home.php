<?php
/**
 * Podcast player options home page
 *
 * @package Podcast Player
 * @since 3.3.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

use Podcast_Player\Helper\Functions\Getters as Get_Fn;
$feed_index = Get_Fn::get_feed_index();
?>

	<div class="pp-welcome-wrapper">
		<div class="pp-welcome-main">
			<?php if ( $feed_index && is_array( $feed_index ) && ! empty( $feed_index ) ) : ?>
				<?php if ( count( $feed_index ) > 1 ) : ?>
					<h2 class="pp-podcasts-list-title"><?php esc_html_e( 'Your Podcasts', 'podcast-player' ); ?></h2>
				<?php else : ?>
					<h2 class="pp-podcasts-list-title"><?php esc_html_e( 'Your Podcast', 'podcast-player' ); ?></h2>
				<?php endif; ?>
				<div class="pp-podcasts-list-wrapper">
					<ul class="pp-podcasts-list">
						<?php foreach ( $feed_index as $key => $args ) : ?>
							<li class="pp-podcast-list-item" data-podcast="<?php echo esc_attr( $key ); ?>">
								<span class="pp-podcast-info">
									<span class="pp-podcast-title"><?php echo is_array( $args ) && isset( $args['title'] ) ? esc_html( $args['title'] ) : ''; ?></span>
									<span class="pp-podcast-url"><a href="<?php echo is_array( $args ) && isset( $args['url'] ) ? esc_html( $args['url'] ) : '';?>" target="_blank"><?php echo is_array( $args ) && isset( $args['url'] ) ? esc_html( $args['url'] ) : ''; ?></a></span>
								</span>
								<span class="pp-podcast-actions">
									<button class="pp-toolkit-buttons pp-podcast-refresh-btn button">
										<span class="dashicons dashicons-update"></span>
										<span class="pp-toolkit-btn-text">Update</span>
										<span class="pp-loader"></span>
									</button>
									<button class="pp-toolkit-buttons pp-podcast-delete-btn button">
										<span class="dashicons dashicons-trash"></span>
										<span class="pp-toolkit-btn-text">Delete</span>
										<span class="pp-loader"></span>
									</button>
								</span>
							</li>
						<?php endforeach; ?>
					</ul>
				</div>
			<?php else : ?>
				<h3>Welcome to Podcast Player</h3>
				<p>Podcast player offers an easy and versatile way to show and play your <span class="pp-bold">existing podcast</span> on your website. You only need your <span class="pp-bold">podcast’s feed URL</span> to get started. Once you provide the feed URL, the player will automatically pull in your podcast information and episodes.</p>
				<p>You can display the player using Widget, Editor Block, Shortcode or even Elementor plugin. We have created a <a href="<?php echo esc_attr( esc_url( admin_url( 'admin.php?page=pp-help' ) ) ); ?>" class="pp-bold" style="text-decoration: underline; ">Help & Support</a> section to get started with the plugin.</p>
				<p>If you need any more help with our plugin, please feel free to <a href="https://wordpress.org/support/plugin/podcast-player/" target="_blank">open a support ticket</a> or <a href="https://easypodcastpro.com/contact-us-2/" target="_blank">contact us</a>.</p>
			<?php endif; ?>
		</div>
	</div>
