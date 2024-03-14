<?php if(!function_exists('it_epoll_default_add_pro_metadata')){
    
    add_action('it_epoll_poll_option_meta_ui','it_epoll_default_add_pro_metadata',0);
    add_action('it_epoll_opinion_option_meta_ui','it_epoll_default_add_pro_metadata',0);
    
    function it_epoll_default_add_pro_metadata($args){
        $post_id = $args['poll_id'];
        ?>
        <table class="form-table">
            <thead>
                <tr>
                    <th colspan="4">
                            <label><?php esc_attr_e('Advance Settings','it_epoll');?></label>
                        </th>
                    </tr>
            </thead>
                <tbody>
                  
                    <tr>
                       
                        <td colspan="2">   
                                    <label>
                                        <input class="it_epoll_has_oncheck_div" type="checkbox" name="it_epoll_poll_enable_private_voting" value="1"<?php if(get_post_meta($post_id,'it_epoll_poll_enable_private_voting',true)) echo esc_attr(' checked','it_epoll');?>> <?php esc_attr_e('Enable Private Voting','it_epoll');?></label>
                                        <div class="it_epoll_oncheck_div<?php if(get_post_meta($post_id,'it_epoll_poll_enable_private_voting',true)) echo esc_attr(' it_epoll_oncheck_div_show','it_epoll');?>" style="margin-top: 10px;">
                                        <table>
                                            <tr>
                                            <td>
                                                <label><?php esc_attr_e('Voting Access Code','it_epoll');?></label>
                                            </td>
                                            <td>
                                                <input type="text" class="widefat" name="it_epoll_poll_private_voting_pin" id="it_epoll_poll_private_voting_pin" value="<?php echo esc_attr(get_post_meta($post_id,'it_epoll_poll_private_voting_pin',true),'it_epoll');?>">
                                            </td>
                                            <td>
                                                <button class="button button-primary" type="button" onclick="generateVotingAccessCode();">
                                                <span class="btn-inner--icon">
                                                    <i class="fa-solid fa-lock"></i> <?php esc_attr_e('Generate Pin','it_epoll');?></span>
                                                </button>
                                            </td>
                                            </tr>
                                        </table>
                                        </div>
                                </td>

                    
                    </tr> 
                    <tr>
                        <td>
                            <?php esc_attr_e('Poll Start Date','it_epoll');?>
                            <span class="it_epolladmin_pro_badge" style="top: 2px; position: relative;"><i class="dashicons dashicons-star-empty"></i> <?php esc_attr_e('Premium Only','it_epoll');?></span></td>
		
                        </td>
                        <td>
                            <input type="date" id="it_epoll_vote_start_date_time" name="it_epoll_vote_start_date_time" readonly/>
                        </td>
                        <td>
                            <?php esc_attr_e('Poll End Date','it_epoll');?> 
                        
                        </td>
                        <td>
                            <input type="date" min="<?php echo esc_attr(gmdate('Y-m-d', strtotime('+1 day')),'it_epoll'); ?>" id="it_epoll_vote_end_date_time" name="it_epoll_vote_end_date_time"  value="<?php echo esc_attr(get_post_meta($post_id,'it_epoll_vote_end_date_time',true),'it_epoll');?>"/>
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <?php esc_attr_e('Result Visibility','it_epoll');?>
                        </td>
                        <td>
                            <select name="it_epoll_poll_result_visibility" class="widefat">
                                <option value="public"<?php if(get_post_meta($post_id,'it_epoll_poll_result_visibility',true) == 'public') echo esc_attr(' selected','it_epoll');?>><?php esc_attr_e('Always Public','it_epoll');?></option>
                            </select>
                        </td>
                        
                        <td>
                            <?php esc_attr_e('Voting restrictions','it_epoll');?>
                        </td>
                        <td>
                            <select name="it_epoll_poll_voting_restriction" class="widefat">
                                <option value=""<?php if(!get_post_meta($post_id,'it_epoll_poll_voting_restriction',true)) echo esc_attr(' selected','it_epoll');?>><?php esc_attr_e('Unlimited votes per user','it_epoll');?></option>
                                <option value="session"<?php if(get_post_meta($post_id,'it_epoll_poll_voting_restriction',true) == 'session') echo esc_attr(' selected','it_epoll');?>><?php esc_attr_e('One vote per browser session','it_epoll');?></option>
                                <option value="cookie"<?php if(get_post_meta($post_id,'it_epoll_poll_voting_restriction',true) == 'cookie') echo esc_attr(' selected','it_epoll');?>><?php esc_attr_e('Detect voter Via Cookie','it_epoll');?></option>
                               
                                <?php do_action('it_epoll_poll_option_meta_ui_voting_restriction_options',array('poll_id'=>$post_id));?>
                            </select>
                        </td>
                    </tr>
                   
                    <?php  do_action('it_epoll_poll_option_meta_ui_after_advanced',array('poll_id'=>$post_id));?>
                </tbody>
            </table>
            <script type="text/javascript">
            jQuery.noConflict();
            jQuery(document).ready(function($) {

            
                jQuery('.it_epoll_has_oncheck_div').on('change',function(){
                    if(jQuery(this).is(":checked")){
                        generateVotingAccessCode();
                        jQuery(this).parent().parent().find('.it_epoll_oncheck_div').addClass('it_epoll_oncheck_div_show');
                    }else{
                        jQuery(this).parent().parent().find('.it_epoll_oncheck_div').removeClass('it_epoll_oncheck_div_show');
                    }
                
                });

        function generateVotingAccessCode(){
        var pin =    Math.floor(100000 + Math.random() * 900000);
            jQuery('#it_epoll_poll_private_voting_pin').val(pin);
        }
    });
    </script>
        <?php 
    }
}


