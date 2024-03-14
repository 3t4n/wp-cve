<?php	
if ( !class_exists( 'TSP_Easy_Dev_Options' ) )
{
	/**
	 * Class to display admin settings in admin area
	 * @package 	TSP_Easy_Dev
	 * @author 		sharrondenice, letaprodoit
	 * @author 		Sharron Denice, Let A Pro Do IT!
	 * @copyright 	2021 Let A Pro Do IT!
	 * @license 	APACHE v2.0 (http://www.apache.org/licenses/LICENSE-2.0)
	 * @version 	1.2.9
	 */
	abstract class TSP_Easy_Dev_Options
	{
		/**
		 * Does the plugin need a parent page?
		 *
		 * @var boolean
		 */
		public $has_parent_page 		= false;
		/**
		 * Does the plugin save settings options?
		 *
		 * @var boolean
		 */
		public $has_options_page 	= false;
		/**
		 * Does the plugin save widget options?
		 *
		 * @var boolean
		 */
		public $has_widget_options 		= false;
		/**
		 * Does the plugin save shortcode options?
		 *
		 * @var boolean
		 */
		public $has_shortcode_options 	= false;
		/**
		 * Does the plugin save post options?
		 *
		 * @since 1.2.9
		 *
		 * @var boolean
		 */
		public $has_post_options = false;
		/**
		 * Does the plugin save term/category options?
		 *
		 * @since 1.2.9
		 *
		 * @var boolean
		 */
		public $has_term_options = false;
		
		/**
		 * A reference to the TSP_Easy_Dev_Posts object
		 *
		 * @since 1.2.9
		 *
		 * @var object
		 */
		private $pro_post 		= null;
		/**
		 * A reference to the TSP_Easy_Dev_Terms object
		 *
		 * @since 1.2.9
		 *
		 * @var object
		 */
		private $pro_term 		= null;
		/**
		 * The URL link to the settings menu icon
		 *
		 * @var string
		 */
		private $menu_icon;
		/**
		 * The array of global values for the plugin
		 *
		 * @var array
		 */
		private $settings 				= array(); // sub-classes can call directly
		/**
		 * A boolean to turn debugging on for this class
		 *
		 * @ignore
		 *
		 * @var boolean
		 */
		private $debugging 				= false;
				
		/**
		 * Constructor
		 *
		 * @ignore
		 *
		 * @since 1.0
		 *
		 * @param array $settings Required the default plugin settings
		 * @param boolean $has_parent_page Optional does the plugin have a parent/company page - default true
		 * @param boolean $has_options_page Optional does the plugin have an options page - default true
		 *
		 * @return void
		 */
		public function __construct( $settings, $has_parent_page = true, $has_options_page = true ) 
		{
			$this->settings				= $settings;
			
			$this->has_options_page 	= $has_options_page;
			$this->has_parent_page 		= $has_parent_page;
		}//end __construct
				
		/**
		 * Intialize the options class
		 *
		 * @since 1.0
		 *
		 * @param void
		 *
		 * @return void
		 */
		public function init ()
		{
			$this->set_menu_icon( $this->get_value('plugin_icon') );
			add_action( 'admin_menu', 			array( $this, 'add_admin_menu' ) );
			
			if ( $this->has_options_page )
			{
				add_filter( 'plugin_action_links', 	array( $this, 'add_settings_link'), 10, 2 );
			}//end if

            if ( array_key_exists('category_fields', $this->settings['plugin_options'] ) && !empty($this->settings['plugin_options']['category_fields']) )
            {
                $this->has_term_options = true;
				$this->pro_term = new TSP_Easy_Dev_Terms( $this );
			}//endif

            if ( array_key_exists('post_fields', $this->settings['plugin_options'] ) && !empty($this->settings['plugin_options']['post_fields']) )
            {
                $this->has_post_options = true;
				$this->pro_post = new TSP_Easy_Dev_Posts( $this );
			}//endif
			
			self::register_options();
		}//end register_options
					
		/**
		 * Create settings entry in database
		 *
		 * @ignore
		 *
		 * @since 1.0
		 *
		 * @param void
		 *
		 * @return void
		 */
		public function register_options ()
		{
			// Remove old plugin settigns
			if( get_option( $this->get_value('option_prefix_old') ) ) 
			{
				delete_option( $this->get_value('option_prefix_old') );
			}//end if

			$prefix = $this->get_value('option_prefix');
			
			$this->set_value('widget-fields-option-name', 	$prefix.'-widget-fields');
			$this->set_value('shortcode-fields-option-name',$prefix.'-shortcode-fields');
			$this->set_value('settings-fields-option-name', $prefix.'-settings-fields');
			
			$this->set_value('term-fields-option-name', 	$prefix.'-term-fields');
			$this->set_value('term-data-option-name', 	$prefix.'-term-data');
			
			$this->set_value('post-fields-option-name', 	$prefix.'-post-fields');

			$database_widget_fields 	= get_option( $this->get_value('widget-fields-option-name') );
			$database_shortcode_fields 	= get_option( $this->get_value('shortcode-fields-option-name') );
			$database_settings_fields 	= get_option( $this->get_value('settings-fields-option-name') );
			
			$database_post_fields 	= get_option( $this->get_value('post-fields-option-name') );
			$database_term_fields 	= get_option( $this->get_value('term-fields-option-name') );

			$default_widget_fields 		= $this->get_value('widget_fields');
			$default_shortcode_fields 	= $this->get_value('shortcode_fields');
			$default_settings_fields 	= $this->get_value('settings_fields');
			
			$default_post_fields 	= $this->get_value('post_fields');
			$default_term_fields 	= $this->get_value('category_fields');

			// if has options and the database options != the current options
			// then if database options are not empty copy them to the default fields and update
			// if the database option does not exist add default
			if( $this->has_widget_options &&  $database_widget_fields != $default_widget_fields ) 
			{
				if (!empty ( $database_widget_fields ) )
				{
					$default_widget_fields = array_merge( $default_widget_fields, $database_widget_fields);
					update_option( $this->get_value('widget-fields-option-name'), $default_widget_fields );
				}//end if
				else
				{
					add_option( $this->get_value('widget-fields-option-name'), $default_widget_fields );
				}//end else
			}//end if

			// if has options and the database options != the current options
			// then if database options are not empty copy them to the default fields and update
			// if the database option does not exist add default
			if( $this->has_shortcode_options &&  $database_shortcode_fields != $default_shortcode_fields  ) 
			{
				if (!empty ( $database_shortcode_fields ) )
				{
					$default_shortcode_fields = array_merge( $default_shortcode_fields, $database_shortcode_fields);
					update_option( $this->get_value('shortcode-fields-option-name'), $default_shortcode_fields );
				}//end if
				else
				{
					add_option( $this->get_value('shortcode-fields-option-name'), $default_shortcode_fields );
				}//end else
			}//end if

			// if has options and the database options != the current options
			// then if database options are not empty copy them to the default fields and update
			// if the database option does not exist add default
			if( $this->has_options_page &&  $database_settings_fields != $default_settings_fields ) 
			{
				if (!empty ( $database_settings_fields ) )
				{
					$default_settings_fields = array_merge( $default_settings_fields, $database_settings_fields);
					update_option( $this->get_value('settings-fields-option-name'), $default_settings_fields );
				}//end if
				else
				{
					add_option( $this->get_value('settings-fields-option-name'), $default_settings_fields );
				}//end else
			}//end if
			
			// if has options and the database options != the current options
			// then if database options are not empty copy them to the default fields and update
			// if the database option does not exist add default
			if( $this->has_post_options &&  $database_post_fields != $default_post_fields ) 
			{
				if (!empty ( $database_post_fields ) )
				{
					$default_post_fields = array_merge( $default_post_fields, $database_post_fields);
					update_option( $this->get_value('post-fields-option-name'), $default_post_fields, 'yes' );
				}//end if
				else
				{
					add_option( $this->get_value('post-fields-option-name'), $default_post_fields, '', 'yes' );
				}//end else
			}//end if

			// if has options and the database options != the current options
			// then if database options are not empty copy them to the default fields and update
			// if the database option does not exist add default
			if( $this->has_term_options &&  $database_term_fields != $default_term_fields ) 
			{
				if (!empty ( $database_term_fields ) )
				{
					$default_term_fields = array_merge( $default_term_fields, $database_term_fields);
					update_option( $this->get_value('term-fields-option-name'), $default_term_fields, 'yes' );
				}//end if
				else
				{
					add_option( $this->get_value('term-fields-option-name'), $default_term_fields, '', 'yes' );
				}//end else
			}//end if

			// if option was not found this means the plugin is being installed
			// ONLY overwrite the user data if none is stored
			if( $this->has_term_options && !get_option( $this->get_value('term-data-option-name') ) ) 
			{
				add_option( $this->get_value('term-data-option-name'), null, '', 'yes' );
			}//end if
		}//end register_options

					
		/**
		 * Remove settings entry in database
		 *
		 * @ignore
		 *
		 * @since 1.0
		 *
		 * @param void
		 *
		 * @return void
		 */
		public function deregister_options ()
		{
            global $wpdb;

            $wpdb->query(
                    $wpdb->prepare(
                            "DELETE FROM $wpdb->options WHERE option_name LIKE %s",
                            '%' . $wpdb->esc_like( TSP_EASY_DEV_NAME ) . '%'
                    )
            ); // No cache OK. DB call ok.
		}//end deregister_options
		
		
		/**
		 * Get reference to pro post options object
		 *
		 * @api
		 *
		 * @since 1.2.9
		 *
		 * @param void
		 *
		 * @return TSP_Easy_Dev_Posts reference to TSP_Easy_Dev_Posts object
		 */
		public function get_pro_post()
		{			
			if (empty($this->pro_post))
				$this->pro_post = new TSP_Easy_Dev_Posts( $this );

			return $this->pro_post;
		}//end get_pro_post
		

		/**
		 * Get reference to pro terms options object
		 *
		 * @api
		 *
		 * @since 1.2.9
		 *
		 * @param void
		 *
		 * @return TSP_Easy_Dev_Terms reference to TSP_Easy_Dev_Terms object
		 */
		public function get_pro_term()
		{
			if (empty($this->pro_term))
				$this->pro_term = new TSP_Easy_Dev_Terms( $this );
				
			return $this->pro_term;
		}//end get_term_post

		/**
		 * Add settings links to the plugin option links (on plugins page)
		 *
		 * @ignore - Must be public because used in WordPress hooks
		 *
		 * @since 1.0
		 *
		 * @param void
		 *
		 * @return void (can be overriden to remove settings links if they are not required)
		 */
		public function add_settings_link( $links, $file ) 
		{
			//Static so we don't call plugin_basename on every plugin row.
			static $this_plugin;
			if ( ! $this_plugin ) $this_plugin = $this->get_value('base_name');
		
			if ( $file == $this_plugin ){
					 $config_link = '<a href="admin.php?page=' . $this->get_value('name') . '.php">' . __( 'Settings', $this->get_value('name') ) . '</a>';
					 array_unshift( $links, $config_link );
			}
			
			return $links;
		} // end function plugin_action_links

		/**
		 * Add the default setting tab to the side menu to display TSP plugins
		 *
		 * @ignore - Must be public because used in WordPress hooks
		 *
		 * @since 1.0
		 *
		 * @param void
		 *
		 * @return void (extend to add submenus to the parent menu)
		 */
		public function add_admin_menu()
		{
			$parent_slug = $this->get_value('parent_name');
			$menu_slug = $this->get_value('name').'.php';

            if ( !menu_page_url( $parent_slug, false ) && $this->has_parent_page )
            {
                // Make sure that each setting is nested into a company
                // menu area
                add_menu_page( $this->get_value('parent_title'),
                    $this->get_value('parent_title'),
                    'manage_options',
                    $parent_slug,
                    array( $this, 'display_parent_page' ),
                    $this->menu_icon,
                    $this->get_value('menu_pos'));
            }//endif

            if ( !menu_page_url( $menu_slug, false ) && $this->has_options_page )
            {
                // If there is to be no parent menu then add the settings page as the main page
                if ( empty ( $parent_slug ) )
                {
                    // Add menu as a stand-alone
                    add_menu_page( __( $this->get_value('title_short'), $this->get_value('name') ),
                        __( $this->get_value('title_short'), $this->get_value('name') ),
                        'manage_options',
                        $menu_slug,
                        array( $this, 'display_plugin_options_page' ),
                        $this->menu_icon,
                        $this->get_value('menu_pos'));
                }//end if
                else
                {
                    // Add child menu
                    add_submenu_page($this->get_value('parent_name'),
                        __( $this->get_value('title_short'), $this->get_value('name') ),
                        __( $this->get_value('title_short'), $this->get_value('name') ),
                        'manage_options',
                        $menu_slug,
                        array( $this, 'display_plugin_options_page' ));
                }//end else
            }//endif

		}//end add_admin_menu
		
		/**
		 * Get a value from the settings array, recursively
		 *
		 * @since 1.0
		 *
		 * @param string $key Required get the key to return from settings
		 * @param array $arr Optional array to search recursively
		 * @param int $loop_count Optional for testing purposes only
		 *
		 * @return string the setting key value
		 */
		public function get_value ( $find_key, $arr = array(), $loop_count = 0 ) 
		{
			$return_value = null;
			
			// if the loop is just starting and there is no array value set
			// then we are being told to loop through our settings
			if ($loop_count == 0 && empty ( $arr ))
			{
				$arr = $this->settings;
			}//end if
			
			// if $arr is currently set to the find_key then return the array
			if ( array_key_exists( $find_key, $arr ) )
			{
				if ( $this->debugging )
				{
					d( "1. It took $loop_count recursive calls to find $find_key with [" . serialize( $arr[$find_key] ) . "]" );
				}//end if
				
				$return_value = $arr[$find_key];
			}//end elseif
			else
			{
				foreach( $arr as $key => $value) 
				{ 
					// in the previous condition statements we checked the first level of the array for the key
					// since it was not found we only want to look at the values that are arrays now
					if ( is_array( $value ) && !empty ( $value ))
					{
						// If the find_key was found in the second level then return it else
						// we need to recurse the  array
						if ( array_key_exists( $find_key, $value ) )
						{
							if ( $this->debugging )
							{
								d( "2. It took $loop_count recursive calls to find $find_key with [" . serialize( $value[$find_key] ) . "]" );
							}//end if
							
							$return_value = $value[$find_key];
							break; // stop looping
						}//end if
						else
						{
							if ( $this->debugging )
							{
								d( "Looking for $find_key in the $key array..." );
							}//end if
							$return_value = $this->get_value( $find_key, $value, $loop_count++ );
						}//end else
					}//end if
				}//end foreach
			}//end else
			
			return $return_value;
   		} // end function get_value

		
		/**
		 * Set a value given the settings key
		 *
		 * @since 1.0
		 *
		 * @param string $key Required the key to set
		 * @param string value Required the value to set the key to
		 *
		 * @return void
		 */
		public function set_value ( $key, $value ) 
		{
			$this->settings[$key] = $value;
		} // end function set_value

		
		/**
		 * Append a value to the settings array
		 *
		 * @since 1.0
		 *
		 * @param string $key Required the key to set
		 * @param string value Required the value to set the key to
		 *
		 * @return void
		 */
		public function add_value ( $key, $value ) 
		{
			$this->settings[$key][] = $value;
		} // end function add_value

		/**
		 * Add the menu icon to the settings menu
		 *
		 * @since 1.0
		 *
		 * @param void
		 *
		 * @return void (extend to add submenus to the parent menu)
		 */
		private function set_menu_icon( $icon )
		{
			$this->menu_icon = $icon;
		}
		

        /**
         * Get required plugins
         *
         * @since 1.0
         *
         * @param void
         *
         * @return array of required plugins
         */
		public function get_required_plugins()
        {
            return $this->get_value('required_plugins');
        }


        /**
         * Get incompatible plugins
         *
         * @since 1.0
         *
         * @param void
         *
         * @return array of incompatible plugins
         */
        public function get_incompatible_plugins()
        {
            return $this->get_value('incompatible_plugins');
        }

        /**
         * Get settings from database
         *
         * @since 1.0
         *
         * @param $key - string
         *
         * @return array of settings
         */
        public function get_dbase_settings($key)
        {
            $message = "";

            $fields = get_option($this->get_value("{$key}-fields-option-name"));

            $defaults = new TSP_Easy_Dev_Data ($fields, $key);

            $form = null;
            if (array_key_exists($this->get_value('name') . '_form_submit', $_REQUEST)) {
                $form = $_REQUEST[$this->get_value('name') . '_form_submit'];
            }//endif

            // Save data for page
            if (isset($form) && check_admin_referer($this->get_value('name'), $this->get_value('name') . '_nonce_name')) {
                $defaults->set_values($_POST);
                $fields = $defaults->get();

                update_option($this->get_value("{$key}-fields-option-name"), $fields);

                $message = __(ucfirst($key) . " saved.", $this->get_value('name'));
            }

            $fields = $defaults->get_values(true);

            return array($form, $message, $fields);
        }//end settings_page

		/**
		 * Must be implemented by the plugin to include a options page for the plugin, if not required implement empty. Best used for displaying informational data to user (ie Listing company information)
		 *
		 * @api
		 *
		 * @since 1.0
		 *
		 * @param void
		 *
		 * @return void
		 */
		public function display_parent_page()
        {
            $active_plugins			= get_option('active_plugins');
            $all_plugins 			= get_plugins();

            $free_active_plugins 	= array();
            $free_installed_plugins = array();
            $free_recommend_plugins = array();

            $pro_active_plugins 	= array();
            $pro_installed_plugins 	= array();
            $pro_recommend_plugins 	= array();

            $json 					= @file_get_contents( TSP_PLUGINS_URL );
            $tsp_plugins 			= @json_decode($json);

            if (!empty($tsp_plugins))
            {
                foreach ( $tsp_plugins->{'plugins'} as $plugin_data )
                {
                    if ( $plugin_data->{'type'} == 'FREE' )
                    {
                        if ( in_array($plugin_data->{'name'}, $active_plugins ) )
                        {
                            $free_active_plugins[] = (array)$plugin_data;
                        }//endif
                        elseif ( array_key_exists($plugin_data->{'name'}, $all_plugins ) )
                        {
                            $free_installed_plugins[] = (array)$plugin_data;
                        }//end elseif
                        else
                        {
                            $free_recommend_plugins[] = (array)$plugin_data;
                        }//endelse
                    }//endif
                    elseif ( $plugin_data->{'type'} == 'PRO' )
                    {
                        if ( in_array($plugin_data->{'name'}, $active_plugins ) )
                        {
                            $pro_active_plugins[] = (array)$plugin_data;
                        }//endif
                        elseif ( array_key_exists($plugin_data->{'name'}, $all_plugins ) )
                        {
                            $pro_installed_plugins[] = (array)$plugin_data;
                        }//endelseif
                        else
                        {
                            $pro_recommend_plugins[] = (array)$plugin_data;
                        }//endelse
                    }//endelseif
                }//endforeach
            }

            $free_active_count									= count($free_active_plugins);
            $free_installed_count 								= count($free_installed_plugins);
            $free_recommend_count 								= count($free_recommend_plugins);

            $free_total											= $free_active_count + $free_installed_count + $free_recommend_count;

            $pro_active_count									= count($pro_active_plugins);
            $pro_installed_count 								= count($pro_installed_plugins);
            $pro_recommend_count 								= count($pro_recommend_plugins);

            $pro_total											= $pro_active_count + $pro_installed_count + $pro_recommend_count;

            // Display settings to screen
            $smarty = new TSP_Easy_Dev_Smarty( $this->get_value('smarty_template_dirs'),
                $this->get_value('smarty_cache_dir'),
                $this->get_value('smarty_compiled_dir'), true );

            $smarty->assign( 'free_active_count',		$free_active_count);
            $smarty->assign( 'free_installed_count',	$free_installed_count);
            $smarty->assign( 'free_recommend_count',	$free_recommend_count);

            $smarty->assign( 'pro_active_count',		$pro_active_count);
            $smarty->assign( 'pro_installed_count',		$pro_installed_count);
            $smarty->assign( 'pro_recommend_count',		$pro_recommend_count);

            $smarty->assign( 'free_active_plugins',		$free_active_plugins);
            $smarty->assign( 'free_installed_plugins',	$free_installed_plugins);
            $smarty->assign( 'free_recommend_plugins',	$free_recommend_plugins);

            $smarty->assign( 'pro_active_plugins',		$pro_active_plugins);
            $smarty->assign( 'pro_installed_plugins',	$pro_installed_plugins);
            $smarty->assign( 'pro_recommend_plugins',	$pro_recommend_plugins);

            $smarty->assign( 'free_total',				$free_total);
            $smarty->assign( 'pro_total',				$pro_total);

            $smarty->assign( 'title',					"WordPress Plugins by " . TSP_COMPANY_NAME);
            $smarty->assign( 'contact_url',				TSP_SUPPORT_URL);

            $smarty->display( 'easy-dev-parent-page.tpl' );
        }

		/**
		 * Must be implemented by the plugin to include a options page for the plugin, if not required implement empty
		 *
		 * @api
		 *
		 * @since 1.0
		 *
		 * @param void
		 *
		 * @return void
		 */
		abstract public function display_plugin_options_page();
		
	}//end TSP_Easy_Dev_Options
}//endif