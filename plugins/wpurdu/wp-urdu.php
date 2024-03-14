<?php
/**
 * Plugin Name: WPUrdu (اردو)- Typing Urdu in WordPress
 * Plugin URI:  https://wordpress.org/plugins/wpurdu
 * Description: Possibility of Urdu Typing in WordPress. Urdu -> اردو It will enable you to write Urdu latin language content in Classic Editors
 * Version:     1.0.5
 * Author:      Hassan Ali
 * Author URI:  https://hassanali.pro
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: wpurdu
 */

if ( ! class_exists( 'wpurdu' ) ) {
    class wpurdu {
		var $plugin_name = "";
        public function __construct() {
			$this->plugin_name = "wpurdu";

			// Add Btn after 'Media'
			add_action( 'media_buttons', array($this, 'add_urdu_media_button'), 10);

			// enqueue for the front end styles and javascript
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts_style' ), 10 );

			//enqueue for the admin section styles and javascript
			add_action('admin_enqueue_scripts', array( $this, 'admin_style_scripts' ));

			//enable the second row in tiny mce and add font-size and font-family section
			add_filter( 'mce_buttons_2', array( $this,'wpurdu_mce_editor_buttons') );

			//adding the list of fonts for Urdu Language
			add_filter( 'tiny_mce_before_init', array( $this,'wpurdu_mce_before_init') );

			//adding the stylesheet to enable font in editor
			add_filter( 'init', array( $this,'wpurdu_add_editor_styles') );

			//save the WP Urdu status in metadata
			add_action( 'save_post', array( $this, 'wpurdu_save_status'), 1, 2 );

			//language support
			add_action( 'plugins_loaded', array( $this, 'wpurdu_plugin_textdomain' ));
        }

		function wpurdu_plugin_textdomain(){
			$plugin_rel_path = basename( dirname( __FILE__ ) ) . '/languages';
			load_plugin_textdomain( 'wpurdu', false, $plugin_rel_path );
		}

		/**
		 * Add the style sheet in editor.
		 */
		public function wpurdu_add_editor_styles() {
		    add_editor_style( plugin_dir_url( __FILE__ ) . "assets/css/editor-control.css" );
		}

		/**
		 * Add the "font-family and font-size" section in editor.
		 */

		public function wpurdu_mce_editor_buttons( $buttons ) {

		    array_unshift( $buttons, 'fontselect' );
		    array_unshift( $buttons, 'fontsizeselect' );
		    return $buttons;
		}


		/**
		 * Add fonts to the "Font Family" drop-down.
		 */
		public function wpurdu_mce_before_init( $settings ) {

		    $font_formats = "Nafees Nastaleeq  اُردُو ='Nafees Nastaleeq',sans-serif;"
		    					. 'Arial Black=arial black,avant garde;'
		    					. 'Book Antiqua=book antiqua,palatino;'
		    					. 'Comic Sans MS=comic sans ms,sans-serif;'
		    					. 'Courier New=courier new,courier;'
		    					. 'Georgia=georgia,palatino;'
		    					. 'Helvetica=helvetica;'
		    					. 'Impact=impact,chicago;'
		    					. 'Symbol=symbol;'
		    					. 'Tahoma=tahoma,arial,helvetica,sans-serif;'
		    					. 'Terminal=terminal,monaco;'
		    					. 'Times New Roman=times new roman,times;'
		    					. 'Trebuchet MS=trebuchet ms,geneva;'
		    					. 'Verdana=verdana,geneva;';
		    $settings[ 'font_formats' ] = $font_formats;
		    $settings[ 'fontsize_formats' ] = "9px 10px 12px 13px 14px 16px 18px 21px 24px 28px 32px 36px 40px 42px 44px";

		    return $settings;

		}

		/**
		 * Add media button for enable and disable Wp Urdu option.
		 */
		function add_urdu_media_button() {
			global $post;
			$post_id = $post->ID;
			$wpurdu_status = get_post_meta( $post_id, 'wpurdu_save_status', true );
			if($wpurdu_status == "yes"){ ?>
				<a href = "#" class = "button media-button-wpurdu enabled active" title = "<?php echo __('Disable WPUrdu', $this->plugin_name); ?>">
					<img src="<?php echo plugin_dir_url( __FILE__ ) . 'assets/images/urdu.png'; ?>" alt="urdu" /><strong><?php echo __('Disable WPUrdu', $this->plugin_name); ?></strong>
					<input type="hidden" name="wpurdu_save_status" value="yes" class="wpurdu_save_status" />
				</a>
			<?php } else { ?>
				<a href = "#" class = "button media-button-wpurdu enabled" title = "<?php echo __('Enable WPUrdu', $this->plugin_name); ?>">
					<img src="<?php echo plugin_dir_url( __FILE__ ) . 'assets/images/urdu.png'; ?>" alt="urdu" /><strong><?php echo __('Enable WPUrdu', $this->plugin_name); ?></strong>
					<input type="hidden" name="wpurdu_save_status" value="no" class="wpurdu_save_status" />
				</a>
			<?php
			}
		}

		/**
		 * Load frontend CSS & JS.
		 */
		public function enqueue_scripts_style(){
			//wp_enqueue_script('wpurdu', plugin_dir_url( __FILE__ ) . 'assets/js/wpurdu.js');

			wp_enqueue_style( 'wpurdu', plugin_dir_url( __FILE__ ) . 'assets/css/wpurdu.css' );

			wp_enqueue_style( 'wpurdu-editor', plugin_dir_url( __FILE__ ) . "assets/css/editor-control.css" );
		}

		/**
		 * Load Backend Admin CSS & JS.
		 */
		public function admin_style_scripts( $page ) {
			if ( $page == 'post-new.php' || $page == 'post.php' ) {
				wp_enqueue_script('wpurdu-translate-api', plugin_dir_url( __FILE__ ) . 'assets/js/translate-api.js', null, '1.0.0', true);

				wp_enqueue_script('wpurdu-admin', plugin_dir_url( __FILE__ ) . 'assets/js/wpurdu-admin.js', 'wpurdu-translate-api', '1.0.0', true);
				wp_localize_script( 'wpurdu-admin', 'button_text',
					array( 'enable' => __('Enable WPUrdu', $this->plugin_name), 'disable' => __('Disable WPUrdu', $this->plugin_name) )
				);

				wp_enqueue_script('wpurdu-block', plugin_dir_url(__FILE__).'assets/js/block.js', array('wpurdu-translate-api', 'wp-blocks', 'wp-element', 'wp-editor', 'wp-i18n', 'wp-edit-post'), '1.0.0', true );

				wp_enqueue_style( 'wpurdu-translate', plugin_dir_url( __FILE__ ) . 'assets/css/translate.css' );

				wp_enqueue_style('wpurdu-admin', plugin_dir_url( __FILE__ ) . 'assets/css/wpurdu-admin.css');

				wp_enqueue_style( 'wpurdu-editor', plugin_dir_url( __FILE__ ) . "assets/css/editor-control.css" );

				wp_enqueue_script( 'wpurdu-block', plugin_dir_url(__DIR__).'assets/js/api.js', null, '1.0.0', true );
			}
		}

		/**
		 * Save the wp urdu option for post | page.
		 */
		function wpurdu_save_status($post_id, $post){
			if(isset($_POST['wpurdu_save_status'])){
				$wpurdu_status = sanitize_text_field($_POST['wpurdu_save_status']);
				update_post_meta( $post_id, 'wpurdu_save_status', $wpurdu_status );
			}
		}

    }
	$wpurdu = new wpurdu();
}
