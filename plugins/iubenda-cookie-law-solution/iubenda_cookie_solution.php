<?php //phpcs:ignore WordPress.Files.FileName.NotHyphenatedLowercase, WordPress.Files.FileName.InvalidClassFileName
/**
 * Plugin Name: iubenda | All-in-one Compliance for GDPR / CCPA Cookie Consent + more
 * Plugin URI: https://www.iubenda.com
 * Description: The iubenda plugin is an <strong>all-in-one</strong>, extremely easy to use 360° compliance solution, with text crafted by actual lawyers, that quickly <strong>scans your site and auto-configures to match your specific setup</strong>.  It supports the GDPR (DSGVO, RGPD), UK-GDPR, ePrivacy, LGPD, USPR, CalOPPA, PECR and more.
 * Version: 3.10.1
 * Author: iubenda
 * Author URI: https://www.iubenda.com
 * License: MIT License
 * License URI: http://opensource.org/licenses/MIT
 * Text Domain: iubenda
 * Domain Path: /languages
 *
 * Cookie and Consent Database for the GDPR & ePrivacy
 * Copyright (C) 2018-2020, iubenda s.r.l
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 *
 * @package  Iubenda
 */

// exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// define contants.
define( 'IUB_DEBUG', false );

// phpcs:disable PEAR.NamingConventions.ValidClassName.StartWithCapital
/**
 * Iubenda final class.
 *
 * @property Iubenda_AMP                $AMP
 * @property Iubenda_Legal_Block        $block
 * @property Iubenda_Forms              $forms
 * @property No_Script_Policy_Embedder  $no_script_policy_embedder
 * @property Iubenda_Notice             $notice
 * @property Service_Rating             $service_rating
 * @property Iubenda_Settings           $settings
 * @property Iubenda_Legal_Widget       $widget
 *
 * @class   iubenda
 * @version 3.10.1
 */
class iubenda {
// phpcs:enable

	/**
	 * Instance.
	 *
	 * @var iubenda
	 */
	private static $instance;

	/**
	 * Services Options.
	 *
	 * @var array
	 */
	public $options = array();

	/**
	 * Defaults Services Options.
	 *
	 * @var array
	 */
	public $defaults = array(
		'cs'   => array(
			'parse'                      => true, // iubenda_parse.
			'skip_parsing'               => false, // skip_parsing.
			'ctype'                      => true, // iubenda_ctype.
			'parser_engine'              => 'new', // parser_engine.
			'output_feed'                => true, // iubenda_output_feed.
			'output_post'                => true,
			'block_gtm'                  => false,
			'code_default'               => false, // iubenda-code-default,.
			'menu_position'              => 'topmenu',
			'amp_support'                => false,
			'amp_source'                 => 'local',
			'amp_template_done'          => false,
			'amp_template'               => '',
			'custom_scripts'             => array(),
			'custom_iframes'             => array(),
			'deactivation'               => false,
			'configured'                 => false,
			'configuration_type'         => 'manual',
			'us_legislation_handled'     => false,
			'stop_showing_cs_for_admins' => false,
			'simplified'                 => array(
				'position'               => 'float-top-center',
				'background_overlay'     => false,
				'banner_style'           => 'dark',
				'legislation'            => array(
					'gdpr' => true,
					'uspr' => false,
					'lgpd' => false,
					'all'  => false,
				),
				'require_consent'        => 'worldwide',
				'explicit_accept'        => true,
				'explicit_reject'        => true,
				'tcf'                    => true,
				'frontend_auto_blocking' => array(),
			),
		),
		'pp'   => array(
			'version'         => '', // Simplified / Embed Code.
			'configured'      => false,
			'button_style'    => 'white',
			'button_position' => 'automatic',
		),
		'tc'   => array(
			'configured'      => false,
			'button_style'    => 'white',
			'button_position' => 'automatic',
		),
		'cons' => array(
			'public_api_key' => '',
			'configured'     => false,
			'cons_endpoint'  => 'https://consent.iubenda.com/public/consent',
		),
	);

	/**
	 * Base URL.
	 *
	 * @var string
	 */
	public $base_url;

	/**
	 * Current plugin version.
	 *
	 * @var string
	 */
	public $version = '3.10.1';

	/**
	 * Plugin activation info.
	 *
	 * @var array
	 */
	public $activation = array(
		'update_version'    => 0,
		'update_notice'     => true,
		'update_date'       => '',
		'update_delay_date' => 0,
	);

	/**
	 * NoHtml.
	 *
	 * @var bool
	 */
	public $no_html = false;

	/**
	 * Multilang.
	 *
	 * @var bool
	 */
	public $multilang = false;

	/**
	 * Languages.
	 *
	 * @var array
	 */
	public $languages = array();

	/**
	 * LanguagesLocale.
	 *
	 * @var array
	 */
	public $languages_locale = array();

	/**
	 * LangDefault.
	 *
	 * @var string
	 */
	public $lang_default = '';

	/**
	 * LangCurrent.
	 *
	 * @var string
	 */
	public $lang_current = '';

	/**
	 * LangMapping.
	 *
	 * @var string[]
	 */
	public $lang_mapping = array(
		// wordpress language    //iubenda language.
		'nl_NL' => 'nl',
		'en_US' => 'en',
		'en_UK' => 'en',
		'en_GB' => 'en-GB',
		'fr_FR' => 'fr',
		'de_DE' => 'de',
		'it_IT' => 'it',
		'pt_BR' => 'pt-BR',
		'pt_PT' => 'pt',
		'ru_RU' => 'ru',
		'es_ES' => 'es',
	);

	/**
	 * Supported languages.
	 *
	 * @var string[]
	 */
	public $supported_languages = array(
		'nl'    => 'Dutch',
		'en'    => 'English (US)',
		'en-GB' => 'English (UK)',
		'fr'    => 'French',
		'de'    => 'German',
		'it'    => 'Italian',
		'pt-BR' => 'Portuguese (BR)',
		'pt'    => 'Portuguese',
		'ru'    => 'Russian',
		'es'    => 'Spanish',
	);

	/**
	 * Iubenda_AMP class.
	 *
	 * @var Iubenda_AMP
	 */
	public $amp;

	/**
	 * Iubenda forms class.
	 *
	 * @var Iubenda_Forms
	 */
	public $forms;

	/**
	 * Iubenda legal block class.
	 *
	 * @var Iubenda_Legal_Block
	 */
	public $block;

	/**
	 * Iubenda no script policy embedder class
	 *
	 * @var No_Script_Policy_Embedder
	 */
	public $no_script_policy_embedder;

	/**
	 * Iubenda notice service class
	 *
	 * @var Iubenda_Notice
	 */
	public $notice;

	/**
	 * Service Rating class.
	 *
	 * @var Service_Rating
	 */
	public $service_rating;

	/**
	 * Iubenda settings class.
	 *
	 * @var Iubenda_Settings
	 */
	public $settings;

	/**
	 * Iubenda legal widget class.
	 *
	 * @var Iubenda_Legal_Widget
	 */
	public $widget;

	/**
	 * Iubenda radar dashboard class.
	 *
	 * @var Radar_Dashboard_Widget
	 */
	private $radar_dashboard_widget;

