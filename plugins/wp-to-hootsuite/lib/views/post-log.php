<?php
/**
 * Outputs the Logs table when viewing/editing an individual Post.
 *
 * @package WP_To_Social_Pro
 * @author  WP Zinc
 */

?>
<div class="wpzinc-option">
	<div class="full">
		<table class="widefat wp-to-social-log">
			<thead>
				<tr>
					<th><?php esc_html_e( 'Request Sent', 'wp-to-hootsuite' ); ?></th>
					<th><?php esc_html_e( 'Action', 'wp-to-hootsuite' ); ?></th>
					<th><?php esc_html_e( 'Profile', 'wp-to-hootsuite' ); ?></th>
					<th><?php esc_html_e( 'Status Text', 'wp-to-hootsuite' ); ?></th>
					<th><?php esc_html_e( 'Result', 'wp-to-hootsuite' ); ?></th>
					<th><?php esc_html_e( 'Response', 'wp-to-hootsuite' ); ?></th>
					<th>
						<?php
						echo esc_html(
							sprintf(
							/* translators: Social Media Service Name (Buffer, Hootsuite, SocialPilot) */
								__( '%s: Status Created At', 'wp-to-hootsuite' ),
								$this->base->plugin->account
							)
						);
						?>
					</th>
					<th>
						<?php
						echo esc_html(
							sprintf(
								/* translators: Social Media Service Name (Buffer, Hootsuite, SocialPilot) */
								__( '%s: Status Scheduled For', 'wp-to-hootsuite' ),
								$this->base->plugin->account
							)
						);
						?>
					</th>
				</tr>
			</thead>
			<tbody>
				<?php
				echo $this->base->get_class( 'log' )->build_log_table_output( $log ); // phpcs:ignore WordPress.Security.EscapeOutput
				?>
			</tbody>
		</table>
	</div>
</div>
<div class="wpzinc-option">
	<div class="full">
		<a href="post.php?post=<?php echo esc_attr( $post->ID ); ?>&action=edit&<?php echo esc_attr( $this->base->plugin->name ); ?>-refresh-log=1" class="<?php echo esc_attr( $this->base->plugin->name ); ?>-refresh-log button" data-action="<?php echo esc_attr( $this->base->plugin->filter_name ); ?>_get_log" data-target="#<?php echo esc_attr( $this->base->plugin->name ); ?>-log">
			<?php esc_html_e( 'Refresh Log', 'wp-to-hootsuite' ); ?>
		</a>
		<a href="post.php?post=<?php echo esc_attr( $post->ID ); ?>&action=edit&<?php echo esc_attr( $this->base->plugin->name ); ?>-export-log=1" class="<?php echo esc_attr( $this->base->plugin->name ); ?>-export-log button">
			<?php esc_html_e( 'Export Log', 'wp-to-hootsuite' ); ?>
		</a>
		<a href="post.php?post=<?php echo esc_attr( $post->ID ); ?>&action=edit&<?php echo esc_attr( $this->base->plugin->name ); ?>-clear-log=1" class="<?php echo esc_attr( $this->base->plugin->name ); ?>-clear-log button wpzinc-button-red" data-action="<?php echo esc_attr( $this->base->plugin->filter_name ); ?>_clear_log" data-target="#<?php echo esc_attr( $this->base->plugin->name ); ?>-log" data-message="<?php esc_attr_e( 'Are you sure you want to clear the logs associated with this Post?', 'wp-to-hootsuite' ); ?>">
			<?php esc_html_e( 'Clear Log', 'wp-to-hootsuite' ); ?>
		</a>
	</div>
</div>
