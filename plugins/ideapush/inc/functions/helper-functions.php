<?php

function idea_push_send_email($to,$subject,$body){
    
    $headers = array('Content-Type: text/html; charset=UTF-8');
    
    wp_mail($to, $subject, $body, $headers);   
    
}


//check to see if email is valid
function idea_push_is_email_valid($email){
    
    if(strlen($email)>0 && strpos($email, '@') !== false){
        return true;
    } else {
        return false;
    }
    
}



function idea_push_shortcode_replacement($ideaId,$setting,$voterId){
    
    //lets do various operations
    
    //do some standard variables
    $postAuthorId = get_post_field( 'post_author', $ideaId );
    $postAuthor = get_user_by('id',$postAuthorId);
    
    $boardSettings = idea_push_get_board_settings(idea_push_get_board_id_from_post_id($ideaId));
    
    $postContent = htmlentities(strip_tags(get_post_field('post_content',$ideaId,'raw'))); 
    
    
    
    
    // 1. replace the title
    $setting = str_replace('[Idea Title]',get_the_title($ideaId),$setting); 
    
    // 2. replace author first name
    $setting = str_replace('[Author First Name]',$postAuthor->first_name,$setting);
    
    // 3. replace author last name
    $setting = str_replace('[Author Last Name]',$postAuthor->last_name,$setting);
    
    // 4. replace board name
    $setting = str_replace('[Board Name]',$boardSettings[1],$setting);
    
    // 5. replace the content
    $setting = str_replace('[Idea Content]',$postContent,$setting);
    
    // 6. replace the link
    $setting = str_replace('[Idea Link]',get_post_permalink($ideaId),$setting);
    
    // 7. replace vote count
    $setting = str_replace('[Vote Count]',get_post_meta($ideaId,'votes',true),$setting);
    
    // 8. do voter name replacement
    if(strlen($voterId)>0){
        
        $voter = get_user_by('id',$voterId);
        
        // 8. replace voter first name
        $setting = str_replace('[Voter First Name]',$voter->first_name,$setting);
        
        // 9. replace voter last name
        $setting = str_replace('[Voter Last Name]',$voter->last_name,$setting);
  
    }
    
    
    // 9. replace the edit link
    $setting = str_replace('[Idea Edit Link]',get_admin_url().'post.php?post='.$ideaId.'&action=edit',$setting);
    

    return $setting;
    
}












//this function sanitizes and validates input to improve security
function idea_push_sanitization_validation($data,$type){
    
    //types include email, name, post id, board name
    
    //if the data is LOGGED-IN MAKE IT PASS
    if($data == 'LOGGED-IN'){
        return $data;     
    }
    
    
    
    //first sanitize inputs
    if($type == 'email'){
        $data = sanitize_email($data);      
    } elseif ($type == 'name'){
        $data = sanitize_text_field($data);    
    } elseif($type == 'id') {
        $data = sanitize_text_field($data);  
    } elseif($type == 'boardname'){
        $data = sanitize_text_field($data);    
    } elseif($type == 'textarea'){
        $data = sanitize_textarea_field($data); 
    } else { 
    }
    
    return $data;
    
    
    //lets now validate the input
   
    if($type == 'email' && strlen($data) > 254 && strpos($data, '@') === false){
        
        return false;

    } elseif ($type == 'name' && 1 === preg_match('~[0-9]~', $data)){
        
        return false;    
        
    } elseif ($type == 'id' && strlen($data) > 9 && is_numeric($data) == false) {
        
        return false;      
        
    } elseif($type == 'boardname' && strlen($data) > 254) {
        
        return false;    
        
    } else {
        
        return $data;
        
    }
     
}



//gets the users ip address
function idea_push_get_ip_address(){
                                    
    if ( ! empty( $_SERVER['HTTP_CLIENT_IP'] ) ) {
        return $_SERVER['HTTP_CLIENT_IP'];    
    } elseif(! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] )) {
        return $_SERVER['HTTP_X_FORWARDED_FOR'];     
    } else {
        return $_SERVER['REMOTE_ADDR'];    
    }

}





