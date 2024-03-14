<?php
/**
 * Moove_GDPR_Actions File Doc Comment
 *
 * @category  Moove_GDPR_Actions
 * @package   gdpr-cookie-compliance
 * @author    Moove Agency
 */
if ( ! defined( 'ABSPATH' ) ) :
	exit;
endif; // Exit if accessed directly.

/**
 * Moove_GDPR_Actions Class Doc Comment
 *
 * @category Class
 * @package  Moove_GDPR_Actions
 * @author   Moove Agency
 */
class Moove_GDPR_Actions {

	/**
	 * Global variable used in localization
	 *
	 * @var $gdpr_loc_data Localization variable
	 */
	public $gdpr_loc_data;
	/**
	 * Construct
	 */
	public function __construct() {
		$this->moove_register_scripts();
		$this->moove_register_ajax_actions();
		add_action( 'gdpr_cookie_filter_settings', array( &$this, 'gdpr_remove_cached_scripts' ) );
		add_action( 'gdpr_settings_tab_nav_extensions', array( &$this, 'gdpr_settings_tab_nav_extensions' ), 10, 1 );
		add_action( 'gdpr_check_extensions', array( &$this, 'gdpr_check_extensions' ), 10, 2 );
		add_action( 'gdpr_premium_section_ads', array( &$this, 'gdpr_premium_section_ads' ) );
		add_action( 'gdpr_tab_cbm_ph', array( &$this, 'gdpr_premium_section_ads' ) );
		add_action( 'gdpr_tab_cbm_ps', array( &$this, 'gdpr_premium_section_ads' ) );
		add_action( 'gdpr_get_alertbox', array( 'Moove_GDPR_Content', 'gdpr_get_alertbox' ), 10, 3 );
		add_action( 'gdpr_licence_input_field', array( 'Moove_GDPR_Content', 'gdpr_licence_input_field' ), 10, 2 );
		add_action( 'gdpr_licence_action_button', array( 'Moove_GDPR_Content', 'gdpr_licence_action_button' ), 10, 2 );
		add_action( 'gdpr_premium_update_alert', array( 'Moove_GDPR_Content', 'gdpr_premium_update_alert' ) );
		add_action( 'gdpr_cdn_url', array( &$this, 'gdpr_cdn_base_url' ), 10, 1 );
		add_action( 'gdpr_info_bar_button_extensions', array( &$this, 'gdpr_info_add_reject_button_extensions' ) );
		add_action( 'gdpr_info_bar_button_extensions', array( &$this, 'gdpr_info_add_close_button_extensions' ) );
		add_action( 'gdpr_info_bar_notice_content', array( &$this, 'gdpr_info_add_close_button_content' ) );
		add_action( 'gdpr_support_sidebar_class', array( &$this, 'gdpr_support_sidebar_class' ), 10, 1 );
		$gdpr_default_content = new Moove_GDPR_Content();
		$option_key           = $gdpr_default_content->moove_gdpr_get_key_name();
		$gdpr_key             = $gdpr_default_content->gdpr_get_activation_key( $option_key );

		add_action( 'admin_enqueue_scripts', array( &$this, 'gdpr_thirdparty_admin_scripts' ) );
		
		add_action( 'gdpr_cc_keephtml', array( &$this, 'gdpr_cc_keephtml' ), 10, 2 );

		add_action( 'wp_footer', array( 'Moove_GDPR_Controller', 'moove_gdpr_cookie_popup_modal' ), 99 );
		add_action( 'wp_head', array( 'Moove_GDPR_Content', 'gdpr_google_consent_mode2_snippet' ) );

		add_action( 'admin_init', array( 'Moove_GDPR_Controller', 'moove_gdpr_add_editor_styles' ) );
		add_action( 'wp_footer', array( 'Moove_GDPR_Controller', 'moove_gdpr_cookie_popup_info' ) );
		add_action( 'moove_gdpr_inline_styles', array( &$this, 'gdpr_custom_button_styles' ), 20, 3 );

		// Get Option hook
		add_action( 'pre_option_' . $gdpr_default_content->moove_gdpr_get_option_name(), array( &$this, 'gdpr_get_options' ), 99, 1 );

		// Update Option Hook
		add_action( 'pre_update_option_' . $gdpr_default_content->moove_gdpr_get_option_name(), array( &$this, 'gdpr_update_options' ), 99, 3 );

		// Update Option Hook
		add_action( 'delete_option_' . $gdpr_default_content->moove_gdpr_get_option_name(), array( &$this, 'gdpr_delete_options' ), 99, 1 );
		add_action( 'gdpr_licence_key_visibility', array( &$this, 'gdpr_licence_key_visibility_hide' ), 10, 1 );

		if ( $gdpr_key && ! isset( $gdpr_key['deactivation'] ) ) :
			do_action( 'gdpr_plugin_loaded' );
		endif;

		// Admin CSS
		add_action('admin_head', function() {
			?>
			<style>.gdpr-plugin-star-rating{display:inline-block;color:#ffb900;position:relative;top:3px}.gdpr-plugin-star-rating svg,.gdpr-plugin-star-rating svg:hover{fill:#ffb900}.gdpr-plugin-star-rating svg:hover~svg{fill:none}</style>
			<?php
		});
		
		add_action( 'gdpr_cookie_custom_attributes', array( &$this, 'gdpr_cc_multisite_subdomain_url' ), 99, 1);
		add_action( 'gdpr_tab_section_cnt_class', array( &$this, 'gdpr_tab_section_cnt_class_filter' ), 10, 1 );
		add_action( 'gdpr_tabindex_attribute', array( &$this, 'gdpr_insert_tabindex_attribute' ), 10, 2 );

		// TranslatePress language support
		if ( function_exists( 'trp_get_languages' ) ) :
			add_action( 'gdpr_language_alert_bottom', array( &$this, 'gdpr_translatepress_language_select_extension' ), 10, 1 );
			add_action( 'admin_url', array( &$this, 'gdpr_form_admin_url_filter' ), 10, 1 );
		endif;

		// Falang language support
		if ( class_exists( 'Falang' ) ) :
			add_action( 'gdpr_language_alert_bottom', array( &$this, 'gdpr_falang_language_select_extension' ), 10, 1 );
			add_action( 'admin_url', array( &$this, 'gdpr_form_admin_url_filter' ), 10, 1 );
		endif;

		add_action( 'gdpr_template_html_load', array( &$this, 'gdpr_prevent_html_load_to_divi_builder' ), 10, 1);
			
		add_filter( 'gdpr_integration_modules', array( &$this, 'gdpr_integration_modules_gtm4wp' ), 10, 3 );
		add_filter( 'gdpr_cc_before_script_cache_set', array( 'Moove_GDPR_Content', 'gdpr_extend_integration_snippets' ), 10, 2 );
		add_action( 'gdpr_cc_licence_manager_action_button', array( 'Moove_GDPR_Content', 'gdpr_cc_licence_manager_action_button' ), 10, 1 );

		/**
		 * Integration Modules
		 */
		add_action( 'gdpr_insert_integration_ga_snippet', array( 'Moove_GDPR_Content', 'gdpr_insert_integration_ga_snippet' ), 10, 2 );
		add_action( 'gdpr_insert_integration_ga4_snippet', array( 'Moove_GDPR_Content', 'gdpr_insert_integration_ga4_snippet' ), 10, 2 );
		add_action( 'gdpr_insert_integration_gtm_snippet', array( 'Moove_GDPR_Content', 'gdpr_insert_integration_gtm_snippet' ), 10, 2 );
		add_action( 'gdpr_insert_integration_gtmc2_snippet', array( 'Moove_GDPR_Content', 'gdpr_insert_integration_gtmc2_snippet' ), 10, 2 );		
		add_action( 'gdpr_insert_integration_fbp_snippet', array( 'Moove_GDPR_Content', 'gdpr_insert_integration_fbp_snippet' ), 10, 2 );
		add_action( 'gdpr_insert_integration_gtm4wp_snippet', array( 'Moove_GDPR_Content', 'gdpr_insert_integration_gtm4wp_snippet' ), 10, 2 );
		add_action( 'gdpr_insert_integration_gadc_snippet', array( 'Moove_GDPR_Content', 'gdpr_insert_integration_gadc_snippet' ), 10, 2 );

		add_action( 'wp_ajax_gdpr_msba_bulk_activate', array( 'Moove_GDPR_License_Manager', 'gdpr_msba_bulk_activate_ajx' ) );
    	add_action( 'wp_ajax_nopriv_gdpr_msba_bulk_activate', array( 'Moove_GDPR_License_Manager', 'gdpr_msba_bulk_activate_ajx' ) );
	}

	/**
	 * GTM4WP Plugin Compatibility Integrations
	 * @param array $gdin_modules GDPR Integration Modules.
	 * @param array $gdpr_options GDPR Options.
	 * @param array $gdin_valie Integration Values.
	 * @return array $gdin_modules Extended modules.
	 */
	public static function gdpr_integration_modules_gtm4wp( $gdin_modules, $gdpr_options, $gdin_values ) {
		if ( defined( 'GTM4WP_VERSION' ) && defined('GTM4WP_OPTIONS') ) :
			$gdin_modules = $gdin_modules ? $gdin_modules : array();
			if ( isset( $gdin_modules['gtm'] ) ) :
				unset ( $gdin_modules['gtm'] );
			endif;
			
			$status = isset( $gdin_values['gtm4wp'] );
			if ( defined('GTM4WP_OPTIONS') && defined ( 'GTM4WP_OPTION_GTM_PLACEMENT' ) && defined ( 'GTM4WP_PLACEMENT_OFF' ) ) :
		      $storedoptions = (array) get_option( GTM4WP_OPTIONS );
				if ( isset( $storedoptions[GTM4WP_OPTION_GTM_PLACEMENT] ) && $storedoptions[GTM4WP_OPTION_GTM_PLACEMENT] !== GTM4WP_PLACEMENT_OFF ) :
					$status = false;
				endif;
				$gdin_modules['gtm4wp'] = array(
					'name'				=> 'Google Tag Manager',
					'desc'				=> 'Compatibility for GTM4WP',
					'cookie_cat'	=> isset( $gdin_values['gtm4wp'] ) ? intval( $gdin_values['gtm4wp'] ) : 2,
					'tacking_id'	=> isset(  $storedoptions['gtm-code'] ) && $storedoptions['gtm-code'] ? $storedoptions['gtm-code'] : '',
					'id_format'		=> 'G-XXXXXXX',
					'atts'				=> array(
						'toggle'		=> true,
						'input'		=> 'disabled'
					),
					'status'			=> $status
				);
			endif;
		endif;
		return $gdin_modules;
	}

	/**
	 * TranslatePress plugin support to switch language inside GDPR Cookie Compliance admin page
	 */
	public static function gdpr_form_admin_url_filter( $url ) {
		if ( strpos( $url, '?page=moove-gdpr' ) !== false && isset( $_GET['gdpr-lang'] ) ) :
			$lang_code 	= sanitize_text_field( wp_unslash( $_GET['gdpr-lang'] ) );
			$url 			= remove_query_arg( 'gdpr-lang', $url );
			$url 			= add_query_arg( 'gdpr-lang', $lang_code, $url );
		endif;
		return $url;
	}

	/**
	 * Prevent loading GDPR HTML templates to Divi Builder
	 */
	public static function gdpr_prevent_html_load_to_divi_builder( $load ) {
		if ( function_exists( 'et_core_is_fb_enabled' ) && et_core_is_fb_enabled() ) :
			$load = false;
		endif;
		return $load;
	}

	public static function gdpr_translatepress_language_select_extension( $language ) {
		if ( function_exists( 'trp_get_languages' ) ) :
			$trp_languages = trp_get_languages();
			global $TRP_LANGUAGE;
			?>
			<hr />
			<div class="gdpr-language-switch-admin">				
				<?php
					$server_host      = ( isset( $_SERVER['HTTPS'] ) && sanitize_text_field( wp_unslash( $_SERVER['HTTPS'] ) ) === 'on' ? 'https' : 'http' );
					$server_http_host = ( isset( $_SERVER['HTTP_HOST'] ) ) ? sanitize_text_field( wp_unslash( $_SERVER['HTTP_HOST'] ) ) : false;
					$server_req_uri   = ( isset( $_SERVER['REQUEST_URI'] ) ) ? sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : false;
					$actual_link      = $server_host . '://' . $server_http_host . $server_req_uri;
					$actual_link 		= remove_query_arg( 'gdpr-lang', $actual_link );
					$lang_links 		= [];
					foreach ( $trp_languages as $lang_code => $lang_name ) :
						if ( $lang_name !== $language ) :
							ob_start();
							?>
								<a href="<?php echo add_query_arg('gdpr-lang', $lang_code, $actual_link ); ?>" style="color: #fff"><?php echo $lang_name; ?></a>
							<?php
							$lang_links[] = ob_get_clean();
						endif;
					endforeach;
					if ( ! empty( $lang_links ) ) :
						?>
						<span style="color: #fff">Switch language: </span>
						<?php
						echo implode( ' | ', $lang_links );
					endif;
				?>
			</div>
			<!-- .gdpr-language-switch-admin -->
			<?php
		endif;
	}

	public static function gdpr_falang_language_select_extension( $language ) {
		if ( class_exists( 'Falang' ) ) :
			$gdpr_default_content 	= new Moove_GDPR_Content();
			$wpml_lang            	= $gdpr_default_content->moove_gdpr_get_wpml_lang();
			$falang_languages 		= Falang()->get_model()->get_languages_list();
			?>
			<hr />
			<div class="gdpr-language-switch-admin">				
				<?php
					$server_host      = ( isset( $_SERVER['HTTPS'] ) && sanitize_text_field( wp_unslash( $_SERVER['HTTPS'] ) ) === 'on' ? 'https' : 'http' );
					$server_http_host = ( isset( $_SERVER['HTTP_HOST'] ) ) ? sanitize_text_field( wp_unslash( $_SERVER['HTTP_HOST'] ) ) : false;
					$server_req_uri   = ( isset( $_SERVER['REQUEST_URI'] ) ) ? sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : false;
					$actual_link      = $server_host . '://' . $server_http_host . $server_req_uri;
					$actual_link 		= remove_query_arg( 'gdpr-lang', $actual_link );
					$lang_links 		= [];
					foreach ( $falang_languages as $language ) :
						$lang_name = isset( $language->locale ) ? $language->locale : ( isset( $language->slug ) ? $language->slug : '' );
						if ( $lang_name !== $wpml_lang ) :
							ob_start();
							?>
								<a href="<?php echo add_query_arg('gdpr-lang', $lang_name, $actual_link ); ?>" style="color: #fff"><?php echo $language->name; ?></a>
							<?php
							$lang_links[] = ob_get_clean();
						endif;
					endforeach;
					if ( ! empty( $lang_links ) ) :
						?>
						<span style="color: #fff">Switch language: </span>
						<?php
						echo implode( ' | ', $lang_links );
					endif;
				?>
			</div>
			<!-- .gdpr-language-switch-admin -->
			<?php
		endif;
	}

	/**
	 * Tab main section premium class
	 * @param string $tabindex Custom attribute.
	 * @param string $index_value Index Value.
	 */
	public static function gdpr_insert_tabindex_attribute( $tabindex, $index_value ) {
		$gdpr_default_content = new Moove_GDPR_Content();
		$option_name          = $gdpr_default_content->moove_gdpr_get_option_name();
		$gdpr_options         = get_option( $option_name );

		if ( isset( $gdpr_options['gdpr_accesibility'] ) && intval( $gdpr_options['gdpr_accesibility'] ) === 1 ) :
			$tabindex = ' tabindex="' . esc_attr( $index_value ) . '" ';
		else :
			$tabindex = '';
		endif;
		return $tabindex;
	}

	/**
	 * Tab main section premium class
	 * @param array $classes Classes.
	 */
	public static function gdpr_tab_section_cnt_class_filter( $classes = array() ) {
		if ( defined( 'GDPR_ADDON_VERSION' ) ) :
			$classes[] = 'gdpr-has-premium';
		endif;
		return $classes;
	}

	/**
   * Using main domain for WP MultiSite - Subdomain installs
   * @param string $attr Cookie attributes.
   */
	public static function gdpr_cc_multisite_subdomain_url( $attr ) {
		$gdpr_default_content = new Moove_GDPR_Content();
		$option_name          = $gdpr_default_content->moove_gdpr_get_option_name();
		$gdpr_options         = get_option( $option_name );

		if ( isset( $gdpr_options['moove_gdpr_sync_user_consent'] ) && intval( $gdpr_options['moove_gdpr_sync_user_consent'] ) ) :
			if ( function_exists( 'is_multisite' ) && is_multisite() && defined( 'SUBDOMAIN_INSTALL' ) && SUBDOMAIN_INSTALL === true ) :
				$site_url = network_site_url();
				$current_site_url = get_bloginfo( 'url' );
				$p_url 		= parse_url( $site_url );
				$domain		= $p_url && isset( $p_url['host'] ) && $p_url['host'] ? $p_url['host'] : false;
				$domain 		= str_replace( 'www.', '', $domain );
				if ( $domain && strpos( $current_site_url, $domain ) !== false && strpos( 'domain=', $attr ) === false ) :
					$domain = apply_filters( 'gdpr_cc_multisite_subdomain_main_domain', $domain );
					$attr .= 'domain=.' . $domain . ';';
				endif;
			endif;
		endif;
		return $attr;
	}

	/**
	 * Licence key asterisks hide in admin area
	 *
	 * @param string $key Licence key.
	 */
	public static function gdpr_licence_key_visibility_hide( $key ) {
		if ( $key ) :
			$_key = explode( '-', $key );
			if ( $_key && is_array( $_key ) ) :
				$_hidden_key = array();
				$key_count   = count( $_key );
				for ( $i = 0; $i < $key_count; $i++ ) :
					if ( 0 === $i || ( $key_count - 1 ) === $i ) :
						$_hidden_key[] = $_key[ $i ];
					else :
						$_hidden_key[] = '****';
					endif;
				endfor;
				$key = implode( '-', $_hidden_key );
			endif;
		endif;
		return $key;
	}

	/**
	 * Enqueue a script in the WordPress admin, excluding GDPR Settings page.
	 *
	 * @param int $hook Hook suffix for the current admin page.
	 */
	function gdpr_thirdparty_admin_scripts( $hook ) {
    if ( 'toplevel_page_moove-gdpr' !== $hook && 'gdpr-cookie-compliance_page_moove-gdpr_help' !== $hook ) :
       return;
    endif;
    wp_enqueue_script( 'gdpr_colorpicker_script', esc_url( moove_gdpr_get_plugin_directory_url() ) . 'dist/scripts/colorpicker.js', array(), MOOVE_GDPR_VERSION, true );
    wp_enqueue_script( 'gdpr_codemirror_script', esc_url( moove_gdpr_get_plugin_directory_url() ) . 'dist/scripts/codemirror.js', array(), MOOVE_GDPR_VERSION, true );  
    wp_enqueue_script( 'jquery-ui-sortable');  
	}

	/**
	 * Using custom database instead default WordPress options
	 * @param array $option_data Option data.
	 */
	public static function gdpr_get_options( $option_data ) {
		$gdpr_controller 	= new Moove_GDPR_Controller();
		$database_options	= gdpr_get_options();

		if ( $database_options && ! empty( $database_options ) ) :
			$option_data = $database_options;
		else :
			if ( is_array( $option_data ) ) :
				foreach ( $option_data as $option_key => $option_value ) :
					gdpr_update_field( $option_key, $option_value );
				endforeach;
			endif;
		endif;
		 			
		return $option_data;
	}

	/**
	 * Using custom database instead default WordPress options
	 * @param mixed $old_value Old Value.
	 * @param mixed $new_value New Value.bx-loading
	 * @param string $option Option.
	 */
	public static function gdpr_update_options( $new_value, $old_value, $option ) {
		if ( is_array( $new_value ) && ! empty( $new_value ) ) :
			foreach ( $new_value as $option_key => $option_value ) :
				if ( isset( $old_value[$option_key] ) ) :
					if ( $new_value[$option_key] !== $old_value[$option_key] ) :
						// updating option only if value was changed
						gdpr_update_field( $option_key, $option_value );
					endif;
				else :
					// creating new option value
					gdpr_update_field( $option_key, $option_value );
				endif;
			endforeach;
		endif;
		return '';
	}

	/**
	 * Using custom database instead default WordPress options
	 * @param mixed $old_value Old Value.
	 * @param mixed $new_value New Value.bx-loading
	 * @param string $option Option.
	 */
	public static function gdpr_delete_options( $option ) {
		gdpr_delete_option();
		return $option;
	}

	/**
	 * Extra class for admin sidebar widgets
	 *
	 * @param string $class Class name.
	 * @return string $class
	 */
	public function gdpr_support_sidebar_class( $class ) {
		if ( class_exists( 'Moove_GDPR_Addon_View' ) ) :
			$class = 'm-plugin-box-highlighted';
		endif;
		return $class;
	}

	public static function gdpr_custom_button_styles( $styles, $primary, $secondary ) {
		$gdpr_default_content = new Moove_GDPR_Content();
		$option_name          = $gdpr_default_content->moove_gdpr_get_option_name();
		$gdpr_options         = get_option( $option_name );
		$css 						= '';
		if ( isset( $gdpr_options['moove_gdpr_button_style'] ) && $gdpr_options['moove_gdpr_button_style'] !== 'rounded' ) :
			$css 	= apply_filters( 'gdpr_custom_button_styles', 'border-radius: 0;' );
		else :
			$css 	= apply_filters( 'gdpr_custom_button_styles', '' );
		endif;
		if ( $css ) :
			$styles .= '#moove_gdpr_cookie_info_bar .moove-gdpr-info-bar-container .moove-gdpr-info-bar-content a.mgbutton, #moove_gdpr_cookie_info_bar .moove-gdpr-info-bar-container .moove-gdpr-info-bar-content button.mgbutton, #moove_gdpr_cookie_modal .moove-gdpr-modal-content .moove-gdpr-modal-footer-content .moove-gdpr-button-holder a.mgbutton, #moove_gdpr_cookie_modal .moove-gdpr-modal-content .moove-gdpr-modal-footer-content .moove-gdpr-button-holder button.mgbutton, .gdpr-shr-button,
			#moove_gdpr_cookie_info_bar .moove-gdpr-infobar-close-btn { ' . $css . ' }';
		endif;

		$custom_font_weight = apply_filters( 'gdpr_font_wieght_title', 'inherit' );
		// Custom Font Weights
		if ( isset( $gdpr_options['moove_gdpr_plugin_font_type'] ) && '1' !== $gdpr_options['moove_gdpr_plugin_font_type'] || $custom_font_weight !== 'inherit' ) :
			?>
				#moove_gdpr_cookie_modal .moove-gdpr-modal-content .moove-gdpr-tab-main h3.tab-title, 
				#moove_gdpr_cookie_modal .moove-gdpr-modal-content .moove-gdpr-tab-main span.tab-title,
				#moove_gdpr_cookie_modal .moove-gdpr-modal-content .moove-gdpr-modal-left-content #moove-gdpr-menu li a, 
				#moove_gdpr_cookie_modal .moove-gdpr-modal-content .moove-gdpr-modal-left-content #moove-gdpr-menu li button,
				#moove_gdpr_cookie_modal .moove-gdpr-modal-content .moove-gdpr-modal-left-content .moove-gdpr-branding-cnt a,
				#moove_gdpr_cookie_modal .moove-gdpr-modal-content .moove-gdpr-modal-footer-content .moove-gdpr-button-holder a.mgbutton, 
				#moove_gdpr_cookie_modal .moove-gdpr-modal-content .moove-gdpr-modal-footer-content .moove-gdpr-button-holder button.mgbutton,
				#moove_gdpr_cookie_modal .cookie-switch .cookie-slider:after, 
				#moove_gdpr_cookie_modal .cookie-switch .slider:after, 
				#moove_gdpr_cookie_modal .switch .cookie-slider:after, 
				#moove_gdpr_cookie_modal .switch .slider:after,
				#moove_gdpr_cookie_info_bar .moove-gdpr-info-bar-container .moove-gdpr-info-bar-content p, 
				#moove_gdpr_cookie_info_bar .moove-gdpr-info-bar-container .moove-gdpr-info-bar-content p a,
				#moove_gdpr_cookie_info_bar .moove-gdpr-info-bar-container .moove-gdpr-info-bar-content a.mgbutton, 
				#moove_gdpr_cookie_info_bar .moove-gdpr-info-bar-container .moove-gdpr-info-bar-content button.mgbutton,
				#moove_gdpr_cookie_modal .moove-gdpr-modal-content .moove-gdpr-tab-main .moove-gdpr-tab-main-content h1, 
				#moove_gdpr_cookie_modal .moove-gdpr-modal-content .moove-gdpr-tab-main .moove-gdpr-tab-main-content h2, 
				#moove_gdpr_cookie_modal .moove-gdpr-modal-content .moove-gdpr-tab-main .moove-gdpr-tab-main-content h3, 
				#moove_gdpr_cookie_modal .moove-gdpr-modal-content .moove-gdpr-tab-main .moove-gdpr-tab-main-content h4, 
				#moove_gdpr_cookie_modal .moove-gdpr-modal-content .moove-gdpr-tab-main .moove-gdpr-tab-main-content h5, 
				#moove_gdpr_cookie_modal .moove-gdpr-modal-content .moove-gdpr-tab-main .moove-gdpr-tab-main-content h6,
				#moove_gdpr_cookie_modal .moove-gdpr-modal-content.moove_gdpr_modal_theme_v2 .moove-gdpr-modal-title .tab-title,
				#moove_gdpr_cookie_modal .moove-gdpr-modal-content.moove_gdpr_modal_theme_v2 .moove-gdpr-tab-main h3.tab-title, 
				#moove_gdpr_cookie_modal .moove-gdpr-modal-content.moove_gdpr_modal_theme_v2 .moove-gdpr-tab-main span.tab-title,
				#moove_gdpr_cookie_modal .moove-gdpr-modal-content.moove_gdpr_modal_theme_v2 .moove-gdpr-branding-cnt a {
				 	font-weight: <?php echo $custom_font_weight; ?>
				}
			<?php
		endif;

	  return $styles;
	}

