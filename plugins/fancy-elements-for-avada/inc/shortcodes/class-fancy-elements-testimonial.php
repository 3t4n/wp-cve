<?php
/**
 * Class responsible for the fancy testimonial.
 *
 * @author    WP Square
 * @package   fancy-elements-avada
 */

/**
 * Class for testimonial element.
 */
class Fancy_Elements_Testimonial {

	/**
	 * Parent shortcode params.
	 *
	 * @access private
	 * @since 1.0
	 * @var int
	 */
	protected $parent_args;

	/**
	 * Child shortcode params.
	 *
	 * @access private
	 * @since 1.0
	 * @var int
	 */
	protected $child_args;

	/**
	 * Instance of shortcode.
	 *
	 * @access private
	 * @since 1.0
	 * @var int
	 */
	private static $instance;

	/**
	 * Counter of instances.
	 *
	 * @access private
	 * @since 1.0
	 * @var int
	 */
	private $testimonial_counter = 1;

	/**
	 * Creates or returns an instance of this class.
	 *
	 * @static
	 * @access public
	 * @since 1.0
	 */
	public static function get_instance() {

		// If an instance hasn't been created and set to $instance create an instance and set it to $instance.
		if ( null === self::$instance ) {
			self::$instance = new Fancy_Elements_Testimonial();
		}
		return self::$instance;
	}

	/**
	 * Constructor.
	 *
	 * @since 1.0
	 */
	public function __construct() {

		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

		add_shortcode( 'fea_fancy_testimonial', array( $this, 'render_parent' ) );
		add_shortcode( 'fea_fancy_testimonial_child', array( $this, 'render_child' ) );

	}

	/**
	 * Enqueue scripts & styles.
	 *
	 * @access public
	 * @since 1.0
	 */
	public function enqueue_scripts() {

		wp_enqueue_style( 'testimonial-css', plugin_dir_url( __DIR__ ) . 'assets/css/testimonial.css', array(), '1.0.0' );
		wp_enqueue_style( 'owl-carousel-css', plugin_dir_url( __DIR__ ) . 'assets/css/owl.carousel.css', array());
		wp_enqueue_script( 'testimonial-js', plugin_dir_url( __DIR__ ) . 'assets/js/testimonial.js', array(), '1.0', true );
		wp_enqueue_script( 'owl-carousel-js', plugin_dir_url( __DIR__ ) . 'assets/js/owl.carousel.min.js');

	}



	/**
	 * Render the parent shortcode.
	 *
	 * @access public
	 * @since 1.0
	 * @param  array  $args    Shortcode parameters.
	 * @param  string $content Content between shortcode.
	 * @return string          HTML output.
	 */
	public function render_parent( $args, $content = '' ) {

		global $fusion_settings;

		$html     = '';
		$defaults = FusionBuilder::set_shortcode_defaults(
			array(
				'class'             => '',
				'id'                => '',
				'primarybgcolor'    => '',
				'captioncolor'      => '',
				'titlecolor'        => '',
				'textcolor'         => '',
				'heading_size'      => '',
			),
			$args
		);

		extract( $defaults );

		$this->parent_args = $defaults;

		$testimonial_class         = '.fea-testimonial-' . $this->testimonial_counter;
		$testimonial_class_wrapper = 'fea-testimonial-' . $this->testimonial_counter;
		$this->testimonial_counter++;

		$styles = "";

		$styles .= $testimonial_class . ' .fea-testimonial-item-caption{
			background:' . $this->parent_args['primarybgcolor'] . '!important;
		}';
		$styles .= $testimonial_class . ' .fea-testimonial-item .fea-testimonial-item-caption::after{
			border-top-color:' . $this->parent_args['primarybgcolor'] . '!important;
		}';
		$styles .= $testimonial_class . ' .owl-controls .owl-dots .owl-dot.active{
			background:' . $this->parent_args['primarybgcolor'] . '!important;
		}';
		$styles .= $testimonial_class . ' .item:nth-child(even) .fea-testimonial-item-caption::after{
			border-color: transparent ' . $this->parent_args['primarybgcolor'] . ' transparent transparent;
		}';
		$styles .= $testimonial_class . ' .caption{
			color:' . $this->parent_args['captioncolor'] . ';
		}';
		$styles .= $testimonial_class . ' .title{
			color:' . $this->parent_args['titlecolor'] . ';
		}';
		$styles .= $testimonial_class . ' .fea-testimonial-item-caption  p{
			color:' . $this->parent_args['textcolor'] . ';
		}';

		$styles = '<style type="text/css">' . $styles . '</style>';

		$html = '
		' . $styles . '
		<div class="owl-carousel fea-testimonialv1 ' . esc_attr( $testimonial_class_wrapper ) . ' ' . $class . '" id=' . esc_attr( $id ) . '>
			' . do_shortcode( $content ) . '
		</div>';

		return $html;

	}


	/**
	 * Render the child shortcode.
	 *
	 * @access public
	 * @since 1.0
	 * @param  array  $args   Shortcode parameters.
	 * @param  string $content Content between shortcode.
	 * @return string         HTML output.
	 */
	public function render_child( $args, $content = '' ) {

		$html     = '';
		$defaults = FusionBuilder::set_shortcode_defaults(
			array(
				'fea_testimonial_title'   => '',
				'fea_testimonial_caption' => '',
				'fea_testimonial_image'   => '',
			),
			$args
		);

		extract( $defaults );

		$this->child_args = $defaults;

			$html = '<div class="item">

			<!-- FEA testimonial Item start -->
			<div class="fea-testimonial-item">
			<div class="fea-testimonial-item-caption">
			<p>' . $content . '</p>
			</div>
			<div class="fea-testimonial-item-info">
			<div class="fea-testimonial-item-view"> <img src="' . $this->child_args['fea_testimonial_image'] . '" alt=""> </div>
			<div class="fea-testimonial-item-head">
			<h' . $this->parent_args['heading_size'] . ' class="title">' . esc_html( $this->child_args['fea_testimonial_title'] ) . '</h' . $this->parent_args['heading_size'] . '>
			<p class="caption">' . esc_html( $this->child_args['fea_testimonial_caption'] ) . '</p>
			</div>
			</div>
			</div>
			<!-- FEA testimonial Item end -->
			</div>';

		return $html;

	}

}
