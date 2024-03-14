<?php
/**
 * Moove_Controller File Doc Comment
 *
 * @category Moove_Controller
 * @package   gdpr-cookie-compliance
 * @author    Moove Agency
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

/**
 * Moove_Controller Class Doc Comment
 *
 * @category Class
 * @package  Moove_Controller
 * @author   Moove Agency
 */
class Moove_GDPR_Controller {
	/**
	 * Construct function
	 */
	public function __construct() {
	}

	/**
	 * Custom Editor CSS added to GDPR plugin WYSIWYG editors
	 *
	 * @return void
	 */
	public static function moove_gdpr_add_editor_styles() {
		add_editor_style( moove_gdpr_get_plugin_directory_url() . 'dist/styles/custom-editor-style.css' );
	}

	/**
	 * Checking if database exists
	 *
	 * @return bool
	 */
	public static function moove_gdpr_check_database() {
		$has_database = get_option( 'gdpr_cc_has_database' ) ? true : false;
		return $has_database;
	}

	/**
	 * JavaScript localization script
	 */
	public static function moove_gdpr_localize_scripts() {
		$content_cnt = new Moove_GDPR_Content();
		echo json_encode( $content_cnt->moove_gdpr_get_localize_scripts() );
		die();
	}

	/**
	 * Reading plugin statistics from WordPress.org
	 * - star rating
	 * - downloads & active installations
	 *
	 * @param string $plugin_slug Plugin slug.
	 */
	public function get_gdpr_plugin_details( $plugin_slug = '' ) {
		$plugin_return   = false;
		$wp_repo_plugins = '';
		$wp_response     = '';
		$wp_version      = get_bloginfo( 'version' );
		$transient       = get_transient( 'plugin_info_' . $plugin_slug );

		if ( $transient ) :
			$plugin_return = $transient;
		else :
			if ( $plugin_slug && $wp_version > 3.8 ) :
				$url 					= 'http://api.wordpress.org/plugins/info/1.2/';
				$args        	= array(
					'author' => 'MooveAgency',
					'fields' => array(
						'downloaded'      => true,
						'active_installs' => true,
						'ratings'         => true,
					),
				);

		    $url = add_query_arg(
		      array(
		        'action'  => 'query_plugins',
		        'request' => $args,
		      ),
		      $url
		    );

		    $http_url = $url;
		    $ssl      = wp_http_supports( array( 'ssl' ) );
		    if ( $ssl ) :
		      $url = set_url_scheme( $url, 'https' );
		    endif;

		    $http_args = array(
		      'timeout'    => 30,
		      'user-agent' => 'WordPress/' . $wp_version . '; ' . home_url( '/' ),
		    );
		    $request   = wp_remote_get( $url, $http_args );

		    if ( ! is_wp_error( $request ) ) :
		      $response = json_decode( wp_remote_retrieve_body( $request ), true );
		      if ( is_array( $response ) ) :
		      	$wp_repo_plugins = isset( $response['plugins'] ) && is_array( $response['plugins'] ) ? $response['plugins'] : array(); 
		        foreach ( $wp_repo_plugins as $plugin_details ) :
		        	$plugin_details = (object) $plugin_details;
		        	if ( isset( $plugin_details->slug ) && $plugin_slug === $plugin_details->slug ) :
								$plugin_return = $plugin_details;
								set_transient( 'plugin_info_' . $plugin_slug, $plugin_return, 12 * HOUR_IN_SECONDS );
							endif;
		        endforeach;
		      endif;
		    endif;
			endif;
		endif;
		return $plugin_return;
	}

	/**
	 * CSS minification for inlined CSS styles
	 *
	 * @param  string $input Inlined styles.
	 * @return string        Minified styles.
	 */
	public function moove_gdpr_minify_css( $input ) {
		if ( trim( $input ) === '' ) {
			return $input;
		}
		return preg_replace(
			array(
				// Remove comment(s).
				'#("(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\')|\/\*(?!\!)(?>.*?\*\/)|^\s*|\s*$#s',
				// Remove unused white-space(s).
				'#("(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\'|\/\*(?>.*?\*\/))|\s*+;\s*+(})\s*+|\s*+([*$~^|]?+=|[{};,>~+]|\s*+-(?![0-9\.])|!important\b)\s*+|([[(:])\s++|\s++([])])|\s++(:)\s*+(?!(?>[^{}"\']++|"(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\')*+{)|^\s++|\s++\z|(\s)\s+#si',
				// Replace `0(cm|em|ex|in|mm|pc|pt|px|vh|vw|%)` with `0`.
				'#(?<=[\s:])(0)(cm|em|ex|in|mm|pc|pt|px|vh|vw|%)#si',
				// Replace `:0 0 0 0` with `:0`.
				'#:(0\s+0|0\s+0\s+0\s+0)(?=[;\}]|\!important)#i',
				// Replace `background-position:0` with `background-position:0 0`.
				'#(background-position):0(?=[;\}])#si',
				// Replace `0.6` with `.6`, but only when preceded by `:`, `,`, `-` or a white-space.
				'#(?<=[\s:,\-])0+\.(\d+)#s',
				// Minify string value.
				'#(\/\*(?>.*?\*\/))|(?<!content\:)([\'"])([a-z_][a-z0-9\-_]*?)\2(?=[\s\{\}\];,])#si',
				'#(\/\*(?>.*?\*\/))|(\burl\()([\'"])([^\s]+?)\3(\))#si',
				// Minify HEX color code.
				'#(?<=[\s:,\-]\#)([a-f0-6]+)\1([a-f0-6]+)\2([a-f0-6]+)\3#i',
				// Replace `(border|outline):none` with `(border|outline):0`.
				'#(?<=[\{;])(border|outline):none(?=[;\}\!])#',
				// Remove empty selector(s).
				'#(\/\*(?>.*?\*\/))|(^|[\{\}])(?:[^\s\{\}]+)\{\}#s',
			),
			array(
				'$1',
				'$1$2$3$4$5$6$7',
				'$1',
				':0',
				'$1:0 0',
				'.$1',
				'$1$3',
				'$1$2$4$5',
				'$1$2$3',
				'$1:0',
				'$1$2',
			),
			$input
		);
	}

