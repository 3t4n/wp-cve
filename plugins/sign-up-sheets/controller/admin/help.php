<?php
/**
 * Admin Page: Help
 */

namespace FDSUS\Controller\Admin;

use WP_Error;
use FDSUS\Id;
use FDSUS\Model\Data;
use FDSUS\Model\Sheet as SheetModel;

class Help
{
    private $data;

    public function __construct()
    {
        $this->data = new Data();
        add_action('admin_menu', array(&$this, 'menu'));

        if (
            isset($_POST['mode'])
            && $_POST['mode'] == 'submitted'
            && isset($_POST['return_path'])
            && $_POST['return_path'] == 'true'
        ) {
            add_action('phpmailer_init', array($this, 'fixReturnPath'));
        }
    }

    /**
     * Menu
     */
    public function menu()
    {
        $caps = $this->data->get_add_caps_array(SheetModel::POST_TYPE);

        add_submenu_page(
            'edit.php?post_type=' . SheetModel::POST_TYPE,
            esc_html__('Sign-up Sheets Help', 'fdsus'),
            esc_html__('Help', 'fdsus'),
            $caps['read_post'],
            Id::PREFIX . '-help',
            array(&$this, 'page')
        );
    }

    /**
     * Page
     */
    public function page()
    {
        $caps = $this->data->get_add_caps_array(SheetModel::POST_TYPE);
        if (!current_user_can($caps['read_post'])) {
            wp_die(esc_html__('You do not have sufficient permissions to access this page.'));
        }

        $mail_result = $this->processEmailTest();

        echo '
            <div class="wrap dls_sus">
                <h1 class="wp-heading-inline">' . esc_html__('Sign-up Sheets', 'fdsus') . (Id::isPro() ? ' <sup class="dls-sus-pro">Pro</sup>' : '') . ' ' . esc_html__('Help', 'fdsus') . '</h1>
        ';

        if (is_wp_error($mail_result)) {
            echo '<div class="error"><p>'
                . implode('</p></div><div class="error"><p>', $mail_result->get_error_messages())
                . '</p></div>';
        } elseif ($mail_result === true) {
            echo '<div class="updated"><p>' . esc_html__('Test email successfully sent by WordPress.', 'fdsus') . '</p></div>';
        }
        ?>

        <h3>Need Help?</h3>
        <ol>
            <li>View the <a href="https://www.fetchdesigns.com/sign-up-sheets-wordpress-plugin/#faq" target="_blank">Frequently Asked Questions</a></li>
            <li>View and post questions on the <a href="https://www.fetchdesigns.com/forums/forum/sign-up-sheets-support/">Support Forum</a></li>
            <li>Didn't find your answer with any of the above options? <a href="https://www.fetchdesigns.com/contact/" target="_blank">Email Fetch Designs</a></li>
        </ol>

        <?php
        $theme = wp_get_theme();
        $all_options = wp_load_alloptions();
        $sus_options = array();
        $sus_options_display['oneline'] = null;
        $sus_options_display['multiline'] = null;
        if (!empty($all_options) && is_array($all_options)) {
            foreach ($all_options as $key => $value) {
                if (strpos($key, 'dls_sus_') !== 0 && strpos($key, 'dlssus_') !== 0) {
                    continue;
                }
                switch ($key) {
                    // Skip sensitive data
                    case 'dls_sus_recaptcha_public_key':
                    case 'dls_sus_recaptcha_private_key':
                        break;
                    // Multi-line
                    case 'dls_sus_email_message':
                    case 'dls_sus_reminder_email_message':
                    case 'dls_sus_removed_email_message':
                    case 'dls_sus_custom_fields':
                    case 'dls_sus_custom_task_fields':
                    case 'dls_sus_roles':
                        $sus_options['multiline'][$key] = $value;
                        $sus_options_display['multiline'] .= "\n# " . str_replace('dls_sus_', '', $key) . PHP_EOL
                            . print_r(maybe_unserialize($value), true) . PHP_EOL;
                        break;
                    // One-line
                    default:
                        $sus_options['oneline'][$key] = $value;
                        $sus_options_display['oneline'] .= str_replace('dlssus_', '', str_replace('dls_sus_', '', $key))
                            . ": $value\n    ";
                }
            }
            reset($all_options);
        }
        $plugins = get_plugins();
        $active_plugins = get_option('active_plugins', array());
        $plugins_display = null;
        if (!empty($plugins) && is_array($plugins)) {
            $pluginCount = 0;
            foreach ($plugins as $pluginPath => $plugin) {
                $pluginCount++;
                if (!in_array($pluginPath, $active_plugins)) {
                    continue;
                }
                $firstPlugin = ($plugins_display === null);
                $plugins_display .= $plugin['Name'] . ': ' . $plugin['Version'] . " <{$plugin['PluginURI']}>";
                if (!$firstPlugin) {
                    $plugins_display .= PHP_EOL . "    ";
                }
            }
            reset($plugins);
        }

        // Output System Information
        ?>

        <h3><?php esc_html_e('System Information', 'fdsus') ?></h3>
        <p><?php echo sprintf('%s <strong><a href="%s">%s</a></strong>',
                esc_html__(
                    'We may request this information to help us troubleshoot your support request. You can now find this under',
                    'fdsus'
                ),
                admin_url('site-health.php?tab=debug'),
                esc_html__('Tools > Site Health > Info', 'fdsus')
            ) ?></p>
        <textarea readonly="readonly" class="dls-sus-system-info" rows="1" onclick="this.focus(); this.select();"><?php esc_html_e('System information has moved to Tools > Site Health > Info', 'fdsus') ?></textarea>

        <?php
        $from = get_option('dls_sus_email_from');
        if (empty($from)) {
            $from = get_bloginfo('admin_email');
        }
        ?>
            <h3><?php esc_html_e('Email Test', 'fdsus') ?></h3>
            <p><?php esc_html_e('Having trouble with emails sending on your site? Use this quick for to test your site with sending emails to different recipients.', 'fdsus') ?></p>
            <p><?php esc_html_e('If the test email is successfully sent by WordPress, but still not being received...', 'fdsus') ?></p>
            <ol>
                <li><?php esc_html_e('Ask the recipient to check their SPAM mailbox or SPAM filters.  Your "From" address may need to be added to their list of safe senders.', 'fdsus') ?></li>
                <li><?php esc_html_e('Check with your host to see if they can trace emails being sent from your site.  It is possible your emails are being delayed or blacklisted by your recipient\'s mail host.', 'fdsus') ?></li>
                <li><?php echo sprintf(
                    /* translators: %s is replaced with a link to an SMTP plugin */
                        esc_html__('Try sending email via SMTP instead by using a plugin like %s.', 'fdsus'),
                        '<a href="https://wordpress.org/plugins/easy-wp-smtp/">Easy WP SMTP</a>'
                    ) ?></li>
                <li><?php echo sprintf(
                    /* translators: %1$s is replaced with a link to Mailgun and %2$s is replaced with a link to the Mailgun WordPress plugin */
                        esc_html__('Try sending email via a 3rd party service like %1$s with the %2$s', 'fdsus'),
                        '<a href="https://www.mailgun.com/">Mailgun</a>',
                        '<a href="https://wordpress.org/plugins/mailgun/">Mailgun for WordPress plugin</a>'
                    ) ?></li>
            </ol>
            <form method="post" action="<?php echo $_SERVER['REQUEST_URI'] ?>" class="dlssus-email-test">
                <fieldset>
                    <p>
                        <label for="from"><?php esc_html_e('From', 'fdsus') ?></label><br />
                        <input type="email" name="from" id="from" value="<?php echo esc_attr($from) ?>" />
                    </p>
                    <p>
                        <label for="recipient"><?php esc_html_e('To', 'fdsus') ?></label><br />
                        <input type="email" name="recipient" id="recipient" value="" />
                    </p>
                    <p>
                        <label><?php esc_html_e('Message', 'fdsus') ?></label><br />
                        <textarea name="message" rows="3"><?php esc_html_e('This is a test email.', 'fdsus') ?></textarea>
                    </p>
                    <p>
                        <input type="checkbox" name="return_path" id="return_path" value="true" checked="checked" />
                        <label for="return_path"><?php esc_html_e('Send bounced messages to "From" address', 'fdsus') ?></label>
                    </p>
                    <input type="hidden" name="mode" value="submitted" />
                    <input type="submit" value="<?php esc_attr_e('Send', 'fdsus') ?>" class="button" />
                </fieldset>
            </form>
        </div><!-- .wrap -->
        <?php
    }

