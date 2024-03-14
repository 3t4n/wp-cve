<?php

namespace WPPayForm\App\Modules\PDF\Templates;

use WPPayForm\App\Services\PlaceholderParser;
use WPPayForm\App\Modules\PDF\Templates\TemplateManager;
use WPPayForm\App\Models\Submission;

class GeneralTemplate extends TemplateManager
{

    public $headerHtml = '';

    public function __construct()
    {
        parent::__construct();
    }

    public function getDefaultSettings($form)
    {
        return [
            'header' => '<h2>PDF Title</h2>',
            'footer' => '<table width="100%"><tr><td width="50%">{DATE j-m-Y}</td><td width="50%"  style="text-align: right;" align="right">{PAGENO}/{nbpg}</td></tr></table>',
            'body'   => '{submission.all_input_field_html}'
        ];
    }

    public function getSettingsFields()
    {
        return array(
            [
                'key'       => 'header',
                'label'     => 'Header Content',
                'tips'      => 'Write your header content which will be shown every page of the PDF',
                'type' => 'wp-editor'
            ],
            [
                'key'        => 'body',
                'label'      => 'PDF Body Content',
                'tips'       => 'Write your Body content for actual PDF body',
                'type'  => 'wp-editor',
                'inline_tip' => defined('FLUENTFORMPRO') ?
                    sprintf(
                        __(
                            'You can use Conditional Content in PDF body, for details please check this %s. ',
                            'wp-payment-form'
                        ),
                        '<a target="_blank" href="https://wpmanageninja.com/docs/fluent-form/advanced-features-functionalities-in-wp-fluent-form/conditional-shortcodes-in-email-notifications-form-confirmation/">Documentation</a>'
                    ) : __(
                        'Conditional PDF Body Content is supported in Fluent Forms Pro Version',
                        'wp-payment-form'
                    ),

            ],
            [
                'key'        => 'footer',
                'label'      => 'Footer Content',
                'tips'       => 'Write your Footer content which will be shown every page of the PDF',
                'type'  => 'wp-editor',
                'inline_tip' => 'Write your Footer content which will be shown every page of the PDF',

            ]
        );
    }

    public function generatePdf($submissionId, $feed, $outPut = 'I', $fileName = '')
    {
        $settings = $feed['settings'];
        $submissionModel = new Submission();
        $submission = $submissionModel->getSubmission($submissionId);

        $formData = maybe_unserialize($submission->form_data_formatted, true);
        $settings = PlaceholderParser::parse($settings, $submission);
        if (!empty($settings['header'])) {
            $this->headerHtml = $settings['header'];
        }

        $htmlBody = $settings['body'];  // Inserts HTML line breaks before all newlines in a string

        $htmlBody = PlaceholderParser::parse($htmlBody, $submissionId, $formData);

        $footer = $settings['footer'];

        if (!$fileName) {
            $fileName = PlaceholderParser::parse($feed['name'], $submissionId, $formData);
            $fileName = sanitize_title($fileName, 'pdf-file', 'display');
        }

        return $this->pdfBuilder($fileName, $feed, $htmlBody, $footer, $outPut);
    }
}