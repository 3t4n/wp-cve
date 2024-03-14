<?php

/**
 * $Id: hacklogra.class.php 1863150 2018-04-23 18:54:26Z ihacklog $
 * $Revision: 1863150 $
 * $Date: 2018-04-23 18:54:26 +0000 (Mon, 23 Apr 2018) $
 * @package Hacklog Remote Attachment
 * @encoding UTF-8
 * @author 荒野无灯 <HuangYeWuDeng>
 * @link http://80x86.io
 * @copyright Copyright (C) 2011 荒野无灯
 * @license http://www.gnu.org/licenses/
 */
class hacklogra
{
	const textdomain = 'hacklog-remote-attachment';
	const plugin_name = 'Hacklog Remote Attachment';
	const opt_space = 'hacklogra_remote_filesize';
	const opt_primary = 'hacklogra_options';
	const version = '1.2.8';
	private static $img_ext = array('jpg', 'jpeg', 'png', 'gif', 'bmp');
	private static $ftp_user = 'admin';
	private static $ftp_pwd = '4d4173594c77453d';
	private static $ftp_server = '172.30.16.31';
	private static $ftp_port = 21;
	private static $ftp_timeout = 30;
	private static $subdir = '';
	private static $ftp_remote_path = 'wp-files';
	private static $http_remote_path = 'wp-files';
	private static $remote_url = '';
	private static $remote_baseurl = '';
	private static $local_basepath = '';
	private static $local_path = '';
	private static $local_url = '';
	private static $local_baseurl = '';
	private static $fs = null;

	public function __construct()
	{
		self::init();
		//this should always check
		add_action('admin_notices', array(__CLASS__, 'check_ftp_connection'));
		//should load before 'admin_menu' hook ... so,use init hook  
		add_action('init', array(__CLASS__, 'load_textdomain'));
		//menu
		add_action('admin_menu', array(__CLASS__, 'plugin_menu'));
		//HOOK the upload , use init to support xmlrpc upload
		add_action('init', array(__CLASS__, 'admin_init'));
		//frontend filter,filter on image only
		add_filter('wp_get_attachment_url', array(__CLASS__, 'replace_baseurl'), -999);
	}

############################## PRIVATE FUNCTIONS ##############################################

	private static function encrypt($plain_text)
	{
		if (!class_exists('Crypt'))
		{
			require dirname(__FILE__) . '/crypt.class.php';
		}
		$cypher = new Crypt(Crypt::CRYPT_MODE_HEXADECIMAL, Crypt::CRYPT_HASH_SHA1);
		$cypher->Key = AUTH_KEY;
		return $cypher->encrypt($plain_text);
	}

	private static function decrypt($encrypted)
	{
		if (!class_exists('Crypt'))
		{
			require dirname(__FILE__) . '/crypt.class.php';
		}
		$cypher = new Crypt(Crypt::CRYPT_MODE_HEXADECIMAL, Crypt::CRYPT_HASH_SHA1);
		$cypher->Key = AUTH_KEY;
		return $cypher->decrypt($encrypted);
	}

	private static function update_options()
	{
		$value = self::get_default_opts();
		$keys = array_keys($value);
		foreach ($keys as $key)
		{
			if (!empty($_POST[$key]))
			{
				$value[$key] = addslashes(trim($_POST[$key]));
				if ('ftp_pwd' == $key)
				{
					$value[$key] = self::encrypt($value[$key]);
				}
			}
		}
		$value['remote_baseurl'] = rtrim($value['remote_baseurl'], '/');
		$value['ftp_remote_path'] = rtrim($value['ftp_remote_path'], '/');
		$value['http_remote_path'] = rtrim($value['http_remote_path'], '/');
		if (update_option(self::opt_primary, $value))
			return TRUE;
		else
			return FALSE;
	}

	/**
	 * get file extension
	 * @static
	 * @param $path
	 * @return mixed
	 */
	private static function get_ext($path)
	{
		return pathinfo($path, PATHINFO_EXTENSION);
	}

	/**
	 * to see if a file is an image file.
	 * @static
	 * @param $path
	 * @return bool
	 */
	private static function is_image_file($path)
	{
		return in_array(self::get_ext($path), self::$img_ext);
	}