	/**
	 * Sanitize filter allowing html tags and styles with attributes
	 *
	 * @param string  $content Content.
	 * @param boolean $echo Option echo the value or return.
	 */
	public function gdpr_cc_keephtml( $content, $echo = false ) {
		if ( $echo ) :
			echo $content;
		else :
			return $content;
		endif;
	}

	/**
	 * Reject button extension, will be listed next to the Accept button if it's enabled in the CMS
	 */
	public function gdpr_info_add_reject_button_extensions() {
		$gdpr_default_content = new Moove_GDPR_Content();
		$option_name          = $gdpr_default_content->moove_gdpr_get_option_name();
		$modal_options        = get_option( $option_name );
		$wpml_lang            = $gdpr_default_content->moove_gdpr_get_wpml_lang();
		$buttons_order  		 = isset( $modal_options['gdpr_bs_buttons_order'] ) ? json_decode( $modal_options['gdpr_bs_buttons_order'], true ) : array('accept', 'reject', 'settings', 'close');
		if ( isset( $modal_options['moove_gdpr_reject_button_enable'] ) && intval( $modal_options['moove_gdpr_reject_button_enable'] ) === 1 ) :
			$button_label = isset( $modal_options[ 'moove_gdpr_infobar_reject_button_label' . $wpml_lang ] ) && $modal_options[ 'moove_gdpr_infobar_reject_button_label' . $wpml_lang ] ? $modal_options[ 'moove_gdpr_infobar_reject_button_label' . $wpml_lang ] : __( 'Reject', 'gdpr-cookie-compliance' );
			$button_class 	= apply_filters( 'gdpr_reject_button_class_extension', '' );
			
			$button_order = in_array( 'reject', $buttons_order ) ? array_search( 'reject', $buttons_order ) : 'auto';
			?>
				<button class="mgbutton moove-gdpr-infobar-reject-btn gdpr-fbo-<?php echo esc_attr( $button_order ); ?> <?php echo esc_attr( $button_class ); ?>" <?php echo apply_filters('gdpr_tabindex_attribute', '', $button_order ); ?> aria-label="<?php echo esc_attr( $button_label ); ?>"><?php echo esc_attr( $button_label ); ?></button>
			<?php
		endif;

		if ( isset( $modal_options['moove_gdpr_settings_button_enable'] ) && intval( $modal_options['moove_gdpr_settings_button_enable'] ) === 1 ) :
			$button_order = in_array( 'settings', $buttons_order ) ? array_search( 'settings', $buttons_order ) : 'auto';
			$button_label = isset( $modal_options[ 'moove_gdpr_infobar_settings_button_label' . $wpml_lang ] ) && $modal_options[ 'moove_gdpr_infobar_settings_button_label' . $wpml_lang ] ? $modal_options[ 'moove_gdpr_infobar_settings_button_label' . $wpml_lang ] : __( 'Settings', 'gdpr-cookie-compliance' );
			?>
				<button class="mgbutton moove-gdpr-infobar-settings-btn change-settings-button gdpr-fbo-<?php echo esc_attr( $button_order ); ?>" data-href="#moove_gdpr_cookie_modal"<?php echo apply_filters('gdpr_tabindex_attribute', '', $button_order ); ?> aria-label="<?php echo esc_attr( $button_label ); ?>"><?php echo esc_attr( $button_label ); ?></button>
			<?php
		endif;
	}

