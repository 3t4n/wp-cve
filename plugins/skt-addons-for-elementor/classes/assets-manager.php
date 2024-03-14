<?php

namespace Skt_Addons_Elementor\Elementor;

use Elementor\Core\Files\CSS\Post as Post_CSS;
use Elementor\Core\Settings\Manager as SettingsManager;

defined('ABSPATH') || die();

class Assets_Manager {

	/**
	 * Bind hook and run internal methods here
	 */
	public static function init() {
		// Frontend scripts
		add_action('wp_enqueue_scripts', [__CLASS__, 'frontend_register']);
		add_action('wp_enqueue_scripts', [__CLASS__, 'frontend_enqueue'], 100);
		add_action('elementor/css-file/post/enqueue', [__CLASS__, 'frontend_enqueue_exceptions']);

		// Edit and preview enqueue
		add_action('elementor/preview/enqueue_styles', [__CLASS__, 'enqueue_preview_styles']);

		// Enqueue editor scripts
		add_action('elementor/editor/after_enqueue_scripts', [__CLASS__, 'editor_enqueue']);

		// Paragraph toolbar registration
		add_filter('elementor/editor/localize_settings', [__CLASS__, 'add_inline_editing_intermediate_toolbar']);
	}

	/**
	 * Register inline editing paragraph toolbar
	 *
	 * @param array $config
	 * @return array
	 */
	public static function add_inline_editing_intermediate_toolbar($config) {
		if (!isset($config['inlineEditing'])) {
			return $config;
		}

		$tools = [
			'bold',
			'underline',
			'italic',
			'createlink',
		];

		if (isset($config['inlineEditing']['toolbar'])) {
			$config['inlineEditing']['toolbar']['intermediate'] = $tools;
		} else {
			$config['inlineEditing'] = [
				'toolbar' => [
					'intermediate' => $tools,
				],
			];
		}

		return $config;
	}

