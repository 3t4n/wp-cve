<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class MVC_Factory_HC_System
{
	private $dirs = array();
	private $modules = array();
	private $class_prefix = NULL;

	private $aliases = array();
	private $registry = array(); // keep singletons

	private $slug2classname = array();
	private $classname2slug = array();

	public function __construct( 
		$dirs = array(),
		$aliases = array(),
		$class_prefix = NULL,
		$active_modules = array()
		)
	{
		$this->dirs = $dirs;
		$this->aliases = $aliases;
		$this->class_prefix = $class_prefix;
		$this->modules = $active_modules;
	}

	public function get_slug( $classname )
	{
		if( isset($this->classname2slug[$classname]) ){
			$return = '/'. $this->classname2slug[$classname];
		}
		else {
			$return = NULL;
		}
		return $return;
	}

	public function make_classname_path( $slug )
	{
		static $slug2class = array();
		if( isset($slug2class[$slug]) ){
			$return = $slug2class[ $slug ];
			return $return;
		}

		$prepared_slug = $slug;
		$prepared_slug = str_replace('-', '_', $prepared_slug);

		$slug_array = explode('/', $prepared_slug);

		$return_classname = join('_', $slug_array);
		$return_classname = str_replace('.', '_', $return_classname);

		$return_module = array_shift( $slug_array );

		$return_path = join('_', $slug_array);
		$return_path .= '.php';
		$return_path = array( $return_path );

		if( count($slug_array) > 1 ){
			$return_path = array();
			$this_return_path = join('_', $slug_array);
			$this_return_path .= '.php';
			array_unshift( $return_path, $this_return_path );
			// $final_return_path[] = $this_return_path;

			$last_subdir = '';
			while( ($subdir = array_shift($slug_array)) && $slug_array ){
				$this_return_path = $subdir . '/' . join('_', $slug_array);
				if( $last_subdir ){
					$this_return_path = $last_subdir . '/' . $this_return_path;
				}

				$this_return_path .= '.php';
				// $final_return_path[] = $this_return_path;
				array_unshift( $return_path, $this_return_path );

				if( $last_subdir ){
					$last_subdir .= '/';
				}
				$last_subdir .= $subdir;
			}
		}

// echo "FINAL RETURN PATH<br>";
// _print_r( $return_path );

		$return = array( $return_module, $return_classname, $return_path );

// echo "MAKING CLASSNAME PATH FROM '$slug'<br>";
// _print_r( $return );
		$slug2class[ $slug ] = $return;
		return $return;
	}

	protected function _get_full_class_name_options( $short_name )
	{
		$return = array();

		$addons = array();
		if( $this->class_prefix ){
			$addons[] = $this->class_prefix;
		}
		$addons[] = '';
 
		foreach( $addons as $this_addon ){
			$prefix = '';
			$suffix = '_hc_mvc';
			if( strlen($this_addon) ){
				$suffix = '_' . $this_addon . $suffix;
			}
			$this_return = $prefix . $short_name . $suffix;
			$return[] = $this_return;
		}

		return $return;
	}

	protected function _get_class_name_by_slug( $slug )
	{
		$return = NULL;

		$slug = strtolower($slug);
		$slug = trim($slug);

		if( isset($this->aliases[$slug]) ){
			// echo "USE ALIAS FOR '$slug'<br>";
			$slug = $this->aliases[$slug];
		}
		$slug = trim($slug, '/');

		$return = NULL;
		if( isset($this->slug2classname[$slug]) ){
			$return = $this->slug2classname[$slug];
			return $return;
		}

		list( $module, $class_name, $path_array ) = $this->make_classname_path( $slug );

	// check if module exists
		$module_exists = TRUE;

		if( strpos($module, '.') === FALSE ){
			if( ! isset($this->modules[$module]) ){
				$module_exists = FALSE;
			}
		}
		else {
			$test_modules = explode('.', $module);
			foreach( $test_modules as $test_module ){
				if( ! isset($this->modules[$test_module]) ){
					$module_exists = FALSE;
					break;
				}
			}
		}

		if( ! $module_exists ){
			$error_msg = array();
			$error_msg[] = "Module '$module' not available for '$slug'<br>";
			$error_msg = join('<br>', $error_msg);
			hc_show_error( $error_msg );
			return $return;
		}

	// if already exists
		$full_class_names = $this->_get_full_class_name_options( $class_name );
		reset( $full_class_names );
		foreach( $full_class_names as $fcn ){
			if( class_exists($fcn) ){
				$return = $fcn;

				if( ! isset($this->slug2classname[$slug]) ){
					$this->slug2classname[$slug] = $fcn;
				}
				if( ! isset($this->classname2slug[$fcn]) ){
					$this->classname2slug[$fcn] = $slug;
				}

				return $return;
			}
		}

	// trying to load
		$tried = array();
		reset( $this->dirs );

		$module_dir = 'modules/' . $module;
		foreach( $this->dirs as $try_dir ){
			reset( $path_array );
			foreach( $path_array as $path ){
				$file = $try_dir . '/' . $module_dir . '/' . $path;
				if( file_exists($file) ){
					require( $file );

				// check which class is here
					reset( $full_class_names );
					foreach( $full_class_names as $full_class_name ){
						if( class_exists($full_class_name) ){
							$return = $full_class_name;

							$this->slug2classname[$slug] = $full_class_name;
							$this->classname2slug[$full_class_name] = $slug;

							return $return;
						}
						else {
							$tried[] = $file . ": '$fcn' class not exists";
						}
					}
				}
				else {
					$tried[] = $file . ': file not found';
				}
			}
		}

		$error_msg = array();
		$error_msg[] = "Can't locate class for '$slug'<br>";

		if( defined('NTS_DEVELOPMENT2') ){
			reset( $tried );
			foreach( $tried as $tr ){
				$error_msg[] = 'tried: ' . $tr;
			}
		}

		$error_msg = join('<br>', $error_msg);
		hc_show_error( $error_msg );

		return $return;
	}

	public function make( $slug )
	{
		$class_name = $this->_get_class_name_by_slug( $slug );

	// FIND MVC OBJECT
		if( method_exists($class_name, 'single_instance') ){
			if( ! isset($this->registry[$class_name]) ){
				$this->registry[$class_name] = new $class_name;
			}
			$return = $this->registry[$class_name];
		}
		else {
			$return = new $class_name;
		}

		return $return;
	}
}