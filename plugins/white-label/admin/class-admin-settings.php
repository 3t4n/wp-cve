<?php
/**
 * Admin Settings.
 *
 * @package White Label
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * white_label_Admin_Settings
 *
 * Create and setup the admin options page.
 */
class white_label_Admin_Settings
{
    /**
     * Settings API.
     *
     * @var $settings_api white_label_Settings_Api class.
     */
    private $settings_api;

    /**
     * Constants.
     *
     * @var $conatants contains plugin setup.
     */
    private $constants;

    /**
     * Construct.
     *
     * @param object $constants contains plugin setup.
     */
    public function __construct($constants)
    {
        // Load only on admin side.
        if (!is_admin()) {
            return;
        }

        // set up the plugin config.
        $this->constants = $constants;

        // Add menus.
        add_action('admin_menu', [$this, 'menu']);
        // Create our settings.
        add_action('admin_init', [$this, 'register_settings']);
        // Quick settings link.
        add_action('plugin_action_links_'.plugin_basename($this->constants['file']), [$this, 'action_links']);
        // Add Scripts
        add_action('admin_enqueue_scripts', [$this, 'scripts']);
    }

    /**
     * Add plugin action links.
     *
     * Add a link to the settings page on the plugins.php page.
     *
     * @since 1.0.0
     *
     * @param  array $links List of existing plugin action links.
     * @return array         List of modified plugin action links.
     */
    public function action_links($links)
    {
        $links = array_merge(
            [
                '<a href="'.esc_url('https://whitewp.com/pro?utm_source=plugin_white_label&utm_content=plugins').'" target="_blank"><b style="color:#0055cf">'.__('Get White Label Pro', 'white-label').'</b></a>',
            ],
            [
                '<a href="'.esc_url(admin_url('/options-general.php?page=white-label')).'">'.__('Settings', 'white-label').'</a>',
            ],
            [
                '<a href="'.esc_url('https://whitewp.com/documentation?utm_source=plugin_white_label&utm_content=plugins').'" target="_blank">'.__('Docs', 'white-label').'</a>',
            ],
            $links
        );

        return $links;
    }

    public function sections()
    {
        $sections = [];

        $sections['white_label_general'] = [
            'id' => 'white_label_general',
            'title' => __('General', 'white_label'),
            'requires_verification' => false,
        ];

        $sections['white_label_login'] = [
            'id' => 'white_label_login',
            'title' => __('Login', 'white_label'),
            'requires_verification' => false,
        ];

        $sections['white_label_visual_tweaks'] = [
            'id' => 'white_label_visual_tweaks',
            'title' => __('Admin', 'white-label'),
            'requires_verification' => false,
        ];

        $sections['white_label_dashboard'] = [
            'id' => 'white_label_dashboard',
            'title' => __('Dashboard', 'white-label'),
            'requires_verification' => false,
        ];

        $sections['white_label_menus'] = [
            'id' => 'white_label_menus',
            'title' => __('Menus', 'white-label'),
            'requires_verification' => false,
        ];
        
        $sections['white_label_plugins'] = [
            'id' => 'white_label_plugins',
            'title' => __('Plugins', 'white-label'),
            'requires_verification' => false,
        ];
        
        $sections['white_label_themes'] = [
            'id' => 'white_label_themes',
            'title' => __('Themes', 'white-label'),
            'requires_verification' => false,
        ];

        $sections['white_label_front_end'] = [
            'id' => 'white_label_front_end',
            'title' => __('Front End', 'white_label'),
            'requires_verification' => false,
        ];


        if (is_multisite()) {
            $sections['white_label_multisite'] = [
                'id' => 'white_label_multisite',
                'title' => __('Multisite', 'white_label'),
                'requires_verification' => false,
            ];
        }

        $sections['white_label_import_export'] = [
            'id' => 'white_label_import_export',
            'title' => __('Import & Export', 'white-label'),
            'requires_verification' => false,
            'custom_tab' => true,
        ];

        $sections['white_label_upgrade'] = [
            'id' => 'white_label_upgrade',
            'title' => __('Upgrade', 'white-label'),
            'requires_verification' => false,
        ];

        return $sections;
    }

