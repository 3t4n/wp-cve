<?php

namespace WpLHLAdminUi\Forms;

class AdminForm {


    static $active_license_key_text = "You have an active License Key. This feature is avaialble for you.";
    static $inactive_license_key_text = "This is a Premium Feature. You need a valid license key to activate this feature.";
    static $inactive_license_key_partial_options = "Limited options without an active license key. You need a valid license key.";

    public function __construct() {
    }

    /****************************************************************************************************************************
     * Select dropdown
     ****************************************************************************************************************************/

    /**
     * 
     * Generate a Select box that is only available if license key has been purchased
     *  // Example Usage
     *  // Select Box input
     *      $options = get_option( 'settings_term_modal_woo_options' );
     *      $select_options_array = [
     *          'anonymous_only' => [
     *              'value' => 'anonymous_only',
     *              'label' => __( 'Anonymous visitors only', 'tgen-template-generator' ),
     *              'with_license_key_only' => false
     *          ],
     *          'anonymous_and_logged_in' => [
     *              'value' => 'anonymous_and_logged_in',
     *              'label' => __( 'Anonymous visitors and logged in users', 'tgen-template-generator' ),
     *              'with_license_key_only' => true
     *          ],
     *          'logged_in_only' => [
     *              'value' => 'logged_in_only',
     *              'label' => __( 'Logged in users only', 'tgen-template-generator' ),
     *              'with_license_key_only' => true
     *          ]
     *      ];
     * 
     * // Render Select box
     *          LHL_Admin_UI::form_select__active_key_required(
     *              $this->license_key_valid,
     *              $options,
     *              'settings_term_modal_woo_options',
     *              'terms_modal_woo_display_user_type',
     *              $select_options_array,
     *              true
     *          );
     */
    public static function select__active_key_required($license_key_valid, array $options, string $option_name, string $options_id, array $select_options_array, $sub_options_disabled_only = false) {

        if ($license_key_valid) {
            echo '<div class="lhl__alowed_box">';
            echo '<p class="lhl__license_notify_text">' . esc_html(self::$active_license_key_text) . '</p>';
        } else {
            echo '<div class="lhl__restricted_box">';
            if ($sub_options_disabled_only) {
                echo '<p class="lhl__license_notify_text">' . esc_html(self::$inactive_license_key_partial_options) . '</p>';
            } else {
                echo '<p class="lhl__license_notify_text">' . esc_html(self::$inactive_license_key_text) . '</p>';
            }
        }
        echo '<br/>';

        // only the options inside the select are disabled for non active license holders
        if ($sub_options_disabled_only) {
            $is_select_disabled = false;
        } else {
            $is_select_disabled = !$license_key_valid;
        }

        echo '<div>';
        self::select($options, $option_name, $options_id, $select_options_array, $is_select_disabled, $license_key_valid);
        echo '</div>';

        echo '</div>';
    }

    /**
     * Generate a select from an array
     * 
     * // Example Usage
     *  // Select Box input
     *      $options = get_option( 'settings_term_modal_woo_options' );
     * 
     *      $select_options_array = [
     *          'anonymous_only' => [
     *              'value' => 'anonymous_only',
     *              'label' => __( 'Anonymous visitors only', 'tgen-template-generator' ),
     *          ],
     *          'anonymous_and_logged_in' => [
     *              'value' => 'anonymous_and_logged_in',
     *              'label' => __( 'Anonymous visitors and logged in users', 'tgen-template-generator' ),
     *          ],
     *          'logged_in_only' => [
     *              'value' => 'logged_in_only',
     *              'label' => __( 'Logged in users only', 'tgen-template-generator' ),
     *          ]
     *      ];
     * 
     * // Render Select box
     *          LHL_Admin_UI::form_select(
     *              $this->license_key_valid,
     *              $options,
     *              'settings_term_modal_woo_options',
     *              'terms_modal_woo_display_user_type',
     *              $select_options_array,
     *              true
     *          );
     */
    public static function select(array $options, string $option_name, string $options_id, array $select_options_array, bool $is_disabled = false, bool $license_key_valid = false) {
        $selectbox_name = "{$option_name}[{$options_id}]";
        $disabled_attribute = $is_disabled ? " disabled='disabled'" : "";

        echo "<select name='" . esc_attr($selectbox_name) . "'" . esc_attr($disabled_attribute) . ">";
        foreach ($select_options_array as $option_item) {

            $is_disabled_sub_option = "";
            if (!empty($option_item['with_license_key_only'])) {
                $is_disabled_sub_option = ($option_item['with_license_key_only'] && !$license_key_valid) ? "disabled" : "";
            }

            echo '<option value="' . esc_attr($option_item['value']) . '" ' . esc_attr(selected($option_item['value'], $options[$options_id])) . " {esc_attr($is_disabled_sub_option)}>" . esc_attr($option_item['label']) . '</option>';
        }
        echo "</select>";
    }


