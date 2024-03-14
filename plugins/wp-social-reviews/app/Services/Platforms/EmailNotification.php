<?php
namespace WPSocialReviews\App\Services\Platforms;

use WPSocialReviews\App\Services\GlobalSettings;
use WPSocialReviews\App\Services\Libs\Emogrifier\Emogrifier;
use WPSocialReviews\Framework\Support\Arr;

class EmailNotification
{
    public function __construct()
    {
        add_action('wpsr_email_report_scheduled_tasks', array($this, 'maybeSendFeedIssueEmail'));
    }

    public function send($subject, $title, $content)
    {
        $subject = apply_filters('wpsocialreviews/email_summary_subject', $subject);

        $settings = $this->getEmailReportSettings();

        if($settings['status'] === 'false'){
            return false;
        }

        $recipients = !empty( $settings['recipients'] ) ? str_replace( ' ', '', $settings['recipients'] ) : get_option( 'admin_email', '' );
        $all_emails = explode(',', $recipients);

        $valid_emails = [];
        foreach ($all_emails as $email) {
            $email = trim($email);
            if(is_email($email)) {
                $valid_emails[] = $email;
            }
        }

        if(!$valid_emails) {
            return false;
        }

        $logo = WPSOCIALREVIEWS_URL . 'assets/images/icon/wp-social-ninja-email-logo.png';

        $data = array(
            'subject' => $subject,
            'title'   => $title,
            'content' => $content,
            'logo'     => $logo,
            'footer_link' => admin_url('admin.php?page=wpsocialninja.php#/')
        );

        $emailBody = wpsrSocialReviews('view')->make('email.report.body', $data);

        $originalEmailBody = $emailBody;
        ob_start();
        try {
            // apply CSS styles inline for picky email clients
            $emogrifier = new Emogrifier($emailBody);
            $emailBody = $emogrifier->emogrify();
        } catch (\Exception $e) {

        }
        $maybeError = ob_get_clean();

        if ($maybeError) {
            $emailBody =  $originalEmailBody;
        }

        $from_name = esc_html( wp_specialchars_decode( get_bloginfo( 'name' ) ) );
        $email_from = $from_name . ' <' . get_option( 'admin_email', $valid_emails[0] ) . '>';
        $header_from  = "From: " . $email_from;
        $headers = [
            'Content-Type: text/html; charset=utf-8',
            $header_from
        ];

        $emailResult = wp_mail($valid_emails, $subject, $emailBody, $headers);

        return $emailResult;
    }

    public function getEmailReportSettings()
    {
        $defaults = [
            'status' => 'false',
            'recipients' => '',
            'sending_day' => 'Mon'
        ];

        $advance_settings = (new GlobalSettings())->getGlobalSettings('advance_settings');
        $settings = Arr::get($advance_settings, 'email_report');

        if($settings) {
            $settings = wp_parse_args($settings, $defaults);
        } else {
            $settings = $defaults;
        }

        return $settings;
    }
}