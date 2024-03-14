<?php

namespace FSPoster\App\Providers;

use Exception;

class Helper
{
	use URLHelper;

	public static function response ( $status, $arr = [] )
	{
		$arr = is_array( $arr ) ? $arr : ( is_string( $arr ) ? [ 'error_msg' => $arr ] : [] );

		if ( $status )
		{
			$arr[ 'status' ] = 'ok';
		}
		else
		{
			$arr[ 'status' ] = 'error';
			if ( ! isset( $arr[ 'error_msg' ] ) )
			{
				$arr[ 'error_msg' ] = 'Error!';
			}
		}

		echo json_encode( $arr );
		exit();
	}

	public static function cutText ( $text, $n = 35 )
	{
		return mb_strlen( $text, 'UTF-8' ) > $n ? mb_substr( $text, 0, $n, 'UTF-8' ) . '...' : $text;
	}

	public static function getVersion ()
	{
		$plugin_data = get_file_data( FSPL_ROOT_DIR . '/init.php', [ 'Version' => 'Version' ], FALSE );

		return isset( $plugin_data[ 'Version' ] ) ? $plugin_data[ 'Version' ] : '1.0.0';
	}

	public static function getInstalledVersion ()
	{
		$ver = Helper::getOption( 'poster_plugin_installed', '0', TRUE );

		return empty( $ver ) ? '0.0.0' : $ver;
	}

	public static function debug ()
	{
		error_reporting( E_ALL );

		ini_set( 'display_errors', 'on' );
	}

	public static function disableDebug ()
	{
		error_reporting( 0 );

		ini_set( 'display_errors', 'off' );
	}

	public static function get_found_from_options ()
	{
		$get_options = self::api_cmd( 'get_found_from_options', 'GET' );

		if ( $get_options[ 'status' ] === 'error' )
		{
			$get_options[ 'options' ] = [];
		}

		$options = '<option selected disabled>' . esc_html__( 'Please, select how did You find us?', 'fs-poster' ) . '</option>';

		foreach ( $get_options[ 'options' ] as $key => $value )
		{
			$options .= '<option value="' . htmlspecialchars( $key ) . '">' . htmlspecialchars( $value ) . '</option>';
		}

		return $options;
	}

	private static $_options_cache = [];

	public static function getOption ( $optionName, $default = NULL, $network_option = FALSE )
	{
		if ( ! isset( self::$_options_cache[ $optionName ] ) )
		{
			$network_option = ! is_multisite() && $network_option == TRUE ? FALSE : $network_option;
			$fnName         = $network_option ? 'get_site_option' : 'get_option';

			self::$_options_cache[ $optionName ] = $fnName( 'fs_' . $optionName, $default );
		}

		return self::$_options_cache[ $optionName ];
	}

	public static function setOption ( $optionName, $optionValue, $network_option = FALSE, $autoLoad = NULL )
	{
		$network_option = ! is_multisite() && $network_option == TRUE ? FALSE : $network_option;
		$fnName         = $network_option ? 'update_site_option' : 'update_option';

		self::$_options_cache[ $optionName ] = $optionValue;

		$arguments = [ 'fs_' . $optionName, $optionValue ];

		if ( ! is_null( $autoLoad ) && ! $network_option )
		{
			$arguments[] = $autoLoad;
		}

		return call_user_func_array( $fnName, $arguments );
	}

	public static function deleteOption ( $optionName, $network_option = FALSE )
	{
		$network_option = ! is_multisite() && $network_option == TRUE ? FALSE : $network_option;
		$fnName         = $network_option ? 'delete_site_option' : 'delete_option';

		if ( isset( self::$_options_cache[ $optionName ] ) )
		{
			unset( self::$_options_cache[ $optionName ] );
		}

		return $fnName( 'fs_' . $optionName );
	}

