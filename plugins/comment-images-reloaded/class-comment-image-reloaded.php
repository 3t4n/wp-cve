<?php


require_once(ABSPATH . 'wp-admin/includes/media.php');
require_once(ABSPATH . 'wp-admin/includes/file.php');
require_once(ABSPATH . 'wp-admin/includes/image.php');






class Comment_Image_Reloaded {

	/*--------------------------------------------*
	 * Constructor
	 *--------------------------------------------*/

	/**
	 * Whether or not the image needs to be approved before displaying
	 * it to the user.
	 *
	 * @since    1.17.0
	 * @access   private
	 * @var      bool
	 */
	private $needs_to_approve;
	
	/**
	 * Instance of this class.
	 *
	 * @since    1.0.0
	 *
	 * @var      object
	 */
	protected static $instance = null;

	/**
	 * Plugn option 
	 *
	 * @since    2.0.0
	 * @access   private
	 * @var      array
	 */
	private static $options = array();

	/**
	 * The maximum number of images to upload at time
	 *
	 * @since    1.17.0
	 * @access   private
	 * @var      int
	 */



	private  $limit_files_count;

	/**
	 * The maximum size of the file in bytes.
	 *
	 * @since    1.17.0
	 * @access   private
	 * @var      int
	 */
    private $limit_file_size;

	public $cir_front;

	/**
	 * Return an instance of this class.
	 *
	 * @since     1.0.0
	 *
	 * @return    object    A single instance of this class.
	 *
	 */
	public static function get_instance() {

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		} // end if

