<?php
/**
 * Created by PhpStorm.
 * User: thanhlam
 * Date: 15/01/2021
 * Time: 22:57
 */

namespace NineKolor\TelegramWC\Classes;


class SettingPage extends \WC_Settings_Page
{
    /**
     * Constructor
     */
    public function __construct()
    {

        $this->id = 'nktgnfw';
        add_filter('woocommerce_settings_tabs_array', array($this, 'add_settings_tab'), 50);
        add_action('woocommerce_settings_' . $this->id, array($this, 'output'));
        add_action('woocommerce_settings_save_' . $this->id, array($this, 'save'));

    }

    public function add_settings_tab($settings_tabs)
    {
        $settings_tabs[$this->id] = __('Telegram Notification', 'nktgnfw');
        return $settings_tabs;
    }

    public function get_settings($section = null)
    {
        $settings = array(
            'section_title_1' => array(
                'name' => __('Settings', 'nktgnfw'),
                'type' => 'title',
                'desc' => $this->renderHelpDescription(),
                'id' => 'wc_settings_tab_nktgnfw_title_1'
            ),
            'token' => array(
                'name' => __('Token', 'nktgnfw'),
                'type' => 'text',
                'id' => 'nktgnfw_setting_token',
                'desc_tip' => true,
                'desc' => __('Type your API Token. Chat @BotFather to get it', 'nktgnfw')
            ),
            'chatid' => array(
                'name' => __('ChatID / GroupID', 'nktgnfw'),
                'type' => 'text',
                'id' => 'nktgnfw_setting_chatid',
                'desc_tip' => true,
                'desc' => __('Type your Chat ID. May be group or user', 'nktgnfw')
            ),
            'sending_after_order_status_changed' => array(
                'name' => __('Order status changed', 'nktgnfw'),
                'type' => 'checkbox',
                'id' => 'nktgnfw_send_after_order_status_changed',
                'desc_tip' => true,
                'desc' => __('Allows sending message after order status changed (Apply below order statuses). If enabled, sending notification for a new order is disabled', 'nktgnfw')
            ),
            'order_statuses' => array(
                'name' => __('Select order statuses', 'nktgnfw'),
                'type' => 'multiselect',
                'id' => 'nktgnfw_order_statuses',
                'options' => wc_get_order_statuses(),
                'class' => 'wc-enhanced-select',
                'desc_tip' => true,
                'desc' => __('Select one or more statuses for which this notification will be sent', 'nktgnfw')
            ),
            'message_template' => array(
                'name' => __('Message Template', 'nktgnfw'),
                'type' => 'textarea',
                'id' => 'nktgnfw_setting_template',
                'class' => 'code',
                'css' => 'max-width:550px;width:100%',
                'default'=>'A new order has been placed at {order_date_created}'.chr(10).'ORDER_ID: <b>#{order_id}</b>'.chr(10).'Products: {products}'.chr(10).'Total: <b>{total}</b>',
                'custom_attributes' => ['rows' => 10],
            ),
            'section_end' => array(
                'type' => 'sectionend',
                'id' => 'wc_settings_tab_nktgnfw_end_section_2'
            ),
        );
        return apply_filters('wc_settings_tab_nktgnfw_settings', $settings, $section);

    }

    public function renderHelpDescription()
    {
        $token_help = wp_kses(__("Just talk to BotFather <a href='https://telegram.im/BotFather' target='_blank'>@BotFather</a> and text <code>/start</code>, then <code>/newbot</code>, finally <code>YourNameBot</code> (Note: your bot name must end with Bot, ex: ChoPluginBot). Once you've created a bot and received your authorization token.", 'nktgnfw'), array('a' => ['href' => 'https://telegram.im/BotFather','target' =>'_blank'], 'code' => []));
        $chatid_help = wp_kses(__("To get Chat ID, just talk to  <a href='https://telegram.im/userinfobot' target='_blank'>@userinfobot</a> and text <code>/start</code>", "nktgnfw"), ['a' => ['href' => 'https://telegram.im/userinfobot','target' =>'_blank'], 'code' => []]);
        return $token_help . chr(10) . $chatid_help;
    }

    public function renderAllowTagsDescription()
    {
        ob_start();
        ?>
        <style>
            .row {
                display: flex;
                flex-wrap: wrap;
                justify-content: space-between;
            }

            .left {
                width: 50%;
            }
            .padding{
                padding: 0 10px;
            }
            @media (max-width: 768px) {
                .left{
                    width: 98%;
                }
            }
            textarea.code {
                background-color: #141414;
                color: #F8F8F8;
                width: 100%;
                font: 12px/normal 'Monaco', 'Menlo', 'Ubuntu Mono', 'Consolas', 'source-code-pro', monospace;
            }
        </style>
        <div class="row">
            <table class="form-table">
                <tbody>
                <tr>
                    <th></th>
                    <td> <button id="nktgnfw_send_test_message" type="button" class="button-primary"><?= __('Send test message','nktgnfw')?></button></td>
                </tr>
                <tr>
                    <th><h4><?= __('Allow tags for Telegram Message', 'nktgnfw') ?></h4></th>
                    <td><textarea class="code wp-editor-area" rows="15" readonly><b>bold</b>&#10;<strong>bold</strong>&#10;<i>italic</i>&#10;<em>italic</em>&#10;<u>underline</u>&#10;<ins>underline</ins>&#10;<s>strikethrough</s>&#10;<strike>strikethrough</strike>&#10;<del>strikethrough</del>&#10;<a href="http://www.domain.com/">inline URL</a>&#10;<code>code</code>&#10;<pre>code block</pre></textarea></td>
                </tr>
                <tr>
                    <th><h4><?= __('Shortcodes for Telegram Message', 'nktgnfw') ?></h4></th>
                    <td><textarea class="code wp-editor-area" rows="15" readonly>{order_id}&#10;{order_date_created}&#10;{order_status}&#10;{products}&#10;{total}&#10;{billing-first_name}&#10;{billing-last_name}&#10;{billing-address_1}&#10;{billing-address_2}&#10;{billing-city}&#10;{billing-state}&#10;{billing-postcode}&#10;{billing-email}&#10;{billing-phone}&#10;{payment_method}&#10;{payment_method_title}&#10;{customer_ip_address}&#10;{customer_user_agent}
                        </textarea></td>
                </tr>
                <tr>
                    <th></th>
                    <td><?= wp_kses(__("Visit <a href='https://choplugins.com' target='_blank'>our website</a> to get more useful plugins", "nktgnfw"), ['a' => ['href' => 'https://choplugins.com','target' =>'_blank']]); ?></td>
                </tr>
                </tbody>
            </table>
        </div>
        <?php
        $content = ob_get_contents();
        ob_clean();
        ob_end_flush();
        return $content;
    }
    public function renderHeaderSettingPage(){
        return '<div id="nktgnfw-header">
			<a href="https://choplugins.com" target="_blank"><img class="nktgnfw-header-logo" src="'.plugin_dir_url(__DIR__).'assets/images/tele_woo.svg" alt="Telegram Notification for Woocommerce"></a>
		    </div>';
    }
    /**
     * Output the settings
     */
    public function output()
    {   echo $this->renderHeaderSettingPage();
        $settings = $this->get_settings();
        \WC_Admin_Settings::output_fields($settings);
        echo $this->renderAllowTagsDescription();
    }

    /**
     * Save settings
     */
    public function save()
    {
        $settings = $this->get_settings();
        \WC_Admin_Settings::save_fields($settings);
    }
}