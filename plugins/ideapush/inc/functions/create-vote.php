<?php





function idea_push_submit_vote(){
    
    //the idea id
    $ideaId = idea_push_sanitization_validation($_POST['ideaId'],'id');
    
    if($ideaId == false){
        wp_die();    
    }


    //we need to get the board number so we can see what the voting threshold is
    $boardId = idea_push_get_board_id_from_post_id($ideaId);
    $individualBoardSetting = idea_push_get_board_settings($boardId);
    $voteThreshold = $individualBoardSetting[2];
    $multiIp = $individualBoardSetting[27];

    if(!isset($multiIp)){
        $multiIp = 'No';
    }

    //the very first thing we want to check is if the person is logged in or not
    //if they are not logged in echo an error and kill the function
    $userId = idea_push_check_if_non_logged_in_user_is_guest($multiIp);

    if($userId == false){
        echo 'NOT LOGGED IN';
        wp_die();
    }

    //whether the person clicked on an up or down vote
    $voteIntent = sanitize_text_field($_POST['voteIntent']);
    
    
    
    
    
    //author details
    $postAuthorId = get_post_field( 'post_author', $ideaId );
    $postAuthor = get_user_by('id',$postAuthorId);
    $postAuthorEmail = $postAuthor->user_email;
    
    
    
    //get the options
    $options = get_option('idea_push_settings');

    
    
    
    
    
    //checks to see if voting is permitted
    //this may be false if the person has reached their voting threshold
    if(idea_push_action_permission($boardId,$userId,'vote')){
        
        
 
        //lets get existing voters array - this function reformulates the array value as well
        $downVotersArray = idea_push_get_voter_array($ideaId,'down-voters');
        $upVotersArray = idea_push_get_voter_array($ideaId,'up-voters');
        
      

        //start output of return value
        $returnVariable = '';


        //LETS WORK THROUGH SOME SCENARIOS

        //1. PERSON UNVOTING - person is voting for something they have already voted for
        // in which case change the vote by +-1 and remove them from the +-voters
        //return +-1
        //WE NEED TO DO SOMETHING HERE FOR VOTE BANK
        if($voteIntent == 'up'){

            if(idea_push_check_voter_history($upVotersArray,$userId) == true){

                //add 1 to users vote bank aka add credit
                idea_push_add_or_remove_from_user_vote_bank($userId,$boardId,'add');


                //we now need to remove the person from the array    
                idea_push_add_or_remove_from_array($userId,$ideaId,'remove','up-voters');
                //we now need to change the vote
                idea_push_increase_or_decrease_votes(-1,$ideaId);

                $returnVariable .= '-1|1|';

            }
        } else {
            //they have downvoted
            if(idea_push_check_voter_history($downVotersArray,$userId) == true){

                //add 1 to users vote bank aka add credit
                idea_push_add_or_remove_from_user_vote_bank($userId,$boardId,'add');

                //we now need to remove the person from the array 
                idea_push_add_or_remove_from_array($userId,$ideaId,'remove','down-voters');
                //we now need to change the vote
                idea_push_increase_or_decrease_votes(1,$ideaId);

                $returnVariable .= '1|2|';
            }   
        }



        //2. FIRST TIME VOTING - person is voting for something they haven't voted for before and they haven't voted for the opposite thing before
        // in which case change the vote by +-1 and add them to either the +-voters
        //return +-1
        //WE NEED TO DO SOMETHING HERE FOR VOTE BANK
        if($voteIntent == 'up'){

            if(idea_push_check_voter_history($upVotersArray,$userId) == false && idea_push_check_voter_history($downVotersArray,$userId) == false) {

                //take away 1 from users vote bank
                //we need to check whether they have any votes left
                $vote_bank = idea_push_add_or_remove_from_user_vote_bank($userId,$boardId,'remove');

                if($vote_bank == 'FAILURE'){
                    echo 'NO VOTES IN VOTE BANK';
                    wp_die();
                }


                //add them to the array
                idea_push_add_or_remove_from_array($userId,$ideaId,'add','up-voters');
                //change the score
                idea_push_increase_or_decrease_votes(1,$ideaId);

                $returnVariable .= '1|3|';

                

            }


        } else {
            //they downvoted
            if(idea_push_check_voter_history($upVotersArray,$userId) == false && idea_push_check_voter_history($downVotersArray,$userId) == false) {

                //take away 1 from users vote bank
                //we need to check whether they have any votes left
                $vote_bank = idea_push_add_or_remove_from_user_vote_bank($userId,$boardId,'remove');

                if($vote_bank == 'FAILURE'){
                    echo 'NO VOTES IN VOTE BANK';
                    wp_die();
                }

                //add them to the array
                idea_push_add_or_remove_from_array($userId,$ideaId,'add','down-voters');
                //change the score
                idea_push_increase_or_decrease_votes(-1,$ideaId);

                $returnVariable .= '-1|4|';

                

            }
        }




        //3. DOING AN OPPOSITE VOTE - person is voting for something they haven't voted for before and they have voted for the opposite category
        // in which case change the vote by +-2 and remove them from the current +-voters and add them to the new +-voters
        //return +-2
        if($voteIntent == 'up'){

            if(idea_push_check_voter_history($upVotersArray,$userId) == false && idea_push_check_voter_history($downVotersArray,$userId) == true) {
                //add them to the array
                idea_push_add_or_remove_from_array($userId,$ideaId,'add','up-voters');
                idea_push_add_or_remove_from_array($userId,$ideaId,'remove','down-voters');
                //change the score
                idea_push_increase_or_decrease_votes(2,$ideaId);

                $returnVariable .= '2|5|';
            }


        } else {
            //they downvoted
            if(idea_push_check_voter_history($upVotersArray,$userId) == true && idea_push_check_voter_history($downVotersArray,$userId) == false) {
                //add them to the array
                idea_push_add_or_remove_from_array($userId,$ideaId,'add','down-voters');
                idea_push_add_or_remove_from_array($userId,$ideaId,'remove','up-voters');
                //change the score
                idea_push_increase_or_decrease_votes(-2,$ideaId);

                $returnVariable .= '-2|6|';
            }
        }






        //if new vote count is equal or greater than threshold change status from open to reviewed
        //return status change, return vote change    

        //get the vote score now after all the actions have taken place
        $ideaScoreNow = get_post_meta($ideaId,'votes',true);

        $returnVariable .= $ideaScoreNow.'|';

        if($ideaScoreNow >= $voteThreshold){

            //change the status
            wp_set_object_terms($ideaId,'reviewed','status',false);

            update_post_meta($ideaId,'current-status', 'reviewed');

            //change status from open to reviewed
            $returnVariable .= 'true|';   

            
            //lets do our standard action here
            do_action('idea_push_idea_vote_threshold',$ideaId,$voteThreshold);
            
            
            
            //send admin email if enabled in the settings
            if(isset($options['idea_push_notification_idea_review'])){

                if(strlen($options['idea_push_notification_email'])>0 && strpos($options['idea_push_notification_email'], '@') !== FALSE){

                    $to = $options['idea_push_notification_email'];
                    
                    $subject = idea_push_shortcode_replacement($ideaId, $options['idea_push_notification_idea_review_subject'],'');

                    $body = idea_push_shortcode_replacement($ideaId, $options['idea_push_notification_idea_review_content'],'');


                    idea_push_send_email($to,$subject,$body); 
                    
                    
                    
                    do_action('idea_push_idea_review_admin_notification',$ideaId,$options['idea_push_notification_idea_review_subject'].'|'.$options['idea_push_notification_idea_review_content']);
                    
         

                }

            }




            //send status change email to voters and authors

            //lets start with the author


            //send email to author if enabled that the status has changed
            if(isset($options['idea_push_notification_author_idea_change_review_enable'])){

                $subject = idea_push_shortcode_replacement($ideaId, $options['idea_push_notification_author_idea_change_review_subject'],'');

                $body = idea_push_shortcode_replacement($ideaId, $options['idea_push_notification_author_idea_change_review_content'],'');
                
                do_action('idea_push_idea_vote_author_notification_review',$ideaId,$options['idea_push_notification_author_idea_change_review_subject'].'|'.$options['idea_push_notification_author_idea_change_review_content']);

                //send email to author on first publush
                idea_push_send_email($postAuthorEmail,$subject,$body);  
            }



            //send email to voters if enabled that the status has changed
            if(isset($options['idea_push_notification_voter_idea_change_review_enable'])){

                //get positive voters
                $positiveVoters = get_post_meta($ideaId,'up-voters',true);

                //now cycle through each voter
                foreach($positiveVoters as $voter){
                    if(strlen($voter[0])>0){
                        $subject = idea_push_shortcode_replacement($ideaId, $options['idea_push_notification_voter_idea_change_review_subject'],$voter[0]);

                        $body = idea_push_shortcode_replacement($ideaId, $options['idea_push_notification_voter_idea_change_review_content'],$voter[0]);

                        //get voter email
                        $voterEmail = get_user_by('id',$voter[0]);    
                        $voterEmail = $voterEmail->user_email;

                        //send email to author on first publush
                        idea_push_send_email($voterEmail,$subject,$body);
                    }
                }  
                
                do_action('idea_push_idea_vote_voter_notification',$ideaId,$options['idea_push_notification_voter_idea_change_review_subject'].'|'.$options['idea_push_notification_voter_idea_change_review_content']);
                
                
            }

            
            
            
            
        } //end vote threshold reached 
        else {
            $returnVariable .= 'false|';    
        }



        //send notification to author that someone has voted if enabled
        //and we only want to do this if the vote intent was positive
        if(isset($options['idea_push_notification_author_voter_voted_enable']) && $voteIntent == 'up' && idea_push_check_voter_history($upVotersArray,$userId) == false){

            //create email
            $subject = idea_push_shortcode_replacement($ideaId, $options['idea_push_notification_author_voter_voted_subject'],$userId);

            $body = idea_push_shortcode_replacement($ideaId, $options['idea_push_notification_author_voter_voted_content'],$userId);

            //send email to author on first publush
            idea_push_send_email($postAuthorEmail,$subject,$body);
            
            do_action('idea_push_idea_vote_author_notification',$ideaId,$options['idea_push_notification_author_voter_voted_subject'].'|'.$options['idea_push_notification_author_voter_voted_content']);

        }
        
        //we dont actually use this action but lets make it available
        do_action('idea_push_vote_cast',$ideaId,$userId,$voteIntent,$ideaScoreNow,$voteThreshold);
        
        echo apply_filters( 'idea_push_change_vote_render', $returnVariable );
        
        
    } else { 
        //the voter is not allowed to vote, this could because the max votes is set to 0 or the voter has exceeded the vote limit
        echo "FAILURE";
        
    }
    

    wp_die(); 
 
}

