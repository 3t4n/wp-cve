<?php
if ( ! defined( 'ABSPATH' ) ) {
        exit; // Exit if accessed directly
}

/**
 * TinyMCE Shortcode Integration
 */
if ( !class_exists('Animate_TinyMCE') ) {

class Animate_TinyMCE {

	function __construct() {
		// Init
                add_action( 'admin_init', array( $this, 'init' ) );

                // wp_ajax_... is only run for logged users.
                add_action( 'wp_ajax_animate_check_url_action', array( $this, 'ajax_action_check_url' ) );
                add_action( 'wp_ajax_animate_nonce', array( $this, 'ajax_action_generate_nonce' ) );

                add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ), 99 );

                // Output the markup in the footer.
                add_action( 'admin_footer', array( $this, 'output_dialog_markup' ) );
	}
	
	// get everything started
        function init() {
                global $pagenow;

                if ( ( current_user_can( 'edit_posts' ) || current_user_can( 'edit_pages' ) ) && get_user_option( 'rich_editing' ) == 'true' && ( in_array( $pagenow, array( 'post.php', 'post-new.php', 'page-new.php', 'page.php' ) ) ) )  {

                       	// Add the tinyMCE buttons and plugins.
                       	add_filter( 'mce_buttons', array( $this, 'filter_mce_buttons' ) );
                       	add_filter( 'mce_external_plugins', array( $this, 'filter_mce_external_plugins' ) );

                       	wp_enqueue_style( 'animate-tinymce-shortcodes', ANIMATE_URL . 'admin/css/tinymce-shortcodes.css', false, ANIMATE_VERSION, 'all' );

			// main plugin css
	                wp_register_style( 'animate-plugin', ANIMATE_URL.'stylesheets/app.css', array(),  ANIMATE_VERSION, 'all' ) ;
                	wp_enqueue_style( 'animate-plugin' );

	                // main plugin js
        	        wp_register_script( 'animate-plugin',  ANIMATE_URL.'js/app.js', array('jquery'), ANIMATE_VERSION, true );
                	wp_enqueue_script( 'animate-plugin' );

			// admin js
			wp_register_script( 'animate-plugin-admin',  ANIMATE_URL.'admin/js/admin.js', array('jquery'), ANIMATE_VERSION, true );
                        wp_enqueue_script( 'animate-plugin-admin' );

	                add_action( 'in_admin_footer', Array(__CLASS__, 'script_in_footer'), 100 );
                }
        }

	// add new button to the tinyMCE editor
        function filter_mce_buttons( $buttons ) {
	        array_push( $buttons, '|', 'animate_button' );
                return $buttons;
        }

	// add functionality to the tinyMCE editor as an external plugin
        function filter_mce_external_plugins( $plugins ) {
 	       global $wp_version;
               $plugins['ANIMATETinyMCE'] = wp_nonce_url( esc_url( ANIMATE_URL . 'admin/shortcodes/editor.js?v=0.2' ), 'animate-tinymce' );
               return $plugins;
        }

	// checks if a given url (via GET or POST) exists
        function ajax_action_check_url() {
	        $hadError = true;
                $url = isset( $_REQUEST['url'] ) ? $_REQUEST['url'] : '';
                if ( strlen( $url ) > 0  && function_exists( 'get_headers' ) ) {
		        $url = esc_url( $url );
                        $file_headers = @get_headers( $url );
                        $exists = $file_headers && $file_headers[0] != 'HTTP/1.1 404 Not Found';
                        $hadError = false;
                }
                echo '{ "exists": '. ($exists ? '1' : '0') . ($hadError ? ', "error" : 1 ' : '') . ' }';
                die();
        }

	// generate a nonce
        function ajax_action_generate_nonce() {
	        echo wp_create_nonce( 'animate-tinymce' );
                die();
        }

        function enqueue_scripts() {
	        wp_register_script( 'animate-tinymce-dialog-script', plugins_url( 'admin/shortcodes/dialog.js', __FILE__ ), array( 'jquery' ), ANIMATE_VERSION, true );
                wp_enqueue_script( 'animate-tinymce-dialog-script' );
                $plugin_data = array(
         	       	'url' => ANIMATE_URL,
			'error_loading_details_for_shortcode' => __('Error loading details for shortcode', 'animate'),
			'animation_style_label' => __('Animation Style','animate'),
			'duration_label' => __('Duration','animate'),
			'duration_help' => __('Animation duration in seconds. (e.g. 0.15)','animate'),
			'delay_label' => __('Delay','animate'),
			'delay_help' => __('Delay before the animation starts in seconds. (e.g. 0.5)','animate'),
			'offset_label' => __('Offset','animate'),
			'offset_help' => __('Distance to start the animation in pixels (related to the browser bottom). (e.g. 10)','animate'),
			'iteration_label' => __('Iteration','animate'),
			'iteration_help' => __('Number of times the animation is repeated. (e.g. 5)','animate'),
			'animate_infinitely_label' => __('Animate Infinitely','animate'),
			'custom_class_label' => __('Custom Class','animate'),
			'custom_class_help' => __('Any CSS classes you want to add.','animate')
                );
	        wp_localize_script( 'animate-tinymce-dialog-script', 'animate_plugin_data', $plugin_data );
        }
	
        // Output the HTML markup for the dialog box.
        function output_dialog_markup () {
	        // URL to TinyMCE plugin folder
                $plugin_url = ANIMATE_URL . '/includes/shortcodes/'; ?>

                <div id="animatedialog" style="display:none">
        	        <div class="buttons-wrapper">
                               <input type="button" id="animatedialog-cancel-button" class="button alignleft" name="cancel" value="<?php _e('Cancel', 'animate') ?>" accesskey="C" />
                 	       <input type="button" id="animatedialog-insert-button" class="button-primary alignright" name="insert" value="<?php _e('Insert', 'animate') ?>" accesskey="I" />
                               <div class="clear"></div>
                        </div>
                        <div class="clear"></div>
			<div class="sandbox" id="animatedialog-sandbox">
				<section class="wow bounce"><img src='<?php echo ANIMATE_URL ;?>images/round200x200.png' width='200' height='200' alt=''></section>
				<div class="clear"></div>
				<button><?php _e('Animate it', 'animate'); ?></button>
			</div>
                        <h3 class="sc-options-title"><?php _e('Shortcode Options', 'animate') ?></h3>
                        <div id="animatedialog-shortcode-options" class="alignleft">
	                        <table id="animatedialog-options-table"></table>
                                <input type="hidden" id="animatedialog-selected-shortcode" value="">
                        </div>
                        <div class="clear"></div>
                </div><!-- /#animatedialog -->
        <?php }
	
	public static function script_in_footer(){
                $code = "<script type='text/javascript'>
                /* <![CDATA[ */
		jQuery(function(){
                wow = new WOW(
                      {
                        boxClass:     'wow',        	// default wow
                        animateClass: 'animated',    	// default animated
                        offset:       0,            	// default 0
                        mobile:       true,             // default true
                        live:         true              // default true
                      }
                    )
                    wow.init();
		});
                /* ]]> */
                </script>";
                echo $code;
        }
}
}
