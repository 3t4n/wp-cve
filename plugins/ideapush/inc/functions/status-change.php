<?php



//when post is published/saved
//this is used to update the status and send appropriate emails
function idea_push_when_post_is_updated($post_ID, $post_after, $post_before){
    
    //only run if the post is an idea post
    if(get_post_type($post_ID) == 'idea'){

        //get the options
        $options = get_option('idea_push_settings');

        //get existing status
        $existingStatus = get_post_meta($post_ID,'current-status',true);

        //get new status
        $getStatus = get_the_terms($post_ID,'status');
        $newStatus = $getStatus[0]->slug;
        
        //get the user who carried out the action - this is untested
        $currentUser = idea_push_check_if_non_logged_in_user_is_guest('Yes');

        if($currentUser !== false){
            //do common status change function which sends notifications and allows action hooks
        
            //if the existing status is empty the status change didn't actually occur so therefore we dont need to send notifications of add an item to the idea history list
            if(!empty($existingStatus)){
                idea_push_common_status($post_ID,$existingStatus,$newStatus,$currentUser);    
            }
            
    
            
            //if there are no votes, let's set this to 0
            $isThereAVote = get_post_meta($post_ID,'votes',true);
            
            if($isThereAVote == false){
                update_post_meta($post_ID,'votes', 0);    
                
            }

        }


        
        


        
        
    }//end post type check
    
}
add_action( 'post_updated', 'idea_push_when_post_is_updated', 10, 3 );







function idea_push_change_status(){
    
    //get the options
    $options = get_option('idea_push_settings');
    
    $status = idea_push_sanitization_validation($_POST['status'],'name');
    $ideaId = idea_push_sanitization_validation($_POST['ideaId'],'id');
    
    if($status == false || $ideaId == false){
        wp_die();      
    }
    
    
    $html = '';

    $boardNumber = idea_push_get_board_id_from_post_id($ideaId);
    $individualBoardSetting = idea_push_get_board_settings($boardNumber);
    $multiIp = $individualBoardSetting[27];

    if(!isset($multiIp)){
        $multiIp = 'No';
    }
    
    //lets check if the user is an admin and authorised to this action
    $currentUser = idea_push_check_if_non_logged_in_user_is_guest($multiIp);
    
    if($currentUser !== false){
        if(user_can( $currentUser, 'administrator' ) || user_can( $currentUser, 'idea_push_manager' )){
            
    
            //we can do this in one process by using wp_set_object_terms, this will remove existing terms as well as adding the new one
            wp_set_object_terms( $ideaId, $status, 'status', false );

            //we are going to return the replacement, followed by the other statuses

            $html .= idea_push_render_status($status,'ONLY');

            $html .= '|';

            $html .= idea_push_render_status($status,'EXCEPT');
            

            //lets do our notifications as well
            //lets check if the statuses are different
            
            $existingStatus = get_post_meta($ideaId,'current-status',true);
            
            
            //do common status change function which sends notifications and allows action hooks
            idea_push_common_status($ideaId,$existingStatus,$status,$currentUser);
            

        }
        
    }
    
    echo $html;
    wp_die();    
}

add_action( 'wp_ajax_change_status', 'idea_push_change_status' );
add_action( 'wp_ajax_nopriv_change_status', 'idea_push_change_status' );














//this function is called in the above 2 functions because the above 2 functions have common tasks relating to when a status changes. One of the functions is called on demand when an admin changes the status from the front end on the single post page, and the other function fires when a post is updated
function idea_push_common_status($ideaId,$oldStatus,$newStatus,$currentUser){
    
    //get the options
    $options = get_option('idea_push_settings');
    
    if($newStatus !== $oldStatus){
                
        $statusRenamed = str_replace('-','_',$newStatus);
        
        
        
        
        
        //this action is used for the general status change and not the notifications like the above 2
        do_action('idea_push_idea_status_change',$ideaId,$currentUser,$oldStatus.'|'.$newStatus);
        
        
        
        
        
        

        //lets do author notification first
        if(isset($options['idea_push_notification_author_idea_change_'.$statusRenamed.'_enable'])){


            $subject = idea_push_shortcode_replacement($ideaId, $options['idea_push_notification_author_idea_change_'.$statusRenamed.'_subject'],'');

            $body = idea_push_shortcode_replacement($ideaId, $options['idea_push_notification_author_idea_change_'.$statusRenamed.'_content'],'');

            $postAuthorId = get_post_field( 'post_author', $ideaId );
            $postAuthor = get_user_by('id',$postAuthorId);
            $postAuthorEmail = $postAuthor->user_email;

            idea_push_send_email($postAuthorEmail,$subject,$body);

            do_action('_author_notification',$ideaId,$options['idea_push_notification_author_idea_change_'.$statusRenamed.'_subject'].'|'.$options['idea_push_notification_author_idea_change_'.$statusRenamed.'_content']);


        }


        //lets do our voters notifications
        if(isset($options['idea_push_notification_voter_idea_change_'.$statusRenamed.'_enable'])){


            //get positive voters
            $positiveVoters = get_post_meta($ideaId,'up-voters',true);  

            foreach($positiveVoters as $voter){

                if(strlen($voter[0])>0){

                    $subject = idea_push_shortcode_replacement($ideaId, $options['idea_push_notification_voter_idea_change_'.$statusRenamed.'_subject'],$voter[0]);

                    $body = idea_push_shortcode_replacement($ideaId, $options['idea_push_notification_voter_idea_change_'.$statusRenamed.'_content'],$voter[0]);

                    //get voter email
                    $voterEmail = get_user_by('id',$voter[0]);    
                    $voterEmail = $voterEmail->user_email;

                    //send email to author on first publush
                    idea_push_send_email($voterEmail,$subject,$body);  
                }


            }

            do_action('idea_push_idea_status_change_voter_notification',$ideaId,$options['idea_push_notification_voter_idea_change_'.$statusRenamed.'_subject'].'|'.$options['idea_push_notification_voter_idea_change_'.$statusRenamed.'_content']);


        }

        

        //now lets change the current status meta value    
        update_post_meta($ideaId,'current-status', $newStatus);

    }

    //we dont need to return anything
}




