	/**
	 * Close button extension
	 */
	public function gdpr_info_add_close_button_extensions() {
		$gdpr_default_content = new Moove_GDPR_Content();
		$option_name          = $gdpr_default_content->moove_gdpr_get_option_name();
		$modal_options        = get_option( $option_name );
		$buttons_order  		 = isset( $modal_options['gdpr_bs_buttons_order'] ) ? json_decode( $modal_options['gdpr_bs_buttons_order'], true ) : array('accept', 'reject', 'settings', 'close');
		if ( isset( $modal_options['moove_gdpr_close_button_enable'] ) && intval( $modal_options['moove_gdpr_close_button_enable'] ) === 1 ) :
			$button_order = in_array( 'close', $buttons_order ) ? array_search( 'close', $buttons_order ) : 'auto';
			?>
				<button class="moove-gdpr-infobar-close-btn gdpr-fbo-<?php echo esc_attr( $button_order ); ?>" aria-label="<?php esc_html_e( 'Close GDPR Cookie Banner', 'gdpr-cookie-compliance' ); ?>" <?php echo apply_filters('gdpr_tabindex_attribute', '', $button_order ); ?>>
					<span class="gdpr-sr-only"><?php esc_html_e( 'Close GDPR Cookie Banner', 'gdpr-cookie-compliance' ); ?></span>
					<i class="moovegdpr-arrow-close"></i>
				</button>
			<?php
		endif;
	}

