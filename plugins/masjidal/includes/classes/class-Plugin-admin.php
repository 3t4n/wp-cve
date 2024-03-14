<?php
/** @wordpress-plugin
 * Author:            Masjidal 
 * Author URI:        http://www.masjidal.com/
 */
/**
 * The dashboard-specific functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Plugin_Name
 * @subpackage Plugin_Name/admin
 */

/**
 * The dashboard-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the dashboard-specific stylesheet and JavaScript.
 *
 * @package    Plugin_Name
 * @subpackage Plugin_Name/admin
 * @author     Your Name <email@example.com>
 */
namespace masjidal_namespace;
class MPSTI_cWeb_Admin {

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
	 * @var      string    $plugin_name       The name of this plugin.
	 * @var      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		add_action('admin_menu', array(&$this, 'register_my_custom_menu_page'));
                add_action('admin_menu',array(&$this, 'remove_menus') );
	}

	/**
	 * Register the stylesheets for the Dashboard.
	 *
	 * @since    1.0.0
	 */
		public function enqueue_styles() {
			/**
			 * This function is provided for demonstration purposes only.
			 *
			 * An instance of this class should be passed to the run() function
			 * defined in Plugin_Name_Loader as all of the hooks are defined
			 * in that particular class.
			 *
			 * The Plugin_Name_Loader will then create the relationship
			 * between the defined hooks and the functions defined in this
			 * class.
			 */

       wp_enqueue_style('customcss', plugin_dir_url(dirname(dirname(__FILE__))) . 'admin/css/admin.css', array(), $this->version, 'all' );
		
		}

		/**
		* Register the JavaScript for the dashboard.
		*
		* @since    1.0.0
		*/
		
	   public function enqueue_scripts() {
		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Plugin_Name_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Plugin_Name_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
         
           
            }

		/** Menu Function **/
        function register_my_custom_menu_page() {
            global $submenu,$user_ID;
            global $PluginTextDomain;
            global $cwebPluginName;
            
            $roles = $user_info = array();
            $user_info = get_userdata( $user_ID );
            $roles = $user_info->roles;
            
          add_menu_page(__($cwebPluginName,$PluginTextDomain),__('Masjidal',$PluginTextDomain), 'read', 'masjidal_settings', array(&$this, 'my_custom_masjidal'));
         // add_submenu_page('settings', __('Add Form',$PluginTextDomain), __('Settings',$PluginTextDomain), 'read', 'settings', array(&$this, 'cwebco_ado_settings'));

         // add_submenu_page('settings', __('Add Form',$PluginTextDomain), __('Logs',$PluginTextDomain), 'read', 'cwebco_ado_logs', array(&$this, 'cwebco_ado_logs'));
		 
         // add_submenu_page($cwebPluginName, __('masjidal',$PluginTextDomain), __('masjidal',$PluginTextDomain), 'read', 'masjidal', array(&$this, 'my_custom_masjidal'));
		 

        }
	
     
  

function my_custom_masjidal() {
	
            global $PluginTextDomain;
            if (!current_user_can('read')) {
                wp_die(__('You do not have sufficient permissions to access this page.',$PluginTextDomain));
            } else {
                include(CWEB_MASJIDAL_PATH . 'admin/admin_page/masjidal_details.php');
            }
        }
	


    /*code to Hide Admin Bar Menus for Business user */
    function remove_menus () {
        
        global $user_ID;
            
        $roles = $user_info = array();
        $user_info = get_userdata( $user_ID );
        $roles = $user_info->roles;

        if(in_array('employee_user', $roles))
        {
            global $menu;

            $restricted = array( __('Posts'),__('Media'),__('Users'), __('Dashboard'), __('Contact'), __('Links'), __('Pages'), __('Appearance'), __('Tools'), __('Settings'), __('Comments'), __('Plugins'));
            end ($menu);
            while (prev($menu)){
                $value = explode(' ',$menu[key($menu)][0]);
                if(in_array($value[0] != NULL?$value[0]:"" , $restricted)){unset($menu[key($menu)]);}
            }
        }
    }
    
    /*code to Hide Admin Bar Menus for Business user -- END -- */
//    
        function add_theme_caps() {
            // gets the author role
            global $wp_roles;
            
            $wp_roles->add_cap( 'catalog_manager','manage_categories' );
            
            $wp_roles->add_cap( 'catalog_manager','read_company' );
            $wp_roles->add_cap( 'catalog_manager','edit_company' );
            $wp_roles->add_cap( 'catalog_manager','publish_company' );
            $wp_roles->add_cap( 'catalog_manager','delete_company' );
        }

    
}
