<?php
/**
 * Iubenda AMP class.
 *
 * Any function related with AMP pages.
 *
 * @package  Iubenda
 */

// exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Iubenda_AMP class.
 *
 * @class Iubenda_AMP
 */
class Iubenda_AMP {

	/**
	 * The required banner configuration for AMP
	 *
	 * @var array
	 */
	private $required_banner_configuration = array(
		'position'               => 'float-center',
		'acceptButtonDisplay'    => true,
		'customizeButtonDisplay' => true,
		'rejectButtonDisplay'    => true,
		'backgroundOverlay'      => true,
		'applyStyles'            => true,
	);

	/**
	 * AMP shared style
	 *
	 * @var string
	 */
	private $amp_style = "
            .iubenda-tp-btn {
                position: fixed;
                z-index: 2147483647;
                background-image: url(\"data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='32' height='32' viewBox='0 0 32 32'%3E%3Cpath fill='%231CC691' fill-rule='evenodd' d='M16 7a4 4 0 0 1 2.627 7.016L19.5 25h-7l.873-10.984A4 4 0 0 1 16 7z'/%3E%3C/svg%3E\");
                background-repeat: no-repeat;
                background-size: 32px 32px;
                background-position: top .5px left 1px;
                width: 34px;
                border: none;
                cursor: pointer;
                margin: 16px;
                padding: 0;
                box-shadow: 0 0 0 1px rgba(0,0,0,.15);
                background-color: #fff;
                display: inline-block;
                height: 34px;
                min-width: 34px;
                border-radius: 4px;
                bottom: 0;
                right: 0;
            }
            .iubenda-tp-btn--top-left {top: 0;left: 0;}
            .iubenda-tp-btn--top-right {top: 0;right: 0;}
            .iubenda-tp-btn--bottom-left {bottom: 0;left: 0;}
            .iubenda-tp-btn--bottom-right {bottom: 0;right: 0;}
        ";

	/**
	 * Class constructor.
	 */
	public function __construct() {
		// actions.
		add_action( 'wp_head', array( $this, 'wp_head_amp' ), 100 );
		add_action( 'amp_post_template_head', array( $this, 'wp_head_amp' ), 100 );
		add_action( 'wp_footer', array( $this, 'wp_footer_amp' ), 100 );
		add_action( 'amp_post_template_footer', array( $this, 'wp_footer_amp' ), 100 );
		add_action( 'amp_post_template_css', array( $this, 'amp_post_template_css' ), 1 );

		// filters.
		add_filter( 'amp_post_template_data', array( $this, 'amp_post_template_data' ), 100 );
		add_filter( 'amp_analytics_entries', array( $this, 'fix_analytics_wp_amp' ), 10 );
	}

	/**
	 * Add scripts and CSS to WP AMP plugin in Transitional mode.
	 *
	 * @return void
	 */
	public function wp_head_amp() {
		if ( false === iubenda()->options['cs']['amp_support'] ) {
			return;
		}

		if ( ( function_exists( 'is_amp_endpoint' ) && is_amp_endpoint() ) || ( function_exists( 'ampforwp_is_amp_endpoint' ) && ampforwp_is_amp_endpoint() ) ) {
			echo '
				<script async custom-element="amp-consent" src="https://cdn.ampproject.org/v0/amp-consent-latest.js"></script>';// phpcs:ignore WordPress.WP.EnqueuedResources.NonEnqueuedScript

			// optional geo support.
			if ( iubenda()->multilang && ! empty( iubenda()->lang_current ) ) {
				$code = iub_array_get( iubenda()->options['cs'], 'code_' . iubenda()->lang_current );
			} else {
				$code = iub_array_get( iubenda()->options['cs'], 'code_default' );
			}

			$configuration_raw = iubenda()->parse_configuration( $code );

			if ( isset( $configuration_raw['gdprAppliesGlobally'] ) && ! $configuration_raw['gdprAppliesGlobally'] ) {
				echo '
				<script async custom-element="amp-geo" src="https://cdn.ampproject.org/v0/amp-geo-0.1.js"></script>';// phpcs:ignore WordPress.WP.EnqueuedResources.NonEnqueuedScript
			}

			echo '<meta name="amp-consent-blocking" content="amp-analytics,amp-ad">';

			// Integrate with amp-wp.org.
			if ( is_plugin_active( 'amp/amp.php' ) ) {
				echo "<style>{$this->amp_style}</style>"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}
		}
	}

	/**
	 * AMP post template css.
	 */
	public function amp_post_template_css() {
		if ( false === (bool) iubenda()->options['cs']['amp_support'] ) {
			return;
		}

		// CSS style.
		echo "{$this->amp_style}"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	/**
	 * Add AMP consent HTML to WP AMP plugin in Transitional mode.
	 *
	 * @return void
	 */
	public function wp_footer_amp() {
		if ( false === (bool) iubenda()->options['cs']['amp_support'] ) {
			return;
		}

		if ( ( function_exists( 'is_amp_endpoint' ) && is_amp_endpoint() ) || ( function_exists( 'ampforwp_is_amp_endpoint' ) && ampforwp_is_amp_endpoint() ) ) {

			// get code.
			if ( iubenda()->multilang && ! empty( iubenda()->lang_current ) ) {
				$code = iub_array_get( iubenda()->options['cs'], 'code_' . iubenda()->lang_current );
			} else {
				$code = iub_array_get( iubenda()->options['cs'], 'code_default' );
			}

			$configuration = iubenda()->parse_configuration( $code );

			if ( empty( $configuration ) ) {
				return;
			}

			// local file.
			if ( 'local' === (string) iubenda()->options['cs']['amp_source'] ) {
				// multi-language support.
				if ( iubenda()->multilang && ! empty( iubenda()->lang_current ) ) {
					$template_url = $this->get_amp_template_url( iubenda()->lang_current );
				} else {
					$template_url = $this->get_amp_template_url();
				}
				// remote file.
			} elseif ( iubenda()->multilang && ! empty( iubenda()->lang_current ) ) {
				$template_url = esc_url( isset( iubenda()->options['cs']['amp_template'][ iubenda()->lang_current ] ) ? iubenda()->options['cs']['amp_template'][ iubenda()->lang_current ] : '' );
			} else {
				$template_url = esc_url( iubenda()->options['cs']['amp_template'] );
			}

			if ( empty( $template_url ) ) {
				return;
			}

			echo '
			<amp-consent id="iubenda" layout="nodisplay" type="iubenda">
				<script type="application/json">
					{
						"promptUISrc": "' . esc_url( $template_url ) . '",
						"postPromptUI": "myConsentFlow"
					}
				</script>
			</amp-consent>
			<!-- This is the update preferences button, visible only when preferences are already expressed. -->
			<div id="myConsentFlow">
				<!-- You may change the position of the update preferences button. -->
				<!-- Use the class "iubenda-tp-btn--bottom-left" for bottom left position, other positions:
				"iubenda-tp-btn--bottom-right", "iubenda-tp-btn--top-left", "iubenda-tp-btn--top-right" -->
				<button class="iubenda-tp-btn iubenda-tp-btn--bottom-right" on="tap:iubenda.prompt()"></button>
			</div>
			';

		}
	}

	/**
	 * Add scripts to AMP for WP plugin and WP AMP plugin in Standard mode.
	 *
	 * @param array $data Template data.
	 *
	 * @return mixed
	 */
	public function amp_post_template_data( $data ) {
		if ( false === (bool) iubenda()->options['cs']['amp_support'] ) {
			return $data;
		}

		if ( function_exists( 'ampforwp_is_amp_endpoint' ) && ampforwp_is_amp_endpoint() ) {
			$data['amp_component_scripts'] = array_merge(
				$data['amp_component_scripts'],
				array( 'amp-consent' => 'https://cdn.ampproject.org/v0/amp-consent-latest.js' )
			);
		}

		return $data;
	}

	/**
	 * Block analytics in AMP for WP plugin.
	 *
	 * @param array $data Template data.
	 *
	 * @return mixed
	 */
	public function fix_analytics_amp_for_wp( $data ) {
		if ( false === (bool) iubenda()->options['cs']['amp_support'] ) {
			return $data;
		}

		if ( ! class_exists( 'iubendaParser' ) ) {
			return $data;
		}

		global $redux_builder_amp;

		if ( null === $redux_builder_amp ) {
			$redux_builder_amp = get_option( 'redux_builder_amp', true );
		}

		// trick to block the analytics using global $redux_builder_amp variable.
		if ( ! iubendaParser::consent_given() ) {
			$redux_builder_amp = true;
		}

		return $data;
	}

	/**
	 * Block analytics in WP AMP plugin.
	 *
	 * @param array $analytics_entries An associative array of the analytics entries we want to output. Each array entry must have a unique key, and the value should be an array with the following keys: `type`, `attributes`, `config_data`. See readme for more details.
	 *
	 * @return mixed
	 */
	public function fix_analytics_wp_amp( $analytics_entries ) {
		if ( false === (bool) iubenda()->options['cs']['amp_support'] ) {
			return $analytics_entries;
		}

		if ( ! class_exists( 'iubendaParser' ) ) {
			return $analytics_entries;
		}

		// block the analytics using the entries filter hook.
		if ( is_array( $analytics_entries ) && ! empty( $analytics_entries ) && ! iubendaParser::consent_given() ) {
			foreach ( $analytics_entries as $id => $entry ) {
				$entry['attributes'] = ! empty( $entry['attributes'] ) ? $entry['attributes'] : array();

				$analytics_entries[ $id ]['attributes'] = array_merge( array( 'data-block-on-consent' => '_till_accepted' ), $entry['attributes'] );
			}
		}

		return $analytics_entries;
	}

	/**
	 * Prepare HTML iframe template for the AMP.
	 *
	 * @param string $code embed code.
	 *
	 * @return mixed
	 */
	public function prepare_amp_template( $code ) {
		$html = '';

		$configuration_raw = iubenda()->parse_configuration( $code );

		if ( empty( $configuration_raw ) ) {
			$code              = stripslashes( $code );
			$configuration_raw = iubenda()->parse_configuration( $code );
		}

		$banner_configuration = iubenda()->parse_configuration(
			$code,
			array(
				'mode'  => 'banner',
				'parse' => false,
			)
		);
		$banner_configuration = wp_json_encode( array_merge( $banner_configuration, $this->required_banner_configuration ) );

		if ( ! empty( $configuration_raw ) ) {
			// get script.
			$script_src = ! empty( $configuration_raw['script'] ) ? $configuration_raw['script'] : '//cdn.iubenda.com/cs/iubenda_cs.js';

			// remove from configuration.
			if ( isset( $configuration_raw['script'] ) ) {
				unset( $configuration_raw['script'] );
			}

			// encode array.
			$configuration = wp_json_encode( $configuration_raw );

			// remove quotes.
			$configuration = preg_replace( '/"([a-zA-Z]+[a-zA-Z0-9]*)":/', '$1:', $configuration );
			// replace first and last bracket.
			$configuration = substr( $configuration, 1, - 1 );

			// Here we are generating amp page, for this reason no need to use enqueue.
			// phpcs:disable WordPress.WP.EnqueuedResources.NonEnqueuedScript
			$html .= '<!DOCTYPE html>
<html lang="' . esc_html( $configuration_raw['lang'] ) . '">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="robots" content="noindex">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>' . __( 'AMP Cookie Consent', 'iubenda' ) . '</title>
	<style>
		#iubenda-iframe.iubenda-iframe-visible {
	      background-color: transparent !important;
	      border-radius: 4px !important;
	    }

	    #iubenda-cs-banner .iubenda-cs-container .iubenda-cs-content {
	      border-radius: 4px !important;
	    }

	    .iubenda-cookie-solution #iubenda-cs-banner.iubenda-cs-default-floating.iubenda-cs-center:not(.iubenda-cs-top):not(.iubenda-cs-bottom) .iubenda-cs-container, #iubenda-cs-banner.iubenda-cs-default-floating:not(.iubenda-cs-bottom):not(.iubenda-cs-center) .iubenda-cs-container, #iubenda-cs-banner.iubenda-cs-default-floating:not(.iubenda-cs-top):not(.iubenda-cs-center) .iubenda-cs-container {
	      width: 100vw !important;
	      height: 100vh !important;
	    }

	    .iubenda-cookie-solution #iubenda-cs-banner.iubenda-cs-fix-height.iubenda-cs-default-floating .iubenda-cs-content {
	      height: 100% !important;
	    }

