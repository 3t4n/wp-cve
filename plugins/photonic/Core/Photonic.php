<?php

namespace Photonic_Plugin\Core;

use Photonic_Plugin\Admin\Admin_Menu;
use Photonic_Plugin\Options\Defaults;
use Photonic_Plugin\Options\Options;
use WP_Error;

class Photonic {
	public $defaults;
	public $localized;
	public $provider_map;
	public $admin_menu;
	public static $library;
	public static $lightbox_replacements;
	public static $safe_tags;
	private $ajax;

	public function __construct() {
		// $start = microtime(true);
		global $photonic_options;

		self::$lightbox_replacements = [
			'fancybox'    => 'fancybox3',
			'magnific'    => 'venobox',
			'prettyphoto' => 'spotlight',
		];

		self::$safe_tags = array_merge_recursive(
			[
				// Videos
				'video' => [
					'width'    => true,
					'height'   => true,
					'class'    => true,
					'controls' => true,
					'preload'  => true,
					'poster'   => true,
				],

				'source' => [
					'src'  => true,
					'type' => true,
				],

				// Password prompters
				'input'  => [
					'type' => true,
					'name' => true,
					'id' => true,
					'class' => true,
					'value' => true,
					'data-photonic-shortcode' => true,
				],

				'button' => [
					'class' => true,
				],

				// Custom data attributes
				'div' => [
					'data-photonic' => true,
					'data-photonic-gallery-columns' => true,
					'data-photonic-query' => true,
					'data-photonic-platform' => true,
					'data-photonic-prompt' => true,

					// Slideshow
					'data-splide' => true,
				],

				'ul' => [
					// Slideshows
					'data-photonic-columns' => true,
					'data-photonic-controls' => true,
					'data-photonic-fx' => true,
					'data-photonic-layout' => true,
					'data-photonic-pause' => true,
					'data-photonic-speed' => true,
					'data-photonic-strip-style' => true,
					'data-photonic-timeout' => true,
				],

				'img' => [
					// Lazy loading
					'data-src' => true,

					// Tooltips
					'data-photonic-tooltip' => true,
				],

				'a' => [
					// Non-photonic
					'data-html5-href' => true,
					'data-title' => true,
					'data-type' => true,

					// Non-Photonic - BaguetteBox
					'data-content-type' => true,

					// Non-Photonic - BigPicture
					'data-bp' => true,
					'data-bp-type' => true,

					// Non-Photonic - Fancybox
					'data-fancybox' => true,

					// Non-Photonic - Featherlight
					'data-featherlight' => true,
					'data-featherlight-type' => true,

					// Non-Photonic - GLightbox
					'data-format' => true,

					// Non-Photonic - Lightcase
					'data-lc-options' => true,

					// Non-Photonic - LightGallery
					'data-download-url' => true,
					'data-video' => true,
					'data-sub-html' => true,

					// Non-Photonic - PhotoSwipe5
					'data-pswp-height' => true,
					'data-pswp-width' => true,

					// Non-Photonic - Spotlight
					'data-media' => true,
					'data-src-mp4' => true,
					'data-poster' => true,

					// Non-Photonic - Strip
					'data-strip-group' => true,
					'data-strip-group-options' => true,
					'data-strip-caption' => true,

					// Non-Photonic - VenoBox
					'data-gall' => true,
					'data-vbtype' => true,

					// Tooltips
					'data-photonic-tooltip' => true,

					// Level 3
					'data-photonic-layout' => true,
					'data-photonic-level-3' => true,

					// Photonic photos
					'data-photonic-deep' => true,
					'data-photonic-media-type' => true,
					'data-rel' => true,

					// Photonic albums, common

					// Albums, Google

					// Photos, SmugMug
					'data-photonic-buy' => true,

					// Albums, Zenfolio
					'data-photonic-realm' => true,
				],

				'figure' => [
					'data-photonic-idx' => true,
				],
			],
			wp_kses_allowed_html('post')
		);

		require_once PHOTONIC_PATH . "/Options/Options.php";
		add_action('admin_init', [Options::get_instance(), 'prepare_options'], 20); // Setting to 20 so that CPTs can be picked up - Utilities are loaded with a priority 10

		$this->localized = false;
		$this->provider_map = [
			'flickr'    => 'Flickr',
			'smug'      => 'SmugMug',
			'smugmug'   => 'SmugMug',
			'google'    => 'Google',
			'zenfolio'  => 'Zenfolio',
			'instagram' => 'Instagram',
		];

		add_action('admin_menu', [&$this, 'add_admin_menu']);
		add_action('admin_init', [&$this, 'admin_init']);

		$photonic_options = get_option('photonic_options');
		$set_options = isset($photonic_options) && is_array($photonic_options) ? $photonic_options : [];

		$defaults = Defaults::get_options();
		$all_options = array_merge($defaults, $set_options);

		foreach ($all_options as $key => $value) {
			$mod_key = 'photonic_' . $key;
			global ${$mod_key};
			${$mod_key} = $value; // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals
		}

		define('PHOTONIC_SSL_VERIFY', empty($photonic_ssl_verify_off));
		define('PHOTONIC_DEBUG', !empty($photonic_debug_on));

		if (!empty($photonic_script_dev_mode)) {
			define('PHOTONIC_DEV_MODE', '');
		}
		else {
			define('PHOTONIC_DEV_MODE', '.min');
		}

		if (!empty($photonic_curl_timeout) && is_numeric($photonic_curl_timeout)) {
			define('PHOTONIC_CURL_TIMEOUT', $photonic_curl_timeout);
		}
		else {
			define('PHOTONIC_CURL_TIMEOUT', 30);
		}

		global $photonic_slideshow_library, $photonic_custom_lightbox;
		if ('custom' !== $photonic_slideshow_library) {
			self::$library = esc_attr($photonic_slideshow_library);
			self::$library = empty(self::$lightbox_replacements[self::$library]) ? self::$library : self::$lightbox_replacements[self::$library];
		}
		elseif (empty($photonic_slideshow_library)) {
			self::$library = 'baguettebox';
		}
		else {
			self::$library = esc_attr($photonic_custom_lightbox);
		}

		// Gallery
		if (!empty($photonic_alternative_shortcode)) {
			add_shortcode($photonic_alternative_shortcode, [&$this, 'modify_gallery']);
			add_filter('shortcode_atts_' . $photonic_alternative_shortcode, [&$this, 'native_gallery_attributes'], 10, 3);
		}
		else {
			add_filter('post_gallery', [&$this, 'modify_gallery'], 20, 2);
			add_filter('shortcode_atts_gallery', [&$this, 'native_gallery_attributes'], 10, 3);
		}

		add_shortcode('photonic_helper', [&$this, 'helper_shortcode']);

		add_action('wp_enqueue_scripts', [&$this, 'always_add_styles'], 20);
		if (!empty($photonic_always_load_scripts)) {
			add_action('wp_enqueue_scripts', [&$this, 'conditionally_add_scripts'], 20, 0);
		}

		require_once PHOTONIC_PATH . "/Core/AJAX.php";
		$this->ajax = AJAX::get_instance($this);

		/*
		add_action('photonic_token_monitor', [&$this, 'monitor_token_validity']);
		if (!wp_next_scheduled('photonic_token_monitor')) {
			wp_schedule_event(time(), 'hourly', 'photonic_token_monitor');
		}
		*/
		// The above code was commented out in March 2023, Photonic v 2.85, to prevent running the cron for Instagram.
		// The code below was added to clean out any previously scheduled cron jobs.
		// This will be removed not before March 2024, to give users the opportunity to install the plugin and have it un-schedule their jobs.
		$photonic_token_monitor_timestamp = wp_next_scheduled('photonic_token_monitor');
		wp_unschedule_event($photonic_token_monitor_timestamp, 'photonic_token_monitor');

		$this->add_extensions();
		$this->add_gutenberg_support();

		add_action('http_api_curl', [&$this, 'curl_timeout'], 100, 1);

		add_action('plugins_loaded', [&$this, 'enable_translations']);

		add_filter('body_class', [&$this, 'body_class']);

		add_filter('safe_style_css', [&$this, 'safe_styles']);

		// $end = microtime(true);
		// print_r("<!-- Photonic initialization: ".($end - $start)." -->\n");

		add_action('widgets_init', [&$this, 'load_widget']);

		// add_action('elementor/common/after_register_scripts', [&$this, 'enqueue_widget_scripts']);
		add_action('elementor/editor/before_enqueue_scripts', [&$this, 'enqueue_widget_scripts']);

		require_once PHOTONIC_PATH . "/Core/Template.php";
	}

