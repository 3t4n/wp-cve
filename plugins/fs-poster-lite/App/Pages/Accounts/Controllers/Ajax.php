<?php

namespace FSPoster\App\Pages\Accounts\Controllers;

use FSPoster\App\Providers\DB;
use FSPoster\App\Providers\Pages;
use FSPoster\App\Providers\Helper;
use FSPoster\App\Providers\Request;

trait Ajax
{
	public function get_accounts ()
	{
		$social_networks = [
			'fb',
			'linkedin',
			'vk',
			'reddit',
			'tumblr',
			'ok',
			'instagram',
			'pinterest',
			'google_b',
			'blogger',
			'wordpress',
			'telegram',
			'medium',
			'plurk'
		];
		$name            = Request::post( 'name', '', 'string' );
		$filter_by       = Request::post( 'filter_by', 'all', 'string', [
			'all',
			'active',
			'inactive',
			'visible',
			'hidden'
		] );

		if ( empty( $name ) || ! in_array( $name, $social_networks ) )
		{
			Helper::response( FALSE );
		}

		$data = Pages::action( 'Accounts', 'get_' . $name . '_accounts', $filter_by );

		if ( $name === 'telegram' )
		{
			$data[ 'button_text' ] = esc_html__( 'ADD A BOT', 'fs-poster' );
			$data[ 'err_text' ]    = esc_html__( 'bots', 'fs-poster' );
		}
		else
		{
			$data[ 'button_text' ] = esc_html__( 'ADD AN ACCOUNT', 'fs-poster' );
			$data[ 'err_text' ]    = esc_html__( 'accounts', 'fs-poster' );
		}

		Pages::modal( 'Accounts', $name . '/index', $data, [ 'button_text' => $data[ 'button_text' ] ] );
	}

	public function search_subreddits ()
	{
		$account_id = Request::post( 'account_id', '' );
		$search     = Request::post( 'search', '' );

		$result = DB::fetch( 'accounts', [ 'id' => $account_id ] );

		if ( mb_strlen( $search ) < 2 )
		{
			Helper::response( TRUE, [ 'subreddits' => [] ] );
		}

		if ( $result )
		{
			$data = Helper::api_cmd( 'search_subreddits', 'POST', Helper::getOption( 'access_token', '', TRUE ), [
				'profile_id' => $result[ 'profile_id' ],
				'search'     => $search
			] );
			Helper::response( TRUE, $data );
		}

	}

	public function reddit_get_subreddit_flairs ()
	{
		$accountId = Request::post( 'account_id', '0', 'num' );
		$subreddit = Request::post( 'subreddit', '', 'string' );

		$result = DB::fetch( 'accounts', [ 'id' => $accountId ] );

		$subreddit = basename( $subreddit );

		if ( $result )
		{
			$response = Helper::api_cmd( 'get_subreddit_flairs', 'POST', Helper::getOption( 'access_token', '', TRUE ), [
				'profileId' => $result[ 'profile_id' ],
				'subreddit' => $subreddit
			] );

			Helper::response( TRUE, $response );
		}
	}

	public function add_vk_account ()
	{
		$accessToken = Request::post( 'at', '', 'string' );
		$response    = Helper::api_cmd( 'add_vk_account', 'POST', Helper::getOption( 'access_token', '', TRUE ), [ 'vk_token' => $accessToken ] );

		if ( isset( $response[ 'data' ] ) )
		{
			Action::add_account( $response[ 'data' ][ 'account' ], $response[ 'data' ][ 'nodes' ] );

			Helper::response( TRUE );
		}

		Helper::response( FALSE, $response );
	}

	public function add_plurk_account ()
	{
		$requestToken       = Request::post( 'requestToken' );
		$requestTokenSecret = Request::post( 'requestTokenSecret' );
		$verifier           = Request::post( 'verifier' );

		$response = Helper::api_cmd( 'add_plurk_account', 'POST', Helper::getOption( 'access_token', '', TRUE ), [
			'token'    => $requestToken,
			'secret'   => $requestTokenSecret,
			'verifier' => $verifier
		] );

		if ( isset( $response[ 'data' ] ) )
		{
			Action::add_account( $response[ 'data' ][ 'account' ] );

			Helper::response( TRUE );
		}

		Helper::response( FALSE, $response );
	}