	public static function removePlugin ()
	{
		Helper::api_cmd( 'remove_plugin', 'POST', Helper::getOption( 'access_token', '', TRUE ) );

		$fsTables = [
			'account_node_status',
			'account_nodes',
			'account_status',
			'grouped_accounts',
			'accounts',
			'feeds',
		];

		foreach ( $fsTables as $tableName )
		{
			DB::DB()->query( "DROP TABLE IF EXISTS `" . DB::table( $tableName ) . "`" );
		}

		DB::DB()->query( 'DELETE FROM `' . DB::DB()->base_prefix . 'options` WHERE `option_name` LIKE \'fs_%\' OR `option_name` LIKE \'%fs_poster%\'' );
		DB::DB()->query( 'DELETE FROM `' . DB::DB()->base_prefix . 'sitemeta` WHERE `meta_key` LIKE \'fs_%\' OR `meta_key` LIKE \'%fs_poster%\'' );
		DB::DB()->query( "DELETE FROM " . DB::WPtable( 'posts', TRUE ) . " WHERE post_type='fs_post_tmp' OR post_type='fs_post'" );
	}

	public static function socialIcon ( $driver )
	{
		switch ( $driver )
		{
			case 'fb':
				return "fab fa-facebook-f";
			case 'plurk':
				return "fas fa-parking";
			case 'wordpress':
			case 'medium':
			case 'reddit':
			case 'telegram':
			case 'pinterest':
			case 'linkedin':
			case 'vk':
			case 'instagram':
			case 'tumblr':
				return "fab fa-{$driver}";

			case 'ok':
				return "fab fa-odnoklassniki";
			case 'google_b':
				return "fab fa-google";
		}
	}

	public static function profilePic ( $info, $w = 40, $h = 40 )
	{
		if ( ! isset( $info[ 'driver' ] ) )
		{
			return '';
		}

		if ( empty( $info ) )
		{
			return Pages::asset( 'Base', 'img/no-photo.png' );
		}

		if ( is_array( $info ) && key_exists( 'cover', $info ) ) // nodes
		{
			if ( ! empty( $info[ 'cover' ] ) )
			{
				return $info[ 'cover' ];
			}
			else
			{
				if ( $info[ 'driver' ] === 'fb' )
				{
					return "https://graph.facebook.com/" . esc_html( $info[ 'node_id' ] ) . "/picture?redirect=1&height={$h}&width={$w}&type=normal";
				}
				else if ( $info[ 'driver' ] === 'telegram' )
				{
					return Pages::asset( 'Base', 'img/telegram.svg' );
				}
				else if ( $info[ 'driver' ] === 'tumblr' )
				{
					return "https://api.tumblr.com/v2/blog/" . esc_html( $info[ 'node_id' ] ) . "/avatar/" . ( $w > $h ? $w : $h );
				}
				else if ( $info[ 'driver' ] === 'reddit' )
				{
					return "https://www.redditstatic.com/avatars/avatar_default_10_25B79F.png";
				}
				else if ( $info[ 'driver' ] === 'linkedin' )
				{
					return Pages::asset( 'Base', 'img/no-photo.png' );
				}
			}
		}
		else
		{
			if ( $info[ 'driver' ] === 'fb' )
			{
				return "https://graph.facebook.com/" . esc_html( $info[ 'profile_id' ] ) . "/picture?redirect=1&height={$h}&width={$w}&type=normal";
			}
			else if ( $info[ 'driver' ] === 'telegram' )
			{
				return Pages::asset( 'Base', 'img/telegram.svg' );
			}
			else if ( $info[ 'driver' ] === 'linkedin' )
			{
				return $info[ 'profile_pic' ];
			}
			else if ( $info[ 'driver' ] === 'vk' )
			{
				return $info[ 'profile_pic' ];
			}
			else if ( $info[ 'driver' ] === 'reddit' )
			{
				return $info[ 'profile_pic' ];
			}
			else if ( $info[ 'driver' ] === 'tumblr' )
			{
				return "https://api.tumblr.com/v2/blog/" . esc_html( $info[ 'username' ] ) . "/avatar/" . ( $w > $h ? $w : $h );
			}
			else if ( $info[ 'driver' ] === 'ok' )
			{
				return $info[ 'profile_pic' ];
			}
			else if ( $info[ 'driver' ] === 'plurk' )
			{
				return $info[ 'profile_pic' ];
			}
		}
	}