//returns user id if logged in or returns false if logged out
function idea_push_check_if_non_logged_in_user_is_guest($multiIp){
    
    
    if(is_user_logged_in()){
                            
        $current_user = wp_get_current_user();
        
        //returns current user id
        $userIpMatchFound =  $current_user->ID;
                            
    } else {
    
        //if the user has enabled multiple ip addresses we don't need to do an IP check and just return false
        if($multiIp == 'Yes'){
            $userIpMatchFound = false;
        } else {
            //get current visitors ip address
            $currentVisitorIP = idea_push_get_ip_address();

            //user is not logged in, lets check the user meta to see if their IP is associated with a user account
            $allIdeaPushUsers = get_users( array( 'role' => 'idea_push_guest' ) );

            $userIpMatchFound = false;

            // Array of WP_User objects.
            foreach ($allIdeaPushUsers as $ideaPushUser) {

                $userID = $ideaPushUser->ID;

                $userIP = get_user_meta($userID,'ip_address',true);

                if($userIP == $currentVisitorIP){
                    //returns user id from lookup
                    $userIpMatchFound = $userID;
                    break;    
                }

            }

        }

    }
    
    return $userIpMatchFound;
   
}









//i dont believe this function isn't used anywhere...why does it exist...probably delete it soon
// function idea_push_check_if_non_logged_in_user_is_guest_frontend(){
    
//     echo idea_push_check_if_non_logged_in_user_is_guest();
//     wp_die(); 
// }
// add_action( 'wp_ajax_is_person_logged_in', 'idea_push_check_if_non_logged_in_user_is_guest_frontend' );
// add_action( 'wp_ajax_nopriv_is_person_logged_in', 'idea_push_check_if_non_logged_in_user_is_guest_frontend' );



function idea_push_is_person_able_to_add_tag(){
    
    $options = get_option('idea_push_settings');

    if(isset($options['idea_push_disable_user_tag_creation'])){
        $disableUserTagCreation = $options['idea_push_disable_user_tag_creation'];  
    } else {
        $disableUserTagCreation = '';    
    }
    
    echo $disableUserTagCreation;
    
    wp_die(); 
}
add_action( 'wp_ajax_is_person_able_to_add_tag', 'idea_push_is_person_able_to_add_tag' );
add_action( 'wp_ajax_nopriv_is_person_able_to_add_tag', 'idea_push_is_person_able_to_add_tag' );










function idea_push_get_board_settings($boardId){
    
    $options = get_option('idea_push_settings');

    $boardSettings = $options['idea_push_board_configuration'];
        
    if( strpos($boardSettings, '^^^') !== false ){
        $explodeBoardSettings = explode('^^^',$boardSettings);
    } else {
        $explodeBoardSettings = explode('||',$boardSettings);
    }

    foreach ($explodeBoardSettings as $boardSetting){

        //explode the settings again
        $explodeBoardSetting = explode('|',$boardSetting);

        if($explodeBoardSetting[0] == $boardId) {
            return $explodeBoardSetting; 
        }

    }
    
    //we can just put anything here but not an Array!
    return 'Nothing found';

}




function idea_push_avatar_exists($id_or_email) {
    //id or email code borrowed from wp-includes/pluggable.php
      $email = '';
      if ( is_numeric($id_or_email) ) {
          $id = (int) $id_or_email;
          $user = get_userdata($id);
          if ( $user )
              $email = $user->user_email;
      } elseif ( is_object($id_or_email) ) {
          // No avatar for pingbacks or trackbacks
          $allowed_comment_types = apply_filters( 'get_avatar_comment_types', array( 'comment' ) );
          if ( ! empty( $id_or_email->comment_type ) && ! in_array( $id_or_email->comment_type, (array) $allowed_comment_types ) )
              return false;
  
          if ( !empty($id_or_email->user_id) ) {
              $id = (int) $id_or_email->user_id;
              $user = get_userdata($id);
              if ( $user)
                  $email = $user->user_email;
          } elseif ( !empty($id_or_email->comment_author_email) ) {
              $email = $id_or_email->comment_author_email;
          }
      } else {
          $email = $id_or_email;
      }
  
      $hashkey = md5(strtolower(trim($email)));
      $uri = 'http://www.gravatar.com/avatar/' . $hashkey . '?d=404';
  
      $data = wp_cache_get($hashkey);
      if (false === $data) {
          $response = wp_remote_head($uri);
          if( is_wp_error($response) ) {
              $data = 'not200';
          } else {
              $data = $response['response']['code'];
          }
          wp_cache_set($hashkey, $data, $group = '', $expire = 60*5);
  
      }		
      if ($data == '200'){
          return true;
      } else {
          return false;
      }
  }






