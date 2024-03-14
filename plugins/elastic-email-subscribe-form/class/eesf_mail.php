<?php

class eemail
{
    static $options = array(),
        $conflict = false;

    public static function on_load($pluginpath)
    {
        self::$options = get_option('ee_options');

        if (function_exists('wp_mail')) {
            self::$conflict = true;
            add_action('admin_notices', array(__CLASS__, 'adminNotices'));
            return;
        }

        if (self::is_configured() === false) {
            return;
        }

        require_once($pluginpath . '/api/ElasticEmailClient.php');
        \ElasticEmailClient\ApiClient::SetApiKey(self::getOption('ee_apikey'));

        function wp_mail($to, $subject, $message, $headers = '', $attachments = array())
        {
            try {
                $rs = eemail::send($to, $subject, $message, $headers, $attachments, $ee_channel);
                if ($rs !== true) {
                    return eemail::wp_mail_native($to, $subject, $message, $headers, $attachments, $rs);
                }
                return $rs;
            } catch (Exception $e) {
                return eemail::wp_mail_native($to, $subject, $message, $headers, $attachments, $e->getMessage());
            }
        }

    }

    static function send($to, $subject, $message, $headers, $attachments, $ee_channel = null)
    {
        $atts = apply_filters('wp_mail', compact('to', 'subject', 'message', 'headers', 'attachments'));
        if (isset($atts['to'])) {
            $to = $atts['to'];
        }
        if (isset($atts['subject'])) {
            $subject = $atts['subject'];
        }
        if (isset($atts['message'])) {
            $message = $atts['message'];
        }
        if (isset($atts['headers'])) {
            $headers = $atts['headers'];
        }
        if (isset($atts['attachments'])) {
            $attachments = $atts['attachments'];
        }
        if (!is_array($attachments)) {
            $attachments = explode("\n", str_replace("\r\n", "\n", $attachments));
        }
        $cc = $bcc = array();
        if (empty($headers)) {
            $headers = array();
        } else {
            if (!is_array($headers)) {
                $tempheaders = explode("\n", str_replace("\r\n", "\n", $headers));
            } else {
                $tempheaders = $headers;
            }
            $headers = array();
            $j = 1;
            if (!empty($tempheaders)) {
                foreach ((array)$tempheaders as $header) {
                    if (strpos($header, ':') === false) {
                        if (false !== stripos($header, 'boundary=')) {
                            $parts = preg_split('/boundary=/i', trim($header));
                            $boundary = trim(str_replace(array("'", '"'), '', $parts[1]));
                        }
                        continue;
                    }
                    list($name, $content) = explode(':', trim($header), 2);
                    $name = trim($name);
                    $content = trim($content);
                    switch (strtolower($name)) {
                        case 'from':
                            list($from_email, $from_name) = self::getEmailAndName($content);
                            break;
                        case 'content-type':
                            if (strpos($content, ';') !== false) {
                                list($type, $charset_content) = explode(';', $content);
                                $content_type = trim($type);
                                if (false !== stripos($charset_content, 'charset=')) {
                                    $charset = trim(str_replace(array('charset=', '"'), '', $charset_content));
                                } elseif (false !== stripos($charset_content, 'boundary=')) {
                                    $boundary = trim(str_replace(array('BOUNDARY=', 'boundary=', '"'), '', $charset_content));
                                    $charset = '';
                                }
                            } elseif ('' !== trim($content)) {
                                $content_type = trim($content);
                            }
                            break;
                        case 'cc':
                            $cc = array_merge((array)$cc, explode(',', $content));
                            break;
                        case 'bcc':
                            $bcc = array_merge((array)$bcc, explode(',', $content));
                            break;
                        case 'reply-to':
                            list($reply_to, $reply_to_name) = self::getEmailAndName($content);
                            break;
                        default:
                            //custom headers
                            $headers[('header' . $j++)] = sprintf('%1$s: %2$s', trim($name), trim($content));
                            break;
                    }
                }
            }
        }

        if (empty(get_option('ee_config_from_name'))) {
            if (!isset($from_name)) {
                $from_name = 'Wordpress';
            }
        } else {
            $from_name = get_option('ee_config_from_name');
        }

        if (empty(get_option('ee_config_from_email'))) {
            if (!isset($from_email)) {
                $from_email = 'wordpress@' . self::getDefaultDomain();
                update_option('ee_from_email', $from_email);
            }
        } else {
            $from_email = get_option('ee_config_from_email');
            update_option('ee_from_email', $from_email);
        }
        if (!isset($content_type)) {
            $content_type = 'text/plain';
        }

        $lostpassword = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_SPECIAL_CHARS);
        if (isset($lostpassword)) {
            if ($lostpassword === 'lostpassword') {
                $content_type = 'text/html';
            }
        }
        $content_type = apply_filters('wp_mail_content_type', $content_type);