	/**
	 * Adds a menu item to the "Settings" section of the admin page.
	 *
	 * @return void
	 */
	public function add_admin_menu() {
		if (current_user_can('edit_theme_options')) {
			$parent_slug = 'photonic-options-manager';
		}
		elseif (current_user_can('edit_posts')) {
			$parent_slug = 'photonic-getting-started';
		}

		if (!empty($parent_slug)) {
			add_menu_page('Photonic', 'Photonic', 'edit_posts', $parent_slug, [&$this->admin_menu, 'settings'], PHOTONIC_URL . 'include/images/Photonic-20-gr.png');
			add_submenu_page($parent_slug, esc_html__('Settings', 'photonic'), esc_html__('Settings', 'photonic'), 'edit_theme_options', 'photonic-options-manager', [&$this->admin_menu, 'settings']);
			add_submenu_page($parent_slug, 'Getting Started', 'Getting Started', 'edit_posts', 'photonic-getting-started', [&$this->admin_menu, 'getting_started']);
			add_submenu_page($parent_slug, 'Authentication', 'Authentication', 'edit_theme_options', 'photonic-auth', [&$this->admin_menu, 'authentication']);
			add_submenu_page($parent_slug, esc_html__('Shortcode Replacement', 'photonic'), esc_html__('Shortcode Replacement', 'photonic'), 'edit_posts', 'photonic-shortcode-replace', [&$this->admin_menu, 'shortcode']);
			add_submenu_page($parent_slug, 'Helpers', 'Helpers', 'edit_posts', 'photonic-helpers', [&$this->admin_menu, 'helpers']);
		}
	}

