<?php

namespace WPPayForm\App\Services;

use WPPayForm\Framework\Support\Arr;
use WPPayForm\App\Models\Form;
use WPPayForm\App\Services\PlaceholderParser;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Confirmation helper for a submission
 * @since 1.0.0
 */
class ConfirmationHelper
{
    public static function getFormConfirmation($formId, $submission)
    {
        $confirmation = Form::getConfirmationSettings($formId);
        $confirmation = ConfirmationHelper::parseConfirmation($confirmation, $submission);
        return apply_filters('wppayform/form_confirmation', $confirmation, $submission->id, $formId);
    }

    public static function parseConfirmation($confirmation, $submission)
    {
        // add payment hash to the url
        if (
            ($confirmation['redirectTo'] == 'customUrl' && $confirmation['customUrl']) ||
            ($confirmation['redirectTo'] == 'customPage' && $confirmation['customPage'])
        ) {
            if ($confirmation['redirectTo'] == 'customUrl') {
                $url = $confirmation['customUrl'];
                $url = PlaceholderParser::parse($url, $submission);
            } else {
                $url = get_permalink(intval($confirmation['customPage']));
                $url = add_query_arg('wpf_submission', $submission->submission_hash, $url);
            }
            $confirmation['redirectTo'] = 'customUrl';
	        $url = str_replace('wpf_page=frameless','', $url);
            $confirmation['customUrl'] = sanitize_url($url);
        } elseif ($confirmation['redirectTo'] == 'samePage') {
            do_action('wppayform/require_entry_html');
            $confirmation['messageToShow'] = PlaceholderParser::parse($confirmation['messageToShow'], $submission);
            if (strpos($confirmation['messageToShow'], '[wppayform_reciept]') !== false) {
                $modifiedShortcode = '[wppayform_reciept hash="' . $submission->submission_hash . '"]';
                $confirmation['messageToShow'] = str_replace('[wppayform_reciept]', $modifiedShortcode, $confirmation['messageToShow']);
            }
            
            $confirmation['messageToShow'] = do_shortcode($confirmation['messageToShow']);
            do_action('wppayform/require_entry_html_done');
        }

        return $confirmation;
    }
}
