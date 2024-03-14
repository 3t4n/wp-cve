<?php
/**
 * Builder for etracker Tracklet.
 *
 * @link       https://etracker.com
 * @since      1.0.0
 *
 * @package    Etracker
 */

namespace Etracker\Frontend\Generator;

/**
 * Builder for etracker Tracklet.
 *
 * This class defines all code necessary to generate etracker tracklet.
 *
 * @since      1.0.0
 *
 * @package    Etracker
 *
 * @author     etracker GmbH <support@etracker.com>
 */
class TrackletGenerator {
	/**
	 * The unique identifier from etracker account.
	 *
	 * @since    1.0.0
	 *
	 * @access   protected
	 *
	 * @var string $secure_code    The etracker secure code.
	 */
	protected $secure_code;

	/**
	 * Flag to respect DNT.
	 *
	 * @since    1.0.0
	 * @deprecated 2.1.0
	 *
	 * @access   protected
	 *
	 * @var bool $respect_dnt    Tracklet respect_dnt flag.
	 */
	protected $respect_dnt;

	/**
	 * Flag block cookies.
	 *
	 * By default, etracker Analytics does not require to set cookies. Set
	 * $block_cookies to false to enable tracking cookies. Default: false.
	 *
	 * @since    1.0.0
	 *
	 * @access   protected
	 *
	 * @var bool $block_cookies    Disables tracking cookies.
	 */
	protected $block_cookies;

	/**
	 * Source URL to get static etracker code.
	 *
	 * @since    1.0.0
	 *
	 * @access   protected
	 *
	 * @var string $src_e_js    URL for etrackers static code `e.js`.
	 */
	protected $src_e_js;

	/**
	 * The et_pagename etracker variable. It will not be set if empty.
	 *
	 * @since    1.0.0
	 *
	 * @access   protected
	 *
	 * @var string $et_pagename    The etracker pagename to send.
	 */
	protected $et_pagename;

	/**
	 * Option disable_et_pagename.
	 *
	 * By default, etracker Analytics detects the pagename and sets tracking
	 * parameter $et_pagename accordingly. If you like to use automatic page name
	 * detection within etracker Analytics, you have to disable the submit of
	 * $et_pagename parameter by enabling this option. Default: false.
	 *
	 * @since    1.3.0
	 *
	 * @access   protected
	 *
	 * @var bool $disable_et_pagename    Disables pagename detection.
	 */
	protected $disable_et_pagename;

	/**
	 * Additional tracklet data-attributes
	 *
	 * @since    1.7.0
	 *
	 * @access   protected
	 *
	 * @var string $custom_attributes    Additional custom attributes.
	 */
	protected $custom_attributes;

	/**
	 * Custom tracking domain.
	 *
	 * @since    2.3.0
	 *
	 * @access   protected
	 *
	 * @var string $custom_tracking_domain Custom tracking domain.
	 */
	protected $custom_tracking_domain;

	/**
	 * The WordPress plugin name.
	 *
	 * WordPress plugin name will be set as part of tracklet attribute
	 * data-plugin-version.
	 *
	 * @since    1.3.0
	 *
	 * @access   private
	 *
	 * @var string $plugin_name    WordPress plugin name.
	 */
	private $plugin_name;

	/**
	 * The WordPress plugin version.
	 *
	 * WordPress plugin version will be set as part of tracklet attribute
	 * data-plugin-version.
	 *
	 * @since    1.3.0
	 *
	 * @access   private
	 *
	 * @var string $plugin_version    WordPress plugin version.
	 */
	private $plugin_version;

	/**
	 * Option disable_data_plugin_version.
	 *
	 * By default, etracker Analytics sets tracklet attribute data-plugin-version.
	 * This will be disabled if this option is set to true. Default: false.
	 *
	 * @since    1.3.0
	 *
	 * @access   protected
	 *
	 * @var bool $disable_data_plugin_version    Disables data-plugin-version.
	 */
	protected $disable_data_plugin_version;

	/**
	 * Constructor for tracklet.
	 *
	 * Sets default values for minimal required settings.
	 *
	 * @since    1.0.0
	 *
	 * @param string $plugin_name    The name of the plugin.
	 * @param string $plugin_version The version of this plugin.
	 */
	public function __construct( string $plugin_name, string $plugin_version ) {
		// Initialize properties with default values.
		$this->src_e_js                    = '//code.etracker.com/code/e.js';
		$this->custom_tracking_domain      = null;
		$this->respect_dnt                 = null;
		$this->block_cookies               = true;
		$this->et_pagename                 = '';
		$this->disable_et_pagename         = false;
		$this->disable_data_plugin_version = false;
		$this->plugin_name                 = $plugin_name;
		$this->plugin_version              = $plugin_version;
	}

