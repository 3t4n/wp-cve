<?php
class OTW_Shortcode_Info_Box extends OTW_Shortcodes{
	
	public function __construct(){
		
		$this->has_custom_options = true;
		
		parent::__construct();
	}
	
	
	public function register_external_libs(){
	
		$this->add_external_lib( 'css', 'otw-shortcode-general_foundicons', $this->component_url.'css/general_foundicons.css', 'all', 10 );
		$this->add_external_lib( 'css', 'otw-shortcode-social_foundicons', $this->component_url.'css/social_foundicons.css', 'all', 20 );
		$this->add_external_lib( 'css', 'otw-shortcode', $this->component_url.'css/otw_shortcode.css', 'all', 100 );
	}
	
	
	/**
	 * apply settings
	 */
	public function apply_settings(){
	
		$this->settings = array(
			'border_types' => array(
				''       => $this->get_label( 'none(default)' ),
				'bordered'          => $this->get_label( 'All sides' ),
				'border-top-bottom' => $this->get_label( 'Top and Bottom' ),
				'border-left-right' => $this->get_label( 'Left and Right' )
			),
			'default_border_type' => '',
			
			'border_color_classes' => array(
				''                      => $this->get_label( 'none (Default)' ),
				'otw-red-border'                   => $this->get_label( 'Red' ),
				'otw-orange-border'                => $this->get_label( 'Orange' ),
				'otw-green-border'                 => $this->get_label( 'Green' ),
				'otw-greenish-border'              => $this->get_label( 'Greenish' ),
				'otw-aqua-border'                  => $this->get_label( 'Aqua' ),
				'otw-blue-border'                  => $this->get_label( 'Blue' ),
				'otw-pink-border'                  => $this->get_label( 'Pink' ),
				'otw-purple-border'                  => $this->get_label( 'Purple' ),
				'otw-silver-border'                => $this->get_label( 'Silver' ),
				'otw-brown-border'                 => $this->get_label( 'Brown' ),
				'otw-black-border'                 => $this->get_label( 'Black' ),
				'otw-white-border'                  => $this->get_label( 'White' )
			),
			'default_border_color_class' => '',
			
			'border_styles' => array(
				'bordered'   => $this->get_label( 'solid(default)' ),
				'dashed'     => $this->get_label( 'dashed' ),
				'dotted'     => $this->get_label( 'dotted' )
			),
			'default_border_style' => 'bordered',
			
			'shadows' => array(
				''       => $this->get_label( 'none(default)' ),
				'shadow-inner'      => $this->get_label( 'inner' ),
				'shadow-outer'      => $this->get_label( 'outer' ),
				'shadow-down-left'  => $this->get_label( 'Left and Down' ),
				'shadow-down-right' => $this->get_label( 'Right and Down' )
			),
			'default_shadow' => '',
			
			'rounded_corners' => array(
				''     => $this->get_label( 'none(default)' ),
				'rounded-3'      => $this->get_label( '3px' ),
				'rounded-5'      => $this->get_label( '5px' ),
				'rounded-10'     => $this->get_label( '10px' )
			),
			'default_rounded_corner' => '',
			
			'background_color_classes' => array(
				''                      => $this->get_label( 'none (Default)' ),
				'otw-red'                   => $this->get_label( 'Red' ),
				'otw-orange'                => $this->get_label( 'Orange' ),
				'otw-green'                 => $this->get_label( 'Green' ),
				'otw-greenish'              => $this->get_label( 'Greenish' ),
				'otw-aqua'                  => $this->get_label( 'Aqua' ),
				'otw-blue'                  => $this->get_label( 'Blue' ),
				'otw-pink'                  => $this->get_label( 'Pink' ),
				'otw-silver'                => $this->get_label( 'Silver' ),
				'otw-brown'                 => $this->get_label( 'Brown' ),
				'otw-black'                 => $this->get_label( 'Black' )
			),
			'default_background_color_class' => '',
			
			'background_patterns' => array(
				''               => $this->get_label( 'none(default)' ),
				'otw-pattern-1'      => $this->get_label( 'pattern 1' ),
				'otw-pattern-2'      => $this->get_label( 'pattern 2' ),
				'otw-pattern-3'      => $this->get_label( 'pattern 3' ),
				'otw-pattern-4'      => $this->get_label( 'pattern 4' ),
				'otw-pattern-5'      => $this->get_label( 'pattern 5' )
			),
			'default_background_pattern' => '',
			'icon_types' => array(
				
				''                      => $this->get_label( 'none (Default)' ),
				'general foundicon-settings'      => $this->get_label( 'Settings' ),
				'general foundicon-heart'         => $this->get_label( 'Heart' ),
				'general foundicon-star'          => $this->get_label( 'Star' ),
				'general foundicon-plus'          => $this->get_label( 'Plus' ),
				'general foundicon-minus'         => $this->get_label( 'Minus' ),
				'general foundicon-checkmark'     => $this->get_label( 'Checkmark' ),
				'general foundicon-remove'        => $this->get_label( 'Remove' ),
				'general foundicon-mail'          => $this->get_label( 'Mail' ),
				'general foundicon-calendar'      => $this->get_label( 'Calendar' ),
				'general foundicon-page'          => $this->get_label( 'Page' ),
				'general foundicon-tools'         => $this->get_label( 'Tools' ),
				'general foundicon-globe'         => $this->get_label( 'Globe' ),
				'general foundicon-cloud'         => $this->get_label( 'Cloud' ),
				'general foundicon-error'         => $this->get_label( 'Error' ),
				'general foundicon-right-arrow'   => $this->get_label( 'Right arrow' ),
				'general foundicon-left-arrow'    => $this->get_label( 'Left arrow' ),
				'general foundicon-up-arrow'      => $this->get_label( 'Up arrow' ),
				'general foundicon-down-arrow'    => $this->get_label( 'Down arrow' ),
				'general foundicon-trash'         => $this->get_label( 'Trash' ),
				'general foundicon-add-doc'       => $this->get_label( 'Add Doc' ),
				'general foundicon-edit'          => $this->get_label( 'Edit' ),
				'general foundicon-lock'          => $this->get_label( 'Lock' ),
				'general foundicon-unlock'        => $this->get_label( 'Unlock' ),
				'general foundicon-refresh'       => $this->get_label( 'Refresh' ),
				'general foundicon-paper-clip'    => $this->get_label( 'Paper clip' ),
				'general foundicon-video'         => $this->get_label( 'Video' ),
				'general foundicon-photo'         => $this->get_label( 'Photo' ),
				'general foundicon-graph'         => $this->get_label( 'Graph' ),
				'general foundicon-idea'          => $this->get_label( 'Idea' ),
				'general foundicon-mic'           => $this->get_label( 'Mic' ),
				'general foundicon-cart'          => $this->get_label( 'Cart' ),
				'general foundicon-address-book'  => $this->get_label( 'Address book' ),
				'general foundicon-compass'       => $this->get_label( 'Compass' ),
				'general foundicon-flag'          => $this->get_label( 'Flag' ),
				'general foundicon-location'      => $this->get_label( 'Location' ),
				'general foundicon-clock'         => $this->get_label( 'Clock' ),
				'general foundicon-folder'        => $this->get_label( 'Folder' ),
				'general foundicon-inbox'         => $this->get_label( 'Inbox' ),
				'general foundicon-website'       => $this->get_label( 'Website' ),
				'general foundicon-smiley'        => $this->get_label( 'Smiley' ),
				'general foundicon-search'        => $this->get_label( 'Search' ),
				'general foundicon-phone'         => $this->get_label( 'Phone' ),
				
				'social foundicon-thumb-up'       => $this->get_label( 'Thumb up' ),
				'social foundicon-thumb-down'     => $this->get_label( 'Thumb down' ),
				'social foundicon-rss'            => $this->get_label( 'Rss' ),
				'social foundicon-facebook'       => $this->get_label( 'Facebook' ),
				'social foundicon-twitter'        => $this->get_label( 'Twitter' ),
				'social foundicon-pinterest'      => $this->get_label( 'Pinterest' ),
				'social foundicon-github'         => $this->get_label( 'Github' ),
				'social foundicon-path'           => $this->get_label( 'Path' ),
				'social foundicon-linkedin'       => $this->get_label( 'LinkedIn' ),
				'social foundicon-dribbble'       => $this->get_label( 'Dribbble' ),
				'social foundicon-stumble-upon'   => $this->get_label( 'Stumble upon' ),
				'social foundicon-behance'        => $this->get_label( 'Behance' ),
				'social foundicon-reddit'         => $this->get_label( 'Reddit' ),
				'social foundicon-google-plus'    => $this->get_label( 'Google plus' ),
				'social foundicon-youtube'        => $this->get_label( 'Youtube' ),
				'social foundicon-vimeo'          => $this->get_label( 'Vimeo' ),
				'social foundicon-clickr'         => $this->get_label( 'Clickr' ),
				'social foundicon-slideshare'     => $this->get_label( 'Slideshare' ),
				'social foundicon-picassa'        => $this->get_label( 'Picassa' ),
				'social foundicon-skype'          => $this->get_label( 'Skype' ),
				'social foundicon-instagram'      => $this->get_label( 'instagram' ),
				'social foundicon-foursquare'     => $this->get_label( 'Foursquare' ),
				'social foundicon-delicious'      => $this->get_label( 'Delicious' ),
				'social foundicon-chat'           => $this->get_label( 'Chat' ),
				'social foundicon-torso'          => $this->get_label( 'Torso' ),
				'social foundicon-tumblr'         => $this->get_label( 'Tumblr' ),
				'social foundicon-video-chat'     => $this->get_label( 'Video chat' ),
				'social foundicon-digg'           => $this->get_label( 'Digg' ),
				'social foundicon-wordpress'      => $this->get_label( 'Wordpress' )
			),
			'default_icon_type' => '',
			
			'icon_sizes' => array(
				''       => $this->get_label( 'Small' ),
				'medium' => $this->get_label( 'Medium' ),
				'large'  => $this->get_label( 'Large' ),
				'xlarge' => $this->get_label( 'Extra Large' ),
			),
			'default_icon_size' => '',
			
			'icon_color_classes' => array(
				''                      => $this->get_label( 'none (Default)' ),
				'otw-red-text'                   => $this->get_label( 'Red' ),
				'otw-orange-text'                => $this->get_label( 'Orange' ),
				'otw-green-text'                 => $this->get_label( 'Green' ),
				'otw-greenish-text'              => $this->get_label( 'Greenish' ),
				'otw-aqua-text'                  => $this->get_label( 'Aqua' ),
				'otw-blue-text'                  => $this->get_label( 'Blue' ),
				'otw-pink-text'                  => $this->get_label( 'Pink' ),
				'otw-silver-text'                => $this->get_label( 'Silver' ),
				'otw-brown-text'                 => $this->get_label( 'Brown' ),
				'otw-black-text'                 => $this->get_label( 'Black' )
			),
			'default_icon_color_class' => '',

		);
	}
	