	/**
	 * Register frontend assets.
	 *
	 * Frontend assets handler will be used in widgets map
	 * to load widgets assets on demand.
	 *
	 * @return void
	 */
	public static function frontend_register() {
		$suffix = skt_addons_elementor_is_script_debug_enabled() ? '.' : '.min.';

		wp_register_style(
			'skt-icons',
			SKT_ADDONS_ELEMENTOR_ASSETS . 'fonts/style.min.css',
			null,
			SKT_ADDONS_ELEMENTOR_VERSION
		);

		wp_register_style(
			'twentytwenty',
			SKT_ADDONS_ELEMENTOR_ASSETS . 'vendor/twentytwenty/css/twentytwenty.css',
			null,
			SKT_ADDONS_ELEMENTOR_VERSION
		);

		wp_register_script(
			'jquery-event-move',
			SKT_ADDONS_ELEMENTOR_ASSETS . 'vendor/twentytwenty/js/jquery.event.move.js',
			['jquery'],
			SKT_ADDONS_ELEMENTOR_VERSION,
			true
		);

		wp_register_script(
			'jquery-twentytwenty',
			SKT_ADDONS_ELEMENTOR_ASSETS . 'vendor/twentytwenty/js/jquery.twentytwenty.js',
			['jquery-event-move'],
			SKT_ADDONS_ELEMENTOR_VERSION,
			true
		);

		// Justified Grid
		wp_register_style(
			'justifiedGallery',
			SKT_ADDONS_ELEMENTOR_ASSETS . 'vendor/justifiedGallery/css/justifiedGallery.min.css',
			null,
			SKT_ADDONS_ELEMENTOR_VERSION
		);

		wp_register_script(
			'jquery-justifiedGallery',
			SKT_ADDONS_ELEMENTOR_ASSETS . 'vendor/justifiedGallery/js/jquery.justifiedGallery.min.js',
			['jquery'],
			SKT_ADDONS_ELEMENTOR_VERSION,
			true
		);

		// Carousel and Slider
		wp_register_style(
			'slick',
			SKT_ADDONS_ELEMENTOR_ASSETS . 'vendor/slick/slick.css',
			null,
			SKT_ADDONS_ELEMENTOR_VERSION
		);

		wp_register_style(
			'slick-theme',
			SKT_ADDONS_ELEMENTOR_ASSETS . 'vendor/slick/slick-theme.css',
			null,
			SKT_ADDONS_ELEMENTOR_VERSION
		);

		wp_register_script(
			'jquery-slick',
			SKT_ADDONS_ELEMENTOR_ASSETS . 'vendor/slick/slick.min.js',
			['jquery'],
			SKT_ADDONS_ELEMENTOR_VERSION,
			true
		);

		// Masonry grid
		wp_register_script(
			'jquery-isotope',
			SKT_ADDONS_ELEMENTOR_ASSETS . 'vendor/jquery.isotope.js',
			['jquery'],
			SKT_ADDONS_ELEMENTOR_VERSION,
			true
		);

		// Number animation
		wp_register_script(
			'jquery-numerator',
			SKT_ADDONS_ELEMENTOR_ASSETS . 'vendor/jquery-numerator/jquery-numerator.min.js',
			['jquery'],
			SKT_ADDONS_ELEMENTOR_VERSION,
			true
		);

		// Magnific popup
		wp_register_style(
			'magnific-popup',
			SKT_ADDONS_ELEMENTOR_ASSETS . 'vendor/magnific-popup/magnific-popup.css',
			null,
			SKT_ADDONS_ELEMENTOR_VERSION
		);

		wp_register_script(
			'jquery-magnific-popup',
			SKT_ADDONS_ELEMENTOR_ASSETS . 'vendor/magnific-popup/jquery.magnific-popup.min.js',
			null,
			SKT_ADDONS_ELEMENTOR_VERSION,
			true
		);
		
		// keyframes
		wp_register_script(
			'jquery-keyframes',
			SKT_ADDONS_ELEMENTOR_ASSETS . 'vendor/keyframes/jquery.keyframes.min.js',
			['jquery'],
			SKT_ADDONS_ELEMENTOR_VERSION,
			true
		);

		// Chart.js
		wp_register_script(
			'chart-js',
			SKT_ADDONS_ELEMENTOR_ASSETS . 'vendor/chart/chart.min.js',
			['jquery'],
			SKT_ADDONS_ELEMENTOR_VERSION,
			true
		);

		// Threesixty Rotation js
		wp_register_script(
			'circlr',
			SKT_ADDONS_ELEMENTOR_ASSETS . 'vendor/threesixty-rotation/circlr.min.js',
			['jquery'],
			SKT_ADDONS_ELEMENTOR_VERSION,
			true
		);

		// skt magnify js
		wp_register_script(
			'skt-simple-magnify',
			SKT_ADDONS_ELEMENTOR_ASSETS . 'vendor/threesixty-rotation/skt-simple-magnify.js',
			['jquery'],
			SKT_ADDONS_ELEMENTOR_VERSION,
			true
		);

		// fullcalendar js
		wp_register_script(
			'skt-fullcalendar',
			SKT_ADDONS_ELEMENTOR_ASSETS . 'vendor/fullcalendar/fullcalendar.min.js',
			['jquery'],
			SKT_ADDONS_ELEMENTOR_VERSION,
			true
		);

		// fullcalendar language js
		wp_register_script(
			'skt-fullcalendar-locales',
			SKT_ADDONS_ELEMENTOR_ASSETS . 'vendor/fullcalendar/locales-all.min.js',
			['jquery'],
			SKT_ADDONS_ELEMENTOR_VERSION,
			true
		);

		// fullcalendar css
		wp_register_style(
			'skt-fullcalendar',
			SKT_ADDONS_ELEMENTOR_ASSETS . 'vendor/fullcalendar/fullcalendar.min.css',
			null,
			SKT_ADDONS_ELEMENTOR_VERSION
		);

		// Hover css
		wp_register_style(
			'hover-css',
			SKT_ADDONS_ELEMENTOR_ASSETS . 'vendor/hover-css/hover-css.css',
			null,
			SKT_ADDONS_ELEMENTOR_VERSION
		);

		// Sharer JS
		wp_register_script(
			'sharer-js',
			SKT_ADDONS_ELEMENTOR_ASSETS . 'vendor/sharer-js/sharer.min.js',
			['jquery'],
			SKT_ADDONS_ELEMENTOR_VERSION,
			true
		);

		/////////////////////////////////////////////////////////////////////////

		// datatables.js
		wp_register_script(
			'data-table',
			SKT_ADDONS_ELEMENTOR_ASSETS . 'vendor/data-table/datatables.min.js',
			['jquery'],
			SKT_ADDONS_ELEMENTOR_VERSION
		);

		//Countdown
		// Unregister first to load our own countdown version
		wp_deregister_script( 'jquery-countdown' );
		wp_register_script(
			'jquery-countdown',
			SKT_ADDONS_ELEMENTOR_ASSETS . 'vendor/countdown/js/countdown.js',
			[ 'jquery' ],
			SKT_ADDONS_ELEMENTOR_VERSION,
			true
		);

		// animate.css
		wp_register_style(
			'animate-css',
			SKT_ADDONS_ELEMENTOR_ASSETS . 'vendor/animate-css/main.min.css',
			[],
			SKT_ADDONS_ELEMENTOR_VERSION
		);

		// Prism
		wp_register_style(
			'prism',
			SKT_ADDONS_ELEMENTOR_ASSETS . 'vendor/prism/css/prism.min.css',
			[],
			SKT_ADDONS_ELEMENTOR_VERSION
		);

		wp_register_script(
			'prism',
			SKT_ADDONS_ELEMENTOR_ASSETS . 'vendor/prism/js/prism.js',
			[ 'jquery' ],
			SKT_ADDONS_ELEMENTOR_VERSION,
			true
		);

		// Plyr: video player plugin
		wp_register_style(
			'plyr',
			SKT_ADDONS_ELEMENTOR_ASSETS . 'vendor/plyr/plyr.min.css',
			[],
			SKT_ADDONS_ELEMENTOR_VERSION
		);

		wp_register_script(
			'plyr',
			SKT_ADDONS_ELEMENTOR_ASSETS . 'vendor/plyr/plyr.min.js',
			[ 'jquery' ],
			SKT_ADDONS_ELEMENTOR_VERSION,
			true
		);
		
		// owl carousel
		wp_register_style(
			'owl-carousel',
			SKT_ADDONS_ELEMENTOR_ASSETS . 'vendor/owl/owl.carousel.min.css',
			[],
			SKT_ADDONS_ELEMENTOR_VERSION
		);

		wp_register_style(
			'owl-theme-default',
			SKT_ADDONS_ELEMENTOR_ASSETS . 'vendor/owl/owl.theme.default.min.css',
			[],
			SKT_ADDONS_ELEMENTOR_VERSION
		);

		wp_register_style(
			'owl-animate',
			SKT_ADDONS_ELEMENTOR_ASSETS . 'vendor/owl/animate.min.css',
			[],
			SKT_ADDONS_ELEMENTOR_VERSION
		);

		wp_register_script(
			'owl-carousel-js',
			SKT_ADDONS_ELEMENTOR_ASSETS . 'vendor/owl/owl.carousel.min.js',
			['jquery'],
			SKT_ADDONS_ELEMENTOR_VERSION,
			true
		);
		
		/**
		 * Swiperjs library for advanced slider
		 * handler change becasue elementor used older version of swiperjs.
		 * We used latest version which was conflicting.
		 */
		wp_register_script(
			'skt-swiper',
			SKT_ADDONS_ELEMENTOR_ASSETS . 'vendor/swiper/js/swiper-bundle.js',
			[],
			'6.4.5',
			true
		);
		wp_register_style(
			'skt-swiper',
			SKT_ADDONS_ELEMENTOR_ASSETS . 'vendor/swiper/css/swiper-bundle.css',
			[],
			'6.4.5'
		);

		//Animated Text
		wp_register_script(
			'animated-text',
			SKT_ADDONS_ELEMENTOR_ASSETS . 'vendor/animated-text/js/animated-text.js',
			[ 'jquery' ],
			SKT_ADDONS_ELEMENTOR_VERSION,
			true
		);

		// Tipso: tooltip plugin
		wp_register_style(
			'tipso',
			SKT_ADDONS_ELEMENTOR_ASSETS . 'vendor/tipso/tipso.css',
			[],
			SKT_ADDONS_ELEMENTOR_VERSION
		);

		wp_register_script(
			'jquery-tipso',
			SKT_ADDONS_ELEMENTOR_ASSETS . 'vendor/tipso/tipso.js',
			[ 'jquery' ],
			SKT_ADDONS_ELEMENTOR_VERSION,
			true
		);

		// Hamburger.css
		wp_register_style(
			'hamburgers',
			SKT_ADDONS_ELEMENTOR_ASSETS . 'vendor/hamburgers/hamburgers.min.css',
			[],
			SKT_ADDONS_ELEMENTOR_VERSION
		);

		// Nice Select Plugin
		wp_register_style(
			'nice-select',
			SKT_ADDONS_ELEMENTOR_ASSETS . 'vendor/nice-select/nice-select.css',
			[],
			SKT_ADDONS_ELEMENTOR_VERSION
		);

		wp_register_script(
			'jquery-nice-select',
			SKT_ADDONS_ELEMENTOR_ASSETS . 'vendor/nice-select/jquery.nice-select.min.js',
			[ 'jquery' ],
			SKT_ADDONS_ELEMENTOR_VERSION,
			true
		);
		
		// LordIcon JS
		wp_register_script(
			'lord-icon',
			SKT_ADDONS_ELEMENTOR_ASSETS . 'vendor/lord-icon/lord-icon-2.1.0.js',
			[],
			SKT_ADDONS_ELEMENTOR_ASSETS,
			false
		);

		/////////////////////////////////////////////////////////////////////////

		// Skt addons PDF JS 
		wp_register_script(
			'pdf-js',
			SKT_ADDONS_ELEMENTOR_ASSETS . 'vendor/pdfjs/pdfobject.min.js',
			[ 'jquery' ],
			SKT_ADDONS_ELEMENTOR_VERSION,
			false
		);

		// Main assets
		wp_register_style(
			'skt-addons-elementor',
			SKT_ADDONS_ELEMENTOR_ASSETS . 'css/main.css',
			['elementor-frontend'],
			SKT_ADDONS_ELEMENTOR_VERSION
		);

		// Skt addons script
		wp_register_script(
			'skt-addons-elementor',
			SKT_ADDONS_ELEMENTOR_ASSETS . 'js/skt-addons.js',
			['jquery'],
			SKT_ADDONS_ELEMENTOR_VERSION,
			true
		);

		//Localize scripts
		wp_localize_script( 'skt-addons-elementor', 'SktProLocalize', [
			'ajax_url' => admin_url( 'admin-ajax.php' ),
			'nonce' => wp_create_nonce( 'skt_addons_elementor_addons_pro_nonce' ),
		] );
		
		//Localize scripts
		wp_localize_script(
			'skt-addons-elementor',
			'SktLocalize',
			[
				'ajax_url' => admin_url('admin-ajax.php'),
				'nonce'    => wp_create_nonce('skt_addons_elementor_addons_nonce'),
				'pdf_js_lib' => SKT_ADDONS_ELEMENTOR_ASSETS . 'vendor/pdfjs/lib'
			]
		);
	}