		return self::$instance;

	} // end get_instance


	/**
	 * Initializes the plugin by setting localization, admin styles, and content filters.
	 */
	private function __construct() {
		// get plugin options
		self::$options = get_option( 'CI_reloaded_settings' );




		// Load plugin textdomain
		add_action( 'init', array( $this, 'plugin_textdomain' ) );


		// Determine if the hosting environment can save files.
		if( $this->can_save_files() ) {

			//set filesize limit
			$this->limit_file_size = $this->set_limit_filesize();

			$this->needs_to_approve = FALSE;

			$this->limit_files_count = !empty(self::$options['max_img_count']) ? self::$options['max_img_count'] : 5;

			// Go ahead and enable comment images site wide
			add_option( 'comment_image_reloaded_toggle_state', 'enabled' );

			//CIR VARIOUS FUNCTIONS
			require_once (plugin_dir_path(__FILE__).'functions/cir-functions.php');
			$functions = new CIR_Functions();
			// check html5 comments support
			add_action( 'after_setup_theme', array( $functions, 'support_comment_list' ), 9999 );

			//Delete Comment

			require_once (plugin_dir_path(__FILE__).'functions/delete-comment.php');
			$delete_comment = new CIR_Delete_Comment();
			add_action( 'wp_ajax_cir_delete_image', array( $delete_comment, 'cir_delete_image') );

			// clean commentmeta when comments or media image deleted
			add_filter( 'delete_comment', array( $delete_comment, 'clear_commentmeta_ondelete_comment' ) );
			add_filter( 'delete_attachment', array( $delete_comment, 'clear_commentmeta_ondelete_attachment' ) );

			// Add comment related stylesheets and JavaScript
			add_action( 'wp_enqueue_scripts', array( $this, 'add_scripts' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'add_admin_scripts' ) );

			/**************************************/



			//FRONT **********************************************************************************/
			require_once (plugin_dir_path(__FILE__).'front/front-functions.php');
			$front = new CIR_Front(self::$options,$this->limit_file_size,$this->limit_files_count,$this->needs_to_approve);
			$this->cir_front = $front;
			// Add the Upload input to the comment form
            $autofield = ( isset(self::$options['auto_echo']) && 'disable' == self::$options['auto_echo'] ) ? false : true; 
			if ( $autofield ) {
				add_action( 'comment_form' , array( $front, 'add_image_upload_form' ) );
			}

			add_filter( 'wp_insert_comment', array( $front, 'save_comment_image' ) );
			add_filter( 'comments_array', array( $front, 'display_comment_image' ), 10, 2 );

			add_action( 'wp_head', array( $front, 'add_authorslink_style' ) );

			// END FRONT ******************************************************************************



			//Back-end side of the Wordpress
			if(is_admin()) {

				//*********Admin Option page*******************************************************/
				require_once (plugin_dir_path(__FILE__) . 'admin/options.php');
				$option_page = new CIR_Options(self::$options);
				add_action( 'admin_init', array( $option_page, 'CI_reloaded_settings_init' ) );
				add_action( 'admin_menu', array( $option_page, 'CI_reloaded_add_admin_menu' ) );

				//*************** Setup the Project Completion metabox*****************************/
				require_once (plugin_dir_path(__FILE__).'admin/meta-box.php');
				$metabox = new CIR_MetaBox();
				add_action( 'add_meta_boxes', array( $metabox, 'add_comment_image_meta_box' ) );
				add_action( 'save_post', array( $metabox, 'save_comment_image_display' ) );

				// Add a note to recent comments that they have Comment Images
				add_filter( 'comment_row_actions', array( $metabox, 'recent_comment_has_image' ), 20, 2 );

				// Add a column to the comment images if there is an image for the given comment
				add_filter( 'manage_edit-comments_columns', array( $metabox, 'comment_has_image' ) );
				add_filter( 'manage_comments_custom_column', array( $metabox, 'comment_image' ), 20, 2 );

				// END METABOX *******************************************************************************/

				//Convert Images from other plugins
				require_once (plugin_dir_path(__FILE__) . 'functions/Importer-class.php');
				$importer = new CIR_Importer();
				add_action( 'wp_ajax_convert_CI', array( $importer, 'convert_CI_images') ); // Import from Comment Images
				add_action( 'wp_ajax_convert_CA', array( $importer, 'convert_CA_images') ); // Import from Comment Attachment


			}

			/********************************************************************************/



		} else {

			// If not, display a notice.
			add_action( 'admin_notices', array( $this, 'save_error_notice' ) );

		} // end if/else

	} // end constructor


	private function set_limit_filesize(){
		// set maximum allowed file size get php.ini settings / CIR option / default 5MB
		$phpini_limit = self::getMaxFilesize(); // in bytes
		$opt = ( isset(self::$options['max_filesize']) ) ? self::$options['max_filesize'] : 5; // in MBytes
		$limit = min( $phpini_limit, self::MBtoB($opt) ); // set limit
		return $limit;
	}



	 /**
	  * Loads the plugin text domain for translation
	  */
	 function plugin_textdomain() {
		 load_plugin_textdomain( 'comment-images-reloaded', false, dirname( plugin_basename( __FILE__ ) ) . '/lang' );
	 } // end plugin_textdomain

	 /**
	  * In previous versions of the plugin, the image were written out after the comments. Now,
	  * they are actually part of the comment content so we need to update all old options.
	  *
	  * Note that this option is not removed on deactivation because it will run *again* if the
	  * user ever re-activates it this duplicating the image.
	  */
	





	 /**
	  * Display a WordPress error to the administrator if the hosting environment does not support 'file_get_contents.'
	  */
	 function save_error_notice() {

		 $html = '<div id="comment-image-notice" class="error">';
		 	$html .= '<p>';
		 		$html .= __( '<strong>Comment Images Notice:</strong> Unfortunately, your host does not allow uploads from the comment form. This plugin will not work for your host.', 'comment-images-reloaded' );
		 	$html .= '</p>';
		 $html .= '</div><!-- /#comment-image-notice -->';

		 echo $html;

	 } // end save_error_notice

	


	/**
	 * Adds the public JavaScript to the single post page.
	 */
	function add_scripts() {

		global $wp_scripts;

		if ( is_single() || is_page() ) {

			$jsfile = 'js/cir.min.js';
			if ( isset(self::$options['image_zoom']) && 'enable' == self::$options['image_zoom'] ) {

				$jsfile = 'js/cir_andzoom.min.js';
				// check jQuery version, magnific required jQuery 1.7.2+
        		if ( ( version_compare( '1.7.2', $wp_scripts->registered['jquery']->ver ) == 1 ) && !is_admin() ) {
                	wp_deregister_script('jquery'); 
					wp_register_script('jquery', 'http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js', false, '1.7.2' );
        		}
				
				wp_enqueue_style( 'magnific', plugins_url( 'js/magnific.css', __FILE__ ) );

			}

			wp_register_script( 'comment-images-reloaded', plugins_url( $jsfile, __FILE__ ), array( 'jquery' ), false, true );
            wp_localize_script(
            	'comment-images-reloaded',
            	'cm_imgs',
            	array(
                	'fileTypeError' => __( '<strong>Heads up!</strong> You are attempting to upload an invalid image. If saved, this image will not display with your comment.', 'comment-images-reloaded' ),
					'fileSizeError' => __( '<strong>Heads up!</strong> You are attempting to upload an image that is too large. If saved, this image will not be uploaded.<br />The maximum file size is: ', 'comment-images-reloaded' ),
					'limitFileSize' => $this->limit_file_size,
					'fileCountError'=> __( '<strong>Heads up!</strong> You are attempting to upload too many images. If saved, this images will not be uploaded.<br />The maximum number of images is: ', 'comment-images-reloaded' ),
					'limitFileCount'=>$this->limit_files_count,
				)
			);
			wp_enqueue_script( 'comment-images-reloaded' );

		} // end if

	} // end add_scripts


	/**
	 * Adds the public JavaScript to the single post editor
	 */
	function add_admin_scripts() {

		wp_register_script( 'comment-images-reloaded-ajax', plugins_url( 'js/admin-ajax.min.js', __FILE__ ), array( 'jquery' ) );
		wp_localize_script( 
			'comment-images-reloaded-ajax', 
			'cmr_reloaded_ajax_object', 
			array(
				'ajax_url' => admin_url( 'admin-ajax.php' ),
				'before_delete_text' => __( 'Do you want to permanently delete an image attached to this comment?', 'comment-images-reloaded' ),
				'after_delete_text' => __( 'Image deleted!', 'comment-images-reloaded' ),
			) 
		);
		wp_enqueue_script( 'comment-images-reloaded-ajax' );

		$screen = get_current_screen();
		if( 'post' === $screen->id || 'page' == $screen->id ) {

			wp_register_script( 'comment-images-reloaded-admin', plugins_url( 'js/admin.min.js', __FILE__ ), array( 'jquery' ) );
			
            wp_localize_script(
            	'comment-images-reloaded-admin',
            	'cm_imgs',
            	array(
                	'toggleConfirm' => __( 'By doing this, you will toggle Comment Images for all posts on your blog. Are you sure you want to do this?', 'comment-images-reloaded' )
				)
			);

			wp_enqueue_script( 'comment-images-reloaded-admin' );

		} // end if

	} // end add_admin_scripts



	/**
	 * Determines if the hosting environment allows the users to upload files.
	 *
	 * @return			Whether or not the hosting environment supports the ability to upload files.
	 */
	private function can_save_files() {
		return function_exists( 'file_get_contents' );
	} // end can_save_files


	//
	//
	//

	//
	// get max filesize (in bytes) allowed in php.ini
	//
	public static function getMaxFilesize() {

		static $max_size = -1;

		if ($max_size < 0) {
			// Start with post_max_size.
			$max_size = self::parse_size(ini_get('post_max_size'));

			// If upload_max_size is less, then reduce. Except if upload_max_size is
			// zero, which indicates no limit.
			$upload_max = self::parse_size( ini_get('upload_max_filesize') );
			if ($upload_max > 0 && $upload_max < $max_size) {
				$max_size = $upload_max;
			}
		}

		return $max_size;

	}



	/* ==================================================================================== */
	// filesize & php.ini
	/* ==================================================================================== */
	public static function BtoMB( $bytes ) {
		return round( $bytes / 1048576 , 2 );
	}
	public static function MBtoB( $MB ) {
		return round( $MB * 1048576 );
	}
	public static function parse_size($size) {
		$unit = preg_replace('/[^bkmgtpezy]/i', '', $size); // Remove the non-unit characters from the size.
		$size = preg_replace('/[^0-9\.]/', '', $size); // Remove the non-numeric characters from the size.
		if ($unit) {
			// Find the position of the unit in the ordered string which is the power of magnitude to multiply a kilobyte by.
			return round($size * pow(1024, stripos('bkmgtpezy', $unit[0])));
		}
		else {
			return round($size);
		}
	}


} // end class
	

