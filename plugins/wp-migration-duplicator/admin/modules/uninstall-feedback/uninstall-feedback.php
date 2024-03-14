<?php
/**
 * Uninstall Feedback
 *
 * @link       
 * @since 2.5.0     
 *
 * @package  Wp_Migration_Duplicator  
 */
if (!defined('ABSPATH')) {
    exit;
}
class Wp_Migration_Duplicator_Uninstall_Feedback
{
	protected $api_url='';
    protected $current_version=WP_MIGRATION_DUPLICATOR_VERSION;
    protected $auth_key='wtmigrator_uninstall_1234#';
    protected $plugin_id='wtmigrator';
    protected $plugin_file=WT_MGDP_PLUGIN_FILENAME; //plugin main file
    public function __construct()
    {
        $this->api_url='https://feedback.webtoffee.com/wp-json/'.$this->plugin_id.'/v1/uninstall';

        add_action('admin_footer', array($this,'deactivate_scripts'));
        add_action('wp_ajax_'.$this->plugin_id.'_submit_uninstall_reason', array($this,"send_uninstall_reason"));
        add_filter('plugin_action_links_'.plugin_basename($this->plugin_file),array($this,'plugin_action_links'));
    }
    public function plugin_action_links($links) 
	{
		if(array_key_exists('deactivate',$links))
		{
            $links['deactivate']=str_replace('<a', '<a class="'.$this->plugin_id.'-deactivate-link"',$links['deactivate']);
        }
		return $links;
	}
    private function get_uninstall_reasons()
    {

        $reasons = array(
            array(
                'id' => 'used-it-successfully',
                'text' => __('Used it successfully.'),
                'type' => 'reviewhtml',
                'placeholder' => __('Have used it successfully and aint in need of it anymore', 'wp-migration-duplicator')
            ),
            array(
                'id' => 'temporary-deactivation',
                'text' => __('Temporary deactivation.', 'wp-migration-duplicator'),
                'type' => 'reviewhtml',
                'placeholder' => __('', 'wp-migration-duplicator')
            ),
            array(
                'id' => 'import-failed.',
                'text' => __('Import failed.', 'wp-migration-duplicator'),
                'type' => 'notworkinghtml',
                'placeholder' => __('', 'wp-migration-duplicator')
            ),
            array(
                'id' => 'export-failed-timedout',
                'text' => __('Export failed/timed out.', 'wp-migration-duplicator'),
                'type' => 'notworkinghtml',
                'placeholder' => __('', 'wp-migration-duplicator')
            ),
            array(
                'id' => 'could-not-understand',
                'text' => __('I couldn\'t understand how to make it work.', 'wp-migration-duplicator'),
                'type' => 'could-not-understandhtml',
                'placeholder' => __('', 'wp-migration-duplicator')
            ),
            array(
                'id' => 'not-what-i-was-looking-for',
                'text' => __('Not what I was looking for.', 'wp-migration-duplicator'),
                'type' => 'textarea',
                'placeholder' => __('Please tell us more about your requirements.', 'wp-migration-duplicator')
            ),
            array(
                'id' => 'plugin-ui-is-not-user-friendly',
                'text' => __('Plugin UI is not user-friendly.', 'wp-migration-duplicator'),
                'type' => 'textarea',
                'placeholder' => __('Can you please tell us how to improve?', 'wp-migration-duplicator')
            ),
            array(
                'id' => 'other',
                'text' => __('Other', 'wp-migration-duplicator'),
                'type' => 'textarea',
                'placeholder' => __('Could you tell us a bit more?', 'wp-migration-duplicator')
            ),
        );

        return $reasons;
    }