add_action( 'wp_ajax_submit_vote', 'idea_push_submit_vote' );
add_action( 'wp_ajax_nopriv_submit_vote', 'idea_push_submit_vote' );



















//helper function to change the vote
function idea_push_increase_or_decrease_votes($voteAmount,$ideaId){

    //enable filtering of the vote
    $voteAmount = apply_filters( 'idea_push_change_vote_amount', $voteAmount );

    //get idea score
    $ideaScore = get_post_meta($ideaId,'votes',true);
    $ideaScore = $ideaScore + $voteAmount;

    update_post_meta($ideaId,'votes',$ideaScore);

}





//helper function to add or remove someone from an array
//for $addRemove accepts 'add' or 'remove'
//for $voterArray it should be either 'up-voters' or 'down-voters'
function idea_push_add_or_remove_from_array($userId,$ideaId,$addRemove,$voterArray){

    $metaValue = get_post_meta($ideaId,$voterArray,true);
    
    $currentTime = current_time( 'timestamp', 0 ); 
    
    
    if($addRemove == 'remove'){
        //this is more tricky
        //we have values like array(array(1,time1),array(2,time2),array(3,time3))
        
        $counter = 0;
        
        // foreach($metaValue as $voter){
            
        //     if($voter[0]==$userId){
        //         $positionInArray =  $counter;
        //         break;
        //     }
            
        //     $counter++;  
        // }

        foreach($metaValue as $arrayid => $voter){
            if($voter[0]==$userId){
                $positionInArray = $arrayid; //$counter;
                break;
            }
        }
        
        if(isset($positionInArray)){
            // unset($metaValue,$positionInArray);    
            unset($metaValue[$positionInArray]);

        }
        
        
        
    } else {
        //we are adding to the array    
        //at this point the meta value above should be in a good format, that is an array of some sort
        array_push($metaValue,array($userId,$currentTime));
        
    }
    
    update_post_meta($ideaId,$voterArray,$metaValue);


}