	/**
	 * Adds all scripts and their dependencies to the end of the <body> element only on pages using Photonic.
	 *
	 * @return void
	 */
	public function conditionally_add_scripts() {
		global $photonic_slideshow_library, $photonic_custom_lightbox_js, $photonic_custom_lightbox, $photonic_always_load_scripts,
			   $photonic_disable_photonic_lightbox_scripts, $photonic_disable_photonic_slider_scripts, $photonic_js_in_header;

		$library_versions = [
			'baguettebox'   => '1.11.1',
			'bigpicture'    => '2022-03-31',
			'colorbox'      => '1.6.4',
			'fancybox3'     => '3.5.7',
			'featherlight'  => '1.7.14',
			'glightbox'     => '2022-03-12',
			'imagelightbox' => '2018-08-17',
			'lightcase'     => '2.5.0',
			'lightgallery'  => '2.7.1',
			'photoswipe'    => '4.1.3',
			'photoswipe5'   => '5.3.7',
			'prettyphoto'   => '3.1.6',
			'spotlight'     => '0.7.8',
			'splide'        => '4.1.4',
			'strip'         => '1.8.0',
			'swipebox'      => '1.5.2',
			'venobox'       => '2.0.4',
		];

		$requires_jq = [
			'colorbox',
			'fancybox',
			'fancybox2',
			'fancybox3',
			'featherlight',
			'imagelightbox',
			'lightcase',
			'magnific',
			'prettyphoto',
			'strip',
			'swipebox',
		];

		$photonic_dependencies = [];
		$lb_deps = [];
		if (in_array(self::$library, $requires_jq, true)) {
			$photonic_dependencies[] = 'jquery';
			$lb_deps[] = 'jquery';
		}

		if (in_array(self::$library, ['colorbox', 'fancybox', 'fancybox2', 'prettyphoto'], true)) {
			wp_enqueue_script('jquery-detect-swipe', PHOTONIC_URL . 'include/ext/jquery.detect_swipe.js', ['jquery'], $this->get_version(PHOTONIC_PATH . '/include/ext/jquery.detect_swipe.js'), !($photonic_always_load_scripts && $photonic_js_in_header));
			$photonic_dependencies[] = 'jquery-detect-swipe';
		}

		if ('thickbox' === self::$library) {
			wp_enqueue_script('thickbox');
			$photonic_dependencies[] = 'thickbox';
		}
		elseif ('custom' === $photonic_slideshow_library && 'strip' !== $photonic_custom_lightbox) {
			$counter = 1;
			$dependencies = ['jquery'];
			foreach (preg_split("/((\r?\n)|(\r\n?))/", $photonic_custom_lightbox_js) as $line) {
				wp_enqueue_script('photonic-lightbox-' . $counter, trim($line), $dependencies, PHOTONIC_VERSION, !($photonic_always_load_scripts && $photonic_js_in_header));
				$photonic_dependencies[] = 'photonic-lightbox-' . $counter;
				$counter++;
			}
		}
		elseif ('none' !== self::$library) {
			if (empty($photonic_disable_photonic_lightbox_scripts)) {
				$photonic_dependencies[] = self::$library;
			}

			if (empty($photonic_disable_photonic_lightbox_scripts)) {
				if ('lightgallery' === self::$library) {
					$lightgallery_plugins = self::get_lightgallery_plugins();
					if (!empty($lightgallery_plugins)) {
						wp_enqueue_script('lightgallery', PHOTONIC_URL . 'include/ext/' . self::$library . '/' . self::$library . PHOTONIC_DEV_MODE . '.js', $lb_deps, $this->get_version(PHOTONIC_PATH . '/include/ext/' . self::$library . '/' . self::$library . PHOTONIC_DEV_MODE . '.js'), !($photonic_always_load_scripts && $photonic_js_in_header));
						$photonic_dependencies[] = 'lightgallery';
					}
					if (!empty(PHOTONIC_DEV_MODE)) {
						foreach ($lightgallery_plugins as $plugin) {
							wp_enqueue_script('photonic-lightbox-' . $plugin, PHOTONIC_URL . 'include/ext/' . self::$library . '/lg-plugin-' . $plugin . '.min.js', ['lightgallery'], $this->get_version(PHOTONIC_PATH . '/include/ext/' . self::$library . '/lg-plugin-' . $plugin . '.min.js'), !($photonic_always_load_scripts && $photonic_js_in_header));
						}
					}
					else {
						wp_enqueue_script('photonic-lightbox-plugins', PHOTONIC_URL . 'include/ext/' . self::$library . '/lightgallery-plugins.js', ['lightgallery'], $this->get_version(PHOTONIC_PATH . '/include/ext/' . self::$library . '/lightgallery-plugins.js'), !($photonic_always_load_scripts && $photonic_js_in_header));
					}
				}
				elseif ('photoswipe5' === self::$library) {
					wp_deregister_script('photoswipe'); // Remove any older version of PhotoSwipe, since the new version is not compatible with it
				}
			}
		}

		if (empty($photonic_disable_photonic_slider_scripts)) {
			wp_enqueue_script('splide', PHOTONIC_URL . 'include/ext/splide/splide' . PHOTONIC_DEV_MODE . '.js', [], $library_versions['splide'] . '-' . $this->get_version(PHOTONIC_PATH . '/include/ext/splide/splide' . PHOTONIC_DEV_MODE . '.js'), !($photonic_always_load_scripts && $photonic_js_in_header));
			$photonic_dependencies[] = 'splide';
		}

		$slideshow_library = self::$library;

		if (empty($photonic_disable_photonic_lightbox_scripts) && 'none' !== $slideshow_library && 'thickbox' !== $slideshow_library && $slideshow_library !== $photonic_custom_lightbox/* && 'photoswipe5' !== $slideshow_library*/) {
			wp_enqueue_script($slideshow_library, PHOTONIC_URL . 'include/ext/' . $slideshow_library . '/' . $slideshow_library . PHOTONIC_DEV_MODE . '.js', $lb_deps, (empty($library_versions[$slideshow_library]) ? $this->get_version(PHOTONIC_PATH . "/include/ext/$slideshow_library/$slideshow_library" . PHOTONIC_DEV_MODE . ".js") : $library_versions[$slideshow_library]), !($photonic_always_load_scripts && $photonic_js_in_header));
		}

		$module = "include/js/front-end/out/photonic-" . $slideshow_library . PHOTONIC_DEV_MODE . '.js';

		wp_enqueue_script('photonic', PHOTONIC_URL . $module, $photonic_dependencies, $this->get_version(PHOTONIC_PATH . "/" . $module), !($photonic_always_load_scripts && $photonic_js_in_header));

		$this->localize_variables_once();
	}