    public function deactivate_scripts()
    {
        global $pagenow;
        if('plugins.php' != $pagenow)
        {
            return;
        }
        $reasons = $this->get_uninstall_reasons();
        ?>
        <div class="<?php echo $this->plugin_id;?>-modal" id="<?php echo $this->plugin_id;?>-modal">
            <div class="<?php echo $this->plugin_id;?>-modal-wrap">
                <div class="<?php echo $this->plugin_id;?>-modal-header">
                    <h3><?php _e('If you have a moment, please let us know why you are deactivating:', 'wp-migration-duplicator'); ?></h3>
                </div>
                <div class="<?php echo $this->plugin_id;?>-modal-body">
                    <ul class="reasons"><?php foreach ($reasons as $reason) { ?>
                            <li data-type="<?php echo esc_attr($reason['type']); ?>" data-placeholder="<?php echo esc_attr($reason['placeholder']); ?>">
                                <label><input type="radio" name="selected-reason" value="<?php echo $reason['id']; ?>"><?php echo $reason['text']; ?></label>
                            </li><?php } ?>
                    </ul>
                </div>
                <div class="<?php echo $this->plugin_id;?>-modal-footer">
                    <a href="#" class="dont-bother-me"><?php _e('I rather wouldn\'t say', 'wp-migration-duplicator'); ?></a>
                    <a class="button-primary" href="https://www.webtoffee.com/support/" target="_blank">
                        <span class="dashicons dashicons-external" style="margin-top:3px;"></span> 
                        <?php _e('Go to support', 'wp-migration-duplicator'); ?></a>
                    <button class="button-primary <?php echo $this->plugin_id;?>-model-submit"><?php _e('Submit & Deactivate', 'wp-migration-duplicator'); ?></button>
                    <button class="button-secondary <?php echo $this->plugin_id;?>-model-cancel"><?php _e('Cancel', 'wp-migration-duplicator'); ?></button>
                </div>
            </div>
        </div>
        <style type="text/css">
            .<?php echo $this->plugin_id;?>-modal {
                position: fixed;
                z-index: 99999;
                top: 0;
                right: 0;
                bottom: 0;
                left: 0;
                background: rgba(0,0,0,0.5);
                display: none;
            }
             .reviewlink{
                    /*margin-left:-12px !important;*/
                    margin-top:0px !important;
                    font-size: 13px;
                }
                .mgdp-reviewlink{
                    margin-left:25px !important;
                    line-height: 22px;
                }
                .mgdp-review-and-deactivate{
                        padding:5px;
                    }
            .<?php echo $this->plugin_id;?>-modal.modal-active {display: block;}
            .<?php echo $this->plugin_id;?>-modal-wrap {
                width: 50%;
                position: relative;
                margin: 10% auto;
                background: #fff;
            }
            .<?php echo $this->plugin_id;?>-modal-header {
                border-bottom: 1px solid #eee;
                padding: 8px 20px;
            }
            .<?php echo $this->plugin_id;?>-modal-header h3 {
                line-height: 150%;
                margin: 0;
            }
            .<?php echo $this->plugin_id;?>-modal-body {padding: 5px 20px 20px 20px;}
            .<?php echo $this->plugin_id;?>-modal-body .input-text,.<?php echo $this->plugin_id;?>-modal-body textarea {width:75%;}
            .<?php echo $this->plugin_id;?>-modal-body .reason-input {
                margin-top: 5px;
                margin-left: 20px;
            }
            .<?php echo $this->plugin_id;?>-modal-footer {
                border-top: 1px solid #eee;
                padding: 12px 20px;
                text-align: right;
            }
        </style>
        <script type="text/javascript">
            (function ($) {
                $(function () {
                    var plugin_id='<?php echo $this->plugin_id;?>';
                    var modal = $('#'+plugin_id+'-modal');
                    var deactivateLink = '';
                    $('a.'+plugin_id+'-deactivate-link').click(function (e) {
                        e.preventDefault();
                        modal.addClass('modal-active');
                        deactivateLink = $(this).attr('href');
                        modal.find('a.dont-bother-me').attr('href', deactivateLink).css('float', 'left');
                    });
                    $('#'+plugin_id+'-modal').on('click', 'a.mgdp-review-and-deactivate', function (e) {
                        e.preventDefault();
                        window.open("https://wordpress.org/support/plugin/wp-migration-duplicator/reviews/#new-post");
                        window.location.href = deactivateLink;
                    });
                    modal.on('click', 'button.'+plugin_id+'-model-cancel', function (e) {
                        e.preventDefault();
                        modal.removeClass('modal-active');
                    });
                    modal.on('click', 'input[type="radio"]', function () {
                        var parent = $(this).parents('li:first');
                        modal.find('.reason-input').remove();
                        var inputType = parent.data('type');
                                inputPlaceholder = parent.data('placeholder');
                                if ('reviewhtml' === inputType && $('.reviewlink').length == 0) {
                                    var reasonInputHtml = '<div class="reason-input reviewlink"><a href="#" target="_blank" class="mgdp-review-and-deactivate"><?php _e('Deactivate and leave a review', 'wp-migration-duplicator'); ?> <span class="wt-userimport-rating-link"> &#9733;&#9733;&#9733;&#9733;&#9733; </span></a></div>';
                                } else if ('notworkinghtml' === inputType && $('.mgdp-reviewlink').length == 0) {
                                    var reasonInputHtml = '<div class="reason-input mgdp-reviewlink"><?php echo sprintf(wp_kses(__('To identify the potential cause of failure, check the import logs under the menu: WordPress Migration > Logs. For further assistance, contact us via <a href="%s" target="_blank">support</a>.', 'wp-migration-duplicator'), array('a' => array('href' => array(), 'target' => array()))), esc_url('https://wordpress.org/support/plugin/wp-migration-duplicator/')); ?> </div>';
                                }else if ('could-not-understandhtml' === inputType && $('.mgdp-reviewlink').length == 0) {
                                    var reasonInputHtml = '<div class="reason-input mgdp-reviewlink"><?php _e('No worries! ', 'wp-migration-duplicator'); ?><br><?php echo sprintf(wp_kses(__('Please read the <a href="%s" target="_blank">user guide</a> for a basic understanding.', 'wp-migration-duplicator'), array('a' => array('href' => array(), 'target' => array()))), esc_url('https://www.webtoffee.com/wordpress-backup-migration-user-guide/')); ?><br><?php echo sprintf(wp_kses(__('For further assistance, contact us via <a href="%s" target="_blank">support</a>.', 'wp-migration-duplicator'), array('a' => array('href' => array(), 'target' => array()))), esc_url('https://wordpress.org/support/plugin/wp-migration-duplicator/')); ?> </div>';
                                } else if ('textarea' === inputType){
                                reasonInputHtml = '<div class="reason-input">' + (('text' === inputType) ? '<input type="text" class="input-text" size="40" />' : '<textarea rows="5" cols="45"></textarea>') + '</div>';
                                }
                        if (inputType !== '') {
                            parent.append($(reasonInputHtml));
                            parent.find('input, textarea').attr('placeholder', inputPlaceholder).focus();
                        }
                    });

                    modal.on('click', 'button.'+plugin_id+'-model-submit', function (e) {
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
                                action: plugin_id+'_submit_uninstall_reason',
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

    public function send_uninstall_reason()
    {
        global $wpdb;
        if (!isset($_POST['reason_id'])) {
            wp_send_json_error();
        }
        //$current_user = wp_get_current_user();
        $data = array(
            'reason_id' => sanitize_text_field($_POST['reason_id']),
            'plugin' =>$this->plugin_id,
            'auth' =>$this->auth_key,
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
            $this->plugin_id.'_version' =>$this->current_version,
        );
        // Write an action/hook here in webtoffe to recieve the data
        $resp = wp_remote_post($this->api_url, array(
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
new Wp_Migration_Duplicator_Uninstall_Feedback();