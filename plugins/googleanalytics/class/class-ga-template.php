<?php
/**
 * Google Analytics template.
 *
 * @package GoogleAnalytics
 */

/**
 * Class Ga_Template
 */
class Ga_Template {
	/**
	 * Array of template properties.
	 *
	 * @var array Props array.
	 */
	protected $props;

	/**
	 * Relative path in view/ folder.
	 *
	 * @var string Path string.
	 */
	protected $path;

	/**
	 * Ga_Template constructor.
	 *
	 * @param string $path Relative path in view/ folder.
	 * @param array  $props Array of props to be passed to the template.
	 */
	public function __construct( $path, $props = array() ) {
		$this->path  = $path;
		$this->props = $props;
	}

	/**
	 * Include rendered template inline.
	 *
	 * @param string $path Relative path in view/ folder.
	 * @param array  $props Array of props to be passed to the template.
	 */
	public static function load( $path, $props = array() ) {
		( new static( $path, $props ) )->include_template();
	}

	/**
	 * Get rendered template.
	 *
	 * @param string $path Relative path in view/ folder.
	 * @param array  $props Array of props to be passed to the template.
	 *
	 * @return string Rendered template.
	 */
	public static function render( $path, $props = array() ) {
		return ( new static( $path, $props ) )->render_template();
	}

	/**
	 * Include template.
	 */
	public function include_template() {
		$template_path = GA_PLUGIN_DIR . '/view/' . $this->path . '.php';

		if ( is_readable( $template_path ) ) {
			load_template( $template_path, false, $this->props );
		}
	}

	/**
	 * Get rendered template.
	 *
	 * @return string
	 */
	public function render_template() {
		ob_start();
		$this->include_template();
		$render = ob_get_contents();

		return false === empty( $render ) ? $render : '';
	}
}
