<?php
/**
 * order PDF builder class.
 *
 * @since 1.8.0
 */

namespace Masteriyo\PDF\Order;

use Mpdf\HTMLParserMode;
use Mpdf\Mpdf;
use Mpdf\Output\Destination;

class OrderPDF {
	/**
	 * The Mpdf instance.
	 *
	 * @since 1.8.0
	 *
	 * @var \Mpdf\Mpdf
	 */
	public $mpdf;

	/**
	 * Order ID.
	 *
	 * @since 1.8.0
	 *
	 * @var integer
	 */
	protected $order_id;

	/**
	 * Student ID.
	 *
	 * @since 1.8.0
	 *
	 * @var integer
	 */
	protected $student_id;

	/**
	 * order template html.
	 *
	 * @since 1.8.0
	 *
	 * @var string
	 */
	protected $template;

	/**
	 * Contains all the css.
	 *
	 * @var array
	 */
	protected $styles = array();

	/**
	 * Contain the html blocks
	 *
	 * @var array
	 */
	protected $html = array();

	/**
	 * True if the order preview is being generated. Otherwise false.
	 *
	 * @since 1.8.0
	 *
	 * @var boolean
	 */
	protected $preview = false;

	/**
	 * Constructor.
	 *
	 * @since 1.8.0
	 *
	 * @param integer $order_id
	 * @param integer $student_id
	 * @param string $template
	 */
	public function __construct( $order_id, $student_id, $template ) {
		$this->set_order_id( $order_id );
		$this->set_student_id( $student_id );
		$this->set_template( $template );
	}

	/**
	 * Initialize mpdf.
	 *
	 * @since 1.8.0
	 */
	public function init_mpdf() {
		if ( $this->mpdf instanceof Mpdf ) {
			return;
		}

		$upload_dir = wp_upload_dir();

		$font_dirs   = ( new \Mpdf\Config\ConfigVariables() )->getDefaults()['fontDir'];
		$font_dirs[] = $upload_dir['basedir'] . '/masteriyo/certificate-fonts';

		$default_font_config = ( new \Mpdf\Config\FontVariables() )->getDefaults();
		$fontdata            = $default_font_config['fontdata'];

		$this->mpdf = new Mpdf(
			array(
				'tempDir'          => masteriyo_get_temp_dir() . '/mpdf',
				'fontDir'          => $font_dirs,
				'margin_left'      => 0,
				'margin_right'     => 0,
				'margin_top'       => 0,
				'margin_bottom'    => 0,
				'default_font'     => 'Arial, sans-serif',
				'autoScriptToLang' => true,
				'autoLangToFont'   => true,
				'fontdata'         => $fontdata + array(
					'cinzel'              => array(
						'R' => 'Cinzel-VariableFont_wght.ttf',
					),
					'dejavusanscondensed' => array(
						'R' => 'DejaVuSansCondensed.ttf',
						'B' => 'DejaVuSansCondensed-Bold.ttf',
					),
					'dmsans'              => array(
						'R' => 'DMSans-Regular.ttf',
						'B' => 'DMSans-Bold.ttf',
						'I' => 'DMSans-Italic.ttf',
					),
					'greatvibes'          => array(
						'R' => 'GreatVibes-Regular.ttf',
					),
					'grenzegotisch'       => array(
						'R' => 'GrenzeGotisch-VariableFont_wght.ttf',
					),
					'librebaskerville'    => array(
						'R' => 'LibreBaskerville-Regular.ttf',
						'B' => 'LibreBaskerville-Bold.ttf',
						'I' => 'LibreBaskerville-Italic.ttf',
					),
					'lora'                => array(
						'R' => 'Lora-VariableFont_wght.ttf',
						'I' => 'Lora-Italic-VariableFont_wght.ttf',
					),
					'poppins'             => array(
						'R' => 'Poppins-Regular.ttf',
						'B' => 'Poppins-Bold.ttf',
						'I' => 'Poppins-Italic.ttf',
					),
					'roboto'              => array(
						'R' => 'Roboto-Regular.ttf',
						'B' => 'Roboto-Bold.ttf',
						'I' => 'Roboto-Italic.ttf',
					),
					'abhayalibre'         => array(
						'R' => 'AbhayaLibre-Regular.ttf',
						'B' => 'AbhayaLibre-Bold.ttf',
					),
					'adinekirnberg'       => array(
						'R' => 'AdineKirnberg.ttf',
					),
					'alexbrush'           => array(
						'R' => 'AlexBrush-Regular.ttf',
					),
					'allura'              => array(
						'R' => 'Allura-Regular.ttf',
					),
				),
			)
		);
		$this->mpdf->setMBencoding( 'UTF-8' );

		/**
		 * Filters mpdf debug mode for making order PDF file.
		 *
		 * @since 1.8.0
		 *
		 * @param boolean $bool
		 * @param \Mpdf\Mpdf $mpdf
		 */
		$this->mpdf->debug = apply_filters( 'masteriyo_order_mpdf_debug_mode', false, $this->mpdf );

		/**
		 * Filters mpdf image debug mode for making order PDF file.
		 *
		 * @since 1.8.0
		 *
		 * @param boolean $bool
		 * @param \Mpdf\Mpdf $mpdf
		 */
		$this->mpdf->showImageErrors = apply_filters( 'masteriyo_order_mpdf_show_image_errors', false, $this->mpdf );

		/**
		 * Filters Mpdf class instance used for making order PDF file.
		 *
		 * @since 1.8.0
		 *
		 * @param boolean $bool
		 * @param \Mpdf\Mpdf $mpdf
		 */
		$this->mpdf = apply_filters( 'masteriyo_order_builder_mpdf', $this->mpdf );
	}