	/**
	 * get the default options
	 * @static
	 * @return array
	 */
	private static function get_default_opts()
	{
		return array(
			'ftp_user' => self::$ftp_user,
			'ftp_pwd' => self::$ftp_pwd,
			'ftp_server' => self::$ftp_server,
			'ftp_port' => self::$ftp_port,
			'ftp_timeout' => self::$ftp_timeout,
			'ftp_remote_path' => self::$ftp_remote_path,
			'http_remote_path' => self::$http_remote_path,
			'remote_baseurl' => self::$remote_baseurl,
		);
	}

	/**
	 * increase the filesize,keep the filesize tracked.
	 * @static
	 * @param $file
	 * @return void
	 */
	private static function update_filesize_used($file)
	{
		if (file_exists($file))
		{
			$filesize = filesize($file);
			$previous_value = get_option(self::opt_space);
			$to_save = $previous_value + $filesize;
			update_option(self::opt_space, $to_save);
		}
	}

	/**
	 * decrease the filesize when a remote file is deleted.
	 * @static
	 * @param $fs
	 * @param $file
	 * @return void
	 */
	private static function decrease_filesize_used($fs, $file)
	{
		if ($fs->exists($file))
		{
			$filesize = $fs->size($file);
			$previous_value = get_option(self::opt_space);
			$to_save = $previous_value - $filesize;
			update_option(self::opt_space, $to_save);
		}
	}

	/**
	 * like  wp_handle_upload_error in file.php under wp-admin/includes
	 * @param type $file
	 * @param type $message
	 * @return type 
	 */
	function handle_upload_error($message)
	{
		return array('error' => $message);
	}

	static function xmlrpc_error($errorString = '')
	{
		return new IXR_Error(500, $errorString);
	}

	/**
	 * report upload error
	 * @return type 
	 */
	private static function raise_upload_error()
	{
		$error_str = sprintf('%s:' . __('upload file to remote server failed!', self::textdomain), self::plugin_name);
		if (defined('XMLRPC_REQUEST'))
		{
			return self::xmlrpc_error($error_str);
		}
		else
		{
			return call_user_func(array(__CLASS__, 'handle_upload_error'), $error_str);
		}
	}

	/**
	 * report FTP connection error
	 * @return type 
	 */
	private static function raise_connection_error()
	{
		$error_str = sprintf('%s:' . self::$fs->errors->get_error_message(), self::plugin_name);
		if (defined('XMLRPC_REQUEST'))
		{
			return self::xmlrpc_error($error_str);
		}
		else
		{
			return call_user_func(array(__CLASS__, 'handle_upload_error'), $error_str);
		}
	}

############################## PUBLIC FUNCTIONS ##############################################
	/**
	 * init
	 * @static
	 * @return void
	 */

	public static function init()
	{
		register_activation_hook(HACKLOG_RA_LOADER, array(__CLASS__, 'my_activation'));
		register_deactivation_hook(HACKLOG_RA_LOADER, array(__CLASS__, 'my_deactivation'));
		$opts = get_option(self::opt_primary);
		self::$ftp_user = $opts['ftp_user'];
		self::$ftp_pwd = $opts['ftp_pwd'];
		self::$ftp_server = $opts['ftp_server'];
		self::$ftp_port = $opts['ftp_port'];
		self::$ftp_timeout = $opts['ftp_timeout'] > 0 ? $opts['ftp_timeout'] : 30;

		$opts['ftp_remote_path'] = rtrim($opts['ftp_remote_path'], '/');
		$opts['http_remote_path'] = rtrim($opts['http_remote_path'], '/');
		$opts['remote_baseurl'] = rtrim($opts['remote_baseurl'], '/');
		$upload_dir = wp_upload_dir();
		//be aware of / in the end
		self::$local_basepath = $upload_dir['basedir'];
		self::$local_path = $upload_dir['path'];
		self::$local_baseurl = $upload_dir['baseurl'];
		self::$local_url = $upload_dir['url'];
		self::$subdir = $upload_dir['subdir'];
		//if the post publish date was different from the media upload date,the time should take from the database.
		if (get_option('uploads_use_yearmonth_folders') && isset($_REQUEST['post_id']))
		{
			$post_id = (int) $_REQUEST['post_id'];
			if ($post = get_post($post_id))
			{
				if (substr($post->post_date, 0, 4) > 0)
				{
					$time = $post->post_date;
					$y = substr($time, 0, 4);
					$m = substr($time, 5, 2);
					$subdir = "/$y/$m";
					self::$subdir = $subdir;
				}
			}
		}
		//后面不带 /
		self::$ftp_remote_path = $opts['ftp_remote_path'];
		self::$http_remote_path = $opts['http_remote_path'];
		//此baseurl与options里面的不同！
		self::$remote_baseurl = '.' == self::$http_remote_path ? $opts['remote_baseurl'] :
				$opts['remote_baseurl'] . '/' . self::$http_remote_path;
		self::$remote_url = self::$remote_baseurl . self::$subdir;
	}

