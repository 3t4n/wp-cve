<?php

namespace CODNetwork\Services;

class CODN_Email_Service
{
    const EMAIL_REPORTING_TO = '';

    const EMAIL_SUBJECT = 'Support required: log file in attachment';

    public function codn_send_log_via_email(string $attachments): bool
    {
        $result = false;

        if (!empty($attachments)) {
            $body = sprintf(
                '<p>Hello team,<br> I have trouble with the plugin please check the log file to solve it.</p><h4>====COD.network Version: %s ====</h4> <p>Please check the log file in the attachment to view the activity of plugin</p> <h4>====Store: %s ====</h4> ',
                COD_PLUGIN_VERSION,
                get_site_url()
            );
            $headers = ['Content-Type: text/html; charset=UTF-8'];
            $headers[] = sprintf('From: Plugin COD.network <%s>', get_option('admin_email'));
            $result = wp_mail(self::EMAIL_REPORTING_TO, self::EMAIL_SUBJECT, $body, $headers, $attachments);
            $this->info('a new email was sent with attachment logs');
        }

        return $result;
    }
}
