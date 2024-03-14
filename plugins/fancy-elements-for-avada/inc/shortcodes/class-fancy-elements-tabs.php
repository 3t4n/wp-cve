<?php
/**
 * Class responsible for the fancy tabs.
 *
 * @author    WP Square
 * @package   fancy-elements-avada
 */

/**
 * Class for the fancy tabs element
 */
class Fancy_Elements_Tabs {

	/**
	 * Tabs counter.
	 *
	 * @access private
	 * @since 1.0
	 * @var int
	 */
	private $tabs_counter = 1;

	/**
	 * Tab counter.
	 *
	 * @access private
	 * @since 1.0
	 * @var int
	 */
	private $tab_counter = 1;

	/**
	 * Identifier for a single tab.
	 *
	 * @access private
	 * @since 1.0
	 * @var int
	 */
	private $tabs_identifier;

	/**
	 * Array of our tabs.
	 *
	 * @access private
	 * @since 1.0
	 * @var array
	 */
	private $tabs = array();

	/**
	 * Whether the tab is active or not.
	 *
	 * @access private
	 * @since 1.0
	 * @var bool
	 */
	private $active = false;

	/**
	 * Parent SC arguments.
	 *
	 * @access protected
	 * @since 1.0
	 * @var array
	 */
	protected $parent_args;

	/**
	 * Child SC arguments.
	 *
	 * @access protected
	 * @since 1.0
	 * @var array
	 */
	protected $child_args;

	/**
	 * Parent fusion_tabs SC arguments.
	 *
	 * @access protected
	 * @since 1.0
	 * @var array
	 */

	/**
	 * Child fusion_tab SC arguments.
	 *
	 * @access protected
	 * @since 1.0
	 * @var array
	 */
	protected $fusion_fancy_tab_args;

	/**
	 * The one, true instance of this object.
	 *
	 * @static
	 * @access private
	 * @since 1.0
	 * @var object
	 */
	private static $instance;

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
			self::$instance = new Fancy_Elements_Tabs();
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

		add_filter( 'fusion_attr_fancy-tabs-shortcode-link', array( $this, 'fancy_link_attr' ) );
		add_filter( 'fusion_attr_fancy-tabs-shortcode-tab', array( $this, 'fancy_tab_attr' ) );

		add_shortcode( 'fea_raw_fancy_tabs', array( $this, 'render_parent' ) );
		add_shortcode( 'fea_raw_fancy_tab', array( $this, 'render_child' ) );