	/**
	 * Determines if Yoast SEO plugin is installed.
	 *
	 * @return boolean True if Plugin is installed or False.
	 *
	 * @access private
	 */
	private function is_yoast_seo_installed() {
		if ( class_exists( 'WPSEO_Utils' ) ) {
			return true;
		}
		return false;
	}

	/**
	 * WordPress filter wpseo_title to query page title from Yoast plugin.
	 *
	 * @param string $title WordPress page title.
	 *
	 * @return string WordPress page title.
	 */
	public function wpseo_title( $title ) {
		if ( ! $this->is_yoast_seo_installed() ) {
			// no Yoast SEO plugin installed. Just return input value to next filter.
			return $title;
		}
		// Query title separator from Yoast SEO.
		if ( function_exists( 'YoastSEO' ) && method_exists( '\Yoast\WP\SEO\Helpers\Options_Helper', 'get_title_separator' ) ) {
			$wpseo_title_sep = YoastSEO()->helpers->options->get_title_separator();
		} else {
			// Fallback to old deprecated method call.
			$wpseo_title_sep = WPSEO_Utils::get_title_separator();
		}

		// Split title at separator.
		$title_parts = explode( $wpseo_title_sep, $title );
		// Set et_pagename to first part of title.
		$this->set_et_pagename( $title_parts[0] );
		return $title;
	}

	/**
	 * WordPress filter document_title_parts to query page title.
	 *
	 * @param array $title {
	 *                     The document title parts.
	 *
	 * @type string $title   Title of the viewed page.
	 * @type string $page    Optional. Page number if paginated.
	 * @type string $tagline Optional. Site description when on home page.
	 * @type string $site    Optional. Site title when not on home page.
	 *              }
	 *
	 * @return array WordPress title parts.
	 *
	 * @since 1.0.0
	 */
	public function document_title_parts( $title ) {
		// Handle NULL title (issue #6).
		if ( is_string( $title['title'] ) ) {
			$this->set_et_pagename( $title['title'] );
		}
		// Function must return same value as input to keep page unchanged.
		return $title;
	}

	/**
	 * Get the value of The unique identifier from etracker account.
	 *
	 * @return string $secure_code    The etracker secure code.
	 */
	public function get_secure_code() {
		return trim( $this->secure_code );
	}

	/**
	 * Set the value of The unique identifier from etracker account.
	 *
	 * @param string $secure_code The etracker secure code.
	 *
	 * @return self
	 */
	public function set_secure_code( string $secure_code ) {
		$this->secure_code = $secure_code;

		return $this;
	}

	/**
	 * Get the value of Flag to respect DNT.
	 *
	 * @deprecated 2.1.0
	 *
	 * @return bool $respect_dnt    Tracklet respect_dnt flag.
	 */
	public function get_respect_dnt() {
		return $this->respect_dnt;
	}

	/**
	 * Set the value of Flag to respect DNT.
	 *
	 * @deprecated 2.1.0
	 *
	 * @param bool $respect_dnt Tracklet respect_dnt flag.
	 *
	 * @return self
	 */
	public function set_respect_dnt( bool $respect_dnt ) {
		$this->respect_dnt = $respect_dnt;

		return $this;
	}

	/**
	 * Get the value of Source URL to get static etracker code.
	 *
	 * @return string $src_e_js    URL for etrackers static code `e.js`.
	 */
	public function get_src_e_js() {
		return $this->src_e_js;
	}

	/**
	 * Set the value of Source URL to get static etracker code.
	 *
	 * @param string $src_e_js URL for etrackers static code `e.js`.
	 *
	 * @return self
	 */
	public function set_src_e_js( string $src_e_js ) {
		$this->src_e_js = $src_e_js;

		return $this;
	}

	/**
	 * Get the custom tracking domain used for proxy tracking.
	 *
	 * @return string $custom_tracking_domain    The custom tracking domain.
	 */
	public function get_custom_tracking_domain() {
		$domain = $this->custom_tracking_domain;
		if ( $domain && strpos( $domain, '//' ) !== false ) {
			return $domain;
		}
		return $domain ? '//' . $domain : null;
	}