//function which renders the status or statuses
//the onlyOrExcept arguement takes either ONLY which brings back just that status or EXCEPT which brings back everything but that status
function idea_push_render_status($status,$onlyOrExcept){
    
    //get settings
    $options = get_option('idea_push_settings');
    
    //start output
    $html = '';

    if($onlyOrExcept == "ONLY"){
        
        //get translation
        if(strlen($options['idea_push_change_'.str_replace("-","_",$status).'_status'])>0) {
            $statusName = $options['idea_push_change_'.str_replace("-","_",$status).'_status'];   
        } else {
            $statusName = str_replace("-"," ",$status);  
        }
        
        
        $html .= '<span class="idea-item-status idea-item-status-'.strtolower($status).'">'.esc_html($statusName).'</span>';
        
        
    } else {
        
        //an array containing all statuses
        $statusesArray = array('open','reviewed','approved','declined','in_progress','completed');
        
        //remove the provided status from the array
        if (($key = array_search($status, $statusesArray)) !== false) {
            unset($statusesArray[$key]);
        }

        //we also need to remove statuses which have been removed in the settings
        if(isset($options['idea_push_disable_approved_status']) && $options['idea_push_disable_approved_status']){
            unset($statusesArray[2]);
        }

        if(isset($options['idea_push_disable_declined_status']) && $options['idea_push_disable_declined_status']){
            unset($statusesArray[3]);
        }

        if(isset($options['idea_push_disable_in_progress_status']) && $options['idea_push_disable_in_progress_status']){
            unset($statusesArray[4]);
        }

        if(isset($options['idea_push_disable_completed_status']) && $options['idea_push_disable_completed_status']){
            unset($statusesArray[5]);
        }
        
        //now lets render the list
        foreach($statusesArray as $statusItem){
            
            //first lets get the status name if it has been translated
            if(strlen($options['idea_push_change_'.$statusItem.'_status'])>0) {
                $statusName = $options['idea_push_change_'.$statusItem.'_status'];   
            } else {
                $statusName = str_replace("_"," ",$statusItem);      
            }

            $classSlug = strtolower(str_replace("_","-",$statusItem));
            
            
            $html .= '<span data="'.esc_html($classSlug).'" class="idea-item-statuses idea-item-status idea-item-status-'.esc_html($classSlug).'">'.esc_html($statusName).'</span>';
            
        }
         
    }
    
    
    return $html;
    
}

//lets do an author notification when an idea goes from pending to published
function idea_push_when_post_is_published( $new_status, $old_status, $post ) {
    if ( ( 'publish' === $new_status && 'pending' === $old_status )
        && 'idea' === $post->post_type) {

        //get settings
        $options = get_option('idea_push_settings');

        if(isset($options['idea_push_notification_author_idea_published_enable'])){

            $ideaId = $post->ID;

            $subject = idea_push_shortcode_replacement($ideaId, $options['idea_push_notification_author_idea_published_subject'],'');

            $body = idea_push_shortcode_replacement($ideaId, $options['idea_push_notification_author_idea_published_content'],'');

            $postAuthorId = get_post_field( 'post_author', $ideaId );
            $postAuthor = get_user_by('id',$postAuthorId);
            $postAuthorEmail = $postAuthor->user_email;

            //send the notification
            idea_push_send_email($postAuthorEmail,$subject,$body);

            //also add this to idea history...
            do_action('idea_push_idea_published_after_pending_author_notification',$ideaId,$options['idea_push_notification_author_idea_published_subject'].'|'.$options['idea_push_notification_author_idea_published_content']);
            


        }



    }
}
add_action( 'transition_post_status', 'idea_push_when_post_is_published', 10, 3 );


?>