	/**
	 * Shortcode info box admin interface
	 */
	public function build_shortcode_editor_options(){
		
		$html = '';
		
		$source = array();
		if( otw_post( 'shortcode_object', false, array(), 'json' ) ){
			$source = otw_post( 'shortcode_object', array(), array(), 'json' );
		}
		
		$html .= OTW_Form::text_area( array( 'id' => 'otw-shortcode-element-content', 'label' => $this->get_label( 'Content' ), 'description' => $this->get_label( 'The content of your info box. HTML is allowed.' ), 'parse' => $source )  );
		
		$html .= OTW_Form::select( array( 'id' => 'otw-shortcode-element-border_type', 'label' => $this->get_label( 'Border type' ), 'description' => $this->get_label( 'Choose border type.' ), 'parse' => $source, 'options' => $this->settings['border_types'], 'value' => $this->settings['default_border_type'] )  );
		
		$html .= OTW_Form::select( array( 'id' => 'otw-shortcode-element-border_color_class', 'label' => $this->get_label( 'Border color' ), 'description' => $this->get_label( 'Choose predefined color of the border.' ), 'parse' => $source, 'options' => $this->settings['border_color_classes'], 'value' => $this->settings['default_border_color_class'] )  );
		
		$html .= OTW_Form::select( array( 'id' => 'otw-shortcode-element-border_style', 'label' => $this->get_label( 'Border style' ), 'description' => $this->get_label( 'Choose the style for the border.' ), 'parse' => $source, 'options' => $this->settings['border_styles'], 'value' => $this->settings['default_border_style'] )  );
		
		$html .= OTW_Form::select( array( 'id' => 'otw-shortcode-element-shadow', 'label' => $this->get_label( 'Shadow' ), 'description' => $this->get_label( 'Choose shadow type.' ), 'parse' => $source, 'options' => $this->settings['shadows'], 'value' => $this->settings['default_shadow'] )  );
		
		$html .= OTW_Form::select( array( 'id' => 'otw-shortcode-element-rounded_corners', 'label' => $this->get_label( 'Rounded corners' ), 'description' => $this->get_label( 'Choose rounded corners.' ), 'parse' => $source, 'options' => $this->settings['rounded_corners'], 'value' => $this->settings['default_rounded_corner'] )  );
		
		$html .= OTW_Form::select( array( 'id' => 'otw-shortcode-element-background_color_class', 'label' => $this->get_label( 'Background color' ), 'description' => $this->get_label( 'Choose predefined color.' ), 'parse' => $source, 'options' => $this->settings['background_color_classes'], 'value' => $this->settings['default_background_color_class'] )  );
		
		$html .= OTW_Form::select( array( 'id' => 'otw-shortcode-element-background_pattern', 'label' => $this->get_label( 'Background pattern' ), 'description' => $this->get_label( 'Choose predefined pattern.' ), 'parse' => $source, 'options' => $this->settings['background_patterns'], 'value' => $this->settings['default_background_pattern'] )  );
		
		$html .= OTW_Form::select( array( 'id' => 'otw-shortcode-element-icon_type', 'label' => $this->get_label( 'Icon type' ), 'description' => $this->get_label( 'The icons here are based on foundation icon fonts.' ), 'parse' => $source, 'options' => $this->settings['icon_types'], 'value' => $this->settings['default_icon_type'] )  );
		
		$html .= OTW_Form::select( array( 'id' => 'otw-shortcode-element-icon_size', 'label' => $this->get_label( 'Icon size' ), 'description' => $this->get_label( 'The size of the icon.' ), 'parse' => $source, 'options' => $this->settings['icon_sizes'], 'value' => $this->settings['default_icon_size'] )  );
		
		$html .= OTW_Form::select( array( 'id' => 'otw-shortcode-element-icon_color_class', 'label' => $this->get_label( 'Icon color' ), 'description' => $this->get_label( 'The color for the icon.' ), 'parse' => $source, 'options' => $this->settings['icon_color_classes'], 'value' => $this->settings['default_icon_color_class'] )  );
		
		return $html;
	}
	
