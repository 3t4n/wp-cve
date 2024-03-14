<?php

namespace WPPayForm\App\Modules\PDF\Templates;

use WPPayForm\App\Services\PlaceholderParser;
use WPPayForm\App\Modules\PDF\Templates\TemplateManager;
use WPPayForm\App\Models\Submission;
use WPPayForm\Framework\Support\Arr;

class CustomTemplate extends TemplateManager
{

    public $headerHtml = '';

    public function __construct()
    {
        parent::__construct();
    }

    public function getDefaultSettings($form)
    {
        return [
            'pdf_content' => [
                'header' => '<h2>PDF Title</h2>',
                'body'   => '<div>{all_data}</div>',
                'footer' => '<table width="100%"><tr><td width="50%">{DATE j-m-Y}</td><td width="50%"  style="text-align: right;" align="right">{PAGENO}/{nbpg}</td></tr></table>',
            ]
        ];
    }

    public function getSettingsFields()
    {
        return array(
            [
                'key'       => 'pdf_content',
                'label'     => 'PDF Content',
                'tips'      => 'Build your pdf by dragging and dropping the fields aside. You can also use the conditional logic to show/hide fields.',
                'component' => 'pdf-builder',
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

            ]
        );
    }

    public function generatePdf($submissionId, $feed, $outPut = 'I', $fileName = '')
    {
        $settings = $feed['settings'];
        $submissionModel = new Submission();
        $submission = $submissionModel->getSubmission($submissionId);

        $formData = maybe_unserialize($submission->form_data_formatted, true);


        $settings = PlaceholderParser::parse($settings, $submissionId, $formData, null, false, 'pdfFeed');

        if (!empty($settings['pdf_content']['header'])) {
            $this->headerHtml = $settings['pdf_content']['header'];
        }

        $htmlBody = $settings['pdf_content']['body'];  // Inserts HTML line breaks before all newlines in a string

        $htmlBody = PlaceholderParser::parse($htmlBody, $submissionId, $formData);

        $footer = $settings['pdf_content']['footer'];

        if (!$fileName) {
            $fileName = PlaceholderParser::parse($feed['name'], $submissionId, $formData);
            $fileName = sanitize_title($fileName, 'pdf-file', 'display');
        }

        return $this->pdfBuilder($fileName, $feed, $htmlBody, $footer, $outPut);
    }
}