    /****************************************************************************************************************************
     * Text Input
     ****************************************************************************************************************************/

    /**
     * Example usage
     *  $options = get_option( 'tgentg_generator_options' );
     *	$option_name = "tgentg_generator_options";
     *	$option_id = "content_region_selector";
     *
     *	AdminForm::form_text_input__active_key_required(
     *      true,
     *		$options,
     *		$option_name,
     *		$option_id
     *	);
     */
    public static function text_input__active_key_required($license_key_valid, array $options, string $option_name, string $options_id, bool $is_disabled = false) {

        if ($license_key_valid) {
            echo '<div class="lhl__alowed_box">';
            echo '<p class="lhl__license_notify_text">' . esc_html(self::$active_license_key_text) . '</p>';
        } else {
            echo '<div class="lhl__restricted_box">';
            echo '<p class="lhl__license_notify_text">' . esc_html(self::$inactive_license_key_text) . '</p>';
        }
        echo '<br/>';

        $is_disabled = !$license_key_valid;
        echo '<div>';
        self::text_input($options, $option_name, $options_id, $is_disabled);
        echo '</div>';
        echo '</div>';
    }

    /**
     * Example usage
     *  $options = get_option( 'tgentg_generator_options' );
     *	$option_name = "tgentg_generator_options";
     *	$option_id = "content_region_selector";
     *
     *	AdminForm::admin_text_input(
     *		$options,
     *		$option_name,
     *		$option_id
     *	);
     */
    public static function text_input(array $options, string $option_name, string $options_id, bool $is_disabled = false) {

        $input_name = "{$option_name}[{$options_id}]";
        $disabled_attribute = $is_disabled ? " disabled='disabled'" : "";
        $value = (isset($options[$options_id])) ? $options[$options_id] : '';

        echo '<input type="text" name="' . esc_attr($input_name) . '" value="' . esc_attr($value) . '" class="regular-text"' . esc_attr($disabled_attribute) . '>';
    }

    /**
     * Example usage
     *  $options = get_option( 'tgentg_generator_options' );
     *	$option_name = "tgentg_generator_options";
     *	$option_id = "content_region_selector";
     *
     *	AdminForm::admin_text_input(
     *		$options,
     *		$option_name,
     *		$option_id
     *	);
     */
    public static function email_input(array $options, string $option_name, string $options_id, bool $is_disabled = false) {

        $input_name = "{$option_name}[{$options_id}]";
        $disabled_attribute = $is_disabled ? " disabled='disabled'" : "";
        $value = (isset($options[$options_id])) ? $options[$options_id] : '';

        echo '<input type="email" name="' . esc_attr($input_name) . '" value="' . esc_attr($value) . '" class="regular-text"' . esc_attr($disabled_attribute) . '>';
    }