	/**
	 * Set the value of the custom tracking domain.
	 *
	 * @param string $custom_tracking_domain The custom tracking domain.
	 *
	 * @return self
	 */
	public function set_custom_tracking_domain( string $custom_tracking_domain ) {
		$this->custom_tracking_domain = $custom_tracking_domain;

		return $this;
	}

	/**
	 * Get the custom attributes.
	 *
	 * @return string $custom_code    The custom code.
	 */
	public function get_custom_attributes() {
		return $this->custom_attributes;
	}

	/**
	 * Set the value of the custom attributes.
	 *
	 * @param string $custom_attributes The custom attributes.
	 *
	 * @return self
	 */
	public function set_custom_attributes( string $custom_attributes ) {
		$this->custom_attributes = $custom_attributes;

		return $this;
	}

	/**
	 * Generate HTML tracklet.
	 *
	 * @return string
	 */
	public function generate() {
		// build and return _etrackerOnReady.
		$dom = new \DOMDocument( '1.0', 'utf-8' );

		$dom_async_queue = $dom->createElement( 'script' );
		$js_async_queue  = new \DOMText( 'var _etrackerOnReady = [];' );
		$dom_async_queue->appendChild( $js_async_queue );
		$dom->appendChild( $dom_async_queue );

		// return empty tracklet if no SecureCode was set.
		if ( strlen( $this->get_secure_code() ) < 1 ) {
			return $dom->saveHTML();
		}

		$custom_tracking_domain = $this->get_custom_tracking_domain();
		if ( ! empty( $custom_tracking_domain ) ) {
			$dom_proxy_tracking_script = $dom->createElement( 'script' );
			$dom_proxy_tracking        = new \DOMText( 'var et_proxy_redirect = \'' . $custom_tracking_domain . '\';' );
			$dom_proxy_tracking_script->appendChild( $dom_proxy_tracking );
			$dom->appendChild( $dom_proxy_tracking_script );
		}

		$dom_tracklet = $dom->createElement( 'script' );
		$dom_tracklet->setAttribute( 'type', 'text/javascript' );
		$dom_tracklet->setAttribute( 'id', '_etLoader' );
		$dom_tracklet->setIdAttribute( 'id', true );
		$dom_tracklet->setAttribute( 'charset', 'UTF-8' );
		$dom_tracklet->setAttribute(
			'data-secure-code',
			esc_attr( $this->get_secure_code() )
		);
		$dom_tracklet->setAttribute(
			'data-block-cookies',
			( $this->get_block_cookies() ? 'true' : 'false' )
		);

		$src_e_js = $this->get_src_e_js();
		if ( ! empty( $custom_tracking_domain ) ) {
			$src_e_js = $custom_tracking_domain . '/code/e.js';
		}

		$dom_tracklet->setAttribute(
			'src',
			esc_attr( $src_e_js )
		);

		$custom_attributes = $this->get_custom_attributes();
		if ( ! empty( $custom_attributes ) && strlen( $custom_attributes ) < 100 ) {
			$split = explode( ';', $custom_attributes );
			foreach ( $split as $entry ) {
				$entry = trim( $entry );
				if ( strpos( $entry, 'data-' ) === 0 ) {
					$split    = explode( '=', $entry );
					$esc_name = esc_attr( trim( $split[0] ) );
					if ( 1 === count( $split ) ) {
						$dom_tracklet->setAttributeNode( new \DOMAttr( $esc_name ) );
					} elseif ( 2 === count( $split ) ) {
						// any combination of ' and ".
						$value = trim( trim( $split[1] ), '\'"' );
						$dom_tracklet->setAttribute(
							$esc_name,
							esc_attr( $value )
						);
					}
				}
			}
		}

		// Set data-plugin-version only if value is not empty.
		// This allowes us to disable data-plugin-version attribute later.
		if ( ! empty( $this->get_data_plugin_version() ) ) {
			$dom_tracklet->setAttribute(
				'data-plugin-version',
				esc_attr( $this->get_data_plugin_version() )
			);
		}
		// add attributes without values as last attributes.
		$dom_tracklet->setAttributeNode( new \DOMAttr( 'async' ) );
		$dom->appendChild( $dom_tracklet );

		if ( $this->are_optional_parameters_set() ) {
			$dom_optional = $dom->createElement( 'script' );
			$js_optional  = new \DOMText( $this->get_optional_parameters() );
			$dom_optional->appendChild( $js_optional );
			$dom->appendChild( $dom_optional );
		}

		return $dom->saveHTML();
	}