	/**
	 * Close button extension content
	 */
	public function gdpr_info_add_close_button_content( $content ) {
		ob_start();
		$gdpr_default_content = new Moove_GDPR_Content();
		$option_name          = $gdpr_default_content->moove_gdpr_get_option_name();
		$modal_options        = get_option( $option_name );
		if ( isset( $modal_options['moove_gdpr_close_button_enable'] ) && intval( $modal_options['moove_gdpr_close_button_enable'] ) === 1 ) :
			?>
				<button class="moove-gdpr-infobar-close-btn gdpr-content-close-btn" aria-label="<?php esc_html_e( 'Close GDPR Cookie Banner', 'gdpr-cookie-compliance' ); ?>">
					<span class="gdpr-sr-only"><?php esc_html_e( 'Close GDPR Cookie Banner', 'gdpr-cookie-compliance' ); ?></span>
					<i class="moovegdpr-arrow-close"></i>
				</button>
			<?php
		endif;
		$content .= ob_get_clean();
		return $content;
	}

	/**
	 * CDN base URLs
	 *
	 * @param string $plugin_url Plugin URL.
	 */
	public function gdpr_cdn_base_url( $plugin_url ) {
		$gdpr_default_content = new Moove_GDPR_Content();
		$option_name          = $gdpr_default_content->moove_gdpr_get_option_name();
		$modal_options        = get_option( $option_name );
		if ( isset( $modal_options['moove_gdpr_cdn_url'] ) && $modal_options['moove_gdpr_cdn_url'] && intval( $modal_options['moove_gdpr_cdn_url'] ) !== 1 ) :
			$cdn_url    = esc_url_raw( $modal_options['moove_gdpr_cdn_url'] );
			$plugin_url = str_replace( trailingslashit( site_url() ), trailingslashit( $cdn_url ), $plugin_url );
		endif;

		return $plugin_url;

	}

