(function( $ ) {
	
	$(function() {
		$( '.fallback' ).each(function(){
			if ( $(this).attr('type') == 'text') {
			$(this).addClass( 'color-field' );
			}
		});
	});
	
    // Add Color Picker to all inputs that have 'color-field' class
   $(document).ready(function() {
       $('.color-field').wpColorPicker();
   });
     
})( jQuery );

jQuery(document).ready(function(){
	bpGroupChatHeartbeat();
	bpGroupChatLoadMessages('fresh');
	jQuery(document).everyTime(5000, function() {
		bpGroupChatLoadMessages();
	}, 0);
	jQuery(document).everyTime(10000, function() {
		bpGroupChatHeartbeat();
	}, 0);
	
});

function bpGroupChatHeartbeat(i) {
	var groupInfo = document.getElementById('group-chatroom-info');
	if ( groupInfo !== null ) {
		var groupId = groupInfo.dataset.groupid;
		jQuery.post(ajaxurl, { security: chat_ajax_object.check_nonce, bp_group_chat_online_query: "1", bp_group_chat_group_id: groupId, action: 'bp_chat_heartbeat' }, function(data) {
			if ( data != jQuery('#chat-users-online').html() ) {
				jQuery('#chat-users-online').html(data);
			}
		});
	}
}

function bpGroupChatsubmitNewMessage(){
	var groupInfo = document.getElementById('group-chatroom-info');
	var groupId = groupInfo.dataset.groupid;
	var message = document.getElementById('bp_group_chat_textbox');
	var message_content = message.innerHTML;
	var emoji = document.getElementById( 'bp-group-chatroom-emoji' );
	var video = document.getElementById( 'bp_group_chatroom_video' );
	emoji.style.display = 'none';
	video.style.display = 'none';
	if ( message_content != '' ) {
		jQuery.post(ajaxurl, { security: chat_ajax_object.check_nonce, bp_group_chat_new_message: "1", bp_group_chat_group_id: groupId, bp_group_chat_textbox: message_content, action: 'bp_chat_new_message' }, function() {
			message.innerHTML = '';
			bpGroupChatLoadMessages();
		});
	}
}

function bpGroupChatLoadMessages(update = 'update' ) {	
	var groupInfo = document.getElementById('group-chatroom-info');
	if ( groupInfo !== null ) {
		if ( update == 'fresh' ) {
			var groupId = groupInfo.dataset.groupid;
			var messages = document.getElementById( 'bp-chat-chat-container' );
			jQuery.post(ajaxurl, { security: chat_ajax_object.check_nonce, bp_group_chat_load_messages: "2", bp_group_chat_group_id: groupId, action: 'bp_chat_load_messages' }, function(data) {
				if ( data != '' ) {
					jQuery('#bp-chat-chat-container').html(data);
					var shouldScroll = messages.scrollTop + messages.clientHeight === messages.scrollHeight;
					if ( !shouldScroll ) {
						scrollToBottom();
					}
				}
			});
			
		} else {
			var groupId = groupInfo.dataset.groupid;
			var messages = document.getElementById( 'bp-chat-chat-container' );
			jQuery.post(ajaxurl, { security: chat_ajax_object.check_nonce, bp_group_chat_load_messages: "1", bp_group_chat_group_id: groupId, action: 'bp_chat_load_messages' }, function(data) {
				if ( data != '' ) {
					jQuery('#bp-chat-chat-container').append(data);
					var shouldScroll = messages.scrollTop + messages.clientHeight === messages.scrollHeight;
					if ( !shouldScroll ) {
						scrollToBottom();
					}
				}
			});
		}
	}
}
function scrollToBottom() {
	var messages = document.getElementById( 'bp-chat-chat-container' );
	messages.scrollTop = messages.scrollHeight;
}
//Video embed functions
function bpGroupChatOpenVideo() {
	var emoji = document.getElementById( 'bp-group-chatroom-emoji' );
	emoji.style.display = 'none';
	var video = document.getElementById( 'bp_group_chatroom_video' );
	video.style.display = 'block';
}

function bpGroupChatEmbedVideo() {
	var video = document.getElementById( 'bp_group_chatroom_video' );
	var videoInput = document.getElementById('bp_group_chat_videobox');
	var groupInfo = document.getElementById('group-chatroom-info');
	var groupId = groupInfo.dataset.groupid;
	
	if ( videoInput.value != '' ) {
		jQuery.post(ajaxurl, { security: chat_ajax_object.check_nonce, video_url: videoInput.value, bp_group_chat_new_video: "1", bp_group_chat_group_id: groupId, action: 'bp_chat_new_video' }, function() {
			videoInput.value = '';
			video.style.display = 'none';
			bpGroupChatLoadMessages();
		});
		
	} else {
			video.style.display = 'none';
	}
	
}

