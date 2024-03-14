<?php
if (!defined('ABSPATH')) {
    exit;
}

class Theplus_plugin_options
{
    
    /**
     * Option key, and option page slug
     * @var string
     */
    private $key = 'theplus_options';
    
    /**
     * Array of metaboxes/fields
     * @var array
     */
    protected $option_metabox = array();
    
    /**
     * Options Page title
     * @var string
     */
    protected $title = '';
    
    /**
     * Options Page hook
     * @var string
     */
    protected $options_page = '';
    protected $options_pages = array();
    /**
     * Constructor
     * @since 0.1.0
     */
    public function __construct()
    {
        // Set our title
		add_action( 'admin_enqueue_scripts', array( $this,'pt_theplus_options_scripts') );
        $this->title = __('ThePlus Ultimate Plugin Options', 'pt_theplus');
        require_once THEPLUS_PLUGIN_PATH.'post-type/cmb2-conditionals.php';
        // Set our CMB fields
        $this->fields = array(
        );
    }
    
    /**
     * Initiate our hooks
     * @since 1.0.0
     */
	public function pt_theplus_options_scripts() {   
		wp_enqueue_script( 'pt-theplus-js', THEPLUS_PLUGIN_URL .'/post-type/cmb2-conditionals.js', array() );
	}
	
    public function hooks()
    {
        add_action('admin_init', array(
            $this,
            'init'
        ));
        add_action('admin_menu', array(
            $this,
            'add_options_page'
        ));
    }
    
    /**
     * Register our setting to WP
     * @since  1.0.0
     */
    public function init()
    {
        //register_setting( $this->key, $this->key );
        $option_tabs = self::option_fields();
        foreach ($option_tabs as $index => $option_tab) {
            register_setting($option_tab['id'], $option_tab['id']);
        }
    }
    
    /**
     * Add menu options page
     * @since 1.0.0
     */
    public function add_options_page()
    {
       
        $option_tabs = self::option_fields();
        foreach ($option_tabs as $index => $option_tab) {
            if ($index == 0) {
                $this->options_pages[] = add_menu_page($this->title, $this->title, 'manage_options', $option_tab['id'], array(
                    $this,
                    'admin_page_display'
                )); 
                add_submenu_page($option_tabs[0]['id'], $this->title, $option_tab['title'], 'manage_options', $option_tab['id'], array(
                    $this,
                    'admin_page_display'
                )); 
            } else {
                $this->options_pages[] = add_submenu_page($option_tabs[0]['id'], $this->title, $option_tab['title'], 'manage_options', $option_tab['id'], array(
                    $this,
                    'admin_page_display'
                ));
            }
        }
    }
    
    /**
     * 
     * @since  1.0.0
     */
    public function admin_page_display()
    {
        $option_tabs = self::option_fields(); //get all option tabs
        $tab_forms   = array();
?>

		<div class="<?php  echo $this->key; ?>">
		<div id="ptplus-banner-wrap">
			<div id="ptplus-banner" class="ptplus-banner-sticky">
				<h2><span><img src="<?php echo THEPLUS_PLUGIN_URL .'/vc_elements/images/thepluslogo.png'; ?>"></span><span><?php echo esc_html('Lite Version','pt_theplus'); ?><sup style="font-size:10px;"><?php echo esc_html('1.0.0','pt_theplus'); ?></sup></span></h2>
				<a href="https://codecanyon.net/item/theplus-visual-composer-addons/21346121?ref=posimyththemes" target="_blank" class="ptplus-premium-link"><?php echo esc_html('Premium version','pt_theplus'); ?></a>
			</div>
		</div>
		<h2 class="nav-tab-wrapper">
            	<?php
	        foreach ($option_tabs as $option_tab):
	            $tab_slug  = $option_tab['id'];
	            $nav_class = 'nav-tab';
	            if ($tab_slug == $_GET['page']) {
	                $nav_class .= ' nav-tab-active'; //add active class to current tab
	                $tab_forms[] = $option_tab; //add current tab to forms to be rendered
	            } ?>            	
            	<a class="<?php echo $nav_class; ?>" href="<?php  menu_page_url($tab_slug); ?>"><?php esc_attr_e($option_tab['title']); ?></a>
            	<?php endforeach; ?>
            </h2>
		<?php foreach ($tab_forms as $tab_form): ?>
	            <div id="<?php esc_attr_e($tab_form['id']); ?>" class="group theplus_form_content">
	            	<?php cmb_metabox_form($tab_form, $tab_form['id']); ?>
	            </div>
            	<?php  endforeach; ?>
		</div>
		<?php
    }
    