	/**
	 * Lock screen of premium tabs, visible in the free version
	 */
	public function gdpr_premium_section_ads() {
		if ( class_exists( 'Moove_GDPR_Addon_View' ) ) :
			wp_verify_nonce( 'gdpr_nonce', 'gdpr_cookie_compliance_nonce' );
			$slug         		= isset( $_GET['tab'] ) ? sanitize_text_field( wp_unslash( $_GET['tab'] ) ) : false;
			$licence_manager 	= new Moove_GDPR_License_Manager();
			$add_on_slug 			= $licence_manager->get_add_on_plugin_slug();
			$view_path				= $add_on_slug ? WP_PLUGIN_DIR . '/' . plugin_dir_path( $add_on_slug ) . '/views/moove/admin/settings/' . $slug .'.php' : false;

			$view_content 		= $slug && $view_path ? file_exists( $view_path ) : false;

			if ( ! $view_content && $slug && 'help' !== $slug ) :
				?>
				<div class="gdpr-locked-section">
					<span>
						<i class="dashicons dashicons-lock"></i>
						<h4><?php esc_html_e( 'This feature is not supported in this version of the Premium Add-on.', 'gdpr-cookie-compliance' ); ?></h4>
						<p><strong><a href="<?php echo esc_url( admin_url( 'admin.php' ) ); ?>?page=moove-gdpr_licence" class="gdpr_admin_link"><?php esc_html_e( 'Activate your licence', 'gdpr-cookie-compliance' ); ?></a> <?php esc_html_e( 'to download the latest version of the Premium Add-on', 'gdpr-cookie-compliance' ); ?>.</strong></p>
						<p class="gdpr_license_info"><?php esc_html_e( 'Don’t have a valid licence key yet?', 'gdpr-cookie-compliance' ); ?> <br><a href="<?php echo esc_url( MOOVE_SHOP_URL ); ?>/my-account" target="_blank" class="gdpr_admin_link"><?php esc_html_e( 'Login to your account', 'gdpr-cookie-compliance' ); ?></a> <?php esc_html_e( 'to generate the key or', 'gdpr-cookie-compliance' ); ?> <a href="https://www.mooveagency.com/wordpress-plugins/gdpr-cookie-compliance/" class="gdpr_admin_link" target="_blank"><?php esc_html_e( 'buy a new licence here', 'gdpr-cookie-compliance' ); ?></a>.</p>
						<br />

						<a href="https://www.mooveagency.com/wordpress-plugins/gdpr-cookie-compliance/" target="_blank" class="plugin-buy-now-btn"><?php esc_html_e( 'Buy Now', 'gdpr-cookie-compliance' ); ?></a>
					</span>

				</div>
				<!--  .gdpr-locked-section -->
				<?php
			endif;
		else :
			?>
			<div class="gdpr-locked-section">
				<span>
					<i class="dashicons dashicons-lock"></i>
					<h4>This feature is part of the Premium Add-on</h4>
					<?php
					$gdpr_default_content = new Moove_GDPR_Content();
					$option_key           = $gdpr_default_content->moove_gdpr_get_key_name();
					$gdpr_key             = $gdpr_default_content->gdpr_get_activation_key( $option_key );
					?>
					<?php if ( $gdpr_key && isset( $gdpr_key['deactivation'] ) ) : ?>
						<p><strong><a href="<?php echo esc_url( admin_url( 'admin.php' ) ); ?>?page=moove-gdpr_licence" class="gdpr_admin_link"><?php esc_html_e( 'Activate your licence', 'gdpr-cookie-compliance' ); ?></a> <?php esc_html_e( 'or', 'gdpr-cookie-compliance' ); ?> <a href="https://www.mooveagency.com/wordpress-plugins/gdpr-cookie-compliance/" class="gdpr_admin_link" target="_blank"><?php esc_html_e( 'buy a new licence here', 'gdpr-cookie-compliance' ); ?></a></strong></p>
						<?php else : ?>
							<p><strong><?php esc_html_e( 'Do you have a licence key?', 'gdpr-cookie-compliance' ); ?> <br /><?php esc_html_e( 'Insert your license key to the', 'gdpr-cookie-compliance' ); ?> "<a href="<?php echo esc_url( admin_url( 'admin.php' ) ); ?>?page=moove-gdpr_licence" class="gdpr_admin_link"><?php esc_html_e( 'Licence Manager', 'gdpr-cookie-compliance' ); ?></a>" <?php esc_html_e( 'and activate it', 'gdpr-cookie-compliance' ); ?>.</strong></p>

						<?php endif; ?>
						<br />

						<a href="https://www.mooveagency.com/wordpress-plugins/gdpr-cookie-compliance/" target="_blank" class="plugin-buy-now-btn"><?php esc_html_e( 'Buy Now', 'gdpr-cookie-compliance' ); ?></a>
					</span>

				</div>
				<!--  .gdpr-locked-section -->
				<?php
			endif;
	}