	/**
	 * Inline styles based on the colours selected in the options page
	 *
	 * @param string $primary_colour Primary Color.
	 * @param string $secondary_colour Secondary Color.
	 * @param string $button_bg Button Background Color.
	 * @param string $button_hover_bg Button Hover Background Color.
	 * @param string $button_font Button Font Color.
	 * @param string $font_family Font Family.
	 */
	public function get_minified_styles( $primary_colour, $secondary_colour, $button_bg, $button_hover_bg, $button_font, $font_family ) {
		ob_start();
		?>
		#moove_gdpr_cookie_modal,
		#moove_gdpr_cookie_info_bar,
		.gdpr_cookie_settings_shortcode_content {
			font-family: <?php echo $font_family; ?>;
		}
		#moove_gdpr_save_popup_settings_button {
			background-color: <?php echo esc_attr( $button_bg ); ?>;
			color: <?php echo esc_attr( $button_font ); ?>;
		}
		#moove_gdpr_save_popup_settings_button:hover {
			background-color: <?php echo esc_attr( $button_hover_bg ); ?>;
		}

		#moove_gdpr_cookie_info_bar .moove-gdpr-info-bar-container .moove-gdpr-info-bar-content a.mgbutton,
		#moove_gdpr_cookie_info_bar .moove-gdpr-info-bar-container .moove-gdpr-info-bar-content button.mgbutton {
			background-color: <?php echo esc_attr( $primary_colour ); ?>;
		}
		#moove_gdpr_cookie_modal .moove-gdpr-modal-content .moove-gdpr-modal-footer-content .moove-gdpr-button-holder a.mgbutton,
		#moove_gdpr_cookie_modal .moove-gdpr-modal-content .moove-gdpr-modal-footer-content .moove-gdpr-button-holder button.mgbutton,
		.gdpr_cookie_settings_shortcode_content .gdpr-shr-button.button-green {
			background-color: <?php echo esc_attr( $primary_colour ); ?>;
			border-color: <?php echo esc_attr( $primary_colour ); ?>;
		}

		#moove_gdpr_cookie_modal .moove-gdpr-modal-content .moove-gdpr-modal-footer-content .moove-gdpr-button-holder a.mgbutton:hover,
		#moove_gdpr_cookie_modal .moove-gdpr-modal-content .moove-gdpr-modal-footer-content .moove-gdpr-button-holder button.mgbutton:hover,
		.gdpr_cookie_settings_shortcode_content .gdpr-shr-button.button-green:hover {
			background-color: #fff;
			color: <?php echo esc_attr( $primary_colour ); ?>;
		}

		#moove_gdpr_cookie_modal .moove-gdpr-modal-content .moove-gdpr-modal-close i, 
		#moove_gdpr_cookie_modal .moove-gdpr-modal-content .moove-gdpr-modal-close span.gdpr-icon {
		background-color: <?php echo esc_attr( $primary_colour ); ?>;
		border: 1px solid <?php echo esc_attr( $primary_colour ); ?>;
		}

		#moove_gdpr_cookie_info_bar span.change-settings-button.focus-g,
		#moove_gdpr_cookie_info_bar span.change-settings-button:focus,
		#moove_gdpr_cookie_info_bar button.change-settings-button.focus-g,
		#moove_gdpr_cookie_info_bar button.change-settings-button:focus {
		-webkit-box-shadow: 0 0 1px 3px <?php echo esc_attr( $primary_colour ); ?>;
	  -moz-box-shadow:    0 0 1px 3px <?php echo esc_attr( $primary_colour ); ?>;
	  box-shadow:         0 0 1px 3px <?php echo esc_attr( $primary_colour ); ?>;
		}

		#moove_gdpr_cookie_modal .moove-gdpr-modal-content .moove-gdpr-modal-close i:hover, 
		#moove_gdpr_cookie_modal .moove-gdpr-modal-content .moove-gdpr-modal-close span.gdpr-icon:hover,
		#moove_gdpr_cookie_info_bar span[data-href] > u.change-settings-button {
			color: <?php echo esc_attr( $primary_colour ); ?>;
		}

		#moove_gdpr_cookie_modal .moove-gdpr-modal-content .moove-gdpr-modal-left-content #moove-gdpr-menu li.menu-item-selected a span.gdpr-icon, 
		#moove_gdpr_cookie_modal .moove-gdpr-modal-content .moove-gdpr-modal-left-content #moove-gdpr-menu li.menu-item-selected button span.gdpr-icon {
			color: inherit;
		}

		#moove_gdpr_cookie_modal .moove-gdpr-modal-content .moove-gdpr-modal-left-content #moove-gdpr-menu li a span.gdpr-icon, 
		#moove_gdpr_cookie_modal .moove-gdpr-modal-content .moove-gdpr-modal-left-content #moove-gdpr-menu li button span.gdpr-icon {
			color: inherit;
		}

		#moove_gdpr_cookie_modal .gdpr-acc-link {
			line-height: 0;
			font-size: 0;
			color: transparent;
			position: absolute;
		}

		#moove_gdpr_cookie_modal .moove-gdpr-modal-content .moove-gdpr-modal-close:hover i,
		#moove_gdpr_cookie_modal .moove-gdpr-modal-content .moove-gdpr-modal-left-content #moove-gdpr-menu li a,
		#moove_gdpr_cookie_modal .moove-gdpr-modal-content .moove-gdpr-modal-left-content #moove-gdpr-menu li button,
		#moove_gdpr_cookie_modal .moove-gdpr-modal-content .moove-gdpr-modal-left-content #moove-gdpr-menu li button i,
		#moove_gdpr_cookie_modal .moove-gdpr-modal-content .moove-gdpr-modal-left-content #moove-gdpr-menu li a i,
		#moove_gdpr_cookie_modal .moove-gdpr-modal-content .moove-gdpr-tab-main .moove-gdpr-tab-main-content a:hover,
		#moove_gdpr_cookie_info_bar.moove-gdpr-dark-scheme .moove-gdpr-info-bar-container .moove-gdpr-info-bar-content a.mgbutton:hover,
		#moove_gdpr_cookie_info_bar.moove-gdpr-dark-scheme .moove-gdpr-info-bar-container .moove-gdpr-info-bar-content button.mgbutton:hover,
		#moove_gdpr_cookie_info_bar.moove-gdpr-dark-scheme .moove-gdpr-info-bar-container .moove-gdpr-info-bar-content a:hover,
		#moove_gdpr_cookie_info_bar.moove-gdpr-dark-scheme .moove-gdpr-info-bar-container .moove-gdpr-info-bar-content button:hover,
		#moove_gdpr_cookie_info_bar.moove-gdpr-dark-scheme .moove-gdpr-info-bar-container .moove-gdpr-info-bar-content span.change-settings-button:hover,
		#moove_gdpr_cookie_info_bar.moove-gdpr-dark-scheme .moove-gdpr-info-bar-container .moove-gdpr-info-bar-content button.change-settings-button:hover,
		#moove_gdpr_cookie_info_bar.moove-gdpr-dark-scheme .moove-gdpr-info-bar-container .moove-gdpr-info-bar-content u.change-settings-button:hover,
		#moove_gdpr_cookie_info_bar span[data-href] > u.change-settings-button,
		#moove_gdpr_cookie_info_bar.moove-gdpr-dark-scheme .moove-gdpr-info-bar-container .moove-gdpr-info-bar-content a.mgbutton.focus-g,
		#moove_gdpr_cookie_info_bar.moove-gdpr-dark-scheme .moove-gdpr-info-bar-container .moove-gdpr-info-bar-content button.mgbutton.focus-g,
		#moove_gdpr_cookie_info_bar.moove-gdpr-dark-scheme .moove-gdpr-info-bar-container .moove-gdpr-info-bar-content a.focus-g,
		#moove_gdpr_cookie_info_bar.moove-gdpr-dark-scheme .moove-gdpr-info-bar-container .moove-gdpr-info-bar-content button.focus-g,
		#moove_gdpr_cookie_info_bar.moove-gdpr-dark-scheme .moove-gdpr-info-bar-container .moove-gdpr-info-bar-content a.mgbutton:focus,
		#moove_gdpr_cookie_info_bar.moove-gdpr-dark-scheme .moove-gdpr-info-bar-container .moove-gdpr-info-bar-content button.mgbutton:focus,
		#moove_gdpr_cookie_info_bar.moove-gdpr-dark-scheme .moove-gdpr-info-bar-container .moove-gdpr-info-bar-content a:focus,
		#moove_gdpr_cookie_info_bar.moove-gdpr-dark-scheme .moove-gdpr-info-bar-container .moove-gdpr-info-bar-content button:focus,
		#moove_gdpr_cookie_info_bar.moove-gdpr-dark-scheme .moove-gdpr-info-bar-container .moove-gdpr-info-bar-content span.change-settings-button.focus-g,
		span.change-settings-button:focus,
		button.change-settings-button.focus-g,
		button.change-settings-button:focus,
		#moove_gdpr_cookie_info_bar.moove-gdpr-dark-scheme .moove-gdpr-info-bar-container .moove-gdpr-info-bar-content u.change-settings-button.focus-g,
		#moove_gdpr_cookie_info_bar.moove-gdpr-dark-scheme .moove-gdpr-info-bar-container .moove-gdpr-info-bar-content u.change-settings-button:focus {
			color: <?php echo esc_attr( $primary_colour ); ?>;
		}

		#moove_gdpr_cookie_modal.gdpr_lightbox-hide {
			display: none;
		}

		<?php
		$input           = apply_filters( 'moove_gdpr_inline_styles', ob_get_clean(), $primary_colour, $secondary_colour, $button_bg, $button_hover_bg, $button_font );
		$gdpr_controller = new Moove_GDPR_Controller();
		return ! is_admin() || wp_doing_ajax() ? $gdpr_controller->moove_gdpr_minify_css( $input ) : '';
	}

	/**
	 * GDPR Modal Main content
	 *
	 * @return void
	 */
	public static function moove_gdpr_cookie_popup_modal() {
		if ( ! is_admin() ) :
			// FLOATING BUTTON.
			$content = gdpr_get_module( 'floating-button' );
			apply_filters( 'gdpr_cc_keephtml', $content, true );

			// MODAL CONTENT.
			$content = gdpr_get_module( 'modal-base' );
			apply_filters( 'gdpr_cc_keephtml', $content, true );
		endif;
	}

	/**
	 * GDPR Cookie info bar with settings icon
	 *
	 * @return void
	 */
	public static function moove_gdpr_cookie_popup_info() {
		if ( ! is_admin() ) :
			$content = gdpr_get_module( 'infobar-base' );
			apply_filters( 'gdpr_cc_keephtml', $content, true );
		endif;
	}

	public static function moove_gdpr_get_static_scripts() {
		$strict     = true;
		$thirdparty = true;
		$advanced   = true;
		$gdpr_default_content = new Moove_GDPR_Content();
		$wp_lang 							= $gdpr_default_content->moove_gdpr_get_wpml_lang();

		$transient_key = 'gdpr_cookie_cache' . $wp_lang . MOOVE_GDPR_VERSION;
		$transient     = apply_filters( 'gdpr_cookie_script_cache', get_transient( $transient_key ) );
		if ( ! empty( $transient ) ) :
			$transient_from_cache = json_decode( $transient, true );
		else :
			$option_name          = $gdpr_default_content->moove_gdpr_get_option_name();
			$modal_options        = get_option( $option_name );

			$cache_array = array(
				'thirdparty' => array(
					'header' => '',
					'body'   => '',
					'footer' => '',
				),
				'advanced'   => array(
					'header' => '',
					'body'   => '',
					'footer' => '',
				),
			);

			// THIRD PARTY - SCRIPT CACHE.
			ob_start();
			if ( isset( $modal_options['moove_gdpr_third_party_cookies_enable'] ) && intval( $modal_options['moove_gdpr_third_party_cookies_enable'] ) === 1 ) :
				$third_party_scripts = isset( $modal_options['moove_gdpr_third_party_header_scripts'] ) && $modal_options['moove_gdpr_third_party_header_scripts'] ? maybe_unserialize( $modal_options['moove_gdpr_third_party_header_scripts'] ) : '';
				$third_party_scripts = apply_filters( 'moove_gdpr_third_party_header_assets', $third_party_scripts );
				apply_filters( 'gdpr_cc_keephtml', $third_party_scripts, true );
			endif;
			$cache_array['thirdparty']['header'] .= ob_get_clean();

			ob_start();
			if ( isset( $modal_options['moove_gdpr_third_party_cookies_enable'] ) && intval( $modal_options['moove_gdpr_third_party_cookies_enable'] ) === 1 ) :
				$third_party_scripts = isset( $modal_options['moove_gdpr_third_party_body_scripts'] ) && $modal_options['moove_gdpr_third_party_body_scripts'] ? maybe_unserialize( $modal_options['moove_gdpr_third_party_body_scripts'] ) : '';
				$third_party_scripts = apply_filters( 'moove_gdpr_third_party_body_assets', $third_party_scripts );
				apply_filters( 'gdpr_cc_keephtml', $third_party_scripts, true );
			endif;
			$cache_array['thirdparty']['body'] .= ob_get_clean();

			ob_start();
			if ( isset( $modal_options['moove_gdpr_third_party_cookies_enable'] ) && intval( $modal_options['moove_gdpr_third_party_cookies_enable'] ) === 1 ) :
				$third_party_scripts = isset( $modal_options['moove_gdpr_third_party_footer_scripts'] ) && $modal_options['moove_gdpr_third_party_footer_scripts'] ? maybe_unserialize( $modal_options['moove_gdpr_third_party_footer_scripts'] ) : '';
				$third_party_scripts = apply_filters( 'moove_gdpr_third_party_footer_assets', $third_party_scripts );
				apply_filters( 'gdpr_cc_keephtml', $third_party_scripts, true );
			endif;
			$cache_array['thirdparty']['footer'] .= ob_get_clean();

			// ADVANCED - SCRIPT CACHE.
			ob_start();
			if ( isset( $modal_options['moove_gdpr_advanced_cookies_enable'] ) && intval( $modal_options['moove_gdpr_advanced_cookies_enable'] ) === 1 ) :
				$advanced_scripts = isset( $modal_options['moove_gdpr_advanced_cookies_header_scripts'] ) && $modal_options['moove_gdpr_advanced_cookies_header_scripts'] ? maybe_unserialize( $modal_options['moove_gdpr_advanced_cookies_header_scripts'] ) : '';
				$advanced_scripts = apply_filters( 'moove_gdpr_advanced_cookies_header_assets', $advanced_scripts );
				apply_filters( 'gdpr_cc_keephtml', $advanced_scripts, true );
			endif;
			$cache_array['advanced']['header'] .= ob_get_clean();

			ob_start();
			if ( isset( $modal_options['moove_gdpr_advanced_cookies_enable'] ) && intval( $modal_options['moove_gdpr_advanced_cookies_enable'] ) === 1 ) :
				$advanced_scripts = isset( $modal_options['moove_gdpr_advanced_cookies_body_scripts'] ) && $modal_options['moove_gdpr_advanced_cookies_body_scripts'] ? maybe_unserialize( $modal_options['moove_gdpr_advanced_cookies_body_scripts'] ) : '';
				$advanced_scripts = apply_filters( 'moove_gdpr_advanced_cookies_body_assets', $advanced_scripts );
				apply_filters( 'gdpr_cc_keephtml', $advanced_scripts, true );
			endif;
			$cache_array['advanced']['body'] .= ob_get_clean();

			ob_start();
			if ( isset( $modal_options['moove_gdpr_advanced_cookies_enable'] ) && intval( $modal_options['moove_gdpr_advanced_cookies_enable'] ) === 1 ) :
				$advanced_scripts = isset( $modal_options['moove_gdpr_advanced_cookies_footer_scripts'] ) && $modal_options['moove_gdpr_advanced_cookies_footer_scripts'] ? maybe_unserialize( $modal_options['moove_gdpr_advanced_cookies_footer_scripts'] ) : '';
				$advanced_scripts = apply_filters( 'moove_gdpr_advanced_cookies_footer_assets', $advanced_scripts );
				apply_filters( 'gdpr_cc_keephtml', $advanced_scripts, true );
			endif;
			$cache_array['advanced']['footer'] .= ob_get_clean();

			$cache_array = apply_filters( 'gdpr_cc_before_script_cache_set', $cache_array, $modal_options );

			$cache_json = json_encode( $cache_array, true );

			set_transient( $transient_key, $cache_json, 86400 );
			$transient_from_cache = $cache_array;
		endif;

		$scripts_array = array(
			'cache'  => ! empty( $transient ),
			'header' => '',
			'body'   => '',
			'footer' => '',
		);

		if ( true === $strict ) :
			$transient_from_cache = apply_filters( 'gdpr_lss_extension', $transient_from_cache, $wp_lang );
			if ( $thirdparty ) :
				if ( isset( $transient_from_cache['thirdparty'] ) ) :
					$scripts_array['thirdparty']['header'] = $transient_from_cache['thirdparty']['header'];
					$scripts_array['thirdparty']['body']   = $transient_from_cache['thirdparty']['body'];
					$scripts_array['thirdparty']['footer'] = $transient_from_cache['thirdparty']['footer'];
				endif;
			endif;

			if ( $advanced ) :
				if ( isset( $transient_from_cache['advanced'] ) ) :
					$scripts_array['advanced']['header'] = $transient_from_cache['advanced']['header'];
					$scripts_array['advanced']['body']   = $transient_from_cache['advanced']['body'];
					$scripts_array['advanced']['footer'] = $transient_from_cache['advanced']['footer'];
				endif;
			endif;
		endif;
		$scripts_json = apply_filters( 'gdpr_filter_scripts_before_insert', json_encode( $scripts_array ) );
		return str_replace( '<script', '<script data-gdpr', $scripts_json );
	}

	/**
	 * AJAX function to display the allowed scripts from the plugin settings page
	 *
	 * @return void
	 */
	public static function moove_gdpr_get_scripts() {
		wp_verify_nonce( 'gdpr_nonce', 'gdpr_cookie_compliance_nonce' );
		$strict     = isset( $_POST['strict'] ) && intval( $_POST['strict'] ) && 1 === intval( $_POST['strict'] ) ? true : false;
		$thirdparty = isset( $_POST['thirdparty'] ) && intval( $_POST['thirdparty'] ) && 1 === intval( $_POST['thirdparty'] ) ? true : false;
		$advanced   = isset( $_POST['advanced'] ) && intval( $_POST['advanced'] ) && 1 === intval( $_POST['advanced'] ) ? true : false;

		$wp_lang 		= isset( $_POST['wp_lang'] ) ? sanitize_text_field( wp_unslash( urlencode( $_POST['wp_lang'] ) ) ) : '';

		$transient_key = 'gdpr_cookie_cache' . $wp_lang . MOOVE_GDPR_VERSION;
		$transient     = apply_filters( 'gdpr_cookie_script_cache', get_transient( $transient_key ) );

		if ( ! empty( $transient ) ) :
			$transient_from_cache = json_decode( $transient, true );
		else :
			$gdpr_default_content = new Moove_GDPR_Content();
			$option_name          = $gdpr_default_content->moove_gdpr_get_option_name();
			$modal_options        = get_option( $option_name );

			$cache_array = array(
				'thirdparty' => array(
					'header' => '',
					'body'   => '',
					'footer' => '',
				),
				'advanced'   => array(
					'header' => '',
					'body'   => '',
					'footer' => '',
				),
			);

			// THIRD PARTY - SCRIPT CACHE.
			ob_start();
			if ( isset( $modal_options['moove_gdpr_third_party_cookies_enable'] ) && intval( $modal_options['moove_gdpr_third_party_cookies_enable'] ) === 1 ) :
				$third_party_scripts = isset( $modal_options['moove_gdpr_third_party_header_scripts'] ) && $modal_options['moove_gdpr_third_party_header_scripts'] ? maybe_unserialize( $modal_options['moove_gdpr_third_party_header_scripts'] ) : '';
				$third_party_scripts = apply_filters( 'moove_gdpr_third_party_header_assets', $third_party_scripts );
				apply_filters( 'gdpr_cc_keephtml', $third_party_scripts, true );
			endif;
			$cache_array['thirdparty']['header'] .= ob_get_clean();

			ob_start();
			if ( isset( $modal_options['moove_gdpr_third_party_cookies_enable'] ) && intval( $modal_options['moove_gdpr_third_party_cookies_enable'] ) === 1 ) :
				$third_party_scripts = isset( $modal_options['moove_gdpr_third_party_body_scripts'] ) && $modal_options['moove_gdpr_third_party_body_scripts'] ? maybe_unserialize( $modal_options['moove_gdpr_third_party_body_scripts'] ) : '';
				$third_party_scripts = apply_filters( 'moove_gdpr_third_party_body_assets', $third_party_scripts );
				apply_filters( 'gdpr_cc_keephtml', $third_party_scripts, true );
			endif;
			$cache_array['thirdparty']['body'] .= ob_get_clean();

			ob_start();
			if ( isset( $modal_options['moove_gdpr_third_party_cookies_enable'] ) && intval( $modal_options['moove_gdpr_third_party_cookies_enable'] ) === 1 ) :
				$third_party_scripts = isset( $modal_options['moove_gdpr_third_party_footer_scripts'] ) && $modal_options['moove_gdpr_third_party_footer_scripts'] ? maybe_unserialize( $modal_options['moove_gdpr_third_party_footer_scripts'] ) : '';
				$third_party_scripts = apply_filters( 'moove_gdpr_third_party_footer_assets', $third_party_scripts );
				apply_filters( 'gdpr_cc_keephtml', $third_party_scripts, true );
			endif;
			$cache_array['thirdparty']['footer'] .= ob_get_clean();

			// ADVANCED - SCRIPT CACHE.
			ob_start();
			if ( isset( $modal_options['moove_gdpr_advanced_cookies_enable'] ) && intval( $modal_options['moove_gdpr_advanced_cookies_enable'] ) === 1 ) :
				$advanced_scripts = isset( $modal_options['moove_gdpr_advanced_cookies_header_scripts'] ) && $modal_options['moove_gdpr_advanced_cookies_header_scripts'] ? maybe_unserialize( $modal_options['moove_gdpr_advanced_cookies_header_scripts'] ) : '';
				$advanced_scripts = apply_filters( 'moove_gdpr_advanced_cookies_header_assets', $advanced_scripts );
				apply_filters( 'gdpr_cc_keephtml', $advanced_scripts, true );
			endif;
			$cache_array['advanced']['header'] .= ob_get_clean();

			ob_start();
			if ( isset( $modal_options['moove_gdpr_advanced_cookies_enable'] ) && intval( $modal_options['moove_gdpr_advanced_cookies_enable'] ) === 1 ) :
				$advanced_scripts = isset( $modal_options['moove_gdpr_advanced_cookies_body_scripts'] ) && $modal_options['moove_gdpr_advanced_cookies_body_scripts'] ? maybe_unserialize( $modal_options['moove_gdpr_advanced_cookies_body_scripts'] ) : '';
				$advanced_scripts = apply_filters( 'moove_gdpr_advanced_cookies_body_assets', $advanced_scripts );
				apply_filters( 'gdpr_cc_keephtml', $advanced_scripts, true );
			endif;
			$cache_array['advanced']['body'] .= ob_get_clean();

			ob_start();
			if ( isset( $modal_options['moove_gdpr_advanced_cookies_enable'] ) && intval( $modal_options['moove_gdpr_advanced_cookies_enable'] ) === 1 ) :
				$advanced_scripts = isset( $modal_options['moove_gdpr_advanced_cookies_footer_scripts'] ) && $modal_options['moove_gdpr_advanced_cookies_footer_scripts'] ? maybe_unserialize( $modal_options['moove_gdpr_advanced_cookies_footer_scripts'] ) : '';
				$advanced_scripts = apply_filters( 'moove_gdpr_advanced_cookies_footer_assets', $advanced_scripts );
				apply_filters( 'gdpr_cc_keephtml', $advanced_scripts, true );
			endif;
			$cache_array['advanced']['footer'] .= ob_get_clean();

			$cache_array = apply_filters( 'gdpr_cc_before_script_cache_set', $cache_array, $modal_options );

			$cache_json = json_encode( $cache_array, true );

			set_transient( $transient_key, $cache_json, 86400 );
			$transient_from_cache = $cache_array;
		endif;

		$scripts_array = array(
			'cache'  => ! empty( $transient ),
			'header' => '',
			'body'   => '',
			'footer' => '',
		);

		if ( true === $strict ) :
			$transient_from_cache = apply_filters( 'gdpr_lss_extension', $transient_from_cache, $wp_lang );
			if ( $thirdparty ) :
				if ( isset( $transient_from_cache['thirdparty'] ) ) :
					$scripts_array['header'] .= $transient_from_cache['thirdparty']['header'];
					$scripts_array['body']   .= $transient_from_cache['thirdparty']['body'];
					$scripts_array['footer'] .= $transient_from_cache['thirdparty']['footer'];
				endif;
			endif;

			if ( $advanced ) :
				if ( isset( $transient_from_cache['advanced'] ) ) :
					$scripts_array['header'] .= $transient_from_cache['advanced']['header'];
					$scripts_array['body']   .= $transient_from_cache['advanced']['body'];
					$scripts_array['footer'] .= $transient_from_cache['advanced']['footer'];
				endif;
			endif;
		else :
			$d_domains = array('_ga', '_fbp', '_gid', '_gat', '__utma', '__utmb', '__utmc', '__utmt', '__utmz');
			$d_domains = apply_filters( 'gdpr_d_domains_filter', $d_domains );

			if ( isset( $_SERVER['HTTP_COOKIE'] ) ) {
				$cookies = explode( ';', sanitize_text_field( wp_unslash( $_SERVER['HTTP_COOKIE'] ) ) );

				$urlparts = wp_parse_url( site_url( '/' ) );
				$domain   = preg_replace( '/www\./i', '', $urlparts['host'] );
				$store_cookie_on_reject = apply_filters('gdpr_cc_store_cookie_on_reject', true);
				foreach ( $cookies as $cookie ) {					
					$parts = explode( '=', $cookie );
					$name  = trim( $parts[0] );					
					if ( false === $strict && $name === 'moove_gdpr_popup' && !$store_cookie_on_reject ) :
						setcookie( $name, '', time() - 1000 );
						setcookie( $name, '', time() - 1000, '/' );
					endif;
					if ( $name !== 'moove_gdpr_popup' && strpos( $name, 'woocommerce' ) === false  && strpos( $name, 'wc_' ) === false && strpos( $name, 'wordpress' ) === false ) :
						if ( 'language' === $name || 'currency' === $name ) {
							setcookie( $name, null, -1, '/', 'www.' . $domain );
						} elseif ( in_array( $name, $d_domains ) || strpos( $name, '_ga' ) !== false || strpos( $name, '_fbp' ) !== false ) {
							setcookie( $name, null, -1, '/', '.' . $domain );
						} else {
							setcookie( $name, '', time() - 1000 );
							setcookie( $name, '', time() - 1000, '/' );
						}
					endif;
				}
			}

			if ( isset( $_COOKIE ) && is_array( $_COOKIE ) ) :
				$urlparts = wp_parse_url( site_url( '/' ) );
				$domain   = preg_replace( '/www\./i', '', $urlparts['host'] );
				$store_cookie_on_reject = apply_filters('gdpr_cc_store_cookie_on_reject', true);
				foreach ( $_COOKIE as $key => $value ) {
					if ( false === $strict && $key === 'moove_gdpr_popup' && !$store_cookie_on_reject ) :
						setcookie( $key, null, -1, '/', 'www.' . $domain );
						setcookie( $key, null, -1, '/', '.' . $domain );
						$cookies_removed[$key] = $domain;
					endif;

					if ( $key !== 'moove_gdpr_popup' && strpos( $key, 'woocommerce' ) === false && strpos( $key, 'wc_' ) === false && strpos( $key, 'wordpress' ) === false ) : 
						if ( 'language' === $key || 'currency' === $key ) {
							setcookie( $key, null, -1, '/', 'www.' . $domain );
							$cookies_removed[$key] = $domain;
						} elseif ( in_array( $key, $d_domains ) || strpos( $key, '_ga' ) !== false || strpos( $key, '_fbp' ) !== false ) {
							setcookie( $key, null, -1, '/', '.' . $domain );
							$cookies_removed[$key] = $domain;
						}
					endif;
				}
			endif;
		endif;
		$scripts_json = apply_filters( 'gdpr_filter_scripts_before_insert', json_encode( $scripts_array ) );
		echo str_replace( '<script', '<script data-gdpr', $scripts_json );
		die();
	}

	/**
	 * Removing all the cookies including www and non-www domains
	 */
	public static function moove_gdpr_remove_php_cookies() {
		$urlparts = wp_parse_url( site_url( '/' ) );
		$domain   = preg_replace( '/www\./i', '', $urlparts['host'] );
		$cookies_removed = array();
		$d_domains = array('_ga', '_fbp', '_gid', '_gat', '__utma', '__utmb', '__utmc', '__utmt', '__utmz');
		$d_domains = apply_filters( 'gdpr_d_domains_filter', $d_domains );
		if ( isset( $_COOKIE ) && is_array( $_COOKIE ) && $domain ) :
			foreach ( $_COOKIE as $key => $value ) {
				if ( $key !== 'moove_gdpr_popup' && strpos( $key, 'woocommerce' ) === false && strpos( $key, 'wc_' ) === false && strpos( $key, 'wordpress' ) === false ) : 
					if ( 'language' === $key || 'currency' === $key ) {
						setcookie( $key, null, -1, '/', 'www.' . $domain );
						$cookies_removed[$key] = $domain;
					} elseif ( in_array( $key, $d_domains ) || strpos( $key, '_ga' ) !== false || strpos( $key, '_fbp' ) !== false ) {
						setcookie( $key, null, -1, '/', '.' . $domain );
						$cookies_removed[$key] = $domain;
					}
				endif;
			}
		endif;

		$cookies = isset( $_SERVER['HTTP_COOKIE'] ) ? explode( ';', sanitize_text_field( wp_unslash( $_SERVER['HTTP_COOKIE'] ) ) ) : false;
		if ( is_array( $cookies ) ) :
			foreach ( $cookies as $cookie ) {
				$parts = explode( '=', $cookie );
				$name  = trim( $parts[0] );
				if ( $name && $name !== 'moove_gdpr_popup' && strpos( $name, 'woocommerce' ) === false && strpos( $name, 'wc_' ) === false && strpos( $name, 'wordpress' ) === false ) :
					setcookie( $name, '', time() - 1000 );
					setcookie( $name, '', time() - 1000, '/' );
					if ( 'language' === $name || 'currency' === $name ) {
						setcookie( $name, null, -1, '/', 'www.' . $domain );
						$cookies_removed[$name] = $domain;
					} elseif ( in_array( $key, $d_domains ) || strpos( $name, '_ga' ) !== false || strpos( $name, '_fbp' ) !== false ) {
						setcookie( $name, null, -1, '/', '.' . $domain );
						$cookies_removed[$name] = '.' . $domain;
					} else {
						setcookie( $name, null, -1, '/' );
						$cookies_removed[$name] = $domain;
					}
				endif;
			}
		endif;
		echo json_encode( $cookies_removed );
	}

	/**
	 * Language notice hide
	 */
	public static function moove_hide_language_notice() {
		$nonce = isset( $_POST['nonce'] ) ? sanitize_key( wp_unslash( $_POST['nonce'] ) ) : false;
		if ( wp_verify_nonce( $nonce, 'gdpr_hide_language_nonce' ) ) :
			wp_verify_nonce( 'gdpr_nonce', 'gdpr_cookie_compliance_nonce' );
			$user_id = isset( $_POST['user_id'] ) ? intval( $_POST['user_id'] ) : false;
			if ( $user_id ) :
				$gdpr_default_content = new Moove_GDPR_Content();
				$option_name          = $gdpr_default_content->moove_gdpr_get_option_name();
				$modal_options        = get_option( $option_name );
				$modal_options[ 'gdpr_hide_language_notice_' . $user_id ] = 1;
				update_option( $option_name, $modal_options );
			endif;
		endif;
	}

}
new Moove_GDPR_Controller();
