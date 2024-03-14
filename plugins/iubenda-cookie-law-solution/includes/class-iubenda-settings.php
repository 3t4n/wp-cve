<?php
/**
 * Iubenda Settings class.
 *
 * @package  Iubenda
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Iubenda_Settings class.
 *
 * @class Iubenda_Settings
 */
class Iubenda_Settings {
	const IUB_QG_API_KEY = 'c52997770b2613f6b0d8b6becffeff8d8071a6ab';

	const IUB_QG_RESPONSE = 'iubenda_quick_generator_response';

	/**
	 * Tabs.
	 *
	 * @var array
	 */
	public $tabs = array();

	/**
	 * Action.
	 *
	 * @var string
	 */
	public $action = '';

	/**
	 * Links.
	 *
	 * @var array
	 */
	public $links = array();

	/**
	 * Subject_fields.
	 *
	 * @var array
	 */
	public $subject_fields = array();

	/**
	 * Quick_generator.
	 *
	 * @var array
	 */
	public $quick_generator = array();

	/**
	 * Quick generator API key.
	 *
	 * @var string
	 */
	public $iub_qg_api_key;

	/**
	 * Services.
	 *
	 * @var array
	 */
	public $services = array();

	/**
	 * Legal notices
	 *
	 * @var string[]
	 */
	public $legal_notices;

	/**
	 * Tag types
	 *
	 * @var array
	 */
	private $tag_types;

