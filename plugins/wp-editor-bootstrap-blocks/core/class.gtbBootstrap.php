<?php
/**
 * Bootstrap Blocks for WP Editor class.
 *
 * @version 1.0.1
 *
 * @package Bootstrap Blocks for WP Editor
 * @author  Virgial Berveling
 * @updated 2023-04-18
 * 
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}


if ( ! class_exists( 'GutenbergBootstrap', false ) ) {

    class GutenbergBootstrap
    {
        private static $modules = array();
        private static $notices = array();
		private static $network_managed = false;
        var $menu_slug = GUTENBERGBOOTSTRAP_SLUG;

        
        public function __construct($modules=false)
        {
            if (!$this->check_gutenberg() ) return;

            add_action('init',array($this,'do_modules'));
            add_filter( 'block_categories_all',array($this,'add_category_bootstrap'), 10, 2 );
            add_action( 'admin_footer', array($this,'admin_notices') );
            self::$network_managed = self::get_option( 'design-package_fa_license_network' ) ? true : false;

        }
        
        static function AddModule($name,$args)
        {
            self::$modules[$name] = (object) array(
                'name' => isset($args['name'])?$args['name']:$name,
                'version' => isset($args['version'])?$args['version']:GUTENBERGBOOTSTRAP_VERSION,
                'licensed' => isset($args['licensed'])?true:false,
                'slug' => isset($args['slug'])?$args['slug']:$name
            );
        }

        static function getModules()
        {
            return self::$modules;
        }


        static function AddNotice($message='missing notification message', $priority='error',$dismissible=false)
        {
            self::$notices[] = array('message'=>$message, 'priority'=>$priority, 'dismissible'=>$dismissible );
        }
    
        public function admin_notices()
        {
            while (is_array(self::$notices) && count(self::$notices)>0):
                $notice = array_pop(self::$notices);
                $dismissible = $notice['dismissible']?' is-dismissible':'';
                printf( '<div class="%1$s"><p>%2$s</p></div>', 'notice notice-'.$notice['priority'].$dismissible, $notice['message'] ); 
            endwhile;
        }
    


		/**
		 * Get_option
		 *
		 * @param  mixed $name
		 * @return mixed $option
		 */
		public static function get_option( $name ) {
			if ( 'gtbbootstrap_version' === $name ) {
				return get_site_option( $name );
			}
			if ( 'design-package_fa_license' === $name ) {
				return get_site_option( $name );
			}
			if ( 'design-package_fa_license_network' === $name ) {
				return get_site_option( $name );
			}

			return get_option( $name );
		}

		/**
		 * Update_option
		 *
		 * @param  string $name
		 * @param  mixed  $options
		 * @return boolean
		 */
		public static function update_option( $name, $options ) {


            if (self::$network_managed):
                if ( 'gtbbootstrap_version' === $name ) {
                    return update_site_option( $name, $options );
                }
                if ( 'design-package_fa_license' === $name ) {
                    return update_site_option( $name, $options );
                }

                if ( 'design-package_fa_license_network' === $name ) {
                    return update_site_option( $name, $options );
                }
            endif;
            // only local site for other options
			return update_option( $name, $options );
		}

		/**
		 * Default_options
		 *
		 * @return array
		 */
		public static function default_options() {
			return array(
				'wp_new_user_notification_to_user'    => '1',
				'wp_new_user_notification_to_admin'   => '1',
				'wp_notify_postauthor'                => '1',
				'wp_notify_moderator'                 => '1',
				'wp_password_change_notification'     => '1',
				'send_password_change_email'          => '1',
				'send_email_change_email'             => '1',
				'send_password_forgotten_email'       => '1',
				'send_password_admin_forgotten_email' => '1',
				'auto_core_update_send_email'         => '1',
				'auto_plugin_update_send_email'       => '1',
				'auto_theme_update_send_email'        => '1',
			);
		}

        /**
         * @since 2.0.1 
         * added folder namespacing
         */
        
        public function do_modules()
        {
            do_action('gtb_bootstrap_modules');
            do_action('gtb_init');
        }

        static function get_licensed_modules()
        {
            $m = array();
            foreach(GutenbergBootstrap::$modules as $key=>$module):
                if ($module->licensed) $m[$key] = $module;
            endforeach;
            return count($m)>0?$m:false;
        }




        function add_category_bootstrap( $categories, $post )
        {

            
            return array_merge(
                $categories,
                array(
                    array(
                        'slug'  => 'bootstrap',
                        'title' => 'Bootstrap Blocks',
                        'icon' => plugins_url( '/modules/settings-page/assets/logo-wp-editor-bootstrap-blocks-blue.svg', GUTENBERGBOOTSTRAP_PLUGIN_BASENAME)
                    ),
                )
            );
        }

    

        private function check_gutenberg()
        {
            if (!defined('GUTENBERG_VERSION') && version_compare(get_bloginfo('version'),'5.0') < 0):

                fa_add_notice( __('Gutenberg is required for plugin Gutenberg Bootstrap to work properly.', GUTENBERGBOOTSTRAP_SLUG ),'error' ); 
                return false;
            endif;
            return true;
        }


        public static function update_check($version)
        {
            if (self::get_option( 'gtbbootstrap_version' ) != $version) {
        
                
                $options = self::get_option( 'gtbbootstrap_options' );
                
                
                /* Is this the first install, then set all defaults to active */
                if ($options === false)
                {
                    $options = self::$default_options;
                    
                    self::update_option('gtbbootstrap_options',$options);
                }


                /* UPDATE DONE! */
                self::update_option('gtbbootstrap_version', $version);
            }
        }

        public static $default_options = array(
            'gridsize'                  => 12,
            'bootstrap_included'        => 'N', 
            'bootstrap_on_template'     => '1', 
            'bootstrap_colors_included' => '1',
            'bootstrap_version'         => '5.3',
    
            'bootstrap_color1'    => '#007bff',
            'bootstrap_color2'    => '#6c757d',
            'bootstrap_color3'    => '#28a745',
            'bootstrap_color4'    => '#dc3545',
            'bootstrap_color5'    => '#ffc107',
            'bootstrap_color6'    => '#17a2b8',
            'bootstrap_color7'    => '#ffffff',
            'bootstrap_color8'    => '#000000',
        );
    


        static function uninstall()
        {
            delete_option('gtbbootstrap_version');
            delete_option('design-package_fa_license');
            if (self::$network_managed):
                delete_site_option('design-package_fa_license');
                delete_site_option('gtbbootstrap_version');
                delete_site_option('design-package_fa_license_network');
            endif;
        }
    }



    if (!function_exists('fa_add_notice')):
     function fa_add_notice($message='', $priority='error',$dismissible=false)
     {
        GutenbergBootstrap::AddNotice($message, $priority,$dismissible);
     }
     endif;
    
}