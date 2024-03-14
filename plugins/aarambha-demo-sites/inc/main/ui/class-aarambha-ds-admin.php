<?php

/**
 * Handles the UI generation part
 * 
 * @since       1.0.0
 * @package     Aarambha_Demo_Sites
 * @subpackage  Aarambha_Demo_Sites/Inc/Core/UI
 */

if (!defined('WPINC')) {
    exit;    // Exit if accessed directly.
}

/**
 * Class Aarambha_DS_Admin
 * 
 * Handles the creation of admin page and its user interfaces.
 */
class Aarambha_DS_Admin
{
    /**
     * Single class instance.
     * 
     * @since 1.0.0
     * @access private
     * 
     * @var object
     */
    private static $instance = null;

    /**
     * Page slug.
     * 
     * @since 1.0.0
     * @access protected
     * 
     * @var string
     */
    protected $page = '';

    /**
     * Creates the Admin page and handles importer UI stuffs.
     *
     * @class Aarambha_DS_Admin
     * 
     * @version 1.0.0
     * @since 1.0.0
     * 
     * @return object Aarambha_DS_Admin
     */
    public static function getInstance()
    {
        if (null === self::$instance) {
            self::$instance = new self();
            self::$instance->init();
        }

        return self::$instance;
    }

    /**
     * A dummy constructor to prevent this class from being loaded more than once.
     *
     * @see Aarambha_DS_Admin::getInstance()
     *
     * @since 1.0.0
     * @access private
     * @codeCoverageIgnore
     */
    private function __construct()
    {
        /* We do nothing here! */
    }

    /**
     * You cannot clone this class.
     *
     * @since 1.0.0
     * @codeCoverageIgnore
     */
    public function __clone()
    {
        _doing_it_wrong(
            __FUNCTION__,
            esc_html__('Cheatin&#8217; huh?', 'aarambha-demo-sites'),
            '1.0.0'
        );
    }

    /**
     * You cannot unserialize instances of this class.
     *
     * @since 1.0.0
     * @codeCoverageIgnore
     */
    public function __wakeup()
    {
        _doing_it_wrong(
            __FUNCTION__,
            esc_html__('Cheatin&#8217; huh?', 'aarambha-demo-sites'),
            '1.0.0'
        );
    }

    /**
     * Initialize the Aarambha_DS_Admin
     */
    private function init()
    {
        add_action('admin_menu', [$this, 'createAdminMenu']);
        add_action('admin_footer', [$this, 'renderTemplates']);

        add_action('admin_init', [$this, 'onAdminInit']);
    }

    /**
     * When admin init runs.
     */
    public function onAdminInit()
    {
        if (!current_user_can('manage_options')) {
            return;
        }

        if (isset($_GET['_clear']) && 'cache' === sanitize_text_field($_GET['_clear'])) {
            $this->deleteCache();
        }
    }

    /**
     * Delete the cache.
     */
    private function deleteCache()
    {
        global $wpdb;
        $table = $wpdb->options;
        $query = "SELECT * FROM {$table} WHERE `option_name` LIKE '%_aarambha_ds_%'";
        $results = $wpdb->get_results($query);

        if (!$results) {
            return;
        }

        if (is_array($results) && count($results) > 0) {
            foreach ($results as $result) {
                $wpdb->delete($table, ['option_id' => $result->option_id]);
            }
        }
    }

    /**
     * Hooked into 'admin_menu' to register the main page.
     */
    public function createAdminMenu()
    {
        $args = Aarambha_DS()->getAdminPageArgs();

        if ('submenu' === $args['menu_type']) {

            $page = add_submenu_page(
                $args['parent'],
                $args['title'],
                $args['menu_name'],
                'manage_options',
                $args['slug'],
                [$this, 'renderAdminPage'],
                $args['position']
            );
        } else if ('menu' === $args['menu_type']) {

            $page = add_menu_page(
                $args['title'],
                $args['menu_name'],
                'manage_options',
                $args['slug'],
                [$this, 'renderAdminPage'],
                $args['icon'],
                $args['position']
            );
        }

        $this->page = $page;

        add_action('admin_enqueue_scripts', [$this, 'enqueueScriptsStyles']);
    }


    /**
     * Render the admin page.
     * 
     * @since 1.0.0
     * @return void.
     */
    public function renderAdminPage()
    {
        $data = [
            'categories' => Aarambha_DS()->api()->categories(),
            'demos' => Aarambha_DS()->api()->demos()
        ];

        Aarambha_DS()->view('body', $data);
    }