	/**
	 * Handle exception cases where regular enqueue won't work
	 *
	 * @param Post_CSS $file
	 *
	 * @return void
	 */
	public static function frontend_enqueue_exceptions(Post_CSS $file) {
		$post_id = $file->get_post_id();

		if (get_queried_object_id() === $post_id) {
			return;
		}

		$template_type = get_post_meta($post_id, '_elementor_template_type', true);

		if ($template_type === 'kit') {
			return;
		}

		self::enqueue($post_id);
	}

	/**
	 * Enqueue fontend assets
	 *
	 * @return void
	 */
	public static function frontend_enqueue() {
		if (!is_singular()) {
			return;
		}

		self::enqueue(get_the_ID());
	}

	/**
	 * Just enqueue the assets
	 *
	 * It just processes the assets from cache if avilable
	 * otherwise raw assets
	 *
	 * @param int $post_id
	 *
	 * @return void
	 */
	public static function enqueue($post_id) {
		if (Cache_Manager::should_enqueue($post_id)) {
			Cache_Manager::enqueue($post_id);
		}

		if (Cache_Manager::should_enqueue_raw($post_id)) {
			Cache_Manager::enqueue_raw($post_id);
		}
	}

	public static function get_dark_stylesheet_url() {
		return SKT_ADDONS_ELEMENTOR_ASSETS . 'admin/css/editor-dark.min.css';
	}

