<?php
if(!function_exists('it_epoll_advance_core_voting_add_fields')){
    add_action('it_epoll_options_advanced_fields','it_epoll_advance_core_voting_add_fields');
        function it_epoll_advance_core_voting_add_fields(){?>
            <table class="widefat  no-border-table">
                <tbody>
                <tr>
                    <td>  
                        <label>
                            <input type="checkbox" name="it_epoll_settings_cookies_blocking" value="1"<?php if(get_option('it_epoll_settings_cookies_blocking')) echo esc_attr(' checked','it_epoll');?>/> <?php echo esc_attr('Enable Cookies Based Voting','it_epoll');?>
                        </label> 
                    </td>
                </tr> 
            </tbody>
        </table>
        <?php }
}



if(!function_exists('it_epoll_default_translation_fields')){
    
    add_action('it_epoll_options_translate_fields','it_epoll_default_translation_fields');
    function it_epoll_default_translation_fields(){
        $it_epoll_settings_vote_number_text = get_option('it_epoll_settings_vote_number_text');
        $it_epoll_settings_vote_numbers_text = get_option('it_epoll_settings_vote_numbers_text');
        $it_epoll_settings_vote_button_text  = get_option('it_epoll_settings_vote_button_text');
        $it_epoll_settings_result_button_text = get_option('it_epoll_settings_result_button_text');
        $it_epoll_settings_result_back_button_text  = get_option('it_epoll_settings_result_back_button_text');
        $it_epoll_settings_time_left_text  = get_option('it_epoll_settings_time_left_text');
        $it_epoll_settings_live_badge_text  = get_option('it_epoll_settings_live_badge_text');
        $it_epoll_settings_end_badge_text  = get_option('it_epoll_settings_end_badge_text');
        $it_epoll_settings_upcoming_badge_text  = get_option('it_epoll_settings_upcoming_badge_text');
        $it_epoll_settings_share_badge_text  = get_option('it_epoll_settings_share_badge_text');
        $it_epoll_settings_share_on_menu_text  = get_option('it_epoll_settings_share_on_menu_text');
        $it_epoll_settings_already_voted_text  = get_option('it_epoll_settings_already_voted_text');
        $it_epoll_settings_voted_text   = get_option('it_epoll_settings_voted_text');
        $it_epoll_settings_participated_text  = get_option('it_epoll_settings_participated_text');
        $it_epoll_settings_verify_access_code_btn_text  = get_option('it_epoll_settings_verify_access_code_btn_text');
        $it_epoll_settings_verify_access_code_text_hint  = get_option('it_epoll_settings_verify_access_code_text_hint');
        $it_epoll_settings_verify_access_code_text_title  = get_option('it_epoll_settings_verify_access_code_text_title');
        $it_epoll_settings_share_on_social_media   = get_option('it_epoll_settings_share_on_social_media');
        ?>
        <table class="widefat white-border-table">
                            <tbody>
                                <tr>
                                    <td colspan="2" class="it_epoll_admin_table_bold_th"><?php echo esc_attr('Vote Text','it_epoll');?></th>
                                </tr>
                                <tr>
                                    <td>   
                                        <label><?php /* translators: %s: voting numbers text*/
                                        echo esc_attr('%s Vote','it_epoll');?></label>   
                                    </td>
                                    <td>      
                                        <input type="text" class="widefat" name="it_epoll_settings_vote_number_text" value="<?php echo esc_attr($it_epoll_settings_vote_number_text,'it_epoll');?>"/>
                                    </td>
                                </tr>
                                <tr>
                                    <td>   
                                        <label><?php /* translators: %s: voting number text*/
                                        echo esc_attr('%s Votes','it_epoll');?></label>       
                                        </td>
                                    <td>      
                                        <input type="text" class="widefat" name="it_epoll_settings_vote_numbers_text" value="<?php echo esc_attr($it_epoll_settings_vote_numbers_text,'it_epoll');?>"/>
                                    </td>
                                </tr> 
                                <tr>
                                    <td colspan="2" class="it_epoll_admin_table_bold_th"><?php echo esc_attr('Button Text','it_epoll');?></th>
                                </tr>
                                <tr>
                                    <td>   
                                        <label><?php echo esc_attr('Result','it_epoll');?></label>       
                                        </td>
                                    <td>      
                                        <input type="text" class="widefat" name="it_epoll_settings_result_button_text" value="<?php echo esc_attr($it_epoll_settings_result_button_text,'it_epoll');?>"/>
                                    </td>
                                </tr> 
                                <tr>
                                    <td>   
                                        <label><?php echo esc_attr('Vote','it_epoll');?></label>       
                                        </td>
                                    <td>      
                                        <input type="text" class="widefat" name="it_epoll_settings_vote_button_text" value="<?php echo esc_attr($it_epoll_settings_vote_button_text,'it_epoll');?>"/>
                                    </td>
                                </tr> 
                                <tr>
                                    <td>   
                                        <label><?php echo esc_attr('Back to Vote','it_epoll');?></label>       
                                        </td>
                                    <td>      
                                        <input type="text" class="widefat" name="it_epoll_settings_result_back_button_text" value="<?php echo esc_attr($it_epoll_settings_result_back_button_text,'it_epoll');?>"/>
                                    </td>
                                </tr> 
                                
                                <tr>
                                    <td colspan="2" class="it_epoll_admin_table_bold_th"><?php echo esc_attr('Extra Text','it_epoll');?></th>
                                </tr>
                                
                                <tr>
                                    <td>   
                                        <label><?php
                                        /* translators: %s: time left*/
                                        echo esc_attr('%s Left','it_epoll');?></label>       
                                        </td>
                                    <td>      
                                        <input type="text" class="widefat" name="it_epoll_settings_time_left_text" value="<?php echo esc_attr($it_epoll_settings_time_left_text,'it_epoll');?>"/>
                                    </td>
                                </tr> 
                                <tr>
                                    <td>   
                                        <label><?php echo esc_attr('Live','it_epoll');?></label>       
                                        </td>
                                    <td>      
                                        <input type="text" class="widefat" name="it_epoll_settings_live_badge_text" value="<?php echo esc_attr($it_epoll_settings_live_badge_text,'it_epoll');?>"/>
                                    </td>
                                </tr>
                                <tr>
                                    <td>   
                                        <label><?php echo esc_attr('End','it_epoll');?></label>       
                                        </td>
                                    <td>      
                                        <input type="text" class="widefat" name="it_epoll_settings_end_badge_text" value="<?php echo esc_attr($it_epoll_settings_end_badge_text,'it_epoll');?>"/>
                                    </td>
                                </tr> 
                                <tr>
                                    <td>   
                                        <label><?php echo esc_attr('Upcoming','it_epoll');?></label>       
                                        </td>
                                    <td>      
                                        <input type="text" class="widefat" name="it_epoll_settings_upcoming_badge_text" value="<?php echo esc_attr($it_epoll_settings_upcoming_badge_text,'it_epoll');?>"/>
                                    </td>
                                </tr> 
                                
                                <tr>
                                    <td>   
                                        <label><?php echo esc_attr('Share','it_epoll');?></label>       
                                        </td>
                                    <td>      
                                        <input type="text" class="widefat" name="it_epoll_settings_share_badge_text" value="<?php echo esc_attr($it_epoll_settings_share_badge_text,'it_epoll');?>"/>
                                    </td>
                                </tr> 
                                <tr>
                                    <td>   
                                        <label><?php echo esc_attr('Share on','it_epoll');?></label>       
                                        </td>
                                    <td>      
                                        <input type="text" class="widefat" name="it_epoll_settings_share_on_menu_text" value="<?php echo esc_attr($it_epoll_settings_share_on_menu_text,'it_epoll');?>"/>
                                    </td>
                                </tr> 
                                <tr>
                                    <td>   
                                        <label><?php echo esc_attr('Already Voted','it_epoll');?></label>       
                                        </td>
                                    <td>      
                                        <input type="text" class="widefat" name="it_epoll_settings_already_voted_text" value="<?php echo esc_attr($it_epoll_settings_already_voted_text,'it_epoll');?>"/>
                                    </td>
                                </tr> 
                                <tr>
                                    <td>   
                                        <label><?php echo esc_attr('Voted','it_epoll');?></label>       
                                        </td>
                                    <td>      
                                        <input type="text" class="widefat" name="it_epoll_settings_voted_text" value="<?php echo esc_attr($it_epoll_settings_voted_text,'it_epoll');?>"/>
                                    </td>
                                </tr> 
                                <tr>
                                    <td>   
                                        <label><?php echo esc_attr('You Already Participated!','it_epoll');?></label>       
                                        </td>
                                    <td>      
                                        <input type="text" class="widefat" name="it_epoll_settings_participated_text" value="<?php echo esc_attr($it_epoll_settings_participated_text,'it_epoll');?>"/>
                                    </td>
                                </tr> 
                                <tr>
                                    <td>   
                                        <label><?php echo esc_attr('Verify','it_epoll');?></label>       
                                        </td>
                                    <td>      
                                        <input type="text" class="widefat" name="it_epoll_settings_verify_access_code_btn_text" value="<?php echo esc_attr($it_epoll_settings_verify_access_code_btn_text,'it_epoll');?>"/>
                                    </td>
                                </tr> 
                                <tr>
                                    <td>   
                                        <label><?php echo esc_attr('Your Access Code','it_epoll');?></label>       
                                        </td>
                                    <td>      
                                        <input type="text" class="widefat" name="it_epoll_settings_verify_access_code_text_hint" value="<?php echo esc_attr($it_epoll_settings_verify_access_code_text_hint,'it_epoll');?>"/>
                                    </td>
                                </tr> 
                                <tr>
                                    <td>   
                                        <label><?php echo esc_attr('Enter Your Voting Access Pin','it_epoll');?></label>       
                                        </td>
                                    <td>      
                                        <input type="text" class="widefat" name="it_epoll_settings_verify_access_code_text_title" value="<?php echo esc_attr($it_epoll_settings_verify_access_code_text_title,'it_epoll');?>"/>
                                    </td>
                                </tr>
                                <tr>
                                    <td>   
                                        <label><?php echo esc_attr('Share on Social Media','it_epoll');?></label>       
                                        </td>
                                    <td>      
                                        <input type="text" class="widefat" name="it_epoll_settings_share_on_social_media" value="<?php echo esc_attr($it_epoll_settings_share_on_social_media,'it_epoll');?>"/>
                                    </td>
                                </tr>
                                
                            </tbody>
                    </table>
    <?php }
 }
 
