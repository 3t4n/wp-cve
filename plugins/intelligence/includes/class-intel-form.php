<?php

/**
 * Form building help
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       getlevelten.com/blog/tom
 * @since      1.0.0
 *
 * @package    Intel
 * @subpackage Intel/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Intel
 * @subpackage Intel/includes
 * @author     Tom McCracken <tomm@getlevelten.com>
 */
class Intel_Form  {

	private static $instance;

	private function __construct() {

	}

	public static function getInstance() {
		if (empty(self::$instance)) {
			self::$instance = new Intel_Form();
		}
		return self::$instance;
	}

	/**
	 * Returns a renderable form array for a given form ID.
	 *
	 * This function should be used instead of drupal_build_form() when $form_state
	 * is not needed (i.e., when initially rendering the form) and is often
	 * used as a menu callback.
	 *
	 * @param $form_id
	 *   The unique string identifying the desired form. If a function with that
	 *   name exists, it is called to build the form array. Modules that need to
	 *   generate the same form (or very similar forms) using different $form_ids
	 *   can implement hook_forms(), which maps different $form_id values to the
	 *   proper form constructor function. Examples may be found in node_forms(),
	 *   and search_forms(). hook_forms() can also be used to define forms in
	 *   classes.
	 * @param ...
	 *   Any additional arguments are passed on to the functions called by
	 *   drupal_get_form(), including the unique form constructor function. For
	 *   example, the node_edit form requires that a node object is passed in here
	 *   when it is called. These are available to implementations of
	 *   hook_form_alter() and hook_form_FORM_ID_alter() as the array
	 *   $form_state['build_info']['args'].
	 *
	 * @return
	 *   The form array.
	 *
	 * @see drupal_build_form()
	 */
	public static function drupal_get_form($form_id) {
		$count = &Intel_Df::drupal_static(__FUNCTION__, 0);

		if ($count == 0) {
			wp_enqueue_script( 'intel_form', INTEL_URL . 'admin/js/intel-form.js', array( 'jquery' ), intel()->get_version(), false );
			wp_enqueue_style( 'intel_form', INTEL_URL . 'admin/css/intel-form.css', array(), intel()->get_version(), 'all');
		}
		$count++;

		$form_state = array();

		$args = func_get_args();
		// Remove $form_id from the arguments.
		array_shift($args);
		$form_state['build_info']['args'] = $args;

		$form = self::drupal_build_form($form_id, $form_state);

		//$out = self::form_render($form);
		//$out = Intel_Df::render($form);
		return $form;
	}

	public static function drupal_build_form($form_id, &$form_state) {
		// Ensure some defaults; if already set they will not be overridden.
		$form_state += self::form_state_defaults();

		if (!isset($form_state['input'])) {
			$form_state['input'] = $form_state['method'] == 'get' ? $_GET : $_POST;
		}

		if (isset($_SESSION['batch_form_state'])) {
			// We've been redirected here after a batch processing. The form has
			// already been processed, but needs to be rebuilt. See _batch_finished().
			$form_state = $_SESSION['batch_form_state'];
			unset($_SESSION['batch_form_state']);
			return drupal_rebuild_form($form_id, $form_state);
		}

		// If the previous bit of code didn't result in a populated $form object, we
		// are hitting the form for the first time and we need to build it from
		// scratch.
		if (!isset($form)) {

			$form = self::drupal_retrieve_form($form_id, $form_state);
			self::drupal_prepare_form($form_id, $form, $form_state);

  	}

		// Now that we have a constructed form, process it. This is where:
		// - Element #process functions get called to further refine $form.
		// - User input, if any, gets incorporated in the #value property of the
		//   corresponding elements and into $form_state['values'].
		// - Validation and submission handlers are called.
		// - If this submission is part of a multistep workflow, the form is rebuilt
		//   to contain the information of the next step.
		// - If necessary, the form and form state are cached or re-cached, so that
		//   appropriate information persists to the next page request.
		// All of the handlers in the pipeline receive $form_state by reference and
		// can use it to know or update information about the state of the form.
		self::drupal_process_form($form_id, $form, $form_state);

		return $form;
	}

	/**
	 * Retrieves default values for the $form_state array.
	 */
	public static function form_state_defaults() {
		return array(
			'rebuild' => FALSE,
			'rebuild_info' => array(),
			'redirect' => NULL,
			// @todo 'args' is usually set, so no other default 'build_info' keys are
			//   appended via += form_state_defaults().
			'build_info' => array(
				'args' => array(),
				'files' => array(),
			),
			'temporary' => array(),
			'submitted' => FALSE,
			'executed' => FALSE,
			'programmed' => FALSE,
			'programmed_bypass_access_check' => TRUE,
			'cache'=> FALSE,
			'method' => 'post',
			'groups' => array(),
			'buttons' => array(),
		);
	}

	/**
	 * Constructs a new $form from the information in $form_state.
	 *
	 * This is the key function for making multi-step forms advance from step to
	 * step. It is called by drupal_process_form() when all user input processing,
	 * including calling validation and submission handlers, for the request is
	 * finished. If a validate or submit handler set $form_state['rebuild'] to TRUE,
	 * and if other conditions don't preempt a rebuild from happening, then this
	 * function is called to generate a new $form, the next step in the form
	 * workflow, to be returned for rendering.
	 *
	 * Ajax form submissions are almost always multi-step workflows, so that is one
	 * common use-case during which form rebuilding occurs. See ajax_form_callback()
	 * for more information about creating Ajax-enabled forms.
	 *
	 * @param $form_id
	 *   The unique string identifying the desired form. If a function
	 *   with that name exists, it is called to build the form array.
	 *   Modules that need to generate the same form (or very similar forms)
	 *   using different $form_ids can implement hook_forms(), which maps
	 *   different $form_id values to the proper form constructor function. Examples
	 *   may be found in node_forms() and search_forms().
	 * @param $form_state
	 *   A keyed array containing the current state of the form.
	 * @param $old_form
	 *   (optional) A previously built $form. Used to retain the #build_id and
	 *   #action properties in Ajax callbacks and similar partial form rebuilds. The
	 *   only properties copied from $old_form are the ones which both exist in
	 *   $old_form and for which $form_state['rebuild_info']['copy'][PROPERTY] is
	 *   TRUE. If $old_form is not passed, the entire $form is rebuilt freshly.
	 *   'rebuild_info' needs to be a separate top-level property next to
	 *   'build_info', since the contained data must not be cached.
	 *
	 * @return
	 *   The newly built form.
	 *
	 * @see drupal_process_form()
	 * @see ajax_form_callback()
	 */
	public static function drupal_rebuild_form($form_id, &$form_state, $old_form = NULL) {
		$form = self::drupal_retrieve_form($form_id, $form_state);

		// If only parts of the form will be returned to the browser (e.g., Ajax or
		// RIA clients), or if the form already had a new build ID regenerated when it
		// was retrieved from the form cache, reuse the existing #build_id.
		// Otherwise, a new #build_id is generated, to not clobber the previous
		// build's data in the form cache; also allowing the user to go back to an
		// earlier build, make changes, and re-submit.
		// @see drupal_prepare_form()
		$enforce_old_build_id = isset($old_form['#build_id']) && !empty($form_state['rebuild_info']['copy']['#build_id']);
		$old_form_is_mutable_copy = isset($old_form['#build_id_old']);
		if ($enforce_old_build_id || $old_form_is_mutable_copy) {
			$form['#build_id'] = $old_form['#build_id'];
			if ($old_form_is_mutable_copy) {
				$form['#build_id_old'] = $old_form['#build_id_old'];
			}
		}
		else {
			if (isset($old_form['#build_id'])) {
				$form['#build_id_old'] = $old_form['#build_id'];
			}
			//$form['#build_id'] = 'form-' . drupal_random_key();
			$form['#build_id'] = 'form-' . uniqid();
		}

		// #action defaults to request_uri(), but in case of Ajax and other partial
		// rebuilds, the form is submitted to an alternate URL, and the original
		// #action needs to be retained.
		if (isset($old_form['#action']) && !empty($form_state['rebuild_info']['copy']['#action'])) {
			$form['#action'] = $old_form['#action'];
		}

		self::drupal_prepare_form($form_id, $form, $form_state);

		// Caching is normally done in drupal_process_form(), but what needs to be
		// cached is the $form structure before it passes through form_builder(),
		// so we need to do it here.
		// @todo For Drupal 8, find a way to avoid this code duplication.
		if (empty($form_state['no_cache'])) {
			self::form_set_cache($form['#build_id'], $form, $form_state);
		}

		// Clear out all group associations as these might be different when
		// re-rendering the form.
		$form_state['groups'] = array();

		// Return a fully built form that is ready for rendering.
		return self::form_builder($form_id, $form, $form_state);
	}


	/**
	 * Fetches a form from cache.
	 */
	public static function form_get_cache($form_build_id, &$form_state) {
		// TODO WP
		return NULL;

		if ($cached = cache_get('form_' . $form_build_id, 'cache_form')) {
			$form = $cached->data;

			global $user;
			if ((isset($form['#cache_token']) && drupal_valid_token($form['#cache_token'])) || (!isset($form['#cache_token']) && !$user->uid)) {
				if ($cached = cache_get('form_state_' . $form_build_id, 'cache_form')) {
					// Re-populate $form_state for subsequent rebuilds.
					$form_state = $cached->data + $form_state;

					// If the original form is contained in include files, load the files.
					// @see form_load_include()
					$form_state['build_info'] += array('files' => array());
					foreach ($form_state['build_info']['files'] as $file) {
						if (is_array($file)) {
							$file += array('type' => 'inc', 'name' => $file['module']);
							module_load_include($file['type'], $file['module'], $file['name']);
						}
						elseif (file_exists($file)) {
							require_once DRUPAL_ROOT . '/' . $file;
						}
					}
				}
				// Generate a new #build_id if the cached form was rendered on a cacheable
				// page.
				if (!empty($form_state['build_info']['immutable'])) {
					$form['#build_id_old'] = $form['#build_id'];
					$form['#build_id'] = 'form-' . drupal_random_key();
					$form['form_build_id']['#value'] = $form['#build_id'];
					$form['form_build_id']['#id'] = $form['#build_id'];
					unset($form_state['build_info']['immutable']);
				}
				return $form;
			}
		}
	}

	/**
	 * Stores a form in the cache.
	 */
	public static function form_set_cache($form_build_id, $form, $form_state) {
		// TODO WP
		return NULL;

		// 6 hours cache life time for forms should be plenty.
		$expire = 21600;

		// Ensure that the form build_id embedded in the form structure is the same as
		// the one passed in as a parameter. This is an additional safety measure to
		// prevent legacy code operating directly with form_get_cache and
		// form_set_cache from accidentally overwriting immutable form state.
		if ($form['#build_id'] != $form_build_id) {
			watchdog('form', 'Form build-id mismatch detected while attempting to store a form in the cache.', array(), WATCHDOG_ERROR);
			return;
		}

		// Cache form structure.
		if (isset($form)) {
			if ($GLOBALS['user']->uid) {
				$form['#cache_token'] = drupal_get_token();
			}
			unset($form['#build_id_old']);
			cache_set('form_' . $form_build_id, $form, 'cache_form', REQUEST_TIME + $expire);
		}

		// Cache form state.
		if (variable_get('cache', 0) && drupal_page_is_cacheable()) {
			$form_state['build_info']['immutable'] = TRUE;
		}
		if ($data = array_diff_key($form_state, array_flip(form_state_keys_no_cache()))) {
			cache_set('form_state_' . $form_build_id, $data, 'cache_form', REQUEST_TIME + $expire);
		}
	}

	/**
	 * Returns an array of $form_state keys that shouldn't be cached.
	 */
	public static function form_state_keys_no_cache() {
		return array(
			// Public properties defined by form constructors and form handlers.
			'always_process',
			'must_validate',
			'rebuild',
			'rebuild_info',
			'redirect',
			'no_redirect',
			'temporary',
			// Internal properties defined by form processing.
			'buttons',
			'triggering_element',
			'clicked_button',
			'complete form',
			'groups',
			'input',
			'method',
			'submit_handlers',
			'submitted',
			'executed',
			'validate_handlers',
			'values',
		);
	}

	/**
	 * Ensures an include file is loaded whenever the form is processed.
	 *
	 * Example:
	 * @code
	 *   // Load node.admin.inc from Node module.
	 *   form_load_include($form_state, 'inc', 'node', 'node.admin');
	 * @endcode
	 *
	 * Use this function instead of module_load_include() from inside a form
	 * constructor or any form processing logic as it ensures that the include file
	 * is loaded whenever the form is processed. In contrast to using
	 * module_load_include() directly, form_load_include() makes sure the include
	 * file is correctly loaded also if the form is cached.
	 *
	 * @param $form_state
	 *   The current state of the form.
	 * @param $type
	 *   The include file's type (file extension).
	 * @param $module
	 *   The module to which the include file belongs.
	 * @param $name
	 *   (optional) The base file name (without the $type extension). If omitted,
	 *   $module is used; i.e., resulting in "$module.$type" by default.
	 *
	 * @return
	 *   The filepath of the loaded include file, or FALSE if the include file was
	 *   not found or has been loaded already.
	 *
	 * @see module_load_include()
	 */
	function form_load_include(&$form_state, $type, $module, $name = NULL) {
		if (!isset($name)) {
			$name = $module;
		}
		if (!isset($form_state['build_info']['files']["$module:$name.$type"])) {
			// Only add successfully included files to the form state.
			if ($result = module_load_include($type, $module, $name)) {
				$form_state['build_info']['files']["$module:$name.$type"] = array(
					'type' => $type,
					'module' => $module,
					'name' => $name,
				);
				return $result;
			}
		}
		return FALSE;
	}


	/**
	 * Retrieves, populates, and processes a form.
	 *
	 * This function allows you to supply values for form elements and submit a
	 * form for processing. Compare to drupal_get_form(), which also builds and
	 * processes a form, but does not allow you to supply values.
	 *
	 * There is no return value, but you can check to see if there are errors
	 * by calling form_get_errors().
	 *
	 * @param $form_id
	 *   The unique string identifying the desired form. If a function
	 *   with that name exists, it is called to build the form array.
	 *   Modules that need to generate the same form (or very similar forms)
	 *   using different $form_ids can implement hook_forms(), which maps
	 *   different $form_id values to the proper form constructor function. Examples
	 *   may be found in node_forms() and search_forms().
	 * @param $form_state
	 *   A keyed array containing the current state of the form. Most important is
	 *   the $form_state['values'] collection, a tree of data used to simulate the
	 *   incoming $_POST information from a user's form submission. If a key is not
	 *   filled in $form_state['values'], then the default value of the respective
	 *   element is used. To submit an unchecked checkbox or other control that
	 *   browsers submit by not having a $_POST entry, include the key, but set the
	 *   value to NULL.
	 * @param ...
	 *   Any additional arguments are passed on to the functions called by
	 *   drupal_form_submit(), including the unique form constructor function.
	 *   For example, the node_edit form requires that a node object be passed
	 *   in here when it is called. Arguments that need to be passed by reference
	 *   should not be included here, but rather placed directly in the $form_state
	 *   build info array so that the reference can be preserved. For example, a
	 *   form builder function with the following signature:
	 *   @code
	 *   function mymodule_form($form, &$form_state, &$object) {
	 *   }
	 *   @endcode
	 *   would be called via drupal_form_submit() as follows:
	 *   @code
	 *   $form_state['values'] = $my_form_values;
	 *   $form_state['build_info']['args'] = array(&$object);
	 *   drupal_form_submit('mymodule_form', $form_state);
	 *   @endcode
	 * For example:
	 * @code
	 * // register a new user
	 * $form_state = array();
	 * $form_state['values']['name'] = 'robo-user';
	 * $form_state['values']['mail'] = 'robouser@example.com';
	 * $form_state['values']['pass']['pass1'] = 'password';
	 * $form_state['values']['pass']['pass2'] = 'password';
	 * $form_state['values']['op'] = t('Create new account');
	 * drupal_form_submit('user_register_form', $form_state);
	 * @endcode
	 */
	public static function drupal_form_submit($form_id, &$form_state) {
		if (!isset($form_state['build_info']['args'])) {
			$args = func_get_args();
			array_shift($args);
			array_shift($args);
			$form_state['build_info']['args'] = $args;
		}
		// Merge in default values.
		$form_state += self::form_state_defaults();

		// Populate $form_state['input'] with the submitted values before retrieving
		// the form, to be consistent with what drupal_build_form() does for
		// non-programmatic submissions (form builder functions may expect it to be
		// there).
		$form_state['input'] = $form_state['values'];

		$form_state['programmed'] = TRUE;
		$form = drupal_retrieve_form($form_id, $form_state);
		// Programmed forms are always submitted.
		$form_state['submitted'] = TRUE;

		// Reset form validation.
		$form_state['must_validate'] = TRUE;
		self::form_clear_error();

		self::drupal_prepare_form($form_id, $form, $form_state);
		self::drupal_process_form($form_id, $form, $form_state);
	}

	public static function drupal_retrieve_form($form_id, &$form_state) {
		$forms = &Intel_Df::drupal_static(__FUNCTION__, array());

		// Record the $form_id.
		$form_state['build_info']['form_id'] = $form_id;

		// We save two copies of the incoming arguments: one for modules to use
		// when mapping form ids to constructor functions, and another to pass to
		// the constructor function itself.
		$args = $form_state['build_info']['args'];

		// We first check to see if there's a function named after the $form_id.
		// If there is, we simply pass the arguments on to it to get the form.
		//if (!function_exists($form_id)) {
		if (!is_callable($form_id)) {
			// In cases where many form_ids need to share a central constructor function,
			// such as the node editing form, modules can implement hook_forms(). It
			// maps one or more form_ids to the correct constructor functions.
			//
			// We cache the results of that hook to save time, but that only works
			// for modules that know all their form_ids in advance. (A module that
			// adds a small 'rate this comment' form to each comment in a list
			// would need a unique form_id for each one, for example.)
			//
			// So, we call the hook if $forms isn't yet populated, OR if it doesn't
			// yet have an entry for the requested form_id.
			if (!isset($forms) || !isset($forms[$form_id])) {
				//$forms = module_invoke_all('forms', $form_id, $args);
			}
			$form_definition = $forms[$form_id];
			if (isset($form_definition['callback arguments'])) {
				$args = array_merge($form_definition['callback arguments'], $args);
			}
			if (isset($form_definition['callback'])) {
				$callback = $form_definition['callback'];
				$form_state['build_info']['base_form_id'] = isset($form_definition['base_form_id']) ? $form_definition['base_form_id'] : $callback;
			}
			// In case $form_state['wrapper_callback'] is not defined already, we also
			// allow hook_forms() to define one.
			if (!isset($form_state['wrapper_callback']) && isset($form_definition['wrapper_callback'])) {
				$form_state['wrapper_callback'] = $form_definition['wrapper_callback'];
			}
		}

		$form = array();
		// We need to pass $form_state by reference in order for forms to modify it,
		// since call_user_func_array() requires that referenced variables are passed
		// explicitly.
		$args = array_merge(array($form, &$form_state), $args);

		// When the passed $form_state (not using drupal_get_form()) defines a
		// 'wrapper_callback', then it requests to invoke a separate (wrapping) form
		// builder function to pre-populate the $form array with form elements, which
		// the actual form builder function ($callback) expects. This allows for
		// pre-populating a form with common elements for certain forms, such as
		// back/next/save buttons in multi-step form wizards. See drupal_build_form().
		if (isset($form_state['wrapper_callback']) && is_callable($form_state['wrapper_callback'])) {
			$form = call_user_func_array($form_state['wrapper_callback'], $args);
			// Put the prepopulated $form into $args.
			$args[0] = $form;
		}

		// If $callback was returned by a hook_forms() implementation, call it.
		// Otherwise, call the function named after the form id.
		$form = call_user_func_array(isset($callback) ? $callback : $form_id, $args);
		$form['#form_id'] = $form_id;

		return $form;
	}

