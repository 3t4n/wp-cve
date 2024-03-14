<?php
if (!class_exists('ProductFeed_Uninstall_Feedback')) :

    /**
     * Class for catch Feedback on uninstall
     */
    class ProductFeed_Uninstall_Feedback {

        public function __construct() {
            add_action('admin_footer', array($this, 'deactivate_scripts'));
            add_action('wp_ajax_productfeed_submit_uninstall_reason', array($this, "send_uninstall_reason"));
        }

        private function get_uninstall_reasons() {

            $reasons = array(
                array(
                    'id' => 'could-not-understand',
                    'text' => __('I couldn\'t understand how to make it work', 'webtoffee-product-feed'),
                    'type' => 'supportlink',
                    'placeholder' => __('Could you tell us which step you found difficult?', 'webtoffee-product-feed')
                ),
                array(
                    'id' => 'not-have-that-feature',
                    'text' => __('I need a specific feature that you don’t support', 'webtoffee-product-feed'),
                    'type' => 'textarea',
                    'placeholder' => __('Could you tell us more about that feature?', 'webtoffee-product-feed')
                ),
                array(
                    'id' => 'ecountered-an-error',
                    'text' => __('Encountered an error', 'webtoffee-product-feed'),
                    'type' => 'encountered',
                    'placeholder' => __('Could you tell us which one it is?', 'webtoffee-product-feed')
                ),				
                array(
                    'id' => 'found-better-plugin',
                    'text' => __('Found a better plugin', 'webtoffee-product-feed'),
                    'type' => 'text',
                    'placeholder' => __('Could you specify the plugin?', 'webtoffee-product-feed')
                ),
                array(
                    'id' => 'is-not-looking-for',
                    'text' => __('It\'s not what I was looking for', 'webtoffee-product-feed'),
                    'type' => 'textarea',
                    'placeholder' => __('Could you tell us a bit more?', 'webtoffee-product-feed')
                ),				
                array(
                    'id' => 'not-work-as-expected',
                    'text' => __('The plugin didn’t work as expected', 'webtoffee-product-feed'),
                    'type' => 'textarea',
                    'placeholder' => __('Could you tell us what went wrong?', 'webtoffee-product-feed')
                ),
                array(
                    'id' => 'other',
                    'text' => __('Other', 'webtoffee-product-feed'),
                    'type' => 'textarea',
                    'placeholder' => __('Could you tell us a bit more?', 'webtoffee-product-feed')
                ),
            );

            return $reasons;
        }

        public function deactivate_scripts() {

            global $pagenow;
            if ('plugins.php' != $pagenow) {
                return;
            }
            $reasons = $this->get_uninstall_reasons();
            ?>
            <div class="productfeed-modal" id="productfeed-productfeed-modal">
                <div class="productfeed-modal-wrap">
                    <div class="productfeed-modal-header">
                        <h3><?php _e('If you have a moment, please let us know why you are deactivating:', 'webtoffee-product-feed'); ?></h3>
                    </div>
                    <div class="productfeed-modal-body">
                        <ul class="reasons">
                            <?php foreach ($reasons as $reason) { ?>
                                <li data-type="<?php echo esc_attr($reason['type']); ?>" data-placeholder="<?php echo esc_attr($reason['placeholder']); ?>">
                                    <label><input type="radio" name="selected-reason" value="<?php echo $reason['id']; ?>"> <?php echo $reason['text']; ?></label>
                                </li>
                            <?php } ?>
                        </ul>
                    </div>
                    <div class="productfeed-modal-footer">
                        <a href="#" class="dont-bother-me"><?php _e('I rather wouldn\'t say', 'webtoffee-product-feed'); ?></a>
                        <a class="button-primary" href="https://www.webtoffee.com/contact/" target="_blank">
                        <span class="dashicons dashicons-external" style="margin-top:3px;"></span> 
                        <?php _e('Get quick help', 'webtoffee-product-feed'); ?></a>
                        <button class="button-primary productfeed-model-submit"><?php _e('Submit & Deactivate', 'webtoffee-product-feed'); ?></button>
                        <button class="button-secondary productfeed-model-cancel"><?php _e('Cancel', 'webtoffee-product-feed'); ?></button>
                    </div>
                </div>
            </div>

            <style type="text/css">
                .productfeed-modal {
                    position: fixed;
                    z-index: 99999;
                    top: 0;
                    right: 0;
                    bottom: 0;
                    left: 0;
                    background: rgba(0,0,0,0.5);
                    display: none;
                }
                .productfeed-modal.modal-active {display: block;}
                .productfeed-modal-wrap {
                    width: 50%;
                    position: relative;
                    margin: 10% auto;
                    background: #fff;
                }
                .productfeed-modal-header {
                    border-bottom: 1px solid #eee;
                    padding: 8px 20px;
                }
                .productfeed-modal-header h3 {
                    line-height: 150%;
                    margin: 0;
                }
                .productfeed-modal-body {padding: 5px 20px 20px 20px;}
                .productfeed-modal-body .input-text,.productfeed-modal-body textarea {width:75%;}
                .productfeed-modal-body .reason-input {
                    margin-top: 5px;
                    margin-left: 20px;
                }
                .productfeed-modal-footer {
                    border-top: 1px solid #eee;
                    padding: 12px 20px;
                    text-align: right;
                }
                .encountered, .supportlink {
                        padding: 10px 0px 0px 35px !important;
                        font-size: 14px;
                }				
            </style>
            <script type="text/javascript">
                (function ($) {
                    $(function () {
                        var modal = $('#productfeed-productfeed-modal');
                        var deactivateLink = '';
                        $('#the-list').on('click', 'a.productfeed-deactivate-link', function (e) {
                            e.preventDefault();
                            modal.addClass('modal-active');
                            deactivateLink = $(this).attr('href');
                            modal.find('a.dont-bother-me').attr('href', deactivateLink).css('float', 'left');
                        });
						
						
                        modal.on('click', 'a.doc-and-support-doc', function (e) {
                            e.preventDefault();
                            window.open("https://www.webtoffee.com/webtoffee-product-feed-sync-setup-guide/");
                        });
                        modal.on('click', 'a.doc-and-support-forum', function (e) {
                            e.preventDefault();
                            window.open("https://www.webtoffee.com/contact/");
                        });
                        modal.on('click', 'a.pf-encountered', function (e) {
                            e.preventDefault();
                            window.open("https://www.webtoffee.com/contact/");
                        });							
						
                        modal.on('click', 'button.productfeed-model-cancel', function (e) {
                            e.preventDefault();
                            modal.removeClass('modal-active');
                        });
                        modal.on('click', 'input[type="radio"]', function () {
                            var parent = $(this).parents('li:first');
                            modal.find('.reason-input').remove();
                            var inputType = parent.data('type'),
                                    inputPlaceholder = parent.data('placeholder'),                                    
                                    reasonInputHtml = '';                                                                      
                                if ('encountered' === inputType) {
                                    if($('.encountered').length == 0){
                                        reasonInputHtml = '<div class="encountered"><?php _e('Please contact the', 'webtoffee-product-feed'); ?><a href="#" target="_blank" class="pf-encountered"> <?php _e('support', 'webtoffee-product-feed'); ?></a> <?php _e('and let us fix the issue for you.', 'webtoffee-product-feed'); ?></div>';
                                    }
                                }else if('supportlink' === inputType){
									if($('.supportlink').length == 0){
										reasonInputHtml = '<div class="supportlink"><?php _e('Please go through the', 'webtoffee-product-feed'); ?><a href="#" target="_blank" class="doc-and-support-doc"> <?php _e('documentation', 'webtoffee-product-feed'); ?></a> <?php _e('or contact us via', 'webtoffee-product-feed'); ?><a href="#" target="_blank" class="doc-and-support-forum"> <?php _e('support', 'webtoffee-product-feed'); ?></a></div>';
									}
								}else {
                                    if($('.encountered').length){
                                       $('.encountered'). remove();
                                    } 
									if($('.supportlink').length){
									   $('.supportlink'). remove();
									}									
                                    reasonInputHtml = '<div class="reason-input">' + (('text' === inputType) ? '<input type="text" class="input-text" size="40" />' : '<textarea rows="5" cols="45"></textarea>') + '</div>';
                                }
                            if (inputType !== '') {
                                parent.append($(reasonInputHtml));
                                parent.find('input, textarea').attr('placeholder', inputPlaceholder).focus();
                            }
                        });

                        modal.on('click', 'button.productfeed-model-submit', function (e) {
                            e.preventDefault();
                            var button = $(this);
                            if (button.hasClass('disabled')) {
                                return;
                            }
                            var $radio = $('input[type="radio"]:checked', modal);
                            var $selected_reason = $radio.parents('li:first'),
                                    $input = $selected_reason.find('textarea, input[type="text"]');

                            $.ajax({
                                url: ajaxurl,
                                type: 'POST',
                                data: {
                                    action: 'productfeed_submit_uninstall_reason',
                                    reason_id: (0 === $radio.length) ? 'none' : $radio.val(),
                                    reason_info: (0 !== $input.length) ? $input.val().trim() : ''
                                },
                                beforeSend: function () {
                                    button.addClass('disabled');
                                    button.text('Processing...');
                                },
                                complete: function () {
                                    window.location.href = deactivateLink;
                                }
                            });
                        });
                    });
                }(jQuery));
            </script>
            <?php
        }

        public function send_uninstall_reason() {

            global $wpdb;

            if (!isset($_POST['reason_id'])) {
                wp_send_json_error();
            }


            $data = array(
                'reason_id' => sanitize_text_field($_POST['reason_id']),
                'plugin' => "productfeed",
                'auth' => 'productfeed_uninstall_1234#',
                'date' => gmdate("M d, Y h:i:s A"),
                'url' => '',
                'user_email' => '',
                'reason_info' => isset($_REQUEST['reason_info']) ? trim(stripslashes($_REQUEST['reason_info'])) : '',
                'software' => $_SERVER['SERVER_SOFTWARE'],
                'php_version' => phpversion(),
                'mysql_version' => $wpdb->db_version(),
                'wp_version' => get_bloginfo('version'),
                'wc_version' => (!defined('WC_VERSION')) ? '' : WC_VERSION,
                'locale' => get_locale(),
                'multisite' => is_multisite() ? 'Yes' : 'No',
                'productfeed_version' => WEBTOFFEE_PRODUCT_FEED_SYNC_VERSION,
                
            );
            // Write an action/hook here in webtoffe to recieve the data
            $resp = wp_remote_post('https://feedback.webtoffee.com/wp-json/productfeed/v1/uninstall', array(
                'method' => 'POST',
                'timeout' => 45,
                'redirection' => 5,
                'httpversion' => '1.0',
                'blocking' => false,
                'body' => $data,
                'cookies' => array()
                    )
            );

            wp_send_json_success();
        }

    }
    new ProductFeed_Uninstall_Feedback();

endif;