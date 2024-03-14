<?php

namespace Reuse\Builder;

/*
 *
 */

class Reuse_Builder_Reuse
{
    public static function load($localPath)
    {
        $reuseform_scripts = json_decode(file_get_contents(REUSE_BUILDER_DIR.'/resource/reuse.json'), true);
        if (isset($reuseform_scripts['vendor'])) {
            wp_register_script('reuse_vendor', $localPath.$reuseform_scripts['vendor']['js'], array(), $ver = false, true);
            wp_enqueue_script('reuse_vendor');
        }
        if (isset($reuseform_scripts['reuse'])) {
            wp_register_script('reuse-builder-js', $localPath.$reuseform_scripts['reuse']['js'], array('jquery', 'underscore'), $ver = false, true);
            wp_enqueue_script('reuse-builder-js');
        }

        wp_localize_script('reuse-builder-js', 'REUSE_ADMIN', array(
      'LANG' => Reuse_Builder_Reuse::reuse_form_language(),
      'ERROR_MESSAGE' => Reuse_Builder_Reuse::reuse_form_error_messages(),
      '_WEBPACK_PUBLIC_PATH_' => $localPath,
      'base_url' => apply_filters('reuse_image_base_url', REUSE_BUILDER_DIR),
    ));
    }

    public static function reuse_form_language()
    {
        /**
         * Localize language files for reuse form rendering.
         */
        $lang = array(
        'BUNDLE_COMPONENT' => __('Bundle Component', 'reuse-builder'),
        'PICK_COLOR' => __('Pick Color', 'reuse-builder'),
        'NO_RESULT_FOUND' => __('No result found', 'reuse-builder'),
        'SEARCH' => __('search', 'reuse-builder'),
        'OPEN_ON_SELECTED_HOURS' => __('Open on selected hours', 'reuse-builder'),
        'ALWAYS_OPEN' => __('Always open', 'reuse-builder'),
        'NO_HOURS_AVAILABLE' => __('No hours available', 'reuse-builder'),
        'PERMANENTLY_CLOSE' => __('Permanently closed', 'reuse-builder'),
        'MONDAY' => __('Monday', 'reuse-builder'),
        'TUESDAY' => __('Tuesday', 'reuse-builder'),
        'WEDNESDAY' => __('Wednesday', 'reuse-builder'),
        'THURSDAY' => __('Thursday', 'reuse-builder'),
        'FRIDAY' => __('Friday', 'reuse-builder'),
        'SATURDAY' => __('Saturday', 'reuse-builder'),
        'SUNDAY' => __('Sunday', 'reuse-builder'),
        'WRONG_PASS' => __('Wrong Password', 'reuse-builder'),
        'PASS_MATCH' => __('Password Matched', 'reuse-builder'),
        'CONFIRM_PASS' => __('Confirm Password', 'reuse-builder'),
        'CURRENTLY_WORK' => __('I currently work here', 'reuse-builder'),
    );

        return $lang;
    }

    public static function reuse_form_error_messages()
    {
        /**
         * Localize Error Message files for js rendering.
         */
        $error_message_list = array(
            'notNull' => __('The field should not be empty', 'reuse-builder'),
            'email' => __('The field should be email', 'reuse-builder'),
            'isNumeric' => __('The field should be numeric', 'reuse-builder'),
            'isURL' => __('The field should be Url', 'reuse-builder'),
        );

        return $error_message_list;
    }
}
