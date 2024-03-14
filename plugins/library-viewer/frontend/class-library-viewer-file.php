<?php

/**
 * Class Library_Viewer_File.
 *
 * This class extends the `Library_Viewer_File_Alias` class.
 * `Library_Viewer_File_Alias` class is an alias of the class
 * that name is the last item of array that is returned by `lv_shortcode_class_names` filter.
 *
 * @since 2.0.0
 */
class Library_Viewer_File extends Library_Viewer_File_Alias
{
	/**
	 * All the useful variables that methods use,
	 * parameters are included.
	 *
	 * SOS: for each parameter that isn't passed to the shortcode,
	 * $globals keep its DEFAULT value, that is initialized
	 * in `init_globals_default_values` method,
	 * except these that is initialized as null and defined in `init_global_{$global}` method.
	 *
	 * @since 2.0.0
	 * @var array $globals{
	 * 		The globals variables.
	 *
	 * 		@type array $have_file_access The shortcode parameter `have_file_access`, as an array!
	 * 		@type string $my_doc_viewer The shortcode parameter `my_doc_viewer`.
	 * 		@type string $login_page The shortcode parameter `login_page`.
	 * 		@type string $path For Library Viewer, `path` is `library`. Pro parameter!
	 * 		@type array $hidden_folders For Library Viewer, `$hidden_folders` is `hidden-folder`. Pro parameter!
	 * 		@type array $hidden_files For Library Viewer, `$hidden_files` are .php', '.ini', 'hidden-file'. Pro parameter!
	 *		@type string $waiting_seconds For Library Viewer, `$waiting_seconds` are `5'. Pro parameter!
	 * 		@type string $current_viewer The current viewer. Are you viewing a folder or a file?
	 * 									 If you are viewing a folder, current_viewer is `folder`.
	 * 									 If you are viewing a file, current_viewer is `file`.
	 * 		@type string $current_page_url Full current URL without get parameters.
	 * 		@type string $file_identifier String that identifies if file will be loaded. Default is `LV`.
	 * 		@type array $folder_fake_path_symbols These symbols will replace the `$folder_real_path_symbols`,
	 * 											  if the fake path of folder was called.
	 * 		@type array $folder_real_path_symbols These symbols will replace the `$folder_fake_path_symbols`,
	 * 											  if the real path of folder will be asked.
	 * 											  (real path is the relative path of the folder)
	 * 		@type array $file_fake_path_symbols These symbols will replace the `$file_real_path_symbols`,
	 * 											if the fake path of file was called.
	 * 		@type array $file_real_path_symbols These symbols will replace the `$file_fake_path_symbols`,
	 * 											if the real path of file will be asked.
	 * 											(real path is the relative path of the file)
	 * 		@type string $file_fake_link The link (fake link) of current file (the path after the /$file_identifier/).
	 * 		@type string $file_real_link The real (relative) link of current file.
	 * 		@type string $file_abs_path The absolute path of current file.
	 * 		@type string $file_name The name of the file, with the extension.
	 * 		@type string $file_extension The extension of the file.
	 *		@type string $file_folder_real_link The real (relative) link of folder contains the current file.
	 * 		@type string $file_folder_abs_path The absolute path of folder contains the current file.
	 * }
	 */
	protected $globals = array();

	/**
	 * The array file status property.
	 * Can be filtered by Library Viewer Pro.
	 *
	 * @since 2.0.0
	 * @var array $file_status{
	 * 		The file status array.
	 *
	 * 		@type array $headers The headers that will be sent. Isn an array with numeric keys.
	 * 							 Default is empty array.
	 * 		@type string $readfile The file that will be displayed. Default is empty string.
	 * 		@type string $error_message The error message that will be displayed in wordpress error page,
	 * 									according to $action. Default is empty string.
	 * 		@type string $error_page_title The wordpress error page title (second parameter of wp_die function).
	 * }
	 */
	protected $file_status = array(
		'headers'		   => array(),
		'readfile'		   => '',
		'error_message'	   => '',
		'error_page_title' => '',
	);

	protected function Library_Viewer_File__rest_globals($rest_globals)
	{
		return array('current_viewer', 'file_identifier', 'current_page_url', 'folder_fake_path_symbols', 'folder_real_path_symbols',
			'file_fake_path_symbols', 'file_real_path_symbols', 'file_fake_link', 'file_real_link', 'file_abs_path', 'file_name', 'file_extension', 'file_folder_real_path', 'file_folder_abs_path');
	}


