<?php namespace Premmerce\Brands;

use Premmerce\Brands\Admin\Admin;
use Premmerce\Brands\Frontend\Frontend;
use Premmerce\Brands\Frontend\Widgets\BrandsWidget;

use Premmerce\SDK\V2\FileManager\FileManager;
use Premmerce\SDK\V2\Notifications\AdminNotifier;
use Premmerce\SDK\V2\Plugin\PluginInterface;

/**
 * Class BrandsPlugin
 * @package Premmerce\Brands
 */
class BrandsPlugin implements PluginInterface
{
    const DOMAIN = 'premmerce-brands';

    /**
     * @var FileManager
     */
    private $fileManager;

    /**
     * @var AdminNotifier
     */
    private $notifier;

    /**
     * PremmerceBrandsPlugin constructor.
     *
     * @param string $mainFile
     *
     */
    public function __construct($mainFile)
    {
        $this->fileManager = new FileManager($mainFile, 'premmerce-woocommerce-brands');
        $this->notifier    = new AdminNotifier();
        add_action('plugins_loaded', array($this, 'loadTextDomain'));
        add_action('admin_init', array($this, 'checkRequirePlugins'));
    }

    /**
     * Register plugin hooks
     */
    private function registerHooks()
    {
        add_action('init', array($this, 'createProductsTaxonomies'));
        add_action('widgets_init', array($this, 'registerWidgets'));
    }

    /**
     * Configurate and register brands taxonomy
     */
    public function createProductsTaxonomies()
    {
        $labels = array(
            'name'              => __('Brands', 'premmerce-brands'),
            'singular_name'     => __('Brand', 'premmerce-brands'),
            'search_items'      => __('Search brands', 'premmerce-brands'),
            'all_items'         => __('All brands', 'premmerce-brands'),
            'parent_item'       => __('Parent brand', 'premmerce-brands'),
            'parent_item_colon' => __('Parent brand:', 'premmerce-brands'),
            'edit_item'         => __('Edit brand', 'premmerce-brands'),
            'update_item'       => __('Update brand', 'premmerce-brands'),
            'add_new_item'      => __('Add new brand', 'premmerce-brands'),
            'new_item_name'     => __('New brand name', 'premmerce-brands'),
            'menu_name'         => __('Brands', 'premmerce-brands'),
        );

        $args = array(
            'hierarchical'       => false,
            'labels'             => $labels,
            'show_ui'            => true,
            'query_var'          => true,
            'show_admin_column'  => true,
            'show_in_quick_edit' => false,
            'meta_box_cb'        => array($this, 'productBrandMetaBox'),
            'no_tagcloud'        => __('No brands found', 'premmerce-brands'),
            'rewrite'            => array(
                'slug'       => get_option('premmerce_brands_base', 'product_brand'),
                'with_front' => true
            )
        );

        register_taxonomy('product_brand', 'product', $args);
        register_taxonomy_for_object_type('product_brand', 'product');
    }

    /**
     * Register Wordpress widgets
     */
    public function registerWidgets()
    {
        $brandsWidget = new BrandsWidget($this->fileManager);

        register_widget($brandsWidget);
    }

    /**
     * Check required plugins and push notifications
     */
    public function checkRequirePlugins()
    {
        $message = __('The %s plugin requires %s plugin to be active!', 'premmerce-brands');

        $plugins = $this->validateRequiredPlugins();

        if (count($plugins)) {
            foreach ($plugins as $plugin) {
                $error = sprintf($message, 'Premmerce Brands for WooCommerce', $plugin);
                $this->notifier->push($error, AdminNotifier::ERROR, false);
            }
        }
    }

    /**
     * Validate required plugins
     *
     * @return array
     */
    private function validateRequiredPlugins()
    {
        $plugins = array();

        /**
         * Check if WooCommerce is active
         **/
        if (! in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {
            $plugins[] = '<a target="_blank" href="https://wordpress.org/plugins/woocommerce/">WooCommerce</a>';
        }

        return $plugins;
    }

    /**
     * Change brands meta box to radio
     *
     * @param \WP_Post $post
     */
    public function productBrandMetaBox(\WP_Post $post)
    {
        $terms = get_terms(array('hide_empty' => false, 'taxonomy' => 'product_brand'));
        $brand = wp_get_object_terms($post->ID, 'product_brand', array('orderby' => 'term_id', 'order' => 'ASC'));
        $name  = '';

        if (! is_wp_error($brand)) {
            if (isset($brand[0]) && isset($brand[0]->name)) {
                $name = $brand[0]->name;
            }
        }

        $this->fileManager->includeTemplate('admin/brands-select.php', array(
            'terms' => $terms,
            'name'  => $name,
        ));
    }

    /**
     * Run plugin part
     */
    public function run()
    {
        $valid = count($this->validateRequiredPlugins()) === 0;

        if ($valid) {
            $this->registerHooks();

            if (is_admin()) {
                new Admin($this->fileManager);
            }

            if (! is_admin() || wp_doing_ajax()) {
                new Frontend($this->fileManager);
            }
        }
    }

    /**
     * Fired when the plugin is activated
     * Check unique
     */
    public function activate()
    {
        if (! get_page_by_title('Brands')) {
            $post_data = array(
                'post_title'   => 'Brands',
                'post_content' => '[brands_page]',
                'post_status'  => 'publish',
                'post_author'  => 1,
                'post_type'    => 'page',
            );

            wp_insert_post($post_data);
        }
    }

    /**
     * Fired when the plugin is deactivated
     */
    public function deactivate()
    {
        if ($page = get_page_by_title('Brands')) {
            wp_delete_post($page->ID, true);
        }
    }

    /**
     * Fired when the plugin is uninstall
     * Remove related data from database
     */
    public static function uninstall()
    {
        global $wpdb;

        $query = '
		    DELETE ' . $wpdb->terms . ', ' . $wpdb->termmeta . ', ' . $wpdb->term_taxonomy . ', ' . $wpdb->term_relationships . ' FROM ' . $wpdb->terms . '
            JOIN ' . $wpdb->term_taxonomy . ' ON ' . $wpdb->terms . '.term_id = ' . $wpdb->term_taxonomy . '.term_id
            JOIN ' . $wpdb->termmeta . ' ON ' . $wpdb->termmeta . '.term_id = ' . $wpdb->term_taxonomy . '.term_id
            LEFT JOIN ' . $wpdb->term_relationships . ' ON ' . $wpdb->term_relationships . '.term_taxonomy_id = ' . $wpdb->term_taxonomy . '.term_id
            WHERE ' . $wpdb->term_taxonomy . '.taxonomy = "product_brand"
		';

        $wpdb->query($query);
    }

    /**
     * Load plugin translations
     */
    public function loadTextDomain()
    {
        $name = $this->fileManager->getPluginName();
        load_plugin_textdomain('premmerce-brands', false, $name . '/languages/');
    }
}