	/**
	 * Special function to ensure that wp_localize_script, defining Photonic_JS is called at the most once.
	 * Since <code>conditionally_add_scripts</code> is called from <code>modify_gallery</code>, it can include the Photonic_JS
	 * definition multiple times. This code ensures that it is included just once.
	 */
	public function localize_variables_once() {
		if ($this->localized) {
			return;
		}
		// Technically JS, but needs to happen here, otherwise the script is repeated multiple times, once for each time
		// <code>conditionally_add_scripts</code> is called.
		require_once PHOTONIC_PATH . '/Core/Front_End.php';
		$js_array = Front_End::get_instance()->get_localized_js_variables();
		wp_localize_script('photonic', 'Photonic_JS', $js_array);
		$this->localized = true;
	}

	/**
	 * Adds all styles to all pages because styles, if not added in the header can cause issues.
	 *
	 * @return void
	 */
	public function always_add_styles() {
		global $photonic_slideshow_library, $photonic_custom_lightbox_css, $photonic_disable_photonic_lightbox_scripts, $photonic_disable_photonic_slider_scripts;

		if ('custom' === $photonic_slideshow_library) {
			$counter = 1;
			foreach (preg_split("/((\r?\n)|(\r\n?))/", $photonic_custom_lightbox_css) as $line) {
				wp_enqueue_style('photonic-lightbox-' . $counter, trim($line), [], PHOTONIC_VERSION);
				$counter++;
			}
		}

		$slideshow_library = esc_attr(!empty($photonic_disable_photonic_lightbox_scripts) ? 'none' : self::$library);

		$this->enqueue_lightbox_styles($slideshow_library, empty($photonic_disable_photonic_slider_scripts));

		global $photonic_css_in_file;
		$file = trailingslashit(PHOTONIC_UPLOAD_DIR) . 'custom-styles.css';
		if (@file_exists($file) && !empty($photonic_css_in_file)) { // phpcs:ignore WordPress.PHP.NoSilencedErrors
			wp_enqueue_style('photonic-custom', trailingslashit(PHOTONIC_UPLOAD_URL) . 'custom-styles.css', ['photonic'], $this->get_version($file));
		}
		else {
			wp_add_inline_style('photonic', $this->generate_css());
		}

		if (class_exists('\FLBuilderModel') && \FLBuilderModel::is_builder_active()) {
			$this->enqueue_widget_scripts();
		}
	}

