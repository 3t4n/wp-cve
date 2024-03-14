<?php

require_once( CIR_PLUGIN_PATH . '/class-comment-image-reloaded.php');

class CIR_Options{

	private static $options = array();


	public function __construct() {
		self::$options = self::$options = get_option( 'CI_reloaded_settings' );
	}


	public static function CI_reloaded_add_admin_menu(  ) {
		add_options_page(
			'Comment Images Reloaded',
			'Comment Images Reloaded',
			'manage_options',
			'comment_images_reloaded',
			array( 'CIR_Options', 'CI_reloaded_options_page')
		);
	}


	//
	//
	//
	public static function CI_reloaded_options_page(  ) {
		echo "<div class='wrap'>
				<div class='updated settings-error notice is-dismissible' style='display:none'>
					<pre class='responce_convert'></pre>
				<div class='notice-dismiss'>
			</div></div>";
		echo "<form action='options.php' method='post'>";
		echo "<h1>Comment Images Reloaded</h1>";
		settings_fields( 'CI_reloaded_settings_page' );
		do_settings_sections( 'CI_reloaded_settings_page' );
		submit_button();
		echo "</form></div>";
	}

	//
	//
	//
	public static function CI_reloaded_settings_init(  ) {

		register_setting( 'CI_reloaded_settings_page', 'CI_reloaded_settings' );

		//
		// import images
		//
		add_settings_section(
			'CIR_import',
			__( 'Import from Comment Images', 'comment-images-reloaded' ),
			array( 'CIR_Options', 'CI_reloaded_settings_section_callback'),
			'CI_reloaded_settings_page'
		);

		add_settings_field(
			'convert_images',
			__( 'Comment Images import', 'comment-images-reloaded' ),
			array( 'CIR_Options', 'CI_reloaded_convert_images'),
			'CI_reloaded_settings_page',
			'CIR_import'
		);

		add_settings_field(
			'convert_CA_images',
			__( 'Comment Attachment import', 'comment-images-reloaded' ),
			array( 'CIR_Options', 'CA_reloaded_convert_images'),
			'CI_reloaded_settings_page',
			'CIR_import'
		);

		//
		// other settings
		//
		add_settings_section(
			'CI_reloaded_checkbox_settings',
			__( 'Settings Comment Images Reloaded', 'comment-images-reloaded' ),
			array( 'CIR_Options', 'CI_reloaded_settings_section_callback'),
			'CI_reloaded_settings_page'
		);

		add_settings_field(
			'image_size',
			__( 'Image size', 'comment-images-reloaded' ),
			array( 'CIR_Options', 'CIR_imagesize_render'),
			'CI_reloaded_settings_page',
			'CI_reloaded_checkbox_settings'
		);

		add_settings_field(
			'max_image_count',
			__( 'Maximum number of images to upload', 'comment-images-reloaded' ),
			array( 'CIR_Options', 'CI_max_img_munber_render'),
			'CI_reloaded_settings_page',
			'CI_reloaded_checkbox_settings'
		);

		add_settings_field(
			'max_filesize',
			__( 'Maximum file size', 'comment-images-reloaded' ),
			array( 'CIR_Options', 'CIR_maxfilesize_render'),
			'CI_reloaded_settings_page',
			'CI_reloaded_checkbox_settings'
		);

		add_settings_field(
			'before_title',
			__( 'Text before input', 'comment-images-reloaded' ),
			array( 'CIR_Options', 'CIR_beforetitle_render'),
			'CI_reloaded_settings_page',
			'CI_reloaded_checkbox_settings'
		);

		add_settings_field(
			'image_zoom',
			__( 'Images zoom', 'comment-images-reloaded' ),
			array( 'CIR_Options', 'CI_imageszoom_render'),
			'CI_reloaded_settings_page',
			'CI_reloaded_checkbox_settings'
		);

		add_settings_field(
			'show_brand_img',
			__("Author's link", 'comment-images-reloaded'),
			array( 'CIR_Options', 'CIR_show_brand_img_render'),
			'CI_reloaded_settings_page',
			'CI_reloaded_checkbox_settings'
		);

		add_settings_field(
			'auto_echo',
			__( 'Upload file input', 'comment-images-reloaded' ),
			array( 'CIR_Options', 'CIR_auto_echo_render'),
			'CI_reloaded_settings_page',
			'CI_reloaded_checkbox_settings'
		);

		add_settings_field(
			'disable_comment_images',
			__( 'Disable for all', 'comment-images-reloaded' ),
			array( 'CIR_Options', 'CI_disableCIR_render'),
			'CI_reloaded_settings_page',
			'CI_reloaded_checkbox_settings'
		);




	}