	/**
	 * Iubenda Auto Blocking class.
	 *
	 * @var Auto_Blocking
	 */
	public $iub_auto_blocking;

	/**
	 * Disable object clone.
	 *
	 * @throws Exception Cloning is not allowed.
	 */
	public function __clone() {
		throw new Exception( 'Cloning is not allowed for ' . __CLASS__ );
	}

	/**
	 * Disable unserializing of the class.
	 *
	 * @throws Exception Serializing is disabled.
	 */
	public function __wakeup() {
		throw new Exception( 'Serializing is disabled for class ' . __CLASS__ );
	}

	/**
	 * Main plugin instance,
	 * Insures that only one instance of the plugin exists in memory at one time.
	 *
	 * @return object
	 */
	public static function instance() {
		if ( ! isset( self::$instance ) && ! ( self::$instance instanceof iubenda ) ) {

			self::$instance = new iubenda();
			self::$instance->define_constants();

			add_action( 'plugins_loaded', array( self::$instance, 'load_textdomain' ) );
			add_action( 'plugins_loaded', array( self::$instance, 'init' ) );

			self::$instance->includes();

			// phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
			self::$instance->service_rating            = new Service_Rating();
			self::$instance->amp                       = new Iubenda_AMP();
			self::$instance->forms                     = new Iubenda_Forms();
			self::$instance->settings                  = new Iubenda_Settings();
			self::$instance->widget                    = new Iubenda_Legal_Widget();
			self::$instance->block                     = new Iubenda_Legal_Block();
			self::$instance->notice                    = new Iubenda_Notice();
			self::$instance->no_script_policy_embedder = new No_Script_Policy_Embedder();
			self::$instance->iub_auto_blocking         = new Auto_Blocking();
			self::$instance->radar_dashboard_widget    = new Radar_Dashboard_Widget();
		}

		return self::$instance;
	}

	/**
	 * Class constructor.
	 */
	public function __construct() {
		register_activation_hook( __FILE__, array( $this, 'activation' ) );
		register_deactivation_hook( __FILE__, array( $this, 'deactivation' ) );

		// settings.
		$cs_options   = (array) get_option( 'iubenda_cookie_law_solution', $this->defaults['cs'] );
		$pp_options   = (array) get_option( 'iubenda_privacy_policy_solution', $this->defaults['pp'] );
		$tc_options   = (array) get_option( 'iubenda_terms_conditions_solution', $this->defaults['tc'] );
		$cons_options = (array) get_option( 'iubenda_consent_solution', $this->defaults['cons'] );

		// activate AMP if not available before.
		if ( function_exists( 'is_amp_endpoint' ) || function_exists( 'ampforwp_is_amp_endpoint' ) ) {
			if ( ! isset( $cs_options['amp_support'] ) ) {
				$this->defaults['cs']['amp_support'] = true;
			}
		}

		$this->options['cs']                 = array_merge( $this->defaults['cs'], $cs_options );
		$this->options['pp']                 = array_merge( $this->defaults['pp'], $pp_options );
		$this->options['tc']                 = array_merge( $this->defaults['tc'], $tc_options );
		$this->options['cons']               = array_merge( $this->defaults['cons'], $cons_options );
		$this->options['activated_products'] = (array) get_option( 'iubenda_activated_products', array() );
		$this->options['global_options']     = (array) get_option( 'iubenda_global_options', array() );

		$this->base_url = esc_url_raw( add_query_arg( 'page', 'iubenda', admin_url( 'submenu' === (string) $this->options['cs']['menu_position'] ? 'options-general.php' : 'admin.php' ) ) );

		// actions.
		add_action( 'after_setup_theme', array( $this, 'register_shortcode' ) );
		add_action( 'wp_head', array( $this, 'wp_head_cons' ), 1 );
		add_action( 'template_redirect', array( $this, 'output_start' ), 0 );
		add_action( 'shutdown', array( $this, 'output_end' ), 100 );
		add_action( 'template_redirect', array( $this, 'disable_jetpack_tracking' ) );
		add_action( 'admin_init', array( $this, 'maybe_do_upgrade' ) );
		add_action( 'admin_init', array( $this, 'check_iubenda_version' ) );
		add_action( 'upgrader_process_complete', array( $this, 'upgrade' ), 10, 2 );
		add_filter( 'plugin_action_links', array( $this, 'plugin_action_links' ), 10, 2 );
		add_action( 'upgrader_overwrote_package', array( $this, 'do_upgrade_processes' ) );
		add_action( 'after_switch_theme', array( $this, 'assign_legal_block_or_widget' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'wp_enqueue_scripts' ), - PHP_INT_MAX );
	}

	/**
	 * Print embed code.
	 */
	public function wp_enqueue_scripts() {
		// Getting embed code.
		if ( ! $this->is_cs_service_enabled_and_configured() ) {
			return;
		}

		// If this user can access admin panel.
		if ( $this->options['cs']['stop_showing_cs_for_admins'] && $this->is_the_current_user_can_access_live_editor() ) {
			return;
		}

		// check content type.
		if ( true === (bool) $this->options['cs']['ctype'] ) {
			$iub_headers = headers_list();
			$destroy     = true;

			foreach ( $iub_headers as $header ) {
				if ( strpos( $header, 'Content-Type: text/html' ) !== false || strpos( $header, 'Content-type: text/html' ) !== false ) {
					$destroy = false;
					break;
				}
			}

			if ( $destroy ) {
				$this->no_html = true;
			}
		}

		// is post or not html content type?
		if (
			(
				( isset( $_SERVER['REQUEST_METHOD'] ) && 'POST' === $_SERVER['REQUEST_METHOD'] ) &&
				$this->options['cs']['output_post']
			)
			||
			( $this->no_html )
		) {
			return;
		}

		// bail if current page is page builder of Divi by elegant themes.
		if ( function_exists( 'et_fb_is_enabled' ) && et_fb_is_enabled() ) {
			return;
		}

		// bail if current page is builder frame of (Fusion/Avada) theme.
		if ( function_exists( 'fusion_is_builder_frame' ) && fusion_is_builder_frame() ) {
			return;
		}

		// initial head output.
		$iubenda_code = '';

		// Check if there is multi-language plugin installed and activated.
		if ( true === $this->multilang && defined( 'ICL_LANGUAGE_CODE' ) && isset( $this->options['cs'][ 'code_' . ICL_LANGUAGE_CODE ] ) ) {
			$iubenda_code .= $this->options['cs'][ 'code_' . ICL_LANGUAGE_CODE ];

			// no code for current language, use default.
			if ( ! $iubenda_code ) {
				$iubenda_code .= $this->options['cs']['code_default'];
			}
		} else {
			$iubenda_code .= $this->options['cs']['code_default'];
		}

		$iubenda_code = $this->parse_code( $iubenda_code, true );

		if ( empty( $iubenda_code ) ) {
			return;
		}

		try {
			( new Iubenda_Code_Extractor() )->enqueue_embed_code( $iubenda_code );
		} catch ( Exception $e ) {
			iub_caught_exception( $e );
		} catch ( Error $e ) {
			iub_caught_exception( $e );
		}
	}