	/**
	 * Processes a form submission.
	 *
	 * This function is the heart of form API. The form gets built, validated and in
	 * appropriate cases, submitted and rebuilt.
	 *
	 * @param $form_id
	 *   The unique string identifying the current form.
	 * @param $form
	 *   An associative array containing the structure of the form.
	 * @param $form_state
	 *   A keyed array containing the current state of the form. This
	 *   includes the current persistent storage data for the form, and
	 *   any data passed along by earlier steps when displaying a
	 *   multi-step form. Additional information, like the sanitized $_POST
	 *   data, is also accumulated here.
	 */
	public static function drupal_process_form($form_id, &$form, &$form_state) {
		$form_state['values'] = array();

		// With $_GET, these forms are always submitted if requested.
		if ($form_state['method'] == 'get' && !empty($form_state['always_process'])) {
			if (!isset($form_state['input']['form_build_id'])) {
				$form_state['input']['form_build_id'] = $form['#build_id'];
			}
			if (!isset($form_state['input']['form_id'])) {
				$form_state['input']['form_id'] = $form_id;
			}
			if (!isset($form_state['input']['form_token']) && isset($form['#token'])) {
				$form_state['input']['form_token'] = drupal_get_token($form['#token']);
			}
		}

		// form_builder() finishes building the form by calling element #process
		// functions and mapping user input, if any, to #value properties, and also
		// storing the values in $form_state['values']. We need to retain the
		// unprocessed $form in case it needs to be cached.
		$unprocessed_form = $form;
		$form = self::form_builder($form_id, $form, $form_state);

		// Only process the input if we have a correct form submission.
		if ($form_state['process_input']) {
			self::drupal_validate_form($form_id, $form, $form_state);

			// drupal_html_id() maintains a cache of element IDs it has seen,
			// so it can prevent duplicates. We want to be sure we reset that
			// cache when a form is processed, so scenarios that result in
			// the form being built behind the scenes and again for the
			// browser don't increment all the element IDs needlessly.
			if (!self::form_get_errors()) {
				// In case of errors, do not break HTML IDs of other forms.
				Intel_Df::drupal_static_reset('drupal_html_id');
			}

			if ($form_state['submitted'] && !self::form_get_errors() && !$form_state['rebuild']) {
					// Execute form submit handlers.
				self::form_execute_handlers('submit', $form, $form_state);

				// We'll clear out the cached copies of the form and its stored data
				// here, as we've finished with them. The in-memory copies are still
				// here, though.
				if (0 && !get_option('cache', 0) && !empty($form_state['values']['form_build_id'])) {
					cache_clear_all('form_' . $form_state['values']['form_build_id'], 'cache_form');
					cache_clear_all('form_state_' . $form_state['values']['form_build_id'], 'cache_form');
				}

				// If batches were set in the submit handlers, we process them now,
				// possibly ending execution. We make sure we do not react to the batch
				// that is already being processed (if a batch operation performs a
				// drupal_form_submit).
				// DWP - no batch support
				if (0 && $batch =& batch_get() && !isset($batch['current_set'])) {
					// Store $form_state information in the batch definition.
					// We need the full $form_state when either:
					// - Some submit handlers were saved to be called during batch
					//   processing. See form_execute_handlers().
					// - The form is multistep.
					// In other cases, we only need the information expected by
					// drupal_redirect_form().
					if ($batch['has_form_submits'] || !empty($form_state['rebuild'])) {
						$batch['form_state'] = $form_state;
					}
					else {
						$batch['form_state'] = array_intersect_key($form_state, array_flip(array('programmed', 'rebuild', 'storage', 'no_redirect', 'redirect')));
					}

					$batch['progressive'] = !$form_state['programmed'];
					batch_process();

					// Execution continues only for programmatic forms.
					// For 'regular' forms, we get redirected to the batch processing
					// page. Form redirection will be handled in _batch_finished(),
					// after the batch is processed.
				}

				// Set a flag to indicate that the form has been processed and executed.
				$form_state['executed'] = TRUE;

				// Redirect the form based on values in $form_state.
				self::drupal_redirect_form($form_state);
			}

			// Don't rebuild or cache form submissions invoked via drupal_form_submit().
			if (!empty($form_state['programmed'])) {
				return;
			}

			// If $form_state['rebuild'] has been set and input has been processed
			// without validation errors, we are in a multi-step workflow that is not
			// yet complete. A new $form needs to be constructed based on the changes
			// made to $form_state during this request. Normally, a submit handler sets
			// $form_state['rebuild'] if a fully executed form requires another step.
			// However, for forms that have not been fully executed (e.g., Ajax
			// submissions triggered by non-buttons), there is no submit handler to set
			// $form_state['rebuild']. It would not make sense to redisplay the
			// identical form without an error for the user to correct, so we also
			// rebuild error-free non-executed forms, regardless of
			// $form_state['rebuild'].
			// @todo D8: Simplify this logic; considering Ajax and non-HTML front-ends,
			//   along with element-level #submit properties, it makes no sense to have
			//   divergent form execution based on whether the triggering element has
			//   #executes_submit_callback set to TRUE.
			if (($form_state['rebuild'] || !$form_state['executed']) && !self::form_get_errors()) {
				// Form building functions (e.g., _form_builder_handle_input_element())
				// may use $form_state['rebuild'] to determine if they are running in the
				// context of a rebuild, so ensure it is set.
				$form_state['rebuild'] = TRUE;
				$form = self::drupal_rebuild_form($form_id, $form_state, $form);
			}
		}

		// After processing the form, the form builder or a #process callback may
		// have set $form_state['cache'] to indicate that the form and form state
		// shall be cached. But the form may only be cached if the 'no_cache' property
		// is not set to TRUE. Only cache $form as it was prior to form_builder(),
		// because form_builder() must run for each request to accommodate new user
		// input. Rebuilt forms are not cached here, because drupal_rebuild_form()
		// already takes care of that.
		if (!$form_state['rebuild'] && $form_state['cache'] && empty($form_state['no_cache'])) {
			form_set_cache($form['#build_id'], $unprocessed_form, $form_state);
		}
	}

	/**
	 * Prepares a structured form array.
	 *
	 * Adds required elements, executes any hook_form_alter functions, and
	 * optionally inserts a validation token to prevent tampering.
	 *
	 * @param $form_id
	 *   A unique string identifying the form for validation, submission,
	 *   theming, and hook_form_alter functions.
	 * @param $form
	 *   An associative array containing the structure of the form.
	 * @param $form_state
	 *   A keyed array containing the current state of the form. Passed
	 *   in here so that hook_form_alter() calls can use it, as well.
	 */
	public static function drupal_prepare_form($form_id, &$form, &$form_state) {
		$user = wp_get_current_user();

		$form['#type'] = 'form';
		$form_state['programmed'] = isset($form_state['programmed']) ? $form_state['programmed'] : FALSE;

		// Fix the form method, if it is 'get' in $form_state, but not in $form.
		if ($form_state['method'] == 'get' && !isset($form['#method'])) {
			$form['#method'] = 'get';
		}

		// Generate a new #build_id for this form, if none has been set already. The
		// form_build_id is used as key to cache a particular build of the form. For
		// multi-step forms, this allows the user to go back to an earlier build, make
		// changes, and re-submit.
		// @see drupal_build_form()
		// @see drupal_rebuild_form()
		if (!isset($form['#build_id'])) {
			//$form['#build_id'] = 'form-' . drupal_random_key(); // TODO WP
			$form['#build_id'] = 'form-' . rand(0, 1000);
		}
		$form['form_build_id'] = array(
			'#type' => 'hidden',
			'#value' => $form['#build_id'],
			'#id' => $form['#build_id'],
			'#name' => 'form_build_id',
			// Form processing and validation requires this value, so ensure the
			// submitted form value appears literally, regardless of custom #tree
			// and #parents being set elsewhere.
			'#parents' => array('form_build_id'),
		);

		// Add a token, based on either #token or form_id, to any form displayed to
		// authenticated users. This ensures that any submitted form was actually
		// requested previously by the user and protects against cross site request
		// forgeries.
		// This does not apply to programmatically submitted forms. Furthermore, since
		// tokens are session-bound and forms displayed to anonymous users are very
		// likely cached, we cannot assign a token for them.
		// During installation, there is no $user yet.
		$nonce = wp_create_nonce($form_id);
		if (!empty($user->ID) && !$form_state['programmed']) {
			// Form constructors may explicitly set #token to FALSE when cross site
			// request forgery is irrelevant to the form, such as search forms.
			if (isset($form['#token']) && $form['#token'] === FALSE) {
				unset($form['#token']);
			}
			// Otherwise, generate a public token based on the form id.
			else {
				$form['#token'] = $form_id;
				$form['form_token'] = array(
					//'#id' => drupal_html_id('edit-' . $form_id . '-form-token'),
					'#id' => 'edit-' . $form_id . '-form-token',
					//'#type' => 'token',
					'#type' => 'hidden',
					//'#default_value' => drupal_get_token($form['#token']),
					'#default_value' => $nonce,
					// Form processing and validation requires this value, so ensure the
					// submitted form value appears literally, regardless of custom #tree
					// and #parents being set elsewhere.
					'#parents' => array('form_token'),
				);
			}
		}
		if (isset($form_id)) {
			$form['form_id'] = array(
				'#type' => 'hidden',
				'#value' => $form_id,
				//'#id' => drupal_html_id("edit-$form_id"),
				'#id' => "edit-$form_id",
				// Form processing and validation requires this value, so ensure the
				// submitted form value appears literally, regardless of custom #tree
				// and #parents being set elsewhere.
				'#parents' => array('form_id'),
			);
		}
		if (!isset($form['#id'])) {
			//$form['#id'] = drupal_html_id($form_id);
			$form['#id'] = $form_id;
		}

		$form += intel()->element_info('form');
		$form += array('#tree' => FALSE, '#parents' => array());

		if (!isset($form['#validate'])) {
			// Ensure that modules can rely on #validate being set.
			$form['#validate'] = array();
			// Check for a handler specific to $form_id.
			//if (function_exists($form_id . '_validate')) {
			if (is_callable($form_id . '_validate')) {
				$form['#validate'][] = $form_id . '_validate';
			}
			// Otherwise check whether this is a shared form and whether there is a
			// handler for the shared $form_id.
			//elseif (isset($form_state['build_info']['base_form_id']) && function_exists($form_state['build_info']['base_form_id'] . '_validate')) {
			elseif (isset($form_state['build_info']['base_form_id']) && is_callable($form_state['build_info']['base_form_id'] . '_validate')) {
				$form['#validate'][] = $form_state['build_info']['base_form_id'] . '_validate';
			}
		}

		if (!isset($form['#submit'])) {
			// Ensure that modules can rely on #submit being set.
			$form['#submit'] = array();
			// Check for a handler specific to $form_id.
			//if (function_exists($form_id . '_submit')) {
			if (is_callable($form_id . '_submit')) {
				$form['#submit'][] = $form_id . '_submit';

			}
			// Otherwise check whether this is a shared form and whether there is a
			// handler for the shared $form_id.
			//elseif (isset($form_state['build_info']['base_form_id']) && function_exists($form_state['build_info']['base_form_id'] . '_submit')) {
			elseif (isset($form_state['build_info']['base_form_id']) && is_callable($form_state['build_info']['base_form_id'] . '_submit')) {
				$form['#submit'][] = $form_state['build_info']['base_form_id'] . '_submit';
			}
		}

		// If no #theme has been set, automatically apply theme suggestions.
		// theme_form() itself is in #theme_wrappers and not #theme. Therefore, the
		// #theme function only has to care for rendering the inner form elements,
		// not the form itself.
		if (!isset($form['#theme'])) {
			$form['#theme'] = array($form_id);
			if (isset($form_state['build_info']['base_form_id'])) {
				$form['#theme'][] = $form_state['build_info']['base_form_id'];
			}
		}

		$form['intel_form'] = array(
			'#type' => 'hidden',
			'#value' => 1,
			'#name' => 'intel_form',
		);

		// Invoke hook_form_alter(), hook_form_BASE_FORM_ID_alter(), and
		// hook_form_FORM_ID_alter() implementations.

		do_action_ref_array( 'intel_form_alter', array(&$form, &$form_state, $form_id) );

		if (isset($form_state['build_info']['base_form_id'])) {
			//$hooks[] = 'form_' . $form_state['build_info']['base_form_id'];
			//apply_filters('intel_form_' . $form_state['build_info']['base_form_id'] . '_alter', $form, $form_state, $form_id);
			do_action_ref_array( 'intel_form_' . $form_state['build_info']['base_form_id'] . '_alter', array(&$form, &$form_state, $form_id) );
		}
		//$hooks[] = 'form_' . $form_id . '_alter';
		do_action_ref_array('intel_form_' . $form_id . '_alter', array(&$form, &$form_state, $form_id) );
	}

	/**
	 * Helper function to call form_set_error() if there is a token error.
	 */
	public static function _drupal_invalid_token_set_form_error() {
		$path = ''; // TODO WP current_path();
		$query = $_GET; // TODO WP drupal_get_query_parameters();
		$url = Intel_Df::url($path, array('query' => $query));

		// Setting this error will cause the form to fail validation.
		Intel_Form::form_set_error('form_token', Intel_Df::t('The form has become outdated. Copy any unsaved work in the form below and then <a href="@link">reload this page</a>.', array('@link' => $url)));
	}

	/**
	 * Validates user-submitted form data in the $form_state array.
	 *
	 * @param $form_id
	 *   A unique string identifying the form for validation, submission,
	 *   theming, and hook_form_alter functions.
	 * @param $form
	 *   An associative array containing the structure of the form, which is passed
	 *   by reference. Form validation handlers are able to alter the form structure
	 *   (like #process and #after_build callbacks during form building) in case of
	 *   a validation error. If a validation handler alters the form structure, it
	 *   is responsible for validating the values of changed form elements in
	 *   $form_state['values'] to prevent form submit handlers from receiving
	 *   unvalidated values.
	 * @param $form_state
	 *   A keyed array containing the current state of the form. The current
	 *   user-submitted data is stored in $form_state['values'], though
	 *   form validation functions are passed an explicit copy of the
	 *   values for the sake of simplicity. Validation handlers can also use
	 *   $form_state to pass information on to submit handlers. For example:
	 *     $form_state['data_for_submission'] = $data;
	 *   This technique is useful when validation requires file parsing,
	 *   web service requests, or other expensive requests that should
	 *   not be repeated in the submission step.
	 */
	public static function drupal_validate_form($form_id, &$form, &$form_state) {
		$validated_forms = &Intel_Df::drupal_static(__FUNCTION__, array());

		if (isset($validated_forms[$form_id]) && empty($form_state['must_validate'])) {
			return;
		}

		// If the session token was set by drupal_prepare_form(), ensure that it
		// matches the current user's session. This is duplicate to code in
		// form_builder() but left to protect any custom form handling code.
		if (isset($form['#token'])) {
			//if (!drupal_valid_token($form_state['values']['form_token'], $form['#token']) || !empty($form_state['invalid_token'])) {
			if (!wp_verify_nonce($form_state['values']['form_token'], $form['#token']) || !empty($form_state['invalid_token'])) {
				self::_drupal_invalid_token_set_form_error();
				// Stop here and don't run any further validation handlers, because they
				// could invoke non-safe operations which opens the door for CSRF
				// vulnerabilities.
				$validated_forms[$form_id] = TRUE;
				return;
			}
		}

		self::_form_validate($form, $form_state, $form_id);
		$validated_forms[$form_id] = TRUE;

		// If validation errors are limited then remove any non validated form values,
		// so that only values that passed validation are left for submit callbacks.
		if (isset($form_state['triggering_element']['#limit_validation_errors']) && $form_state['triggering_element']['#limit_validation_errors'] !== FALSE) {
			$values = array();
			foreach ($form_state['triggering_element']['#limit_validation_errors'] as $section) {
				// If the section exists within $form_state['values'], even if the value
				// is NULL, copy it to $values.
				$section_exists = NULL;
				$value = Intel_Df::drupal_array_get_nested_value($form_state['values'], $section, $section_exists);
				if ($section_exists) {
					Intel_Df::drupal_array_set_nested_value($values, $section, $value);
				}
			}
			// A button's #value does not require validation, so for convenience we
			// allow the value of the clicked button to be retained in its normal
			// $form_state['values'] locations, even if these locations are not included
			// in #limit_validation_errors.
			if (isset($form_state['triggering_element']['#button_type'])) {
				$button_value = $form_state['triggering_element']['#value'];

				// Like all input controls, the button value may be in the location
				// dictated by #parents. If it is, copy it to $values, but do not override
				// what may already be in $values.
				$parents = $form_state['triggering_element']['#parents'];
				if (!Intel_Df::drupal_array_nested_key_exists($values, $parents) && Intel_Df::drupal_array_get_nested_value($form_state['values'], $parents) === $button_value) {
					Intel_Df::drupal_array_set_nested_value($values, $parents, $button_value);
				}

				// Additionally, form_builder() places the button value in
				// $form_state['values'][BUTTON_NAME]. If it's still there, after
				// validation handlers have run, copy it to $values, but do not override
				// what may already be in $values.
				$name = $form_state['triggering_element']['#name'];
				if (!isset($values[$name]) && isset($form_state['values'][$name]) && $form_state['values'][$name] === $button_value) {
					$values[$name] = $button_value;
				}
			}
			$form_state['values'] = $values;
		}
	}

	/**
	 * Redirects the user to a URL after a form has been processed.
	 *
	 * After a form is submitted and processed, normally the user should be
	 * redirected to a new destination page. This function figures out what that
	 * destination should be, based on the $form_state array and the 'destination'
	 * query string in the request URL, and redirects the user there.
	 *
	 * Usually (for exceptions, see below) $form_state['redirect'] determines where
	 * to redirect the user. This can be set either to a string (the path to
	 * redirect to), or an array of arguments for drupal_goto(). If
	 * $form_state['redirect'] is missing, the user is usually (again, see below for
	 * exceptions) redirected back to the page they came from, where they should see
	 * a fresh, unpopulated copy of the form.
	 *
	 * Here is an example of how to set up a form to redirect to the path 'node':
	 * @code
	 * $form_state['redirect'] = 'node';
	 * @endcode
	 * And here is an example of how to redirect to 'node/123?foo=bar#baz':
	 * @code
	 * $form_state['redirect'] = array(
	 *   'node/123',
	 *   array(
	 *     'query' => array(
	 *       'foo' => 'bar',
	 *     ),
	 *     'fragment' => 'baz',
	 *   ),
	 * );
	 * @endcode
	 *
	 * There are several exceptions to the "usual" behavior described above:
	 * - If $form_state['programmed'] is TRUE, the form submission was usually
	 *   invoked via drupal_form_submit(), so any redirection would break the script
	 *   that invoked drupal_form_submit() and no redirection is done.
	 * - If $form_state['rebuild'] is TRUE, the form is being rebuilt, and no
	 *   redirection is done.
	 * - If $form_state['no_redirect'] is TRUE, redirection is disabled. This is
	 *   set, for instance, by ajax_get_form() to prevent redirection in Ajax
	 *   callbacks. $form_state['no_redirect'] should never be set or altered by
	 *   form builder functions or form validation/submit handlers.
	 * - If $form_state['redirect'] is set to FALSE, redirection is disabled.
	 * - If none of the above conditions has prevented redirection, then the
	 *   redirect is accomplished by calling drupal_goto(), passing in the value of
	 *   $form_state['redirect'] if it is set, or the current path if it is
	 *   not. drupal_goto() preferentially uses the value of $_GET['destination']
	 *   (the 'destination' URL query string) if it is present, so this will
	 *   override any values set by $form_state['redirect']. Note that during
	 *   installation, install_goto() is called in place of drupal_goto().
	 *
	 * @param $form_state
	 *   An associative array containing the current state of the form.
	 *
	 * @see drupal_process_form()
	 * @see drupal_build_form()
	 */
	public static function drupal_redirect_form($form_state) {
		// if form is on front side of WP, don't do redirect
		if (!is_admin()) {
			$form_state['no_redirect'] = TRUE;
		}
		// Skip redirection for form submissions invoked via drupal_form_submit().
		if (!empty($form_state['programmed'])) {
			return;
		}
		// Skip redirection if rebuild is activated.
		if (!empty($form_state['rebuild'])) {
			return;
		}
		// Skip redirection if it was explicitly disallowed.
		if (!empty($form_state['no_redirect'])) {
			return;
		}
		// Only invoke drupal_goto() if redirect value was not set to FALSE.
		if (!isset($form_state['redirect']) || $form_state['redirect'] !== FALSE) {
			if (isset($form_state['redirect'])) {
				if (is_array($form_state['redirect'])) {
					call_user_func('Intel_Df::drupal_goto', $form_state['redirect']);
				}
				else {
					// This function can be called from the installer, which guarantees
					// that $redirect will always be a string, so catch that case here
					// and use the appropriate redirect function.
					//$function = drupal_installation_attempted() ? 'install_goto' : 'drupal_goto';
					$function = 'Intel_Df::drupal_goto';
					$function($form_state['redirect']);
				}
			}
			// if an intel_admin page use Intel_Df::current_path() for default redirect
			if (intel()->is_intel_admin_page()) {
				Intel_Df::drupal_goto(Intel_Df::current_path(), array('query' => Intel_Df::drupal_get_query_parameters()));
			}
			else {
				// non intel path, use standard wp_redirect to current page
				$url = "//" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
				wp_redirect( $url );
				exit;
			}

		}
	}