    /**
     * Defines the theme option metabox and field configuration
     * @since  1.0.0
     * @return array
     */
    public function option_fields()
    {
        
        // Only need to initiate the array once per page-load
        if (!empty($this->option_metabox)) {
            return $this->option_metabox;
        }
        
        $this->option_metabox[] = array(
            'id' => 'general_options',
            'title' => 'General',
            'show_on' => array(
                'key' => 'options-page',
                'value' => array(
                    'general_options'
                )
            ),
            'show_names' => true,
            'fields' => array(
	            array(
	                'name' => __('Display Elements', 'pt_theplus'),
	                'desc' => __('Display Elements', 'pt_theplus'),
	                'id' => 'check_elements',
	                'type' => 'multicheck',
	                'options' => array(
	                    'tp_accordion' => __('Accordation', 'pt_theplus'),
						'tp_advertisement_banner' => __('Advertisement Banner', 'pt_theplus'),
						'tp_animated_svg' => __('Animated Svg', 'pt_theplus'),
						'tp_before_after' => __('Before After', 'pt_theplus'),
						'tp_button' => __('Ts Button', 'pt_theplus'),
						'tp_contact_form' => __('Contact Form 7', 'pt_theplus'),
	                    'tp_countdown' => __('CountDown', 'pt_theplus'),
	                    'tp_empty_space' => __('Empty Space', 'pt_theplus'),
						'tp_food_menu' => __('Food Menu', 'pt_theplus'),
						'tp_header_breadcrumbs' => __('Breadcrumbs', 'pt_theplus'),
						'tp_heading_animation' => __('Animated Text', 'pt_theplus'),
						'tp_heading_title' => __('Heading Style', 'pt_theplus'),
	                    'tp_icon_counter' => __('Icon Counter', 'pt_theplus'),
						'tp_info_banner' => __('Info Banner', 'pt_theplus'),
						'tp_info_box' => __('Info Box', 'pt_theplus'),
	                    'tp_pricing_table' => __('Pricing Table', 'pt_theplus'),
	                    'tp_progressbar' => __('Progress Bar', 'pt_theplus'),
	                    'tp_social_share' => __('Social Share', 'pt_theplus'),
	                    'tp_stylish_list' => __('Stylish List', 'pt_theplus'),
						'tp_tabs' => __('Tabs', 'pt_theplus'),
	                    'tp_tours' => __('Tours', 'pt_theplus'),
	                    'tp_video_player' => __('Video Player', 'pt_theplus'),
	                )	                
	            ),
            )
        );
        
        return $this->option_metabox;
    }
   
    public function get_option_key($field_id)
    {
        $option_tabs = $this->option_fields();
        foreach ($option_tabs as $option_tab) { //search all tabs
            foreach ($option_tab['fields'] as $field) { //search all fields
                if ($field['id'] == $field_id) {
                    return $option_tab['id'];
                }
            }
        }
        return $this->key; //return default key if field id not found
    }
    /**
     * Public getter method for retrieving protected/private variables
     * @since  1.0.0
     * @param  string  $field Field to retrieve
     * @return mixed          Field value or exception is thrown
     */
    public function __get($field)
    {
        
        // Allowed fields to retrieve
        if (in_array($field, array('key','fields','title','options_page'), true)) {
            return $this->{$field};
        }
        if ('option_metabox' === $field) {
            return $this->option_fields();
        }
        
        throw new Exception('Invalid property: ' . $field);
    }
    
}


// Get it started
$Theplus_plugin_options = new Theplus_plugin_options();
$Theplus_plugin_options->hooks();

/**
 * Wrapper function around cmb_get_option
 * @since  1.0.0
 * @param  string  $key Options array key
 * @return mixed        Option value
 */
function pt_theplus_get_option($key = '')
{
    global $Theplus_plugin_options;
    return cmb_get_option($Theplus_plugin_options->key, $key);
}