	/**
	 * Append settings to plugin action link
	 *
	 * @param string[] $actions     An array of plugin action links. By default this can include
	 *                              'activate', 'deactivate', and 'delete'.
	 * @param string   $plugin_file Path to the plugin file relative to the plugins directory.
	 *
	 * @return array|mixed
	 */
	public function plugin_action_links( $actions, $plugin_file ) {
		static $plugin;
		if ( ! isset( $plugin ) ) {
			$plugin = plugin_basename( __FILE__ );
		}

		if ( (string) $plugin_file === (string) $plugin ) {
			$menu_page = esc_url_raw( add_query_arg( 'page', 'iubenda', admin_url( 'submenu' === $this->options['cs']['menu_position'] ? 'options-general.php' : 'admin.php' ) ) );
			$settings  = array( 'settings' => "<a href='{$menu_page}'>" . esc_html__( 'Settings', 'iubenda' ) . '</a>' );
			$actions   = array_merge( $actions, $settings );
		}

		return $actions;
	}

	/**
	 * Setup plugin constants.
	 *
	 * @return void
	 */
	private function define_constants() {
		define( 'IUBENDA_PLUGIN_URL', plugins_url( '', __FILE__ ) );
		define( 'IUBENDA_PLUGIN_REL_PATH', dirname( plugin_basename( __FILE__ ) ) . '/' );
		define( 'IUBENDA_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
	}

	/**
	 * Include required files.
	 *
	 * @return void
	 */
	private function includes() {
		include_once IUBENDA_PLUGIN_PATH . 'includes/functions.php';
		include_once IUBENDA_PLUGIN_PATH . 'includes/class-iubenda-settings.php';
		include_once IUBENDA_PLUGIN_PATH . 'includes/class-iubenda-forms.php';
		include_once IUBENDA_PLUGIN_PATH . 'includes/class-iubenda-amp.php';
		include_once IUBENDA_PLUGIN_PATH . 'includes/class-quick-generator-service.php';
		include_once IUBENDA_PLUGIN_PATH . 'includes/class-radar-service.php';
		include_once IUBENDA_PLUGIN_PATH . 'includes/class-cookie-solution-generator.php';
		include_once IUBENDA_PLUGIN_PATH . 'includes/class-privacy-policy-generator.php';
		include_once IUBENDA_PLUGIN_PATH . 'includes/class-terms-conditions-generator.php';
		include_once IUBENDA_PLUGIN_PATH . 'includes/class-service-rating.php';
		include_once IUBENDA_PLUGIN_PATH . 'includes/widget/class-iubenda-legal-widget.php';
		include_once IUBENDA_PLUGIN_PATH . 'includes/block/class-iubenda-legal-block.php';
		include_once IUBENDA_PLUGIN_PATH . 'includes/class-product-helper.php';
		include_once IUBENDA_PLUGIN_PATH . 'includes/class-language-helper.php';
		include_once IUBENDA_PLUGIN_PATH . 'includes/class-iubenda-notice.php';
		include_once IUBENDA_PLUGIN_PATH . 'includes/services/class-iubenda-abstract-product-service.php';
		include_once IUBENDA_PLUGIN_PATH . 'includes/services/class-iubenda-cs-product-service.php';
		include_once IUBENDA_PLUGIN_PATH . 'includes/services/class-iubenda-pp-product-service.php';
		include_once IUBENDA_PLUGIN_PATH . 'includes/services/class-iubenda-tc-product-service.php';
		include_once IUBENDA_PLUGIN_PATH . 'includes/services/class-iubenda-plugin-setting-service.php';
		include_once IUBENDA_PLUGIN_PATH . 'includes/services/class-iubenda-code-extractor.php';
		include_once IUBENDA_PLUGIN_PATH . 'includes/class-no-script-policy-embedder.php';
		include_once IUBENDA_PLUGIN_PATH . 'includes/class-auto-blocking.php';
		include_once IUBENDA_PLUGIN_PATH . 'includes/class-auto-blocking-script-appender.php';
		include_once IUBENDA_PLUGIN_PATH . 'includes/class-sync-script-appender.php';
		include_once IUBENDA_PLUGIN_PATH . 'includes/class-radar-dashboard-widget.php';
	}

	/**
	 * Initialize plugin.
	 *
	 * @return void
	 */
	public function init() {
		// check if WPML or Polylang is active.
		include_once ABSPATH . 'wp-admin/includes/plugin.php';

		// Polylang support.
		if ( function_exists( 'pll_default_language' ) && function_exists( 'PLL' ) && function_exists( 'pll_current_language' ) && iub_is_polylang_active() ) {
			$this->multilang = true;

			// get registered languages.
			$registered_languages = PLL()->model->get_languages_list();

			if ( ! empty( $registered_languages ) ) {
				foreach ( $registered_languages as $language ) {
					$this->languages[ $language->slug ]          = $language->name;
					$this->languages_locale[ $language->locale ] = $language->slug;
				}
			}

			// get default language.
			$this->lang_default = pll_default_language();
			// get current language.
			$this->lang_current = pll_current_language();

			// WPML support.
		} elseif ( function_exists( 'icl_get_languages' ) && iub_is_wpml_active() ) {
			$this->multilang = true;

			global $sitepress;

			// get registered languages.
			$registered_languages = icl_get_languages();

			if ( ! empty( $registered_languages ) ) {
				foreach ( $registered_languages as $language ) {
					$this->languages[ $language['code'] ]                  = $language['display_name'];
					$this->languages_locale[ $language['default_locale'] ] = $language['code'];
				}
			}

			// get default language.
			$this->lang_default = $sitepress->get_default_language();
			// get current language.
			$this->lang_current = $sitepress->get_current_language();
		} else {
			// if no plugin for multi lang installed.
			$this->lang_default = iub_array_get( iubenda()->lang_mapping, get_locale(), 'en' );
			$this->lang_current = iub_array_get( iubenda()->lang_mapping, get_locale() );
		}

		// load iubenda parser.
		include_once __DIR__ . '/iubenda-cookie-class/iubenda.class.php';
	}

	/**
	 * Plugin activation.
	 *
	 * @return void
	 */
	public function activation() {
		// Check Iubenda version on plugin activation.
		$this->check_iubenda_version();

		set_transient( 'iub_activation_completed', 1, 3600 );

		add_option( 'iubenda_cookie_law_solution', $this->options['cs'], '', 'no' );
		add_option( 'iubenda_cookie_law_solution', $this->options['cons'], '', 'no' );
		$this->iub_update_options( 'iubenda_cookie_law_version', $this->version );
		add_option( 'iubenda_activation_data', $this->activation, '', 'no' );

		// Send a radar request on plugin activation.
		// (Only if the php version under 8.1).
		if ( defined( 'PHP_VERSION' ) && ! version_compare( PHP_VERSION, 8.1, '>=' ) ) {
			$radar = new Radar_Service();
			$radar->ask_radar_to_send_request();
		}
	}

	/**
	 * Plugin deactivation.
	 *
	 * @return void
	 */
	public function deactivation() {
		// remove options from database?
		if ( $this->options['cs']['deactivation'] ) {
			delete_option( 'iubenda_activated_products' );
			delete_option( 'iubenda_activation_data' );
			delete_option( 'iubenda_consent_forms' );
			delete_option( 'iubenda_consent_solution' );
			delete_option( 'iubenda_cookie_law_solution' );
			delete_option( 'iubenda_cookie_law_version' );
			delete_option( 'iubenda_cs_page_configuration' );
			delete_option( 'iubenda_pp_page_configuration' );
			delete_option( 'iubenda_privacy_policy_solution' );
			delete_option( 'iubenda_quick_generator_response' );
			delete_option( 'iubenda_tc_page_configuration' );
			delete_option( 'iubenda_terms_conditions_solution' );
			delete_option( 'iubenda_global_options' );
			delete_option( Iubenda_Notice::IUB_NOTIFICATIONS );

			// Detach iubenda legal block from footer.
			$this->block->detach_legal_block_from_footer();
		}

		// remove radar options from database.
		delete_option( 'iubenda_radar_api_configuration' );
		delete_option( 'iubenda_radar_api_response' );
	}

	/**
	 * Plugin upgrade.
	 *
	 * @param Array $upgrader_object WP_Upgrader instance.
	 * @param Array $options Array of bulk item update data.
	 *
	 * @return void
	 */
	public function upgrade( $upgrader_object, $options ) {
		// if an update has taken place and the updated type is plugins and the plugins element exists.
		if ( 'update' === (string) $options['action'] && 'plugin' === (string) $options['type'] ) {
			$this->set_transient_flag_on_plugin_upgrade( $options );
		}
	}

	/**
	 * Set the transient flag on the plugin upgrade/update
	 *
	 * @param Array $options Array of bulk item update data.
	 *
	 * @return void
	 */
	private function set_transient_flag_on_plugin_upgrade( $options ) {
		// the path to our plugin's main file.
		$our_plugin = plugin_basename( __FILE__ );

		// Check our plugin is there and being updated.
		if ( isset( $options['plugins'] ) && is_array( $options['plugins'] ) && in_array( $our_plugin, $options['plugins'], true ) ) {

			// set a transient to record that our plugin has just been updated.
			set_transient( 'iub_upgrade_completed', 1, 3600 );

			return;
		}

		// Check our plugin is there and being updated.
		if ( isset( $options['plugin'] ) && __FILE__ === (string) $options['plugin'] ) {
			set_transient( 'iub_upgrade_completed', 1, 3600 );
		}
	}

	/**
	 * Load textdomain.
	 *
	 * @return void
	 */
	public function load_textdomain() {
		load_plugin_textdomain( 'iubenda', false, IUBENDA_PLUGIN_REL_PATH . 'languages/' );
	}

	/**
	 * Register shortcode function.
	 *
	 * @return void
	 */
	public function register_shortcode() {
		add_shortcode( 'iub-cookie-policy', array( $this, 'block_shortcode' ) );
		add_shortcode( 'iub-cookie-block', array( $this, 'block_shortcode' ) );
		add_shortcode( 'iub-cookie-skip', array( $this, 'skip_shortcode' ) );
	}

	/**
	 * Handle block shortcode function.
	 *
	 * @param   array $atts Array of attributes.
	 * @param   mixed $content Shortcode content.
	 *
	 * @return mixed
	 */
	public function block_shortcode( $atts, $content = '' ) {
		return '<!--IUB-COOKIE-BLOCK-START-->' . do_shortcode( $content ) . '<!--IUB-COOKIE-BLOCK-END-->';
	}

	/**
	 * Handle skip shortcode function.
	 *
	 * @param   array $atts Array of attributes.
	 * @param   mixed $content Shortcode content.
	 *
	 * @return mixed
	 */
	public function skip_shortcode( $atts, $content = '' ) {
		return '<!--IUB-COOKIE-BLOCK-SKIP-START-->' . do_shortcode( $content ) . '<!--IUB-COOKIE-BLOCK-SKIP-END-->';
	}

	/**
	 * Add wp_head Consent Database content.
	 *
	 * @return void
	 */
	public function wp_head_cons() {
		if ( ! empty( $this->options['cons']['public_api_key'] ) && ( new Product_Helper() )->is_cons_service_enabled() ) {

			$parameters = apply_filters(
				'iubenda_cons_init_parameters',
				array(
					'log_level'       => 'error',
					'logger'          => 'console',
					'send_from_local' => true,
				)
			);

			$_logger = ( ! empty( $parameters['logger'] ) && in_array( (string) $parameters['logger'], array( 'console', 'none' ), true ) ? $parameters['logger'] : 'console' );

			wp_enqueue_script( 'iubenda-cons-cdn', '//cdn.iubenda.com/cons/iubenda_cons.js', array(), iubenda()->version, true );
			wp_enqueue_script( 'iubenda-cons', IUBENDA_PLUGIN_URL . '/assets/js/cons.js', array(), iubenda()->version, true );
			wp_localize_script(
				'iubenda-cons',
				'data',
				array(
					'api_key'                         => esc_html( $this->options['cons']['public_api_key'] ),
					'log_level'                       => esc_html( $parameters['log_level'] ),
					'logger'                          => esc_html( $_logger ),
					'send_from_local_storage_at_load' => ( (bool) ( $parameters['send_from_local'] ) ? 'true' : 'false' ),
				)
			);
		}
	}

	/**
	 * Initialize html output.
	 *
	 * @return void
	 */
	public function output_start() {
		if ( ! is_admin() ) {
			ob_start( array( $this, 'output_callback' ) );
		}
	}

	/**
	 * Finish html output.
	 *
	 * @return void
	 */
	public function output_end() {
		if ( ! is_admin() && ob_get_level() ) {
			ob_end_flush();
		}
	}

	/**
	 * Handle final html output.
	 *
	 * @param callback $output [optional].
	 * An optional output_callback function may be specified. This function takes a string as a parameter and should return a string. The function will be called when the output buffer is flushed (sent) or cleaned (with ob_flush, ob_clean or similar function) or when the output buffer is flushed to the browser at the end of the request. When output_callback is called, it will receive the contents of the output buffer as its parameter and is expected to return a new output buffer as a result, which will be sent to the browser. If the output_callback is not a callable function, this function will return false.
	 *
	 * @return mixed
	 */
	public function output_callback( $output ) {
		// check whether to run parser or not.
		// bail on ajax, xmlrpc or iub_no_parse request.
		if (
			( defined( 'XMLRPC_REQUEST' ) && XMLRPC_REQUEST ) ||
			( defined( 'DOING_AJAX' ) && DOING_AJAX ) ||
			isset( $_SERVER['HTTP_X_REQUESTED_WITH'] ) ||
			! empty( iub_get_request_parameter( 'iub_no_parse' ) )
		) {
			return $output;
		}

		if ( ! $this->is_cs_service_enabled_and_configured() ) {
			return $output;
		}

		// bail on admin side.
		if ( is_admin() ) {
			return $output;
		}

		// If this user can access admin panel.
		if ( $this->options['cs']['stop_showing_cs_for_admins'] && $this->is_the_current_user_can_access_live_editor() ) {
			return $output;
		}

		// bail on rss feed.
		if ( is_feed() && $this->options['cs']['output_feed'] ) {
			return $output;
		}

		if ( strpos( $output, '<html' ) === false ) {
			return $output;
		} elseif ( strpos( $output, '<html' ) > 200 ) {
			return $output;
		}

		// bail if skripts blocking disabled.
		if ( ! $this->options['cs']['parse'] ) {
			return $output;
		}

		if ( ! class_exists( 'iubendaParser' ) ) {
			return $output;
		}

		// bail if consent given and skip parsing enabled.
		if ( iubendaParser::consent_given() && $this->options['cs']['skip_parsing'] ) {
			return $output;
		}

		// bail on POST request.
		if (
			( isset( $_SERVER['REQUEST_METHOD'] ) && 'POST' === $_SERVER['REQUEST_METHOD'] ) &&
			$this->options['cs']['output_post']
		) {
			return $output;
		}

		// bail if bot detected, no html in output, or it's a post request.
		if ( $this->no_html || iubendaParser::bot_detected() ) {
			return $output;
		}

		// bail if current page is page builder of Divi by elegant themes.
		if ( function_exists( 'et_fb_is_enabled' ) && et_fb_is_enabled() ) {
			return $output;
		}

		// bail if current page is builder frame of (Fusion/Avada) theme.
		if ( function_exists( 'fusion_is_builder_frame' ) && fusion_is_builder_frame() ) {
			return $output;
		}

		// bail if the current page is page builder for any theme.
		if ( function_exists( 'is_customize_preview' ) && is_customize_preview() ) {
			return $output;
		}

		// google recaptcha v3 compatibility.
		if ( class_exists( 'WPCF7' ) && (int) WPCF7::get_option( 'iqfix_recaptcha' ) === 0 && ! iubendaParser::consent_given() ) {
			$this->options['cs']['custom_scripts']['grecaptcha'] = 2;
		}

		// Jetpack compatibility.
		if ( class_exists( 'Jetpack' ) ) {
			$this->options['cs']['custom_scripts']['stats.wp.com'] = 5;
		}

		$star_time = microtime( true );
		$output    = apply_filters( 'iubenda_initial_output', $output );

		// prepare scripts and iframes.
		$scripts = $this->prepare_custom_data( $this->options['cs']['custom_scripts'] );
		$iframes = $this->prepare_custom_data( $this->options['cs']['custom_iframes'] );

		// If block_gtm option is checked, Block Google Tag Manager scripts and iframes with purpose 2.
		if ( (bool) $this->options['cs']['block_gtm'] ) {
			$scripts[2][] = 'googletagmanager.com/gtm.';
			$iframes[2][] = 'googletagmanager.com/ns.html';
		}

		// Check if the current language have a valid CS code or not.
		if ( ! ( new Product_Helper() )->check_iub_cs_code_exists_current_lang() ) {
			return $output;
		}

		// experimental class.
		if ( 'new' === (string) $this->options['cs']['parser_engine'] && can_use_dom_document_class() ) {
			if ( function_exists( 'mb_encode_numericentity' ) ) {
				$output = (string) mb_encode_numericentity( $output, array( 0x80, 0x10FFFF, 0, ~0 ), 'UTF-8' );
			}
			$iubenda = new iubendaParser(
				$output,
				array(
					'type'    => 'faster',
					'amp'     => $this->options['cs']['amp_support'],
					'scripts' => $scripts,
					'iframes' => $iframes,
				)
			);

			// render output.
			$output = $iubenda->parse();

			// append signature.
			$output .= '<!-- Parsed with iubenda experimental class in ' . round( microtime( true ) - $star_time, 4 ) . ' sec. -->';
		} else {
			// default.
			$iubenda = new iubendaParser(
				$output,
				array(
					'type'    => 'page',
					'amp'     => $this->options['cs']['amp_support'],
					'scripts' => $scripts,
					'iframes' => $iframes,
				)
			);

			// render output.
			$output = $iubenda->parse();

			// append signature.
			$output .= '<!-- Parsed with iubenda default class in ' . round( microtime( true ) - $star_time, 4 ) . ' sec. -->';
		}

		return apply_filters( 'iubenda_final_output', $output );
	}

	/**
	 * Prepare scripts/iframes.
	 *
	 * @param   array $data  Custom scripts/iframes.
	 *
	 * @return array
	 */
	public function prepare_custom_data( $data ) {
		$newdata = array();

		foreach ( $data as $script => $type ) {
			if ( ! array_key_exists( $type, $newdata ) ) {
				$newdata[ $type ] = array();
			}

			$newdata[ $type ][] = $script;
		}

		return $newdata;
	}

	/**
	 * Parse iubenda code.
	 *
	 * @param   string $source Embed code.
	 * @param   bool   $display Display.
	 *
	 * @return string
	 */
	public function parse_code( $source, $display = false ) {
		// Add placeholder to empty values in embed code to prevent preg_match_all fail.
		$source = str_replace( '""', '"IUBENDA_PLACEHOLDER"', trim( (string) $source ) );

		preg_match_all( '/(\"(?:html|content)\"(?:\s+)?\:(?:\s+)?)\"((?:.*?)(?:[^\\\\]))\"/s', $source, $matches );

		// found subgroup?
		if ( ! empty( $matches[1] ) && ! empty( $matches[2] ) ) {
			foreach ( $matches[2] as $no => $match ) {
				$source = str_replace( $matches[0][ $no ], $matches[1][ $no ] . '[[IUBENDA_TAG_START]]' . $match . '[[IUBENDA_TAG_END]]', $source );
			}

			// kses it.
			$source = wp_kses( $source, $this->get_allowed_html() );

			preg_match_all( '/\[\[IUBENDA_TAG_START\]\](.*?)\[\[IUBENDA_TAG_END\]\]/s', $source, $matches_tags );

			if ( ! empty( $matches_tags[1] ) ) {
				foreach ( $matches_tags[1] as $no => $match ) {
					$source = str_replace( $matches_tags[0][ $no ], '"' . ( $display ? str_replace( '</', '<\/', $matches[2][ $no ] ) : $matches[2][ $no ] ) . '"', $source );
				}
			}
		}

		// Remove recently added placeholder.
		$source = str_replace( '"IUBENDA_PLACEHOLDER"', '""', $source );

		return $source;
	}

	/**
	 * Disable Jetpack tracking on AMP cached pages.
	 *
	 * @return void
	 */
	public function disable_jetpack_tracking() {
		// bail no Jetpack active.
		if ( ! class_exists( 'Jetpack' ) ) {
			return;
		}

		// disable if it's not AMP cached request.
		if ( ! class_exists( 'Jetpack_AMP_Support' ) || ! Jetpack_AMP_Support::is_amp_request() ) {
			return;
		}

		// if ( is_feed() || is_robots() || is_trackback() || is_preview() || jetpack_is_dnt_enabled() ).
		// bail if skripts blocking disabled.
		if ( ! $this->options['cs']['parse'] ) {
			return;
		}

		if ( ! class_exists( 'iubendaParser' ) ) {
			return;
		}

		// bail if consent given and skip parsing enabled.
		if ( $this->options['cs']['skip_parsing'] && iubendaParser::consent_given() ) {
			return;
		}

		remove_action( 'wp_head', 'stats_add_shutdown_action' );
		remove_action( 'wp_footer', 'stats_footer', 101 );
		remove_action( 'wp_footer', 'add_to_footer', 101 );
	}

	/**
	 * Perform actions on plugin installation/upgrade.
	 *
	 * @return void
	 */
	public function maybe_do_upgrade() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		// bail if no activation or upgrade transient is set.
		if ( ! get_transient( 'iub_upgrade_completed' ) && ! get_transient( 'iub_activation_completed' ) ) {
			return;
		}

		// delete the activation transient.
		delete_transient( 'iub_activation_completed' );
		// delete the upgrade transient.
		delete_transient( 'iub_upgrade_completed' );

		// bail if activating from network, or bulk, or within an iFrame.
		if ( is_network_admin() || ! empty( iub_get_request_parameter( 'activate-multi' ) ) || defined( 'IFRAME_REQUEST' ) ) {
			return;
		}

		// generate AMP template file if AMP plugins available.
		if ( function_exists( 'is_amp_endpoint' ) || function_exists( 'ampforwp_is_amp_endpoint' ) ) {
			$this->regenerate_amp_templates();
		}

		// Sending a radar request when installing the plugin for the first time.
		// (Only if the php version under 8.1).
		if ( defined( 'PHP_VERSION' ) && ! version_compare( PHP_VERSION, 8.1, '>=' ) ) {
			$radar = new Radar_Service();
			$radar->ask_radar_to_send_request();
		}
	}


