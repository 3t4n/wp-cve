<?php


//add content at the top of the post
function idea_push_add_content_after_title( $content ) {
    if ( is_single() && 'idea' == get_post_type()) {
        
        //enqueue scripts and styles
        wp_enqueue_style(array('custom-frontend-style-ideapush','ideapush-font'));
        wp_enqueue_script(array('alertify','custom-frontend-script-ideapush','scroll-reveal','read-more','custom-frontend-script-ideapush-pro'));

        //declare variables
        $ideaId = get_the_ID();
        
        
        $getBoard = get_the_terms($ideaId,'boards');

        $getBoardId = $getBoard[0]->term_id;

        $getBoardSettings = idea_push_get_board_settings($getBoardId);
        
        $showBoardTo = $getBoardSettings[14];
        $multiIp = $getBoardSettings[27];

        if(!isset($multiIp)){
            $multiIp = 'No';
        }
        
        $currentUserId = idea_push_check_if_non_logged_in_user_is_guest($multiIp);

        if($currentUserId == false){
            $currentUserRole = array();
        } else {
            $currentUserObject = get_user_by( 'id', $currentUserId);
            $currentUserRole = apply_filters('idea_push_extend_role_permissions', $currentUserObject->roles);
        }
        
        if(in_array($showBoardTo, $currentUserRole) || $showBoardTo == "Everyone"){
        
        
            $custom_content = '<div style="margin-bottom: 20px;" class="ideapush-container" data="'.$ideaId.'">';

                $custom_content .= idea_push_single_page_below_title($ideaId);

            $custom_content .= '</div>';


            //get dialog text
            //login to vote title
            $custom_content .= '<div style="display: none;" id="dialog-login-to-vote" data="'.__( 'Please enter your name and email to vote', 'ideapush' ).'"></div>';

            //login form
            $custom_content .= '<div style="display: none;" id="create-user-form" data-first="'.__( 'First name', 'ideapush' ).'" data-last="'.__( 'Last name', 'ideapush' ).'" data-email="'.__( 'Email', 'ideapush' ).'" data-email-confirm="'.__( 'Confirm email', 'ideapush' ).'" data-password="'.__( 'Password', 'ideapush' ).'"></div>';

            //login to vote required fields
            $custom_content .= '<div style="display: none;" id="dialog-login-to-vote-required" data="'.__( 'Please ensure all fields are completed and a valid email is entered.', 'ideapush' ).'"></div>';

            //delete idea dialog
            $custom_content .= '<div style="display: none;" id="dialog-idea-delete" data="'.__( 'Are you sure you want to delete this idea?', 'ideapush' ).'"></div>';

            //idea deleted dialog
            $custom_content .= '<div style="display: none;" id="dialog-idea-deleted" data="'.__( 'The idea has been added to the trash but not permanently deleted.', 'ideapush' ).'"></div>';

            //vote limit dialog
            $custom_content .= '<div style="display: none;" id="dialog-no-vote" data="'.__( 'You have reached your daily vote limit or voting has been disabled on this board.', 'ideapush' ).'"></div>';

            //edit idea popup heading
            $custom_content .= '<div style="display: none;" id="dialog-edit-idea-heading" data="'.__( 'Edit Idea', 'ideapush' ).'"></div>';

            //edit idea popup heading
            $custom_content .= '<div style="display: none;" id="dialog-idea-edited" data="'.__( 'The idea has been updated.', 'ideapush' ).'"></div>';

            //duplicate idea
            $custom_content .= '<div style="display: none;" id="dialog-idea-duplicate" data="'.__( 'Please search for and select the original idea. Once you click submit the duplicate idea will be given a duplicate status and no more votes will be allowed for this idea and votes given to this idea will be transferred to the original idea where appropriate. The author of this duplicate idea will be notified of this status change if notifications are enabled in the plugin settings.', 'ideapush' ).'"></div>';

            //duplicate idea success
            $custom_content .= '<div style="display: none;" id="dialog-idea-duplicate-success" data="'.__( 'Idea successfully marked as duplicate', 'ideapush' ).'"></div>';

            //buttons
            $custom_content .= '<div style="display: none;" id="ok-cancel-buttons" data-submit="'.__( 'Submit', 'ideapush' ).'" data-cancel="'.__( 'Cancel', 'ideapush' ).'" data-yes="'.__( 'Yes', 'ideapush' ).'" data-no="'.__( 'No', 'ideapush' ).'"></div>';


            global $ideapush_is_pro;

            //add suggested idea if pro
            if($ideapush_is_pro == 'YES'){

                //output suggested tags
                //temporarily lets make the ideascope 'Board'
                $ideaScope = $getBoardSettings[24];
                if(!isset($ideaScope)){
                    $ideaScope = 'Board';
                }
        
                //we want to output this anyway even if suggested ideas are disabled because we can use this for the duplicate idea feature
                $custom_content .= idea_push_output_suggested_ideas($ideaScope,$getBoardId);
        
            }



            $custom_content .= $content;


            return $custom_content;
        
        } else {
            
            
            #comments
            $html = '<style>#comments{display: none!important}</style>';

            $html .= '<div id="remove-comments"></div>';
            $html .= __( 'Sorry you do not have permission to view this idea.', 'ideapush' );

            return $html; 
        }
        
        
    } else {
        return $content;
    }
}
add_filter( 'the_content', 'idea_push_add_content_after_title' );