	/**
	 * Performs validation on form elements.
	 *
	 * First ensures required fields are completed, #maxlength is not exceeded, and
	 * selected options were in the list of options given to the user. Then calls
	 * user-defined validators.
	 *
	 * @param $elements
	 *   An associative array containing the structure of the form.
	 * @param $form_state
	 *   A keyed array containing the current state of the form. The current
	 *   user-submitted data is stored in $form_state['values'], though
	 *   form validation functions are passed an explicit copy of the
	 *   values for the sake of simplicity. Validation handlers can also
	 *   $form_state to pass information on to submit handlers. For example:
	 *     $form_state['data_for_submission'] = $data;
	 *   This technique is useful when validation requires file parsing,
	 *   web service requests, or other expensive requests that should
	 *   not be repeated in the submission step.
	 * @param $form_id
	 *   A unique string identifying the form for validation, submission,
	 *   theming, and hook_form_alter functions.
	 */
	public static function _form_validate(&$elements, &$form_state, $form_id = NULL) {
		// Also used in the installer, pre-database setup.
		//$t = get_t();
		$t = 'Intel_Df::t';

		// Recurse through all children.
		foreach (Intel_Df::element_children($elements) as $key) {
			if (isset($elements[$key]) && $elements[$key]) {
				self::_form_validate($elements[$key], $form_state);
			}
		}

		// Validate the current input.
		if (!isset($elements['#validated']) || !$elements['#validated']) {
			// The following errors are always shown.
			if (isset($elements['#needs_validation'])) {
				// Verify that the value is not longer than #maxlength.
				if (isset($elements['#maxlength']) && strlen($elements['#value']) > $elements['#maxlength']) {
					form_error($elements, $t('!name cannot be longer than %max characters but is currently %length characters long.', array('!name' => empty($elements['#title']) ? $elements['#parents'][0] : $elements['#title'], '%max' => $elements['#maxlength'], '%length' => drupal_strlen($elements['#value']))));
				}

				if (isset($elements['#options']) && isset($elements['#value'])) {
					if ($elements['#type'] == 'select') {
						$options = self::form_options_flatten($elements['#options']);
					}
					else {
						$options = $elements['#options'];
					}
					if (is_array($elements['#value'])) {
						$value = in_array($elements['#type'], array('checkboxes', 'tableselect')) ? array_keys($elements['#value']) : $elements['#value'];
						foreach ($value as $v) {
							if (!isset($options[$v])) {
								self::form_error($elements, call_user_func($t, 'An illegal choice has been detected. Please contact the site administrator.'));
								Intel_Df::watchdog('form', 'Illegal choice %choice in !name element.', array('%choice' => $v, '!name' => empty($elements['#title']) ? $elements['#parents'][0] : $elements['#title']), Intel_Df::WATCHDOG_ERROR);
							}
						}
					}
					// Non-multiple select fields always have a value in HTML. If the user
					// does not change the form, it will be the value of the first option.
					// Because of this, form validation for the field will almost always
					// pass, even if the user did not select anything. To work around this
					// browser behavior, required select fields without a #default_value get
					// an additional, first empty option. In case the submitted value is
					// identical to the empty option's value, we reset the element's value
					// to NULL to trigger the regular #required handling below.
					// @see form_process_select()
					elseif ($elements['#type'] == 'select' && !$elements['#multiple'] && $elements['#required'] && !isset($elements['#default_value']) && $elements['#value'] === $elements['#empty_value']) {
						$elements['#value'] = NULL;
						self::form_set_value($elements, NULL, $form_state);
					}
					elseif (!isset($options[$elements['#value']])) {
						self::form_error($elements, call_user_func($t, 'An illegal choice has been detected. Please contact the site administrator.'));
						Intel_Df::watchdog('form', 'Illegal choice %choice in %name element.', array('%choice' => $elements['#value'], '%name' => empty($elements['#title']) ? $elements['#parents'][0] : $elements['#title']), Intel_Df::WATCHDOG_ERROR);
					}
				}
			}

			// While this element is being validated, it may be desired that some calls
			// to form_set_error() be suppressed and not result in a form error, so
			// that a button that implements low-risk functionality (such as "Previous"
			// or "Add more") that doesn't require all user input to be valid can still
			// have its submit handlers triggered. The triggering element's
			// #limit_validation_errors property contains the information for which
			// errors are needed, and all other errors are to be suppressed. The
			// #limit_validation_errors property is ignored if submit handlers will run,
			// but the element doesn't have a #submit property, because it's too large a
			// security risk to have any invalid user input when executing form-level
			// submit handlers.
			if (isset($form_state['triggering_element']['#limit_validation_errors']) && ($form_state['triggering_element']['#limit_validation_errors'] !== FALSE) && !($form_state['submitted'] && !isset($form_state['triggering_element']['#submit']))) {
				self::form_set_error(NULL, '', $form_state['triggering_element']['#limit_validation_errors']);
			}
			// If submit handlers won't run (due to the submission having been triggered
			// by an element whose #executes_submit_callback property isn't TRUE), then
			// it's safe to suppress all validation errors, and we do so by default,
			// which is particularly useful during an Ajax submission triggered by a
			// non-button. An element can override this default by setting the
			// #limit_validation_errors property. For button element types,
			// #limit_validation_errors defaults to FALSE (via system_element_info()),
			// so that full validation is their default behavior.
			elseif (isset($form_state['triggering_element']) && !isset($form_state['triggering_element']['#limit_validation_errors']) && !$form_state['submitted']) {
				self::form_set_error(NULL, '', array());
			}
			// As an extra security measure, explicitly turn off error suppression if
			// one of the above conditions wasn't met. Since this is also done at the
			// end of this function, doing it here is only to handle the rare edge case
			// where a validate handler invokes form processing of another form.
			else {
				Intel_Df::drupal_static_reset('form_set_error:limit_validation_errors');
			}

			// Make sure a value is passed when the field is required.
			if (isset($elements['#needs_validation']) && $elements['#required']) {
				// A simple call to empty() will not cut it here as some fields, like
				// checkboxes, can return a valid value of '0'. Instead, check the
				// length if it's a string, and the item count if it's an array.
				// An unchecked checkbox has a #value of integer 0, different than string
				// '0', which could be a valid value.
				$is_empty_multiple = (is_array($elements['#value']) && !count($elements['#value']));
				$is_empty_string = (is_string($elements['#value']) && strlen(trim($elements['#value'])) == 0);
				$is_empty_value = ($elements['#value'] === 0);
				if ($is_empty_multiple || $is_empty_string || $is_empty_value) {
					// Although discouraged, a #title is not mandatory for form elements. In
					// case there is no #title, we cannot set a form error message.
					// Instead of setting no #title, form constructors are encouraged to set
					// #title_display to 'invisible' to improve accessibility.
					if (isset($elements['#title'])) {
						$vars = array(
							'!name field is required.',
							array(
								'!name' => $elements['#title'],
							),
						);
						$msg = call_user_func_array($t, $vars);
						self::form_error($elements, $msg);
						//self::form_error($elements, $t('!name field is required.', array('!name' => $elements['#title'])));
					}
					else {
						self::form_error($elements);
					}
				}
			}

			// Call user-defined form level validators.
			if (isset($form_id)) {
				self::form_execute_handlers('validate', $elements, $form_state);
			}
			// Call any element-specific validators. These must act on the element
			// #value data.
			elseif (isset($elements['#element_validate'])) {
				foreach ($elements['#element_validate'] as $function) {
					//$function($elements, $form_state, $form_state['complete form']);
					if (is_callable($function)) {
						call_user_func_array($function, array($elements, $form_state, $form_state['complete form']));
					}
				}
			}
			$elements['#validated'] = TRUE;
		}

		// Done validating this element, so turn off error suppression.
		// _form_validate() turns it on again when starting on the next element, if
		// it's still appropriate to do so.
		Intel_Df::drupal_static_reset('form_set_error:limit_validation_errors');
	}

	/**
	 * Executes custom validation and submission handlers for a given form.
	 *
	 * Button-specific handlers are checked first. If none exist, the function
	 * falls back to form-level handlers.
	 *
	 * @param $type
	 *   The type of handler to execute. 'validate' or 'submit' are the
	 *   defaults used by Form API.
	 * @param $form
	 *   An associative array containing the structure of the form.
	 * @param $form_state
	 *   A keyed array containing the current state of the form. If the user
	 *   submitted the form by clicking a button with custom handler functions
	 *   defined, those handlers will be stored here.
	 */
	public static function form_execute_handlers($type, &$form, &$form_state) {
		$return = FALSE;
		// If there was a button pressed, use its handlers.
		if (isset($form_state[$type . '_handlers'])) {
			$handlers = $form_state[$type . '_handlers'];
		}
		// Otherwise, check for a form-level handler.
		elseif (isset($form['#' . $type])) {
			$handlers = $form['#' . $type];
		}
		else {
			$handlers = array();
		}

		foreach ($handlers as $function) {
			// Check if a previous _submit handler has set a batch, but make sure we
			// do not react to a batch that is already being processed (for instance
			// if a batch operation performs a drupal_form_submit()).
			// DWP no batch support
			if (0 && $type == 'submit' && ($batch =& batch_get()) && !isset($batch['id'])) {
				// Some previous submit handler has set a batch. To ensure correct
				// execution order, store the call in a special 'control' batch set.
				// See _batch_next_set().
				$batch['sets'][] = array('form_submit' => $function);
				$batch['has_form_submits'] = TRUE;
			}
			else {
				//$function($form, $form_state);
				call_user_func_array($function, array(&$form, &$form_state));
			}
			$return = TRUE;
		}
		return $return;
	}

	/**
	 * Files an error against a form element.
	 *
	 * When a validation error is detected, the validator calls form_set_error() to
	 * indicate which element needs to be changed and provide an error message. This
	 * causes the Form API to not execute the form submit handlers, and instead to
	 * re-display the form to the user with the corresponding elements rendered with
	 * an 'error' CSS class (shown as red by default).
	 *
	 * The standard form_set_error() behavior can be changed if a button provides
	 * the #limit_validation_errors property. Multistep forms not wanting to
	 * validate the whole form can set #limit_validation_errors on buttons to
	 * limit validation errors to only certain elements. For example, pressing the
	 * "Previous" button in a multistep form should not fire validation errors just
	 * because the current step has invalid values. If #limit_validation_errors is
	 * set on a clicked button, the button must also define a #submit property
	 * (may be set to an empty array). Any #submit handlers will be executed even if
	 * there is invalid input, so extreme care should be taken with respect to any
	 * actions taken by them. This is typically not a problem with buttons like
	 * "Previous" or "Add more" that do not invoke persistent storage of the
	 * submitted form values. Do not use the #limit_validation_errors property on
	 * buttons that trigger saving of form values to the database.
	 *
	 * The #limit_validation_errors property is a list of "sections" within
	 * $form_state['values'] that must contain valid values. Each "section" is an
	 * array with the ordered set of keys needed to reach that part of
	 * $form_state['values'] (i.e., the #parents property of the element).
	 *
	 * Example 1: Allow the "Previous" button to function, regardless of whether any
	 * user input is valid.
	 *
	 * @code
	 *   $form['actions']['previous'] = array(
	 *     '#type' => 'submit',
	 *     '#value' => t('Previous'),
	 *     '#limit_validation_errors' => array(),       // No validation.
	 *     '#submit' => array('some_submit_function'),  // #submit required.
	 *   );
	 * @endcode
	 *
	 * Example 2: Require some, but not all, user input to be valid to process the
	 * submission of a "Previous" button.
	 *
	 * @code
	 *   $form['actions']['previous'] = array(
	 *     '#type' => 'submit',
	 *     '#value' => t('Previous'),
	 *     '#limit_validation_errors' => array(
	 *       array('step1'),       // Validate $form_state['values']['step1'].
	 *       array('foo', 'bar'),  // Validate $form_state['values']['foo']['bar'].
	 *     ),
	 *     '#submit' => array('some_submit_function'), // #submit required.
	 *   );
	 * @endcode
	 *
	 * This will require $form_state['values']['step1'] and everything within it
	 * (for example, $form_state['values']['step1']['choice']) to be valid, so
	 * calls to form_set_error('step1', $message) or
	 * form_set_error('step1][choice', $message) will prevent the submit handlers
	 * from running, and result in the error message being displayed to the user.
	 * However, calls to form_set_error('step2', $message) and
	 * form_set_error('step2][groupX][choiceY', $message) will be suppressed,
	 * resulting in the message not being displayed to the user, and the submit
	 * handlers will run despite $form_state['values']['step2'] and
	 * $form_state['values']['step2']['groupX']['choiceY'] containing invalid
	 * values. Errors for an invalid $form_state['values']['foo'] will be
	 * suppressed, but errors flagging invalid values for
	 * $form_state['values']['foo']['bar'] and everything within it will be
	 * flagged and submission prevented.
	 *
	 * Partial form validation is implemented by suppressing errors rather than by
	 * skipping the input processing and validation steps entirely, because some
	 * forms have button-level submit handlers that call Drupal API functions that
	 * assume that certain data exists within $form_state['values'], and while not
	 * doing anything with that data that requires it to be valid, PHP errors
	 * would be triggered if the input processing and validation steps were fully
	 * skipped.
	 *
	 * @param $name
	 *   The name of the form element. If the #parents property of your form
	 *   element is array('foo', 'bar', 'baz') then you may set an error on 'foo'
	 *   or 'foo][bar][baz'. Setting an error on 'foo' sets an error for every
	 *   element where the #parents array starts with 'foo'.
	 * @param $message
	 *   The error message to present to the user.
	 * @param $limit_validation_errors
	 *   Internal use only. The #limit_validation_errors property of the clicked
	 *   button, if it exists.
	 *
	 * @return
	 *   Return value is for internal use only. To get a list of errors, use
	 *   form_get_errors() or form_get_error().
	 *
	 * @see http://drupal.org/node/370537
	 * @see http://drupal.org/node/763376
	 */
	public static function form_set_error($name = NULL, $message = '', $limit_validation_errors = NULL) {
		$form = &Intel_Df::drupal_static(__FUNCTION__, array());
		$sections = &Intel_Df::drupal_static(__FUNCTION__ . ':limit_validation_errors');
		if (isset($limit_validation_errors)) {
			$sections = $limit_validation_errors;
		}

		if (isset($name) && !isset($form[$name])) {
			$record = TRUE;
			if (isset($sections)) {
				// #limit_validation_errors is an array of "sections" within which user
				// input must be valid. If the element is within one of these sections,
				// the error must be recorded. Otherwise, it can be suppressed.
				// #limit_validation_errors can be an empty array, in which case all
				// errors are suppressed. For example, a "Previous" button might want its
				// submit action to be triggered even if none of the submitted values are
				// valid.
				$record = FALSE;
				foreach ($sections as $section) {
					// Exploding by '][' reconstructs the element's #parents. If the
					// reconstructed #parents begin with the same keys as the specified
					// section, then the element's values are within the part of
					// $form_state['values'] that the clicked button requires to be valid,
					// so errors for this element must be recorded. As the exploded array
					// will all be strings, we need to cast every value of the section
					// array to string.
					if (array_slice(explode('][', $name), 0, count($section)) === array_map('strval', $section)) {
						$record = TRUE;
						break;
					}
				}
			}
			if ($record) {
				$form[$name] = $message;
				if ($message) {
					Intel_df::drupal_set_message($message, 'error');
				}
			}
		}

		return $form;
	}

	/**
	 * Clears all errors against all form elements made by form_set_error().
	 */
	public static function form_clear_error() {
		Intel_Df::drupal_static_reset('form_set_error');
	}

	/**
	 * Returns an associative array of all errors.
	 */
	public static function form_get_errors() {
		$form = self::form_set_error();
		if (!empty($form)) {
			return $form;
		}
	}

	/**
	 * Returns the error message filed against the given form element.
	 *
	 * Form errors higher up in the form structure override deeper errors as well as
	 * errors on the element itself.
	 */
	public static function form_get_error($element) {
		$form = self::form_set_error();
		$parents = array();
		foreach ($element['#parents'] as $parent) {
			$parents[] = $parent;
			$key = implode('][', $parents);
			if (isset($form[$key])) {
				return $form[$key];
			}
		}
	}

	/**
	 * Flags an element as having an error.
	 */
	public static function form_error(&$element, $message = '') {
		self::form_set_error(implode('][', $element['#parents']), $message);
	}