	/**
	 * Compare Iubenda plugin versions and
	 * do functions if compare result false (DB_version < Current version of plugin files ).
	 */
	public function check_iubenda_version() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		if ( $this->compare_iub_plugin_versions() ) {
			// Upgrade processes.
			$this->do_upgrade_processes();

			// Update Iubenda plugin version.
			$this->update_iubenda_version();
		}
	}

	/**
	 * Update Iubenda version in Database.
	 *
	 * @return void
	 */
	private function update_iubenda_version() {
		$this->iub_update_options( 'iubenda_cookie_law_version', $this->version );
	}


	/**
	 * Perform processes on plugin upgrade.
	 *
	 * @return void
	 */
	public function do_upgrade_processes() {
		$db_version = '2.5.91';
		if ( ! empty( (string) get_option( 'iubenda_cookie_law_version' ) ) ) {
			$db_version = (string) get_option( 'iubenda_cookie_law_version' );
		}

		// Version 3.0.0 and above.
		if ( version_compare( $db_version, '3.0.6', '<' ) ) {
			$this->upgrading_to_ver_3_process();
		}

		// Version 3.4.0 and under.
		if ( get_option( 'iubenda_cookie_law_version' ) && version_compare( $db_version, '3.4.0', '<' ) ) {
			iubenda()->notice->add_notice( 'iub_us_legislation_handle' );
		}

		// Version 3.5.0 under.
		if ( get_option( 'iubenda_cookie_law_version' ) && version_compare( $db_version, '3.5.0', '<' ) ) {
			iubenda()->clean_embed_scripts();
		}

		// Version 3.6.0 under.
		if ( get_option( 'iubenda_cookie_law_version' ) && version_compare( $db_version, '3.6.0', '<' ) ) {
			$legislation = iub_array_get( iubenda()->options['cs'], 'simplified.legislation' );
			if ( ! empty( $legislation ) && ! is_array( $legislation ) ) {
				$legislation = array( $legislation => true );
				iubenda()->options['cs']['simplified']['legislation'] = $legislation;
				$this->iub_update_options( 'iubenda_cookie_law_solution', iubenda()->options['cs'] );
				$this->settings->load_defaults();
			}
		}
	}

	/**
	 * Get configuration data parsed from iubenda code
	 *
	 * @param   string $code  code.
	 * @param   array  $args  args.
	 *
	 * @return array
	 */
	public function parse_configuration( $code, $args = array() ) {
		// Check if the embed code have Callback Functions inside it or not.
		if ( strpos( $code, 'callback' ) !== false ) {
			$code = $this->replace_the_callback_functions_to_parse_configuration( $code );
		}

		$configuration = array();
		$defaults      = array(
			'mode'  => 'basic',
			'parse' => false,
		);

		// parse incoming $args into an array and merge it with $defaults.
		$args = wp_parse_args( $args, $defaults );

		if ( empty( $code ) ) {
			return $configuration;
		}

		// parse code if needed.
		$parsed_code = true === $args['parse'] ? $this->parse_code( $code, true ) : $code;

		// get script.
		$parsed_script = '';

		preg_match_all( '/src\=(?:[\"|\'])(.*?)(?:[\"|\'])/', $parsed_code, $matches );

		// find the iubenda script url.
		if ( ! empty( $matches[1] ) ) {
			foreach ( $matches[1] as $found_script ) {
				if ( wp_http_validate_url( $found_script ) && strpos( $found_script, 'iubenda_cs.js' ) ) {
					$parsed_script = $found_script;
					continue;
				}
			}
		}

		// strip tags.
		$parsed_code = wp_kses( $parsed_code, array() );

		// get configuration.
		preg_match( '/_iub.csConfiguration *= *{(.*?)\};/', $parsed_code, $matches );

		if ( ! empty( $matches[1] ) ) {
			$parsed_code = '{' . $matches[1] . '}';
		}

		// decode.
		$decoded = json_decode( $parsed_code, true );

		if ( ! empty( $decoded ) && is_array( $decoded ) ) {

			$decoded['script'] = $parsed_script;

			// basic mode.
			if ( 'basic' === $args['mode'] ) {
				if ( isset( $decoded['banner'] ) ) {
					unset( $decoded['banner'] );
				}
				if ( isset( $decoded['callback'] ) ) {
					unset( $decoded['callback'] );
				}
				if ( isset( $decoded['perPurposeConsent'] ) ) {
					unset( $decoded['perPurposeConsent'] );
				}
				// Banner mode to get banner configuration only.
			} elseif ( 'banner' === (string) $args['mode'] ) {
				if ( isset( $decoded['banner'] ) ) {
					return $decoded['banner'];
				}

				return array();
			}

			$configuration = $decoded;
		}

		return $configuration;
	}

	/**
	 * Get configuration data parsed from TC & PP iubenda code.
	 *
	 * @param string $code Embed code.
	 *
	 * @return array|false
	 */
	public function parse_tc_pp_configuration( $code ) {
		if ( empty( $code ) ) {
			return false;
		}

		// Remove slashes and backslashes before use preg match all.
		$code = stripslashes( $code );

		preg_match_all( '/<a[^>]+href=([\'"])(?<href>.+?)\1[^>]*>/i', $code, $result );
		$url = iub_array_get( $result, 'href.0' );

		if ( ! $url ) {
			return false;
		}

		$button_style     = strpos( stripslashes( $code ), 'iubenda-white' ) !== false ? 'white' : 'black';
		$cookie_policy_id = basename( $url );

		return array(
			'button_style'     => $button_style,
			'cookie_policy_id' => $cookie_policy_id,
		);
	}

	/**
	 * Domain info helper function.
	 *
	 * @param type $domainb domainb.
	 *
	 * @return type
	 */
	public function domain( $domainb ) {
		$bits = explode( '/', $domainb );
		if ( (string) 'http:' === $bits[0] || (string) 'https:' === $bits[0] ) {
			$domainb = $bits[2];
		} else {
			$domainb = $bits[0];
		}
		unset( $bits );
		$bits = explode( '.', $domainb );
		$idz  = 0;
		while ( isset( $bits[ $idz ] ) ) {
			++$idz;
		}
		$idz -= 3;
		$idy  = 0;
		while ( $idy < $idz ) {
			unset( $bits[ $idy ] );
			++$idz;
		}
		$part = array();
		foreach ( $bits as $bit ) {
			$part[] = $bit;
		}
		unset( $bit );
		unset( $bits );
		unset( $domainb );
		$domainb = '';

		if ( strlen( $part[1] ) > 3 ) {
			unset( $part[0] );
		}
		foreach ( $part as $bit ) {
			$domainb .= $bit . '.';
		}
		unset( $bit );

		return preg_replace( '/(.*)\./', '$1', $domainb );
	}

	/**
	 * Check if file exists helper function.
	 *
	 * @param   mixed $file  Path to the file or directory.
	 *
	 * @return bool
	 */
	public function file_exists( $file ) {
		// phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged
		$file_headers = @get_headers( $file );

		if ( ! $file_headers || 'HTTP/1.1 404 Not Found' === (string) $file_headers[0] ) {
			$exists = false;
		} else {
			$exists = true;
		}

		return $exists;
	}

	/**
	 * Get allowed iubenda script HTML.
	 *
	 * @return array
	 */
	public function get_allowed_html() {
		// Jetpack fix.
		remove_filter( 'pre_kses', array( 'Filter_Embedded_HTML_Objects', 'filter' ), 11 );

		$html = array_merge(
			wp_kses_allowed_html( 'post' ),
			array(
				'script'   => array(
					'type'    => array(),
					'src'     => array(),
					'charset' => array(),
					'async'   => array(),
				),
				'noscript' => array(),
				'style'    => array(
					'type' => array(),
				),
				'iframe'   => array(
					'src'             => array(),
					'height'          => array(),
					'width'           => array(),
					'frameborder'     => array(),
					'allowfullscreen' => array(),
				),
			)
		);

		return apply_filters( 'iub_code_allowed_html', $html );
	}

	/**
	 * Re-generate the amp templates
	 */
	private function regenerate_amp_templates() {
		// For multi-language.
		if ( iubenda()->multilang && ! empty( iubenda()->languages ) ) {
			foreach ( iubenda()->languages as $lang_id => $lang_name ) {
				// get code for the language.
				$code = '';
				if ( ! empty( iubenda()->options['cs'][ 'code_' . $lang_id ] ) ) {
					$code = html_entity_decode( iubenda()->parse_code( iubenda()->options['cs'][ 'code_' . $lang_id ] ) );
				}

				if ( empty( $code ) && (string) iubenda()->lang_default === (string) $lang_id ) {
					// handle default if empty.
					$code = iubenda()->parse_code( iubenda()->options['cs']['code_default'] );
				}

				if ( ! empty( $code ) ) {
					// Generate code if it was set for the selected language.
					iubenda()->amp->generate_amp_template( $code, $lang_id );
				}
			}

			return;
		}

		// For one language.
		$code = iubenda()->options['cs']['code_default'];
		iubenda()->amp->generate_amp_template( $code );
	}

	/**
	 * Upgrading from ver -3 to ver 3+ process.
	 */
	private function upgrading_to_ver_3_process() {
		$products = array(
			'iubenda_cookie_law_solution' => 'cs',
			'iubenda_consent_solution'    => 'cons',
		);

		$old_data = array(
			'iubenda_cookie_law_solution'        => iubenda()->options['cs'],
			'iubenda_cookie_law_solution_status' => 'true',
			'iubenda_consent_solution'           => iubenda()->options['cons'],
			'iubenda_consent_solution_status'    => 'true',
		);
		$result   = $this->settings->init_prepare_product_options_while_upgrading( $products, $old_data );

		// Count valid codes for iubenda cookie law solution codes and set the service inactive.
		if ( 0 === count( array_filter( (array) iub_array_get( $result, 'codes_statues.iubenda_cookie_law_solution_codes', array() ) ) ) ) {
			$result['iubenda_activated_products']['iubenda_cookie_law_solution'] = 'false';
		}

		$this->settings->save_init_prepared_product_options( $products, $result );

		// Reload Options.
		$this->settings->load_defaults();
	}

	/**
	 * Workaround to replace the callback functions with empty json array to parse configuration.
	 *
	 * @param string $code embed code.
	 *
	 * @return string|string[]
	 */
	private function replace_the_callback_functions_to_parse_configuration( $code ) {
		$callback_position       = strpos( $code, 'callback' );
		$opened_callback_braces  = strpos( $code, '{', $callback_position );
		$closing_callback_braces = $this->find_closing_bracket( $code, $opened_callback_braces );

		return substr_replace( $code, '{', $opened_callback_braces, $closing_callback_braces - $opened_callback_braces );
	}

	/**
	 * Find closing bracket.
	 *
	 * @param string $target_string  String.
	 * @param string $open_position  Open Position.
	 *
	 * @return mixed
	 */
	private function find_closing_bracket( $target_string, $open_position ) {
		$close_pos = $open_position;
		$counter   = 1;
		while ( $counter > 0 ) {

			// To Avoid the infinity loop.
			if ( ! isset( $target_string[ $close_pos + 1 ] ) ) {
				break;
			}

			$c = $target_string[ ++$close_pos ];
			if ( '{' === (string) $c ) {
				++$counter;
			} elseif ( '}' === (string) $c ) {
				--$counter;
			}
		}

		return $close_pos;
	}

	/**
	 * Compare between Iubenda DB version and This version and
	 *
	 * Return true if DB version is lower than this version, false if DB
	 * version is equal or more than this version.
	 *
	 * @return bool|int
	 */
	private function compare_iub_plugin_versions() {
		$db_version = '2.5.91';
		if ( ! empty( (string) get_option( 'iubenda_cookie_law_version' ) ) ) {
			$db_version = (string) get_option( 'iubenda_cookie_law_version' );
		}

		return version_compare( $db_version, $this->version, '<' );
	}

	/**
	 * Decide which will be included into footer (Block or Widget)
	 */
	public function assign_legal_block_or_widget() {
		$pp_status   = ( (string) iub_array_get( iubenda()->settings->services, 'pp.status' ) === 'true' );
		$pp_position = ( (string) iub_array_get( iubenda()->options['pp'], 'button_position' ) === 'automatic' );

		// Privacy Policy button should appear.
		$pp_should_appear = ( $pp_status && $pp_position );

		$tc_status   = ( (string) iub_array_get( iubenda()->settings->services, 'tc.status' ) === 'true' );
		$tc_position = ( (string) iub_array_get( iubenda()->options['tc'], 'button_position' ) === 'automatic' );

		// Terms and conditions button should appear.
		$tc_should_appear = ( $tc_status && $tc_position );

		if ( ! ( $pp_should_appear || $tc_should_appear ) ) {
			return;
		}

		if ( $this->widget->check_current_theme_supports_widget() ) {
			// If current theme supports widget.
			do_action( 'iubenda_assign_widget_to_first_sidebar' );
		} elseif ( $this->block->check_current_theme_supports_blocks() ) {
			// if current theme supports blocks.
			do_action( 'iubenda_attach_block_in_footer' );
		}
	}

	/**
	 * Check if we support current theme to attach legal
	 */
	public function check_if_we_support_current_theme_to_attach_legal() {
		return $this->widget->check_current_theme_supports_widget() || $this->block->check_current_theme_supports_blocks();
	}

	/**
	 * Check if Privacy Controls and Cookie Solution service is activated and configured
	 */
	private function is_cs_service_enabled_and_configured(): bool {
		$product_helper = new Product_Helper();

		return ( $product_helper->is_cs_service_enabled() && $product_helper->is_cs_service_configured() );
	}

	/**
	 * Remove tampered scripts if exist
	 */
	public function clean_embed_scripts() {
		try {
			$iubenda_code_extractor             = new Iubenda_Code_Extractor();
			$iubenda_cookie_solution_generator  = new Cookie_Solution_Generator();
			$iubenda_privacy_policy_generator   = new Privacy_Policy_Generator();
			$iubenda_terms_conditions_generator = new Terms_Conditions_Generator();

			// Is the current configuration type is simplified.
			$is_cs_simplified = ( new Iubenda_CS_Product_Service() )->is_cs_simplified();

			// Check embed codes in CS product.
			foreach ( iubenda()->options['cs'] as $key => $option ) {

				if (
					is_string( $option ) &&
					strpos( $key, 'code_' ) !== false &&
					$iubenda_code_extractor->has_tampered_scripts( $option )
				) {
					if ( $is_cs_simplified ) {
						$simplified_options              = iub_array_get( iubenda()->options['cs'], 'simplified' );
						$lang_id                         = trim( substr( $key, strpos( $key, 'code_' ) + strlen( 'code_' ) ) );
						$public_id                       = iub_array_get( iubenda()->options, "global_options.public_ids.{$lang_id}" );
						$site_id                         = iub_array_get( iubenda()->options, 'global_options.site_id' );
						iubenda()->options['cs'][ $key ] = $iubenda_cookie_solution_generator->handle( $lang_id, $site_id, $public_id, $simplified_options );
					} else {
						iubenda()->options['cs'][ $key ] = $iubenda_code_extractor->clean_tampered_scripts( $option );
					}
				}
			}
			$this->iub_update_options( 'iubenda_cookie_law_solution', iubenda()->options['cs'] );

			// Check embed codes in PP product.
			foreach ( iubenda()->options['pp'] as $key => $option ) {
				if (
					is_string( $option ) &&
					strpos( $key, 'code_' ) !== false &&
					$iubenda_code_extractor->has_tampered_scripts( $option )
				) {
					$button_style                    = iub_array_get( iubenda()->options['pp'], 'button_style', iubenda()->defaults['pp']['button_style'] );
					$lang_id                         = trim( substr( $key, strpos( $key, 'code_' ) + strlen( 'code_' ) ) );
					$public_id                       = iub_array_get( iubenda()->options, "global_options.public_ids.{$lang_id}" );
					iubenda()->options['pp'][ $key ] = $iubenda_privacy_policy_generator->handle( $lang_id, $public_id, $button_style );
				}
			}
			$this->iub_update_options( 'iubenda_privacy_policy_solution', iubenda()->options['pp'] );

			// Check embed codes in TC product.
			foreach ( iubenda()->options['tc'] as $key => $option ) {
				if (
					is_string( $option ) &&
					strpos( $key, 'code_' ) !== false &&
					$iubenda_code_extractor->has_tampered_scripts( $option )
				) {
					$button_style                    = iub_array_get( iubenda()->options['tc'], 'button_style', iubenda()->defaults['tc']['button_style'] );
					$lang_id                         = trim( substr( $key, strpos( $key, 'code_' ) + strlen( 'code_' ) ) );
					$public_id                       = iub_array_get( iubenda()->options, "global_options.public_ids.{$lang_id}" );
					iubenda()->options['tc'][ $key ] = $iubenda_terms_conditions_generator->handle( $lang_id, $public_id, $button_style );
				}
			}
			$this->iub_update_options( 'iubenda_terms_conditions_solution', iubenda()->options['tc'] );
		} catch ( Exception $e ) {
			iub_caught_exception( $e );
		} catch ( Error $e ) {
			iub_caught_exception( $e );
		}
	}

	/**
	 * Updates the value of an option that was already added.
	 *
	 * If the option does not exist, it will be created.
	 *
	 * @param string $option   Name of the option to update. Expected to not be SQL-escaped.
	 * @param mixed  $value    Option value. Must be serializable if non-scalar. Expected to not be SQL-escaped.
	 *
	 * @return bool True if the value was updated, false otherwise.
	 */
	public function iub_update_options( $option, $value ) {
		$allowed_options = array(
			'iubenda_cookie_law_version',
			'iubenda_cookie_law_solution',
			'iubenda_privacy_policy_solution',
			'iubenda_terms_conditions_solution',
			'iubenda_consent_solution',
			'iubenda_activation_data',
			'iubenda_activated_products',
			'iubenda_global_options',
			'iubenda_radar_api_configuration',
			Iubenda_Settings::IUB_QG_RESPONSE,
			Iubenda_Notice::IUB_NOTIFICATIONS,
		);

		if ( ! in_array( $option, $allowed_options, true ) ) {
			wp_die( esc_html__( 'Sorry, only iubenda options allowed to update with the plugin.' ), 403 );
		}

		// Check user capability before update_option.
		iub_verify_user_capability();

		return update_option( $option, $value );
	}

	/**
	 * Check if the current user can edit pages/posts so they can open the live editors
	 *
	 * @return bool
	 */
	private function is_the_current_user_can_access_live_editor() {
		return ( current_user_can( 'edit_pages' ) || current_user_can( 'edit_posts' ) );
	}

	/**
	 * Check if elementor installed and activated
	 */
	public function is_elementor_installed_and_activated() {
		return is_plugin_active( 'elementor/elementor.php' );
	}

	/**
	 * Check if the current context is WP-CLI.
	 *
	 * @return bool True if running in WP-CLI context, false otherwise.
	 */
	public static function is_wp_cli() {
		return defined( 'WP_CLI' ) && WP_CLI;
	}
}

