<?php
/**
* This is a ... for WordPress
*
* @version SVN: $Id: class.wp-admin-page.php 109998 2009-04-12 14:13:03Z Mrasnika $
* @author Kaloyan K. Tsvetkov <kaloyan@kaloyan.info>
* @link http://kaloyan.info/blog/wp-admin-page/
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*/

/////////////////////////////////////////////////////////////////////////////

/**
* @internal check if some other plugin has already imported this class
*/
if (class_exists('wp_admin_page')) {
	return ;
	}

/////////////////////////////////////////////////////////////////////////////

/**
* ...
*/
Class wp_admin_page {

	/**
	*/
	Function admin_head() {
		}

	/**
	*
	*/
	Function wp_admin_page () {

		// attach the `admin_head` hook
		//
		add_action('admin_head', array(&$this, 'admin_head'));

		// operation ?
		//
		$action = (isset($_POST['action']) && $_POST['action'])
			? $_POST['action']
			: (
				(isset($_POST['action2']) && $_POST['action2'])
					? $_POST['action2']
					: null
			);

		if ($action) {
			$callback = array($this, 'action_' . $action);
			if (is_callable($callback)) {
				call_user_func($callback);
				exit;
				}
			}
		}

	/**
	*/
	Function run($id = null) {
		$id = isset($_REQUEST['id']) ? $_REQUEST['id'] : null;
		return $id ? $this->edit($id) : $this->index();
		}

	/**
	*/
	Function index() {
		trigger_error(
			sprintf('You have to override the [%s::%s()] method.',
				get_class($this),
				__FUNCTION__
				),
			E_USER_ERROR
			);
		}

	/**
	*/
	Function edit() {
		trigger_error(
			sprintf('You have to override the [%s::%s()] method.',
				get_class($this),
				__FUNCTION__
				),
			E_USER_ERROR
			);
		}

	// -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- --

	/**
	* Performs a redirect
	*
	* @param string $url
	* @param boolean $force whether to attempt javasctipt/meta-tag redirection
	* @access public
	* @static
	*/
	Function redirect($url, $force = 1) {
		
		if (!$force && !headers_sent()) {
			header('Location: ' . $url);
			exit;
			}
		
		echo '<html>
<head><meta http-equiv="refresh" content="5;URL=' , $url, '" /></head>
<body><script type="text/javascript">document.location.href = \'', $url, '\';</script></body>
</html>';
		exit;
		}

	// -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- --

	/**
	* Renders an OK message
	*
	* If a $_GET element with the provided {$input_name} is
	* detected, the provided $msg will be rendered.
	*
	* @param string $msg
	* @param string $input_name name of the $_GET element
	* @param string $additional additional HTML that you might want appended
	*/
	Function ok($msg, $input_name, $additional = '') {

		if (!isset($_GET[$input_name]) || !$_GET[$input_name]) {
			return;
			}

		?>
<div id="message" class="updated fade">
	<p><?php _e($msg);
	echo $additional;
	?></p>
</div>
		<?php
		$_SERVER['REQUEST_URI'] = remove_query_arg(
			array($input_name),
			$_SERVER['REQUEST_URI']
			);
		}

	/**
	* Renders an OK message for a batch operation
	*
	* If a $_GET element with the provided {$input_name} is detected, the 
	* provided message will be rendered. The message will change depending on 
	* the number of the records, affected by the batch operation.
	*
	* @param string $msg_1 message for a single record
	* @param string $msg_n message for N records
	* @param string $input_name name of the $_GET element
	* @param string $additional additional HTML that you might want appended
	*/
	Function ok_batch($msg_1, $msg_n, $input_name, $additional = '') {
		
		if (!isset($_GET[$input_name]) || !$_GET[$input_name]) {
			return;
			}

		?>
<div id="message" class="updated fade">
	<p><?php printf(
		__ngettext( $msg_1, $msg_n, $_GET[$input_name] ),
		number_format_i18n( $_GET[$input_name] )
		);
	echo $additional;
	?></p>
</div>
		<?php
		$_SERVER['REQUEST_URI'] = remove_query_arg(
			array($input_name),
			$_SERVER['REQUEST_URI']
			);
		}

// -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- --

	/**
	* Error handling
	*
	* This method serves dual purpose: when an argument is provided, it is 
	* stored to the collection of errors; when there is no argument, the 
	* collection of errors (if any) is rendered
	*
	* @param string $error
	* @param boolean $render whether to render the errors
	* @static
	*/
	Function error($error = null, $render = 0) {
		
		// error container
		//
		static $_errors;
		
		// store new error ?
		//
		if ($error) {
			$_errors[] = $error;
			return true;
			}
		
		// no errors to render ? 
		//
		if (!$_errors) {
			return 0;
			}

		// render the errors
		//
		if (!!$render) { ?>
<div class="error">
	<ul>
		<li><p><?php echo join("</p></li>\r\n\t\t<li><p>", $_errors);  ?></p></li>
	</ul>
</div>
<?php		}

		return 1;
		}

	// -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- --
	
	/**
	*/
	Function search($search_query = '') {

		if (!$search_query) {
			$search_query = isset($_GET['s']) ? $_GET['s'] : '';
			}

		if ($search_query) {
			printf(
				'<span class="subtitle"> &ndash; '
					. __('Search results for &#8220;%s&#8221;')
					. '</span>',
				wp_specialchars( $_GET['s'] )
				);
			}
		}

	/**
	*/
	Function search_form($search_query = '', $params = array()) {

		if (!$search_query) {
			$search_query = isset($_GET['s']) ? $_GET['s'] : '';
			}
?>
<form id="wp-admin-page-search" action="" method="get">
	<?php foreach($params As $k=>$v) {
		echo "<input type=\"hidden\" name=\"{$k}\" value=\"{$v}\" />\r\n";
		} ?>
	<p class="search-box">
		<label class="hidden" for="wp-admin-page-search-input">Search:</label>
		<input type="text" class="search-input" id="wp-admin-page-search-input" name="s"
			value="<?php echo wp_specialchars( $search_query ); ?>" />
		<input type="submit" value="<?php _e( 'Search' ); ?>" class="button" />
	</p>
</form>
<?php
		}

	// -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- --

	/**
	* Sets the admin menu for {@link wp_admin_page} based admin pages.
	*
	* Use this method to set the admin menu: you just need to pass an array 
	* with the extra menu commands that you want to add, and then this amazing 
	* method will do everything on its own.
	*
	* @param string $file_prefix
	* @param array $new_menu
	* @return wp_admin_page
	*/
	Function &admin_menu($file_prefix = '', $new_menu = null) {

		static $_menu = array();
		static $_page = null;
		static $_selected = '';

		// store the menu structure 
		//
		if (is_array($new_menu)) {

			$_menu = array_merge_recursive($new_menu, $_menu);
			
			// controller found ?
			//
			$menu = null;
			if (isset($_REQUEST['page'])) {
				foreach($new_menu as $top) {
					if (isset($top['submenu'][$_REQUEST['page']])) {
	
						$menu = $top['submenu'][$_REQUEST['page']];
						$menu['page'] = $_REQUEST['page'];
						break;
						}
					}
				}

			// attach the hook
			//
			$c = array(__CLASS__, __FUNCTION__);
			if (!has_action('admin_menu', $c)) {
				add_action('admin_menu', $c);
				}

			// create the page controller
			//
			if ($menu) {
				
				// include the file
				//
				include_once(
					$file_prefix . $menu['file']
					);

				// the page controller
				//
				$_page = new $menu['class'];
				$_selected = $menu['page'];

				return $_page;
				}

			return false;
			}
		
		// attach the menus
		//
		$core_files = array(
			'index.php',
			'tools.php',
			'options-general.php',
			'themes.php',
			'users.php',
			'profile.php',
			'edit.php',
			'upload.php',
			'link-manager.php',
			'edit-pages.php',
			'edit-comments.php'
			);
	
		// walk the top-level menus
		//
		foreach ($_menu As $file=>$menu) {
			
			// default values ?
			//
			$menu = $menu + array(
				'page_title' => $file,
				'menu_title' => $file,
				'level' => 8,
				'submenu' => array(),
				);

			// inject a top-level page ?
			//
			if (!in_array($file, $core_files)) {

				add_menu_page(
					$menu['page_title'],
					$menu['menu_title'],
					$menu['level'],
					$file
					);
				}
			
			// walk the submenus
			//
			$parent = $file;
			foreach ($menu['submenu'] as $file=>$menu) {

				// default values ?
				//
				$menu = $menu + array(
					'page_title' => $file,
					'menu_title' => $file,
					'level' => 8,
					'method' => 'run',
					);

				$callback = ($file == $_selected)
					? array(&$_page, $menu['method'])
					: 'pi';

				// inject a sub-level page
				//
				add_submenu_page(
					$parent, 
					$menu['page_title'],
					$menu['menu_title'],
					$menu['level'],
					$file, $callback
					);
				}
			}
		}

	// -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- --

	/**
	* Render a list of options
	*
	* @param array $options key/value pairs for the options
	* @param mixed $selected key(s) of the selected options
	* @static
	*/
	Function html_options($options, $selected) {
		
		// convert to array in order to
		// allow multiple selected options
		//
		if (is_scalar($selected)) {
			$selected = array($selected);
			} else {
			$selected = (array) $selected;
			}

		// render the options
		//
		foreach ($options as $k=>$v) {
			echo '<option value="', $k , (
				in_array($k, $selected)
					? '" selected="selected"> &rarr; '
					: '">'
				), $v, '&nbsp; </option>';
			}
		}

	// -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- --
	
	/**
	* Render a list of radio buttons
	*
	* @param string $name name for the radio button inputs
	* @param array $options key/value pairs for the options
	* @param mixed $selected key of the selected options; if the selected
	*	value is not found in the keys of the $options argument, then
	*	the first key will be automatically selected
	* @static
	*/
	Function html_radios($name, $options, $selected) {
		
		// check if selection is valid
		//
		if (!in_array($selected, $k = array_keys($options))) {
			$selected = $k[0];
			}
		
		// render the radio buttons
		//
		foreach ($options as $k=>$v) {
			$id = 'hr_' . md5($k . $v);
			echo '<label for="', $id, '">',
				'<input class="chckbx" type="radio" name="' , $name, '" ', (
					$selected == $k
						? ' checked="checked" '
						: '' ),'
				value="', $k , '" id="', $id, '"/>', (
					$selected == $k
						? "<em>{$v}</em>"
						: $v
					), ' </label>';
			}
		}

	// -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- --

	//--end-of-class--
	}