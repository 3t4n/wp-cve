<?php if(!function_exists('it_epoll_google_analytics_add_fields')){
    add_action('it_epoll_options_advanced_fields','it_epoll_google_analytics_add_fields');
        function it_epoll_google_analytics_add_fields(){?>
            <table class="widefat  no-border-table">
                <tbody>
                <tr>
                    <td>   
                        <label>
                            <input class="it_epoll_has_oncheck_div" type="checkbox" name="it_epoll_settings_google_analytics_enabled" value="1"<?php if(get_option('it_epoll_settings_google_analytics_enabled')) echo esc_attr(' checked','it_epoll');?>/> <?php esc_attr_e('Enable Google Analytics on Voting','it_epoll');?>
                        </label>
                        <div class="it_epoll_oncheck_div <?php if(get_option('it_epoll_settings_google_analytics_enabled')) echo esc_attr(' it_epoll_oncheck_div_show','it_epoll');?>">
                            <label><?php esc_attr_e('GA Tracking ID','it_epoll');?></label>
                            <input type="text" class="widefat" placeholder="<?php esc_attr_e("eg: UA-123456789-2",'it_epoll');?>" name="it_epoll_settings_google_analytics_id" value="<?php echo esc_attr(get_option('it_epoll_settings_google_analytics_id'),'it_epoll');?>"/>
                            <hr>
                            <label><?php esc_attr_e('Event Name','it_epoll');?></label>
                            <input type="text" class="widefat" name="it_epoll_settings_google_analytics_event" placeholder="<?php esc_attr_e("eg: vote_button_clcik",'it_epoll');?>" value="<?php echo esc_attr(get_option('it_epoll_settings_google_analytics_event'),'it_epoll');?>"/>
                        
                        </div>
                    </td>
                </tr> 
            </tbody>
        </table>
        <?php }
    }

    if(!function_exists('it_epoll_google_analytics_fields_save')){
        add_action('it_epoll_options_save_extra_settings','it_epoll_google_analytics_fields_save');
        
        function it_epoll_google_analytics_fields_save(){
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
            register_setting( 'it_epoll_opt_settings','it_epoll_settings_google_analytics_enabled', $int_args);
            register_setting( 'it_epoll_opt_settings','it_epoll_settings_google_analytics_id', $string_args);
            register_setting( 'it_epoll_opt_settings','it_epoll_settings_google_analytics_event', $string_args);
        }
        
    }


    if(!function_exists('it_epoll_google_analytics_js_code')){
        add_action('wp_footer','it_epoll_google_analytics_js_code');
        function it_epoll_google_analytics_js_code(){
            if(get_option('it_epoll_settings_google_analytics_enabled')){?>
            <!-- Google tag (gtag.js) -->
            <script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo esc_attr(get_option('it_epoll_settings_google_analytics_id','it_epoll'));?>"></script>
            <script type="text/javascript">
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());
            gtag('config', '<?php echo esc_attr(get_option('it_epoll_settings_google_analytics_id','it_epoll'));?>');
            jQuery('#epoll_opinion_vote_button,#it_epoll_survey-confirm-button,#it_epoll_survey-vote-button').on('click',function(){
                ga('send', 'event', {
                    eventCategory: '<?php echo esc_attr(get_option('it_epoll_settings_google_analytics_event'),'it_epoll');?>',
                    eventAction: 'click',
                    eventLabel: '<?php wp_title();?>'
                });
            });
            </script>
        <?php }
        }
}