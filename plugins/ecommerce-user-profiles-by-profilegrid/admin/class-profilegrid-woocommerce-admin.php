<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Profilegrid_Woocommerce
 * @subpackage Profilegrid_Woocommerce/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Profilegrid_Woocommerce
 * @subpackage Profilegrid_Woocommerce/admin
 * @author     Your Name <email@example.com>
 */
class Profilegrid_Woocommerce_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $profilegrid_woocommerce    The ID of this plugin.
	 */
	private $profilegrid_woocommerce;

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
	 * @param      string    $profilegrid_woocommerce       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $profilegrid_woocommerce, $version ) {

		$this->profilegrid_woocommerce = $profilegrid_woocommerce;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Profilegrid_Woocommerce_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Profilegrid_Woocommerce_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
            
                wp_enqueue_style( $this->profilegrid_woocommerce, plugin_dir_url( __FILE__ ) . 'css/profilegrid-woocommerce-admin.css', array(), $this->version, 'all' );
            
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Profilegrid_Woocommerce_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Profilegrid_Woocommerce_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
           
                wp_enqueue_script( $this->profilegrid_woocommerce, plugin_dir_url( __FILE__ ) . 'js/profilegrid-woocommerce-admin.js', array( 'jquery' ), $this->version, false );
                wp_localize_script( $this->profilegrid_woocommerce, 'pm_ajax_object',array( 'ajax_url' => admin_url( 'admin-ajax.php'),'plugin_emoji_url'=>plugin_dir_url( __FILE__ ).'partials/images/img') );
                
            
	}
        
        public function profilegrid_woocommerce_admin_menu()
	{
                add_submenu_page("",__("Woocommerce Settings","profilegrid-woocommerce"),__("Woocommerce Settings","profilegrid-woocommerce"),"manage_options","pm_woocommerce_settings",array( $this, 'pm_woocommerce_settings' ));
        }
	
        public function pm_woocommerce_settings()
        {
             if (!class_exists('Profile_Magic') ) {
                 wp_safe_redirect( 'plugins.php' );
			exit;
             }
            
            include 'partials/profilegrid-woocommerce-admin-display.php';
            update_option('pg_woo_activation_redirect','0');
            update_option( 'pg_redirect_to_group_page', '0' );
        }
        
        public function profilegrid_woocommerce_add_option_setting_page()
        {
            include 'partials/profilegrid-woocommerce-setting-option.php';
        }
        
        public function profile_magic_woocommerce_notice_fun()
        {
            if (!class_exists('Profile_Magic') ) {
                    
                $this->Woocommerce_installation();
                    //wp_die( "ProfileGrid Stripe won't work as unable to locate ProfileGrid plugin files." );
            }
            
            if ( !is_plugin_active( 'woocommerce/woocommerce.php' ) ) 
            {
                $this->Woocommerce_installation2();
            }
        }
        
        public function Woocommerce_installation()
        {
            $plugin_slug= 'profilegrid-user-profiles-groups-and-communities';
            $installUrl = admin_url('update.php?action=install-plugin&plugin=' . $plugin_slug);
            $installUrl = wp_nonce_url($installUrl, 'install-plugin_' . $plugin_slug);
            ?>
            <div class="notice notice-success is-dismissible">
                <p><?php echo sprintf(__( "ProfileGrid WooCommerce Extension works with ProfileGrid Plugin. <a href='%s'>Click here to install it.</a>.", 'profilegrid-woocommerce'),$installUrl ); ?></p>
            </div>
            <?php
            //deactivate_plugins('profilegrid-user-profiles-groups-and-communities-profilegrid-woocommerce/profilegrid-woocommerce.php'); 
        }
        
        public function Woocommerce_installation2()
        {
            $plugin_slug= 'woocommerce';
            $installUrl = admin_url('update.php?action=install-plugin&plugin=' . $plugin_slug);
            $installUrl = wp_nonce_url($installUrl, 'install-plugin_' . $plugin_slug);
            ?>
            <div class="notice notice-success is-dismissible">
                <p><?php _e( "Since you have deactivated WooCommerce, the ProfileGrid WooCommerce Extension has been automatically deactivated. You will have to manually turn it on when you activate WooCommerce.", 'profilegrid-woocommerce' ); ?></p>
            </div>
            <?php
            deactivate_plugins('profilegrid-user-profiles-groups-and-communities-profilegrid-woocommerce/profilegrid-woocommerce.php');
        }
      
        
        public function activate_sitewide_plugins($blog_id)
        {
            // Switch to new website
            $dbhandler = new PM_DBhandler;
            $activator = new Profile_Magic_Activator;
            switch_to_blog( $blog_id );
            // Activate
            foreach( array_keys( get_site_option( 'active_sitewide_plugins' ) ) as $plugin ) {
                do_action( 'activate_'  . $plugin, false );
                do_action( 'activate'   . '_plugin', $plugin, false );
                $activator->activate();
                
            }
            // Restore current website 
            restore_current_blog();
        }
        
        public function profile_magic_woocommerce_group_option($id,$group_options)
        {
            $dbhandler = new PM_DBhandler;
            if($dbhandler->get_global_option_value('pm_enable_woocommerce','1')==1):
             include 'partials/profilegrid-woocommerce-group-option.php';
            endif;
        }
        
        public function pm_woocommerce_tabs_filters($pm_profile_tabs_status)
        {
            $dbhandler = new PM_DBhandler;
            $status = $dbhandler->get_global_option_value('pm_enable_woocommerce','1');
            $check_ids = array();
            foreach($pm_profile_tabs_status as $oldtab)
            {
                $check_ids[] =$oldtab['id'];
            }
            if(!in_array('pg-woocommerce_purchases',$check_ids))
            {
                $pm_profile_tabs_status['pg-woocommerce_purchases'] = array('id'=>'pg-woocommerce_purchases','title'=>__('Purchases','profilegrid-woocommerce'),'status'=>$status,'class'=>'');
            }
            if(!in_array('pg-woocommerce_cart',$check_ids))
            {
                $pm_profile_tabs_status['pg-woocommerce_cart'] = array('id'=>'pg-woocommerce_cart','title'=>__('Cart','profilegrid-woocommerce'),'status'=>$status,'class'=>'');
            }
            
            if(!in_array('pg-woocommerce_reviews',$check_ids))
            {
                $pm_profile_tabs_status['pg-woocommerce_reviews'] = array('id'=>'pg-woocommerce_reviews','title'=>__('Product Reviews','profilegrid-woocommerce'),'status'=>$status,'class'=>'');
            }
           
            
            return $pm_profile_tabs_status;
           
        }
        
       
        
        public function pm_woocommerce_plugin_popup()
        {
            global $pagenow;
            $setting_path = admin_url('admin.php?page=pm_woocommerce_settings');
            $path =  plugin_dir_url(__FILE__)."partials/images/woocommerce.png";
            
            if(class_exists('Profile_Magic') && $pagenow == 'plugins.php' && get_option('pm_show_woocommerce_plugin_popup','0')==0)
            {
            ?>
                <div class="pg-woo-modal-wrap pg-modal-box-main pg-woo-setting-banner">
                    <div class="pg-modal-box-overlay pg-modal-box-overlay-fade-in"></div>
                    <div class="pg-modal-box-wrap ">
                        <div class="pg-modal-box-header">
                            <div class="pm-popup-title pg-woo-install-modal-title"><?php _e('WooCommerce Integration', 'profilegrid-user-profiles-groups-and-communities'); ?>   </div>
                            <span class="pg-modal-box-close">×</span>

                        </div>
                        <div class="pg-extension-modal-img" style="text-align: center;margin: 1px auto 0px auto;">
                            <img src="<?php echo $path;?>" width="100"  />
                        </div>
                        <div class="pg-extension-modal-des">
                            
                           <?php _e('Thanks for installing WooCommerce Integration extension for ProfileGrid. You will find extension specific options in ProfileGrid Global Settings.', 'profilegrid-user-profiles-groups-and-communities'); ?>
                        </div>
                        <div class="pg-modal-box-footer">


                            <button onclick="window.location.href='<?php echo $setting_path;?>'"><?php _e("Go to settings", 'profilegrid-user-profiles-groups-and-communities'); ?></button>
                        </div>


                    </div>
                </div>
            <?php
            update_option('pm_show_woocommerce_plugin_popup','1');
            }
        }
        
        public function pm_woocommerce_check_core_plugin_install_popup()
        {
            global $pagenow;
            $setting_path = admin_url('admin.php?page=pm_woocommerce_settings');
            $path =  plugin_dir_url(__FILE__)."partials/images/ajax-loader.gif";
            $url = add_query_arg(array('page' => 'pm_woocommerce_settings'), admin_url('admin.php'));
           // $dbhandler = new PM_DBhandler;
            
            if(!class_exists('Profile_Magic') && $pagenow == 'plugins.php' && get_option('pm_show_woocommerce_check_core_plugin_popup','0')==1)
            {
                ?>
                <div class="pm-core-plugin-install-plugin pg-modal-box-main">
                    <div class="pg-modal-box-overlay pg-modal-box-overlay-fade-in"></div>
                   <div class="pg-modal-box-wrap ">
        <div class="pg-modal-box-header">
            <div class="pm-popup-title">  </div>
           <span class="pg-modal-box-close">×</span>

        </div>

                       <div class="pg-woo-modal-wrap">
                                       <div class="pg-woo-modal-pitch"><?php _e('ProfileGrid WooCommerce Extension requires ProfileGrid Plugin installed and activated to work.','profilegrid-woocommerce');?> </div> 
                                      
                                   </div>
                                   <div  class="pg-woo-modal-footer">
                                       <div class="pg-woo-modal-action pg-core-installation-btn"><a onclick="pg_install_core_plugin('<?php echo $url; ?>')"> <?php _e('Click here to install it now.', 'profilegrid-woocommerce'); ?></a></div> 
                                       <div id='pg_activation_response'></div>
                                       <div id="pm_woocommerce_activation_loader" style="display:none;">
                                           <div class="pg-woo-active-loader"> <img src="<?php echo esc_url($path);?>" /></div>
                                           <span class="pg-woo-active-loader-notice"><?php echo _e("Setting up ProfileGrid for you! It should only take a few seconds.",'profilegrid-woocommerce'); ?></span>
                                       </div>
                                   </div>
        
        

    </div>
                </div>
            <?php
               update_option('pm_show_woocommerce_check_core_plugin_popup',"0",true);
            }
        }
        
        public function pg_install_profilegrid() {
            // modify these variables with your new/old plugin values
            $plugin_slug = 'profilegrid-user-profiles-groups-and-communities/profile-magic.php';
            $plugin_zip = 'https://downloads.wordpress.org/plugin/profilegrid-user-profiles-groups-and-communities.zip';
            $old_plugin_slug = 'profilegrid-user-profiles-groups-and-communities/profile-magic.php';

            //echo 'If things are not done in a minute <a href="plugins.php">click here to return to Plugins page</a><br><br>';
            //echo 'Starting ...<br><br>';

            //echo 'Check if new plugin is already installed - ';
            if ( $this->is_plugin_installed( $plugin_slug ) ) {
              //echo 'it\'s installed! Making sure it\'s the latest version.';
              $this->upgrade_plugin( $plugin_slug );
              $installed = true;
            } else {
              //echo 'it\'s not installed. Installing.';
              $installed = $this->install_plugin( $plugin_zip );
            }

            if ( !is_wp_error( $installed ) && $installed ) {
              //echo 'Activating new plugin.';
              $activate = activate_plugin( $plugin_slug );
              //print_r($activate);
              if ( is_null($activate) ) {
                //echo '<br>Deactivating old plugin.<br>';
                //deactivate_plugins( array( $old_plugin_slug ) );

               //echo '<br>Done! Everything went smooth.';
              }
            } else {
              //echo 'Could not install the new plugin.';
            }
            
            die;
       }
   
        public function is_plugin_installed( $slug ) {
          if ( ! function_exists( 'get_plugins' ) ) {
            require_once ABSPATH . 'wp-admin/includes/plugin.php';
          }
          $all_plugins = get_plugins();

          if ( !empty( $all_plugins[$slug] ) ) {
            return true;
          } else {
            return false;
          }
        }
 
        public function install_plugin( $plugin_zip ) {
          include_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
          wp_cache_flush();

          $upgrader = new Plugin_Upgrader();
          $installed = $upgrader->install( $plugin_zip );

          return $installed;
        }
 
        public function upgrade_plugin( $plugin_slug ) {
          include_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
          wp_cache_flush();

          $upgrader = new Plugin_Upgrader();
          $upgraded = $upgrader->upgrade( $plugin_slug );

          return $upgraded;
        }
        
        public function pg_woo_activation_redirect()
        {
            if ( class_exists('Profile_Magic') && get_option( 'pg_woo_activation_redirect','0' ) == '1' ) {
                        wp_safe_redirect( 'admin.php?page=pm_woocommerce_settings' );
                    exit;
            }
        }
        

}