	public function add_telegram_bot ()
	{
		$token = Request::post( 'token' );

		$response = Helper::api_cmd( 'add_telegram_bot', 'POST', Helper::getOption( 'access_token', '', TRUE ), [ 'token' => $token ] );

		if ( isset( $response[ 'data' ] ) )
		{
			Action::add_account( $response[ 'data' ][ 'account' ] );

			Helper::response( TRUE );
		}

		Helper::response( FALSE, $response );
	}

	public function telegram_chat_save ()
	{
		$account_id = Request::post( 'account_id', '', 'int' );
		$chat_id    = Request::post( 'chat_id', '', 'string' );

		if ( empty( $account_id ) || empty( $chat_id ) )
		{
			Helper::response( FALSE );
		}

		$account_info = DB::fetch( 'accounts', [ 'id' => $account_id ] );

		if ( ! $account_info )
		{
			Helper::response( FALSE );
		}

		$response = Helper::api_cmd( 'save_telegram_chat', 'POST', Helper::getOption( 'access_token', '', TRUE ), [
			'fs_account_id' => $account_info[ 'fs_account_id' ],
			'chat_id'       => $chat_id
		] );

		if ( isset( $response[ 'status' ] ) && $response[ 'status' ] === 'error' )
		{
			Helper::response( FALSE, $response );
		}

		$response[ 'chat' ][ 'account_id' ] = $account_id;

		$chat_exists = DB::fetch( 'account_nodes', [ 'fs_account_id' => $response[ 'chat' ][ 'fs_account_id' ] ] );

		if ( $chat_exists )
		{
			DB::DB()->update( DB::table( 'account_nodes' ), $response[ 'chat' ], [ 'fs_account_id' => $response[ 'chat' ][ 'fs_account_id' ] ] );
		}
		else
		{
			DB::DB()->insert( DB::table( 'account_nodes' ), $response[ 'chat' ] );
		}

		Helper::response( TRUE, [
			'id'        => DB::DB()->insert_id,
			'chat_pic'  => Pages::asset( 'Base', 'img/telegram.svg' ),
			'chat_name' => htmlspecialchars( $response[ 'chat' ][ 'name' ] ),
			'chat_link' => Helper::profileLink( [
				'driver'   => 'telegram',
				'username' => $response[ 'chat' ][ 'username' ]
			] )
		] );
	}

	public function telegram_last_active_chats ()
	{
		$account_id = Request::post( 'account', '', 'int' );

		if ( ! ( is_numeric( $account_id ) && $account_id > 0 ) )
		{
			Helper::response( FALSE );
		}

		$account_info = DB::fetch( 'accounts', [ 'id' => $account_id ] );
		if ( ! $account_info )
		{
			Helper::response( FALSE );
		}

		$data = Helper::api_cmd( 'get_recent_telegram_chats', 'POST', Helper::getOption( 'access_token', '', TRUE ), [ 'fs_account_id' => $account_info[ 'fs_account_id' ] ] );

		if ( empty( $data[ 'chats' ] ) )
		{
			Helper::response( FALSE, esc_html__( 'No active chat(s) found.', 'fs-poster' ) );
		}

		Helper::response( TRUE, [ 'list' => $data[ 'chats' ] ] );
	}

	public function save_subreddit ()
	{
		$accountId = Request::post( 'account_id', '0', 'num' );
		$subreddit = Request::post( 'subreddit', '', 'string' );
		$flairId   = Request::post( 'flair', '', 'string' );
		$flairName = Request::post( 'flair_name', '', 'string' );

		if ( empty( $flairId ) )
		{
			$flairId   = '';
			$flairName = 'no flair';
		}

		$result = DB::fetch( 'accounts', [ 'id' => $accountId ] );

		$subredditExists = DB::fetch( 'account_nodes', [
			'account_id'   => $accountId,
			'screen_name'  => $subreddit,
			'access_token' => $flairId
		] );

		if ( $result && ! $subredditExists )
		{
			$response = Helper::api_cmd( 'save_subreddit', 'POST', Helper::getOption( 'access_token', '', TRUE ), [
				'profile_id'  => $result[ 'profile_id' ],
				'fsAccountId' => $result[ 'fs_account_id' ],
				'subreddit'   => $subreddit,
				'flairId'     => $flairId,
				'flairName'   => $flairName
			] );

			if ( isset( $response[ 'status' ] ) && $response[ 'status' ] === 'ok' )
			{
				$nodeSQL                 = $response[ 'node' ];
				$nodeSQL[ 'account_id' ] = $accountId;

				if ( DB::fetch( 'account_nodes', [ 'fs_account_id' => $nodeSQL[ 'fs_account_id' ] ] ) )
				{
					DB::DB()->update( DB::table( 'account_nodes' ), $nodeSQL, [ 'fs_account_id' => $nodeSQL[ 'fs_account_id' ] ] );
				}
				else
				{
					DB::DB()->insert( DB::table( 'account_nodes' ), $nodeSQL );
				}

				Helper::response( TRUE );
			}
			else
			{
				Helper::response( FALSE );
			}
		}

		Helper::response( TRUE );
	}