	public static function enqueue_dark_stylesheet() {
		$theme = SettingsManager::get_settings_managers('editorPreferences')->get_model()->get_settings('ui_theme');

		if ('light' !== $theme) {
			$media_queries = 'all';

			if ('auto' === $theme) {
				$media_queries = '(prefers-color-scheme: dark)';
			}

			wp_enqueue_style(
				'skt-addons-editor-dark',
				self::get_dark_stylesheet_url(),
				[
					'elementor-editor',
				],
				SKT_ADDONS_ELEMENTOR_VERSION,
				$media_queries
			);
		}
	}

	/**
	 * Enqueue editor assets
	 *
	 * @return void
	 */
	public static function editor_enqueue() {
		wp_enqueue_style(
			'skt-icons',
			SKT_ADDONS_ELEMENTOR_ASSETS . 'fonts/style.min.css',
			null,
			SKT_ADDONS_ELEMENTOR_VERSION
		);

		wp_enqueue_style(
			'skt-addons-elementor-editor',
			SKT_ADDONS_ELEMENTOR_ASSETS . 'admin/css/editor.min.css',
			null,
			SKT_ADDONS_ELEMENTOR_VERSION
		);

		wp_enqueue_script(
			'skt-addons-elementor-editor',
			SKT_ADDONS_ELEMENTOR_ASSETS . 'admin/js/editor.min.js',
			['elementor-editor', 'jquery'],
			SKT_ADDONS_ELEMENTOR_VERSION,
			true
		);

		/**
		 * Make sure to enqueue this at the end
		 * otherwise it may not work properly
		 */
		self::enqueue_dark_stylesheet();

		$localize_data = [
			'placeholder_widgets' => [],
			'hasPro'                  => skt_addons_elementor_has_pro(),
			'editor_nonce'            => wp_create_nonce('skt_addons_elementor_editor_nonce'),
			'dark_stylesheet_url'     => self::get_dark_stylesheet_url(),
			'i18n' => [
				'promotionDialogHeader'     => esc_html__('%s Widget', 'skt-addons-elementor'),
				'promotionDialogMessage'    => esc_html__('Use %s widget with other exclusive pro widgets and 100% unique features to extend your toolbox and build sites faster and better.', 'skt-addons-elementor'),
				'templatesEmptyTitle'       => esc_html__('No Templates Found', 'skt-addons-elementor'),
				'templatesEmptyMessage'     => esc_html__('Try different category or sync for new templates.', 'skt-addons-elementor'),
				'templatesNoResultsTitle'   => esc_html__('No Results Found', 'skt-addons-elementor'),
				'templatesNoResultsMessage' => esc_html__('Please make sure your search is spelled correctly or try a different words.', 'skt-addons-elementor'),
			],
		];

		if (!skt_addons_elementor_has_pro() && skt_addons_elementor_is_elementor_version('>=', '2.9.0')) {
			$localize_data['placeholder_widgets'] = Widgets_Manager::get_pro_widget_map();
		}

		wp_localize_script(
			'skt-addons-elementor-editor',
			'SktAddonsEditor',
			$localize_data
		);
	}

