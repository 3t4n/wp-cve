<?php
/**
 * JF Simple Coming Soon
 *
 * @package   JFSimpleComingSoon
 * @author    Jerome Fitzpatrick <jerome@jeromefitzpatrick.com>
 * @license   GPL-2.0+
 * @link      http://www.jeromefitzpatrick.com
 * @copyright 2013 Jerome Fitzpatrick
 */

/**
 * Plugin class.
 *
 *
 * @package JFSimpleComingSoon
 * @author  Jerome Fitzpatrick <jerome@jeromefitzpatrick.com>
 */
class JFSimpleComingSoon {

	/**
	 * Plugin version, used for cache-busting of style and script file references.
	 *
	 * @since   1.0.0
	 *
	 * @var     string
	 */
	protected $version = '1.0.0';

	/**
	 * Unique identifier for your plugin.
	 *
	 * Use this value (not the variable name) as the text domain when internationalizing strings of text. It should
	 * match the Text Domain file header in the main plugin file.
	 *
	 * @since    1.0.0
	 *
	 * @var      string
	 */
	protected $plugin_slug = 'jf-simple-coming-soon';

	/**
	 * Instance of this class.
	 *
	 * @since    1.0.0
	 *
	 * @var      object
	 */
	protected static $instance = null;

	/**
	 * Slug of the plugin screen.
	 *
	 * @since    1.0.0
	 *
	 * @var      string
	 */
	protected $plugin_screen_hook_suffix = null;

	/**
	 * Array of option fields
	 *
	 * @since    1.0.0
	 *
	 * @var      array
	 */
	protected static $option_fields = array();    
    
	/**
	 * Initialize the plugin by setting localization, filters, and administration functions.
	 *
	 * @since     1.0.0
	 */
	private function __construct() {

        // Create array of option fields for easy interation
        self::create_option_fields_array();
        
		// Load plugin text domain
		add_action( 'init', array( $this, 'load_plugin_textdomain' ) );

		// Add the options page and menu item.
		add_action( 'admin_menu', array( $this, 'add_plugin_admin_menu' ) );

		// Load admin style sheet and JavaScript.
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_styles' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );

        // Plugin options		
        add_action('admin_init', array($this,'init_plugin_options'));
        add_action('admin_init', array($this,'set_option_defaults'));

        // Check if user logged in - if so, add function to template_redirect action
		add_action( 'init', array( $this, 'check_if_user_logged_in' ) );
	}

