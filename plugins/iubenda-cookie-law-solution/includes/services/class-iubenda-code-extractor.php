<?php
/**
 * Iubenda enqueue embed code.
 *
 * @package  Iubenda
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Enqueue embed code.
 */
class Iubenda_Code_Extractor {
	// phpcs:disable WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase

	/**
	 * Scripts with src that will enqueued.
	 *
	 * @var array
	 */
	private $scripts = array();

	/**
	 * Attributes for scripts that will enqueued.
	 *
	 * @var array
	 */
	private $script_attributes = array();

	/**
	 * Scripts that will printed inlined.
	 *
	 * @var array
	 */
	private $scripts_inline = array();

	/**
	 * Styles with src that will enqueued.
	 *
	 * @var array
	 */
	private $styles = array();

	/**
	 * Styles that will printed inlined.
	 *
	 * @var array
	 */
	private $styles_inline = array();

	/**
	 * Unreadable scripts.
	 *
	 * @var array
	 */
	private $tampered_scripts = array();

	/**
	 * Embed code.
	 *
	 * @var string
	 */
	private $code;

	/**
	 * Auto Blocking Enabled.
	 *
	 * @var bool
	 */
	private $is_auto_blocking_enabled = false;

	/**
	 * Site ID.
	 *
	 * @var string
	 */
	private $site_id;

	/**
	 * Cookie Policy ID.
	 *
	 * @var string
	 */
	private $cookie_policy_id;

	/**
	 * List of scripts that will be ignored from appending to body
	 *
	 * @var string[]
	 */
	private $escaped_scripts_from_body = array(
		'iubenda.com/sync/',
		'iubenda.com/autoblocking/',
	);

	/**
	 * Array of classes required for appending scripts in the head section.
	 *
	 * @var string[] Class names of the script appenders.
	 */
	private $required_scripts_in_head = array(
		Auto_Blocking_Script_Appender::class,
		Sync_Script_Appender::class,
	);

	/**
	 * Extract scripts and styles from embed code and enqueue it
	 *
	 * @param   string $code  embed code.
	 *
	 * @return void
	 */
	public function enqueue_embed_code( $code ) {
		$this->code = $code;

		$this->set_auto_blocking_state( $code );
		$this->extract_html_tags();
		$this->handle_scripts();
		$this->dispatch_scripts_in_head();
		$this->handle_styles();

		add_filter( 'script_loader_tag', array( $this, 'iub_add_attribute_to_scripts' ), 10, 2 );
	}

	/**
	 * Dispatch the required scripts in head based on conditions
	 *
	 * @return void
	 */
	private function dispatch_scripts_in_head() {
		foreach ( $this->required_scripts_in_head as $class ) {
			$instance = new $class( $this );
			$instance->handle();
		}
	}

	/**
	 * Declare auto blocking feature state
	 *
	 * @param   string $code  embed code.
	 */
	private function set_auto_blocking_state( $code ) {
		$instance      = new Auto_Blocking();
		$this->site_id = $instance->get_site_id_from_cs_code( $code );
		if ( $this->site_id ) {
			$this->is_auto_blocking_enabled = (bool) iub_array_get( iubenda()->options, "cs.frontend_auto_blocking.{$this->site_id}" );
		}
		$this->cookie_policy_id = $instance->get_cookie_policy_id_from_cs_code( $code );
	}

	/**
	 * Extract tampered scripts from embed code and return it
	 *
	 * @param   string $code  embed code.
	 *
	 * @return  mixed
	 */
	public function clean_tampered_scripts( $code ) {
		$this->code = $code;
		$this->extract_html_tags();

		return $this->code;
	}

	/**
	 * If has tampered scripts return true else return false.
	 *
	 * @param   string $code  embed code.
	 *
	 * @return  mixed
	 */
	public function has_tampered_scripts( $code ) {
		$this->code = $code;
		$this->extract_html_tags();

		return count( $this->tampered_scripts ) > 0;
	}