    /**
     * Create our settings fields, sections and sidebars.
     *
     * @param mixed $get_part either sections, fields or sidebars.
     */
    public function settings($get_part = false)
    {
        $sections = $this->sections();
        $fields = [];
        
        $fields['white_label_general'] = [
            [
                'name' => 'general_section',
                'label' => __('White Label', 'white-label'),
                'desc' => __('Quickly turn on and off all White Label settings.', 'white-label'),
                'type' => 'subheading',
                'class' => 'subheading',
            ],
            [
                'name' => 'enable_white_label',
                'label' => __('Enable White Label', 'white-label'),
                'desc' => __('Turn on White Labeling for this site.', 'white-label'),
                'type' => 'checkbox',
            ],
            [
                'name' => 'wl_admin_sub_section',
                'label' => __('White Label Administators', 'white-label').'<a target="_blank" tabindex="-1" class="white-label-help" href="https://whitewp.com/documentation/article/white-label-administrators/"><span class="dashicons dashicons-editor-help"></span></a>',
                'desc' => __('A White Label Administrator will bypass other rules set inside the White Label plugin. You will be able to hide sensitive menus, plugins, 
                updates, and more from all normal administrators. <a target="_blank" tabindex="-1" href="https://whitewp.com/documentation/article/white-label-administrators?utm_source=plugin_white_label&utm_content=general">Learn more about White Label Administrators</a>.', 'white-label'),
                'type' => 'subheading',
                'class' => 'subheading',
            ],
            [
                'name' => 'wl_administrators',
                'label' => __('White Label Administrators', 'white-label'),
                'desc' => __('Select which administrators should also be White Label Administrators.', 'white-label'),
                'type' => 'multicheck',
                'options' => white_label_get_regular_admins(),
            ],

        ];

        $fields['white_label_login'] = [
            [
                'name' => 'login_section',
                'label' => __('Template', 'white-label'),
                'desc' => __('Set the template of the login page.', 'white-label'),
                'type' => 'subheading',
                'class' => 'subheading',
            ],
            [
                'name' => 'login_page_template',
                'label' => __('Alignment', 'white-label').'<a target="_blank" tabindex="-1" class="white-label-help" href="https://whitewp.com/documentation/article/align-wordpress-login/"><span class="dashicons dashicons-editor-help"></span></a>',
                'type' => 'radio',
                'class' => 'setting-with-image',
                'options' => [
                    '' => '<span class="dashicons dashicons-align-center"></span>'.__('Default', 'white-label'),
                    'left' => '<span class="dashicons dashicons-align-left"></span>'.__('Left Login', 'white-label'),
                    'right' => '<span class="dashicons dashicons-align-right"></span>'.__('Right Login', 'white-label'),
                ],
            ],
            [
                'name' => 'business_name',
                'label' => __('Login Logo Name', 'white-label'),
                'desc' => __('Your business name will be included inside the link attribute on the login logo.', 'white-label'),
                'placeholder' => __('Business Name', 'white-label'),
                'type' => 'text',
            ],
            [
                'name' => 'business_url',
                'label' => __('Login Logo Link', 'white-label').'<a target="_blank" tabindex="-1" class="white-label-help" href="https://whitewp.com/documentation/article/wordpress-login-logo-link/"><span class="dashicons dashicons-editor-help"></span></a>',
                'desc' => __('The login logo will link to your business URL.', 'white-label'),
                'placeholder' => __('https://whitewp.com/', 'white-label'),
                'type' => 'url',
            ],
            [
                'name' => 'login_logo_file',
                'label' => __('Login Logo Image', 'white-label').'<a target="_blank" tabindex="-1" class="white-label-help" href="https://whitewp.com/documentation/article/custom-wordpress-login-logo/"><span class="dashicons dashicons-editor-help"></span></a>',
                'desc' => __('Replaces the WordPress logo on the login screen.', 'white-label'),
                'type' => 'file',
                'default' => '',
                'options' => [
                    'button_label' => __('Choose Logo', 'white-label'),
                ],
            ],
            [
                'name' => 'login_logo_width',
                'label' => __('Login Logo Width', 'white-label'),
                'desc' => __('Width of login logo in pixels.', 'white-label'),
                'type' => 'number',
                'default' => '',
                'max' => '300',
            ],
            [
                'name' => 'login_logo_height',
                'label' => __('Login Logo Height', 'white-label'),
                'desc' => __('Height of login logo in pixels.', 'white-label'),
                'type' => 'number',
                'default' => '',
            ],
            [
                'name' => 'login_background_file',
                'label' => __('Background Image', 'white-label').'<a target="_blank" tabindex="-1" class="white-label-help" href="https://whitewp.com/documentation/article/wordpress-login-background/"><span class="dashicons dashicons-editor-help"></span></a>',
                'desc' => __('Choose a background image to use on the login screen.', 'white-label'),
                'type' => 'file',
                'default' => '',
                'options' => [
                    'button_label' => __('Choose Image', 'white-label'),
                ],
            ],
            [
                'name' => 'login_remove_remember_me_checkbox',
                'label' => __('Remember Me', 'white-label').'<a target="_blank" tabindex="-1" class="white-label-help" href="https://whitewp.com/documentation/article/remove-wordpress-remember-me/"><span class="dashicons dashicons-editor-help"></span></a>',
                'desc' => __('Remove the Remember Me checkbox from the WordPress login page.', 'white-label'),
                'type' => 'checkbox',
            ],
            [
                'name' => 'login_remove_language_switcher',
                'label' => __('Language Switcher', 'white-label').'<a target="_blank" tabindex="-1" class="white-label-help" href="https://whitewp.com/documentation/article/remove-wordpress-language-switcher/"><span class="dashicons dashicons-editor-help"></span></a>',
                'desc' => __('Remove the Language Switcher from the WordPress login page.', 'white-label'),
                'type' => 'checkbox',
            ],
            [
                'name' => 'login_remove_go_to_site_link',
                'label' => __('Go to Site', 'white-label').'<a target="_blank" tabindex="-1" class="white-label-help" href="https://whitewp.com/documentation/article/remove-wordpress-go-to-site-link/"><span class="dashicons dashicons-editor-help"></span></a>',
                'desc' => __('Remove the Go to Site link from the WordPress login page.', 'white-label'),
                'type' => 'checkbox',
            ],
            [
                'name' => 'login_remove_lost_your_password_link',
                'label' => __('Lost Your Password?', 'white-label').'<a target="_blank" tabindex="-1" class="white-label-help" href="https://whitewp.com/documentation/article/wordpress-lost-your-password/"><span class="dashicons dashicons-editor-help"></span></a>',
                'desc' => __('Remove the Lost Your Password? link from the WordPress login page.', 'white-label'),
                'type' => 'checkbox',
            ],
            [
                'name' => 'color_section',
                'label' => __('Color Scheme', 'white-label').'<a target="_blank" tabindex="-1" class="white-label-help" href="https://whitewp.com/documentation/article/wordpress-login-page-colors/"><span class="dashicons dashicons-editor-help"></span></a>',
                'desc' => __('Customize the WordPress login page color scheme to suit your branding.', 'white-label'),
                'type' => 'subheading',
                'class' => 'subheading',
            ],
            [
                'name' => 'login_background_color',
                'label' => __('Background Color', 'white-label'),
                'desc' => __('Background color of the login page if you do not have an image selected.', 'white-label'),
                'type' => 'color',
                'default' => '#f1f1f1',
            ],
            [
                'name' => 'login_box_background_color',
                'label' => __('Login Box Background Color', 'white-label'),
                'desc' => __('Background color of the login details box.', 'white-label'),
                'type' => 'color',
                'default' => '#fff',
            ],
            [
                'name' => 'login_box_text_color',
                'label' => __('Login Box Text Color', 'white-label'),
                'desc' => __('Color of the text inside the login box.', 'white-label'),
                'type' => 'color',
                'default' => '#444',
            ],
            [
                'name' => 'login_text_color',
                'label' => __('Link Color', 'white-label'),
                'desc' => __('Color of the links outside the login box.', 'white-label'),
                'type' => 'color',
                'default' => '#555d66',
            ],
            [
                'name' => 'login_button_background_color',
                'label' => __('Button Background Color', 'white-label'),
                'desc' => __('Background color of the login button.', 'white-label'),
                'type' => 'color',
                'default' => '#007cba',
            ],
            [
                'name' => 'login_button_border_color',
                'label' => __('Button Border Color', 'white-label'),
                'desc' => __('Border color of the login button.', 'white-label'),
                'type' => 'color',
                'default' => '#2271b1',
            ],
            [
                'name' => 'login_button_font_color',
                'label' => __('Button Text Color', 'white-label'),
                'desc' => __('Color of the login button text.', 'white-label'),
                'type' => 'color',
                'default' => '#fff',
            ],
            [
                'name' => 'css_section',
                'label' => __('CSS', 'white-label').'<a target="_blank" tabindex="-1" class="white-label-help" href="https://whitewp.com/documentation/article/wordpress-login-css/"><span class="dashicons dashicons-editor-help"></span></a>',
                'desc' => __('Add your own custom CSS to the WordPress login page.', 'white-label'),
                'type' => 'subheading',
                'class' => 'subheading',
            ],
            [
                'name' => 'login_custom_css',
                'label' => __('Custom CSS', 'white-label'),
                'desc' => __('Any CSS in this box will apply to the login screen.', 'white-label'),
                'placeholder' => '',
                'type' => 'textarea',
                'size' => 'large',
            ],
        ];

        $fields['white_label_visual_tweaks'] = [
            [
                'name' => 'admin_bar_section',
                'label' => __('Admin Bar', 'white-label'),
                'desc' => __('Customize the admin bar by replacing text and logos.', 'white-label'),
                'type' => 'subheading',
                'class' => 'subheading',
            ],
            [
                'name' => 'admin_howdy_replacment',
                'label' => __('Howdy Text', 'white-label').'<a target="_blank" tabindex="-1" class="white-label-help" href="https://whitewp.com/documentation/article/replace-howdy-wordpress/"><span class="dashicons dashicons-editor-help"></span></a>',
                'desc' => '',
                'placeholder' => 'Howdy',
                'type' => 'text',
            ],
            [
                'name' => 'admin_remove_wp_logo',
                'label' => __('Remove WordPress Logo', 'white-label').'<a target="_blank" tabindex="-1" class="white-label-help" href="https://whitewp.com/documentation/article/how-to-remove-the-wordpress-logo/"><span class="dashicons dashicons-editor-help"></span></a>',
                'desc' => __('Remove the WordPress logo in the admin bar.', 'white-label'),
                'type' => 'checkbox',
            ],
            [
                'name' => 'admin_replace_wp_logo',
                'label' => __('Replace WordPress Logo', 'white-label').'<a target="_blank" tabindex="-1" class="white-label-help" href="https://whitewp.com/documentation/article/replace-wordpress-logo/"><span class="dashicons dashicons-editor-help"></span></a>',
                'desc' => __('Replace the WordPress logo in the admin bar with your own.', 'white-label'),
                'placeholder' => __('https://whitewp.com/', 'white-label'),
                'type' => 'file',
                'options' => [
                    'button_label' => __('Choose Logo', 'white-label'),
                ],
            ],
            [
                'name' => 'admin_color_scheme_section',
                'label' => __('Color Scheme', 'white-label').'<a target="_blank" tabindex="-1" class="white-label-help" href="https://whitewp.com/documentation/article/wordpress-admin-color-scheme/"><span class="dashicons dashicons-editor-help"></span></a>',
                'desc' => __('Customize the admin color scheme.', 'white-label'),
                'type' => 'subheading',
                'class' => 'subheading',
            ],
            [
                'name' => 'admin_color_scheme_enable',
                'label' => __('Enable Admin Color Scheme', 'white-label'),
                'desc' => __('Apply this admin color scheme to all users.', 'white-label'),
                'type' => 'checkbox',
            ],
            [
                'name' => 'admin_color_scheme_menu_background',
                'label' => __('Menu Background', 'white-label'),
                'type' => 'color',
                'default' => '#23282d',
            ],
            [
                'name' => 'admin_color_scheme_menu_text',
                'label' => __('Menu Text', 'white-label'),
                'type' => 'color',
                'default' => '#ffffff',
            ],
            [
                'name' => 'admin_color_scheme_menu_highlight',
                'label' => __('Menu Highlight', 'white-label'),
                'type' => 'color',
                'default' => '#0073aa',
            ],
            [
                'name' => 'admin_color_scheme_submenu_background',
                'label' => __('Submenu Background', 'white-label'),
                'type' => 'color',
                'default' => '#2c3338',
            ],
            [
                'name' => 'admin_color_scheme_submenu_text',
                'label' => __('Submenu Text', 'white-label'),
                'type' => 'color',
                'default' => '#e5e5e5',
            ],
            [
                'name' => 'admin_color_scheme_submenu_highlight',
                'label' => __('Submenu Highlight', 'white-label'),
                'type' => 'color',
                'default' => '#0073aa',
            ],
            [
                'name' => 'admin_color_scheme_notifications',
                'label' => __('Notifications', 'white-label'),
                'type' => 'color',
                'default' => '#d54e21',
            ],
            [
                'name' => 'admin_color_scheme_links',
                'label' => __('Links', 'white-label'),
                'type' => 'color',
                'default' => '#0073aa',
            ],
            [
                'name' => 'admin_color_scheme_buttons',
                'label' => __('Button Backgrounds', 'white-label'),
                'type' => 'color',
                'default' => '#04a4cc',
            ],
            [
                'name' => 'admin_color_scheme_form_fields',
                'label' => __('Form Fields', 'white-label'),
                'type' => 'color',
                'default' => '#2271b1',
            ],
            [
                'name' => 'footer_section',
                'label' => __('Footer', 'white-label'),
                'desc' => __('Change the information displayed in the WordPress admin footer.', 'white-label'),
                'type' => 'subheading',
                'class' => 'subheading',
            ],
            [
                'name' => 'admin_footer_upgrade',
                'label' => __('Remove WordPress Version', 'white-label'),
                'desc' => __('Remove the WordPress version from the admin footer.', 'white-label'),
                'type' => 'checkbox',
            ],
            [
                'name' => 'admin_footer_credit',
                'label' => __('Footer Text', 'white-label').'<a target="_blank" tabindex="-1" class="white-label-help" href="https://whitewp.com/documentation/article/change-footer-in-wordpress/"><span class="dashicons dashicons-editor-help"></span></a>',
                'type' => 'wysiwyg',
                'size' => '100%',
            ],
        ];


        $fields['white_label_visual_tweaks'] = array_merge($fields['white_label_visual_tweaks'], [
            [
                'name' => 'admin_javascript_section',
                'label' => __('Scripts', 'white-label').'<a target="_blank" tabindex="-1" class="white-label-help" href="https://whitewp.com/documentation/article/add-live-chat-wordpress-admin/"><span class="dashicons dashicons-editor-help"></span></a>',
                'desc' => __('Run Javascript in the admin area. Great for adding a live chat for your clients to contact you.', 'white-label'),
                'type' => 'subheading',
                'class' => 'subheading',
            ],
            [
                'name' => 'admin_javascript',
                'label' => __('Admin Scripts', 'white-label'),
                'desc' => __('Any scripts here will only run on the administrator side of WordPress.', 'white-label'),
                'placeholder' => '<script>...</script>',
                'type' => 'textarea',
                'size' => 'large',
            ]
        ]);

        $desc_remove_widgets_section = __('Remove widgets from the admin dashboard.', 'white-label');
        $desc_remove_widgets_section = __('Remove widgets from the admin dashboard. Remove individual widgets by upgrading to <a href="https://whitewp.com/pro?utm_source=plugin_white_label&utm_content=remove_widgets" target="_blank">White Label Pro</a>.', 'white-label');

        $fields['white_label_dashboard'] = [
            [
                'name' => 'dashboard_section',
                'label' => __('Custom Welcome Panel', 'white-label').'<a target="_blank" tabindex="-1" class="white-label-help" href="https://whitewp.com/documentation/article/custom-wordpress-welcome-panel/"><span class="dashicons dashicons-editor-help"></span></a>',
                'desc' => __('Replace the default Welcome Panel content on the WordPress admin dashboard.', 'white-label'),
                'type' => 'subheading',
                'class' => 'subheading',
            ],
            [
                'name' => 'admin_welcome_panel_content',
                'label' => __('', 'white-label'),
                'desc' => __('', 'white-label'),
                'type' => 'wysiwyg',
                'size' => '100%',
            ],
            [
                'name' => 'remove_widgets_section',
                'label' => __('Remove Dashboard Widgets', 'white-label'),
                'desc' => $desc_remove_widgets_section,
                'type' => 'subheading',
                'class' => 'subheading',
            ],
            [
                'name' => 'admin_remove_dashboard_widgets',
                'label' => __('Remove Dashboard Widgets', 'white-label').'<a target="_blank" tabindex="-1" class="white-label-help" href="https://whitewp.com/documentation/article/remove-wordpress-dashboard-widgets/"><span class="dashicons dashicons-editor-help"></span></a>',
                'type' => 'dashboard_widgets',
                'options' => [
                    'admin_remove_default_widgets' => __('Default Dashboard Widgets', 'white-label'),
                    'admin_remove_third_party_widgets' => __('Third-Party Dashboard Widgets from Plugins and Themes', 'white-label'),
                ],
            ],
            [
                'name' => 'custom_widget_section',
                'label' => __('Custom Widget', 'white-label').'<a target="_blank" tabindex="-1" class="white-label-help" href="https://whitewp.com/documentation/article/add-widget-to-wordpress-dashboard/"><span class="dashicons dashicons-editor-help"></span></a>',
                'desc' => __('Add a custom widget to the admin dashboard. Provide users with quick links or information.', 'white-label'),
                'type' => 'subheading',
                'class' => 'subheading',
            ],
            [
                'name' => 'admin_enable_widget',
                'label' => __('Enable Custom Widget', 'white-label'),
                'desc' => __('The custom widget will show up for all users in the admin dashboard.', 'white-label'),
                'type' => 'checkbox',
            ],
            [
                'name' => 'admin_widget_title',
                'label' => __('Custom Widget Title', 'white-label'),
                'type' => 'text',
                'size' => 'large',
            ],
            [
                'name' => 'admin_widget_content',
                'label' => __('', 'white-label'),
                'desc' => __('', 'white-label'),
                'type' => 'wysiwyg',
                'size' => '100%',
            ],
            [
                'name' => 'custom_dashboard_section',
                'label' => __('Custom Dashboard', 'white-label').'<a target="_blank" tabindex="-1" class="white-label-help" href="https://whitewp.com/documentation/article/replace-wordpress-dashboard/"><span class="dashicons dashicons-editor-help"></span></a>',
                'desc' => __('If you do not wish to have any widgets on the dashboard, then you can replace them with your own dashboard content.', 'white-label'),
                'type' => 'subheading',
                'class' => 'subheading',
            ],
            [
                'name' => 'admin_enable_custom_dashboard',
                'label' => __('Enable Custom Dashboard', 'white-label'),
                'desc' => __('Replace the default dashboard and widgets with a custom dashboard.', 'white-label'),
                'type' => 'checkbox',
            ],
            [
                'name' => 'admin_custom_dashboard_content',
                'label' => __('', 'white-label'),
                'desc' => __('', 'white-label'),
                'type' => 'wysiwyg',
                'size' => '100%',
            ],
        ];

        $desc_sidebar_menus_section = __('Modify sidebar menu items for non-White Label Administrators.', 'white-label');
        $desc_sidebar_menus_section = __('Modify sidebar menu items for non-White Label Administrators. Rename sidebar menus and change icons by upgrading to <a href="https://whitewp.com/pro?utm_source=plugin_white_label&utm_content=sidebar_menus" target="_blank">White Label Pro</a>.', 'white-label');

        $fields['white_label_menus'] = [
            [
                'name' => 'sidebar_menus_section',
                'label' => __('Sidebar Menus', 'white-label'),
                'desc' => $desc_sidebar_menus_section,
                'type' => 'subheading',
                'class' => 'subheading',
            ],
            [
                'name' => 'sidebar_menu_width',
                'label' => __('Sidebar Menu Width', 'white-label').'<a target="_blank" tabindex="-1" class="white-label-help" href="https://whitewp.com/documentation/article/resize-wordpress-admin-menu/"><span class="dashicons dashicons-editor-help"></span></a>',
                'desc' => __('Width of the sidebar menu in pixels. WordPress default is 160.', 'white-label'),
                'type' => 'number',
                'default' => '160',
                'placeholder' => '160',
                'min' => '160',
                'max' => '300',
            ],
            [
                'name' => 'sidebar_menus',
                'label' => '',
                'desc' => '',
                'type' => 'sidebar_menus',
                'options' => white_label_get_sidebar_menus(),
            ],
        ];

        $desc_plugins_section = __('Hide plugins, or change their details, when users who are not White Label Administrators view the Plugins screen.', 'white-label');
        $desc_plugins_section = __('Hide plugins when users who are not White Label Administrators view the Plugins screen. Change plugin details by upgrading to <a href="https://whitewp.com/pro?utm_source=plugin_white_label&utm_content=plugins" target="_blank">White Label Pro</a>.', 'white-label');

        $fields['white_label_plugins'] = [
            [
                'name' => 'plugins_section',
                'label' => __('Plugins', 'white-label'),
                'desc' => $desc_plugins_section,
                'type' => 'subheading',
                'class' => 'subheading',
            ],
            [
                'name' => 'hidden_plugins',
                'label' => __('Hidden Plugins', 'white-label'),
                'desc' => '',
                'type' => 'plugins',
                'options' => white_label_get_plugins(),
            ],
        ];

        $desc_themes_section = __('Hide themes, or change their details, when users who are not White Label Administrators view the Themes screen.', 'white-label');
        $desc_themes_section = __('Hide themes when users who are not White Label Administrators view the Themes screen. Change theme details by upgrading to <a href="https://whitewp.com/pro?utm_source=plugin_white_label&utm_content=themes" target="_blank">White Label Pro</a>.', 'white-label');

        $fields['white_label_themes'] = [
            [
                'name' => 'plugins_section',
                'label' => __('Themes', 'white-label'),
                'desc' => $desc_themes_section,
                'type' => 'subheading',
                'class' => 'subheading',
            ],
            [
                'name' => 'hidden_themes',
                'label' => __('Hidden Themes', 'white-label'),
                'desc' => '',
                'type' => 'themes',
                'options' => white_label_get_themes(),
            ],
        ];

        $fields['white_label_front_end'] = [
            [
                'name' => 'wordpress_version_section',
                'label' => __('WordPress Version Number', 'white-label').'<a target="_blank" tabindex="-1" class="white-label-help" href="https://whitewp.com/documentation/article/hide-wordpress-version-number/"><span class="dashicons dashicons-editor-help"></span></a>',
                'desc' => __('Remove the WordPress version number from front end markup.', 'white-label'),
                'type' => 'subheading',
                'class' => 'subheading',
            ],
            [
                'name' => 'remove_wordpress_version_number_meta_generator',
                'label' => __('Meta Generator', 'white-label'),
                'desc' => __('Remove the <meta> tag with the current version number.', 'white-label'),
                'type' => 'checkbox',
            ],
            [
                'name' => 'remove_wordpress_version_number_rss_feed',
                'label' => __('RSS Feed', 'white-label'),
                'desc' => __('Remove the current WordPress version number from the site\'s RSS feed.', 'white-label'),
                'type' => 'checkbox',
            ],
            [
                'name' => 'remove_wordpress_version_number_stylesheets',
                'label' => __('Stylesheets', 'white-label'),
                'desc' => __('Remove the current WordPress version number from the end of stylesheets. May impact caching.', 'white-label'),
                'type' => 'checkbox',
            ],
            [
                'name' => 'remove_wordpress_version_number_scripts',
                'label' => __('Scripts', 'white-label'),
                'desc' => __('Remove the current WordPress version number from the end of scripts. May impact caching.', 'white-label'),
                'type' => 'checkbox',
            ],
        ];

        if (is_multisite()) {
            $fields['white_label_multisite'][] =
                [
                    'name' => 'multisite_section',
                    'label' => __('Multisite', 'white-label').'<a target="_blank" tabindex="-1" class="white-label-help" href="https://whitewp.com/documentation/article/is-white-label-multisite-compatible/"><span class="dashicons dashicons-editor-help"></span></a>',
                    'desc' => __('Adjust general settings for WordPress Multisite installations. <a target="_blank" tabindex="-1" href="https://whitewp.com/documentation/article/is-white-label-multisite-compatible/?utm_source=plugin_white_label&utm_content=multisite">Learn more about White Label and WordPress Multisite</a>.', 'white-label'),
                    'type' => 'subheading',
                    'class' => 'subheading',
                ];

            if (is_main_site()) {
                $fields['white_label_multisite'][] =
                [
                    'name' => 'enable_global_settings',
                    'label' => __('Global Settings', 'white-label'),
                    'desc' => __('Apply this main site\'s White Label settings to all sites on the network.', 'white-label'),
                    'type' => 'checkbox',
                ];
            } elseif (!is_main_site()) {
                $fields['white_label_multisite'][] =
                [
                    'name' => 'ignore_global_settings',
                    'label' => __('Ignore Global Settings', 'white-label'),
                    'desc' => __('Apply this site\'s White Label settings even if the main site has Global Settings activated.', 'white-label'),
                    'type' => 'checkbox',
                ];
            }
        }

        $settings = [
            'sections' => $sections,
            'fields' => $fields,
        ];

        $settings = apply_filters('white_label_admin_settings', $settings);

        if ($get_part) {
            return $settings[$get_part];
        }

        return $settings;
    }

    /**
     * Set the admin settings page.
     */
    public function register_settings()
    {
        global $pagenow;

        // Make sure we are on a settings page. We can't check for specific at admin_init.
        if ($pagenow === 'options-general.php' || $pagenow === 'options.php') {
            $this->settings_api = new white_label_Settings_Api($this->constants);
            
            $this->settings_api->set_sections($this->settings('sections'));
            $this->settings_api->set_fields($this->settings('fields'));
            
            $this->settings_api->admin_init();
        }
    }

    /**
     * Display the plugin page
     */
    public function plugin_page()
    {
        echo '<div id="white-label-header">';
        echo '<div id="white-label-header-version">';
        echo '<b>White Label</b> &middot; v2.11.0';
        echo '</div>';
    
        echo '<div id="white-label-header-links">';
        echo '<a href="https://whitewp.com/documentation?utm_source=plugin_white_label&utm_content=documentation" target="_blank">'.__('Documentation', 'white-label').'</a> | ';
        echo '<a href="https://whitewp.com/support?utm_source=plugin_white_label&utm_content=support" target="_blank">'.__('Support', 'white-label').'</a> | ';
        echo '<a href="https://whitewp.com/feedback?utm_source=plugin_white_label&utm_content=feedback" target="_blank">'.__('Feedback', 'white-label').'</a>';
        ?>
        <a class="white-label-upgrade" target="_blank" href="https://whitewp.com/pro?utm_source=plugin_white_label&utm_content=upgrade"><?php echo __('Get White Label Pro', 'white-label'); ?></a>
        <?php
        echo '</div>';
        echo '</div>';

        echo '<div class="wrap white-label-admin">';
        $this->settings_api->show_navigation();
        // $this->settings_api->show_sidebar();
        $this->settings_api->show_forms();
        echo '</div>';
    }

    /**
     * Register a custom menu page.
     */
    public function menu()
    {
        if (!white_label_is_wl_admin()) {
            return;
        }

        $parent = 'options-general.php';
        $plugin_name = __('White Label', 'white-label');
        $permissions = 'manage_options';
        $slug = 'white-label';
        $callback = [$this, 'plugin_page'];
        $priority = 100;

        add_submenu_page(
            $parent,
            $plugin_name,
            $plugin_name,
            $permissions,
            $slug,
            $callback,
            $priority
        );
    }

    /**
     * Load scripts.
     */
    public function scripts($hook)
    {
        $wl_panel = white_label_get_option('admin_welcome_panel_content', 'white_label_dashboard', false);

        if (! empty($wl_panel)) {
            wp_enqueue_style('white-label-dashboard', plugins_url('assets/css/white-label-dashboard.css', dirname(__FILE__)), null, '2.11.0');
        }

        if ($hook != 'settings_page_white-label') {
            return;
        }

        wp_enqueue_media();

        // WP Color Picker
        wp_enqueue_style('wp-color-picker');
        wp_enqueue_script('wp-color-picker');

        // White Label
        wp_enqueue_style('white-label', plugins_url('assets/css/white-label.css', dirname(__FILE__)), null, '2.11.0');
        wp_enqueue_script('white-label', plugins_url('assets/js/white-label.min.js', dirname(__FILE__)), ['jquery'], '2.11.0');
    }
}
