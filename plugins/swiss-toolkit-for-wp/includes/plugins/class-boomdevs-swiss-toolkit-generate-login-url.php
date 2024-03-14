<?php

/**
 * Prevent direct access to this file.
 */
if (!defined('ABSPATH')) {
    exit;
}

require_once BDSTFW_SWISS_TOOLKIT_PATH . 'utils/Translator.php';
require_once BDSTFW_SWISS_TOOLKIT_PATH . 'vendor/autoload.php';

/**
 * Manages the generation and redirection of login URLs for the WP Swiss Toolkit plugin.
 */
if (!class_exists('BDSTFW_Swiss_Toolkit_Generate_Login_URL')) {
    class BDSTFW_Swiss_Toolkit_Generate_Login_URL
    {
        /**
         * The single instance of the class.
         */
        protected static $instance;

        /**
         * Returns the single instance of the class.
         *
         * @return BDSTFW_Swiss_Toolkit_Generate_Login_URL Singleton instance.
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
         * Initializes actions for login URL generation and redirection.
         */
        public function __construct()
        {
            add_action('init', [$this, 'swiss_redirect_to_admin']);
        }

        /**
         * Handles the redirection and login process for generated login URLs.
         */
        public function swiss_redirect_to_admin()
        {
            if (isset($_GET['sk'])) {
                $data = Translator::decode($_GET['sk']);
                $post_id = absint($data[0]);

                if ($post_id === '') {
                    include_once(BDSTFW_SWISS_TOOLKIT_PATH . '/template-part/generate-url-token-warning.php');
                }

                $swiss_usage_limitation = get_post_meta($post_id, 'swiss_usage_limitation', true);
                $swiss_usage_custom_limitation = get_post_meta($post_id, 'swiss_usage_custom_limitation', true);
                $usage_count = get_post_meta($post_id, 'bdstfw_swiss_usage_count', true);
                $expiration_time = get_post_meta($post_id, 'bdstfw_swiss_expiration_time', true);

                if (!($data[2] === 'administrator')) {
                    include_once(BDSTFW_SWISS_TOOLKIT_PATH . '/template-part/generate-url-token-warning.php');
                }

                if ($swiss_usage_limitation !== '' && $swiss_usage_limitation !== 'unlimited') {
                    if ($swiss_usage_limitation !== '' && $usage_count >= $swiss_usage_limitation) {
                        include_once(BDSTFW_SWISS_TOOLKIT_PATH . '/template-part/generate-url-limit-warning.php');
                    } elseif ($swiss_usage_custom_limitation !== '' && $usage_count >= $swiss_usage_custom_limitation) {
                        include_once(BDSTFW_SWISS_TOOLKIT_PATH . '/template-part/generate-url-limit-warning.php');
                    } else {
                        if ($expiration_time && $expiration_time > time()) {
                            $storedToken = get_post_meta($post_id, 'bdstfw_encrypted_token', true);

                            if ($_GET['sk'] === $storedToken) {
                                $user_id = get_post_meta($post_id, 'bdstfw_current_login_userId', true);
                                wp_set_auth_cookie(intval($user_id), 1, is_ssl());
                                include_once(BDSTFW_SWISS_TOOLKIT_PATH . '/template-part/generate-url-login-success.php');
                                echo '<script>
                                setTimeout(function(){
                                    window.location.href = "' . esc_url(admin_url()) . '";
                                }, 1000);
                            </script>';
                                $current_value = get_post_meta($post_id, 'bdstfw_swiss_usage_count', true);
                                $new_value = sanitize_text_field(intval($current_value) + intval(1));
                                update_post_meta($post_id, 'bdstfw_swiss_usage_count', $new_value);
                                exit();
                            } else {
                                include_once(BDSTFW_SWISS_TOOLKIT_PATH . '/template-part/generate-url-token-warning.php');
                            }
                        } else {
                            include_once(BDSTFW_SWISS_TOOLKIT_PATH . '/template-part/generate-url-token-warning.php');
                        }
                    }
                } else {
                    if ($expiration_time && $expiration_time > time()) {
                        $storedToken = get_post_meta($post_id, 'bdstfw_encrypted_token', true);

                        if ($_GET['sk'] === $storedToken) {
                            $user_id = get_post_meta($post_id, 'bdstfw_current_login_userId', true);
                            wp_set_auth_cookie(intval($user_id), 1, is_ssl());
                            include_once(BDSTFW_SWISS_TOOLKIT_PATH . '/template-part/generate-url-login-success.php');
                            echo '<script>
                            setTimeout(function(){
                                window.location.href = "' . esc_url(admin_url()) . '";
                            }, 1000);
                        </script>';
                            exit();
                        } else {
                            include_once(BDSTFW_SWISS_TOOLKIT_PATH . '/template-part/generate-url-token-warning.php');
                        }
                    } else {
                        include_once(BDSTFW_SWISS_TOOLKIT_PATH . '/template-part/generate-url-token-warning.php');
                    }
                }
            }
        }
    }

    // Initialize the BDSTFW_Swiss_Toolkit_Generate_Login_URL class
    BDSTFW_Swiss_Toolkit_Generate_Login_URL::get_instance();
}