<?php
/**
 * The settings link of the plugin in admin panel.
 *
 * @package    Wishlist-and-compare
 * @subpackage Wishlist-and-compare/inc/base
 *
 * @link  https://themehigh.com
 * @since 1.0.0.0
 */

namespace THWWC\base;

use \THWWC\base\THWWC_Base_Controller;

if (!class_exists('THWWC_Settings_Links')) :
    /**
     * Settings link class
     *
     * @package Wishlist-and-compare
     * @link    https://themehigh.com
     */
    class THWWC_Settings_Links extends THWWC_Base_Controller
    {
        /**
         * Hook for settings link.
         *
         * @return void
         */
        public function register()
        {
            add_filter("plugin_action_links_".THWWC_BASE_NAME, array($this, 'settings_links'));
            add_action('admin_footer-plugins.php', array($this, 'thwwc_deactivation_form'));
            add_action('wp_ajax_thwwc_deactivation_reason', array($this, 'thwwc_deactivation_reason'));
        }

        /**
         * Settings link
         *
         * @param array $links All links link like activate
         *
         * @return array
         */
        public function settings_links( $links )
        {
            $settings_link = '<a href="'.admin_url('admin.php?page=th_wishlist_settings').'">'.esc_html('Settings').'</a>';
            array_unshift($links, $settings_link);

            if (array_key_exists('deactivate', $links)) {
                $links['deactivate'] = str_replace('<a', '<a class="thwwc-deactivate-link"', $links['deactivate']);
            }
            return $links;
        }
        public function thwwc_deactivation_form()
        {
            $is_snooze_time = get_user_meta( get_current_user_id(), 'thwwc_deactivation_snooze', true );
            $now = time();

            if($is_snooze_time && ($now < $is_snooze_time)){
                return;
            }

            $deactivation_reasons = $this->get_deactivation_reasons();
            ?>
            <div id="thwwc_deactivation_form" class="thpladmin-modal-mask">
                <div class="thpladmin-modal">
                    <div class="modal-container">
                        <!-- <span class="modal-close" onclick="thwwcfCloseModal(this)">×</span> -->
                        <div class="modal-content">
                            <div class="modal-body">
                                <div class="model-header">
                                    <img class="th-logo" src="<?php echo esc_url(THWWC_URL .'assets/libs/icons/themehigh.svg'); ?>" alt="themehigh-logo">
                                    <span><?php echo __('Quick Feedback', 'wishlist-and-compare'); ?></span>
                                </div>

                                <!-- <div class="get-support-version-b">
                                    <p>We are sad to see you go. We would be happy to fix things for you. Please raise a ticket to get help</p>
                                    <a class="thwwc-link thwwc-right-link thwwc-active" target="_blank" href="https://help.themehigh.com/hc/en-us/requests/new?utm_source=wac_free&utm_medium=feedback_form&utm_campaign=get_support"><?php echo __('Get Support', 'wishlist-and-compare'); ?></a>
                                </div> -->

                                <main class="form-container main-full">
                                    <p class="thwwc-title-text"><?php echo __('If you have a moment, please let us know why you want to deactivate this plugin', 'wishlist-and-compare'); ?></p>
                                    <ul class="deactivation-reason" data-nonce="<?php echo wp_create_nonce('thwwc_deactivate_nonce'); ?>">
                                        <?php 
                                        if($deactivation_reasons){
                                            foreach($deactivation_reasons as $key => $reason){
                                                $reason_type = isset($reason['reason_type']) ? $reason['reason_type'] : '';
                                                $reason_placeholder = isset($reason['reason_placeholder']) ? $reason['reason_placeholder'] : '';
                                                ?>
                                                <li data-type="<?php echo esc_attr($reason_type); ?>" data-placeholder="<?php echo esc_attr($reason_placeholder); ?> ">
                                                    <label>
                                                        <input type="radio" name="selected-reason" value="<?php echo esc_attr($key); ?>">
                                                        <span><?php echo esc_html($reason['radio_label']); ?></span>
                                                    </label>
                                                </li>
                                                <?php
                                            }
                                        }
                                        ?>
                                    </ul>
                                    <p class="thwwc-privacy-cnt"><?php echo __('This form is only for getting your valuable feedback. We do not collect your personal data. To know more read our ', 'wishlist-and-compare'); ?> <a class="thwwc-privacy-link" target="_blank" href="<?php echo esc_url('https://www.themehigh.com/privacy-policy/');?>"><?php echo __('Privacy Policy', 'wishlist-and-compare'); ?></a></p>
                                </main>
                                <footer class="modal-footer">
                                    <div class="thwwc-left">
                                        <a class="thwwc-link thwwc-left-link thwwc-deactivate" href="#"><?php echo __('Skip & Deactivate', 'wishlist-and-compare'); ?></a>
                                    </div>
                                    <div class="thwwc-right">
                                        
                                        <a class="thwwc-link thwwc-right-link thwwc-active" target="_blank" href="https://help.themehigh.com/hc/en-us/requests/new?utm_source=wac_free&utm_medium=feedback_form&utm_campaign=get_support"><?php echo __('Get Support', 'wishlist-and-compare'); ?></a>

                                        <a class="thwwc-link thwwc-right-link thwwc-active thwwc-submit-deactivate" href="#"><?php echo __('Submit and Deactivate', 'wishlist-and-compare'); ?></a>
                                        <a class="thwwc-link thwwc-right-link thwwc-close" href="#"><?php echo __('Cancel', 'wishlist-and-compare'); ?></a>
                                    </div>
                                </footer>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <style type="text/css">
                .th-logo{
                    margin-right: 10px;
                }
                .thpladmin-modal-mask{
                    position: fixed;
                    background-color: rgba(17,30,60,0.6);
                    top: 0;
                    left: 0;
                    width: 100%;
                    height: 100%;
                    z-index: 9999;
                    overflow: scroll;
                    transition: opacity 250ms ease-in-out;
                }
                .thpladmin-modal-mask{
                    display: none;
                }
                .thpladmin-modal .modal-container{
                    position: absolute;
                    background: #fff;
                    border-radius: 2px;
                    overflow: hidden;
                    left: 50%;
                    top: 50%;
                    transform: translate(-50%,-50%);
                    width: 50%;
                    max-width: 960px;
                    /*min-height: 560px;*/
                    /*height: 80vh;*/
                    /*max-height: 640px;*/
                    animation: appear-down 250ms ease-in-out;
                    border-radius: 15px;
                }
                .model-header {
                    padding: 21px;
                }
                .thpladmin-modal .model-header span {
                    font-size: 18px;
                    font-weight: bold;
                }
                .thpladmin-modal .model-header {
                    padding: 21px;
                    background: #ECECEC;
                }
                .thpladmin-modal .form-container {
                    margin-left: 23px;
                    clear: both;
                }
                .thpladmin-modal .deactivation-reason input {
                    margin-right: 13px;
                }
                .thpladmin-modal .thwwc-privacy-cnt {
                    color: #919191;
                    font-size: 12px;
                    margin-bottom: 31px;
                    margin-top: 18px;
                    max-width: 75%;
                }
                .thpladmin-modal .deactivation-reason li {
                    margin-bottom: 17px;
                }
                .thpladmin-modal .modal-footer {
                    padding: 20px;
                    border-top: 1px solid #E7E7E7;
                    float: left;
                    width: 100%;
                    box-sizing: border-box;
                }
                .thwwc-left {
                    float: left;
                }
                .thwwc-right {
                    float: right;
                }
                .thwwc-link {
                    line-height: 31px;
                    font-size: 12px;
                }
                .thwwc-left-link {
                    font-style: italic;
                }
                .thwwc-right-link {
                    padding: 0px 20px;
                    border: 1px solid;
                    display: inline-block;
                    text-decoration: none;
                    border-radius: 5px;
                }
                .thwwc-right-link.thwwc-active {
                    background: #0773AC;
                    color: #fff;
                }
                .thwwc-title-text {
                    color: #2F2F2F;
                    font-weight: 500;
                    font-size: 15px;
                }
                .reason-input {
                    margin-left: 31px;
                    margin-top: 11px;
                    width: 70%;
                }
                .reason-input input {
                    width: 100%;
                    height: 40px;
                }
                .reason-input textarea {
                    width: 100%;
                    min-height: 80px;
                }
                input.th-snooze-checkbox {
                    width: 15px;
                    height: 15px;
                }
                input.th-snooze-checkbox:checked:before {
                    width: 1.2rem;
                    height: 1.2rem;
                }
                .th-snooze-select {
                    margin-left: 20px;
                    width: 172px;
                }

                /* Version B */
                .get-support-version-b {
                    width: 100%;
                    padding-left: 23px;
                    clear: both;
                    float: left;
                    box-sizing: border-box;
                    background: #0673ab;
                    color: #fff;
                    margin-bottom: 20px;
                }
                .get-support-version-b p {
                    font-size: 12px;
                    line-height: 17px;
                    width: 70%;
                    display: inline-block;
                    margin: 0px;
                    padding: 15px 0px;
                }
                .get-support-version-b .thwwc-right-link {
                    background-image: url(<?php echo esc_url(THWWC_URL .'admin/assets/css/get_support_icon.svg'); ?>);
                    background-repeat: no-repeat;
                    background-position: 11px 10px;
                    padding-left: 31px;
                    color: #0773AC;
                    background-color: #fff;
                    float: right;
                    margin-top: 17px;
                    margin-right: 20px;
                }
                .thwwc-privacy-link {
                    font-style: italic;
                }
                .wac-review-link {
                    margin-top: 7px;
                    margin-left: 31px;
                    font-size: 16px;
                }
                span.wac-rating-link {
                    color: #ffb900;
                }
                .thwwc-review-and-deactivate {
                    text-decoration: none;
                }
            </style>

            <script type="text/javascript">
                (function($){
                    var popup = $("#thwwc_deactivation_form");
                    var deactivation_link = '';
                    $('.thwwc-deactivate-link').on('click', function(e){
                        e.preventDefault();
                        deactivation_link = $(this).attr('href');
                        popup.css("display", "block");
                        popup.find('a.thwwc-deactivate').attr('href', deactivation_link);
                    });

                    popup.on('click', 'input[type="radio"]', function () {
                        var parent = $(this).parents('li:first');
                        popup.find('.reason-input').remove();

                        var type = parent.data('type');
                        var placeholder = parent.data('placeholder');

                        var reason_input = '';
                        if('text' == type){
                            reason_input += '<div class="reason-input">';
                            reason_input += '<input type="text" placeholder="'+ placeholder +'">';
                            reason_input += '</div>';
                        }else if('textarea' == type){
                            reason_input += '<div class="reason-input">';
                            reason_input += '<textarea row="5" placeholder="'+ placeholder +'">';
                            reason_input += '</textarea>';
                            reason_input += '</div>';
                        }else if('checkbox' == type){
                            reason_input += '<div class="reason-input ">';
                            reason_input += '<input type="checkbox" id="th-snooze" name="th-snooze" class="th-snooze-checkbox">';
                            reason_input += '<label for="th-snooze">Snooze this panel while troubleshooting</label>';
                            reason_input += '<select name="th-snooze-time" class="th-snooze-select" disabled>';
                            reason_input += '<option value="<?php echo HOUR_IN_SECONDS ?>">1 Hour</option>';
                            reason_input += '<option value="<?php echo 12*HOUR_IN_SECONDS ?>">12 Hour</option>';
                            reason_input += '<option value="<?php echo DAY_IN_SECONDS ?>">24 Hour</option>';
                            reason_input += '<option value="<?php echo WEEK_IN_SECONDS ?>">1 Week</option>';
                            reason_input += '<option value="<?php echo MONTH_IN_SECONDS ?>">1 Month</option>';
                            reason_input += '</select>';
                            reason_input += '</div>';
                        }else if('reviewlink' == type){
                            reason_input += '<div class="reason-input wac-review-link">';
                            /*
                            reason_input += '<?php _e('Deactivate and ', 'wishlist-and-compare');?>'
                            reason_input += '<a href="#" target="_blank" class="thwwc-review-and-deactivate">';
                            reason_input += '<?php _e('leave a review', 'wishlist-and-compare'); ?>';
                            reason_input += '<span class="wac-rating-link"> &#9733;&#9733;&#9733;&#9733;&#9733; </span>';
                            reason_input += '</a>';
                            */
                            reason_input += '<input type="hidden" value="<?php _e('Upgraded', 'wishlist-and-compare');?>">';
                            reason_input += '</div>';
                        }

                        if(reason_input !== ''){
                            parent.append($(reason_input));
                        }
                    });

                    popup.on('click', '.thwwc-close', function () {
                        popup.css("display", "none");
                    });

                    /*
                    popup.on('click', '.thwwc-review-and-deactivate', function () {
                        e.preventDefault();
                        window.open("https://wordpress.org/support/plugin/wishlist-and-compare/reviews/?rate=5#new-post");
                        console.log(deactivation_link);
                        window.location.href = deactivation_link;
                    });
                    */

                    popup.on('click', '.thwwc-submit-deactivate', function (e) {
                        e.preventDefault();
                        var button = $(this);
                        if (button.hasClass('disabled')) {
                            return;
                        }
                        var radio = $('.deactivation-reason input[type="radio"]:checked');
                        var parent_li = radio.parents('li:first');
                        var parent_ul = radio.parents('ul:first');
                        var input = parent_li.find('textarea, input[type="text"], input[type="hidden"]');
                        var wac_deacive_nonce = parent_ul.data('nonce');

                        $.ajax({
                            url: ajaxurl,
                            type: 'POST',
                            data: {
                                action: 'thwwc_deactivation_reason',
                                reason: (0 === radio.length) ? 'none' : radio.val(),
                                comments: (0 !== input.length) ? input.val().trim() : '',
                                security: wac_deacive_nonce,
                            },
                            beforeSend: function () {
                                button.addClass('disabled');
                                button.text('Processing...');
                            },
                            complete: function () {
                                window.location.href = deactivation_link;
                            }
                        });
                    });

                    popup.on('click', '#th-snooze', function () {
                        if($(this).is(':checked')){
                            popup.find('.th-snooze-select').prop("disabled", false);
                        }else{
                            popup.find('.th-snooze-select').prop("disabled", true);
                        }
                    });

                }(jQuery))
            </script>

            <?php 
        }
        private function get_deactivation_reasons()
        {
            return array(
                'feature_missing'=> array(
                    'radio_val'          => 'feature_missing',
                    'radio_label'        => __('A specific feature is missing', 'wishlist-and-compare'),
                    'reason_type'        => 'text',
                    'reason_placeholder' => __('Type in the feature', 'wishlist-and-compare'),
                ),

                'error_or_not_working'=> array(
                    'radio_val'          => 'error_or_not_working',
                    'radio_label'        => __('Found an error in the plugin/ Plugin was not working', 'wishlist-and-compare'),
                    'reason_type'        => 'text',
                    'reason_placeholder' => __('Specify the issue', 'wishlist-and-compare'),
                ),

                'hard_to_use' => array(
                    'radio_val'          => 'hard_to_use',
                    'radio_label'        => __('It was hard to use', 'wishlist-and-compare'),
                    'reason_type'        => 'text',
                    'reason_placeholder' => __('How can we improve your experience?', 'wishlist-and-compare'),
                ),

                'found_better_plugin' => array(
                    'radio_val'          => 'found_better_plugin',
                    'radio_label'        => __('I found a better Plugin', 'wishlist-and-compare'),
                    'reason_type'        => 'text',
                    'reason_placeholder' => __('Could you please mention the plugin?', 'wishlist-and-compare'),
                ),

                // 'not_working_as_expected'=> array(
                //  'radio_val'          => 'not_working_as_expected',
                //  'radio_label'        => __('The plugin didn’t work as expected', 'wishlist-and-compare'),
                //  'reason_type'        => 'text',
                //  'reason_placeholder' => __('Specify the issue', 'wishlist-and-compare'),
                // ),

                'temporary' => array(
                    'radio_val'          => 'temporary',
                    'radio_label'        => __('It’s a temporary deactivation - I’m troubleshooting an issue', 'wishlist-and-compare'),
                    'reason_type'        => 'checkbox',
                    'reason_placeholder' => __('Could you please mention the plugin?', 'wishlist-and-compare'),
                ),

                'other' => array(
                    'radio_val'          => 'other',
                    'radio_label'        => __('Not mentioned here', 'wishlist-and-compare'),
                    'reason_type'        => 'textarea',
                    'reason_placeholder' => __('Kindly tell us your reason, so that we can improve', 'wishlist-and-compare'),
                ),
            );
        }

        public function thwwc_deactivation_reason()
        {
            global $wpdb;

            check_ajax_referer('thwwc_deactivate_nonce', 'security');

            if(!isset($_POST['reason'])){
                return;
            }

            if($_POST['reason'] === 'temporary'){

                $snooze_period = isset($_POST['th-snooze-time']) && $_POST['th-snooze-time'] ? $_POST['th-snooze-time'] : MINUTE_IN_SECONDS ;
                $time_now = time();
                $snooze_time = $time_now + $snooze_period;

                update_user_meta(get_current_user_id(), 'thwwc_deactivation_snooze', $snooze_time);

                return;
            }
            
            $data = array(
                'plugin'        => 'thwwc',
                'reason'        => sanitize_text_field($_POST['reason']),
                'comments'      => isset($_POST['comments']) ? sanitize_textarea_field(wp_unslash($_POST['comments'])) : '',
                'date'          => gmdate("M d, Y h:i:s A"),
                'software'      => $_SERVER['SERVER_SOFTWARE'],
                'php_version'   => phpversion(),
                'mysql_version' => $wpdb->db_version(),
                'wp_version'    => get_bloginfo('version'),
                'wc_version'    => (!defined('WC_VERSION')) ? '' : WC_VERSION,
                'locale'        => get_locale(),
                'multisite'     => is_multisite() ? 'Yes' : 'No',
                'plugin_version'=> THWWC_VERSION
            );

            $response = wp_remote_post('https://feedback.themehigh.in/api/add_feedbacks', array(
                'method'      => 'POST',
                'timeout'     => 45,
                'redirection' => 5,
                'httpversion' => '1.0',
                'blocking'    => false,
                'headers'     => array( 'Content-Type' => 'application/json' ),
                'body'        => json_encode($data),
                'cookies'     => array()
                    )
            );

            wp_send_json_success();
        }
    }
endif;