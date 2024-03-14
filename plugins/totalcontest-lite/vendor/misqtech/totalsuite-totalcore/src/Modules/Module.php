<?php

namespace TotalContestVendors\TotalCore\Modules;


use TotalContestVendors\TotalCore\Application;
use TotalContestVendors\TotalCore\Contracts\Modules\Module as ModuleContract;
use TotalContestVendors\TotalCore\Helpers\Arrays;

/**
 * Class Module
 * @package TotalContestVendors\TotalCore\Modules
 */
abstract class Module implements ModuleContract {
	/**
	 * @var array $options
	 */
	public $options = [];
	/**
	 * @var string $textdomain
	 */
	public $textdomain = '';
	/**
	 * @var string $root
	 */
	protected $root = __FILE__;
	/**
	 * @var string $path
	 */
	protected $path = __DIR__;
	/**
	 * @var string $url
	 */
	protected $url = '';

	/**
	 * Module constructor.
	 *
	 * @param array $options
	 */
	public function __construct( $options = [] ) {
		$this->options = (array) $options;
		$env           = Application::getInstance()->container( 'env' );

		$this->path = str_replace( '\\', '/', dirname( $this->root ) . '/' );

		if ( stripos( $this->root, 'uploads' ) !== false ) {
			$uploadDir = wp_upload_dir();
			$this->url = $uploadDir['baseurl'] . str_replace( $uploadDir['basedir'], '', $this->path );

			$uploadDir = wp_upload_dir();
			$baseUrl   = is_ssl() ? set_url_scheme( $uploadDir['baseurl'], 'https' ) : $uploadDir['baseurl'];
			$baseDir   = realpath( $uploadDir['basedir'] );
			$this->url = $baseUrl . str_replace( $baseDir, '', $this->path );
		} else {
			$this->url = $env->get( 'url' ) . str_replace( $env->get( 'path' ), '', $this->path );
		}
	}

	/**
	 * On activation hook.
	 */
	public static function onActivate() {
		return;
	}

	/**
	 * On deactivation hook.
	 */
	public static function onDeactivate() {
		return;
	}

	/**
	 * On uninstall hook.
	 */
	public static function onUninstall() {
		return;
	}

	/**
	 * Get URL.
	 *
	 * @param string $relativePath relative path.
	 *
	 * @return bool true on success, false on failure.
	 * @since 1.0.0
	 *
	 */
	public function getUrl( $relativePath = '' ) {
		return $this->url . $relativePath;
	}

	/**
	 * Get path.
	 *
	 * @param string $relativePath relative path.
	 *
	 * @return bool true on success, false on failure.
	 * @since 1.0.0
	 *
	 */
	public function getPath( $relativePath = '' ) {
		return $this->path . $relativePath;
	}

	/**
	 * Load text domain.
	 *
	 * @return bool true on success, false on failure.
	 * @since 1.0.0
	 */
	public function loadTextdomain() {
		if ( ! empty( $this->textdomain ) ):
			$locale = apply_filters( 'plugin_locale', get_locale(), $this->textdomain );

			return load_textdomain( $this->textdomain, "{$this->path}/languages/{$this->textdomain}-{$locale}.mo" );
		endif;

		return false;
	}

	/**
	 * Get option.
	 *
	 * @param      $needle
	 * @param null $default
	 *
	 * @return mixed|null
	 */
	public function getOption( $needle, $default = null ) {
		return Arrays::getDotNotation( $this->options, $needle, $default );
	}

	/**
	 * Get options.
	 *
	 * @return array
	 */
	public function getOptions() {
		return $this->options;
	}
}
