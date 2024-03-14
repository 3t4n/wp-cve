<?php


class LHL_Admin_UI_TPUL {

    public function __construct() {
    }


    /**
     * 
     * Generate a Select box that is only available if license key has been purchased
     *  // Example Usage
     *  // Select Box input
     *      $options = get_option( 'tpul_settings_term_modal_woo_options' );
     *      $select_options_array = [
     *          'anonymous_only' => [
     *              'value' => 'anonymous_only',
     *              'label' => __( 'Anonymous visitors only', 'terms-popup-on-user-login' ),
     *              'with_license_key_only' => false
     *          ],
     *          'anonymous_and_logged_in' => [
     *              'value' => 'anonymous_and_logged_in',
     *              'label' => __( 'Anonymous visitors and logged in users', 'terms-popup-on-user-login' ),
     *              'with_license_key_only' => true
     *          ],
     *          'logged_in_only' => [
     *              'value' => 'logged_in_only',
     *              'label' => __( 'Logged in users only', 'terms-popup-on-user-login' ),
     *              'with_license_key_only' => true
     *          ]
     *      ];
     * 
     * // Render Select box
     *          LHL_Admin_UI_TPUL::admin_select_active_key_required(
     *              $this->license_key_valid,
     *              $options,
     *              'tpul_settings_term_modal_woo_options',
     *              'terms_modal_woo_display_user_type',
     *              $select_options_array,
     *              true
     *          );
     */
    public static function admin_select_active_key_required($license_key_valid, array $options, string $option_name, string $options_id, array $select_options_array, $sub_options_disabled_only = false) {

        if ($license_key_valid) {
            echo '<div class="tpul__alowed_box">';
            echo '<p class="tpul__license_notify_text">' . esc_html__('You have an active License Key. This feature is avaialble for you.', 'terms-popup-on-user-login') . '</p>';
        } else {
            echo '<div class="tpul__restricted_box">';
            if ($sub_options_disabled_only) {
                echo '<p class="tpul__license_notify_text">' . esc_html__('Your options are limited without an active license key. You need a valid license key.', 'terms-popup-on-user-login') . '</p>';
            } else {
                echo '<p class="tpul__license_notify_text">' . esc_html__('This is a Premium Feature. You need a valid license key to activate this feature.', 'terms-popup-on-user-login') . '</p>';
            }

            echo '<br/>';
        }

        // only the options inside the select are disabled for non active license holders
        if ($sub_options_disabled_only) {
            $is_select_disabled = false;
        } else {
            $is_select_disabled = !$license_key_valid;
        }

        self::admin_select($options, $option_name, $options_id, $select_options_array, $is_select_disabled, $license_key_valid);

        echo '</div>';
    }

    /**
     * Generate a select from an array
     * 
     * // Example Usage
     *  // Select Box input
     *      $options = get_option( 'tpul_settings_term_modal_woo_options' );
     * 
     *      $select_options_array = [
     *          'anonymous_only' => [
     *              'value' => 'anonymous_only',
     *              'label' => __( 'Anonymous visitors only', 'terms-popup-on-user-login' ),
     *          ],
     *          'anonymous_and_logged_in' => [
     *              'value' => 'anonymous_and_logged_in',
     *              'label' => __( 'Anonymous visitors and logged in users', 'terms-popup-on-user-login' ),
     *          ],
     *          'logged_in_only' => [
     *              'value' => 'logged_in_only',
     *              'label' => __( 'Logged in users only', 'terms-popup-on-user-login' ),
     *          ]
     *      ];
     * 
     * // Render Select box
     *          LHL_Admin_UI_TPUL::admin_select(
     *              $this->license_key_valid,
     *              $options,
     *              'tpul_settings_term_modal_woo_options',
     *              'terms_modal_woo_display_user_type',
     *              $select_options_array,
     *              true
     *          );
     */
    public static function admin_select(array $options, string $option_name, string $options_id, array $select_options_array, bool $is_disabled = false, bool $license_key_valid = false) {
        $selectbox_name = "{$option_name}[{$options_id}]";
        $disabled_attribute = $is_disabled ? " disabled='disabled'" : "";

        echo "<select name='{$selectbox_name}'{$disabled_attribute}>";
        foreach ($select_options_array as $option_item) {

            $is_disabled_sub_option = "";
            if (!empty($option_item['with_license_key_only'])) {
                $is_disabled_sub_option = ($option_item['with_license_key_only'] && !$license_key_valid) ? "disabled" : "";
            }

            echo '<option value="' . $option_item['value'] . '" ' . selected($option_item['value'], $options[$options_id]) . " {$is_disabled_sub_option}>" . $option_item['label'] . '</option>';
        }
        echo "</select>";
    }

    /**
     * Generate a Button
     * that has a message field next to it to display message responsens returned by ajax
     */
    public static function button($classes = [], $title = "Empty", $eventname = '', $repsoneHTML = '', $id = '') {

        $output = "";
        $attr = [];
        $def_classes = [
            'button',
            'button-default'
        ];

        /**
         * Classes
         * Merge incoming classes with default classes
         */

        if (is_array($classes)) {
            // sanitize if array
            $classes = array_map(function ($a) {
                return sanitize_html_class($a);
            }, $classes);
            // merge with defaults
            $classes = array_merge($classes, $def_classes);
            $classes_string = join(' ', $classes);
        } elseif (is_string($classes)) {
            // as is string
            $classes_string = join(' ', $classes);
            $classes_string = $classes_string . " " . $classes;
        }
        $classes_string = join(' ', $classes);


        /**
         * Add Clicke Event
         */
        if (!empty($eventname)) {
            $evet = $eventname . "(event)";
            $attr[] = 'onclick="' . $evet . '"';
        }

        /**
         * Get
         */
        $attr_string = join(' ', $attr);

        $output .= "<div class='lhl-admin-button__container {$classes[0]}__container'>";
        $output .= sprintf('<button id="%s" class="%s" %s>%s</button>', $id, $classes_string, $attr_string, $title);
        $output .= "<span class='lhl-admin-button__message {$classes[0]}__message'>";
        $output .= "</span>";
        $output .= "<div class='lhl-admin-button__response {$classes[0]}__response empty'>";
        $output .= $repsoneHTML;
        $output .= "</div>";
        $output .= "</div>";


        return $output;
    }


    public static function print_support_token($license_key_valid = false, $token = "") {

        if ($license_key_valid) {
            echo '<div class="tpul__alowed_box">';
            echo '<p class="tpul__license_notify_text">' . esc_html__('You have an active License Key. This feature is avaialble for you.', 'terms-popup-on-user-login') . '</p>';
            echo '<input type="text" class="regular-text" name="" value="6PNP-PZNP " readonly >';
        } else {
            echo '<div class="tpul__restricted_box">';
            echo '<p class="tpul__license_notify_text"> This is a Premium Feature. You need a valid license key to activate this feature. </p>';
            echo '<input type="password" class="regular-text" name="" value="NO Peeking!" readonly >';
        }

        echo '</div>';
    }
    public static function print_support_email($license_key_valid = false) {

        echo '<p class="tpul__license_notify_text">' . esc_html__('If you have premium license key activation issues please reach out to.', 'terms-popup-on-user-login') . '</p>';
        echo "contact@lehelmatyus.com ";
    }
}
