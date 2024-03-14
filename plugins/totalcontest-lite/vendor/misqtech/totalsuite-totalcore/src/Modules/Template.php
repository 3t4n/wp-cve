<?php

namespace TotalContestVendors\TotalCore\Modules;

use TotalContestVendors\TotalCore\Helpers\Compressor;
use TotalContestVendors\TotalCore\Helpers\Strings;

/**
 * Class Template
 * @package TotalContestVendors\TotalCore\Modules
 */
abstract class Template extends Module {
	/**
	 * @var string Buffer.
	 * @access protected
	 * @since  1.0.0
	 */
	protected $stream = [];

	/**
	 * Get CSS.
	 *
	 * @param array $args
	 *
	 * @return null|string|string[]
	 */
	public function getCompiledCss( $args = [] ) {
		if ( ! \TotalContestVendors\TotalCore\Application::get( 'filesystem' )->is_readable( $this->getPath( 'assets/css/style.css' ) ) ):
			return null;
		endif;

		$content = file_get_contents( $this->path . 'assets/css/style.css' );

		return Compressor::css( Strings::template( $content, $args ) );
	}

	/**
	 * Get view.
	 *
	 * @param       $view
	 * @param array $data
	 *
	 * @return bool|string
	 */
	public function getView( $view, $data = [] ) {
		if ( empty( $view ) ):
			return false;
		endif;

		$path = $this->getPath( 'views' . DIRECTORY_SEPARATOR . str_replace( '.', DIRECTORY_SEPARATOR, $view ) . '.php' );

		ob_start();
		if ( file_exists( $path ) === true ):
			$includeClosure = function ( $__includePath, $data ) {
				extract( $data, EXTR_OVERWRITE );
				include $__includePath;
			};

			$includeClosure( $path, $data );
		endif;

		return ob_get_clean();
	}

}