<?php
/**
 * Copyright (c) 2016 Ultra Community (http://www.ultracommunity.com)
 */

namespace InvisibleReCaptcha\MchLib\Utils;

final class MchWpUtils
{
	public static function getSiteNameById($siteId)
	{
		return get_blog_option($siteId, 'blogname', null);
	}

	public static function isUserLoggedIn()
	{
		return (bool)self::getCurrentUserId(); //is_user_logged_in();
	}

	public static function isAdminLoggedIn()
	{
		return self::isSuperAdminLoggedIn();
	}

	public static function isSuperAdminLoggedIn()
	{
		static $isLoggedIn = -1;
		if(-1 !== $isLoggedIn)
			return $isLoggedIn;
		return $isLoggedIn = is_super_admin();
	}

	public static function isUserInDashboard()
	{
		return  ( ( !defined( 'DOING_AJAX' ) || !DOING_AJAX ) && is_admin() );
	}

	public static function isAdminInDashboard()
	{
		return self::isAdminLoggedIn() && self::isUserInDashboard();
	}


	public static function isUserInNetworkDashboard()
	{
		return  is_network_admin();
	}

	public static function isAdminInNetworkDashboard()
	{
		return self::isAdminLoggedIn() && self::isUserInNetworkDashboard();
	}

	public static function isAjaxRequest()
	{
		return ( defined( 'DOING_AJAX' ) && DOING_AJAX && is_admin());
	}
	public static function isXmlRpcRequest()
	{
		return defined('XMLRPC_REQUEST') && XMLRPC_REQUEST;
	}

	public static function isMultiSite()
	{
		return is_multisite();
	}

	public static function logOutCurrentUser($urlRedirectTo = null)
	{
		wp_logout();
		wp_set_current_user(0);

		if(empty($urlRedirectTo)){
			return;
		}

		headers_sent() ?: nocache_headers();

		isset($urlRedirectTo[1]) ? self::redirectToUrl($urlRedirectTo) : null;
	}

	public static function logInUser($userId, $remember = false, $urlRedirectTo = null)
	{
		wp_set_current_user($userId);
		wp_set_auth_cookie($userId, $remember );

		if(empty($urlRedirectTo)){
			return;
		}

		isset($urlRedirectTo[1]) ? self::redirectToUrl($urlRedirectTo) : null;
	}

	public static function getAdminEmailAddress()
	{
		return get_bloginfo('admin_email');
	}

	public static function getAdminDisplayName()
	{
		if(! function_exists('get_user_by') )
			require_once(ABSPATH .'wp-includes/pluggable.php');

		$adminUser = get_user_by('email', get_bloginfo('admin_email')); //get_option( 'admin_email' );
		if(false === $adminUser)
			return null;

		return !empty($adminUser->display_name) ? $adminUser->display_name : null;
	}


	public static function getAdminFullName()
	{
		if(! function_exists('get_user_by') )
			require_once(ABSPATH .'wp-includes/pluggable.php');

		$adminUser = get_user_by('email', get_bloginfo('admin_email')); //get_option( 'admin_email' );
		if(false === $adminUser)
			return null;

		$adminFullName  = empty($adminUser->first_name) ? '' : $adminUser->first_name;
		$adminFullName .= empty($adminUser->last_name)  ? '' : ' ' . $adminUser->last_name;

		return trim($adminFullName);

	}


	public static function isPluginNetworkActivated($pluginFilePath)
	{
		if(!self::isMultiSite())
			return false;

		function_exists( 'is_plugin_active_for_network' ) || require_once( ABSPATH . '/wp-admin/includes/plugin.php' );

		return  !empty($pluginFilePath) ? is_plugin_active_for_network(plugin_basename($pluginFilePath)) : false;
	}

	public static function isPermaLinkActivated()
	{
		return (bool)(get_option('permalink_structure'));
	}


	public static function getAjaxUrl()
	{
		$ajaxUrl = admin_url('admin-ajax.php', self::isSslRequest() ? 'admin' : 'http');

		if(0 === strpos(self::getCurrentPageUrl(), 'https') && 0 !== strpos($ajaxUrl, 'https'))
			return  str_replace('http:', 'https:', $ajaxUrl);

		if(0 === strpos(self::getCurrentPageUrl(), 'http:') && 0 !== strpos($ajaxUrl, 'http:'))
			return str_replace('https:', 'http:', $ajaxUrl);

		return $ajaxUrl;
	}