	/**
	 * Prepare PDF.
	 *
	 * @since 1.8.0
	 *
	 * @since 1.8.0  Added $is_preview argument.
	 *
	 * @param string $template The order template.
	 * @param boolean $is_preview
	 *
	 * @return true|\WP_Error
	 */
	public function prepare_pdf( $is_preview = false ) {
		$this->init_mpdf();
		$this->set_is_preview( $is_preview );

		$template = str_replace( 'https:', 'http:', $this->get_template() );

		$this->mpdf->WriteHTML( $this->prepare_css(), HTMLParserMode::HEADER_CSS );
		$this->mpdf->WriteHTML( $this->prepare_fonts_css(), HTMLParserMode::HEADER_CSS );
		$this->mpdf->WriteHTML( $this->prepare_html() );
	}

	/**
	 * Serve order preview.
	 *
	 * @since 1.8.0
	 */
	public function serve_preview() {
		$result = $this->prepare_pdf( true );

		if ( is_wp_error( $result ) ) {
			wp_die( esc_html( $result->get_error_message() ) );
		}

		$this->mpdf->Output( $this->make_filename( true ), Destination::INLINE );
		die;
	}

	/**
	 * Serve order download.
	 *
	 * @since 1.8.0
	 */
	public function serve_download() {
		$result = $this->prepare_pdf( false );

		if ( is_wp_error( $result ) ) {
			wp_die( esc_html( $result->get_error_message() ) );
		}
		$this->mpdf->Output( $this->make_filename(), Destination::DOWNLOAD );
		die;
	}

	/**
	 * Make order filename.
	 *
	 * @since 1.8.0
	 *
	 * @param boolean $is_preview
	 *
	 * @return string
	 */
	public function make_filename( $is_preview = false ) {
		$course   = masteriyo_get_course( $this->get_order_id() );
		$student  = masteriyo_get_user( $this->get_student_id() );
		$filename = 'order-' . get_bloginfo( 'name' );

		if ( ! is_null( $course ) && ! is_null( $student ) && ! is_wp_error( $student ) ) {
			$filename = sprintf( '%s - %s - %s', $student->get_username(), $course->get_name(), get_bloginfo( 'name' ) );
		}

		if ( $is_preview ) {
			$filename .= ' - preview';
		}

		$filename = sanitize_file_name( $filename . '.pdf' );

		/**
		 * Filters order PDF filename.
		 *
		 * @since 1.8.0
		 *
		 * @param string $filename
		 * @param \Masteriyo\Addons\order\PDF\orderPDF $order_pdf_instance
		 * @param boolean $is_preview
		 */
		return apply_filters( 'masteriyo_order_pdf_filename', $filename, $this, $is_preview );
	}

