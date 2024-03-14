<?php
/**
 * Template Tags
 */

use FDSUS\Id;
use FDSUS\Controller\Base as BaseController;
use FDSUS\Controller\TaskTable as TaskTableController;
use FDSUS\Model\Settings;
use FDSUS\Model\Base as BaseModel;
use FDSUS\Model\Sheet as SheetModel;
use FDSUS\Model\SignupFormInitialValues;
use FDSUS\Model\Task as TaskModel;
use FDSUS\Model\States as StatesModel;

if (!function_exists('dlssus_get_template_part')) {
    /**
     * Allow custom template parts
     *
     * @param string      $slug main template name (not ending with .php)
     * @param string|null $name suffix appended with a `-` (not ending with .php)
     * @param bool        $load
     * @param array       $args
     *
     * @return string
     */
    function dlssus_get_template_part($slug, $name = null, $load = true, $args = array())
    {
        // Execute code for this part
        do_action('get_template_part_' . $slug, $slug, $name);

        $type = get_post_type();

        // Display signup form instead
        if (get_post_type() == SheetModel::POST_TYPE) {
            if (!empty($_GET['task_id'])) {
                $type = TaskModel::POST_TYPE;
            }
        }

        // Setup possible parts
        $templates = array();
        if (isset($name)) {
            $templates[] = $slug . '-' . $name . '.php';
        }
        $templates[] = $slug . '-' . $type . '.php';
        $templates[] = $slug . '.php';

        /**
         * Allow template parts to be filtered
         *
         * @param array  $templates
         * @param string $slug
         * @param string $name
         *
         * @return array templates
         */
        $templates = apply_filters('fdsus_get_template_part', $templates, $slug, $name);

        // Return the part that is found
        $baseController = new BaseController();
        return $baseController->locateTemplate($templates, $load, false, $args);
    }
}

if (!function_exists('dlssus_has_sheet_date')) {
    /**
     * Does sheet have a sheet date set?
     *
     * @global WP_Post          $post
     *
     * @return bool|string
     */
    function dlssus_has_sheet_date()
    {
        global $post;
        $sheet = new SheetModel($post);
        if (!empty($sheet->dlssus_date) && !$sheet->dlssus_use_task_dates) {
            return true;
        }

        return false;
    }
}

if (!function_exists('dlssus_field')) {
    /**
     * @param  string            $slug
     *
     * @return bool|string
     *
     * @global WP_Post          $post
     */
    function dlssus_field($slug)
    {
        global $post;

        $value = get_post_meta(
            $post->ID,
            Id::PREFIX . '_' . $slug, true
        );

        if (
            $post->post_type == SheetModel::POST_TYPE
            && $slug == 'date'
            && !empty($value)
        ) {
            return date(get_option('date_format'), strtotime($value));
        }

        return $value;
    }
}

if (!function_exists('dlssus_the_tasks_table')) {
    /**
     * Output the tasks table
     *
     * @global WP_Post $post
     */
    function dlssus_the_tasks_table()
    {
        global $post;
        $sheet = new SheetModel($post);

        $taskTableController = new TaskTableController(
            $sheet,
            array('showSignupLink' => true)
        );
        $taskTableController->output();
    }
}

if (!function_exists('fdsus_the_signup_form_response')) {
    /**
     * Output the signup form response
     *
     * @param int|string|null $sheetId deprecated as of version 2.1.5.2
     */
    function fdsus_the_signup_form_response($sheetId = null)
    {
        echo apply_filters(Id::PREFIX . '_notices', null);
    }
}

if (!function_exists('fdsus_output_wrapper')) {
    /**
     * Output wrappers
     *
     * @param string $type ex: "start", "end", "content-start", "content-end"
     *
     * @since 2.1.4
     */
    function fdsus_output_wrapper($type)
    {
        dlssus_get_template_part('fdsus-global/wrapper', $type);
    }
}