	/**
	 * do the stuff once the plugin is installed
	 * @static
	 * @return void
	 */
	public static function my_activation()
	{
		add_option(self::opt_space, 0);
		$opt_primary = self::get_default_opts();
		add_option(self::opt_primary, $opt_primary);
	}

	/**
	 * do cleaning stuff when the plugin is deactivated.
	 * @static
	 * @return void
	 */
	public static function my_deactivation()
	{
		//delete_option(self::opt_space);
		//delete_option(self::opt_primary);
	}

	private static function get_opt($key, $defaut='')
	{
		$opts = get_option(self::opt_primary);
		return isset($opts[$key]) ? $opts[$key] : $defaut;
	}

	/**
	 * humanize file size.
	 * @static
	 * @param $bytes
	 * @return string
	 */
	public static function human_size($bytes)
	{
		$types = array('B', 'KB', 'MB', 'GB', 'TB');
		for ($i = 0; $bytes >= 1024 && $i < (count($types) - 1); $bytes /= 1024, $i++)
			;
		return (round($bytes, 2) . " " . $types[$i]);
	}

	/**
	 * load the textdomain on init
	 * @static
	 * @return void
	 */
	public static function load_textdomain()
	{
		load_plugin_textdomain(self::textdomain, false, basename(dirname(HACKLOG_RA_LOADER)) . '/languages/');
	}

	/**
	 * @since 1.2.1
	 * note that admin_menu runs before admin_init
	 */
	public static function admin_init()
	{
		//DO NOT HOOK the update or upgrade page for that they may upload zip file.
		$current_page = basename($_SERVER['SCRIPT_FILENAME']);
		switch ($current_page)
		{
			//	wp-admin/update.php?action=upload-plugin
			//	wp-admin/update.php?action=upload-theme
			case 'update.php':
			//update-core.php?action=do-core-reinstall
			case 'update-core.php':
				//JUST DO NOTHING ,SKIP.
				break;
			default:
				add_filter('wp_handle_upload', array(__CLASS__, 'upload_and_send'));
				add_filter('media_send_to_editor', array(__CLASS__, 'replace_attachurl'), -999);
				add_filter('attachment_link', array(__CLASS__, 'replace_baseurl'), -999);
                add_filter('wp_calculate_image_srcset', array(__CLASS__, 'replace_attachurl_srcset'), -999, 5);
				//生成缩略图后立即上传生成的文件并删除本地文件,this must after watermark generate
				add_filter('wp_update_attachment_metadata', array(__CLASS__, 'upload_images'), 999);
				//删除远程附件
				add_action('wp_delete_file', array(__CLASS__, 'delete_remote_file'));
				break;
		}
	}

	/**
	 * set up ftp connection
	 * @static
	 * @param $args
	 * @return bool
	 */
	public static function setup_ftp($args)
	{
		require_once(ABSPATH . 'wp-admin/includes/class-wp-filesystem-base.php');
		//ftpext or ftpsockets
		//if php has enabled ftp module ,use it ,else ,uses sockets API
		$method = function_exists('ftp_login') ? 'ftpext' : 'ftpsockets';
		if (!class_exists("WP_Filesystem_$method"))
		{
			$abstraction_file = ABSPATH . 'wp-admin/includes/class-wp-filesystem-' . $method . '.php';
			if (!file_exists($abstraction_file))
			{
				return false;
			}
			require_once($abstraction_file);
		}
		$method = "WP_Filesystem_$method";
		self::$fs = new $method($args);
		//Define the timeouts for the connections. Only available after the construct is called to allow for per-transport overriding of the default.
		if (!defined('FS_CONNECT_TIMEOUT'))
			define('FS_CONNECT_TIMEOUT', self::$ftp_timeout);
		if (!defined('FS_TIMEOUT'))
			define('FS_TIMEOUT', 30);

		if (is_wp_error($wp_filesystem->errors) && $wp_filesystem->errors->get_error_code())
			return false;

		if (!self::$fs->connect())
			return false; //There was an erorr connecting to the server.



			
// Set the permission constants if not already set.
		if (!defined('FS_CHMOD_DIR'))
			define('FS_CHMOD_DIR', 0755);
		if (!defined('FS_CHMOD_FILE'))
			define('FS_CHMOD_FILE', 0644);

		return true;
	}