	protected function init_parameters($parameters)
	{
		$this->init_global_file_identifier();
		$this->init_global_current_page_url();
		$this->init_global_shortcode_page_link();

		$shortcode_page_link = $this->globals['shortcode_page_link'];

		$library_viewer_shortcodes = get_option('library-viewer-shortcodes');

		if ( isset($library_viewer_shortcodes[$shortcode_page_link]) ) {
			$this->parameters = $library_viewer_shortcodes[$shortcode_page_link];
		} else {
			$this->parameters = array();
		}
	}

	protected function Library_Viewer_Shortcode__init_globals_before_init_parameters()
	{
		$this->globals['current_viewer'] = 'file';
	}

	/**
	 * Inits the global `file_extension` variable.
	 *
	 * @since 2.0.0
	 */
	protected function init_global_file_extension()
	{
		$this->globals['file_extension'] = pathinfo( $this->globals['file_real_link'],PATHINFO_EXTENSION );
	}

	/**
	 * Inits the global `file_name` variable.
	 *
	 * @since 2.0.0
	 */
	protected function init_global_file_name()
	{
		$this->globals['file_name'] = $this->basename( $this->globals['file_real_link'] );
	}

	/**
	 * Inits the global `shortcode_page_link` variable.
	 *
	 * @since 2.0.0
	 */
	protected function init_global_shortcode_page_link()
	{
		extract($this->globals);

		$temp = explode("/$file_identifier/", $current_page_url);
		$this->globals['shortcode_page_link'] = $temp[0];
	}

	/**
	 * Inits the global `file_real_link` variable.
	 *
	 * @since 2.0.0
	 */
	protected function init_global_file_real_link()
	{
		extract($this->globals);

		$file_real_link = str_replace(
			$file_fake_path_symbols,
			$file_real_path_symbols,
			$file_fake_link
		);

		$this->globals['file_real_link'] = $this->path_prefix() . $file_real_link;
	}

	/**
	 * Inits the global `file_fake_link` variable.
	 *
	 * @since 2.0.0
	 */
	protected function init_global_file_fake_link()
	{
		extract($this->globals);

		$temp = explode("/$file_identifier/", $current_page_url);

		$this->globals['file_fake_link'] = $temp[1];
	}

	/**
	 * Inits the global `file_abs_path` variable.
	 *
	 * @since 2.0.0
	 */
	protected function init_global_file_abs_path()
	{
		$this->globals['file_abs_path'] = ABSPATH . $this->globals['file_real_link'];
	}

	/**
	 * Inits the global `file_folder_real_path` variable.
	 *
	 * @since 2.0.5
	 */
	protected function init_global_file_folder_real_path()
	{
		$expl = explode('/', $this->globals['file_real_link']);
		$expl[count($expl)-1] = '';

		$this->globals['file_folder_real_path'] = implode('/', $expl);
	}

	/**
	 * Inits the global `file_folder_abs_path` variable.
	 *
	 * @since 2.0.5
	 */
	protected function init_global_file_folder_abs_path()
	{
		$this->globals['file_folder_abs_path'] = ABSPATH . $this->globals['file_folder_real_path'];

	}

	/**
	 * File Viewer method.
	 *
	 * Sends the appropriate headers, prints the contents of file and exit the code.
	 *
	 * @since 2.0.0
	 */
	public function call_file_viewer()
	{
		$action = $this->get_file_action();

		if ( 'view_file' == $action )
		{
			$this->do_lv_file_was_viewed_action();

			$this->view_file__init_file_status();
		}
		elseif ( 'redirect_to_login' == $action )
		{
			$waiting_seconds = $this->globals['waiting_seconds'];
			$this->file_status['headers'][] = 'refresh:' . $waiting_seconds . '; url=' . $this->get_redirect_to_url();

			$message  = '<span style="font-size:20px; display:block; text-align:center;">';
			$message .= library_viewer_error('redirect_to_login', $waiting_seconds);
			$message .= '<br><img src="' . LIBRARY_VIEWER_PLUGIN_DIR_URL . 'assets/loading.gif">';
			$message .= '</span>';

			$this->file_status['error_message'] = $message;
			$this->file_status['error_page_title'] = library_viewer_error('redirection_page_title');

		}
		else {
			$this->file_status['error_message'] = library_viewer_error($action);
		}

		/**
		 * Filter the status of the file.
		 *
		 * This array determines which headers will be sent,
		 * possible error messages and
		 * which file will be read if needed.
		 *
		 * @since 2.0.0
		 *
		 * @param array $file_status {
		 * 		The array that contains the file status details.
		 *
		 * 		@type array $header {
		 * 			This array contains the headers that will be sent. Default is empty array.
		 *
		 * 			@type string $key The header that will be sent.
		 * 		}
		 * 		@type string $error_message The error message that will be displayed in wordpress error page,
		 * 									according to $action. Default is empty string.
		 * 		@type string $error_page_title The wordpress error page title (second parameter of wp_die function).
		 * 		@type string $readfile The file that will be displayed. Default is empty string.
		 * }
		 * @param string $action The value that `lv_file_action` filter returns.
		 * @param array $this->globals See property's documentation.
		 */
		$this->file_status = apply_filters('lv_file_status', $this->file_status, $action, $this->globals);

		// Clean any output
		error_reporting(0);
		ob_get_clean();

		foreach ($this->file_status['headers'] as $header) {
			header($header);
		}

		if( !empty($this->file_status['readfile']) ){
			readfile($this->file_status['readfile']);
			exit;
		} else {
			return $this->file_status;
		}

	}