	/**
	 * Builds and processes all elements in the structured form array.
	 *
	 * Adds any required properties to each element, maps the incoming input data
	 * to the proper elements, and executes any #process handlers attached to a
	 * specific element.
	 *
	 * This is one of the three primary functions that recursively iterates a form
	 * array. This one does it for completing the form building process. The other
	 * two are _form_validate() (invoked via drupal_validate_form() and used to
	 * invoke validation logic for each element) and drupal_render() (for rendering
	 * each element). Each of these three pipelines provides ample opportunity for
	 * modules to customize what happens. For example, during this function's life
	 * cycle, the following functions get called for each element:
	 * - $element['#value_callback']: A function that implements how user input is
	 *   mapped to an element's #value property. This defaults to a function named
	 *   'form_type_TYPE_value' where TYPE is $element['#type'].
	 * - $element['#process']: An array of functions called after user input has
	 *   been mapped to the element's #value property. These functions can be used
	 *   to dynamically add child elements: for example, for the 'date' element
	 *   type, one of the functions in this array is form_process_date(), which adds
	 *   the individual 'year', 'month', 'day', etc. child elements. These functions
	 *   can also be used to set additional properties or implement special logic
	 *   other than adding child elements: for example, for the 'fieldset' element
	 *   type, one of the functions in this array is form_process_fieldset(), which
	 *   adds the attributes and JavaScript needed to make the fieldset collapsible
	 *   if the #collapsible property is set. The #process functions are called in
	 *   preorder traversal, meaning they are called for the parent element first,
	 *   then for the child elements.
	 * - $element['#after_build']: An array of functions called after form_builder()
	 *   is done with its processing of the element. These are called in postorder
	 *   traversal, meaning they are called for the child elements first, then for
	 *   the parent element.
	 * There are similar properties containing callback functions invoked by
	 * _form_validate() and drupal_render(), appropriate for those operations.
	 *
	 * Developers are strongly encouraged to integrate the functionality needed by
	 * their form or module within one of these three pipelines, using the
	 * appropriate callback property, rather than implementing their own recursive
	 * traversal of a form array. This facilitates proper integration between
	 * multiple modules. For example, module developers are familiar with the
	 * relative order in which hook_form_alter() implementations and #process
	 * functions run. A custom traversal function that affects the building of a
	 * form is likely to not integrate with hook_form_alter() and #process in the
	 * expected way. Also, deep recursion within PHP is both slow and memory
	 * intensive, so it is best to minimize how often it's done.
	 *
	 * As stated above, each element's #process functions are executed after its
	 * #value has been set. This enables those functions to execute conditional
	 * logic based on the current value. However, all of form_builder() runs before
	 * drupal_validate_form() is called, so during #process function execution, the
	 * element's #value has not yet been validated, so any code that requires
	 * validated values must reside within a submit handler.
	 *
	 * As a security measure, user input is used for an element's #value only if the
	 * element exists within $form, is not disabled (as per the #disabled property),
	 * and can be accessed (as per the #access property, except that forms submitted
	 * using drupal_form_submit() bypass #access restrictions). When user input is
	 * ignored due to #disabled and #access restrictions, the element's default
	 * value is used.
	 *
	 * Because of the preorder traversal, where #process functions of an element run
	 * before user input for its child elements is processed, and because of the
	 * Form API security of user input processing with respect to #access and
	 * #disabled described above, this generally means that #process functions
	 * should not use an element's (unvalidated) #value to affect the #disabled or
	 * #access of child elements. Use-cases where a developer may be tempted to
	 * implement such conditional logic usually fall into one of two categories:
	 * - Where user input from the current submission must affect the structure of a
	 *   form, including properties like #access and #disabled that affect how the
	 *   next submission needs to be processed, a multi-step workflow is needed.
	 *   This is most commonly implemented with a submit handler setting persistent
	 *   data within $form_state based on *validated* values in
	 *   $form_state['values'] and setting $form_state['rebuild']. The form building
	 *   functions must then be implemented to use the $form_state data to rebuild
	 *   the form with the structure appropriate for the new state.
	 * - Where user input must affect the rendering of the form without affecting
	 *   its structure, the necessary conditional rendering logic should reside
	 *   within functions that run during the rendering phase (#pre_render, #theme,
	 *   #theme_wrappers, and #post_render).
	 *
	 * @param $form_id
	 *   A unique string identifying the form for validation, submission,
	 *   theming, and hook_form_alter functions.
	 * @param $element
	 *   An associative array containing the structure of the current element.
	 * @param $form_state
	 *   A keyed array containing the current state of the form. In this
	 *   context, it is used to accumulate information about which button
	 *   was clicked when the form was submitted, as well as the sanitized
	 *   $_POST data.
	 */
	public static function form_builder($form_id, &$element, &$form_state) {
		// Initialize as unprocessed.
		$element['#processed'] = FALSE;

		// Use element defaults.
		if (isset($element['#type']) && empty($element['#defaults_loaded']) && ($info = intel()->element_info($element['#type']))) {

			// Overlay $info onto $element, retaining preexisting keys in $element.
			$element += $info;
			$element['#defaults_loaded'] = TRUE;
		}
		// Assign basic defaults common for all form elements.
		$element += array(
			'#required' => FALSE,
			'#attributes' => array(),
			'#title_display' => 'before',
		);

		// Special handling if we're on the top level form element.
		if (isset($element['#type']) && $element['#type'] == 'form') {
			if (!empty($element['#https']) && get_option('https', FALSE) &&
				!Intel_Df::url_is_external($element['#action'])) {
				global $base_root;

				// Not an external URL so ensure that it is secure.
				$element['#action'] = str_replace('http://', 'https://', $base_root) . $element['#action'];
			}

			// Store a reference to the complete form in $form_state prior to building
			// the form. This allows advanced #process and #after_build callbacks to
			// perform changes elsewhere in the form.
			$form_state['complete form'] = &$element;

			// Set a flag if we have a correct form submission. This is always TRUE for
			// programmed forms coming from drupal_form_submit(), or if the form_id coming
			// from the POST data is set and matches the current form_id.
			if ($form_state['programmed'] || (!empty($form_state['input']) && (isset($form_state['input']['form_id']) && ($form_state['input']['form_id'] == $form_id)))) {
				$form_state['process_input'] = TRUE;
				// If the session token was set by drupal_prepare_form(), ensure that it
				// matches the current user's session.
				$form_state['invalid_token'] = FALSE;
				if (isset($element['#token'])) {
					if (empty($form_state['input']['form_token']) || !wp_verify_nonce( $form_state['input']['form_token'], $element['#token'])) {
						// Set an early form error to block certain input processing since that
						// opens the door for CSRF vulnerabilities.
						self::_drupal_invalid_token_set_form_error();
						// This value is checked in _form_builder_handle_input_element().
						$form_state['invalid_token'] = TRUE;
						// Make sure file uploads do not get processed.
						$_FILES = array();
					}
				}
			}
			else {
				$form_state['process_input'] = FALSE;
			}

			// All form elements should have an #array_parents property.
			$element['#array_parents'] = array();
		}

		if (!isset($element['#id'])) {
			$element['#id'] = Intel_Df::drupal_html_id('edit-' . implode('-', $element['#parents']));
		}
		// Handle input elements.
		if (!empty($element['#input'])) {
			self::_form_builder_handle_input_element($form_id, $element, $form_state);
		}
		// Allow for elements to expand to multiple elements, e.g., radios,
		// checkboxes and files.
		if (isset($element['#process']) && !$element['#processed']) {
			foreach ($element['#process'] as $process) {
				if (is_callable($process)) {
					//$element = $process($element, $form_state, $form_state['complete form']);
					$element = call_user_func_array( $process, array(&$element, &$form_state, $form_state['complete form']));
				}
			}
			$element['#processed'] = TRUE;
		}

		// We start off assuming all form elements are in the correct order.
		$element['#sorted'] = TRUE;

		// Recurse through all child elements.
		$count = 0;
		foreach (Intel_Df::element_children($element) as $key) {
			// Prior to checking properties of child elements, their default properties
			// need to be loaded.
			if (isset($element[$key]['#type']) && empty($element[$key]['#defaults_loaded']) && ($info = intel()->element_info($element[$key]['#type']))) {
				$element[$key] += $info;
				$element[$key]['#defaults_loaded'] = TRUE;
			}

			// Don't squash an existing tree value.
			if (!isset($element[$key]['#tree'])) {
				$element[$key]['#tree'] = $element['#tree'];
			}

			// Deny access to child elements if parent is denied.
			if (isset($element['#access']) && !$element['#access']) {
				$element[$key]['#access'] = FALSE;
			}

			// Make child elements inherit their parent's #disabled and #allow_focus
			// values unless they specify their own.
			foreach (array('#disabled', '#allow_focus') as $property) {
				if (isset($element[$property]) && !isset($element[$key][$property])) {
					$element[$key][$property] = $element[$property];
				}
			}

			// Don't squash existing parents value.
			if (!isset($element[$key]['#parents'])) {
				// Check to see if a tree of child elements is present. If so,
				// continue down the tree if required.
				$element[$key]['#parents'] = $element[$key]['#tree'] && $element['#tree'] ? array_merge($element['#parents'], array($key)) : array($key);
			}
			// Ensure #array_parents follows the actual form structure.
			$array_parents = $element['#array_parents'];
			$array_parents[] = $key;
			$element[$key]['#array_parents'] = $array_parents;

			// Assign a decimal placeholder weight to preserve original array order.
			if (!isset($element[$key]['#weight'])) {
				$element[$key]['#weight'] = $count/1000;
			}
			else {
				// If one of the child elements has a weight then we will need to sort
				// later.
				unset($element['#sorted']);
			}
			$element[$key] = self::form_builder($form_id, $element[$key], $form_state);
			$count++;
		}

		// The #after_build flag allows any piece of a form to be altered
		// after normal input parsing has been completed.
		if (isset($element['#after_build']) && !isset($element['#after_build_done'])) {
			foreach ($element['#after_build'] as $function) {
				$element = $function($element, $form_state);
			}
			$element['#after_build_done'] = TRUE;
		}

		// If there is a file element, we need to flip a flag so later the
		// form encoding can be set.
		if (isset($element['#type']) && $element['#type'] == 'file') {
			$form_state['has_file_element'] = TRUE;
		}

		// Final tasks for the form element after form_builder() has run for all other
		// elements.
		if (isset($element['#type']) && $element['#type'] == 'form') {
			// If there is a file element, we set the form encoding.
			if (isset($form_state['has_file_element'])) {
				$element['#attributes']['enctype'] = 'multipart/form-data';
			}

			// Allow Ajax submissions to the form action to bypass verification. This is
			// especially useful for multipart forms, which cannot be verified via a
			// response header.
			$element['#attached']['js'][] = array(
				'type' => 'setting',
				'data' => array(
					'urlIsAjaxTrusted' => array(
						$element['#action'] => TRUE,
					),
				),
			);

			// If a form contains a single textfield, and the ENTER key is pressed
			// within it, Internet Explorer submits the form with no POST data
			// identifying any submit button. Other browsers submit POST data as though
			// the user clicked the first button. Therefore, to be as consistent as we
			// can be across browsers, if no 'triggering_element' has been identified
			// yet, default it to the first button.
			if (!$form_state['programmed'] && !isset($form_state['triggering_element']) && !empty($form_state['buttons'])) {
				$form_state['triggering_element'] = $form_state['buttons'][0];
			}

			// If the triggering element specifies "button-level" validation and submit
			// handlers to run instead of the default form-level ones, then add those to
			// the form state.
			foreach (array('validate', 'submit') as $type) {
				if (isset($form_state['triggering_element']['#' . $type])) {
					$form_state[$type . '_handlers'] = $form_state['triggering_element']['#' . $type];
				}
			}

			// If the triggering element executes submit handlers, then set the form
			// state key that's needed for those handlers to run.
			if (!empty($form_state['triggering_element']['#executes_submit_callback'])) {
				$form_state['submitted'] = TRUE;
			}

			// Special processing if the triggering element is a button.
			if (isset($form_state['triggering_element']['#button_type'])) {
				// Because there are several ways in which the triggering element could
				// have been determined (including from input variables set by JavaScript
				// or fallback behavior implemented for IE), and because buttons often
				// have their #name property not derived from their #parents property, we
				// can't assume that input processing that's happened up until here has
				// resulted in $form_state['values'][BUTTON_NAME] being set. But it's
				// common for forms to have several buttons named 'op' and switch on
				// $form_state['values']['op'] during submit handler execution.
				$form_state['values'][$form_state['triggering_element']['#name']] = $form_state['triggering_element']['#value'];

				// @todo Legacy support. Remove in Drupal 8.
				$form_state['clicked_button'] = $form_state['triggering_element'];
			}
		}
		return $element;
	}

	/**
	 * Adds the #name and #value properties of an input element before rendering.
	 */
	public static function _form_builder_handle_input_element($form_id, &$element, &$form_state) {
		static $safe_core_value_callbacks = array(
			'Intel_Form::form_type_token_value',
			'Intel_Form::form_type_textarea_value',
			'Intel_Form::form_type_textfield_value',
			'Intel_Form::form_type_checkbox_value',
			'Intel_Form::form_type_checkboxes_value',
			'Intel_Form::form_type_radios_value',
			'Intel_Form::form_type_password_confirm_value',
			'Intel_Form::form_type_select_value',
			'Intel_Form::form_type_tableselect_value',
			'Intel_Form::list_boolean_allowed_values_callback',
		);

		if (!isset($element['#name'])) {
			$name = array_shift($element['#parents']);
			$element['#name'] = $name;
			if ($element['#type'] == 'file') {
				// To make it easier to handle $_FILES in file.inc, we place all
				// file fields in the 'files' array. Also, we do not support
				// nested file names.
				$element['#name'] = 'files[' . $element['#name'] . ']';
			}
			elseif (count($element['#parents'])) {
				$element['#name'] .= '[' . implode('][', $element['#parents']) . ']';
			}
			array_unshift($element['#parents'], $name);
		}

		// Setting #disabled to TRUE results in user input being ignored, regardless
		// of how the element is themed or whether JavaScript is used to change the
		// control's attributes. However, it's good UI to let the user know that input
		// is not wanted for the control. HTML supports two attributes for this:
		// http://www.w3.org/TR/html401/interact/forms.html#h-17.12. If a form wants
		// to start a control off with one of these attributes for UI purposes only,
		// but still allow input to be processed if it's sumitted, it can set the
		// desired attribute in #attributes directly rather than using #disabled.
		// However, developers should think carefully about the accessibility
		// implications of doing so: if the form expects input to be enterable under
		// some condition triggered by JavaScript, how would someone who has
		// JavaScript disabled trigger that condition? Instead, developers should
		// consider whether a multi-step form would be more appropriate (#disabled can
		// be changed from step to step). If one still decides to use JavaScript to
		// affect when a control is enabled, then it is best for accessibility for the
		// control to be enabled in the HTML, and disabled by JavaScript on document
		// ready.
		if (!empty($element['#disabled'])) {
			if (!empty($element['#allow_focus'])) {
				$element['#attributes']['readonly'] = 'readonly';
			}
			else {
				$element['#attributes']['disabled'] = 'disabled';
			}
		}

		// With JavaScript or other easy hacking, input can be submitted even for
		// elements with #access=FALSE or #disabled=TRUE. For security, these must
		// not be processed. Forms that set #disabled=TRUE on an element do not
		// expect input for the element, and even forms submitted with
		// drupal_form_submit() must not be able to get around this. Forms that set
		// #access=FALSE on an element usually allow access for some users, so forms
		// submitted with drupal_form_submit() may bypass access restriction and be
		// treated as high-privilege users instead.
		$process_input = empty($element['#disabled']) && (($form_state['programmed'] && $form_state['programmed_bypass_access_check']) || ($form_state['process_input'] && (!isset($element['#access']) || $element['#access'])));

		// Set the element's #value property.
		if (!isset($element['#value']) && !array_key_exists('#value', $element)) {
			$value_callback = !empty($element['#value_callback']) ? $element['#value_callback'] : 'Intel_Form::form_type_' . $element['#type'] . '_value';
			if ($process_input) {
				// Get the input for the current element. NULL values in the input need to
				// be explicitly distinguished from missing input. (see below)
				$input_exists = NULL;
				$input = Intel_Df::drupal_array_get_nested_value($form_state['input'], $element['#parents'], $input_exists);
				// For browser-submitted forms, the submitted values do not contain values
				// for certain elements (empty multiple select, unchecked checkbox).
				// During initial form processing, we add explicit NULL values for such
				// elements in $form_state['input']. When rebuilding the form, we can
				// distinguish elements having NULL input from elements that were not part
				// of the initially submitted form and can therefore use default values
				// for the latter, if required. Programmatically submitted forms can
				// submit explicit NULL values when calling drupal_form_submit(), so we do
				// not modify $form_state['input'] for them.
				if (!$input_exists && !$form_state['rebuild'] && !$form_state['programmed']) {
					// Add the necessary parent keys to $form_state['input'] and sets the
					// element's input value to NULL.
					Intel_Df::drupal_array_set_nested_value($form_state['input'], $element['#parents'], NULL);
					$input_exists = TRUE;
				}
				// If we have input for the current element, assign it to the #value
				// property, optionally filtered through $value_callback.
				if ($input_exists) {
					if (is_callable($value_callback)) {
						// Skip all value callbacks except safe ones like text if the CSRF
						// token was invalid.
						if (empty($form_state['invalid_token']) || in_array($value_callback, $safe_core_value_callbacks)) {
							$element['#value'] = call_user_func_array($value_callback, array(&$element, $input, &$form_state));
						}
						else {
							$input = NULL;
						}
					}
					if (!isset($element['#value']) && isset($input)) {
						$element['#value'] = $input;
					}
				}
				// Mark all posted values for validation.
				if (isset($element['#value']) || (!empty($element['#required']))) {
					$element['#needs_validation'] = TRUE;
				}
			}
			// Load defaults.
			if (!isset($element['#value'])) {
				// Call #type_value without a second argument to request default_value handling.
				if (is_callable($value_callback)) {
					$element['#value'] = call_user_func_array( $value_callback, array(&$element, FALSE, &$form_state));
				}
				// Final catch. If we haven't set a value yet, use the explicit default value.
				// Avoid image buttons (which come with garbage value), so we only get value
				// for the button actually clicked.
				if (!isset($element['#value']) && empty($element['#has_garbage_value'])) {
					$element['#value'] = isset($element['#default_value']) ? $element['#default_value'] : '';
				}
			}
		}

		// Determine which element (if any) triggered the submission of the form and
		// keep track of all the clickable buttons in the form for
		// form_state_values_clean(). Enforce the same input processing restrictions
		// as above.
		if ($process_input) {
			// Detect if the element triggered the submission via Ajax.
			if (self::_form_element_triggered_scripted_submission($element, $form_state)) {
				$form_state['triggering_element'] = $element;
			}

			// If the form was submitted by the browser rather than via Ajax, then it
			// can only have been triggered by a button, and we need to determine which
			// button within the constraints of how browsers provide this information.
			if (isset($element['#button_type'])) {
				// All buttons in the form need to be tracked for
				// form_state_values_clean() and for the form_builder() code that handles
				// a form submission containing no button information in $_POST.
				$form_state['buttons'][] = $element;
				if (self::_form_button_was_clicked($element, $form_state)) {
					$form_state['triggering_element'] = $element;
				}
			}
		}

		// Set the element's value in $form_state['values'], but only, if its key
		// does not exist yet (a #value_callback may have already populated it).
		if (!Intel_Df::drupal_array_nested_key_exists($form_state['values'], $element['#parents'])) {
			self::form_set_value($element, $element['#value'], $form_state);
		}
	}

	/**
	 * Detects if an element triggered the form submission via Ajax.
	 *
	 * This detects button or non-button controls that trigger a form submission via
	 * Ajax or some other scriptable environment. These environments can set the
	 * special input key '_triggering_element_name' to identify the triggering
	 * element. If the name alone doesn't identify the element uniquely, the input
	 * key '_triggering_element_value' may also be set to require a match on element
	 * value. An example where this is needed is if there are several buttons all
	 * named 'op', and only differing in their value.
	 */
	public static function _form_element_triggered_scripted_submission($element, &$form_state) {
		if (!empty($form_state['input']['_triggering_element_name']) && $element['#name'] == $form_state['input']['_triggering_element_name']) {
			if (empty($form_state['input']['_triggering_element_value']) || $form_state['input']['_triggering_element_value'] == $element['#value']) {
				return TRUE;
			}
		}
		return FALSE;
	}

	/**
	 * Determines if a given button triggered the form submission.
	 *
	 * This detects button controls that trigger a form submission by being clicked
	 * and having the click processed by the browser rather than being captured by
	 * JavaScript. Essentially, it detects if the button's name and value are part
	 * of the POST data, but with extra code to deal with the convoluted way in
	 * which browsers submit data for image button clicks.
	 *
	 * This does not detect button clicks processed by Ajax (that is done in
	 * _form_element_triggered_scripted_submission()) and it does not detect form
	 * submissions from Internet Explorer in response to an ENTER key pressed in a
	 * textfield (form_builder() has extra code for that).
	 *
	 * Because this function contains only part of the logic needed to determine
	 * $form_state['triggering_element'], it should not be called from anywhere
	 * other than within the Form API. Form validation and submit handlers needing
	 * to know which button was clicked should get that information from
	 * $form_state['triggering_element'].
	 */
	public static function _form_button_was_clicked($element, &$form_state) {
		// First detect normal 'vanilla' button clicks. Traditionally, all
		// standard buttons on a form share the same name (usually 'op'),
		// and the specific return value is used to determine which was
		// clicked. This ONLY works as long as $form['#name'] puts the
		// value at the top level of the tree of $_POST data.
		if (isset($form_state['input'][$element['#name']]) && $form_state['input'][$element['#name']] == $element['#value']) {
			return TRUE;
		}
		// When image buttons are clicked, browsers do NOT pass the form element
		// value in $_POST. Instead they pass an integer representing the
		// coordinates of the click on the button image. This means that image
		// buttons MUST have unique $form['#name'] values, but the details of
		// their $_POST data should be ignored.
		elseif (!empty($element['#has_garbage_value']) && isset($element['#value']) && $element['#value'] !== '') {
			return TRUE;
		}
		return FALSE;
	}