	public function enqueue_lightbox_styles($slideshow_library = 'swipebox', $include_slider = true) {
		if ($include_slider) {
			wp_enqueue_style('photonic-slider', PHOTONIC_URL . 'include/ext/splide/splide' . PHOTONIC_DEV_MODE . '.css', [], $this->get_version(PHOTONIC_PATH . '/include/ext/splide/splide' . PHOTONIC_DEV_MODE . '.css'));
		}

		$no_css = [
			'bigpicture',

			'none',
			'fancybox',
			'fancybox2',
			'fancybox4',
			'magnific',
			'prettyphoto',
		];

		if ('colorbox' === $slideshow_library) {
			global $photonic_cbox_theme;
			if ('theme' === $photonic_cbox_theme) {
				wp_enqueue_style('photonic-lightbox', PHOTONIC_URL . 'include/ext/colorbox/style-1/colorbox.css', [], $this->get_version(PHOTONIC_PATH . '/include/ext/colorbox/style-1/colorbox.css'));
			}
			else {
				wp_enqueue_style('photonic-lightbox', PHOTONIC_URL . 'include/ext/colorbox/style-' . $photonic_cbox_theme . '/colorbox.css', [], $this->get_version(PHOTONIC_PATH . '/include/ext/colorbox/style-' . $photonic_cbox_theme . '/colorbox.css'));
			}
		}
		elseif ('lightgallery' === $slideshow_library) {
			global $photonic_enable_lg_transitions;
			wp_enqueue_style('photonic-lightbox', PHOTONIC_URL . 'include/ext/lightgallery/lightgallery' . PHOTONIC_DEV_MODE . '.css', [], $this->get_version(PHOTONIC_PATH . '/include/ext/lightgallery/lightgallery' . PHOTONIC_DEV_MODE . '.css'));
			if (!empty($photonic_enable_lg_transitions)) {
				wp_enqueue_style('photonic-lightbox-lg-transitions', PHOTONIC_URL . 'include/ext/lightgallery/lightgallery-transitions.min.css', [], $this->get_version(PHOTONIC_PATH . '/include/ext/lightgallery/lightgallery-transitions.min.css'));
			}
		}
		elseif ('thickbox' === $slideshow_library) {
			wp_enqueue_style('thickbox');
		}
		elseif (!in_array($slideshow_library, $no_css, true)) {
			wp_enqueue_style('photonic-lightbox', PHOTONIC_URL . 'include/ext/' . $slideshow_library . '/' . $slideshow_library . PHOTONIC_DEV_MODE . '.css', [], $this->get_version(PHOTONIC_PATH . '/include/ext/' . $slideshow_library . '/' . $slideshow_library . PHOTONIC_DEV_MODE . '.css'));
		}

		wp_enqueue_style('photonic', PHOTONIC_URL . "include/css/front-end/core/photonic" . PHOTONIC_DEV_MODE . ".css", [], $this->get_version(PHOTONIC_PATH . "/include/css/front-end/core/photonic" . PHOTONIC_DEV_MODE . ".css"));
	}