	/**
	 * Return an instance of this class.
	 *
	 * @since     1.0.0
	 *
	 * @return    object    A single instance of this class.
	 */
	public static function get_instance() {

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}
		return self::$instance;
	}

	/**
	 * Fired when the plugin is activated.
	 *
	 * @since    1.0.0
	 *
	 * @param    boolean    $network_wide    True if WPMU superadmin uses "Network Activate" action, false if WPMU is disabled or plugin is activated on an individual blog.
	 */
	public static function activate( $network_wide ) {
		// TODO: Define activation functionality here
	}

	/**
	 * Fired when the plugin is deactivated.
	 *
	 * @since    1.0.0
	 *
	 * @param    boolean    $network_wide    True if WPMU superadmin uses "Network Deactivate" action, false if WPMU is disabled or plugin is deactivated on an individual blog.
	 */
	public static function deactivate( $network_wide )
    {
        $clear = get_option('jf_scs_delete_options');
        if ($clear == 1)
        {
            foreach (self::$option_fields as $field_id=>$options)
            {
                delete_option($field_id);
            }
        }
	}

	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		$domain = $this->plugin_slug;
		$locale = apply_filters( 'plugin_locale', get_locale(), $domain );

		load_textdomain( $domain, WP_LANG_DIR . '/' . $domain . '/' . $domain . '-' . $locale . '.mo' );
		load_plugin_textdomain( $domain, FALSE, dirname( plugin_basename( __FILE__ ) ) . '/lang/' );
	}

	/**
	 * Register and enqueue admin-specific style sheet.
	 *
	 * @since     1.0.0
	 *
	 * @return    null    Return early if no settings page is registered.
	 */
	public function enqueue_admin_styles() {

		if ( ! isset( $this->plugin_screen_hook_suffix ) ) {
			return;
		}

		$screen = get_current_screen();
		if ( $screen->id == $this->plugin_screen_hook_suffix ) {
			wp_enqueue_style( $this->plugin_slug .'-admin-styles', plugins_url( 'css/admin.css', __FILE__ ), array(), $this->version );
		}

	}

	/**
	 * Register and enqueue admin-specific JavaScript.
	 *
	 * @since     1.0.0
	 *
	 * @return    null    Return early if no settings page is registered.
	 */
	public function enqueue_admin_scripts()
    {

		if ( ! isset( $this->plugin_screen_hook_suffix ) ) {
			return;
		}

		$screen = get_current_screen();
		if ( $screen->id == $this->plugin_screen_hook_suffix )
        {
            wp_enqueue_style( 'wp-color-picker' );
			wp_enqueue_script( $this->plugin_slug . '-admin-script', plugins_url( 'js/admin.js', __FILE__ ), array( 'jquery', 'wp-color-picker' ), $this->version );
 
            wp_enqueue_script( 'ace_code_highlighter_js', plugins_url( 'js/ace.js', __FILE__ ), '', '1.0.0', true );
            wp_enqueue_script( 'ace_mode_js', plugins_url( 'js/mode-css.js', __FILE__ ), array( 'ace_code_highlighter_js' ), '1.0.0', true );
            
        }
	}

	/**
	 * Register the administration menu for this plugin into the WordPress Dashboard menu.
	 *
	 * @since    1.0.0
	 */
	public function add_plugin_admin_menu()
    {

		$this->plugin_screen_hook_suffix = add_options_page(
			__( 'JF Simple Coming Soon', $this->plugin_slug ),
			__( 'Simple Coming Soon', $this->plugin_slug ),
			'read',
			$this->plugin_slug,
			array( $this, 'display_plugin_admin_page' )
		);

	}

	/**
	 * Render the settings page for this plugin.
	 *
	 * @since    1.0.0
	 */
	public function display_plugin_admin_page()
    {
		include_once( 'views/admin.php' );
	}

	
	/**
	 * Create Array of Plugin Option Fields for easy interation
	 * 
	 * @since    1.0.0
	 */
	protected static function create_option_fields_array()
	{
       self::$option_fields = array(
                        'jf_scs_enable' => array('label' => 'Enable This Plugin', 'default'=>'','type'=>'checkbox','extra'=>array(),),
                        'jf_scs_content_field' => array('label' => 'Simple Coming Soon Content','type'=>'wp-editor', 'default'=>'<p style="text-align: center;">This website is coming soon...</p>','extra'=>array(),),
                        'jf_scs_title_field' => array('label' => 'Text For Title Bar','type'=>'text', 'default'=>'', 'extra'=>array('placeholder'=>'E.g. Coming Soon - '),),
                        'jf_scs_bgcolor_field' => array('label' => 'Background Color','type'=>'color-picker', 'default'=>'#000000','extra'=>array(),),
                        'jf_scs_textcolor_field' => array('label' => 'Text Color','type'=>'color-picker', 'default'=>'#FFFFFF','extra'=>array(),),
                        'jf_scs_topmargin_field' => array('label' => 'Top Margin','type'=>'numeric', 'default'=>'20', 'sanitize'=>'intval','extra'=>array(),),
                        'jf_scs_use_custom_css' => array('label' => 'Use Custom CSS','type'=>'checkbox', 'default'=>'','extra'=>array(),),
                        'jf_scs_custom_css_field' => array('label' => 'Custom CSS','type'=>'ace', 'default'=>self::preset_css(),'extra'=>array(),),
                        'jf_scs_delete_options' => array('label' => 'Clear Options on Deactivate','type'=>'checkbox', 'default'=>'','extra'=>array(),),
                        );
	}

	/**
	 * Add function to template_redirect action if user not logged in
	 * 
	 * @since    1.0.0
	 */
	public function check_if_user_logged_in()
	{
        if (!is_user_logged_in() && get_option('jf_scs_enable') == 1)
        {
    		add_action( 'template_redirect', array( $this, 'replace_template' ) );
        }
	}
    
	/**
	 * Display markup for coming soon page
	 * 
	 * @since    1.0.0
	 */
	public function replace_template()
	{
        require_once('views/replacement-template.php');
        exit();
	}
	
	/**
	 * Coming Soon Page Content
	 * 
	 * @since    1.0.0
	 */
	public function splash_page_content()
	{
        $content = get_option('jf_scs_content_field'); // content grabbed from wp styled editor on the options page
        if ($content)
        {
            echo apply_filters('the_content',$content);
        }
	}
    
	/**
	 * Check if Use Custom CSS option is selected and if so, add custom CSS to the head section of the template as the final CSS entry
	 * 
	 * @since    1.0.0
	 */
	public function custom_css()
	{
        $use_custom_css = get_option('jf_scs_use_custom_css');
        if ($use_custom_css)
        {
            $custom_css = get_option('jf_scs_custom_css_field');
            return $custom_css;
        }
	}
    
	/**
	 * Check if Use Custom CSS option is selected and if so, add custom CSS to the head section of the template as the final CSS entry
	 * 
	 * @since    1.0.0
	 */
	public function title_field()
	{
        $title_field = get_option('jf_scs_title_field');
        if ($title_field)
        {
            return $title_field;
        }
	}
        
    
    /* ------------------------------------------------------------------------ *
     * Setting Registration
     * ------------------------------------------------------------------------ */ 
    
    public function init_plugin_options()
    {
        $fields = self::$option_fields;
        
        add_settings_section(
	    'jf_scs_section_id',
	    'Edit Content',
	    array($this, 'description'),
	    $this->plugin_slug
        );
        
        foreach ($fields as $field => $options)
        {
            if (!empty($options['sanitize']))
            {
                register_setting('jf_scs_group', $field, $options['sanitize']);
            }
            else
            {
                register_setting('jf_scs_group', $field);
            }
            
            add_settings_field(
            $field, 
            $options['label'], 
            array($this, 'render_fields'), 
            $this->plugin_slug,
            'jf_scs_section_id',
            array('field'=>$field,'type'=> $options['type'], 'extra'=>$options['extra'],)
            );            
        }
    }

	/**
	 * Set defaults for options - if an option is empty or not created, populate it with default settings.
	 * 
	 * @since    1.0.0
	 */
    public function set_option_defaults()
    {
        foreach (self::$option_fields as $field=>$options)
        {
            $current = get_option($field);
            if ($current === FALSE || $current==="")
            {
                update_option($field,$options['default']);
            }
        }
    }
	
	/**
	 * Description of Option
	 * 
	 * @since    1.0.0
	 */
    public function description()
    {
        // Nothing to say here... I find the label is descriptive enough
    }

    	
	/**
	 * Display field controls on the options page
	 * 
	 * @since    1.0.0
	 */
    public function render_fields($args = array())
    {
        $field = $args['field'];
        $type = $args['type'];
        switch($type)
        {
            case 'checkbox':
                echo "<input type='checkbox' name='{$field}' value='1' " . checked( get_option($field), 1, false) . " />";
            break;
        
            case 'wp-editor':
                wp_editor(get_option($field),$field);
            break;

            case 'color-picker':
                echo "<input type='text' id='{$field}' name='{$field}' class='jf-scs-color-picker' value='" . get_option($field) . "' />";
            break;

            case 'numeric':
                echo "<input type='text' id='{$field}' name='{$field}' value='" . get_option($field) . "' />%";
            break;
        
            case 'text':
                echo "<input type='text' id='{$field}' name='{$field}' value='" . get_option($field) . "' placeholder='" . $args['extra']['placeholder'] ."' />";
            break;
        
            case 'textarea':
                echo "<textarea id='{$field}' name='{$field}' class='jf-scs-textarea'>" . esc_textarea(get_option($field)) . "</textarea>";
            break;
        
            case 'ace':
                echo "<div id='{$field}_container'>";
                echo "<div name='{$field}' id='{$field}' class='jf-scs-textarea'></div>";
                echo "</div>";
                echo "<textarea id='{$field}_textarea' name='{$field}' style='display: none;'>" . esc_textarea(get_option($field)) . "</textarea>";                
            break;        
        }
    }
	
	/**
	 * Set Preset CSS which is the first CSS in the head section of the replacement template.  Can be overridden by custom CSS option.
	 * 
	 * @since    1.0.0
	 */    
    public static function preset_css()
    {
        $body = "";
        $wrapper = "";
        $bgcolor = get_option('jf_scs_bgcolor_field');
        $textcolor = get_option('jf_scs_textcolor_field');
        $topmargin = get_option('jf_scs_topmargin_field');
        
        $body .= $bgcolor ? "\n\tbackground-color:" . $bgcolor . "; ": "";
        $body .= $textcolor ? "\n\tcolor:" . $textcolor . "; " : "";
        $body = "html \{\n\theight:100%;\n\}\n\n#jf-scs \{{$body}\n\theight:100%;\n\}\n\n";
        
        $wrapper .= $topmargin ? 'top:' . $topmargin . "%; " : "";
        $wrapper ="#jf-scs .content-area-wrapper \{\n\t{$wrapper}\n\tposition:relative;\n\}\n\n";
        
        $other = ".aligncenter \{\n\tdisplay:block;\n\tmargin:0 auto;\n\}\n\n";

        return stripslashes($body . $wrapper . $other);
    }
    
} // End of Class