	/**
	 * Shortcode info box admin interface custom options
	 */
	public function build_shortcode_editor_custom_options(){
		
		$html = '';
		
		$source = array();
		if( otw_post( 'shortcode_object', false, array(), 'json' ) ){
			$source = otw_post( 'shortcode_object', array(), array(), 'json' );
		}
		
		$html .= OTW_Form::color_picker( array( 'id' => 'otw-shortcode-element-border_color', 'label' => $this->get_label( 'Border color custom' ), 'description' => $this->get_label( 'Choose a custom border color.' ), 'parse' => $source )  );
		
		$html .= OTW_Form::color_picker( array( 'id' => 'otw-shortcode-element-background_color', 'label' => $this->get_label( 'Background color custom' ), 'description' => $this->get_label( 'Choose a custom background color.' ), 'parse' => $source )  );
		
		$html .= OTW_Form::text_input( array( 'id' => 'otw-shortcode-element-background_pattern_url', 'label' => $this->get_label( 'Background pattern URL' ), 'description' => $this->get_label( 'URL to a custom background pattern.' ), 'parse' => $source )  );
		
		$html .= OTW_Form::color_picker( array( 'id' => 'otw-shortcode-element-icon_color', 'label' => $this->get_label( 'Icon color custom' ), 'description' => $this->get_label( 'Applies on foundation icons only.' ), 'parse' => $source )  );
		
		$html .= OTW_Form::uploader( array( 'id' => 'otw-shortcode-element-icon_url', 'label' => $this->get_label( 'Icon URL' ), 'description' => $this->get_label( 'Url to a custom icon.' ), 'preview_label' => $this->get_label( 'Preview:' ), 'parse' => $source )  );
		
		$html .= OTW_Form::text_input( array( 'id' => 'otw-shortcode-element-css_class', 'label' => $this->get_label( 'CSS Class' ), 'description' => $this->get_label( 'If you\'d like to style this element separately enter a name here. A CSS class with this name will be available for you to style this particular element..' ), 'parse' => $source )  );
		
		return $html;
	}
	
