<?php

namespace WPPayForm\App\Services;

use WPPayForm\App\Models\Form;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Form Placeholders Definations here
 * @since 1.1.0
 */
class FormPlaceholders
{
    public static function getAllPlaceholders($formId = false)
    {
        $allFields = array(
            'submission' => array(
                'title' => __('Submission Info', 'wp-payment-form'),
                'placeholders' => self::getFormPlaceHolders($formId)
            ),
            'input' => array(
                'title' => __('Inputs', 'wp-payment-form'),
                'placeholders' => self::getInputsPlaceHolders($formId)
            ),
            'payment' => array(
                'title' => __('Payment Items', 'wp-payment-form'),
                'placeholders' => self::getPaymentItemsPlaceholders($formId)
            ),
            'wp' => array(
                'title' => __('WordPress', 'wp-payment-form'),
                'placeholders' => self::getWPPlaceHolders()
            ),
            'other' => array(
                'title' => __('Other', 'wp-payment-form'),
                'placeholders' => self::getOtherPlaceholders()
            )
        );
        return apply_filters('wppayform/all_placeholders', $allFields, $formId);
    }


    public static function getAllShortCodes($formId = false)
    {
        $inputs = self::getInputsPlaceHolders($formId);
        $formattedInputs = array();
        foreach ($inputs as $key => $value) {
            $formattedInputs[$value['tag']] = $value['label'];
        }

        $paymentItems = self::getPaymentItemsPlaceholders($formId);
        $formattedPaymentItems = array();
        foreach ($paymentItems as $key => $value) {
            $formattedPaymentItems[$value['tag']] = $value['label'];
        }

        $wp = self::getWPPlaceHolders();
        $formattedWp = array();
        foreach ($wp as $key => $value) {
            $formattedWp[$value['tag']] = $value['label'];
        }

        $others = self::getOtherPlaceholders();
        $formattedOthers = array();
        foreach ($others as $otherKey => $otherValue) {
            $formattedOthers[$otherValue['tag']] = $otherValue['label'];
        }

        $allFields = array(
            array(
                'title' => __('Inputs', 'wp-payment-form'),
                'shortcodes' => $formattedInputs
            ),
            array(
                'title' => __('Submission Info', 'wp-payment-form'),
                'shortcodes' => self::subShortCodes($formId)
            ),
            array(
                'title' => __('Payment Items', 'wp-payment-form'),
                'shortcodes' => $formattedPaymentItems
            ),
            array(
                'title' => __('WordPress', 'wp-payment-form'),
                'shortcodes' => $formattedWp
            ),
            array(
                'title' => __('Other', 'wp-payment-form'),
                'shortcodes' => $formattedOthers
            )
        );
        return apply_filters('wppayform/all_shortcodes', $allFields, $formId);
    }