//helper function to check if someone has voted for an idea already
function idea_push_check_voter_history($checkArray,$userId){
    
    $returnValue = false;
    
    foreach($checkArray as $vote){
        if($vote[0] == $userId){
            $returnValue = true;    
            break;
        }  
    }
    
    return $returnValue;

}







//this function checks to see whether a person is able to vote or create an idea
function idea_push_action_permission($boardId,$userId,$action){
    
    //check if user is admin because if they are let them do whatever they want
    if(user_can($userId, 'manage_options') || user_can($userId, 'idea_push_manager')){
        return true;    
    }
    
    
    $individualBoardSetting = idea_push_get_board_settings($boardId);

    //check if we are getting the max votes or ideas per a day
    if($action == 'vote'){
        $maxVotes = $individualBoardSetting[11]; 
        
        if(array_key_exists(32,$individualBoardSetting)){
            $maxVotesPeriod = intval($individualBoardSetting[32]);
        } else {
            $maxVotesPeriod = 1; 
        }

    } else {
        $maxVotes = $individualBoardSetting[12];   
        
        if(array_key_exists(33,$individualBoardSetting)){
            $maxVotesPeriod = intval($individualBoardSetting[33]);
        } else {
            $maxVotesPeriod = 1; 
        }
    }

    
    



    //if not set or if it equals the default -1 aka unlimited let them vote
    if($maxVotes == '' || $maxVotes == -1){
        return true;
    }

    //this essentially means voting is disabled so return false
    if($maxVotes == 0){
        return false;
    }

    $transientName = 'ideapush_'.$action.'_'.$boardId.'_'.$userId;
    $getTransient = get_transient($transientName);

    //the person hasn't voted yet and theres nothing stopping from voting so lets enable them to vote
    if($getTransient == false){

        set_transient($transientName,1,DAY_IN_SECONDS);
        return true;  

    } else {

        //if the voter has hit or exceeded the limit don't let them vote
        if($getTransient >= $maxVotes){
            return false;
        } else {
            set_transient($transientName,$getTransient+1,DAY_IN_SECONDS*$maxVotesPeriod);
            return true;
        }
    }
}