	/**
	 * Add a CSS statement.
	 *
	 * @since 1.8.0
	 *
	 * @param string $selector CSS selector.
	 * @param string $css_property The CSS property name.
	 * @param string $value The CSS property value.
	 */
	public function add_style( $selector, $css_property = null, $value = null ) {
		if ( ! isset( $this->styles[ $selector ] ) ) {
			$this->styles[ $selector ] = array();
		}
		$this->styles[ $selector ][ $css_property ] = $value;
	}

	/**
	 * Prepare CSS.
	 *
	 * @since 1.8.0
	 *
	 * @return string
	 */
	public function prepare_css() {
		$css = array();

		foreach ( $this->styles as $selector => $style ) {
			$css[] = $selector . ' {';

			foreach ( $style as $key => $val ) {
				$css [] = sprintf( '%s: %s;', $key, $val );
			}
			$css [] = '}';
		}

		$css [] = masteriyo_get_filesystem()->get_contents( MASTERIYO_ASSETS . '/css/gutenberg-styles.css' );

		return implode( PHP_EOL, $css );
	}

	/**
	 * Prepare fonts css.
	 *
	 * @since 1.8.2
	 *
	 * @return string
	 */
	public function prepare_fonts_css() {
		$css = '';
		foreach ( array_keys( masteriyo_get_certificate_font_urls() ) as $font_family ) {
			$css .= '.has-' . masteriyo_camel_to_kebab( $font_family ) . '-font-family {font-family: ' . strtolower( $font_family ) . ';}';
		}
		return $css;
	}

	/**
	 * Add HTML markup.
	 *
	 * @since 1.8.0
	 *
	 * @param string $html
	 */
	public function add_html( $html ) {
		$this->html[] = $html;
	}

	/**
	 * Output the content
	 *
	 * @since 1.8.0
	 *
	 * @return string
	 */
	public function prepare_html() {
		return implode( PHP_EOL, $this->html );
	}

	/**
	 * Set 'preview' property.
	 *
	 * True if a order preview is being generated. Otherwise false.
	 *
	 * @since 1.8.0
	 *
	 * @param boolean $is_preview
	 */
	public function set_is_preview( $is_preview ) {
		$this->preview = $is_preview;
	}

	/**
	 * Set order ID.
	 *
	 * @since 1.8.0
	 *
	 * @param integer $order_id
	 */
	public function set_order_id( $order_id ) {
		$this->order_id = $order_id;
	}

	/**
	 * Set student ID.
	 *
	 * @since 1.8.0
	 *
	 * @param integer $student_id
	 */
	public function set_student_id( $student_id ) {
		$this->student_id = $student_id;
	}

	/**
	 * Set template html.
	 *
	 * @since 1.8.0
	 *
	 * @param string $template
	 */
	public function set_template( $template ) {
		$this->template = $template;
	}

	/**
	 * Get preview property.
	 *
	 * True if a order preview is being generated. Otherwise false.
	 *
	 * @since 1.8.0
	 *
	 * @return boolean
	 */
	public function is_preview() {
		return $this->preview;
	}

	/**
	 * Get order ID.
	 *
	 * @since 1.8.0
	 *
	 * @return integer
	 */
	public function get_order_id() {
		return $this->order_id;
	}

	/**
	 * Get student ID.
	 *
	 * @since 1.8.0
	 *
	 * @return integer
	 */
	public function get_student_id() {
		return $this->student_id;
	}

	/**
	 * Get template html.
	 *
	 * @since 1.8.0
	 *
	 * @return string
	 */
	public function get_template() {
		return $this->template;
	}
}
