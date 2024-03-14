<?php
/**
 * Short description: Booking calendar created by Innate Images, LLC
 * PHP Version 8.0
 
 * @category  VRCEmail_Class
 * @package   VRCEmail_Class
 * @author    Innate Images, LLC <info@innateimagesllc.com>
 * @copyright 2015 Innate Images, LLC
 * @license   GPL-2.0+ http://www.vrcalendarsync.com
 * @link      http://www.vrcalendarsync.com
 */

 /**
  * Short description: Booking calendar created by Innate Images, LLC
  * VRCEmail Class Doc Comment
  * 
  * VRCEmail Class
  * 
  * @category  VRCEmail_Class
  * @package   VRCEmail_Class
  * @author    Innate Images, LLC <info@innateimagesllc.com>
  * @copyright 2015 Innate Images, LLC
  * @license   GPL-2.0+ http://www.vrcalendarsync.com
  * @link      http://www.vrcalendarsync.com
  */
class VRCEmail extends VRCSingleton
{

    /**
     * Send email based on web instance
     * 
     * @param array  $recipients send to
     * @param string $subject    email subject
     * @param string $content    email content
     * @param string $template   email template
     * @param string $from_email email from
     * 
     * @return String
     */
    public function sendMail($recipients, $subject, $content, $template= 'general', $from_email='')
    {
        $sitename = sanitize_url($_SERVER['SERVER_NAME']);
        
        if (substr($sitename, 0, 4) == 'www.' ) {
            $sitename = substr($sitename, 4);
        }
        if (empty($from_email)) {
            $from_email_id = 'noreply@'.$sitename;
        } else {
            $from_email_id = $from_email;
        }
        // Generate Headers
        $header['From']         = get_bloginfo('name') . " <".$from_email_id.">";
        $header['X-Mailer']     = "PHP" . phpversion() . "";
        $header['Content-Type'] = get_option('html_type') . "; charset=\"". get_option('blog_charset') . "\"";

        foreach ( $header as $key => $value ) {
            $headers[$key] = $key . ": " . $value;
        }
        $headers = implode("\n", $headers);
        $headers .= "\n";

        // Main Recipient
        $email = array_pop($recipients);

        // BCC Recipients
        if (sizeof($recipients)>0) {
            $bcc = 'Bcc: ' . implode(', ', $recipients);
            $headers .= "$bcc\n";
        }

        // Filter
        $headers = apply_filters('vrcalendar_send_mail_headers', $headers);
        $vars = array('heading'=>$subject, 'content'=>$content);
        $message = $this->processTemplate($template, $vars);

        // Send email
        wp_mail($email, $subject, $message, $headers);
    }

    /**
     * Process template based on web instance
     * 
     * @param string $template email template
     * @param string $vars     email variable
     * 
     * @return String
     */
    public function processTemplate($template, $vars)
    {
        /* Now setup template data */
        $vars['email_url'] =  VRCALENDAR_PLUGIN_URL . "/Public/Views/Email/{$template}/";

        $template_content = file_get_contents(VRCALENDAR_PLUGIN_DIR . "/Public/Views/Email/{$template}/index.html");
        foreach ($vars as $var=>$val) {
            $template_content = str_replace('{'.$var.'}', $val, $template_content);
        }
        return $template_content;
    }
}