	public function account_activity_change ()
	{
		$id = Request::post( 'id', '0', 'num' );

		if ( ! ( $id > 0 ) )
		{
			Helper::response( FALSE );
		}

		$res = Action::activate_deactivate_account( $id );

		if ( $res[ 'status' ] === FALSE )
		{
			Helper::response( FALSE, $res );
		}

		Helper::response( TRUE );
	}

	public function settings_node_activity_change ()
	{
		$id = Request::post( 'id', '0', 'num' );

		if ( ! ( $id > 0 ) )
		{
			Helper::response( FALSE );
		}

		$res = Action::activate_deactivate_node( $id );

		if ( $res[ 'status' ] === FALSE )
		{
			Helper::response( FALSE, $res );
		}

		Helper::response( TRUE );
	}

	public function delete_account ()
	{
		$id = Request::post( 'id', 0, 'num' );

		if ( ! ( $id > 0 ) )
		{
			exit();
		}

		$res = Action::delete_account( $id );

		if ( $res[ 'status' ] === FALSE )
		{
			Helper::response( FALSE, $res[ 'error_msg' ] );
		}

		Helper::response( TRUE );
	}

	public function settings_node_delete ()
	{
		$id = Request::post( 'id', 0, 'num' );

		if ( ! $id > 0 )
		{
			Helper::response( FALSE );
		}

		$res = Action::delete_node( $id );

		if ( $res[ 'status' ] === FALSE )
		{
			Helper::response( FALSE, $res[ 'error_msg' ] );
		}

		Helper::response( TRUE );
	}

	public function refetch_account ()
	{
		$account_id = Request::post( 'account_id', 0, 'int' );

		if ( ! ( $account_id > 0 ) )
		{
			Helper::response( FALSE, esc_html__( 'Account not found!', 'fs-poster' ) );
		}

		$get_account = DB::fetch( 'accounts', [ 'id' => $account_id ] );

		if ( ! $get_account )
		{
			Helper::response( FALSE, esc_html__( 'Account not found!', 'fs-poster' ) );
		}

		$response = Helper::api_cmd( 'refetch_account', 'POST', Helper::getOption( 'access_token', '', TRUE ), [ 'id' => $get_account[ 'fs_account_id' ] ] );

		if ( $response[ 'status' ] === 'ok' && ! empty( $response[ 'data' ] ) )
		{
			Action::add_node( $account_id, $response[ 'data' ][ 'nodes' ] );

			Helper::response( TRUE );
		}
		else
		{
			Helper::response( FALSE, $response );
		}
	}

	public function hide_unhide_account ()
	{
		$id      = Request::post( 'id', '0', 'num' );
		$checked = Request::post( 'hidden', 0, 'num', [ '0', '1' ] );

		if ( ! ( $id > 0 && $checked >= 0 ) )
		{
			Helper::response( FALSE );
		}

		$res = Action::hide_unhide_account( $id, $checked );

		if ( $res[ 'status' ] === FALSE )
		{
			Helper::response( FALSE, $res[ 'error_msg' ] );
		}

		Helper::response( TRUE );
	}

	public function hide_unhide_node ()
	{
		$id      = Request::post( 'id', '0', 'num' );
		$checked = Request::post( 'hidden', 0, 'num', [ '0', '1' ] );

		if ( ! ( $id > 0 && $checked >= 0 ) )
		{
			Helper::response( FALSE );
		}

		$res = Action::hide_unhide_node( $id, $checked );

		if ( $res[ 'status' ] === FALSE )
		{
			Helper::response( FALSE, $res[ 'error_msg' ] );
		}

		Helper::response( TRUE );
	}
}
