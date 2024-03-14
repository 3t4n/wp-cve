<?php
/**
 * Save WPCF7 contact form entries to Notion database.
 *
 * @package add-on-cf7-for-notion
 */

namespace WPC_WPCF7_NTN\Entry;

use WPC_WPCF7_NTN\WPCF7_Field_Mapper;
use WPC_WPCF7_NTN\WPCF7_Notion_Service;
use WPC_WPCF7_NTN\Helpers;

defined( 'ABSPATH' ) || exit;

/**
 * Saves contact form submission to Notion database
 *
 * @param WPCF7_ContactForm $contact_form A WPCF7_ContactForm instance.
 * @param bool              $abort Set to true if the form submission should be aborted.
 * @param WPCF7_Submission  $submission A WPCF7_Submission instance.
 * @return void
 */
function save_wpcf7_entry_in_notion_database($contact_form, &$abort, $submission)
{
    $service = WPCF7_Notion_Service::get_instance();
    if (!$service->is_active()) {
        return;
    }
    if ($contact_form->in_demo_mode()) {
        return;
    }

    $consented = true;
    $optional_consent_tag = false;
    foreach ($contact_form->scan_form_tags('feature=name-attr') as $tag) {
        if ($tag->has_option('consent_for:notion')) {
            if ($tag->has_option('optional')) {
                $optional_consent_tag = $tag;
            }
            if (null === $submission->get_posted_data($tag->name)) {
                $consented = false;
            }
            break;
        }
    }

    if (!$consented) {
        return;
    }

    $prop = wp_parse_args(
        $contact_form->prop('wpc_notion'),
        array(
            'enable_database' => false,
            'database_selected' => '',
        )
    );

    if (!$prop['enable_database'] || empty($prop['database_selected'])) {
        return;
    }

    $database_id = $prop['database_selected'];
    $columns = Helpers\get_notion_databases_columns($database_id);
    $data = (array)$submission->get_posted_data();
    $notion_fields = array();
    $files = $submission->uploaded_files();
    $field_mapper = WPCF7_Field_Mapper::get_instance();
    $mapped_tags = Helpers\get_mapped_tags_from_contact_form($contact_form, $columns);
    $cleaned_mapped_tags = $field_mapper->filter_mapped_tags($mapped_tags);

    $files = $submission->uploaded_files();

    foreach ($cleaned_mapped_tags as $wpcf7_field_name => $field) {
        $column_id = $field['notion_field_id'];
        $original_field_value = $data[$wpcf7_field_name];

        if ('acceptance' === $field['type']) {
            $original_field_value = $original_field_value ? $field['content'] : __('No', 'add-on-cf7-for-notion');
        } 
		elseif ( 'file' === $field['type'] ) {
			if ( ! isset( $files[ $wpcf7_field_name ] ) ) {
				$original_field_value = array();
			} else {
				$original_field_value = $files[ $wpcf7_field_name ];
			}
		}
        $field_value = $field_mapper->get_formatted_field_value($field['type'], $field['notion_field_type'], $original_field_value);
        $notion_fields[$column_id] = (object)array(
            'column_id' => $column_id,
            'field_value' => $field_value,
            'field_type' => $field['notion_field_type'],
        );
    }

    $notion_fields_ok = Helpers\prepare_fields_for_notion($notion_fields, $database_id);

    $api = wpconnect_wpcf7_notion_get_api();
    $response = $api->add_database_row($database_id, $notion_fields_ok);
    if (is_wp_error($response)) {
        $error_response = $response->get_error_data();
        $error_response_details = '';

        if (!empty($error_response['pretty_response'])) {
            $error_response_details .= __("Status: ", 'add-on-cf7-for-notion') . $error_response['pretty_response']->status . "\n";
            $error_response_details .= __("Code: ", 'add-on-cf7-for-notion') . $error_response['pretty_response']->code . "\n";
            $error_response_details .= __("Message: ", 'add-on-cf7-for-notion') . $error_response['pretty_response']->message . "\n";
        }

        // Display error
        $submission->set_status('aborted');
        $submission_response = __("Due to an error, your message could not be sent. Please try again later.", 'add-on-cf7-for-notion');
        $submission->set_response($submission_response);

        // Send email to administrator.
        $admin_email = get_option('admin_email');
        $subject = __('Error during Contact Form 7 submission', 'add-on-cf7-for-notion');
        $message = __("An error occurred while saving the submission to Notion.", 'add-on-cf7-for-notion') . "\n\n";

        if (!empty($error_response_details)) {
            $message .= $error_response_details;
        }

        wp_mail($admin_email, $subject, $message);

        $abort = true;
    }
}

