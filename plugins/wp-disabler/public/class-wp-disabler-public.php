<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Plugin_Name
 * @subpackage Plugin_Name/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Plugin_Name
 * @subpackage Plugin_Name/public
 * @author     Your Name <email@example.com>
 */
class Wp_Disabler_Public {

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
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wp-disabler-public.css', array(), time(), 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
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

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wp-disabler-public.js', array( 'jquery' ), time(), false );

	}

	public function wpdisabler_toast_message(){


		$right_click_disable 				= AdminPageFramework::getOption( 'APF_Tabs', 'right_click_disable' );
		$control_c_disable 					= AdminPageFramework::getOption( 'APF_Tabs', 'control_c_disable' );
		$inspect_element_disable 			= AdminPageFramework::getOption( 'APF_Tabs', 'inspect_element_disable' );
		$view_source_code_disable 			= AdminPageFramework::getOption( 'APF_Tabs', 'view_source_code_disable' );
		$disable_message_backgound 			= AdminPageFramework::getOption( 'APF_Tabs', 'message_backgound_color' );

		$right_click_disable_message 		= AdminPageFramework::getOption( 'APF_Tabs', 'right_click_disable_message' );
		$control_c_disable_message 			= AdminPageFramework::getOption( 'APF_Tabs', 'control_c_disable_message' );
		$inspect_element_disable_message 	= AdminPageFramework::getOption( 'APF_Tabs', 'inspect_element_disable_message' );
		$selection_disable					= AdminPageFramework::getOption( 'APF_Tabs', 'selection_disable' );
		$selection_disable_message			= AdminPageFramework::getOption( 'APF_Tabs', 'selection_disable_message' );

		//Style
		$toast_vertical_positon				= AdminPageFramework::getOption( 'APF_Tabs', 'toast_vertical_positon' );
		$toast_horizontal_positon			= AdminPageFramework::getOption( 'APF_Tabs', 'toast_horizontal_positon' );



		?>

		<script>
			jQuery(document).ready(function($) {
			    var theme_message_div = document.createElement('div');
			    theme_message_div.setAttribute("id", "show_message");
			    document.getElementsByTagName('body')[0].appendChild(theme_message_div);

			    function show_toast_message(message_value) {
			        var x = document.getElementById("show_message");
			        x.innerHTML = message_value;
			        x.className = "show";
			        setTimeout(function() {
			            x.className = x.className.replace("show", "");
			        }, 3000);
			    }
			    window.onload = function() {
			    	<?php if ($right_click_disable == 1) { ?>

			    		document.addEventListener("contextmenu", function(e) {
				            e.preventDefault();
				            show_toast_message('<?php echo $right_click_disable_message ?>');
				        }, false);
			    		
			    	<?php } ?>
			        
			        //Disabler Selection
			        <?php if ($selection_disable == 1) { ?>
				    	window.addEventListener('selectstart', function(e){ 
				    		show_toast_message('<?php echo $selection_disable_message; ?>');
				    		e.preventDefault();

				    	});
				    <?php } ?>

			        document.addEventListener("keydown", function(e) {
			            //document.onkeydown = function(e) {
			            // "I" key
			            <?php if ( $inspect_element_disable == 1 ) { ?>
				            if (e.ctrlKey && e.shiftKey && e.keyCode == 73) {
				                disabledEvent(e);
				                show_toast_message('<?php echo $inspect_element_disable_message ?>');
				            }
				            // "J" key
				            if (e.ctrlKey && e.shiftKey && e.keyCode == 74) {
				                disabledEvent(e);
				                show_toast_message('<?php echo $inspect_element_disable_message ?>');
				            }
				            // "F12" key
				            if (event.keyCode == 123) {
				                disabledEvent(e);
				                show_toast_message('<?php echo $inspect_element_disable_message ?>');
				            }
				        <?php } ?>
			            // "U" key
			            <?php if ($view_source_code_disable == 1) { ?>
				            if (e.ctrlKey && e.keyCode == 85) {
				                disabledEvent(e);
				                show_toast_message('Sorce Code Disable');
				            }
				        <?php } ?>
			            
			            // "C" key
			            <?php if ($control_c_disable == 1) { ?>
				            if (event.keyCode == 67) {
				                disabledEvent(e);
				                show_toast_message('<?php echo $control_c_disable_message ?>');
				            }
				            // "S" key + macOS
				            if (e.keyCode == 83 && (navigator.platform.match("Mac") ? e.metaKey : e.ctrlKey)) {
				                disabledEvent(e);
				                show_toast_message('<?php echo $control_c_disable_message ?>');
				            }
				        <?php } ?>
			        }, false);

			        function disabledEvent(e) {
			            if (e.stopPropagation) {
			                e.stopPropagation();
			            } else if (window.event) {
			                window.event.cancelBubble = true;
			            }
			            e.preventDefault();
			            return false;
			        }
			    };

			    jQuery("#show_message").css("background", "<?php echo $disable_message_backgound ?>");

			    <?php if ($toast_horizontal_positon == 'l'): ?>
			    	jQuery("#show_message").css("left", "15%");
			    <?php endif ?>

			    <?php if ($toast_horizontal_positon == 'c'): ?>
			    	jQuery("#show_message").css("left", "50%");
			    <?php endif ?>

			    <?php if ($toast_horizontal_positon == 'r'): ?>
			    	jQuery("#show_message").css("left", "85%");
			    <?php endif ?>

			    <?php if ($toast_vertical_positon == 't'): ?>
			    	jQuery("#show_message").css("bottom", "80%");
			    <?php endif ?>

			    <?php if ($toast_vertical_positon == 'c'): ?>
			    	jQuery("#show_message").css("bottom", "50%");
			    <?php endif ?>

			    <?php if ($toast_vertical_positon == 'b'): ?>
			    	jQuery("#show_message").css("bottom", "5%");
			    <?php endif ?>


			});
		</script>

		<?php 
	}

	public function hide_admin_bar(){ 
		return false;
	}

	public function wpd_disable_feed() {
		wp_die( __('No feed available,please visit our <a href="'. get_bloginfo('url') .'">homepage</a>!') );
	}

	function wpdisabler_remove_default_endpoints( $endpoints ) {
	  return array( );
	}

	function wpdisabler_remove_default_endpoints_smarter( $endpoints ) {
		$prefix = 'your_custom_endpoint_prefix';
	 
		foreach ( $endpoints as $endpoint => $details ) {
			if ( !fnmatch( '/' . $prefix . '/*', $endpoint, FNM_CASEFOLD ) ) {
		  		unset( $endpoints[$endpoint] );
			}
	    }
	  	return $endpoints;
	}
		


}
