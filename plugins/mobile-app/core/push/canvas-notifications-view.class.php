<?php

if ( ! defined( 'CANVAS_DIR' ) ) {
	die();
}

class CanvasNotificationsView {


	public static function show_json( $result ) {
		header( 'Content-type: application/json' );
		echo json_encode( $result );
	}

	public static function show_true_false( $result ) {
		echo $result ? 'true' : '';
	}

	public static function show_chart( $notifications ) {
		if ( is_array( $notifications ) && count( $notifications ) > 0 ) {
			?>
			<script type="text/javascript">
				google.load("visualization", "1", {packages: ["corechart"], callback: canvasDrawChart});

				function canvasDrawChart() {
					<?php self::push_notification_chart_data( $notifications ); ?>


					var options = {
						title: 'Latest Notifications',
						hAxis: {title: 'Notifications'}
					};

					var chart = new google.visualization.ColumnChart(document.getElementById('notifications_chart'));
					chart.draw(data, options);
				}
			</script>
			<div id="notifications_chart" style="width: 100%; height: 200px; margin: 0 auto; margin-bottom: 20px;"></div>
			<?Php
		}
	}

	private static function push_notification_chart_data( $notifications ) {
		$data = 'var data = google.visualization.arrayToDataTable([
		[\'Date\', \'Count\'],';

		$dates = array();
		if ( count( $notifications ) ) {
			foreach ( $notifications as $notification ) {
				if ( date( 'mY' ) === date( 'mY', $notification->time ) ) {
					// same month so group by day
					if ( ! isset( $dates[ date( 'd M Y', $notification->time ) ] ) ) {
						$dates[ date( 'd M Y', $notification->time ) ] = 0;
					}
					$dates[ date( 'd M Y', $notification->time ) ] += 1;
				} else {
					if ( ! isset( $dates[ date( 'M Y', $notification->time ) ] ) ) {
						$dates[ date( 'M Y', $notification->time ) ] = 0;
					}
					$dates[ date( 'M Y', $notification->time ) ] += 1;
				}
			}
		}
		$dates = array_reverse( $dates );
		foreach ( $dates as $date => $count ) {
			$data .= '[\'' . $date . '\', ' . $count . '],';
		}
		$data  = rtrim( $data, ',' );
		$data .= ']);';
		echo $data;
	}

	public static function show_history( $notifications ) {
		?>
		<table class="wp-list-table widefat fixed posts">
			<thead>
				<tr>
					<th scope="col" id="time" class="manage-column column-time">Sent</th>
					<th scope="col" id="message" class="manage-column column-message">Message</th>
					<th scope="col" id="attachment" class="manage-column column-attachment">Attachment</th>
					<th scope="col" id="platform" class="manage-column column-platform" style="">Platform</th>
					<th scope="col" id="tags" class="manage-column column-tags" style="">Tags</th>
				</tr>
			</thead>

			<tfoot>
				<tr>
					<th scope="col" id="time" class="manage-column column-time">Sent</th>
					<th scope="col" id="message" class="manage-column column-message">Message</th>
					<th scope="col" id="attachment" class="manage-column column-attachment">Attachment</th>
					<th scope="col" id="platform" class="manage-column column-platform" style="">Platform</th>
					<th scope="col" id="tags" class="manage-column column-tags" style="">Tags</th>
				</tr>
			</tfoot>

			<tbody id="the-list">
				<?php if ( count( $notifications ) ) : ?>
					<?php foreach ( $notifications as $notification ) : ?>
						<?php
						$notificationPlatform = '';
						if ( $notification->android == 'Y' && $notification->ios == 'Y' ) {
							$notificationPlatform = 'All';
						} elseif ( $notification->android == 'Y' ) {
							$notificationPlatform = 'Android';
						} elseif ( $notification->ios == 'Y' ) {
							$notificationPlatform = 'iOS';
						}
						$attach = array();
						if ( ! empty( $notification->url ) ) {
							$attach[] = 'url:"' . $notification->url . '"';
						} elseif ( $notification->post_id > 0 ) {
							$attach[] = 'post_id:' . $notification->post_id;
						}
						?>
						<tr id="notification-<?php echo $notification->id; ?>">
							<td class="column-time"><?php echo date( 'd/m/Y H:i:s', $notification->time ); ?></td>
							<td class="column-time"><?php echo $notification->msg; ?></td>
							<td class="column-time"><?php echo ! empty( $attach ) ? '{' . implode( ', ', $attach ) . '}' : ''; ?></td>
							<td class="column-time"><?php echo $notificationPlatform; ?></td>
							<td class="column-time"><?php echo self::tags_to_labels( $notification->tags ); ?></td>
						</tr>
					<?php endforeach; ?>
				<?php else : ?>
					<tr>
						<td colspan="5">No notifications found.</td>
					</tr>
				<?php endif; ?>
			</tbody>
		</table>
		<?php
	}

	private static function tags_to_labels( $tags ) {
		$labels = '';
		if ( strlen( $tags ) > 0 ) {
			$tags = explode( ',', $tags );
			foreach ( $tags as $tag ) {
				$labels .= '<div class="tag-label info">' . $tag . '</div>';
			}
		}

		return $labels;
	}

	public static function show_attachment( $posts, $pages ) {
		?>
		<option value=''>Select attachment...</option>
		<optgroup label="Custom">
			<option value="url">An external or internal URL</option>
			<option value="custom">Post/Page ID</option>
		</optgroup>
		<optgroup label="Posts">
			<?php foreach ( $posts as $post ) { ?>
				<option value="post_id-<?php echo $post->ID; ?>">
					<?php if ( strlen( $post->post_title ) > 40 ) { ?>
						<?php echo substr( $post->post_title, 0, 40 ); ?>

						..
					<?php } else { ?>
						<?php echo $post->post_title; ?>

					<?php } ?>
				</option>
			<?php } ?>

		</optgroup>
		<optgroup label="Pages">
			<?php foreach ( $pages as $page ) { ?>
				<option value="post_id-<?php echo $page->ID; ?>">
					<?php if ( strlen( $page->post_title ) > 40 ) { ?>
						<?php echo substr( $page->post_title, 0, 40 ); ?>

						..
					<?php } else { ?>
						<?php echo $page->post_title; ?>

					<?php } ?>
				</option>
			<?php } ?>
		</optgroup>
		<?php
	}
}
