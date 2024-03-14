<?php

if (!defined('ABSPATH')) { exit; }

class gdmaq_admin_settings {
    private $settings;

    public function __construct() {
        $this->init();
    }

    public function get($panel, $group = '') {
        if ($group == '') {
            return $this->settings[$panel];
        } else {
            return $this->settings[$panel][$group];
        }
    }

	public function settings($panel) : array {
		$list = array();

		foreach ($this->settings[$panel] as $obj) {
			if (isset($obj['settings'])) {
				foreach ( $obj[ 'settings' ] as $o ) {
					$list[] = $o;
				}
			}
		}

		return $list;
	}

    private function init() {
        $phpmailer_engine = gdmaq_settings()->get('mode', 'engine_phpmailer');

        $settings = apply_filters('gdmaq_admin_internal_settings', array(
            'basic' => array(
                'intercept' => array('name' => __("Intercept WP Mail", "gd-mail-queue"), 'settings' => array(
                    new d4pSettingElement('settings', 'intercept', __("Intercept", "gd-mail-queue"), __("This is the main entry point for the plugin, it has to be enabled so that the plugin can automatically handle all wp_mail requests and decide if they need to be queued and turned into HTML. For log to work, this option is also required.", "gd-mail-queue").' '.__("If you still want to use HTMLfy and Log features, but not the queue, this option must be enabled, and you can disable adding to queue with the option bellow.", "gd-mail-queue"), d4pSettingType::BOOLEAN, gdmaq_settings()->get('intercept', 'settings'))
                )),
                'htmlfy' => array('name' => __("Turn Plain text emails into HTML", "gd-mail-queue"), 'settings' => array(
                    new d4pSettingElement('settings', 'htmlfy', __("HTMLfy", "gd-mail-queue"), __("Emails that are only plain text, will be turned into the HTML emails, where all plain text content is wrapped in the email ready HTML.", "gd-mail-queue"), d4pSettingType::BOOLEAN, gdmaq_settings()->get('htmlfy', 'settings')),
	                new d4pSettingElement('htmlfy', 'preprocess', __("HTML tags handling", "gd-mail-queue"), __("The goal of this process is to take plain text email content and turn it into HTML. Plain text should not contain HTML tags, but if it does, it may contain malicious SCRIPT tags, and it is important to process it, before use.", "gd-mail-queue"), d4pSettingType::SELECT, gdmaq_settings()->get('preprocess', 'htmlfy'), 'array', $this->get_htmlfy_preprocess())
                )),
                'engine' => array('name' => __("Mail sending Engine", "gd-mail-queue"), 'settings' => array(
                    new d4pSettingElement('settings', 'engine', __("Engine", "gd-mail-queue"), __("The engine is used to send emails. When the queue sends emails, it sends them through the selected engine. Only one engine can be loaded at the time.", "gd-mail-queue"), d4pSettingType::SELECT, gdmaq_settings()->get('engine', 'settings'), 'array', gdmaq()->get_list_of_engines())
                )),
                'queue' => array('name' => __("Add emails into the Queue", "gd-mail-queue"), 'settings' => array(
                    new d4pSettingElement('settings', 'q', __("Queue", "gd-mail-queue"), __("This option has to be enabled for plugin to test if the email is eligible for the queue based on the next option.", "gd-mail-queue"), d4pSettingType::BOOLEAN, gdmaq_settings()->get('q', 'settings')),
                    new d4pSettingElement('settings', 'queue', __("What to Queue", "gd-mail-queue"), __("Based on the number of email recipients, the plugin can add the email into the queue, and for each recipient create an individual email copy. CC and BCC recipients will be added as separate emails, each one receiving own email with the original content.", "gd-mail-queue"), d4pSettingType::SELECT, gdmaq_settings()->get('queue', 'settings'), 'array', $this->get_queue_methods())
                ))
            ),
            'extra' => array(
                'from' => array('name' => __("Change Email From", "gd-mail-queue"), 'settings' => array(
                    new d4pSettingElement('settings', 'from', __("Change From", "gd-mail-queue"), __("This will change default FROM email and name for all emails passing through wp_mail.", "gd-mail-queue"), d4pSettingType::BOOLEAN, gdmaq_settings()->get('from', 'settings')),
                    new d4pSettingElement('', '', '', '', d4pSettingType::HR),
                    new d4pSettingElement('settings', 'from_email', __("From Email", "gd-mail-queue"), '', d4pSettingType::EMAIL, gdmaq_settings()->get('from_email', 'settings')),
                    new d4pSettingElement('settings', 'from_name', __("From Name", "gd-mail-queue"), '', d4pSettingType::TEXT, gdmaq_settings()->get('from_name', 'settings'))
                )),
                'reply' => array('name' => __("Change Email Reply", "gd-mail-queue"), 'settings' => array(
                    new d4pSettingElement('settings', 'reply', __("Change Reply", "gd-mail-queue"), __("This will change default REPLY TO email and name for all emails passing through wp_mail.", "gd-mail-queue"), d4pSettingType::BOOLEAN, gdmaq_settings()->get('reply', 'settings')),
                    new d4pSettingElement('', '', '', '', d4pSettingType::HR),
                    new d4pSettingElement('settings', 'reply_email', __("Reply Email", "gd-mail-queue"), '', d4pSettingType::EMAIL, gdmaq_settings()->get('reply_email', 'settings')),
                    new d4pSettingElement('settings', 'reply_name', __("Reply Name", "gd-mail-queue"), '', d4pSettingType::TEXT, gdmaq_settings()->get('reply_name', 'settings'))
                ))
            ),
            'misc' => array(
                'misc_htmlfy' => array('name' => __("HTML Emails", "gd-mail-queue"), 'settings' => array(
                    new d4pSettingElement('', '', __("Important", "gd-mail-queue"), __("These options will be used during the first stage of the HTMLfy process. If that is disabled, plugin will not use these options.", "gd-mail-queue"), d4pSettingType::INFO),
                    new d4pSettingElement('', '', '', '', d4pSettingType::HR),
                    new d4pSettingElement('settings', 'plain_text_check', __("Plain text check", "gd-mail-queue"), __("Detecting if the email is plain text only can be a problem if the PHPMailer object is malformed due to the wrong way of setting the email type. This option controls how to check for the plain text only emails.", "gd-mail-queue"), d4pSettingType::SELECT, gdmaq_settings()->get('plain_text_check', 'settings'), 'array', $this->get_htmlfy_plain_text_detection()),
                    new d4pSettingElement('settings', 'fix_content_type', __("Fix content type", "gd-mail-queue"), __("If the email is detected as HTML, but content type is wrong, plugin can attempt to fix that and set proper content type.", "gd-mail-queue"), d4pSettingType::BOOLEAN, gdmaq_settings()->get('fix_content_type', 'settings'))
                ))
            ),
            'buddypress' => array(
                'buddypress_settings' => array('name' => __("Email sending control", "gd-mail-queue"), 'settings' => array(
                    new d4pSettingElement('external', 'buddypress_force_wp_mail', __("Force using WP_MAIL()", "gd-mail-queue"), __("By default, BuddyPress uses own mailing system (based on PHPMailer, and compatible with GD Mail Queue) and it will send HTML emails using own templates. If you enable this option, BuddyPress will send only plain text emails, and the content can be wrapped in HTML by GD Mail Queue plugin.", "gd-mail-queue"), d4pSettingType::BOOLEAN, gdmaq_settings()->get('buddypress_force_wp_mail', 'external'))
                ))
            ),
            'engine_phpmailer' => array(
                'phpmailer_mode' => array('name' => __("PHPMailer Mode", "gd-mail-queue"), 'settings' => array(
                    new d4pSettingElement('engine_phpmailer', 'mode', __("Mode", "gd-mail-queue"), __("By default, PHPMailer uses PHP mail function. If you configure SMTP server, you can choose it instead.", "gd-mail-queue"), d4pSettingType::SELECT, gdmaq_settings()->get('mode', 'engine_phpmailer'), 'array', $this->get_phpmailer_mode())
                )),
                'service_smtp' => array('label' => __("Custom SMTP Server", "gd-mail-queue"),
                    'args' => array('hidden' => $phpmailer_engine != 'smtp'), 'type' => 'separator'),
                'service_smtp_server' => array('name' => __("SMTP Server", "gd-mail-queue"),
                    'args' => array('hidden' => $phpmailer_engine != 'smtp'),
                    'kb' => array('label' => __("KB", "gd-mail-queue"), 'url' => 'phpmailer-service-custom-smtp'), 'settings' => array(
                        new d4pSettingElement('service_smtp', 'host', __("Host", "gd-mail-queue"), __("Consult the documentation for the SMTP server you want to use to get valid connection information. It is advisable to use SSL or TLS encryption to connect to SMTP. Most servers require authentication.", "gd-mail-queue"), d4pSettingType::TEXT, gdmaq_settings()->get('host', 'service_smtp')),
                        new d4pSettingElement('service_smtp', 'port', __("Port", "gd-mail-queue"), __("Depending on the encryption used, port will be different.", "gd-mail-queue").' '.__("Different companies can have different ports, and they don't always need to be commonly used ports listed below. Make sure to check for exact port number in relation to the encryption for your SMTP server.", "gd-mail-queue").'<br/>'.$this->render_ports(), d4pSettingType::ABSINT, gdmaq_settings()->get('port', 'service_smtp')),
                        new d4pSettingElement('service_smtp', 'encryption', __("Encryption", "gd-mail-queue"), __("Some servers support both encryption methods, but you need to test which one will work.", "gd-mail-queue"), d4pSettingType::SELECT, gdmaq_settings()->get('encryption', 'service_smtp'), 'array', $this->get_phpmailer_encryption())
                )),
                'service_smtp_auth' => array('name' => __("Server Authentication", "gd-mail-queue"),
                    'args' => array('hidden' => $phpmailer_engine != 'smtp'), 'settings' => array(
                        new d4pSettingElement('service_smtp', 'auth', __("Authentication", "gd-mail-queue"), __("The plugin stores username and password encrypted in the database. If your website is hacked, and your database is exposed, the encrypted values are safe. But, if your website files are exposed and the attacker can use this plugin on your encrypted data, it will be able to read them. There is no foolproof protection of any data once the website data gets stolen.", "gd-mail-queue"), d4pSettingType::BOOLEAN, gdmaq_settings()->get('auth', 'service_smtp')),
                        new d4pSettingElement('service_smtp', 'username', __("Username", "gd-mail-queue"), '', d4pSettingType::TEXT, gdmaq_settings()->get('username', 'service_smtp')),
                        new d4pSettingElement('service_smtp', 'password', __("Password", "gd-mail-queue"), '', d4pSettingType::PASSWORD, gdmaq_settings()->get('password', 'service_smtp'), '', '', array('autocomplete' => 'new-password'))
                ))
            ),
            'cleanup' => array(
                'cleanup_queue' => array('name' => __("Queue Cleanup", "gd-mail-queue"), 'settings' => array(
                    new d4pSettingElement('cleanup', 'queue_active', __("Status", "gd-mail-queue"), __("If enabled, plugin will run cleanup process once a day, and remove entries from the Queue database table based on the cleanup criteria set on this page.", "gd-mail-queue"), d4pSettingType::BOOLEAN, gdmaq_settings()->get('queue_active', 'cleanup')),
                    new d4pSettingElement('', '', '', '', d4pSettingType::HR),
                    new d4pSettingElement('cleanup', 'queue_scope', __("What to delete", "gd-mail-queue"), __("Select what queue entries will be removed.", "gd-mail-queue"), d4pSettingType::SELECT, gdmaq_settings()->get('queue_scope', 'cleanup'), 'array', $this->get_cleanup_scope()),
                    new d4pSettingElement('cleanup', 'queue_days', __("Older than", "gd-mail-queue"), __("Only emails older then number of days specified in this option will be considered for cleanup.", "gd-mail-queue"), d4pSettingType::ABSINT, gdmaq_settings()->get('queue_days', 'cleanup'), '', '', array('label_unit' => __("Days", "gd-mail-queue")))
                )),
                'cleanup_log' => array('name' => __("Log Cleanup", "gd-mail-queue"), 'settings' => array(
                    new d4pSettingElement('cleanup', 'log_active', __("Status", "gd-mail-queue"), __("If enabled, plugin will run cleanup process once a day, and remove entries from the Log database tables based on the cleanup criteria set on this page.", "gd-mail-queue"), d4pSettingType::BOOLEAN, gdmaq_settings()->get('log_active', 'cleanup')),
                    new d4pSettingElement('', '', '', '', d4pSettingType::HR),
                    new d4pSettingElement('cleanup', 'log_days', __("Older than", "gd-mail-queue"), __("Only emails older then number of days specified in this option will be considered for cleanup.", "gd-mail-queue"), d4pSettingType::ABSINT, gdmaq_settings()->get('log_days', 'cleanup'), '', '', array('label_unit' => __("Days", "gd-mail-queue")))
                ))
            ),
            'pause' => array(
                'pause_emails' => array('name' => __("Emails Control", "gd-mail-queue"), 'settings' => array(
                    new d4pSettingElement('core', 'email_pause', __("Pause Emails", "gd-mail-queue"), __("If enabled, this option will prevent wp_mail from sending emails. If the queue intercepts emails, these emails will still go into queue.", "gd-mail-queue").' '.sprintf(__("The queue can also be paused using the filter: '%s'.", "gd-mail-queue"), 'gdmaq_queue_paused'), d4pSettingType::BOOLEAN, gdmaq_settings()->get('email_pause', 'core'))
                )),
                'pause_queue' => array('name' => __("Queue Control", "gd-mail-queue"), 'settings' => array(
                    new d4pSettingElement('core', 'queue_pause', __("Pause Queue", "gd-mail-queue"), __("If enabled, this option will remove the CRON job for the queue processing, and the queue will be stopped. Emails will still get into the queue, but they are not going to be sent.", "gd-mail-queue").' '.sprintf(__("The queue can also be paused using the filter: '%s'.", "gd-mail-queue"), 'gdmaq_queue_paused'), d4pSettingType::BOOLEAN, gdmaq_settings()->get('queue_pause', 'core'))
                ))
            ),
            'log' => array(
                'log_basic' => array('name' => __("Log emails", "gd-mail-queue"), 'settings' => array(
                    new d4pSettingElement('log', 'active', __("Status", "gd-mail-queue"), __("Log emails into database.", "gd-mail-queue"), d4pSettingType::BOOLEAN, gdmaq_settings()->get('active', 'log'))
                )),
                'log_scope' => array('name' => __("What to log", "gd-mail-queue"), 'settings' => array(
                    new d4pSettingElement('', '', __("Standard Emails", "gd-mail-queue"), '', d4pSettingType::HR),
                    new d4pSettingElement('log', 'mail', __("Status", "gd-mail-queue"), __("Log each email that passess through 'wp_mail' function.", "gd-mail-queue"), d4pSettingType::BOOLEAN, gdmaq_settings()->get('mail', 'log')),
                    new d4pSettingElement('log', 'mail_if_not_queue', __("Only if not queued", "gd-mail-queue"), __("Log emails only if they are not queued.", "gd-mail-queue"), d4pSettingType::BOOLEAN, gdmaq_settings()->get('mail_if_not_queue', 'log')),
                    new d4pSettingElement('', '', __("Queue Emails", "gd-mail-queue"), '', d4pSettingType::HR),
                    new d4pSettingElement('log', 'queue', __("Status", "gd-mail-queue"), __("Log each email that is send by the plugin Queue processor.", "gd-mail-queue"), d4pSettingType::BOOLEAN, gdmaq_settings()->get('queue', 'log'))
                )),
                'log_extra' => array('name' => __("Additional settings", "gd-mail-queue"), 'settings' => array(
                    new d4pSettingElement('log', 'store_smtp_password', __("Store SMTP Password", "gd-mail-queue"), __("If SMTP is used to send emails, plugin logs all SMTP parameters of the connection, including the password if this option is enabled.", "gd-mail-queue"), d4pSettingType::BOOLEAN, gdmaq_settings()->get('store_smtp_password', 'log'))
                )),
                'log_preview' => array('name' => __("Email preview dialog", "gd-mail-queue"), 'settings' => array(
                    new d4pSettingElement('log', 'preview_html_disable_links', __("Disable HTML content links", "gd-mail-queue"), __("When viewing email from the log panel, parse HTML email content and replace URL in all A tags with hash character. This can cause problems if the HTML is malformed or invalid.", "gd-mail-queue"), d4pSettingType::BOOLEAN, gdmaq_settings()->get('preview_html_disable_links', 'log'))
                )),
                'log_actions' => array('name' => __("Additional log actions", "gd-mail-queue"), 'settings' => array(
                    new d4pSettingElement('log', 'action_retry', __("Retry", "gd-mail-queue"), __("The action will be displayed for all emails that have failed to send, and it will add new Bulk action. Using this action will effectively add the failed email back into queue to retry sending it. Failed email remains in the database, but, the flag will be changed to 'retry'.", "gd-mail-queue"), d4pSettingType::BOOLEAN, gdmaq_settings()->get('action_retry', 'log'))
                ))
            ),
            'queue_override' => array(
                'from' => array('name' => __("Change Email From", "gd-mail-queue"), 'settings' => array(
                    new d4pSettingElement('queue', 'from', __("Change From", "gd-mail-queue"), __("Emails sent through Queue can retain the default from, or you can override it, and all emails going though Queue can have different from.", "gd-mail-queue"), d4pSettingType::BOOLEAN, gdmaq_settings()->get('from', 'queue')),
                    new d4pSettingElement('', '', '', '', d4pSettingType::HR),
                    new d4pSettingElement('queue', 'from_email', __("From Email", "gd-mail-queue"), '', d4pSettingType::EMAIL, gdmaq_settings()->get('from_email', 'queue')),
                    new d4pSettingElement('queue', 'from_name', __("From Name", "gd-mail-queue"), '', d4pSettingType::TEXT, gdmaq_settings()->get('from_name', 'queue'))
                )),
                'reply' => array('name' => __("Change Email Reply", "gd-mail-queue"), 'settings' => array(
                    new d4pSettingElement('queue', 'reply', __("Change Reply", "gd-mail-queue"), __("Emails sent through Queue can retain the default reply, or you can override it, and all emails going though Queue can have different reply.", "gd-mail-queue"), d4pSettingType::BOOLEAN, gdmaq_settings()->get('reply', 'queue')),
                    new d4pSettingElement('', '', '', '', d4pSettingType::HR),
                    new d4pSettingElement('queue', 'reply_email', __("Reply Email", "gd-mail-queue"), '', d4pSettingType::EMAIL, gdmaq_settings()->get('reply_email', 'queue')),
                    new d4pSettingElement('queue', 'reply_name', __("Reply Name", "gd-mail-queue"), '', d4pSettingType::TEXT, gdmaq_settings()->get('reply_name', 'queue'))
                )),
                'sender' => array('name' => __("Change Email Sender", "gd-mail-queue"), 'settings' => array(
                    new d4pSettingElement('queue', 'sender', __("Change Sender", "gd-mail-queue"), __("Emails sent through Queue can retain the default sender, or you can override it, and all emails going though Queue can have different sender.", "gd-mail-queue"), d4pSettingType::BOOLEAN, gdmaq_settings()->get('sender', 'queue')),
                    new d4pSettingElement('', '', '', '', d4pSettingType::HR),
                    new d4pSettingElement('queue', 'sender_email', __("Sender Email", "gd-mail-queue"), '', d4pSettingType::EMAIL, gdmaq_settings()->get('sender_email', 'queue'))
                ))
            ),
            'queue' => array(
                'cron' => array('name' => __("Queue CRON Job", "gd-mail-queue"), 'settings' => array(
                    new d4pSettingElement('', '', __("Information", "gd-mail-queue"), __("Queued emails are stored in the database, and they are sent in the background, using the WordPress CRON job.", "gd-mail-queue"), d4pSettingType::INFO),
                    new d4pSettingElement('', '', '', '', d4pSettingType::HR),
                    new d4pSettingElement('queue', 'cron', __("CRON Job Interval", "gd-mail-queue"), __("The interval between the CRON job repeating. Setting this value to under 5 minutes is not recommended.", "gd-mail-queue"), d4pSettingType::ABSINT, gdmaq_settings()->get('cron', 'queue'), '', '', array('label_unit' => __("Minutes", "gd-mail-queue"))),
                    new d4pSettingElement('queue', 'method', __("Processing Method", "gd-mail-queue"), '', d4pSettingType::SELECT, gdmaq_settings()->get('method', 'queue'), 'array', $this->get_queue_method()),
                )),
                'classic' => array('name' => __("Classic Queue Processing", "gd-mail-queue"), 'settings' => array(
	                new d4pSettingElement('queue', 'timeout', __("Maximum Job Length", "gd-mail-queue"), __("Limit the length of each Cron Job. This should typically be lower than the PHP script timeout on your server.", "gd-mail-queue").' '.sprintf(__("Current PHP execution time is %s seconds."), '<strong>'.ini_get('max_execution_time').'</strong>'), d4pSettingType::ABSINT, gdmaq_settings()->get('timeout', 'queue'), '', '', array('label_unit' => __("Seconds", "gd-mail-queue"))),
                    new d4pSettingElement('queue', 'limit', __("Emails per Job", "gd-mail-queue"), __("A number of emails to send in each Job. This value will depend on your website, and if you need to send a lot of emails, you need to experiment to get this value correctly. Most shared hostings should handle up to 100 emails. If the timeout limit is not reached, and if there is enough time, the plugin can attempt to send another batch of emails.", "gd-mail-queue"), d4pSettingType::ABSINT, gdmaq_settings()->get('limit', 'queue'), '', '', array('label_unit' => __("Emails", "gd-mail-queue"))),
                    new d4pSettingElement('queue', 'limit_flex', __("Flexible Job", "gd-mail-queue"), __("Plugin measures queue process, and it can happen that sending a specified number of emails takes less time then the timeout. If that is the case, and this option is active, the plugin will attempt to send one more batch of emails during the same CRON execution. If you need to stick to the limited emails sending imposed by your host, it is best to leave this option disabled.", "gd-mail-queue"), d4pSettingType::BOOLEAN, gdmaq_settings()->get('limit_flex', 'queue'))
                )),
                'sleep' => array('name' => __("Sleep periods", "gd-mail-queue"), 'settings' => array(
                    new d4pSettingElement('queue', 'sleep_batch', __("After queue batch", "gd-mail-queue"), __("If sending more then one batch of emails during same session, this is the time for process to sleep between two batches.", "gd-mail-queue"), d4pSettingType::ABSINT, gdmaq_settings()->get('sleep_batch', 'queue'), '', '', array('label_unit' => __("Microseconds", "gd-mail-queue"))),
                    new d4pSettingElement('queue', 'sleep_single', __("After each email", "gd-mail-queue"), __("This is the time for process to sleep between sending of two emails.", "gd-mail-queue"), d4pSettingType::ABSINT, gdmaq_settings()->get('sleep_single', 'queue'), '', '', array('label_unit' => __("Microseconds", "gd-mail-queue")))
                )),
                'errors' => array('name' => __("Error handling", "gd-mail-queue"), 'settings' => array(
                    new d4pSettingElement('queue', 'requeue', __("Queue waiting emails", "gd-mail-queue"), __("During queue processing, plugin locks number of queued emails for sending. If during the queue sending, server experiences errors that break the execution, anf the locked emails will remain locked, and will not be sent. This option will take these locked emails, and will change their status back to active and ready for sending. To avoid various issues, only emails that have been locked for 2 or more queue cycles will be re-queued.", "gd-mail-queue"), d4pSettingType::BOOLEAN, gdmaq_settings()->get('requeue', 'queue'))
                )),
                'advanced' => array('name' => __("Advanced", "gd-mail-queue"), 'settings' => array(
                    new d4pSettingElement('queue', 'header', __("Add Processor Header", "gd-mail-queue"), __("Emails sent through Queue will get the 'X-Mailer-Processor' header with plugin name and version as a confirmation that it has been sent through the queue.", "gd-mail-queue"), d4pSettingType::BOOLEAN, gdmaq_settings()->get('header', 'queue'))
                ))
            ),
            'htmlfy' => array(
                'htmlfy_what' => array('name' => __("HTML Template", "gd-mail-queue"), 'settings' => array(
                    new d4pSettingElement('htmlfy', 'replace', __("What to use", "gd-mail-queue"), __("Select what you want to use to turn plain text into HTML email.", "gd-mail-queue"), d4pSettingType::SELECT, gdmaq_settings()->get('replace', 'htmlfy'), 'array', $this->get_htmlfy_replace())
                )),
                'htmlfy_template' => array('name' => __("Built-in HTML Templates", "gd-mail-queue"), 'settings' => array(
                    new d4pSettingElement('htmlfy', 'template', __("Template", "gd-mail-queue"), __("Plugin includes some default templates that are email valid and tested with various email clients.", "gd-mail-queue"), d4pSettingType::SELECT, gdmaq_settings()->get('template', 'htmlfy'), 'array', $this->get_htmlfy_templates())
                ))
            ),
            'htmlparts' => array(
                'part_preheader' => array('name' => __("Preheader", "gd-mail-queue"), 'settings' => array(
                    new d4pSettingElement('htmlfy', 'preheader', __("Content", "gd-mail-queue"), __("Preheader is short and hidden text added on top of emails, usually used by email clients to enhance email listing.", "gd-mail-queue"), d4pSettingType::SELECT, gdmaq_settings()->get('preheader', 'htmlfy'), 'array', $this->get_htmlfy_preheader()),
                    new d4pSettingElement('htmlfy', 'preheader_limit', __("Limit", "gd-mail-queue"), __("Preheader should be between 50 and 80 characters in lenght. But, some clients may show only 20 to 30 characters.", "gd-mail-queue"), d4pSettingType::ABSINT, gdmaq_settings()->get('preheader_limit', 'htmlfy'), '', '', array('label_unit' => __("Characters", "gd-mail-queue")))
                )),
                'part_header' => array('name' => __("Header", "gd-mail-queue"), 'settings' => array(
                    new d4pSettingElement('htmlfy', 'header', __("Header Template", "gd-mail-queue"), __("HTML for the Header tag in the template.", "gd-mail-queue").$this->render_toggle_table(__("Allowed Template Tags", "gd-mail-queue"), $this->get_shared_template_tags()), d4pSettingType::CODE, gdmaq_settings()->get('header', 'htmlfy'))
                )),
                'part_footer' => array('name' => __("Footer", "gd-mail-queue"), 'settings' => array(
                    new d4pSettingElement('htmlfy', 'footer', __("Footer Template", "gd-mail-queue"), __("HTML for the Footer tag in the template.", "gd-mail-queue").$this->render_toggle_table(__("Allowed Template Tags", "gd-mail-queue"), $this->get_shared_template_tags()), d4pSettingType::CODE, gdmaq_settings()->get('footer', 'htmlfy'))
                ))
            )
        ));

        if (gdmaq()->has_additional_templates()) {
            $settings['htmlfy']['template_additional'] = array('name' => __("Additional HTML Templates", "gd-mail-queue"), 'settings' => array(
                new d4pSettingElement('htmlfy', 'additional', __("Template", "gd-mail-queue"), __("You can register additional templates. Open Help tab to find more information about it, including available special content tags you can use to make the templates.", "gd-mail-queue"), d4pSettingType::SELECT, gdmaq_settings()->get('additional', 'htmlfy'), 'array', $this->get_htmlfy_additional_templates())
            ));
        }

        $this->settings = apply_filters('gdmaq_admin_internal_settings', $settings);
    }

