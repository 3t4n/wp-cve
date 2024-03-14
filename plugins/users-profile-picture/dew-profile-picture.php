<?php
/**
 * Plugin Name: User Profile Picture
 * Description: Use the native WP uploader on your user profile page.
 * Version: 1.0.4
 * Plugin URI: http://dewtechnolab.com/project/user-profile-picture-plugin/
 * Author: Dew Technolab
 * Author URI: http://dewtechnolab.com/
 * Requires at least: 4.5
 * Text Domain: dew-profile-picture
 * Domain Path: /languages
 * License: GPLv3 or later License
 * URI: http://www.gnu.org/licenses/gpl-3.0.html
**/
class Dew_Profile_Picture {
	//private
	private $plugin_url = '';
	private $plugin_dir = '';
	private $plugin_path = '';
	/**
	* __construct()
	* 
	* Class constructor
	*
	*/
	function __construct(){
		//* Localization Code */
		load_plugin_textdomain( 'dew-profile-picture', false, dirname( plugin_basename( __FILE__ ) ));
		$this->plugin_path = plugin_basename( __FILE__ );
		$this->plugin_url = rtrim( plugin_dir_url(__FILE__), '/' );
		$this->plugin_dir = rtrim( plugin_dir_path(__FILE__), '/' );
		add_action( 'show_user_profile', array( $this, 'insert_upload_form' ) );
		add_action( 'edit_user_profile', array( $this, 'insert_upload_form' ) );
		add_action( 'user_new_form', array( $this, 'insert_upload_form' ) );
		//Scripts
		add_action( 'admin_enqueue_scripts', array( $this, 'print_media_scripts' ) );
		//User update action
		add_action( 'edit_user_profile_update', array( $this, 'save_user_profile' ) );
		add_action( 'personal_options_update', array( $this, 'save_user_profile' ) );
		//User Avatar override
		add_filter( 'get_avatar', array( $this, 'avatar_override' ), 10, 6 );
	}
	/* end constructor */

	/**
	* insert_upload_form
	*
	* Adds an upload form to the user profile page and outputs profile image if there is one
	*/
	public function insert_upload_form() {
		$user_id = isset( $_GET[ 'user_id' ] ) ? absint( $_GET[ 'user_id' ] ) : 0;
		if ( $user_id == 0 && defined( 'IS_PROFILE_PAGE' ) && IS_PROFILE_PAGE == true ) {
			$current_user = wp_get_current_user();
			$user_id = $current_user->ID;
		}
		$profile_pic = get_user_meta($user_id, 'profilepicture', true);
		?>
		<table class="form-table fh-profile-upload-options">
			<tr>
				<th>
					<label for="image"><?php _e('Profile Picture', 'dew') ?></label>
				</th>
	 
				<td>
					<input type="button" data-id="dew_image_id" data-src="dew-img" class="button dew-image" name="dew_image" id="dew-image" value="Upload" />
					<input type="hidden" class="button" name="dew_image_id" id="dew_image_id" value="<?php echo !empty($profile_pic) ? esc_url($profile_pic) : ''; ?>" />
					<img id="dew-img" src="<?php echo !empty($profile_pic) ? esc_url($profile_pic) : $this->get_plugin_url( 'img/placeholder.png' ); ?>" style="max-width: 100px; max-height: 100px;"/>
				</td>
			</tr>
		</table><?php
	} //end insert_upload_form

	/**
	* print_media_scripts
	*
	* Output media scripts for thickbox and media uploader
	**/
	public function print_media_scripts() {
		wp_enqueue_media();
		wp_enqueue_script('dew-uploader', $this->get_plugin_url('/js/uploaders.js'), array('jquery'), false, true );
	} //end print_media_scripts

	/**
	* get_plugin_dir()
	* 
	* Returns an absolute path to a plugin item
	*
	* @param		string    $path	Relative path to make absolute (e.g., /css/image.png)
	* @return		string               An absolute path (e.g., /htdocs/ithemes/wp-content/.../css/image.png)
	*/
	public function get_plugin_dir( $path = '' ) {
		$dir = $this->plugin_dir;
		if ( !empty( $path ) && is_string( $path) )
			$dir .= '/' . ltrim( $path, '/' );
		return $dir;		
	} //end get_plugin_dir
	
	
	/**
	* get_plugin_url()
	* 
	* Returns an absolute url to a plugin item
	*
	* @param		string    $path	Relative path to plugin (e.g., /css/image.png)
	* @return		string               An absolute url (e.g., http://www.domain.com/plugin_url/.../css/image.png)
	*/
	public function get_plugin_url( $path = '' ) {
		$dir = $this->plugin_url;
		if ( !empty( $path ) && is_string( $path) )
			$dir .= '/' . ltrim( $path, '/' );
		return $dir;	
	} //get_plugin_url
	