if (!function_exists('fdsus_content_header_class')) {
    /**
     * Displays the class names for the entry header element.
     *
     * @since 2.1.4
     *
     * @param string|string[] $class Space-separated string or array of class names to add to the class list.
     */
    function fdsus_content_header_class($class = '')
    {
        $classes = array();

        if (is_archive()) {
            $classes[] = 'archive-header page-header';
        } else {
            $classes[] = 'entry-header';
        }

        if (!is_array($class)) {
            $class = explode(' ', $class);
        }

        // Theme support
        $template = strtolower(get_option('template'));
        switch ($template) {
            case 'twentytwenty':
                $class[] = 'has-text-align-center';
                if (is_singular()) {
                    $class[] = ' header-footer-group';
                }
                break;
            case 'virtue':
                $class[] = 'page-header';
                break;
        }

        $classes = array_merge($classes, $class);
        $classes = array_map('esc_attr', $classes);
        $classes = array_unique($classes);
        // Separates class names with a single space, collates class names for body element.
        echo 'class="' . join(' ', $classes) . '"';
    }
}

if (!function_exists('fdsus_h1_class')) {
    /**
     * Displays the class names for the entry header element.
     *
     * @since 2.1.4
     *
     * @param string|string[] $class Space-separated string or array of class names to add to the class list.
     * @param bool $echo true to echo or false to return
     *
     * @return
     */
    function fdsus_h1_class($class = '', $echo = true)
    {
        if (!is_array($class)) {
            $class = explode(' ', $class);
        }

        // Theme support
        $template = strtolower(get_option('template'));
        switch ($template) {
            case 'divi':
                $class[] = 'main_title';
                break;
        }

        $class = array_map('esc_attr', $class);
        $class = array_unique($class);
        // Separates class names with a single space, collates class names for body element.
        $return = 'class="' . join(' ', $class) . '"';
        if ($echo) {
            echo $return;
        } else {
            return $return;
        }
    }
}

if (!function_exists('fdsus_back_to_sheet_url')) {
    /**
     * Get the URL to go back to the sheet from a task signup page
     *
     * @param int $taskId
     *
     * @return string
     * @since 2.2
     */
    function fdsus_back_to_sheet_url($taskId)
    {
        $url = remove_query_arg(array('task_id', 'signup_id', 'redirect_to'));

        /**
         * Filter Back to Sheet URL
         *
         * @param string $url
         * @param int    $taskId
         */
        return apply_filters('fdsus_back_to_sheet_url', $url, $taskId);
    }
}

if (!function_exists('fdsus_current_url')) {
    /**
     * Current URL
     *
     * @return string
     * @since 2.2
     */
    function fdsus_current_url()
    {
        $baseModel = new BaseModel;

        return $baseModel->getCurrentUrl();
    }
}

if (!function_exists('fdsus_the_signup_form_additional_fields')) {
    /**
     * Output additional fields in the sign-up form
     *
     * @param SheetModel $sheet
     * @param array      $initial
     *
     * @since 2.2
     */
    function fdsus_the_signup_form_additional_fields($sheet, $initial)
    {
        /**
         * Action run to add additional fields to the sign-up form
         *
         * @param SheetModel $sheet
         * @param array      $initial
         */
        do_action('fdsus_signup_form_additional_fields', $sheet, $initial);
    }
}

if (!function_exists('fdsus_the_signup_form_last_fields')) {
    /**
     * Output additional fields in the sign-up form at the end (by default this will be right before the submit)
     *
     * @param SheetModel $sheet
     *
     * @since 2.2
     */
    function fdsus_the_signup_form_last_fields($sheet, $args)
    {
        /**
         * Action run to add additional fields to the sign-up form
         *
         * @param SheetModel $sheet
         * @param array      $args
         */
        do_action('fdsus_signup_form_last_fields', $sheet, $args);
    }
}

if (!function_exists('fdsus_is_honeypot_disabled')) {
    /**
     * Is honeypot disabled?
     *
     * @return bool
     * @since 2.2
     *
     */
    function fdsus_is_honeypot_disabled()
    {
        return Settings::isHoneypotDisabled();
    }
}

if (!function_exists('fdsus_is_all_captcha_disabled')) {
    /**
     * Is all captcha disabled?
     *
     * @return bool
     * @since 2.2
     */
    function fdsus_is_all_captcha_disabled()
    {
        $return = is_admin() || Settings::isAllCaptchaDisabled();

        /**
         * Filter return value for template tag fdsus_is_all_captcha_disabled()
         *
         * @param bool $return
         *
         * @since 2.2.11
         */
        return apply_filters('fdsus_is_all_captcha_disabled', $return);
    }
}