	//
	// Render convert button
	//
	public static function CI_reloaded_convert_images(){
		$html = '<input type="button" class="button" id="convert_images" data-action="CI" value="' . __( 'Import all images data', 'comment-images-reloaded' ) . '">';
		$html .= '<p class="description">'. __( 'You can import data from original Comment Images plugin. This will not remove original data', 'comment-images-reloaded' ) .'</p>';
		echo $html;
	}

	public static function CA_reloaded_convert_images(){
		$html = '<input type="button" class="button" id="convert_CA_images" data-action="CA" value="' . __( 'Import all images data', 'comment-images-reloaded' ) . '">';
		$html .= '<p class="description">'. __( 'You can import data from Comments Attachment plugin. This will not remove original data ', 'comment-images-reloaded' ) .'</p>';
		echo $html;
	}

	//
	// Render image sizes
	//
	public static function CIR_imagesize_render() {

		$val = ( isset(self::$options['image_size']) ) ? self::$options['image_size'] : 'large';

		$html = '';
		$sizes = get_intermediate_image_sizes();
		$all_sizes = array();

		global $_wp_additional_image_sizes;
		foreach($sizes as $size){
			if($size == 'medium_large') continue;

			if ( in_array( $size, array('thumbnail', 'medium', 'full', 'large') ) ) {
				$all_sizes[$size]['width']  = get_option( "{$size}_size_w" );
				$all_sizes[$size]['height'] = get_option( "{$size}_size_h" );
			} elseif ( isset( $_wp_additional_image_sizes[ $size ] ) ) {
				$all_sizes[$size] = array(
					'width'  => $_wp_additional_image_sizes[ $size ]['width'],
					'height' => $_wp_additional_image_sizes[ $size ]['height'],
				);
			}
			if ( $all_sizes[$size]['height'] != 0 && $all_sizes[$size]['width'] != 0){
				$html .= '<input type="radio" id="radio_'.$size.'" name="CI_reloaded_settings[image_size]" value="'.$size.'"' . checked( $size, $val, false ) . '/>';
				$html .= '<label for="radio_'.$size.'">' . $size . ' ( '.$all_sizes[$size]['width'] . 'x' . $all_sizes[$size]['height'] . ' )</label><br>';
			}
		}


		$html .= '<input type="radio" id="radio_full" name="CI_reloaded_settings[image_size]" value="full"' . checked( 'full', self::$options['image_size'], false ) . '/>';
		$html .= '<label for="radio_full">full ('.__( 'Original size of the image', 'comment-images-reloaded' ).')</label><br>';


		$html .= '<script type="text/javascript">
	    			function my_CI_alert(){
	    				return confirm("'. __( 'Converting all images from Comment Images to Comment Images Reloaded. Disable old plugin to avoid dublicating the images in comments. You can allways revert to old plugin', 'comment-images-reloaded' ).'");
	    				}
	    			function my_CA_alert(){
	    			    return confirm("'. __( 'Converting all images from Comment Attachment to Comment Images Reloaded. Disable old plugin to avoid dublicating the images in comments. You can allways revert to old plugin', 'comment-images-reloaded' ).'");
	    			}
	    		</script>';
		echo $html;

	}


	//
	// Render disable CIR new uploads
	//
	public static function CIR_maxfilesize_render(){

		$phpini_limit = Comment_Image_Reloaded::BtoMB( Comment_Image_Reloaded::getMaxFilesize() );

		$val = ( isset(self::$options['max_filesize']) )
			? min( $phpini_limit, self::$options['max_filesize'] )
			: min( $phpini_limit, 5 );
		echo '<label><input type="text" name="CI_reloaded_settings[max_filesize]" value="'. $val .'" /> MB</label> ';
		echo '<code>'. Comment_Image_Reloaded::MBtoB( $val ) . __( ' bytes', 'comment-images-reloaded' ) . '</code>';
		echo '<p class="description">'. __( 'Maximum allowed all files sizes ', 'comment-images-reloaded' ) . $phpini_limit  . ' MB ('. __( 'php.ini settings', 'comment-images-reloaded' ) .')</p>';

	}