	/**
	 * Handle scripts that detected inside embed code.
	 */
	private function handle_scripts() {
		// Extract scripts without src from embed code to add it inline.
		foreach ( $this->scripts_inline as $key => $script ) {
			wp_register_script( "iubenda-head-inline-scripts-$key", ' ' );// phpcs:ignore WordPress.WP.EnqueuedResourceParameters.NotInFooter,WordPress.WP.EnqueuedResourceParameters.MissingVersion
			wp_enqueue_script( "iubenda-head-inline-scripts-$key" );
			wp_add_inline_script(
				"iubenda-head-inline-scripts-$key",
				$script['content'],
				'after'
			);

			$this->script_attributes[ "iubenda-head-inline-scripts-$key" ] = $script['attributes'];
		}

		// Extract scripts with src to enqueue it.
		foreach ( $this->scripts as $key => $script ) {
			if ( $this->may_escape_script_from_body( $script ) ) {
				continue;
			}

			wp_enqueue_script(
				"iubenda-head-scripts-{$key}",
				$script['src'],
				array(),
				iubenda()->version,
				false
			);

			// Store script attributes for reference.
			$this->script_attributes[ "iubenda-head-scripts-{$key}" ] = $script['attributes'];
		}
	}

	/**
	 * Handle styles that detected inside embed code.
	 */
	private function handle_styles() {
		// Extract styles without src from embed code to add it inline.
		wp_register_style( 'iubenda-inline-styles-from-embed', '' ); // phpcs:ignore WordPress.WP.EnqueuedResourceParameters.MissingVersion
		wp_enqueue_style( 'iubenda-inline-styles-from-embed' );
		foreach ( $this->styles_inline as $key => $style ) {
			wp_add_inline_style(
				'iubenda-inline-styles-from-embed',
				$style
			);
		}

		// Extract styles with src to enqueue it.
		foreach ( $this->styles as $key => $src ) {
			wp_enqueue_style( "iubenda-styles-from-embed-{$key}", $src, array(), iubenda()->version );
		}
	}

	/**
	 * Extract html tags.
	 */
	private function extract_html_tags() {
		// phpcs:disable WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
		if ( empty( $this->code ) ) {
			return;
		}

		// Ensure DOMDocument class exists.
		if ( can_use_dom_document_class() ) {
			$this->extract_html_tags_with_dom();

			return;
		}

		// Ensure helper class were loaded.
		if ( ! function_exists( 'str_get_html' ) ) {
			if ( ! file_exists( IUBENDA_PLUGIN_PATH . 'iubenda-cookie-class/simple_html_dom.php' ) ) {
				return;
			}
			require_once IUBENDA_PLUGIN_PATH . 'iubenda-cookie-class/simple_html_dom.php';
		}

		$this->extract_html_tags_with_simple_html_dom();
	}

	/**
	 * Check if the script is tampered script.
	 *
	 * @param   mixed $script_value  Script value that will be checked.
	 *
	 * @return bool
	 */
	private function check_tampered_script( $script_value ) {
		// Remove new lines and spaces.
		$script_value = strtolower( trim( preg_replace( '/\s+/', '', $script_value ) ) );

		return strpos( $script_value, 'eval(' ) !== false;
	}

	/**
	 * Add attribute to script tags with defined handles.
	 *
	 * @param   string $tag     HTML for the script tag.
	 * @param   string $handle  Handle of script.
	 *
	 * @return string
	 */
	public function iub_add_attribute_to_scripts( $tag, $handle ) {
		$key = array_search( $handle, array_keys( $this->script_attributes ), true );

		if ( false !== $key ) {
			$attributes = $this->wp_sanitize_script_attributes( $this->script_attributes[ $handle ] );
			$tag        = str_replace( '<script', '<script ' . $attributes, $tag );
		}

		// Inline scripts do not have src, so we look for scripts that are queued by iubenda handler and have src.
		if ( strpos( $handle, 'iubenda-head-inline-scripts' ) !== false && strpos( $tag, 'src' ) !== false ) {
			// Workaround to append attributes on inline scripts
			// Remove temp enqueued script tag.
			$tag = $this->remove_first_script_tag( $tag );
			// Remove -js-after from script id.
			$tag = str_replace( '-js-after', '', $tag );
		}

		return $tag;
	}