if (!function_exists('fdsus_is_recaptcha_enabled')) {
    /**
     * Is all captcha disabled?
     *
     * @return bool
     * @since 2.2
     */
    function fdsus_is_recaptcha_enabled()
    {
        return Settings::isRecaptchaEnabled();
    }
}

if (!function_exists('fdsus_recaptcha')) {
    /**
     * reCAPTCHA v2 HTML
     *
     * @param string $tagAttributes
     *
     * @return string
     * @since 2.2
     */
    function fdsus_recaptcha($tagAttributes = '')
    {
        if (!Settings::isRecaptchaEnabled() || Settings::getRecaptchaVersion() !== 'v2-checkbox') {
            return '';
        }

        /**
         * Filter reCAPTCHA tag attributes
         *
         * @param string $tagAttributes
         *
         * @return string
         */
        $tagAttributes = apply_filters('fdsus_recaptcha_tag_attributes', $tagAttributes);

        return '<p class="g-recaptcha" ' . esc_html($tagAttributes) . '
            data-sitekey="' . esc_attr(get_option('dls_sus_recaptcha_public_key')) . '"></p>';
    }
}

if (!function_exists('fdsus_captcha')) {
    /**
     * Captcha markup if applicable
     *
     * @since 2.2.11
     */
    function fdsus_captcha()
    {
        if (fdsus_is_all_captcha_disabled()) {
            return;
        }

        if (Settings::isRecaptchaEnabled()) {
            /**
             * Filter reCAPTCHA tag attributes
             *
             * @param string $tagAttributes
             *
             * @return string
             */
            $tagAttributes = apply_filters('fdsus_recaptcha_tag_attributes', '');
            echo fdsus_recaptcha($tagAttributes);
            return;
        }

        dlssus_get_template_part('fdsus/parts/captcha-simple');
    }
}

if (!function_exists('fdsus_signup_form_button_attributes')) {
    /**
     * Sign-up Form Button attributes (used for things like reCAPTCHA v3)
     *
     * @param string $tagAttributes
     *
     * @return string
     * @since 2.2
     */
    function fdsus_signup_form_button_attributes($tagAttributes = '')
    {
        $defaultReturn = 'class="button-primary wp-block-button__link wp-element-button"';

        if (!Settings::isRecaptchaEnabled()) {
            return $defaultReturn;
        }

        switch(Settings::getRecaptchaVersion()) {
            case 'v2-invisible':
                return 'class="button-primary wp-block-button__link wp-element-button g-recaptcha"
                    data-callback="fdsusSignupFormSubmit"
                    data-sitekey="' . esc_attr(get_option('dls_sus_recaptcha_public_key')) . '"';
            case 'v3':
                return 'class="button-primary wp-block-button__link wp-element-button g-recaptcha"
                    data-callback="fdsusSignupFormSubmit"
                    data-sitekey="' . esc_attr(get_option('dls_sus_recaptcha_public_key')) . '"
                    data-submit="submit"';
        }

        return $defaultReturn;
    }
}

if (!function_exists('fdsus_scode_user_sign_ups_actions')) {
    /**
     * Call do_action for customizing the user sign-ups after the additional fields output
     *
     * @param array $row
     *
     * @return void
     */
    function fdsus_scode_user_sign_ups_actions($row)
    {
        /**
         * [user_sign_ups] shortcode - after signup name is output
         *
         * @param SheetModel  $sheet
         * @param TaskModel   $task
         * @param SignupModel $signup
         *
         * @since 2.2.11
         */
        do_action('fdsus_scode_user_sign_ups_actions', $row['sheet'], $row['task'], $row['signup']);
    }
}

if (!function_exists('fdsus_is_pro')) {
    /**
     * Is the pro version of the plugin being used?
     *
     * @return bool true = pro version, false = free version
     */
    function fdsus_is_pro()
    {
        return Id::isPro();
    }
}
