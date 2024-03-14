<?php

namespace WPPayForm\App\Services\Integrations\Slack;

use WPPayForm\Framework\Foundation\App;
use WPPayForm\Framework\Support\Arr;
use WPPayForm\App\Models\Form;

class Slack
{
    protected $settings = [];

    public static function handle($feed, $formData, $entry, $formId)
    {
        $settings = $feed['processedValues'];
        $slackTitle = Arr::get($settings, 'textTitle');
        $form = Form::getFormattedElements($formId);
        $formInputs = $form['input'];

        $fields = [];

        foreach ($formInputs as $dataKey => $dataValue) {
            $value = Arr::get($entry->form_data_formatted, $dataKey);
            $value = str_replace('&', '&amp;', $value);
            $value = str_replace('<', '&lt;', $value);
            $value = str_replace('>', "&gt;", $value);

            $item = array(
                "title" => Arr::get($dataValue, 'label'),
                "value" => $value,
                "short" => false
            );
            array_push($fields, $item);
        };

        if ($slackTitle === '') {
            $title = "New submission on " . $form->title;
        } else {
            $title = $slackTitle;
        }

        $slackHook = Arr::get($settings, 'webhook');

        $titleLink = admin_url(
            'admin.php?page=wppayform.php#/edit-form/'
            . $formId
            . "/entries/"
            . $entry->id . '/view'
        );

        $body = [
            'payload' => json_encode([
                'attachments' => [
                    [
                        'color'      => '#0078ff',
                        'fallback'   => $title,
                        'title'      => $title,
                        'title_link' => $titleLink,
                        'fields'     => $fields,
                        'footer'     => 'wppayform',
                        'ts'         => round(microtime(true) * 1000)
                    ]
                ]
            ])
        ];

        $result = wp_remote_post($slackHook, [
            'method'      => 'POST',
            'timeout'     => 30,
            'redirection' => 5,
            'httpversion' => '1.0',
            'headers'     => [],
            'body'        => $body,
            'cookies'     => []
        ]);

        if (is_wp_error($result)) {
            $status = 'failed';
            $message = $result->get_error_message();
        } else {
            $message = 'Slack feed has been successfully initiated and pushed data';
            $status = 'success';
        }

        return array(
            'status'  => $status,
            'message' => $message
        );
    }
}