	/**
	 * Checking for Premium Add-on installed and activated
	 *
	 * @param string $content Content.
	 * @param string $slug Slug.
	 */
	public function gdpr_check_extensions( $content, $slug ) {
		$return = $content;
		if ( class_exists( 'Moove_GDPR_Addon_View' ) ) :
			$licence_manager 	= new Moove_GDPR_License_Manager();
			$add_on_slug 			= $licence_manager->get_add_on_plugin_slug();
			$view_path				= $add_on_slug ? WP_PLUGIN_DIR . '/' . plugin_dir_path( $add_on_slug ) . '/views/moove/admin/settings/' . $slug .'.php' : false;
			$view_content 		= $slug && $view_path ? file_exists( $view_path ) : false;
			if ( ! $view_content ) :
				$return = $return;
			else :
				$return = '';
			endif;
		endif;
		return $return;
	}

	/**
	 * Clearing AJAX transient cache
	 */
	public function gdpr_remove_cached_scripts() {
		$gdpr_default_content 			= new Moove_GDPR_Content();
		$wp_lang 							= $gdpr_default_content->moove_gdpr_get_wpml_lang();
		$transient_key 					= 'gdpr_cookie_cache' . $wp_lang . MOOVE_GDPR_VERSION;
		delete_transient( $transient_key );
	}

	/**
	 * Register Front-end / Back-end scripts
	 *
	 * @return void
	 */
	public function moove_register_scripts() {
		if ( ! is_admin() ) :
			add_action( 'wp_enqueue_scripts', array( &$this, 'moove_frontend_gdpr_scripts' ), 999 );
		endif;
	}

