<?php

namespace FluentSupport\App\Services\Tickets;

class CustomFieldsService
{
    public function getCustomFields()
    {
        $customFields = [
            [
                'data_key' => 'ticket_type',
                'admin_label' => __('Support Type', 'fluent-support'),
                'public_label' => __('Please let us know about this support type', 'fluent-support'),
                'type' => 'input-select',
                'placeholder' => __('Support Type', 'fluent-support'),
                'options' => [
                    'bug_report' => __('Bug Report', 'fluent-support'),
                    'feature_request' => __('Feature Request', 'fluent-support'),
                    'integration_issues' => __('Integration Issues', 'fluent-support')
                ],
                'required' => true
            ],
            [
                'data_key' => 'invoice_number',
                'admin_label' => __('Invoice Number', 'fluent-support'),
                'public_label' => __('Please let us know your invoice number', 'fluent-support'),
                'type' => 'input-text',
                'placeholder' => __('Invoice Number', 'fluent-support'),
                'data-type' => 'text', // number / text
                'required' => false
            ],
            [
                'data_key' => 'details_about_issue',
                'admin_label' => __('Details about the issue', 'fluent-support'),
                'public_label' => __('Please let us know more details about this issue', 'fluent-support'),
                'type' => 'input-textarea',
                'placeholder' => __('Details about the issue', 'fluent-support'),
                'required' => false
            ]
        ];
    }



}