	/**
	 * do connecting to server.DO NOT call this on any page that not needed!
	 * if can not connect to remote server successfully,the plugin will refuse to work
	 * @static
	 * @return void
	 */
	public static function connect_remote_server()
	{
		//if object not inited.
		if (null == self::$fs)
		{
			$credentials = array(
				'hostname' => self::$ftp_server,
				'port' => self::$ftp_port,
				'username' => self::$ftp_user,
				'password' => self::decrypt(self::$ftp_pwd),
				'ssl' => FALSE,
			);
			if (!self::setup_ftp($credentials))
			{
				return FALSE;
			}
		}
		return self::$fs;
	}

	/**
	 * notice the user to setup the plugin options
	 */
	public static function check_ftp_connection()
	{
		$current_page = basename($_SERVER['SCRIPT_FILENAME']);
		if ('plugins.php' == $current_page)
		{
			if (!self::connect_remote_server())
			{
				$error = self::raise_connection_error();
				$redirect_msg = sprintf(__('Click <a href="%s">here</a> to setup the plugin options.', self::textdomain), admin_url('options-general.php?page=' . md5(HACKLOG_RA_LOADER)));
				echo '<div class="error"><p><strong>' . $error['error'] . '<br />' . $redirect_msg . '</strong></p></div>';
			}
		}
	}

	/**
	 * the hook is in function get_attachment_link()
	 * @static
	 * @param $html
	 * @return mixed
	 */
	public static function replace_attachurl($html)
	{
		$html = str_replace(self::$local_url, self::$remote_url, $html);
		return $html;
	}

    /**
     * @param $sources
     * @param $size_array
     * @param $image_src
     * @param $image_meta
     * @param $attachment_id
     * @return mixed
     */
    public static function replace_attachurl_srcset($sources, $size_array, $image_src, $image_meta, $attachment_id)
    {
        $local_url = self::$local_url;
        // using the same logic as WP
        global $wp_version;
        if ( version_compare($wp_version, "4.5", '>=') && is_ssl() && 'https' !== substr( $local_url, 0, 5 ) && parse_url( $local_url, PHP_URL_HOST ) === $_SERVER['HTTP_HOST'] ) {
            $local_url = set_url_scheme( $local_url, 'https' );
        }
        foreach((array) $sources as $index => $source) {
            $sources[$index]['url'] = str_replace($local_url, self::$remote_url, $source['url']);
        }
        return $sources;
    }

	/**
	 * the hook is in function media_send_to_editor
	 * @static
	 * @param $html
	 * @return mixed
	 */
	public static function replace_baseurl($html)
	{
		$html = str_replace(self::$local_baseurl, self::$remote_baseurl, $html);
		return $html;
	}