	/**
	 * Register global variables to head, AJAX, Form validation messages
	 *
	 * @param  string $ascript The registered script handle you are attaching the data for.
	 * @return void
	 */
	public function moove_localize_script( $ascript ) {
		$gdpr_default_content = new Moove_GDPR_Content();
		$option_name          = $gdpr_default_content->moove_gdpr_get_option_name();
		$modal_options        = get_option( $option_name );
		$force_reload 			 = isset( $modal_options['gdpr_force_reload'] ) && intval( $modal_options['gdpr_force_reload'] ) >= 0 ? intval( $modal_options['gdpr_force_reload'] ) : apply_filters( 'gdpr_force_reload', false );
		$force_reload         = $force_reload ? 'true' : 'false';
		$wpml_lang            = $gdpr_default_content->moove_gdpr_get_wpml_lang();
	
		$geo_location_enabled = apply_filters( 'gdpr_cc_geolocation_status', 'false', $modal_options );

		$cookie_expiration 		= isset( $modal_options['moove_gdpr_consent_expiration'] ) && intval( $modal_options['moove_gdpr_consent_expiration'] ) >= 0 ? intval( $modal_options['moove_gdpr_consent_expiration'] ) : 365;

		$hide_save_btn 				= apply_filters( 'gdpr_keep_save_changes_button_visible', true );
		$hide_save_btn 				= $hide_save_btn ? 'false' : 'true';
		$close_button_v 				= true;

		$gdpr_gs_buttons_order = isset( $modal_options['gdpr_gs_buttons_order'] ) ? json_decode( $modal_options['gdpr_gs_buttons_order'], true ) : array( 'enable', 'reject', 'save', 'close' );
		
		if ( is_array( $gdpr_gs_buttons_order ) && in_array( 'close', $gdpr_gs_buttons_order ) && isset( $modal_options['moove_gdpr_cb_close_button_enable'] ) && 0 === intval( $modal_options['moove_gdpr_cb_close_button_enable'] ) ) :
			$close_button_v 		 = false;
		endif;

		$close_btn_action 	= isset( $modal_options['gdpr_close_button_bhv'] ) && intval( $modal_options['gdpr_close_button_bhv'] ) ? intval( $modal_options['gdpr_close_button_bhv'] ) : 1;
		$close_btn_redirect 	= $close_btn_action === 4 && isset( $modal_options['gdpr_close_button_bhv_redirect'] ) && sanitize_url( wp_unslash( $modal_options['gdpr_close_button_bhv_redirect'] ) ) ? sanitize_url( wp_unslash( $modal_options['gdpr_close_button_bhv_redirect'] ) ) : '';
		$close_btn_redirect = apply_filters( 'gdpr_close_btn_redirect', $close_btn_redirect, $modal_options );

		$initalization_delay 	= isset( $modal_options['gdpr_initialization_delay'] ) && intval( $modal_options['gdpr_initialization_delay'] ) >= 0 ? intval( $modal_options['gdpr_initialization_delay'] ) : apply_filters( 'gdpr_init_script_delay', 2000 );

		$cookie_removal_static = isset( $modal_options['gdpr_cookie_removal'] ) ? intval( $modal_options['gdpr_cookie_removal'] ) : ! apply_filters( 'gdpr_ajax_cookie_removal', false );
		$cookie_removal_static = $cookie_removal_static ? 'false' : 'true';

		$loc_data            = array(
			'ajaxurl'         		=> admin_url( 'admin-ajax.php' ),
			'post_id'         		=> get_the_ID(),
			'plugin_dir'      		=> apply_filters( 'gdpr_cdn_url', plugins_url( basename( dirname( __FILE__ ) ) ) ),
			'show_icons'      		=> apply_filters( 'gdpr_show_icons', 'all' ),
			'is_page'         		=> is_page(),
			'ajax_cookie_removal'	=> $cookie_removal_static,
			'strict_init'     		=> isset( $modal_options['moove_gdpr_strictly_necessary_cookies_functionality'] ) && intval( $modal_options['moove_gdpr_strictly_necessary_cookies_functionality'] ) ? intval( $modal_options['moove_gdpr_strictly_necessary_cookies_functionality'] ) : 1,
			'enabled_default' 		=> array(
				'third_party' => isset( $modal_options['moove_gdpr_third_party_cookies_enable_first_visit'] ) && intval( $modal_options['moove_gdpr_third_party_cookies_enable_first_visit'] ) ? intval( $modal_options['moove_gdpr_third_party_cookies_enable_first_visit'] ) : 0,
				'advanced'    => isset( $modal_options['moove_gdpr_advanced_cookies_enable_first_visit'] ) && intval( $modal_options['moove_gdpr_advanced_cookies_enable_first_visit'] ) ? intval( $modal_options['moove_gdpr_advanced_cookies_enable_first_visit'] ) : 0,
			),
			'geo_location'    	=> $geo_location_enabled,
			'force_reload'    	=> $force_reload,
			'is_single'       	=> is_single(),
			'hide_save_btn'		=> $hide_save_btn,
			'current_user'    	=> get_current_user_id(),
			'cookie_expiration' 	=> apply_filters( 'gdpr_cookie_expiration_days', $cookie_expiration ),
			'script_delay'			=> $initalization_delay,
			'close_btn_action'	=> $close_btn_action,
			'close_btn_rdr'		=> $close_btn_redirect
		);

		$ajax_script_handler 	= isset( $modal_options['script_insertion_method'] ) && intval( $modal_options['script_insertion_method'] ) >= 0 ? intval( $modal_options['script_insertion_method'] ) : apply_filters( 'gdpr_cc_prevent_ajax_script_inject', true );


		if ( $ajax_script_handler ) :
			$gdpr_controller = new Moove_GDPR_Controller();
			$loc_data['scripts_defined'] = $gdpr_controller->moove_gdpr_get_static_scripts();
		endif;


		$cookie_attributes 	= apply_filters( 'gdpr_cookie_custom_attributes', false );
		if ( $cookie_attributes ) :
			$loc_data['cookie_attributes'] = $cookie_attributes;
		endif;

		$store_cookie_on_reject = apply_filters('gdpr_cc_store_cookie_on_reject', true);
		$loc_data['gdpr_scor'] 	= $store_cookie_on_reject ? 'true' : 'false';
		$loc_data['wp_lang'] 	= $wpml_lang;

		$this->gdpr_loc_data = apply_filters( 'gdpr_extend_loc_data', $loc_data );
		wp_localize_script( $ascript, 'moove_frontend_gdpr_scripts', $this->gdpr_loc_data );


		$strict 					= 'false';
		$thirdparty 			= 'false';
		$advanced 				= 'false';
		$consent_cookies 	= array();
		if ( function_exists( 'gdpr_cookie_is_accepted' ) ) :
			if ( gdpr_cookie_is_accepted( 'strict' ) ) :
				$strict = 'true';
				$consent_cookies[] = 'strict';
			endif;

			if ( gdpr_cookie_is_accepted( 'thirdparty' ) ) :
				$thirdparty = 'true';
				$consent_cookies[] = 'thirdparty';
			endif;

			if ( gdpr_cookie_is_accepted( 'advanced' ) ) :
				$advanced = 'true';
				$consent_cookies[] = 'advanced';
			endif;

			wp_add_inline_script( $ascript, 'var gdpr_consent__strict = "'. $strict . '"' );
			wp_add_inline_script( $ascript, 'var gdpr_consent__thirdparty = "' . $thirdparty . '"');
			wp_add_inline_script( $ascript, 'var gdpr_consent__advanced = "' . $advanced . '"');
			wp_add_inline_script( $ascript, 'var gdpr_consent__cookies = "' . implode( '|', $consent_cookies ) . '"');
		endif;
	}

	/**
	 * Registe FRONT-END Javascripts and Styles
	 *
	 * @return void
	 */
	public function moove_frontend_gdpr_scripts() {
		$disable_main_assets = apply_filters( 'gdpr_disable_main_assets_enqueue', false );
		if ( ! $disable_main_assets ) :
			$gdpr_deps = apply_filters( 'gdpr_main_script_depends_on', array('jquery') );
			wp_enqueue_script( 'moove_gdpr_frontend', plugins_url( basename( dirname( __FILE__ ) ) ) . '/dist/scripts/main.js', $gdpr_deps, MOOVE_GDPR_VERSION, true );
		
			$gdpr_default_content = new Moove_GDPR_Content();
			$option_name          = $gdpr_default_content->moove_gdpr_get_option_name();
			$modal_options        = get_option( $option_name );
			$wpml_lang            = $gdpr_default_content->moove_gdpr_get_wpml_lang();
			$css_file             = 'gdpr-main.css';
			if ( isset( $modal_options['moove_gdpr_plugin_font_type'] ) ) :
				if ( '1' === $modal_options['moove_gdpr_plugin_font_type'] ) :
					$css_file = 'gdpr-main.css';
				elseif ( '2' === $modal_options['moove_gdpr_plugin_font_type'] ) :
					$css_file = 'gdpr-main-nf.css';
				else :
					$css_file = isset( $modal_options['moove_gdpr_plugin_font_family'] ) && $modal_options['moove_gdpr_plugin_font_family'] && false === strpos( strtolower( $modal_options['moove_gdpr_plugin_font_family'] ), 'nunito' ) ? 'gdpr-main-nf.css' : 'gdpr-main.css';
				endif;
			endif;
			wp_enqueue_style( 'moove_gdpr_frontend', plugins_url( basename( dirname( __FILE__ ) ) ) . '/dist/styles/' . $css_file, '', MOOVE_GDPR_VERSION );
			$this->moove_localize_script( 'moove_gdpr_frontend' );
		endif;

		wp_add_inline_style( 'moove_gdpr_frontend', gdpr_get_module( 'branding-styles' ) );
	}

