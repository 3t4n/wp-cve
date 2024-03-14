<?php

/**
 * The methods defined here is run during this plugin activation.
 * 
 * @since       1.0.0
 * @package     Aarambha_Demo_Sites
 * @subpackage  Aarambha_Demo_Sites/Inc/Core
 */

if (!defined('WPINC')) {
    exit;    // Exit if accessed directly.
}

/**
 * Class Aarambha_DS
 * 
 * Core file of the plugin.
 */

final class Aarambha_DS
{
    /**
     * The single class instance.
     *
     * @since 1.0.0
     * @access private
     *
     * @var object
     */
    private static $instance = null;

    /**
     * API Url for demo data.
     * 
     * @since 1.0.0
     * @access private
     * 
     * @var string
     */
    private $apiUrl = '';

    /**
     * Admin page arugments.
     * 
     * @since 1.0.0
     * @access private
     * 
     * @var string
     */
    private $adminPageArgs = [];

    /**
     * Should display admin page?
     * 
     * @since 1.0.0
     * @access private
     * 
     * @var boolean
     */
    private $displayPanel = true;

    /**
     * Main Aarambha_DS Instance
     *
     * Ensures only one instance of this class exists in memory at any one time.
     *
     * @see Aarambha_DS()
     * @uses Aarambha_DS::init_globals() Setup class globals.
     * @uses Aarambha_DS::init_includes() Include required files.
     * @uses Aarambha_DS::init_actions() Setup hooks and actions.
     *
     * @since 1.0.0
     * @static
     * @return Aarambha_DS.
     * @codeCoverageIgnore
     */
    public static function getInstance()
    {
        if (null === self::$instance) {
            self::$instance = new self();

            self::$instance->initGlobals();
            self::$instance->includeCoreFiles();
            self::$instance->runActions();
        }

        return self::$instance;
    }

    /**
     * A dummy constructor to prevent this class from being loaded more than once.
     *
     * @see Aarambha_DS::getInstance()
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
        _doing_it_wrong(__FUNCTION__, __('Cheatin&#8217; huh?', 'aarambha-demo-sites'), '1.0.0');
    }

    /**
     * You cannot unserialize instances of this class.
     *
     * @since 1.0.0
     * @codeCoverageIgnore
     */
    public function __wakeup()
    {
        _doing_it_wrong(__FUNCTION__, __('Cheatin&#8217; huh?', 'aarambha-demo-sites'), '1.0.0');
    }

    /**
     * Initialize the plugin globals.
     */
    private function initGlobals()
    {

        $default_url = AARAMBHA_DS_API_URL;

        $url  = apply_filters('aarambha_ds_api_url', $default_url);

        $args = apply_filters('aarambha_ds_admin_page_args', [
            'menu_type' => 'menu',   // menu | submenu,
            'slug'      => 'aarambha-ds',
            'menu_name' => esc_html__('Import Demo', 'aarambha-demo-sites'),
            'title'     => esc_html__('Import Demo', 'aarambha-demo-sites'),
            'icon'      => false,
            'parent'    => false,
            'position'  => 90,
        ]);

        $this->apiUrl = $url;

        $this->adminPageArgs = $args;
    }

    /**
     * Loads our all the core files.
     */
    private function includeCoreFiles()
    {
        /* Include core classes */
        require_once AARAMBHA_DS_CLASSES . 'class-aarambha-ds-api.php';

        require_once AARAMBHA_DS_CLASSES . 'class-aarambha-ds-ajax.php';

        require_once AARAMBHA_DS_CLASSES . 'class-aarambha-ds-plugins.php';
        require_once AARAMBHA_DS_CLASSES . 'class-aarambha-ds-core.php';

        /* Include admin ui */
        require_once AARAMBHA_DS_UI . 'class-aarambha-ds-admin.php';
    }

    /**
     * Fires the actions & filters.
     * 
     * @since 1.0.0
     * @return void
     */
    private function runActions()
    {
        // Load the textdomain.
        add_action('init', [$this, 'loadTextdomain']);
        add_action('plugins_loaded', [$this, 'pluginsLoaded']);

        $this->ajax();



    }