	public static function isSslRequest()
	{
		static $isSsl = null;
		if(null !== $isSsl)
			return $isSsl;

		if (isset($_SERVER['HTTP_CF_VISITOR']) && false !== strpos($_SERVER['HTTP_CF_VISITOR'], 'https'))
			return $isSsl = true;

		if (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && stripos($_SERVER['HTTP_X_FORWARDED_PROTO'], 'https') === 0)
			return $isSsl = true;

//		if (isset($_SERVER['SERVER_PORT']) && ($_SERVER['SERVER_PORT'] == 443)) # wp is_ssl() function is looking for port 443 as well
//			return $isSsl = true;

		if(isset($_SERVER['HTTP_X_FORWARDED_SSL']) && $_SERVER['HTTP_X_FORWARDED_SSL'] === 'on')
			return $isSsl = true;

		if(stripos(get_option('siteurl'), 'https') === 0)
			return $isSsl = true;

		return $isSsl = is_ssl();
	}

	public static function getCurrentPageUrl()
	{
		static $pageUrl = null;

		if(null !== $pageUrl)
			return $pageUrl;

//		if(is_front_page())
//			return $pageUrl = home_url('/', self::isSslRequest());

		$pageUrl = self::isSslRequest() ? 'https://' : 'http://';

		if(isset($_SERVER['SERVER_PORT']) && ($_SERVER['SERVER_PORT'] != 80))
			$pageUrl .= $_SERVER['SERVER_NAME' ]. ':' . $_SERVER['SERVER_PORT'] . $_SERVER['REQUEST_URI'];
		else
			$pageUrl .= $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];

