<?php
if(!function_exists('it_epoll_add_poll_list_ui_vote_button')){
    add_action('it_epoll_poll_list_ui_vote_button','it_epoll_add_poll_list_ui_vote_button');
        function it_epoll_add_poll_list_ui_vote_button($args){
            $poll_id = $args['poll_id'];
            $option_id = $args['option_id'];
            if(!it_epoll_check_for_unique_voting($poll_id,$option_id)){
            ?>
             <form action="" name="it_epoll_survey-item-action-form" class="it_epoll_survey-item-action-form">
                    <input type="hidden" name="it_epoll_poll-security_check" id="it_epoll_poll-security_check" value="<?php echo esc_attr(wp_create_nonce( 'it_epoll_poll'),'it_epoll');?>" required/>
                    <input type="hidden" name="it_epoll_poll-id" id="it_epoll_poll-id" value="<?php echo esc_attr($poll_id,'it_epoll');?>">
					<input type="hidden" name="it_epoll_survey-item-id" id="it_epoll_survey-item-id" value="<?php echo esc_attr($option_id,'it_epoll');?>">
					<input type="button" name="it_epoll_survey-vote-button" id="it_epoll_survey-vote-button" class="it_epoll_orange_gradient" value="<?php esc_attr_e('Vote','it_epoll');?>">
			</form>
        <?php }}
}


if(!function_exists('it_epoll_add_opinion_ui_add_nonce_field')){
    add_action('it_epoll_opinion_ui_pre_options','it_epoll_add_opinion_ui_add_nonce_field');
        function it_epoll_add_opinion_ui_add_nonce_field($args){
            $poll_id = $args['poll_id'];?>
            <input type="hidden" name="wp_nonce" id="it_epoll_poll-security_check" value="<?php echo esc_attr(wp_create_nonce( 'it_epoll_opinion'),'it_epoll');?>" required/>
        <?php }
}


if(!function_exists('it_epoll_add_poll_ui_vote_button')){
   
    add_action('it_epoll_poll_ui_vote_button','it_epoll_add_poll_ui_vote_button');
    
    function it_epoll_add_poll_ui_vote_button($args){
        $poll_id = $args['poll_id'];
        $option_id = $args['option_id'];
        if(!it_epoll_check_for_unique_voting($poll_id,$option_id)){
       
     
        ?>
        <form action="" name="it_epoll_survey-item-action-form" class="it_epoll_survey-item-action-form">
            <input type="hidden" name="it_epoll_poll-security_check" id="it_epoll_poll-security_check" value="<?php echo esc_attr(wp_create_nonce( 'it_epoll_poll'),'it_epoll');?>" required/>   
            <input type="hidden" name="it_epoll_poll-id" id="it_epoll_poll-id" value="<?php echo esc_attr($poll_id,'it_epoll');?>">
            <input type="hidden" name="it_epoll_survey-item-id" id="it_epoll_survey-item-id" value="<?php echo esc_attr($option_id,'it_epoll');?>">
            <input type="button" name="it_epoll_survey-vote-button" id="it_epoll_survey-vote-button" class="it_epoll_orange_gradient" value="<?php esc_attr_e('Vote','it_epoll');?>">
        </form>
    <?php 
    }
}
}


if(!function_exists('it_epoll_poll_ui_after_options_private_voting')){

    add_action('it_epoll_after_poll','it_epoll_poll_ui_after_options_private_voting');
        function it_epoll_poll_ui_after_options_private_voting($args){ 
            $poll_id = sanitize_text_field($args['poll_id']);
          
            if(get_post_meta($poll_id,'it_epoll_poll_enable_private_voting',true)){
                $voting_access_code = "";
                
                if(isset($_GET['it_epoll_voting_access_code'])) $voting_access_code = sanitize_text_field($_GET['it_epoll_voting_access_code']);
                if ( ! isset( $_GET['it_epoll_voting_access_nonce'] )  || ! wp_verify_nonce( $_GET['it_epoll_voting_access_nonce'], 'it_epoll_voting_access' ) ) {
                    $voting_access_code ="";
                }
             if(get_post_meta($poll_id,'it_epoll_poll_private_voting_pin',true) != $voting_access_code){
            ?>
            <div class="it_epoll_container_alert it_epoll_container_alert_top it_epoll_container_alert_show" id="it_epoll_voting_access">
                <div class="it_epoll_container_alert_inner epoll_no_shadow">
                            <h3>
                                <span><?php esc_attr_e('Enter Your Voting Access Pin','it_epoll');?></span>
                             </h3>
                        <div id="it_epoll_opinion_cc" class="it_epoll_poll_modal_container">
                            <form action="" method="get" name="it_epoll_access_code_form" id="it_epoll_access_code_form" class="it_epoll_access_code_form">
                                <div class="it_edb_input_container it_edb_input_va_container">
                                    <label><?php esc_attr_e('Your Access Code','it_epoll');?></label>
                                    <?php wp_nonce_field( 'it_epoll_voting_access', 'it_epoll_voting_access_nonce' ); ?>
                                    <input type="text" name="it_epoll_voting_access_code" class="it_epoll_opinion_cc_search it_epoll_cform_input it_edb_input" autocomplete="off" placeholder="<?php esc_attr_e('Enter Voting Access Pin eg: 414141','it_epoll');?>" required/>
                                </div>
                                <button type="submit" class="it_epoll_voting_access_btn"><?php esc_attr_e('Verify','it_epoll');?></button>
                            </form>
                        </div>
                
                </div>
            </div>
    <?php }
            }
    }
}