    /**
     * Make plugin available for translation.
     * 
     * @return void
     */
    public function loadTextdomain()
    {
        load_plugin_textdomain('aarambha-demo-sites', false, AARAMBHA_DS_LANGUAGES);
    }

    /**
     * Runs during the plugin load.
     */
    public function pluginsLoaded()
    {
        $activeTheme = aarambha_ds_get_theme();

        $authorThemes = get_site_transient('aarambha_ds_author_themes');

        if (!$authorThemes) {
            $authorThemes = $this->api()->themes();
        }

        if (
            !in_array($activeTheme, $authorThemes) &&
            AARAMBHA_DS_AUTHOR !== aarambha_ds_get_theme_author()
        ) {
            add_action('admin_notices', [$this, 'aarambha_ds_print_admin_notice']);
        } else {
            $this->admin();
        }

        // For testing purpose.
        // $this->admin();
    }

    /**
     * Displays the admin notice.
     */
    public function aarambha_ds_print_admin_notice()
    {
        $class = 'notice notice-error is-dismissible';

        echo sprintf('<div class="%s">', $class);
        echo sprintf(
            __('<p>You need to have one of the themes from <a href="%1$s" target="_blank">%2$s</a> installed, to use <strong>%3$s</strong> plugin</p>', 'aarambha-demo-sites'),
            esc_url(AARAMBHA_DS_AUTHOR_URI),
            ucfirst(AARAMBHA_DS_AUTHOR),
            AARAMBHA_DS_PLUGIN_NAME
        );
        echo '</div>';
    }

    /**
     * Includes the file.
     * 
     * Generally used for view generation along with data.
     *
     * @since 1.0.0
     * @return void
     */
    public function view($view, $data = [])
    {
        try {
            include AARAMBHA_DS_VIEWS . "{$view}.php";
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    /**
     * AarambhaThemes API class.
     * 
     * @since 1.0.0
     * @return object Aarambha_DS_API
     */
    public function api()
    {
        return Aarambha_DS_API::getInstance();
    }

    /**
     * AarambhaThemes Helper Class
     */
    public function plugins()
    {
        return Aarambha_DS_Plugins::getInstance();
    }


    /**
     * AarambhaThemes AJAX class.
     * 
     * @since 1.0.0
     * @return void
     */
    public function ajax()
    {
        Aarambha_DS_Ajax::getInstance();
    }

    /**
     * Generates the Admin pages and UI for the importer
     * 
     * @since 1.0.0
     * 
     * @return void
     */
    public function admin()
    {
        Aarambha_DS_Admin::getInstance();
    }

    /**
     * Get the api URL.
     */
    public function getApiUrl()
    {
        return $this->apiUrl;
    }

    /**
     * Get the admin page url.
     * 
     * @return string
     */
    public function getPageUrl()
    {
        $args = $this->adminPageArgs;

        $menuType = $args['menu_type'];
        $slug     = $args['slug'];
        $parent   = 'admin.php';

        if ('submenu' == $menuType) {
            $parent = sanitize_text_field($args['parent']);
        }

        $url = admin_url($parent);

        return add_query_arg(['page' => sanitize_key($slug)], $url);
    }

    /**
     * Get thee admin menu slug.
     * 
     * @return array
     */
    public function getAdminPageArgs()
    {
        return $this->adminPageArgs;
    }

    /**
     * Get the admin page slug.
     * 
     * @return string
     */
    public function getSlug()
    {
        return ($this->adminPageArgs)['slug'];
    }


    /**
     * Main Content Importer.
     * 
     * @return Aarambha_DS_Core
     */
    public function importer()
    {
        return Aarambha_DS_Core::getInstance();
    }

    /**
     * Get the cached demo from transient.
     * 
     * @return mixed.
     */
    public function demo($slug)
    {
        if (!$slug) {
            return false;
        }

        $theme = aarambha_ds_get_theme();
        $key = "aarambha_ds_{$theme}_demo_{$slug}";

        $transientData = get_site_transient($key);

        if (!$transientData) {
            return false;
        }

        return $transientData['data'];
    }
}
