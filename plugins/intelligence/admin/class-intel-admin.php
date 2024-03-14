<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       getlevelten.com/blog/tom
 * @since      1.0.0
 *
 * @package    Intel
 * @subpackage Intel/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Intel
 * @subpackage Intel/admin
 * @author     Tom McCracken <tomm@getlevelten.com>
 */
class Intel_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Holds query string
	 * @var
	 */
	public $q = '';

	public $args = array();

	public $admin_notices = array();

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Intel_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Intel_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/intel-admin.css', array(), $this->version, 'all' );

		wp_enqueue_style( 'intel_bootstrap', plugin_dir_url( __FILE__ ) . 'css/intel-bootstrap.min.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Intel_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Intel_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		// core js support functions
		wp_register_script('intel', INTEL_URL . 'js/intel.js', array('jquery'), $this->version, true);

		wp_enqueue_script( $this->plugin_name, INTEL_URL . 'admin/js/intel-admin.js', array( 'jquery' ), $this->version, false );

		//wp_enqueue_script('intel_admin_js_bootstrap_hack', INTEL_URL . 'admin/js/intel-bootstrap-hack.js', false, $this->version, false);

		wp_enqueue_script('intel_admin_bootstrap', INTEL_URL . 'vendor/bootstrap/js/bootstrap.min.js', false, $this->version, false);


		$data = array();
		$data['intel_dir'] = INTEL_DIR;
		$data['intel_url'] = INTEL_URL;
		$data['intel_file'] = INTEL_FILE;
		wp_localize_script('intel_admin_js_bootstrap_hack', 'intel_admin_settings', $data);
	}

	public function admin_init() {
		// this will break some forms as objects may not be loaded yet. Need to implement
		// form redirects in javascript. For example set wpcf7_intel edit form.
		/*
		if (!empty($_POST['intel_form'])) {
			$vars = !empty($_POST['form_options']) ? $_POST['form_options'] : '{}';
			$vars = stripcslashes($vars);
			$vars = json_decode($vars, 1);
			$form = $this->admin_init_get_form($_POST['form_id'], $vars);
		}
		*/
	}

	function admin_init_get_form($form_id, $options) {
		$forms = &Intel_Df::drupal_static( __FUNCTION__, array());

		if (isset($forms[$form_id])) {
			return $forms[$form_id];
		}

		if (is_callable($form_id)) {
			include_once(INTEL_DIR . 'includes/class-intel-form.php');
			$forms[$form_id] = Intel_Form::drupal_get_form($form_id, $options);
		}
		else {
			$forms[$form_id] = FALSE;
		}
		return $forms[$form_id];
	}

	// buffer page output incase we need to do a redirect
	public static function ob_callback($buffer) {
		return $buffer;
	}

	public function ob_start() {
		ob_start("Intel_Admin::ob_callback");
	}

	function ob_end(){
		ob_end_flush();
	}

	public function session_start() {
		// need to start session for messages to queue across pages
		if(!session_id()) {
			try {
				$success = session_start();
			}
			catch (Exception $e) {
				Intel_Df::drupal_set_message($e->getMessage() . '[' , $e->getCode());
			}
		}
	}

	public function session_end() {
		try {
			session_destroy();
		}
		catch (Exception $e) {
			Intel_Df::drupal_set_message($e->getMessage() . '[' , $e->getCode());
		}

    }

	public function site_menu($network_site_menu = FALSE) {
		global $wp_version;

		$capability = ($network_site_menu) ? 'manage_network_options' : 'manage_options';

		// if network framework mode, hide admin menu for subsites
        if (intel()->is_network_framework_mode && !$network_site_menu) {
            return;
        }

		if ( current_user_can( 'manage_options' ) ) {
			if (0 && intel_is_framework_only()) {
              add_menu_page( esc_html__( "Intelligence", 'intel' ), esc_html__( "Intelligence", 'intel' ), $capability, 'intel_config', array( $this, 'menu_router' ), 'dashicons-analytics');
            }
			else {
              add_menu_page( esc_html__( "Intelligence", 'intel' ), esc_html__( "Intelligence", 'intel' ), $capability, 'intel_admin', array( $this, 'menu_router' ), 'dashicons-analytics');
              add_submenu_page( 'intel_admin', esc_html__( "Dashboard", 'intel' ), esc_html__( "Dashboard", 'intel' ), $capability, 'intel_admin', array( $this, 'menu_router' ) );
              add_submenu_page( 'intel_admin', esc_html__( "Reports", 'intel' ), esc_html__( "Reports", 'intel' ), $capability, 'intel_reports', array( $this, 'menu_router' ) );
              add_submenu_page( 'intel_admin', esc_html__( "Annotations", 'intel' ), esc_html__( "Annotations", 'intel' ), $capability, 'intel_annotation', array( $this, 'menu_router' ) );
              add_submenu_page( 'intel_admin', esc_html__( "Contacts", 'intel' ), esc_html__( "Contacts", 'intel' ), $capability, 'intel_visitor', array( $this, 'menu_router' ) );
              add_submenu_page( 'intel_admin', esc_html__( "Settings", 'intel' ), esc_html__( "Settings", 'intel' ), $capability, 'intel_config', array( $this, 'menu_router' ) );
              add_submenu_page( 'intel_admin', esc_html__( "Utilities", 'intel' ), esc_html__( "Utilities", 'intel' ), $capability, 'intel_util', array( $this, 'menu_router' ) );
              add_submenu_page( 'intel_admin', esc_html__( "Help", 'intel' ), esc_html__( "Help", 'intel' ), $capability, 'intel_help', array( $this, 'menu_router' ) );
            }
  	    }
	}

	public function network_site_menu() {
	    $this->site_menu(TRUE);
    }

	// for return_type json, page needs to be called earlier than standard menu routing
	public function init_menu_routing() {
		if (!empty($_GET['return_type']) && $_GET['return_type'] == 'json') {
			$this->menu_router();
			wp_die();
		}
	}

	public function  sort_menu_info_weight($a, $b) {
		if ($a['key_args_count'] != $b['key_args_count']) {
			return $a['key_args_count'] < $b['key_args_count'] ? -1 : 1;
		}
		$a_weight = is_array($a) && isset($a['weight']) ? $a['weight'] : 0;
		$b_weight = is_array($b) && isset($b['weight']) ? $b['weight'] : 0;
		if ($a_weight == $b_weight) {
			return $a['_index'] < $b['_index'] ? -1 : 1;
		}
		return $a_weight < $b_weight ? -1 : 1;
	}

	public function menu_router() {
		$intel = intel();
		$menu_info = $intel->menu_info();

		uasort($menu_info, array($this, 'sort_menu_info_weight'));

		$install_levels = intel_is_installed('all');
		$install_access_error = 0;

		$info = array();
		$tree = array();
		$breadcrumbs = array();
		$breadcrumbs[] = array(
			'text' => esc_html__('Intelligence', 'intel'),
			//'path' => Intel_Df::url('admin/intel'),
		);
		$navbar_exclude = array();

		$q = '';
		if ($_GET['page'] == 'intel_admin') {
          $q = 'admin/reports/intel';
          //$navbar_exclude[$q] = 1;
          $breadcrumbs[] = array(
            'text' => esc_html__('Reports', 'intel'),
            'path' => Intel_Df::url($q),
          );
          $navbar_base_q = $navbar_base_qt = $q;
          if (!$install_levels['ga_data']) {
            $install_access_error = intel_get_install_access_error_message(array('level' => 'ga_data'));
          }
		}
		if ($_GET['page'] == 'intel_reports') {
			$q = 'admin/reports/intel';
			//$navbar_exclude[$q] = 1;
			$breadcrumbs[] = array(
				'text' => esc_html__('Reports', 'intel'),
				'path' => Intel_Df::url($q),
			);
			$navbar_base_q = $navbar_base_qt = $q;
			if (!$install_levels['ga_data']) {
				$install_access_error = intel_get_install_access_error_message(array('level' => 'ga_data'));
			}
		}

		if ($_GET['page'] == 'intel_config') {
			$q = 'admin/config/intel/settings';
			$breadcrumbs[] = array(
				'text' => esc_html__('Settings', 'intel'),
				'path' => Intel_Df::url($q),
			);
		}
		if ($_GET['page'] == 'intel_util') {
			$q = 'admin/util';
			$breadcrumbs[] = array(
				'text' => esc_html__('Utilities', 'intel'),
				'path' => Intel_Df::url($q),
			);
			$navbar_base_q = $navbar_base_qt = $q;
		}
		if ($_GET['page'] == 'intel_help') {
			$q = 'admin/help';
			$breadcrumbs[] = array(
				'text' => esc_html__('Help', 'intel'),
				'path' => Intel_Df::url($q),
			);
			$navbar_base_q = $navbar_base_qt = $q;
		}
		if ($_GET['page'] == 'intel_visitor') {
			$q = 'admin/people/contacts';
			$breadcrumbs[] = array(
				'text' => esc_html__('Contacts', 'intel'),
				'path' => Intel_Df::url($q),
			);
		}
		if ($_GET['page'] == 'intel_annotation') {
			$q = 'admin/annotations';
			$breadcrumbs[] = array(
				'text' => esc_html__('Annotations', 'intel'),
				'path' => Intel_Df::url($q),
			);
		}
		if (intel_is_framework_only()) {
          $q = 'admin/config/intel/settings/framework';
        }
		if (isset($_GET['q'])) {
			$q = $_GET['q'];
		}
		else {
			$_GET['q'] = &$q;
		}
		$q = stripslashes($q);
		$intel->q = $q;
		// set translated path
		$qt = $q;

		if (!$install_levels['min']) {
			$install_access_error = intel_get_install_access_error_message();
		}

		$entities = array(
			'submission',
			'visitor',
			'annotation',
		);
		$this->args = $path_args = explode('/', $q);

		$path_args_t = array();

		if (empty($info)) {
			if (!empty($menu_info[$q])) {
				$info = $menu_info[$q];
				// special exceptions for admin/config/intel/settings/*/add
				if (!empty($path_args[1]) && $path_args[1] == 'config') {
					if (!empty($path_args[5]) && $path_args[5] == 'add') {
						if ($path_args[4] == 'intel_event') {
							$bc_title = Intel_Df::t('Events');
						}
						elseif ($path_args[4] == 'goal') {
							$bc_title = Intel_Df::t('Goals');
						}
						elseif ($path_args[4] == 'taxonomy') {
							$bc_title = Intel_Df::t('Taxonomies');
						}
						if (!empty($bc_title)) {
							$a = array_slice($path_args, 0, 5);
							$breadcrumbs[] = array(
								'text' => $bc_title,
								'path' => Intel_Df::url(implode('/', $a)),
							);
						}

					}
					elseif (!empty($path_args[4]) && $path_args[4] == 'form') {
						$a = array_slice($path_args, 0, 5);
						$breadcrumbs[] = array(
							'text' => Intel_Df::t('Forms'),
							'path' => Intel_Df::url(implode('/', $a)),
						);
					}
				}
                else if (!empty($path_args[1]) && $path_args[1] == 'util') {
                  if (!empty($path_args[2]) && $path_args[2] == 'log') {
                    $bc_title = Intel_Df::t('Log');
                  }
                  if (!empty($bc_title)) {
                    $a = array_slice($path_args, 0, 4);
                    $breadcrumbs[] = array(
                      'text' => $bc_title,
                      'path' => Intel_Df::url(implode('/', $a)),
                    );
                  }
                }
			}
			else {
				if (in_array($path_args[0], $entities)) {
					$a = $path_args;
					$a[1] = '%intel_' . $path_args[0];
					$qt = implode('/', $a);
					if (!empty($menu_info[$qt])) {
						$info = $menu_info[$qt];
						if (!empty($path_args[1])) {
							$entity_type = substr($a[1], 1);

							// load entity using arg(1);
							$entity = $intel->get_entity_controller($entity_type)->loadOne($path_args[1]);
							// visitor entities can be passed by vtk or vtkid, if entity not found
							// using vid, try creating one using vtk/vtkid
							if (empty($entity) && $path_args[0] == 'visitor' && strlen($path_args[1]) >= 20) {
								$ev = array(
									'id' => $path_args[1],
								);
								$entity = $intel->get_entity_controller($entity_type)->create($ev);
							}

							if (empty($entity)) {
								$vars = array(
									'title' => esc_html__('404 Error', 'intel'),
									'markup' => esc_html__('Entity not found', 'intel'),
									'messages' => Intel_Df::drupal_get_messages(),
								);
								print Intel_Df::theme('intel_page', $vars);
								return;
							}
							$path_args_t[1] = $entity;
						}
						if ($path_args[0] == 'visitor') {
							$breadcrumbs[] = array(
								'text' => $entity->label(),
								'path' => Intel_Df::url($entity->uri()),
							);
						}
						if ($path_args[0] == 'submission') {
							$breadcrumbs[] = array(
								'text' => $entity->label(),
								'path' => Intel_Df::url($entity->uri()),
							);
						}
						if ($path_args[0] == 'annotation') {
							$breadcrumbs[] = array(
								'text' => $entity->label(),
								'path' => Intel_Df::url($entity->uri()),
							);
						}
					}
				}
				elseif ($path_args[0] == 'admin') {
					// deal with menu paths that need to load objects
					$load_index = 0;
					$load_type = '';
					$load_title = '';
					$entity_title = '';
					if ($path_args[1] == 'config') {
                      if ($path_args[4] == 'intel_event') {
                        $load_index = 5;
                        $load_type = 'intel_intel_event';
                        $load_title = Intel_Df::t('Intel Event');
                        $bc_title = Intel_Df::t('Event');
                      }
                      elseif ($path_args[4] == 'goal') {
                        $load_index = 5;
                        $load_type = 'intel_goal';
                        $bc_title = $load_title = Intel_Df::t('Goal');
                      }
                      elseif ($path_args[4] == 'taxonomy') {
                        $load_index = 5;
                        $load_type = 'intel_taxonomy';
                        $bc_title = $load_title = Intel_Df::t('Taxonomy');
                      }
                    }
					else if ($path_args[1] == 'util') {
                      if ($path_args[2] == 'log' && !empty($path_args[3])) {
                        $load_index = 3;
                        $load_type = 'intel_log';
                        $load_title = Intel_Df::t('Intel Log');
                        $bc_title = Intel_Df::t('Log');
                      }
                    }

                    if ($load_index) {
                        $func = $load_type . '_load';
                        $path_args_t[$load_index] = $func($path_args[$load_index]);
                        $entity = $path_args_t[$load_index];
                        if(empty($entity)) {
                            $vars = array(
                                'title' => esc_html__('404 Error', 'intel'),
                                'markup' => Intel_Df::t('@load_title not found', array(
                                    '@load_title' => $load_title,
                                )),
                                //'markup' => esc_html__('@load_title not found', 'intel'),
                                'messages' => Intel_Df::drupal_get_messages(),
                            );
                            print Intel_Df::theme('intel_page', $vars);
                            return;
                        }
                        $a = $path_args;
                        $a[$load_index] = '%' . $load_type;
                        $qt = implode('/', $a);
                        if (!empty($menu_info[$qt])) {
                            $info = $menu_info[$qt];
                        }
                        $a = array_slice($path_args, 0, $load_index);
                        $breadcrumbs[] = array(
                            'text' => $bc_title,
                            'path' => Intel_Df::url(implode('/', $a)),
                        );
                        $a = array_slice($path_args, 0, $load_index + 1);
                        $breadcrumbs[] = array(
                            'text' => ($entity instanceof IntelEntity) ? $entity->label() : $entity['title'],
                            //'path' => Intel_Df::url(implode('/', $a)),
                        );
                    }
				}
			}
		}

		if (empty($info)) {
			$vars = array(
				'title' => esc_html__('404 Error', 'intel'),
				'markup' => esc_html__('Page not found', 'intel'),
				'messages' => Intel_Df::drupal_get_messages(),
			);
			print Intel_Df::theme('intel_page', $vars);
			return;
		}



		/*
		$a = explode('/', $qt);
		$qt_arg_count = count($a);
		$qt_len = strlen($qt);
		foreach ($menu_info as $k => $mi) {
			if (isset($mi['type']) && ($mi['type'] == Intel_Df::MENU_DEFAULT_LOCAL_TASK) && substr($k, 0, $qt_len) == $qt) {
				$a = explode('/', $k);
				$a_cnt = count($a);
				if ($a_cnt == ($qt_arg_count + 1)) {
					$info = $mi + $info;
					$breadcrumbs[] = array(
						'text' => Intel_Df::t($info['title']),
						'path' => Intel_Df::url($q),
					);
					$q .= '/' . $a[$a_cnt - 1];
					$qt .= '/' . $a[$a_cnt - 1];

					break;
				}
			}
		}
		d($q);
		d($qt);
		*/

		$a = explode('/', $q);
		$q_arg_count = count($a);
		array_pop($a);
		$parent_q = implode('/', $a);

		$a = explode('/', $qt);
		array_pop($a);
		$parent_qt = implode('/', $a);

		$defs = array(
			'type' => Intel_Df::MENU_CALLBACK,
		);
		$info += $defs;

		if ($info['type'] & Intel_Df::MENU_LINKS_TO_PARENT ) {
			if (isset($menu_info[$parent_qt])) {
				$info += $menu_info[$parent_qt];
			}
		}

		// check permissions
		$func = !empty($info['access callback']) ? $info['access callback'] : 'user_access';
		if ($func == 'user_access') {
			$func = 'Intel_Df::' . $func;
		}
		$args = !empty($info['access arguments']) ? $info['access arguments'] : array();
		if (!call_user_func_array($func, $args)) {
			$vars = array(
				'title' => esc_html__('401 Error', 'intel'),
				'markup' => esc_html__('Not authorized', 'intel'),
				'messages' => Intel_Df::drupal_get_messages(),
			);
			print Intel_Df::theme('intel_page', $vars);
			return;
		}

		// check menu item install level access
		if (isset($info['intel_install_access'])) {

			if(intel_is_installed($info['intel_install_access'])) {
				$install_access_error = 0;
			}
			else {
				$install_access_error = intel_get_install_access_error_message($info['intel_install_access']);
			}
		}

		// process page arguments
		$page_args = !empty($info['page arguments']) ? $info['page arguments'] : array();

		foreach ($page_args as $k => $arg) {
			if (is_integer($arg)) {
				$page_args[$k] = !empty($path_args_t[$arg]) ? $path_args_t[$arg] : $path_args[$arg];
			}
		}

		// TODO WP handle permissions

		// set page title using menu info
		if (!empty($info['title'])) {
			$title = esc_html__($info['title'], 'intel');
			$intel->set_page_title($title);
		}

		if (!empty($info['file'])) {
			$fp = !empty($info['file path']) ? $info['file path'] : INTEL_DIR;
			$fn = $fp . $info['file'];
			include_once $fn;
		}

		// check if setup is complete
		if ($install_access_error && (substr($q, 0, 33) != 'admin/config/intel/settings/setup')) {
			//$msg = Intel_Df::t('Intelligence setup must be complete before accessing this page. !link.', array(
			//	'!link' => Intel_Df::l(Intel_Df::t('Click here to run the setup wizard'), 'admin/config/intel/settings/setup'),
			//));
			Intel_Df::drupal_set_message($install_access_error, 'warning');
			$vars['markup'] = '';
		}
		else {
			$page_func = $info['page callback'];
			if ($page_func == 'drupal_get_form') {
				include_once ( INTEL_DIR . 'includes/class-intel-form.php' );
				$page_func = 'Intel_Form::drupal_get_form';
			}
			$vars['markup'] = call_user_func_array($page_func, $page_args);
			$vars['markup'] = Intel_Df::render($vars['markup']);
		}


		$base_q = $q;
		$base_qt = $qt;
		$menu_actions = array();
		if ($info['type'] & Intel_Df::MENU_IS_LOCAL_TASK ) {
			$base_q = $parent_q;
			$base_qt = $parent_qt;
		}
		if (empty($tree)) {
			$tree = self::build_submenu_tree(isset($navbar_base_qt) ? $navbar_base_qt : $base_qt, $q, $menu_info, $navbar_exclude, $menu_actions, $breadcrumbs);
		}

		$nb_vars = array(
			'brand' => 'Intelligence',
			'base_path' => isset($navbar_base_q) ? $navbar_base_q : $base_q,
			'tree' => $tree,
			'tree2' => $menu_actions,
		);
		$vars['navbar'] = Intel_Df::theme('intel_navbar', $nb_vars);

		$vars['breadcrumbs'] = Intel_Df::theme('intel_breadcrumbs', array('breadcrumbs' => $breadcrumbs));

		$vars['messages'] = Intel_Df::drupal_get_messages();

		print Intel_Df::theme('intel_page', $vars);
	}

	public function build_submenu_tree($base_qt, $q, $menu_info, $exclude = array(), &$actions = array(), &$breadcrumbs = array()) {
		$tree = array();
		$actions = array();
		$qt_len = strlen($base_qt);
		$a = explode('/', $base_qt);
		$b = explode('/', $q);
		$q_end_arr = array_slice($b, count($a));
		$q_end = implode('/', $q_end_arr);
		$bc_add = array();

		foreach ($menu_info as $k => $info) {
			if (!empty($exclude[$k])) {
				continue;
			}

			if (isset($info['type']) && ($info['type'] & Intel_Df::MENU_LOCAL_TASK)  && (substr($k, 0, $qt_len) == $base_qt) && ($k != $base_qt)) {

				// if default local task, get info from parent;
				if ($info['type'] & Intel_Df::MENU_DEFAULT_LOCAL_TASK) {
					$a = explode('/', $k);
					array_pop($a);
					$parent_k = implode('/', $a);
					if (!empty($menu_info[$parent_k])) {
						$info += $menu_info[$parent_k];
					}
				}

				// check permissions
				$func = !empty($info['access callback']) ? $info['access callback'] : 'user_access';
				if ($func == 'user_access') {
					$func = 'Intel_Df::' . $func;
				}
				$args = !empty($info['access arguments']) ? $info['access arguments'] : array();
				if (!call_user_func_array($func, $args)) {
					continue;
				}
				//d($k);

				// get elements after $qt;
				$defs = array(
					'type' => Intel_Df::MENU_CALLBACK,
				);
				$info += $defs;

				$qt_end = substr($k, $qt_len + 1);
				$qt_end_arr = explode('/', $qt_end);

				if (count($qt_end_arr) == 1) {
					if (!empty($q_end_arr[0]) && $qt_end_arr[0] == $q_end_arr[0]
						|| (empty($q_end_arr[0]) && ($info['type'] & Intel_Df::MENU_LINKS_TO_PARENT) )
					) {

						$info['active'] = 1;
						$bc_add[0] = array(
							'text' => $info['title'],
							'path' => Intel_Df::url($q),
						);
					}
					if ($info['type'] & Intel_Df::MENU_IS_LOCAL_ACTION) {
						$actions[$qt_end_arr[0]] = array(
							'#info' => $info,
						);
					}
					else {
						$tree[$qt_end_arr[0]] = array(
							'#info' => $info,
						);
					}
				}
				elseif (count($qt_end_arr) == 2) {
					if (!isset($tree[$qt_end_arr[0]])) {
						$tree[$qt_end_arr[0]] = array();
					}
					if (
						!empty($q_end_arr[0]) && $qt_end_arr[0] == $q_end_arr[0]
						&& !empty($q_end_arr[1]) && $qt_end_arr[1] == $q_end_arr[1]
					) {
						$info['active'] = 1;
						$bc_add[1] = array(
							'text' => $info['title'],
							'path' => Intel_Df::url($q),
						);
					}
					$tree[$qt_end_arr[0]][$qt_end_arr[1]] = array(
						'#info' => $info,
					);
				}
			}
		}
		$breadcrumbs = array_merge ( $breadcrumbs, $bc_add );
		return $tree;
	}

	public function admin_bar_menu($wp_admin_bar) {
		$args = array(
			'id'    => 'my_page',
			'title' => 'My Page',
			'href'  => 'http://mysite.com/my-page/',
			'meta'  => array( 'class' => 'my-toolbar-page' )
		);
		$wp_admin_bar->add_node( $args );
	}

	public function contacts_column_headers() {
		$ch = array(
			'cb' => '<input type="checkbox" />',
			'name' => esc_html__('Name', 'intel'),
			'email' => esc_html__('Email', 'intel'),
			'contact_created' => esc_html__('Created', 'intel'),
			'last_activity' => esc_html__('Last activity', 'intel'),
			'score' => esc_html__('Score', 'intel'),
			//'entrances' => esc_html__('Visits', 'intel'),
			//'pageviews' => esc_html__('Pageviews', 'intel'),
			//'timeOnSite' => esc_html__('Time on site', 'intel'),
		);
		return $ch;
	}

	public function plugin_action_links($links) {
		$l = array();
		$l[] = Intel_Df::l(Intel_Df::t('Settings'), 'admin/config/intel/settings');
		$links = $l + $links;
		return $links;
	}

	/**
	 * Implements hook_admin_notices()
	 */
	public function admin_notices() {
		$output = '';
		// Don't show the connect notice anywhere but the plugins.php after activating
		$current = get_current_screen();
		if (!intel_is_installed('min') && !intel_is_framework() && 'plugins' === $current->parent_base  ) {
			$l_options = Intel_Df::l_options_add_class('btn btn-info');
			$output .= '  <div class="panel panel-info m-t-1">';
			$output .= '    <h2 class="panel-heading m-t-0">' . __( 'Get Intelligence!', 'intel' ) . '</h2>';
			$output .= '    <div class="panel-body">';
			$output .= '      <p>' . __( 'To complete the installation of Intelligence launch the setup wizard using the button below.', 'intel' ) . '</p>';
			$output .= '      <p>' . Intel_Df::l( Intel_Df::t('Launch Setup Wizard'), 'admin/config/intel/settings/setup', $l_options) . '</p>' ;
			$output .= '    </div>';
			$output .= '  </div>';
		}
		$intel = intel();

		if (!empty($intel->system_meta['needed_updates'])) {
			$l_options = Intel_Df::l_options_add_class('btn btn-info');
			$output .= '<div class="alert alert-danger">';
			$output .= __( 'Intelligence plugin has requires updates. ', 'intel' );
			$output .= Intel_Df::l(Intel_Df::t('Click here to view and run updates'), 'admin/util/update') . '.';
			$output .= '</div>';
		}

		if ($output) {
			$this->enqueue_scripts();
			$this->enqueue_styles();
			print '<div id="intel-admin-notices" class="bootstrap-wrapper wrap">' . $output . '</div>';
		}
	}

	public function admin_setup_notice() {

	}

	public function admin_update_notice() {
		$current = get_current_screen();
		if ( 'plugins' !== $current->parent_base ) {
			//return;
		}

		if (intel_is_installed('min')) {
			return;
		}

		$this->enqueue_scripts();
		$this->enqueue_styles();
		?>
				<div class="alert alert-danger">
					<p><?php print __( 'To complete the installation of Intelligence launch the setup wizard using the button below.', 'intel' ); ?></p>
					<p>
						<a href="<?php print Intel_Df::url('admin/config/intel/utilities/update'); ?>" class="btn btn-info">Updates</a>
					</p>
				</div>

		<?php
	}

	public function activated_plugin($plugin) {
		require_once( INTEL_DIR . 'intel_com/intel.setup.php' );
		intel_setup_activated_plugin($plugin);
	}
}
