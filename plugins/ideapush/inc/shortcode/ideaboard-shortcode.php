<?php
// shortcode
function idea_push_board_shortcode($atts) {

    //enqueue scripts and styles
    wp_enqueue_style(array('custom-frontend-style-ideapush','ideapush-font'));
    wp_enqueue_script(array('alertify','custom-frontend-script-ideapush','scroll-reveal','read-more','custom-frontend-script-ideapush-pro'));

    //get options
    $options = get_option('idea_push_settings');
    
    //get is pro
    global $ideapush_is_pro;

    //set a default attribute for board
    $a = shortcode_atts(array('board' => ''),$atts);
    
    
    //only do something if the board property is populated
    if(strlen($a['board'])>0){
        
        
        $individualBoardSetting = idea_push_get_board_settings($a['board']);
        
        //check if is array, that is, whether a setting was found
        if(is_array($individualBoardSetting)){
            
            
            //lets create standard variables for the settings
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
            $showBoardTo = $individualBoardSetting[14];
            $formSettings = $individualBoardSetting[23];
            $defaultShowingSetting = $individualBoardSetting[28];
            $defaultStatusSetting = $individualBoardSetting[29];
            
            $multiIp = 'No';

            //only get the following settings if pro
            if($ideapush_is_pro == 'YES'){
                $enableChallenge = $individualBoardSetting[16];
                $challengeVictory = $individualBoardSetting[17];
                $challengeDate = $individualBoardSetting[18];
                $challengeTime = $individualBoardSetting[19];
                $challengeMessage = $individualBoardSetting[20];
                $challengeVictoryMessage = $individualBoardSetting[21];  
                $hideLeaderboard = $individualBoardSetting[26];
                $multiIp = $individualBoardSetting[27]; 
                $enableVoteBank = $individualBoardSetting[30];    
                $hideCommentsWidget = $individualBoardSetting[31];
            }
            

            //set the defaults for the filters
            //first we will get the query string, if this is not set we will get the option, if this doesn't exist we will set a default value

            if(isset($_GET['showing'])){
                $defaultShowing = sanitize_text_field($_GET['showing']);
            } elseif(isset($defaultShowingSetting) && strlen($defaultShowingSetting)>0){

                $defaultShowingSetting = strtolower($defaultShowingSetting);
                $defaultShowingSetting = str_replace(" ", "-", $defaultShowingSetting);
                $defaultShowing = $defaultShowingSetting; 

            } else {
                $defaultShowing = 'popular';    
            }


            if(isset($_GET['status'])){
                $defaultStatus = sanitize_text_field($_GET['status']);
            } elseif(isset($defaultStatusSetting) && strlen($defaultStatusSetting)>0){

                $defaultStatusSetting = strtolower($defaultStatusSetting);
                $defaultStatusSetting = str_replace(" ", "-", $defaultStatusSetting);
                $defaultStatus = $defaultStatusSetting; 

            } else {
                $defaultStatus = 'open';    
            }


            if(isset($_GET['tag'])){
                $defaultTag = sanitize_text_field($_GET['tag']);

                //now lets get the tag id from the name
                //first we are going to check with a board prefix and if this fails get it without

                if($defaultTag == 'All'){
                    $defaultTag = 'all'; 
                } else {

                    if(get_term_by( 'name', $defaultTag, 'tags') == false){
                        $defaultTag = get_term_by( 'name', 'BoardTag-'.$a['board'].'-'.$defaultTag, 'tags');
                    } else {
                        $defaultTag = get_term_by( 'name', $defaultTag, 'tags');
                    }
            
                    $defaultTag = $defaultTag->term_id;

                }

            } else {
                $defaultTag = 'all';    
            }




            //do default custom fields - nothing to do for not
            if(isset($_GET['custom'])){
                $defaultCustomFields = sanitize_text_field($_GET['custom']);
            } else {
                $defaultCustomFields = '';
            }
            

            
            //lets call this function once and use throughout
            //this function will reutn the user id or false
            
            

            $currentUserId = idea_push_check_if_non_logged_in_user_is_guest($multiIp);
            
            //echo 'The user id is: '.$currentUserId.'</br>'; 
            
            //checking whether the user exists or not
            if($currentUserId !== false){
                $currentUserObject = get_user_by( 'id', $currentUserId);
                $currentUserRole = apply_filters('idea_push_extend_role_permissions', $currentUserObject->roles);

                //the error is saying the currentuserrole is not an array and it's null
                if(in_array($showBoardTo, $currentUserRole)){
                    $userRoleCheck = true;
                } else {
                    $userRoleCheck = false;   
                }
                
            } else {
                $userRoleCheck = false;     
            }
            

            
            
            //only show the board if the person is allowed to see it
            if($userRoleCheck || $showBoardTo == "Everyone"){
 
                //lets create our holding variable
                $html = '';

                $html .= idea_push_output_styles();

                //we need to output whether we are rendering HTML - this is used by the search and replace functionality
                if( isset($options['idea_push_render_html']) ){
                    $render_html_search_help = 'true';
                } else {
                    $render_html_search_help = 'false';  
                }

                $html .= '<div class="ideapush-container" data="'.esc_attr($boardId).'" data-render-html="'.$render_html_search_help.'">';

                    //header
                    $html .= '<div class="ideapush-container-header">';

                        //board title
                        //only show board title if it is nominated in the settings
                        if($showBoardTitle == 'Yes'){
                            $html .= '<h2 class="ideapush-board-title">'.esc_html($boardName).'</h2>';    
                        }

                        //board target
                        //only show board title if it is nominated in the settings
                        if($showVoteTarget == 'Yes'){                            
                            $html .= '<div class="ideapush-vote-target">'.sprintf(__('Get your idea to %d to be reviewed','ideapush'),$voteThreshold).'</div>'; 
                        }

                

                    $html .= '</div>';


                    if($guestAccess == "Yes" || $currentUserId !== false){
                        $additionalContainerClass = '';   
                    } else {
                        $additionalContainerClass = ' ideapush-container-ideas-full-width';       
                    }

                    //ideas
                    $html .= '<div class="ideapush-container-ideas'.esc_attr($additionalContainerClass).'">';

                        $html .= '<div class="ideapush-container-idea-header">'; //put border below me
                
                            //show challenge box    
                            if($ideapush_is_pro == 'YES' && isset($enableChallenge) && $enableChallenge == 'Yes' && strtotime(current_time('mysql')) <= strtotime($challengeDate.' '.$challengeTime.':00') ){
                                $html .= idea_push_challenge_message($individualBoardSetting);  
                            }

                            //show challenge vicotry box    
                            if($ideapush_is_pro == 'YES' && isset($enableChallenge) && $enableChallenge == 'Yes' && strtotime(current_time('mysql')) > strtotime($challengeDate.' '.$challengeTime.':00') ){
                                $html .= idea_push_challenge_victory_message($individualBoardSetting);  
                            }

                            //show vote bank box   
                            if($ideapush_is_pro == 'YES' && isset($enableVoteBank) && $enableVoteBank == 'Yes' && $currentUserId !== false){
                                $html .= idea_push_vote_bank_message($currentUserId);  
                            }
                            

                            //fetch header
                            $html .= idea_push_header_render_output($a['board'],$defaultShowing,$defaultStatus,$defaultTag,$defaultCustomFields);


                        $html .= '</div>'; //end header


                        //dynamic data
                        $html .= '<ul class="dynamic-idea-listing" data="'.esc_attr($boardId).'">';

                            $html .= idea_push_list_items($boardId,$defaultShowing,$defaultStatus,$defaultTag,$defaultCustomFields);

                        $html .= '</ul>';

                    $html .= '</div>';



                    //don't show the whole form section if guest voting new ideas isn't enabled
                    if($guestAccess == "Yes" || $currentUserId !== false){
                        

                        //only show the whole form section if not pro or challenge enabled and challenge expiry hasn't been reached
                        if(($ideapush_is_pro == 'NO') || (!isset($enableChallenge)) || ($ideapush_is_pro == 'YES' && $enableChallenge == 'No') || ($ideapush_is_pro == 'YES' && $enableChallenge == 'Yes' && strtotime(current_time('mysql')) < strtotime($challengeDate.' '.$challengeTime.':00'))      ){
                            
                            $currentOption = $options['idea_push_form_settings'];

                            $explodedOptions = explode('^^^^',$currentOption);

                            //gets the submit idea button text from the specific form settings
                            foreach($explodedOptions as $formSetting){

                                $explodedSubOptions = explode('|||',$formSetting);

                                $settingName = $explodedSubOptions[0];
                                
                                if($settingName == $formSettings){

                                    if (is_array($explodedSubOptions) && count($explodedSubOptions)>1){
                                        $standardFormLabels = $explodedSubOptions[1];
                                    } else {
                                        $standardFormLabels = "|| || || || || || ";
                                    }

                                    $standardFormLabelsExploded = explode('||',$standardFormLabels);

                                }
                            }  

                            

                            if(isset($standardFormLabelsExploded[6]) && strlen($standardFormLabelsExploded[6])>1){
                                $ideaButtonText = esc_html($standardFormLabelsExploded[6]);    
                            } else {
                                $ideaButtonText = __( 'Submit new idea', 'ideapush' ); 
                            }

                            $html .= '<button class="create-idea-form-reveal">'.$ideaButtonText.'</button>';

                            //if single idea disabled create data attribute
                            //this is a necessary indicator to pro users who want suggested ideas but want to disable links to single idea page

                            if(isset($options['idea_push_disable_single_idea_page'])){
                                $singleIdeaDisabled = 'true';
                            } else {
                                $singleIdeaDisabled = 'false';    
                            }

                            //form
                            $html .= '<div class="ideapush-container-form" data-single-idea-disabled="'.$singleIdeaDisabled.'">';

                                $html .= idea_push_form_render_output($a['board']);    

                                //if pro do leaderboard
                                //we may need to do a condition to show this conditionally based on an option
                                if($ideapush_is_pro == 'YES'){
                                    if($hideLeaderboard !== 'Yes'){
                                        $html .= idea_push_leader_board_render_output($a['board']); 
                                    }


                                    if($hideCommentsWidget !== 'Yes'){
                                        $html .= idea_push_comments_widget_render_output($a['board']); 
                                    }
                                }

                            $html .= '</div>'; //end container    

                            

                             
                        }
                            
                    } //end condition if guest 

                $html .= '</div>'; //end whole idea page section



                //output dialogs

                //vote limit dialog
                $html .= '<div style="display: none;" id="dialog-no-vote" data="'.__( 'You have reached your daily vote limit or voting has been disabled on this board.', 'ideapush' ).'"></div>';

                //idea limit dialog
                $html .= '<div style="display: none;" id="dialog-no-idea" data="'.__( 'You have reached your daily idea creation limit or idea creation has been disabled on this board.', 'ideapush' ).'"></div>';

                //user delete idea dialog
                $html .= '<div style="display: none;" id="dialog-idea-delete" data="'.__( 'Are you sure you want to delete this idea?', 'ideapush' ).'"></div>';

                //user idea deleted dialog
                $html .= '<div style="display: none;" id="dialog-idea-deleted" data="'.__( 'The idea has been deleted.', 'ideapush' ).'"></div>';

                //edit idea popup heading
                $html .= '<div style="display: none;" id="dialog-edit-idea-heading" data="'.__( 'Edit Idea', 'ideapush' ).'"></div>';

                //edit idea popup heading
                $html .= '<div style="display: none;" id="dialog-idea-edited" data="'.__( 'The idea has been updated.', 'ideapush' ).'"></div>';

                //duplicate idea
                $html .= '<div style="display: none;" id="dialog-idea-duplicate" data="'.__( 'Please search for and select the original idea. Once you click submit the duplicate idea will be given a duplicate status and no more votes will be allowed for this idea and votes given to this idea will be transferred to the original idea where appropriate. The author of this duplicate idea will be notified of this status change if notifications are enabled in the plugin settings.', 'ideapush' ).'"></div>';

                //duplicate idea success
                $html .= '<div style="display: none;" id="dialog-idea-duplicate-success" data="'.__( 'Idea successfully marked as duplicate', 'ideapush' ).'"></div>';

                //login form
                $html .= '<div style="display: none;" id="create-user-form" data-first="'.__( 'First name', 'ideapush' ).'" data-last="'.__( 'Last name', 'ideapush' ).'" data-email="'.__( 'Email', 'ideapush' ).'" data-email-confirm="'.__( 'Confirm email', 'ideapush' ).'" data-password="'.__( 'Password', 'ideapush' ).'"></div>';

                //duplicate idea
                $html .= '<div style="display: none;" id="duplicate-idea-placeholder" data="'.__( 'Enter original idea title here', 'ideapush' ).'"></div>';

                //buttons
                $html .= '<div style="display: none;" id="ok-cancel-buttons" data-submit="'.__( 'Submit', 'ideapush' ).'" data-cancel="'.__( 'Cancel', 'ideapush' ).'" data-yes="'.__( 'Yes', 'ideapush' ).'" data-no="'.__( 'No', 'ideapush' ).'"></div>';


                return $html;
            
            } else {
            //the person doesn't have permission to view the board
            
                return __( 'Sorry you do not have permission to view this board.', 'ideapush' );
            }   
            
            
        } else {
            //the board doesn't exist in the settings
            return __( 'The board number does not exist. Please create a board by going to: ', 'ideapush' ).'<a target="_blank" href="'.esc_url(get_admin_url(null,'/edit.php?post_type=idea&page=idea_push_settings')).'">'.__( 'IdeaPush > Settings > Boards', 'ideapush' ).'</a>.';  
        }

        
    } else {
        //there's no board parameter
        return __('Please ensure your shortcode parameter has a board number like: <code>[ideapush board="1"]</code>', 'ideapush' );    
    }
    
    
 
}
add_shortcode('ideapush', 'idea_push_board_shortcode');



?>