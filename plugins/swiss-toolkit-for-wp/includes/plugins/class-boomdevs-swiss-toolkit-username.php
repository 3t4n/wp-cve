<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Include the plugin setting class
require_once BDSTFW_SWISS_TOOLKIT_PATH . 'includes/class-boomdevs-swiss-toolkit-settings.php';

/**
 * Class BDSTFW_Swiss_Toolkit_Username
 *
 * This class manages the username editing feature.
 */
if (!class_exists('BDSTFW_Swiss_Toolkit_Username')) {
    class BDSTFW_Swiss_Toolkit_Username
    {
        /**
         * The single instance of the class.
         *
         * @var BDSTFW_Swiss_Toolkit_Username|null
         */
        protected static $instance = null;

        /**
         * Returns the single instance of the class.
         *
         * @return BDSTFW_Swiss_Toolkit_Username The class instance.
         */
        public static function get_instance()
        {
            if (is_null(self::$instance)) {
                self::$instance = new self();
            }

            return self::$instance;
        }

        /**
         * Constructor.
         * Initializes the class and sets up actions for username editing.
         */
        public function __construct()
        {
            $settings = BDSTFW_Swiss_Toolkit_Settings::get_settings();

            if (isset($settings['boomdevs_swiss_edit_username_switch'])) {
                if ($settings['boomdevs_swiss_edit_username_switch'] === '1') {
                    add_action('show_user_profile', [$this, 'edit_username_field']);
                    add_action('edit_user_profile', [$this, 'edit_username_field']);
                    add_action('user_profile_update_errors', [$this, 'swiss_knife_update_username'], 10, 3);
                }
            }
        }

        /**
         * Display the username editing field in user profile.
         *
         * @param WP_User $user The user object.
         */
        public function edit_username_field($user)
        {
            ?>
            <table class="form-table">
                <tr>
                    <th><label for="edit_username"><?php echo esc_html(__('Edit Username', 'swiss-toolkit-for-wp')); ?></label></th>
                    <td>
                        <input type="text" name="edit_username" id="edit_username" value="<?php echo esc_attr(get_the_author_meta('user_login', $user->ID)); ?>" class="regular-text" /><br />
                        <span class="description"><?php echo esc_html(__('Enter your new username here.', 'swiss-toolkit-for-wp')); ?></span>
                    </td>
                </tr>
            </table>
            <?php
        }

        /**
         * Check if a string contains special characters.
         *
         * @param string $string The input string to check.
         * @return bool True if the string contains special characters, false otherwise.
         */
        public function swiss_knife_check_string($string)
        {
            return preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]|[ ]/', $string);
        }

        /**
         * Update the username after validation.
         *
         * @param WP_Error $errors An instance of WP_Error.
         * @param bool $update Whether to update the username.
         * @param WP_User $user The user object.
         */
        public function swiss_knife_update_username($errors, $update, $user)
        {
            global $wpdb;
            $wp_user = $wpdb->prefix . 'users';

            $new_username = sanitize_text_field($_POST['edit_username']);

            $exits = $this->swiss_knife_check_string($new_username);

            if ($exits) {
                $errors->add('user_exists', esc_html__('Username not allowed with special character.', 'swiss-toolkit-for-wp'));
                return false;
            }

            $existing_user = get_user_by('login', $new_username);

            if ($existing_user && $existing_user->ID !== $user->ID) {
                $errors->add('user_exists', esc_html__('Username already exists.', 'swiss-toolkit-for-wp'));
            } else {
                $data = array('user_login' => $new_username);
                $where = array('ID' => $user->ID);

                $wpdb->update($wp_user, $data, $where);
            }
        }
    }

    // Initialize the class
    BDSTFW_Swiss_Toolkit_Username::get_instance();
}