//gets the user avatar
function idea_push_get_user_avatar($userId){

    //create a filter
    if(has_filter( 'idea_push_change_user_image' )){
        return apply_filters( 'idea_push_change_user_image', $userId );
    } else {
       
        $imageUrl = get_user_meta($userId, 'ideaPushImage', true);


        if(empty($imageUrl)){

            if(idea_push_avatar_exists($userId)){
                $imageUrl = get_avatar_url($userId, array('size'=>96));   
            } else {
                $imageUrl = plugins_url( '../images/default-image.png', __FILE__ );    
            }

        }

        return $imageUrl;

    }
 
}


//gets board id from post id
function idea_push_get_board_id_from_post_id($postId){
    
    
    $getTerms = get_the_terms($postId,'boards');

    if($getTerms == false){
        return false;
    } else {
        foreach($getTerms as $term) {
            $boardId = $term->term_id;    
        }
        
        return $boardId;
    }

}




function idea_push_header_render(){
    
    
    $boardNumber = idea_push_sanitization_validation($_POST['boardNumber'],'id');
    $showing = sanitize_text_field($_POST['showing']);
    
    if($boardNumber == false){
        wp_die();      
    }

    $individualBoardSetting = idea_push_get_board_settings($boardNumber);
    $defaultStatusSetting = $individualBoardSetting[29];


    //get status and tag from query string
    if(isset($_GET['status'])){
        $defaultStatus = sanitize_text_field($_GET['status']);
    } elseif(isset($defaultStatusSetting) && strlen($defaultStatusSetting)>0){

        $defaultStatusSetting = strtolower($defaultStatusSetting);
        $defaultStatusSetting = str_replace(" ", "-", $defaultStatusSetting);
        $defaultStatus = $defaultStatusSetting; 

    } else {
        $defaultStatus = 'open';    
    }

    $defaultTag = 'all';    
   
    echo idea_push_header_render_output($boardNumber,$showing,$defaultStatus,$defaultTag,''); //we are going to pass in an empty string for custom fields parameter
    wp_die();
}

add_action( 'wp_ajax_header_render', 'idea_push_header_render' );
add_action( 'wp_ajax_nopriv_header_render', 'idea_push_header_render' );



function idea_push_form_render(){
    
    global $ideapush_is_pro;

    $boardNumber = idea_push_sanitization_validation($_POST['boardNumber'],'id');
    
    if($boardNumber == false){
        wp_die();       
    }

    //set return data variable
    $returnData = '';
    
    $returnData .= idea_push_form_render_output($boardNumber);

    if($ideapush_is_pro == 'YES'){

        $individualBoardSetting = idea_push_get_board_settings($boardNumber);
        $hideLeaderboard = $individualBoardSetting[26];
        $hideCommentsWidget = $individualBoardSetting[31];  

        if($hideLeaderboard !== 'Yes'){
            $returnData .= idea_push_leader_board_render_output($boardNumber); 
        }

        if($hideCommentsWidget !== 'Yes'){
            $returnData .= idea_push_comments_widget_render_output($boardNumber); 
        }

    }

    echo $returnData;
    
    wp_die();
}

add_action( 'wp_ajax_form_render', 'idea_push_form_render' );
add_action( 'wp_ajax_nopriv_form_render', 'idea_push_form_render' );