    private function get_phpmailer_mode() : array {
        return array(
            'mail' => __("PHP Mail Function", "gd-mail-queue"),
            'smtp' => __("Custom SMTP Server", "gd-mail-queue")
        );
    }

    private function get_phpmailer_encryption() : array {
        return array(
            '' => __("None", "gd-mail-queue"),
            'ssl' => __("SSL", "gd-mail-queue"),
            'tls' => __("TLS", "gd-mail-queue")
        );
    }

    private function get_queue_methods() : array {
        return array(
            'all' => __("All emails will be added to queue", "gd-mail-queue"),
            'cc' => __("Emails with one or more recepient in CC field", "gd-mail-queue"),
            'bcc' => __("Emails with one or more recepient in BCC field", "gd-mail-queue"),
            'ccbcc' => __("Emails with one or more recepient in CC or BCC field", "gd-mail-queue")
        );
    }

	private function get_htmlfy_preprocess() : array {
		return array(
			'kses_post' => __("Allow post editor set of HTML tags", "gd-mail-queue"),
			'kses_basic' => __("Allow basic HTML tags", "gd-mail-queue"),
			'kses_expanded' => __("Allow expanded set of HTML tags", "gd-mail-queue"),
			'strip_tags' => __("Strip all HTML tags", "gd-mail-queue")
		);
	}

