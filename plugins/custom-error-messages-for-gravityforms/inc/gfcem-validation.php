<?php

if (!defined('ABSPATH')) {
	exit;
}

/**
 * Validate required field option
 */
add_filter('gform_field_validation', function ($result, $value, $form, $field) {
    $errorMessage = gfcem_get_custom_message($form, $field, 'gfcem_default_required', 'inputGFCEMMessageRequired');

    if ($field->isRequired && !empty($errorMessage)) {
        if ('checkbox' === $field->type) {
            $all_empty = true;
            foreach ($value as $key => $val) {
                if (!empty($val)) {
                    $all_empty = false;
                    break;
                }
            }

            if ($all_empty) {
                $result['message'] = $errorMessage;
            }
        } else if (empty($value)) {
            $result['message'] = $errorMessage;
        }
        return $result;
    }

    return $result;
}, 10, 4);

/**
 * Validate email field
 */
add_filter('gform_field_validation', function ($result, $value, $form, $field) {
    if ('email' === $field->type) {
        $email = is_array( $value ) ? rgar( $value, 0 ) : $value;

        $validEmailErrorMessage = gfcem_get_custom_message($form, $field, 'gfcem_default_valid_email', 'inputGFCEMMessageValidEmail');

        if (!is_email(trim($email)) && !empty($validEmailErrorMessage)) {
            $result['message'] = $validEmailErrorMessage;
            return $result;
        }

        $confirmEmailErrorMessage = gfcem_get_custom_message($form, $field, 'gfcem_default_confirm_email', 'inputGFCEMMessageConfirmEmail');

        if ($field->emailConfirmEnabled && !empty($email) && !empty($confirmEmailErrorMessage)) {
            $confirm = is_array($email) ? rgar($email, 1) : $field->get_input_value_submission('input_' . $field->id . '_2');
            if ($confirm != $email) {
                $result['message'] = $confirmEmailErrorMessage;
                return $result;
            }
        }
    }

    return $result;
}, 10, 5);

add_filter('gform_duplicate_message', function ($message, $form, $field, $value) {
    $errorMessage = gfcem_get_custom_message($form, $field, 'gfcem_default_unique', 'inputGFCEMMessageUnique');

    if (!empty($errorMessage)) {
        return $errorMessage;
    }

    return $message;
}, 10, 4);

function gfcem_is_custom_message_enabled($form, $field) {
    $globalEnabled = intval(rgar($form, 'gfcem_default_enabled')) === 1;

    return $globalEnabled || gfcem_is_field_message_enabled($field);
}

function gfcem_is_field_message_enabled($field) {
    return isset($field['gfcemAllowed']) && $field->gfcemAllowed;
}

function gfcem_get_custom_message($form, $field, $defaultKey, $fieldKey) {
    $customMessagesEnabled = gfcem_is_custom_message_enabled($form, $field);

    if (!$customMessagesEnabled) {
        return '';
    }

    if (isset($field[$fieldKey]) && gfcem_is_field_message_enabled($field)) {
        return $field[$fieldKey];
    }

    return rgar($form, $defaultKey, '');
}