	/**
	 * Removes internal Form API elements and buttons from submitted form values.
	 *
	 * This function can be used when a module wants to store all submitted form
	 * values, for example, by serializing them into a single database column. In
	 * such cases, all internal Form API values and all form button elements should
	 * not be contained, and this function allows to remove them before the module
	 * proceeds to storage. Next to button elements, the following internal values
	 * are removed:
	 * - form_id
	 * - form_token
	 * - form_build_id
	 * - op
	 *
	 * @param $form_state
	 *   A keyed array containing the current state of the form, including
	 *   submitted form values; altered by reference.
	 */
	public static function form_state_values_clean(&$form_state) {
		// Remove internal Form API values.
		unset($form_state['values']['form_id'], $form_state['values']['form_token'], $form_state['values']['form_build_id'], $form_state['values']['op'], $form_state['values']['intel_form']);

		// Remove button values.
		// form_builder() collects all button elements in a form. We remove the button
		// value separately for each button element.
		foreach ($form_state['buttons'] as $button) {
			// Remove this button's value from the submitted form values by finding
			// the value corresponding to this button.
			// We iterate over the #parents of this button and move a reference to
			// each parent in $form_state['values']. For example, if #parents is:
			//   array('foo', 'bar', 'baz')
			// then the corresponding $form_state['values'] part will look like this:
			// array(
			//   'foo' => array(
			//     'bar' => array(
			//       'baz' => 'button_value',
			//     ),
			//   ),
			// )
			// We start by (re)moving 'baz' to $last_parent, so we are able unset it
			// at the end of the iteration. Initially, $values will contain a
			// reference to $form_state['values'], but in the iteration we move the
			// reference to $form_state['values']['foo'], and finally to
			// $form_state['values']['foo']['bar'], which is the level where we can
			// unset 'baz' (that is stored in $last_parent).
			$parents = $button['#parents'];
			$last_parent = array_pop($parents);
			$key_exists = NULL;
			$values = &drupal_array_get_nested_value($form_state['values'], $parents, $key_exists);
			if ($key_exists && is_array($values)) {
				unset($values[$last_parent]);
			}
		}
	}

	/**
	 * Determines the value for an image button form element.
	 *
	 * @param $form
	 *   The form element whose value is being populated.
	 * @param $input
	 *   The incoming input to populate the form element. If this is FALSE,
	 *   the element's default value should be returned.
	 * @param $form_state
	 *   A keyed array containing the current state of the form.
	 *
	 * @return
	 *   The data that will appear in the $form_state['values'] collection
	 *   for this element. Return nothing to use the default.
	 */
	public static function form_type_image_button_value($form, $input, $form_state) {
		if ($input !== FALSE) {
			if (!empty($input)) {
				// If we're dealing with Mozilla or Opera, we're lucky. It will
				// return a proper value, and we can get on with things.
				return $form['#return_value'];
			}
			else {
				// Unfortunately, in IE we never get back a proper value for THIS
				// form element. Instead, we get back two split values: one for the
				// X and one for the Y coordinates on which the user clicked the
				// button. We'll find this element in the #post data, and search
				// in the same spot for its name, with '_x'.
				$input = $form_state['input'];
				foreach (explode('[', $form['#name']) as $element_name) {
					// chop off the ] that may exist.
					if (substr($element_name, -1) == ']') {
						$element_name = substr($element_name, 0, -1);
					}

					if (!isset($input[$element_name])) {
						if (isset($input[$element_name . '_x'])) {
							return $form['#return_value'];
						}
						return NULL;
					}
					$input = $input[$element_name];
				}
				return $form['#return_value'];
			}
		}
	}

	/**
	 * Determines the value for a checkbox form element.
	 *
	 * @param $form
	 *   The form element whose value is being populated.
	 * @param $input
	 *   The incoming input to populate the form element. If this is FALSE,
	 *   the element's default value should be returned.
	 *
	 * @return
	 *   The data that will appear in the $element_state['values'] collection
	 *   for this element. Return nothing to use the default.
	 */
	public static function form_type_checkbox_value($element, $input = FALSE) {
		if ($input === FALSE) {
			// Use #default_value as the default value of a checkbox, except change
			// NULL to 0, because _form_builder_handle_input_element() would otherwise
			// replace NULL with empty string, but an empty string is a potentially
			// valid value for a checked checkbox.
			return isset($element['#default_value']) ? $element['#default_value'] : 0;
		}
		else {
			// Checked checkboxes are submitted with a value (possibly '0' or ''):
			// http://www.w3.org/TR/html401/interact/forms.html#successful-controls.
			// For checked checkboxes, browsers submit the string version of
			// #return_value, but we return the original #return_value. For unchecked
			// checkboxes, browsers submit nothing at all, but
			// _form_builder_handle_input_element() detects this, and calls this
			// function with $input=NULL. Returning NULL from a value callback means to
			// use the default value, which is not what is wanted when an unchecked
			// checkbox is submitted, so we use integer 0 as the value indicating an
			// unchecked checkbox. Therefore, modules must not use integer 0 as a
			// #return_value, as doing so results in the checkbox always being treated
			// as unchecked. The string '0' is allowed for #return_value. The most
			// common use-case for setting #return_value to either 0 or '0' is for the
			// first option within a 0-indexed array of checkboxes, and for this,
			// form_process_checkboxes() uses the string rather than the integer.
			return isset($input) ? $element['#return_value'] : 0;
		}
	}

	/**
	 * Determines the value for a checkboxes form element.
	 *
	 * @param $element
	 *   The form element whose value is being populated.
	 * @param $input
	 *   The incoming input to populate the form element. If this is FALSE,
	 *   the element's default value should be returned.
	 *
	 * @return
	 *   The data that will appear in the $element_state['values'] collection
	 *   for this element. Return nothing to use the default.
	 */
	public static function form_type_checkboxes_value($element, $input = FALSE) {
		if ($input === FALSE) {
			$value = array();
			$element += array('#default_value' => array());
			foreach ($element['#default_value'] as $key) {
				$value[$key] = $key;
			}
			return $value;
		}
		elseif (is_array($input)) {
			// Programmatic form submissions use NULL to indicate that a checkbox
			// should be unchecked; see drupal_form_submit(). We therefore remove all
			// NULL elements from the array before constructing the return value, to
			// simulate the behavior of web browsers (which do not send unchecked
			// checkboxes to the server at all). This will not affect non-programmatic
			// form submissions, since all values in $_POST are strings.
			foreach ($input as $key => $value) {
				if (!isset($value)) {
					unset($input[$key]);
				}
			}
			return Intel_Df::drupal_map_assoc($input);
		}
		else {
			return array();
		}
	}

	/**
	 * Determines the value for a tableselect form element.
	 *
	 * @param $element
	 *   The form element whose value is being populated.
	 * @param $input
	 *   The incoming input to populate the form element. If this is FALSE,
	 *   the element's default value should be returned.
	 *
	 * @return
	 *   The data that will appear in the $element_state['values'] collection
	 *   for this element. Return nothing to use the default.
	 */
	public static function form_type_tableselect_value($element, $input = FALSE) {
		// If $element['#multiple'] == FALSE, then radio buttons are displayed and
		// the default value handling is used.
		if (isset($element['#multiple']) && $element['#multiple']) {
			// Checkboxes are being displayed with the default value coming from the
			// keys of the #default_value property. This differs from the checkboxes
			// element which uses the array values.
			if ($input === FALSE) {
				$value = array();
				$element += array('#default_value' => array());
				foreach ($element['#default_value'] as $key => $flag) {
					if ($flag) {
						$value[$key] = $key;
					}
				}
				return $value;
			}
			else {
				return is_array($input) ? Intel_Df::drupal_map_assoc($input) : array();
			}
		}
	}

	/**
	 * Form value callback: Determines the value for a #type radios form element.
	 *
	 * @param $element
	 *   The form element whose value is being populated.
	 * @param $input
	 *   (optional) The incoming input to populate the form element. If FALSE, the
	 *   element's default value is returned. Defaults to FALSE.
	 *
	 * @return
	 *   The data that will appear in the $element_state['values'] collection for
	 *   this element.
	 */
	public static function form_type_radios_value(&$element, $input = FALSE) {
		if ($input !== FALSE) {
			// When there's user input (including NULL), return it as the value.
			// However, if NULL is submitted, _form_builder_handle_input_element() will
			// apply the default value, and we want that validated against #options
			// unless it's empty. (An empty #default_value, such as NULL or FALSE, can
			// be used to indicate that no radio button is selected by default.)
			if (!isset($input) && !empty($element['#default_value'])) {
				$element['#needs_validation'] = TRUE;
			}
			return $input;
		}
		else {
			// For default value handling, simply return #default_value. Additionally,
			// for a NULL default value, set #has_garbage_value to prevent
			// _form_builder_handle_input_element() converting the NULL to an empty
			// string, so that code can distinguish between nothing selected and the
			// selection of a radio button whose value is an empty string.
			$value = isset($element['#default_value']) ? $element['#default_value'] : NULL;
			if (!isset($value)) {
				$element['#has_garbage_value'] = TRUE;
			}
			return $value;
		}
	}

	/**
	 * Determines the value for a password_confirm form element.
	 *
	 * @param $element
	 *   The form element whose value is being populated.
	 * @param $input
	 *   The incoming input to populate the form element. If this is FALSE,
	 *   the element's default value should be returned.
	 *
	 * @return
	 *   The data that will appear in the $element_state['values'] collection
	 *   for this element. Return nothing to use the default.
	 */
	public static function form_type_password_confirm_value($element, $input = FALSE) {
		if ($input === FALSE) {
			$element += array('#default_value' => array());
			return $element['#default_value'] + array('pass1' => '', 'pass2' => '');
		}
		$value = array('pass1' => '', 'pass2' => '');
		// Throw out all invalid array keys; we only allow pass1 and pass2.
		foreach ($value as $allowed_key => $default) {
			// These should be strings, but allow other scalars since they might be
			// valid input in programmatic form submissions. Any nested array values
			// are ignored.
			if (isset($input[$allowed_key]) && is_scalar($input[$allowed_key])) {
				$value[$allowed_key] = (string) $input[$allowed_key];
			}
		}
		return $value;
	}

	/**
	 * Determines the value for a select form element.
	 *
	 * @param $element
	 *   The form element whose value is being populated.
	 * @param $input
	 *   The incoming input to populate the form element. If this is FALSE,
	 *   the element's default value should be returned.
	 *
	 * @return
	 *   The data that will appear in the $element_state['values'] collection
	 *   for this element. Return nothing to use the default.
	 */
	public static function form_type_select_value($element, $input = FALSE) {
		if ($input !== FALSE) {
			if (isset($element['#multiple']) && $element['#multiple']) {
				// If an enabled multi-select submits NULL, it means all items are
				// unselected. A disabled multi-select always submits NULL, and the
				// default value should be used.
				if (empty($element['#disabled'])) {
					return (is_array($input)) ? Intel_Df::drupal_map_assoc($input) : array();
				}
				else {
					return (isset($element['#default_value']) && is_array($element['#default_value'])) ? $element['#default_value'] : array();
				}
			}
			// Non-multiple select elements may have an empty option preprended to them
			// (see form_process_select()). When this occurs, usually #empty_value is
			// an empty string, but some forms set #empty_value to integer 0 or some
			// other non-string constant. PHP receives all submitted form input as
			// strings, but if the empty option is selected, set the value to match the
			// empty value exactly.
			elseif (isset($element['#empty_value']) && $input === (string) $element['#empty_value']) {
				return $element['#empty_value'];
			}
			else {
				return $input;
			}
		}
	}

	/**
	 * Determines the value for a textarea form element.
	 *
	 * @param array $element
	 *   The form element whose value is being populated.
	 * @param mixed $input
	 *   The incoming input to populate the form element. If this is FALSE,
	 *   the element's default value should be returned.
	 *
	 * @return string
	 *   The data that will appear in the $element_state['values'] collection
	 *   for this element. Return nothing to use the default.
	 */
	public static function form_type_textarea_value($element, $input = FALSE) {
		if ($input !== FALSE && $input !== NULL) {
			// This should be a string, but allow other scalars since they might be
			// valid input in programmatic form submissions.
			// WP sanitization
			$input = is_scalar($input) ? (string) $input : '';
			if (empty($element['#html'])) {
				$input = sanitize_textarea_field($input);
			}
			return $input;
			//return is_scalar($input) ? (string) $input : ''; // Drupal version
		}
	}

	/**
	 * Determines the value for a textfield form element.
	 *
	 * @param $element
	 *   The form element whose value is being populated.
	 * @param $input
	 *   The incoming input to populate the form element. If this is FALSE,
	 *   the element's default value should be returned.
	 *
	 * @return
	 *   The data that will appear in the $element_state['values'] collection
	 *   for this element. Return nothing to use the default.
	 */
	public static function form_type_textfield_value($element, $input = FALSE) {
		if ($input !== FALSE && $input !== NULL) {
			// This should be a string, but allow other scalars since they might be
			// valid input in programmatic form submissions.
			if (!is_scalar($input)) {
				$input = '';
			}
			$input = (string) $input;
			if (empty($element['#html'])) {
				$input = sanitize_text_field($input);
			}
			return str_replace(array("\r", "\n"), '', $input);
			//return str_replace(array("\r", "\n"), '', (string) $input); // Drupal version
		}
	}

	/**
	 * Determines the value for form's token value.
	 *
	 * @param $element
	 *   The form element whose value is being populated.
	 * @param $input
	 *   The incoming input to populate the form element. If this is FALSE,
	 *   the element's default value should be returned.
	 *
	 * @return
	 *   The data that will appear in the $element_state['values'] collection
	 *   for this element. Return nothing to use the default.
	 */
	public static function form_type_token_value($element, $input = FALSE) {
		if ($input !== FALSE) {
			return (string) $input;
		}
	}

	/**
	 * Changes submitted form values during form validation.
	 *
	 * Use this function to change the submitted value of a form element in a form
	 * validation function, so that the changed value persists in $form_state
	 * through the remaining validation and submission handlers. It does not change
	 * the value in $element['#value'], only in $form_state['values'], which is
	 * where submitted values are always stored.
	 *
	 * Note that form validation functions are specified in the '#validate'
	 * component of the form array (the value of $form['#validate'] is an array of
	 * validation function names). If the form does not originate in your module,
	 * you can implement hook_form_FORM_ID_alter() to add a validation function
	 * to $form['#validate'].
	 *
	 * @param $element
	 *   The form element that should have its value updated; in most cases you can
	 *   just pass in the element from the $form array, although the only component
	 *   that is actually used is '#parents'. If constructing yourself, set
	 *   $element['#parents'] to be an array giving the path through the form
	 *   array's keys to the element whose value you want to update. For instance,
	 *   if you want to update the value of $form['elem1']['elem2'], which should be
	 *   stored in $form_state['values']['elem1']['elem2'], you would set
	 *   $element['#parents'] = array('elem1','elem2').
	 * @param $value
	 *   The new value for the form element.
	 * @param $form_state
	 *   Form state array where the value change should be recorded.
	 */
	public static function form_set_value($element, $value, &$form_state) {
		Intel_Df::drupal_array_set_nested_value($form_state['values'], $element['#parents'], $value, TRUE);
	}

	/**
	 * Allows PHP array processing of multiple select options with the same value.
	 *
	 * Used for form select elements which need to validate HTML option groups
	 * and multiple options which may return the same value. Associative PHP arrays
	 * cannot handle these structures, since they share a common key.
	 *
	 * @param $array
	 *   The form options array to process.
	 *
	 * @return
	 *   An array with all hierarchical elements flattened to a single array.
	 */
	public static function form_options_flatten($array) {
		// Always reset static var when first entering the recursion.
		Intel_Df::drupal_static_reset('_form_options_flatten');
		return self::_form_options_flatten($array);
	}

	/**
	 * Iterates over an array and returns a flat array with duplicate keys removed.
	 *
	 * This function also handles cases where objects are passed as array values.
	 */
	public static function _form_options_flatten($array) {
		$return = &Intel_Df::drupal_static(__FUNCTION__);

		foreach ($array as $key => $value) {
			if (is_object($value)) {
				self::_form_options_flatten($value->option);
			}
			elseif (is_array($value)) {
				self::_form_options_flatten($value);
			}
			else {
				$return[$key] = 1;
			}
		}

		return $return;
	}

	/**
	 * Sets a form element's class attribute.
	 *
	 * Adds 'required' and 'error' classes as needed.
	 *
	 * @param $element
	 *   The form element.
	 * @param $name
	 *   Array of new class names to be added.
	 */
	public static function _form_set_class(&$element, $class = array()) {
		if (!empty($class)) {
			if (!isset($element['#attributes']['class'])) {
				$element['#attributes']['class'] = array();
			}
			$element['#attributes']['class'] = array_merge($element['#attributes']['class'], $class);
		}

		// add form-control to some elements. Breaks checkbox
		$exclude = array(
			'checkbox' => 1,
			'checkboxes' => 1,
			'fieldset' => 1,
			'radio' => 1,
			'radios' => 1,
		);
		if (empty($exclude[$element['#type']])) {
			$element['#attributes']['class'][] = 'form-control';
		}

		// This function is invoked from form element theme functions, but the
		// rendered form element may not necessarily have been processed by
		// form_builder().
		if (!empty($element['#required'])) {
			$element['#attributes']['class'][] = 'required';
		}
		if (isset($element['#parents']) && self::form_get_error($element) !== NULL && !empty($element['#validated'])) {
			$element['#attributes']['class'][] = 'error';
			$element['#attributes']['class'][] = 'has-error';
		}
	}

	/**
	 * Processes a select list form element.
	 *
	 * This process callback is mandatory for select fields, since all user agents
	 * automatically preselect the first available option of single (non-multiple)
	 * select lists.
	 *
	 * @param $element
	 *   The form element to process. Properties used:
	 *   - #multiple: (optional) Indicates whether one or more options can be
	 *     selected. Defaults to FALSE.
	 *   - #default_value: Must be NULL or not set in case there is no value for the
	 *     element yet, in which case a first default option is inserted by default.
	 *     Whether this first option is a valid option depends on whether the field
	 *     is #required or not.
	 *   - #required: (optional) Whether the user needs to select an option (TRUE)
	 *     or not (FALSE). Defaults to FALSE.
	 *   - #empty_option: (optional) The label to show for the first default option.
	 *     By default, the label is automatically set to "- Select -" for a required
	 *     field and "- None -" for an optional field.
	 *   - #empty_value: (optional) The value for the first default option, which is
	 *     used to determine whether the user submitted a value or not.
	 *     - If #required is TRUE, this defaults to '' (an empty string).
	 *     - If #required is not TRUE and this value isn't set, then no extra option
	 *       is added to the select control, leaving the control in a slightly
	 *       illogical state, because there's no way for the user to select nothing,
	 *       since all user agents automatically preselect the first available
	 *       option. But people are used to this being the behavior of select
	 *       controls.
	 *       @todo Address the above issue in Drupal 8.
	 *     - If #required is not TRUE and this value is set (most commonly to an
	 *       empty string), then an extra option (see #empty_option above)
	 *       representing a "non-selection" is added with this as its value.
	 *
	 * @see _form_validate()
	 */
	public static function form_process_select($element) {
		// #multiple select fields need a special #name.
		if ($element['#multiple']) {
			$element['#attributes']['multiple'] = 'multiple';
			$element['#attributes']['name'] = $element['#name'] . '[]';
		}
		// A non-#multiple select needs special handling to prevent user agents from
		// preselecting the first option without intention. #multiple select lists do
		// not get an empty option, as it would not make sense, user interface-wise.
		else {
			$required = $element['#required'];
			// If the element is required and there is no #default_value, then add an
			// empty option that will fail validation, so that the user is required to
			// make a choice. Also, if there's a value for #empty_value or
			// #empty_option, then add an option that represents emptiness.
			if (($required && !isset($element['#default_value'])) || isset($element['#empty_value']) || isset($element['#empty_option'])) {
				$element += array(
					'#empty_value' => '',
					'#empty_option' => $required ? Intel_Df::t('- Select -') : Intel_Df::t('- None -'),
				);
				// The empty option is prepended to #options and purposively not merged
				// to prevent another option in #options mistakenly using the same value
				// as #empty_value.
				$empty_option = array($element['#empty_value'] => $element['#empty_option']);
				$element['#options'] = $empty_option + $element['#options'];
			}
		}
		return $element;
	}

