<?php
/**
 * Plugin Name: Quote Master
 * Plugin URI: http://mylocalwebstop.com
 * Description: Use this plugin to add quotes to your website.
 * Author: Frank Corso, Andrew Gunn
 * Author URI: http://mylocalwebstop.com
 * Version: 7.1.1
 * Text Domain: quote-master
 * Domain Path: /languages
 *
 * Disclaimer of Warranties
 * The plugin is provided "as is". My Local Webstop and its suppliers and licensors hereby disclaim all warranties of any kind,
 * express or implied, including, without limitation, the warranties of merchantability, fitness for a particular purpose and non-infringement.
 * Neither My Local Webstop nor its suppliers and licensors, makes any warranty that the plugin will be error free or that access thereto will be continuous or uninterrupted.
 * You understand that you install, operate, and uninstall the plugin at your own discretion and risk.
 *
 * @author Frank Corso
 * @version 7.1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;


/**
  * This class is the main class of the plugin
  *
  * When loaded, it loads the included plugin files and add functions to hooks or filters. The class also handles the admin menu
  *
  * @since 7.0.0
  */
class MLW_Quote_Master
{

    /**
     * QM Version Number
     *
     * @var string
     * @since 7.0.0
     */
    public $version = '7.1.0';

    /**
      * Main Construct Function
      *
      * Call functions within class
      *
      * @since 7.0.0
      * @uses MLW_Quote_Master::load_dependencies() Loads required filed
      * @uses MLW_Quote_Master::add_hooks() Adds actions to hooks and filters
      * @return void
      */
    function __construct()
    {
      $this->load_dependencies();
      $this->add_hooks();
    }

    /**
      * Load File Dependencies
      *
      * @since 7.0.0
      * @return void
      */
    public function load_dependencies()
    {
      include("php/qm-adverts.php");
      include("php/qm-widgets.php");
      include("php/qm-shortcodes.php");
      include("php/qm-update.php");
      include("php/qm-help-page.php");
      include("php/qm-post-meta-boxes.php");
      include("php/qm-about-page.php");
      include("php/qm-settings.php");
    }

    /**
      * Add Hooks
      *
      * Adds functions to relavent hooks and filters
      *
      * @since 7.0.0
      * @return void
      */
    public function add_hooks()
    {
        add_action('admin_init', 'qm_update');
        add_action('widgets_init', create_function('', 'return register_widget("QM_Widget");'));
        add_action('admin_menu', array( $this, 'setup_admin_menu'));
        add_action('admin_head', array( $this, 'admin_head'), 900);
        add_action('init', array( $this, 'register_quote_taxonomy'), 0);
        add_action('init', array( $this, 'register_quote_post_types'), 1);
        add_filter( 'post_row_actions', array($this, 'remove_views'), 10, 1 );
    }

    /**
     * Creates Quote Categories For Custom Post Type
     *
     * @since 7.0.0
     * @return void
     */
    public function register_quote_taxonomy()
    {
      // Add new taxonomy, make it hierarchical (like categories)
  		$labels = array(
  			'name'              => _( 'Quote Categories'),
  			'singular_name'     => _( 'Quote Category'),
  			'search_items'      => __( 'Search Quote Categories' ),
  			'all_items'         => __( 'All Quote Categories' ),
  			'parent_item'       => __( 'Parent Quote Category' ),
  			'parent_item_colon' => __( 'Parent Quote Category:' ),
  			'edit_item'         => __( 'Edit Quote Category' ),
  			'update_item'       => __( 'Update Quote Category' ),
  			'add_new_item'      => __( 'Add New Quote Category' ),
  			'new_item_name'     => __( 'New Quote Category Name' ),
  			'menu_name'         => __( 'Quote Category' ),
  		);
  		$args = array(
  			'public'						=> true,
  			'hierarchical'      => true,
  			'labels'            => $labels,
  			'show_ui'           => true,
  			'query_var' 				=> 'quote_category',
  			'show_admin_column' => true,
  			'rewrite'           => array( 'slug' => "quote/category" ),
  		);
  		register_taxonomy( 'quote_category', array( 'quote' ), $args );
  		register_taxonomy_for_object_type( 'quote_category', 'quote' );
    }

    /**
  	 * Creates Custom Quote Post Type
  	 *
  	 * @since 7.0.0
  	 * @return void
  	 */
  	public function register_quote_post_types()
   	{
  		$labels = array(
  			'name'               => 'Quotes',
  			'singular_name'      => 'Quote',
  			'menu_name'          => 'Quote',
  			'name_admin_bar'     => 'Quote',
  			'add_new'            => 'Add New',
  			'add_new_item'       => 'Add New Quote',
  			'new_item'           => 'New Quote',
  			'edit_item'          => 'Edit Quote',
  			'view_item'          => 'View Quote',
  			'all_items'          => 'All Quotes',
  			'search_items'       => 'Search Quotes',
  			'parent_item_colon'  => 'Parent Quote:',
  			'not_found'          => 'No Quote Found',
  			'not_found_in_trash' => 'No Quote Found In Trash'
  		);
  		$args = array(
  			'public' => true,
  			'show_ui' => true,
  			'show_in_nav_menus' => true,
  			'show_in_menu' => true,
  			'query_var' => true,
  			'labels' => $labels,
  			'publicly_queryable' => true,
  			'exclude_from_search' => true,
  			'label'  => 'Quotes',
  			'menu_icon' => 'dashicons-editor-quote',
  			'rewrite' => array('slug' => 'quote'),
  			'has_archive'        => false,
  			'supports'           => array( 'editor' )
  		);
  		register_post_type( 'quote', $args );
  	}

    /**
     * Removes View Links From Quote Posts
     *
     * @since 7.0.0
     * @return void
     */
    public function remove_views($actions)
    {
      if( get_post_type() === 'quote' )
      {
        unset( $actions['view'] );
      }
      return $actions;
    }

    /**
      * Setup Admin Menu
      *
      * Creates the admin menu and pages for the plugin and attaches functions to them
      *
      * @since 7.0.0
      * @return void
      */
    public function setup_admin_menu()
    {
      if (function_exists('add_submenu_page'))
      {
        add_submenu_page('edit.php?post_type=quote', __('Settings', 'quote-master'), __('Settings', 'quote-master'), 'moderate_comments', 'qm_settings', array('QMGlobalSettingsPage', 'display_page'));
        add_submenu_page('edit.php?post_type=quote', __('Help', 'quote-master'), __('Help', 'quote-master'), 'moderate_comments', 'qm_help', array('QM_Help_Page', 'generate_page'));
      }
      add_dashboard_page(
				__( 'QM About', 'quote-master' ),
				__( 'QM About', 'quote-master' ),
				'moderate_comments',
				'qm_about',
				array('QM_About_Page', 'generate_page')
			);
    }

    /**
  	 * Removes Unnecessary Admin Page
  	 *
  	 * Removes the update, quiz settings, and quiz results pages from the Quiz Menu
  	 *
  	 * @since 7.0.0
  	 * @return void
  	 */
  	public function admin_head()
  	{
  		remove_submenu_page( 'index.php', 'qm_about' );
  	}

    /**
      * Loads the plugin language files
      *
      * @since 7.0.0
      * @return void
      */
    public function setup_translations()
    {
      load_plugin_textdomain( 'quote-master', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
    }
}
$quote_master = new MLW_Quote_Master();

?>