function idea_push_single_page_below_title($ideaId){
    
    
    global $ideapush_is_pro;

    //get options
    $options = get_option('idea_push_settings');
    
    $primaryColor = esc_html($options['idea_push_primary_link_colour']);
    
    $custom_content = '<style>

            .ideapush-container i, .ideapush-container a, .ideapush-container .idea-item-tag:hover, .ideapush-container .idea-item-file:hover, .single-idea .close-button
            {color: '.$primaryColor.';}

            .submit-new-idea,.submit-new-idea:hover,.submit-new-idea:focus, .single-idea .ui-button, .single-idea .ui-button:hover, .single-idea .ui-button:focus, .admin-star-outer
            {background: '.$primaryColor.';}

            .ideapush-container .idea-item-tag:hover, .ideapush-container .idea-item-file:hover, .single-idea .ui-button, .single-idea .ui-button:hover, .single-idea .ui-button:focus, .single-idea .close-button
            {border-color: '.$primaryColor.';}

            .alertify .cancel
            {color: '.$primaryColor.' !important;}

            .alertify .ok, .alertify .cancel
            {border-color: '.$primaryColor.' !important;}

            .alertify .ok
            {background-color: '.$primaryColor.' !important;}


        </style>';
    
    
    
    //do breadcrumbs
    $pageLinkOfPageThatHasShortcode = idea_push_what_page_has_shortcode($ideaId);
    
    if($pageLinkOfPageThatHasShortcode !== false){
        
        //get the boardId
        $boardId = idea_push_get_board_id_from_post_id($ideaId);
        
        if($boardId !== false){
            //get the board settings
            $individualBoardSetting = idea_push_get_board_settings($boardId);
            
            //get the board name
            $boardName = $individualBoardSetting[1];

            $formSettings = $individualBoardSetting[23];
            $currentOption = $options['idea_push_form_settings'];
            // var_dump($currentOption);
            if(strlen($currentOption) < 1){
                $currentOption = '^^^^Default||| || || || || || || ||| ';
            }
            $explodedOptions = explode('^^^^',$currentOption);

            foreach($explodedOptions as $formSetting){

                $explodedSubOptions = explode('|||',$formSetting);
        
                $settingName = $explodedSubOptions[0];
        
                if($settingName == $formSettings){
                    $customFields = $explodedSubOptions[2];
                }
        
            }  

            




            
            //get the idea title
            $ideaTitle = get_the_title($ideaId);
            
            
            
            
            $custom_content .= '<div class="idea-item-breadcrumbs">';

                $custom_content .= '<a href="'.$pageLinkOfPageThatHasShortcode.'">'.$boardName.'</a> <i class="ideapush-icon-Submit breadcrumb-divider"></i>

    <a href="#">'.$ideaTitle.'</a>';        

            $custom_content .= '</div>';   
        
        } //end board id check
        
    }

    //do custom field work
    $custom_field_array = array();

    if($ideapush_is_pro == "YES"){
        $individual_custom_fields = explode('||',$customFields);

        foreach($individual_custom_fields as $customField){
            $further_custom_field_info = explode('|',$customField);
            if(array_key_exists(1,$further_custom_field_info)){
                $field_type = $further_custom_field_info[0];
                $field_name = $further_custom_field_info[1];
                //i dont think we need to get any further info for now
                $custom_field_array[$field_name] = $field_type;
            }
        }
    }
    
    
        
    
    
    $getStatus = get_the_terms($ideaId,'status');

    $getBoard = get_the_terms($ideaId,'boards');

    $getBoardId = $getBoard[0]->term_id;

    $getBoardSettings = idea_push_get_board_settings($getBoardId);
    
    
    
    
    //left side
    $custom_content .= '<div class="idea-item-left">';

        $custom_content .= idea_push_vote_part($ideaId,$getStatus[0]->slug,$getBoardSettings);        

    $custom_content .= '</div>';




    //right side
    $custom_content .= '<div class="idea-item-right">';
        $custom_content .= '<div class="idea-item-right-inner">';
        
            $custom_content .= idea_push_get_idea_meta(get_post($ideaId),$getStatus[0]->slug,$getBoardSettings);
    
            $custom_content .= idea_push_get_tags_and_attachments($ideaId,$custom_field_array,$boardId);
            
        $custom_content .= '</div>';
    $custom_content .= '</div>';

    
    //admin section below
    if(current_user_can('administrator') || current_user_can('idea_push_manager')){
        $custom_content .= '<div class="idea-item-admin-functions">';
            //add admin stuff here
            $custom_content .= '<div class="idea-item-admin-functions-title-area">';
                $custom_content .= '<h3 class="admin-functions-heading">'.__( 'Admin Functions', 'ideapush' ).'</h3>';

                $custom_content .= '<span class="admin-functions-disclaimer">'.__( 'These quick admin functions are only available to admin users.', 'ideapush' ).'</span>';
            $custom_content .= '</div>';
            
            
            $custom_content .= '<div class="idea-statuses-listing">';
                $custom_content .= __( 'Change status:', 'ideapush' ).' <span class="status-listing-container">'.idea_push_render_status($getStatus[0]->slug,"EXCEPT");
            $custom_content .= '</span></div>';    


            $custom_content .= '<a class="idea-item-edit" href="'.get_edit_post_link().'">';
                $custom_content .= '<i class="ideapush-icon-Edit edit-idea-icon" aria-hidden="true"></i> '.__( 'Edit', 'ideapush' );
            $custom_content .= '</a>';

            $custom_content .= '<a href="#" data="'.$ideaId.'" class="idea-item-delete">';
                $custom_content .= '<i class="ideapush-icon-Delete delete-idea-icon" aria-hidden="true"></i> '.__( 'Delete', 'ideapush' );
            $custom_content .= '</a>';



        $custom_content .= '</div>';    

    }

    //do video
    //show video and image if pro
    if($ideapush_is_pro == "YES" && isset($options['idea_push_show_custom_fields'])){

        $custom_content .= '<div class="ideapush-video-section">';

        $postMeta = get_post_meta($ideaId,'',true);

        foreach($postMeta as $key => $value){

            //check if key has our special prefix
            if(strpos($key, 'ideapush-custom-field-') !== false) {

                //dont show if youtube or vimeo link as we want to show that inline instead
                if(strpos(strtolower($value[0]), 'youtube.com') !== false){
                    //do youtube
                    $parts = parse_url($value[0]);
                    parse_str($parts['query'], $query);
                    $video_id = $query['v'];
                    $video_id_exploded = explode(' ',$video_id);
                    $video_id = $video_id_exploded[0];

                    $custom_content .= '<div class="idea-item-attachment-inline-youtube"><iframe width="853" height="480" src="https://www.youtube-nocookie.com/embed/'.$video_id.'" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe></div>';
                }

                //this for shortened youtube videos
                if(strpos(strtolower($value[0]), 'youtu.be') !== false){

                    $video_id = str_replace('https://youtu.be/','',$value[0]);

                    $custom_content .= '<div class="idea-item-attachment-inline-youtube"><iframe width="853" height="480" src="https://www.youtube-nocookie.com/embed/'.$video_id.'" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe></div>';
                }

                if(strpos(strtolower($value[0]), 'vimeo.com') !== false){
                    //do vimeo
                    $positionOfId = strpos ( $value[0] ,'vimeo.com/' );
                    $video_id = substr ($value[0] ,$positionOfId+10 );
                    $video_id_exploded = explode(' ',$video_id);
                    $video_id = $video_id_exploded[0];

                    $custom_content .= '<div class="idea-item-attachment-inline-vimeo" style="padding:56.25% 0 0 0;position:relative;"><iframe src="https://player.vimeo.com/video/'.$video_id.'" style="position:absolute;top:0;left:0;width:100%;height:100%;" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe></div><script src="https://player.vimeo.com/api/player.js"></script>';
                }

                //do images
                $actual_field_name = str_replace('ideapush-custom-field-','',$key);

                if(is_array($custom_field_array) && array_key_exists($actual_field_name,$custom_field_array) && $custom_field_array[$actual_field_name] == 'image'){
                    $image_url = wp_get_attachment_image_src( $value[0], 'full',false );

                    if(!$image_url){
                        $image_url = wp_get_attachment_url( $value[0] ); 

                        $custom_content .= '<a class="idea-item-attachment-inline-file" target="_blank" href="'.$image_url.'">'.__('Download file','ideapush').'</a>';

                    } else {
                        $image_url = $image_url[0];
                        $custom_content .= '<img class="idea-item-attachment-inline-image" src="'.$image_url.'">'; 
                    }
                }




            }    
        } 

        $custom_content .= '</div>';
    }
    
    return $custom_content;
    
}


function idea_push_single_page_below_title_get(){
    
    $ideaId = idea_push_sanitization_validation($_POST['ideaId'],'id');
    
    if($ideaId == false){
        wp_die();    
    }
    
    echo idea_push_single_page_below_title($ideaId);
    
    wp_die();
    
}
add_action( 'wp_ajax_below_title_header', 'idea_push_single_page_below_title_get' );
add_action( 'wp_ajax_nopriv_below_title_header', 'idea_push_single_page_below_title_get' );




//helps to add breadcrumbs to single page
function idea_push_what_page_has_shortcode($ideaId) {
    
    $boardId = idea_push_get_board_id_from_post_id($ideaId);

    if($boardId !== false){
        $shortcode = '[ideapush board="'.$boardId.'"]';
        
        
        $pages = get_pages();
        foreach($pages as $page) {
            
            $pageId = $page->ID;    
            $pageContent = $page->post_content;
            $pageLink = get_permalink($pageId);
            
            if(strpos($pageContent, $shortcode) !== false){
                return $pageLink;
                break;
            }
        }
    }
    
    return false;

}


?>