    public static function email_input_multi(array $options, string $option_name, string $options_id, bool $is_disabled = false, $rows = 3, $cols = 50) {

        $input_name = "{$option_name}[{$options_id}]";
        $disabled_attribute = $is_disabled ? " disabled='disabled'" : "";
        $value = (isset($options[$options_id])) ? $options[$options_id] : '';

        $email_array = explode(',', $value);
        // Remove none email
        foreach ($email_array as $key => $email) {
            if (!is_email(trim($email))) {
                unset($email_array[$key]);
            } else {
                $email_array[$key] = trim($email);
            }
        }
        $value = implode(', ', $email_array);

        // echo '<textarea id="'. esc_attr($options_id) .'" name="'. esc_attr($input_name) .'" value="' . esc_attr($value) . '" class="regular-text"' . esc_attr($disabled_attribute) . '>';
        echo '<textarea id="' . esc_attr($options_id) . '" name="' . esc_attr($input_name) . '" rows="' . esc_attr($rows) . '" cols="' . esc_attr($cols) . '"' . esc_attr($disabled_attribute) . '>' . esc_html($value) . '</textarea>';
    }

    /****************************************************************************************************************************
     * Textarea
     ****************************************************************************************************************************/

    public static function textarea(array $options, string $option_name, string $options_id, bool $is_disabled = false, $rows = 15, $cols = 100) {

        $input_name = "{$option_name}[{$options_id}]";
        $disabled_attribute = $is_disabled ? " disabled='disabled'" : "";
        $value = (isset($options[$options_id])) ? $options[$options_id] : '';

        // Render the output
        echo '<textarea id="' . esc_attr($options_id) . '" name="' . esc_attr($input_name) . '" rows="' . esc_attr($rows) . '" cols="' . esc_attr($cols) . '"' . esc_attr($disabled_attribute) . '>' . esc_html($value) . '</textarea>';
    }

    /****************************************************************************************************************************
     * Checkbox
     ****************************************************************************************************************************/

    public static function checkbox_single__active_key_required($license_key_valid, array $options, string $option_name, string $options_id, $label, bool $is_disabled = false) {

        $disabled = false;
        if ($license_key_valid) {
            echo '<div class="lhl__alowed_box">';
            echo '<p class="lhl__license_notify_text">' . esc_html(self::$active_license_key_text) . '</p>';
            // $disabled = $disabled;
        } else {
            echo '<div class="lhl__restricted_box">';
            echo '<p class="lhl__license_notify_text">' . esc_html(self::$inactive_license_key_text) . '</p>';
            $disabled = true;
        }
        echo '<br/>';

        echo '<div>';
        self::checkbox_single($options, $option_name, $options_id, $label, $disabled);
        echo '</div>';
        echo '</div>';
    }

    public static function checkbox_single(array $options, string $option_name, string $options_id, $label, bool $is_disabled = false) {

        $input_name = "{$option_name}[{$options_id}]";
        $disabled_attribute = $is_disabled ? " disabled='disabled'" : "";
        $value = (isset($options[$options_id])) ? $options[$options_id] : 0;

        echo '<input type="checkbox" id="' . esc_attr($options_id) . '" name="' . esc_attr($input_name) . '" value="1"' . esc_attr(checked(1, $value, false)) .  esc_attr($disabled_attribute) . '/>';
        echo '<label for="' . esc_attr($options_id) . '">' . esc_html($label) . '</label>';
    }

    /****************************************************************************************************************************
     * Button
     ****************************************************************************************************************************/

