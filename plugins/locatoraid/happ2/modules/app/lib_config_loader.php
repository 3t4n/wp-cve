<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class App_Lib_Config_Loader_HC_MVC extends _HC_MVC
{
	private $dirs = array();
	private $config_file = NULL;
	private $config = array();
	private $modules = array();
	
	public function single_instance()
	{
	}

	public function set_config_file( $f )
	{
		$this->config_file = $f;
		return $this;
	}

	public function set_dirs( $dirs, $modules = array() )
	{
		$this->dirs = $dirs;
		$this->modules = $modules;

		$config = array();

		if( 0 ){
			if( ! defined('NTS_DEVELOPMENT2') ){
				foreach( $dirs as $dir ){
					$full_file_name = $dir . '/config/'. $this->config_file;
					if( file_exists($full_file_name) ){
						require( $full_file_name );
						$this->config = array_merge( $this->config, $config );
					}
				}
				return $this;
			}
		}

	// only in dev setup
	// update: everytime
		$priority = array('app');

		$use_dirs = array();
		foreach( $this->dirs as $dir ){
			$dir = $dir . '/modules';
			$subdirs = glob($dir . '/*', GLOB_ONLYDIR | GLOB_NOSORT);

		// first priority
			foreach( $subdirs as $mod_dir ){
				$module = substr( $mod_dir, strlen($dir) + 1 );
				$test_modules = explode('.', $module);
				if( ! array_intersect($test_modules, $priority) ){
					continue;
				}

				if( count(array_intersect($test_modules, $this->modules)) == count($test_modules) ){
					$use_dirs[] = $mod_dir;
				}
			}

		// then others
			foreach( $subdirs as $mod_dir ){
				$module = substr( $mod_dir, strlen($dir) + 1 );
				$test_modules = explode('.', $module);
				if( array_intersect($test_modules, $priority) ){
					continue;
				}
				if( count(array_intersect($test_modules, $this->modules)) == count($test_modules) ){
					$use_dirs[] = $mod_dir;
				}
			}
		}

		$config_names = array(
			'_config_route.php',
			'_config_settings.php',
			'_config_bootstrap.php',
			'_config_relations.php',
			'_config_alias.php',
			'_config_after.php',
			'_config_migration.php',
			);

		$to_require = array();
		reset( $use_dirs );
		foreach( $use_dirs as $dir ){
			reset( $config_names );
			foreach( $config_names as $f ){
				$full_f = $dir . '/' . $f;
				if( file_exists($full_f) ){
					$to_require[] = $full_f;
				}
			}
		}

		foreach( $to_require as $f ){
			require( $f );
		}

		$this->config = $config;
		return $this;
	}

	public function get( $what )
	{
		$return = isset($this->config[$what]) ? $this->config[$what] : array();
		return $return;
	}
}