	public static function CI_max_img_munber_render(){

		$phpini_limit = Comment_Image_Reloaded::BtoMB( Comment_Image_Reloaded::getMaxFilesize() );

		if( isset(self::$options['max_img_count']) ) {
			$option = self::$options['max_img_count'];
		} else {
			$option = 5; // default 5 images at time
		}

		$val = ( isset(self::$options['max_filesize']) )
			? min( $phpini_limit, self::$options['max_filesize'] )
			: min( $phpini_limit, 5 );

		$files_max = intval($phpini_limit/$val);
		$option = min($files_max,$option);
		echo '<input type="number" name="CI_reloaded_settings[max_img_count]" class="" value="'. $option .'" />';
		echo '<p class="description">'. __( 'Enter how many images can be uploaded at a time', 'comment-images-reloaded' ) . '</p>';
		echo '<p class="description">'. sprintf(__( 'Maximum allowed number of files of selected size - %s files. php.ini settings', 'comment-images-reloaded' ),$files_max) .'</p>';
	}


	//
	// Render before title
	//
	public static function CIR_beforetitle_render(){

		$phpini_limit = Comment_Image_Reloaded::BtoMB( Comment_Image_Reloaded::getMaxFilesize() );

		$val = ( isset(self::$options['before_title']) )
			? self::$options['before_title']
			: __( 'Select an image for your comment (GIF, PNG, JPG, JPEG):', 'comment-images-reloaded' );

		echo '<input type="text" name="CI_reloaded_settings[before_title]" class="regular-text" value="'. $val .'" />';
		echo '<p class="description">'. __( 'Enter custom title for file input field', 'comment-images-reloaded' ) . '</p>';

	}


	//
	// Render images zoom
	//
	public static function CI_imageszoom_render(){
		$option = '';
		if( isset(self::$options['image_zoom']) ) {
			$option = self::$options['image_zoom'];
		} else {
			$option = 'disable'; // default zoom OFF
		}
		echo '<label><input type="checkbox" name="CI_reloaded_settings[image_zoom]" value="enable" ' .checked( "enable", $option, false ) .' /> ';
		echo __( 'Enable image zoom on click (it work with Magnific Popup jQuery plugin)', 'comment-images-reloaded' ) . '</label>';
	}



	//
	// Render show brand img
	//
	public static function CIR_show_brand_img_render(){
		$option = '';
		if( isset(self::$options['show_brand_img']) ) {
			$option = self::$options['show_brand_img'];
		} else {
			$option = 'enable'; // default link ON
			// $option = 'disable'; // default link OFF
		}
		echo '<label><input type="checkbox" name="CI_reloaded_settings[show_brand_img]" value="disable" ' .checked( "disable", $option, false ) .' /> ';
		echo __( "Check it to hide author's link", 'comment-images-reloaded') . '</label>';
		echo '<p class="description">' . __( 'We place a small link under the image field, letting others know about our plugin. Thanks for your promotion!', 'comment-images-reloaded' ) . '</p>';
	}



	//
	// Render auto echo
	//
	public static function CIR_auto_echo_render(){
		$option = '';
		if( isset(self::$options['auto_echo']) ) {
			$option = self::$options['auto_echo'];
		} else {
			$option = 'enable'; // default ON
		}
		echo '<label><input type="checkbox" name="CI_reloaded_settings[auto_echo]" value="disable" ' .checked( "disable", $option, false ) .' /> ';
		echo __( 'Check it to disable automatic show file upload field', 'comment-images-reloaded' ) . '</label>';
		echo '<p class="description">' . __( 'For manual show input, place code into your template:', 'comment-images-reloaded' )
		     . '<br>' . __( 'echo html', 'comment-images-reloaded' ) . ': <code>&lt;?php if (function_exists("the_cir_upload_field")) { the_cir_upload_field(); } ?&gt;</code>'
		     . '<br>' . __( 'return value', 'comment-images-reloaded' ) . ': <code>&lt;?php if (function_exists("get_cir_upload_field")) { get_cir_upload_field(); } ?&gt;</code></p>';
	}



	//
	// Render disable CIR new uploads
	//
	public static function CI_disableCIR_render(){
		$option = '';
		if( isset(self::$options['disable_comment_images']) ) {
			$option = self::$options['disable_comment_images'];
		} else {
			$option = 'enable'; // default it OFF
		}
		echo '<label><input type="checkbox" name="CI_reloaded_settings[disable_comment_images]" value="disable" ' .checked( "disable", $option, false ) .' /> ';
		echo __( 'Deactivate images for all posts', 'comment-images-reloaded' ) . '</label>';
	}




	//
	//
	//
	public static function CI_reloaded_settings_section_callback(  ) {

	}
}