		return $pageUrl = esc_url($pageUrl);

	}

	public static function getCurrentBlogLink()
	{
		return '<a href = "'. esc_url(get_bloginfo('url')) .'">' . get_bloginfo('name') . '</a>';
	}

	public static function getCurrentBlogName()
	{
		return get_bloginfo('name');
	}

	public static function getAllBlogIds()
	{
		global $wpdb;

		if( empty($wpdb->blogs) )
			return array();

		return false === ( $arrBlogs = $wpdb->get_col(  "SELECT blog_id FROM $wpdb->blogs WHERE archived = '0' AND spam = '0' AND deleted = '0'" ) ) ? array() : $arrBlogs;

	}


	public static function getDirectoryPathForCache()
	{
		$arrPossibleDirectoryPath = array(
			//dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . '_temp',
			WP_CONTENT_DIR . DIRECTORY_SEPARATOR . 'cache',
			WP_CONTENT_DIR,
		);

		$arrUploadDirInfo = wp_upload_dir();
		if(MchWpUtils::isMultiSite()){
			switch_to_blog( 1 );
			$arrUploadDirInfo = wp_upload_dir();
			restore_current_blog();
		}

		(empty($arrUploadDirInfo['error']) && !empty($arrUploadDirInfo['basedir']))
		? $arrPossibleDirectoryPath[] = $arrUploadDirInfo['basedir'] : null;

		defined('WP_TEMP_DIR') ? $arrPossibleDirectoryPath[] = WP_TEMP_DIR : null;

		foreach($arrPossibleDirectoryPath as $directoryPath)
		{
			$tempDirPath = rtrim($directoryPath, '/\\');
			if(self::isDirectoryUsable($tempDirPath, false) )
				return $tempDirPath;
		}

		return null;
	}


	public static function getCurrentUserId()
	{
		return \get_current_user_id();

//		static $currentUserId = -1;
//		if(-1 !== $currentUserId )
//			return $currentUserId;
//
//		return $currentUserId = \get_current_user_id();
	}


	public static function getPageUrl($pageId)
	{
		return \get_page_link($pageId);
	}

	public static function sendAjaxSuccessMessage($message = null)
	{
		is_array($message) ? wp_send_json_success($message) : wp_send_json_success(null === $message ?: array('message' => $message));
	}

	public static function sendAjaxErrorMessage($message = null, $allowHtml = false)
	{
		wp_send_json_error(null === $message ?: array('message' => $allowHtml ? $message : self::stripAllHTMLTags($message)));
	}


	public static function addActionHook($actionName,  $callback, $priority = 10, $numberOfArgumentsToPass = 1)
	{
		\add_action($actionName, $callback, $priority,  $numberOfArgumentsToPass);
	}

	public static function doAction($actionName, $args = '') // avoid using it
	{
		$args = func_get_args();
		call_user_func_array('do_action', $args);
	}

	public static function applyFilters($filterName, $value)// avoid using it
	{
		$args = func_get_args();
		return call_user_func_array('apply_filters', $args);
	}

	public static function addFilterHook($filterName, $callback, $priority = 10, $numberOfArgumentsToPass = 1)
	{
		\add_filter($filterName, $callback, $priority,  $numberOfArgumentsToPass);
	}



	public static function isWPError($something)
	{
		return ( $something instanceof \WP_Error );
	}

	public static function isWPUser($something)
	{
		return ( $something instanceof \WP_User );
	}

	public static function stripAllHTMLTags($content, $removeLineBreaks = true)
	{
		return \wp_strip_all_tags($content, $removeLineBreaks);
	}

	public static function formatUrlPath($urlPathPart)
	{
		return \sanitize_title($urlPathPart);
	}

	public static function serializeData($data)
	{
		return is_serialized($data) ? $data : \maybe_serialize($data);
	}

	public static function unSerializeData($data)
	{
		return \maybe_unserialize($data);
	}

	public static function redirectToUrl($redirectUrl, $safe = false)
	{
		($safe) ? wp_safe_redirect(esc_url($redirectUrl)) : wp_redirect(esc_url($redirectUrl));
		exit;
	}

	public static function redirectTo404()
	{
		global $wp_query;

		if(!empty($wp_query)){
			$wp_query->set_404();
		}

		status_header( 404 );
		get_template_part( 404 );

		exit;
	}


	public static function stripSlashes($textOrArray)
	{
		//function_exists('stripslashes_deep') || require_once( ABSPATH . WPINC . '/formatting.php' );

		return stripslashes_deep($textOrArray);
	}

	public static function stripHtmlTags($str, $stripLineBreaks = false)
	{
		//function_exists('wp_strip_all_tags') || require_once( ABSPATH . WPINC . '/formatting.php' );

		return wp_strip_all_tags($str, $stripLineBreaks);
	}

	public static function sanitizeText($strText)
	{
		//function_exists('sanitize_text_field') || require_once( ABSPATH . WPINC . '/formatting.php' );

		return \sanitize_text_field($strText);
	}

	public static function sanitizeTextArea($strHtml, $arrAllowedTags = array())
	{
		//function_exists('wp_kses') || require_once( ABSPATH . WPINC . '/kses.php' );

		return \wp_kses( $strHtml, $arrAllowedTags );
	}


	public static function sanitizeEmail($strText)
	{
		//function_exists('sanitize_email') || require_once( ABSPATH . WPINC . '/formatting.php' );

		return \sanitize_email($strText);
	}

	public static function sanitizeUserName($strUserName, $strict = false)
	{
		//function_exists('sanitize_user') || require_once( ABSPATH . WPINC . '/formatting.php' );

		return \str_replace(' ', '', \sanitize_user($strUserName, $strict));
	}

	public static function sanitizeFileName($strFileName)
	{
		//function_exists('sanitize_file_name') || require_once( ABSPATH . WPINC . '/formatting.php' );

		return \sanitize_file_name($strFileName);
	}

	public static function sanitizeKey($strKeyValue)
	{
		//function_exists('sanitize_key') || require_once( ABSPATH . WPINC . '/formatting.php' );

		return \sanitize_key($strKeyValue);
	}


	public static function sendNoCacheHeaders()
	{
		\headers_sent() ?: \nocache_headers();
	}


	public static function isHookRegistered($hookName, $callBackFuntion = null)
	{
		return \has_filter($hookName, (null === $callBackFuntion) ? false : $callBackFuntion);
	}

	public static function installedVersionIs( $operator, $versionToCompareWith)
	{
		global $wp_version;
		list( $formattedWPVersion ) = \explode( '-', $wp_version );

		return \version_compare($formattedWPVersion, $versionToCompareWith, $operator );
	}

	private function __construct(){}

}