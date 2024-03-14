<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
if( class_exists('HC_Application') ){
	return;
}

class _HC_MVC
{
	public $app;
}

class HC_Application
{
	private $template = NULL;

	private $is_started = FALSE;
	private $is_bootstraped = FALSE;

	private $app_name = '';
	private $app_dirs = array();
	private $extension_modules = array();
	private $app_short_name = '';
	private $app_pages = array();

	private $factory = NULL;
	private $uri = NULL;

	public $profiler = NULL;

	public $migration = NULL;
	public $app_config = array();
	public $config = NULL;

	public $web_dir = NULL;

	public $db = NULL;
	public $class_prefix = '';

	public function __construct( $app_name, $app_dirs, $class_prefix = NULL, $extension_modules = array() )
	{
		$this->app_name = $app_name;
		$app_code = '';

		$this->app_dirs = $app_dirs;

		$this->app_short_name = 'hc' . $app_code;
		if( ! class_exists('HC_lib2') ){
			require dirname(__FILE__) . '/../lib/lib.php';
		}
		if( ! class_exists('Form_Input_HC_MVC') ){
			require dirname(__FILE__) . '/../modules/form/_interface.php';
		}

		$this->class_prefix = $class_prefix;
		$this->extension_modules = $extension_modules;
	}

	public function dir()
	{
		$return = $this->app_dirs[0];
		return $return;
	}

	public function set_template( $template )
	{
		$this->template = $template;
		return $this;
	}

	public function template()
	{
		return $this->template;
	}

	public function add_app_page( $page )
	{
		$this->app_pages[] = $page;
		return $this;
	}

	public function app_name()
	{
		return $this->app_name;
	}

	public function app_short_name()
	{
		return $this->app_short_name;
	}

	public function app_pages()
	{
		$return = $this->app_pages;
		if( ! in_array($this->app_short_name(), $return) ){
			$return[] = $this->app_short_name();
		}
		return $return;
	}

	public function go()
	{
		$this->start();
		$this->bootstrap();
		$view = $this->handle_request();
		echo $this->display_view( $view );
	}

	public function display_view( $view, $maybe_profiler = TRUE )
	{
		$return = '';
$this->profiler->mark('view_render_start');
		if( is_string($view) ){
			$return .= $view;
		}
		elseif( is_object($view) ){
			$return .= $view->render();
		}
		if( ! $maybe_profiler ){
			return $return;
		}

		// $return .= $view;
$this->profiler->mark('view_render_end');

$this->profiler->mark('total_execution_time_end');

$show_profiler = FALSE;
if( defined('NTS_DEVELOPMENT2') ){
// check if want json
	if( isset($_SERVER["CONTENT_TYPE"]) && (strtolower($_SERVER["CONTENT_TYPE"]) == 'application/json') ){
	}
	else {
		$uri = $this->make('/http/uri');
		$slug = $uri->slug();
		if( substr($slug, 0, strlen('api/')) == 'api/' ){
$show_profiler = TRUE;
		}
		else {
			$show_profiler = TRUE;
		}
	}

	// is ajax
	if( isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') ){
		$show_profiler = FALSE;
	}
}
// $show_profiler = TRUE;
// $show_profiler = FALSE;

if( $show_profiler ){
	$return .= '<div class="hc-xs-hide">';
	$return .= $this->profiler->run();
	$return .= '</div>';
}

		return $return;
	}

	public function handle_request( $start_slug = NULL )
	{
		$this_dir = dirname(__FILE__);

		if( ! $this->is_started() ){
			$this->start();
			$this->bootstrap();
		}

$this->profiler->mark('controller_start');

		$args = array();
		$uri = $this->make('/http/uri');

		$uri->from_url( $uri->current() );
		$slug = $uri->slug();
		
		if( (! $slug) && $start_slug ){
			$slug = $start_slug;
		}

// init session
		$session = $this->make('/session/lib');

	// LOCATE CONTROLLER
		list( $callable_controller, $args ) = $this->route($slug);
		$view = call_user_func_array( $callable_controller, $args );

$this->profiler->mark('controller_end');

	// out if it is a redirect
		if( $this->is_redirect($view) ){
			echo $this->display_view( $view, FALSE );
			exit;
		}

	// if print view
		if( isset($this->app_config['modules']['print']) ){
			$is_print_view = $this->make('/print/controller')->is_print_view();
			if( $is_print_view ){
				echo $this->display_view( $view );
				exit;
			}
		}

		return $view;
	}

	public function profiler()
	{
		return $this->profiler;
	}

	public function is_redirect( $return )
	{
		if( is_object($return) && is_callable(array($return, 'redirect')) && $return->redirect() ){
			return TRUE;
		}
		return FALSE;
	}

	public function is_started()
	{
		return $this->is_started;
	}

	public function set_db( $db )
	{
		$this->db = $db;
		return $this;
	}