if(!function_exists('it_epoll_poll_option_meta_save_basic_field_data')){
    
    add_action('it_epoll_poll_option_meta_save','it_epoll_poll_option_meta_save_basic_field_data');
    add_action('it_epoll_opinion_option_meta_save','it_epoll_poll_option_meta_save_basic_field_data');
    function it_epoll_poll_option_meta_save_basic_field_data($args){ 
        $post_id = $args['poll_id'];

        // Check if our nonce is set.
        if ( ! isset( $_POST['it_epoll_poll_metabox_id_nonce'] ) ) {
            return;
        }

        // Verify that the nonce is valid.
        if ( ! wp_verify_nonce( $_POST['it_epoll_poll_metabox_id_nonce'], 'it_epoll_poll_metabox_id' ) ) {
            return;
        }
        //Update OTP Option
        if(isset($_POST['it_epoll_vote_end_date_time'])){
            $it_epoll_vote_end_date_time =  sanitize_text_field($_POST['it_epoll_vote_end_date_time']);
            update_post_meta( $post_id, 'it_epoll_vote_end_date_time', $it_epoll_vote_end_date_time );
        }


        if(isset($_POST['it_epoll_poll_result_visibility'])){
            $it_epoll_poll_result_visibility =  sanitize_text_field($_POST['it_epoll_poll_result_visibility']);
            update_post_meta( $post_id, 'it_epoll_poll_result_visibility', $it_epoll_poll_result_visibility );
        }

        
        if(isset($_POST['it_epoll_poll_voting_restriction'])){
            $it_epoll_poll_voting_restriction =  sanitize_text_field($_POST['it_epoll_poll_voting_restriction']);
            update_post_meta( $post_id, 'it_epoll_poll_voting_restriction', $it_epoll_poll_voting_restriction );
        }
        
        if(isset($_POST['it_epoll_poll_enable_private_voting'])){
            $it_epoll_poll_enable_private_voting =  sanitize_text_field($_POST['it_epoll_poll_enable_private_voting']);
            update_post_meta( $post_id, 'it_epoll_poll_enable_private_voting', $it_epoll_poll_enable_private_voting );
        }else{
           delete_post_meta( $post_id, 'it_epoll_poll_enable_private_voting');
        }

        if(isset($_POST['it_epoll_poll_private_voting_pin'])){
            $it_epoll_poll_private_voting_pin =  sanitize_text_field($_POST['it_epoll_poll_private_voting_pin']);
            update_post_meta( $post_id, 'it_epoll_poll_private_voting_pin', $it_epoll_poll_private_voting_pin );
        }

        
        if(isset($_POST['it_epoll_poll_container_color_secondary'])){
            $it_epoll_poll_container_color_secondary =  sanitize_text_field($_POST['it_epoll_poll_container_color_secondary']);
            update_post_meta( $post_id, 'it_epoll_poll_container_color_secondary', $it_epoll_poll_container_color_secondary );
        }

        do_action('it_epoll_opinion_advance_meta_save',$post_id);
        do_action('it_epoll_poll_advance_meta_save',$post_id);
        do_action('it_epoll_poll_schedule_cron_event',$post_id);

    }
}