if(!function_exists('it_epoll_core_voting_add_fields_save')){
    add_action('it_epoll_options_save_extra_settings','it_epoll_core_voting_add_fields_save');
    
    function it_epoll_core_voting_add_fields_save(){

        $string_args = array(
            'type' => 'string', 
            'sanitize_callback' => 'sanitize_text_field',
            'default' => NULL,
            );
        
        $int_args = array(
            'type' => 'integer', 
            'sanitize_callback' => 'sanitize_text_field',
            'default' => NULL,
            );
          register_setting( 'it_epoll_opt_settings','it_epoll_settings_cookies_blocking', $int_args);
          //Localization Text Options
          register_setting( 'it_epoll_opt_settings', 'it_epoll_settings_vote_number_text', $string_args);
          register_setting( 'it_epoll_opt_settings', 'it_epoll_settings_vote_numbers_text', $string_args);
          register_setting( 'it_epoll_opt_settings', 'it_epoll_settings_result_button_text', $string_args);
          register_setting( 'it_epoll_opt_settings', 'it_epoll_settings_result_back_button_text', $string_args);
          
          register_setting( 'it_epoll_opt_settings', 'it_epoll_settings_vote_button_text', $string_args);
          register_setting( 'it_epoll_opt_settings', 'it_epoll_settings_time_left_text', $string_args);
          register_setting( 'it_epoll_opt_settings', 'it_epoll_settings_live_badge_text', $string_args);
          register_setting( 'it_epoll_opt_settings', 'it_epoll_settings_upcoming_badge_text', $string_args);
          register_setting( 'it_epoll_opt_settings', 'it_epoll_settings_end_badge_text', $string_args);
          register_setting( 'it_epoll_opt_settings', 'it_epoll_settings_share_badge_text', $string_args);
          register_setting( 'it_epoll_opt_settings', 'it_epoll_settings_share_on_menu_text', $string_args);
          
          register_setting( 'it_epoll_opt_settings', 'it_epoll_settings_already_voted_text', $string_args);
          register_setting( 'it_epoll_opt_settings', 'it_epoll_settings_share_on_social_media', $string_args);
          register_setting( 'it_epoll_opt_settings', 'it_epoll_settings_voted_text', $string_args);
          register_setting( 'it_epoll_opt_settings', 'it_epoll_settings_participated_text', $string_args);
          register_setting( 'it_epoll_opt_settings', 'it_epoll_settings_verify_access_code_btn_text', $string_args);
          register_setting( 'it_epoll_opt_settings', 'it_epoll_settings_verify_access_code_text_hint', $string_args);
          register_setting( 'it_epoll_opt_settings', 'it_epoll_settings_verify_access_code_text_title', $string_args);
    }
}