	/**
	 * Returns HTML for a select form element.
	 *
	 * It is possible to group options together; to do this, change the format of
	 * $options to an associative array in which the keys are group labels, and the
	 * values are associative arrays in the normal $options format.
	 *
	 * @param $variables
	 *   An associative array containing:
	 *   - element: An associative array containing the properties of the element.
	 *     Properties used: #title, #value, #options, #description, #extra,
	 *     #multiple, #required, #name, #attributes, #size.
	 *
	 * @ingroup themeable
	 */
	public static function theme_select($variables) {
		$element = $variables['element'];
		Intel_Df::element_set_attributes($element, array('id', 'name', 'size'));
		self::_form_set_class($element, array('form-select'));

		return '<select' . Intel_Df::drupal_attributes($element['#attributes']) . '>' . self::form_select_options($element) . '</select>';
	}

	/**
	 * Converts an array of options into HTML, for use in select list form elements.
	 *
	 * This function calls itself recursively to obtain the values for each optgroup
	 * within the list of options and when the function encounters an object with
	 * an 'options' property inside $element['#options'].
	 *
	 * @param array $element
	 *   An associative array containing the following key-value pairs:
	 *   - #multiple: Optional Boolean indicating if the user may select more than
	 *     one item.
	 *   - #options: An associative array of options to render as HTML. Each array
	 *     value can be a string, an array, or an object with an 'option' property:
	 *     - A string or integer key whose value is a translated string is
	 *       interpreted as a single HTML option element. Do not use placeholders
	 *       that sanitize data: doing so will lead to double-escaping. Note that
	 *       the key will be visible in the HTML and could be modified by malicious
	 *       users, so don't put sensitive information in it.
	 *     - A translated string key whose value is an array indicates a group of
	 *       options. The translated string is used as the label attribute for the
	 *       optgroup. Do not use placeholders to sanitize data: doing so will lead
	 *       to double-escaping. The array should contain the options you wish to
	 *       group and should follow the syntax of $element['#options'].
	 *     - If the function encounters a string or integer key whose value is an
	 *       object with an 'option' property, the key is ignored, the contents of
	 *       the option property are interpreted as $element['#options'], and the
	 *       resulting HTML is added to the output.
	 *   - #value: Optional integer, string, or array representing which option(s)
	 *     to pre-select when the list is first displayed. The integer or string
	 *     must match the key of an option in the '#options' list. If '#multiple' is
	 *     TRUE, this can be an array of integers or strings.
	 * @param array|null $choices
	 *   (optional) Either an associative array of options in the same format as
	 *   $element['#options'] above, or NULL. This parameter is only used internally
	 *   and is not intended to be passed in to the initial function call.
	 *
	 * @return string
	 *   An HTML string of options and optgroups for use in a select form element.
	 */
	public static function form_select_options($element, $choices = NULL) {
		if (!isset($choices)) {
			$choices = $element['#options'];
		}
		// array_key_exists() accommodates the rare event where $element['#value'] is NULL.
		// isset() fails in this situation.
		$value_valid = isset($element['#value']) || array_key_exists('#value', $element);
		$value_is_array = $value_valid && is_array($element['#value']);
		$options = '';
		foreach ($choices as $key => $choice) {
			if (is_array($choice)) {
				$options .= '<optgroup label="' . Intel_Df::check_plain($key) . '">';
				$options .= self::form_select_options($element, $choice);
				$options .= '</optgroup>';
			}
			elseif (is_object($choice)) {
				$options .= self::form_select_options($element, $choice->option);
			}
			else {
				$key = (string) $key;
				if ($value_valid && (!$value_is_array && (string) $element['#value'] === $key || ($value_is_array && in_array($key, $element['#value'])))) {
					$selected = ' selected="selected"';
				}
				else {
					$selected = '';
				}
				$options .= '<option value="' . Intel_Df::check_plain($key) . '"' . $selected . '>' . Intel_Df::check_plain($choice) . '</option>';
			}
		}
		return $options;
	}

	/**
	 * Returns the indexes of a select element's options matching a given key.
	 *
	 * This function is useful if you need to modify the options that are
	 * already in a form element; for example, to remove choices which are
	 * not valid because of additional filters imposed by another module.
	 * One example might be altering the choices in a taxonomy selector.
	 * To correctly handle the case of a multiple hierarchy taxonomy,
	 * #options arrays can now hold an array of objects, instead of a
	 * direct mapping of keys to labels, so that multiple choices in the
	 * selector can have the same key (and label). This makes it difficult
	 * to manipulate directly, which is why this helper function exists.
	 *
	 * This function does not support optgroups (when the elements of the
	 * #options array are themselves arrays), and will return FALSE if
	 * arrays are found. The caller must either flatten/restore or
	 * manually do their manipulations in this case, since returning the
	 * index is not sufficient, and supporting this would make the
	 * "helper" too complicated and cumbersome to be of any help.
	 *
	 * As usual with functions that can return array() or FALSE, do not
	 * forget to use === and !== if needed.
	 *
	 * @param $element
	 *   The select element to search.
	 * @param $key
	 *   The key to look for.
	 *
	 * @return
	 *   An array of indexes that match the given $key. Array will be
	 *   empty if no elements were found. FALSE if optgroups were found.
	 */
	public static function form_get_options($element, $key) {
		$keys = array();
		foreach ($element['#options'] as $index => $choice) {
			if (is_array($choice)) {
				return FALSE;
			}
			elseif (is_object($choice)) {
				if (isset($choice->option[$key])) {
					$keys[] = $index;
				}
			}
			elseif ($index == $key) {
				$keys[] = $index;
			}
		}
		return $keys;
	}

	/**
	 * Returns HTML for a fieldset form element and its children.
	 *
	 * @param $variables
	 *   An associative array containing:
	 *   - element: An associative array containing the properties of the element.
	 *     Properties used: #attributes, #children, #collapsed, #collapsible,
	 *     #description, #id, #title, #value.
	 *
	 * @ingroup themeable
	 */
	public static function theme_fieldset(&$variables) {
		$count = &Intel_Df::drupal_static(__FUNCTION__, 0);

		$element = $variables['element'];
		Intel_Df::element_set_attributes($element, array('id'));
		self::_form_set_class($element, array('form-wrapper'));
		if (!isset($element['#attributes']['class'])) {
			$element['#attributes']['class'] = array();
		}
		$element['#attributes']['class'][] = 'panel-group';

		$output = '';
		$output .= '<fieldset' . Intel_Df::drupal_attributes($element['#attributes']) . '>';
		$output .= '<div class="panel panel-default">';
		if (!empty($element['#title'])) {
			// Always wrap fieldset legends in a SPAN for CSS positioning.
			if (!empty($element['#collapsible'])) {
				$output .= '<div class="panel-heading"><div class="panel-title collapsible-fieldset-title"><a data-toggle="collapse" data-parent="#accordion" href="#fieldset-panel-' . $count . '" class="collapsible-fieldset-link collapsible-fieldset-link-' . $count . '">' . $element['#title'] . ' <span class="collapsible-fieldset-icon collapsible-fieldset-icon-' . $count . ' glyphicon" aria-hidden="true"></span></a></div></div>';
			}
			else {
				$output .= '<div class="panel-heading"><div class="panel-title">' . $element['#title'] . '</div></div>';
			}
		}
		$output .= '<div id="fieldset-panel-' . $count . '" class="fieldset-panel panel-collapse collapse ' . (!empty($element['#collapsed']) ? '' : ' in') . '"">';
		$output .= '<div class="panel-body">';

		if (!empty($element['#description'])) {
			$output .= '<div class="fieldset-description">' . $element['#description'] . '</div>';
		}

		$output .= $element['#children'];
		if (isset($element['#value'])) {
			$output .= $element['#value'];
		}
		$output .= '</div>';
		$output .= '</div>'; // close panel-body div
		$output .= '</div>'; // close panel div
		$output .= "</fieldset>\n";
		$count++;
		return $output;
	}

	/**
	 * Returns HTML for a radio button form element.
	 *
	 * Note: The input "name" attribute needs to be sanitized before output, which
	 *       is currently done by passing all attributes to drupal_attributes().
	 *
	 * @param $variables
	 *   An associative array containing:
	 *   - element: An associative array containing the properties of the element.
	 *     Properties used: #required, #return_value, #value, #attributes, #title,
	 *     #description
	 *
	 * @ingroup themeable
	 */
	public static function theme_radio($variables) {
		$element = $variables['element'];
		$element['#attributes']['type'] = 'radio';
		Intel_Df::element_set_attributes($element, array('id', 'name', '#return_value' => 'value'));

		if (isset($element['#return_value']) && $element['#value'] !== FALSE && $element['#value'] == $element['#return_value']) {
			$element['#attributes']['checked'] = 'checked';
		}
		self::_form_set_class($element, array('form-radio'));

		return '<input' . Intel_Df::drupal_attributes($element['#attributes']) . ' />';
	}

	/**
	 * Returns HTML for a set of radio button form elements.
	 *
	 * @param $variables
	 *   An associative array containing:
	 *   - element: An associative array containing the properties of the element.
	 *     Properties used: #title, #value, #options, #description, #required,
	 *     #attributes, #children.
	 *
	 * @ingroup themeable
	 */
	public static function theme_radios($variables) {
		$element = $variables['element'];
		$attributes = array();
		if (isset($element['#id'])) {
			$attributes['id'] = $element['#id'];
		}
		$attributes['class'] = 'form-radios';
		if (!empty($element['#attributes']['class'])) {
			$attributes['class'] .= ' ' . implode(' ', $element['#attributes']['class']);
		}
		if (isset($element['#attributes']['title'])) {
			$attributes['title'] = $element['#attributes']['title'];
		}
		return '<div' . Intel_Df::drupal_attributes($attributes) . '>' . (!empty($element['#children']) ? $element['#children'] : '') . '</div>';
	}

	/**
	 * Expand a password_confirm field into two text boxes.
	 */
	public static function form_process_password_confirm($element) {
		$element['pass1'] =  array(
			'#type' => 'password',
			'#title' => Intel_Df::t('Password'),
			'#value' => empty($element['#value']) ? NULL : $element['#value']['pass1'],
			'#required' => $element['#required'],
			'#attributes' => array('class' => array('password-field')),
		);
		$element['pass2'] =  array(
			'#type' => 'password',
			'#title' => Intel_Df::t('Confirm password'),
			'#value' => empty($element['#value']) ? NULL : $element['#value']['pass2'],
			'#required' => $element['#required'],
			'#attributes' => array('class' => array('password-confirm')),
		);
		$element['#element_validate'] = array('password_confirm_validate');
		$element['#tree'] = TRUE;

		if (isset($element['#size'])) {
			$element['pass1']['#size'] = $element['pass2']['#size'] = $element['#size'];
		}

		return $element;
	}

	/**
	 * Validates a password_confirm element.
	 */
	public static function password_confirm_validate($element, &$element_state) {
		$pass1 = trim($element['pass1']['#value']);
		$pass2 = trim($element['pass2']['#value']);
		if (strlen($pass1) > 0 || strlen($pass2) > 0) {
			if (strcmp($pass1, $pass2)) {
				form_error($element, Intel_Df::t('The specified passwords do not match.'));
			}
		}
		elseif ($element['#required'] && !empty($element_state['input'])) {
			form_error($element, Intel_Df::t('Password field is required.'));
		}

		// Password field must be converted from a two-element array into a single
		// string regardless of validation results.
		form_set_value($element['pass1'], NULL, $element_state);
		form_set_value($element['pass2'], NULL, $element_state);
		form_set_value($element, $pass1, $element_state);

		return $element;

	}

	/**
	 * Returns HTML for a date selection form element.
	 *
	 * @param $variables
	 *   An associative array containing:
	 *   - element: An associative array containing the properties of the element.
	 *     Properties used: #title, #value, #options, #description, #required,
	 *     #attributes.
	 *
	 * @ingroup themeable
	 */
	public static function theme_date($variables) {
		$element = $variables['element'];

		$attributes = array();
		if (isset($element['#id'])) {
			$attributes['id'] = $element['#id'];
		}
		if (!empty($element['#attributes']['class'])) {
			$attributes['class'] = (array) $element['#attributes']['class'];
		}
		$attributes['class'][] = 'container-inline';

		return '<div' . Intel_Df::drupal_attributes($attributes) . '>' . Intel_Df::drupal_render_children($element) . '</div>';
	}

	/**
	 * Expands a date element into year, month, and day select elements.
	 */
	public static function form_process_date($element) {
		// Default to current date
		if (empty($element['#value'])) {
			$element['#value'] = array(
				'day' => Intel_Df::format_date(REQUEST_TIME, 'custom', 'j'),
				'month' => Intel_Df::format_date(REQUEST_TIME, 'custom', 'n'),
				'year' => Intel_Df::format_date(REQUEST_TIME, 'custom', 'Y'),
			);
		}

		$element['#tree'] = TRUE;

		// Determine the order of day, month, year in the site's chosen date format.
		$format = get_option('intel_date_format_short', 'm/d/Y - H:i');
		$sort = array();
		$sort['day'] = max(strpos($format, 'd'), strpos($format, 'j'));
		$sort['month'] = max(strpos($format, 'm'), strpos($format, 'M'));
		$sort['year'] = strpos($format, 'Y');
		asort($sort);
		$order = array_keys($sort);

		// Output multi-selector for date.
		foreach ($order as $type) {
			switch ($type) {
				case 'day':
					$options = Intel_Df::drupal_map_assoc(range(1, 31));
					$title = Intel_Df::t('Day');
					break;

				case 'month':
					$options = Intel_Df::drupal_map_assoc(range(1, 12), 'map_month');
					$title = Intel_Df::t('Month');
					break;

				case 'year':
					$options = Intel_Df::drupal_map_assoc(range(1900, 2050));
					$title = Intel_Df::t('Year');
					break;
			}

			$element[$type] = array(
				'#type' => 'select',
				'#title' => $title,
				'#title_display' => 'invisible',
				'#value' => $element['#value'][$type],
				'#attributes' => $element['#attributes'],
				'#options' => $options,
			);
		}

		return $element;
	}

	/**
	 * Validates the date type to prevent invalid dates (e.g., February 30, 2006).
	 */
	public static function date_validate($element) {
		if (!checkdate($element['#value']['month'], $element['#value']['day'], $element['#value']['year'])) {
			form_error($element, Intel_Df::t('The specified date is invalid.'));
		}
	}

	/**
	 * Helper function for usage with drupal_map_assoc to display month names.
	 */
	public static function map_month($month) {
		$months = &drupal_static(__FUNCTION__, array(
			1 => 'Jan',
			2 => 'Feb',
			3 => 'Mar',
			4 => 'Apr',
			5 => 'May',
			6 => 'Jun',
			7 => 'Jul',
			8 => 'Aug',
			9 => 'Sep',
			10 => 'Oct',
			11 => 'Nov',
			12 => 'Dec',
		));
		return Intel_Df::t($months[$month]);
	}

	/**
	 * Sets the value for a weight element, with zero as a default.
	 */
	public static function weight_value(&$form) {
		if (isset($form['#default_value'])) {
			$form['#value'] = $form['#default_value'];
		}
		else {
			$form['#value'] = 0;
		}
	}

	/**
	 * Expands a radios element into individual radio elements.
	 */
	public static function form_process_radios($element) {
		if (count($element['#options']) > 0) {
			$weight = 0;
			foreach ($element['#options'] as $key => $choice) {
				// Maintain order of options as defined in #options, in case the element
				// defines custom option sub-elements, but does not define all option
				// sub-elements.
				$weight += 0.001;

				$element += array($key => array());
				// Generate the parents as the autogenerator does, so we will have a
				// unique id for each radio button.
				$parents_for_id = array_merge($element['#parents'], array($key));
				$element[$key] += array(
					'#type' => 'radio',
					'#super_type' => 'radios', // flag for element to determine if checkbox is a part of checkboxes
					'#title' => $choice,
					// The key is sanitized in drupal_attributes() during output from the
					// theme function.
					'#return_value' => $key,
					// Use default or FALSE. A value of FALSE means that the radio button is
					// not 'checked'.
					'#default_value' => isset($element['#default_value']) ? $element['#default_value'] : FALSE,
					'#attributes' => $element['#attributes'],
					'#parents' => $element['#parents'],
					'#id' => Intel_Df::drupal_html_id('edit-' . implode('-', $parents_for_id)),
					'#ajax' => isset($element['#ajax']) ? $element['#ajax'] : NULL,
					'#weight' => $weight,
				);
			}
		}
		return $element;
	}

	/**
	 * Returns HTML for a checkbox form element.
	 *
	 * @param $variables
	 *   An associative array containing:
	 *   - element: An associative array containing the properties of the element.
	 *     Properties used: #id, #name, #attributes, #checked, #return_value.
	 *
	 * @ingroup themeable
	 */
	public static function theme_checkbox($variables) {
		$element = $variables['element'];
		$element['#attributes']['type'] = 'checkbox';
		Intel_Df::element_set_attributes($element, array('id', 'name', '#return_value' => 'value'));

		// Unchecked checkbox has #value of integer 0.
		if (!empty($element['#checked'])) {
			$element['#attributes']['checked'] = 'checked';
		}
		self::_form_set_class($element, array('form-checkbox'));

		return '<input' . Intel_Df::drupal_attributes($element['#attributes']) . ' />';
	}

	/**
	 * Returns HTML for a set of checkbox form elements.
	 *
	 * @param $variables
	 *   An associative array containing:
	 *   - element: An associative array containing the properties of the element.
	 *     Properties used: #children, #attributes.
	 *
	 * @ingroup themeable
	 */
	public static function theme_checkboxes($variables) {
		$element = $variables['element'];
		$attributes = array();
		if (isset($element['#id'])) {
			$attributes['id'] = $element['#id'];
		}
		$attributes['class'][] = 'form-checkboxes';
		if (!empty($element['#attributes']['class'])) {
			$attributes['class'] = array_merge($attributes['class'], $element['#attributes']['class']);
		}
		if (isset($element['#attributes']['title'])) {
			$attributes['title'] = $element['#attributes']['title'];
		}
		return '<div' . Intel_Df::drupal_attributes($attributes) . '>' . (!empty($element['#children']) ? $element['#children'] : '') . '</div>';
	}

	/**
	 * Adds form element theming to an element if its title or description is set.
	 *
	 * This is used as a pre render function for checkboxes and radios.
	 */
	public static function form_pre_render_conditional_form_element($element) {
		//$t = get_t();
		$t = 'Intel_Df::t';
		// Set the element's title attribute to show #title as a tooltip, if needed.
		if (isset($element['#title']) && $element['#title_display'] == 'attribute') {
			$element['#attributes']['title'] = $element['#title'];
			if (!empty($element['#required'])) {
				// Append an indication that this field is required.
				$element['#attributes']['title'] .= ' (' . $t('Required') . ')';
			}
		}

		if (isset($element['#title']) || isset($element['#description'])) {
			$element['#theme_wrappers'][] = 'form_element';
		}
		return $element;
	}