	/**
	 * handle orig image file and other files.
	 * @static
	 * @param $file
	 * @return array|mixed
	 */
	public static function upload_and_send($file)
	{
		/**
		 * 泥马， xmlrpc mw_newMediaObject 方法中的 wp_handle_upload HOOK  file 参数仅为文件名！
		 */
		if (defined('XMLRPC_REQUEST'))
		{
			$file['file'] = self::$local_path . '/' . $file['file'];
		}
		if (!self::connect_remote_server())
		{
			//failed ,delete the orig file
			file_exists($file['file']) && unlink($file['file']);
			return self::raise_connection_error();
		}
		$upload_error_handler = 'wp_handle_upload_error';

		$local_basename = basename($file['file']);
		$local_basename_unique = self::unique_filename(self::$ftp_remote_path . self::$subdir, $local_basename);
		/**
		 * since we can not detect wether remote file is duplicated or not.
		 * if remote already has the file,then first rename local filename to target.after this,the file uploaded to remote
		 * server should not overwrote the existed file.
		 */
		if ($local_basename_unique != $local_basename)
		{
			$local_full_filename = dirname($file['file']) . '/' . $local_basename_unique;
			@rename($file['file'], $local_full_filename);
			$file['file'] = $local_full_filename;
		}
		$localfile = $file['file'];
		//file path on remote server
		$remotefile = self::$ftp_remote_path . self::$subdir . '/' . $local_basename_unique;
		$remote_subdir = dirname($remotefile);
		$remote_subdir = str_replace('\\', '/', $remote_subdir);
		if (!self::$fs->is_dir($remote_subdir))
		{
			//make sure the dir on FTP server is exists.
			$subdir = explode('/', $remote_subdir);
			$i = 0;
			$dir_needs = '';
			while (isset($subdir[$i]) && !empty($subdir[$i])) {
				$dir_needs .= $subdir[$i] . '/';
				!self::$fs->is_dir($dir_needs) && self::$fs->mkdir($dir_needs, 0755);
				//disable directory browser
				!self::$fs->is_file($dir_needs . 'index.html') && self::$fs->put_contents($dir_needs . 'index.html', 'Silence is golden.');
				++$i;
			}
			if (!self::$fs->is_dir($remote_subdir))
			{
				$error_str = sprintf('%s:' . __('failed to make dir on remote server!Please check your FTP permissions.', self::textdomain), self::plugin_name);
				if (defined('XMLRPC_REQUEST'))
				{
					return self::xmlrpc_error($error_str);
				}
				else
				{
					return call_user_func($upload_error_handler, $file, $error_str);
				}
			}
		}
		
		//xmlrpc 得在这里处理url
		$file['url'] = str_replace(self::$local_url, self::$remote_url, $file['url']);
		//如果是图片，此处不处理，因为要与水印插件兼容的原因　
		if (self::is_image_file($file['file']))
		{
			//对xmlrpc 这里又给它还原
			if (defined('XMLRPC_REQUEST'))
			{
				$file['file'] = basename($file['file']);
			}
			return $file;
		}
		$content = file_get_contents($localfile);
		//        return array('error'=> $remotefile);
		if (!self::$fs->put_contents($remotefile, $content, 0644))
		{
			$error_str = sprintf('%s:' . __('upload file to remote server failed!', self::textdomain), self::plugin_name);
			if (defined('XMLRPC_REQUEST'))
			{
				return self::xmlrpc_error($error_str);
			}
			else
			{
				return call_user_func($upload_error_handler, $file, $error_str);
			}
		}
		unset($content);
		//uploaded successfully
		self::update_filesize_used($localfile);
		//delete the local file
		file_exists($file['file']) && unlink($file['file']);
		//对于非图片文件，且为xmlrpc的情况，还原file参数
		if (defined('XMLRPC_REQUEST'))
		{
			$file['file'] = basename($file['file']);
		}
		return $file;
	}

	/**
	 * 上传缩略图到远程服务器并删除本地服务器文件
	 * @static
	 * @param $metadata from function wp_generate_attachment_metadata
	 * @return array
	 */
	public static function upload_images($metadata)
	{
		if (!self::is_image_file($metadata['file']))
		{
			return $metadata;
		}

		if (!self::connect_remote_server())
		{
			return self::raise_connection_error();
		}

		//deal with fullsize image file
		if (!self::upload_file($metadata['file']))
		{
			return self::raise_upload_error();
		}

		if (isset($metadata['sizes']) && count($metadata['sizes']) > 0)
		{
			//there may be duplicated filenames,so ....
			$uniqe_images = array();
			foreach ($metadata['sizes'] as $image_size => $image_item)
			{
				$uniqe_images[] = $image_item['file'];
			}
			$uniqe_images = array_unique($uniqe_images);
			foreach ($uniqe_images as $image_filename)
			{
				$relative_filepath = dirname($metadata['file']) . '/' . $image_filename;
				if (!self::upload_file($relative_filepath))
				{
					return self::raise_upload_error();
				}
			}
		}
		return $metadata;
	}

	/**
	 * upload single file to remote  FTP  server, used by upload_images
	 * @param type $relative_path the path relative to upload basedir
	 * @return type 
	 */
	private static function upload_file($relative_path)
	{
		$local_filepath = self::$local_basepath . '/' . $relative_path;
		$local_basename = basename($local_filepath);
		$remotefile = self::$ftp_remote_path . self::$subdir . '/' . $local_basename;
		if (!file_exists($local_filepath))
		{
			return FALSE;
		}
		$file_data = file_get_contents($local_filepath);
		if (!self::$fs->put_contents($remotefile, $file_data, 0644))
		{
			return FALSE;
		}
		else
		{
			//更新占用空间
			self::update_filesize_used($local_filepath);
			@unlink($local_filepath);
			return TRUE;
		}
	}