	/**
	 * Returns the file action.
	 *
	 * @since 2.0.0
	 *
	 * @return string $action The file action. Accepts `view_file`, `file_not_allowed`, `file_not_exists`,
	 * 						  `php_forbidden`, `no_appropriate_capabilities`, `redirect_to_login`.
	 */
	protected function get_file_action()
	{
		extract($this->globals);

		/**
		 * @since 1.2.0
		 * @since 2.0.0
		 */
		if( array() === $this->parameters )// shortcode parameters haven't been saved
		{
			$action = 'file_not_allowed';
		}
		elseif( ! $this->is_dir_accessible($file_fake_link) )
		{
			$action = 'file_not_allowed';
		}
		elseif($this->is_current_folder_hidden($file_folder_real_path))
		{
			$action = 'file_not_allowed';
		}
		elseif($this->is_file_hidden($file_name))
		{
			$action = 'file_not_allowed';
		}
		elseif( !is_file($file_real_link) )
		{
			$action = 'file_not_exists';
		}
		elseif( 'php' === $file_extension )
		{
			$action = 'php_forbidden';
		}
		elseif( 'all' == $have_file_access[0] )
		{
			$action = 'view_file';
		}
		elseif( is_user_logged_in() )
		{
			//FROM 1.1.0
			foreach($have_file_access as $capability){
				if( 'logged_in' == $capability || current_user_can($capability) ){
					$action = 'view_file';
					break;
				}
			}
			if( !isset($action) ) {
				$action = 'no_appropriate_capabilities';
			}
		}
		else
		{
			$action = 'redirect_to_login';
		}

		/**
		 * File action filter.
		 *
		 * Determine which action the file viewer will do.
		 *
		 * @since 2.0.0
		 *
		 * @param string $action The file action. Accepts `view_file`, `file_not_allowed`, `file_not_exists`,
		 *                       `php_forbidden`, `no_appropriate_capabilities`, `redirect_to_login`.
		 * @param array $this->globals See property's documentation.
		 */
		$action = apply_filters('lv_file_action', $action, $this->globals);

		return $action;
	}

	/**
	 * Method that do_action('lv_file_was_viewed', ...)
	 *
	 * @since 2.0.0
	 */
	protected function do_lv_file_was_viewed_action()
	{
		extract($this->globals);

		$deprecated_args = array($file_name, $file_extension);
		do_action_deprecated('LV__file_was_viewed', $deprecated_args, '2.0.0', 'lv_file_was_viewed');

		/**
		 * File was viewed action.
		 *
		 * Do some actions if a file was accessed/viewed.
		 *
		 * @since 2.0.0
		 *
		 * @param string $file_name The name of file.
		 * @param string $file_extension The extension of the file.
		 * @param array $this->globals See property's documentation.
		 */
		do_action('lv_file_was_viewed', $file_name, $file_extension, $this->globals);
	}