if(!function_exists('it_epoll_poll_get_ttext')){
    function it_epoll_poll_get_ttext($field_name){

global $it_epoll_translation_default_fields;
$it_epoll_translation_default_fields = array(
    "it_epoll_settings_vote_number_text"=>"%s Vote",
    "it_epoll_settings_vote_numbers_text"=>"%s Votes",
    "it_epoll_settings_result_button_text"=>"Result",
    "it_epoll_settings_vote_button_text"=>"Vote",
    "it_epoll_settings_result_back_button_text"=>"Back To Vote",
    "it_epoll_settings_time_left_text"=>"%s Left",
    "it_epoll_settings_live_badge_text"=>"Live",						
    "it_epoll_settings_end_badge_text"=>"End",
    "it_epoll_settings_upcoming_badge_text"=>"Upcoming",
    "it_epoll_settings_share_badge_text"=>"Share",
    "it_epoll_settings_share_on_social_media"=>"Share on Social Media",
    "it_epoll_settings_share_on_menu_text"=>"Share on",
    "it_epoll_settings_already_voted_text"=>"Already Voted",
    "it_epoll_settings_voted_text"=>"Voted",
    "it_epoll_settings_participated_text"=>"You Already Participated!",	
    "it_epoll_settings_verify_access_code_btn_text"=>"Verify",
    "it_epoll_settings_verify_access_code_text_hint"=>"Your Access Code",
    "it_epoll_settings_verify_access_code_text_title" =>"Enter Your Voting Access Pin");
        do_action('it_epoll_poll_get_ttext_add_default_vals');
        $field_value = apply_filters('it_epoll_poll_get_ttext_additional',$field_name);
       return $field_value;
    }
}

if(!function_exists('it_epoll_get_ttext_default_fields')){
   function it_epoll_get_ttext_default_fields($field_name){
       
        global $it_epoll_translation_default_fields;
       
        $field_value = get_option($field_name);
        if(!$field_value &&  isset($it_epoll_translation_default_fields[$field_name])){
            return $it_epoll_translation_default_fields[$field_name];
        }else{
            return $field_value;
        }
     
    }
    
    add_filter( 'it_epoll_poll_get_ttext_additional', 'it_epoll_get_ttext_default_fields'); 
}

if(!function_exists('it_epoll_get_ttext_default_fields')){
   function it_epoll_get_ttext_default_fields($field_name){
       
        global $it_epoll_translation_default_fields;
        
        $field_value = get_option($field_name);
        if(!$field_value &&  isset($it_epoll_translation_default_fields[$field_name])){
            return $it_epoll_translation_default_fields[$field_name];
        }else{
            return $field_value;
        }
     
    }
    add_filter( 'it_epoll_poll_get_ttext_additional', 'it_epoll_get_ttext_default_fields'); 
}