<?php
if ( !class_exists( 'TSP_Easy_Dev' ) )
{
	/**
	 * API implementations for LAPDI Easy Dev Pro, Use LAPDI Easy Dev package to easily create, manage and display wordpress plugins
	 * @package 	TSP_Easy_Dev
	 * @author 		sharrondenice, letaprodoit
	 * @author 		Sharron Denice, Let A Pro Do IT!
	 * @copyright 	2021 Let A Pro Do IT!
	 * @license 	APACHE v2.0 (http://www.apache.org/licenses/LICENSE-2.0)
	 * @version 	1.3.3
	 */
	class TSP_Easy_Dev
    {
        /**
         * The version of WordPress that this plugin requires
         *
         * @api
         *
         * @var string
         */
        public $required_wordpress_version;
        /**
         * Does the plugin use shortcodes?
         *
         * @api
         *
         * @var boolean
         */
        public $uses_shortcodes 			= false;
        /**
         * A string that contains the absolute path and file name of the plugin
         *
         * @var string
         */
        public $plugin_file 				= null;
        /**
         * A string that contains the base name (file) of the plugin
         *
         * @var string
         */
        public $plugin_base_name 			= null;
        /**
         * A string that contains the title of the plugin
         *
         * @var string
         */
        public $plugin_title 				= null;
        /**
         * A string that contains the name of the plugin
         *
         * @var string
         */
        public $plugin_name 				= null;
        /**
         * Does the plugin require Smarty?
         *
         * @api
         *
         * @var boolean
         */
        public $uses_smarty 				= true; // Always Set to TRUE
        /**
		 * The extended TSP_Easy_Dev_Options class, must be instantiated (ie $my_plugin->set_options_handler ( new TSP_Easy_Dev_Options_MY_PLUGIN() ))
         *
         * @api
         *
		 * @var TSP_Easy_Dev_Options
         */
        public $options;
        /**
         * The name of the widget class created by the user, a placeholder because logic can not be handled
         * by this class, the widget class has to be static and and called statically by WordPress
         *
         * @api
         *
         * @var string
         */
        public $widget; //TODO: There was no way to aggregate a class for widget it has to be handled by WordPress via a hook, look into this with newer versions of WordPress
        /**
         * The current message this plugin needs to display
         *
         * @ignore
         *
         * @var boolean
         */
        protected $message 					= null;
        /**
         * An array of links that will be displayed in the description
         *
         * @var array
         */
        private $meta_links 				= array();
        /**
         * An array of CSS URLs to include in the admin area
         *
         * @var array
         */
        private $admin_css_files			= array();
        /**
         * An array of JS URLs to include in the admin area
         *
         * @var array
         */
        private $admin_js_files				= array();
        /**
         * An array of CSS URLs to include in the user front-end
         *
         * @var array
         */
        private $user_css_files				= array();
        /**
         * An array of JS URLs to include in the user front-end
         *
         * @var array
         */
        private $user_js_files				= array();
        /**
         * An array of script tags to deregister
         *
         * @var array
         */
        private $script_tags_to_deregister	= array();
        /**
         * An array of short codes that this plugin will process
         *
         * @var array
         */
        private $shortcodes					= array();
        /**
         * An array of listeners that this plugin will process
         *
         * @var array
         */
        private $listeners					= array();
        /**
         * An array of listeners that this plugin will remove
         *
         * @var array
         */
        private $remove_listeners			= array();
        /**
         * This plugin's icon URL
         *
         * @var string
         */
        private $plugin_icon				= null;
        /**
         * A boolean to turn debugging on for this class
         *
         * @ignore
         *
         * @var boolean
         */
        private $debugging 					= false;
        /**
         * Array of required plugins
         *
         * @since 1.2.9
         *
         * @ignore
         *
         * @var array
         */
        private $required_plugins 		= false;
        /**
         * Array of incompatible plugins
         *
         * @since 1.2.9
         *
         * @ignore
         *
         * @var array
         */
        private $incompatiable_plugins 	= false;

        /**
         * Constructor
         *
         * @since 1.0
         *
         * @param array $globals Required - Sets the global settings for the plugin
         *
		 * @return void
         */
        public function __construct( $plugin, $required_wordpress_version )
        {
            $this->required_wordpress_version = $required_wordpress_version;

            // register install/uninstall hooks
            register_activation_hook( $plugin, 	array( $this, 'activate') );
            register_deactivation_hook( $plugin, 	array( $this, 'deactivate') );
        }//end __construct


        /**
         * After all the settings are initialized, run the plugin
         *
         * @api
         *
         * @since 1.0
         *
         * @param string $plugin Required - The file name of the plugin, __FILE__
         *
		 * @return void
         */
        public function run( $plugin )
        {
            // If the user has added links add them to the plugin meta data
            if ( !empty( $this->meta_links ))
            {
                add_filter( 'plugin_row_meta', 			array( $this, 'add_plugin_meta_links'), 10, 2);
            }//endif

            add_action('init', 						array( $this, 'deregister_scripts' ));
            add_action('admin_enqueue_scripts', 	array( $this, 'enqueue_admin_scripts' ));
            add_action('wp_enqueue_scripts', 		array( $this, 'enqueue_user_scripts' ));

            // If the user added listeners add them
            if (!empty($this->listeners))
            {
                foreach ($this->listeners as $index => $data)
                {
                    if ($data['type'] == 'action')
                    {
                        add_action ($data['tag'], $data['func'], $data['priority'], $data['arg_count']);
                    }//end if
                    else if ($data['type'] == 'filter')
                    {
                        add_filter ($data['tag'], $data['func'], $data['priority'], $data['arg_count']);
                    }//end elseif
                }//end foreach
            }//end if

            // If the user removed listeners process them
            if (!empty($this->remove_listeners))
            {
                foreach ($this->remove_listeners as $index => $data)
                {
                    if ($data['type'] == 'action')
                    {
                        remove_action ($data['tag'], $data['func'], $data['priority']);
                    }//end if
                    else if ($data['type'] == 'filter')
                    {
                        remove_filter ($data['tag'], $data['func'], $data['priority']);
                    }//end elseif
                }//end foreach
            }//end if

            // If the plugin uses settings add them
            if ( $this->options )
            {
                if ( $this->widget )
                {
                    $this->options->has_widget_options = true;
                }//end if

                if ( $this->uses_shortcodes )
                {
                    $this->options->has_shortcode_options = true;
                    $this->options->set_value( 'shortcodes', $this->shortcodes );
                }//end if

                if ( !empty( $this->plugin_icon ))
                {
                    $this->options->set_value( 'plugin_icon', $this->plugin_icon );
                }//end if

                $this->options->init();

                if ( !isset ($this->plugin_title) )
                {
                    $this->plugin_title = $this->options->get_value( 'title' );
                }//end if

                if ( !isset ($this->plugin_name) )
                {
                    $this->plugin_name = $this->options->get_value( 'name' );
                }//end if

                if ( !isset ($this->plugin_file) )
                {
                    $this->plugin_file = $this->options->get_value( 'file' );
                }//end if

                if ( !isset ($this->plugin_base_name) )
                {
                    $this->plugin_base_name = $this->options->get_value( 'base_name' );
                }//end if

            }//end if
            else
            {
                $message = "";

                //Check to make sure that the required variables are set
                if ( !isset ($this->plugin_title) )
                {
					$message .= "Since you are not extending the `TSP_Easy_Dev_Options` class, you must set <strong>plugin_title</strong> in your plugins file (Example: \$my_plugin->plugin_title = 'My Plugin').";
                }//end if

                if ( !isset ($this->plugin_name) )
                {
					$message .= "Since you are not extending the `TSP_Easy_Dev_Options` class, you must set <strong>plugin_name</strong> in your plugins file (Example: \$my_plugin->plugin_name = 'my-plugin').";
                }//end if

                if ( !isset ($this->plugin_file) )
                {
					$message .= "Since you are not extending the `TSP_Easy_Dev_Options` class, you must set <strong>plugin_file</strong> in your plugins file (Example: \$my_plugin->plugin_name = __FILE__ ).";
                }//end if

                if ( !isset ($this->plugin_base_name) )
                {
					$message .= "Since you are not extending the `TSP_Easy_Dev_Options` class, you must set <strong>plugin_base_name</strong> in your plugins file (Example: \$my_plugin->plugin_base_name = plugin_basename( __FILE__ ) ).";
                }//end if

                if (!empty( $message ))
                {
                    $message .= "<br>See <a href='http://lab.thesoftwarepeople.com/tracker/wiki/wordpress-ed:MainPage' target='_blank'>docs</a> for more details.";

                    $this->message = $message;

                    add_action( 'admin_notices', array( $this, 'display_error' ));

                    return;
                }//end

            }//end else

            add_action('admin_init', 	array( $this, 'init' ) );
            add_action('admin_init', 	array( $this, 'process_incompatible_plugins' ));
            add_action('admin_init', 	array( $this, 'process_required_plugins' ));
        }//end setup


        /**
         * Function to initialize the plugin on install or activation
         *
         * @ignore - Must be public because used in WordPress hooks
         *
         * @since 1.0
         *
         * @param void
         *
         * @return void (extend to add additional checks)
         */
        public function init()
        {
            if ( $this->required_wordpress_version )
            {
                global $wp_version;

                if (version_compare($wp_version, $this->required_wordpress_version, "<"))
                {

                    $this->message =  $this->plugin_title . " requires WordPress version <strong>{$this->required_wordpress_version} or higher</strong>.<br>You have version <strong>$wp_version</strong> installed.";

                    add_action( 'admin_notices', array( $this, 'display_error' ));

                    deactivate_plugins( $this->plugin_base_name );

                    return;
                }//endif
            }//endif
        }//end init

        /**
         * Method to intialize the options class for this plugin
         *
         * @since 1.0
         *
         * @param TSP_Easy_Dev_Options $options Required The settings handler class for this plugin
         * @param boolean $has_post_options Optional Does the plugin save post options?
         * @param boolean $has_term_options Optional Does the plugin save term/category options?
         *
         * @return void
         */
        public function set_options_handler( $options, $has_post_options = false, $has_term_options = false )
        {
            if ( is_subclass_of( $options, 'TSP_Easy_Dev_Options' ) )
            {
                $this->options 						= $options;
                $this->options->has_post_options 	= $has_post_options;
                $this->options->has_term_options 	= $has_term_options;

                $this->required_plugins($this->options->get_required_plugins());
                $this->incompatible_plugins($this->options->get_incompatible_plugins());
            }//end if
            else
            {
                wp_die ( "The settings handler must be a subclass of TSP_Easy_Dev_Options." );
            }//end else
        }//end set_options_handler


        /**
         * Method to return the name of the widget class handler
         *
         * @since 1.0
         *
         * @param void
         *
         * @return TSP_Easy_Dev_Options - object reference
         */
        public function get_options_handler()
        {
            return $this->options;
        }//end get_settings_handler

        /**
         * Method to intialize the options class for this plugin
         *
         * @since 1.0
         *
         * @param string $widget Required The NAME of the widget handler class for this plugin
         *
         * @return void
         */
        public function set_widget_handler( $widget )
        {
            $this->widget = $widget;
        }//end set_widget_handler

        /**
         * Method to return the name of the widget class handler
         *
         * @since 1.0
         *
         * @param void
         *
         * @return string Widget class name
         */
        public function get_widget_handler()
        {
            return $this->widget;
        }//end get_widget_handler

        /**
         * Method to get the meta links
         *
         * @since 1.3.0
         *
         * @param void
         *
         * @return array meta_links
         */
        public function get_meta_links()
        {
            return $this->meta_links;
        }//end get_widget_handler


        /**
         * Add URL links to the plugin description on the plugin list page
         *
         * @api
         *
         * @since 1.0
         *
         * @param string $title Required title for the a tag
         * @param string $url Required url of the a tag
         *
         * @return void
         */
        public function add_link( $title, $url )
        {
            $this->meta_links[] = "<a target='_blank' href='$url'>$title</a>";
        }//end add_link

        /**
         * Add listener
         *
         * @api
         *
         * @since 1.1
         *
         * @param string $tag Required - The tag
         * @param array $func Required - The parent class (first arg) and the function (second arg)
         * @param string $type Required - The type of listener, action or filter
         * @param int $priority optional - Used to specify the order in which the functions
         * 	associated with a particular action are executed. Lower numbers correspond
         * 	with earlier execution, and functions with the same priority are executed in
         * 	the order in which they were added to the filter.
         * @param int $arg_count optional - The number of arguments the function(s) accept(s)
         *
         * @return void
         */
        public function add_listener( $tag, $type, $func, $priority = 10, $arg_count = 1 )
        {
            $this->listeners[] = array(
                'tag' => $tag,
                'type' => $type,
                'func' => $func,
                'priority' => $priority,
                'arg_count' => $arg_count,
            );
        }//end add_listener

        /**
         * Remove listener
         *
         * @api
         *
         * @since 1.3.3
         *
         * @param string $tag Required - The tag
         * @param array $func Required - The parent class (first arg) and the function (second arg)
         * @param string $type Required - The type of listener, action or filter
         * @param int $priority optional - Used to specify the order in which the functions
         * 	associated with a particular action are executed. Lower numbers correspond
         * 	with earlier execution, and functions with the same priority are executed in
         * 	the order in which they were added to the filter.
         *
         * @return void
         */
        public function remove_listener( $tag, $type, $func, $priority = 10 )
        {
            $this->remove_listeners[] = array(
                'tag' => $tag,
                'type' => $type,
                'func' => $func,
                'priority' => $priority,
            );
        }//end remove_listener

        /**
         * Add additional links to the plugin description section (on plugins page)
         *
         * @ignore - Must be public, used by WordPress hooks
         *
         * @since 1.0
         *
         * @param array $links Required list of a tag links to display
         * @param string $file Required the name of the plugin
         *
         * @return void
         */
        public function add_plugin_meta_links( $links, $file )
        {
            if ( $file == $this->plugin_base_name )
            {
                $links = array_merge( $links, $this->meta_links );
            }//endif

            return $links;
        } // end function register_plugin_links

        /**
         * Add styles to the queue
         *
         * @api
         *
         * @since 1.0
         *
         * @param string $css Required - The full URL of the css file
         * @param boolean $admin Optional - Is the style for the admin or user interface
         *
         * @return void
         */
        public function add_css( $css, $admin = false )
        {
            if ( $admin )
            {
                $this->admin_css_files[]  	= $css;
            }//endif
            else
            {
                $this->user_css_files[] 	= $css;
            }//end else
        }//end add_css


        /**
         * Add user scripts to the queue
         *
         * @api
         *
         * @since 1.0
         *
         * @param string $script Required - The full URL of the script file
         * @param array $required_scripts Optional - Array of required script tags (ie 'jquery','jquery-ui-widget')
         * @param boolean $admin Optional - Is the script for the admin or user interface
         * @param boolean $footer Optional - Will the script be loaded in the footer
         * @param int $version Optional - Script version number or false if none
         *
         * @return void
         */
        public function add_script( $script, $required_scripts = array(), $admin = false, $footer = false, $version = 0 )
        {
            if ( $admin )
            {
                $this->admin_js_files[$script] 	= array(
                    'required' => $required_scripts,
                    'footer' => $footer,
                    'version' => $version,
                );
            }//endif
            else
            {
                $this->user_js_files[$script] 	= array(
                    'required' => $required_scripts,
                    'footer' => $footer,
                    'version' => $version,
                );
            }//end else
        }//end add_css

        /**
         * Remove registered scripts
         *
         * @ignore
         *
         * @since 1.2.4
         *
         * @param void
         *
         * @return void
         */
        public function deregister_scripts()
        {
            if (!empty( $this->script_tags_to_deregister ))
            {
                foreach ( $this->script_tags_to_deregister as $tag )
                {
                    wp_deregister_script( $tag );
                }//end foreach
            }//end if
        }//end deregister_scripts


        /**
         * Store registered scripts
         *
         * @api
         *
         * @since 1.0
         *
         * @param array $tags Optional - Array of registered script tags to store (ie 'autosave')
         *
         * @return void
         */
        public function remove_registered_scripts( $tags )
        {
            if (!empty( $tags ))
            {
                $this->script_tags_to_deregister = $tags;
            }//end if
        }//end remove_registered_scripts

        /**
         * Add short codes for processing
         *
         * @api
         *
         * @since 1.0
         *
         * @param string $tag Required - the tag name of the shortcode
         *
         * @return void
         */
        public function add_shortcode( $tag )
        {
            if ( $this->uses_shortcodes )
            {
                $this->shortcodes[] = $tag;
            }//endif
        }//end add_shortcode

        /**
         * Set the plugin icon (used by options class on run)
         *
         * @api
         *
         * @since 1.0
         *
         * @param string $icon Required - The full URL of the icon file
         *
         * @return void
         */
        public function set_plugin_icon( $icon )
        {
            $this->plugin_icon = $icon;
        }//end set_plugin_icon

        /**
         *  Implementation to queue user scripts and stylesheets
         *
         * @ignore - Must be public because used in WordPress hooks
         *
         * @since 1.0
         *
         * @param void
         *
         * @return void
         */
        public function enqueue_user_scripts()
        {
            foreach ($this->user_css_files as $style)
            {
                $tag = basename($style);
                $tag = preg_replace( "/\.css/", "", $tag);
                $tag = preg_replace( "/-|\./", "_", $tag);
                $tag = TSP_EASY_DEV_NAME . "_css_" . $tag;

                wp_register_style( $tag, $style );
                wp_enqueue_style( $tag );
            }//endforeach

            foreach ($this->user_js_files as $script => $data)
            {
                $tag = basename($script);
                $tag = preg_replace( "/\.js/", "", $tag);
                $tag = preg_replace( "/-|\./", "_", $tag);
                $tag = TSP_EASY_DEV_NAME . "_js_" . $tag;

                wp_register_script( $tag, $script, $data['required'], $data['version'], $data['footer'] );
                wp_enqueue_script( $tag );
            }//endforeach

        }//end  enqueue_styles

        /**
         *  Implementation to dequeue user scripts and stylesheets
         *
         * @ignore - Must be public because used in WordPress hooks
         *
         * @since 1.3.2
         *
         * @param void
         *
         * @return void
         */
        public function dequeue_user_scripts()
        {
            foreach ($this->user_css_files as $style)
            {
                $tag = basename($style);
                $tag = preg_replace( "/\.css/", "", $tag);
                $tag = preg_replace( "/-|\./", "_", $tag);
                $tag = TSP_EASY_DEV_NAME . "_css_" . $tag;

                wp_deregister_style( $tag, $style );
                wp_dequeue_style( $tag );
            }//endforeach

            foreach ($this->user_js_files as $script => $data)
            {
                $tag = basename($script);
                $tag = preg_replace( "/\.js/", "", $tag);
                $tag = preg_replace( "/-|\./", "_", $tag);
                $tag = TSP_EASY_DEV_NAME . "_js_" . $tag;

                wp_deregister_script( $tag, $script, $data['required'] );
                wp_dequeue_script( $tag );
            }//endforeach

        }//end  enqueue_styles

        /**
         *  Implementation to queue admin scripts and stylesheets
         *
         * @ignore - Must be public because used in WordPress hooks
         *
         * @since 1.0
         *
         * @param void
         *
         * @return void
         */
        public function enqueue_admin_scripts()
        {
            $screen = get_current_screen();

            if (!preg_match("/". TSP_ACRONYM . "|" . LAPDI_ACRONYM . "|edit-category/", $screen->id))
                return;

            foreach ($this->admin_css_files as $style)
            {
                $tag = basename($style);
                $tag = preg_replace( "/\.css/", "", $tag);
                $tag = preg_replace( "/-|\./", "_", $tag);
                $tag = TSP_EASY_DEV_NAME . "_css_" . $tag;
				
                wp_register_style( $tag, $style );
                wp_enqueue_style( $tag );
            }//endforeach

            foreach ($this->admin_js_files as $script => $data)
            {
                $tag = basename($script);
                $tag = preg_replace( "/\.js/", "", $tag);
                $tag = preg_replace( "/-|\./", "_", $tag);
                $tag = TSP_EASY_DEV_NAME . "_js_" . $tag;
                
                wp_register_script($tag, $script, $data['required'], $data['version'], $data['footer']);
                wp_enqueue_script($tag);
            }//endforeach

            if (preg_match("/edit-category/", $screen->id))
            {
                wp_enqueue_script('media-upload');
                wp_enqueue_script('thickbox');
                wp_enqueue_style('thickbox');
            }
        }//end enqueue_scripts


        /**
         *  Implementation to dequeue admin scripts and stylesheets
         *
         * @ignore - Must be public because used in WordPress hooks
         *
         * @since 1.3.2
         *
         * @param void
         *
         * @return void
         */
        public function dequeue_admin_scripts()
        {
            foreach ($this->admin_css_files as $style)
            {
                $tag = basename($style);
                $tag = preg_replace( "/\.css/", "", $tag);
                $tag = preg_replace( "/-|\./", "_", $tag);
                $tag = TSP_EASY_DEV_NAME . "_css_" . $tag;
				
                wp_deregister_style( $tag, $style );
                wp_dequeue_style( $tag );
            }//endforeach

            foreach ($this->admin_js_files as $script => $data)
            {
                $tag = basename($script);
                $tag = preg_replace( "/\.js/", "", $tag);
                $tag = preg_replace( "/-|\./", "_", $tag);
                $tag = TSP_EASY_DEV_NAME . "_js_" . $tag;

                wp_deregister_script( $tag, $script, $data['required'] );
                wp_dequeue_script( $tag );
            }//endforeach
        }//end enqueue_scripts

        /**
         * Method to display a notice
         *
         * @api
         *
         * @since 1.2.1
         *
         * @param void
         *
         * @return void
         */
        public function display_notice()
        {
            if ( $this->message )
            {
                ?><div class="updated">
                <p><?php _e( $this->message, $this->plugin_name  ); ?></p>
                </div>
                <?php
            }//end if

            $this->message = null;
        }//end display_notice

        /**
         * Method to display an error
         *
         * @api
         *
         * @since 1.2.1
         *
         * @param void
         *
         * @return void
         */
        public function display_error()
        {
            if ( $this->message )
            {
                ?><div class="error">
                <p><?php _e( $this->message, $this->plugin_name  ); ?></p>
                </div>
                <?php
            }//end if

            $this->message = null;
        }//end display_notice

        /**
         * Optional implementation to activate plugin - can be extended by subclasses, not to be called directly but extended by subclasses
         *
         * @api
         *
         * @since 1.0
         *
         * @param void
         *
         * @return string $message Optional any messages generated by deactivation
         */
        public function activate()
        {
            $message = "";

            register_uninstall_hook( TSP_EASY_DEV_FILE, 	$this->uninstall() );

            // If the plugin requires smarty create cache and compiled directories
            if ( $this->uses_smarty )
            {
                $smarty_cache_dir = $this->options->get_value('smarty_cache_dir');
                $smarty_compiled_dir = $this->options->get_value('smarty_compiled_dir');

                if ( !file_exists( $smarty_cache_dir ) )
                {
                    if (!@mkdir( $smarty_cache_dir, 0777, true ))
                    {
                        $message .= "<br>Unable to create $smarty_cache_dir directory. Please create this directory manually via FTP or cPanel.";
                    }//end if
                }//end if

                if ( !file_exists( $smarty_compiled_dir ) )
                {
                    if (!!@mkdir( $smarty_compiled_dir, 0777, true ))
                    {
                        $message .= "<br>Unable to create $smarty_compiled_dir directory. Please create this directory manually via FTP or cPanel.";
                    }//end if
                }//end if
            }//end if

            return $message;
        }//end activate

        /**
         * Optional implementation to deactivate plugin - can be extended by subclasses, not to be called directly but extended by subclasses
         *
         * @api
         *
         * @since 1.0
         *
         * @param void
         *
         * @return string $message Optional any messages generated by deactivation
         */
        public function deactivate()
        {
            $message = "";

            // If the plugin requires smarty create cache and compiled directories
            if ( $this->uses_smarty )
            {
                $smarty_cache_dir 		= $this->options->get_value('smarty_cache_dir');
                $smarty_compiled_dir 	= $this->options->get_value('smarty_compiled_dir');

                if ( file_exists( $smarty_cache_dir ) )
                {
                    if (!@rmdir( $smarty_cache_dir ))
                    {
                        $message .= "<br>Unable to remove $smarty_cache_dir directory. Please remove this directory manually via FTP or cPanel.";
                    }//end if
                }//end if

                if ( file_exists( $smarty_compiled_dir ) )
                {
                    if (!@rmdir( $smarty_compiled_dir ))
                    {
                        $message .= "<br>Unable to remove $smarty_compiled_dir directory. Please remove this directory manually via FTP or cPanel.";
                    }//end if
                }//end if
            }//end if


            $this->dequeue_admin_scripts();
            $this->dequeue_user_scripts();

            return $message;
        }//end deactivate


        /**
         * Optional implementation to uninstall plugin - can be extended by subclasses, not to be called directly but extended by subclasses
         *
         * @api
         *
         * @since 1.0
         *
         * @param void
         *
         * @return string $message Optional any messages generated by uninstall
         */
        public function uninstall()
        {
            if ( $this->options )
            {
                $this->options->deregister_options();
            }//end if

            $this->deactivate();
        }//end uninstall
        /**
         *  Implementation to deactivate incompatible plugins
         *
         * @api
         *
         * @since 1.2.9
         *
         * @param array $plugins_list ($plugin_name => $plugin_title) Required array of plugins to deactivate
         *
         * @return void
         */
        public function incompatible_plugins ( $plugins_list )
        {
            $this->incompatiable_plugins = $plugins_list;
        }//end incompatible_plugins

        /**
         *  Implementation to deactivate self if required plugins not installed
         *
         * @api
         *
         * @since 1.2.9
         *
         * @param array $plugins_list ($key => $value) Required array of plugins to deactivate
         *
         * @return void
         */
        public function required_plugins ( $plugins_list )
        {
            $this->required_plugins = $plugins_list;
        }//end required_plugins


        /**
         * Process the incompatible plugins
         *
         * @ignore
         *
         * @since 1.2.9
         *
         * @param void
         *
         * @return void
         */
        public function process_incompatible_plugins ()
        {
            if ( !empty( $this->incompatiable_plugins ) )
            {
                foreach ( $this->incompatiable_plugins as $a_plugin => $a_plugin_info )
                {
                    if (!preg_match("/(\.php)$/", $a_plugin))
                    {
                        $a_plugin .= "/{$a_plugin}.php";
                    }

                    $plugin_data = get_plugin_data( WP_PLUGIN_DIR . "/{$a_plugin}", false, false );
                    $plugin_version = $plugin_data['Version'];

                    if (array_key_exists('version', $a_plugin_info) && (!version_compare($plugin_version, $a_plugin_info['version'], $a_plugin_info['operator'])))
                    {
                        $this->message = $this->plugin_title . " <strong>was deactivated</strong>, this plugin is not compatible with <a href='plugin-install.php?tab=search&type=term&s={$a_plugin_info['title']}'>{$a_plugin_info['title']}</a> version {$a_plugin_info['version']}.";

                        add_action( 'admin_notices', array ( $this, 'display_error') );

                        deactivate_plugins( $this->plugin_name . DS . $this->plugin_name.'.php' );
                    }
                    else if( is_plugin_active( $a_plugin ) )
                    {
                        $this->message = $a_plugin_info['title'] . " <strong>was deactivated</strong>, this plugin is not compatible with {$this->plugin_title}.";

                        add_action( 'admin_notices', array ( $this, 'display_notice') );

                        deactivate_plugins( $a_plugin );
                    }//endif
                }//endforeach
            }//end if
        }//end process_incompatible_plugins

        /**
         * Process the required plugins
         *
         * @ignore
         *
         * @since 1.2.9
         *
         * @param void
         *
         * @return void
         */
        public function process_required_plugins ()
        {
            if ( !empty( $this->required_plugins ) )
            {
                foreach ( $this->required_plugins as $a_plugin => $a_plugin_info )
                {
                    if (!preg_match("/(\.php)$/", $a_plugin))
                    {
                        $a_plugin .= "/{$a_plugin}.php";
                    }

                    $plugin_data = get_plugin_data( WP_PLUGIN_DIR . "/{$a_plugin}", false, false );
                    $plugin_version = $plugin_data['Version'];

                    if (array_key_exists('version', $a_plugin_info) && (!version_compare($plugin_version,$a_plugin_info['version'], $a_plugin_info['operator'])))
                    {
                        $url = "plugin-install.php?tab=search&type=term&s={$a_plugin_info['title']}'>{$a_plugin_info['title']}";

                        if (array_key_exists('url', $a_plugin_info))
                            $url = $a_plugin_info['url'];
                            
                        $this->message = $this->plugin_title . " <strong>was not installed</strong>, plugin requires the installation and activation of <a href='{$url}'></a> version {$a_plugin_info['version']}.";

                        add_action( 'admin_notices', array ( $this, 'display_error') );

                        deactivate_plugins( $this->plugin_name . DS . $this->plugin_name.'.php' );
                    }
                    else if( !is_plugin_active( $a_plugin ) )
                    {
                        $url = "plugin-install.php?tab=search&type=term&s={$a_plugin_info['title']}";

                        if (array_key_exists('url', $a_plugin_info))
                            $url = $a_plugin_info['url'];

                        $this->message = $this->plugin_title . " <strong>was not installed</strong>, plugin requires the installation and activation of <a href='{$url}'>{$a_plugin_info['title']}</a>.";

                        add_action( 'admin_notices', array ( $this, 'display_error') );

                        deactivate_plugins( $this->plugin_name . DS . $this->plugin_name.'.php' );
                    }//endif
                    else
                    {
                        if (file_exists( WP_PLUGIN_DIR . "/{$a_plugin}" ))
                        {
                            include_once WP_PLUGIN_DIR . "/{$a_plugin}";
                        }//end else
                    }
                }//endforeach
            }//end if
        }//end process_required_plugins
    }//end TSP_Easy_Dev
}//endif