// Delete Message

function bpChatroomDeleteMsg( msgId ) {

	var groupInfo = document.getElementById( 'group-chatroom-info' );
	var message = document.getElementById( 'bp-group-chatroom-' + msgId );
	var groupId = groupInfo.dataset.groupid;
	jQuery.post(ajaxurl, { security: chat_ajax_object.check_nonce, message_id: msgId, bp_group_chat_delete_msg: "1", bp_group_chat_group_id: groupId, action: 'bp_chat_delete_msg' }, function() {
		message.style.display = 'none';
	});
	
}

// Emoji support

//Open Emoji
function bpGroupChatOpenEmoji() {
	var video = document.getElementById( 'bp_group_chatroom_video' );
	video.style.display = 'none';
	var emoji = document.getElementById( 'bp-group-chatroom-emoji' );
	emoji.style.display = 'block';
	var reveal = document.getElementById('content1' );
	reveal.style.display = 'block';
}

// Close Emoji window
function bpGroupChatroonClosePopup() {
	var emoji = document.getElementById( 'bp-group-chatroom-emoji' );
	emoji.style.display = 'none';
	
}

// Insert Emoji
function emojiinsert(icon) {

	var icontag = '<img src="'+bpgc_translate.siteUrl+'includes/icons/'+icon+'" width="18" height="18" style="margin-left:3px; margin-right:3px; vertical-align:middle; -webkit-box-shadow:none; -moz-box-shadow:none; box-shadow:none;">';
	var href = 'bpgc_translate.siteUrl' + 'includes/icons.' + icon;
	var message = document.getElementById('bp_group_chat_textbox');
	var emoji = document.getElementById( 'bp-group-chatroom-emoji' );
	var msgDiv = document.getElementById('bp-group-chatbox-text-input');
	var newMsg = message.innerHTML + icontag;
	message.innerHTML = newMsg
	emoji.style.display = 'none';
	
	return;
}

//Display Emoji

jQuery(document).ready(function(){
	var tabSelector = document.getElementById('bp-group-chatroom-emoji-selectors');
	function revealEmoji(e) {
		selectorID = e.target.id;
		idNumber = selectorID.replace( 'tab', '' );
		
		for ( i=1; i < 8; i++ ) {
			if ( i == idNumber ) {
				var reveal = document.getElementById('content' + idNumber );
				if ( reveal != null ) {
					reveal.style.display = 'block';
				}
			} else {
				var hide = document.getElementById('content' + i );
				if ( hide != null ) {
					hide.style.display = 'none';
				}
			}
		}
	} 
	if ( tabSelector != null ) {
		tabSelector.addEventListener( 'click', revealEmoji );
	}
});

// Close thread manually
function bpChatroomCloseThread() {

	var groupInfo = document.getElementById( 'group-chatroom-info' );
	var groupId = groupInfo.dataset.groupid;
	jQuery.post(ajaxurl, { security: chat_ajax_object.check_nonce, bp_group_chat_close_thread: "1", bp_group_chat_group_id: groupId, action: 'bp_chat_close_thread' }, function(data) {
		console.log('success: ', data);
	});

}

// Insert Media

function bpGroupChatOpenMedia() {

    var attachedImage = {};
    var pml_global = {};

	pml_global.custom_uploader = wp.media.frames.file_frame = wp.media({
		title: 'Choose an image',
		frame: 'select',
		button: {
			text: 'Pick it',
			id: 'pick-link-to-wp-file',
			onclick: function () {
				//console.log('hello');
			}
		},
		multiple: false
	});

	pml_global.custom_uploader.on('select', function () {
		attachedImage = pml_global.custom_uploader.state().get('selection').first().toJSON();
		handleFormSubmit(attachedImage);
	});

	pml_global.custom_uploader.open(attachedImage);
}

function handleFormSubmit(attachedImage) {
	if(attachedImage) {
		;
		jQuery.post(ajaxurl, { security: chat_ajax_object.check_nonce, imageURL: attachedImage.url, bp_group_chat_insert_image: "1", action: 'bp_chat_insert_image' }, function() {
			
		});
	}
}