/**
 * Add stars in iubenda plugin meta.
 */
add_filter(
	'plugin_row_meta',
	function ( $meta_fields, $file ) {
		if ( plugin_basename( __FILE__ ) === (string) $file ) {
			$plugin_url     = 'https://wordpress.org/support/plugin/iubenda-cookie-law-solution/reviews/?rate=5#new-post';
			$new_meta_field = "<a href='%s' target='_blank' title='%s'><i class='iubenda-rate-stars'><svg xmlns='http://www.w3.org/2000/svg' width='15' height='15' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-star'><polygon points='12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2'/></svg><svg xmlns='http://www.w3.org/2000/svg' width='15' height='15' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-star'><polygon points='12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2'/></svg><svg xmlns='http://www.w3.org/2000/svg' width='15' height='15' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-star'><polygon points='12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2'/></svg><svg xmlns='http://www.w3.org/2000/svg' width='15' height='15' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-star'><polygon points='12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2'/></svg><svg xmlns='http://www.w3.org/2000/svg' width='15' height='15' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-star'><polygon points='12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2'/></svg></i></a>";
			$meta_fields[]  = sprintf( $new_meta_field, esc_url( $plugin_url ), esc_html__( 'Rate', 'iubenda' ) );

		}

		return $meta_fields;
	},
	10,
	2
);

// iubenda Plugin instance Initialization.
require 'iubenda-init.php';
