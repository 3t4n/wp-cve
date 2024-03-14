<?php


add_filter( 'bbp_get_forum_last_active', 'blp_change_freshness_forum', 10, 2 );
add_filter( 'bbp_get_topic_last_active', 'blp_change_freshness_topic', 10, 2 );
add_filter( 'gettext', 'blp_change_translate_text', 20, 3 );

//this function changes the bbp freshness data (time since) into a last post date for forums
function blp_change_freshness_forum ($forum_id = 0 ) {

// Verify forum and get last active meta
		$forum_id    = bbp_get_forum_id( $forum_id );
		$last_active = get_post_meta( $forum_id, '_bbp_last_active_time', true );

		if ( empty( $last_active ) ) {
			$reply_id = bbp_get_forum_last_reply_id( $forum_id );
			if ( !empty( $reply_id ) ) {
				$last_active = get_post_field( 'post_date', $reply_id );
			} else {
				$topic_id = bbp_get_forum_last_topic_id( $forum_id );
				if ( !empty( $topic_id ) ) {
					$last_active = bbp_get_topic_last_active_time( $topic_id );
				}
			}
		}

		$last_active = bbp_convert_date( $last_active ) ;
		$date_format = get_option( 'date_format' );
		$time_format = get_option( 'time_format' );
		$date= date_i18n( "{$date_format}", $last_active );
		$time=date_i18n( "{$time_format}", $last_active );
		$active_time = sprintf( _x( '%1$s at %2$s', 'date at time', 'bbp-last-post' ), $date, $time );  
		return apply_filters ('blp_change_freshness_forum' , $active_time) ;
		}
		


//this function changes the bbp freshness data (time since) into a last post date for topics
function blp_change_freshness_topic ($last_active, $topic_id) {

$topic_id = bbp_get_topic_id( $topic_id );

		// Try to get the most accurate freshness time possible
		$last_active = get_post_meta( $topic_id, '_bbp_last_active_time', true );
		if ( empty( $last_active ) ) {
		$reply_id = bbp_get_topic_last_reply_id( $topic_id );
		if ( !empty( $reply_id ) ) {
			$last_active = get_post_field( 'post_date', $reply_id );
		} else {
				$last_active = get_post_field( 'post_date', $topic_id );
			}
		}
		
		
		$last_active = bbp_convert_date( $last_active ) ;
		$date_format = get_option( 'date_format' );
		$time_format = get_option( 'time_format' );
		$date= date_i18n( "{$date_format}", $last_active );
		$time=date_i18n( "{$time_format}", $last_active );
		$active_time = sprintf( _x( '%1$s at %2$s', 'date at time', 'bbp-last-post' ), $date, $time );  
		return apply_filters ('blp_change_freshness_topic' , $active_time) ;
		}
		


//This function changes the heading "Freshness" to the name created in Settings>bbp last post
function blp_change_translate_text( $translated_text, $text, $domain ) {
		$testtext = 'Freshness' ;
		$testdomain = 'bbpress' ;
			if ( ($text == $testtext) && ($domain == $testdomain) ) {
			global $rlp_options;
			$translated_text = __( $rlp_options['heading_label'], $testdomain );
			}
	
 return $translated_text;
}