//this function adds or removes from the users vote bank
//$action can be 'add' or 'remove'
function idea_push_add_or_remove_from_user_vote_bank($userId,$boardId,$action){

    //get is pro
    global $ideapush_is_pro;

    //first check whether the vote bank functionality is even enabled
    $individualBoardSetting = idea_push_get_board_settings($boardId);
    $options = get_option('idea_push_settings');

    //check if we are getting the max votes or ideas per a day
    $enableVoteBank = $individualBoardSetting[30]; 
    $userVoteBankNumber = intval($options['idea_push_amount_of_user_votes_in_bank']);   
    
    if($enableVoteBank == 'Yes'  && $ideapush_is_pro == 'YES'){

        //we need to first check if user has any votes left in their bank
        //first if the user doesn't have a bank lets create one
        if(!get_user_meta($userId, 'ideaPushVotesRemaining', false)){
            //set the users vote meta to the score
            update_user_meta($userId,'ideaPushVotesRemaining',$userVoteBankNumber);
        }

        $users_bank = get_user_meta($userId, 'ideaPushVotesRemaining', true);

        if($users_bank <= 0 && $action == 'remove'){
            return 'FAILURE';    
        }

        //ok the user has credit lets add or remove the credit
        if($action == 'add'){
            $users_bank++;
        } else {
            //we are removing
            $users_bank--;
        }

        update_user_meta($userId,'ideaPushVotesRemaining',$users_bank);

        return 'SUCCESS';

    } else {
        //we don't need to do anything the feature is disabled
        return 'SUCCESS';
    }


    

}




?>