	/** build info box shortcode
	 *
	 *  @param array
	 *  @return string
	 */
	public function build_shortcode_code( $attributes ){
		
		$code = '';
		
		if( !isset( $attributes['content'] ) || !strlen( trim( $attributes['content'] ) ) ){
			$this->add_error( $this->get_label( 'Content is required field' ) );
		}
		
		if( !$this->has_error ){
		
			$code = '[otw_shortcode_info_box';
			
			$code .= $this->format_attribute( 'border_type', 'border_type', $attributes );
			$code .= $this->format_attribute( 'border_color_class', 'border_color_class', $attributes );
			$code .= $this->format_attribute( 'border_style', 'border_style', $attributes );
			$code .= $this->format_attribute( 'shadow', 'shadow', $attributes );
			$code .= $this->format_attribute( 'rounded_corners', 'rounded_corners', $attributes );
			$code .= $this->format_attribute( 'background_color_class', 'background_color_class', $attributes );
			$code .= $this->format_attribute( 'background_pattern', 'background_pattern', $attributes );
			$code .= $this->format_attribute( 'icon_type', 'icon_type', $attributes );
			$code .= $this->format_attribute( 'icon_size', 'icon_size', $attributes );
			$code .= $this->format_attribute( 'icon_color_class', 'icon_color_class', $attributes );
			$code .= $this->format_attribute( 'border_color', 'border_color', $attributes );
			$code .= $this->format_attribute( 'background_color', 'background_color', $attributes );
			$code .= $this->format_attribute( 'background_pattern_url', 'background_pattern_url', $attributes );
			$code .= $this->format_attribute( 'icon_url', 'icon_url', $attributes );
			$code .= $this->format_attribute( 'icon_color', 'icon_color', $attributes );
			$code .= $this->format_attribute( 'css_class', 'css_class', $attributes, false, '', true  );
			
			$code .= ']';
			
			$code .= $attributes['content'];
			
			$code .= '[/otw_shortcode_info_box]';
		}
		
		return $code;

	}
	
