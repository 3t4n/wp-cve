<?php
/**
 *  Object that stores integrations objects.
 *
 * @package SurferSEO
 * @link https://surferseo.com
 */

namespace SurferSEO\Surfer\Integrations;

use Elementor\Controls_Manager;
use Elementor\Core\DocumentTypes\PageBase;

/**
 * Content exporter object.
 */
class Elementor {



	/**
	 * The identifier for the elementor tab.
	 */
	const SURFER_TAB = 'surfer-tab';

	/**
	 * Object construct.
	 */
	public function __construct() {

		add_action( 'init', array( $this, 'init_hooks' ) );
	}

	/**
	 * Initialize hooks.
	 */
	public function init_hooks() {

		add_action( 'elementor/editor/after_enqueue_styles', array( $this, 'enqueue_scripts_and_styles_in_elementor' ) );

		// We are too late for elementor/init. We should see if we can be on time, or else this workaround works (we do always get the "else" though).
		if ( ! \did_action( 'elementor/init' ) ) {
			\add_action( 'elementor/init', array( $this, 'add_surfer_panel_tab' ) );
		} else {
			$this->add_surfer_panel_tab();
		}

		add_action( 'elementor/documents/register_controls', array( $this, 'register_document_controls' ) );
	}

	/**
	 * Enqueue scripts and styles in Elementor.
	 */
	public function enqueue_scripts_and_styles_in_elementor() {

		wp_enqueue_style( 'surfer-elementor-integration', Surfer()->get_baseurl() . 'assets/css/surfer-elementor-integration.css', array(), SURFER_VERSION );

		Surfer()->get_surfer()->enqueue_surfer_react_apps();
	}

	/**
	 * Register a panel tab slug, in order to allow adding controls to this tab.
	 */
	public function add_surfer_panel_tab() {
		Controls_Manager::add_tab( $this::SURFER_TAB, 'Surfer' );
	}

	/**
	 * Register additional document controls.
	 *
	 * https://developers.elementor.com/docs/editor/page-settings-panel/
	 *
	 * @param PageBase $document The PageBase document.
	 */
	public function register_document_controls( $document ) {

		// PageBase is the base class for documents like `post` `page` and etc.
		if ( ! $document instanceof PageBase || ! $document::get_property( 'has_elements' ) ) {
			return;
		}

		$this->add_exporter_box( $document );
		$this->add_keyword_research_box( $document );
	}

	/**
	 * Creates box inside Elementor editor.
	 *
	 * @param PageBase $document The PageBase document.
	 */
	private function add_exporter_box( $document ) {

		$raw_html  = '<script src="' . Surfer()->get_baseurl() . 'assets/js/surfer-general.js"></script>';
		$raw_html .= '<link type="text/css" rel="stylesheet" href="' . Surfer()->get_baseurl() . 'assets/css/surferseo.css"></style>';
		$raw_html .= '<link type="text/css" rel="stylesheet" href="' . Surfer()->get_baseurl() . 'assets/css/admin.css"></style>';
		$raw_html .= '<div id="surfer-content-export-box"></div>';

		$document->start_controls_section(
			'surfer_content_export_section',
			array(
				'label' => 'Export Content to Surfer',
				'tab'   => self::SURFER_TAB,
			)
		);

		$document->add_control(
			'content_export',
			array(
				'type'      => Controls_Manager::RAW_HTML,
				'raw'       => $raw_html,
				'separator' => 'none',
			)
		);

		$document->end_controls_section();
	}

	/**
	 * Adds keyword research box to Elementor editor.
	 *
	 * @param PageBase $document The PageBase document.
	 */
	private function add_keyword_research_box( $document ) {

		$raw_html  = '<script src="' . Surfer()->get_baseurl() . 'assets/js/surfer-general.js"></script>';
		$raw_html .= '<link type="text/css" rel="stylesheet" href="' . Surfer()->get_baseurl() . 'assets/css/surferseo.css"></style>';
		$raw_html .= '<link type="text/css" rel="stylesheet" href="' . Surfer()->get_baseurl() . 'assets/css/admin.css"></style>';
		$raw_html .= '<div id="surfer-keyword-surfer"></div>';

		$document->start_controls_section(
			'surfer_keyword_research_section',
			array(
				'label' => 'Keyword Surfer',
				'tab'   => self::SURFER_TAB,
			)
		);

		$document->add_control(
			'keyword_research',
			array(
				// 'label'        => esc_html__( 'Keywrod Research', 'surferseo' ),
				'type'      => Controls_Manager::RAW_HTML,
				// 'return_value' => 'open',
				'raw'       => $raw_html,
				'separator' => 'none',
			)
		);

		$document->end_controls_section();
	}
}