	    .iubenda-cookie-solution #iubenda-iframe-popup .iubenda-iframe-top-container.bottom-border-radius,
        .iubenda-cookie-solution #iubenda-iframe-popup #iab-container,
        .iubenda-cookie-solution #iubenda-iframe-popup #iub-cmp-widget,
        .iubenda-cookie-solution #iubenda-iframe-popup .iubenda-iframe-footer.iubenda-iframe-footer-absolute {
            border-radius: 0 0 4px 4px !important;
        }
	</style>
</head>
<body class="iubenda-cookie-solution">
<span class="iubenda-cs-preferences-link"></span>
	<script type="text/javascript">
	var _iub = _iub || [];
	_iub.csConfiguration = {
	  ';
			// print configuration.
			$html .= $configuration . ',';
			$html .= '
	  banner: ' . $banner_configuration . '
    };
	</script>
	<script async type="text/javascript" src="' . esc_url( $script_src ) . '"></script>
</body>
</html>';
			// phpcs:enable
		}

		return $html;
	}

	/**
	 * Get local file template url;
	 *
	 * @param string $template_lang Template language.
	 *
	 * @return string
	 */
	public function get_amp_template_url( string $template_lang = '' ) {
		$template_url  = '';
		$template_lang = ! empty( $template_lang ) && is_string( $template_lang ) ? $template_lang : '';

		// get basic site host and template file data.
		$file_url = ! empty( $template_lang ) ? IUBENDA_PLUGIN_URL . '/templates/amp-' . $template_lang . '.html' : IUBENDA_PLUGIN_URL . '/templates/amp.html';

		$parsed_site  = wp_parse_url( home_url() );
		$parsed_file  = wp_parse_url( $file_url );
		$site_host    = 'localhost' !== (string) $parsed_site['host'] ? iubenda()->domain( $parsed_site['host'] ) : 'localhost';
		$file_host    = 'localhost' !== (string) $parsed_file['host'] ? iubenda()->domain( $parsed_file['host'] ) : 'localhost';
		$is_localhost = (bool) ( 'localhost' === (string) $site_host );
		$is_subdomain = ! $is_localhost ? (bool) ( $parsed_file['host'] !== $file_host ) : false;

		// check if file host and server host match.
		// if not, we're good to go.
		if ( $site_host !== $file_host ) {
			return $file_url;
		}

		// all ok if we're on different subdomains.
		if ( $parsed_site['host'] !== $parsed_file['host'] ) {
			$template_url = $file_url;
		} else {
			// same hosts, let's tweak the http/https.
			$has_www = strpos( $parsed_file['host'], 'www.' ) === 0;

			// add or remove www from url string to make iframe url pass AMP validation.
			// 1 Check if not localhost and not subdomain or doesn't have www.
			if ( ! $is_localhost && ! $has_www ) {
				// 2 Append www if not exist.
				$tweaked_host = 'www.' . $parsed_file['host'];
			} elseif ( ! $is_localhost && $has_www ) {
				// 3 Remove www if exist.
				$tweaked_host = preg_replace( '/^www\./i', '', $parsed_file['host'] );
			} else {
				// 4 else Get the current host normally.
				$tweaked_host = $parsed_file['host'];
			}

			// generate new url.
			$tweaked_url = $parsed_file['scheme'] . '://' . $tweaked_host . ( isset( $parsed_file['port'] ) ? ':' . $parsed_file['port'] : '' ) . $parsed_file['path'] . ( ! empty( $parsed_file['query'] ) ? '?' . $parsed_file['query'] : '' );

			// check if file url is valid.
			if ( $tweaked_url ) {
				$template_url = $tweaked_url;
			}
		}

		return $template_url;
	}

	/**
	 * Generate HTML iframe template for the AMP.
	 *
	 * @param string $code embed code.
	 * @param string $lang language.
	 *
	 * @return mixed
	 */
	public function generate_amp_template( $code = '', $lang = '' ) {
		if ( empty( $code ) ) {
			return false;
		}

		global $wp_filesystem;
		if ( empty( $wp_filesystem ) ) {
			require_once ABSPATH . 'wp-admin/includes/file.php';
			WP_Filesystem();
		}

		$template_dir = IUBENDA_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR;
		if ( ! empty( $lang ) && array_key_exists( (string) $lang, iubenda()->languages ) ) {
			$template_file = $template_dir . ( 'amp-' . $lang . '.html' );
		} else {
			$template_file = $template_dir . ( 'amp.html' );
		}
		$html = $this->prepare_amp_template( $code );

		// bail if the template was not created properly.
		if ( empty( $html ) ) {
			return false;
		}

		if ( ! is_dir( $template_dir ) ) {
			// dir doesn't exist, make it.
			$wp_filesystem->mkdir( $template_dir );
		}

		if ( ! $wp_filesystem->is_writable( $template_dir ) ) {
			( new Quick_Generator_Service() )->add_amp_permission_error();
			return false;
		}

		// Use WP_Filesystem for file operations.
		$result = $wp_filesystem->put_contents( $template_file, $html );

		return (bool) $result;
	}
}