	/**
	 * Prints the dynamically generated CSS based on option selections.
	 *
	 * @param bool $header
	 * @return string
	 */
	public function generate_css($header = true): string {
		global $photonic_tile_spacing, $photonic_masonry_tile_spacing, $photonic_mosaic_tile_spacing;

		$css = '';
		$saved_css = get_option('photonic_css');

		if ($header && !empty($saved_css)) {
			$css .= "/* Retrieved from saved CSS */\n";
			$css .= $saved_css;
		}
		else {
			require_once PHOTONIC_PATH . '/Core/Front_End.php';
			$front_end = Front_End::get_instance();

			if ($header) {
				$css .= "/* Dynamically generated CSS */\n";
			}
			$css .= ".photonic-panel { " .
				$front_end->get_bg_css('photonic_flickr_gallery_panel_background') .
				$front_end->get_border_css('photonic_flickr_set_popup_thumb_border') .
				" }\n";

			$css .= ".photonic-random-layout .photonic-thumb { padding: " . esc_attr($photonic_tile_spacing) . "px}\n";
			$css .= ".photonic-masonry-layout .photonic-thumb { padding: " . esc_attr($photonic_masonry_tile_spacing) . "px}\n";
			$css .= ".photonic-mosaic-layout .photonic-thumb { padding: " . esc_attr($photonic_mosaic_tile_spacing) . "px}\n";
		}

		return $css;
	}

	public static function get_version($file) {
		return date("Ymd-Gis", @filemtime($file)); // phpcs:ignore WordPress.PHP.NoSilencedErrors
	}

	public function admin_init() {
		require_once PHOTONIC_PATH . "/Admin/Admin.php";

		if (!empty($_REQUEST['page']) && // phpcs:ignore WordPress.Security.NonceVerification
			in_array($_REQUEST['page'], ['photonic-options-manager', 'photonic-options', 'photonic-helpers', 'photonic-getting-started', 'photonic-auth', 'photonic-shortcode-replace'], true)) { // phpcs:ignore WordPress.Security.NonceVerification
			require_once PHOTONIC_PATH . "/Admin/Admin_Menu.php";
			$this->admin_menu = new Admin_Menu(__FILE__, $this);
		}
	}

	public function add_extensions() {
		require_once "Gallery.php";
		require_once PHOTONIC_PATH . "/Platforms/Base.php";
		require_once PHOTONIC_PATH . '/Layouts/Core_Layout.php';
	}

