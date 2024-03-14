<?php
if ( !defined( 'ABSPATH' ) ) exit;

global $bp;
if ( !$chat_display ) die;

bp_group_chatroom_reset_updates_for_user( $bp->groups->current_group->id, $bp->loggedin_user->id );
?>


<div id="chat-chat-box">
	<div id="bp-chat-chat-container">
	</div>
	<form>
		<input type="hidden" id="group-chatroom-info" data-groupid="<?php echo $bp->groups->current_group->id; ?>"/>
		<div id="bp-group-chatbox-text-input">
			<div id="bp_group_chat_textbox" name="bp_group_chat_textbox" contenteditable="true">
			</div>
		</div>
		<input type="submit" value="<?php echo sanitize_text_field( __( 'Say', 'bp-group-chatroom' ) ); ?>" onClick="bpGroupChatsubmitNewMessage();return false;"/>
		<input type="submit" value="<?php echo sanitize_text_field( __( 'Video', 'bp-group-chatroom' ) ); ?>" onClick="bpGroupChatOpenVideo();return false;"/>
		<input type="submit" value="<?php echo sanitize_text_field( __( 'Emoji', 'bp-group-chatroom' ) ); ?>" onClick="bpGroupChatOpenEmoji();return false;"/>

		<?php if ( current_user_can( 'upload_files' ) ) : ?>

			<input type="submit" value="<?php echo sanitize_text_field( __( 'Images', 'bp-group-chatroom' ) ); ?>" onClick="bpGroupChatOpenMedia();return false;"/>
			
		<?php endif; ?>
			
		<div id="bp_group_chatroom_video" style="display:none;">
			<input id="bp_group_chat_videobox" name="bp_group_chat_videobox" type="text" size="45" placeholder="<?php echo sanitize_text_field( __( 'Paste video here...', 'bp-group-chatroom' ) ); ?>"/>
			<input type="submit" value="<?php echo sanitize_text_field( __( 'Submit Video', 'bp-group-chatroom' ) ); ?>" onClick="bpGroupChatEmbedVideo();return false;"/>
		</div>
		<div id="bp-group-chatroom-emoji" style="display: none;">
			<?php load_template( plugin_dir_path( __FILE__ ) . '/bp-group-chatroom-emoji-display.php' ); ?>
		</div>
	</form>
</div>

<div id="chat-users-online-container" >
	<h5><?php echo sanitize_text_field( __( 'Users in the Chat Room', 'bp-group-chatroom' ) ); ?></h5>
	<ul id="chat-users-online" class="item-list-chat" role="main">
	</ul>
</div>