//function to count posts pending status
function idea_push_count_pending_ideas() {
    
    $args = array(
        'numberposts'   => -1,
        'post_type'     => 'idea',
        'post_status'   => 'pending',
    );
    
    $count_posts = count( get_posts( $args ) ); 
    return $count_posts;
}

//function to count posts pending status
function idea_push_count_ideas_review_status() {
    
    $args = array(
        'numberposts'   => -1,
        'post_type'     => 'idea',
        'post_status'   => 'publish',
        'tax_query' => array(
            array(
                'taxonomy' => 'status',
                'field'    => 'slug',
                'terms'    => array('reviewed')
            )
        )
    );
    
    $count_posts = count( get_posts( $args ) ); 
    return $count_posts;
}



//checks whether user is an admin
function idea_push_is_user_admin($userId){
    
    $user = get_user_by('id', $userId);
    
    $userRoles = $user->roles;   
    
    if(is_array($userRoles)){
        if(in_array('administrator',$userRoles)){     
            return true;
        } else {
            return false;
        }
    } else {
        return false;
    }

}




//this function returns an array of positive or negative voters but also initialises a meta value or reformulates if it is legacy
function idea_push_get_voter_array($ideaId,$upOrDown){
    
    $existingVoters = get_post_meta($ideaId,$upOrDown,true);
    
    if(is_array($existingVoters)){
            
            //lets do nothing as everything is good and in the new format

    } elseif (empty($existingVoters)){

        //lets update the meta with an empty array
        update_post_meta( $ideaId, $upOrDown, array());

    } else {

        //theres existing values but in the older format, so lets put this into the new format
        //we can consider removing this after sometime
        $updatedMeta = array();
        $explodedUsers = explode(',',$existingVoters);

        foreach($explodedUsers as $user){
            if($user !== ''){
                $currentTime = current_time( 'timestamp', 0 ); 
                array_push($updatedMeta,array(intval($user),$currentTime));      
            }
        }

        update_post_meta( $ideaId, $upOrDown, $updatedMeta);

    }

    //return updated value
    return get_post_meta($ideaId,$upOrDown,true);
 
}



//gets the users name
function idea_push_get_user_name($userId){
    if(has_filter( 'idea_push_change_user_name' )){
        return apply_filters( 'idea_push_change_user_name', $userId );
    } else {
        $userObject = get_user_by('id',$userId); 
        
        $returnName = $userObject->first_name;

        if(strlen($returnName)<1){
            $returnName = $userObject->display_name;
        }

        return $returnName; 
    }
}

//gets the link to the authors page
function idea_push_get_user_author_page($userId){

    if(has_filter( 'idea_push_change_author_link' )){
        return apply_filters( 'idea_push_change_author_link', $userId );
    } else {
        
        // $userObject = get_user_by('id',$userId);    
        
        return get_author_posts_url($userId).'?post_type=idea';

    }

}

function idea_push_check_default_showing($optionValue,$defaultShowing){
    if($optionValue == $defaultShowing){
        return 'selected="selected"';
    } else {
        return '';
    }
}
/**
* 
*
*
* Adds a counter to either rosters, teams or dates or members
* input should be either roster,team,date, notification,autonotification
*/
function idea_push_user_counter($type) {

    $optionName = 'idea_push_counter';

    //get existing option
    $existingValue = get_option($optionName);

    //if the option doesnt even exist we need to create it
    if($existingValue == false){
        //the option doesn't exist so we need to create it
        $existingValue = array($type=>1);  
        $updatedNumber = 1; 
    } else {
        //the option at least exists
        //check to see if the type is in the array
        if(array_key_exists($type,$existingValue)){
            //the item exists so we need to update it
            $typeValue = $existingValue[$type];
            //add one to it
            $typeValue++;
            //now update the option
            $existingValue[$type] = $typeValue;

            $updatedNumber = $typeValue;
            
        } else {
            //the option exists but the type doesnt
            $existingValue[$type] = 1;
            $updatedNumber = 1; 

        }
    }


    update_option($optionName,$existingValue);
    return $updatedNumber;

} 





