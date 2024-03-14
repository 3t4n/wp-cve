<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       zemartino.com
 * @since      1.0.0
 *
 * @package    Wp_Bigfoot
 * @subpackage Wp_Bigfoot/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Wp_Bigfoot
 * @subpackage Wp_Bigfoot/public
 * @author     Adam Martinez <am@zemartino.com>
 */
class Wp_Bigfoot_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		add_shortcode('footnote', array($this,'shortcode_footnote') );
		add_filter( 'the_content', array($this, 'the_content' ), 12 );
		add_action('wp_footer', array($this, 'override_footnotestyle')); 

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wp_Bigfoot_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wp_Bigfoot_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		 $options = get_option('wpbf-options');
		 $style = $options['wpbf-style'];

		wp_enqueue_style( $this->plugin_name.'-public', plugin_dir_url( __FILE__ ) . 'css/wp-bigfoot-public.css', array(), $this->version, 'all' );
		wp_enqueue_style( $this->plugin_name.'-style', plugin_dir_url( __FILE__ ) . 'css/bigfoot-'.strtolower($style).'.css', array(), $this->version, 'all');

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wp_Bigfoot_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wp_Bigfoot_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script(  $this->plugin_name.'-publicjs', plugin_dir_url( __FILE__ ) . 'js/wp-bigfoot-public.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script(  $this->plugin_name.'-min', plugin_dir_url( __FILE__ ) . 'js/bigfoot.min.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script(  $this->plugin_name.'-wp-bigfoot', plugin_dir_url( __FILE__ ) . 'js/wp-bigfoot.js', array( 'jquery' ), $this->version, false );

	}
	

		function shortcode_footnote( $atts, $content=NULL ){
			global $id;
			if ( null === $content )	return;
			$content = $this->remove_crappy_markup( $content );
			if ( ! isset( $this->footnotes[$id] ) ) $this->footnotes[$id] = array();
			$this->footnotes[$id][] = $content;
			$count = count( $this->footnotes[$id] );
			return '<a href="#footnote-' . $count . '-' . $id . '" ' . 'id="note-' . $count . '-' . $id . '" ' . 'rel="footnote">' . $count . '</a>';
		}
		
		function remove_crappy_markup( $string ){
			$patterns = array(
				'#^\s*</p>#',
				'#<p>\s*$#'
			);
			return preg_replace($patterns, '', $string);
		}
	
		function the_content($content) {
			return $this->get_footnotes( $content );
		}
	
		function get_footnotes( $content ) {
			global $id;
			if ( empty( $this->footnotes[$id] ) )	return $content;
			$footnotes = $this->footnotes[$id];
			if( count($footnotes) ){
				$content .= '<div class="footnotes">';
				$content .= '<hr />';
				$content .= '<ol>';
				foreach ( $footnotes as $number => $footnote ): 
					$number++;
					$content .= '<li id="footnote-'.$number.'-'.$id.'" class="footnote">';
					$content .= '<p>';
					$content .= $footnote;
					$content .= '<a href="#note-'.$number.'-'.$id.'" class="footnote-return">&#8617;</a>';
					$content .= '</p>';
					$content .= '</li><!--/#footnote-'.$number.'.footnote-->';
				endforeach;
				$content .= '</ol>';
				$content .= '</div><!--/#footnotes-->';
			}
			return $content;
	
	
	}

	/**
	 * Override player styles
	 * @todo what is this used for
	 */
	public function override_footnotestyle() {
		$options = get_option( 'wpbf-options');
		$bg = $options['wpbf-bgcolor'];
		$fg = $options['wpbf-fgcolor'];

		if ( isset( $bg ) || isset( $fg) )
		?>
		<style type="text/css">
			.bigfoot-footnote__button  {
				background-color: <?php echo $bg; ?> !important;
			}
			.bigfoot-footnote__button:after {
				color: <?php echo $fg; ?> !important;
			}
		</style>
		<?php
	}	

}
