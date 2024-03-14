<?php

/**
 * Prevent direct access to this file.
 */
if (!defined('ABSPATH')) {
    exit;
}

if (!class_exists('Redux')) {
    return;
}

/**
 * Class for managing settings and options in the WP Swiss Toolkit plugin.
 */
if (!class_exists('BDSTFW_Swiss_Toolkit_Settings')) {
    class BDSTFW_Swiss_Toolkit_Settings
    {
        /**
         * Plugin settings prefix.
         *
         * @var string
         */
        public static $prefix = BDSTFW_SWISS_TOOLKIT_NAME;

        public $max_up_size = 64;

        /**
         * The single instance of the class.
         */
        protected static $instance;

        /**
         * Returns single instance of the class
         */
        public static function get_instance()
        {
            if (is_null(self::$instance)) {
                self::$instance = new self();
            }

            return self::$instance;
        }

        /**
         * Constructor function for initializing actions and filters.
         */
        public function __construct()
        {
            add_action('admin_notices', [$this, 'swiss_toolkit_notice']);
            $this->generate_settings();
            $this->check_max_size();
        }

        public function check_max_size()
        {
            $max_upload_size = wp_max_upload_size();

            if (!$max_upload_size) {
                $max_upload_size = 0;
            } else {
                $max_upload_size = $max_upload_size / 1048576;
            }

            $this->max_up_size = $max_upload_size;
        }

        public function remove_special_character($input_string): string
        {
            // Remove special characters and make first character uppercase
            $cleaned_string = preg_replace_callback('/[^a-zA-Z0-9]+/', function ($match) {
                return ' ';
            }, $input_string);

            return ucwords(strtolower(trim($cleaned_string)));
        }

        /**
         * Retrieve all WordPress user roles (except 'administrator').
         *
         * This method fetches all available user roles excluding the 'administrator' role,
         * which is typically reserved for site administrators.
         *
         * @return array An associative array containing role names as both keys and values.
         */
        public function get_all_roles()
        {
            // Include WordPress core functions.
            require_once(ABSPATH . 'wp-load.php');

            // Fetch all user roles.
            $roles = wp_roles()->roles;

            // Initialize an empty array to store role options.
            $options = array();

            // Iterate through roles and add them to the options array.
            foreach ($roles as $role_name => $role) {
                if ($role_name !== 'administrator') {
                    // Exclude the 'administrator' role.
                    $options[$role_name] = $this->remove_special_character($role_name);
                }
            }

            // Return the array of role options.
            return $options;
        }

        public function generate_settings()
        {
            $pro_global_settings = apply_filters('wp_swiss_toolkit_spotlight_search_premium_settings', []);
            $switch_user_pro_settings = apply_filters('wp_swiss_toolkit_switch_user_premium_settings', []);
            $login_url_pro_settings = apply_filters('wp_swiss_toolkit_admin_login_url', []);

            if (!$pro_global_settings) {
                $pro_global_settings = array(
                    array(
                        'id' => 'boomdevs_swiss_spotlight_search_switch',
                        'type' => 'switch',
                        'title' => sprintf(
                            esc_html__('Spotlight Search %s'),
                            '<strong>' . esc_html('(Pro)') . '</strong>'
                        ),
                        'subtitle' => esc_html__('Discover all your WordPress settings in one central hub.', 'swiss-toolkit-for-wp'),
                        'default' => false,
                        'class' => 'pro_only bdstfw_switch_toggle',
                        'attributes' => array(
                            'disabled' => 'disabled'
                        )
                    )
                );
            }

            if (!$switch_user_pro_settings) {
                $switch_user_pro_settings = array(
                    array(
                        'id' => 'boomdevs_swiss_user_switching_switcher',
                        'type' => 'switch',
                        'title' => sprintf(
                            esc_html__('User Switching %s', 'swiss-toolkit-for-wp'),
                            '<strong>' . esc_html('(Pro)') . '</strong>'
                        ),
                        'subtitle' => esc_html__('Quickly switch between user accounts.', 'swiss-toolkit-for-wp'),
                        'class' => 'pro_only bdstfw_switch_toggle',
                        'default' => false
                    ),
                );
            }

            if (!$login_url_pro_settings) {
                $login_url_pro_settings = array(
                    array(
                        'id' => 'boomdevs_swiss_change_login_url_switcher',
                        'type' => 'switch',
                        'title' => sprintf(
                            esc_html__('WP Admin Login URL Changer %s', 'swiss-toolkit-for-wp'),
                            '<strong>' . esc_html('(Pro)') . '</strong>'
                        ),
                        'subtitle' => esc_html__('Secures WordPress site effortlessly by modify admin login URL to enhance sites security.', 'swiss-toolkit-for-wp'),
                        'help' => esc_html__('Please be careful with the address', 'swiss-toolkit-for-wp'),
                        'class' => 'pro_only bdstfw_switch_toggle',
                        'default' => false,
                    ),
                );
            }

            $args = array(
                'opt_name' => BDSTFW_Swiss_Toolkit_Settings::$prefix,
                'menu_icon' => BDSTFW_SWISS_TOOLKIT_URL . 'admin/img/toolkit-settings.svg',
                'menu_title' => esc_html__('WP Swiss Toolkit', 'swiss-toolkit-for-wp'),
                'page_slug' => 'boomdevs-swiss-toolkit-settings',
                'page_title' => esc_html__('Sample Options', 'swiss-toolkit-for-wp'),
                'database' => 'option',
                'page_priority' => '59',
                'menu_type' => 'menu',
                'show_import_export' => true,
                'dev_mode' => false,
                'disable_save_warn' => false,
                'search' => false,
                'footer_text'     => sprintf('Our WP Swiss Toolkit documentation can help you get started <a href="%s">documentation</a>', esc_url('https://boomdevs.com/docs/')),
                'footer_credit'   => sprintf('A proud creation of <a href="%s">BoomDevs</a>', esc_url('https://boomdevs.com/')),
                'page_permissions' => 'manage_options',
                'class' => 'bdstfw_swiss_toolkit_framework',
            );

            Redux::set_args(BDSTFW_Swiss_Toolkit_Settings::$prefix, $args);


            $section_id = 'general_settings';
            Redux::set_section(BDSTFW_Swiss_Toolkit_Settings::$prefix, array(
                'title' => esc_html__('Features', 'swiss-toolkit-for-wp'),
                'id' => $section_id,
                'icon' => '',
                'fields' => array(
                    array(
                        'type' => 'section',
                        'title' => esc_html__('Appearance', 'swiss-toolkit-for-wp'),
                        'subtitle' => esc_html__('Customize options related to the block editor interface and editing flow.', 'swiss-toolkit-for-wp'),
                        'class' => 'bdstfw_functional_content',
                    ),
                    /***
                     * Maximum upload file size and max execution time
                     */
                    array(
                        'id'      => 'boomdevs_swiss_upload_file_size_and_execution_time_switcher',
                        'type'    => 'switch',
                        'title'   => esc_html__('Maximum Upload Size Increaser', 'swiss-toolkit-for-wp'),
                        'subtitle' => esc_html__('Unlock the power of unlimited uploading', 'swiss-toolkit-for-wp'),
                        'class' => 'bdstfw_switch_toggle',
                        'default' => false,
                    ),
                    //dependency conditions for Maximum upload file size
                    array(
                        'id'      => 'boomdevs_swiss_maximum_upload_file_size',
                        'type'    => 'text',
                        'title'   => esc_html__('Maximum Upload File Size', 'swiss-toolkit-for-wp'),
                        'desc' => esc_html__('Enter the file size in MB.', 'swiss-toolkit-for-wp'),
                        'class' => 'maximum_upload_file_size bdstfw_upload_size_input',
                        'default' => $this->max_up_size,
                        'validate' => array('numeric'),
                        'required' => array('boomdevs_swiss_upload_file_size_and_execution_time_switcher', 'equals', 'true'),
                    ),
                    //dependency conditions for Maximum upload file size
                    array(
                        'id'      => 'boomdevs_swiss_max_execution_time',
                        'type'    => 'text',
                        'title'   => esc_html__('Maximum execution time', 'swiss-toolkit-for-wp'),
                        'desc' => esc_html__('Input the time in seconds.', 'swiss-toolkit-for-wp'),
                        'class' => 'maximum_execution_time bdstfw_upload_size_input',
                        'default' => 120,
                        'validate' => array('numeric'),
                        'required' => array('boomdevs_swiss_upload_file_size_and_execution_time_switcher', 'equals', 'true'),
                    ),

                    /***
                     * Start favicon uploader
                     */
                    array(
                        'id' => 'boomdevs_swiss_favicon_uploader_switch',
                        'type' => 'switch',
                        'title' => esc_html__('Favicon Uploader', 'swiss-toolkit-for-wp'),
                        'subtitle' => esc_html__('Hassle-Free Favicon Replacement: Simple Upload and Apply', 'swiss-toolkit-for-wp'),
                        'class' => 'bdstfw_switch_toggle',
                        'default' => false,
                    ),

                    array(
                        'id' => 'boomdevs_swiss_favicon_uploader_image',
                        'type' => 'media',
                        'default' => array(
                            'url' => 'https://s.wordpress.org/style/images/codeispoetry.png'
                        ),
                        'url' => false,
                        'class' => 'bdstfw_favicon_uploader',
                        'required' => array('boomdevs_swiss_favicon_uploader_switch', 'equals', 'true')
                    ),

                    /***
                     * Start Extension Supports
                     */
                    array(
                        'id' => 'boomdevs_swiss_extension_supports_toggle',
                        'type' => 'switch',
                        'title' => esc_html__('Enhanced Multi-Format Image Support', 'swiss-toolkit-for-wp'),
                        'subtitle' => wp_kses(__('Please specify the desired image format by entering the extension name. Supported formats include: <strong>.jpg</strong>, <strong>.png</strong>, <strong>.avi</strong>, <strong>.webp</strong>', 'swiss-toolkit-for-wp'), array('strong' => array())),
                        'class' => 'bdstfw_switch_toggle',
                        'default' => false
                    ),

                    array(
                        'id' => 'boomdevs_swiss_extension_supports_textarea',
                        'type' => 'textarea',
                        'class' => 'boomdevs_swiss_extension_supports_textarea',
                        'default' => false,
                        'required' => array('boomdevs_swiss_extension_supports_toggle', 'equals', 'true')
                    ),

                    /***
                     * Start Svg Image Support
                     */
//                    array(
//                        'id' => 'boomdevs_swiss_svg_support',
//                        'type' => 'switch',
//                        'title' => esc_html__('Svg Image Support', 'swiss-toolkit-for-wp'),
//                        'subtitle' => esc_html__('Revolutionize Graphics with Scalable Vectors', 'swiss-toolkit-for-wp'),
//                        'class' => 'bdstfw_switch_toggle',
//                        'default' => false
//                    ),

                    /***
                     * Start WebP Image Support
                     */
//                    array(
//                        'id' => 'boomdevs_swiss_webp_support',
//                        'type' => 'switch',
//                        'title' => esc_html__('WebP Image Support', 'swiss-toolkit-for-wp'),
//                        'subtitle' => esc_html__('Elevate Performance with WebP Format', 'swiss-toolkit-for-wp'),
//                        'class' => 'bdstfw_switch_toggle',
//                        'default' => false
//                    ),

                    /***
                     * Start Avif Image Support
                     */
//                    array(
//                        'id' => 'boomdevs_swiss_avif_support',
//                        'type' => 'switch',
//                        'title' => esc_html__('Avif Image Support', 'swiss-toolkit-for-wp'),
//                        'subtitle' => esc_html__('Elevate Performance with Avif Format', 'swiss-toolkit-for-wp'),
//                        'class' => 'bdstfw_switch_toggle',
//                        'default' => false
//                    ),

                    /***
                     * Start Avatar Image Support
                     */
                    array(
                        'id'      => 'boomdevs_swiss_avatar_uploader_switcher',
                        'type'    => 'switch',
                        'title'   => esc_html__('Upload Custom Avatar', 'swiss-toolkit-for-wp'),
                        'subtitle' => sprintf(
                            esc_html__('Change your default avatar by uploading an image directly without relying on 3rd party website.%sTo change username goto User → Profile → Change Avatar', 'swiss-toolkit-for-wp'),
                            '<br/>'
                        ),
                        'class' => 'bdstfw_switch_toggle',
                        'default' => false,
                    ),
                    array(
                        'id'         => 'boomdevs_swiss_avatar_uploader_permissions',
                        'type'       => 'checkbox',
                        'title'      => esc_html__('Permissions', 'swiss-toolkit-for-wp'),
                        'options'    => $this->get_all_roles(),
                        'class'      => 'boomdevs_swiss_avatar_uploader_permissions',
                        'required' => array('boomdevs_swiss_avatar_uploader_switcher', 'equals', 'true')
                    ),

                    /***
                     * Start Username Change
                     */
                    array(
                        'id'     => 'boomdevs_swiss_edit_username_switch',
                        'type'   => 'switch',
                        'title'   => esc_html__('Username Changer', 'swiss-toolkit-for-wp'),
                        'subtitle' => sprintf(
                            esc_html__('The previous limitations are gone - you have the freedom to revise your WP login username repeatedly.%sTo change username goto User → Profile → Edit Username', 'swiss-toolkit-for-wp'),
                            '<br/>'
                        ),
                        'class' => 'swiss_edit_username_switch bdstfw_switch_toggle',
                        'default' => false,
                    ),

                    array(
                        'type' => 'section',
                        'title' => esc_html__('Functional', 'swiss-toolkit-for-wp'),
                        'subtitle' => esc_html__('CustomizeCustomize options related to the block editor interface and editing flow.', 'swiss-toolkit-for-wp'),
                        'class' => 'bdstfw_functional_content',
                    ),

                    /***
                     * Start Page Duplicator
                     */
                    array(
                        'id' => 'boomdevs_swiss_Post_Page_duplicator',
                        'type' => 'switch',
                        'title' => esc_html__('Post/Page Duplicator', 'swiss-toolkit-for-wp'),
                        'subtitle' => esc_html__('Easily enable duplication of posts and pages directly from the post/page list page.', 'swiss-toolkit-for-wp'),
                        'class' => 'bdstfw_switch_toggle',
                        'default' => false
                    ),

                    /***
                     * Start bulk theme delete
                     */
                    array(
                        'id' => 'boomdevs_swiss_bulk_theme_delete',
                        'type' => 'switch',
                        'title' => esc_html__('Bulk Theme Remover', 'swiss-toolkit-for-wp'),
                        'subtitle' => sprintf(
                            esc_html__('Get rid of the unnecessary theme all at once by selecting and deleting theme.%sFor bulk theme deletion, goto Appearance → Themes → Select', 'swiss-toolkit-for-wp'),
                            '<br/>'
                        ),
                        'class' => 'bdstfw_switch_toggle',
                        'default' => false
                    ),

                    /***
                     * Start login URL change
                     */
                    ...$login_url_pro_settings,

                    /***
                     * Start user switching
                     */
                    ...$switch_user_pro_settings,

                    /***
                     * Start insert header and footer
                     */
                    array(
                        'id' => 'boomdevs_swiss_insert_header_footer_switch',
                        'type' => 'switch',
                        'title' => esc_html__('Headers And Footers Inserter', 'swiss-toolkit-for-wp'),
                        'subtitle' => sprintf('Enable users to incorporate an infinite number of code snippets without the necessity of adding extra plugins. <p class="access_snippets">To access snippets</p> <a href="%s">click here.</a>', esc_url(home_url('wp-admin/edit.php?post_type=swiss_snippets'))),
                        'class' => 'bdstfw_switch_toggle',
                        'default' => false
                    ),

                    /***
                     * Start generate login url
                     */
                    array(
                        'id'     => 'boomdevs_swiss_generate_url_switch',
                        'type'   => 'switch',
                        'title'   => esc_html__('Passwordless Temporary Login', 'swiss-toolkit-for-wp'),
                        'subtitle'   => sprintf('Direct link logins (no username/password required) allow you to quickly create temporary admin accounts. <p class="access_snippets">To access temp login</p> <a href="%s">click here.</a>', esc_url(home_url('wp-admin/edit.php?post_type=swiss_generate_url'))),
                        'class' => 'bdstfw_switch_toggle',
                        'default' => false
                    ),

                    /***
                     * Start spotlight search
                     */
                    ...$pro_global_settings,
                ),
            ));
        }

        /**
         * Display a notice for WP Swiss Toolkit in the WordPress admin.
         *
         * This function displays a notice on the admin page when the user is on the WP Swiss Toolkit settings page.
         * It provides information about the plugin and a link to the developer's website.
         */
        public function swiss_toolkit_notice()
        {
            global $pagenow;

            // Check if the current page is 'admin.php'
            if ($pagenow === 'admin.php') {
                // Check if the 'page' query parameter is set to 'boomdevs-swiss-toolkit-settings'
                if (isset($_GET['page']) && $_GET['page'] === 'boomdevs-swiss-toolkit-settings') {
?>
                    <div class="swiss-toolkit-top">
                        <h3><?php echo esc_html__('WP Swiss Toolkit | Plugin by BoomDevs', 'swiss-toolkit-for-wp') ?></h3>

                        <div class="swiss-toolkit-top-notice">
                            <h6><?php echo esc_html__('About Us', 'swiss-toolkit-for-wp') ?></h6>
                            <p class="swiss-toolkit-top-notice-content"><?php echo sprintf("We're a team of passionate WordPress developers committed to enhancing your website's functionality. Our goal is to bring innovative and user-friendly solutions to WordPress users. Explore our complete range of offering <a href='%s'>plugins</a> to learn more to empower your WordPress journey.", esc_url('https://staging53.boomdevs.com/product-category/wordpress/wordpress-plugins/')); ?></p>
                            <p><?php echo esc_html__('Check out our website for more information:', 'swiss-toolkit-for-wp'); ?> <a href="<?php echo esc_url('https://boomdevs.com'); ?>" target="_blank"><?php echo esc_url('https://boomdevs.com'); ?></a></p>
                            <span class="swiss-toolkit-notice-close">
                                <svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M6 12C9.31373 12 12 9.31373 12 6C12 2.68629 9.31373 0 6 0C2.68629 0 0 2.68629 0 6C0 9.31373 2.68629 12 6 12ZM8.35353 3.64645C8.5488 3.84171 8.5488 4.15829 8.35353 4.35355L6.70713 6L8.35353 7.64647C8.5488 7.84173 8.5488 8.15827 8.35353 8.35353C8.15827 8.5488 7.84173 8.5488 7.64647 8.35353L6 6.70713L4.35355 8.35353C4.15829 8.5488 3.84171 8.5488 3.64645 8.35353C3.45119 8.15827 3.45119 7.84173 3.64645 7.64647L5.29287 6L3.64645 4.35355C3.45119 4.15829 3.45119 3.84171 3.64645 3.64645C3.84171 3.45119 4.15829 3.45119 4.35355 3.64645L6 5.29287L7.64647 3.64645C7.84173 3.45119 8.15827 3.45119 8.35353 3.64645Z" fill="#949494" />
                                </svg>
                            </span>
                        </div>
                    </div>
<?php
                }
            }
        }

        /**
         * Retrieve all settings for the WP Swiss Toolkit plugin.
         *
         * @return array|string Plugin settings values.
         */
        public static function get_settings()
        {
            // Retrieve and return the plugin settings using the prefix defined in Boomdevs_Swiss_Toolkit_Settings::$prefix
            return get_option(BDSTFW_Swiss_Toolkit_Settings::$prefix);
        }
    }

    new BDSTFW_Swiss_Toolkit_Settings();
}