	/**
	* save_user_profile()
	*
	* Saves user profile fields
	* @param int $user_id 
	**/
	public function save_user_profile( $user_id ) {
		if( current_user_can('edit_users') ){
			$profile_pic = empty($_POST['dew_image_id']) ? '' : esc_url($_POST['dew_image_id']);
			update_user_meta($user_id, 'profilepicture', $profile_pic);
		}
	} //end save_user_profile

	/**
	* avatar_override()
	*
	* Overrides an avatar with a profile image
	*
	* @param string $avatar SRC to the avatar
	* @param mixed $id_or_email 
	* @param int $size Size of the image
	* @param string $default URL to the default image
	* @param string $alt Alternative text
	**/
	public function avatar_override( $avatar, $id_or_email, $size, $default, $alt, $args = array() ) {
		global $pagenow;
		if ( 'options-discussion.php' == $pagenow ) return $avatar; //Stop overriding gravatars on options-discussion page
		
		//Get user data
		if ( is_numeric( $id_or_email ) ) {
			$user = get_user_by( 'id', ( int )$id_or_email );
		} elseif( is_object( $id_or_email ) )  {
			$comment = $id_or_email;
			if ( empty( $comment->user_id ) ) {
				$user = get_user_by( 'id', $comment->user_id );
			} else {
				$user = get_user_by( 'email', $comment->comment_author_email );
			}
			if ( !$user ) return $avatar;
		} elseif( is_string( $id_or_email ) ) {
			$user = get_user_by( 'email', $id_or_email );
		} else {
			return $avatar;
		}
		if ( !$user ) return $avatar;
		$user_id = $user->ID;

		//Build classes array based on passed in args, else set defaults - see get_avatar in /wp-includes/pluggable.php
		$classes = array(
			'avatar',
			sprintf( 'avatar-%s', esc_attr( $size ) ),
			'photo'
		);	
		if ( isset( $args[ 'class' ] ) ) {
			if ( is_array( $args['class'] ) ) {
				$classes = array_merge( $classes, $args['class'] );
			} else {
				$args[ 'class' ] = explode( ' ', $args[ 'class' ] );
				$classes = array_merge( $classes, $args[ 'class' ] );
			}
		}

		//Get custom filter classes
		$classes = (array)apply_filters( 'dpp_avatar_classes', $classes );

		//Determine if the user has a profile image
		$custom_avatar = dew_profile_img( $user_id, array( 
			'size' => array( $size, $size ), 
			'attr' => array( 'alt' => $alt, 'class' => implode( ' ', $classes ) ), 
			'echo' => false )
		 );

		if ( ! $custom_avatar ) return $avatar; 
		return $custom_avatar;	
	} //end avatar_override
}
/* end Dew_Profile_Picture */
//instantiate the class
global $dew_pp;
if (class_exists('Dew_Profile_Picture')) {
	if (get_bloginfo('version') >= "4.5") {
		add_action( 'plugins_loaded', 'dew_pp_instantiate' );
	}
}
function dew_pp_instantiate() {
	global $dew_pp;
	$dew_pp = new Dew_Profile_Picture();
}
/**
* dew_profile_img
* 
* Adds a profile image
*
@param $user_id INT - The user ID for the user to retrieve the image for
*/
function dew_profile_img( $user_id, $args = array() ) {

	$defaults = array(
		'size' => 'thumbnail',
		'attr' => '',
		'echo' => true
	);
	$args = wp_parse_args( $args, $defaults );
	extract( $args ); //todo - get rid of evil extract

	$profile = '';
	$profile_pic = get_user_meta($user_id, 'profilepicture', true);
	if ($profile_pic) {
		$profile = '<img alt="" src="'.esc_url($profile_pic).'" class="avatar avatar-'.$size[0].' photo" height="'.$size[0].'" width="'.$size[0].'">';
	}
	if ( $echo ) {
		echo $Profile;
	} else {
		return $profile;
	}
} //end dew_profile_img