    public static function getWPPlaceHolders()
    {
        $mergeTags = array(
            'post_id' => array(
                'id' => 'id',
                'tag' => '{wp:post_id}',
                'label' => __('Post ID', 'wp-payment-form'),
                'callback' => 'post_id'
            ),
            'post_title' => array(
                'id' => 'title',
                'tag' => '{wp:post_title}',
                'label' => __('Post Title', 'wp-payment-form'),
                'callback' => 'post_title'
            ),
            'post_url' => array(
                'id' => 'url',
                'tag' => '{wp:post_url}',
                'label' => __('Post URL', 'wp-payment-form'),
                'callback' => 'post_url'
            ),
            'post_author' => array(
                'id' => 'author',
                'tag' => '{wp:post_author}',
                'label' => __('Post Author', 'wp-payment-form'),
                'callback' => 'post_author'
            ),
            'post_author_email' => array(
                'id' => 'author_email',
                'tag' => '{wp:post_author_email}',
                'label' => __('Post Author Email', 'wp-payment-form'),
                'callback' => 'post_author_email'
            ),
            'post_meta' => array(
                'id' => 'post_meta',
                'tag' => '{post_meta:YOUR_META_KEY}',
                'label' => __('Post Meta', 'wp-payment-form'),
                'callback' => null
            ),
            'user_id' => array(
                'id' => 'user_id',
                'tag' => '{wp:user_id}',
                'label' => __('User ID', 'wp-payment-form'),
                'callback' => 'user_id'
            ),
            'user_first_name' => array(
                'id' => 'first_name',
                'tag' => '{wp:user_first_name}',
                'label' => __('User First Name', 'wp-payment-form'),
                'callback' => 'user_first_name'
            ),
            'user_last_name' => array(
                'id' => 'last_name',
                'tag' => '{wp:user_last_name}',
                'label' => __('User Last Name', 'wp-payment-form'),
                'callback' => 'user_last_name'
            ),
            'user_display_name' => array(
                'id' => 'display_name',
                'tag' => '{wp:user_display_name}',
                'label' => __('User Display Name', 'wp-payment-form'),
                'callback' => 'user_display_name'
            ),
            'user_email' => array(
                'id' => 'user_email',
                'tag' => '{wp:user_email}',
                'label' => __('User Email', 'wp-payment-form'),
                'callback' => 'user_email'
            ),
            'user_url' => array(
                'id' => 'user_url',
                'tag' => '{wp:user_url}',
                'label' => __('User URL', 'wp-payment-form'),
                'callback' => 'user_url'
            ),
            'user_meta' => array(
                'id' => 'user_meta',
                'tag' => '{user_meta:YOUR_META_KEY}',
                'label' => __('User Meta', 'wp-payment-form'),
                'callback' => null
            ),
            'site_title' => array(
                'id' => 'site_title',
                'tag' => '{wp:site_title}',
                'label' => __('Site Title', 'wp-payment-form'),
                'callback' => 'site_title'
            ),
            'site_url' => array(
                'id' => 'site_url',
                'tag' => '{wp:site_url}',
                'label' => __('Site URL', 'wp-payment-form'),
                'callback' => 'site_url'
            ),
            'admin_email' => array(
                'id' => 'admin_email',
                'tag' => '{wp:admin_email}',
                'label' => __('Admin Email', 'wp-payment-form'),
                'callback' => 'admin_email'
            )
        );

        return apply_filters('wppayform/wp_merge_tags', $mergeTags);
    }

    public static function getUserPlaceholders()
    {
        $mergeTags = array(
            'user_id' => array(
                'id' => 'ID',
                'tag' => '{user:ID}',
                'label' => __('User ID', 'wp-payment-form')
            ),
            'first_name' => array(
                'id' => 'first_name',
                'tag' => '{user:first_name}',
                'label' => __('First name', 'wp-payment-form')
            ),
            'last_name' => array(
                'id' => 'last_name',
                'tag' => '{user:last_name}',
                'label' => __('Last name', 'wp-payment-form')
            ),
            'display_name' => array(
                'id' => 'display_name',
                'tag' => '{user:display_name}',
                'label' => __('Display name', 'wp-payment-form')
            ),
            'user_email' => array(
                'id' => 'user_email',
                'tag' => '{user:user_email}',
                'label' => __('User Email', 'wp-payment-form')
            ),
            'user_url' => array(
                'id' => 'user_url',
                'tag' => '{user:user_url}',
                'label' => __('User URL', 'wp-payment-form')
            ),
            'description' => array(
                'id' => 'description',
                'tag' => '{user:description}',
                'label' => __('User Description', 'wp-payment-form')
            ),
            'roles' => array(
                'id' => 'roles',
                'tag' => '{user:roles}',
                'label' => __('User Role', 'wp-payment-form')
            )
        );
        return apply_filters('wppayform/user_merge_tags', $mergeTags);
    }