	/**
	 * Sets the #checked property of a checkbox element.
	 */
	public static function form_process_checkbox($element, $form_state) {
		$value = $element['#value'];
		$return_value = $element['#return_value'];
		// On form submission, the #value of an available and enabled checked
		// checkbox is #return_value, and the #value of an available and enabled
		// unchecked checkbox is integer 0. On not submitted forms, and for
		// checkboxes with #access=FALSE or #disabled=TRUE, the #value is
		// #default_value (integer 0 if #default_value is NULL). Most of the time,
		// a string comparison of #value and #return_value is sufficient for
		// determining the "checked" state, but a value of TRUE always means checked
		// (even if #return_value is 'foo'), and a value of FALSE or integer 0 always
		// means unchecked (even if #return_value is '' or '0').
		if ($value === TRUE || $value === FALSE || $value === 0) {
			$element['#checked'] = (bool) $value;
		}
		else {
			// Compare as strings, so that 15 is not considered equal to '15foo', but 1
			// is considered equal to '1'. This cast does not imply that either #value
			// or #return_value is expected to be a string.
			$element['#checked'] = ((string) $value === (string) $return_value);
		}
		return $element;
	}

	/**
	 * Processes a checkboxes form element.
	 */
	public static function form_process_checkboxes($element) {
		$value = is_array($element['#value']) ? $element['#value'] : array();
		$element['#tree'] = TRUE;
		if (count($element['#options']) > 0) {
			if (!isset($element['#default_value']) || $element['#default_value'] == 0) {
				$element['#default_value'] = array();
			}
			$weight = 0;
			foreach ($element['#options'] as $key => $choice) {
				// Integer 0 is not a valid #return_value, so use '0' instead.
				// @see form_type_checkbox_value().
				// @todo For Drupal 8, cast all integer keys to strings for consistency
				//   with form_process_radios().
				if ($key === 0) {
					$key = '0';
				}
				// Maintain order of options as defined in #options, in case the element
				// defines custom option sub-elements, but does not define all option
				// sub-elements.
				$weight += 0.001;

				$element += array($key => array());
				$element[$key] += array(
					'#type' => 'checkbox',
					'#super_type' => 'checkboxes', // flag for element to determine if checkbox is a part of checkboxes
					'#title' => $choice,
					'#return_value' => $key,
					'#default_value' => isset($value[$key]) ? $key : NULL,
					'#attributes' => $element['#attributes'],
					'#ajax' => isset($element['#ajax']) ? $element['#ajax'] : NULL,
					'#weight' => $weight,
				);
			}
		}
		return $element;
	}

	/**
	 * Processes a form actions container element.
	 *
	 * @param $element
	 *   An associative array containing the properties and children of the
	 *   form actions container.
	 * @param $form_state
	 *   The $form_state array for the form this element belongs to.
	 *
	 * @return
	 *   The processed element.
	 */
	public static function form_process_actions($element, &$form_state) {
		$element['#attributes']['class'][] = 'form-actions';
		return $element;
	}

	/**
	 * Processes a container element.
	 *
	 * @param $element
	 *   An associative array containing the properties and children of the
	 *   container.
	 * @param $form_state
	 *   The $form_state array for the form this element belongs to.
	 *
	 * @return
	 *   The processed element.
	 */
	public static function form_process_container($element, &$form_state) {
		// Generate the ID of the element if it's not explicitly given.
		if (!isset($element['#id'])) {
			$element['#id'] = Intel_Df::drupal_html_id(implode('-', $element['#parents']) . '-wrapper');
		}
		return $element;
	}

	/**
	 * Returns HTML to wrap child elements in a container.
	 *
	 * Used for grouped form items. Can also be used as a theme wrapper for any
	 * renderable element, to surround it with a <div> and add attributes such as
	 * classes or an HTML ID.
	 *
	 * See the @link forms_api_reference.html Form API reference @endlink for more
	 * information on the #theme_wrappers render array property.
	 *
	 * @param $variables
	 *   An associative array containing:
	 *   - element: An associative array containing the properties of the element.
	 *     Properties used: #id, #attributes, #children.
	 *
	 * @ingroup themeable
	 */
	public static function theme_container($variables) {
		$element = $variables['element'];
		// Ensure #attributes is set.
		$element += array('#attributes' => array());

		// Special handling for form elements.
		if (isset($element['#array_parents'])) {
			// Assign an html ID.
			if (!isset($element['#attributes']['id'])) {
				$element['#attributes']['id'] = $element['#id'];
			}
			// Add the 'form-wrapper' class.
			$element['#attributes']['class'][] = 'form-wrapper';
		}

		return '<div' . Intel_Df::drupal_attributes($element['#attributes']) . '>' . $element['#children'] . '</div>';
	}

	/**
	 * Returns HTML for a table with radio buttons or checkboxes.
	 *
	 * @param $variables
	 *   An associative array containing:
	 *   - element: An associative array containing the properties and children of
	 *     the tableselect element. Properties used: #header, #options, #empty,
	 *     and #js_select. The #options property is an array of selection options;
	 *     each array element of #options is an array of properties. These
	 *     properties can include #attributes, which is added to the
	 *     table row's HTML attributes; see theme_table(). An example of per-row
	 *     options:
	 *     @code
	 *     $options = array(
	 *       array(
	 *         'title' => 'How to Learn Drupal',
	 *         'content_type' => 'Article',
	 *         'status' => 'published',
	 *         '#attributes' => array('class' => array('article-row')),
	 *       ),
	 *       array(
	 *         'title' => 'Privacy Policy',
	 *         'content_type' => 'Page',
	 *         'status' => 'published',
	 *         '#attributes' => array('class' => array('page-row')),
	 *       ),
	 *     );
	 *     $header = array(
	 *       'title' => t('Title'),
	 *       'content_type' => t('Content type'),
	 *       'status' => t('Status'),
	 *     );
	 *     $form['table'] = array(
	 *       '#type' => 'tableselect',
	 *       '#header' => $header,
	 *       '#options' => $options,
	 *       '#empty' => t('No content available.'),
	 *     );
	 *     @endcode
	 *
	 * @ingroup themeable
	 */
	public static function theme_tableselect($variables) {
		$element = $variables['element'];
		$rows = array();
		$header = $element['#header'];
		if (!empty($element['#options'])) {
			// Generate a table row for each selectable item in #options.
			foreach (element_children($element) as $key) {
				$row = array();

				$row['data'] = array();
				if (isset($element['#options'][$key]['#attributes'])) {
					$row += $element['#options'][$key]['#attributes'];
				}
				// Render the checkbox / radio element.
				$row['data'][] = drupal_render($element[$key]);

				// As theme_table only maps header and row columns by order, create the
				// correct order by iterating over the header fields.
				foreach ($element['#header'] as $fieldname => $title) {
					$row['data'][] = $element['#options'][$key][$fieldname];
				}
				$rows[] = $row;
			}
			// Add an empty header or a "Select all" checkbox to provide room for the
			// checkboxes/radios in the first table column.
			if ($element['#js_select']) {
				// Add a "Select all" checkbox.
				drupal_add_js('misc/tableselect.js');
				array_unshift($header, array('class' => array('select-all')));
			}
			else {
				// Add an empty header when radio buttons are displayed or a "Select all"
				// checkbox is not desired.
				array_unshift($header, '');
			}
		}
		return theme('table', array('header' => $header, 'rows' => $rows, 'empty' => $element['#empty'], 'attributes' => $element['#attributes']));
	}

	/**
	 * Creates checkbox or radio elements to populate a tableselect table.
	 *
	 * @param $element
	 *   An associative array containing the properties and children of the
	 *   tableselect element.
	 *
	 * @return
	 *   The processed element.
	 */
	public static function form_process_tableselect($element) {

		if ($element['#multiple']) {
			$value = is_array($element['#value']) ? $element['#value'] : array();
		}
		else {
			// Advanced selection behavior makes no sense for radios.
			$element['#js_select'] = FALSE;
		}

		$element['#tree'] = TRUE;

		if (count($element['#options']) > 0) {
			if (!isset($element['#default_value']) || $element['#default_value'] === 0) {
				$element['#default_value'] = array();
			}

			// Create a checkbox or radio for each item in #options in such a way that
			// the value of the tableselect element behaves as if it had been of type
			// checkboxes or radios.
			foreach ($element['#options'] as $key => $choice) {
				// Do not overwrite manually created children.
				if (!isset($element[$key])) {
					if ($element['#multiple']) {
						$title = '';
						if (!empty($element['#options'][$key]['title']['data']['#title'])) {
							$title = t('Update @title', array(
								'@title' => $element['#options'][$key]['title']['data']['#title'],
							));
						}
						$element[$key] = array(
							'#type' => 'checkbox',
							'#title' => $title,
							'#title_display' => 'invisible',
							'#return_value' => $key,
							'#default_value' => isset($value[$key]) ? $key : NULL,
							'#attributes' => $element['#attributes'],
							'#ajax' => isset($element['#ajax']) ? $element['#ajax'] : NULL,
						);
					}
					else {
						// Generate the parents as the autogenerator does, so we will have a
						// unique id for each radio button.
						$parents_for_id = array_merge($element['#parents'], array($key));
						$element[$key] = array(
							'#type' => 'radio',
							'#title' => '',
							'#return_value' => $key,
							'#default_value' => ($element['#default_value'] == $key) ? $key : NULL,
							'#attributes' => $element['#attributes'],
							'#parents' => $element['#parents'],
							'#id' => drupal_html_id('edit-' . implode('-', $parents_for_id)),
							'#ajax' => isset($element['#ajax']) ? $element['#ajax'] : NULL,
						);
					}
					if (isset($element['#options'][$key]['#weight'])) {
						$element[$key]['#weight'] = $element['#options'][$key]['#weight'];
					}
				}
			}
		}
		else {
			$element['#value'] = array();
		}
		return $element;
	}

	/**
	 * Processes a machine-readable name form element.
	 *
	 * @param $element
	 *   The form element to process. Properties used:
	 *   - #machine_name: An associative array containing:
	 *     - exists: A function name to invoke for checking whether a submitted
	 *       machine name value already exists. The submitted value is passed as
	 *       argument. In most cases, an existing API or menu argument loader
	 *       function can be re-used. The callback is only invoked, if the submitted
	 *       value differs from the element's #default_value.
	 *     - source: (optional) The #array_parents of the form element containing
	 *       the human-readable name (i.e., as contained in the $form structure) to
	 *       use as source for the machine name. Defaults to array('name').
	 *     - label: (optional) A text to display as label for the machine name value
	 *       after the human-readable name form element. Defaults to "Machine name".
	 *     - replace_pattern: (optional) A regular expression (without delimiters)
	 *       matching disallowed characters in the machine name. Defaults to
	 *       '[^a-z0-9_]+'.
	 *     - replace: (optional) A character to replace disallowed characters in the
	 *       machine name via JavaScript. Defaults to '_' (underscore). When using a
	 *       different character, 'replace_pattern' needs to be set accordingly.
	 *     - error: (optional) A custom form error message string to show, if the
	 *       machine name contains disallowed characters.
	 *     - standalone: (optional) Whether the live preview should stay in its own
	 *       form element rather than in the suffix of the source element. Defaults
	 *       to FALSE.
	 *   - #maxlength: (optional) Should be set to the maximum allowed length of the
	 *     machine name. Defaults to 64.
	 *   - #disabled: (optional) Should be set to TRUE in case an existing machine
	 *     name must not be changed after initial creation.
	 */
	public static function form_process_machine_name($element, &$form_state) {
		// Apply default form element properties.
		$element += array(
			'#title' => t('Machine-readable name'),
			'#description' => t('A unique machine-readable name. Can only contain lowercase letters, numbers, and underscores.'),
			'#machine_name' => array(),
			'#field_prefix' => '',
			'#field_suffix' => '',
			'#suffix' => '',
		);
		// A form element that only wants to set one #machine_name property (usually
		// 'source' only) would leave all other properties undefined, if the defaults
		// were defined in hook_element_info(). Therefore, we apply the defaults here.
		$element['#machine_name'] += array(
			'source' => array('name'),
			'target' => '#' . $element['#id'],
			'label' => t('Machine name'),
			'replace_pattern' => '[^a-z0-9_]+',
			'replace' => '_',
			'standalone' => FALSE,
			'field_prefix' => $element['#field_prefix'],
			'field_suffix' => $element['#field_suffix'],
		);

		// By default, machine names are restricted to Latin alphanumeric characters.
		// So, default to LTR directionality.
		if (!isset($element['#attributes'])) {
			$element['#attributes'] = array();
		}
		$element['#attributes'] += array('dir' => 'ltr');

		// The source element defaults to array('name'), but may have been overidden.
		if (empty($element['#machine_name']['source'])) {
			return $element;
		}

		// Retrieve the form element containing the human-readable name from the
		// complete form in $form_state. By reference, because we may need to append
		// a #field_suffix that will hold the live preview.
		$key_exists = NULL;
		$source = drupal_array_get_nested_value($form_state['complete form'], $element['#machine_name']['source'], $key_exists);
		if (!$key_exists) {
			return $element;
		}

		$suffix_id = $source['#id'] . '-machine-name-suffix';
		$element['#machine_name']['suffix'] = '#' . $suffix_id;

		if ($element['#machine_name']['standalone']) {
			$element['#suffix'] .= ' <small id="' . $suffix_id . '">&nbsp;</small>';
		}
		else {
			// Append a field suffix to the source form element, which will contain
			// the live preview of the machine name.
			$source += array('#field_suffix' => '');
			$source['#field_suffix'] .= ' <small id="' . $suffix_id . '">&nbsp;</small>';

			$parents = array_merge($element['#machine_name']['source'], array('#field_suffix'));
			drupal_array_set_nested_value($form_state['complete form'], $parents, $source['#field_suffix']);
		}

		$js_settings = array(
			'type' => 'setting',
			'data' => array(
				'machineName' => array(
					'#' . $source['#id'] => $element['#machine_name'],
				),
			),
		);
		$element['#attached']['js'][] = 'misc/machine-name.js';
		$element['#attached']['js'][] = $js_settings;

		return $element;
	}

	/**
	 * Form element validation handler for machine_name elements.
	 *
	 * Note that #maxlength is validated by _form_validate() already.
	 */
	public static function form_validate_machine_name(&$element, &$form_state) {
		// Verify that the machine name not only consists of replacement tokens.
		if (preg_match('@^' . $element['#machine_name']['replace'] . '+$@', $element['#value'])) {
			form_error($element, t('The machine-readable name must contain unique characters.'));
		}

		// Verify that the machine name contains no disallowed characters.
		if (preg_match('@' . $element['#machine_name']['replace_pattern'] . '@', $element['#value'])) {
			if (!isset($element['#machine_name']['error'])) {
				// Since a hyphen is the most common alternative replacement character,
				// a corresponding validation error message is supported here.
				if ($element['#machine_name']['replace'] == '-') {
					form_error($element, Intel_Df::t('The machine-readable name must contain only lowercase letters, numbers, and hyphens.'));
				}
				// Otherwise, we assume the default (underscore).
				else {
					form_error($element, Intel_Df::t('The machine-readable name must contain only lowercase letters, numbers, and underscores.'));
				}
			}
			else {
				form_error($element, $element['#machine_name']['error']);
			}
		}

		// Verify that the machine name is unique.
		if ($element['#default_value'] !== $element['#value']) {
			$function = $element['#machine_name']['exists'];
			if ($function($element['#value'], $element, $form_state)) {
				form_error($element, t('The machine-readable name is already in use. It must be unique.'));
			}
		}
	}

	/**
	 * Arranges fieldsets into groups.
	 *
	 * @param $element
	 *   An associative array containing the properties and children of the
	 *   fieldset. Note that $element must be taken by reference here, so processed
	 *   child elements are taken over into $form_state.
	 * @param $form_state
	 *   The $form_state array for the form this fieldset belongs to.
	 *
	 * @return
	 *   The processed element.
	 */
	public static function form_process_fieldset(&$element, &$form_state) {
		$parents = implode('][', $element['#parents']);

		// Each fieldset forms a new group. The #type 'vertical_tabs' basically only
		// injects a new fieldset.
		$form_state['groups'][$parents]['#group_exists'] = TRUE;
		$element['#groups'] = &$form_state['groups'];

		// Process vertical tabs group member fieldsets.
		if (isset($element['#group'])) {
			// Add this fieldset to the defined group (by reference).
			$group = $element['#group'];
			$form_state['groups'][$group][] = &$element;
		}

		// Contains form element summary functionalities.
		$element['#attached']['library'][] = array('system', 'drupal.form');

		// The .form-wrapper class is required for #states to treat fieldsets like
		// containers.
		if (!isset($element['#attributes']['class'])) {
			$element['#attributes']['class'] = array();
			$element['#attributes']['class'][] = 'panel-group';
		}

		// Collapsible fieldsets
		if (!empty($element['#collapsible'])) {
			$element['#attached']['library'][] = array('system', 'drupal.collapse');
			$element['#attributes']['class'][] = 'collapsible';
			if (!empty($element['#collapsed'])) {
				$element['#attributes']['class'][] = 'collapsed';
			}
		}

		return $element;
	}

	/**
	 * Adds members of this group as actual elements for rendering.
	 *
	 * @param $element
	 *   An associative array containing the properties and children of the
	 *   fieldset.
	 *
	 * @return
	 *   The modified element with all group members.
	 */
	public static function form_pre_render_fieldset($element) {
		// Fieldsets may be rendered outside of a Form API context.
		if (!isset($element['#parents']) || !isset($element['#groups'])) {
			return $element;
		}
		// Inject group member elements belonging to this group.
		$parents = implode('][', $element['#parents']);
		$children = Intel_Df::element_children($element['#groups'][$parents]);
		if (!empty($children)) {
			foreach ($children as $key) {
				// Break references and indicate that the element should be rendered as
				// group member.
				$child = (array) $element['#groups'][$parents][$key];
				$child['#group_fieldset'] = TRUE;
				// Inject the element as new child element.
				$element[] = $child;

				$sort = TRUE;
			}
			// Re-sort the element's children if we injected group member elements.
			if (isset($sort)) {
				$element['#sorted'] = FALSE;
			}
		}

		if (isset($element['#group'])) {
			$group = $element['#group'];
			// If this element belongs to a group, but the group-holding element does
			// not exist, we need to render it (at its original location).
			if (!isset($element['#groups'][$group]['#group_exists'])) {
				// Intentionally empty to clarify the flow; we simply return $element.
			}
			// If we injected this element into the group, then we want to render it.
			elseif (!empty($element['#group_fieldset'])) {
				// Intentionally empty to clarify the flow; we simply return $element.
			}
			// Otherwise, this element belongs to a group and the group exists, so we do
			// not render it.
			elseif (Intel_Df::element_children($element['#groups'][$group])) {
				$element['#printed'] = TRUE;
			}
		}

		return $element;
	}

	/**
	 * Creates a group formatted as vertical tabs.
	 *
	 * @param $element
	 *   An associative array containing the properties and children of the
	 *   fieldset.
	 * @param $form_state
	 *   The $form_state array for the form this vertical tab widget belongs to.
	 *
	 * @return
	 *   The processed element.
	 */
	public static function form_process_vertical_tabs($element, &$form_state) {
		// Inject a new fieldset as child, so that form_process_fieldset() processes
		// this fieldset like any other fieldset.
		$element['group'] = array(
			'#type' => 'fieldset',
			'#theme_wrappers' => array(),
			'#parents' => $element['#parents'],
		);

		// The JavaScript stores the currently selected tab in this hidden
		// field so that the active tab can be restored the next time the
		// form is rendered, e.g. on preview pages or when form validation
		// fails.
		$name = implode('__', $element['#parents']);
		if (isset($form_state['values'][$name . '__active_tab'])) {
			$element['#default_tab'] = $form_state['values'][$name . '__active_tab'];
		}
		$element[$name . '__active_tab'] = array(
			'#type' => 'hidden',
			'#default_value' => $element['#default_tab'],
			'#attributes' => array('class' => array('vertical-tabs-active-tab')),
		);

		return $element;
	}