	/**
	 * Fetch script attributes to associative array.
	 * Key      = attribute name.
	 * Value    = attribute value.
	 *
	 * @param   mixed $script  Script node.
	 *
	 * @return array
	 */
	private function fetch_script_attributes( $script ) {
		$attributes = array();

		foreach ( $script->attributes as $attribute ) {
			if ( strpos( $attribute->nodeName, 'src' ) !== false ) {
				continue;
			}

			$attributes[ $attribute->nodeName ] = $attribute->nodeValue;
		}

		return $attributes;
	}

	/**
	 * Fetch script attributes to associative array and append iub cs skip class.
	 *
	 * @param   mixed $script  Script node.
	 *
	 * @return array
	 * Key      = attribute name.
	 * Value    = attribute value.
	 */
	private function fetch_attributes_and_add_skip_class( $script ) {
		$attributes = $this->fetch_script_attributes( $script );

		return $this->add_iub_cs_skip_class( $attributes );
	}

	/**
	 * Sanitizes an attributes array into an attributes string to be placed inside a `<script>` tag.
	 *
	 * Automatically injects type attribute if needed.
	 * Copied wp_sanitize_script_attributes core function because we support 5.0+
	 *
	 * @param   array $attributes  Key-value pairs representing `<script>` tag attributes.
	 *
	 * @return string String made of sanitized `<script>` tag attributes.
	 * @todo to be removed when we upgrade the support version to 5.7+
	 */
	private function wp_sanitize_script_attributes( $attributes ) {
		if ( function_exists( 'wp_sanitize_script_attributes' ) ) {
			return wp_sanitize_script_attributes( $attributes );
		}

		$html5_script_support = ! is_admin() && ! current_theme_supports( 'html5', 'script' );
		$attributes_string    = '';

		// If HTML5 script tag is supported, only the attribute name is added
		// to $attributes_string for entries with a boolean value, and that are true.
		foreach ( $attributes as $attribute_name => $attribute_value ) {
			if ( is_bool( $attribute_value ) ) {
				if ( $attribute_value ) {
					$attributes_string .= $html5_script_support ? sprintf( ' %1$s="%2$s"', esc_attr( $attribute_name ), esc_attr( $attribute_name ) ) : ' ' . esc_attr( $attribute_name );
				}
			} else {
				$attributes_string .= sprintf( ' %1$s="%2$s"', esc_attr( $attribute_name ), esc_attr( $attribute_value ) );
			}
		}

		return $attributes_string;
	}

	/**
	 * Extract html tags with dom document using PHP-XML module.
	 */
	private function extract_html_tags_with_dom() {
		$previous_value = libxml_use_internal_errors( true );
		if ( function_exists( 'mb_encode_numericentity' ) ) {
			$this->code = mb_encode_numericentity( $this->code, array( 0x80, 0x10FFFF, 0, ~0 ), 'UTF-8' );
		}

		$document = new DOMDocument();
		$document->loadHTML(
			'<html>' . $this->code . '</html>',
			LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD
		);

		$scripts = $document->getElementsByTagName( 'script' );
		if ( ! empty( $scripts ) && is_object( $scripts ) ) {
			foreach ( $scripts as $script ) {
				if ( $script->hasAttribute( 'src' ) ) {
					$this->scripts[] = array(
						'src'        => $script->getAttribute( 'src' ),
						'attributes' => $this->fetch_attributes_and_add_skip_class( $script ),
					);
				} elseif ( $this->check_tampered_script( $script->nodeValue ) ) {
					$this->tampered_scripts[] = $script->nodeValue;
					$script->nodeValue        = '';
				} else {
					$this->scripts_inline[] = array(
						'content'    => $script->nodeValue,
						'attributes' => $this->fetch_attributes_and_add_skip_class( $script ),
					);
				}
			}
		}

		$styles = $document->getElementsByTagName( 'style' );
		if ( ! empty( $styles ) && is_object( $styles ) ) {
			foreach ( $styles as $style ) {
				if ( $style->hasAttribute( 'src' ) ) {
					$this->styles[] = $style->getAttribute( 'src' );
				} else {
					$this->styles_inline[] = $style->nodeValue;
				}
			}
		}
		libxml_use_internal_errors( $previous_value );

		$this->code = str_replace( array( '<html>', '</html>' ), '', $document->saveHTML() );
	}