    /**
     * If necessary, process sending the email test message
     *
     * @return WP_Error|bool|null
     */
    private function processEmailTest()
    {
        if (!isset($_POST['mode']) || $_POST['mode'] != 'submitted') {
            return null;
        }

        add_filter('wp_mail_from', array($this, 'getEmailTestFrom'));
        add_filter('wp_mail_from_name', array($this, 'getEmailTestFromName'));
        $mail_result = wp_mail(
            $_POST['recipient'], 'Test Email',
            $_POST['message'] . "\n\n---\nThis is a test message sent from the Sign-up Sheets Help section."
        );
        add_filter('wp_mail_from', array($this, 'getEmailTestFrom'));
        add_filter('wp_mail_from_name', array($this, 'getEmailTestFromName'));

        if (!$mail_result) {
            global $ts_mail_errors;
            global $phpmailer;
            if (!isset($ts_mail_errors)) {
                $ts_mail_errors = array();
            }
            if (isset($phpmailer)) {
                $ts_mail_errors[] = $phpmailer->ErrorInfo;
            }
            return new WP_Error(
                Id::PREFIX . 'test_email_error',
                esc_html__('Error sending email.', 'fdsus')
                . '.. ' . implode(' --- ', $ts_mail_errors)
            );
        }

        return $mail_result;
    }

    /**
     * Get From address for email test
     *
     * @return string
     */
    public function getEmailTestFrom()
    {
        return $_POST['from'];
    }

    /**
     * Get From Name for emails
     *
     * @return string
     */
    public function getEmailTestFromName()
    {
        return wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);
    }

    /**
     * Sets proper email for bouncebacks
     *
     * @param $phpmailer
     */
    public function fixReturnPath($phpmailer)
    {
        $phpmailer->Sender = $phpmailer->From; // use the from email
    }

}