	/**
	 * Overrides the native gallery short code, and does a lot more.
	 *
	 * @param $content
	 * @param array $attr
	 * @return string
	 */
	public function modify_gallery($content, $attr = []) {
		global $photonic_disable_on_home_page, $photonic_disable_on_archives;
		if ((is_archive() && !empty($photonic_disable_on_archives)) ||
			(is_home() && !empty($photonic_disable_on_home_page))) {
			return false;
		}

		global $photonic_alternative_shortcode;

		// If an alternative shortcode is used, then $content has the shortcode attributes
		if (!empty($photonic_alternative_shortcode)) {
			$attr = $content;
		}
		if (empty($attr)) {
			$attr = [];
		}

		$this->conditionally_add_scripts();
		$images = $this->get_gallery_images($attr);

		if (isset($images) && !is_array($images)) {
			return wp_kses($images, self::$safe_tags);
		}

		return wp_kses($content, self::$safe_tags);
	}

	/**
	 * Adds Photonic attributes to the native WP galleries. This cannot be called in <code>Photonic_Plugin\Platforms\Native</code> because
	 * that class is not initialised until a gallery of the native type is encountered
	 *
	 * @param array $out
	 * @param array $pairs Not used, but needed since this is for a standard gallery filter.
	 * @param array $attributes
	 * @return mixed
	 */
	public function native_gallery_attributes(array $out, array $pairs, array $attributes): array { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter
		global $photonic_wp_title_caption, $photonic_thumbnail_style, $photonic_alternative_shortcode;

		$defaults = [
			'layout'      => esc_attr($photonic_thumbnail_style ?: 'square'),

			'custom_classes' => '',
			'alignment'      => '',

			'caption'          => esc_attr($photonic_wp_title_caption),
			'page'             => 1,
			'count'            => -1,
			'thumb_size'       => 'thumbnail',
			'slide_size'       => 'large',
		];

		$attributes = array_merge($defaults, $attributes);
		if (empty($attributes['style']) || ('default' === $attributes['style'] && !empty($photonic_alternative_shortcode) && 'gallery' !== $photonic_alternative_shortcode)) {
			$attributes['style'] = $attributes['layout'];
		}

		foreach ($attributes as $key => $value) {
			$out[$key] = $value;
		}
		return $out;
	}

	/**
	 * @param array $attr
	 * @return string
	 */
	public function helper_shortcode($attr = []): string {
		if (empty($attr)) {
			$attr = [];
		}

		$this->conditionally_add_scripts();

		if (empty($attr['type']) || !in_array(strtolower($attr['type']), ['google', 'flickr', 'smugmug', 'zenfolio'], true)) {
			return sprintf(esc_html__('Please specify a value for %1$s. Accepted values are %2$s, %3$s, %4$s, %5$s', 'photonic'), '<code>type</code>', '<code>google</code>', '<code>flickr</code>', '<code>smugmug</code>', '<code>zenfolio</code>');
		}

		$gallery = new Gallery($attr);
		return $gallery->get_helper_contents();
	}

	/**
	 * @param array $attr
	 * @return string
	 */
	public function get_gallery_images(array $attr): string {
		require_once PHOTONIC_PATH . '/Core/Front_End.php';
		return Front_End::get_instance()->get_gallery_images($attr);
	}

	/**
	 * Make an HTTP request
	 *
	 * @static
	 * @param $url
	 * @param string $method GET | POST | DELETE.
	 * @param null $post_fields
	 * @param string $user_agent
	 * @param int $timeout
	 * @param bool $ssl_verify_peer
	 * @param array $headers
	 * @param array $cookies
	 * @return array|WP_Error
	 */
	public static function http($url, $method = 'POST', $post_fields = null, $user_agent = null, $timeout = 90, $ssl_verify_peer = false, $headers = [], $cookies = []) {
		$curl_args = [
			'user-agent' => $user_agent,
			'timeout'    => $timeout,
			'sslverify'  => $ssl_verify_peer,
			'headers'    => array_merge(['Expect:'], $headers),
			'method'     => $method,
			'body'       => $post_fields,
			'cookies'    => $cookies,
		];

		switch ($method) {
			case 'DELETE':
				if (!empty($post_fields)) {
					$url = "{$url}?{$post_fields}";
				}
				break;
		}

		return wp_remote_request($url, $curl_args);
	}

	public function enable_translations() {
		load_plugin_textdomain('photonic', false, false);
	}

	/**
	 * @param string|array $classes
	 * @return array
	 */
	public function body_class($classes = []) {
		if (!is_array($classes)) {
			$classes = explode(' ', $classes);
		}

		return $classes;
	}

