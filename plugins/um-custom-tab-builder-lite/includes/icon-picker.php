<?php
/**
 * Class CMB2_Icon_Picker
 */
class CMB2_Icon_Picker {

	/**
	 * Current version number
	 */
	const VERSION = '1.0.0';

	/**
	 * @var CMB2_Icon_Picker
	 */
	protected static $single_instance = null;

	/**
	 * $default_icons holds all default icons
	 * @access public
	 * @since  0.1.0
	 * @var array
	 */
	var $default_icons = array( 'dashicons-menu','dashicons-dashboard','dashicons-admin-site','dashicons-admin-media','dashicons-admin-page','dashicons-admin-comments','dashicons-admin-appearance','dashicons-admin-plugins','dashicons-admin-users','dashicons-admin-tools','dashicons-admin-settings','dashicons-admin-network','dashicons-admin-generic','dashicons-admin-home','dashicons-admin-collapse','dashicons-admin-links','dashicons-admin-post','dashicons-format-standard','dashicons-format-image','dashicons-format-gallery','dashicons-format-audio','dashicons-format-video','dashicons-format-links','dashicons-format-chat','dashicons-format-status','dashicons-format-aside','dashicons-format-quote','dashicons-welcome-write-blog','dashicons-welcome-edit-page','dashicons-welcome-add-page','dashicons-welcome-view-site','dashicons-welcome-widgets-menus','dashicons-welcome-comments','dashicons-welcome-learn-more','dashicons-image-crop','dashicons-image-rotate-left','dashicons-image-rotate-right','dashicons-image-flip-vertical','dashicons-image-flip-horizontal','dashicons-undo','dashicons-redo','dashicons-editor-bold','dashicons-editor-italic','dashicons-editor-ul','dashicons-editor-ol','dashicons-editor-quote','dashicons-editor-alignleft','dashicons-editor-aligncenter','dashicons-editor-alignright','dashicons-editor-insertmore','dashicons-editor-spellcheck','dashicons-editor-distractionfree','dashicons-editor-expand','dashicons-editor-contract','dashicons-editor-kitchensink','dashicons-editor-underline','dashicons-editor-justify','dashicons-editor-textcolor','dashicons-editor-paste-word','dashicons-editor-paste-text','dashicons-editor-removeformatting','dashicons-editor-video','dashicons-editor-customchar','dashicons-editor-outdent','dashicons-editor-indent','dashicons-editor-help','dashicons-editor-strikethrough','dashicons-editor-unlink','dashicons-editor-rtl','dashicons-editor-break','dashicons-editor-code','dashicons-editor-paragraph','dashicons-align-left','dashicons-align-right','dashicons-align-center','dashicons-align-none','dashicons-lock','dashicons-calendar','dashicons-visibility','dashicons-post-status','dashicons-edit','dashicons-post-trash','dashicons-trash','dashicons-external','dashicons-arrow-up','dashicons-arrow-down','dashicons-arrow-left','dashicons-arrow-right','dashicons-arrow-up-alt','dashicons-arrow-down-alt','dashicons-arrow-left-alt','dashicons-arrow-right-alt','dashicons-arrow-up-alt2','dashicons-arrow-down-alt2','dashicons-arrow-left-alt2','dashicons-arrow-right-alt2','dashicons-leftright','dashicons-sort','dashicons-randomize','dashicons-list-view','dashicons-exerpt-view','dashicons-hammer','dashicons-art','dashicons-migrate','dashicons-performance','dashicons-universal-access','dashicons-universal-access-alt','dashicons-tickets','dashicons-nametag','dashicons-clipboard','dashicons-heart','dashicons-megaphone','dashicons-schedule','dashicons-wordpress','dashicons-wordpress-alt','dashicons-pressthis','dashicons-update','dashicons-screenoptions','dashicons-info','dashicons-cart','dashicons-feedback','dashicons-cloud','dashicons-translation','dashicons-tag','dashicons-category','dashicons-archive','dashicons-tagcloud','dashicons-text','dashicons-media-archive','dashicons-media-audio','dashicons-media-code','dashicons-media-default','dashicons-media-document','dashicons-media-interactive','dashicons-media-spreadsheet','dashicons-media-text','dashicons-media-video','dashicons-playlist-audio','dashicons-playlist-video','dashicons-yes','dashicons-no','dashicons-no-alt','dashicons-plus','dashicons-plus-alt','dashicons-minus','dashicons-dismiss','dashicons-marker','dashicons-star-filled','dashicons-star-half','dashicons-star-empty','dashicons-flag','dashicons-share','dashicons-share1','dashicons-share-alt','dashicons-share-alt2','dashicons-twitter','dashicons-rss','dashicons-email','dashicons-email-alt','dashicons-facebook','dashicons-facebook-alt','dashicons-networking','dashicons-googleplus','dashicons-location','dashicons-location-alt','dashicons-camera','dashicons-images-alt','dashicons-images-alt2','dashicons-video-alt','dashicons-video-alt2','dashicons-video-alt3','dashicons-vault','dashicons-shield','dashicons-shield-alt','dashicons-sos','dashicons-search','dashicons-slides','dashicons-analytics','dashicons-chart-pie','dashicons-chart-bar','dashicons-chart-line','dashicons-chart-area','dashicons-groups','dashicons-businessman','dashicons-id','dashicons-id-alt','dashicons-products','dashicons-awards','dashicons-forms','dashicons-testimonial','dashicons-portfolio','dashicons-book','dashicons-book-alt','dashicons-download','dashicons-upload','dashicons-backup','dashicons-clock','dashicons-lightbulb','dashicons-microphone','dashicons-desktop','dashicons-tablet','dashicons-smartphone','dashicons-smiley'
	);	

