<?php

function idea_push_form_render_output($boardNumber){
    
    $individualBoardSetting = idea_push_get_board_settings($boardNumber);
    
    //declare standard variables
    $boardId = $individualBoardSetting[0];
    $boardName = $individualBoardSetting[1];
    $voteThreshold = $individualBoardSetting[2];
    $holdIdeas = $individualBoardSetting[3];
    $showComments = $individualBoardSetting[4];
    $showTags = $individualBoardSetting[5];
    $showAttachments = $individualBoardSetting[6];
    $showBoardTitle = $individualBoardSetting[7];
    $showVoteTarget = $individualBoardSetting[8];
    $guestAccess = $individualBoardSetting[9];
    $downVoting = $individualBoardSetting[10];
    $formSettings = $individualBoardSetting[23];
    $multiIp = $individualBoardSetting[27];
        
    if(!isset($multiIp)){
        $multiIp = 'No';
    }

    $currentUserId = idea_push_check_if_non_logged_in_user_is_guest($multiIp);
    

    //if not pro set the form settings to the default Default value
    global $ideapush_is_pro;

    if($ideapush_is_pro !== 'YES'){
        $formSettings = 'Default';       
    }


    //get options
    $options = get_option('idea_push_settings');

    $currentOption = $options['idea_push_form_settings'];
    
    // var_dump($currentOption);
    
    if(strlen($currentOption) < 1){
        $currentOption = '^^^^Default||| || || || || || || ||| ';
    }


    if(strpos($currentOption, '^^^^') !== false){
        $explodedOptions = explode('^^^^',$currentOption);
    } else {
        $explodedOptions = explode('||||',$currentOption);   
    }

    

    foreach($explodedOptions as $formSetting){

        $explodedSubOptions = explode('|||',$formSetting);

        $settingName = $explodedSubOptions[0];

        if($settingName == $formSettings){

            $standardFormLabels = $explodedSubOptions[1];
            $customFields = $explodedSubOptions[2];

        }

    }  




    
    $html = '';

    $html .= idea_push_output_styles();

    //output suggested tags for pro users
    //we are going to output this here as that way when new ideas are created those tags can be used in new ideas created
    //because when we publish an idea we rerender the header
    //here is where we will output tags
    if($ideapush_is_pro == 'YES'){

        //we need to get the actual existings tags    
        $tagScope = $individualBoardSetting[22]; //can be either Board or Global

        if(!isset($tagScope)){
            $tagScope = 'Global';
        }
        
        //output suggested tags
        $html .= idea_push_output_suggested_tags($tagScope,$boardId);

        //output suggested tags
        //temporarily lets make the ideascope 'Board'
        $ideaScope = $individualBoardSetting[24];
        if(!isset($ideaScope)){
            $ideaScope = 'Board';
        }

        //we want to output this anyway even if suggested ideas are disabled because we can use this for the duplicate idea feature
        $html .= idea_push_output_suggested_ideas($ideaScope,$boardId);

    }


    if($currentUserId !== false){
        $userObject = get_user_by('id',$currentUserId);
        //lets get the users info if they exist
        $user_roles = ( array ) $userObject->roles;
        //deny certain roles from creating ideas
        $denied_roles = apply_filters('idea_push_deny_roles_from_creating_ideas', array());

        $role_match_check = array_intersect($user_roles, $denied_roles);
    } else {
        $role_match_check = array(); 
    }        

    //prevent certain roles from creating ideas
    if( empty($role_match_check) ){

        $html .= '<div class="ideapush-form-inner">';

            if($currentUserId !== false){

                $userObject = get_user_by('id',$currentUserId);

                $html .= '<div class="user-profile-container">';


                    $html .= '<div class="user-profile-image-container">';
                        $html .= '<img class="user-profile-image" src="'.esc_url(idea_push_get_user_avatar($currentUserId)).'" />';

                        //do admin star            
                        if(idea_push_is_user_admin($currentUserId)){
                            $html .= '<span class="admin-star-outer-large"><i class="ideapush-icon-Star admin-star-icon-large"></i></span>';    
                        }
                    
                    $html .= '</div>';
                
                    $html .= '<div class="user-profile-detail">';
                    
                        $html .= '<a class="user-profile-name" href="'.esc_url(idea_push_get_user_author_page($currentUserId)).'">'.esc_html(idea_push_get_user_name($currentUserId)).'</a>'; 


                        //only show edit link if user profile editing enabled
                        if(!isset($options['idea_push_disable_profile_edit'])){
                            //edit link
                            $html .= '<a class="user-profile-edit" href="#">'.__( 'Edit', 'ideapush' ).'</a>'; 
                        }

                        if(!isset($options['idea_push_disable_profile_edit']) && $multiIp == 'Yes'){
                            $html .= '<span class="link-separator">|</span>';    
                        }

                        global $wp;
                        $currentPage = home_url($wp->request);

                        //show login
                        if($multiIp == 'Yes'){
                            $html .= '<a href="'.wp_logout_url($currentPage).'" class="ideapush-logout-link">'.__( 'Logout', 'ideapush' ).'</a>';
                        }

                        

                        
                
                    $html .= '</div>';
                
                    if(!isset($options['idea_push_disable_profile_edit'])){
                        $html .= '<div class="user-profile-edit-form">';
                            //first name
                            $html .= '<input placeholder="'.__( 'First name', 'ideapush' ).'" type="text" class="ideapush-form-first-name-edit" value="'.esc_html($userObject->first_name).'" maxlength="100" required>';     

                            //last name
                            $html .= '<input placeholder="'.__( 'Last name', 'ideapush' ).'" type="text" class="ideapush-form-last-name-edit"  maxlength="100" value="'.esc_html($userObject->last_name).'" required>'; 

                            //email
                            $html .= '<input placeholder="'.__( 'Email', 'ideapush' ).'" type="email" class="ideapush-form-email-edit" value="'.esc_html($userObject->user_email).'" maxlength="150" required>'; 

                            //password
                            // $html .= '<input placeholder="'.__( 'Password', 'ideapush' ).'" type="password" class="ideapush-form-password-edit" value="'.esc_html($userObject->user_pass).'" required>'; 
                    
                            //image
                            $html .= '<input class="ideapush-user-profile-attachment" type="file" name="fileToUpload" id="userProfileImage" accept=".jpg, .jpeg, .png, .gif">';
                    
                            $html .= '<label class="ideapush-user-profile-attachment-label" for="userProfileImage"><i class="ideapush-icon-Image"></i> '.__( 'Update image', 'ideapush' ).'</label>';
                    
                            //submit
                            $html .= '<button class="update-user-profile">'.__( 'Update', 'ideapush' ).' <i class="ideapush-icon-Submit"></i></button>';
                        $html .= '</div>';

                    }    
                
                $html .= '</div>';

            }
        
                    
            $standardFormLabelsExploded = explode('||',$standardFormLabels);
        
            //form title
            if(strlen($standardFormLabelsExploded[0])>1){
                $formTitle = $standardFormLabelsExploded[0];    
            } else {
                $formTitle = 'Push your idea';
            }
            $html .= '<span class="ideapush-form-title">'.__(esc_html($formTitle),'ideapush').'</span>';        

            //if guess access is enabled lets show the name and email field if the user isnt a known user
            if($guestAccess == 'Yes'){


                //check if person is a guest
                if($currentUserId == false){

                    global $wp;
                    $currentPage = home_url($wp->request);
                    $login_link = apply_filters( 'idea_push_change_login_link', wp_login_url($currentPage));

                    //show login
                    $html .= '<a href="'.$login_link.'" class="ideapush-login-link">'.__( 'Login', 'ideapush' ).'</a>'; 

                    //first name
                    $html .= '<input type="text" class="ideapush-form-first-name" placeholder="'.__( 'First name', 'ideapush' ).'" maxlength="100" required>';     

                    //last name
                    $html .= '<input type="text" class="ideapush-form-last-name" placeholder="'.__( 'Last name', 'ideapush' ).'" maxlength="100" required>'; 

                    //email
                    $html .= '<input type="email" class="ideapush-form-email" placeholder="'.__( 'Email', 'ideapush' ).'" maxlength="150" required>'; 

                    //password
                    $html .= '<input type="password" class="ideapush-form-password" placeholder="'.__( 'Password', 'ideapush' ).'" required>'; 
                    
                }

            }


            //idea suggestion
            if(isset($options['idea_push_suggested_idea'])){
                $html .= '<div class="suggested-idea"></div>';    
            }

            //idea title
            if(strlen($standardFormLabelsExploded[1])>1){
                $ideaTitlePlaceholder = esc_html($standardFormLabelsExploded[1]);    
            } else {
                $ideaTitlePlaceholder = 'Idea title';
            }

            

            $html .= '<input type="text" class="ideapush-form-idea-title" placeholder="'.__($ideaTitlePlaceholder,'ideapush').'" maxlength="250" required>'; 

            //idea description
            if(strlen($standardFormLabelsExploded[2])>1){
                $ideaDescriptionPlaceholder = esc_html($standardFormLabelsExploded[2]);    
            } else {
                $ideaDescriptionPlaceholder = 'Add additional details';
            }

            $max_characters = apply_filters('idea_push_max_characters_for_description', 2000);

            $html .= '<textarea class="ideapush-form-idea-description" placeholder="'.__($ideaDescriptionPlaceholder,'ideapush').'" maxlength="'.$max_characters.'" rows="8" required="required"></textarea>';

            //show character count here
            $html .= '<span class="ideapush-form-idea-description-counter"><span class="counter-number">0</span>/'.$max_characters.'</span>';

        
            //idea tags
            if($showTags == 'Yes'){
                if(strlen($standardFormLabelsExploded[3])>1){
                    $ideaTagsPlaceholder = esc_html($standardFormLabelsExploded[3]);    
                } else {
                    $ideaTagsPlaceholder = 'Tags';
                }
        
                if(isset($options['idea_push_enable_tag_suggestion'])){
                    $html .= '<div class="suggested-tags"></div>';    
                }

                
                
                $html .= '<div dataError="'.__( 'You can\'t enter custom tags', 'ideapush' ).'" dataDuplicateError="'.__( 'Enter a unique value', 'ideapush' ).'" class="ideapush-form-idea-tags" >';
                $html .= '<input placeholder="'.__($ideaTagsPlaceholder,'ideapush').'" class="ideapush-form-idea-tags-input"></input>';
                $html .= '</div>';    
            }

        
        
        
        
        
        
        
            //idea attachment
            if($showAttachments == 'Yes'){
                
                if(strlen($standardFormLabelsExploded[4])>1){
                    $attachmentText = esc_html($standardFormLabelsExploded[4]);    
                } else {
                    $attachmentText = 'Attach image';
                }

                $html .= '<input class="ideapush-form-idea-attachment" type="file" name="fileToUpload" id="fileToUpload" accept="'.apply_filters('idea_push_allowed_file_types','.jpg, .jpeg, .png, .gif').'">';
                
                $html .= '<label class="ideapush-form-idea-attachment-label" for="fileToUpload"><i class="ideapush-icon-Image"></i> '.__($attachmentText,'ideapush').'</label>';

            }
        


            //do custom fields if pro
            if($ideapush_is_pro == "YES"){
                $html .= idea_push_create_custom_fields($customFields);
            }

        
        


            //button
            if(strlen($standardFormLabelsExploded[5])>1){
                $ideaButtonText = esc_html($standardFormLabelsExploded[5]);    
            } else {
                $ideaButtonText = 'Push';
            }
            
        
        
        
            //enhanced bot protection
            if(isset($options['idea_push_enable_bot_protection']) && $options['idea_push_enable_bot_protection'] == 1){
                
                $firstNumber = randonNumberBetweenOneAndTen();
                $secondNumber = randonNumberBetweenOneAndTen();    
                $sign = randomSigngenerator();
                
                $html .= '<input data-number-one="'.$firstNumber.'" data-number-two="'.$secondNumber.'" data-sign="'.$sign.'" type="number" class="ideapush-form-math-problem" placeholder="'.$firstNumber.' '.$sign.' '.$secondNumber.'" required>';    
                
                //honeypot
                $html .= '<input type="text" class="ideapush-form-extended-description" placeholder="Please enter an extended description of the idea" required>'; 
            }
            
            
            
            //show privacy notification checkbox if enabled in settings
            $privacyNotification = $options['idea_push_privacy_confirmation'];

            if(isset($privacyNotification) && strlen($privacyNotification)>0){
                //display checkbox

                $privacyNotification = wp_kses($privacyNotification, array(
                    'a' => array(
                        'href' => array(),
                        'title' => array()
                    ),
                    'br' => array(),
                    'em' => array(),
                    'strong' => array(),
                    'p' => array(),
                ));

                $html .= '<input type="checkbox" class="ideapush-form-privacy-confirmation"><span class="ideapush-form-privacy-confirmation-text">'.$privacyNotification.'</span><br>';
            }
        
            //submit button
            $html .= '<button class="submit-new-idea">'.__($ideaButtonText,'ideapush').' <i class="ideapush-icon-Submit"></i></button>';
            
        
            //display loading
            $html .= '<span class="idea-publish-loading" style="display:none;">'.__( 'Hold on', 'ideapush' ).' <i class="ideapush-icon-Loading idea-publish-loading-icon"></i></span>';
        
        
        
        $html .= '</div>'; //end inner

    } //end user role check


    //output dialogs
    //published dialog
    $html .= '<div style="display: none;" id="dialog-idea-published" data="'.__( 'Idea submitted successfully', 'ideapush' ).'"></div>';

    //reviewed dialog
    $html .= '<div style="display: none;" id="dialog-idea-reviewed" data="'.__( 'Your idea has been submitted and will be reviewed soon', 'ideapush' ).'"></div>';

    //recaptcha fail dialog
    $html .= '<div style="display: none;" id="dialog-recaptcha-fail" data="'.__( 'Please make sure the reCAPTCHA is validated and all fields are completed', 'ideapush' ).'"></div>';

    //math fail dialog
    $html .= '<div style="display: none;" id="dialog-math-fail" data="'.__( 'You did not enter in a correct math value', 'ideapush' ).'"></div>';

    //honey fail dialog
    $html .= '<div style="display: none;" id="dialog-honey-fail" data="'.__( 'It looks like you could be a robot, or alternatively please turn off any form autofill you have setup', 'ideapush' ).'"></div>';

    //privacy fail dialog
    $html .= '<div style="display: none;" id="dialog-privacy-fail" data="'.__( 'Please accept our privacy statement', 'ideapush' ).'"></div>';

    //file fail dialog
    $html .= '<div style="display: none;" id="dialog-file-fail" data="'.__( 'Please make sure all fields are completed', 'ideapush' ).'"></div>';

    //attachment fail dialog
    $html .= '<div style="display: none;" id="dialog-attachment-fail" data="'.__( 'There was an issue with the file you uploaded, please try again', 'ideapush' ).'"></div>';

    //login to vote title
    $html .= '<div style="display: none;" id="dialog-login-to-vote" data="'.__( 'You must first register your name and email address before you can vote. Please enter your information below and then try voting again.', 'ideapush' ).'"></div>';

    //login to vote required fields
    $html .= '<div style="display: none;" id="dialog-login-to-vote-required" data="'.__( 'Please ensure all fields are completed and a valid email is entered.', 'ideapush' ).'"></div>';
    
    //existing account
    $html .= '<div style="display: none;" id="dialog-existing-account" data="'.__( 'An account already exists with this email, please login to associate the idea with your existing account.', 'ideapush' ).'"></div>';
                
    //user profile error
    $html .= '<div style="display: none;" id="dialog-user-profile-error" data="'.__( 'Please ensure a first and last is completed and your email is a valid email.', 'ideapush' ).'"></div>';

    //no votes in bank
    $html .= '<div style="display: none;" id="no-votes-in-bank" data="'.__( 'You have no votes votes left. Please wait until one of your ideas meets the threshold score or remove one of your existing votes.', 'ideapush' ).'"></div>';

    //no comment
    $html .= '<div style="display: none;" id="no-comment" data="'.__( 'Please enter a comment.', 'ideapush' ).'"></div>';


    if(isset($options['idea_push_max_file_size'])){
        $max_size = intval($options['idea_push_max_file_size']);
    } else {
        $max_size = 5;    
    }

    //user profile error
    $html .= '<div style="display: none;" id="max-file-size" data="'.$max_size.'"></div>';
    
    

    return $html;
    
}

?>