    /**
     * Generate a Button
     * that has a message field next to it to display message responsens returned by ajax
     */
    public static function button($classes = [], $title = "Empty", $id = '', $disabled = false, $onclick_event_name = '', $attr = [], $msg = []) {

        if (empty($msg)) {
            $msg = [
                "wait" => "Script Running Please Wait...",
                "success" => "Success!",
                "error" => "Error 100"
            ];
        }

        $def_atts = [];
        $def_classes = [
            'button',
            'button-default'
        ];

        /**
         * Classes
         */

        if (is_array($classes)) {
            // is array A-OK
        } elseif (is_string($classes)) {
            $classes = explode(" ", $classes);
        } else {
            // bad value
            $classes = [];
        }
        // sanitize array
        $classes = array_map(function ($a) {
            return sanitize_html_class($a);
        }, $classes);
        // merge with defaults
        $classes = array_merge($classes, $def_classes);

        /**
         * Atts
         */

        if (is_array($attr)) {
        } elseif (is_string($attr)) {
            $attr = explode(" ", $attr);
        } else {
            $attr = [];
        }
        // sanitize array
        $attr = array_map(function ($a) {
            return esc_attr($a);
        }, $attr);
        // merge with defaults
        $attr = array_merge($attr, $def_atts);


        /**
         * is disabled?
         */
        if (!empty($disabled)) {
            $attr[] = 'disabled';
        }

        /**
         * Add Click Event
         */
        if (!empty($onclick_event_name)) {
            $onclick_attr = " onclick=" . $onclick_event_name . "(event)";
        } else {
            $onclick_attr = '';
        }

        /**
         * Aggregate all classes and attributes
         */
        $classes_string = join(' ', $classes);
        $attr_string = join(' ', $attr);

        /**
         * Output
         */
        echo "<div class='lhl-admin-button__container " . esc_attr($id) . "__container'>";
        echo sprintf(
            '<button id="%s" class="%s" %s%s>%s</button>',
            esc_attr($id),
            esc_attr($classes_string),
            esc_attr($attr_string),
            esc_attr($onclick_attr),
            esc_attr($title)
        );

        echo sprintf(
            "<span class='%s__msg lhl__p_5 lhl__btn_msg' data-wait-msg='%s' data-success-msg='%s' data-error-msg='%s'>",
            esc_attr($id),
            esc_html($msg['wait']),
            esc_html($msg['success']),
            esc_html($msg['error']),
        );
        echo "</span>";
        echo "</div>";
    }

    public static function button__active_key_required($license_key_valid, $classes = [], $title = "Empty", $id = '', $disabled = false, $onclick_event_name = '', $attr = [], $msg = []) {

        if ($license_key_valid) {
            echo '<div class="lhl__alowed_box">';
            echo '<p class="lhl__license_notify_text">' . esc_html(self::$active_license_key_text) . '</p>';
            // $disabled = $disabled;
        } else {
            echo '<div class="lhl__restricted_box">';
            echo '<p class="lhl__license_notify_text">' . esc_html(self::$inactive_license_key_text) . '</p>';
            $disabled = true;
        }
        echo '<br/>';

        self::button($classes, $title, $id, $disabled, $onclick_event_name, $attr, $msg);

        echo '</div>';
    }


    /****************************************************************************************************************************
     * License Card
     ****************************************************************************************************************************/

    public static function license_card($license_key_valid) {

        $classes = [];
        $classes[] = "lhl__license_box";

        if ($license_key_valid) {
            $classes[] = "lhl__license_box--active";
            $classes[] = "lhl__license_box--closed";
        } else {
            $classes[] = "lhl__license_box--inactive";
            $classes[] = "lhl__license_box--open";
        }

        $class_string = implode(" ", $classes);

        echo "<div class='" . esc_attr($class_string) . "'>";
        echo "<div>";
        echo "<h2>Account Status:";

        if ($license_key_valid) {
            echo '<span class="lhl__license_box_label_active" style="color: #008000; font-style: italic;">Active</span>';
        } else {
            echo '<span class="lhl__license_box_label_inactive" style="color: #800000; font-style: italic;">Inactive</span>';
        }

        echo '<a class="lhl__license_box-view-button lhl__license_box-view-button--open" style="font-size:14px;" href="#">View License</a>';
        echo '<a class="lhl__license_box-view-button lhl__license_box-view-button--close" style="font-size:14px;" href="#">Close</a>';

        echo "</h2>";
        echo "</div>";
        echo "</div>";
    }
}
