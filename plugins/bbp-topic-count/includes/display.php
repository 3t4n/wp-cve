<?php

//Make sure we don't expose any info if called directly
if ( !function_exists( 'add_action' ) ) {
	echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
	exit;
}
else
{
global $tc_options;
	
if (empty($tc_options['location'])) add_action ('bbp_theme_after_reply_author_details', 'display_counts') ;

else add_action ('bbp_theme_before_reply_content', 'display_counts_in_reply') ;

//this function hooks to BBpress loop-single-reply.php and adds the counts to the reply display
function render_display_counts($location, $reply_id = 0) {
    		global $tc_options;
		$user_id = bbp_get_reply_author_id( $reply_id ) ;
                $user_nicename = bbp_get_user_nicename($user_id);
		$topic_count  = bbp_get_user_topic_count_raw( $user_id);
		$reply_count = bbp_get_user_reply_count_raw( $user_id);
		$post_count = (int) $topic_count + $reply_count;			
		$sep = (!empty($tc_options['sep']) ? $tc_options['sep'] : 0) ;
                $link_counts = (!empty($tc_options['link_counts']) ? 1 : 0) ;
		
		if ($sep == 1) {
			$topic_count = number_format($topic_count);
			$reply_count = number_format($reply_count);
			$post_count = number_format($post_count);
		}
		if ($sep == 2) {
			$topic_count = number_format($topic_count, 0, '', ' ');
			$reply_count = number_format($reply_count,0, '',  ' ');
			$post_count = number_format($post_count, 0, '',  ' ');
		}
                
                if ( (bool) $link_counts ) {
                    $topic_count_string = $topic_count > 0 ? '<a href="'.esc_url( bbp_get_user_topics_created_url( $user_id ) ).'" title="'.esc_attr($tc_options['topic_label']).': '.esc_attr($user_nicename).'">' . $topic_count . '</a>' : $topic_count;
                    $reply_count_string = $reply_count > 0 ? '<a href="'.esc_url( bbp_get_user_replies_created_url( $user_id ) ).'" title="'.esc_attr($tc_options['reply_label']).': '.esc_attr($user_nicename).'">' . $reply_count . '</a>' : $reply_count;
                    $post_count_string = $post_count > 0 ? '<a href="'.esc_url( bbp_get_user_engagements_url( $user_id ) ).'" title="'.esc_attr($tc_options['posts_label']).': '.esc_attr($user_nicename).'">' . $post_count . '</a>' : $post_count;
                } else {
                    $topic_count_string = $topic_count;
                    $reply_count_string = $reply_count;
                    $post_count_string = $post_count;
                } 
                
                echo '<div class="tc_display">' ;
                
                if ($location === 'author_details') {
                    echo '<ul>' ;
                }
                if ($location === 'in_reply') {
                    echo '<table><tr>' ;
                }
		
		
// displays topic count
		
		$value = !empty($tc_options['activate_topics']) ? $tc_options['activate_topics'] : '';
		if(!empty ($value)) {
			echo $location === 'author_details' ? '<li>' : '<td>';
				if (empty($tc_options['order'])) { 
					echo $label1 =  $tc_options['topic_label']." " ;
					echo $topic_count_string ;
				}
				else {
					echo $topic_count_string ;	
					echo $label1 =  $tc_options['topic_label']." " ;
				}
			echo $location === 'author_details' ? '</li>' : '</td>';
		}
		
		
// displays replies count
		
		$value = !empty($tc_options['activate_replies']) ? $tc_options['activate_replies'] : '';
		if(!empty ($value)) {
			echo $location === 'author_details' ? '<li>' : '<td>';
				if (empty($tc_options['order'])) { 
					echo $label2 =  $tc_options['reply_label']." " ;
					echo $reply_count_string ;
				}
				else {
					echo $reply_count_string ;
					echo $label2 =  $tc_options['reply_label']." " ;
				}
			echo $location === 'author_details' ? '</li>' : '</td>';
		}
		
		
// displays total posts count
		
		$value = !empty($tc_options['activate_posts']) ? $tc_options['activate_posts'] : '';
                if(!empty ($value)) {
                    echo $location === 'author_details' ? '<li>' : '<td>';
			echo '<li>' ;
				if (empty($tc_options['order'])) { 
					echo $label3 =  $tc_options['posts_label']." " ;
					echo $post_count_string ;
				}
				else {
					echo $post_count_string ;
					echo $label3 =  $tc_options['posts_label']." " ;
				}
			echo $location === 'author_details' ? '</li>' : '</td>';
		}
		
//end of list		
                if ($location === 'author_details') {
                    echo '</ul>' ;
                }
                if ($location === 'in_reply') {
                    echo '</tr></table>' ;
                }

		echo "</div>" ;

		}
}

// the function triggers the rendering of counts based on location in the author details
function display_counts ($reply_id = 0) {
    
        render_display_counts('author_details', $reply_id);
        
}

// the function triggers the rendering of counts based on location within replies
function display_counts_in_reply ($reply_id = 0) {
    
        render_display_counts('in_reply', $reply_id);

}