    private function get_htmlfy_replace() : array {
        $list = array(
            'template' => __("Built-in HTML Templates", "gd-mail-queue")
        );

        if (gdmaq()->has_additional_templates()) {
            $list['additional'] = __("Additional HTML Templates", "gd-mail-queue");
        }

        return $list;
    }

    private function get_htmlfy_plain_text_detection() : array {
        return array(
            'tags' => __("Check for common HTML tags and Content Type", "gd-mail-queue"),
            'type' => __("Check Content Type only", "gd-mail-queue")
        );
    }

    private function get_htmlfy_additional_templates() : array {
        return gdmaq()->get_additional_templates_list();
    }

    private function get_htmlfy_templates() : array {
        return array(
            'clean-basic' => __("Clean template with content and footer area", "gd-mail-queue"),
            'clean-header' => __("Clean template with header, content and footer area", "gd-mail-queue")
        );
    }

    private function get_htmlfy_preheader() : array {
        return array(
            'none' => __("None", "gd-mail-queue"),
            'subject' => __("From Subject", "gd-mail-queue"),
            'content' => __("From Content", "gd-mail-queue")
        );
    }

    public function get_queue_method() : array {
        return array(
            'classic' => __("Classic", "gd-mail-queue")
        );
    }

    private function get_shared_template_tags() : array {
        return gdmaq_admin()->get_shared_template_tags();
    }