	/**
	 * Registe BACK-END Javascripts and Styles
	 *
	 * @return void
	 */
	public static function moove_gdpr_admin_scripts() {
		wp_enqueue_script( 'moove_gdpr_backend', plugins_url( basename( dirname( __FILE__ ) ) ) . '/dist/scripts/admin.js', array( 'jquery', 'jquery-ui-core', 'jquery-ui-slider', 'jquery-ui-draggable' ), MOOVE_GDPR_VERSION, true );
		wp_enqueue_style( 'moove_gdpr_backend', plugins_url( basename( dirname( __FILE__ ) ) ) . '/dist/styles/admin.css', '', MOOVE_GDPR_VERSION );
	}

	/**
	 * Register AJAX actions for the plugin
	 */
	public function moove_register_ajax_actions() {
		add_action( 'wp_ajax_moove_gdpr_get_scripts', array( 'Moove_GDPR_Controller', 'moove_gdpr_get_scripts' ) );
		add_action( 'wp_ajax_nopriv_moove_gdpr_get_scripts', array( 'Moove_GDPR_Controller', 'moove_gdpr_get_scripts' ) );

		add_action( 'wp_ajax_moove_gdpr_localize_scripts', array( 'Moove_GDPR_Controller', 'moove_gdpr_localize_scripts' ) );
		add_action( 'wp_ajax_nopriv_moove_gdpr_localize_scripts', array( 'Moove_GDPR_Controller', 'moove_gdpr_localize_scripts' ) );

		add_action( 'wp_ajax_moove_gdpr_remove_php_cookies', array( 'Moove_GDPR_Controller', 'moove_gdpr_remove_php_cookies' ) );
		add_action( 'wp_ajax_nopriv_moove_gdpr_remove_php_cookies', array( 'Moove_GDPR_Controller', 'moove_gdpr_remove_php_cookies' ) );

		add_action( 'wp_ajax_moove_hide_language_notice', array( 'Moove_GDPR_Controller', 'moove_hide_language_notice' ) );

		add_action( 'wp_ajax_moove_hide_update_notice', array( 'Moove_GDPR_Updater', 'moove_hide_update_notice' ) );
	}

	/**
	 * GDPR Modal Footer Branding
	 */
	public function moove_gdpr_footer_branding_content() {
		$gdpr_default_content = new Moove_GDPR_Content();
		$option_name          = $gdpr_default_content->moove_gdpr_get_option_name();
		$modal_options        = get_option( $option_name );
		$wpml_lang            = $gdpr_default_content->moove_gdpr_get_wpml_lang();
		$powered_label        = ( isset( $modal_options[ 'moove_gdpr_modal_powered_by_label' . $wpml_lang ] ) && $modal_options[ 'moove_gdpr_modal_powered_by_label' . $wpml_lang ] ) ? $modal_options[ 'moove_gdpr_modal_powered_by_label' . $wpml_lang ] : 'Powered by';
		ob_start();
		?>
		<a href="https://wordpress.org/plugins/gdpr-cookie-compliance/" <?php echo apply_filters('gdpr_branding_link_attributes', 'rel="noopener noreferrer"'); ?> <?php echo apply_filters('gdpr_branding_link_target', 'target="_blank"'); ?> class='moove-gdpr-branding'><?php echo esc_attr( $powered_label ); ?>&nbsp; <span><?php esc_attr_e( 'GDPR Cookie Compliance', 'gdpr-cookie-compliance' ); ?></span></a>
		<?php
		return ob_get_clean();
	}

	/**
	 * GDPR Cookie Compliance - Admin Tabs - Routing & views
	 *
	 * @param string $active_tab Active tab.
	 */
	public function gdpr_settings_tab_nav_extensions( $active_tab ) {
		$tab_data = array(
			array(
				'name' => __( 'Export/Import Settings', 'gdpr-cookie-compliance' ),
				'slug' => 'export-import',
			),
			array(
				'name' => __( 'Multisite Settings', 'gdpr-cookie-compliance' ),
				'slug' => 'multisite-settings',
			),
			array(
				'name' => __( 'Accept on Scroll / Hide timer', 'gdpr-cookie-compliance' ),
				'slug' => 'accept-on-scroll',
			),
			array(
				'name' => __( 'Full-screen / Cookiewall', 'gdpr-cookie-compliance' ),
				'slug' => 'full-screen-mode',
			),
			array(
				'name' => __( 'Analytics', 'gdpr-cookie-compliance' ),
				'slug' => 'stats',
			),
			array(
				'name' => __( 'Geo Location', 'gdpr-cookie-compliance' ),
				'slug' => 'geo-location',
			),
			array(
				'name' => __( 'Hide Cookie Banner', 'gdpr-cookie-compliance' ),
				'slug' => 'cookie-banner-manager',
			),
			array(
				'name' => __( 'Iframe Blocker', 'gdpr-cookie-compliance' ),
				'slug' => 'iframe-blocker',
			),
			array(
				'name' => __( 'Cookie Declaration', 'gdpr-cookie-compliance' ),
				'slug' => 'cookie-declaration',
			),
			array(
				'name' => __( 'Consent Log', 'gdpr-cookie-compliance' ),
				'slug' => 'consent-log',
			),
			array(
				'name' => __( 'Renew Consent', 'gdpr-cookie-compliance' ),
				'slug' => 'renew-consent',
			)
		);

		foreach ( $tab_data as $tab ) :
			ob_start();
			?>
			<a href="<?php echo esc_url( admin_url( 'admin.php' ) ); ?>?page=moove-gdpr&amp;tab=<?php echo esc_attr( $tab['slug'] ); ?>" class="gdpr-cc-addon nav-tab <?php echo $active_tab === $tab['slug'] ? 'nav-tab-active' : ''; ?>">
				<?php echo esc_attr( $tab['name'] ); ?>
			</a>
			<?php
			$content = ob_get_clean();
			$content = apply_filters( 'gdpr_check_extensions', $content, $tab['slug'] );
			apply_filters( 'gdpr_cc_keephtml', $content, true );
		endforeach;
	}

}
new Moove_GDPR_Actions();