    public static function getOtherPlaceholders()
    {
        $mergeTags = array(
            'querystring' => array(
                'tag' => '{querystring:YOUR_KEY}',
                'label' => __('Query String', 'wp-payment-form'),
                'callback' => null,
            ),
            'date' => array(
                'id' => 'date',
                'tag' => '{other:date}',
                'label' => __('Date', 'wp-payment-form'),
                'callback' => 'system_date'
            ),
            'time' => array(
                'id' => 'time',
                'tag' => '{other:time}',
                'label' => __('Time', 'wp-payment-form'),
                'callback' => 'system_time'
            ),
            'ip' => array(
                'id' => 'ip',
                'tag' => '{other:user_ip}',
                'label' => __('User IP Address', 'wp-payment-form'),
                'callback' => 'user_ip'
            ),
        );

        return apply_filters('wppayform/other_merge_tags', $mergeTags);
    }

    public static function getInputsPlaceHolders($formId = false, $html = true) {
        if (!$formId) {
            return array();
        }
        $shortcodes = Form::getEditorShortCodes($formId, 'input', $html);

        $formattedItems = array();

        foreach ($shortcodes as $codeSection) {
            foreach ($codeSection['shortcodes'] as $codeIndex => $codeTitle) {
                $codeIndexOnly = str_replace(['{', '}'], ['', ''], $codeIndex);
                $formattedItems[$codeIndexOnly] = array(
                    'tag' => $codeIndex,
                    'label' => $codeTitle,
                    'callback' => null,
                );
            }
        }

        return apply_filters('wppayform/form_merge_tags', $formattedItems, $formId);
    }

    public static function getPaymentItemsPlaceholders($formId = false, $html = true) {
        if (!$formId) {
            return array();
        }
        $shortcodes = Form::getEditorShortCodes($formId, 'payment', $html);

        $formattedItems = array();

        foreach ($shortcodes as $codeSection) {
            foreach ($codeSection['shortcodes'] as $codeIndex => $codeTitle) {
                $codeIndexOnly = str_replace(['{', '}'], ['', ''], $codeIndex);
                $formattedItems[$codeIndexOnly] = array(
                    'tag' => $codeIndex,
                    'label' => $codeTitle,
                    'callback' => null,
                );
            }
        }

        return apply_filters('wppayform/form_merge_tags', $formattedItems, $formId);
    }

    public static function getFormPlaceHolders($formId = false, $html = true)
    {
        if (!$formId) {
            return array();
        }
        $shortcodes = Form::getEditorShortCodes( $formId, 'submission', $html);

        $formattedItems = array();

        foreach ($shortcodes as $codeSection) {
            foreach ($codeSection['shortcodes'] as $codeIndex => $codeTitle) {
                $codeIndexOnly = str_replace(['{', '}'], ['', ''], $codeIndex);
                $formattedItems[$codeIndexOnly] = array(
                    'tag' => $codeIndex,
                    'label' => $codeTitle,
                    'callback' => null,
                );
            }
        }

        return apply_filters('wppayform/form_merge_tags', $formattedItems, $formId);
    }

    public static function subShortCodes($formId = false, $html = true)
    {
        if (!$formId) {
            return array();
        }
        $shortcodes = Form::getEditorShortCodes($formId, 'submission', $html);

        $formattedItems = array();

        foreach ($shortcodes as $codeSection) {
            foreach ($codeSection['shortcodes'] as $codeIndex => $codeTitle) {
                $codeIndexOnly = str_replace(['{', '}'], ['', ''], $codeIndex);
                $exclude = [
                    "{submission.all_input_field_html}",
                    "{submission.all_input_field_html_with_empty}",
                    "{submission.payment_receipt}"
                ];

                if (!in_array($codeIndex, $exclude)) {
                    $formattedItems[$codeIndex] = $codeTitle;
                }
            }
        }

        return apply_filters('wppayform/form_submission_merge_tags', $formattedItems, $formId);
    }
}