    public function get_cleanup_scope() : array {
        return array(
            'sent' => __("All successfully sent emails", "gd-mail-queue"),
            'sent-failed' => __("All successfully sent and failed emails", "gd-mail-queue")
        );
    }

    private function render_toggle_table($title, $elements) : string {
        $render = '<div class="d4p-section-toggle d4p-free-height">';
        $render.= '<div class="d4p-toggle-title"><i class="fa fa-fw fa-caret-down"></i> '.$title.'</div>';
        $render.= '<div class="d4p-toggle-content" style="display: none;">';
        $render.= '<table class="form-table" style="width: 100%;"><tbody>';

        foreach ($elements as $tag => $label) {
            $render.= '<tr><th>'.$label.'</th><td><code>'.$tag.'</code></td></tr>';
        }

        $render.= '</tbody></table>';
        $render.= '</div></div>';

        return $render;
    }

    private function render_ports() : string {
        $list = array(
            array(_x("Standard", "Connection type for the SMTP ports common list.", "gd-mail-queue"), '25, 2525, 587'),
            array(_x("Secure SSL", "Connection type for the SMTP ports common list.", "gd-mail-queue"), '465'),
            array(_x("Secure TLS", "Connection type for the SMTP ports common list.", "gd-mail-queue"), '465, 587')
        );

        $render = array();

        foreach ($list as $item) {
            $render[] = $item[0].': <strong>'.$item[1].'</strong>';
        }

        return join('<br/>', $render);
    }
}
