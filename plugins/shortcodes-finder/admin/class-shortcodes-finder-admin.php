<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @since      1.0.0
 * @package    shortcodes-finder
 * @subpackage shortcodes-finder/admin
 * @author     Scribit <wordpress@scribit.it>
 */
class Shortcodes_Finder_Admin
{

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $plugin_name    The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string    $plugin_name       The name of this plugin.
     * @param      string    $version    The version of this plugin.
     * @access   public
     */
    public function __construct($plugin_name, $version)
    {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }

    /**
     * Register the stylesheets for the admin area.
     *
     * @since    1.0.0
     * @access   public
     */
    public function enqueue_styles()
    {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Shortcodes_Finder_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Shortcodes_Finder_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        if (isset($_GET['page']) && ($_GET['page'] == SHORTCODES_FINDER_PLUGIN_SLUG)) {
            wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/shortcodes-finder-admin.css', array(), $this->version, 'all');
        }
    }

    /**
     * Register the JavaScript for the admin area.
     *
     * @since    1.0.0
     * @access   public
     */
    public function enqueue_scripts()
    {
		if (isset($_GET['page']) && ($_GET['page'] == SHORTCODES_FINDER_PLUGIN_SLUG)) {
			wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/shortcodes-finder-admin.js', array( 'jquery' ), $this->version, false);

			if (isset($_POST['subpage'])) {
                $_POST['subpage'] = esc_attr($_POST['subpage']);
				if (($_POST['subpage'] == 'find_content' || $_POST['subpage'] == 'find_unused') && isset($_POST['search_into_content'])) {
					require_once plugin_dir_path(__FILE__) . '../includes/shortcodes-finder-utils.php';

					$post_type = esc_attr($_POST['search_into_content']);
					$include_not_published = (isset($_POST['include_not_published']) && (esc_attr($_POST['include_not_published']) == 'on'));
                
					$posts = sf_get_posts_ids($post_type, $include_not_published);	// Pass the post type

					wp_localize_script(
						$this->plugin_name,
						'ajax_vars',
						array(
							'ajax_url' => admin_url('admin-ajax.php'),
							'action' => $_POST['subpage'],
							'posts' => $posts
						)
					);
				}
			}
		}
    }

    /**
     * Define menu items for tools menu.
     *
     * @since    1.0.0
     * @access   public
     */
    public function management_page()
    {
        require_once plugin_dir_path(__FILE__) . 'partials/shortcodes-finder-admin-display.php';

        add_management_page(
            __('Shortcodes Finder', 'shortcodes-finder'),
            __('Shortcodes Finder', 'shortcodes-finder'),
            'manage_options',
            SHORTCODES_FINDER_PLUGIN_SLUG,
            'sf_admin_page_handler'
        );
    }

	/**
     * Manage actions on plugin load
     *
     * @since    1.4.3
     * @access   public
     */
	public function load_plugin()
	{
		// Manage redirection after plugin activation
		// See Wordpress tip: https://developer.wordpress.org/reference/functions/register_activation_hook/
		if ( is_admin() && get_option( 'activated_plugin' ) == SHORTCODES_FINDER_PLUGIN_SLUG ) {
			delete_option( 'activated_plugin' );
			exit( wp_redirect( esc_url( admin_url( 'tools.php?page='. SHORTCODES_FINDER_PLUGIN_SLUG ) ) ) );
		}
	}

    /**
     * Manage ajax call for shortcodes search by content
     *
     * @since    1.2.9
     * @access   public
     */
    public function ajax_sf_content_search_process()
    {
        require_once plugin_dir_path(__FILE__) . 'partials/shortcodes-finder-admin-display.php';

        $args = array(
            'posts_per_page' => -1,
            'post_type' => 'any',
            'post_status' => 'any',
            'orderby' => 'date',
            'order' => 'DESC',
            'post__in' => $_POST['posts']
        );
        $posts = get_posts($args);

        sf_print_contents_shortcodes($posts);

        die;
    }

    /**
     * Manage ajax call for unused shortcodes search
     *
     * @since    1.2.9
     * @access   public
     */
    public function ajax_sf_unused_search_process()
    {
        require_once plugin_dir_path(__FILE__) . 'partials/shortcodes-finder-admin-display.php';

        $args = array(
            'posts_per_page' => -1,
            'post_type' => 'any',
            'post_status' => 'any',
            'orderby' => 'date',
            'order' => 'DESC',
            'post__in' => $_POST['posts']
        );
        $posts = get_posts($args);

        sf_get_unused_shortcodes($posts);

        die;
    }

    /**
     * Manage admin notices for admin pages
     *
     * @since    1.3.0
     * @access   public
     */
    public function sf_admin_notices()
    {
        $current_page = get_current_screen()->base;
        if ('tools_page_shortcodes_finder' == $current_page) {

        }
    }
}