	/**
	 * Get the value of Flag block cookies.
	 *
	 * @return bool $block_cookies    Disables tracking cookies.
	 */
	public function get_block_cookies() {
		return $this->block_cookies;
	}

	/**
	 * Set the value of Flag block cookies.
	 *
	 * @param bool $block_cookies Disables tracking cookies.
	 *
	 * @return self
	 */
	public function set_block_cookies( bool $block_cookies ) {
		$this->block_cookies = $block_cookies;

		return $this;
	}

	/**
	 * Get the value of The et_pagename etracker variable. It will not be set if empty.
	 *
	 * @return string $et_pagename    The etracker pagename to send.
	 */
	public function get_et_pagename() {
		// option disable_et_pagename enforces et_pagename parameter to be unset.
		if ( true === $this->get_disable_et_pagename() ) {
			return '';
		}
		// return settings value.
		return $this->et_pagename;
	}

	/**
	 * Set the value of The et_pagename etracker variable. It will not be set if empty.
	 *
	 * @param string $et_pagename The etracker pagename to send.
	 *
	 * @return self
	 */
	public function set_et_pagename( string $et_pagename ) {
		$this->et_pagename = trim( $et_pagename );

		return $this;
	}

	/**
	 * Get the value of Option disable_et_pagename.
	 *
	 * @since   1.3.0
	 *
	 * @return bool $disable_et_pagename    Disables pagename detection.
	 */
	public function get_disable_et_pagename() {
		return $this->disable_et_pagename;
	}

	/**
	 * Set the value of Option disable_et_pagename.
	 *
	 * @since   1.3.0
	 *
	 * @param bool $disable_et_pagename Disables pagename detection.
	 *
	 * @return self
	 */
	public function set_disable_et_pagename( bool $disable_et_pagename ) {
		$this->disable_et_pagename = $disable_et_pagename;

		return $this;
	}

	/**
	 * Get the value of The WordPress plugin name.
	 *
	 * @since   1.3.0
	 *
	 * @return string $plugin_name    WordPress plugin name.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * Get the value of The WordPress plugin version.
	 *
	 * @since   1.3.0
	 *
	 * @return string $plugin_version    WordPress plugin version.
	 */
	public function get_plugin_version() {
		return $this->plugin_version;
	}

	/**
	 * Get the value of Option disable_data_plugin_version.
	 *
	 * @since   1.3.0
	 *
	 * @return bool $disable_data_plugin_version    Disables data-plugin-version.
	 */
	public function get_disable_data_plugin_version() {
		return $this->disable_data_plugin_version;
	}

	/**
	 * Set the value of Option disable_data_plugin_version.
	 *
	 * @since   1.3.0
	 *
	 * @param bool $disable_data_plugin_version Disables data-plugin-version.
	 *
	 * @return self
	 */
	public function set_disable_data_plugin_version( bool $disable_data_plugin_version ) {
		$this->disable_data_plugin_version = $disable_data_plugin_version;

		return $this;
	}

	/**
	 * Get data-plugin-version.
	 *
	 * Returns composed string based of plugin name, plugin version and
	 * WordPress identifier.
	 *
	 * @since   1.3.0
	 *
	 * @return string
	 */
	public function get_data_plugin_version() {
		// option disable_data_plugin_version enforces data-plugin-version to be unset.
		if ( true === $this->get_disable_data_plugin_version() ) {
			return '';
		}

		return sprintf(
			'WP:%s:%s',
			$this->get_plugin_name(),
			$this->get_plugin_version()
		);
	}

	/**
	 * Returns true if there is at lease one optional tracking parameter set.
	 *
	 * @return boolean
	 */
	private function are_optional_parameters_set() {
		if ( ! empty( $this->get_et_pagename() ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Combines all optional tracking parameters together.
	 *
	 * @return string JavaScript etracker plugin section.
	 */
	private function get_optional_parameters() {
		$format_js_string = "%s = '%s';";
		// Use IIFE to protect function name to collide.
		$format_js_html_decoded_string = '%s = (function(html){' .
			'var txt = document.createElement("textarea");' .
			'txt.innerHTML = html;' .
			'return txt.value;' .
			"}('%s'))";

		$js_optional = array();

		if ( ! empty( $this->get_et_pagename() ) ) {
			$js_optional[] = sprintf( $format_js_html_decoded_string, 'et_pagename', esc_js( $this->get_et_pagename() ) );
		}

		return implode( "\n", $js_optional );
	}
}