	/**
	 * Display shortcode
	 */
	public function display_shortcode( $attributes, $content ){
		
		$html = '<div';
		
		/*class attributes*/
		$class = 'otw-sc-box';
		
		if( $this->format_attribute( '', 'border_type', $attributes, false, '' ) ){
			$class .= $this->format_attribute( '', 'border_type', $attributes, false, $class );
			$class .= $this->format_attribute( '', 'border_color_class', $attributes, false, $class );
			$class .= $this->format_attribute( '', 'border_style', $attributes, false, $class );
		}
		$class .= $this->format_attribute( '', 'shadow', $attributes, false, $class );
		$class .= $this->format_attribute( '', 'rounded_corners', $attributes, false, $class );
		$class .= $this->format_attribute( '', 'background_color_class', $attributes, false, $class );
		$class .= $this->format_attribute( '', 'background_pattern', $attributes, false, $class );
		
		if( $icon_type = $this->format_attribute( '', 'icon_type', $attributes, false, '' ) ){
			$class = $this->append_attribute( $class, 'with-icon' );
		}elseif( $icon_url = $this->format_attribute( '', 'icon_url', $attributes, false, '' ) ){
			$class = $this->append_attribute( $class, 'with-icon' );
		}
		
		$class .= $this->format_attribute( '', 'icon_size', $attributes, false, $class );
		
		$class .= $this->format_attribute( '', 'css_class', $attributes, false, $class );
		
		if( strlen( $class ) ){
			$html .= ' class="'.esc_attr( $class ).'"';
		}
		/*end class attributes*/
		
		/*styles*/
		$style = '';
		
		if( $border_color = $this->format_attribute( '', 'border_color', $attributes, false, '' ) ){
			$style = $this->append_attribute( $style, 'border-color: '.$border_color.';' );
		}
		
		if( $background_color = $this->format_attribute( '', 'background_color', $attributes, false, '' ) ){
			$style = $this->append_attribute( $style, 'background-color: '.$background_color.';' );
		}
		
		if( $background_pattern_url = $this->format_attribute( '', 'background_pattern_url', $attributes, false, '' ) ){
			$style = $this->append_attribute( $style, 'background-image: url(\''.$background_pattern_url.'\');' );
		}
		
		if( strlen( $style ) ){
			$html .= ' style="'.esc_attr( $style ).'"';
		}
		/*end styles*/
		
		$html .= '>';
		
		//icons
		if( $icon_type = $this->format_attribute( '', 'icon_type', $attributes, false, '' ) ){
			
			$icon_class = $icon_type;
			
			$icon_style = '';
			
			if( $icon_color = $this->format_attribute( '', 'icon_color', $attributes, false, '' ) ){
				$icon_style = $this->append_attribute( $icon_style, 'color: '.$icon_color.' !important;' );
			}else{
				$icon_class .= $this->format_attribute( '', 'icon_color_class', $attributes, false, $icon_class );
			}
			
			if( strlen( $icon_style ) ){
				$icon_style = ' style="'.esc_attr( $icon_style ).'"';
			}
			
			$html .= '<i class="'.esc_attr( $icon_class ).'"'.$icon_style.'></i>';
		}
		
		if( $icon_url = $this->format_attribute( '', 'icon_url', $attributes, false, '' ) ){
		
			$html .= '<img src="'.esc_attr( $icon_url ).'" alt="icon" title="icon" />';
		}
		//end icons
		
		$html .= '<div>';
		
		$html .= nl2br( $content );
		
		$html .= '</div>';
		
		$html .= '</div>';
		
		return $this->format_shortcode_output( $html );
	}
}
