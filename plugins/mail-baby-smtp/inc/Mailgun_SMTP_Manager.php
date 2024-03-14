<?php


class Mailgun
{
    /**
     * Setup shared functionality for Admin and Front End.
     *
     * @since    0.1
     */
    public function __construct()
    {
   		
        $this->options = get_option('MAIL_BABY_SMTP_options');
        $this->plugin_file = __FILE__;
        $this->plugin_basename = plugin_basename($this->plugin_file);
        $options = get_option('MAIL_BABY_SMTP_options');
        if ($options['mailer'] === 'mailgun'):
        	// /die;
            //if (!include dirname(__FILE__) . '/inc/templates/Mailgun/wp-mail-api.php'):
         	  include dirname(__FILE__) . '/templates/Mailgun/wp-mail-api.php';
            //endif;
        endif;

    }

    /**
     * Get specific option from the options table.
     *
     * @param    string $option  Name of option to be used as array key for retrieving the specific value
     * @param    array  $options Array to iterate over for specific values
     * @param    bool   $default False if no options are set
     *
     * @return    mixed
     *
     * @since    0.1
     */
    public function get_option($option, $options = null, $default = false)
    {
        if (is_null($options)):
            $options = &$this->options;
        endif;
        if (isset($options[ $option ])):
            return $options[ $option ];
        else:
            return $default;
        endif;
    }

    /**
     * Hook into phpmailer to override SMTP based configurations
     * to use the Mailgun SMTP server.
     *
     * @param    object $phpmailer The PHPMailer object to modify by reference
     *
     * @return    void
     *
     * @since    0.1
     */
    public function phpmailer_init(&$phpmailer)
    {
        
        $domain = (defined('MAILGUN_DOMAIN') && MAILGUN_DOMAIN) ? MAILGUN_DOMAIN : $this->get_option('MAIL_BABY_SMTP_options')['mail_baby_smtp_mailgun_domain'];
        $region = (defined('MAILGUN_REGION') && MAILGUN_REGION) ? MAILGUN_REGION : $this->get_option('MAIL_BABY_SMTP_options')['mail_baby_smtp_mailgun_region'];

        $smtp_endpoint = mg_smtp_get_region($region);
        $smtp_endpoint = (bool) $smtp_endpoint ? $smtp_endpoint : 'smtp.mailgun.org';
    }


    /**
     * Make a Mailgun api call.
     *
     * @param    string $uri    The endpoint for the Mailgun API
     * @param    array  $params Array of parameters passed to the API
     * @param    string $method The form request type
     *
     * @return    array
     *
     * @since    0.1
     */
    public function api_call($uri, $params = array(), $method = 'POST')
    {
        $options = get_option('MAIL_BABY_SMTP_options');
        $getRegion = (defined('MAILGUN_REGION') && MAILGUN_REGION) ? MAILGUN_REGION : $options[ 'mail_baby_smtp_mailgun_region' ];
        $apiKey = (defined('MAILGUN_APIKEY') && MAILGUN_APIKEY) ? MAILGUN_APIKEY : $options[ 'mail_baby_smtp_mailgun_api_key' ];
        $domain = (defined('MAILGUN_DOMAIN') && MAILGUN_DOMAIN) ? MAILGUN_DOMAIN : $options[ 'mail_baby_smtp_mailgun_domain' ];

        $region = mg_api_get_region($getRegion);
        $this->api_endpoint = ($region) ? $region : 'https://api.mailgun.net/v3/';

        $time = time();
        $url = $this->api_endpoint . $uri;
        $headers = array(
            'Authorization' => 'Basic ' . base64_encode("api:{$apiKey}"),
        );

        switch ($method) {
            case 'GET':
                $params[ 'sess' ] = '';
                $querystring = http_build_query($params);
                $url = $url . '?' . $querystring;
                $params = '';
                break;
            case 'POST':
            case 'PUT':
            case 'DELETE':
                $params[ 'sess' ] = '';
                $params[ 'time' ] = $time;
                $params[ 'hash' ] = sha1(date('U'));
                break;
        }

        // make the request
        $args = array(
            'method' => $method,
            'body' => $params,
            'headers' => $headers,
            'sslverify' => true,
        );

        // make the remote request
        $result = wp_remote_request($url, $args);
        if (!is_wp_error($result)):
            return $result[ 'body' ];
        else:
            return $result->get_error_message();
        endif;
    }
}

$mailgun = new Mailgun();



?>