	/**
	 * Extract html tags with dom document using helper class.
	 */
	private function extract_html_tags_with_simple_html_dom() {
		if ( ! function_exists( 'str_get_html' ) ) {
			return;
		}

		$html = str_get_html( $this->code, true, true, false );

		if ( is_object( $html ) ) {
			$scripts = $html->find( 'script' );
			if ( ! empty( $scripts ) ) {
				foreach ( $scripts as $script ) {
					// Script has src.
					if ( ! empty( $script->src ) ) {
						$this->scripts[] = array(
							'src'        => $script->src,
							'attributes' => $this->add_iub_cs_skip_class( $script->getAllAttributes() ),
						);
						continue;
					}

					// Check if script is tampered.
					if ( $this->check_tampered_script( $script->innertext ) ) {
						$this->tampered_scripts[] = $script;
						$script->innertext        = '';
						continue;
					}

					// For inline scripts.
					$this->scripts_inline[] = array(
						'content'    => $script->innertext,
						'attributes' => $this->add_iub_cs_skip_class( $script->getAllAttributes() ),
					);
				}
			}

			$styles = $html->find( 'style' );
			if ( ! empty( $styles ) ) {
				foreach ( $styles as $style ) {
					if ( ! empty( $style->src ) ) {
						// Styles with src.
						$this->styles[] = $style->src;
					} else {
						// For inline styles.
						$this->styles_inline[] = $style->innertext;
					}
				}
			}

			$this->code = $html;
		}
	}

	/**
	 * Append cs skip class on attributes
	 *
	 * @param array|mixed $attributes Attributes that will append _iub_cs_skip on it.
	 *
	 * @return array|mixed
	 */
	private function add_iub_cs_skip_class( $attributes = array() ) {
		if ( isset( $attributes['class'] ) ) {
			$attributes['class']  = (string) $attributes['class'];
			$attributes['class'] .= strpos( $attributes['class'], '_iub_cs_skip' ) !== false ? '' : ' _iub_cs_skip';
		} else {
			$attributes['class'] = ' _iub_cs_skip';
		}
		return $attributes;
	}

	/**
	 * Removes everything up to and including the end of the first <script> tag in a string.
	 *
	 * @param   string $str  The original script tag.
	 *
	 * @return string The modified string.
	 */
	private function remove_first_script_tag( $str ) {
		// Find the end position of the first <script> tag.
		$pos = strpos( $str, '</script>' );
		if ( false !== $pos ) {
			// Add the length of the closing </script> tag to the end position.
			$pos += strlen( '</script>' );
			// Remove everything up to and including the end of the first <script> tag.
			$str = substr( $str, $pos );
		}

		return $str;
	}

	/**
	 * Determines whether a given script should be excluded from the body based on predefined criteria.
	 *
	 * This method iterates over a list of specific script URLs (or patterns) that are meant to be escaped
	 * from being placed in the body. It checks if the source URL of the provided script matches any of
	 * these predefined URLs or patterns. If a match is found, the script is considered for exclusion.
	 *
	 * @param array $script An associative array containing script attributes, with 'src' being one of the keys representing the script's source URL.
	 *
	 * @return bool Returns true if the script matches any of the predefined URLs or patterns and should be excluded from the body; otherwise, false.
	 */
	private function may_escape_script_from_body( $script ): bool {
		foreach ( $this->escaped_scripts_from_body as $url ) {
			if ( strpos( $script['src'], $url ) !== false ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Get List of scripts
	 *
	 * @return array
	 */
	public function get_scripts() {
		return $this->scripts;
	}

	/**
	 * Get Site ID
	 *
	 * @return string
	 */
	public function get_site_id() {
		return $this->site_id;
	}

	/**
	 * Get Cookie Policy ID
	 *
	 * @return string
	 */
	public function get_cookie_policy_id() {
		return $this->cookie_policy_id;
	}

	/**
	 * Get Auto Blocking State
	 *
	 * @return bool
	 */
	public function is_auto_blocking_enabled() {
		return $this->is_auto_blocking_enabled;
	}
	// phpcs:enable
}
