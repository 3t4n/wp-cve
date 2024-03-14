<?php

    function idea_push_render_board($data,$boardId = '',$boardName = ''){

        //get options
        if(!get_option('idea_push_settings')){
            update_option('idea_push_settings', array());
            $options = array();
        } else {
            $options = get_option('idea_push_settings');
        }

        global $ideapush_is_pro;

        //start output
        $html = '';

        //check if data exists otherwise set the default options
        if(strlen($data)<1){

            $data = $boardId.'|'.$boardName.'|100|Yes|Yes|Yes|Yes|Yes|Yes|No|No|-1|-1|No|Everyone|-1|No|Most Votes|'.date('Y-m-d').'|'.date('H:i').'|Create as many ideas by [expiry] ([expiry-countdown]) and win.|The winner is [winner-name] with [win-number]|Global|Default|Board|No|No|No|Popular|Open|Yes|10';
        }

        //explode the options to get individual values
        $individual_options = explode('|',$data);
        $board_id = $individual_options[0];

        // var_dump($individual_options);


        //do dynamic settings
        //roles
        $editable_roles = get_editable_roles();
        
        $role_data = array('Everyone'=>'Everyone');
        
        foreach ($editable_roles as $role => $details) {
            $role_data[$role] = $details['name'];
        }

        //status filter
        $statuses = array('Open'=>'Open','Reviewed'=>'Reviewed','Approved'=>'Approved','Declined'=>'Declined','In Progress'=>'In Progress','Completed'=>'Completed','All Statuses'=>'All Statuses');
        

        $disable_status_setting_names = array(
            'idea_push_disable_approved_status'=>'Approved',
            'idea_push_disable_declined_status'=>'Declined',
            'idea_push_disable_in_progress_status'=>'In Progress',
            'idea_push_disable_completed_status'=>'Completed',
            'idea_push_disable_all_statuses_status'=>'All Statuses'
        );

        foreach($disable_status_setting_names as $key => $value){
            if(array_key_exists($key,$options)){
                unset($statuses[$value]);   
            }
        }


        //do field setting
        $field_settings = array('Default'=>'Default');
        $currentOption = $options['idea_push_form_settings'];
        if( isset($currentOption) ){

            //get the options and split it into chunks
            if(strpos($currentOption, '^^^^') !== false){
                $explodedOptions = explode('^^^^',$currentOption);
            } else {
                $explodedOptions = explode('||||',$currentOption);   
            }
    
            //if there are no items also set the default value to Default
            if( count($explodedOptions)>0 ){
                foreach($explodedOptions as $formSetting){
    
                    $explodedSubOptions = explode('|||',$formSetting);
        
                    $settingName = $explodedSubOptions[0];

                    if(strlen($settingName)>0){
                        $field_settings[$settingName] = $settingName;
                    }
                    
                }
            }
        } 



        //create array with options
        $board_settings = array(
            array(
                'name' => 'board-name',
                'label' => __('Board Name','ideapush'),
                'type' => 'text',
                'tooltip' => __('Is simply the name of your board and also acts as the title shown at the top of the idea board.','ideapush'),
                'pro-setting' => 'No',
            ),
            array(
                'name' => 'vote-threshold',
                'label' => __('Vote Threshold','ideapush'),
                'type' => 'number',
                'tooltip' => __('Is the amount of votes required for an idea to go from open to \'Reviewed\' status.','ideapush'),
                'min' => 0,
                'pro-setting' => 'No',
            ),
            array(
                'name' => 'hold-ideas',
                'label' => __('Hold Ideas','ideapush'),
                'type' => 'select',
                'tooltip' => __('By default when people create new ideas it creates a new idea with a \'Pending Review\' status. This enables you to check over the idea before it is published. If you turn this to \'No\' ideas will be published immidiately.','ideapush'),
                'options' => array(
                    'Yes' => 'Yes',
                    'No' => 'No',
                ),
                'pro-setting' => 'No',
            ),
            array(
                'name' => 'comments',
                'label' => __('Show Comments','ideapush'),
                'type' => 'select',
                'tooltip' => __('Whether people will be able to comment on ideas and show comments on the idea board listing.','ideapush'),
                'options' => array(
                    'Yes' => 'Yes',
                    'No' => 'No',
                ),
                'pro-setting' => 'No',
            ),
            array(
                'name' => 'tags',
                'label' => __('Show Tags','ideapush'),
                'type' => 'select',
                'tooltip' => __('Whether people will be able to add tags to an idea and whether tags will be visible on the idea board listing.','ideapush'),
                'options' => array(
                    'Yes' => 'Yes',
                    'No' => 'No',
                ),
                'pro-setting' => 'No',
            ),
            array(
                'name' => 'attachments',
                'label' => __('Show Attachments','ideapush'),
                'type' => 'select',
                'tooltip' => __('Whether to enable people to attach an image when creating a new idea from the idea form.','ideapush'),
                'options' => array(
                    'Yes' => 'Yes',
                    'No' => 'No',
                ),
                'pro-setting' => 'No',
            ),
            array(
                'name' => 'board-title',
                'label' => __('Show Board Title','ideapush'),
                'type' => 'select',
                'tooltip' => __('Whether to show the board title above the idea board listing.','ideapush'),
                'options' => array(
                    'Yes' => 'Yes',
                    'No' => 'No',
                ),
                'pro-setting' => 'No',
            ),
            array(
                'name' => 'vote-target',
                'label' => __('Show Vote Target','ideapush'),
                'type' => 'select',
                'tooltip' => __('Whether to show the target vote score for the board which is displayed just below the board title.','ideapush'),
                'options' => array(
                    'Yes' => 'Yes',
                    'No' => 'No',
                ),
                'pro-setting' => 'No',
            ),
            array(
                'name' => 'guest-access',
                'label' => __('Guest Votes/Ideas','ideapush'),
                'type' => 'select',
                'tooltip' => __('Whether to enable guests to be able to vote and also publish new ideas. For a guest to vote or create a new idea they must enter in a name and email address so it\'s not fully anonymous. Although guests from their perspective will appear to be guests, what actually is happening is a user account will be created for them in WordPress with the user role \'IdeaPush Guest\' which is assigned to the visitors IP Address. This does mean that using a proxy server is a method for someone to vote multiple times but this is not an easy process for most and it is a drawback to any guest system.','ideapush'),
                'options' => array(
                    'Yes' => 'Yes',
                    'No' => 'No',
                ),
                'pro-setting' => 'No',
            ),
            array(
                'name' => 'down-voting',
                'label' => __('Enable Down Voting','ideapush'),
                'type' => 'select',
                'tooltip' => __('Enables someone to vote positively or negatively for an idea.','ideapush'),
                'options' => array(
                    'Yes' => 'Yes',
                    'No' => 'No',
                ),
                'pro-setting' => 'No',
            ),
            array(
                'name' => 'max-votes',
                'label' => __('Max Votes','ideapush'),
                'type' => 'number',
                'tooltip' => __('These settings set the limit of the maximum amount of times a user can create an idea or cast a vote for a given period (please see the Max Votes Period setting lower down to set this period). Admin users are excluded from this limit and can create as many ideas or cast as many votes as they would like. A value of minus one or blank means unlimited, a value of zero essentially blocks further idea creation and voting and a positive value sets the upper limit. Any vote contributes to the users vote count, this includes negative votes and votes that are for the same idea or rescinded votes.','ideapush'),
                'min' => -1,
                'pro-setting' => 'No',
            ),
            array(
                'name' => 'max-ideas',
                'label' => __('Max Ideas Per Day','ideapush'),
                'type' => 'number',
                'tooltip' => __('These settings set the limit of the maximum amount of times a user can create an idea or cast a vote for a given period (please see the Max Ideas Period setting lower down to set this period). Admin users are excluded from this limit and can create as many ideas or cast as many votes as they would like. A value of minus one or blank means unlimited, a value of zero essentially blocks further idea creation and voting and a positive value sets the upper limit. Any vote contributes to the users vote count, this includes negative votes and votes that are for the same idea or rescinded votes.','ideapush'),
                'min' => -1,
                'pro-setting' => 'No',
            ),
            array(
                'name' => 'hide-from-search',
                'label' => __('Hide From Search','ideapush'),
                'type' => 'select',
                'tooltip' => __('Whether to hide ideas associated with this board from WordPress\'s search.','ideapush'),
                'options' => array(
                    'Yes' => 'Yes',
                    'No' => 'No',
                ),
                'pro-setting' => 'No',
            ),
            array(
                'name' => 'show-board-to',
                'label' => __('Show Board To','ideapush'),
                'type' => 'select',
                'tooltip' => __('Elect to show the board to only a certain WordPress user role. Please leave on the default \'Everyone\' option to show to everyone (logged in and logged out visitors).','ideapush'),
                'options' => $role_data,
                'pro-setting' => 'No',
            ),
            array(
                'name' => 'pagination',
                'label' => __('Pagination Number','ideapush'),
                'type' => 'number',
                'tooltip' => __('Choose how many ideas to show per a page. Please set to -1 or 0 if you want to show all ideas in an endless scroll.','ideapush'),
                'min' => -1,
                'max' => 200,
                'pro-setting' => 'No',
            ),
            array(
                'name' => 'enable-challenge',
                'label' => __('Enable Challenge','ideapush'),
                'type' => 'select',
                'tooltip' => __('By enabling challenges you can set a time and date which will be the end time/date of your challenge and at this time users won\'t be able to create any more ideas or cast votes. You can also set a victory condition of your challenge. Challenges can be used to incentivise good ideas, voting and idea creation and to work within project deadlines.','ideapush'),
                'options' => array(
                    'Yes' => 'Yes',
                    'No' => 'No',
                ),
                'dependencies' => array(
                    'challenge-victory',
                    'challenge-date',
                    'challenge-time',
                    'challenge-message',
                    'challenge-victory-message'
                ),
                'pro-setting' => 'Yes',
            ),
            array(
                'name' => 'challenge-victory',
                'label' => __('Challenge Victory','ideapush'),
                'type' => 'select',
                'tooltip' => __('Most Votes is the person who created ideas that received the most votes (it\'s not the person who voted the most), Most Ideas is the person who created the most ideas (if creating a large amount of ideas is your goal make sure your Max idea per day option is set to -1), Popular Idea is the person who created the idea with the most votes.','ideapush'),
                'options' => array(
                    'Most Votes' => 'Most Votes',
                    'Most Ideas' => 'Most Ideas',
                    'Popular Idea' => 'Popular Idea',
                ),
                'pro-setting' => 'Yes',
            ),
            array(
                'name' => 'challenge-date',
                'label' => __('Challenge Date','ideapush'),
                'type' => 'date',
                'tooltip' => __('The date the challenge expires.','ideapush'),
                'pro-setting' => 'Yes',
            ),
            array(
                'name' => 'challenge-time',
                'label' => __('Challenge Time','ideapush'),
                'type' => 'time',
                'tooltip' => __('The time of the day on the chosen date the challenge will expire.','ideapush'),
                'pro-setting' => 'Yes',
            ),
            array(
                'name' => 'challenge-message',
                'label' => __('Challenge Message','ideapush'),
                'type' => 'textarea',
                'tooltip' => __('A message that shows above the board describing the challenge. Use the shortcodes [expiry] and [expiry-countdown] to display the date and/or a countdown in your challenge description. This message is hidden once the challenge has expired.','ideapush'),
                'default' => 'undefined',
                'pro-setting' => 'Yes',
            ),
            array(
                'name' => 'challenge-victory-message',
                'label' => __('Challenge Vic. Message','ideapush'),
                'type' => 'textarea',
                'tooltip' => __('(Challenge Victory Message) A message that shows once the challenge has expired above the board. Use the shortcodes [winner-name] and [win-number] to display the victor name(s) and the number that they achieved (this could be the amount of aggregated votes, the amount of ideas created or the amount of votes for the winning idea).','ideapush'),
                'default' => 'undefined',
                'pro-setting' => 'Yes',
            ),

            array(
                'name' => 'tag-scope',
                'label' => __('Tag Scope','ideapush'),
                'type' => 'select',
                'tooltip' => __('If the tag scope is set to \'Global\' all tags created will be added to the global pool of tags and all suggested tags will come from this global pool of tags (this means that ideas from different boards can belong to the same tag). If the tag scope is set to \'board\' all tags created will be specific to the board which means suggested tags will also only come from the board. This can be handy if you want the same tag on different boards but they mean different things or having the tag archive page specific to the board topic is important. Please note if the tag scope is set to \'Board\' all tags created will have the prefix \'BoardTag-#-\' and this will show in the archive page of the tag.','ideapush'),
                'options' => array(
                    'Global' => 'Global',
                    'Board' => 'Board',
                ),
                'pro-setting' => 'Yes',
            ),
            array(
                'name' => 'field-settings',
                'label' => __('Field Setting','ideapush'),
                'type' => 'select',
                'tooltip' => __('In the <a class=\'open-tab\' href=\'#idea_form\'>Idea Form</a> tab you can create multiple forms which are used to create new ideas. You can select what form to use for your board.','ideapush'),
                'options' => $field_settings,
                'pro-setting' => 'No',
            ),
            array(
                'name' => 'idea-scope',
                'label' => __('Idea Scope','ideapush'),
                'type' => 'select',
                'tooltip' => __('If you have enabled idea suggestions in the <a class=\'open-tab\' href=\'#ideapush_pro\'>IdeaPush Pro</a> tab then this setting enables you to choose whether suggested ideas are only retrieved from the current board (Board), or whether they are retrieved from all boards (Global).','ideapush'),
                'options' => array(
                    'Global' => 'Global',
                    'Board' => 'Board',
                ),
                'pro-setting' => 'Yes',
            ),
            array(
                'name' => 'user-idea-edit-delete',
                'label' => __('User Idea Edit/Delete','ideapush'),
                'type' => 'select',
                'tooltip' => __('By setting this to Yes, new icons will be added beside each idea which will enable users to edit and delete their ideas. Of course this will only show for ideas that the user has created, it won\'t enable this functionality for other peoples ideas!','ideapush'),
                'options' => array(
                    'Yes' => 'Yes',
                    'No' => 'No',
                ),
                'pro-setting' => 'Yes',
            ),   
            array(
                'name' => 'hide-leaderboard',
                'label' => __('Hide Leaderboard','ideapush'),
                'type' => 'select',
                'tooltip' => __('Hides the leaderboard which displays statistics for the board like the most votes aggregated, top ideas and most ideas. This is displayed beneath the create idea form.','ideapush'),
                'options' => array(
                    'Yes' => 'Yes',
                    'No' => 'No',
                ),
                'pro-setting' => 'Yes',
            ),
            array(
                'name' => 'enable-multi-ips',
                'label' => __('Enable Multi IP\'s','ideapush'),
                'type' => 'select',
                'tooltip' => __('By default IdeaPush prevents people creating multiple user accounts with the same IP address to prevent vote rigging. However this may not work for you especially if you have a work network with a shared IP address where employees vote for example. So in this case please set this setting to yes.','ideapush'),
                'options' => array(
                    'Yes' => 'Yes',
                    'No' => 'No',
                ),
                'pro-setting' => 'Yes',
            ),

            array(
                'name' => 'default-showing',
                'label' => __('Default Showing Filter','ideapush'),
                'type' => 'select',
                'tooltip' => __('By default your board will have the first filter set to \'Popular\' so ideas are shown with the highest votes to lowest votes. You can change the default so a different filter is used.','ideapush'),
                'options' => array(
                    'Popular' => 'Popular',
                    'Recent' => 'Recent',
                    'Trending' => 'Trending',
                    'My' => 'My',
                    'My Voted' => 'My Voted',
                ),
                'pro-setting' => 'No',
            ),
            array(
                'name' => 'default-status',
                'label' => __('Default Status Filter','ideapush'),
                'type' => 'select',
                'tooltip' => __('By default your will board will have the second filter set to \'Open\' so only open ideas are shown. You can change the default so a different filter is used.','ideapush'),
                'options' => $statuses,
                'pro-setting' => 'No',
            ),
            array(
                'name' => 'enable-vote-bank',
                'label' => __('Enable Vote Bank','ideapush'),
                'type' => 'select',
                'tooltip' => __('With this feature enabled users will be allocated a certain number of votes (to define amount of votes please go to IdeaPush Pro tab and the setting \'Amount of User Votes\'). Once the user hits this number of votes they can no longer vote anymore until the ideas they voted on reach the threshold score, they retract their vote or the idea gets deleted. For example if a user votes for idea X and idea X reaches the vote threshold score they will be refunded 1 vote in their bank. Note, admins can manually change the votes of a user in the users profile edit page.','ideapush'),
                'options' => array(
                    'No' => 'No',
                    'Yes' => 'Yes',
                ),
                // 'dependencies' => array(
                //     'vote-bank-user-vote-limit',
                // ),
                'pro-setting' => 'Yes',
            ),
            array(
                'name' => 'hide-comments-widget',
                'label' => __('Hide Comments Widget','ideapush'),
                'type' => 'select',
                'tooltip' => __('Hides the comments widget which displays under the leaderboard. This shows recent comments made for ideas on the board.','ideapush'),
                'options' => array(
                    'Yes' => 'Yes',
                    'No' => 'No',
                ),
                'pro-setting' => 'Yes',
            ),
            array(
                'name' => 'max-votes-period',
                'label' => __('Max Votes Period','ideapush'),
                'type' => 'number',
                'tooltip' => __('Elect how many days this max amount of votes is for.','ideapush'),
                'min' => 1,
                'pro-setting' => 'No',
            ),
            array(
                'name' => 'max-ideas-period',
                'label' => __('Max Ideas Period','ideapush'),
                'type' => 'number',
                'tooltip' => __('Elect how many days this max amount of ideas is for.','ideapush'),
                'min' => 1,
                'pro-setting' => 'No',
            ),
            // array(
            //     'name' => 'vote-bank-user-vote-limit',
            //     'label' => __('Amount of User Votes','ideapush'),
            //     'type' => 'number',
            //     'tooltip' => __('Amount of votes a user initially has in their bank.','ideapush'),
            //     'min' => 1,
            //     'max' => 999,
            //     'pro-setting' => 'Yes',
            // ),
            
        );
        

        //start list item
        $html .= '<li data="'.$board_id.'">';

            //do table
            $html .= '<table>';
                $html .= '<tbody>';

                    $setting_counter = 0;

                    //loop through the options
                    foreach($board_settings as $board_setting){

                        //add 1 to the settings
                        $setting_counter++;

                        //do pro check
                        if($ideapush_is_pro != 'YES' && $board_setting['pro-setting'] == 'Yes'){
                            $style = 'style="display:none;"';
                        } else {
                            $style = '';
                        }


                        $html .= '<tr data-option-index="'.$setting_counter.'" '.$style.' class="'.$board_setting['name'].' '.$board_id.'-'.$board_setting['name'].'">';   
                            //label
                            $html .= '<td>';
                                $html .= '<label data-tippy-content="'.$board_setting['tooltip'].'">'.$board_setting['label'].'</label>';
                            $html .= '</td>';
                            //do setting
                            $html .= '<td>';

                            //do setting types
                            //text input
                            if($board_setting['type'] == 'text'){
                                $html .= '<input type="text" class="ideapush-board-setting-field '.$board_setting['name'].'-input" style="width: 150px;" value="'.$individual_options[$setting_counter].'">';
                            }

                            //date input
                            if($board_setting['type'] == 'date'){

                                $html .= '<input type="date" class="ideapush-board-setting-field '.$board_setting['name'].'-input" style="width: 150px;" value="'.$individual_options[$setting_counter].'">';
                            }

                            //time input
                            if($board_setting['type'] == 'time'){
                                $html .= '<input type="time" class="ideapush-board-setting-field '.$board_setting['name'].'-input" style="width: 150px;" value="'.$individual_options[$setting_counter].'">';
                            }

                            //textarea input
                            if($board_setting['type'] == 'textarea'){
                                $html .= '<textarea class="ideapush-board-setting-field '.$board_setting['name'].'-input" style="width: 150px;">'.$individual_options[$setting_counter].'</textarea>';
                            }

                            //number input
                            if($board_setting['type'] == 'number'){

                                if(array_key_exists('max',$board_setting)){
                                    $max = $board_setting['max'];
                                } else {
                                    $max = 9999;   
                                }

                                $html .= '<input type="number" min="'.$board_setting['min'].'"  max="'.$max.'"class="ideapush-board-setting-field '.$board_setting['name'].'-input" style="width: 150px;" value="'.$individual_options[$setting_counter].'">';
                            }

                            //select input
                            if($board_setting['type'] == 'select'){

                                if(array_key_exists('dependencies',$board_setting)){

                                    //add board id to start of dependcy
                                    $with_board_id = preg_filter('/^/', $board_id.'-', $board_setting['dependencies']);

                                    //turn array into commas list
                                    $imploded_dependencies = implode(',',$with_board_id);

                                    $data = 'data-dependencies="'.$imploded_dependencies.'"';

                                } else {
                                    $data = '';
                                }
                                
                                

                                $html .= '<select '.$data.' class="ideapush-board-setting-field '.$board_setting['name'].'-select" style="width:150px;">';

                                    //loop through the options
                                    foreach($board_setting['options'] as $key => $value){

                                        if($key == $individual_options[$setting_counter]){
                                            $html .= '<option value="'.$key.'" selected="selected">'.$value.'</option>';  
                                        } else {
                                            $html .= '<option value="'.$key.'">'.$value.'</option>';   
                                        }
                                    }
                                
                                $html .= '</select>';

                            }

                            $html .= '</td>';

                        $html .= '</tr>';   
                    }


                    //do action buttons

                    $html .= '<tr class="board-action-buttons">';
                        $html .= '<td colspan="2">';

                            //copy shortcode
                            $html .= '<button style="margin-right:6px;" class="copy-board-shortcode button-secondary" data-clipboard-text="[ideapush board=&quot;'.$board_id.'&quot;]" data="'.$board_id.'">'.__('Copy Shortcode','ideapush').'</button>';

                            //delete board
                            $html .= '<button class="delete-board button-secondary-red" data="'.$board_id.'">'.__('Delete Board','ideapush').'</button>';

                        $html .= '</td>';
                    $html .= '</tr>';

                $html .= '</tbody>';
            $html .= '</table>';

        $html .= '</li>';




        return $html;
    }
?>