	/**
	 * $font_family holds all default icons
	 * @access public
	 * @since  0.1.0
	 * @var array
	 */
	var $font_family = array('dashicons');

	/**
	 * Creates or returns an instance of this class.
	 * @since  0.1.0
	 * @return CMB2_Icon_Picker A single instance of this class.
	 */
	public static function get_instance() {
		if ( null === self::$single_instance ) {
			self::$single_instance = new self();
		}

		return self::$single_instance;
	}

	/**
	 * Initialize the plugin by hooking into CMB2
	 */
	protected function __construct() {
		add_action( 'cmb2_render_icon_picker', array( $this, 'render' ), 10, 5 );
	}

	public function render( $field, $escaped_value, $object_id, $object_type, $field_type ) {
		
		$custom_fonts = $field->options( 'fonts' );

		if (!empty($custom_fonts) && is_array($custom_fonts)) {
			$this->font_family = $custom_fonts;
		}
		
		$custom_icons = $field->options( 'icons' );

		if (!empty($custom_icons)) {
			$icons = is_array($custom_icons) ? $custom_icons : $this->default_icons;
		} else {
			$icons = $this->default_icons;
		}

		echo '<ul class="cmb2-icon-picker-list">';

		foreach ($icons as $icon) {
			
			$args = array(
				'type' => 'radio', 
				'name' => $field_type->_name(), 
				'id' => $field_type->_id($icon), 
				'value' => $icon,
				'desc' => '',
			);

			if ( $field->options( 'multicheck' ) ) { 
				$args['type'] = 'checkbox';
				$args['name'] = $field_type->_name('[]');
				if ( is_array( $escaped_value ) && in_array( $icon, $escaped_value ) ) {
					$args['checked'] = 'checked';
				}
			} else {
				if (isset($escaped_value) && $escaped_value === $icon) {
					$args['checked'] = 'checked';
				}				
			}

			echo '<li class="cmb2-icon-picker-list-item">';

	    	echo $field_type->input($args);

	   		echo '<label for="' . $field_type->_id($icon) . '"><span class="cmb2-icon-picker-icon '.$icon.'" style="font-family: \'' .  implode('\',\'', $this->font_family) . '\' !important;"></span></label>';

			echo '</li>';
		}

		echo '</ul>';

		// Display our description if one exists
		$field_type->_desc( true, true );		
	}
}

CMB2_Icon_Picker::get_instance();