	public function start()
	{
		if( $this->is_started ){
			return $this;
		}

		$this->is_started = TRUE;
		$this_dir = dirname(__FILE__);

		if ( ! hc_is_php('5.3')){
			@set_magic_quotes_runtime(0); // Kill magic quotes
		}

	// PROFILER
		if( ! class_exists('Profiler_HC_System') ){
			require $this_dir . '/parts/profiler.php';
		}

$this->profiler = new Profiler_HC_System;
$this->profiler->mark('total_execution_time_start');
$this->profiler->mark('loading_time:_base_classes_start');

$this->profiler->mark('app_init_start');

		$config = $this->_load_application_config();
		// if( ! $config ){
			// return $this;
		// }
		$this->app_config = $config;

// exit;
$this->profiler->mark('app_init_end');

	// CONFIG LOADER
		if( ! class_exists('App_Lib_Config_Loader_HC_MVC') ){
			require $this_dir . '/../modules/app/lib_config_loader.php';
		}

		$config_loader = new App_Lib_Config_Loader_HC_MVC;
		$config_loader
			->set_config_file( $this->app_name . '_config.php' )
			->set_dirs( $this->app_dirs, $config['modules'] )
			;

	// MODEL FACTORY
		if( ! class_exists('MVC_Factory_HC_System') ){
			require $this_dir . '/factory.php';
		}

		$config_alias = $config_loader->get('alias');

		$this->factory = new MVC_Factory_HC_System( 
			$this->app_dirs,
			$config_alias,
			$this->class_prefix,
			$this->app_config['modules']
			);

		// DATABASE
// _print_r( $db_params );
// exit;
		$hcdb = NULL;

		if( $this->db ){
			if( isset($config['dbprefix_version']) ){
				$prefix = $this->db->prefix();
				$prefix = $prefix . $config['dbprefix_version'] . '_';
				$this->db->set_prefix( $prefix );
			}

			$this->profiler->add_db( $this->db );
$this->profiler->mark('migration_start');

			$need_migration = $config_loader->get('migration');
			require $this_dir . '/parts/migration.php';
			$this->migration = new Migration_HC_System( $this, $need_migration );
			if( ! $this->migration->current()){
				hc_show_error( 'migration error' );
				exit;
			}

$this->profiler->mark('migration_end');
		}

// _print_r( $extend );
// _print_r( $extend['after'] );
// exit;

		$this->config = $config_loader;

	// do some init for app settings
$this->profiler->mark('app_settings_start');
		$settings = $this->make('/app/settings');
		$settings->set_config_loader( $config_loader );
		if( $this->db ){
			$settings->set_db( $this->db );
		}
$this->profiler->mark('app_settings_end');

		return $this;
	}

	public function bootstrap()
	{
		if( $this->is_bootstraped ){
			return $this;
		}
		$this->is_bootstraped = TRUE;

	// run modules bootstrap
$this->profiler->mark('loading_time:_base_classes_end');

		$bootstrap = $this->config->get('bootstrap');

		if( $bootstrap ){
$this->profiler->mark('loading_time:_bootstrap_start');
			foreach( $bootstrap as $callable ){
				$args = array( $this );
				call_user_func_array( $callable, $args );
			}
$this->profiler->mark('loading_time:_bootstrap_end');
		}

		return $this;
	}

	// returns an array(controller_name, method_name)
	public function route( $slug = '' )
	{
		$slug = strtolower( $slug );
		$slug = trim( $slug );
		$slug = trim( $slug, '/' );

// _print_r( $config_route );
// echo "SLUG = '$slug'<br>";
// exit;

		$root = $this->make('/root/link');
		$slug = $root->execute( $slug );

		$config_route = $this->config->get( 'route' );

		// not allowed
		if( $slug === FALSE ){
			$logged_in = $this->make('/auth/lib')
				->logged_in()
				;
			if( $logged_in ){
				$slug = 'acl/notallowed';
			}
			else {
				$slug = 'auth/login';
			}

			// if( $slug && isset($config_route[$slug]) ){
				// $slug = $config_route[$slug];
			// }
		}

		$return = NULL;
		$controller = NULL;
		$args = array();

		$config_route_keys = array_keys($config_route);

		if( isset($config_route[$slug]) ){
			if( is_callable($config_route[$slug]) ){
				$return = $config_route[$slug];
			// add app to args
				array_unshift( $args, $this );
			}
			else {
				$this_config_route = $config_route[$slug];
				if( is_array($this_config_route) ){
					$controller = array_shift( $this_config_route );
					while( $arg = array_shift($this_config_route) ){
						$args[] = $arg;
					}
				}
				else {
					$controller = $this_config_route;
				}

				$method = 'execute';
			}
		}
		else {
			// if we have wildcards
			$sluga = explode('/', $slug);
			$count_sluga = count($sluga);
			// echo "SLUG: '$slug'<br>";
			// _print_r( $sluga );

			$parametered_routes = array();
			foreach( $config_route_keys as $k ){
				if( strpos($k, '{') === FALSE ){
					continue;
				}
				$parametered_routes[] = $k;
			}

			reset( $parametered_routes );
			foreach( $parametered_routes as $k ){
				$ka = explode('/', $k);

			// check if this one matches
				if( count($ka) != $count_sluga ){
					continue;
				}

				$match = TRUE;
				$parametered_args = array();
				for( $ii = 0; $ii < $count_sluga; $ii++ ){
					if( strpos($ka[$ii], '{') !== FALSE ){
						$parametered_args[] = $sluga[$ii];
					}
					else {
						if( $ka[$ii] != $sluga[$ii] ){
							$match = FALSE;
						}
					}
				}

				if( $match ){
					$controller = $config_route[$k];
					$method = 'execute';
					
					foreach( $parametered_args as $parametered_arg ){
						$args[] = $parametered_arg;
					}
					// $args = $parametered_args;
					break;
				}
			}
		}

		if( ! $controller ){
			list( $controller, $method ) = $this->locate_default_controller( $slug );
		}

		if( ! $return ){
			$controller = $this->make( $controller );
			$return = array( $controller, $method );
		}

		$return = array( $return, $args );
		return $return;
	}

