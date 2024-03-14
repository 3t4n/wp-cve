<?php

/* The public-facing functionality of the plugin. */

class GSPS_Public {

	/* The ID of this plugin. */
	private $plugin_name;

	/* The version of this plugin. */
	private $version;

	/* Initialize the class and set its properties. */
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}

	/* Register the stylesheets for the public-facing side of the site. */
	public function enqueue_styles() {
		/* An instance of this class should be passed to the run() function defined in the Loader class. */
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/genesis-simple-page-sections-public.css', array(), $this->version, 'all' );
	}

	/* Checks if the shortcode is being used on current page or post. */
	public function uses_shortcode() {
		global $post;
		if ( has_shortcode( $post->post_content, 'genesis-simple-page-section' ) || has_shortcode( $post->post_content, 'gsps' ) ) {
			return true;
		} else {
			return false;
		}
	}

	/* Adds body class if the shortcode is being used. */
	public function add_body_class( $classes ) {
		if( $this->uses_shortcode() ) {
			$classes[] = 'genesis-simple-page-sections';
		}
		return $classes;
	}

	/* Replaces flat color name with hex color value. */
	public function filtered_color( $color ) {
		$flat_colors = array( 
			'turquoise',	'green sea',		'emerald',		'nephritis', 
			'peter river',	'belize hole',		'amethyst',		'wisteria', 
			'wet asphalt',	'midnight blue',	'sun flower',	'orange', 
			'carrot',		'pumpkin',			'alizarin',		'pomegranate', 
			'clouds',		'silver',			'concrete',		'asbestos' 
		);
		$flat_color_values = array( 
			'#1abc9c', '#16a085', '#2ecc71', '#27ae60', 
			'#3498db', '#2980b9', '#9b59b6', '#8e44ad', 
			'#34495e', '#2c3e50', '#f1c40f', '#f39c12', 
			'#e67e22', '#d35400', '#e74c3c', '#c0392b', 
			'#ecf0f1', '#bdc3c7', '#95a5a6', '#7f8c8d' 
		);
		return str_replace( $flat_colors, $flat_color_values, $color );
	}

	/* Processes the shortcode. */
	public function gsps_shortcode( $atts, $content = null ) {
		extract( shortcode_atts( array(
			'color' => '',
			'outer_class' => '',
			'outer_css' => '',
			'inner_class' => '',
			'inner_css' => '',
			'width' => ''
			), $atts ) );
		$background_color = $this->filtered_color( esc_attr( $color ) );
		$outer_class = esc_attr( $outer_class );
		$outer_css = esc_attr( $outer_css );
		$inner_class = esc_attr( $inner_class );
		$inner_css = esc_attr( $inner_css );
		$width = esc_attr( $width );
		ob_start();

		// $var_is_greater_than_two = ($var > 2 ? true : false); // returns true
		// Begin opening tag for outer div, add outer class, if it exists
		echo '<div class="gsps-outer' . ( $outer_class ? ' ' . $outer_class : '' );
		// Add style attribute, if background color or outer CSS exist
		echo ( $background_color || $outer_css ? '" style="' : '' );
		// Add background color, if it exists, with # if just a hexadecimal number
		echo ( $background_color ? 'background-color:' . ( ctype_xdigit( $background_color ) ? '#' : '' ) . $background_color . ';' : '' );
		// Add outer CSS, if it exists
		echo $outer_css ? $outer_css : '';
		// End opening tag for outer div
		echo '">';

		// Begin opening tag for inner div, add inner class, if it exists
		echo '<div class="gsps-inner' . ( $inner_class ? ' ' . $inner_class : '' );
		// Add style attribute, if width or inner CSS exist
		echo $width || $inner_css ? '" style="' : '';
		// Add max width, if it exists, with px if just a number
		echo ( $width ? 'max-width:' . $width . ( ctype_digit( $width ) ? 'px' : '' ) . ';' : '' );
		// Add inner CSS, if it exists
		echo $inner_css ? $inner_css : '';
		// End opening tag for inner div
		echo '">';

		// Add content inside shortcode, close inner and outer divs
		echo do_shortcode( $content ) . '</div></div>';
		return ob_get_clean();
	}
}