	/**
	 * Returns HTML for an element's children fieldsets as vertical tabs.
	 *
	 * @param $variables
	 *   An associative array containing:
	 *   - element: An associative array containing the properties and children of
	 *     the fieldset. Properties used: #children.
	 *
	 * @ingroup themeable
	 */
	public static function theme_vertical_tabs($variables) {
		$element = $variables['element'];
		// Add required JavaScript and Stylesheet.
		// TODO WP
		//drupal_add_library('system', 'drupal.vertical-tabs');

		$output = '<h2 class="element-invisible">' . t('Vertical Tabs') . '</h2>';
		$output .= '<div class="vertical-tabs-panes">' . $element['#children'] . '</div>';
		return $output;
	}

	/**
	 * Returns HTML for a submit button form element.
	 *
	 * @param $variables
	 *   An associative array containing:
	 *   - element: An associative array containing the properties of the element.
	 *     Properties used: #attributes, #button_type, #name, #value.
	 *
	 * @ingroup themeable
	 */
	public static function theme_submit($variables) {
		return Intel_Df::theme('button', $variables['element']);
	}

	/**
	 * Returns HTML for a button form element.
	 *
	 * @param $variables
	 *   An associative array containing:
	 *   - element: An associative array containing the properties of the element.
	 *     Properties used: #attributes, #button_type, #name, #value.
	 *
	 * @ingroup themeable
	 */
	public static function theme_button($variables) {
		$element = $variables['element'];
		$element['#attributes']['type'] = 'submit';
		Intel_Df::element_set_attributes($element, array('id', 'name', 'value'));

		$has_btn_class = !empty($element['#attributes']['class']) && in_array('btn', $element['#attributes']['class']);

		$element['#attributes']['class'][] = 'form-' . $element['#button_type'];
		if (!$has_btn_class) {
			$element['#attributes']['class'][] = 'btn';
			$element['#attributes']['class'][] = 'btn-info';
		}

		if (!empty($element['#attributes']['disabled'])) {
			$element['#attributes']['class'][] = 'form-button-disabled';
		}

		return '<input' . Intel_Df::drupal_attributes($element['#attributes']) . ' />';
	}

	/**
	 * Returns HTML for an image button form element.
	 *
	 * @param $variables
	 *   An associative array containing:
	 *   - element: An associative array containing the properties of the element.
	 *     Properties used: #attributes, #button_type, #name, #value, #title, #src.
	 *
	 * @ingroup themeable
	 */
	public static function theme_image_button($variables) {
		$element = $variables['element'];
		$element['#attributes']['type'] = 'image';
		Intel_Df::element_set_attributes($element, array('id', 'name', 'value'));

		$element['#attributes']['src'] = file_create_url($element['#src']);
		if (!empty($element['#title'])) {
			$element['#attributes']['alt'] = $element['#title'];
			$element['#attributes']['title'] = $element['#title'];
		}

		$element['#attributes']['class'][] = 'form-' . $element['#button_type'];
		if (!empty($element['#attributes']['disabled'])) {
			$element['#attributes']['class'][] = 'form-button-disabled';
		}

		return '<input' . Intel_Df::drupal_attributes($element['#attributes']) . ' />';
	}

	/**
	 * Returns HTML for a hidden form element.
	 *
	 * @param $variables
	 *   An associative array containing:
	 *   - element: An associative array containing the properties of the element.
	 *     Properties used: #name, #value, #attributes.
	 *
	 * @ingroup themeable
	 */
	public static function theme_hidden($variables) {
		$element = $variables['element'];
		$element['#attributes']['type'] = 'hidden';
		Intel_Df::element_set_attributes($element, array('name', 'value'));
		return '<input' . Intel_Df::drupal_attributes($element['#attributes']) . " />\n";
	}

	/**
	 * Process function to prepare autocomplete data.
	 *
	 * @param $element
	 *   A textfield or other element with a #autocomplete_path.
	 *
	 * @return array
	 *   The processed form element.
	 */
	function form_process_autocomplete($element) {
		$element['#autocomplete_input'] = array();
		if ($element['#autocomplete_path'] && drupal_valid_path($element['#autocomplete_path'])) {
			$element['#autocomplete_input']['#id'] = $element['#id'] .'-autocomplete';
			// Force autocomplete to use non-clean URLs since this protects against the
			// browser interpreting the path plus search string as an actual file.
			$current_clean_url = isset($GLOBALS['conf']['clean_url']) ? $GLOBALS['conf']['clean_url'] : NULL;
			$GLOBALS['conf']['clean_url'] = 0;
			// Force the script path to 'index.php', in case the server is not
			// configured to find it automatically. Normally it is the responsibility
			// of the site to do this themselves using hook_url_outbound_alter() (see
			// url()) but since this code is forcing non-clean URLs on sites that don't
			// normally use them, it is done here instead.
			$element['#autocomplete_input']['#url_value'] = url($element['#autocomplete_path'], array('absolute' => TRUE, 'script' => 'index.php'));
			$GLOBALS['conf']['clean_url'] = $current_clean_url;
		}
		return $element;
	}

	/**
	 * Returns HTML for a textfield form element.
	 *
	 * @param $variables
	 *   An associative array containing:
	 *   - element: An associative array containing the properties of the element.
	 *     Properties used: #title, #value, #description, #size, #maxlength,
	 *     #required, #attributes, #autocomplete_path.
	 *
	 * @ingroup themeable
	 */
	public static function theme_textfield($variables) {
		$element = $variables['element'];
		$element['#attributes']['type'] = 'text';
		Intel_Df::element_set_attributes($element, array('id', 'name', 'value', 'size', 'maxlength'));
		self::_form_set_class($element, array('form-text', 'form-control'));

		$extra = '';
		if ($element['#autocomplete_path'] && !empty($element['#autocomplete_input'])) {
			//drupal_add_library('system', 'drupal.autocomplete');
			$element['#attributes']['class'][] = 'form-autocomplete';

			$attributes = array();
			$attributes['type'] = 'hidden';
			$attributes['id'] = $element['#autocomplete_input']['#id'];
			$attributes['value'] = $element['#autocomplete_input']['#url_value'];
			$attributes['disabled'] = 'disabled';
			$attributes['class'][] = 'autocomplete';

			$extra = '<input' .  Intel_Df::drupal_attributes($attributes) . ' />';
		}

		$output = '<input' .  Intel_Df::drupal_attributes($element['#attributes']) . ' />';

		return $output . $extra;
	}



	/**
	 * Returns HTML for a textarea form element.
	 *
	 * @param $variables
	 *   An associative array containing:
	 *   - element: An associative array containing the properties of the element.
	 *     Properties used: #title, #value, #description, #rows, #cols, #required,
	 *     #attributes
	 *
	 * @ingroup themeable
	 */
	public static function theme_textarea($variables) {
		$element = $variables['element'];
		Intel_Df::element_set_attributes($element, array('id', 'name', 'cols', 'rows'));
		self::_form_set_class($element, array('form-textarea'));

		$wrapper_attributes = array(
			'class' => array('form-textarea-wrapper'),
		);

		// Add resizable behavior.
		if (!empty($element['#resizable'])) {
			// TODO WP
			//drupal_add_library('system', 'drupal.textarea');
			//$wrapper_attributes['class'][] = 'resizable';
		}

		$output = '<div' . Intel_Df::drupal_attributes($wrapper_attributes) . '>';
		$value = !empty($element['#value']) ? $element['#value'] : '';
		$output .= '<textarea' . Intel_Df::drupal_attributes($element['#attributes']) . '>' . Intel_Df::check_plain($value) . '</textarea>';
		$output .= '</div>';
		return $output;
	}

	/**
	 * Returns HTML for a form.
	 *
	 * @param $variables
	 *   An associative array containing:
	 *   - element: An associative array containing the properties of the element.
	 *     Properties used: #action, #method, #attributes, #children
	 *
	 * @ingroup themeable
	 */
	public static function theme_form($variables) {
		$element = $variables['element'];
		if (isset($element['#action'])) {
			// TODO WP
			//$element['#attributes']['action'] = drupal_strip_dangerous_protocols($element['#action']);
			$element['#attributes']['action'] = $element['#action'];
		}
		Intel_Df::element_set_attributes($element, array('method', 'id'));
		if (empty($element['#attributes']['accept-charset'])) {
			$element['#attributes']['accept-charset'] = "UTF-8";
		}
		// Anonymous DIV to satisfy XHTML compliance.
		return '<form' . Intel_Df::drupal_attributes($element['#attributes']) . '><div>' . $element['#children'] . '</div></form>';
	}

	/**
	 * Returns HTML for a password form element.
	 *
	 * @param $variables
	 *   An associative array containing:
	 *   - element: An associative array containing the properties of the element.
	 *     Properties used: #title, #value, #description, #size, #maxlength,
	 *     #required, #attributes.
	 *
	 * @ingroup themeable
	 */
	public static function theme_password($variables) {
		$element = $variables['element'];
		$element['#attributes']['type'] = 'password';
		Intel_Df::element_set_attributes($element, array('id', 'name', 'size', 'maxlength'));
		self::_form_set_class($element, array('form-text'));

		return '<input' . Intel_Df::drupal_attributes($element['#attributes']) . ' />';
	}

	/**
	 * Expands a weight element into a select element.
	 */
	public static function form_process_weight($element) {
		$element['#is_weight'] = TRUE;

		// If the number of options is small enough, use a select field.
		//$max_elements = variable_get('drupal_weight_select_max', DRUPAL_WEIGHT_SELECT_MAX);
		$max_elements = 1000;
		if ($element['#delta'] <= $max_elements) {
			$element['#type'] = 'select';
			for ($n = (-1 * $element['#delta']); $n <= $element['#delta']; $n++) {
				$weights[$n] = $n;
			}
			$element['#options'] = $weights;
			$element += intel()->element_info('select');
		}
		// Otherwise, use a text field.
		else {
			$element['#type'] = 'textfield';
			// Use a field big enough to fit most weights.
			$element['#size'] = 10;
			$element['#element_validate'] = array('element_validate_integer');
			$element += intel()->element_info('textfield');
		}

		return $element;
	}

	/**
	 * Returns HTML for a file upload form element.
	 *
	 * For assistance with handling the uploaded file correctly, see the API
	 * provided by file.inc.
	 *
	 * @param $variables
	 *   An associative array containing:
	 *   - element: An associative array containing the properties of the element.
	 *     Properties used: #title, #name, #size, #description, #required,
	 *     #attributes.
	 *
	 * @ingroup themeable
	 */
	public static function theme_file($variables) {
		$element = $variables['element'];
		$element['#attributes']['type'] = 'file';
		Intel_Df::element_set_attributes($element, array('id', 'name', 'size'));
		self::_form_set_class($element, array('form-file'));

		return '<input' . Intel_Df::drupal_attributes($element['#attributes']) . ' />';
	}


	/**
	 * Returns HTML for a form element.
	 *
	 * Each form element is wrapped in a DIV container having the following CSS
	 * classes:
	 * - form-item: Generic for all form elements.
	 * - form-type-#type: The internal element #type.
	 * - form-item-#name: The internal form element #name (usually derived from the
	 *   $form structure and set via form_builder()).
	 * - form-disabled: Only set if the form element is #disabled.
	 *
	 * In addition to the element itself, the DIV contains a label for the element
	 * based on the optional #title_display property, and an optional #description.
	 *
	 * The optional #title_display property can have these values:
	 * - before: The label is output before the element. This is the default.
	 *   The label includes the #title and the required marker, if #required.
	 * - after: The label is output after the element. For example, this is used
	 *   for radio and checkbox #type elements as set in system_element_info().
	 *   If the #title is empty but the field is #required, the label will
	 *   contain only the required marker.
	 * - invisible: Labels are critical for screen readers to enable them to
	 *   properly navigate through forms but can be visually distracting. This
	 *   property hides the label for everyone except screen readers.
	 * - attribute: Set the title attribute on the element to create a tooltip
	 *   but output no label element. This is supported only for checkboxes
	 *   and radios in form_pre_render_conditional_form_element(). It is used
	 *   where a visual label is not needed, such as a table of checkboxes where
	 *   the row and column provide the context. The tooltip will include the
	 *   title and required marker.
	 *
	 * If the #title property is not set, then the label and any required marker
	 * will not be output, regardless of the #title_display or #required values.
	 * This can be useful in cases such as the password_confirm element, which
	 * creates children elements that have their own labels and required markers,
	 * but the parent element should have neither. Use this carefully because a
	 * field without an associated label can cause accessibility challenges.
	 *
	 * @param $variables
	 *   An associative array containing:
	 *   - element: An associative array containing the properties of the element.
	 *     Properties used: #title, #title_display, #description, #id, #required,
	 *     #children, #type, #name.
	 *
	 * @ingroup themeable
	 */
	public static function theme_form_element($variables) {
		$element = &$variables['element'];

		// This function is invoked as theme wrapper, but the rendered form element
		// may not necessarily have been processed by form_builder().
		$element += array(
			'#title_display' => 'before',
		);

		// Add element #id for #type 'item'.
		if (isset($element['#markup']) && !empty($element['#id'])) {
			$attributes['id'] = $element['#id'];
		}
		// Add element's #type and #name as class to aid with JS/CSS selectors.
		$attributes['class'] = array('form-item');
		if (!empty($element['#type'])) {
			$attributes['class'][] = 'form-type-' . strtr($element['#type'], '_', '-');
		}
		if (!empty($element['#name'])) {
			$attributes['class'][] = 'form-item-' . strtr($element['#name'], array(' ' => '-', '_' => '-', '[' => '-', ']' => ''));
		}

		// add bootstrap classes
		$type = !empty($element['#type']) ? $element['#type'] : '';
		if ($type == 'checkbox') {
			$attributes['class'][] = 'checkbox';
		}
		else {
			$attributes['class'][] = 'form-group';
		}

		if (isset($element['#parents']) && self::form_get_error($element) !== NULL && !empty($element['#validated'])) {
			$attributes['class'][] = 'has-error';
		}

		// Add a class for disabled elements to facilitate cross-browser styling.
		if (!empty($element['#attributes']['disabled'])) {
			$attributes['class'][] = 'form-disabled';
		}
		$output = '<div' . Intel_Df::drupal_attributes($attributes) . '>' . "\n";

		// If #title is not set, we don't display any label or required marker.
		if (!isset($element['#title'])) {
			$element['#title_display'] = 'none';
		}
		$prefix = !empty($element['#field_prefix']) ? '<span class="input-group-addon">' . $element['#field_prefix'] . '</span> ' : '';
		$suffix = !empty($element['#field_suffix']) ? ' <span class="input-group-addon">' . $element['#field_suffix'] . '</span>' : '';

		// Bootstrap wants input inside label, so a little hacking
		if ($type == 'checkbox') {
			$element['#children'] = $prefix . $element['#children'] . $suffix;
			$output .= ' ' . Intel_Df::theme('form_element_label', $variables) . "\n";
		}
		else {
			switch ($element['#title_display']) {
				case 'before':
				case 'invisible':
					$output .= ' ' . Intel_Df::theme('form_element_label', $variables);
					$output .= ' <div class="input-group">' . $prefix . $element['#children'] . $suffix . "</div>\n";
					break;

				case 'after':
					$output .= ' ' . $prefix . $element['#children'] . $suffix;
					$output .= ' ' . Intel_Df::theme('form_element_label', $variables) . "\n";
					break;

				case 'none':
				case 'attribute':
					// Output no label and no required marker, only the children.
					$output .= ' ' . $prefix . $element['#children'] . $suffix . "\n";
					break;
			}
		}


		if (!empty($element['#description'])) {
			$output .= '<div class="description">' . $element['#description'] . "</div>\n";
		}

		$output .= "</div>\n";

		return $output;
	}

	public static function theme_form_required_marker($variables) {
		return '<span class="form-required" title="' . Intel_Df::t('This field is required.') . '">*</span>';
	}

	/**
	 * Returns HTML for a form element label and required marker.
	 *
	 * Form element labels include the #title and a #required marker. The label is
	 * associated with the element itself by the element #id. Labels may appear
	 * before or after elements, depending on theme_form_element() and
	 * #title_display.
	 *
	 * This function will not be called for elements with no labels, depending on
	 * #title_display. For elements that have an empty #title and are not required,
	 * this function will output no label (''). For required elements that have an
	 * empty #title, this will output the required marker alone within the label.
	 * The label will use the #id to associate the marker with the field that is
	 * required. That is especially important for screenreader users to know
	 * which field is required.
	 *
	 * @param $variables
	 *   An associative array containing:
	 *   - element: An associative array containing the properties of the element.
	 *     Properties used: #required, #title, #id, #value, #description.
	 *
	 * @ingroup themeable
	 */
	public static function theme_form_element_label($variables) {
		$element = $variables['element'];
		// This is also used in the installer, pre-database setup.
		//$t = get_t(); TODO WP
		$t = 'Intel_Df::t';

		// If title and required marker are both empty, output no label.
		if ((!isset($element['#title']) || $element['#title'] === '') && empty($element['#required'])) {
			return '';
		}

		// If the element is required, a required marker is appended to the label.
		$required = !empty($element['#required']) ? Intel_Df::theme('form_required_marker', array('element' => $element)) : '';

		//$title = filter_xss_admin($element['#title']);
		$title = $element['#title'];

		$attributes = array();
		$attributes['class'] = 'control-label';

		// Style the label as class option to display inline with the element.
		if ($element['#title_display'] == 'after') {
			$attributes['class'] = ' option';
			// if element is a single checkbox, add control-label class
			if (($element['#type'] == 'checkbox') && (empty($element['#super_type']) || $element['#super_type'] != 'checkboxes')) {
				$attributes['class'] .= ' control-label';
			}
			// if element is a single radio, add control-label class
			if (($element['#type'] == 'radio') && (empty($element['#super_type']) || $element['#super_type'] != 'radios')) {
				$attributes['class'] .= ' control-label';
			}
		}
		// Show label only to screen readers to avoid disruption in visual flows.
		elseif ($element['#title_display'] == 'invisible') {
			$attributes['class'] = ' element-invisible';
		}

		if (!empty($element['#id'])) {
			$attributes['for'] = $element['#id'];
		}

		if ($element['#type'] == 'checkbox') {
			return ' <label' . Intel_Df::drupal_attributes($attributes) . '>' . $element['#children'] . call_user_func($t, '!title !required', array('!title' => $title, '!required' => $required)) . "</label>\n";
		}

		// The leading whitespace helps visually separate fields from inline labels.
		return ' <label' . Intel_Df::drupal_attributes($attributes) . '>' . call_user_func($t, '!title !required', array('!title' => $title, '!required' => $required)) . "</label>\n";
	}

	public static function confirm_form($form, $question, $path, $description = NULL, $yes = NULL, $no = NULL, $name = 'confirm') {
		$description = isset($description) ? $description : Intel_Df::t('This action cannot be undone.');

		// Prepare cancel link.
		if (isset($_GET['destination'])) {
			$options = Intel_Df::drupal_parse_url($_GET['destination']);
		}
		elseif (is_array($path)) {
			$options = $path;
		}
		else {
			$options = array('path' => $path);
		}

		//Intel_Df::drupal_set_title($question);
		Intel_Df::drupal_set_title(Intel_Df::t('Confirm'));

		$form['#attributes']['class'][] = 'confirmation';
		$form['question'] = array(
			'#type' => 'markup',
			'#markup' => '<div class="alert alert-warning">' . $question . '</div>',
		);
		$form['description'] = array('#markup' => $description);
		$form[$name] = array('#type' => 'hidden', '#value' => 1);

		$form['actions'] = array('#type' => 'actions');

		$form['actions']['submit'] = array(
			'#type' => 'submit',
			'#value' => $yes ? $yes : Intel_Df::t('Confirm'),
		);
		$form['actions']['cancel'] = array(
			'#type' => 'link',
			'#title' => $no ? $no : Intel_Df::t('Cancel'),
			'#href' => $options['path'],
			'#options' => $options,
			'#attributes' => array(
				'class' => array(
					'btn',
					'btn-link'
				)
			)
		);


		// By default, render the form using theme_confirm_form().
		if (!isset($form['#theme'])) {
			$form['#theme'] = 'confirm_form';
		}
		return $form;
	}






}