	/**
	 * Used for handling the front-end for Gutenberg blocks
	 *
	 * @param $attributes
	 * @return string
	 */
	public function render_block($attributes): string {
		if (!empty($attributes['shortcode'])) {
			$shortcode = (array) (json_decode($attributes['shortcode']));

			if (!empty($attributes['align'])) {
				$shortcode['alignment'] = $attributes['align'];
			}
			if (!empty($attributes['className'])) {
				$shortcode['custom_classes'] = $attributes['className'];
			}

			$this->conditionally_add_scripts();
			return $this->get_gallery_images($shortcode);
		}
		return '';
	}

	private function add_gutenberg_support() {
		if (function_exists('register_block_type')) {
			register_block_type(
				'photonic/gallery',
				[
					'attributes'      => [
						'shortcode' => [
							'type' => 'string',
						],
					],
					'render_callback' => [&$this, 'render_block'],
				]
			);
		}
	}

	public function curl_timeout($handle) {
		// Forcing phpcs:ignore here, since the explicit purpose is to change cURL's timeout.
		curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, PHOTONIC_CURL_TIMEOUT); // phpcs:ignore WordPress.WP.AlternativeFunctions
		curl_setopt($handle, CURLOPT_TIMEOUT, PHOTONIC_CURL_TIMEOUT < 30 ? 30 : PHOTONIC_CURL_TIMEOUT); // phpcs:ignore WordPress.WP.AlternativeFunctions
	}

	public static function log($element) {
		if (PHOTONIC_DEBUG) {
			echo wp_kses($element, self::$safe_tags);
		}
	}

	/**
	 * @param $link
	 * @return string
	 */
	public static function doc_link($link): string {
		return ' ' . sprintf(esc_html__('See %1$shere%2$s for documentation.', 'photonic'), "<a href='$link'>", '</a>');
	}

	public function load_widget() {
		require_once PHOTONIC_PATH . '/Add_Ons/WP/Widget.php';
		register_widget("Photonic_Plugin\Add_Ons\WP\Widget");
	}

	public static function enqueue_widget_scripts() {
		global $photonic_alternative_shortcode;
		$js_array = [
			'ajaxurl'           => admin_url('admin-ajax.php'),
			'shortcode'         => esc_js($photonic_alternative_shortcode ?: 'gallery'),
			'current_shortcode' => esc_html__('Current shortcode', 'photonic'),
			'edit_message'      => esc_html__('Click on the icon to edit your gallery.', 'photonic'),
		];
		wp_enqueue_script('photonic-widget', PHOTONIC_URL . 'include/js/admin/widget.js', ['jquery'], self::get_version(PHOTONIC_PATH . '/include/js/admin/widget.js'), true);
		wp_localize_script('photonic-widget', 'Photonic_Widget_JS', $js_array);
		wp_enqueue_style('photonic-widget', PHOTONIC_URL . 'include/css/admin/widget.css', [], self::get_version(PHOTONIC_PATH . '/include/css/admin/widget.css'));
	}

	/**
	 * Custom style attributes, primarily required for the Justified Grid layout. Since <code>wp_kses_post</code> strips out tags that it doesn't recognize, we have this.
	 *
	 * @param $styles
	 * @return mixed
	 */
	public function safe_styles($styles) {
		$styles[] = '--dw';
		$styles[] = '--dh';
		$styles[] = '--tile-min-height';
		$styles[] = 'display';
		return $styles;
	}

	public static function get_lightgallery_plugins(): array {
		global $photonic_enable_lg_zoom, $photonic_enable_lg_thumbnail, $photonic_enable_lg_fullscreen, $photonic_enable_lg_autoplay;
		$lightgallery_plugins = [];
		if (!empty($photonic_enable_lg_autoplay)) {
			$lightgallery_plugins[] = 'autoplay';
		}
		if (!empty($photonic_enable_lg_fullscreen)) {
			$lightgallery_plugins[] = 'fullscreen';
		}
		if (!empty($photonic_enable_lg_thumbnail)) {
			$lightgallery_plugins[] = 'thumbnail';
		}
		if (!empty($photonic_enable_lg_zoom)) {
			$lightgallery_plugins[] = 'zoom';
		}
		return $lightgallery_plugins;
	}
}