	/**
	 * Iubenda_Settings constructor.
	 */
	public function __construct() {
		// actions.
		add_action( 'after_setup_theme', array( $this, 'load_defaults' ) );
		add_action( 'admin_init', array( $this, 'update_plugin' ), 9 );
		add_action( 'admin_menu', array( $this, 'admin_menu_options' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
		add_action( 'admin_print_styles', array( $this, 'admin_print_styles' ) );
		add_action( 'admin_init', array( $this, 'process_actions' ), 20 );

		add_action( 'wp_ajax_synchronize_products', array( $this, 'synchronize_products' ) );
		add_action( 'wp_ajax_quick_generator_api', array( new Quick_Generator_Service(), 'quick_generator_api' ) );
		add_action( 'wp_ajax_integrate_setup', array( new Quick_Generator_Service(), 'integrate_setup' ) );
		add_action( 'wp_ajax_toggle_services', array( $this, 'toggle_services' ) );
		add_action( 'wp_ajax_auto_detect_forms', array( new Quick_Generator_Service(), 'auto_detect_forms' ) );
		add_action( 'wp_ajax_save_cs_options', array( new Quick_Generator_Service(), 'cs_ajax_save' ) );
		add_action( 'wp_ajax_save_pp_options', array( new Quick_Generator_Service(), 'pp_ajax_save' ) );
		add_action( 'wp_ajax_save_cons_options', array( new Quick_Generator_Service(), 'cons_ajax_save' ) );
		add_action( 'wp_ajax_save_tc_options', array( new Quick_Generator_Service(), 'tc_ajax_save' ) );
		add_action( 'wp_ajax_save_plugin_settings_options', array( new Quick_Generator_Service(), 'plugin_settings_ajax_save' ) );
		add_action( 'wp_ajax_radar_percentage_reload', array( new Radar_Service(), 'ask_radar_to_send_request' ) );
		add_action( 'wp_ajax_frontpage_main_box', array( $this, 'get_frontpage_main_box' ) );

		register_setting( 'iubenda_consent_solution_forms', 'status' );
		register_setting( 'iubenda_consent_solution', 'iubenda_consent_forms' );

		add_shortcode( 'iub-tc-button', array( new Quick_Generator_Service(), 'tc_button_shortcode' ) );
		add_shortcode( 'iub-pp-button', array( new Quick_Generator_Service(), 'pp_button_shortcode' ) );

		$this->iub_qg_api_key = $this->get_iub_qg_api_key();
	}

	/**
	 * Load default settings.
	 */
	public function load_defaults() {
		$this->services = $this->services_option();

		$this->subject_fields = array(
			'id'         => __( 'string', 'iubenda' ),
			'email'      => __( 'string', 'iubenda' ),
			'first_name' => __( 'string', 'iubenda' ),
			'last_name'  => __( 'string', 'iubenda' ),
			'full_name'  => __( 'string', 'iubenda' ),
		);

		$this->legal_notices = array(
			'privacy_policy',
			'cookie_policy',
			'term',
		);

		$this->tabs = array(
			'cs'   => array(
				'name'   => __( 'Privacy Controls and Cookie Solution', 'iubenda' ),
				'key'    => 'iubenda_cookie_law_solution',
				'submit' => 'save_iubenda_options',
				'reset'  => 'reset_iubenda_options',
			),
			'cons' => array(
				'name'   => __( 'Consent Database', 'iubenda' ),
				'key'    => 'iubenda_consent_solution',
				'submit' => 'save_consent_options',
				'reset'  => 'reset_consent_options',
			),
		);

		$this->tag_types = array(
			0 => __( 'Not set', 'iubenda' ),
			1 => __( 'Strictly necessary', 'iubenda' ),
			2 => __( 'Basic interactions & functionalities', 'iubenda' ),
			3 => __( 'Experience enhancement', 'iubenda' ),
			4 => __( 'Analytics', 'iubenda' ),
			5 => __( 'Targeting & Advertising', 'iubenda' ),
		);
		$site_id         = iub_array_get( iubenda()->options['global_options'], 'site_id' );

		$qg_response = ( new Quick_Generator_Service() )->qg_response;

		$links = array(
			'en'    => array(
				'iab'                            => 'https://www.iubenda.com/en/help/7440-enable-preference-management-iab-framework',
				'enable_iab'                     => 'https://www.iubenda.com/en/help/7440-iab-framework-cmp#why-publishers-should-enable-the-transparency-and-consent-framework',
				'guide'                          => 'https://www.iubenda.com/en/cookie-solution',
				'plugin_page'                    => 'https://www.iubenda.com/en/help/posts/1215',
				'support_forum'                  => 'https://support.iubenda.com/support/home',
				'documentation'                  => 'https://www.iubenda.com/en/help/posts/1215',
				'how_generate_tc'                => 'https://www.iubenda.com/en/help/19461',
				'how_generate_cs'                => 'https://www.iubenda.com/en/help/1177',
				'how_generate_pp'                => 'https://www.iubenda.com/en/help/463-generate-privacy-policy',
				'how_generate_cons'              => 'https://www.iubenda.com/en/help/6473-consent-solution-js-documentation#generate-embed',
				'about_pp'                       => 'https://www.iubenda.com/en/privacy-and-cookie-policy-generator',
				'about_cs'                       => 'https://www.iubenda.com/en/cookie-solution',
				'about_tc'                       => 'https://www.iubenda.com/en/terms-and-conditions-generator',
				'flow_page'                      => "https://www.iubenda.com/en/flow/{$site_id}",
				'about_cons'                     => 'https://www.iubenda.com/en/consent-solution',
				'amp_support'                    => 'https://www.iubenda.com/en/help/22135-cookie-solution-amp-wordpress#folder-permissions',
				'enable_amp_support'             => 'https://www.iubenda.com/en/help/22135-cookie-solution-amp-wordpress#step-2-enable-the-google-amp-support',
				'wordpress_support'              => 'https://www.iubenda.com/en/help/370-how-to-use-iubenda-privacy-and-cookie-policy-on-a-wordpress-website',
				'privacy_policy_generator_edit'  => iub_array_get( $qg_response, 'privacy_policies.en.edit_url', '' ) ?? '',
				'privacy_policy_generator_setup' => iub_array_get( $qg_response, 'privacy_policies.en.setup_url', '' ) ?? '',
				'automatic_block_scripts'        => 'https://www.iubenda.com/en/help/1215-cookie-solution-wordpress-plugin-installation-guide#functionality',
				'how_cs_rate'                    => 'https://www.iubenda.com/en/help/21985-cookie-banner-do-you-really-need-one-and-how-can-you-get-a-cookie-notice-for-your-website',
				'how_cons_rate'                  => 'https://www.iubenda.com/en/help/3081-prior-blocking-of-cookie-scripts#wordpress',
				'how_pp_rate'                    => 'https://www.iubenda.com/en/help/6187-what-should-be-in-a-privacy-policy',
				'how_tc_rate'                    => 'https://www.iubenda.com/en/help/19482-what-should-basic-terms-and-conditions-include',
				'user_account'                   => 'https://www.iubenda.com/en/account',
				'amp_permission_support'         => 'https://www.iubenda.com/en/help/22135-cookie-solution-amp-wordpress#folder-permissions',
				'google_tag_manager_blocking'    => 'https://www.iubenda.com/en/help/1235-google-tag-manager-blocking-cookies',
				'frontend_auto_blocking'         => 'https://www.iubenda.com/en/help/133036-prior-blocking-of-cookies-automatic-blocking-auto-blocking',
			),
			'it'    => array(
				'iab'                            => 'https://www.iubenda.com/it/help/7440-enable-preference-management-iab-framework',
				'enable_iab'                     => 'https://www.iubenda.com/it/help/7440-iab-framework-cmp#why-publishers-should-enable-the-transparency-and-consent-framework',
				'guide'                          => 'https://www.iubenda.com/it/cookie-solution',
				'plugin_page'                    => 'https://www.iubenda.com/it/help/posts/810',
				'support_forum'                  => 'https://support.iubenda.com/support/home',
				'documentation'                  => 'https://www.iubenda.com/it/help/posts/810',
				'how_generate_tc'                => 'https://www.iubenda.com/it/help/19394',
				'how_generate_cs'                => 'https://www.iubenda.com/it/help/680',
				'how_generate_pp'                => 'https://www.iubenda.com/it/help/463-generate-privacy-policy',
				'how_generate_cons'              => 'https://www.iubenda.com/it/help/6473-consent-solution-js-documentation#generate-embed',
				'about_pp'                       => 'https://www.iubenda.com/it/privacy-and-cookie-policy-generator',
				'about_cs'                       => 'https://www.iubenda.com/it/cookie-solution',
				'about_tc'                       => 'https://www.iubenda.com/it/terms-and-conditions-generator',
				'flow_page'                      => "https://www.iubenda.com/it/flow/{$site_id}",
				'about_cons'                     => 'https://www.iubenda.com/it/consent-solution',
				'amp_support'                    => 'https://www.iubenda.com/it/help/22135-cookie-solution-amp-wordpress#folder-permissions',
				'enable_amp_support'             => 'https://www.iubenda.com/it/help/22135-cookie-solution-amp-wordpress#step-2-enable-the-google-amp-support',
				'wordpress_support'              => 'https://www.iubenda.com/it/help/370-how-to-use-iubenda-privacy-and-cookie-policy-on-a-wordpress-website',
				'privacy_policy_generator_edit'  => iub_array_get( $qg_response, 'privacy_policies.it.edit_url', '' ) ?? '',
				'privacy_policy_generator_setup' => iub_array_get( $qg_response, 'privacy_policies.it.setup_url', '' ) ?? '',
				'automatic_block_scripts'        => 'https://www.iubenda.com/it/help/1215-cookie-solution-wordpress-plugin-installation-guide#functionality',
				'how_cs_rate'                    => 'https://www.iubenda.com/it/help/21985-cookie-banner-do-you-really-need-one-and-how-can-you-get-a-cookie-notice-for-your-website',
				'how_cons_rate'                  => 'https://www.iubenda.com/it/help/3081-prior-blocking-of-cookie-scripts#wordpress',
				'how_pp_rate'                    => 'https://www.iubenda.com/it/help/6187-what-should-be-in-a-privacy-policy',
				'how_tc_rate'                    => 'https://www.iubenda.com/it/help/19482-what-should-basic-terms-and-conditions-include',
				'user_account'                   => 'https://www.iubenda.com/it/account',
				'amp_permission_support'         => 'https://www.iubenda.com/it/help/22135-cookie-solution-amp-wordpress#folder-permissions',
				'google_tag_manager_blocking'    => 'https://www.iubenda.com/it/help/1235-google-tag-manager-blocking-cookies',
				'frontend_auto_blocking'         => 'https://www.iubenda.com/it/help/133036-prior-blocking-of-cookies-automatic-blocking-auto-blocking',
			),
			'de'    => array(
				'iab'                            => 'https://www.iubenda.com/de/help/7440-enable-preference-management-iab-framework',
				'enable_iab'                     => 'https://www.iubenda.com/de/help/7440-iab-framework-cmp#why-publishers-should-enable-the-transparency-and-consent-framework',
				'guide'                          => 'https://www.iubenda.com/de/cookie-solution',
				'plugin_page'                    => 'https://www.iubenda.com/de/help/posts/810',
				'support_forum'                  => 'https://support.iubenda.com/support/home',
				'documentation'                  => 'https://www.iubenda.com/de/help/posts/810',
				'how_generate_tc'                => 'https://www.iubenda.com/de/help/19394',
				'how_generate_cs'                => 'https://www.iubenda.com/de/help/680',
				'how_generate_pp'                => 'https://www.iubenda.com/de/help/463-generate-privacy-policy',
				'how_generate_cons'              => 'https://www.iubenda.com/de/help/6473-consent-solution-js-documentation#generate-embed',
				'about_pp'                       => 'https://www.iubenda.com/de/privacy-and-cookie-policy-generator',
				'about_cs'                       => 'https://www.iubenda.com/de/cookie-solution',
				'about_tc'                       => 'https://www.iubenda.com/de/terms-and-conditions-generator',
				'flow_page'                      => "https://www.iubenda.com/de/flow/{$site_id}",
				'about_cons'                     => 'https://www.iubenda.com/de/consent-solution',
				'amp_support'                    => 'https://www.iubenda.com/de/help/22135-cookie-solution-amp-wordpress#folder-permissions',
				'enable_amp_support'             => 'https://www.iubenda.com/de/help/22135-cookie-solution-amp-wordpress#step-2-enable-the-google-amp-support',
				'wordpress_support'              => 'https://www.iubenda.com/de/help/370-how-to-use-iubenda-privacy-and-cookie-policy-on-a-wordpress-website',
				'privacy_policy_generator_edit'  => iub_array_get( $qg_response, 'privacy_policies.de.edit_url', '' ) ?? '',
				'privacy_policy_generator_setup' => iub_array_get( $qg_response, 'privacy_policies.de.setup_url', '' ) ?? '',
				'automatic_block_scripts'        => 'https://www.iubenda.com/de/help/1215-cookie-solution-wordpress-plugin-installation-guide#functionality',
				'how_cs_rate'                    => 'https://www.iubenda.com/de/help/21985-cookie-banner-do-you-really-need-one-and-how-can-you-get-a-cookie-notice-for-your-website',
				'how_cons_rate'                  => 'https://www.iubenda.com/de/help/3081-prior-blocking-of-cookie-scripts#wordpress',
				'how_pp_rate'                    => 'https://www.iubenda.com/de/help/6187-what-should-be-in-a-privacy-policy',
				'how_tc_rate'                    => 'https://www.iubenda.com/de/help/19482-what-should-basic-terms-and-conditions-include',
				'user_account'                   => 'https://www.iubenda.com/de/account',
				'amp_permission_support'         => 'https://www.iubenda.com/de/help/22135-cookie-solution-amp-wordpress#folder-permissions',
				'google_tag_manager_blocking'    => 'https://www.iubenda.com/de/help/1235-google-tag-manager-blocking-cookies',
				'frontend_auto_blocking'         => 'https://www.iubenda.com/de/help/133036-prior-blocking-of-cookies-automatic-blocking-auto-blocking',
			),
			'es'    => array(
				'iab'                            => 'https://www.iubenda.com/es/help/7440-enable-preference-management-iab-framework',
				'enable_iab'                     => 'https://www.iubenda.com/es/help/7440-iab-framework-cmp#why-publishers-should-enable-the-transparency-and-consent-framework',
				'guide'                          => 'https://www.iubenda.com/es/cookie-solution',
				'plugin_page'                    => 'https://www.iubenda.com/es/help/posts/810',
				'support_forum'                  => 'https://support.iubenda.com/support/home',
				'documentation'                  => 'https://www.iubenda.com/es/help/posts/810',
				'how_generate_tc'                => 'https://www.iubenda.com/es/help/19394',
				'how_generate_cs'                => 'https://www.iubenda.com/es/help/680',
				'how_generate_pp'                => 'https://www.iubenda.com/es/help/463-generate-privacy-policy',
				'how_generate_cons'              => 'https://www.iubenda.com/es/help/6473-consent-solution-js-documentation#generate-embed',
				'about_pp'                       => 'https://www.iubenda.com/es/privacy-and-cookie-policy-generator',
				'about_cs'                       => 'https://www.iubenda.com/es/cookie-solution',
				'about_tc'                       => 'https://www.iubenda.com/es/terms-and-conditions-generator',
				'flow_page'                      => "https://www.iubenda.com/es/flow/{$site_id}",
				'about_cons'                     => 'https://www.iubenda.com/es/consent-solution',
				'amp_support'                    => 'https://www.iubenda.com/es/help/22135-cookie-solution-amp-wordpress#folder-permissions',
				'enable_amp_support'             => 'https://www.iubenda.com/es/help/22135-cookie-solution-amp-wordpress#step-2-enable-the-google-amp-support',
				'wordpress_support'              => 'https://www.iubenda.com/es/help/370-how-to-use-iubenda-privacy-and-cookie-policy-on-a-wordpress-website',
				'privacy_policy_generator_edit'  => iub_array_get( $qg_response, 'privacy_policies.es.edit_url', '' ) ?? '',
				'privacy_policy_generator_setup' => iub_array_get( $qg_response, 'privacy_policies.es.setup_url', '' ) ?? '',
				'automatic_block_scripts'        => 'https://www.iubenda.com/es/help/1215-cookie-solution-wordpress-plugin-installation-guide#functionality',
				'how_cs_rate'                    => 'https://www.iubenda.com/es/help/21985-cookie-banner-do-you-really-need-one-and-how-can-you-get-a-cookie-notice-for-your-website',
				'how_cons_rate'                  => 'https://www.iubenda.com/es/help/3081-prior-blocking-of-cookie-scripts#wordpress',
				'how_pp_rate'                    => 'https://www.iubenda.com/es/help/6187-what-should-be-in-a-privacy-policy',
				'how_tc_rate'                    => 'https://www.iubenda.com/es/help/19482-what-should-basic-terms-and-conditions-include',
				'user_account'                   => 'https://www.iubenda.com/es/account',
				'amp_permission_support'         => 'https://www.iubenda.com/es/help/22135-cookie-solution-amp-wordpress#folder-permissions',
				'google_tag_manager_blocking'    => 'https://www.iubenda.com/es/help/1235-google-tag-manager-blocking-cookies',
				'frontend_auto_blocking'         => 'https://www.iubenda.com/es/help/133036-prior-blocking-of-cookies-automatic-blocking-auto-blocking',
			),
			'fr'    => array(
				'iab'                            => 'https://www.iubenda.com/fr/help/7440-enable-preference-management-iab-framework',
				'enable_iab'                     => 'https://www.iubenda.com/fr/help/7440-iab-framework-cmp#why-publishers-should-enable-the-transparency-and-consent-framework',
				'guide'                          => 'https://www.iubenda.com/fr/cookie-solution',
				'plugin_page'                    => 'https://www.iubenda.com/fr/help/posts/810',
				'support_forum'                  => 'https://support.iubenda.com/support/home',
				'documentation'                  => 'https://www.iubenda.com/fr/help/posts/810',
				'how_generate_tc'                => 'https://www.iubenda.com/fr/help/19394',
				'how_generate_cs'                => 'https://www.iubenda.com/fr/help/680',
				'how_generate_pp'                => 'https://www.iubenda.com/fr/help/463-generate-privacy-policy',
				'how_generate_cons'              => 'https://www.iubenda.com/fr/help/6473-consent-solution-js-documentation#generate-embed',
				'about_pp'                       => 'https://www.iubenda.com/fr/privacy-and-cookie-policy-generator',
				'about_cs'                       => 'https://www.iubenda.com/fr/cookie-solution',
				'about_tc'                       => 'https://www.iubenda.com/fr/terms-and-conditions-generator',
				'flow_page'                      => "https://www.iubenda.com/fr/flow/{$site_id}",
				'about_cons'                     => 'https://www.iubenda.com/fr/consent-solution',
				'amp_support'                    => 'https://www.iubenda.com/fr/help/22135-cookie-solution-amp-wordpress#folder-permissions',
				'enable_amp_support'             => 'https://www.iubenda.com/fr/help/22135-cookie-solution-amp-wordpress#step-2-enable-the-google-amp-support',
				'wordpress_support'              => 'https://www.iubenda.com/fr/help/370-how-to-use-iubenda-privacy-and-cookie-policy-on-a-wordpress-website',
				'privacy_policy_generator_edit'  => iub_array_get( $qg_response, 'privacy_policies.fr.edit_url', '' ) ?? '',
				'privacy_policy_generator_setup' => iub_array_get( $qg_response, 'privacy_policies.fr.setup_url', '' ) ?? '',
				'automatic_block_scripts'        => 'https://www.iubenda.com/fr/help/1215-cookie-solution-wordpress-plugin-installation-guide#functionality',
				'how_cs_rate'                    => 'https://www.iubenda.com/fr/help/21985-cookie-banner-do-you-really-need-one-and-how-can-you-get-a-cookie-notice-for-your-website',
				'how_cons_rate'                  => 'https://www.iubenda.com/fr/help/3081-prior-blocking-of-cookie-scripts#wordpress',
				'how_pp_rate'                    => 'https://www.iubenda.com/fr/help/6187-what-should-be-in-a-privacy-policy',
				'how_tc_rate'                    => 'https://www.iubenda.com/fr/help/19482-what-should-basic-terms-and-conditions-include',
				'user_account'                   => 'https://www.iubenda.com/fr/account',
				'amp_permission_support'         => 'https://www.iubenda.com/fr/help/22135-cookie-solution-amp-wordpress#folder-permissions',
				'google_tag_manager_blocking'    => 'https://www.iubenda.com/fr/help/1235-google-tag-manager-blocking-cookies',
				'frontend_auto_blocking'         => 'https://www.iubenda.com/fr/help/133036-prior-blocking-of-cookies-automatic-blocking-auto-blocking',
			),
			'pt-br' => array(
				'iab'                            => 'https://www.iubenda.com/pt-br/help/7440-enable-preference-management-iab-framework',
				'enable_iab'                     => 'https://www.iubenda.com/pt-br/help/7440-iab-framework-cmp#why-publishers-should-enable-the-transparency-and-consent-framework',
				'guide'                          => 'https://www.iubenda.com/pt-br/cookie-solution',
				'plugin_page'                    => 'https://www.iubenda.com/pt-br/help/45342-cookie-solution-manual-de-instalacao-do-plugin-do-wordpress',
				'support_forum'                  => 'https://support.iubenda.com/support/home',
				'documentation'                  => 'https://www.iubenda.com/pt-br/help/45342-cookie-solution-manual-de-instalacao-do-plugin-do-wordpress',
				'how_generate_tc'                => 'https://www.iubenda.com/pt-br/help/19394',
				'how_generate_cs'                => 'https://www.iubenda.com/pt-br/help/680',
				'how_generate_pp'                => 'https://www.iubenda.com/pt-br/help/463-generate-privacy-policy',
				'how_generate_cons'              => 'https://www.iubenda.com/pt-br/help/6473-consent-solution-js-documentation#generate-embed',
				'about_pp'                       => 'https://www.iubenda.com/pt-br/privacy-and-cookie-policy-generator',
				'about_cs'                       => 'https://www.iubenda.com/pt-br/cookie-solution',
				'about_tc'                       => 'https://www.iubenda.com/pt-br/terms-and-conditions-generator',
				'flow_page'                      => "https://www.iubenda.com/pt-br/flow/{$site_id}",
				'about_cons'                     => 'https://www.iubenda.com/pt-br/consent-solution',
				'amp_support'                    => 'https://www.iubenda.com/pt-br/help/22135-cookie-solution-amp-wordpress#folder-permissions',
				'enable_amp_support'             => 'https://www.iubenda.com/pt-br/help/22135-cookie-solution-amp-wordpress#step-2-enable-the-google-amp-support',
				'wordpress_support'              => 'https://www.iubenda.com/pt-br/help/370-how-to-use-iubenda-privacy-and-cookie-policy-on-a-wordpress-website',
				'privacy_policy_generator_edit'  => iub_array_get( $qg_response, 'privacy_policies.pt-br.edit_url', '' ) ?? '',
				'privacy_policy_generator_setup' => iub_array_get( $qg_response, 'privacy_policies.pt-br.setup_url', '' ) ?? '',
				'automatic_block_scripts'        => 'https://www.iubenda.com/pt-br/help/1215-cookie-solution-wordpress-plugin-installation-guide#functionality',
				'how_cs_rate'                    => 'https://www.iubenda.com/pt-br/help/21985-cookie-banner-do-you-really-need-one-and-how-can-you-get-a-cookie-notice-for-your-website',
				'how_cons_rate'                  => 'https://www.iubenda.com/pt-br/help/3081-prior-blocking-of-cookie-scripts#wordpress',
				'how_pp_rate'                    => 'https://www.iubenda.com/pt-br/help/6187-what-should-be-in-a-privacy-policy',
				'how_tc_rate'                    => 'https://www.iubenda.com/pt-br/help/19482-what-should-basic-terms-and-conditions-include',
				'user_account'                   => 'https://www.iubenda.com/pt-br/account',
				'amp_permission_support'         => 'https://www.iubenda.com/pt-br/help/22135-cookie-solution-amp-wordpress#folder-permissions',
				'google_tag_manager_blocking'    => 'https://www.iubenda.com/pt-br/help/1235-google-tag-manager-blocking-cookies',
				'frontend_auto_blocking'         => 'https://www.iubenda.com/pt-br/help/133036-prior-blocking-of-cookies-automatic-blocking-auto-blocking',
			),
			'nl'    => array(
				'iab'                            => 'https://www.iubenda.com/nl/help/7440-enable-preference-management-iab-framework',
				'enable_iab'                     => 'https://www.iubenda.com/nl/help/7440-iab-framework-cmp#why-publishers-should-enable-the-transparency-and-consent-framework',
				'guide'                          => 'https://www.iubenda.com/nl/cookie-solution',
				'plugin_page'                    => 'https://www.iubenda.com/nl/help/posts/810',
				'support_forum'                  => 'https://support.iubenda.com/support/home',
				'documentation'                  => 'https://www.iubenda.com/nl/help/posts/810',
				'how_generate_tc'                => 'https://www.iubenda.com/nl/help/19394',
				'how_generate_cs'                => 'https://www.iubenda.com/nl/help/680',
				'how_generate_pp'                => 'https://www.iubenda.com/nl/help/463-generate-privacy-policy',
				'how_generate_cons'              => 'https://www.iubenda.com/nl/help/6473-consent-solution-js-documentation#generate-embed',
				'about_pp'                       => 'https://www.iubenda.com/nl/privacy-and-cookie-policy-generator',
				'about_cs'                       => 'https://www.iubenda.com/nl/cookie-solution',
				'about_tc'                       => 'https://www.iubenda.com/nl/terms-and-conditions-generator',
				'flow_page'                      => "https://www.iubenda.com/nl/flow/{$site_id}",
				'about_cons'                     => 'https://www.iubenda.com/nl/consent-solution',
				'amp_support'                    => 'https://www.iubenda.com/nl/help/22135-cookie-solution-amp-wordpress#folder-permissions',
				'enable_amp_support'             => 'https://www.iubenda.com/nl/help/22135-cookie-solution-amp-wordpress#step-2-enable-the-google-amp-support',
				'wordpress_support'              => 'https://www.iubenda.com/nl/help/370-how-to-use-iubenda-privacy-and-cookie-policy-on-a-wordpress-website',
				'privacy_policy_generator_edit'  => iub_array_get( $qg_response, 'privacy_policies.nl.edit_url', '' ) ?? '',
				'privacy_policy_generator_setup' => iub_array_get( $qg_response, 'privacy_policies.nl.setup_url', '' ) ?? '',
				'automatic_block_scripts'        => 'https://www.iubenda.com/nl/help/1215-cookie-solution-wordpress-plugin-installation-guide#functionality',
				'how_cs_rate'                    => 'https://www.iubenda.com/nl/help/21985-cookie-banner-do-you-really-need-one-and-how-can-you-get-a-cookie-notice-for-your-website',
				'how_cons_rate'                  => 'https://www.iubenda.com/nl/help/3081-prior-blocking-of-cookie-scripts#wordpress',
				'how_pp_rate'                    => 'https://www.iubenda.com/nl/help/6187-what-should-be-in-a-privacy-policy',
				'how_tc_rate'                    => 'https://www.iubenda.com/nl/help/19482-what-should-basic-terms-and-conditions-include',
				'user_account'                   => 'https://www.iubenda.com/nl/account',
				'amp_permission_support'         => 'https://www.iubenda.com/nl/help/22135-cookie-solution-amp-wordpress#folder-permissions',
				'google_tag_manager_blocking'    => 'https://www.iubenda.com/nl/help/1235-google-tag-manager-blocking-cookies',
				'frontend_auto_blocking'         => 'https://www.iubenda.com/nl/help/133036-prior-blocking-of-cookies-automatic-blocking-auto-blocking',
			),
		);

		foreach ( $this->services as $name => $service ) {
			$this->services[ $name ]['status'] = iub_array_get( iubenda()->options, "activated_products.{$service['key']}", 'false' );
		}
		$this->quick_generator = (array) get_option( static::IUB_QG_RESPONSE, array() );

		$user_profile_language = ( new Language_Helper() )->get_user_profile_language_code( true );

		// assign links.
		$this->links = in_array( (string) $user_profile_language, array_keys( $links ), true ) ? $links[ $user_profile_language ] : $links['en'];

		// handle actions.
		$save = iub_get_request_parameter( 'save', null );

		if ( $save ) {
			// update item action.
			$this->action = 'save';
		} else {
			$action  = iub_get_request_parameter( 'action' );
			$action2 = iub_get_request_parameter( 'action2' );

			$this->action = ! empty( $action ) ? $action : '';
			$this->action = ! empty( $action2 ) ? $action2 : $action;
		}
	}

	/**
	 * Add submenu.
	 *
	 * @return void
	 */
	public function admin_menu_options() {
		if ( 'submenu' === iubenda()->options['cs']['menu_position'] ) {
			// sub menu.
			add_submenu_page(
				'options-general.php',
				'iubenda',
				'iubenda',
				apply_filters( 'iubenda_cookie_law_cap', 'manage_options' ),
				'iubenda',
				array( $this, 'options_page' )
			);
		} else {
			// top menu.
			add_menu_page(
				'iubenda',
				'iubenda',
				apply_filters( 'iubenda_cookie_law_cap', 'manage_options' ),
				'iubenda',
				array( $this, 'options_page' ),
				'none'
			);
		}
	}

	/**
	 * Check if at least one service is activated.
	 *
	 * @param   array $services  Array of services with 'status' key.
	 *
	 * @return bool True if at least one service has a true status, false otherwise.
	 */
	public function is_any_service_activated( $services ) {
		$result = array_filter(
			array_column( $services, 'status' ),
			function ( $service ) {
				return ( 'true' === (string) $service || true === $service );
			}
		);

		return ! empty( $result );
	}

	/**
	 * Check if at least one service is configured.
	 *
	 * @param   array $services  Array of services with 'configured' key.
	 *
	 * @return bool True if at least one service is configured as true, false otherwise.
	 */
	public function is_any_service_configured( $services ) {
		$result = array_filter(
			array_column( $services, 'configured' ),
			function ( $service ) {
				return ( 'true' === (string) $service || true === $service );
			}
		);

		return ! empty( $result );
	}

	/**
	 * Check if the site is already set up.
	 *
	 * @return bool True if the site is already set up, false otherwise.
	 */
	public function is_site_already_set_up() {
		// Check if the services has a activated service.
		if ( $this->is_any_service_activated( $this->services ) ) {
			return true;
		}

		// Check if the services are configured.
		if ( $this->is_any_service_configured( iubenda()->options ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Load admin options page.
	 *
	 * @return void
	 */
	public function options_page() {
		global $pagenow;

		$show_products_page = $this->is_site_already_set_up();

		if ( ! current_user_can( apply_filters( 'iubenda_cookie_law_cap', 'manage_options' ) ) ) {
			wp_die( esc_html__( "You don't have permission to access this page.", 'iubenda' ) );
		}
		$default = 'frontpage';

		if ( $show_products_page ) {
			$default = 'products-page';
		} elseif ( ! empty( ( new Quick_Generator_Service() )->qg_response ) ) {
			$default = 'integrate-setup';
		}

		$view = iub_get_request_parameter( 'view' );

		switch ( $view ) {
			case 'plugin-settings':
				$page_labels = array( array( 'title' => __( 'Plugin settings', 'iubenda' ) ) );
				require_once IUBENDA_PLUGIN_PATH . 'views/plugin-settings.php';
				break;
			case 'integrate-setup':
				require_once IUBENDA_PLUGIN_PATH . 'views/integrate-setup.php';
				break;
			case 'products-page':
				require_once IUBENDA_PLUGIN_PATH . 'views/products-page.php';
				break;
			case 'tc-configuration':
				$page_labels = array( array( 'title' => __( 'Terms and condition', 'iubenda' ) ) );
				$key         = 'tc';
				$service     = iub_array_get( iubenda()->settings->services, $key );
				require_once IUBENDA_PLUGIN_PATH . 'views/tc-configuration.php';
				break;
			case 'pp-configuration':
				$page_labels = array( array( 'title' => __( 'Privacy and Cookie Policy', 'iubenda' ) ) );
				$key         = 'pp';
				$service     = iub_array_get( iubenda()->settings->services, $key );
				require_once IUBENDA_PLUGIN_PATH . 'views/pp-configuration.php';
				break;
			case 'cs-configuration':
				$page_labels = array( array( 'title' => __( 'Privacy Controls and Cookie Solution', 'iubenda' ) ) );
				$key         = 'cs';
				$service     = iub_array_get( iubenda()->settings->services, $key );
				require_once IUBENDA_PLUGIN_PATH . 'views/cs-configuration.php';
				break;
			case 'cons-configuration':
				$page_labels = array( array( 'title' => __( 'Consent Database', 'iubenda' ) ) );
				require_once IUBENDA_PLUGIN_PATH . 'views/cons-configuration.php';
				break;
			case 'cons-form-edit':
				$form_id = absint( iub_get_request_parameter( 'form_id', 0 ) );
				$form    = ! empty( $form_id ) ? iubenda()->forms->get_form( $form_id ) : false;

				if ( ! $form ) {
					return;
				}
				$page_labels = array(
					array(
						'title' => __( 'Consent Database', 'iubenda' ),
						'href'  => add_query_arg( array( 'view' => 'cons-configuration' ), iubenda()->base_url ),
					),
					array( 'title' => $form->post_title ),
				);
				require_once IUBENDA_PLUGIN_PATH . 'views/cons-single-form.php';
				break;
			default:
				require_once IUBENDA_PLUGIN_PATH . "views/{$default}.php";
		}
	}

	/**
	 * Admin enqueue scripts
	 *
	 * @param string $page Current admin page.
	 */
	public function admin_enqueue_scripts( $page ) {
		// Get radar api status.
		$iubenda_radar_api_configuration = (array) get_option( 'iubenda_radar_api_configuration', array() );

		// Localize the script with new data.
		$iub_js_vars = array(
			'site_url'                            => get_site_url(),
			'plugin_url'                          => IUBENDA_PLUGIN_URL,
			'site_language'                       => iubenda()->lang_current,
			'site_locale'                         => get_locale(),
			'radar_status'                        => iub_array_get( $iubenda_radar_api_configuration, 'status' ),
			'form_id'                             => iub_get_request_parameter( 'form_id', 0 ),
			'iub_dismiss_general_notice'          => wp_create_nonce( 'iub_dismiss_general_notice' ),
			'iub_quick_generator_callback_nonce'  => wp_create_nonce( 'iub_quick_generator_callback_nonce' ),
			'iub_toggle_service_nonce'            => wp_create_nonce( 'iub_toggle_service_nonce' ),
			'iub_save_cons_options_nonce'         => wp_create_nonce( 'iub_save_cons_options_nonce' ),
			'iub_auto_detect_forms_nonce'         => wp_create_nonce( 'iub_auto_detect_forms_nonce' ),
			'iub_radar_percentage_reload_nonce'   => wp_create_nonce( 'iub_radar_percentage_reload_nonce' ),
			'iub_frontpage_main_box_nonce'        => wp_create_nonce( 'iub_frontpage_main_box_nonce' ),
			'check_frontend_auto_blocking_status' => wp_create_nonce( 'check_frontend_auto_blocking_status' ),
			'iub_dashboard_compliance_nonce'      => wp_create_nonce( 'iub_dashboard_compliance_nonce' ),
		);

		wp_enqueue_script( 'iubenda-radar', IUBENDA_PLUGIN_URL . '/assets/js/radar.js', array( 'jquery' ), iubenda()->version, true );
		wp_localize_script( 'iubenda-radar', 'iub_js_vars', $iub_js_vars );

		if ( ! in_array( (string) $page, array( 'toplevel_page_iubenda', 'settings_page_iubenda' ), true ) ) {
			wp_enqueue_style( 'iubenda-admin', IUBENDA_PLUGIN_URL . '/assets/css/admin.css', array(), iubenda()->version );
			return;
		}
		wp_enqueue_style( 'iubenda-admin', IUBENDA_PLUGIN_URL . '/assets/css/style.css', array(), iubenda()->version );
		wp_enqueue_script( 'iubenda-admin', IUBENDA_PLUGIN_URL . '/assets/js/admin.js', array( 'jquery' ), iubenda()->version, true );

		wp_localize_script( 'iubenda-admin', 'iub_js_vars', $iub_js_vars );
		wp_enqueue_script( 'iubenda-admin-tabs', IUBENDA_PLUGIN_URL . '/assets/js/tabs.js', array( 'jquery' ), iubenda()->version, true );
	}

	/**
	 * Plugin options migration for versions < 1.14.0
	 *
	 * @return void
	 */
	public function update_plugin() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		$db_version = '1.13.0';
		if ( ! empty( (string) get_option( 'iubenda_cookie_law_version' ) ) ) {
			$db_version = (string) get_option( 'iubenda_cookie_law_version' );
		}

		if ( version_compare( $db_version, '1.14.0', '<' ) ) {
				$options = array();

				$old_new = array(
					'iubenda_parse'         => 'parse',
					'skip_parsing'          => 'skip_parsing',
					'iubenda_ctype'         => 'ctype',
					'parser_engine'         => 'parser_engine',
					'iubenda_output_feed'   => 'output_feed',
					'iubenda-code-default'  => 'code_default',
					'default_skip_parsing'  => '',
					'default_iubendactype'  => '',
					'default_iubendaparse'  => '',
					'default_parser_engine' => '',
					'iub_code'              => '',
				);

				foreach ( $old_new as $old => $new ) {
					if ( $new ) {
						$options[ $new ] = get_option( $old );
					}
					delete_option( $old );
				}

				// multilang support.
				if ( ! empty( iubenda()->languages ) ) {
					foreach ( iubenda()->languages as $lang_id => $lang_name ) {
						$code = get_option( 'iubenda-code-' . $lang_id );

						if ( ! empty( $code ) ) {
							$options[ 'code_' . $lang_id ] = $code;

							delete_option( 'iubenda-code-' . $lang_id );
						}
					}
				}

				add_option( 'iubenda_cookie_law_solution', $options, '', 'no' );
				add_option( 'iubenda_cookie_law_version', iubenda()->version, '', 'no' );
		}
	}

	/**
	 * Update products options by ajax request.
	 *
	 * @param   array $data POST data request.
	 */
	public function synchronize_products( $data = array() ) {
		iub_verify_ajax_request( 'iub_synchronize_products', 'iub_synchronize_products_nonce' );
		// phpcs:ignore WordPress.Security.NonceVerification.Missing
		$data = ! empty( $data ) ? $data : $_POST;

		if ( (string) iub_array_get( $data, 'iubenda_privacy_policy_solution_status' ) === 'true' ) {
			// Saving PP data with PP function.
			( new Iubenda_PP_Product_Service() )->saving_pp_options( false );
		} else {
			$result['iubenda_activated_products']['iubenda_privacy_policy_solution'] = 'false';
		}

		if ( (string) iub_array_get( $data, 'iubenda_terms_conditions_solution_status' ) === 'true' ) {
			// Saving TC data with TC function.
			( new Iubenda_TC_Product_Service() )->saving_tc_options();
		} else {
			$result['iubenda_activated_products']['iubenda_terms_conditions_solution'] = 'false';
		}

		if ( (string) iub_array_get( $data, 'iubenda_cookie_law_solution_status' ) === 'true' ) {
			// Saving CS data with CS function.
			( new Iubenda_CS_Product_Service() )->saving_cs_options();
		} else {
			wp_send_json(
				array(
					'status'       => 'error',
					'responseText' => '( CS ) must be activated.',
				)
			);
		}

		if ( (string) iub_array_get( $data, 'iubenda_consent_solution_status' ) === 'true' ) {
			// iubenda_consent_solution saving data.
			$public_api_key = sanitize_text_field( wp_unslash( (string) iub_array_get( $data, 'public_api_key' ) ) );

			if ( ! empty( $public_api_key ) ) {
				$product_option['configured']     = 'true';
				$product_option['public_api_key'] = $public_api_key;
				// update only cons make it activated service.
				iubenda()->options['activated_products']['iubenda_consent_solution'] = 'true';
				iubenda()->iub_update_options( 'iubenda_activated_products', iubenda()->options['activated_products'] );

				// Merge old cons options with new options.
				$old_options = iubenda()->options['cons'];
				$new_options = array_merge( $old_options, $product_option );

				// Update Database and current instance with new TC options.
				iubenda()->options['cons'] = $new_options;
				iubenda()->iub_update_options( 'iubenda_consent_solution', $new_options );
			} else {
				iubenda()->options['activated_products']['iubenda_consent_solution'] = 'false';
				iubenda()->iub_update_options( 'iubenda_activated_products', iubenda()->options['activated_products'] );
			}
		} else {
			iubenda()->options['activated_products']['iubenda_consent_solution'] = 'false';
			iubenda()->iub_update_options( 'iubenda_activated_products', iubenda()->options['activated_products'] );
		}

		wp_send_json( array( 'status' => 'done' ) );
	}

	/**
	 * Toggle iubenda service by ajax request.
	 */
	public function toggle_services() {
		iub_verify_ajax_request( 'iub_toggle_service_nonce', 'iub_nonce' );
		// phpcs:ignore WordPress.Security.NonceVerification.Missing
		$name = sanitize_text_field( iub_array_get( $_POST, 'name' ) );
		// phpcs:ignore WordPress.Security.NonceVerification.Missing
		$status = sanitize_text_field( iub_array_get( $_POST, 'status' ) );

		if ( 'true' !== $status ) {
			$status = 'false';
		}

		$iubenda_activated_products          = get_option( 'iubenda_activated_products' );
		$iubenda_activated_products[ $name ] = $status;

		iubenda()->iub_update_options( 'iubenda_activated_products', $iubenda_activated_products );

		// Reload Options and activated products.
		iubenda()->options['activated_products'] = get_option( 'iubenda_activated_products', array() );
		$this->load_defaults();

		wp_send_json(
			array(
				'status'            => 'done',
				'rating_percentage' => iubenda()->service_rating->services_percentage(),
			)
		);
	}

	/**
	 * Process the bulk actions
	 *
	 * @return void
	 */
	public function process_actions() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		global $pagenow;

		$page = iub_get_request_parameter( 'option_page' );
		if ( empty( $page ) ) {
			$page = iub_get_request_parameter( 'page' );
		}

		$id = iub_get_request_parameter( 'form_id' );
		$id = empty( $id ) ? false : absint( $id );

		$view_key = iub_get_request_parameter( 'view' );

		if ( empty( $view_key ) ) {
			$view_key = null;
		}

		if ( empty( $page ) ) {
			return;
		}

		// get redirect url.
		if ( 'submenu' === iubenda()->options['cs']['menu_position'] && 'admin.php' === $pagenow ) {
			// sub menu.
			$redirect_to = admin_url( 'options-general.php?page=iubenda&view=' . $view_key );
		} else {
			// top menu.
			$redirect_to = admin_url( 'admin.php?page=iubenda&view=' . $view_key );
		}

		// add comments cookie option notice.
		if ( 'cons-configuration' === $view_key && ! empty( iubenda()->options['cons']['public_api_key'] ) ) {
			$cookies_enabled = get_option( 'show_comments_cookies_opt_in' );
			if ( ! $cookies_enabled ) {
				iubenda()->notice->add_notice( 'iub_comment_cookies_disabled' );
			}
		}

		$result = null;

		switch ( $this->action ) {
			case 'save':
				if ( ! $id ) {
					return;
				}

				iub_verify_postback_request( 'iubenda_consent_solution-options' );
				// phpcs:ignore WordPress.Security.NonceVerification.Missing
				$post_data = $_POST;
				$form      = iubenda()->forms->get_form( $id );

				if ( $form->ID !== $id ) {
					return;
				}

				$status        = isset( $post_data['status'] ) && in_array( (string) $post_data['status'], array_keys( iubenda()->forms->statuses ), true ) ? sanitize_text_field( $post_data['status'] ) : 'publish';
				$subject       = isset( $post_data['subject'] ) && is_array( $post_data['subject'] ) ? array_map( 'sanitize_text_field', $post_data['subject'] ) : array();
				$preferences   = array();
				$exclude       = array();
				$legal_notices = array();

				$preferences_raw   = isset( $post_data['preferences'] ) && is_array( $post_data['preferences'] ) ? array_map( array( $this, 'array_map_callback' ), $post_data['preferences'] ) : array();
				$exclude_raw       = isset( $post_data['exclude'] ) && is_array( $post_data['exclude'] ) ? array_map( array( $this, 'array_map_callback' ), $post_data['exclude'] ) : array();
				$legal_notices_raw = isset( $post_data['legal_notices'] ) && is_array( $post_data['legal_notices'] ) ? array_map( array( $this, 'array_map_callback' ), $post_data['legal_notices'] ) : array();

				// format preferences data.
				if ( ! empty( $preferences_raw ) && is_array( $preferences_raw ) ) {
					foreach ( $preferences_raw as $index => $data ) {
						if ( ! empty( $data['field'] ) && ( ! is_null( $data['value'] ) || ! '' === (string) $data['value'] ) ) {
							$preferences[ sanitize_text_field( $data['field'] ) ] = $data['value'];
						}
					}
				}

				// format exclude data.
				if ( ! empty( $exclude_raw ) && is_array( $exclude_raw ) ) {
					foreach ( $exclude_raw as $index => $data ) {
						if ( ! empty( $data['field'] ) ) {
							$exclude[] = $data['field'];
						}
					}
				}

				// format legal notices data.
				if ( ! empty( $legal_notices_raw ) && is_array( $legal_notices_raw ) ) {
					foreach ( $legal_notices_raw as $index => $data ) {
						if ( ! empty( $data['field'] ) ) {
							$legal_notices[] = $data['field'];
						}
					}
				}

				// form first save, update status to mapped automatically.
				if ( empty( $form->form_subject ) && empty( $form->form_preferences ) ) {
					$status = 'mapped';
				}

				$filtered_subjects = array_filter(
					$subject,
					array(
						$this,
						'is_not_empty',
					)
				);

				// bail if empty fields.
				if ( ! count( $filtered_subjects ) ) {
					iubenda()->notice->add_notice( 'iub_form_fields_missing' );
					return;
				}

				$args = array(
					'ID'                 => $form->ID,
					'status'             => $status,
					'object_type'        => $form->object_type,
					'object_id'          => $form->object_id,
					'form_source'        => $form->form_source,
					'form_title'         => $form->post_title,
					'form_date'          => $form->post_modified,
					'form_fields'        => $form->form_fields,
					'form_subject'       => $subject,
					'form_preferences'   => $preferences,
					'form_exclude'       => $exclude,
					'form_legal_notices' => $legal_notices,
				);

				$result = iubenda()->forms->save_form( $args );
				break;
			case 'delete':
				if ( ! $id ) {
					return;
				}

				iub_verify_postback_request( "delete-form_{$id}" );

				$form = iubenda()->forms->get_form( $id );

				if ( empty( $form ) ) {
					return;
				}

				$result = iubenda()->forms->delete_form( $id );

				// make sure it's current host location.
				wp_safe_redirect( $redirect_to );
				exit;
			case 'disable_skip_parsing':
				// disable skip parsing option.
				$options                 = iubenda()->options['cs'];
				$options['skip_parsing'] = false;

				iubenda()->iub_update_options( 'iubenda_cookie_law_solution', $options );

				iubenda()->notice->add_notice( 'iub_settings_updated' );

				// make sure it's current host location.
				wp_safe_redirect( $redirect_to );
				exit;
			default:
				return;
		}
	}

	/**
	 * Sanitize array helper function.
	 *
	 * @param array $target_array  array.
	 *
	 * @return array
	 */
	public function array_map_callback( $target_array ) {
		if ( ! is_array( $target_array ) ) {
			return array();
		}

		return array_map( 'sanitize_text_field', $target_array );
	}

	/**
	 * Check the value is not empty and check it contains any value even 0
	 *
	 * @param mixed $value value.
	 * @return bool
	 */
	private function is_not_empty( $value ) {
		if ( is_null( $value ) || '' === $value ) {
			return false;
		}

		return true;
	}

	/**
	 * Load admin style inline, for menu icon only.
	 *
	 * @return void
	 */
	public function admin_print_styles() {
		?>
		<style>
			a.toplevel_page_iubenda .wp-menu-image {
				background-image: url(data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iVVRGLTgiIHN0YW5kYWxvbmU9Im5vIj8+PCFET0NUWVBFIHN2ZyBQVUJMSUMgIi0vL1czQy8vRFREIFNWRyAxLjEvL0VOIiAiaHR0cDovL3d3dy53My5vcmcvR3JhcGhpY3MvU1ZHLzEuMS9EVEQvc3ZnMTEuZHRkIj48c3ZnIHdpZHRoPSIxMDAlIiBoZWlnaHQ9IjEwMCUiIHZpZXdCb3g9IjAgMCAyMzIgNTAzIiB2ZXJzaW9uPSIxLjEiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiIHhtbDpzcGFjZT0icHJlc2VydmUiIHhtbG5zOnNlcmlmPSJodHRwOi8vd3d3LnNlcmlmLmNvbS8iIHN0eWxlPSJmaWxsLXJ1bGU6ZXZlbm9kZDtjbGlwLXJ1bGU6ZXZlbm9kZDtzdHJva2UtbGluZWpvaW46cm91bmQ7c3Ryb2tlLW1pdGVybGltaXQ6MS40MTQyMTsiPiAgICA8ZyB0cmFuc2Zvcm09Im1hdHJpeCgxLDAsMCwxLDEzNi4yNDcsMjY4LjgzMSkiPiAgICAgICAgPHBhdGggZD0iTTAsLTM1LjgxTC0zNi4zLDAuNDg5TC0zNi4zLDE0MC45NzhMMCwxNDAuOTc4TDAsLTM1LjgxWk0tMjAuOTM4LC0xMjkuODAyQy02LjI4NywtMTI5LjgwMiA1LjU4NywtMTQxLjU2NSA1LjU4NywtMTU2LjA2QzUuNTg3LC0xNzAuNTU2IC02LjI4NywtMTgyLjMwOCAtMjAuOTM4LC0xODIuMzA4Qy0zNS42LC0xODIuMzA4IC00Ny40NzQsLTE3MC41NTYgLTQ3LjQ3NCwtMTU2LjA2Qy00Ny40NzQsLTE0MS41NjUgLTM1LjYsLTEyOS44MDIgLTIwLjkzOCwtMTI5LjgwMk04OS4zNiwtMTU0LjQxNkM4OS4zNiwtMTI3LjgyNSA3OS41NzUsLTEwMy40OTkgNjMuMjY5LC04NC42NzJMODYuNjk0LDIyNi42MjhMLTEyMi43MjgsMjI2LjYyOEwtMTAwLjAyNCwtNzkuMjI5Qy0xMTkuMzUxLC05OC42NjggLTEzMS4yNDcsLTEyNS4xNTkgLTEzMS4yNDcsLTE1NC40MTZDLTEzMS4yNDcsLTIxNC4wODYgLTgxLjg3NCwtMjYyLjQzOCAtMjAuOTM4LC0yNjIuNDM4QzM5Ljk5OSwtMjYyLjQzOCA4OS4zNiwtMjE0LjA4NiA4OS4zNiwtMTU0LjQxNiIgc3R5bGU9ImZpbGw6d2hpdGU7ZmlsbC1ydWxlOm5vbnplcm87Ii8+ICAgIDwvZz48L3N2Zz4=);
				background-position: center center;
				background-repeat: no-repeat;
				background-size: 7px auto;
			}
		</style>
		<?php
	}

	/**
	 * Services option.
	 *
	 * @return array[]
	 */
	private function services_option() {
		$cs_settings = array();
		if ( (string) iub_array_get( iubenda()->options['cs'], 'configuration_type' ) === 'simplified' ) {
			$legislation = (array) iub_array_get( iubenda()->options['cs'], 'simplified.legislation', array() );
			if ( (bool) iub_array_get( $legislation, 'all' ) ) {
				$legislation = array(
					'gdpr' => true,
					'uspr' => true,
					'lgpd' => true,
				);
			}

			$cs_settings = array(
				array(
					'label' => __( 'Style', 'iubenda' ),
					'value' => ucwords( iub_array_get( iubenda()->options['cs'], 'simplified.banner_style' ) ),
				),
				array(
					'label' => __( 'Position', 'iubenda' ),
					'value' => ucwords( iub_array_get( iubenda()->options['cs'], 'simplified.position' ) ),
				),
				array(
					'label' => __( 'legislation', 'iubenda' ),
					'value' => strtoupper( implode( '/', array_keys( $legislation ) ) ),
				),
			);
		} else {
			$languages = ( new Product_Helper() )->get_languages();
			foreach ( $languages as $k => $v ) {
				$code = iub_array_get( iubenda()->options['cs'], "code_{$k}" );
				if ( $code ) {
					$banner      = iubenda()->parse_configuration( $code, array( 'mode' => 'banner' ) );
					$style       = iub_array_get( $banner, 'backgroundColor' ) ? 'White' : 'Dark';
					$legislation = ( new Iubenda_CS_Product_Service() )->get_legislation_from_embed_code( $code );

					$cs_settings = array(
						array(
							'label' => __( 'Style', 'iubenda' ),
							'value' => $style,
						),
						array(
							'label' => __( 'Position', 'iubenda' ),
							'value' => ucwords( iub_array_get( $banner, 'position', 'full-top' ) ),
						),
						array(
							'label' => __( 'legislation', 'iubenda' ),
							'value' => $legislation,
						),
					);
					break;
				}
			}
		}

		return array(
			'pp'   => array(
				'status'     => false,
				'configured' => iub_array_get( iubenda()->options['pp'], 'configured' ),
				'label'      => __( 'Privacy and Cookie Policy', 'iubenda' ),
				'name'       => 'privacy_policy',
				'key'        => 'iubenda_privacy_policy_solution',
				'settings'   => array(
					array(
						'label' => __( 'Version', 'iubenda' ),
						'value' => iub_array_get( iubenda()->options['pp'], 'version' ),
					),
					array(
						'label' => __( 'Style', 'iubenda' ),
						'value' => iub_array_get( iubenda()->options['pp'], 'button_style' ),
					),
					array(
						'label' => __( 'Position', 'iubenda' ),
						'value' => iub_array_get( iubenda()->options['pp'], 'button_position' ),
					),
				),
			),
			'cs'   => array(
				'status'     => false,
				'configured' => iub_array_get( iubenda()->options['cs'], 'configured' ),
				'label'      => __( 'Privacy Controls and Cookie Solution', 'iubenda' ),
				'name'       => 'cookie_law',
				'key'        => 'iubenda_cookie_law_solution',
				'settings'   => $cs_settings,
			),
			'tc'   => array(
				'status'     => false,
				'configured' => iub_array_get( iubenda()->options['tc'], 'configured' ),
				'label'      => __( 'Terms and Conditions', 'iubenda' ),
				'name'       => 'terms_conditions',
				'key'        => 'iubenda_terms_conditions_solution',
				'settings'   => array(
					array(
						'label' => __( 'Version', 'iubenda' ),
						'value' => '1.5.0',
					),
					array(
						'label' => __( 'Style', 'iubenda' ),
						'value' => iub_array_get( iubenda()->options['tc'], 'button_style' ),
					),
					array(
						'label' => __( 'Position', 'iubenda' ),
						'value' => iub_array_get( iubenda()->options['tc'], 'button_position' ),
					),
				),
			),
			'cons' => array(
				'status'     => false,
				'configured' => iub_array_get( iubenda()->options['cons'], 'configured' ),
				'label'      => __( 'Consent Database', 'iubenda' ),
				'name'       => 'consent',
				'key'        => 'iubenda_consent_solution',
			),
		);
	}

	/**
	 * Return error to appear alert modal
	 *
	 * @param string $index index.
	 * @param string $section section.
	 */
	public function return_alert( string $index, $section ) {
		$response = 'code_default' === $index ?? "($index)";
		wp_send_json(
			array(
				'status'       => 'error',
				'responseText' => "invalid script {$response}",
				'focus'        => "#{$index}-{$section}_tab",
			)
		);
	}

	/**
	 * Getting main div in frontpage with updated data
	 */
	public function get_frontpage_main_box() {
		iub_verify_ajax_request( 'iub_frontpage_main_box_nonce', 'iub_nonce' );
		require_once IUBENDA_PLUGIN_PATH . '/views/partials/frontpage-main-box.php';
		wp_die();
	}

	/**
	 * Init prepare product options while upgrading.
	 *
	 * @param array $products products.
	 * @param array $data data.
	 * @return array
	 */
	public function init_prepare_product_options_while_upgrading( $products, $data ) {
		$result = array();

		foreach ( $products as $product_name => $product_key ) {
			$product_key    = (string) $product_key;
			$product_option = array();

			if ( iub_array_get( $data, "{$product_name}_status" ) && 'true' === (string) iub_array_get( $data, "{$product_name}_status" ) ) {
				$result['iubenda_activated_products'][ $product_name ] = 'true';
				$product_option['configured']                          = 'true';

				// Check if product is CONS.
				if ( 'cons' === $product_key ) {
					// iubenda_consent_solution saving data.
					if ( iub_array_get( $data, "{$product_name}.public_api_key" ) ?? null ) {
						$product_option = array( 'public_api_key' => iub_array_get( $data, "{$product_name}.public_api_key" ) );
					} else {
						$result['iubenda_activated_products'][ $product_name ] = 'false';
					}
				}

				// Check if product in ['PP', 'CS', 'TC'] to check and validate embed codes.
				if ( in_array( $product_key, array( 'pp', 'cs', 'tc' ), true ) ) {
					$languages = ( new Product_Helper() )->get_languages();
					foreach ( $languages as $lang_id => $lang_name ) {
						$code = iub_array_get( $data, "{$product_name}.code_{$lang_id}" );

						// check if code is empty or code is invalid.
						$result['codes_statues'][ "{$product_name}_codes" ][] = ! empty( $code );

						// get public_id & site_id if only the product key is CS and had a valid embed code.
						$parsed_code = array_filter( iubenda()->parse_configuration( $code ) );
						if ( 'cs' === $product_key && ! empty( $parsed_code ) ) {
							// getting site id to save it into Iubenda global option.
							if ( iub_array_get( $parsed_code, 'siteId' ) ?? null ) {
								$result['site_id'] = iub_array_get( $parsed_code, 'siteId' );
							}

							// getting public id to save it into Iubenda global option by lang.
							if ( iub_array_get( $parsed_code, 'cookiePolicyId' ) ?? null ) {
								$result['public_ids'][ $lang_id ] = iub_array_get( $parsed_code, 'cookiePolicyId' );
							}
						}

						if ( in_array( $product_key, array( 'pp', 'tc' ), true ) ) {
							$parsed_code = iubenda()->parse_tc_pp_configuration( $code );

							// getting public id to save it into Iubenda global option lang.
							if ( $parsed_code ) {
								$result['public_ids'][ $lang_id ] = iub_array_get( $parsed_code, 'cookie_policy_id' );
								$product_option['button_style']   = iub_array_get( $parsed_code, 'button_style' );
							}

							// to make tc/pp button appear in footer by default.
							$product_option['button_position'] = 'automatic';

							// Add a widget in the sidebar.
							iubenda()->assign_legal_block_or_widget();
						}

						$product_option[ "code_{$lang_id}" ]        = $code;
						$product_option[ "manual_code_{$lang_id}" ] = $code;
					}
				}

				if ( in_array( $product_key, array( 'pp', 'tc' ), true ) ) {
					// Add a widget in the sidebar if the button is positioned automatically.
					iubenda()->assign_legal_block_or_widget();
				}

				// add version if Iubenda privacy policy solution activated.
				if ( 'pp' === $product_key ) {
					$product_option['version'] = 'manual';
				}

				// Send options to save it.
				$result['products_option'][ $product_key ] = $product_option;
			} else {
				$result['iubenda_activated_products'][ $product_name ] = 'false';
			}
		}

		return $result;
	}

	/**
	 * Save init prepared product options.
	 *
	 * @param array $products Iubenda products.
	 * @param array $result Iubenda product options.
	 */
	public function save_init_prepared_product_options( $products, $result ) {
		// Getting product option to save it.
		foreach ( $products as $product_name => $product_key ) {
			$product_status = (string) iub_array_get( $result, "iubenda_activated_products.{$product_name}", 'false' ) ?? 'false';
			if ( 'false' === $product_status ) {
				continue;
			}

			$product_option     = (array) iub_array_get( $result, "products_option.{$product_key}", array() ) ?? array();
			$new_product_option = array_merge( (array) iubenda()->options[ $product_key ] ?? array(), $product_option );

			// Merging old $product_name options with new options.
			iubenda()->iub_update_options( $product_name, $new_product_option );

			// Update Iubenda instance with new $product_name options.
			iubenda()->options[ $product_key ] = $new_product_option;
		}

		$allowed_sections           = array(
			'iubenda_consent_solution',
			'iubenda_cookie_law_solution',
			'iubenda_privacy_policy_solution',
			'iubenda_terms_conditions_solution',
		);
		$iubenda_activated_products = iub_array_only( (array) iub_array_get( $result, 'iubenda_activated_products' ) ?? array(), $allowed_sections );

		// Merging old iubenda activated products with new.
		$old_iubenda_activated_products = (array) iub_array_get( iubenda()->options, 'activated_products', array() ) ?? array();
		$new_iubenda_activated_products = array_merge( $old_iubenda_activated_products, $iubenda_activated_products );
		iubenda()->iub_update_options( 'iubenda_activated_products', $new_iubenda_activated_products );

		// Update Iubenda instance with new activated products.
		iubenda()->options['activated_products'] = $new_iubenda_activated_products;

		// Merging old iubenda global options with new.
		$old_iubenda_global_options = (array) iub_array_get( iubenda()->options, 'global_options', array() ) ?? array();

		$new_iubenda_global_options = array();
		if ( iub_array_get( $result, 'site_id' ) ?? null ) {
			$new_iubenda_global_options['site_id'] = iub_array_get( $result, 'site_id' );
		}
		if ( iub_array_get( $result, 'public_ids', array() ) ?? null ) {
			$new_iubenda_global_options['public_ids'] = iub_array_get( $result, 'public_ids', array() );
		}
		iubenda()->iub_update_options( 'iubenda_global_options', array_merge( $old_iubenda_global_options, $new_iubenda_global_options ) );
		iubenda()->options['global_options'] = array_merge( $old_iubenda_global_options, $new_iubenda_global_options );

		$this->load_defaults();
	}

	/**
	 * Retrieves the Iubenda API key.
	 *
	 * @return string The API key.
	 */
	private function get_iub_qg_api_key() {
		// Check if the API key is set in the $_SERVER variable.
		if ( isset( $_SERVER['iubenda_api_key'] ) && ! empty( $_SERVER['iubenda_api_key'] ) ) {
			return trim( sanitize_text_field( wp_unslash( $_SERVER['iubenda_api_key'] ) ) );
		}

		// Check if the API key is set in the $_ENV variable.
		if ( isset( $_ENV['iubenda_api_key'] ) && ! empty( $_ENV['iubenda_api_key'] ) ) {
			return trim( sanitize_text_field( wp_unslash( $_ENV['iubenda_api_key'] ) ) );
		}

		$iub_api_key = get_option( 'iubenda_api_key' );
		if ( $iub_api_key ) {
			return trim( sanitize_text_field( wp_unslash( $iub_api_key ) ) );
		}

		// If the API key is not set in $_SERVER or $_ENV or in DB, use the predefined constant.
		return self::IUB_QG_API_KEY;
	}
}
