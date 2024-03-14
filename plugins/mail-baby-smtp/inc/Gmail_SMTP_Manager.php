<?php

class MAIL_BABY_SMTP {  

    var $plugin_version = '2.6';

    var $phpmailer_version = '6.3.0';

    var $google_api_client_version = '2.2.0';

    var $plugin_url;

    var $plugin_path;

    var $content;

    function __construct() {

        define('MAIL_BABY_SMTP_VERSION', $this->plugin_version);

        define('MAIL_BABY_SMTP_SITE_URL', site_url());

        define('MAIL_BABY_SMTP_HOME_URL', home_url());

        define('MAIL_BABY_SMTP_URL', plugins_url( '', __FILE__ ));

        define('MAIL_BABY_SMTP_PATH', $this->plugin_path());

        $this->assets_url  = plugins_url( '', __FILE__ ). '/assets';

        $this->loader_operations();

    }

    function loader_operations() {

        if (is_admin()) {

            add_filter('plugin_action_links', array($this, 'add_plugin_action_links'), 10, 2);

        }

        add_action('plugins_loaded', array($this, 'plugins_loaded_handler'));

        add_action('admin_menu', array($this, 'options_menu'));

        add_action('init', array($this, 'plugin_init'));

        //add_action('admin_notices', 'MAIL_BABY_SMTP_admin_notice');

        add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_assets' ] );

        $options = MAIL_BABY_SMTP_get_option();

        if(is_MAIL_BABY_SMTP_configured() && $options['mailer'] == 'gmail' ){

            add_filter('pre_wp_mail', 'MAIL_BABY_SMTP_pre_wp_mail', 10, 2);

        }

    }

    

    function plugins_loaded_handler()