/**
* 
*
*
* Delete Plugin Updates transient/option
*/
function idea_push_delete_plugin_updates_transient() { 

	if (!current_user_can('manage_options')) {
        wp_die();    
    }

    delete_option('_site_transient_update_plugins');

    echo 'SUCCESS';
    wp_die();   

}
add_action( 'wp_ajax_idea_push_delete_plugin_updates_transient', 'idea_push_delete_plugin_updates_transient' );

function idea_push_get_tags($boardId){

    $args = array(
        'post_type' => 'idea',
        'post_status' => 'publish',
        'posts_per_page' => -1,
        'tax_query' => array(
            array(
                'taxonomy' => 'boards',
                'field'    => 'term_id',
                'terms'    => array($boardId)
            ),
        )
    );

    $ideaPosts = get_posts($args);

    $ideaPostsIds = wp_list_pluck($ideaPosts,'ID');

    $ideaPostsFilter = wp_get_object_terms($ideaPostsIds, 'tags');

    return $ideaPostsFilter;
}



function idea_push_output_styles(){

    //get options
    $options = get_option('idea_push_settings');

    $html = '';

    $primaryColor = esc_html($options['idea_push_primary_link_colour']);

    //lets output some css
    $html .= '<style>

    .ideapush-container i, .ideapush-container a, .ideapush-container .idea-item-tag:hover, .ideapush-container .idea-item-file:hover, .ideapush-dialog .close-button, .ideapush-dialog-image .close-button, .idea-page-number.active,.idea-page-number:hover
    {color: '.$primaryColor.';}


    .ideapush-container .idea-item-tag:hover, .ideapush-container .idea-item-file:hover, .ideapush-dialog .ui-button,.ideapush-dialog .ui-button:hover,.ideapush-dialog .ui-button:focus, .ideapush-dialog .close-button, .ideapush-dialog-image .close-button, .idea-page-number.active,.idea-page-number:hover, .ideapush-container .challenge-message-box, .ideapush-container .vote-bank-message-box
    {border-color: '.$primaryColor.';}

    .submit-new-idea,.submit-new-idea:hover,.submit-new-idea:focus, .update-user-profile,.update-user-profile:hover,.update-user-profile:focus, .ideapush-dialog .ui-button,.ideapush-dialog .ui-button:hover,.ideapush-dialog .ui-button:focus, .admin-star-outer,.admin-star-outer-large, .create-idea-form-reveal,.create-idea-form-reveal:hover,.create-idea-form-reveal:focus, .ideapush-container .challenge-message-box,
    .ideapush-container .vote-bank-message-box, .submit-new-comment,.submit-new-comment:hover,.submit-new-comment:focus
    {background-color: '.$primaryColor.';}

    .alertify .cancel
    {color: '.$primaryColor.' !important;}

    .alertify .ok, .alertify .cancel
    {border-color: '.$primaryColor.' !important;}

    .alertify .ok
    {background-color: '.$primaryColor.' !important;}


    </style>';


    return $html;

}

function randonNumberBetweenOneAndTen(){
    return rand(1,10);     
}

function randomSigngenerator(){
    
    $randomNumber = rand(1,3);   
    
    if($randomNumber==1){
        return "+"; 
    }
    
    if($randomNumber==2){
        return "-"; 
    }
    
    if($randomNumber==3){
        return "x"; 
    }
    
}



//function to help translate status names
function idea_push_translate_status($status){

    $options = get_option('idea_push_settings');

    $option_status = strtolower($status);
    $option_status = str_replace('-','_',$option_status);
    $option_status = str_replace(' ','_',$option_status);

    $option = 'idea_push_change_'.$option_status.'_status';

    if(is_array($options)){
        if(array_key_exists($option,$options)){

            $translated_option = $options[$option];

            if(strlen($translated_option)>0){
                $status = $translated_option;
            }
        }
    }

    return $status;
  
}


?>