	/**
	 * Get a filename that is sanitized and unique for the given directory.
	 * @uses self::$fs ,make sure the FTP connection is available when you use this method!
	 * @since 1.2.0
	 * @param string $dir the remote dir
	 * @param string $filename the base filename
	 * @param mixed $unique_filename_callback Callback.
	 * @return string New filename, if given wasn't unique.
	 */
	private static function unique_filename($dir, $filename)
	{
		// sanitize the file name before we begin processing
		$filename = sanitize_file_name($filename);

		// separate the filename into a name and extension
		$info = pathinfo($filename);
		$ext = !empty($info['extension']) ? '.' . $info['extension'] : '';
		$name = basename($filename, $ext);

		// edge case: if file is named '.ext', treat as an empty name
		if ($name === $ext)
			$name = '';

		// Increment the file number until we have a unique file to save in $dir. Use callback if supplied.
		$number = '';

		// change '.ext' to lower case
		if ($ext && strtolower($ext) != $ext)
		{
			$ext2 = strtolower($ext);
			$filename2 = preg_replace('|' . preg_quote($ext) . '$|', $ext2, $filename);

			// check for both lower and upper case extension or image sub-sizes may be overwritten
			while (self::$fs->is_file($dir . "/$filename") || self::$fs->is_file($dir . "/$filename2")) {
				$new_number = $number + 1;
				$filename = str_replace("$number$ext", "$new_number$ext", $filename);
				$filename2 = str_replace("$number$ext2", "$new_number$ext2", $filename2);
				$number = $new_number;
			}
			return $filename2;
		}

		while (self::$fs->is_file($dir . "/$filename")) {
			if ('' == "$number$ext")
				$filename = $filename . ++$number . $ext;
			else
				$filename = str_replace("$number$ext", ++$number . $ext, $filename);
		}

		return $filename;
	}

	/**
	 * 删除远程服务器上的单个文件
	 * @static
	 * @param $file
	 * @return void
	 */
	public static function delete_remote_file($file)
	{
		$file = str_replace(self::$local_basepath, self::$http_remote_path, $file);
		if (strpos($file, self::$http_remote_path) !== 0)
		{
			$file = self::$ftp_remote_path . '/' . $file;
		}

		self::connect_remote_server();
		self::decrease_filesize_used(self::$fs, $file);
		self::$fs->delete($file, false, 'f');
		return '';
	}

	/**
	 * @see wp-admin/includes/scree.php Class Screen
	 *  add_contextual_help is deprecated
	 * method to find current_screen:
	 * function check_current_screen() {
	  if( !is_admin() ) return;
	  global $current_screen;
	  var_dump( $current_screen );
	  }
	  add_action( 'admin_notices', 'check_current_screen' );
	 * @return void
	 */
	public function add_my_contextual_help()
	{
		//WP_Screen id:  'settings_page_hacklog-remote-attachment/loader' 
		$identifier = md5(HACKLOG_RA_LOADER);
		$current_screen_id = 'settings_page_' . $identifier;
		$text = '<p><h2>' . __('Explanation of some Options', self::textdomain) . '</h2></p>' .
				'<p>' . __('<strong>Remote base URL</strong> is the URL to your Ftp root path.', self::textdomain) . '</p>' .
				'<p>' . __('<strong>FTP Remote path</strong> is the relative path to your FTP main directory.Use "<strong>.</strong>" for FTP main(root) directory.You can use sub-directory Like <strong>wp-files</strong>', self::textdomain) . '</p>' .
				'<p>' . __('<strong>HTTP Remote path</strong> is the relative path to your HTTP main directory.Use "<strong>.</strong>" for HTTP main(root) directory.You can use sub-directory Like <strong>wp-files</strong>', self::textdomain) . '</p>' .
				'<p><strong>' . __('For more information:', self::textdomain) . '</strong> ' . __('Please visit the <a href="http://80x86.io/?p=5001" target="_blank">Plugin Home Page</a>', self::textdomain) . '</p>';
		$args = array(
			'title' => sprintf(__("%s Help", self::textdomain), self::plugin_name),
			'id' => $current_screen_id,
			'content' => $text,
			'callback' => false,
		);
		$current_screen = get_current_screen();
		$current_screen->add_help_tab($args);
	}

	/**
	 * add menu page
	 * @see http://codex.wordpress.org/Function_Reference/add_options_page
	 * @static
	 * @return void
	 */
	public static function plugin_menu()
	{
		$identifier = md5(HACKLOG_RA_LOADER);
		$option_page = add_options_page(__('Hacklog Remote Attachment Options', self::textdomain), __('Remote Attachment', self::textdomain), 'manage_options', $identifier, array(__CLASS__, 'plugin_options')
		);
//		 Adds my help tab when my admin page loads
		add_action('load-' . $option_page, array(__CLASS__, 'add_my_contextual_help'));
	}