	public function locate_default_controller( $slug )
	{
		$route = explode('/', $slug);

	// REST API
		$more_args = array();

		if( isset($route[0]) && ($route[0] == 'api') ){
			$full_route = $route;
			$route = array( array_shift($full_route) );
			$route[] = 'controller';

			$real_method = isset($_SERVER['REQUEST_METHOD']) ? strtolower($_SERVER['REQUEST_METHOD']) : 'get';

// echo "FULL ROUTE = ";
// _print_r( $full_route );

			$end_point = array();
			$end_point[] = array_shift( $full_route );

			$id = NULL;
			if( $full_route ){
				// is numeric then is id
				if( (string)(int) $full_route[0] == $full_route[0] ){
					$id = array_shift( $full_route );
				}
				$end_point = array_merge( $end_point, $full_route );
			}

			$end_point = join('/', $end_point);
// echo "ENDPOINT = '$end_point'<br>";
// exit;
			$more_args[] = $end_point;
			$more_args[] = $id;

			$full_route = join('/', $full_route);
		}
	// HTML INTERFACE
		else {
			$real_method = 'execute';
			$route[] = 'controller';
		}

		$real_method_name = str_replace('-', '_', $real_method );
		$controller_slug = '/' . join('/', $route);

		$return = array( $controller_slug, $real_method );
		return $return;
	}

	public function make( $slug )
	{
		$args = func_get_args();
		$slug = $args[0];

		if( substr($slug, 0, 1) != '/' ){
			hc_show_error("NEED FULL SLUG STARTING WITH '/', '$slug' WAS GIVEN<br>");
		}

		if( count($args) > 1 ){
			$args[0] = $slug;
			$return = call_user_func_array( array($this->factory, 'make'), $args );
		}
		else {
			$return = $this->factory->make( $slug );
		}

		$return->app = $this;
		if( method_exists($return, '_init') ){
			$return = call_user_func( array($return, '_init') );
		}

		return $return;
	}

	public function after( $mvc )
	{
		$args = func_get_args();
		$mvc = array_shift( $args );
		$return = array_shift( $args );

		if( is_array($mvc) ){
			$route = $this->factory->get_slug( strtolower(get_class($mvc[0])) )  . '->' . $mvc[1];
		}
		else {
			$route = $this->factory->get_slug( strtolower(get_class($mvc)) );
		}

		$extend = $this->config->get('after');

		if( defined('WPINC') ){
			$extend = apply_filters( 'locatoraid_after', $extend );
		}

		if( ! (isset($extend[$route]) && $extend[$route]) ){
			return $return;
		}

		reset( $extend[$route] );
		foreach( $extend[$route] as $ck => $callable ){
			$args2 = $args;
			array_unshift( $args2, $return );

			if( is_callable($callable) ){
				array_unshift( $args2, $this );
			}
			else {
				if( strpos($callable, '@') !== FALSE ){
					list( $mvc2_slug, $mvc2_method ) = explode('@', $callable);
				}
				else {
					$mvc2_slug = $callable;
					$mvc2_method = 'execute';
				}

				$mvc2 = $this->make( $mvc2_slug );
				$callable = array($mvc2, $mvc2_method);
			}

			$return2 = call_user_func_array( $callable, $args2 );
			if( $return2 !== NULL ){
				$return = $return2;
			}
		}

		return $return;
	}

	private function _load_application_config()
	{
		$config = array( 'modules' => array() );

		$file_found = FALSE;
		$search_file = $this->app_name . '.php';

		$found_file = NULL;
		foreach( $this->app_dirs as $app_dir ){
			$target_file = $app_dir . '/config/' . $search_file;
			if( file_exists($target_file) ){
				$found_file = $target_file;
				break;
			}
		}

		if( $found_file ){
			require( $found_file );
		}
		else {
			$error = 'NO APP CONFIG FILE FOUND! ' . $search_file;
			echo $error;
		}

	/* process modules */
		if( isset($config['modules']) ){
			if( $this->extension_modules ){
				$config['modules'] = array_merge( $config['modules'], $this->extension_modules );
			}

			$new_modules = array();
			foreach( $config['modules'] as $m ){
				$new_modules[$m] = $m;
			}
			$config['modules'] = $new_modules;
		}

		return $config;
	}

	public function has_module( $module )
	{
		return in_array( $module, $this->app_config['modules'] );
	}
}