        if (!isset($charset)) {
            $charset = get_bloginfo('charset');
        }
        
        if (!is_array($to)) {
            $to = array_merge(explode(',', $to));
        }
    
        $from_email = apply_filters('wp_mail_from', $from_email);
        $from_name = apply_filters('wp_mail_from_name', $from_name);
        $charset = apply_filters('wp_mail_charset', $charset);

        $Email = new \ElasticEmailClient\Email();

        if (!isset($reply_to)) {
            $reply_to = '';
        }
        if (!isset($reply_to_name)) {
            $reply_to_name = '';
        }

        $ee_channel = ($ee_channel === null) ? 'Elastic Email Sender' : 'Elastic Email - Send Test';

        $emailType = (get_option('ee_send-email-type') === 'transactional') ? true : false;

        $searchword = '';
        $matches = [];
        foreach($headers as $k=>$v) {
            if (preg_match("/\b$searchword\b/i", $v)) {
                $matches[$k] = $v;
            }
        }
        $isHeaderPlain = (sizeof($matches) >= 1) ? true : false;

        $sendPlain = true;
        if (get_option('ee_mimetype') === 'auto') {
            $sendPlain = $isHeaderPlain;
        } elseif (get_option('ee_mimetype') === 'texthtml') {
            $sendPlain = false;
        } else {
            $sendPlain = true;
        }

        $emailsend = $Email->Send($subject, $from_email, $from_name, null, null, null, null, $reply_to, $reply_to_name, array(), $to , $cc, $bcc, array(), array(), null, $ee_channel, $sendPlain ? null : $message  /* bodyHTML */, $sendPlain ? $message : null /* bodyText */, $charset, /*charsetBodyHtml*/ null, /*charsetBodyText*/ null, ApiTypes\EncodingType::None, null, $attachments, $headers, null, array(), array(), null, $emailType);

        if (isset($emailsend)) {
            if ($emailsend == TRUE) {
                return true;
            } else {
                return false;
            }
        }
    }

    static function wp_mail_native($to, $subject, $message, $headers, $attachments, $error)
    {
        error_log("eemail::wp_mail_native: $to ($subject) Error: $error");
        require_once plugin_dir_path(__DIR__) . 'defaults/function.wp_mail.php';
    }

    //Helpers method
    static function adminNotices()
    {
        if (self::$conflict) {
            echo '<div class="error"><p>wp_mail has been declared by another process or plugin, so you won\'t be able to use ElasticEmailSender until the problem is solved.</p></div>';
        }
    }

    static function is_configured()
    {
        return (self::getOption('ee_enable') === 'yes' && self::getOption('ee_apikey'));
    }

    static function getOption($name, $default = false)
    {
        if (isset(self::$options[$name])) {
            return self::$options[$name];
        }
        return $default;
    }

    static function getEmailAndName($content)
    {
        $address = array('', '');
        $bracket_pos = strpos($content, '<');
        if ($bracket_pos !== false) {
            // Text before the bracketed email is the "From" name.
            if ($bracket_pos > 0) {
                $address[1] = substr($content, 0, $bracket_pos - 1);
                $address[1] = str_replace('"', '', $address[1]);
                $address[1] = trim($address[1]);
            }

            $address[0] = substr($content, $bracket_pos + 1);
            $address[0] = str_replace('>', '', $address[0]);
            $address[0] = trim($address[0]);
            // Avoid setting an empty $email.
        } elseif ('' !== trim($content)) {
            $address[0] = trim($content);
        }
        return $address;
    }

    /* If we don't have an email from the input headers default to wordpress@$sitename
     * Some hosts will block outgoing mail from this address if it doesn't exist but
     * there's no easy alternative. Defaulting to admin_email might appear to be another
     * option but some hosts may refuse to relay mail from an unknown domain. See
     * https://core.trac.wordpress.org/ticket/5007.
     */

    static function getDefaultDomain()
    {
        // Get the site domain and get rid of www.
        $sitename = strtolower($_SERVER['SERVER_NAME']);
        if (substr($sitename, 0, 4) == 'www.') {
            $sitename = substr($sitename, 4);
        }
        return $sitename;
    }

}