	/**
	 * Enqueue stylesheets only for preview window
	 * editing mode basically.
	 *
	 * @return void
	 */
	public static function enqueue_preview_styles() {
		if (skt_addons_elementor_is_weforms_activated()) {
			wp_enqueue_style(
				'skt-addons-weform',
				plugins_url('/weforms/assets/wpuf/css/frontend-forms.css', 'weforms'),
				null,
				SKT_ADDONS_ELEMENTOR_VERSION
			);
		}

		if (skt_addons_elementor_is_wpforms_activated() && defined('WPFORMS_PLUGIN_SLUG')) {
			wp_enqueue_style(
				'skt-addons-wpform',
				plugins_url('/' . WPFORMS_PLUGIN_SLUG . '/assets/css/wpforms-full.css', WPFORMS_PLUGIN_SLUG),
				null,
				SKT_ADDONS_ELEMENTOR_VERSION
			);
		}

		if (skt_addons_elementor_is_calderaforms_activated()) {
			wp_enqueue_style(
				'skt-addons-caldera-forms',
				plugins_url('/caldera-forms/assets/css/caldera-forms-front.css', 'caldera-forms'),
				null,
				SKT_ADDONS_ELEMENTOR_VERSION
			);
		}

		if (skt_addons_elementor_is_gravityforms_activated()) {
			wp_enqueue_style(
				'skt-addons-gravity-forms',
				plugins_url('/gravityforms/css/formsmain.min.css', 'gravityforms'),
				null,
				SKT_ADDONS_ELEMENTOR_VERSION
			);
		}

		$data = '
		.elementor-add-new-section .elementor-add-skt-button {
			background-color: #5636d1;
			margin-left: 5px;
			font-size: 18px;
		}
		';
		wp_add_inline_style('skt-addons-elementor', $data);

		if (skt_addons_elementor_is_fluent_form_activated()) {
			wp_enqueue_style(
				'skt-addons-fluent-forms',
				plugins_url('/fluentform/public/css/fluent-forms-public.css', 'fluentform'),
				null,
				SKT_ADDONS_ELEMENTOR_VERSION
			);
		}
	}
}

Assets_Manager::init();