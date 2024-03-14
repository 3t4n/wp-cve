<?php
/*
Plugin Name: Lead Call Buttons
Plugin URI: http://getyoursmartsiteon.com
Description: Make it easy for website visitors to reach you. When enabled adds customizable buttons to the mobile view of the website, i.e. Call, Map, Schedule.
Author: Team Smart Site
Author URI: http://getyoursmartsiteon.com
Version: 1.0.7
*/

/* Adding Latest jQuery from Wordpress */
function lead_call_button_latest_jquery() {
	wp_enqueue_script('jquery');
    wp_enqueue_script('lead_call_button_movement_script', plugins_url( '/js/movement.js', __FILE__));
    wp_enqueue_script('lead_call_button_script', plugins_url( '/js/script.js', __FILE__));
}
add_action('init', 'lead_call_button_latest_jquery'); 

function lead_call_button_admin_init() {
    wp_register_script('lead_call_button_script', plugins_url( '/js/script.js', __FILE__ ) );
    wp_enqueue_style('lead_call_button_style', plugins_url( '/css/backend-styles.css', __FILE__ ) );
}
add_action('admin_init', 'lead_call_button_admin_init');

/* LCB Hide Meta Box */
function lead_call_buttons_options_get_meta( $value ) {
	global $post;
	$field = get_post_meta( $post->ID, $value, true );
	if ( ! empty( $field ) ) {
		return is_array( $field ) ? stripslashes_deep( $field ) : stripslashes( wp_kses_decode_entities( $field ) );
	} else {
		return false;
	}
}

function lead_call_buttons_options_add_meta_box() {
	add_meta_box(
		'lead_call_buttons_options-lead-call-buttons-options',
		__( 'Lead Call Buttons Options', 'lead_call_buttons_options' ),
		'lead_call_buttons_options_html',
		'post',
		'side',
		'low'
	);
	add_meta_box(
		'lead_call_buttons_options-lead-call-buttons-options',
		__( 'Lead Call Buttons Options', 'lead_call_buttons_options' ),
		'lead_call_buttons_options_html',
		'page',
		'side',
		'low'
	);
}
add_action( 'add_meta_boxes', 'lead_call_buttons_options_add_meta_box' );

function lead_call_buttons_options_html( $post) {
	wp_nonce_field( '_lead_call_buttons_options_nonce', 'lead_call_buttons_options_nonce' ); ?>
	<p>
        <input type="checkbox" name="lead_call_buttons_options_hide_lead_call_buttons" id="lead_call_buttons_options_hide_lead_call_buttons" value="hide-lead-call-buttons" <?php echo ( lead_call_buttons_options_get_meta( 'lead_call_buttons_options_hide_lead_call_buttons' ) === 'hide-lead-call-buttons' ) ? 'checked' : ''; ?>>
		<label for="lead_call_buttons_options_hide_lead_call_buttons"><strong><?php _e( 'Hide Lead Call Buttons', 'lead_call_buttons_options' ); ?></strong></label>
    </p>
<?php
}

function lead_call_buttons_options_save( $post_id ) {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
	if ( ! isset( $_POST['lead_call_buttons_options_nonce'] ) || ! wp_verify_nonce( $_POST['lead_call_buttons_options_nonce'], '_lead_call_buttons_options_nonce' ) ) return;
	if ( ! current_user_can( 'edit_post', $post_id ) ) return;

	if ( isset( $_POST['lead_call_buttons_options_hide_lead_call_buttons'] ) )
		update_post_meta( $post_id, 'lead_call_buttons_options_hide_lead_call_buttons', esc_attr( $_POST['lead_call_buttons_options_hide_lead_call_buttons'] ) );
	else
		update_post_meta( $post_id, 'lead_call_buttons_options_hide_lead_call_buttons', null );
}
add_action( 'save_post', 'lead_call_buttons_options_save' );


/* Main Class */
class LCBMain {

    private $plugin_path;
    private $plugin_url;
    private $frame_work;
    private $LCB;

    function __construct() {	
        
        $this->plugin_path = plugin_dir_path( __FILE__ );
        $this->plugin_url = plugin_dir_url( __FILE__ );
        $this->frame_work = 'lead-call-button-frame';
        add_action( 'admin_menu', array(&$this, 'admin_menu') );
        add_filter( 'plugin_action_links', array(&$this, 'settings_link'), 10, 2);
        
        // Create New Framework
        require_once( $this->plugin_path .'lead-call-button-frame.php' );
		
		// Set Up
		define('LEAD_CALL_BUTTON_HOOK', WP_PLUGIN_URL . '/' . plugin_basename( dirname(__FILE__) ) . '/' );
		//wp_enqueue_style('lead_call_button_css', LEAD_CALL_BUTTON_HOOK.'css/plugin-main.css');

		function lead_call_button_active_hook() {
			include_once( 'lead-call-buttons.php' );
		}
		add_action('wp_footer', 'lead_call_button_active_hook');
		
		function lead_call_button_font_awesome_css() {
            wp_enqueue_style('font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css'); 
            wp_enqueue_style('lcb-css-movement', WP_PLUGIN_URL . '/' . plugin_basename( dirname(__FILE__) ) . '/'.'css/movement.css');
            wp_enqueue_style('lcb-css-main', WP_PLUGIN_URL . '/' . plugin_basename( dirname(__FILE__) ) . '/'.'css/plugin-main.css'); 
		}
		add_action('wp_enqueue_scripts', 'lead_call_button_font_awesome_css');
		
		function lead_call_button_custom_css() {?>
			<style type="text/css">
				<?php echo LCB_get_setting( 'lead_call_buttons', 'general', 'custom-css' ); ?>
			</style>		
		<?php
		}
		add_action('wp_head', 'lead_call_button_custom_css');
		
        $this->LCB = new LCB_setting( $this->plugin_path .'settings/lead-call-button-settings-general.php' );
        // Add an optional settings validation filter (recommended)
        add_filter( $this->LCB->get_option_group() .'_settings_validate', array(&$this, 'validate_settings') );
    }
    
    function admin_menu(){
        $page_hook = add_options_page( __( 'Lead Call Buttons', $this->frame_work ), __( 'Lead Call Buttons', $this->frame_work ), 'manage_options', 'lead_call_buttons', array(&$this, 'settings_page') );
    }
       
    function settings_link($links, $file) {
    	static $this_plugin;
    	if (!$this_plugin) $this_plugin = plugin_basename(__FILE__);
    	if ($file == $this_plugin){
    		$settings_link = '<a href="admin.php?page=lead_call_buttons">'.__("Settings").'</a>';
    		array_unshift($links, $settings_link);
    	}
    	return $links;
    } 
    
    function settings_page() { ?>
	    <div class="wrap">
            <h2>Lead Call Buttons Settings</h2>
            <div class="postbox-container">
                <div class="postbox">
                	<div id="icon-options-general" class="icon32"></div>            	
                    <div class="inside">
                		<?php 
                		// Output your settings form
                		 $this->LCB->settings(); 
                		?>
                    </div>
                </div>
            </div>
         </div>
	<?php
	}
	
	function validate_settings( $input ){
    	return $input;
	}
}

new LCBMain();

?>