	/**
	 * Inits the array property `file_status` for action `view_file`.
	 *
	 * @since 2.0.0
	 */
	protected function view_file__init_file_status()
	{
		extract($this->globals);

		$mime_types = $this->get_supported_mime_types();

		$mime_type_found = false;
		foreach($mime_types as $mime_types_key => $mime_type){
			$_extensions = explode('|', $mime_types_key);
			foreach($_extensions as $_extension){
				if( $_extension == $file_extension ){
					$mime_type_found = true;
					break;
				}
			}
			if ( true === $mime_type_found ) {
				break;
			}

		}

		if ( false === $mime_type_found ) {// download
			$this->file_status['headers'][] = 'Content-Description: File Transfer';
			$this->file_status['headers'][] = 'Content-disposition: attachment; filename="' . $file_name . '"';
			$this->file_status['readfile']  = $file_abs_path;
		} else {// open file
			$this->file_status['headers'][] = "Content-type: $mime_type";
			$this->file_status['headers'][] = 'Content-disposition: inline; filename="' . $file_name . '"';
			$this->file_status['readfile']  = $file_abs_path;
		}
	}

	/**
	 * Method that returns the supported mime types for Library Viewer's file viewer.
	 *
	 * @since 2.0.0
	 *
	 * @return array $mime_types The supported mime types.
	 */
	protected function get_supported_mime_types()
	{

		$mime_types = wp_get_mime_types(); //wordpress default mime types

		//FROM 1.1.2
		$deprecated_args = array($mime_types);
		$mime_types  = apply_filters_deprecated('LV__mime_types', $deprecated_args, '2.0.0', 'lv_mime_types');

		/**
		 * Supported mime types filter.
		 *
		 * With this filter we can add or remove support for specific mime types.
		 * Examples below.
		 *
		 * @since 2.0.0
		 *
		 * @param array $mime_types The supported mime types. Default is the wp_get_mime_types() array.
		 * @param string $file_extension The file extension.
		 * @param array $this->globals See property's documentation.
		 *
		 * [wordpress default mime types] => Array(
		 *		[jpg|jpeg|jpe] => image/jpeg
		 *		[gif] => image/gif
		 *		[png] => image/png
		 *		[bmp] => image/bmp
		 *		[tiff|tif] => image/tiff
		 *		[ico] => image/x-icon
		 *		[asf|asx] => video/x-ms-asf
		 *		[wmv] => video/x-ms-wmv
		 *		[wmx] => video/x-ms-wmx
		 *		[wm] => video/x-ms-wm
		 *		[avi] => video/avi
		 *		[divx] => video/divx
		 *		[flv] => video/x-flv
		 *		[mov|qt] => video/quicktime
		 *		[mpeg|mpg|mpe] => video/mpeg
		 *		[mp4|m4v] => video/mp4
		 *		[ogv] => video/ogg
		 *		[webm] => video/webm
		 *		[mkv] => video/x-matroska
		 *		[3gp|3gpp] => video/3gpp
		 *		[3g2|3gp2] => video/3gpp2
		 *		[txt|asc|c|cc|h|srt] => text/plain
		 *		[csv] => text/csv
		 *		[tsv] => text/tab-separated-values
		 *		[ics] => text/calendar
		 *		[rtx] => text/richtext
		 *		[css] => text/css
		 *		[htm|html] => text/html
		 *		[vtt] => text/vtt
		 *		[dfxp] => application/ttaf+xml
		 *		[mp3|m4a|m4b] => audio/mpeg
		 *		[aac] => audio/aac
		 *		[ra|ram] => audio/x-realaudio
		 *		[wav] => audio/wav
		 *		[ogg|oga] => audio/ogg
		 *		[flac] => audio/flac
		 *		[mid|midi] => audio/midi
		 *		[wma] => audio/x-ms-wma
		 *		[wax] => audio/x-ms-wax
		 *		[mka] => audio/x-matroska
		 *		[rtf] => application/rtf
		 *		[js] => application/javascript
		 *		[pdf] => application/pdf
		 *		[swf] => application/x-shockwave-flash
		 *		[class] => application/java
		 *		[tar] => application/x-tar
		 *		[zip] => application/zip
		 *		[gz|gzip] => application/x-gzip
		 *		[rar] => application/rar
		 *		[7z] => application/x-7z-compressed
		 *		[exe] => application/x-msdownload
		 *		[psd] => application/octet-stream
		 *		[xcf] => application/octet-stream
		 *		[doc] => application/msword
		 *		[pot|pps|ppt] => application/vnd.ms-powerpoint
		 *		[wri] => application/vnd.ms-write
		 *		[xla|xls|xlt|xlw] => application/vnd.ms-excel
		 *		[mdb] => application/vnd.ms-access
		 *		[mpp] => application/vnd.ms-project
		 *		[docx] => application/vnd.openxmlformats-officedocument.wordprocessingml.document
		 *		[docm] => application/vnd.ms-word.document.macroEnabled.12
		 *		[dotx] => application/vnd.openxmlformats-officedocument.wordprocessingml.template
		 *		[dotm] => application/vnd.ms-word.template.macroEnabled.12
		 *		[xlsx] => application/vnd.openxmlformats-officedocument.spreadsheetml.sheet
		 *		[xlsm] => application/vnd.ms-excel.sheet.macroEnabled.12
		 *		[xlsb] => application/vnd.ms-excel.sheet.binary.macroEnabled.12
		 *		[xltx] => application/vnd.openxmlformats-officedocument.spreadsheetml.template
		 *		[xltm] => application/vnd.ms-excel.template.macroEnabled.12
		 *		[xlam] => application/vnd.ms-excel.addin.macroEnabled.12
		 *		[pptx] => application/vnd.openxmlformats-officedocument.presentationml.presentation
		 *		[pptm] => application/vnd.ms-powerpoint.presentation.macroEnabled.12
		 *		[ppsx] => application/vnd.openxmlformats-officedocument.presentationml.slideshow
		 *		[ppsm] => application/vnd.ms-powerpoint.slideshow.macroEnabled.12
		 *		[potx] => application/vnd.openxmlformats-officedocument.presentationml.template
		 *		[potm] => application/vnd.ms-powerpoint.template.macroEnabled.12
		 *		[ppam] => application/vnd.ms-powerpoint.addin.macroEnabled.12
		 *		[sldx] => application/vnd.openxmlformats-officedocument.presentationml.slide
		 *		[sldm] => application/vnd.ms-powerpoint.slide.macroEnabled.12
		 *		[onetoc|onetoc2|onetmp|onepkg] => application/onenote
		 *		[oxps] => application/oxps
		 *		[xps] => application/vnd.ms-xpsdocument
		 *		[odt] => application/vnd.oasis.opendocument.text
		 *		[odp] => application/vnd.oasis.opendocument.presentation
		 *		[ods] => application/vnd.oasis.opendocument.spreadsheet
		 *		[odg] => application/vnd.oasis.opendocument.graphics
		 *		[odc] => application/vnd.oasis.opendocument.chart
		 *		[odb] => application/vnd.oasis.opendocument.database
		 *		[odf] => application/vnd.oasis.opendocument.formula
		 *		[wp|wpd] => application/wordperfect
		 *		[key] => application/vnd.apple.keynote
		 *		[numbers] => application/vnd.apple.numbers
		 *		[pages] => application/vnd.apple.pages
		 * )
		 *
		 * TIPS:
		 * - If you want to force every file to be downloaded, you can hook the $mime_types variable and return an empty array
		 *   and set the shortcode parameter my_doc_viewer="library-viewer"
		 *	 Example Code:
		 *   add_filter('lv_mime_types', function(){
		 *   	return array();
		 *   });
		 *
		 * - If you want to force files with specific extension to be downloaded, just unset the value from the array.
		 *   Example Code:
		 *   add_filter('lv_mime_types', function($mime_types){
		 * 		unset( $mime_types['pdf'] ); //force only pdfs to be downloaded
		 * 		return $mime_types;
		 *   });
		 */
		$mime_types = apply_filters( 'lv_mime_types', $mime_types, $this->globals);

		return $mime_types;

	}

	/**
	 * Method that returns the login url with the get parameter `redirect_to` encoded.
	 *
	 * @since 2.0.0
	 *
	 * @return string $url Login url with the get `redirect_to`.
	 */
	protected function get_redirect_to_url()
	{
		extract($this->globals);

		$login_page = site_url($login_page);
		$url		= "$login_page?redirect_to=" . urlencode( $current_page_url );

		/**
		 * Redirection to URL filter.
		 *
		 * With this filter, you can filter the url that the user is redirected to login page
		 * when attempting to view a file that
		 * is restricted to logged in users or users with specific capabilities/roles.
		 * When the user will login, will redirected to that URL of file.
		 *
		 * @since 2.0.0
		 *
		 * @param string $url The full URL that user will redirected to
		 *                    if hasn't the capabilities to view the file.
		 * @param string $current_page_url The library viewer's URL of the file.
		 *                                 Not the actual URL of the file. This that will be encoded
		 *                                 and be passed to GET parameter `redirect_to` of $url.
		 * @param array $this->globals See property's documentation.
		 */
		$url = apply_filters('lv_redirect_to_url', $url, $current_page_url, $this->globals);

		return $url;
	}


}
