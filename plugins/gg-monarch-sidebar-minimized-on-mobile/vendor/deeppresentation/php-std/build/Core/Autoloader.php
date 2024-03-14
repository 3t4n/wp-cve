<?php namespace MSMoMDP\Std\Core;

use MSMoMDP\Std\Core\Str;
use MSMoMDP\Std\Core\Path;


final class Autoloader {

	private $gPhpList = array();
	private $locked   = false;
	/**
	 * Call this method to get singleton
	 *
	 * @return Autoloader
	 */
	public static function Instance() {
		 static $inst = null;
		if ( $inst === null ) {
			$inst = new Autoloader();
		}
		return $inst;
	}

	public static function g_class_file_path_2_class_name( string $filePathOfGClass ) {
		 $res = pathinfo( $filePathOfGClass, PATHINFO_FILENAME );
		$res  = str_replace( 'class-', '', $res );
		return Str::separed_transform_ucfirst( $res, '-', '_' );
	}

	/**
	 * Private ctor so nobody else can instantiate it
	 *
	 */
	private function __construct() {
		//self::_init_g_support();
	}

	/*private function _init_g_support()
	{
		$rootPath = dirname(__FILE__);
		require_once $rootPath . '/class-g-std.php';
		$gFiles = array_filter(Path::get_dir_contents($rootPath), function ($path){
			$fileParts = pathinfo($path);
			if($fileParts && array_key_exists('extension', $fileParts))
			{
				return $fileParts['extension'] == 'php';
			}
			return false;
		 } );

		foreach ($gFiles as $gFile)
		{
			$this->register_php([self::g_class_file_path_2_class_name($gFile) => $gFile]);
		}
	}*/

	public function register_php( array $componentListToInclude, string $root = '' ) {
		$this->_run_safe( '_register_php', $componentListToInclude, $root );
	}

	public function get_file_path( string $component ) {
		return $this->_run_safe( '_get_file_path', $component );
	}

	public function require_once( array $components ) {
		foreach ( $components as $component ) {
			require_once $this->get_file_path( $component );
		}
	}


	private function _run_safe() {
		while ( $this->locked ) {
			sleep( 0.1 );
		}
		try {
			$args         = func_get_args();
			$function     = array_shift( $args );
			$this->locked = true;
			return call_user_func_array( array( $this, $function ), $args );
		} finally {
			$this->locked = false;
		}
	}

	private function _register_php( $gPhpFileIdAndFilePathList, $root ) {
		foreach ( $gPhpFileIdAndFilePathList as $key => $value ) {
			if ( ! empty( $root ) ) {
				$value = Path::combine_unix( $root, $value );
			}
			if ( array_key_exists( $key, $this->gPhpList ) ) {
				$currentPath = $this->gPhpList[ $key ];
				if ( $currentPath !== $value ) {
					$error = "Autoloader::register_php - Trying to register already existing class ${key} with different path (current path: ${currentPath}, requested path: ${value})";
					throw new Exception( $error );
				}
			} else {
				$this->gPhpList[ $key ] = $value;
			}
		}
	}

	private function _get_file_path( $componentListToInclude ) {
		if ( count( $this->gPhpList ) == 0 ) {
			$error = 'Autoloader::get_file_path - Trying to get path from not-initialized Autoloader. Call register_php begore.';
			throw new Exception( $error );
		} elseif ( ! array_key_exists( $componentListToInclude, $this->gPhpList ) ) {
			$error = "Autoloader::get_file_path - Trying to get path of not registred componentListToInclude - ${componentListToInclude} . Call register_php to register ${componentListToInclude} begore.";
			throw new Exception( $error );
		} else {
			return $this->gPhpList[ $componentListToInclude ];
		}
	}

}
