<?php

namespace QuadLayers\QuadMenu\Integrations;

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

use QuadLayers\QuadMenu\Integrations\Beaver\Module;

	/**
	 * Beaver ex QuadMenu_Beaver Class
	 */
class Beaver {

	private static $instance;

	public function __construct() {
		// add_filter('wp_nav_menu_args', array($this, 'beaver'), 10, 1);
		add_filter( 'fl_get_wp_widgets_exclude', array( $this, 'exclude' ) );
		add_action( 'init', array( $this, 'module' ) );
		add_action( 'wp_footer', array( $this, 'footer' ) );
	}

	function exclude( $exclude ) {
		$exclude[] = '\\QuadLayers\\QuadMenu\\Widget';

		return $exclude;
	}

	function module() {
		if ( class_exists( '\\FLBuilderModule' ) ) {
			Module::instance();
		  require_once 'beaver/module.php';
		}
	}

	function footer() {
		if ( ! class_exists( '\\FLBuilderModel' ) ) {
			return;
		}
		if ( ! \FLBuilderModel::is_builder_active() ) {
			return;
		}
		?>
		<script>
		jQuery(function ($) {

			$(document).ajaxComplete(function (event, xhr, settings) {

				var document = this,
						response = JSON.parse(xhr.responseText);

				if ($('nav#quadmenu', $(response.html)).length) {

					setTimeout(function () {
						$('nav#quadmenu', $(document)).quadmenu();
					}, 100);

				}

			});
		});
		</script>
		<?php

	}

	public static function instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/*
	function beaver($args) {

	if (class_exists('FLBuilder') && class_exists('FLTheme')) {

	if (empty($args['theme_location'])) {

	$args['theme_location'] = 'header';

	$header_layout = FLTheme::get_setting('fl-header-layout');

	$args['layout'] = 'inherit';
	$args['layout_classes'] = 'js';

	if (in_array($header_layout, array('vertical-right', 'vertical-left'))) {
	$args['layout'] = 'inherit';
	}

	if (in_array($header_layout, array('right', 'left'))) {
	$args['layout_align'] = $header_layout;
	}

	if ($header_layout == 'centered') {
	$args['layout_align'] = 'center';
	}
	}
	}

	return $args;
	}
	*/
}
