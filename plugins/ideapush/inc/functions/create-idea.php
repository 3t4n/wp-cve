<?php







function idea_push_create_idea(){
    
    //get the options
    $options = get_option('idea_push_settings');
    
    
    //get post variables
    $boardNumber = idea_push_sanitization_validation($_POST['boardNumber'],'id');
    $title = idea_push_sanitization_validation($_POST['title'],'boardname');

    //enable HTML to be entered if desired in settings
    if( isset($options['idea_push_allow_html_input']) ){
        $description = $_POST['description'];
    } else {
        $description = sanitize_textarea_field($_POST['description']);
    }
    
    $firstName = idea_push_sanitization_validation($_POST['firstName'],'name');
    $lastName = idea_push_sanitization_validation($_POST['lastName'],'name');
    $email = idea_push_sanitization_validation($_POST['email'],'email');
    $password = idea_push_sanitization_validation($_POST['password'],'name');
    $tags = explode(',',sanitize_text_field($_POST['tags']));
    
    
    
    if($boardNumber == false || $title == false || $firstName == false || $lastName == false || $email == false || $password == false){
        wp_die();
    }
    


    //get board settings
    $individualBoardSetting = idea_push_get_board_settings($boardNumber);
    $holdIdeas = $individualBoardSetting[3];
    $showComments = $individualBoardSetting[4];
    $boardName = $individualBoardSetting[1];
    $multiIp = $individualBoardSetting[27];
    
    //is user logged in
    $userId = idea_push_check_if_non_logged_in_user_is_guest($multiIp);

    
    if($userId == false){
        $userId = idea_push_create_user_common($firstName,$lastName,$email,$password);    

        if($userId == false){
            //this means the person is trying to create an account but their email already exists so they switched computers in which case they need to logon
            echo "DUPLICATE";   
            wp_die();
        }
    }
    
    
    
    
    
    //here is where we do our check to see if the person is allowed to create a new idea
    if(idea_push_action_permission($boardNumber,$userId,'idea')){
        

        if($holdIdeas == 'Yes'){
            $postStatus = 'pending';     
        } else {
            $postStatus = 'publish';     
        }

        if($showComments == 'Yes'){
            $commentStatus = 'open';     
        } else {
            $commentStatus = 'closed';     
        }
        
        $currentTime = current_time( 'timestamp', 0 );


        $metaArray = array(
            'votes' => '1',
            'up-voters' => array(array($userId,$currentTime)),   
            'current-status' => 'open', 
            'frontend-created' => 'true', 
        );

        //this is where we will add custom meta by adding items to the metaArray
        $customFields = sanitize_text_field($_POST['customFields']);

        //first check if it is empty or not
        if($customFields !== ''){

            //lets split the pipes baby
            $explodedCustomFields = explode('||||',$customFields);
            //cycle through each field
            foreach($explodedCustomFields as $customField){

                //lets explode things again
                if(strlen($customField)>0){
                    $customFieldInformation = explode('|||',$customField);
                    if(strlen($customField)>0){
                        $customFieldName = 'ideapush-custom-field-'.$customFieldInformation[0];
                        $customFieldValue = $customFieldInformation[1];

                        //now lets add to the meta array
                        $metaArray[$customFieldName] = $customFieldValue;    
                    }
                }

            }

        }


        //what we want to do now is get any custom field images
        //we will save this as the field name and the attachment id
        $customImageFields = sanitize_text_field($_POST['customImageFields']);

        if($customImageFields !== ''){

            //lets split the pipes baby
            $explodedCustomImageFields = explode('||||',$customImageFields);
            //cycle through each field
            foreach($explodedCustomImageFields as $customImageField){

            
                //lets get the attachment
                $imageName = strtolower($customImageField);
                $imageName = str_replace(' ','-', $imageName);



                if(isset($_FILES[$imageName])){
        
                    $uploadedFile = $_FILES[$imageName]; 
        
                    if ( ! function_exists( 'wp_handle_upload' ) ) {
                        require_once( ABSPATH . 'wp-admin/includes/file.php' );
                    }
        
                    $upload_overrides = array( 'test_form' => false );
                    $moveFile = wp_handle_upload( $uploadedFile, $upload_overrides );
        
        
                    if ( $moveFile && ! isset( $moveFile['error'] ) ) {
        
                        $filePath = $moveFile['file'];
                        $fileType = wp_check_filetype( basename( $filePath ), null );
        
                        $wp_upload_dir = wp_upload_dir();
        
                        $attachmentData = array(
                            'guid'           => $wp_upload_dir['url'] . '/' . basename( $filePath ), 
                            'post_mime_type' => $fileType['type'],
                            'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $filePath ) ),
                            'post_content'   => '',
                            'post_status'    => 'inherit'
                        );
        
                        $attach_id = wp_insert_attachment( $attachmentData, $filePath );
        
                        require_once( ABSPATH . 'wp-admin/includes/image.php' );
        
                        $attach_data = wp_generate_attachment_metadata($attach_id, $filePath);
        
                        wp_update_attachment_metadata($attach_id, $attach_data);
        
                        //here add to meta array
                        $customFieldName = 'ideapush-custom-field-'.$customImageField;

                        //now lets add to the meta array
                        $metaArray[$customFieldName] = $attach_id;  
    
                    } else {

                    }
        
        
                }









                

            }

        }




        //now lets actually create the post
        $ideaArray = array(
            'post_title'   => $title,
            'post_content' => $description,
            'post_status'  => $postStatus,
            'post_author'  => $userId,
            'comment_status'  => $commentStatus,
            'post_type'  => 'idea',
            'meta_input'   => $metaArray,
        );

        



        
        //this code below does not work because guests dont have the right capability and I couldn't figure out how to give them this capability
//        if(!empty($tags)){
//            $ideaArray['tax_input'] = array('tags'=>$tags);  
//        }
        

        //if tags are board specific add a prefix to the tag name
        if(isset($individualBoardSetting[22]) && $individualBoardSetting[22] !== 'Global'){
            $updatedTags = array();

            foreach($tags as $tag){
                if(strlen($tag)>0){

                    //if the tag already has the appended board number, then dont add it again!
                    if(strpos($tag, 'BoardTag-') !== false){
                        $newTagName = $tag;
                    } else {
                        $newTagName = 'BoardTag-'.$boardNumber.'-'.$tag;
                    }

                    array_push($updatedTags,$newTagName);

                }
            }

            $tags = $updatedTags;

        }

        

        

        $newIdeaId = wp_insert_post($ideaArray);

        //set status to open
        wp_set_object_terms($newIdeaId,'open','status',false);

        //set tags
        wp_set_object_terms($newIdeaId,$tags,'tags',false);

        //set the board
        wp_set_object_terms($newIdeaId,$boardName,'boards',false);


        //lets flush the rewrite rules aka permalinks so that our newly created tags are clickable
        flush_rewrite_rules();



        //do attachment

        if(isset($_FILES['attachment'])){
            
            $uploadedFile = $_FILES['attachment']; 

            if ( ! function_exists( 'wp_handle_upload' ) ) {
                require_once( ABSPATH . 'wp-admin/includes/file.php' );
            }



            $upload_overrides = array( 'test_form' => false );
            $moveFile = wp_handle_upload( $uploadedFile, $upload_overrides );



            if ( $moveFile && ! isset( $moveFile['error'] ) ) {

                $filePath = $moveFile['file'];
                $fileType = wp_check_filetype( basename( $filePath ), null );

                $wp_upload_dir = wp_upload_dir();

                $attachmentData = array(
                    'guid'           => $wp_upload_dir['url'] . '/' . basename( $filePath ), 
                    'post_mime_type' => $fileType['type'],
                    'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $filePath ) ),
                    'post_content'   => '',
                    'post_status'    => 'inherit'
                );

                $attach_id = wp_insert_attachment( $attachmentData, $filePath, $newIdeaId );




                require_once( ABSPATH . 'wp-admin/includes/image.php' );

                $attach_data = wp_generate_attachment_metadata($attach_id, $filePath);

                wp_update_attachment_metadata($attach_id, $attach_data);



                //accepted image file types
                $acceptedImageFileTypes = array('jpg','jpeg','png','gif','ico');

                //only set the featured image if the file type is appropriate

                if( in_array($fileType['ext'],$acceptedImageFileTypes) ){
                    set_post_thumbnail($newIdeaId, $attach_id);
                } else {
                    //we need to add custom meta to the post with the file URL
                    $fileUrl = wp_get_attachment_url($attach_id);
                    update_post_meta( $newIdeaId,'idea-attachment',$fileUrl);

                }

                



            } else {
        //        /**
        //         * Error generated by _wp_handle_upload()
        //         * @see _wp_handle_upload() in wp-admin/includes/file.php
        //         */
        //        echo $movefile['error'];
            }


        }


        //enable custom hook when idea is created
        //we have disabled this because we are going to put this in the pro end of the plugin in the post transition status that way the event will fire for both ideas created in the front and BACK end
        do_action('idea_push_after_idea_created',$newIdeaId,$userId,$title,$description);



        //send email to admin if enabled
        if(isset($options['idea_push_notification_idea_submitted'])){

            if(idea_push_is_email_valid($options['idea_push_notification_email'])){

                $to = $options['idea_push_notification_email'];
                
                
                $subject = idea_push_shortcode_replacement($newIdeaId, $options['idea_push_notification_idea_submitted_subject'],'');

                $body = idea_push_shortcode_replacement($newIdeaId, $options['idea_push_notification_idea_submitted_content'],'');
                

                idea_push_send_email($to,$subject,$body);  
                
                do_action('idea_push_idea_created_admin_notification',$newIdeaId,$options['idea_push_notification_idea_submitted_subject'].'|'.$options['idea_push_notification_idea_submitted_content']);
                

            }

        }




        //get standard variables
        $authorEmail = get_user_by('id',$userId);    
        $authorEmail = $authorEmail->user_email;




        //send email to author if enabled
        if(isset($options['idea_push_notification_author_idea_created_published_enable']) && $holdIdeas !== 'Yes'){

            $subject = idea_push_shortcode_replacement($newIdeaId, $options['idea_push_notification_author_idea_created_published_subject'],'');

            $body = idea_push_shortcode_replacement($newIdeaId, $options['idea_push_notification_author_idea_created_published_content'],'');


            //send email to author on first publush
            idea_push_send_email($authorEmail,$subject,$body);  
            
            do_action('idea_push_idea_created_published_author_notification',$newIdeaId,$options['idea_push_notification_author_idea_created_published_subject'].'|'.$options['idea_push_notification_author_idea_created_published_content']);
            
        }


        //send email to author if enabled
        if(isset($options['idea_push_notification_author_idea_created_reviewed_enable']) && $holdIdeas == 'Yes'){

            $subject = idea_push_shortcode_replacement($newIdeaId,$options['idea_push_notification_author_idea_created_reviewed_subject'],'');
            $body = idea_push_shortcode_replacement($newIdeaId,$options['idea_push_notification_author_idea_created_reviewed_content'],'');

            //send email to author on first publush
            idea_push_send_email($authorEmail,$subject,$body);
            
            do_action('idea_push_idea_created_reviewed_author_notification',$newIdeaId,$options['idea_push_notification_author_idea_created_reviewed_subject'].'|'.$options['idea_push_notification_author_idea_created_reviewed_content']);
            
            

        }
        
        
        

        
        
        
        //send back data
        echo $holdIdeas.'|'.$userId;

    } else {
        //the person is not allowed to create a new idea, this could because the max votes is set to 0 or the voter has exceeded the vote limit
        echo "FAILURE";    
       
    }

    wp_die();
}

add_action( 'wp_ajax_create_idea', 'idea_push_create_idea' );
add_action( 'wp_ajax_nopriv_create_idea', 'idea_push_create_idea' );












//this function deletes an idea
function idea_push_delete_idea(){
    
    $ideaId = idea_push_sanitization_validation($_POST['ideaId'],'id');
    
    if($ideaId == false){
        wp_die(); 
    }
    
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
            wp_trash_post($ideaId);    
        }
        
    }
    

    echo "SUCCESS";
    wp_die();    
}

add_action( 'wp_ajax_delete_idea', 'idea_push_delete_idea' );
add_action( 'wp_ajax_nopriv_delete_idea', 'idea_push_delete_idea' );

?>