		add_shortcode( 'fea_fancy_tabs', array( $this, 'fea_fancy_tabs' ) );
		add_shortcode( 'fea_fancy_tab', array( $this, 'fea_fancy_tab' ) );

	}


	/**
	 * Enqueue scripts & styles.
	 *
	 * @access public
	 * @since 1.0
	 */
	public function enqueue_scripts() {

		wp_enqueue_style( 'fea-style', plugin_dir_url( __DIR__ ) . 'assets/css/fea-style.css', array(), '1.0.0' );

		wp_enqueue_script( 'bootstrap-transition', get_template_directory_uri() . '/includes/lib/assets/min/js/library/bootstrap.transition.js', array(), '3.3.6', true );
		wp_enqueue_script( 'bootstrap-tab', get_template_directory_uri() . '/includes/lib/assets/min/js/library/bootstrap.tab.js', array( 'bootstrap-transition' ), '3.1.1', true );
		wp_enqueue_script( 'fea-main-js', plugin_dir_url( __DIR__ ) . 'assets/js/fea-main.js', array( 'jquery', 'bootstrap-tab' ), '1.0', true );

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
				'class'          => '',
				'id'             => '',
				'inactivecolor'  => '',
				'activecolor'    => '',
				'hovercolor'     => '',
				'arrowupcolor'   => '',
				'arrowdowncolor' => '',
				'heading_size'   => '',
				'caption_size'   => '',
				'headingcolor'   => '',
				'captioncolor'   => '',
				'border'         => '',
				'heading_size'   => '',
				'caption_size'   => '',
				'active_tab'     => 1,
			),
			$args
		);

		extract( $defaults );

		$this->parent_args = $defaults;
		$custom_id         = 'id=' . esc_attr( $id ) . '';
		$styles            = '.' . $this->tabs_identifier . ' .fea-fancy-tabs-content-menu li{border-top-color:' . $this->parent_args['inactivecolor'] . ';background-color:' . $this->parent_args['inactivecolor'] . ';}';
		$styles           .= '.' . $this->tabs_identifier . ' .fea-fancy-tabs-content-menu li.active{border-top-color:' . $this->parent_args['activecolor'] . ';background-color:' . $this->parent_args['activecolor'] . ';}';
		$styles           .= '.' . $this->tabs_identifier . ' .fea-fancy-tabs-content-menu li:hover{border-top-color:' . $this->parent_args['hovercolor'] . ';background-color:' . $this->parent_args['hovercolor'] . ';}';
		$styles           .= '.' . $this->tabs_identifier . ' .fea-fancy-tabs-content-menu li.active::after{border-bottom: 30px solid ' . $this->parent_args['arrowupcolor'] . ';}';
		$styles           .= '.' . $this->tabs_identifier . ' .fea-fancy-tabs-content-menu li.active:before{border-top: 30px solid ' . $this->parent_args['arrowdowncolor'] . ';}';
		$styles           .= '.' . $this->tabs_identifier . ' .fea-fancytab-heading{color:' . $this->parent_args['headingcolor'] . ' !important;}';
		$styles           .= '.' . $this->tabs_identifier . ' .fea_fancy_tab_caption{color:' . $this->parent_args['captioncolor'] . '!important;}';
		$styles           .= '.' . $this->tabs_identifier . ' .fea-fancy-tabs-content-menu li img{border-radius:' . $this->parent_args['border'] . ';}';
		$styles           .= '.' . $this->tabs_identifier . ' .fea-fancy-tabs-content-menu li img{-moz-border-radius:' . $this->parent_args['border'] . ';}';
		$styles           .= '.' . $this->tabs_identifier . ' .fea-fancy-tabs-content-menu li img{-webkit-border-radius:' . $this->parent_args['border'] . ';}';
		$styles           .= '.' . $this->tabs_identifier . ' .fea-fancy-tabs-content-menu li img{-khtml-border-radius:' . $this->parent_args['border'] . ';}';

		$styles = '<style type="text/css">' . $styles . '</style>';

		$html = '
		<div class="fea-fancy-tabs ' . esc_attr( $class ) . ' ' . esc_attr( $this->tabs_identifier ) . '"  ' . ( '' !== $id ? $custom_id : '' ) . '>
			<div ' . FusionBuilder::attributes( 'fea-fancy-tabs-content-list' ) . '>' . $styles . '
				<ul ' . FusionBuilder::attributes( 'fea-fancy-tabs-content-menu fea-fancy-tab-function' ) . '>';

		$is_active_tab = $this->parent_args['active_tab'];

		if ( empty( $this->tabs ) ) {
			$this->parse_tab_parameter( $content, 'fea_raw_fancy_tab', $args );
		}

		if ( strpos( $content, 'fea_fancy_tab' ) ) {

			preg_match_all( '/(\[fea_fancy_tab (.*?)\](.*?)\[\/fea_fancy_tab\])/s', $content, $matches );
		} else {
			preg_match_all( '/(\[fea_raw_fancy_tab (.*?)\](.*?)\[\/fea_raw_fancy_tab\])/s', $content, $matches );
		}

		$tab_content = '';
		$tabs_count  = count( $this->tabs );

		for ( $i = 0; $i < $tabs_count; $i++ ) {

			$icon         = '';
			$tab_title    = '';
			$icon_link    = '';
			$tab_caption  = '';
			$tab_title    = $this->tabs[ $i ]['title'];
			$tab_caption  = $this->tabs[ $i ]['caption'];
			$icon_link    = $this->tabs[ $i ]['icon'];
			$unique_id    = $i;
			$title_tag    = $this->parent_args['heading_size'];
			$caption_size = $this->parent_args['caption_size'];
			$ptag_caption = '';
			$htag_caption = '';

			if ( 0 === $caption_size ) {
				$ptag_caption = '<p class=fea_fancy_tab_caption>' . esc_html( $tab_caption ) . '</p>';
			} else {
				$htag_caption = '<h' . $caption_size . ' class=fea_fancy_tab_caption>' . esc_html( $tab_caption ) . '</h' . $caption_size . '>';
			}

			if ( '' !== $icon_link && 'unknown' !== $icon_link ) {
				$ft_image = '<img src="' . esc_url( $icon_link ) . '" alt="">';
			} else {
				$ft_image = '';
			}

			$i_counter = $i + 1;

			if ( (int) $is_active_tab === (int) $i_counter ) {
				$this->active = $is_active_tab;
				$tab_nav      = '<li ' . FusionBuilder::attributes( 'active' ) . '><a ' . FusionBuilder::attributes( 'fea-fancy-tabs-shortcode-link', array( 'index' => $this->tab_counter ) ) . '>
					        ' . $ft_image . '
									<h' . $title_tag . ' ' . FusionBuilder::attributes( 'fea-fancytab-heading' ) . '>' . $tab_title . '</h' . $title_tag . '>
					        ' . $ptag_caption . $htag_caption . '
									</a></li>';

			} else {

				$this->active = false;
				$tab_nav      = '<li><a ' . FusionBuilder::attributes( 'fea-fancy-tabs-shortcode-link', array( 'index' => $this->tab_counter ) ) . '>
									' . $ft_image . '
									<h' . $title_tag . ' ' . FusionBuilder::attributes( 'fea-fancytab-heading' ) . '>' . $tab_title . '</h' . $title_tag . '>
									' . $ptag_caption . $htag_caption . '
									</a></li>';
			}

			$html .= $tab_nav;

			// Change ID for mobile to ensure no duplicate ID.
			$tab_nav = str_replace( 'id="fea-tab-', 'id="mobile-fea-tab-', $tab_nav );

			$shortcode_wrapper = '[fea_fancy_tab unique_id="' . $i . '" fea_tab_title="' . $tab_title . '" fea_tab_icon_image="' . $icon_link . '" fea_tab_caption="' . $tab_caption . '" /]';

			if ( isset( $matches[1][ $i ] ) ) {
					$tab_content .= '
									<div ' . FusionBuilder::attributes( 'nav fea-mobile-tab-nav' ) . '>
										<ul ' . FusionBuilder::attributes( 'nav-tabs' ) . '>' . $tab_nav . '</ul>
									</div>
									' . do_shortcode( $matches[1][ $i ] );

			} else {
					$tab_content .= '
									<div ' . FusionBuilder::attributes( 'nav fea-mobile-tab-nav' ) . '>
										<ul ' . FusionBuilder::attributes( 'nav-tabs' ) . '>' . $tab_nav . '</ul>
									</div>
									';

			}
		}

		$html .= '</ul>
				</div>
				<div class="fea-fancy-tabs-content-wrapper">
					<div ' . FusionBuilder::attributes( 'tab-content' ) . '>' . $tab_content . '</div>

				</div>
			</div>';
			$this->tabs_counter++;
			$this->tab_counter = 1;
			unset( $this->tabs );
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

		$defaults = FusionBuilder::set_shortcode_defaults(
			array(
				'id'                   => '',
				'fea_fancy_tab_active' => 'no',
			),
			$args
		);

		extract( $defaults );

		$this->child_args = $defaults;
		$display          = 'style="display:none"';
		if ( 'yes' === $fea_fancy_tab_active ) {
			$display = '';
		}

		return '
		<div ' . $display . ' class="fea-fancy-tabs-shortcode-tab fea-tab-content-' . esc_attr( $args['id'] ) . '">
			' . do_shortcode( $content ) . '
		</div>';

	}


	/**
	 * Returns the fusion-tabs.
	 *
	 * @access public
	 * @since 1.0
	 * @param array       $atts    The attributes.
	 * @param null|string $content The content.
	 * @return string
	 */
	public function fea_fancy_tabs( $atts, $content = null ) {

		$this->tabs_identifier = 'fea-fancy-tabs-' . uniqid();

		global $fusion_settings;

		$defaults = FusionBuilder::set_shortcode_defaults(
			array(
				'class'          => '',
				'id'             => '',
				'inactivecolor'  => '',
				'activecolor'    => '',
				'hovercolor'     => '',
				'arrowupcolor'   => '',
				'arrowdowncolor' => '',
				'headingcolor'   => '',
				'captioncolor'   => '',
				'border'         => '',
				'heading_size'   => '',
				'caption_size'   => '',
				'active_tab'     => 1,

			),
			$atts
		);

		extract( $defaults );

		$this->fea_fancy_tabs_args = $defaults;

		$atts = $defaults;

		$content = preg_replace( '/tab\][^\[]*/', 'tab]', $content );
		$content = preg_replace( '/^[^\[]*\[/', '[', $content );

		$this->parse_tab_parameter( $content, 'fea_fancy_tab' );

		$shortcode_wrapper  = '[fea_raw_fancy_tabs class="' . $atts['class'] . '" id="' . $atts['id'] . '"
		inactivecolor ="' . $atts['inactivecolor'] . '" activecolor ="' . $atts['activecolor'] . '" hovercolor="' . $atts['hovercolor'] . '" arrowupcolor="' . $atts['arrowupcolor'] . '" arrowdowncolor="' . $atts['arrowdowncolor'] . '" headingcolor="' . $atts['headingcolor'] . '"
		captioncolor="' . $atts['captioncolor'] . '" border="' . $atts['border'] . '" heading_size="' . $atts['heading_size'] . '" caption_size="' . $atts['caption_size'] . '"
		active_tab="' . $active_tab . '"]';
		$shortcode_wrapper .= $content;
		$shortcode_wrapper .= '[/fea_raw_fancy_tabs]';

		return do_shortcode( $shortcode_wrapper );
	}


	/**
	 * Returns the fusion-tab.
	 *
	 * @access public
	 * @since 1.0
	 * @param array       $atts    The attributes.
	 * @param null|string $content The content.
	 * @return string
	 */
	public function fea_fancy_tab( $atts, $content = null ) {

		$defaults = FusionBuilder::set_shortcode_defaults(
			array(
				'fea_tab_title'      => '',
				'fea_tab_icon_image' => '',
				'fea_tab_caption'    => '',
				'unique_id'          => '',
			),
			$atts
		);

		extract( $defaults );
		$this->fusion_fancy_tab_args = $defaults;

		$atts = $defaults;

		// Create unique tab id for linking.
		$sanitized_title = hash( 'md5', $fea_tab_title, false );
		$sanitized_title = 'tab' . str_replace( '-', '_', $sanitized_title );
		$unique_id       = $this->tab_counter;

		if ( (int) $unique_id === (int) $this->active ) {
			$shortcode_wrapper = '[fea_raw_fancy_tab id="' . esc_attr( $unique_id ) . '"  fea_fancy_tab_active="yes"]' . do_shortcode( $content ) . '[/fea_raw_fancy_tab]';
		} else {
			$shortcode_wrapper = '[fea_raw_fancy_tab id="' . esc_attr( $unique_id ) . '"  fea_fancy_tab_active="no"]' . do_shortcode( $content ) . '[/fea_raw_fancy_tab]';
		}

		$this->tab_counter++;

		return do_shortcode( $shortcode_wrapper );
	}




	/**
	 * Parses the tab parameters.
	 *
	 * @access public
	 * @since 1.0
	 * @param string $content The content.
	 * @param string $shortcode The shortcode.
	 * @param array  $args      The arguments.
	 */
	public function parse_tab_parameter( $content, $shortcode, $args = null ) {
		$preg_match_tabs_single = preg_match_all( FusionBuilder::get_shortcode_regex( $shortcode ), $content, $tabs_single );
		$i                      = 0;
		if ( is_array( $tabs_single[0] ) ) {
			foreach ( $tabs_single[0] as $key => $tab ) {

				if ( is_array( $args ) ) {
					$preg_match_titles = preg_match_all( '/' . $shortcode . ' id=([0-9]+)/i', $tab, $ids );

					if ( array_key_exists( '0', $ids[1] ) ) {
						$id = $ids[1][0];
					} else {
						$title = 'default';
					}

					foreach ( $args as $key => $value ) {
						if ( 'tab' . $id === $key ) {
							$title = $value;
						}
					}
				} else {

					$preg_match_titles = preg_match_all( '/' . $shortcode . ' fea_tab_title="([^\"]+)"/i', $tab, $titles );

					$title = ( array_key_exists( '0', $titles[1] ) ) ? $titles[1][0] : '';
				}
				$preg_match_icons = preg_match_all( '/( id=[0-9]+| fea_tab_title="[^\"]+")? fea_tab_icon_image="([^\"]+)"/i', $tab, $icons );

				$icon = ( array_key_exists( '0', $icons[2] ) ) ? $icons[2][0] : '';

				if ( '' === $icon && ! empty( $this->fea_fancy_tabs_args['icon'] ) ) {
					$icon = $this->fea_fancy_tabs_args['icon'];
				}

				// for Caption.

				$preg_match_iconsc = preg_match_all( '/( id=[0-9]+| fea_tab_title="[^\"]+")? fea_tab_caption="([^\"]+)"/i', $tab, $caption );

				$cap = ( array_key_exists( '0', $caption[2] ) ) ? $caption[2][0] : '';

				// Create unique tab id for linking.
				$sanitized_title = hash( 'md5', $title, false );
				$sanitized_title = 'tab' . str_replace( '-', '_', $sanitized_title );

				// Create array for every single tab shortcode.
				$this->tabs[] = array(
					'title'     => $title,
					'icon'      => $icon,
					'unique_id' => $this->tab_counter,
					'caption'   => $cap,

				);
				$this->tab_counter++;
				$i++;
			}

			$this->tab_counter = 1;
		}
	}

}
