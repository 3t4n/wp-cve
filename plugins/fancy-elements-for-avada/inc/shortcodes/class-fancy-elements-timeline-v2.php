<?php
/**
 * Class responsible for the fancy time line layout 2.
 *
 * @author    WP Square
 * @package   fancy-elements-avada
 */

/**
 * Class for timeline v2 element.
 */
class Fancy_Elements_Timeline_V2 {

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
	private $timeline_counter = 1;

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
			self::$instance = new Fancy_Elements_Timeline_V2();
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

		add_shortcode( 'fea_fancy_timeline_v2', array( $this, 'render_parent' ) );
		add_shortcode( 'fea_fancy_timeline_v2_child', array( $this, 'render_child' ) );

	}

	/**
	 * Enqueue scripts & styles.
	 *
	 * @access public
	 * @since 1.0
	 */
	public function enqueue_scripts() {

		wp_enqueue_style( 'fea-style', plugin_dir_url( __DIR__ ) . 'assets/css/fea-style.css', array(), '1.0.0' );
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
				'primarycolor'      => '',
				'bgcolor'           => '',
				'datebgcolor'       => '',
				'datecolor'         => '',
				'imagetitlecolor'   => '',
				'titlecolor'        => '',
				'textcolor'         => '',
				'readmorebg'		=> '',
				'readmoretextcolor' => '',
				'heading_size'      => '',
			),
			$args
		);

		extract( $defaults );

		$this->parent_args = $defaults;

		$timeline_class         = '.fea-timelinev2-' . $this->timeline_counter;
		$timeline_class_wrapper = 'fea-timelinev2-' . $this->timeline_counter;
		$this->timeline_counter++;

		$styles = $timeline_class . ' .timeline-img, ' . $timeline_class . ' .timeline::before, ' . $timeline_class . ' .timeline::after {
			background:' . $this->parent_args['primarycolor'] . ';
		}';

		$styles .= $timeline_class . ' .timeline-content{
			background:' . $this->parent_args['bgcolor'] . ';
		}';
		$styles .= $timeline_class . ' .timeline-content .timeline-img-header:before{
			background:' . $this->parent_args['primarycolor'] . ';
		}';
		$styles .= $timeline_class . ' .timeline-content::after{
			border-color: transparent transparent transparent ' . $this->parent_args['bgcolor'] . ';
		}';
		$styles .= $timeline_class . ' .timeline-item:nth-child(even) .timeline-content::after{
			border-color: transparent ' . $this->parent_args['bgcolor'] . ' transparent transparent;
		}';

		$styles .= $timeline_class . ' .date{
			color:' . $this->parent_args['datecolor'] . ';
			background:' . $this->parent_args['datebgcolor'] . ';
		}';
		$styles .= $timeline_class . ' .title{
			color:' . $this->parent_args['titlecolor'] . ';
		}';
		$styles .= $timeline_class . ' .title.withimage{
			color:' . $this->parent_args['imagetitlecolor'] . ';
		}';
		$styles .= $timeline_class . ' .timeline-content  p{
			color:' . $this->parent_args['textcolor'] . ';
		}';
		$styles .= $timeline_class . ' .bnt-more{
			background:' . $this->parent_args['readmorebg'] . ';
		}';
		$styles .= $timeline_class . ' .bnt-more{
			color:' . $this->parent_args['readmoretextcolor'] . ';
		}';
		$styles .= $timeline_class . ' .timeline-card a.bnt-more:hover{
			background:' . $this->parent_args['readmoretextcolor'] . ';
			color:' . $this->parent_args['primarycolor'] . ';
		}';

		$styles = '<style type="text/css">' . $styles . '</style>';

		$html = '
		<div class="fea-timelinev2-wrapper ' . esc_attr( $timeline_class_wrapper ) . ' ' . $class . '" id=' . esc_attr( $id ) . '>' . $styles . '
			<section class="timeline">
				' . do_shortcode( $content ) . '
			</section>
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
				'fea_timeline_title'   => '',
				'fea_timeline_date'    => '',
				'fea_timeline_image'   => '',
				'fea_timeline_rm_text' => '',
				'fea_timeline_rm_link' => '#',
			),
			$args
		);

		extract( $defaults );

		$this->child_args = $defaults;

		if ( '' === $this->child_args['fea_timeline_image'] ) {

			$html = '<div class="timeline-item">
					<div class="timeline-img"></div>
					<div class="timeline-content">
						<h' . $this->parent_args['heading_size'] . ' class="title">' . esc_html( $this->child_args['fea_timeline_title'] ) . '</h' . $this->parent_args['heading_size'] . '>
						<div class="date">' . esc_html( $this->child_args['fea_timeline_date'] ) . '</div>
						<p>' . $content . '</p>
						<a class="bnt-more" href="' . esc_url( $this->child_args['fea_timeline_rm_link'] ) . '">' . esc_html( $this->child_args['fea_timeline_rm_text'] ) . '</a>
					</div>
				</div>';
		} else {

			$html = '<div class="timeline-item">

				<div class="timeline-img"></div>

				<div class="timeline-content timeline-card">
					<div class="timeline-img-header" style="background-image:url(' . $this->child_args['fea_timeline_image'] . ')">
						<h' . $this->parent_args['heading_size'] . ' class="title withimage">' . esc_html( $this->child_args['fea_timeline_title'] ) . '</h' . $this->parent_args['heading_size'] . '>
					</div>
					<div class="date">' . esc_html( $this->child_args['fea_timeline_date'] ) . '</div>
					<p>' . $content . '</p>
					<a class="bnt-more" href="' . esc_url( $this->child_args['fea_timeline_rm_link'] ) . '">' . esc_html( $this->child_args['fea_timeline_rm_text'] ) . '</a>
				</div>

			</div>   ';
		}

		return $html;

	}

}