    {

        load_plugin_textdomain('mail-baby-smtp', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/'); 

    }



    function plugin_url() {

        if ($this->plugin_url)

            return $this->plugin_url;

        return $this->plugin_url = plugins_url(basename(plugin_dir_path(__FILE__)), basename(__FILE__));

    }



    function plugin_path() {

        if ($this->plugin_path)

            return $this->plugin_path;

        return $this->plugin_path = untrailingslashit(plugin_dir_path(__FILE__));

    }



    function enqueue_assets( $hook ) {



        // General styles and js.

        wp_enqueue_style(

            'mail-baby-smtp-admin',

            $this->assets_url . '/css/smtp-admin.min.css',

            false,

            MAIL_BABY_SMTP_VERSION

        );
        wp_enqueue_style(

            'mail-baby-smtp',

            $this->assets_url . '/css/customstyle.css',

            false,

            MAIL_BABY_SMTP_VERSION

        );

        wp_enqueue_script(

            'mail-baby-smtp-admin',

            $this->assets_url . '/js/mbsmtp-new.js',

            array( 'jquery' ),

            false

        );



        do_action( 'mail_baby_smtp_admin_area_enqueue_assets', $hook );

    }



    function add_plugin_action_links($links, $file) {

        if ($file == plugin_basename(dirname(__FILE__) . '/main.php')) {

            $links[] = '<a href="options-general.php?page=mail-baby-smtp-settings">'.__('Settings', 'mail-baby-smtp').'</a>';

        }

        return $links;

    }

    

    function options_menu() {

        add_options_page(__('Mail Baby SMTP', 'mail-baby-smtp'), __('Mail Baby SMTP', 'mail-baby-smtp'), 'manage_options', 'mail-baby-smtp-settings', array($this, 'options_page'));

    }



    function options_page() {

        $plugin_tabs = array(

            'mail-baby-smtp-settings' => __('General', 'mail-baby-smtp'),

            'mail-baby-smtp-settings&action=test-email' => __('Test Email', 'mail-baby-smtp'),

            'mail-baby-smtp-settings&action=server-info' => __('Server Info', 'mail-baby-smtp'),

        );

        $allowed_html = array(

            'a'      => array(

                'class' => array(),

                'id' => array(),

                'href'  => array(),

                'title' => array(),

            ),

            'br'     => array(),

            'em'     => array(),

            'strong' => array(),

            'h3' => array(
                'class' => array()
            ),

            'li' => array(
                'class' => array()
            ),
            'ul' => array(
                'class' => array()
            ),
            'div' => array(
                'class' => array(),
                'id' => array()
            )



        );

        $slug = array(

            'genral','test-mail', 'revoke-access','server-info'

        );

        $url = "https://www.mail.baby/";

        $options = isset($_POST['mailer']) ? sanitize_text_field($_POST['mailer']) : '';

        $link_text = sprintf(wp_kses(__('Please visit the <a target="_blank" href="%s">Mail Baby SMTP</a> documentation page for usage instructions.', 'mail-baby-smtp'), array('a' => array('href' => array(), 'target' => array()))), esc_url($url));

        $content0 = '<div class="card"><h3 class="h3">Mail Baby SMTP v' . MAIL_BABY_SMTP_VERSION . '</h3>';

        $content0 .= '<div class="update-nag">'.$link_text.'</div>';

        $content0 .= '<div id="poststuff"><div id="post-body">';

        echo wp_kses($content0, $allowed_html);



        if (isset($_GET['page'])) {

            $current = sanitize_text_field($_GET['page']);

            if (isset($_GET['action'])) {

                $current .= sanitize_text_field("&action=" . $_GET['action']);

            }

        }

        $content = '';

        $content .= '<ul class="nav nav-tabs">';

        $i=0;

        foreach ($plugin_tabs as $location => $tabname) {



            if ($current == $location) {

                $class = ' active';

            } else {

                $class = ' '.$slug[$i];

            }

            $content .= '<li class="nav-item"><a class="nav-link' . $class . '" href="?page=' . $location . '">' . $tabname . '</a></li>';

            $i++;

        }

        $content .= '</ul>';

       

        echo wp_kses($content, $allowed_html);



       

                

        if(isset($_GET['action']) && $_GET['action'] == 'test-email'){            

            $this->test_email_settings();

        }

        else if(isset($_GET['action']) && $_GET['action'] == 'revoke-access' ){            

            $this->revoke_access_settings();

        }

        else if(isset($_GET['action']) && $_GET['action'] == 'server-info'){            

            $this->server_info_settings();

        }

        else{

            $this->general_settings();

        }

        $content1 = '</div></div>';

        $content1 .= '</div>';

        echo wp_kses($content1, $allowed_html);



    }

    

    function plugin_init(){

        if(is_admin()){

            if(isset($_GET['action']) && $_GET['action'] == "oauth_grant"){

                include_once(plugin_dir_path( __FILE__ ).'templates/Gmail/google-api-php-client/vendor/autoload.php');

                include_once(plugin_dir_path( __FILE__ ).'templates/Gmail/class.phpmaileroauthgoogle.php');

                if (isset($_GET['code'])) {

                    $authCode = sanitize_text_field($_GET['code']);

                    $accessToken = GmailXOAuth2::resetCredentials($authCode);

                    if(isset($accessToken) && !empty($accessToken)){                       

                        //echo __('Access Granted Successfully!', 'mail-baby-smtp');

                        $_GET['MAIL_BABY_SMTP_access_granted'] = "yes";

                    }

                    else{

                        $_GET['MAIL_BABY_SMTP_access_granted'] = "no";

                    }

                }

                else {

                    // If we don't have an authorization code then get one

                    $authUrl_array = GmailXOAuth2::authenticate();

                    if(isset($authUrl_array['authorization_uri'])){

                        $authUrl= $authUrl_array['authorization_uri'];

                        wp_redirect($authUrl);

                        exit;

                    }

                }

                // Unix timestamp of when the token will expire, and need refreshing

                //    echo $token->expires;

            }

        }

    }

    

    function test_email_settings(){

        if(isset($_POST['MAIL_BABY_SMTP_send_test_email'])){

            $to = '';

            if(isset($_POST['MAIL_BABY_SMTP_to_email']) && !empty($_POST['MAIL_BABY_SMTP_to_email'])){

                $to = sanitize_text_field($_POST['MAIL_BABY_SMTP_to_email']);

            }

            $subject = '';

            if(isset($_POST['MAIL_BABY_SMTP_email_subject']) && !empty($_POST['MAIL_BABY_SMTP_email_subject'])){

                $subject = sanitize_text_field($_POST['MAIL_BABY_SMTP_email_subject']);

            }

            $message = '';

            if(isset($_POST['MAIL_BABY_SMTP_email_body']) && !empty($_POST['MAIL_BABY_SMTP_email_body'])){

                $message = sanitize_text_field($_POST['MAIL_BABY_SMTP_email_body']);

            }

            if (wp_mail($to, $subject, $message)){

                $message = 'Email Sent';

                $class = 'alert alert-success';

            }else{



                $message = 'Email Not Sent. Check out the Complete Log below.';

                $class = 'alert alert-danger';

            }

    

        }

        ?>

        <br><br>

        <form method="post" action="<?php echo esc_url($_SERVER["REQUEST_URI"]); ?>">

            <?php wp_nonce_field('MAIL_BABY_SMTP_test_email'); ?>

            <span class="d-flex <?php echo isset($class) ? $class : ''; ?>" ><?php echo isset($message) ? $message : '';?></span>

            <div class="my-3">
    
                <label class="form-label" for="MAIL_BABY_SMTP_to_email"><?php _e('To', 'mail-baby-smtp');?></label>

                <input name="MAIL_BABY_SMTP_to_email" type="text" id="MAIL_BABY_SMTP_to_email" value="" class="mb-2 form-control">

                <small class="description"><?php _e('Email address of the recipient', 'mail-baby-smtp');?></small>

            </div>

            <div class="my-3">
            
                <label class="form-label" for="MAIL_BABY_SMTP_email_subject"><?php _e('Subject', 'mail-baby-smtp');?></label>

                <input name="MAIL_BABY_SMTP_email_subject" type="text" id="MAIL_BABY_SMTP_email_subject" value=""  class="mb-2 form-control">

                <small class="description"><?php _e('Subject of the email', 'mail-baby-smtp');?></small>

            </div>

            <div class="my-3">
                
                <label class="form-label" for="MAIL_BABY_SMTP_email_body"><?php _e('Message', 'mail-baby-smtp');?></label>

                <textarea name="MAIL_BABY_SMTP_email_body" id="MAIL_BABY_SMTP_email_body" rows="3" class="mb-2 form-control"></textarea>

                <small class="description"><?php _e('Email body', 'mail-baby-smtp');?></small>

            </div>

            <div class="my-3">

                <input type="submit" name="MAIL_BABY_SMTP_send_test_email" id="MAIL_BABY_SMTP_send_test_email" class="btn btn-primary btn-sm" value="<?php _e('Send Email', 'mail-baby-smtp');?>">

            </div>

        </form>

        <?php



        //enable debug when sending a test mail

        if(isset($_POST['MAIL_BABY_SMTP_send_test_email'])){

            $errors = get_option('smtp_error_log');



        if(!empty($errors)){

        ?>
            <div class="container">

                <h1 style="margin-left:7px !important;margin:1em;">Error Log</h1>

                        <?php 
                    
                        foreach($errors as $error){

                            print_r('<div class="failed-log">' . $error . "</div>");

                        }

                        update_option('smtp_error_log',''); 
                        
                    ?>

            </div>
        <?php

        } else{

            return;

        }

    }

    }

    

    function revoke_access_settings()

    {

        $allowed_html = array(

            'a'      => array(

                'class' => array(),

                'id' => array(),

                'href'  => array(),

                'title' => array(),

            ),

            'br'     => array(),

            'em'     => array(),

            'strong' => array(),

            'h2' => array(),

            'div' => array(),

            'div' => array()



        );



         $options = MAIL_BABY_SMTP_get_option();

        if (isset($_POST['MAIL_BABY_SMTP_delete_access_key']) && $options['mailer'] == 'gmail' ) {

            $nonce = $_REQUEST['_wpnonce'];

            if (!wp_verify_nonce($nonce, 'MAIL_BABY_SMTP_delete_accesskey')) {

                wp_die('Error! Nonce Security Check Failed! please click on the button again.');

            }

            $options = array();

            $options['oauth_access_token'] = '';

            MAIL_BABY_SMTP_update_option($options);

            $cont = esc_html('<div id="message" class="updated fade"><p><strong>');

            $cont = __('Access Key Successfully Deleted!', 'mail-baby-smtp');

            $cont = '</strong></p></div>';

            echo wp_kses($cont, $allowed_html);

        }

        $url = "https://security.google.com/settings/security/permissions";

        $link_text = sprintf(wp_kses(__('Revoke access by visiting <a target="_blank" href="%s">account settings</a>.', 'mail-baby-smtp'), array('a' => array('href' => array(), 'target' => array()))), esc_url($url));

        ?>
        <div class="update-nag">

            <?php _e('Generally you do not need to do anything on this page. However, for some reason if you wish to revoke access from your application please follow these steps:', 'mail-baby-smtp');?>

            <ol>

                <li><?php echo esc_html($link_text);?></li>

                <li><?php _e('Delete your existing access key by clicking on the "Delete Access Key" button.', 'mail-baby-smtp');?></li>

            </ol>    

        </div>



        <form method="post" action="<?php echo esc_url($_SERVER["REQUEST_URI"]); ?>">

            <?php wp_nonce_field('MAIL_BABY_SMTP_delete_accesskey'); ?>           



            <p class="submit"><input type="submit" name="MAIL_BABY_SMTP_delete_access_key" id="MAIL_BABY_SMTP_delete_access_key" class="button button-primary" value="Delete Access Key"></p>

        </form>            
        <?php

    }

    

    function server_info_settings()

    {

        $server_info = '';

        $server_info .= sprintf('OS: %s%s', php_uname(), PHP_EOL);

        $version = '';

        if(version_compare(PHP_VERSION, '5.4', '<')) {

            $version = ' (PHPMailer requires PHP 5.4 or later in order to send email)';

        }

        $server_info .= sprintf('PHP version: %s%s%s', PHP_VERSION, $version, PHP_EOL);

        $server_info .= sprintf('WordPress version: %s%s', get_bloginfo('version'), PHP_EOL);

        $server_info .= sprintf('WordPress multisite: %s%s', (is_multisite() ? 'Yes' : 'No'), PHP_EOL);

        $openssl_status = 'Available';

        $openssl_text = '';

        if(!extension_loaded('openssl') && !defined('OPENSSL_ALGO_SHA1')){

            $openssl_status = 'Not available';

            $openssl_text = ' (openssl extension is required in order to use any kind of encryption like TLS or SSL. Mail Baby SMTP server does not allow you to send email without an encrypted connection)';

        }      

        $server_info .= sprintf('openssl: %s%s%s', $openssl_status, $openssl_text, PHP_EOL);

        $server_info .= sprintf('allow_url_fopen: %s%s', (ini_get('allow_url_fopen') ? 'Enabled' : 'Disabled'), PHP_EOL);

        $stream_socket_client_status = 'Not Available';

        $fsockopen_status = 'Not Available';

        $socket_enabled = false;

        if(function_exists('stream_socket_client')){

            $stream_socket_client_status = 'Available';

            $socket_enabled = true;

        }

        if(function_exists('fsockopen')){

            $fsockopen_status = 'Available';

            $socket_enabled = true;

        }

        $socket_text = '';

        if(!$socket_enabled){

            $socket_text = ' (In order to make a SMTP connection your server needs to have either stream_socket_client or fsockopen)';

        }

        $server_info .= sprintf('stream_socket_client: %s%s', $stream_socket_client_status, PHP_EOL);

        $server_info .= sprintf('fsockopen: %s%s%s', $fsockopen_status, $socket_text, PHP_EOL);

        $cURL_status = 'Not Available. In order to make a SMTP connection your server needs to have cURL enabled';

        if(function_exists('curl_init')){

            $cURL_status = 'Available';           

        }

        $server_info .= sprintf('cURL: %s%s', $cURL_status, PHP_EOL);

        if(function_exists('curl_version')){

            $curl_version = curl_version();

            $server_info .= sprintf('cURL Version: %s, %s%s', $curl_version['version'], $curl_version['ssl_version'], PHP_EOL);

        }

        ?>

        <textarea rows="10" cols="50" class="large-text code"><?php echo esc_textarea($server_info);?></textarea>

        <?php

    }





    function general_settings() {

            



        if (isset($_POST['MAIL_BABY_SMTP_update_settings'])) {



            //  echo "<pre>";

            // print_r($_POST);

            // echo "</pre>";

            // die();



            $nonce = $_REQUEST['_wpnonce'];

            if (!wp_verify_nonce($nonce, 'MAIL_BABY_SMTP_general_settings')) {

                wp_die('Error! Nonce Security Check Failed! please save the settings again.');

            }



            $mailer = '';

            if(isset($_POST['mailer']) && !empty($_POST['mailer'])){

                $mailer = sanitize_text_field($_POST['mailer']);

            }



            $client_id = '';

            if(isset($_POST['oauth_client_id']) && !empty($_POST['oauth_client_id'])){

                $client_id = sanitize_text_field($_POST['oauth_client_id']);

            }

            $client_secret = '';

            if(isset($_POST['oauth_client_secret']) && !empty($_POST['oauth_client_secret'])){

                $client_secret = sanitize_text_field($_POST['oauth_client_secret']);

            }

            $oauth_user_email = '';

            if(isset($_POST['oauth_user_email']) && !empty($_POST['oauth_user_email'])){

                $oauth_user_email = sanitize_email($_POST['oauth_user_email']);

            }

            $from_email = '';

            if(isset($_POST['from_email']) || !empty($_POST['from_email'])){

                $from_email = sanitize_email($_POST['from_email']);

            }

            $from_email1 = '';

            if(isset($_POST['from_email1']) || !empty($_POST['from_email1'])){

                $from_email1 = sanitize_email($_POST['from_email1']);

            }
            
            $from_email2 = '';

            if(isset($_POST['from_email2']) || !empty($_POST['from_email2'])){

                $from_email2 = sanitize_email($_POST['from_email2']);

            }

            $from_email3 = '';

            if(isset($_POST['from_email3']) || !empty($_POST['from_email3'])){

                $from_email3 = sanitize_email($_POST['from_email3']);

            }

            $from_email4 = '';

            if(isset($_POST['from_email4']) || !empty($_POST['from_email4'])){

                $from_email4 = sanitize_email($_POST['from_email4']);

            }

            $from_email5 = '';

            if(isset($_POST['from_email5']) || !empty($_POST['from_email5'])){

                $from_email5 = sanitize_email($_POST['from_email5']);

            }

            $from_email6 = '';

            if(isset($_POST['from_email6']) || !empty($_POST['from_email6'])){

                $from_email6 = sanitize_email($_POST['from_email6']);

            }

            $from_name = '';

            if(isset($_POST['from_name']) || !empty($_POST['from_name'])){

                $from_name = sanitize_text_field(stripslashes($_POST['from_name']));

            }

            $from_name1 = '';

            if(isset($_POST['from_name1']) || !empty($_POST['from_name1'])){

                $from_name1 = sanitize_text_field(stripslashes($_POST['from_name1']));

            }

            $from_name2 = '';

            if(isset($_POST['from_name2']) || !empty($_POST['from_name2'])){

                $from_name2 = sanitize_text_field(stripslashes($_POST['from_name2']));

            }

            $from_name3 = '';

            if(isset($_POST['from_name3']) || !empty($_POST['from_name3'])){

                $from_name3 = sanitize_text_field(stripslashes($_POST['from_name3']));

            }

            $from_name4 = '';

            if(isset($_POST['from_name4']) || !empty($_POST['from_name4'])){

                $from_name4 = sanitize_text_field(stripslashes($_POST['from_name4']));

            }

            $from_name5 = '';

            if(isset($_POST['from_name5']) || !empty($_POST['from_name5'])){

                $from_name5 = sanitize_text_field(stripslashes($_POST['from_name5']));

            }

            $smtp_host = '';

            if(isset($_POST['smtp_host']) && !empty($_POST['smtp_host'])){

                $smtp_host = sanitize_text_field($_POST['smtp_host']);

            }

            $smtp_auth = '';

            if(isset($_POST['smtp_auth']) && !empty($_POST['smtp_auth'])){

                $smtp_auth = sanitize_text_field($_POST['smtp_auth']);

            }

            $smtp_username = '';

            if(isset($_POST['smtp_username']) && !empty($_POST['smtp_username'])){

                $smtp_username = sanitize_text_field($_POST['smtp_username']);

            }

            $smtp_password = '';

            if(isset($_POST['smtp_password']) && !empty($_POST['smtp_password'])){

                $smtp_password = sanitize_text_field($_POST['smtp_password']);

            }

            $type_of_encryption = '';

            if(isset($_POST['type_of_encryption']) && !empty($_POST['type_of_encryption'])){

                $type_of_encryption = sanitize_text_field($_POST['type_of_encryption']);

            }

            $smtp_auto_tls = '';

            if(isset($_POST['smtp_auto_tls']) && !empty($_POST['smtp_auto_tls'])){

                $smtp_auto_tls = sanitize_text_field($_POST['smtp_auto_tls']);

            }

            $smtp_port = '';

            if(isset($_POST['smtp_port']) && !empty($_POST['smtp_port'])){

                $smtp_port = sanitize_text_field($_POST['smtp_port']);

            }

            $disable_ssl_verification = '';

            if(isset($_POST['disable_ssl_verification']) && !empty($_POST['disable_ssl_verification'])){

                $disable_ssl_verification = sanitize_text_field($_POST['disable_ssl_verification']);

            }



            /*Sendin Blue API*/

            $mail_baby_smtp_sendinblue_api_key = '';

            if(isset($_POST['mail_baby_smtp_sendinblue_api_key']) && !empty($_POST['mail_baby_smtp_sendinblue_api_key'])){

                $mail_baby_smtp_sendinblue_api_key = sanitize_text_field($_POST['mail_baby_smtp_sendinblue_api_key']);

                $mail_baby_smtp_sendinblue_api_key = wp_unslash($mail_baby_smtp_sendinblue_api_key); // To removes slash (automatically added by WordPress) from the password when apostrophe is present

                $mail_baby_smtp_sendinblue_api_key = $mail_baby_smtp_sendinblue_api_key;

            }



            $mail_baby_smtp_sendinblue_domain = '';

            if(isset($_POST['mail_baby_smtp_sendinblue_domain']) && !empty($_POST['mail_baby_smtp_sendinblue_domain'])){

                $mail_baby_smtp_sendinblue_domain = sanitize_text_field($_POST['mail_baby_smtp_sendinblue_domain']);

            }



            /** Send Grid Api */



            $mail_baby_smtp_sendgrid_api_key = '';



            if(isset($_POST['mail_baby_smtp_sendgrid_api_key']) && !empty($_POST['mail_baby_smtp_sendgrid_api_key'])){

                $mail_baby_smtp_sendgrid_api_key = sanitize_text_field($_POST['mail_baby_smtp_sendgrid_api_key']);



                $mail_baby_smtp_sendgrid_api_key = wp_unslash($mail_baby_smtp_sendgrid_api_key); // To removes slash (automatically added by WordPress) from the password when apostrophe is present



                $mail_baby_smtp_sendgrid_api_key = $mail_baby_smtp_sendgrid_api_key;



            }



            $mail_baby_smtp_sendgrid_domain = '';



            if(isset($_POST['mail_baby_smtp_sendgrid_domain']) && !empty($_POST['mail_baby_smtp_sendgrid_domain'])){

                $mail_baby_smtp_sendgrid_domain = sanitize_text_field($_POST['mail_baby_smtp_sendgrid_domain']);

            }



            $mail_baby_smtp_smtpcom_api_key = '';



            if(isset($_POST['mail_baby_smtp_smtpcom_api_key']) && !empty($_POST['mail_baby_smtp_smtpcom_api_key'])){

                $mail_baby_smtp_smtpcom_api_key = sanitize_text_field($_POST['mail_baby_smtp_smtpcom_api_key']);

                $mail_baby_smtp_smtpcom_api_key = wp_unslash($mail_baby_smtp_smtpcom_api_key); // To removes slash (automatically added by WordPress) from the password when apostrophe is present

                $mail_baby_smtp_smtpcom_api_key = $mail_baby_smtp_smtpcom_api_key;

            }



            $mail_baby_smtp_smtpcom_sender_name = '';



            if(isset($_POST['mail_baby_smtp_smtpcom_sender_name']) && !empty($_POST['mail_baby_smtp_smtpcom_sender_name'])){

                $mail_baby_smtp_smtpcom_sender_name = sanitize_text_field($_POST['mail_baby_smtp_smtpcom_sender_name']);

            }





            // Mailgun Api



            $mail_baby_smtp_mailgun_api_key = '';



            if(isset($_POST['mail_baby_smtp_mailgun_api_key']) && !empty($_POST['mail_baby_smtp_mailgun_api_key'])){

                $mail_baby_smtp_mailgun_api_key = sanitize_text_field($_POST['mail_baby_smtp_mailgun_api_key']);

                $mail_baby_smtp_mailgun_api_key = wp_unslash($mail_baby_smtp_mailgun_api_key); // To removes slash (automatically added by WordPress) from the password when apostrophe is present

                $mail_baby_smtp_mailgun_api_key = $mail_baby_smtp_mailgun_api_key;

            }



            $mail_baby_smtp_mailgun_domain = '';



            if(isset($_POST['mail_baby_smtp_mailgun_domain']) && !empty($_POST['mail_baby_smtp_mailgun_domain'])){

                $mail_baby_smtp_mailgun_domain = sanitize_text_field($_POST['mail_baby_smtp_mailgun_domain']);

            }



            $mail_baby_smtp_mailgun_region = '';



            if(isset($_POST['mail_baby_smtp_mailgun_region']) && !empty($_POST['mail_baby_smtp_mailgun_region'])){

                $mail_baby_smtp_mailgun_region = sanitize_text_field($_POST['mail_baby_smtp_mailgun_region']);

            }





            $mail_baby_api_key = '';



            if(isset($_POST['mail_baby_api_key']) && !empty($_POST['mail_baby_api_key'])){

                $mail_baby_api_key = sanitize_text_field($_POST['mail_baby_api_key']);

            }



            $mail_baby_sender_name = '';



            if(isset($_POST['mail_baby_sender_name']) && !empty($_POST['mail_baby_sender_name'])){

                $mail_baby_sender_name = sanitize_text_field($_POST['mail_baby_sender_name']);

            }





            $options = array();



            $options['mailer'] = $mailer;

            if( $options['mailer'] == 'gmail' ){

                $options['from_email5'] = $from_email5;

                $options['from_name4'] = $from_name4;

                $options['oauth_client_id'] = $client_id;

                $options['oauth_client_secret'] = $client_secret;

                $options['oauth_user_email'] = $oauth_user_email;

                $options['type_of_encryption'] = $type_of_encryption;

                $options['smtp_port'] = $smtp_port;

                $options['disable_ssl_verification'] = $disable_ssl_verification; 



            }



            if($options['mailer'] == 'sendinblue') {

                /*Update Sendin Blue*/

                $options['from_email2'] = $from_email2;

                $options['mail_baby_smtp_sendinblue_domain'] = $mail_baby_smtp_sendgrid_domain;

                $options['mail_baby_smtp_sendinblue_api_key'] = $mail_baby_smtp_sendinblue_api_key;

            }



            if($options['mailer'] == 'sendgrid'){

                /*Update Send Grid*/

                $options['from_email4'] = $from_email4;

                $options['from_name3'] = $from_name3;

                $options['mail_baby_smtp_sendgrid_api_key'] = $mail_baby_smtp_sendgrid_api_key;

                $options['mail_baby_smtp_sendgrid_domain'] = $mail_baby_smtp_sendgrid_domain;



            }



            if($options['mailer'] == 'smtp'){

                $options['from_email1'] = $from_email1;

                $options['from_name1'] = $from_name1;

                $options['mail_baby_smtp_smtpcom_api_key'] = $mail_baby_smtp_smtpcom_api_key;

                $options['mail_baby_smtp_smtpcom_sender_name'] = $mail_baby_smtp_smtpcom_sender_name;

            }



            if($options['mailer'] == 'mailgun'){

                //die;
                
                $options['from_email3'] = $from_email3;

                $options['from_name2'] = $from_name2;
                
                $options['mail_baby_smtp_mailgun_api_key'] = $mail_baby_smtp_mailgun_api_key;

                $options['mail_baby_smtp_mailgun_domain'] = $mail_baby_smtp_mailgun_domain;

                $options['mail_baby_smtp_mailgun_region'] = $mail_baby_smtp_mailgun_region;

            }  



            if($options['mailer'] == 'mailbaby'){

                //die;

                $options['from_email'] = $from_email;

                $options['from_name'] = $from_name;

                $options['mail_baby_api_key'] = $mail_baby_api_key;

                $options['mail_baby_sender_name'] = $mail_baby_sender_name;

            }   

            if($options['mailer'] == 'othersmtp'){

                //die;

                $options['from_email6'] = $from_email6;

                $options['from_name5'] = $from_name5;

                $options['smtp_host'] = $smtp_host;

                $options['type_of_encryption'] = $type_of_encryption;

                $options['smtp_port'] = $smtp_port;

                $options['smtp_auto_tls'] = $smtp_auto_tls;

                $options['smtp_auth'] = $smtp_auth;

                $options['smtp_username'] = $smtp_username;

                $options['smtp_password'] = $smtp_password;

            }   

            

            MAIL_BABY_SMTP_update_option($options);



            $allowed_html = array(

                'a'      => array(

                    'class' => array(),

                    'id' => array(),

                    'href'  => array(),

                    'title' => array(),

                ),

                'br'     => array(),

                'em'     => array(),

                'strong' => array(),

                'h2' => array(),
                'span' => array(
                    'id' => array(),
                    'class' => array(),
                    'style' => array()
                ),

                'div' => array(

                    'class' => array(),

                    'id' => array()

                )

    

            );



            ?>



            <?php



            $content = '<span id="message" class="alert alert-success d-block">';

            $content .= __('Settings Saved!', 'mail-baby-smtp');

            $content .='</span>';



            echo wp_kses($content,$allowed_html);

        }

        

        if (isset($_GET['MAIL_BABY_SMTP_access_granted'])) {

            if($_GET['MAIL_BABY_SMTP_access_granted']=="yes"){

                $content2 =  '<div id="message" class="alert alert-success d-block"><p><strong>';

                $content2 =  __('Access Granted Successfully!', 'mail-baby-smtp');

                $content2 =  '</strong></p></div>';

                echo wp_kses($content2,$allowed_html);

            }

            else{

                $content3 =  '<div id="message" class="error"><p><strong>';

                $content3 =  __('Access could not be granted', 'mail-baby-smtp');

                $content3 =  '</strong></p></div>';

                echo wp_kses($content3,$allowed_html);

            }

        }

        

        $options = MAIL_BABY_SMTP_get_option();



       

        if(!is_array($options)){



            $mbsmtp_options = MAIL_BABY_SMTP_get_option();

            // $options = array();

            // $options['oauth_client_id'] = '';

            // $options['oauth_client_secret'] = '';

            // $options['oauth_user_email'] = '';

            // $options['from_email'] = '';

            // $options['from_name'] = '';

            // $options['type_of_encryption'] = '';

            // $options['smtp_port'] = '';

            // $options['disable_ssl_verification'] = '';

        }

        

        // Avoid warning notice since this option was added later

        if(!isset($options['disable_ssl_verification'])){

            $options['disable_ssl_verification'] = '';

        }



            // echo  $mailer;

            // echo "<pre>";

            // print_r($_POST);

            // print_r($options);

            // echo "</pre>";

        ?>

       

        <div class="wrap" id="mail-baby-smtp">

        <h1 style="font-size: 2em;margin: .85em 0;"></h1> 

            <div class="mail-baby-smtp-page mail-baby-smtp-page-general mail-baby-smtp-tab-settings">

                <div class="mail-baby-smtp-page-content">

                    <form method="post" action="<?php echo esc_attr($_SERVER["REQUEST_URI"]); ?>">

                        <?php wp_nonce_field('MAIL_BABY_SMTP_general_settings'); ?>  

                        <div class="mb-5 ml-2">

                            <label for="mailer" class="my-3 form-label" style="font-weight: 600;"><?php _e('Select Mailer:', 'mail-baby-smtp');?></label>                            

                            <section>     

                                <div class="mailer-section"> 

                                    <input class="mailer" type="radio" id="control_01" name="mailer" value="mailbaby"  <?php checked( esc_attr($options['mailer']) == 'mailbaby' ); ?> />

                                    <label class="mailer-label mailbaby" for="control_01">

                                        <h1 class="mailer-title">Mail Baby</h1>

                                    </label>

                                </div> 

                                <div class="mailer-section"> 

                                    <input class="mailer" type="radio" id="control_02" name="mailer" value="php"  <?php checked( esc_attr($options['mailer']) == 'php' ); ?> />

                                    <label class="mailer-label php" for="control_02">

                                        <h1 class="mailer-title">PHP</h1>

                                    </label>

                                </div> 

                                <div class="mailer-section"> 

                                    <input class="mailer" type="radio" id="control_03" name="mailer" value="smtp"  <?php checked( esc_attr($options['mailer']) == 'smtp' ); ?> />

                                    <label class="mailer-label smtp-com" for="control_03">

                                        <h1 class="mailer-title">SMTP.com</h1> 

                                    </label>

                                </div> 

                                <div class="mailer-section"> 

                                    <input class="mailer" type="radio" id="control_04" name="mailer" value="sendinblue"  <?php checked( esc_attr($options['mailer']) == 'sendinblue' ); ?> />

                                    <label class="mailer-label sendinblue" for="control_04">

                                        <h1 class="mailer-title">SendInBlue</h1>

                                    </label>

                                </div>    

                            </section>

                            <section style="margin-top: 20px;">         

                                <div class="mailer-section"> 

                                    <input class="mailer" type="radio" id="control_05" name="mailer" value="mailgun"  <?php checked( esc_attr($options['mailer']) == 'mailgun' ); ?> />

                                    <label class="mailer-label mailgun" for="control_05">

                                        <h1 class="mailer-title">Mail Gun</h1>

                                    </label>

                                </div> 

                                <div class="mailer-section"> 

                                    <input class="mailer" type="radio" id="control_06" name="mailer" value="sendgrid"  <?php checked( esc_attr($options['mailer']) == 'sendgrid' ); ?> />

                                    <label class="mailer-label sendgrid" for="control_06">

                                        <h1 class="mailer-title">Send Grid</h1>

                                    </label>

                                </div> 

                                <div class="mailer-section"> 

                                    <input class="mailer" type="radio" id="control_07" name="mailer" value="gmail"  <?php echo checked( esc_attr($options['mailer']), 'gmail',false ); ?> />

                                    <label class="mailer-label gmail" for="control_07">

                                        <h1 class="mailer-title">GMail</h1> 

                                    </label>

                                </div> 

                                <div class="mailer-section"> 

                                    <input class="mailer" type="radio" id="control_08" name="mailer" value="othersmtp"  <?php checked( esc_attr($options['mailer']) == 'othersmtp' ); ?> />

                                    <label class="mailer-label smtp" for="control_08">

                                        <h1 class="mailer-title">Other SMTP</h1>

                                    </label>

                                </div>    

                            </section>
                        </div>

                        <div class="mail-baby-smtp-mailer-options">

                            <div class="mail-baby-smtp-mailer-option mail-baby-smtp-mailer-option-mail active">



                                <!-- Mailer Title/Notice/Description -->

                                <div class="mail-baby-smtp-setting-row mail-baby-smtp-setting-row-content mail-baby-smtp-clear section-heading no-desc" id="mail-baby-smtp-setting-row-email-heading">

                                    <div class="mail-baby-smtp-setting-field">

                                        <h2>Default (none)</h2>

                                    </div>

                                </div>

                                <blockquote>

                                    You currently have the native WordPress option selected. Please select any other Mailer option above to continue the setup.     
                                
                                </blockquote>

                                <div class="my-3">
                                    <label class="form-label" for="from_email"><?php _e('From Email Address', 'mail-baby-smtp');?></label>
                                    <input class="mb-2 form-control" name="from_email" type="text" id="from_email" value="<?php echo esc_html($options['from_email']); ?>">
                                    <small class="description"><?php _e('The email address that emails are sent from. <br> If you\'re using an email provider (Yahoo, Outlook.com, etc) this should be your email address for that account.', 'mail-baby-smtp');?></small>
                                </div>


                            </div>



                            <div class="mail-baby-smtp-mailer-option mail-baby-smtp-mailer-option-smtpcom hidden">



                                <!-- Mailer Title/Notice/Description -->

                                <div class="mail-baby-smtp-setting-row mail-baby-smtp-setting-row-content mail-baby-smtp-clear section-heading " id="mail-baby-smtp-setting-row-email-heading">

                                    <div class="mail-baby-smtp-setting-field">

                                        <h2>SMTP.com</h2>



                                        <p class="desc">SMTP.com is a premium email delivery and email relay solution that enables you to send and track high volume emails effortlessly.

                                        SMTP.com has been around for almost as long as email itself. With a 22 year track record of reliable email delivery, SMTP.com is a premiere solution for WordPress developers and website owners. Their super simple integration interface makes it easy to get started, while a powerful API and robust documentation make it a preferred choice among developers.<br><br>Start a 30-day free trial with 50,000 emails.<br><br>Read our <a href="https://www.smtp.com/resources/api-documentation/" target="_blank" rel="noopener noreferrer">SMTP.com documentation</a> to learn how to configure SMTP.com and improve your email deliverability.</p><p class="buttonned"><a href="https://www.smtp.com/pricing/" target="_blank" rel="noopener noreferrer" class="mail-baby-smtp-btn mail-baby-smtp-btn-md mail-baby-smtp-btn-blueish">Get Started with SMTP.com</a></p><p></p>

                                    </div>

                                </div>

                                <div class="my-3">
                                    <label class="form-label" for="from_email"><?php _e('From Email Address', 'mail-baby-smtp');?></label>
                                    <input class="mb-2 form-control" name="from_email1" type="text" id="from_email" value="<?php echo esc_html($options['from_email1']); ?>">
                                    <small class="description"><?php _e('The email address that emails are sent from. <br> If you\'re using an email provider (Yahoo, Outlook.com, etc) this should be your email address for that account.', 'mail-baby-smtp');?></small>
                                </div>

                                <div class="my-3">
                                    <label class="form-label" for="from_name"><?php _e('From Name', 'mail-baby-smtp');?></label>
                                    <input class="mb-2 form-control" name="from_name1" type="text" id="from_name" value="<?php echo esc_html($options['from_name1']); ?>">
                                    <small class="description"><?php _e('The Name that emails are sent from.', 'mail-baby-smtp');?></small>
                                </div>

                                <!-- API Key -->

                                <div class="my-3">
                                    <label class="form-label" for="mail_baby_smtp_smtpcom_api_key">API Key</label>
                                    <input class="mb-2 form-control" type="password" spellcheck="false" id="mail_baby_smtp_smtpcom_api_key" name="mail_baby_smtp_smtpcom_api_key" value="<?php echo isset($options['mail_baby_smtp_smtpcom_api_key'])? esc_attr($options['mail_baby_smtp_smtpcom_api_key']) : ''; ?>" >
                                    <small class="desc">Follow this link to get an API Key from SMTP.com: <a href="https://my.smtp.com/settings/api" target="_blank" rel="noopener noreferrer">Get API Key</a>.</small>
                                </div>
                            </div>

                            <div class="mail-baby-smtp-mailer-option mail-baby-smtp-mailer-option-sendinblue hidden">

                                <!-- Mailer Title/Notice/Description -->

                                <div class="mail-baby-smtp-setting-row mail-baby-smtp-setting-row-content mail-baby-smtp-clear section-heading " id="mail-baby-smtp-setting-row-email-heading">

                                    <div class="mail-baby-smtp-setting-field">

                                        <h2>Sendinblue</h2>

                                        <p class="desc"><strong>Sendinblue is a suggested conditional email administration. Established in 2012, they serve 80,000+ developing organizations all throughout the planet and send more than 30 million messages every day. They comprehend that conditional messages are the core of your client connections. Their email deliverability specialists are continually grinding away advancing the dependability and speed of their SMTP foundation.<br><br>Sendinblue provides users 300 free emails per day.<br><br>Read our <a href="https://landing.sendinblue.com/en/smtp" target="_blank" rel="noopener noreferrer">Sendinblue documentation</a> to learn how to configure Sendinblue and improve your email deliverability.</p><p class="buttonned"><a href="https://landing.sendinblue.com/en/smtp" target="_blank" rel="noopener noreferrer" class="mail-baby-smtp-btn mail-baby-smtp-btn-md mail-baby-smtp-btn-blueish">Get Sendinblue Now (Free)</a></p><p></p>

                                    </div>

                                </div>

                                <div class="my-3">
                                    <label class="form-label" for="from_email"><?php _e('From Email Address', 'mail-baby-smtp');?></label>
                                    <input class="mb-2 form-control" name="from_email2" type="text" id="from_email" value="<?php echo esc_html($options['from_email2']); ?>">
                                    <small class="description"><?php _e('The email address that emails are sent from. <br> If you\'re using an email provider (Yahoo, Outlook.com, etc) this should be your email address for that account.', 'mail-baby-smtp');?></small>
                                </div>

                                <!-- API Key -->

                                <div class="my-3">
                                    <label class="form-label" for="mail_baby_smtp_sendinblue_api_key">API Key</label>
                                    <input class="mb-2 form-control" id="mail_baby_smtp_sendinblue_api_key" type="password" spellcheck="false" name="mail_baby_smtp_sendinblue_api_key" value="<?php echo isset($options['mail_baby_smtp_sendinblue_api_key']) ? esc_attr($options['mail_baby_smtp_sendinblue_api_key']) : ''; ?>" id="mail-baby-smtp-setting-sendinblue-api_key">
                                    <small class="desc">Follow this link to get an API Key: <a href="https://account.sendinblue.com/advanced/api" target="_blank" rel="noopener noreferrer">Get v3 API Key</a>.</small>
                                </div>

                                <!-- Sending Domain -->

                                <div class="my-3">
                                    <label class="form-label" for="mail-baby-smtp-setting-sendinblue-domain">Sending Domain</label>
                                    <input class="mb-3 form-control" id="mail-baby-smtp-setting-sendinblue-domain" name="mail_baby_smtp_sendinblue_domain" type="text" value="<?php echo isset($options['mail_baby_smtp_sendinblue_domain']) ? esc_attr($options['mail_baby_smtp_sendinblue_domain']) : ''; ?>" id="mail-baby-smtp-setting-sendinblue-domain" spellcheck="false">
                                    <small class="desc">Please input the sending domain/subdomain you configured in your Sendinblue dashboard. More information can be found in our <a href="https://landing.sendinblue.com/en/smtp" target="_blank" rel="noopener noreferrer">Sendinblue documentation</a>.</small>
                                </div>

                            </div>

                            <div class="mail-baby-smtp-mailer-option mail-baby-smtp-mailer-option-mailgun hidden">



                                <!-- Mailer Title/Notice/Description -->

                                <div class="mail-baby-smtp-setting-row mail-baby-smtp-setting-row-content mail-baby-smtp-clear section-heading " id="mail-baby-smtp-setting-row-email-heading">

                                    <div class="mail-baby-smtp-setting-field">

                                        <h2>Mailgun</h2>

                                        <p class="desc"><a href="https://www.mailgun.com" target="_blank" rel="noopener noreferrer">Mailgun</a> is Powerful Api that enable you to send, receive and track email effortlessly and one of the leading transactional email services trusted by over 150,000+ businesses. <br><br> They provide 5,000 free emails per month for 3 months.<br><br>Read our <a href="https://documentation.mailgun.com/en/latest/" target="_blank" rel="noopener noreferrer">Mailgun documentation</a> to learn how to configure Mailgun and improve your email deliverability.</p>

                                    </div>

                                </div>

                                <div class="my-3">
                                    <label class="form-label" for="from_email"><?php _e('From Email Address', 'mail-baby-smtp');?></label>
                                    <input class="mb-2 form-control" name="from_email3" type="text" id="from_email" value="<?php echo esc_html($options['from_email3']); ?>">
                                    <small class="description"><?php _e('The email address that emails are sent from. <br> If you\'re using an email provider (Yahoo, Outlook.com, etc) this should be your email address for that account.', 'mail-baby-smtp');?></small>
                                </div>

                                <div class="my-3">
                                    <label class="form-label" for="from_name"><?php _e('From Name', 'mail-baby-smtp');?></label>
                                    <input class="mb-2 form-control" name="from_name2" type="text" id="from_name" value="<?php echo esc_html($options['from_name2']); ?>">
                                    <small class="description"><?php _e('The Name that emails are sent from.', 'mail-baby-smtp');?></small>
                                </div>

                                <!-- API Key -->

                                <div class="my-3">
                                    <label class="form-label" for="mail-baby-smtp-setting-mailgun-api_key">Private API Key</label>
                                    <input class="mb-2 form-control" type="password" spellcheck="false" name="mail_baby_smtp_mailgun_api_key" value="<?php echo isset($options['mail_baby_smtp_mailgun_api_key']) ? esc_attr($options['mail_baby_smtp_mailgun_api_key']) : ''; ?>" id="mail-baby-smtp-setting-mailgun-api_key">
                                    <small class="desc">Follow this link to get an API Key from Mailgun: <a href="https://app.mailgun.com/app/account/security/api_keys" target="_blank" rel="noopener noreferrer">Get a Private API Key</a>.</small>
                                </div>

                                <!-- Domain -->

                                <div class="my-3">
                                    <label class="form-label" for="mail-baby-smtp-setting-mailgun-domain">Domain Name</label>
                                    <input class="mb-2 form-control" id="mail-baby-smtp-setting-mailgun-domain" name="mail_baby_smtp_mailgun_domain" type="text" value="<?php echo isset($options['mail_baby_smtp_mailgun_domain']) ? esc_attr($options['mail_baby_smtp_mailgun_domain']) : ''; ?>" id="mail-baby-smtp-setting-mailgun-domain" spellcheck="false">
                                    <small class="desc">Follow this link to get a Domain Name from Mailgun: <a href="https://app.mailgun.com/app/domains" target="_blank" rel="noopener noreferrer">Get a Domain Name</a>.</small>
                                </div>

                                <!-- Region -->
                                <div class="my-3">

                                    <label class="form-label" >Region</label>

                                </div>

                                <div class="my-3 d-flex">
                                    <label style="line-height: 25px;" class="form-label radio-container mx-2" for="mail-baby-smtp-setting-mailgun-region-us">
                                        US
                                        <input type="radio" id="mail-baby-smtp-setting-mailgun-region-us" name="mail_baby_smtp_mailgun_region" value="US" checked="checked" <?php echo checked( esc_attr($options['mail_baby_smtp_mailgun_region']), 'US', false );?>>
                                        <span class="checkmark"></span>
                                    </label>

                                    <label style="line-height: 25px;" class="form-label radio-container mx-2" for="mail-baby-smtp-setting-mailgun-region-eu">
                                        EU
                                        <input type="radio" id="mail-baby-smtp-setting-mailgun-region-eu" name="mail_baby_smtp_mailgun_region" value="EU" <?php echo checked( esc_attr($options['mail_baby_smtp_mailgun_region']), 'EU', false );?>>
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                                <small class="desc">
                                    Define which endpoint you want to use for sending messages.<br>
                                    If you are operating under EU laws, you may be required to use EU region.                   
                                    <a href="https://www.mailgun.com/regions" rel="" target="_blank">More information</a> on Mailgun.com.               
                                </small>
                            </div>

                            <div class="mail-baby-smtp-mailer-option mail-baby-smtp-mailer-option-sendgrid hidden">

                                <!-- Mailer Title/Notice/Description -->

                                <div class="mail-baby-smtp-setting-row mail-baby-smtp-setting-row-content mail-baby-smtp-clear section-heading " id="mail-baby-smtp-setting-row-email-heading">

                                    <div class="mail-baby-smtp-setting-field">

                                        <h2>SendGrid</h2>

                                        <p class="desc"><a href="https://sendgrid.com" target="_blank" rel="noopener noreferrer">SendGrid</a> is one of the leading transactional email services,Over 80,000 paying customers trust SendGrid to send more than 90 billion emails every month. <br> <br> They provide users with 100 free emails per day.<br><br>Read Sendgrid <a href="https://sendgrid.com/docs/" target="_blank" rel="noopener noreferrer">API Documentation</a></p>

                                    </div>

                                </div>

                                <div class="my-3">
                                    <label class="form-label" for="from_email"><?php _e('From Email Address', 'mail-baby-smtp');?></label>
                                    <input class="mb-2 form-control" name="from_email4" type="text" id="from_email" value="<?php echo esc_html($options['from_email4']); ?>">
                                    <small class="description"><?php _e('The email address that emails are sent from. <br> If you\'re using an email provider (Yahoo, Outlook.com, etc) this should be your email address for that account.', 'mail-baby-smtp');?></small>
                                </div>

                                <div class="my-3">
                                    <label class="form-label" for="from_name"><?php _e('From Name', 'mail-baby-smtp');?></label>
                                    <input class="mb-2 form-control" name="from_name3" type="text" id="from_name" value="<?php echo esc_html($options['from_name3']); ?>">
                                    <small class="description"><?php _e('The Name that emails are sent from.', 'mail-baby-smtp');?></small>
                                </div>

                                <!-- API Key -->

                                <div class="my-3">
                                    <label class="form-label" for="mail_baby_smtp_sendgrid_api_key">API Key</label>
                                    <input class="mb-2 form-control" id="mail_baby_smtp_sendgrid_api_key" type="password" spellcheck="false" name="mail_baby_smtp_sendgrid_api_key" value="<?php echo isset($options['mail_baby_smtp_sendgrid_api_key']) ? esc_attr($options['mail_baby_smtp_sendgrid_api_key']) : ''; ?>" id="mail-baby-smtp-setting-sendgrid-api_key">
                                    <small class="desc">
                                        Follow this link to get an API Key from SendGrid: <a href="https://app.sendgrid.com/settings/api_keys" target="_blank" rel="noopener noreferrer">Create API Key</a>.                    <br>
                                        To send emails you will need only a <code>Mail Send</code> access level for this API key.               
                                    </small>
                                </div>
                                
                            </div>

                            <div class="mail-baby-smtp-mailer-option mail-baby-smtp-mailer-option-gmail hidden">

                                <!-- Mailer Title/Notice/Description -->

                                <div class="mail-baby-smtp-setting-row mail-baby-smtp-setting-row-content mail-baby-smtp-clear section-heading " id="mail-baby-smtp-setting-row-email-heading">

                                    <div class="mail-baby-smtp-setting-field">

                                        <h2>Gmail</h2>

                                    </div>

                                </div>

                                    <div class="my-3">
                                        <label class="form-label" for="from_email"><?php _e('From Email Address', 'mail-baby-smtp');?></label>
                                        <input class="mb-2 form-control" name="from_email5" type="text" id="from_email" value="<?php echo esc_html($options['from_email5']); ?>">
                                        <small class="description"><?php _e('The email address that emails are sent from. <br> If you\'re using an email provider (Yahoo, Outlook.com, etc) this should be your email address for that account.', 'mail-baby-smtp');?></small>
                                    </div>

                                    <div class="my-3">
                                        <label class="form-label" for="from_name"><?php _e('From Name', 'mail-baby-smtp');?></label>
                                        <input class="mb-2 form-control" name="from_name4" type="text" id="from_name" value="<?php echo esc_html($options['from_name4']); ?>">
                                        <small class="description"><?php _e('The Name that emails are sent from.', 'mail-baby-smtp');?></small>
                                    </div>

                                    <div class="my-3">
                                        <label class="form-label d-flex"><?php _e('SMTP Status', 'mail-baby-smtp');?></label> 
                                        <?php if(isset($options['oauth_access_token']) && !empty($options['oauth_access_token'])){ ?>
                                         <img src="<?php echo esc_url(MAIL_BABY_SMTP_URL.'/images/connected.png');?>" style="height: 30px;">
                                            <small class="description"><?php _e('Connected', 'mail-baby-smtp');?></small> 
                                        <?php }
                                        else{ ?>
                                         <img src="<?php echo esc_url(MAIL_BABY_SMTP_URL.'/images/not-connected.png');?>" style="height: 30px;">
                                            <small class="description"><?php _e('Not Connected', 'mail-baby-smtp');?></small>     
                                        <?php } ?>
                                    </div>

                                    <div class="my-3">
                                          <label class="form-label" for="oauth_redirect_uri"><?php _e('Authorized Redirect URI', 'mail-baby-smtp');?></label> 
                                         <input class="mb-2 form-control" id="oauth_redirect_uri" name="oauth_redirect_uri" type="text" id="oauth_redirect_uri" value="<?php echo esc_url_raw(admin_url("options-general.php?page=mail-baby-smtp-settings&action=oauth_grant")); ?>" readonly >
                                        <small class="description"><?php _e('Copy this URL into your web application', 'mail-baby-smtp');?></small> 
                                    </div>

                                    <div class="my-3">
                                        <label class="form-label" for="oauth_client_id"><?php _e('Client ID', 'mail-baby-smtp');?></label> 
                                        <input class="mb-2 form-control" id="oauth_client_id" name="oauth_client_id" type="text" id="oauth_client_id" value="<?php echo esc_attr($options['oauth_client_id']); ?>" >
                                        <small class="description"><?php _e('The client ID of your web application', 'mail-baby-smtp');?></small> 
                                    </div>

                                    <div class="my-3">
                                        <label class="form-label" for="oauth_client_secret"><?php _e('Client Secret', 'mail-baby-smtp');?></label> 
                                        <input class="mb-2 form-control" id="oauth_client_secret" name="oauth_client_secret" type="text" id="oauth_client_secret" value="<?php echo esc_attr($options['oauth_client_secret']); ?>" >
                                        <small class="description"><?php _e('The client secret of your web application', 'mail-baby-smtp');?></small> 
                                    </div>
                                      
                                    <div class="my-3">
                                        <label class="form-label" for="oauth_user_email"><?php _e('OAuth Email Address', 'mail-baby-smtp');?></label> 
                                        <input class="mb-2 form-control" id="oauth_user_email" name="oauth_user_email" type="text" id="oauth_user_email" value="<?php echo esc_attr($options['oauth_user_email']); ?>" >
                                        <small class="description"><?php _e('The email address that you will use for SMTP authentication. This should be the same email used in the Google Developers Console.', 'mail-baby-smtp');?></small>
                                    </div>
                                     
                                    <div class="my-2">
                                      <label class="form-label" for="type_of_encryption"><?php _e('Type of Encryption', 'mail-baby-smtp');?></label> 
                                        <select class="mb-2 form-control" name="type_of_encryption" id="type_of_encryption">
                                            <option value="tls" <?php echo selected( esc_attr($options['type_of_encryption']), 'tls', false );?>><?php _e('TLS', 'mail-baby-smtp');?></option>
                                            <option value="ssl" <?php echo selected( esc_attr($options['type_of_encryption']), 'ssl', false );?>><?php _e('SSL', 'mail-baby-smtp');?></option>
                                        </select>
                                        <small class="description"><?php _e('The encryption which will be used when sending an email (TLS is recommended).', 'mail-baby-smtp');?></small>
                                    </div>

                                    <div class="my-3">
                                        <label class="form-label" for="smtp_port"><?php _e('SMTP Port', 'mail-baby-smtp');?></label> 
                                        <input class="mb-2 form-control" name="smtp_port" type="text" id="smtp_port" value="<?php echo esc_attr($options['smtp_port']); ?>" >
                                        <small class="description"><?php _e('The port which will be used when sending an email. If you choose TLS it should be set to 587. For SSL use port 465 instead.', 'mail-baby-smtp');?></small> 
                                    </div>

                                    <div>
                                        <input class="form-control" name="disable_ssl_verification" type="checkbox" id="disable_ssl_verification" <?php checked(esc_attr($options['disable_ssl_verification']), 1); ?> value="1">
                                        <label class="form-label" for="disable_ssl_verification"><?php _e('Disable SSL Certificate Verification', 'mail-baby-smtp');?></label> 
                                    </div>
                                    <small class="description"><?php _e('As of PHP 5.6 you will get a warning/error if the SSL certificate on the server is not properly configured. You can check this option to disable that default behaviour. Please note that PHP 5.6 made this change for a good reason. So you should get your host to fix the SSL configurations instead of bypassing it', 'mail-baby-smtp');?></small>
                            </div>

                            <div class="mail-baby-smtp-mailer-option mail-baby-smtp-mailer-option-smtp hidden">

                                <!-- Mailer Title/Notice/Description -->

                                <div class="mail-baby-smtp-setting-row mail-baby-smtp-setting-row-content mail-baby-smtp-clear section-heading " id="mail-baby-smtp-setting-row-email-heading">

                                    <div class="mail-baby-smtp-setting-field">

                                        <h2>Other SMTP</h2>



                                        <p class="desc">Use the SMTP details provided by your hosting provider or email service.<br><br>To see recommended settings for the popular services, as well as troubleshooting tips.</p>

                                    </div>

                                </div>

                                <div class="my-3">
                                    <label class="form-label" for="from_email"><?php _e('From Email Address', 'mail-baby-smtp');?></label>
                                    <input class="mb-2 form-control" name="from_email6" type="text" id="from_email" value="<?php echo esc_html($options['from_email6']); ?>">
                                    <small class="description"><?php _e('The email address that emails are sent from. <br> If you\'re using an email provider (Yahoo, Outlook.com, etc) this should be your email address for that account.', 'mail-baby-smtp');?></small>
                                </div>

                                <div class="my-3">
                                    <label class="form-label" for="from_name"><?php _e('From Name', 'mail-baby-smtp');?></label>
                                    <input class="mb-2 form-control" name="from_name5" type="text" id="from_name" value="<?php echo esc_html($options['from_name5']); ?>">
                                    <small class="description"><?php _e('The Name that emails are sent from.', 'mail-baby-smtp');?></small>
                                </div>

                                <!-- SMTP Host -->

                                <div class="my-3">
                                    <label class="form-label" for="mail-baby-smtp-setting-smtp-host">SMTP Host</label>
                                    <input class="mb-2 form-control" id="mail-baby-smtp-setting-smtp-host" name="smtp_host" type="text" value="<?php echo esc_attr($options['smtp_host']); ?>" id="mail-baby-smtp-setting-smtp-host" spellcheck="false">
                                </div>

                                <!-- SMTP Encryption -->

                                <div class="my-3">
                                    <label class="form-label">Encryption</label>
                                </div>
                                <div class="my-3 d-flex">
                                    <label class="form-label radio-container mx-2" for="mail-baby-smtp-setting-smtp-enc-none">
                                        None
                                        <input type="radio" id="mail-baby-smtp-setting-smtp-enc-none" name="type_of_encryption" value="none" <?php echo checked( esc_attr($options['type_of_encryption']), 'none', false );?>>
                                        <span class="checkmark"></span>
                                    </label>
                                    <label class="form-label radio-container mx-2" for="mail-baby-smtp-setting-smtp-enc-ssl">
                                        SSL
                                        <input type="radio" id="mail-baby-smtp-setting-smtp-enc-ssl" name="type_of_encryption" value="ssl" <?php echo checked( esc_attr($options['type_of_encryption']), 'ssl', false );?>>
                                        <span class="checkmark"></span>
                                    </label>
                                    <label class="form-label radio-container mx-2" for="mail-baby-smtp-setting-smtp-enc-tls">
                                        TLS
                                        <input type="radio" id="mail-baby-smtp-setting-smtp-enc-tls" name="type_of_encryption" value="tls"  <?php echo checked( esc_attr($options['type_of_encryption']), 'tls', false );?>>
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                                <small class="desc">
                                    For most servers TLS is the recommended option. If your SMTP provider offers both SSL and TLS options, we recommend using TLS.              
                                </small>

                                <!-- SMTP Port -->

                                <div class="my-3">
                                    <label class="form-label" for="bsmtp_port">SMTP Port</label>
                                    <input class="mb-2 form-control" id="bsmtp_port" name="smtp_port" type="number" placeholder="25" value="<?php echo esc_attr($options['smtp_port']); ?>" id="mail-baby-smtp-setting-smtp-port" class="small-text" spellcheck="false">
                                </div>

                                <!-- PHPMailer SMTPAutoTLS -->

                                <div class="my-3">
                                    <label class="form-label" for="mail-baby-smtp-setting-smtp-autotls">Auto TLS</label>
                                    <select class="mb-2 form-control" name="smtp_auto_tls" id="smtp_auto_tls">
                                        <option value="true" <?php echo selected( esc_attr($options['smtp_auto_tls']), 'true', false );?>><?php _e('True', 'mail-baby-smtp');?></option>
                                        <option value="false" <?php echo selected( esc_attr($options['smtp_auto_tls']), 'false', false );?>><?php _e('False', 'mail-baby-smtp');?></option>
                                    </select>
                                    <small class="description"><?php _e('By default, TLS encryption is automatically used if the server supports it (recommended). In some cases, due to server misconfigurations, this can cause issues and may need to be disabled.', 'mail-baby-smtp');?></small>
                                </div>

                                <!-- SMTP Authentication -->

                                <div class="my-3">
                                    <label class="form-label" for="mail-baby-smtp-setting-smtp-auth">Authentication</label>
                                    <select class="mb-2 form-control" name="smtp_auth" id="smtp_auth">
                                        <option value="true" <?php echo selected( esc_attr($options['smtp_auth']), 'true', false );?>><?php _e('True', 'mail-baby-smtp');?></option>
                                        <option value="false" <?php echo selected( esc_attr($options['smtp_auth']), 'false', false );?>><?php _e('False', 'mail-baby-smtp');?></option>
                                    </select>
                                    <small class="description"><?php _e('Whether to use SMTP Authentication when sending an email (recommended: True).', 'mail-baby-smtp');?></small>
                                </div>

                                <!-- SMTP Username -->

                                <div class="my-3">
                                    <label class="form-label" for="smtp_username">SMTP Username</label>
                                    <input class="mb-2 form-control" id="smtp_username" name="smtp_username" type="text" value="<?php echo esc_attr($options['smtp_username']); ?>" id="mail-baby-smtp-setting-smtp-user" spellcheck="false" autocomplete="new-password">
                                </div>

                                <!-- SMTP Password -->

                                <div class="my-3">
                                    <label class="form-label" for="smtp_password">SMTP Password</label>
                                    <input class="mb-2 form-control" id="smtp_password" name="smtp_password" type="password" value="<?php echo esc_attr($options['smtp_password']); ?>" id="mail-baby-smtp-setting-smtp-pass" spellcheck="false" autocomplete="new-password">
                                    <small class="desc">
                                        The password is encrypted in the database, but for improved security we recommend using your site's WordPress configuration file to set your password.                                        
                                    </small>
                                </div>

                            </div>

                            <div class="mail-baby-smtp-mailer-option mail-baby-smtp-mailer-option-mailbaby hidden">

                                <!-- Mailer Title/Notice/Description -->

                                <div class="mail-baby-smtp-setting-row mail-baby-smtp-setting-row-content mail-baby-smtp-clear section-heading" id="mail-baby-smtp-setting-row-email-heading">

                                    <div class="mail-baby-smtp-setting-field">

                                        <h2>Mail Baby</h2>

                                        <p class="desc">MailBaby is an email smart host that offers outbound filtering. Emails are sent to MailBaby systems, and are analyzed for content. Email is then routed through an email zone based on the email content, and score of the email, or bounced as spam. IP reputation is handled by MailBaby. MailBaby monitors all our ips for blacklists, and works with email providers through feedback loops and other abuse monitoring to ensure email delivery.

                                        <br><br>Read our mail.baby documentation to learn how to configure mail.baby and improve your email deliverability.</p><p class="buttonned"><a href="https://www.mail.baby/tips/api/" target="_blank" rel="noopener noreferrer" class="mail-baby-smtp-btn mail-baby-smtp-btn-md mail-baby-smtp-btn-blueish">Get Started with mail.baby</a></p><p></p>

                                    </div>

                                </div>

                                <div class="my-3">
                                    <label class="form-label" for="from_email"><?php _e('From Email Address', 'mail-baby-smtp');?></label>
                                    <input class="mb-2 form-control" name="from_email" type="text" id="from_email" value="<?php echo esc_html($options['from_email']); ?>">
                                    <small class="description"><?php _e('The email address that emails are sent from. <br> If you\'re using an email provider (Yahoo, Outlook.com, etc) this should be your email address for that account.', 'mail-baby-smtp');?></small>
                                </div>

                                <div class="my-3">
                                    <label class="form-label" for="from_name"><?php _e('From Name', 'mail-baby-smtp');?></label>
                                    <input class="mb-2 form-control" name="from_name" type="text" id="from_name" value="<?php echo esc_html($options['from_name']); ?>">
                                    <small class="description"><?php _e('The Name that emails are sent from.', 'mail-baby-smtp');?></small>
                                </div>
                                

                                <!-- API Key -->

                                <div class="my-3">
                                    <label class="form-label" for="mail-baby-smtp-setting-smtpcom-api_key">API Key</label>
                                    <input class="mb-2 form-control" id="mail-baby-smtp-setting-smtpcom-api_key" type="password" spellcheck="false" name="mail_baby_api_key" value="<?php echo isset($options['mail_baby_api_key']) ? esc_attr($options['mail_baby_api_key']) : ''; ?>" id="mail-baby-smtp-setting-mailbaby-api_key">
                                    <small class="desc">
                                        Follow this link to get an API Key from mail.baby: <a href="https://mail.baby/settings/api" target="_blank" rel="noopener noreferrer">Get API Key</a>.              
                                    </small>
                                </div>

                                <!-- Channel/Sender -->

                                <div class="my-3">
                                    <label class="form-label" for="mail_baby_sender_name">Sender Name</label>
                                    <input class="mb-2 form-control" id="mail_baby_sender_name" name="mail_baby_sender_name" type="text" value="<?php echo isset($options['mail_baby_sender_name']) ? esc_attr($options['mail_baby_sender_name']) : ''; ?>" id="mail_baby_sender_name" spellcheck="false">
                                    <small class="desc">
                                        Follow this link to get a Sender Name from mail.baby: <a href="https://mail.baby/" target="_blank" rel="noopener noreferrer">Get Sender Name</a>.               
                                    </small>
                                </div>
                            </div>

                        </div>

                        <p class="submit"><input type="submit" name="MAIL_BABY_SMTP_update_settings" id="MAIL_BABY_SMTP_update_settings" class="btn btn-primary btn-sm" value="<?php _e('Save Changes', 'mail-baby-smtp')?>"></p>



                    </form>

                </div>

            </div>

        </div>

        

        <?php

		$options = MAIL_BABY_SMTP_get_option(); 

        if($this->can_grant_permission() && $options['mailer'] === 'gmail'  ){

        ?>

        <a class="button button-primary" href="<?php echo esc_url($_SERVER["REQUEST_URI"].'&action=oauth_grant'); ?>"><?php _e('Grant Permission', 'mail-baby-smtp');?></a>                             

        <?php

        }        

    }

    

    function can_grant_permission(){

        $options = MAIL_BABY_SMTP_get_option();    

	

        $grant_permission = true;

        if($options['mailer'] === 'gmail'){

			//echo "sd";

		

        if(!isset($options['oauth_client_id']) || empty($options['oauth_client_id'])){

            $grant_permission = false;

        }

        if(!isset($options['oauth_client_secret']) || empty($options['oauth_client_secret'])){

            $grant_permission = false;

        }

        if(!isset($options['oauth_access_token']) && empty($options['oauth_access_token'])){

            $grant_permission = false;

        }

			$grant_permission = true;

		}

        return $grant_permission;

    }



}



function MAIL_BABY_SMTP_get_option(){

    $options = get_option('MAIL_BABY_SMTP_options');

    return $options;



}



function MAIL_BABY_SMTP_update_option($new_options){



    



    $empty_options = MAIL_BABY_SMTP_get_empty_options_array();

    $options = MAIL_BABY_SMTP_get_option();

    if(is_array($options)){

        $current_options = array_merge($empty_options, $options);

        $updated_options = array_merge($current_options, $new_options);

        //print_r($empty_options);die;

        update_option('MAIL_BABY_SMTP_options', $updated_options);

        // SIB_Manager::ajax_validation_process();

    }

    else{

        $updated_options = array_merge($empty_options, $new_options);

        update_option('MAIL_BABY_SMTP_options', $updated_options);

    }

}



function MAIL_BABY_SMTP_get_empty_options_array(){

    $options = array();

    // $options['oauth_client_id'] = '';

    // $options['oauth_client_secret'] = '';

    // $options['oauth_access_token'] = '';

    // $options['oauth_user_email'] = '';

    // $options['from_email'] = '';

    // $options['from_name'] = '';

    // $options['type_of_encryption'] = '';

    // $options['smtp_port'] = '';

    // $options['disable_ssl_verification'] = '';



    $options['smtp_host'] = '';

    $options['smtp_auth'] = '';

    $options['smtp_username'] = '';

    $options['smtp_password'] = '';

    $options['type_of_encryption'] = '';

    $options['mailer'] = '';

    $options['smtp_port'] = '';

    $options['mail_baby_smtp_gmail_access_token'] = '';

    $options['disable_ssl_verification'] = '';

    $options['smtp_auto_tls'] = '';

    $options['mail_baby_api_key'] = '';

    $options['mail_baby_sender_name'] = '';

    $options['oauth_client_id'] = '';

    $options['oauth_client_secret'] = '';

    $options['oauth_access_token'] = '';

    $options['oauth_user_email'] = '';

    $options['from_email'] = '';
    
    $options['from_email1'] = '';

    $options['from_email2'] = '';

    $options['from_email3'] = '';

    $options['from_email4'] = '';

    $options['from_email5'] = '';

    $options['from_email6'] = '';

    $options['from_name'] = '';
    
    $options['from_name1'] = '';

    $options['from_name2'] = '';

    $options['from_name3'] = '';

    $options['from_name4'] = '';

    $options['from_name5'] = '';

    $options['type_of_encryption'] = '';

    $options['smtp_port'] = '';

    $options['disable_ssl_verification'] = '';

    $options['mail_baby_smtp_sendgrid_api_key'] = '';

    $options['mail_baby_smtp_sendgrid_domain'] = '';

    $options['mail_baby_smtp_sendinblue_api_key'] = '';

    $options['mail_baby_smtp_sendinblue_domain'] = '';

    $options['mail_baby_smtp_smtpcom_api_key'] = '';

    $options['mail_baby_smtp_smtpcom_sender_name'] = '';

    $options['mail_baby_smtp_mailgun_api_key'] = '';

    $options['mail_baby_smtp_mailgun_domain'] = '';

    $options['mail_baby_smtp_mailgun_region'] = '';

    $options['mail_baby_smtp_sendgrid_api_key'] = '';

    $options['mail_baby_smtp_sendgrid_domain'] = '';

    $options['mail_baby_smtp_smtpcom_api_key'] = '';

    $options['mail_baby_smtp_smtpcom_sender_name'] = '';

    $options['mail_baby_api_key'] = '';

    $options['mail_baby_sender_name'] = '';

    return $options;

}



function MAIL_BABY_SMTP_admin_notice() {        

    if(!is_MAIL_BABY_SMTP_configured()){

        ?>

        <div class="error">

            <p><?php _e('Mail Baby SMTP plugin cannot send email until you enter your credentials in the settings and grant access to your web application.', 'mail-baby-smtp'); ?></p>

        </div>

        <?php

    }

    if(version_compare(PHP_VERSION, '5.6', '<')){

        ?>

        <div class="error">

            <p><?php _e('Mail Baby SMTP plugin requires PHP 5.6 or higher. Please contact your web host to update your PHP version.', 'mail-baby-smtp'); ?></p>

        </div>

        <?php

    }

}



function is_MAIL_BABY_SMTP_configured() {

    $options = MAIL_BABY_SMTP_get_option();

    $smtp_configured = true;

    if(!isset($options['oauth_client_id']) || empty($options['oauth_client_id'])){

        $smtp_configured = false;

    }

    if(!isset($options['oauth_client_secret']) || empty($options['oauth_client_secret'])){

        $smtp_configured = false;

    }

    if(!isset($options['oauth_access_token']) || empty($options['oauth_access_token'])){

        $smtp_configured = false;

    }

    if(!isset($options['oauth_user_email']) || empty($options['oauth_user_email'])){

        $smtp_configured = false;

    }

    if(!isset($options['from_email']) || empty($options['from_email'])){

        $smtp_configured = false;

    }

    if(!isset($options['from_email1']) || empty($options['from_email1'])){

        $smtp_configured = false;

    }

    if(!isset($options['from_email2']) || empty($options['from_email2'])){

        $smtp_configured = false;

    }

    if(!isset($options['from_email3']) || empty($options['from_email3'])){

        $smtp_configured = false;

    }

    if(!isset($options['from_email4']) || empty($options['from_email4'])){

        $smtp_configured = false;

    }

    if(!isset($options['from_email5']) || empty($options['from_email5'])){

        $smtp_configured = false;

    }

    if(!isset($options['from_email6']) || empty($options['from_email6'])){

        $smtp_configured = false;

    }

    if(!isset($options['from_name']) || empty($options['from_name'])){

        $smtp_configured = false;

    }

    if(!isset($options['from_name1']) || empty($options['from_name1'])){

        $smtp_configured = false;

    }

    if(!isset($options['from_name2']) || empty($options['from_name2'])){

        $smtp_configured = false;

    }

    if(!isset($options['from_name3']) || empty($options['from_name3'])){

        $smtp_configured = false;

    }

    if(!isset($options['from_name4']) || empty($options['from_name4'])){

        $smtp_configured = false;

    }

    if(!isset($options['from_name5']) || empty($options['from_name5'])){

        $smtp_configured = false;

    }

    if(!isset($options['type_of_encryption']) || empty($options['type_of_encryption'])){

        $smtp_configured = false;

    }

    if(!isset($options['smtp_port']) || empty($options['smtp_port'])){

        $smtp_configured = false;

    }



    if(!isset($mbsmtp_options['mail_baby_smtp_sendgrid_api_key']) || empty($mbsmtp_options['mail_baby_smtp_sendgrid_api_key'])){

        $smtp_configured = false;

    }



    if(!isset($mbsmtp_options['mail_baby_smtp_sendgrid_domain']) || empty($mbsmtp_options['mail_baby_smtp_sendgrid_domain'])){

        $smtp_configured = false;

    }



    return $smtp_configured;

}



$GLOBALS['mail-baby-smtp'] = new MAIL_BABY_SMTP();



function MAIL_BABY_SMTP_pre_wp_mail($null, $atts)

{

    if ( isset( $atts['to'] ) ) {

            $to = $atts['to'];

    }



    if ( ! is_array( $to ) ) {

            $to = explode( ',', $to );

    }



    if ( isset( $atts['subject'] ) ) {

            $subject = $atts['subject'];

    }



    if ( isset( $atts['message'] ) ) {

            $message = $atts['message'];

    }



    if ( isset( $atts['headers'] ) ) {

            $headers = $atts['headers'];

    }



    if ( isset( $atts['attachments'] ) ) {

            $attachments = $atts['attachments'];

    }



    if ( ! is_array( $attachments ) ) {

            $attachments = explode( "\n", str_replace( "\r\n", "\n", $attachments ) );

    }

    

    require_once ABSPATH . WPINC . '/PHPMailer/PHPMailer.php';

    require_once ABSPATH . WPINC . '/PHPMailer/SMTP.php';

    require_once ABSPATH . WPINC . '/PHPMailer/Exception.php';

   

    include_once(plugin_dir_path( __FILE__ ).'templates/Gmail/google-api-php-client/vendor/autoload.php');

    include_once(plugin_dir_path( __FILE__ ).'templates/Gmail/class.phpmaileroauthgoogle.php');

    include_once(plugin_dir_path( __FILE__ ).'templates/Gmail/class.phpmaileroauth.php');



    $options = MAIL_BABY_SMTP_get_option();



    $phpmailer = new PHPMailerOAuth; /* this must be the custom class we created */



    // Tell PHPMailer to use SMTP

    $phpmailer->isSMTP();



    // Set AuthType

    $phpmailer->AuthType = 'XOAUTH2';



    // Whether to use SMTP authentication

    $phpmailer->SMTPAuth = true;



    // Set the encryption system to use - ssl (deprecated) or tls

    $phpmailer->SMTPSecure = $options['type_of_encryption'];



    // Set the hostname of the mail server

    $phpmailer->Host = 'smtp.gmail.com';



    // Set the SMTP port number - 587 for authenticated TLS, a.k.a. RFC4409 SMTP submission

    $phpmailer->Port = $options['smtp_port'];



    $phpmailer->SMTPAutoTLS = false;



    if(isset($_POST['MAIL_BABY_SMTP_send_test_email'])){

        $phpmailer->SMTPDebug = 1;

        // Ask for HTML-friendly debug output

        $phpmailer->Debugoutput = 'html';



    }





    //disable ssl certificate verification if checked

    if(isset($options['disable_ssl_verification']) && !empty($options['disable_ssl_verification'])){

        $phpmailer->SMTPOptions = array(

            'ssl' => array(

                'verify_peer' => false,

                'verify_peer_name' => false,

                'allow_self_signed' => true

            )

        );

    }

    // User Email to use for SMTP authentication - Use the same Email used in Google Developer Console

    $phpmailer->oauthUserEmail = $options['oauth_user_email'];



    //Obtained From Google Developer Console

    $phpmailer->oauthClientId = $options['oauth_client_id'];



    //Obtained From Google Developer Console

    $phpmailer->oauthClientSecret = $options['oauth_client_secret'];



    $gmail_token = json_decode($options['oauth_access_token'], true);



    //Obtained By running get_oauth_token.php after setting up APP in Google Developer Console.

    //Set Redirect URI in Developer Console as [https/http]://<yourdomain>/<folder>/get_oauth_token.php

    // eg: http://localhost/phpmail/get_oauth_token.php

    $phpmailer->oauthRefreshToken = $gmail_token['refresh_token'];    



    // Headers.

    $cc       = array();

    $bcc      = array();

    $reply_to = array();



    if ( empty( $headers ) ) {

            $headers = array();

    } else {

            if ( ! is_array( $headers ) ) {

                    // Explode the headers out, so this function can take

                    // both string headers and an array of headers.

                    $tempheaders = explode( "\n", str_replace( "\r\n", "\n", $headers ) );

            } else {

                    $tempheaders = $headers;

            }

            $headers = array();



            // If it's actually got contents.

            if ( ! empty( $tempheaders ) ) {

                    // Iterate through the raw headers.

                    foreach ( (array) $tempheaders as $header ) {

                            if ( strpos( $header, ':' ) === false ) {

                                    if ( false !== stripos( $header, 'boundary=' ) ) {

                                            $parts    = preg_split( '/boundary=/i', trim( $header ) );

                                            $boundary = trim( str_replace( array( "'", '"' ), '', $parts[1] ) );

                                    }

                                    continue;

                            }

                            // Explode them out.

                            list( $name, $content ) = explode( ':', trim( $header ), 2 );



                            // Cleanup crew.

                            $name    = trim( $name );

                            $content = trim( $content );



                            switch ( strtolower( $name ) ) {

                                    // Mainly for legacy -- process a "From:" header if it's there.

                                    case 'from':

                                            $bracket_pos = strpos( $content, '<' );

                                            if ( false !== $bracket_pos ) {

                                                    // Text before the bracketed email is the "From" name.

                                                    if ( $bracket_pos > 0 ) {

                                                            $from_name = substr( $content, 0, $bracket_pos - 1 );

                                                            $from_name = str_replace( '"', '', $from_name );

                                                            $from_name = trim( $from_name );

                                                    }



                                                    $from_email = substr( $content, $bracket_pos + 1 );

                                                    $from_email = str_replace( '>', '', $from_email );

                                                    $from_email = trim( $from_email );



                                                    // Avoid setting an empty $from_email.

                                            } elseif ( '' !== trim( $content ) ) {

                                                    $from_email = trim( $content );

                                            }

                                            break;

                                    case 'content-type':

                                            if ( strpos( $content, ';' ) !== false ) {

                                                    list( $type, $charset_content ) = explode( ';', $content );

                                                    $content_type                   = trim( $type );

                                                    if ( false !== stripos( $charset_content, 'charset=' ) ) {

                                                            $charset = trim( str_replace( array( 'charset=', '"' ), '', $charset_content ) );

                                                    } elseif ( false !== stripos( $charset_content, 'boundary=' ) ) {

                                                            $boundary = trim( str_replace( array( 'BOUNDARY=', 'boundary=', '"' ), '', $charset_content ) );

                                                            $charset  = '';

                                                    }



                                                    // Avoid setting an empty $content_type.

                                            } elseif ( '' !== trim( $content ) ) {

                                                    $content_type = trim( $content );

                                            }

                                            break;

                                    case 'cc':

                                            $cc = array_merge( (array) $cc, explode( ',', $content ) );

                                            break;

                                    case 'bcc':

                                            $bcc = array_merge( (array) $bcc, explode( ',', $content ) );

                                            break;

                                    case 'reply-to':

                                            $reply_to = array_merge( (array) $reply_to, explode( ',', $content ) );

                                            break;

                                    default:

                                            // Add it to our grand headers array.

                                            $headers[ trim( $name ) ] = trim( $content );

                                            break;

                            }

                    }

            }

    }



    // Empty out the values that may be set.

    $phpmailer->clearAllRecipients();

    $phpmailer->clearAttachments();

    $phpmailer->clearCustomHeaders();

    $phpmailer->clearReplyTos();



    // Set "From" name and email.



    // If we don't have a name from the input headers.

    if ( ! isset( $from_name ) ) {

            $from_name = $options['from_name'];//'WordPress';

    }

    if ( ! isset( $from_name1 ) ) {

        $from_name1 = $options['from_name1'];//'WordPress';

    }

    if ( ! isset( $from_name2 ) ) {

        $from_name2 = $options['from_name2'];//'WordPress';

    }

    if ( ! isset( $from_name3 ) ) {

        $from_name3 = $options['from_name3'];//'WordPress';

    }

    if ( ! isset( $from_name4 ) ) {

        $from_name4 = $options['from_name4'];//'WordPress';

    }

    if ( ! isset( $from_name5 ) ) {

        $from_name5 = $options['from_name5'];//'WordPress';

    }



    /*

     * If we don't have an email from the input headers, default to wordpress@$sitename

     * Some hosts will block outgoing mail from this address if it doesn't exist,

     * but there's no easy alternative. Defaulting to admin_email might appear to be

     * another option, but some hosts may refuse to relay mail from an unknown domain.

     * See https://core.trac.wordpress.org/ticket/5007.

     */

    if ( ! isset( $from_email ) ) {

            // Get the site domain and get rid of www.

            $sitename = wp_parse_url( network_home_url(), PHP_URL_HOST );

            if ( 'www.' === substr( $sitename, 0, 4 ) ) {

                    $sitename = substr( $sitename, 4 );

            }



            $from_email = $options['from_email'];//'wordpress@' . $sitename;
            $from_email1 = $options['from_email1'];//'wordpress@' . $sitename;
            $from_email2 = $options['from_email2'];//'wordpress@' . $sitename;
            $from_email3 = $options['from_email3'];//'wordpress@' . $sitename;
            $from_email4 = $options['from_email4'];//'wordpress@' . $sitename;
            $from_email5 = $options['from_email5'];//'wordpress@' . $sitename;
            $from_email6 = $options['from_email6'];//'wordpress@' . $sitename;

    }



    /**

     * Filters the email address to send from.

     *

     * @since 2.2.0

     *

     * @param string $from_email Email address to send from.

     */

    $from_email = apply_filters( 'wp_mail_from', $from_email );



    /**

     * Filters the name to associate with the "from" email address.

     *

     * @since 2.3.0

     *

     * @param string $from_name Name associated with the "from" email address.

     */

    $from_name = apply_filters( 'wp_mail_from_name', $from_name );



    try {

            $phpmailer->setFrom( $from_email, $from_name, false );

    } catch ( PHPMailer\PHPMailer\Exception $e ) {

            $mail_error_data = compact( 'to', 'subject', 'message', 'headers', 'attachments' );

            $mail_error_data['phpmailer_exception_code'] = $e->getCode();



            /** This filter is documented in wp-includes/pluggable.php */

            do_action( 'wp_mail_failed', new WP_Error( 'wp_mail_failed', $e->getMessage(), $mail_error_data ) );



            return false;

    }



    // Set mail's subject and body.

    $phpmailer->Subject = $subject;

    $phpmailer->Body    = $message;



    // Set destination addresses, using appropriate methods for handling addresses.

    $address_headers = compact( 'to', 'cc', 'bcc', 'reply_to' );



    foreach ( $address_headers as $address_header => $addresses ) {

            if ( empty( $addresses ) ) {

                    continue;

            }



            foreach ( (array) $addresses as $address ) {

                    try {

                            // Break $recipient into name and address parts if in the format "Foo <bar@baz.com>".

                            $recipient_name = '';



                            if ( preg_match( '/(.*)<(.+)>/', $address, $matches ) ) {

                                    if ( count( $matches ) == 3 ) {

                                            $recipient_name = $matches[1];

                                            $address        = $matches[2];

                                    }

                            }



                            switch ( $address_header ) {

                                    case 'to':

                                            $phpmailer->addAddress( $address, $recipient_name );

                                            break;

                                    case 'cc':

                                            $phpmailer->addCc( $address, $recipient_name );

                                            break;

                                    case 'bcc':

                                            $phpmailer->addBcc( $address, $recipient_name );

                                            break;

                                    case 'reply_to':

                                            $phpmailer->addReplyTo( $address, $recipient_name );

                                            break;

                            }

                    } catch ( PHPMailer\PHPMailer\Exception $e ) {

                            continue;

                    }

            }

    }



    // Set Content-Type and charset.



    // If we don't have a content-type from the input headers.

    if ( ! isset( $content_type ) ) {

            $content_type = 'text/plain';

    }



    /**

     * Filters the wp_mail() content type.

     *

     * @since 2.3.0

     *

     * @param string $content_type Default wp_mail() content type.

     */

    $content_type = apply_filters( 'wp_mail_content_type', $content_type );



    $phpmailer->ContentType = $content_type;



    // Set whether it's plaintext, depending on $content_type.

    if ( 'text/html' === $content_type ) {

            $phpmailer->isHTML( true );

    }



    // If we don't have a charset from the input headers.

    if ( ! isset( $charset ) ) {

            $charset = get_bloginfo( 'charset' );

    }



    /**

     * Filters the default wp_mail() charset.

     *

     * @since 2.3.0

     *

     * @param string $charset Default email charset.

     */

    $phpmailer->CharSet = apply_filters( 'wp_mail_charset', $charset );



    // Set custom headers.

    if ( ! empty( $headers ) ) {

            foreach ( (array) $headers as $name => $content ) {

                    // Only add custom headers not added automatically by PHPMailer.

                    if ( ! in_array( $name, array( 'MIME-Version', 'X-Mailer' ), true ) ) {

                            try {

                                    $phpmailer->addCustomHeader( sprintf( '%1$s: %2$s', $name, $content ) );

                            } catch ( PHPMailer\PHPMailer\Exception $e ) {

                                    continue;

                            }

                    }

            }



            if ( false !== stripos( $content_type, 'multipart' ) && ! empty( $boundary ) ) {

                    $phpmailer->addCustomHeader( sprintf( 'Content-Type: %s; boundary="%s"', $content_type, $boundary ) );

            }

    }



    if ( ! empty( $attachments ) ) {

            foreach ( $attachments as $attachment ) {

                    try {

                            $phpmailer->addAttachment( $attachment );

                    } catch ( PHPMailer\PHPMailer\Exception $e ) {

                            continue;

                    }

            }

    }



    /**

     * Fires after PHPMailer is initialized.

     *

     * @since 2.2.0

     *

     * @param PHPMailer $phpmailer The PHPMailer instance (passed by reference).

     */

    do_action_ref_array( 'phpmailer_init', array( &$phpmailer ) );



    // Send!

    try {

            return $phpmailer->send();

    } catch ( PHPMailer\PHPMailer\Exception $e ) {



            $mail_error_data = compact( 'to', 'subject', 'message', 'headers', 'attachments' );

            $mail_error_data['phpmailer_exception_code'] = $e->getCode();



            /**

             * Fires after a PHPMailer\PHPMailer\Exception is caught.

             *

             * @since 4.4.0

             *

             * @param WP_Error $error A WP_Error object with the PHPMailer\PHPMailer\Exception message, and an array

             *                        containing the mail recipient, subject, message, headers, and attachments.

             */

            do_action( 'wp_mail_failed', new WP_Error( 'wp_mail_failed', $e->getMessage(), $mail_error_data ) );



            return false;

    }

    

}

