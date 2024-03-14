<?php
class OTW_Shortcode_Quote extends OTW_Shortcodes{
	
	public function __construct(){
		
		$this->has_custom_options = true;
		
		parent::__construct();
	}
	
	/**
	 * register external libs
	 */
	public function register_external_libs(){
	
		$this->add_external_lib( 'css', 'otw-shortcode', $this->component_url.'css/otw_shortcode.css', 'all', 100 );
	}
	/**
	 * apply settings
	 */
	public function apply_settings(){
	
		$this->settings = array(
			'borders' => array(
				'bordered'    => $this->get_label( 'yes' ),
				''          => $this->get_label( 'no (Default)' )
			),
			'default_border' => '',
			
			'border_styles' => array(
				'bordered'   => $this->get_label( 'solid(default)' ),
				'dashed'     => $this->get_label( 'dashed' ),
				'dotted'     => $this->get_label( 'dotted' )
			),
			'default_border_style' => 'bordered',
			
			'background_color_classes' => array(
				''                      => $this->get_label( 'none (Default)' ),
				'otw-red-background'                   => $this->get_label( 'Red' ),
				'otw-orange-background'                => $this->get_label( 'Orange' ),
				'otw-green-background'                 => $this->get_label( 'Green' ),
				'otw-greenish-background'              => $this->get_label( 'Greenish' ),
				'otw-aqua-background'                  => $this->get_label( 'Aqua' ),
				'otw-blue-background'                  => $this->get_label( 'Blue' ),
				'otw-pink-background'                  => $this->get_label( 'Pink' ),
				'otw-silver-background'                => $this->get_label( 'Silver' ),
				'otw-brown-background'                 => $this->get_label( 'Brown' ),
				'otw-black-background'                 => $this->get_label( 'Black' ),
				'otw-white-background'                 => $this->get_label( 'White' )
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
			
			'color_classes' => array(
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
			'default_color_class' => ''
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
		
		$html .= OTW_Form::text_area( array( 'id' => 'otw-shortcode-element-content', 'label' => $this->get_label( 'Quote' ), 'description' => $this->get_label( 'The content of your quote. HTML is allowed.' ), 'parse' => $source )  );
		
		$html .= OTW_Form::select( array( 'id' => 'otw-shortcode-element-border', 'label' => $this->get_label( 'Border' ), 'description' => $this->get_label( 'Enables border.' ), 'parse' => $source, 'options' => $this->settings['borders'], 'value' => $this->settings['default_border'] )  );
		
		$html .= OTW_Form::select( array( 'id' => 'otw-shortcode-element-border_style', 'label' => $this->get_label( 'Border style' ), 'description' => $this->get_label( 'Choose the style for the border.' ), 'parse' => $source, 'options' => $this->settings['border_styles'], 'value' => $this->settings['default_border_style'] )  );
		
		$html .= OTW_Form::select( array( 'id' => 'otw-shortcode-element-background_color_class', 'label' => $this->get_label( 'Background color' ), 'description' => $this->get_label( 'Choose predefined color.' ), 'parse' => $source, 'options' => $this->settings['background_color_classes'], 'value' => $this->settings['default_background_color_class'] )  );
		
		$html .= OTW_Form::select( array( 'id' => 'otw-shortcode-element-background_pattern', 'label' => $this->get_label( 'Background pattern' ), 'description' => $this->get_label( 'Choose predefined pattern.' ), 'parse' => $source, 'options' => $this->settings['background_patterns'], 'value' => $this->settings['default_background_pattern'] )  );
		
		$html .= OTW_Form::select( array( 'id' => 'otw-shortcode-element-color_class', 'label' => $this->get_label( 'Text Color' ), 'description' => $this->get_label( 'Choose predefined text color. None means site default.' ), 'parse' => $source, 'options' => $this->settings['color_classes'], 'value' => $this->settings['default_color_class'] )  );
		
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
		
		$html .= OTW_Form::color_picker( array( 'id' => 'otw-shortcode-element-background_color', 'label' => $this->get_label( 'Background color custom' ), 'description' => $this->get_label( 'Choose a custom background color.' ), 'parse' => $source )  );
		
		$html .= OTW_Form::text_input( array( 'id' => 'otw-shortcode-element-background_pattern_url', 'label' => $this->get_label( 'Background pattern URL' ), 'description' => $this->get_label( 'URL to a custom background pattern.' ), 'parse' => $source )  );
		
		$html .= OTW_Form::color_picker( array( 'id' => 'otw-shortcode-element-color', 'label' => $this->get_label( 'Text color custom' ), 'description' => $this->get_label( 'Choose custom text color.' ), 'parse' => $source )  );
		
		$html .= OTW_Form::text_input( array( 'id' => 'otw-shortcode-element-css_class', 'label' => $this->get_label( 'CSS Class' ), 'description' => $this->get_label( 'If you\'d like to style this element separately enter a name here. A CSS class with this name will be available for you to style this particular element..' ), 'parse' => $source )  );
		
		return $html;
	}
	
	/** build button shortcode
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
		
			$code = '[otw_shortcode_quote';
			
			$code .= $this->format_attribute( 'border', 'border', $attributes );
			$code .= $this->format_attribute( 'border_style', 'border_style', $attributes );
			$code .= $this->format_attribute( 'background_color_class', 'background_color_class', $attributes );
			$code .= $this->format_attribute( 'background_pattern', 'background_pattern', $attributes );
			$code .= $this->format_attribute( 'color_class', 'color_class', $attributes );
			$code .= $this->format_attribute( 'background_color', 'background_color', $attributes );
			$code .= $this->format_attribute( 'background_pattern_url', 'background_pattern_url', $attributes );
			$code .= $this->format_attribute( 'color', 'color', $attributes );
			$code .= $this->format_attribute( 'css_class', 'css_class', $attributes, false, '', true  );
			
			$code .= ']';
			
			$code .= $attributes['content'];
			
			$code .= '[/otw_shortcode_quote]';
		}
		
		return $code;

	}
	
	/**
	 * Display shortcode
	 */
	public function display_shortcode( $attributes, $content ){
		
		$html = '<blockquote';
		
		/*class attributes*/
		$class = 'otw-sc-quote';
		
		if( $this->format_attribute( '', 'border', $attributes, false, '' ) ){
			$class .= $this->format_attribute( '', 'border', $attributes, false, $class );
			$class .= $this->format_attribute( '', 'border_style', $attributes, false, $class );
		}
		
		if( $this->format_attribute( '', 'background_color_class', $attributes, false, '' ) ){
			$class = $this->append_attribute( $class, 'background' );
		}elseif( $this->format_attribute( '', 'background_pattern', $attributes, false, '' ) ){
			$class = $this->append_attribute( $class, 'background' );
		}elseif( $this->format_attribute( '', 'background_pattern_url', $attributes, false, '' ) ){
			$class = $this->append_attribute( $class, 'background' );
		}
		
		$class .= $this->format_attribute( '', 'css_class', $attributes, false, $class );
		
		if( strlen( $class ) ){
			$html .= ' class="'.esc_attr( $class ).'"';
		}
		/*end class attributes*/
		
		$html .= '>';
		
		$html .= '<p';
		
		$content_class = '';
		
		$content_class .= $this->format_attribute( '', 'background_color_class', $attributes, false, $content_class );
		$content_class .= $this->format_attribute( '', 'background_pattern', $attributes, false, $content_class );
		$content_class .= $this->format_attribute( '', 'color_class', $attributes, false, $content_class );
		
		if( strlen( $content_class ) ){
			$html .= ' class="'.esc_attr( $content_class ).'"';
		}
		
		/*styles*/
		$content_style = '';
		
		if( $background_color = $this->format_attribute( '', 'background_color', $attributes, false, '' ) ){
			$content_style = $this->append_attribute( $content_style, 'background-color: '.$background_color.';' );
		}
		
		if( $background_pattern_url = $this->format_attribute( '', 'background_pattern_url', $attributes, false, '' ) ){
			$content_style = $this->append_attribute( $content_style, 'background-image: url(\''.$background_pattern_url.'\');' );
		}
		
		if( $color = $this->format_attribute( '', 'color', $attributes, false, '' ) ){
			$content_style = $this->append_attribute( $content_style, 'color: '.$color.' !important;' );
		}
		
		if( strlen( $content_style ) ){
			$html .= ' style="'.esc_attr( $content_style ).'"';
		}
		/*end styles*/
		
		$html .= '>';
		
		$html .= nl2br( $content );
		
		$html .= '</p>';
		
		$html .= '</blockquote>';
		
		return $this->format_shortcode_output( $html );
	}
}
