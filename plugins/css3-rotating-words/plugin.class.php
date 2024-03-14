<?php 
	/**
	* Plugin Main Class
	*/
	class LA_Words_Rotator
	{
		
		function __construct()
		{
			add_action( "admin_menu", array($this,'rotating_words_admin_options'));
			add_action( 'admin_enqueue_scripts', array($this,'admin_enqueuing_scripts'));
			add_action('wp_ajax_la_save_words_rotator', array($this, 'save_admin_options'));
			add_shortcode( 'animated-words-rotator', array($this, 'render_words_rotator') );
		}
	

		function rotating_words_admin_options(){
			add_menu_page( 'CSS3 Rotating Words', 'CSS3 Rotating Words', 'manage_options', 'word_rotator', array($this,'rotating_wordpress_admin_menu'), 'dashicons-update-alt');
		}

		function admin_enqueuing_scripts($slug){
			if ($slug == 'toplevel_page_word_rotator') {
				wp_enqueue_media();
				wp_enqueue_style( 'wp-color-picker' );
				wp_enqueue_script( 'rw-fontselector-js', plugins_url( 'admin/jquery.fontselect.min.js' , __FILE__ ), array('jquery') );
				wp_enqueue_script( 'wdo-rotator-admin-js', plugins_url( 'admin/admin.js' , __FILE__ ), array('jquery', 'jquery-ui-accordion', 'wp-color-picker','rw-fontselector-js') );
				wp_enqueue_style( 'rotator-ui-css', plugins_url( 'admin/jquery-ui.min.css' , __FILE__ ));
				wp_enqueue_style( 'rotator-admin-css', plugins_url( 'admin/style.css' , __FILE__ ));
				wp_enqueue_style( 'rotator-bootstrap-css', plugins_url( 'admin/bootstrap-min.css' , __FILE__ ));
				wp_enqueue_style( 'rw-fontselector-css', plugins_url( 'admin/fontselect-default.css' , __FILE__ ));
				wp_localize_script( 'wdo-rotator-admin-js', 'laAjax', array( 
					'url' => admin_url( 'admin-ajax.php' ),
					'nonce' => wp_create_nonce('la-ajax-nonce'),
					'path' => plugin_dir_url( __FILE__ )
				));
			}
		}

		function rotating_wordpress_admin_menu(){
			include "includes/admin-settings.php";
		}

		function save_admin_options(){
			
			if (current_user_can('manage_options') || wp_verify_nonce($_REQUEST['nonce'], 'la-ajax-nonce')) {
			        update_option( 'la_words_rotator', $_REQUEST );
			} else {
				exit();
			}
		}
 
		function render_words_rotator($atts, $content, $the_shortcode){
			$savedmeta = get_option( 'la_words_rotator' );
			$postContents = '';
			if (isset($savedmeta['rotwords'])) {
				foreach ($savedmeta['rotwords'] as $key => $data) {
					if ($atts['id']== $data['counter']) {
						wp_enqueue_script( 'rw-text-rotator-js', plugins_url( 'js/jquery.simple-text-rotator.min.js', __FILE__ ),array('jquery'));
						wp_enqueue_script( 'rw-animate-js', plugins_url( 'js/jquery.simple-text-rotator.min.js', __FILE__ ),array('jquery'));
						wp_enqueue_script( 'rw-script-js', plugins_url( 'js/script.js', __FILE__ ),array('jquery'));
						wp_enqueue_style( 'rw-text-rotator-css', plugins_url( 'js/simpletextrotator.css', __FILE__ ));
						wp_enqueue_style( 'rw-animate-css', plugins_url( 'js/animate.min.css', __FILE__ ));
						wp_localize_script( 'rw-script-js', 'words', array(
											'animation' => $data["animation_effect"],
											'speed' => $data['animation_speed'],
											'counter' => $data['counter'],
										));

						$rotate_words = $data['rot_words'];
						$rotate_words_arr = explode(",",$rotate_words);
						ob_start(); ?>
						<div class="rwo-container" data-animation="<?php echo $data['animation_effect']; ?>" data-id="<?php echo $data['counter']; ?>">
							<p class="rotate-words rotating-word<?php echo $data['counter']; ?>">
								<?php echo stripslashes($data["stat_sent"]); ?> 
								<span class="rotate"><?php echo stripslashes($rotate_words); ?></span> 
								<?php echo stripslashes($data['end_sent']); ?>
							</p>
						</div>

					<?php 
						$output = ob_get_clean();
						return $output;
					}	
				}
			}
		}
}
 ?>