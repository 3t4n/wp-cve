<?php
/*
Plugin Name: Live CSS Preview
Plugin URI: http://dojodigital.com
Description: Adds a textarea to the new Customize page that allows theme editors to write, preview & implement css code in real time.
Version: 2.0.0
Author: Randall Runnels
Author URI: http://dojodigital.com
*/

if ( ! defined( 'ABSPATH' ) ) exit;

class DojoDigitalLiveCSSPreview {

	/**
	 * The version number.
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $_version;

	/**
	 * The token.
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $slug = 'dojodigital_live_css';


	/**
	 * The plugin assets directory.
	 * @var     string
	 * @access  public
	 * @since   2.0.0
	 */
	public $assets_dir;

	/**
	 * The plugin assets URL.
	 * @var     string
	 * @access  public
	 * @since   2.0.0
	 */
	public $assets_url;

	/**
	 * String to use for option_name in options table
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
    public $optionFull; 
	
	/**
	 * String to use for index position in options table
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $optionName;

	/**
	 * Constructor function.
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function __construct ( $version = '1.0.0' ) {
		
		// configure the settings handles
		$this->optionName = $this->slug . '_data';
    	$this->optionFull = $this->optionName . '[' . $this->slug . ']';
    	
		$this->_version = $version;

		// Load plugin environment variables
		$this->assets_dir = trailingslashit( dirname( __FILE__ ) ) . 'assets';
		$this->assets_url = esc_url( trailingslashit( plugins_url( '/assets/', __FILE__ ) ) );
		
		// Add the Customizer code
		add_action( 'customize_register', array( $this, 'register_field' ) );
		add_action( 'customize_controls_enqueue_scripts', array($this, 'customizer_scripts') );
		
		// Add style tag to header
		add_action( 'wp_head', array( &$this, 'insert_placeholder' ), 10000 );

		add_action( 'wp_ajax_frontend_save', array( &$this, 'frontend_save' ) );

		add_action('init', array( $this, 'init' ) );
		
	} // __construct ()

	
	/**
	 * Initializes the plugin.
	 * @access	public
	 * @since	1.0.0
	 * @return	void
	 */
	public function init(){
		global $wp_customize;

		if( !current_user_can( 'edit_theme_options' ) || is_admin() || isset( $wp_customize ) ) return false;
		
		// Load frontend JS & CSS
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ), 10 );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ), 10 );

		// Add Editor to front end admin bar
		add_action( 'admin_bar_menu', array( &$this, 'admin_bar_menu' ), 10000 );
		
	} // init()
	
	
	/**
	 * Registers the Customize API and builds the live css section & field.
	 * @param	object	$wp_customize
	 * @access	public
	 * @since	1.0.0
	 * @return	void
	 */
	public function register_field( $wp_customize ) {
	
		// This must be called here! Any earlier and the WP_Customize_Control may not be available.
		require_once( 'wp-customize-ext.class.php' );
		
		$section = $this->slug . '_section';
	
		$wp_customize->add_section( $section, array(
			'title'       	=> __( 'CSS', 'dojodigital_live_css' ),
			'priority'     	=> 1000
		) );
						
		$wp_customize->add_setting( $this->optionFull, array(
			'default'        => '',
			'type'           => 'option',
			'capability'     => 'edit_theme_options',
			'transport'		=> 'postMessage'
		) );
	
		
		$wp_customize->add_control( new DojoDigitalLiveCSSPreview_Control( $wp_customize, $this->slug . '-editor', array(
			'section' 		=> $section,
			'settings' 		=> $this->optionFull
		) ) );
		
		if( $wp_customize->is_preview() && !is_admin() ){
			add_action( 'wp_footer', array( $this, 'preview_scripts' ), 21 );
		}
		
	} // register_field() 	
	
	
    /**
	 * Inserts a style tag as a placeholder into wp_head.
	 * @access	public
	 * @since	1.0.0
	 * @return	void
	 */
	public function insert_placeholder(){
	
		echo '<style type="text/css" id="' . $this->slug . '">';
	
		$opt = get_option( $this->optionName );
		
		if( isset( $opt[ $this->slug ] ) ) echo $opt[ $this->slug ];
		
		echo '</style>';
		
		?><script type="text/javascript">
var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
		</script><?php
	
	} // insert_placeholder()

	
	public function frontend_save(){
		
		$opt[ $this->slug ] = stripslashes_deep( $_POST['styles'] );
	
		echo update_option( $this->optionName, $opt );
			
		exit;
	}
	
		
	/**
	 * Add the CSS button to the admin bar
	 * @access	public
	 * @since	1.0.0
	 * @return	void
	 */
	public function admin_bar_menu( $adminbar ){
			
		if ( !is_admin_bar_showing() || !current_user_can( 'edit_theme_options' ) ) return;
    
    	$adminbar->add_menu( array( 'id' => $this->slug . '-toggle', 'title' => __( 'CSS', 'dojodigital_live_css' ), 'href' => '#' ) );
	  	
	} // admin_bar_menu()
	
	
	/**
	 * Load frontend CSS.
	 * @access  public
	 * @since   1.0.0
	 * @return void
	 */
	public function enqueue_styles () {
		wp_enqueue_style( $this->slug . '-frontend', esc_url( $this->assets_url ) . 'css/frontend.css', array(), $this->_version  );
		wp_enqueue_style('jquery-ui-style', '//code.jquery.com/ui/1.11.2/themes/dark-hive/jquery-ui.css');
	} // enqueue_styles ()

	
	/**
	 * Load frontend Javascript.
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function enqueue_scripts () {
		wp_enqueue_script('jquery');
		wp_enqueue_script('jquery-ui-draggable');
		wp_enqueue_script('jquery-ui-resizable');
		wp_enqueue_script('ace-editor', '//cdn.jsdelivr.net/ace/1.1.8/min/ace.js');
		wp_enqueue_script( $this->slug . '-frontend', esc_url( $this->assets_url ) . 'js/frontend.js', array('jquery', 'jquery-ui-draggable', 'jquery-ui-resizable'), $this->_version, true );		
	} // enqueue_scripts ()

	
	/**
	 * Load customizer Javascript.
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function customizer_scripts () {
		wp_enqueue_script('jquery');
		wp_enqueue_script('ace-editor', '//cdn.jsdelivr.net/ace/1.1.8/min/ace.js');
		wp_enqueue_script( $this->slug . '-customizer', esc_url( $this->assets_url ) . 'js/customizer.js', array('jquery'), $this->_version, true );		
	} // customizer_scripts ()

	
	/**
	 * Prints out the javascript necessary to show previews in real time.
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function preview_scripts() { ?>
	
<script type="text/javascript">
( function( $ ){

	// Bind the Live CSS
wp.customize('<?php echo $this->optionFull; ?>', function( value ) {
	value.bind(function(to) {
		$('#<?php echo $this->slug; ?>').html( to );
		});
	});
	
} )( jQuery )
</script>

	<?php } // preview_scripts()

} // END DojoDigitalLiveCSSPreview


$DojoDigitalLiveCSSPreview = new DojoDigitalLiveCSSPreview('2.0.0');