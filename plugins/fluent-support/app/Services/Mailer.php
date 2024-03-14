<?php

namespace FluentSupport\App\Services;

use FluentSupport\App\App;
use FluentSupport\Framework\Support\Arr;

class Mailer
{
    public static function send($to, $subject, $body, $extraHeader = [], $attachments = [])
    {
        $headers = self::getHeaders();

        if($extraHeader) {
            foreach ($extraHeader as $header) {
                $headers[] = $header;
            }
        }

        return wp_mail($to, $subject, $body, $headers, $attachments);
    }

    public static function getHeaders()
    {
        $headers[] = 'Content-Type: text/html; charset=UTF-8';
        return $headers;
    }

    public static function getWithTemplate($body)
    {
        $app  = App::getInstance();
        return $app->view->make('emails.classic_template', [
            'email_body' => $body
        ]);
    }
}
