<?php
/**
 * Podcast player toolkit page
 *
 * @package Podcast Player
 * @since 3.3.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

use Podcast_Player\Helper\Functions\Getters as Get_Fn;

$feed_index = Get_Fn::get_feed_index();
$keep_old   = Get_Fn::get_plugin_option( 'keep_old' );
?>

<div class="pp-toolkit-page">
	<div class="pp-toolkit-wrapper">
		<h3 class="pp-toolkit-title"><span>Feed Updation Tool</span><span class="dashicons dashicons-arrow-down-alt2"></span></h3>
		<div class="pp-toolkit-content">
			<?php if ( $feed_index && is_array( $feed_index ) && ! empty( $feed_index ) ) : ?>
				<?php
				$feed_index = array_merge(
					array( '' => esc_html__( 'Select a Podcast to update / reset', 'podcast-player' ) ),
					$feed_index
				);
				?>
				<select id="pp-feed-index" name="pp-feed-index" class="select-pp-feed-index">
					<?php
					foreach ( $feed_index as $key => $label ) {
						if ( is_array( $label ) ) {
							$label = isset( $label['title'] ) ? $label['title'] : '';
						}
						echo '<option value="' . esc_attr( $key ) . '">' . esc_html( $label ) . '</option>';
					}
					?>
				</select>
				<button class="pp-toolkit-buttons pp-feed-refresh button">
					<span class="dashicons dashicons-update"></span>
					<span class="pp-toolkit-btn-text">Update Podcast</span>
				</button>
				<button class="pp-toolkit-buttons pp-feed-del button">
					<span class="dashicons dashicons-trash"></span>
					<span class="pp-toolkit-btn-text">Delete Podcast</span>
				</button>
				<div class="pp-toolkit-del-confirm">
					<div class="pp-toolkit-del-msg">
						<?php esc_html_e( 'All stored data for the podcast will be deleted. Please confirm to delete podcast.', 'podcast-player' ); ?>
					</div>
					<button class="pp-toolkit-buttons pp-feed-reset button">
						<span class="pp-toolkit-btn-text">Delete</span>
					</button>
					<button class="pp-toolkit-buttons pp-feed-cancel button">
						<span class="pp-toolkit-btn-text">Cancel</span>
					</button>
				</div>
				<div class="pp-toolkit-feedback">
					<span class="dashicons dashicons-update"></span>
					<span class="dashicons dashicons-no"></span>
					<span class="dashicons dashicons-yes"></span>
					<span class="pp-feedback"></span>
				</div>
			<?php else : ?>
				<div style="font-size: 20px !important; font-weight: bold; margin-bottom: 15px;"><?php esc_html_e( 'No podcast to update or Refresh.', 'podcast-player' ); ?></div>
				<div style="font-size: 16px; margin-bottom: 5px;" class="pp-sub-title"><?php esc_html_e( 'Possible Reasons :-', 'podcast-player' ); ?></div>
				<div style="font-size: 15px;">You are new to Podcast Player and did not add any podcast yet. Do not worry, just head to our <a href="https://easypodcastpro.com/docs7/" target="_blank">documenation page</a> to know how to add podcast player to your site. If that's not enough just <a href="https://easypodcastpro.com/contact-us-2/">contact us</a> and we will help you out.</div>
				<div style="margin: 20px 0;">OR</div>
				<div style="font-size: 15px;">All of your podcasts are already updated/ refreshed.</div>
			<?php endif; ?>
		</div>
	</div>
</div>