	public static function profileLink ( $info )
	{
		if ( ! isset( $info[ 'driver' ] ) )
		{
			return '';
		}

		// IF NODE
		if ( is_array( $info ) && key_exists( 'cover', $info ) ) // nodes
		{
			if ( $info[ 'driver' ] === 'fb' )
			{
				return "https://fb.com/" . esc_html( $info[ 'node_id' ] );
			}
			else if ( $info[ 'driver' ] === 'vk' )
			{
				return "https://vk.com/" . esc_html( $info[ 'screen_name' ] );
			}
			else if ( $info[ 'driver' ] === 'tumblr' )
			{
				return "https://" . esc_html( $info[ 'screen_name' ] ) . ".tumblr.com";
			}
			else if ( $info[ 'driver' ] === 'linkedin' )
			{
				return "https://www.linkedin.com/company/" . esc_html( $info[ 'node_id' ] );
			}
			else if ( $info[ 'driver' ] === 'ok' )
			{
				return "https://ok.ru/group/" . esc_html( $info[ 'node_id' ] );
			}
			else if ( $info[ 'driver' ] === 'reddit' )
			{
				return "https://www.reddit.com/r/" . esc_html( $info[ 'screen_name' ] );
			}
			else if ( $info[ 'driver' ] === 'telegram' )
			{
				return "https://t.me/" . esc_html( $info[ 'screen_name' ] );
			}

			return '';
		}

		if ( $info[ 'driver' ] === 'fb' )
		{
			if ( empty( $info[ 'options' ] ) )
			{
				$info[ 'profile_id' ] = 'me';
			}

			return "https://fb.com/" . esc_html( $info[ 'profile_id' ] );
		}
		else if ( $info[ 'driver' ] === 'linkedin' )
		{
			return "https://www.linkedin.com/in/";
		}
		else if ( $info[ 'driver' ] === 'vk' )
		{
			return "https://vk.com/id" . esc_html( $info[ 'profile_id' ] );
		}
		else if ( $info[ 'driver' ] === 'reddit' )
		{
			return "https://www.reddit.com/u/" . esc_html( $info[ 'username' ] );
		}
		else if ( $info[ 'driver' ] === 'tumblr' )
		{
			return "https://" . esc_html( $info[ 'username' ] ) . ".tumblr.com";
		}
		else if ( $info[ 'driver' ] === 'ok' )
		{
			return 'https://ok.ru/profile/' . urlencode( $info[ 'profile_id' ] );
		}
		else if ( $info[ 'driver' ] === 'plurk' )
		{
			return "https://plurk.com/" . esc_html( $info[ 'username' ] );
		}
		else if ( $info[ 'driver' ] === 'telegram' )
		{
			return "https://t.me/" . esc_html( $info[ 'username' ] );
		}
	}

	public static function isHiddenUser ()
	{
		$hideFSPosterForRoles = explode( '|', Helper::getOption( 'hide_menu_for', '', TRUE ) );

		$userInf   = wp_get_current_user();
		$userRoles = (array) $userInf->roles;

		if ( ! in_array( 'administrator', $userRoles ) )
		{
			foreach ( $userRoles as $roleId )
			{
				if ( in_array( $roleId, $hideFSPosterForRoles ) )
				{
					return TRUE;
				}
			}
		}

		return FALSE;
	}

	public static function api_cmd ( $cmd, $method, $access_token = '', $data = [] )
	{
		$method  = $method === 'POST' ? 'POST' : 'GET';
		$url     = FSPL_API_URL . $cmd;
		$headers = [
			'Connection'    => 'Keep-Alive',
			'Accept'        => 'application/json',
			'Authorization' => 'Bearer ' . $access_token
		];

		$response = Curl::getContents( $url, $method, $data, $headers );

		try
		{
			$data = json_decode( $response, TRUE );
		}
		catch ( Exception $e )
		{
			$data = [];
		}

		if ( ! is_array( $data ) || empty( $data ) )
		{
			$data = [
				'status'    => 'error',
				'error_msg' => esc_html__( 'Something wrong has happened while connecting to the API server!', 'fs-poster' )
			];
		}

		if ( $data[ 'status' ] === 'error' && empty( $data[ 'error_msg' ] ) )
		{
			$data[ 'error_msg' ] = esc_html__( 'Oops. Something went wrong. Please try again later.', 'fs-poster' );
		}

		return $data;
	}
}
