<?php
namespace Adminz\Admin;
use Adminz\Admin\Adminz as Adminz;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class ADMINZ_Mailer extends Adminz {
    public $options_group = "adminz_mailer";
    public $title = 'Mailer';
    static $slug = 'adminz_mailer';
    static $options;
    static $test_sendmail;
    function __construct() {
        $this::$options = get_option('adminz_mailer', []);        
        add_action( 'admin_init', [$this, 'register_option_setting']);
        add_filter( 'adminz_setting_tab', [$this, 'register_tab']); 
        add_action( 'adminz_tabs_html',[$this,'tab_html']);

        if($this->get_option_value('adminz_mailer_disabled',"") !="on"){
            if(
                $this->get_option_value('adminz_mailer_host') and 
                $this->get_option_value('adminz_mailer_username') and 
                $this->get_option_value('adminz_mailer_password')

            ){
                add_filter('pre_wp_mail', [$this,'smtp_mailer_pre_wp_mail'], 10, 2);
            }            
        }
        
    }    
    function tab_html() {    
        if(!isset($_GET['tab']) or $_GET['tab'] !== self::$slug) return;
        ?>
        <table class="form-table">
            <tr valign="top">
                <th scope="row">
                    <h3> Email Test</h3>
                </th>
            </tr>
            <tr valign="top">
                <th scope="row">Your email checker</th>
                <td>
                    <form method="post" action="">
                        <input type="text" name="adminz_mailer[test_email]" value="<?php echo esc_attr($this->get_option_value('admin_email'));?>">
                        <button type="submit" class="button">Test Email</button>
                        <div>
                            <?php                                    
                            if(isset($_POST["adminz_mailer"]['test_email'])){
                                wp_mail( sanitize_email($_POST["adminz_mailer"]['test_email']), 'Test SMTP email function', 'OK!'); 
                            };
                            ?>
                        </div>
                    </form>                    
                </td>
            </tr>
        </table>
        <form method="post" action="options.php">
            <?php             
            settings_fields($this->options_group);
            do_settings_sections($this->options_group);
            ?>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row">
                        <h3> SMTP Config</h3>
                    </th>
                    <td>
                        <input type="checkbox" <?php if($this->check_option('adminz_mailer_disabled',false,"on")) echo 'checked'; ?> name="adminz_mailer[adminz_mailer_disabled]" />
                        <em>Disable this</em>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">Host</th>
                    <td>
                        <input type="text" name="adminz_mailer[adminz_mailer_host]" placeholder="smtp.gmail.com" value="<?php echo esc_attr($this->get_option_value('adminz_mailer_host')); ?>" />
                        <em>The SMTP server which will be used to send email. For example: smtp.gmail.com</em>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">Username</th>
                    <td>
                        <input type="text" name="adminz_mailer[adminz_mailer_username]" value="<?php echo esc_attr($this->get_option_value('adminz_mailer_username')); ?>" />
                        <em>Your SMTP Username.</em>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">Password</th>
                    <td>                        
                        <input type="text" name="adminz_mailer[adminz_mailer_password]" placeholder="Hidden information" value="<?php echo esc_attr($this->get_option_value('adminz_mailer_password'));?>" />
                        <em><?php if(!$this->get_option_value('adminz_mailer_password')){ echo '<mark>Current No password</mark>';} ?> 
                        Your SMTP Password (The saved password is not shown for security reasons. You must <b>re-enter</b> the password when saving the information again).</em>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">From</th>
                    <td>
                        <input type="email" name="adminz_mailer[adminz_mailer_from]" value="<?php echo esc_attr($this->get_option_value('adminz_mailer_from')); ?>" />
                        <em>The email address which will be used as the From Address if it is not supplied to the mail function.</em>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">FromName</th>
                    <td>
                        <input type="text" name="adminz_mailer[adminz_mailer_fromname]" value="<?php echo esc_attr($this->get_option_value('adminz_mailer_fromname')); ?>" />
                        <em>The name which will be used as the From Name if it is not supplied to the mail function.</em>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">Port</th>
                    <td>                        
                        <input type="number" name="adminz_mailer[adminz_mailer_port]" placeholder="587" value="<?php echo esc_attr($this->get_option_value('adminz_mailer_port')); ?>" />
                        <em>The port which will be used when sending an email (587/465/25). If you choose TLS it should be set to 587. For SSL use port 465 instead.</em>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">SMTPAuth</th>
                    <td>
                        <input type="checkbox" <?php if($this->check_option('adminz_mailer_smtpauth',false,"on")) echo 'checked'; ?> name="adminz_mailer[adminz_mailer_smtpauth]" />
                        <em>Whether to use SMTP Authentication when sending an email (recommended: True).</em>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">SMTPSecure</th>
                    <td>
                        <?php $secure = $this->get_option_value('adminz_mailer_smtpsecure'); ?>                        
                        <select name="adminz_mailer[adminz_mailer_smtpsecure]">
                            <option value="tls" <?php if($secure == 'tls') echo 'selected'; ?>>TLS</option>
                            <option value="ssl" <?php if($secure == 'ssl') echo 'selected'; ?>>SSL</option>
                        </select>
                        <em>The encryption which will be used when sending an email (recommended: TLS).</em>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">Enable SSL Certificate Verification</th>
                    <td>     
                        <input type="checkbox" <?php if($this->check_option('enable_ssl',false,"on")) echo 'checked'; ?> name="adminz_mailer[enable_ssl]" />
                        <em>As of PHP 5.6 you will get a warning/error if the SSL certificate on the server is not properly configured. You can check this option to disable that default behaviour. Please note that PHP 5.6 made this change for a good reason. So you should get your host to fix the SSL configurations instead of bypassing it</em>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">
                        <h3> Server info</h3>
                    </th>
                    <td>
                        <?php $this->server_info_settings(); ?>
                    </td>
                </tr>
            </table>
            <em>This plugin copy function from <a target="_blank" href="https://vi.wordpress.org/plugins/smtp-mailer/">SMTP Mailer</a></em>
            <?php submit_button(); ?>
        </form>        
        <?php
    }
    function register_tab($tabs) {
        if(!$this->title) return;
        $this->title = $this->get_icon_html('email').$this->title;
        $tabs[self::$slug] = array(
            'title' => $this->title,
            'slug' => self::$slug,
        );
        return $tabs;
    }
    function register_option_setting() {        
        register_setting($this->options_group, 'adminz_mailer');
    }
    function server_info_settings() {
        // clone from smtp mailer
        $server_info = '';
        $server_info .= sprintf('OS: %s%s', php_uname(), PHP_EOL);
        $server_info .= sprintf('PHP version: %s%s', PHP_VERSION, PHP_EOL);
        $server_info .= sprintf('WordPress version: %s%s', get_bloginfo('version'), PHP_EOL);
        $server_info .= sprintf('WordPress multisite: %s%s', (is_multisite() ? 'Yes' : 'No'), PHP_EOL);
        $openssl_status = 'Available';
        $openssl_text = '';
        if(!extension_loaded('openssl') && !defined('OPENSSL_ALGO_SHA1')){
            $openssl_status = 'Not available';
            $openssl_text = ' (openssl extension is required in order to use any kind of encryption like TLS or SSL)';
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
        ?>
        <textarea rows="10" cols="50" class="large-text code" disabled><?php echo esc_attr($server_info);?></textarea>
        <?php
    }
    function smtp_mailer_pre_wp_mail($null, $atts) {        
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
                if ( ! is_array( $attachments ) ) {
                        $attachments = explode( "\n", str_replace( "\r\n", "\n", $attachments ) );
                }
        }
        
        
        $options = array (
            'smtp_host' => $this::$options['adminz_mailer_host'],
            'smtp_auth' => $this::$options['adminz_mailer_smtpauth'] == "on" ? 'true' : 'false',
            'smtp_username' => $this::$options['adminz_mailer_username'],
            'smtp_password' => base64_encode($this::$options['adminz_mailer_password']),
            'type_of_encryption' => $this::$options['adminz_mailer_smtpsecure'],
            'smtp_port' => $this::$options['adminz_mailer_port'],
            'from_email' => $this::$options['adminz_mailer_from'],
            'from_name' => $this::$options['adminz_mailer_fromname'],
            'disable_ssl_verification' => (isset($this::$options['enable_ssl']) and $this::$options['enable_ssl']) ? "" : "1"
        );


        
        global $phpmailer;

        // (Re)create it, if it's gone missing.
        if ( ! ( $phpmailer instanceof PHPMailer\PHPMailer\PHPMailer ) ) {
                require_once ABSPATH . WPINC . '/PHPMailer/PHPMailer.php';
                require_once ABSPATH . WPINC . '/PHPMailer/SMTP.php';
                require_once ABSPATH . WPINC . '/PHPMailer/Exception.php';
                $phpmailer = new PHPMailer(true);

                $phpmailer::$validator = static function ( $email ) {
                        return (bool) is_email( $email );
                };
        }        
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
        
        if($from_email){
            try {
                    $phpmailer->setFrom( $from_email, $from_name, false );
            } catch ( PHPMailer\PHPMailer\Exception $e ) {
                    $mail_error_data                             = compact( 'to', 'subject', 'message', 'headers', 'attachments' );
                    $mail_error_data['phpmailer_exception_code'] = $e->getCode();

                    /** This filter is documented in wp-includes/pluggable.php */
                    do_action( 'wp_mail_failed', new WP_Error( 'wp_mail_failed', $e->getMessage(), $mail_error_data ) );

                    return false;
            }
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
                                                if($address){
                                                    $phpmailer->addAddress( $address, $recipient_name );
                                                }
                                                break;
                                        case 'cc':
                                                if($address){
                                                    $phpmailer->addCc( $address, $recipient_name );
                                                }
                                                break;
                                        case 'bcc':
                                                if($address){
                                                    $phpmailer->addBcc( $address, $recipient_name );
                                                }
                                                break;
                                        case 'reply_to':
                                                if($address){
                                                    $phpmailer->addReplyTo( $address, $recipient_name );
                                                }
                                                break;
                                }
                        } catch ( PHPMailer\PHPMailer\Exception $e ) {
                                continue;
                        }
                }
        }

        // Tell PHPMailer to use SMTP
        $phpmailer->isSMTP(); //$phpmailer->isMail();
        // Set the hostname of the mail server
        $phpmailer->Host = $options['smtp_host'];
        // Whether to use SMTP authentication
        if(isset($options['smtp_auth']) && $options['smtp_auth'] == "true"){
            $phpmailer->SMTPAuth = true;
            // SMTP username
            $phpmailer->Username = $options['smtp_username'];
            // SMTP password
            $phpmailer->Password = base64_decode($options['smtp_password']);  
        }
        // Whether to use encryption
        $type_of_encryption = $options['type_of_encryption'];
        if($type_of_encryption=="none"){
            $type_of_encryption = '';  
        }
        $phpmailer->SMTPSecure = $type_of_encryption;
        // SMTP port
        $phpmailer->Port = $options['smtp_port'];  

        // Whether to enable TLS encryption automatically if a server supports it
        $phpmailer->SMTPAutoTLS = false;
        //enable debug when sending a test mail
        if(isset($_POST["adminz_mailer"]['test_email'])){
            $phpmailer->SMTPDebug = 4;
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

        if ( isset( $attachments ) && ! empty( $attachments ) ) {
                foreach ( $attachments as $attachment ) {
                    if($attachment){
                        try {
                                $phpmailer->addAttachment( $attachment );
                        } catch ( PHPMailer\PHPMailer\Exception $e ) {
                                continue;
                        }
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

        $mail_data = compact( 'to', 'subject', 'message', 'headers', 'attachments' );
        // Send!
        try {            
                $send = $phpmailer->send();

                /**
                 * Fires after PHPMailer has successfully sent a mail.
                 *
                 * The firing of this action does not necessarily mean that the recipient received the
                 * email successfully. It only means that the `send` method above was able to
                 * process the request without any errors.
                 *
                 * @since 5.9.0
                 *
                 * @param array $mail_data An array containing the mail recipient, subject, message, headers, and attachments.
                 */
                do_action( 'wp_mail_succeeded', $mail_data );

                return $send;
        } catch ( PHPMailer\PHPMailer\Exception $e ) {
                $mail_data['phpmailer_exception_code'] = $e->getCode();

                /**
                 * Fires after a PHPMailer\PHPMailer\Exception is caught.
                 *
                 * @since 4.4.0
                 *
                 * @param WP_Error $error A WP_Error object with the PHPMailer\PHPMailer\Exception message, and an array
                 *                        containing the mail recipient, subject, message, headers, and attachments.
                 */
                do_action( 'wp_mail_failed', new WP_Error( 'wp_mail_failed', $e->getMessage(), $mail_data ) );

                return false;
        }
    }


}
