<?php
/**
 Smartlib User Utils
 */
class Smartlib_User_Utils{

	public $user_social_fields = array();
	public $oPlugin;


	public function __construct(){

		$this->set_user_social_fields();//set user fields array

		add_filter('user_contactmethods', array($this,'extend_contact_link'));
		add_action( 'show_user_profile', array($this,'image_profile_field'));
		add_action( 'edit_user_profile', array($this,'image_profile_field'));
		add_action( 'personal_options_update',array($this,'image_profile_save') );
		add_action( 'edit_user_profile_update', array($this,'image_profile_save'));

		/*Load external script*/
		add_action( 'admin_enqueue_scripts', array($this,'extend_admin_area_enqueue_scripts'));

		//add avatar filter
		add_filter('get_avatar', array($this,'smartlib_userphoto_filter'), 1, 5);
	}

	/*
			 * Set user social array method
			 */
	public function set_user_social_fields(){
		$this->user_social_fields['twitter'] =  __('Twitter Username', 'harmonux');
		$this->user_social_fields['facebook'] = __('Facebook URL', 'harmonux');
		$this->user_social_fields['gplus'] =__('Google+ URL', 'harmonux');
		$this->user_social_fields['pinterest'] =__('Pinterest URL', 'harmonux');
		$this->user_social_fields['linkedin'] =__('LinkedIn URL', 'harmonux');
		$this->user_social_fields['youtube'] =__('YouTube URL', 'harmonux');
	}


	/*EXTEND USER PROFILE*/
	function extend_contact_link($profile_fields) {

		return $this->user_social_fields;
	}

	/**
	 * Display image user profile field
	 *
	 * @param $user
	 */
	function image_profile_field( $user ) {
		if(current_user_can('upload_files')){
			$user_image = get_the_author_meta( 'smartlib_profile_image', $user->ID );
			?>

		<h3><?php _e("User profile picture", 'harmonux') ?></h3>

		<table class="form-table">

			<tr>
				<th><label for="smartlib_profile_image"><?php _e("Image", 'harmonux') ?></label></th>

				<td>
					<div class="smartlib-image smartlib-user-image-container"><?php echo !empty($user_image)? '<img src="'.$user_image.'"  alt="User Image" style="max-width: 300px" />' :'<img src="#" style="width: 0;height: 0" alt="User Image" />'  ?></div>
					<input type="text" name="smartlib_profile_image" id="smartlib_profile_image" value="<?php echo $user_image; ?>" class="regular-text" /><br />
					<a href="#" class="button smartlib-upload-user-photo-btn"><?php _e("Upload user photo", 'harmonux') ?></a>
					<span class="description"><?php _e(" or You can paste external URL", 'harmonux'); ?></span>
				</td>
			</tr>

		</table>
		<?php
		}
	}

	/**
	 * Save user profile image
	 * @param $user_id
	 *
	 * @return bool
	 */
	function image_profile_save( $user_id ) {

		if ( !current_user_can( 'edit_user', $user_id ) )
			return false;


		update_user_meta( $user_id, 'smartlib_profile_image', $_POST['smartlib_profile_image'] );
	}

	/**
	 * Enqueue admin script
	 */
	function extend_admin_area_enqueue_scripts() {
		if(current_user_can('upload_files')){
			wp_enqueue_media(); //add uploader files


			//add common script
		wp_enqueue_script( 'extend_admin_area_plugin', SMARTLIB_PLUGIN_URL.'/assets/js/plugin-scripts.js', array( 'jquery' ), '1.0', false );
		}
	}

	/**
	 *
	 * If no Avatar is found use smartlib_profile_image
	 *
	 * @param $avatar
	 * @param $id_or_email
	 * @param $size
	 * @param $default
	 * @param $alt
	 *
	 * @return string
	 */
	function smartlib_userphoto_filter($avatar, $id_or_email, $size, $default, $alt) {

		$avatarfound = '';
		$safe_alt = '';
		$myavatar = '';
		$user = null;
		$custom_avatar = '';



   //CHECK IF IS EMAIL
		if(!is_object($id_or_email)){

			$string_param = (string)$id_or_email;
			if(strpos($string_param, '@')){
				$user = get_user_by( 'email', $id_or_email );
				$id_or_email = $user->ID;
				$custom_avatar = get_the_author_meta('smartlib_profile_image', $id_or_email);
			}else{
				$custom_avatar = get_the_author_meta('smartlib_profile_image', $id_or_email);
			}

		}else{

			if(isset($id_or_email->ID)){
				$custom_avatar = get_the_author_meta('smartlib_profile_image', $id_or_email->ID);
				if(empty($custom_avatar)){
					$custom_avatar = get_user_meta($id_or_email->ID,'smartlib_profile_image',true);
				}
			}
		}


    if(strlen($custom_avatar)>0)
		$avatar = "<div class='smartlib-author-avatar'><img alt='{$safe_alt}' src='{$custom_avatar}' class='avatar avatar-{$size} photo' height='{$size}' width='{$size}' /></div>";

		return $avatar;

	}


	function extened_user_social_fields(){
		$field_width_values = array();


		foreach($this->user_social_fields as $key =>$row){
			$value = get_the_author_meta($key);

			$rel = '';
			if(!empty($value)){
				if($key=='gplus'){ //check author rel (google headshot)
					$parse_array =  parse_url($value);

					if(isset($parse_array['query'])){
						parse_str($parse_array['query'], $output);

						if(!isset($output['rel']))
							$rel = '?rel=author';
					}else{
						$rel = '?rel=author';
					}
				}
				$field_width_values[$key] = $value.$rel;
			}
		}
		return $field_width_values;
	}


}