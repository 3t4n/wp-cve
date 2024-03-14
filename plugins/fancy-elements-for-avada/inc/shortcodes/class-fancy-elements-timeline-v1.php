<?php
/**
 * Class responsible for the fancy time line layout 1.
 *
 * @author    WP Square
 * @package   fancy-elements-avada
 */

/**
 * Class for timeline v1 element.
 */
class Fancy_Elements_Timeline_V1 {

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
			self::$instance = new Fancy_Elements_Timeline_V1();
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
		add_shortcode( 'fea_fancy_timeline_v1', array( $this, 'render_parent' ) );
		add_shortcode( 'fea_fancy_timeline_v1_child', array( $this, 'render_child' ) );

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
				'class'        => '',
				'id'           => '',
				'primarycolor' => '',
				'iconscolor'   => '',
				'titlecolor'   => '',
				'captioncolor' => '',
				'textcolor'    => '',
				'heading_size' => '',
				'caption_size' => '',
			),
			$args
		);

		extract( $defaults );

		$this->parent_args = $defaults;

		$timeline_class         = '.fea-timelinev1-' . $this->timeline_counter;
		$timeline_class_wrapper = 'fea-timelinev1-' . $this->timeline_counter;
		$this->timeline_counter++;

		$styles = $timeline_class . ' .fea-timeline-row .fea-timeline-date, ' . $timeline_class . ' .fea-timeline-row .fea-timeline-date time, ' . $timeline_class . ' .fea-timeline-row .fea-timeline-aside .fea-timeline-post-item:before, ' . $timeline_class . ' article.fea-timeline-row:before, ' . $timeline_class . ' article.fea-timeline-row:after, ' . $timeline_class . ':after, ' . $timeline_class . ' .fea-timeline-row .fea-timeline-aside .fea-timeline-post-item .fea-timeline-post-icon .fea-timeline-icon {
			background:' . $this->parent_args['primarycolor'] . ';
		}';

		$styles .= $timeline_class . ' .fea-timeline-row .fea-timeline-aside .fea-timeline-post-item .fea-timeline-post-icon .fea-timeline-icon{
			color:' . $this->parent_args['iconscolor'] . ';
		}';

		$styles .= $timeline_class . ' .fea-timeline-post-description .title{
			color:' . $this->parent_args['titlecolor'] . ';
		}';
		$styles .= $timeline_class . ' .fea-timeline-row .fea-timeline-aside .fea-timeline-post-item .caption{
			color:' . $this->parent_args['captioncolor'] . ';
		}';
		$styles .= $timeline_class . ' .fea-timeline-row .fea-timeline-aside .fea-timeline-post-item .fea-timeline-post-description p{
			color:' . $this->parent_args['textcolor'] . ';
		}';

		$styles = '<style type="text/css">' . $styles . '</style>';

		$html = '
		<div class="fea-timeline-container ' . esc_attr( $class ) . '" id=' . esc_attr( $id ) . '>
		' . $styles . '
		  	<div class="' . esc_attr( $timeline_class_wrapper ) . ' fea-timeline-wrapper">

		    	<article class="fea-timeline-row">
		      		<div class="fea-timeline-inner-row">
					' . do_shortcode( $content ) . '

		      		</div>
		    	</article>

		  	</div>
		  	<!-- fea timeline Element End -->
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
				'fea_timeline_caption' => '',
				'fea_timeline_icon'    => '',
			),
			$args
		);

		extract( $defaults );

		$this->child_args = $defaults;

		$html = '<aside class="fea-timeline-aside">
			<div class="fea-timeline-post-item">
				<div class="fea-timeline-post-description">
					<h' . $this->parent_args['heading_size'] . ' class="title">' . esc_html( $this->child_args['fea_timeline_title'] ) . '</h' . $this->parent_args['heading_size'] . '>
					<h' . $this->parent_args['caption_size'] . ' class="caption">' . esc_html( $this->child_args['fea_timeline_caption'] ) . '</h' . $this->parent_args['caption_size'] . '>
					<p>' . $content . '</p>
				</div>
				<div class="fea-timeline-post-icon">
					<div class="fea-timeline-icon"> <i class="' . esc_attr( $this->child_args['fea_timeline_icon'] ) . '"></i> </div>
				</div>
			</div>
		</aside>';
		return $html;

	}

}
