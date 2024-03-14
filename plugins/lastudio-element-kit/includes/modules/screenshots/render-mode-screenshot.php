<?php
namespace LaStudioKitThemeBuilder\Modules\Screenshots;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Elementor\Core\Frontend\RenderModes\Render_Mode_Base;
use LaStudioKitThemeBuilder\Modules\ThemeBuilder\Documents\Theme_Document;

class Render_Mode_Screenshot extends Render_Mode_Base {
	const ENQUEUE_SCRIPTS_PRIORITY = 1000;

	public static function get_name() {
		return 'screenshot';
	}

	public function prepare_render() {

        $post_id = $this->post_id;

		parent::prepare_render();

        $this->switch_to_preview_query();

        add_filter('lastudio-kit/document/wrapper_attributes', function ( $attr ) use ($post_id){
            $attr['class'] .= ' elementor-' . $post_id;
            $attr['class'] .= ' post-' . $post_id;
            $attr['data-elementor-id'] = $post_id;
            return $attr;
        });

		show_admin_bar( false );

		remove_filter(
			'the_content',
			[ \LaStudioKitThemeBuilder\Modules\ThemeBuilder\Module::instance()->get_locations_manager(), 'builder_wrapper' ],
			9999999
		);

		add_filter( 'template_include', [ $this, 'filter_template' ], 20 );

        add_action( 'wp_head', [ $this, 'render_pointer_event_style' ] );
	}

	public function filter_template() {
		return ELEMENTOR_PATH . 'modules/page-templates/templates/canvas.php';
	}

    /**
     * disable all the interactions in the preview render mode.
     */
    public function render_pointer_event_style() {
        ?>
        <style>
          .lakit-nav__sub{display:none}
          .elementor-location-archive,
          .elementor-location-single {
            padding-top: 8vh;
            padding-bottom: 8vh;
          }
          .col-row > [class*=col-desk-] > * {
            opacity: 1 !important;
            animation: none !important;
          }
          .lakit-embla__slide img {
            display: none;
          }
          .lakit-embla__slide{
            padding-bottom: 100%;
            display: block;
            background: #f9f9f9;
          }
        </style>
        <?php
    }

	public function is_static() {
		return true;
	}

	public function enqueue_scripts() {

		$suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG || defined( 'ELEMENTOR_TESTS' ) && ELEMENTOR_TESTS ) ? '' : '.min';
		wp_enqueue_script(
			'dom-to-image',
			lastudio_kit()->plugin_url("includes/modules/screenshots/assets/dom-to-image/js/dom-to-image{$suffix}.js"),
			[],
			'2.6.0',
			true
		);

		wp_enqueue_script(
			'html2canvas',
            lastudio_kit()->plugin_url( "includes/modules/screenshots/assets/html2canvas/js/html2canvas{$suffix}.js"),
			[],
			'1.0.0-rc.5',
			true
		);

		$isDebug = false;

//        $suffix = '';
//        $isDebug = true;
		wp_enqueue_script(
			'elementor-screenshot',
            lastudio_kit()->plugin_url("includes/modules/screenshots/assets/screenshot{$suffix}.js"),
			[ 'dom-to-image', 'html2canvas' ],
            lastudio_kit()->get_version(true),
			true
		);

		$config = [
			'selector' => '.elementor-' . $this->post_id,
			'nonce' => wp_create_nonce( Module::SCREENSHOT_PROXY_NONCE_ACTION ),
			'home_url' => home_url(),
			'post_id' => $this->post_id,
			'isDebug' => $isDebug,
			'excluded_external_css_urls' => ['https://kit-pro.fontawesome.com', 'https://use.typekit.net']
		];

		wp_add_inline_script( 'elementor-screenshot', 'var ElementorScreenshotConfig = ' . wp_json_encode( $config ) . ';' );
	}

    /**
     * @access public
     */
    public function switch_to_preview_query() {
        $current_post_id = get_the_ID();
        $document = lastudio_kit()->elementor()->documents->get_doc_or_auto_save( $current_post_id );

        if ( ! $document || ! $document instanceof Theme_Document ) {
            return;
        }

        if($document->get_template_type()  !== 'product'){
            return;
        }

        $new_query_vars = $document->get_preview_as_query_args();

        lastudio_kit()->elementor()->db->switch_to_query( $new_query_vars, true );

        $document->after_preview_switch_to_query();

    }
}
