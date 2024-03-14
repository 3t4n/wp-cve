<?php

/**
 * Messages Loop
 * Custom template for BuddyPress Messages Tool
 * Cannot be overloaded
 *
 */


if( !isset( $_GET['user'] ) )
	$bpmt_get_member = '&user_id=' . $bpmt_user_data->ID;
else
	$bpmt_get_member = '&user_id=' . $_GET['user'];


if( isset( $_GET['mpage'] ) )
	$bpmt_get_member .= '&mpage=' . $_GET['mpage'];

$bpmt_get_member .= '&box=' . $bpmt_user_data->box;

$alter = false;

?>

<?php if ( bp_has_message_threads( bp_ajax_querystring( 'messages' ) . $bpmt_get_member ) ) : ?>

	<div class="pagination no-ajax" id="user-pag">

		<div class="pag-count" id="messages-dir-count">
			<?php bp_messages_pagination_count(); ?>
		</div>

		<div class="pagination-links" id="messages-dir-pag">
			<?php bp_messages_pagination(); ?>
		</div>

	</div><!-- .pagination -->

	<br>


	<form action="<?php echo bpmt_view_delete_back_link('bulk-delete'); ?>" method="post" id="messages-bulk-management">

		<table id="message-threads" class="widefat fixed" cellspacing="15">

			<tr style="background-color:#ccc">
				<td style="width:5%;vertical-align:top;" class="thread-checkbox bulk-select-all"><input id="select-all-messages" type="checkbox"></td>
				<td style="width:25%;vertical-align:top;"><strong><?php _e( 'Participants / Meta', 'bpmt' ); ?></strong></td>
				<td style="width:10%;vertical-align:top;"><strong><?php _e( 'Delete', 'bpmt' ); ?></strong></td>
				<td style="width:60%;vertical-align:top;"><strong><?php _e( 'Threads', 'bpmt' ); ?></strong></td>
			</tr>

			<?php while ( bp_message_threads() ) : bp_message_thread(); ?>

				<?php

					$class = ( $alter ? 'class="alternate"' : '' );

					$alter = ! $alter;
				?>

				<tr id="m-<?php bp_message_thread_id(); ?>" <?php echo $class; ?> <?php if ( bp_message_thread_has_unread() ) : ?> unread<?php else: ?> read<?php endif; ?>">

					<td style="vertical-align:top;">
						<p>
						<label for="bp-message-thread-<?php bp_message_thread_id(); ?>"><input type="checkbox" name="message_ids[]" id="bp-message-thread-<?php bp_message_thread_id(); ?>" class="message-check" value="<?php bp_message_thread_id(); ?>" /></label>
						</p>
					</td>

					<td style="width:20%;vertical-align:top;">
						<p>
						<?php bp_message_thread_to(); ?>
						<br>
						<?php  _e( 'Message Count: ', 'bpmt' ); echo bp_get_message_thread_total_count(); ?>
						<br><?php bp_message_thread_last_post_date_raw(); ?>
						</p>
					</td>

					<td style="width:10%;vertical-align:top;">
						<p>
						<a class="submitdelete" href="<?php echo bpmt_view_delete_back_link('delete'); ?>" onclick="return confirm('<?php _e( "Are you sure you want to Delete this Message Thread?", "bpmt" ); ?>');" title="<?php _e( "Delete Thread", "bpmt" ); ?>"><?php _e( 'Delete', 'bpmt' ); ?></a>
						</p>
					</td>

					<td style="width:70%;vertical-align:top;">
						<p><a href="<?php echo bpmt_view_delete_back_link('view'); ?>" title="<?php _e( "View Thread", "bpmt" ); ?>"><?php echo stripslashes( bp_get_message_thread_subject() ); ?></a></p>
						<p class="thread-excerpt"><?php echo stripslashes( bp_get_message_thread_content() ); ?></p>
					</td>

				</tr>

			<?php endwhile; ?>

			<tr style="background-color:#ccc">
				<td style="width:5%;vertical-align:top;" class="thread-checkbox bulk-select-all"><input id="select-all-messages-footer" type="checkbox"></td>
				<td style="width:25%;vertical-align:top;"><strong><?php _e( 'Participants / Meta', 'bpmt' ); ?></strong></td>
				<td style="width:10%;vertical-align:top;"><strong><?php _e( 'Delete', 'bpmt' ); ?></strong></td>
				<td style="width:60%;vertical-align:top;"><strong><?php _e( 'Threads', 'bpmt' ); ?></strong></td>
			</tr>


		</table><!-- #message-threads -->

		<div class="messages-options-nav">
			<br>
			<input type="submit" id="messages-bulk-manage" class="button button-primary" value="Delete All Selected Messages"> <em><?php _e( "There is no UnDo for this operation.", "bpmt" ); ?></em>
		</div><!-- .messages-options-nav -->

		<?php wp_nonce_field( 'messages_bulk_nonce', 'messages_bulk_nonce' ); ?>

	</form>


	<script>
		jQuery(document).ready(function($) {
			$('#select-all-messages').click(function() {
				$(':checkbox').prop('checked', this.checked);
			});
			$('#select-all-messages-footer').click(function() {
				$(':checkbox').prop('checked', this.checked);
			});
		});
	</script>

	<br>

	<div class="pagination no-ajax" id="user-pag">

		<div class="pag-count" id="messages-dir-count">
			<?php bp_messages_pagination_count(); ?>
		</div>

		<div class="pagination-links" id="messages-dir-pag">
			<?php bp_messages_pagination(); ?>
		</div>

	</div><!-- .pagination -->


<?php else: ?>

	<div id="message" class="info">
		<p><?php _e( 'Sorry, no messages were found for that member.', 'bpmt' ); ?></p>
	</div>

<?php endif;?>