    /**
     * Enqueue styles and scripts.
     * 
     * @since 1.0.0
     * @return void
     */
    public function enqueueScriptsStyles($hook)
    {
        $slug = Aarambha_DS()->getSlug();

        if ($this->page === $hook) {

            // Enqueue styles.
            wp_enqueue_style(
                'sweetalert2',
                AARAMBHA_DS_CSS . 'sweetalert2.css',
                [],
                '11.10.1',
                'all'
            );

            wp_enqueue_style(
                $slug,
                AARAMBHA_DS_CSS . 'admin.css',
                [],
                AARAMBHA_DS_VERSION,
                'all'
            );

            // Enqueue scripts.
            wp_enqueue_script(
                "sweetalert2",
                AARAMBHA_DS_JS . 'sweetalert2.js',
                [],
                '11.10.1',
                true
            );

            wp_register_script(
                $slug,
                AARAMBHA_DS_JS . 'admin-ui.js',
                ['jquery', 'wp-util', 'updates'],
                AARAMBHA_DS_VERSION,
                true
            );

            $theme       =  get_stylesheet();
            $licenseSlug = "{$theme}-license";
            $licenseUrl  = admin_url("themes.php?page={$licenseSlug}");

            // Localize strings.
            $default = [
                'nonce'          => wp_create_nonce(),
                'themeName'      => aarambha_ds_get_theme_name(),
                'offlineTitle'   => esc_html__('You\'re Offline!', 'aarambha-demo-sites'),
                'purchaseLabel'  => esc_html__('Purchase Now', 'aarambha-demo-sites'),
                'previewLabel'   => esc_html__('Preview', 'aarambha-demo-sites'),
                'loadingText'    => esc_html__('Please Wait!', 'aarambha-demo-sites'),
                'installPlugins' => esc_html__('Install Plugins', 'aarambha-demo-sites'),
                'importContent'  => esc_html__('Import Content', 'aarambha-demo-sites'),
                'installing'     => esc_html__('Installing &#8230;', 'aarambha-demo-sites'),
                'activating'     => esc_html__('Activating &#8230;', 'aarambha-demo-sites'),
                'active'         => esc_html__('Active', 'aarambha-demo-sites'),
                'failedTitle'    => esc_html__('Sorry!', 'aarambha-demo-sites'),
                'activateLink'   => esc_url($licenseUrl),
                'offlineMsg'     => esc_html__(
                    'We cannot import now. Please try again later!',
                    'aarambha-demo-sites'
                ),
                'tryAgain'       => esc_html__(
                    'Refresh the page, and try again!',
                    'aarambha-demo-sites'
                ),
                'content'        => esc_html__(
                    'Importing Content&#8230;',
                    'aarambha-demo-sites'
                ),
                'customizer'     => esc_html__(
                    'Importing Customize Information &#8230;',
                    'aarambha-demo-sites'
                ),
                'widgets'        => esc_html__(
                    'Importing Widgets &#8230;',
                    'aarambha-demo-sites'
                ),
                'slider'         => esc_html__(
                    'Importing Slider &#8230;',
                    'aarambha-demo-sites'
                ),
                'failed'         => esc_html__(
                    'Something Went Wrong!',
                    'aarambha-demo-sites'
                ),
                'prepare'        => esc_html__(
                    'Preparing to import &#8230;',
                    'aarambha-demo-sites'
                ),
                'menu'           => esc_html__(
                    'Setting Menus &#8230;',
                    'aarambha-demo-sites'
                ),
                'pages'          => esc_html__(
                    'Setting Pages &#8230;',
                    'aarambha-demo-sites'
                ),
                'finalize'       => esc_html__(
                    'Finalizing the Import &#8230;',
                    'aarambha-demo-sites'
                ),
            ];

            $user_args = apply_filters('aarambha_ds_localize_data', []);
            $args      = wp_parse_args($user_args, $default);

            wp_localize_script($slug, 'aarambhaDSData', $args);
            wp_enqueue_script($slug);
        }
    }

    /**
     * Render popup templates.
     * 
     * @since 1.0.0
     * @return void
     */
    public function renderTemplates()
    {
        $currentScreen = get_current_screen();

        if ($this->page === $currentScreen->id) {
            Aarambha_DS()->view('popups/activate-theme');
            Aarambha_DS()->view('popups/failed');
            Aarambha_DS()->view('popups/purchase-theme');
            Aarambha_DS()->view('popups/information');
            Aarambha_DS()->view('import');
            Aarambha_DS()->view('importing');
            Aarambha_DS()->view('complete');
        }
    }

}