	public static function show_message($message, $type = 'e')
	{
		if (empty($message))
			return;
		$font_color = 'e' == $type ? '#FF0000' : '#4e9a06';
		$html = '<!-- Last Action --><div class="updated fade"><p>';
		$html .= "<span style='color:{$font_color};'>" . $message . '</span>';
		$html .= '</p></div>';
		echo $html;
	}

	/**
	 * option page
	 * @static
	 * @return void
	 */
	public static function plugin_options()
	{
		$msg = '';
		$error = '';

		//update options
		if (isset($_POST['submit']))
		{
			if (self::update_options())
			{
				$msg = __('Options updated.', self::textdomain);
			}
			else
			{
				$error = __('Nothing changed.', self::textdomain);
			}
			$credentials = array(
				'hostname' => $_POST['ftp_server'],
				'port' => $_POST['ftp_port'],
				'username' => $_POST['ftp_user'],
				'password' => !empty($_POST['ftp_pwd']) ? $_POST['ftp_pwd'] : self::decrypt(self::$ftp_pwd),
				'ssl' => FALSE,
			);
			if (self::setup_ftp($credentials))
			{
				$msg .= __('Connected successfully.', self::textdomain);
			}
			else
			{
				$error .= __('Failed to connect to remote server!', self::textdomain);
			}
		}

		//tools
		if (isset($_GET['hacklog_do']))
		{
			global $wpdb;
			switch ($_GET['hacklog_do'])
			{
				case 'replace_old_post_attach_url':
					$orig_url = self::$local_baseurl;
					$new_url = self::$remote_baseurl;
					$sql = "UPDATE $wpdb->posts set post_content=replace(post_content,'$orig_url','$new_url')";
					break;
				case 'recovery_post_attach_url':
					$orig_url = self::$remote_baseurl;
					$new_url = self::$local_baseurl;
					$sql = "UPDATE $wpdb->posts set post_content=replace(post_content,'$orig_url','$new_url')";
					break;
			}
			if (($num_rows = $wpdb->query($sql)) > 0)
			{
				$msg = sprintf('%d ' . __('posts has been updated.', self::textdomain), $num_rows);
			}
			else
			{
				$error = __('no posts been updated.', self::textdomain);
			}
		}
		?>
		<div class="wrap">
			<?php screen_icon(); ?>
			<h2> <?php _e('Hacklog Remote Attachment Options', self::textdomain) ?></h2>
			<?php
			self::show_message($msg, 'm');
			self::show_message($error, 'e');
			?>
			<form name="form1" method="post"
				  action="<?php echo admin_url('options-general.php?page=' . md5(HACKLOG_RA_LOADER)); ?>">
				<table width="100%" cellpadding="5" class="form-table">
					<tr valign="top">
						<th scope="row"><label for="ftp_server"><?php _e('Ftp server', self::textdomain) ?>:</label></th>
						<td>
							<input name="ftp_server" type="text" class="regular-text" size="100" id="ftp_server"
								   value="<?php echo self::get_opt('ftp_server'); ?>"/>
							<span class="description"><?php _e('the IP or domain name of remote file server.', self::textdomain) ?></span>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><label for="ftp_port"><?php _e('Ftp server port', self::textdomain) ?>:</label></th>
						<td>
							<input name="ftp_port" type="text" class="small-text" size="60" id="ftp_port"
								   value="<?php echo self::get_opt('ftp_port'); ?>"/>
							<span class="description"><?php _e('the listenning port of remote FTP server.', self::textdomain) ?></span>
						</td>
					</tr>

					<tr valign="top">
						<th scope="row"><label for="ftp_user"><?php _e('Ftp username', self::textdomain) ?>:</label></th>
						<td>
							<input name="ftp_user" type="text" class="regular-text" size="60" id="ftp_user"
								   value="<?php echo self::get_opt('ftp_user'); ?>"/>
							<span class="description"><?php _e('the Ftp username.', self::textdomain) ?></span>
						</td>
					</tr>

					<tr valign="top">
						<th scope="row"><label for="ftp_pwd"><?php _e('Ftp password', self::textdomain) ?>:</label></th>
						<td>
							<input name="ftp_pwd" type="password" class="regular-text" size="60" id="ftp_pwd"
								   value=""/>
							<span class="description"><?php _e('the password.', self::textdomain) ?></span>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><label for="ftp_timeout"><?php _e('Ftp timeout', self::textdomain) ?>:</label></th>
						<td>
							<input name="ftp_timeout" type="text" class="small-text" size="30" id="ftp_timeout"
								   value="<?php echo self::get_opt('ftp_timeout'); ?>"/>
							<span class="description"><?php _e('FTP connection timeout.', self::textdomain); ?></span>
						</td>
					</tr>

					<tr valign="top">
						<th scope="row"><label for="remote_baseurl"><?php _e('Remote base URL', self::textdomain) ?>
								:</label></th>
						<td>
							<input name="remote_baseurl" type="text" class="regular-text" size="60" id="remote_baseurl"
								   value="<?php echo self::get_opt('remote_baseurl'); ?>"/>
							<span class="description"><?php _e('Remote base URL,the URL to your Ftp root path.for example: <strong>http://www.your-domain.com</strong>.', self::textdomain); ?></span>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><label for="ftp_remote_path"><?php _e('FTP Remote path', self::textdomain); ?>:</label></th>
						<td>
							<input name="ftp_remote_path" type="text" class="regular-text" size="60" id="ftp_remote_path"
								   value="<?php echo self::get_opt('ftp_remote_path'); ?>"/>
							<span class="description"><?php _e('the relative path to your FTP main directory.Use "<strong>.</strong>" for FTP main(root) directory.You can use sub-directory Like <strong>wp-files</strong>', self::textdomain); ?></span>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><label for="http_remote_path"><?php _e('HTTP Remote path', self::textdomain); ?>:</label></th>
						<td>
							<input name="http_remote_path" type="text" class="regular-text" size="60" id="http_remote_path"
								   value="<?php echo self::get_opt('http_remote_path'); ?>"/>
							<span class="description"><?php _e('the relative path to your HTTP main directory.Use "<strong>.</strong>" for HTTP main(root) directory.You can use sub-directory Like <strong>wp-files</strong>', self::textdomain); ?></span>
						</td>
					</tr>
				</table>
				<p class="submit">
					<input type="submit" class="button-primary" name="submit"
						   value="<?php _e('Save Options', self::textdomain); ?> &raquo;"/>
				</p>
			</form>
		</div>
		<div class="wrap">
			<hr/>
			<h2> <?php _e('Hacklog Remote Attachment Status', self::textdomain); ?></h2>

			<p style="color:#999999;font-size:14px;">
				<?php _e('Space used on remote server:', self::textdomain); ?><?php echo self::human_size(get_option(hacklogra::opt_space)); ?>
			</p>
			<hr/>
			<h2>Tools</h2>

			<p style="color:#f00;font-size:14px;"><strong><?php _e('warning:', self::textdomain); ?></strong>
				<?php _e("if you haven't moved all your attachments OR dont't know what below means,please <strong>DO NOT</strong> click the link below!", self::textdomain); ?>
			</p>

			<h3><?php _e('Move', self::textdomain); ?></h3>

			<p style="color:#4e9a06;font-size:14px;">
				<?php _e('if you have moved all your attachments to the remote server,then you can click', self::textdomain); ?>
				<a onclick="return confirm('<?php _e('Are your sure to do this?Make sure you have backuped your database tables.', self::textdomain); ?>');"
				   href="<?php echo admin_url('options-general.php?page=' . md5(HACKLOG_RA_LOADER)); ?>&hacklog_do=replace_old_post_attach_url"><strong><?php _e('here', self::textdomain); ?></strong></a><?php _e(' to update the database.', self::textdomain); ?>
			</p>

			<h3><?php _e('Recovery', self::textdomain); ?></h3>

			<p style="color:#4e9a06;font-size:14px;">
				<?php _e('if you have moved all your attachments from the remote server to local server,then you can click', self::textdomain); ?>
				<a onclick="return confirm('<?php _e('Are your sure to do this?Make sure you have backuped your database tables.', self::textdomain); ?>');"
				   href="<?php echo admin_url('options-general.php?page=' . md5(HACKLOG_RA_LOADER)); ?>&hacklog_do=recovery_post_attach_url"><strong><?php _e('here', self::textdomain); ?></strong></a><?php _e(' to update the database.', self::textdomain); ?>
			</p>
		</div>
		<?php
	}

}
